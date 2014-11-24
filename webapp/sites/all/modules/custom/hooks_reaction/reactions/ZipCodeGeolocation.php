<?php

function getZipCodeFromLatLong($lat, $lng) {
    
    ensureMySqlDistanceFunctionsExist(); // This function is defined below
    
    $lat = floatval($lat);
    $lng = floatval($lng);
    
    $sub1lat = $lat - 1.0;
    $plus1lat = $lat + 1.0;
    $sub1lng = $lng - 1.0;
    $plus1lng = $lng + 1.0;
    
    $query = "
        SELECT id, zccity, zcstate, latitude, longitude, zipcode 
        FROM zipcodes 
        WHERE 
            latitude BETWEEN {$sub1lat} AND {$plus1lat} 
            AND longitude BETWEEN {$sub1lng} AND {$plus1lng} 
        ORDER BY distLatLong(latitude, longitude, {$lat}, {$lng})
        LIMIT 1
    ";
    
    $result = db_query($query);
    foreach ($result as $record) {
        return $record->zipcode;
    }
    
    return false;
}

/**
 * array getLatLongFromZipCode(int).
 *
 * Retrieves information about a given zip-code from the Drupal database. If the given ZC is
 * not found in the database _getLatLongFromZipCode_lookupAndCache() is called and its 
 * responce is returned.
 *
 * Example return:
 *     return array(
            "lat" => "38.931479",
            "lng" => "-77.40085",
            "state" => "VA",
            "city" => "Herndon"
        );
 */
function getLatLongFromZipCode($zip) {
    
    if ( intval($zip) === 0 ) {
        return array(false, false);
    }
    
    $zip = substr('00000' . intval($zip), -5);
    
    if ( $zip === '22070' ) { // Bug killer for REI HQ
        $zip = '20171';
    }
    
    /*
     * resource connectMySQL()
     * Connects to the MySQL database that this Drupal instance is/will-be using
     * This is meant to create a connection to the database that bypasses Drupal's db_query() and query alter hooks
     * This is also meant to be used in situations where a connection to the database is needed when Drupal is not fully boot-strapped
     * NOTE: If the database connection information is not found in global $databases, then this script will search for settings.php and load it
     */
    if ( function_exists('connectMySQL') == false ) {
        function connectMySQL() {
        
            // If the global $databases is not set, then settings.php is not loaded...
            if ( !isset($GLOBALS["databases"]) && !isset($databases) ) {
                
                while ( !file_exists('sites/default/settings.php') ) {
                    chdir('../');
                }
                include('sites/default/settings.php');
            }
        
            if ( !isset($databases) && isset($GLOBALS["databases"]) ) {
                $databases = $GLOBALS["databases"];
            }
            
            $dbAuth = $databases["default"]["default"];
            $host = $dbAuth["host"];
            $user = $dbAuth["username"];
            $pass = $dbAuth["password"];
            $port = $dbAuth["port"];
            $db = $dbAuth["database"];
            if ( !empty($port) ) {
                $host .= ":" . $port;
            }
            $link = mysql_connect($host, $user, $pass);
            mysql_select_db($db, $link);
            return $link;
        }
    }
    $mySqlLink = connectMySQL();
    
    $query = "
        SELECT id, zccity, zcstate, latitude, longitude, zipcode 
        FROM zipcodes 
        WHERE zipcode = {$zip} 
        LIMIT 1 
    ";
    $result = mysql_query($query, $mySqlLink);
    while ($row = mysql_fetch_assoc($result)) {
        return array(
            "lat" => $row['latitude'],
            "lng" => $row['longitude'],
            "state" => $row['zcstate'],
            "city" => $row['zccity']
        );
    }
    
    // If we have not returned by this line, then this ZC is not in the database... look it up.
    return _getLatLongFromZipCode_lookupAndCache($zip);
}

/**
 * array _getLatLongFromZipCode_lookupAndCache(int).
 *
 * Tries to retrieve information about a given zip-code from Google's geoLocation service.
 * If the ZC is found, it is saved to the database as a node of the zip_codes content-type so 
 * getLatLongFromZipCode() can pull this information later.
 *
 * If the given ZC could not be looked up, then array(false, false) is returned.
 *
 * Example return (when the ZC can be looked up):
 *     return array(
            "lat" => "38.931479",
            "lng" => "-77.40085",
            "state" => "VA",
            "city" => "Herndon"
        );
 */
