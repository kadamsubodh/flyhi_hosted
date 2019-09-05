<?php
    
    /* Connect to the database server */
    function sql_Connect($host, $user, $pass, $db = "") {

            if ($link = @mysql_connect($host, $user, $pass)) {

                    if (!$db OR @mysql_select_db($db)) {
                            return $link;
                    }

            }
            return false;

    }
    
    /* Select the database */
    function sql_SelectDB($db, $link) {

            if (@mysql_select_db($db, $link)) {
                    return $link;
            }

            return false;

    }

    /* Execute an SQL query */
    function sql_Query($query, $link = NULL) {

            global $db;
            if (!$link) $link = &$db;

            return mysql_query($query, $link);
    }
    
    /* Fetch an associative array of the next record in the resultset */
    function sql_Fetch($resultset) {
            return mysql_fetch_assoc($resultset);
    }
    
    /* Return the number of rows in the resultset */
    function sql_NumRows($resultset) {
            return mysql_num_rows($resultset);
    }
?>
