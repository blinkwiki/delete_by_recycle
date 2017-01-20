<?php //*********************************************READ ?>
    
    <hr>
    
    <strong>Read Airports</strong><br>
    <a href="index.php?recycle=1">Recycle Bin</a>
    <br><br><br>
    <?php
        
        // this segmemt uses the recycle $_GET parameter to determine the read mode
        // recycle = 1 means show only records in the recycle bin
        {
            // get the recycle mode
            $get_recycle = mysql_real_escape_string($_GET['recycle']);
            $get_recycle = ($get_recycle > 0) ? $get_recycle : 0;
            
            // modify the where clause
            if ($get_recycle > 0)
            {
                // show only the 'deleted' records
                $whr_recycle = ' AND `status` = "3"';

                // the recover link
                $delete_url = 'index.php?a=d&cfm=yes&recycle=1&rid=';
                $delete_text = 'recover';
            }
            else
            {
                // show only the 'non-deleted' records
                $whr_recycle = ' AND `status` <> "3"';

                // the delete link
                $delete_url = 'index.php?a=d&rid=';
                $delete_text = 'delete';
            }

        }

        // build the read query
        $sql = get_read_sql('tbl_airports')
            
            // include the recycle where clause
            .$whr_recycle
            
            // arrange by code
            .' ORDER BY `airport_code` ASC'
            
            ;

        // process the sql for table
        $mysql_res = send_sql($sql, $conn);

        // get the first row
        $row = $mysql_res['first_row'];

    ?>
    
    <?php // display the table ?>
    <table width="100%">
        <thead class="fw_b" valign="top">
            <tr>
                <td width="5%">SN</td>
                <td width="10%">CODE</td>
                <td width="10%">IATA</td>
                <td>Airport Name</td>
                <td>City</td>
                <td>Country</td>
                <td width="10%">Actions</td>
            </tr>
        </thead>
        <tbody valign="top">
        <?php
            // display the table
            for ($i=0; $i<$mysql_res['total_rows']; $i++)
            {
        ?>
            <tr>
                <td><?php echo $i+1; ?></td>
                <td><?php echo $row['airport_code']; ?></td>
                <td><?php echo $row['airport_iata_code']; ?></td>
                <td><?php echo utf8_encode($row['airport_desc']); ?></td>
                <td><?php echo utf8_encode($row['airport_city']); ?></td>
                <td><?php echo utf8_encode($row['airport_notes']); ?></td>
                <td>
                    <a href="<?php echo $delete_url.$row['airport_id']; ?>">
                        <?php echo $delete_text; ?>
                    </a>
                </td>
            </tr>
            <?php $row = mysql_fetch_assoc($mysql_res['rs']); ?>
        <?php } ?>
        </tbody>
    </table>
    
    <?php // display the table ?>
    
<?php //*********************************************READ ?>