function _getLatLongFromZipCode_lookupAndCache($zip) {

    $zip = substr('00000' . intval($zip), -5);
    
    // Try to pull this information from Google's GeoLocation service
    $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$zip&sensor=false";
    $json = file_get_contents($url);
    $zipInfo = json_decode($json, true);
    
    // Verify we got a valid responce
    if (
        empty($zipInfo)
        || empty($zipInfo['status'])
        || strtoupper($zipInfo['status']) !== 'OK'
        || empty($zipInfo['results'])
        || empty($zipInfo['results'][0])
        || empty($zipInfo['results'][0]['address_components'])
        || empty($zipInfo['results'][0]['geometry'])
        || empty($zipInfo['results'][0]['geometry']['location'])
        || empty($zipInfo['results'][0]['geometry']['location']['lat'])
        || empty($zipInfo['results'][0]['geometry']['location']['lng'])
    ) {
        // Something went wrong
        error_log("warning", "GeoLocation of zip-code $zip via maps.google.com failed in _getLatLongFromZipCode_lookupAndCache($zip)");
        return array(false, false);
    }
    
    // Get the latitude and longitude information 
    $lat = $zipInfo['results'][0]['geometry']['location']['lat'];
    $lng = $zipInfo['results'][0]['geometry']['location']['lng'];
    
    // It appears that the city and state information may appear in any array within $zipInfo['results'][0]['address_components'] where types=array('locality')
    $city = 'Unknown';
    $state = 'Unknown';
    foreach ( $zipInfo['results'][0]['address_components'] as $addressComponent ) {
    
        // Check if this addressComponent is the City information
        if ( 
            is_array($addressComponent) 
            && !empty($addressComponent['types']) 
            && in_array('locality', $addressComponent['types'])
        ) {
            $city = $addressComponent['long_name'];
        }
        
        // Check if this addressComponent is the State information
        if ( 
            is_array($addressComponent) 
            && !empty($addressComponent['types']) 
            && in_array('administrative_area_level_1', $addressComponent['types'])
        ) {
            $state = $addressComponent['short_name'];
        }
    }

    // Save the retrieved information to the database in the zipcodes table
    if ( function_exists('db_insert') ) {
        db_insert('zipcodes')->fields(
            array(
                'zccity' => $city,
                'zcstate' => $state,
                'latitude' => $lat,
                'longitude' => $lng,
                'zipcode' => $zip
            )
        )->execute();
    }
        
    // Return the retrieved information
    return array(
        "lat" => $lat,
        "lng" => $lng,
        "state" => $state,
        "city" => $city
    );
}

/**
 * array getLatLongFromAddress(int).
 *
 * Retrieves information about a given adress from the GoogleMaps API unless the given data is
 * found in function-result-cache.
 *
 * Example return:
 *     return array(
            "lat" => "39.2866799",
            "lng" => "-76.619677",
            "state" => "MD",
            "city" => "Baltimore",
            "zip" => "21201",
            "street" => "300 W Pratt St",
            "streetName" => "W Pratt St",
            "streetNumber " => "300",
        );
 */
