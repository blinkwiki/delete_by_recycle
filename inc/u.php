<?php //*********************************************UPDATE ?>

    <hr>
    
    <strong>Update Aircraft Ownership Record</strong><br><br>
    
    <?php $get_rid = mysql_real_escape_string($_GET['rid']); ?>
    
    <?php if ($get_rid) { ?>
    
    <form id="frm_frm" name="frm_frm" method="post" enctype="multipart/form-data">
        
        <input type="hidden" id="hd_frm" name="hd_frm" value="frm_frm" />
        
        <?php    
            // build the read query for the ownership register
            $sql = get_read_sql('tbl_ac_owner_map')
                .' AND tbl_ac_owner_map.ao_map_id = "'.$get_rid.'"'
            ;
            $rs = mysql_query($sql, $conn) or die(mysql_error());
            $row = mysql_fetch_assoc($rs);
            $total_rows = mysql_num_rows($rs);
        ?>
        
        Aircraft: 
        <select id="ac_id" name="ac_id">
            <?php echo selectbox('tbl_aircraft', 'ac_id', 'ac_model', $row['ac_id'], $conn); ?>
        </select> &nbsp; <a href="#">update aircraft records</a> <br><br>
        
        Owner: 
        <select id="owner_id" name="owner_id">
            <?php echo selectbox('tbl_owner_reg', 'owner_id', 'owner_name', $row['owner_id'], $conn); ?>
        </select> &nbsp; <a href="#">update owner records</a> <br><br>
        
        <input type="hidden" id="ao_map_id" name="ao_map_id" value="<?php echo $row['ao_map_id']; ?>" />
        
        <input type="submit" value="Save Ownership Record" />
        
        &nbsp;&nbsp;&nbsp;
        
        <input type="button" onclick="window.location.href='?a=r';" value="Cancel Form" />
        
    </form>
    
    <?php } else { ?>
    
        No record was selected
    
    <?php } ?>
    
    
<?php //*********************************************UPDATE ?>