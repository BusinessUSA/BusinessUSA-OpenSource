<?php


/** mixed call_user_func_array ( callable $callback , array $param_arr )
  * Calls the callback given by the first parameter with the parameters in param_arr
  *
  * mixed call_user_func_array_cache (int $cacheDieInSeconds, string $functionName , array $param_arr )
  * Similar to call_user_func_array...
  * This function will call $functionName if the given combination of $functionName and the given parameters have not been called before, and then cache the result.
  * If this combination of $functionName and the given parameters HAVE been called before, then the function is not called and the cached results are simply returned.
  * 
  * LIMITS:
  *     Function name max length: 255
  */
  
if ( function_exists('call_user_func_cache') === false ) {
    function call_user_func_cache($cacheDieInSeconds, $functionName) {
        
        // Get given the arguments for the function $functionName into an aray
            $givenArgs = func_get_args();
            array_shift($givenArgs); // Shift off $cacheDieInSeconds
            array_shift($givenArgs); // Shift off $functionName
        
        // Continue functionality from call_user_func_array_cache();
        return call_user_func_array_cache($cacheDieInSeconds, $functionName, $givenArgs);

    }
}
  
if ( function_exists('call_user_func_array_cache') === false ) {
    function call_user_func_array_cache($cacheDieInSeconds, $functionName, $param_arr) {
        
        // Init
            global $FunctionResultCachingInstance;
            if ( isset($FunctionResultCachingInstance) === false ) {
                $FunctionResultCachingInstance = new FunctionResultCaching();
            }
            
        // Check if cache for this function exists - if so return
            $cacheLookupResult = $FunctionResultCachingInstance->getCacheEntry($functionName, $param_arr);
            if ( $cacheLookupResult['cache-exists'] === true ) {
                return $cacheLookupResult['data'];
            }
            
        // If this line is hit (we have not returned yet), then trigger the function 
            $rslt = call_user_func_array($functionName, $param_arr);
            
        // Cache the result and return
            $FunctionResultCachingInstance->storeCacheEntry($cacheDieInSeconds, $functionName, $param_arr, $rslt);
            return $rslt;
    }
}