function getLatLongFromAddress($address) {
    $secondsIn1Day = 86400;
    $daysIn1Year = 365;
    $secondsIn1Year = $secondsIn1Day * $daysIn1Year;
    
    // Call _getLatLongFromAddress() if there is no function-result-cache
    return call_user_func_cache($secondsIn1Year, '_getLatLongFromAddress', $address); // The function call_user_func_cache() is defined in FunctionResultCachingSupport.php
}
function _getLatLongFromAddress($address) {
    
    // Try to pull this information from Google's GeoLocation service
    $encodedAddress = urlencode($address);
    $url = "http://maps.googleapis.com/maps/api/geocode/json?address={$encodedAddress}&sensor=false";
    $json = file_get_contents($url);
    $addressInfo = json_decode($json, true);
    
    // Verify we got a valid responce
    if (
        empty($addressInfo)
        || empty($addressInfo['status'])
        || strtoupper($addressInfo['status']) !== 'OK'
        || empty($addressInfo['results'])
        || empty($addressInfo['results'][0])
        || empty($addressInfo['results'][0]['address_components'])
        || empty($addressInfo['results'][0]['geometry'])
        || empty($addressInfo['results'][0]['geometry']['location'])
        || empty($addressInfo['results'][0]['geometry']['location']['lat'])
        || empty($addressInfo['results'][0]['geometry']['location']['lng'])
    ) {
        // Something went wrong
        error_log("warning", "GeoLocation of zip-code $address via maps.google.com failed in _getLatLongFromZipCode_lookupAndCache($address)");
        return array(false, false);
    }
    
    // Get the latitude and longitude information 
    $lat = $addressInfo['results'][0]['geometry']['location']['lat'];
    $lng = $addressInfo['results'][0]['geometry']['location']['lng'];
    
    // It appears that the city and state information may appear in any array within $addressInfo['results'][0]['address_components'] where types=array('locality')
    $city = 'Unknown';
    $state = 'Unknown';
    $streetNumber = 'Unknown';
    $streetName = 'Unknown';
    $zipCode = 'Unknown';
    foreach ( $addressInfo['results'][0]['address_components'] as $addressComponent ) {
    
        // Check if this addressComponent is the City information
        if ( 
            is_array($addressComponent) 
            && !empty($addressComponent['types']) 
            && in_array('locality', $addressComponent['types'])
        ) {
            $city = $addressComponent['long_name'];
        }
        
        // Check if this addressComponent is the State information
        if ( 
            is_array($addressComponent) 
            && !empty($addressComponent['types']) 
            && in_array('administrative_area_level_1', $addressComponent['types'])
        ) {
            $state = $addressComponent['short_name'];
        }
        
        // Check if this addressComponent is the Street-Number information
        if ( 
            is_array($addressComponent) 
            && !empty($addressComponent['types']) 
            && in_array('street_number', $addressComponent['types'])
        ) {
            $streetNumber = $addressComponent['long_name'];
        }
        
        // Check if this addressComponent is the Street-Name information
        if ( 
            is_array($addressComponent) 
            && !empty($addressComponent['types']) 
            && in_array('route', $addressComponent['types'])
        ) {
            $streetName = $addressComponent['short_name'];
        }
        
        // Check if this addressComponent is the ZipCode information
        if ( 
            is_array($addressComponent) 
            && !empty($addressComponent['types']) 
            && in_array('postal_code', $addressComponent['types'])
        ) {
            $zipCode = $addressComponent['short_name'];
        }
    }
    
    // Return the retrieved information
    return array(
        "lat" => $lat,
        "lng" => $lng,
        "state" => $state,
        "city" => $city,
        "zip" => $zipCode,
        "street" => $streetNumber . ' ' . $streetName,
        "streetName" => $streetName,
        "streetNumber" => $streetNumber,
    );
    
}

