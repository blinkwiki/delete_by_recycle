<?php
/**
* Create a new record.
*
* @package    create.php
* @subpackage 
* @author     BlinkWiki
* @version    1.0
* Copyright 2016 BlinkWiki
* 
*/
?>
<?php //*********************************************CREATE ?>

    <hr>
    
    <strong>Create Aircraft Ownership Record</strong><br><br>
    
    <form id="frm_frm" name="frm_frm" method="post" enctype="multipart/form-data">
        
        <input type="hidden" id="hd_frm" name="hd_frm" value="frm_frm" />
        
        Aircraft: 
        <select id="ac_id" name="ac_id">
            <?php echo selectbox('tbl_aircraft', 'ac_id', 'ac_model', 0, $conn); ?>
        </select> &nbsp; <a href="#">update aircraft records</a> <br><br>
        
        Owner: 
        <select id="owner_id" name="owner_id">
            <?php echo selectbox('tbl_owner_reg', 'owner_id', 'owner_name', 0, $conn); ?>
        </select> &nbsp; <a href="#">update owner records</a> <br><br>
        
        <input type="hidden" id="ao_map_id" name="ao_map_id" value="<?php echo 0; ?>" />
        
        <input class="fw_b" type="submit" value="Save Ownership Record" />
        
        &nbsp;&nbsp;
        
        <input type="button" onclick="window.location.href='?a=r';" value="Cancel Form" />
        
    </form>
    
<?php //*********************************************CREATE ?>