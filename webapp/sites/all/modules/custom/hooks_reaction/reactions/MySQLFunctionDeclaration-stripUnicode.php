<?php

function ensureMySqlStripUnicodeFunctionsExist() {
    $sql = "
        CREATE FUNCTION stripUnicode(in_str varchar(4096)) RETURNS varchar(4096) CHARSET utf8 
        BEGIN 
              DECLARE out_str VARCHAR(4096) DEFAULT ''; 
              DECLARE c VARCHAR(4096) DEFAULT ''; 
              DECLARE pointer INT DEFAULT 1; 

              IF ISNULL(in_str) THEN 
                    RETURN NULL; 
              ELSE 
                    WHILE pointer <= LENGTH(in_str) DO 
                           
                          SET c = MID(in_str, pointer, 1); 

                          IF ASCII(c) > 31 AND ASCII(c) < 127 THEN 
                                SET out_str = CONCAT(out_str, c); 
                          END IF; 

                          SET pointer = pointer + 1; 
                    END WHILE; 
              END IF; 

              RETURN out_str; 
        END 
    ";
        
    $dbInfo = $GLOBALS['databases']['default']['default'];
    $link = mysql_connect($dbInfo['host'] . ( empty($dbInfo['port']) ? "" : $dbInfo['port'] ), $dbInfo['username'], $dbInfo['password']);
    mysql_select_db($dbInfo['database'], $link);
    mysql_query($sql);   
}