if ( class_exists('FunctionResultCaching') === false ) {
    class FunctionResultCaching {
        
        public $mySqlLink = null;
        
        function __construct() {
            $this->mySqlLink = $this->connectMySQL();
            $this->ensureFunctionCacheTabelExists();
        }
        
        function storeCacheEntry($expiresInSeconds, $functName, $paramsArray, $result) {
        
            $serialParamsArray = serialize($paramsArray);
            $thisParamsHash = hash('sha256', $serialParamsArray);
            
            /* STORAGEMODE KEY
                0 = stored as string in the database 
                1 = stored as a string that is returned from serialize($data) in the database
                2 = stored as string in the file located at the path in the MySQL resultdata field 
                3 = stored as a string that is returned from serialize($data) in the file located at the path in the MySQL resultdata field 
            */
            
            // Decide storage mode
            if ( is_string($result) ) {
                $storageMode = 0; // STORAGEMODE KEY: 0 = stored as string in the database 
                $resultToStore = $result;
                $sqlResultToStore = $resultToStore;
                $sqlResultToStore = str_replace("\\", "\\\\", $sqlResultToStore); // Escape double-quotes
            } else {
                $storageMode = 1; // STORAGEMODE KEY: 1 = stored as a string that is returned from serialize($data) in the database
                $resultToStore = serialize($result); 
                $sqlResultToStore = $resultToStore;
                $sqlResultToStore = str_replace("\\", "\\\\", $sqlResultToStore); // Escape double-quotes
                $sqlResultToStore = str_replace('\'', "\\'", $sqlResultToStore); // Escape double-quotes
            }
            
            // Decide storage mode - We shall assume that MySQL can store up to a max of 30,000 characters in the database, and we will write results to disk for any larger string
            if ( strlen($sqlResultToStore) > 30000 ) { 
                
                // Ensure the function_cache directory exists
                if ( !is_dir('sites/default/files/function_cache') ) {
                    if ( !mkdir('sites/default/files/function_cache') ) {
                        exit('Error in FunctionResultCachingSupport.php::storeCacheEntry() - Could not create function_cache directory. Coder Bookmark: CB-V1ILCQE-BC ');
                    }
                }
                
                // Decide the storage path for the result of this function 
                $storageFilePath = 'sites/default/files/function_cache/' . $functName . '_' . $thisParamsHash . '.txt';
                $bytesStoredInFile = file_put_contents($storageFilePath, $resultToStore);
                if ( $bytesStoredInFile === false ) {
                    error_log("CRITICAL ERROR: Could not save function-cache file {$storageFilePath}");
                    $bytesStoredInFile = 0;
                }
                
                if ( $storageMode === 0 ) { // If the return of this function is a string
                    
                    $storageMode = 2; // STORAGEMODE KEY: 2 = stored as string in the file located at the path in the MySQL resultdata field 
                    
                } elseif ( $storageMode === 1 ) { // If the return of this function is not a string (must be serialized)
                    
                    $storageMode = 3; // STORAGEMODE KEY: 3 = stored as a string that is returned from serialize($data) in the file located at the path in the MySQL resultdata field 
                    
                } else {
                    exit('Error in FunctionResultCachingSupport.php::storeCacheEntry() - Invalid storage mode. Coder Bookmark: CB-LBBTRS8-BC ');
                }
                
                // We will store the $storageFilePath in MySQL
                $resultToStore = $storageFilePath;
                $sqlResultToStore = $storageFilePath;
                
            }
            
            // Verbosity
            $mysqlStorageByteCount = strlen($sqlResultToStore);
            switch ( intval($storageMode) ) {
                case 0: // STORAGEMODE KEY: 0 = stored as string in the database 
                    error_log("Note: Creating new entry in the functionresultcache table for the function $functName to live for $expiresInSeconds seconds. Stored as a ($mysqlStorageByteCount byte) string in the database.  Coder Bookmark: CB-0YCOSZX-BC ");
                    break;
                case 1: // STORAGEMODE KEY: 1 = stored as a string that is returned from serialize($data) in the database
                    error_log("Note: Creating new entry in the functionresultcache table for the function $functName to live for $expiresInSeconds seconds. Stored serialized in the database ($mysqlStorageByteCount bytes). Coder Bookmark: CB-M6FZK69-BC  ");
                    break;
                case 2: // STORAGEMODE KEY: 2 = stored as string in the file located at the path in the MySQL resultdata field 
                    error_log("Note: Creating new entry in the functionresultcache table for the function $functName to live for $expiresInSeconds seconds. Stored as a ({$bytesStoredInFile} byte) string in $storageFilePath - Coder Bookmark: CB-AI5A1EN-BC  ");
                    break;
                case 3: // STORAGEMODE KEY: 3 = stored as a string that is returned from serialize($data) in the file located at the path in the MySQL resultdata field 
                    error_log("Note: Creating new entry in the functionresultcache table for the function $functName to live for $expiresInSeconds seconds. Stored serialized in $storageFilePath ({$bytesStoredInFile} bytes) - Coder Bookmark: CB-OKPFIN1-BC  ");
                    break;
            }
            
            // MySQL string sanatizariotn
            $serialParamsArray = str_replace("'", "\\'", $serialParamsArray);
            
            // Save this entry into MySQL
            $expTime = time() + intval($expiresInSeconds);
            $sql="
                INSERT INTO functionresultcache 
                (functname, paramsdata, paramshash, resultdata, resultisserial, expirets) 
                VALUES ('$functName', '$serialParamsArray', '$thisParamsHash', '$sqlResultToStore', $storageMode, $expTime);
            ";
            mysql_query($sql, $this->mySqlLink);
        }
        
        function getCacheEntry($functName, $paramsArray) {
            
            // Clear expired results from table
            $curTime = time();
            $sql = "DELETE FROM functionresultcache WHERE expirets < $curTime";
            mysql_query($sql, $this->mySqlLink);
            
            // Compute needed information for fetching the cache for this function call&params
            $serialParamsArray = serialize($paramsArray);
            $thisParamsHash = hash('sha256', $serialParamsArray);
            
            // Fetch result - try to pull from MySQL
            $sql = "
                SELECT resultdata, resultisserial 
                FROM functionresultcache 
                WHERE 
                    functname = '$functName' 
                    AND paramshash = '$thisParamsHash'
            ";
            $result  = mysql_query($sql, $this->mySqlLink);
            
            // Return now if there was no result
            if (mysql_num_rows($result) == 0) {
                return array(
                    'cache-exists' => false,
                    'data' => null
                );
            }
            
            // We got a result, return
            while ($row = mysql_fetch_assoc($result)) {
            
                $resultData = $row["resultdata"];
                $resultIsSerial = $row["resultisserial"];
                
                switch ( intval($resultIsSerial) ) {
                    case 0: // STORAGEMODE KEY: 0 = stored as string in the database 
                        // No need to alter $resultData, it is in its desiered form
                        break;
                    case 1: // STORAGEMODE KEY: 1 = stored as a string that is returned from serialize($data) in the database
                        $resultData = unserialize($resultData); // Unserialize this back into its original object
                        break;
                    case 2: // STORAGEMODE KEY: 2 = stored as string in the file located at the path in the MySQL resultdata field 
                        // In this scenario, $resultData should contain a path to a file stored on the disk - verify this
                        if ( !file_exists($resultData) ) {
                            return array(
                                'cache-exists' => false,
                                'data' => null
                            );
                        }
                        $resultData = file_get_contents($resultData); // Unserialize this back into its original object
                        break;
                    case 3: // STORAGEMODE KEY: 3 = stored as a string that is returned from serialize($data) in the file located at the path in the MySQL resultdata field 
                        // In this scenario, $resultData should contain a path to a file stored on the disk - verify this
                        if ( !file_exists($resultData) ) {
                            return array(
                                'cache-exists' => false,
                                'data' => null
                            );
                        }
                        $resultData = unserialize(file_get_contents($resultData)); // Unserialize this back into its original object
                        break;
                }
                
                return array(
                    'cache-exists' => true,
                    'data' => $resultData
                );
            }
        }
        
        function ensureFunctionCacheTabelExists() {
        
            $sql = "
                CREATE TABLE IF NOT EXISTS functionresultcache (
                    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    functname CHAR( 255 ) NOT NULL ,
                    paramsdata TEXT NOT NULL DEFAULT  '' COMMENT  'PHP serialized array of the parameters sent to the target function',
                    paramshash CHAR( 65 ) NOT NULL COMMENT  'A 64 character human-readable string of the sha256 of paramsdata',
                    resultdata TEXT NOT NULL ,
                    resultisserial SMALLINT NULL DEFAULT NULL COMMENT  'States weather or not resultdata is PHP-serialized',
                    expirets INT UNSIGNED NOT NULL COMMENT  'The unix-time in which this cache entry should expire and be deleted',
                    INDEX (  functname ,  paramshash )
                )
            ";
            
            mysql_query($sql, $this->mySqlLink);
        }
        
        function connectMySQL() {
            $dbAuth = $GLOBALS["databases"]["default"]["default"];
            $host = $dbAuth["host"];
            $user = $dbAuth["username"];
            $pass = $dbAuth["password"];
            $db = $dbAuth["database"];
            $link = mysql_connect($host, $user, $pass);
            mysql_select_db($db, $link);
            return $link;
        }
        
    }
}