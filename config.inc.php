<?php

    require 'db/mysql.inc.php';
    $TYPE   = 'mysql';
    $HOST   = 'localhost';
    $USER   = 'root'; //flyhi_admin
    $PASS   = 'root'; //T8I$5PF8Vt]k
    $DBNAME = 'flyhi';//flyhi_travels

	$db = sql_Connect($HOST, $USER, $PASS, $DBNAME);
    
?>