function ensureMySqlDistanceFunctionsExist() {

    global $ensureMySqlDistanceFunctionsExist_done;
    
    if ( isset($ensureMySqlDistanceFunctionsExist_done) && $ensureMySqlDistanceFunctionsExist_done === true ) {
        // This has already been done
        return;
    }

    $sql = array(
        '
            CREATE FUNCTION distLatLong (lat1 DOUBLE, lon1 DOUBLE, lat2 DOUBLE, lon2 DOUBLE)
                RETURNS DOUBLE
                BEGIN
                
                    DECLARE R DOUBLE;
                    DECLARE dLat DOUBLE;
                    DECLARE dLon DOUBLE;
                    DECLARE a DOUBLE;
                    DECLARE c DOUBLE;
                    DECLARE d DOUBLE;
                    
                    SET R = 6371;
                    SET dLat = toRad(lat2-lat1);
                    SET dLon = toRad(lon2-lon1);
                    SET lat1 = toRad(lat1);
                    SET lat2 = toRad(lat2);
                    SET a = SIN(dLat/2) * SIN(dLat/2) + SIN(dLon/2) * SIN(dLon/2) * COS(lat1) * COS(lat2); 
                    SET c = 2 * ATAN(SQRT(a), SQRT(1-a)); 
                    SET d = R * c;
                    RETURN d / 1.609344;
                END
        ',
        '
            CREATE FUNCTION toRad (nbr DOUBLE)
                RETURNS DOUBLE
                BEGIN
                    RETURN nbr * 3.14159265359 / 180;
                END
        ');
        
    $dbInfo = $GLOBALS['databases']['default']['default'];
    $link = mysql_connect($dbInfo['host'] . ( empty($dbInfo['port']) ? "" : $dbInfo['port'] ), $dbInfo['username'], $dbInfo['password']);
    mysql_select_db($dbInfo['database'], $link);
    mysql_query($sql[0]);
    mysql_query($sql[1]);
    
    $ensureMySqlDistanceFunctionsExist_done = true;
}

function getGeoCounty($geoAddress) {
    $geoAddress = str_replace(' ', '%20', $geoAddress);
    $url = 'http://maps.google.com/maps/api/geocode/json?address=' . $geoAddress .'&sensor=false'; 
    $get     = file_get_contents($url);
    $geoData = json_decode($get);
    if(isset($geoData->results[0])) {
        foreach($geoData->results[0]->address_components as $addressComponet) {
            if(in_array('administrative_area_level_2', $addressComponet->types)) {
                return $addressComponet->long_name; 
            }
        }
    }
    return null; 
}

if ( function_exists('acronymToStateName') === false ) {
    function acronymToStateName($acronym, $whatToRetOnFail = false) {
    
        $state_list = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut",'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois",'IN'=>"Indiana",'IA'=>"Iowa",'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland",'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming", 'AS' => 'American Samoa', 'VI' => 'U.S. Virgin Islands', 'MP' => 'Northern Mariana Islands', 'PR' => 'Puerto Rico', 'GU' => 'Guam');
        
        if ( empty($state_list[strtoupper($acronym)]) ) {
            if ( in_array($acronym, $state_list) ) {
                return $acronym;
            } else {
                return $whatToRetOnFail;
            }
        } else {
            return $state_list[strtoupper($acronym)];
        }
    }
}

if ( function_exists('stateNameToAcronym') === false ) {
    function stateNameToAcronym($stateName, $whatToRetOnFail = false) {
        $state_list = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut",'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois",'IN'=>"Indiana",'IA'=>"Iowa",'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland",'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming", 'AS' => 'American Samoa', 'VI' => 'U.S. Virgin Islands', 'MP' => 'Northern Mariana Islands', 'PR' => 'Puerto Rico', 'GU' => 'Guam');
        $state_list = array_flip($state_list);
        
        if ( empty($state_list[$stateName]) ) {
            if ( in_array($stateName, $state_list) ) {
                return $stateName;
            } else {
                return $whatToRetOnFail;
            }
        } else {
            return $state_list[$stateName];
        }
    }
}

/** string acronymToCountryName(string $twoLetterCountryCode)
  *
  *  Returns the full name of a country based on given 2-letter abbreviation
  */
if ( function_exists('acronymToCountryName') === false ) {
    function acronymToCountryName($acronym, $whatToRetOnFail = false) {
    
        $country_list = array('AF' => 'Afghanistan','AX' => 'Aland Islands','AL' => 'Albania','DZ' => 'Algeria','AS' => 'American Samoa','AD' => 'Andorra','AO' => 'Angola','AI' => 'Anguilla','AQ' => 'Antarctica','AG' => 'Antigua and Barbuda','AR' => 'Argentina','AM' => 'Armenia','AW' => 'Aruba','AU' => 'Australia','AT' => 'Austria','AZ' => 'Azerbaijan','BS' => 'Bahamas the','BH' => 'Bahrain','BD' => 'Bangladesh','BB' => 'Barbados','BY' => 'Belarus','BE' => 'Belgium','BZ' => 'Belize','BJ' => 'Benin','BM' => 'Bermuda','BT' => 'Bhutan','BO' => 'Bolivia','BA' => 'Bosnia and Herzegovina','BW' => 'Botswana','BV' => 'Bouvet Island (Bouvetoya)','BR' => 'Brazil','IO' => 'British Indian Ocean Territory (Chagos Archipelago)','VG' => 'British Virgin Islands','BN' => 'Brunei Darussalam','BG' => 'Bulgaria','BF' => 'Burkina Faso','BI' => 'Burundi','KH' => 'Cambodia','CM' => 'Cameroon','CA' => 'Canada','CV' => 'Cape Verde','KY' => 'Cayman Islands','CF' => 'Central African Republic','TD' => 'Chad','CL' => 'Chile','CN' => 'China','CX' => 'Christmas Island','CC' => 'Cocos (Keeling) Islands','CO' => 'Colombia','KM' => 'Comoros the','CD' => 'Democratic Republic of the Congo','CG' => 'Republic of the Congo','CK' => 'Cook Islands','CR' => 'Costa Rica','CI' => 'Cote d\'Ivoire','HR' => 'Croatia','CU' => 'Cuba','CY' => 'Cyprus','CZ' => 'Czech Republic','DK' => 'Denmark','DJ' => 'Djibouti','DM' => 'Dominica','DO' => 'Dominican Republic','EC' => 'Ecuador','EG' => 'Egypt','SV' => 'El Salvador','GQ' => 'Equatorial Guinea','ER' => 'Eritrea','EE' => 'Estonia','ET' => 'Ethiopia','FO' => 'Faroe Islands','FK' => 'Falkland Islands (Malvinas)','FJ' => 'Fiji the Fiji Islands','FI' => 'Finland','FR' => 'France, French Republic','GF' => 'French Guiana','PF' => 'French Polynesia','TF' => 'French Southern Territories','GA' => 'Gabon','GM' => 'Gambia the','GE' => 'Georgia','DE' => 'Germany','GH' => 'Ghana','GI' => 'Gibraltar','GR' => 'Greece','GL' => 'Greenland','GD' => 'Grenada','GP' => 'Guadeloupe','GU' => 'Guam','GT' => 'Guatemala','GG' => 'Guernsey','GN' => 'Guinea','GW' => 'Guinea-Bissau','GY' => 'Guyana','HT' => 'Haiti','HM' => 'Heard Island and McDonald Islands','VA' => 'Holy See (Vatican City State)','HN' => 'Honduras','HK' => 'Hong Kong','HU' => 'Hungary','IS' => 'Iceland','IN' => 'India','ID' => 'Indonesia','IR' => 'Iran','IQ' => 'Iraq','IE' => 'Ireland','IM' => 'Isle of Man','IL' => 'Israel','IT' => 'Italy','JM' => 'Jamaica','JP' => 'Japan','JE' => 'Jersey','JO' => 'Jordan','KZ' => 'Kazakhstan','KE' => 'Kenya','KI' => 'Kiribati','KP' => 'Korea','KR' => 'Korea','KW' => 'Kuwait','KG' => 'Kyrgyz Republic','LA' => 'Lao','LV' => 'Latvia','LB' => 'Lebanon','LS' => 'Lesotho','LR' => 'Liberia','LY' => 'Libyan Arab Jamahiriya','LI' => 'Liechtenstein','LT' => 'Lithuania','LU' => 'Luxembourg','MO' => 'Macao','MK' => 'Macedonia','MG' => 'Madagascar','MW' => 'Malawi','MY' => 'Malaysia','MV' => 'Maldives','ML' => 'Mali','MT' => 'Malta','MH' => 'Marshall Islands','MQ' => 'Martinique','MR' => 'Mauritania','MU' => 'Mauritius','YT' => 'Mayotte','MX' => 'Mexico','FM' => 'Micronesia','MD' => 'Moldova','MC' => 'Monaco','MN' => 'Mongolia','ME' => 'Montenegro','MS' => 'Montserrat','MA' => 'Morocco','MZ' => 'Mozambique','MM' => 'Myanmar','NA' => 'Namibia','NR' => 'Nauru','NP' => 'Nepal','AN' => 'Netherlands Antilles','NL' => 'Netherlands the','NC' => 'New Caledonia','NZ' => 'New Zealand','NI' => 'Nicaragua','NE' => 'Niger','NG' => 'Nigeria','NU' => 'Niue','NF' => 'Norfolk Island','MP' => 'Northern Mariana Islands','NO' => 'Norway','OM' => 'Oman','PK' => 'Pakistan','PW' => 'Palau','PS' => 'Palestinian Territory','PA' => 'Panama','PG' => 'Papua New Guinea','PY' => 'Paraguay','PE' => 'Peru','PH' => 'Philippines','PN' => 'Pitcairn Islands','PL' => 'Poland','PT' => 'Portugal, Portuguese Republic','PR' => 'Puerto Rico','QA' => 'Qatar','RE' => 'Reunion','RO' => 'Romania','RU' => 'Russian Federation','RW' => 'Rwanda','BL' => 'Saint Barthelemy','SH' => 'Saint Helena','KN' => 'Saint Kitts and Nevis','LC' => 'Saint Lucia','MF' => 'Saint Martin','PM' => 'Saint Pierre and Miquelon','VC' => 'Saint Vincent and the Grenadines','WS' => 'Samoa','SM' => 'San Marino','ST' => 'Sao Tome and Principe','SA' => 'Saudi Arabia','SN' => 'Senegal','RS' => 'Serbia','SC' => 'Seychelles','SL' => 'Sierra Leone','SG' => 'Singapore','SK' => 'Slovakia (Slovak Republic)','SI' => 'Slovenia','SB' => 'Solomon Islands','SO' => 'Somalia, Somali Republic','ZA' => 'South Africa','GS' => 'South Georgia and the South Sandwich Islands','ES' => 'Spain','LK' => 'Sri Lanka','SD' => 'Sudan', 'SS' => 'South Sudan', 'SR' => 'Suriname','SJ' => 'Svalbard & Jan Mayen Islands','SZ' => 'Swaziland','SE' => 'Sweden','CH' => 'Switzerland, Swiss Confederation','SY' => 'Syrian Arab Republic','TW' => 'Taiwan','TJ' => 'Tajikistan','TZ' => 'Tanzania','TH' => 'Thailand','TL' => 'Timor-Leste','TG' => 'Togo','TK' => 'Tokelau','TO' => 'Tonga','TT' => 'Trinidad and Tobago','TN' => 'Tunisia','TR' => 'Turkey','TM' => 'Turkmenistan','TC' => 'Turks and Caicos Islands','TV' => 'Tuvalu','UG' => 'Uganda','UA' => 'Ukraine','AE' => 'United Arab Emirates','GB' => 'United Kingdom','US' => 'United States of America','UM' => 'United States Minor Outlying Islands','VI' => 'United States Virgin Islands','UY' => 'Uruguay, Eastern Republic of','UZ' => 'Uzbekistan','VU' => 'Vanuatu','VE' => 'Venezuela','VN' => 'Vietnam','WF' => 'Wallis and Futuna','EH' => 'Western Sahara','YE' => 'Yemen','ZM' => 'Zambia','ZW' => 'Zimbabwe');
        
        if ( empty($country_list[$acronym]) ) {
            if ( in_array($acronym, $country_list) ) {
                return $acronym;
            } else {
                return $whatToRetOnFail;
            }
        } else {
            return $country_list[$acronym];
        }
        
    }
}

/** string countryNameToAcronym(string $twoLetterCountryCode)
  *
  *  Returns the acronym of a country name
  */
if ( function_exists('countryNameToAcronym') === false ) {
    function countryNameToAcronym($countryName, $whatToRetOnFail = false) {
    
        $countryName = strtoupper($countryName);
        
        $country_list = array('AF' => 'AFGHANISTAN','AX' => 'ALAND ISLANDS','AL' => 'ALBANIA','DZ' => 'ALGERIA','AS' => 'AMERICAN SAMOA','AD' => 'ANDORRA','AO' => 'ANGOLA','AI' => 'ANGUILLA','AQ' => 'ANTARCTICA','AG' => 'ANTIGUA AND BARBUDA','AR' => 'ARGENTINA','AM' => 'ARMENIA','AW' => 'ARUBA','AU' => 'AUSTRALIA','AT' => 'AUSTRIA','AZ' => 'AZERBAIJAN','BS' => 'BAHAMAS THE','BH' => 'BAHRAIN','BD' => 'BANGLADESH','BB' => 'BARBADOS','BY' => 'BELARUS','BE' => 'BELGIUM','BZ' => 'BELIZE','BJ' => 'BENIN','BM' => 'BERMUDA','BT' => 'BHUTAN','BO' => 'BOLIVIA','BA' => 'BOSNIA AND HERZEGOVINA','BW' => 'BOTSWANA','BV' => 'BOUVET ISLAND (BOUVETOYA)','BR' => 'BRAZIL','IO' => 'BRITISH INDIAN OCEAN TERRITORY (CHAGOS ARCHIPELAGO)','VG' => 'BRITISH VIRGIN ISLANDS','BN' => 'BRUNEI DARUSSALAM','BG' => 'BULGARIA','BF' => 'BURKINA FASO','BI' => 'BURUNDI','KH' => 'CAMBODIA','CM' => 'CAMEROON','CA' => 'CANADA','CV' => 'CAPE VERDE','KY' => 'CAYMAN ISLANDS','CF' => 'CENTRAL AFRICAN REPUBLIC','TD' => 'CHAD','CL' => 'CHILE','CN' => 'CHINA','CX' => 'CHRISTMAS ISLAND','CC' => 'COCOS (KEELING) ISLANDS','CO' => 'COLOMBIA','KM' => 'COMOROS THE','CD' => 'CONGO','CG' => 'CONGO THE','CK' => 'COOK ISLANDS','CR' => 'COSTA RICA','CI' => 'COTE D\'IVOIRE','HR' => 'CROATIA','CU' => 'CUBA','CY' => 'CYPRUS','CZ' => 'CZECH REPUBLIC','DK' => 'DENMARK','DJ' => 'DJIBOUTI','DM' => 'DOMINICA','DO' => 'DOMINICAN REPUBLIC','EC' => 'ECUADOR','EG' => 'EGYPT','SV' => 'EL SALVADOR','GQ' => 'EQUATORIAL GUINEA','ER' => 'ERITREA','EE' => 'ESTONIA','ET' => 'ETHIOPIA','FO' => 'FAROE ISLANDS','FK' => 'FALKLAND ISLANDS (MALVINAS)','FJ' => 'FIJI THE FIJI ISLANDS','FI' => 'FINLAND','FR' => 'FRANCE, FRENCH REPUBLIC','GF' => 'FRENCH GUIANA','PF' => 'FRENCH POLYNESIA','TF' => 'FRENCH SOUTHERN TERRITORIES','GA' => 'GABON','GM' => 'GAMBIA THE','GE' => 'GEORGIA','DE' => 'GERMANY','GH' => 'GHANA','GI' => 'GIBRALTAR','GR' => 'GREECE','GL' => 'GREENLAND','GD' => 'GRENADA','GP' => 'GUADELOUPE','GU' => 'GUAM','GT' => 'GUATEMALA','GG' => 'GUERNSEY','GN' => 'GUINEA','GW' => 'GUINEA-BISSAU','GY' => 'GUYANA','HT' => 'HAITI','HM' => 'HEARD ISLAND AND MCDONALD ISLANDS','VA' => 'HOLY SEE (VATICAN CITY STATE)','HN' => 'HONDURAS','HK' => 'HONG KONG','HU' => 'HUNGARY','IS' => 'ICELAND','IN' => 'INDIA','ID' => 'INDONESIA','IR' => 'IRAN','IQ' => 'IRAQ','IE' => 'IRELAND','IM' => 'ISLE OF MAN','IL' => 'ISRAEL','IT' => 'ITALY','JM' => 'JAMAICA','JP' => 'JAPAN','JE' => 'JERSEY','JO' => 'JORDAN','KZ' => 'KAZAKHSTAN','KE' => 'KENYA','KI' => 'KIRIBATI','KP' => 'KOREA','KR' => 'KOREA','KW' => 'KUWAIT','KG' => 'KYRGYZ REPUBLIC','LA' => 'LAO','LV' => 'LATVIA','LB' => 'LEBANON','LS' => 'LESOTHO','LR' => 'LIBERIA','LY' => 'LIBYAN ARAB JAMAHIRIYA','LI' => 'LIECHTENSTEIN','LT' => 'LITHUANIA','LU' => 'LUXEMBOURG','MO' => 'MACAO','MK' => 'MACEDONIA','MG' => 'MADAGASCAR','MW' => 'MALAWI','MY' => 'MALAYSIA','MV' => 'MALDIVES','ML' => 'MALI','MT' => 'MALTA','MH' => 'MARSHALL ISLANDS','MQ' => 'MARTINIQUE','MR' => 'MAURITANIA','MU' => 'MAURITIUS','YT' => 'MAYOTTE','MX' => 'MEXICO','FM' => 'MICRONESIA','MD' => 'MOLDOVA','MC' => 'MONACO','MN' => 'MONGOLIA','ME' => 'MONTENEGRO','MS' => 'MONTSERRAT','MA' => 'MOROCCO','MZ' => 'MOZAMBIQUE','MM' => 'MYANMAR','NA' => 'NAMIBIA','NR' => 'NAURU','NP' => 'NEPAL','AN' => 'NETHERLANDS ANTILLES','NL' => 'NETHERLANDS THE','NC' => 'NEW CALEDONIA','NZ' => 'NEW ZEALAND','NI' => 'NICARAGUA','NE' => 'NIGER','NG' => 'NIGERIA','NU' => 'NIUE','NF' => 'NORFOLK ISLAND','MP' => 'NORTHERN MARIANA ISLANDS','NO' => 'NORWAY','OM' => 'OMAN','PK' => 'PAKISTAN','PW' => 'PALAU','PS' => 'PALESTINIAN TERRITORY','PA' => 'PANAMA','PG' => 'PAPUA NEW GUINEA','PY' => 'PARAGUAY','PE' => 'PERU','PH' => 'PHILIPPINES','PN' => 'PITCAIRN ISLANDS','PL' => 'POLAND','PT' => 'PORTUGAL, PORTUGUESE REPUBLIC','PR' => 'PUERTO RICO','QA' => 'QATAR','RE' => 'REUNION','RO' => 'ROMANIA','RU' => 'RUSSIAN FEDERATION','RW' => 'RWANDA','BL' => 'SAINT BARTHELEMY','SH' => 'SAINT HELENA','KN' => 'SAINT KITTS AND NEVIS','LC' => 'SAINT LUCIA','MF' => 'SAINT MARTIN','PM' => 'SAINT PIERRE AND MIQUELON','VC' => 'SAINT VINCENT AND THE GRENADINES','WS' => 'SAMOA','SM' => 'SAN MARINO','ST' => 'SAO TOME AND PRINCIPE','SA' => 'SAUDI ARABIA','SN' => 'SENEGAL','RS' => 'SERBIA','SC' => 'SEYCHELLES','SL' => 'SIERRA LEONE','SG' => 'SINGAPORE','SK' => 'SLOVAKIA (SLOVAK REPUBLIC)','SI' => 'SLOVENIA','SB' => 'SOLOMON ISLANDS','SO' => 'SOMALIA, SOMALI REPUBLIC','ZA' => 'SOUTH AFRICA','GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','ES' => 'SPAIN','LK' => 'SRI LANKA','SD' => 'SUDAN','SR' => 'SURINAME','SJ' => 'SVALBARD & JAN MAYEN ISLANDS','SZ' => 'SWAZILAND','SE' => 'SWEDEN','CH' => 'SWITZERLAND, SWISS CONFEDERATION','SY' => 'SYRIAN ARAB REPUBLIC','TW' => 'TAIWAN','TJ' => 'TAJIKISTAN','TZ' => 'TANZANIA','TH' => 'THAILAND','TL' => 'TIMOR-LESTE','TG' => 'TOGO','TK' => 'TOKELAU','TO' => 'TONGA','TT' => 'TRINIDAD AND TOBAGO','TN' => 'TUNISIA','TR' => 'TURKEY','TM' => 'TURKMENISTAN','TC' => 'TURKS AND CAICOS ISLANDS','TV' => 'TUVALU','UG' => 'UGANDA','UA' => 'UKRAINE','AE' => 'UNITED ARAB EMIRATES','GB' => 'UNITED KINGDOM','US' => 'UNITED STATES OF AMERICA','UM' => 'UNITED STATES MINOR OUTLYING ISLANDS','VI' => 'UNITED STATES VIRGIN ISLANDS','UY' => 'URUGUAY, EASTERN REPUBLIC OF','UZ' => 'UZBEKISTAN','VU' => 'VANUATU','VE' => 'VENEZUELA','VN' => 'VIETNAM','WF' => 'WALLIS AND FUTUNA','EH' => 'WESTERN SAHARA','YE' => 'YEMEN','ZM' => 'ZAMBIA','ZW' => 'ZIMBABWE');
        $country_list = array_flip($country_list);
        
        if ( empty($country_list[$countryName]) ) {
            if ( in_array($countryName, $country_list) ) {
                return $countryName;
            } else {
                return $whatToRetOnFail;
            }
        } else {
            return $country_list[$countryName];
        }
        
    }
}
