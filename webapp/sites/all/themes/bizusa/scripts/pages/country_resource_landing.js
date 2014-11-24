/**
 * Created by sanjay.gupta on 9/4/14.
 */
(function ($) {

    $(document).ready(function(){

        $('#btntooltip').tooltip();


        $('.breadcrumb').empty();
        $('.breadcrumb').append('<a href="/">Home</a>');

        $('.breadcrumb').append(' » <a href="/micro-site/country-resource">Country Resources</a>');
        $('.breadcrumb').append(' » ' + Acronymntocountryname(getUrlVars()["country"]));


        //$('.breadcrumb a').after(' » <a href="/micro-site/country_resource">Country Resources</a> » ' + Acronymntocountryname(getUrlVars()["country"]));

        jQuery('#page-title').text(Acronymntocountryname(getUrlVars()["country"]) + ' Trade Leads');

        setTimeout(function(){

            if (getUrlVars()["industry"] != null)
            {
                var industry = getUrlVars()["industry"];
                industry = industry.replace(/%20/g, " ");
                $('#dropdownIndustry span.content').text(industry);
            }

            if (getUrlVars()["funding_source"] != null)
            {
                var funding_source = getUrlVars()["funding_source"];
                funding_source = funding_source.replace(/%20/g, " ");
                $('#dropdownFinance span.content').text(funding_source);
            }






        }, 200);

        //Logic to display no. of records displaying per page.
        var fieldcount = $('.view-content div.views-row').length;
        var text = $('#resultcounts').text();
        text = text.replace('DivFieldCount',fieldcount);

        $('#resultcounts').text(text);

        var query = location.search;
        var pos = query.indexOf('country=') + 8;
        var country = query.substr(pos);
        if (country == 'CA' || country == 'GB') {
          $('.row.row-country-resources').hide();
        }





    });

})(jQuery);



function reloadpage(par1, el)
{

    if (par1 == 'industry')
    {
        if (getUrlVars()["funding_source"] != null)
        {
            window.location = "../micro-site/country-resource-landing?country=" + getUrlVars()['country'] + "&industry=" + $(el).text() + "&funding_source=" + getUrlVars()["funding_source"];
        }
        else
        {
            window.location = "../micro-site/country-resource-landing?country=" + getUrlVars()['country'] + "&industry=" + $(el).text();
        }
    }
    else if (par1 == 'finance_source')
    {
        if (getUrlVars()["industry"] != null)
        {
            window.location = "../micro-site/country-resource-landing?country=" + getUrlVars()['country'] + "&industry=" + getUrlVars()["industry"] + "&funding_source=" + $(el).text();

        }
        else
        {
            window.location = "../micro-site/country-resource-landing?country=" + getUrlVars()['country'] + "&funding_source=" + $(el).text();

        }
    }
}

