<?php //*********************************************DELETE ?>

    <hr>

    <?php
        // get the confirmation GET parameter
        $get_cfm = mysql_real_escape_string($_GET['cfm']);
        
        // if the confirmation is yes
        if ($get_cfm == 'yes')
        {
            // process the DELETE
            delete_record('tbl_airports', $rid, $conn);
        }
    ?>

    <?php

        // build the read query
        $sql = get_read_sql('tbl_airports')
            
            // the current record
            .' AND `airport_id` = '.$rid
            
            ;

        // process the sql for table
        $mysql_res = send_sql($sql, $conn);

        // get the first row
        $row = $mysql_res['first_row'];

    ?>
    
    <strong>Confirm Deletion</strong><br><br>
    
    Are you sure you wish to delete the record <strong>(<?php echo $row['airport_desc'] .' ['. $row['airport_code'].']'; ?>)</strong>?<br><br>
    
    <a href="?a=d&rid=<?php echo $rid; ?>&cfm=yes">Yes, delete the record</a> &nbsp;&nbsp;&nbsp; <a href="#">No, do not delete</a><br><br>
    
<?php //*********************************************DELETE ?>