function Acronymntocountryname(accr)
{
    var countryfullname = '';
    switch (accr.toUpperCase())
    {
        case 'AD':
        {
            countryfullname = 'Andorra';
            break;
        }
        case 'AE':
        {
            countryfullname = 'United Arab Emirates';
            break;
        }
        case 'AF':
        {
            countryfullname = 'Afghanistan';
            break;
        }
        case 'AG':
        {
            countryfullname = 'Antigua and Barbuda';
            break;
        }
        case 'AI':
        {
            countryfullname = 'Anguilla';
            break;
        }
        case 'AL':
        {
            countryfullname = 'Albania';
            break;
        }
        case 'AM':
        {
            countryfullname = 'Armenia';
            break;
        }
        case 'AO':
        {
            countryfullname = 'Angola';
            break;
        }
        case 'AQ':
        {
            countryfullname = 'Antarctica';
            break;
        }
        case 'AR':
        {
            countryfullname = 'Argentina';
            break;
        }
        case 'AS':
        {
            countryfullname = 'American Samoa';
            break;
        }
        case 'AT':
        {
            countryfullname = 'Austria';
            break;
        }
        case 'AU':
        {
            countryfullname = 'Australia';
            break;
        }
        case 'AW':
        {
            countryfullname = 'Aruba';
            break;
        }
        case 'AX':
        {
            countryfullname = 'Åland Islands';
            break;
        }
        case 'AZ':
        {
            countryfullname = 'Azerbaijan';
            break;
        }
        case 'BA':
        {
            countryfullname = 'Bosnia and Herzegovina';
            break;
        }
        case 'BB':
        {
            countryfullname = 'Barbados';
            break;
        }
        case 'BD':
        {
            countryfullname = 'Bangladesh';
            break;
        }
        case 'BE':
        {
            countryfullname = 'Belgium';
            break;
        }
        case 'BF':
        {
            countryfullname = 'Burkina Faso';
            break;
        }
        case 'BG':
        {
            countryfullname = 'Bulgaria';
            break;
        }
        case 'BH':
        {
            countryfullname = 'Bahrain';
            break;
        }
        case 'BI':
        {
            countryfullname = 'Burundi';
            break;
        }
        case 'BJ':
        {
            countryfullname = 'Benin';
            break;
        }
        case 'BL':
        {
            countryfullname = 'Saint Barthélemy';
            break;
        }
        case 'BM':
        {
            countryfullname = 'Bermuda';
            break;
        }
        case 'BN':
        {
            countryfullname = 'Brunei Darussalam';
            break;
        }
        case 'BO':
        {
            countryfullname = 'Bolivia (Plurinational State of)';
            break;
        }
        case 'BQ':
        {
            countryfullname = 'Bonaire, Sint Eustatius and Saba';
            break;
        }
        case 'BR':
        {
            countryfullname = 'Brazil';
            break;
        }
        case 'BS':
        {
            countryfullname = 'Bahamas';
            break;
        }
        case 'BT':
        {
            countryfullname = 'Bhutan';
            break;
        }
        case 'BV':
        {
            countryfullname = 'Bouvet Island';
            break;
        }
        case 'BW':
        {
            countryfullname = 'Botswana';
            break;
        }
        case 'BY':
        {
            countryfullname = 'Belarus';
            break;
        }
        case 'BZ':
        {
            countryfullname = 'Belize';
            break;
        }
        case 'CA':
        {
            countryfullname = 'Canada';
            break;
        }
        case 'CC':
        {
            countryfullname = 'Cocos (Keeling) Islands';
            break;
        }
        case 'CD':
        {
            countryfullname = 'Democratic Republic of the Congo';
            break;
        }
        case 'CF':
        {
            countryfullname = 'Central African Republic';
            break;
        }
        case 'CG':
        {
            countryfullname = 'Republic of the Congo';
            break;
        }
        case 'CH':
        {
            countryfullname = 'Switzerland, Swiss Confederation';
            break;
        }
        case 'CI':
        {
            countryfullname = 'Côte dIvoire';
            break;
        }
        case 'CK':
        {
            countryfullname = 'Cook Islands';
            break;
        }
        case 'CL':
        {
            countryfullname = 'Chile';
            break;
        }
        case 'CM':
        {
            countryfullname = 'Cameroon';
            break;
        }
        case 'CN':
        {
            countryfullname = 'China';
            break;
        }
        case 'CO':
        {
            countryfullname = 'Colombia';
            break;
        }
        case 'CR':
        {
            countryfullname = 'Costa Rica';
            break;
        }
        case 'CU':
        {
            countryfullname = 'Cuba';
            break;
        }
        case 'CV':
        {
            countryfullname = 'Cabo Verde';
            break;
        }
        case 'CW':
        {
            countryfullname = 'Curaçao';
            break;
        }
        case 'CX':
        {
            countryfullname = 'Christmas Island';
            break;
        }
        case 'CY':
        {
            countryfullname = 'Cyprus';
            break;
        }
        case 'CZ':
        {
            countryfullname = 'Czech Republic';
            break;
        }
        case 'DE':
        {
            countryfullname = 'Germany';
            break;
        }
        case 'DJ':
        {
            countryfullname = 'Djibouti';
            break;
        }
        case 'DK':
        {
            countryfullname = 'Denmark';
            break;
        }
        case 'DM':
        {
            countryfullname = 'Dominica';
            break;
        }
        case 'DO':
        {
            countryfullname = 'Dominican Republic';
            break;
        }
        case 'DZ':
        {
            countryfullname = 'Algeria';
            break;
        }
        case 'EC':
        {
            countryfullname = 'Ecuador';
            break;
        }
        case 'EE':
        {
            countryfullname = 'Estonia';
            break;
        }
        case 'EG':
        {
            countryfullname = 'Egypt';
            break;
        }
        case 'EH':
        {
            countryfullname = 'Western Sahara*';
            break;
        }
        case 'ER':
        {
            countryfullname = 'Eritrea';
            break;
        }
        case 'ES':
        {
            countryfullname = 'Spain';
            break;
        }
        case 'ET':
        {
            countryfullname = 'Ethiopia';
            break;
        }
        case 'FI':
        {
            countryfullname = 'Finland';
            break;
        }
        case 'FJ':
        {
            countryfullname = 'Fiji';
            break;
        }
        case 'FK':
        {
            countryfullname = 'Falkland Islands [Malvinas]';
            break;
        }
        case 'FM':
        {
            countryfullname = 'Micronesia (the Federated States of)';
            break;
        }
        case 'FO':
        {
            countryfullname = 'Faroe Islands';
            break;
        }
        case 'FR':
        {
            countryfullname = 'France';
            break;
        }
        case 'GA':
        {
            countryfullname = 'Gabon';
            break;
        }
        case 'GB':
        {
            countryfullname = 'United Kingdom';
            break;
        }
        case 'GD':
        {
            countryfullname = 'Grenada';
            break;
        }
        case 'GE':
        {
            countryfullname = 'Georgia';
            break;
        }
        case 'GF':
        {
            countryfullname = 'French Guiana';
            break;
        }
        case 'GG':
        {
            countryfullname = 'Guernsey';
            break;
        }
        case 'GH':
        {
            countryfullname = 'Ghana';
            break;
        }
        case 'GI':
        {
            countryfullname = 'Gibraltar';
            break;
        }
        case 'GL':
        {
            countryfullname = 'Greenland';
            break;
        }
        case 'GM':
        {
            countryfullname = 'Gambia';
            break;
        }
        case 'GN':
        {
            countryfullname = 'Guinea';
            break;
        }
        case 'GP':
        {
            countryfullname = 'Guadeloupe';
            break;
        }
        case 'GQ':
        {
            countryfullname = 'Equatorial Guinea';
            break;
        }
        case 'GR':
        {
            countryfullname = 'Greece';
            break;
        }
        case 'GS':
        {
            countryfullname = 'South Georgia and the South Sandwich Islands';
            break;
        }
        case 'GT':
        {
            countryfullname = 'Guatemala';
            break;
        }
        case 'GU':
        {
            countryfullname = 'Guam';
            break;
        }
        case 'GW':
        {
            countryfullname = 'Guinea-Bissau';
            break;
        }
        case 'GY':
        {
            countryfullname = 'Guyana';
            break;
        }
        case 'HK':
        {
            countryfullname = 'Hong Kong';
            break;
        }
        case 'HM':
        {
            countryfullname = 'Heard Island and McDonald Islands';
            break;
        }
        case 'HN':
        {
            countryfullname = 'Honduras';
            break;
        }
        case 'HR':
        {
            countryfullname = 'Croatia';
            break;
        }
        case 'HT':
        {
            countryfullname = 'Haiti';
            break;
        }
        case 'HU':
        {
            countryfullname = 'Hungary';
            break;
        }
        case 'ID':
        {
            countryfullname = 'Indonesia';
            break;
        }
        case 'IE':
        {
            countryfullname = 'Ireland';
            break;
        }
        case 'IL':
        {
            countryfullname = 'Israel';
            break;
        }
        case 'IM':
        {
            countryfullname = 'Isle of Man';
            break;
        }
        case 'IN':
        {
            countryfullname = 'India';
            break;
        }
        case 'IO':
        {
            countryfullname = 'British Indian Ocean Territory';
            break;
        }
        case 'IQ':
        {
            countryfullname = 'Iraq';
            break;
        }
        case 'IR':
        {
            countryfullname = 'Iran';
            break;
        }
        case 'IS':
        {
            countryfullname = 'Iceland';
            break;
        }
        case 'IT':
        {
            countryfullname = 'Italy';
            break;
        }
        case 'JE':
        {
            countryfullname = 'Jersey';
            break;
        }
        case 'JM':
        {
            countryfullname = 'Jamaica';
            break;
        }
        case 'JO':
        {
            countryfullname = 'Jordan';
            break;
        }
        case 'JP':
        {
            countryfullname = 'Japan';
            break;
        }
        case 'KE':
        {
            countryfullname = 'Kenya';
            break;
        }
        case 'KG':
        {
            countryfullname = 'Kyrgyzstan';
            break;
        }
        case 'KH':
        {
            countryfullname = 'Cambodia';
            break;
        }
        case 'KI':
        {
            countryfullname = 'Kiribati';
            break;
        }
        case 'KM':
        {
            countryfullname = 'Comoros';
            break;
        }
        case 'KN':
        {
            countryfullname = 'Saint Kitts and Nevis';
            break;
        }
        case 'KP':
        {
            countryfullname = 'Korea (the Democratic Peoples Republic of)';
            break;
        }
        case 'KR':
        {
            countryfullname = 'Korea(the Republic of)';
            break;
        }
        case 'KW':
        {
            countryfullname = 'Kuwait';
            break;
        }
        case 'KY':
        {
            countryfullname = 'Cayman Islands';
            break;
        }
        case 'KZ':
        {
            countryfullname = 'Kazakhstan';
            break;
        }
        case 'LA':
        {
            countryfullname = 'Lao';
            break;
        }
        case 'LB':
        {
            countryfullname = 'Lebanon';
            break;
        }
        case 'LC':
        {
            countryfullname = 'Saint Lucia';
            break;
        }
        case 'LI':
        {
            countryfullname = 'Liechtenstein';
            break;
        }
        case 'LK':
        {
            countryfullname = 'Sri Lanka';
            break;
        }
        case 'LR':
        {
            countryfullname = 'Liberia';
            break;
        }
        case 'LS':
        {
            countryfullname = 'Lesotho';
            break;
        }
        case 'LT':
        {
            countryfullname = 'Lithuania';
            break;
        }
        case 'LU':
        {
            countryfullname = 'Luxembourg';
            break;
        }
        case 'LV':
        {
            countryfullname = 'Latvia';
            break;
        }
        case 'LY':
        {
            countryfullname = 'Libya';
            break;
        }
        case 'MA':
        {
            countryfullname = 'Morocco';
            break;
        }
        case 'MC':
        {
            countryfullname = 'Monaco';
            break;
        }
        case 'MD':
        {
            countryfullname = 'Moldova (the Republic of)';
            break;
        }
        case 'ME':
        {
            countryfullname = 'Montenegro';
            break;
        }
        case 'MF':
        {
            countryfullname = 'Saint Martin (French part)';
            break;
        }
        case 'MG':
        {
            countryfullname = 'Madagascar';
            break;
        }
        case 'MH':
        {
            countryfullname = 'Marshall Islands';
            break;
        }
        case 'MK':
        {
            countryfullname = 'Macedonia';
            break;
        }
        case 'ML':
        {
            countryfullname = 'Mali';
            break;
        }
        case 'MM':
        {
            countryfullname = 'Myanmar';
            break;
        }
        case 'MN':
        {
            countryfullname = 'Mongolia';
            break;
        }
        case 'MO':
        {
            countryfullname = 'Macao';
            break;
        }
        case 'MP':
        {
            countryfullname = 'Northern Mariana Islands';
            break;
        }
        case 'MQ':
        {
            countryfullname = 'Martinique';
            break;
        }
        case 'MR':
        {
            countryfullname = 'Mauritania';
            break;
        }
        case 'MS':
        {
            countryfullname = 'Montserrat';
            break;
        }
        case 'MT':
        {
            countryfullname = 'Malta';
            break;
        }
        case 'MU':
        {
            countryfullname = 'Mauritius';
            break;
        }
        case 'MV':
        {
            countryfullname = 'Maldives';
            break;
        }
        case 'MW':
        {
            countryfullname = 'Malawi';
            break;
        }
        case 'MX':
        {
            countryfullname = 'Mexico';
            break;
        }
        case 'MY':
        {
            countryfullname = 'Malaysia';
            break;
        }
        case 'MZ':
        {
            countryfullname = 'Mozambique';
            break;
        }
        case 'NA':
        {
            countryfullname = 'Namibia';
            break;
        }
        case 'NC':
        {
            countryfullname = 'New Caledonia';
            break;
        }
        case 'NE':
        {
            countryfullname = 'Niger';
            break;
        }
        case 'NF':
        {
            countryfullname = 'Norfolk Island';
            break;
        }
        case 'NG':
        {
            countryfullname = 'Nigeria';
            break;
        }
        case 'NI':
        {
            countryfullname = 'Nicaragua';
            break;
        }
        case 'NL':
        {
            countryfullname = 'Netherlands (The)';
            break;
        }
        case 'NO':
        {
            countryfullname = 'Norway';
            break;
        }
        case 'NP':
        {
            countryfullname = 'Nepal';
            break;
        }
        case 'NR':
        {
            countryfullname = 'Nauru';
            break;
        }
        case 'NU':
        {
            countryfullname = 'Niue';
            break;
        }
        case 'NZ':
        {
            countryfullname = 'New Zealand';
            break;
        }
        case 'OM':
        {
            countryfullname = 'Oman';
            break;
        }
        case 'PA':
        {
            countryfullname = 'Panama';
            break;
        }
        case 'PE':
        {
            countryfullname = 'Peru';
            break;
        }
        case 'PF':
        {
            countryfullname = 'French Polynesia';
            break;
        }
        case 'PG':
        {
            countryfullname = 'Papua New Guinea';
            break;
        }
        case 'PH':
        {
            countryfullname = 'Philippines';
            break;
        }
        case 'PK':
        {
            countryfullname = 'Pakistan';
            break;
        }
        case 'PL':
        {
            countryfullname = 'Poland';
            break;
        }
        case 'PM':
        {
            countryfullname = 'Saint Pierre and Miquelon';
            break;
        }
        case 'PN':
        {
            countryfullname = 'Pitcairn';
            break;
        }
        case 'PR':
        {
            countryfullname = 'Puerto Rico';
            break;
        }
        case 'PS':
        {
            countryfullname = 'Palestine, State of';
            break;
        }
        case 'PT':
        {
            countryfullname = 'Portugal';
            break;
        }
        case 'PW':
        {
            countryfullname = 'Palau';
            break;
        }
        case 'PY':
        {
            countryfullname = 'Paraguay';
            break;
        }
        case 'QA':
        {
            countryfullname = 'Qatar';
            break;
        }
        case 'RE':
        {
            countryfullname = 'Réunion';
            break;
        }
        case 'RO':
        {
            countryfullname = 'Romania';
            break;
        }
        case 'RS':
        {
            countryfullname = 'Serbia';
            break;
        }
        case 'RU':
        {
            countryfullname = 'Russian Federation';
            break;
        }
        case 'RW':
        {
            countryfullname = 'Rwanda';
            break;
        }
        case 'SA':
        {
            countryfullname = 'Saudi Arabia';
            break;
        }
        case 'SB':
        {
            countryfullname = 'Solomon Islands';
            break;
        }
        case 'SC':
        {
            countryfullname = 'Seychelles';
            break;
        }
        case 'SD':
        {
            countryfullname = 'Sudan';
            break;
        }
        case 'SE':
        {
            countryfullname = 'Sweden';
            break;
        }
        case 'SG':
        {
            countryfullname = 'Singapore';
            break;
        }
        case 'SH':
        {
            countryfullname = 'Saint Helena, Ascension and Tristan da Cunha';
            break;
        }
        case 'SI':
        {
            countryfullname = 'Slovenia';
            break;
        }
        case 'SJ':
        {
            countryfullname = 'Svalbard and Jan Mayen';
            break;
        }
        case 'SK':
        {
            countryfullname = 'Slovakia';
            break;
        }
        case 'SL':
        {
            countryfullname = 'Sierra Leone';
            break;
        }
        case 'SM':
        {
            countryfullname = 'San Marino';
            break;
        }
        case 'SN':
        {
            countryfullname = 'Senegal';
            break;
        }
        case 'SO':
        {
            countryfullname = 'Somalia';
            break;
        }
        case 'SR':
        {
            countryfullname = 'Suriname';
            break;
        }
        case 'SS':
        {
            countryfullname = 'South Sudan ';
            break;
        }
        case 'ST':
        {
            countryfullname = 'Sao Tome and Principe';
            break;
        }
        case 'SV':
        {
            countryfullname = 'El Salvador';
            break;
        }
        case 'SX':
        {
            countryfullname = 'Sint Maarten (Dutch part)';
            break;
        }
        case 'SY':
        {
            countryfullname = 'Syrian Arab Republic';
            break;
        }
        case 'SZ':
        {
            countryfullname = 'Swaziland';
            break;
        }
        case 'TC':
        {
            countryfullname = 'Turks and Caicos Islands';
            break;
        }
        case 'TD':
        {
            countryfullname = 'Chad';
            break;
        }
        case 'TF':
        {
            countryfullname = 'French Southern Territories';
            break;
        }
        case 'TG':
        {
            countryfullname = 'Togo';
            break;
        }
        case 'TH':
        {
            countryfullname = 'Thailand';
            break;
        }
        case 'TJ':
        {
            countryfullname = 'Tajikistan';
            break;
        }
        case 'TK':
        {
            countryfullname = 'Tokelau';
            break;
        }
        case 'TL':
        {
            countryfullname = 'Timor-Leste';
            break;
        }
        case 'TM':
        {
            countryfullname = 'Turkmenistan';
            break;
        }
        case 'TN':
        {
            countryfullname = 'Tunisia';
            break;
        }
        case 'TO':
        {
            countryfullname = 'Tonga';
            break;
        }
        case 'TR':
        {
            countryfullname = 'Turkey';
            break;
        }
        case 'TT':
        {
            countryfullname = 'Trinidad and Tobago';
            break;
        }
        case 'TV':
        {
            countryfullname = 'Tuvalu';
            break;
        }
        case 'TW':
        {
            countryfullname = 'Taiwan (Province of China)';
            break;
        }
        case 'TZ':
        {
            countryfullname = 'United Republic of Tanzania';
            break;
        }
        case 'UA':
        {
            countryfullname = 'Ukraine';
            break;
        }
        case 'UG':
        {
            countryfullname = 'Uganda';
            break;
        }
        case 'UM':
        {
            countryfullname = 'United States Minor Outlying Islands';
            break;
        }
        case 'US':
        {
            countryfullname = 'United States';
            break;
        }
        case 'UY':
        {
            countryfullname = 'Uruguay';
            break;
        }
        case 'UZ':
        {
            countryfullname = 'Uzbekistan';
            break;
        }
        case 'VA':
        {
            countryfullname = 'Holy See [Vatican City State]';
            break;
        }
        case 'VC':
        {
            countryfullname = 'Saint Vincent and the Grenadines';
            break;
        }
        case 'VE':
        {
            countryfullname = 'Bolivarian Republic of Venezuela';
            break;
        }
        case 'VG':
        {
            countryfullname = 'Virgin Islands (British)';
            break;
        }
        case 'VI':
        {
            countryfullname = 'Virgin Islands (U.S.)';
            break;
        }
        case 'VN':
        {
            countryfullname = 'Vietnam';
            break;
        }
        case 'VU':
        {
            countryfullname = 'Vanuatu';
            break;
        }
        case 'WF':
        {
            countryfullname = 'Wallis and Futuna';
            break;
        }
        case 'WS':
        {
            countryfullname = 'Samoa';
            break;
        }
        case 'YE':
        {
            countryfullname = 'Yemen';
            break;
        }
        case 'YT':
        {
            countryfullname = 'Mayotte';
            break;
        }
        case 'ZA':
        {
            countryfullname = 'South Africa';
            break;
        }
        case 'ZM':
        {
            countryfullname = 'Zambia';
            break;
        }
        case 'ZW':
        {
            countryfullname = 'Zimbabwe';
            break;
        }
        default:
        {
            countryfullname ='';
        }
    }
    return countryfullname;
}