<?php
/**
* CRUD Functions.
*
* @package    crud.php
* @subpackage 
* @author     BlinkWiki
* @version    1.0
* Copyright 2016 BlinkWiki
* 
*/
?>
<?php
function send_sql($sql, $conn)
{

    // submit the query to generate rows
    $rs = mysql_query($sql, $conn) or die(mysql_error());

    $row = array();
    $total_rows = 0;
    if (is_resource($rs))
    {
        
        // fetch the first 
        $row = mysql_fetch_assoc($rs);

        // calculate total rows
        $total_rows = mysql_num_rows($rs);

    }
    
    // save the values
    $res = array(
        
        // save the mysql resource
        'rs' => $rs
        
        // save the first row
        , 'first_row' => $row
        
        // save the total rows
        , 'total_rows' => $total_rows
    );
    
    // return the values
    return $res;
}

function get_key_fld($tbl, $conn)
{
    // get the columns in the table
    {
        $sql_col = 'SHOW COLUMNS FROM '.$tbl;
        $mysql_res = send_sql($sql, $conn);
    }
    
    // the first column is (should be) the table primary key field; so we save it
    $mysql_res['key'] = $row_col['Field'];
    
    return $mysql_res;
}

function get_create_sql($post, $tbl, $conn)
{
    // get the columns in the table
    {
        $sql_col = 'SHOW COLUMNS FROM '.$tbl;
        $rs_col = mysql_query($sql_col, $conn) or die(mysql_error());
        $row_col = mysql_fetch_assoc($rs_col);
        $total_cols = mysql_num_rows($rs_col);
    }
    
    // the first column is (should be) the table primary key field; so we save it
    $key_fld = $row_col['Field'];
    
    // intiate the update query
    $sql = "INSERT INTO `".$tbl."` (";

    // loop through building the rest of the update query
    for ($i=0; $i<$total_cols; $i++)
    {
        // append the separator comma
        if ($i > 0)
        {
            $sql .= ", ";
        }
        
        // append the table and column pair
        $sql .= "`".$tbl."`.`".$row_col['Field']."`";
    }
    
    // continue to the value section of the insert query
    $sql .= ") VALUES (";
    
    for ($i=0; $i<$total_cols; $i++)
    {
        // append the separator comma
        if ($i > 0)
        {
            $sql .= ", ";
        }
        
        $sql .= mysql_real_escape_string($post[$row_col['Field']]);
    }
    
    // close up the value section
    $sql .= ")";
        
    return $sql;
    
}

function get_update_sql($post, $tbl, $conn)
{
    // get the columns in the table
    {
        $sql_col = 'SHOW COLUMNS FROM '.$tbl;
        $rs_col = mysql_query($sql_col, $conn) or die(mysql_error());
        $row_col = mysql_fetch_assoc($rs_col);
        $total_cols = mysql_num_rows($rs_col);
    }
    
    // the first column is (should be) the table primary key field; so we save it
    $key_fld = $row_col['Field'];
    
    // intiate the update query
    $sql = "UPDATE `".$tbl."` SET";
    
    // loop through building the rest of the update query
    for ($i=0; $i<$total_cols; $i++)
    {
        $sql .= " `".$tbl."`.`".$row_col['Field']."` = ".mysql_real_escape_string($post[$row_col['Field']]);
    }
    
    // append the where clause
    $sql .= " WHERE 1";
    
    // complete the where clause : AND tbl.keyfld = 'post_val'
    $sql .= " AND `".$tbl."`.`".$key_fld."`=".mysql_real_escape_string($post[$key_fld]);
    
    return $sql;
}

function get_read_sql($tbl)
{
    switch($tbl)
    {
        case 'tbl_aircraft':
            $sql = 'SELECT *'
                .' FROM'
                    .' tbl_aircraft'
                    .', tbl_manu'
                    .', tbl_states'
                .' WHERE 1'
                    // relate the aircraft to the manufacturer
                    .' AND tbl_aircraft.ac_manu_id = tbl_manu.manu_id'
                    // relate the owner to the state of location
                    .' AND tbl_manu.manu_state_id = tbl_states.state_id'
            ;
            break;
        case 'tbl_owner_reg':
            $sql = 'SELECT *'
                .' FROM'
                    .' tbl_owner_reg'
                    .', tbl_states'
                .' WHERE 1'
                    // relate the owner to the state of location
                    .' AND tbl_owner_reg.owner_state_id = tbl_states.state_id'
            ;
            break;
        case 'tbl_manu':
            $sql = 'SELECT *'
                .' FROM'
                    .' tbl_ac_owner_map'
                    .', tbl_aircraft'
                    .', tbl_owner_reg'
                    .', tbl_states'
                .' WHERE 1'
                    // relate the normalised tables to the aircraft
                    .' AND tbl_ac_owner_map.ac_id = tbl_aircraft.ac_id'
                    // relate the normalised tables to the owner
                    .' AND tbl_ac_owner_map.owner_id = tbl_owner_reg.owner_id'
                    // relate the owner to the state of location
                    .' AND tbl_owner_reg.owner_state_id = tbl_states.state_id'
            ;
            break;
        case 'tbl_ac_owner_map':
            $sql = 'SELECT *'
                .' FROM'
                    .' tbl_ac_owner_map'
                    .', tbl_aircraft'
                    .', tbl_owner_reg'
                    .', tbl_manu'
                    .', tbl_states'
                .' WHERE 1'
                    // relate the normalised tables to the aircraft
                    .' AND tbl_ac_owner_map.ac_id = tbl_aircraft.ac_id'
                    // relate the normalised tables to the owner
                    .' AND tbl_ac_owner_map.owner_id = tbl_owner_reg.owner_id'
                    // relate the aircraft to the manufacturer
                    .' AND tbl_aircraft.ac_manu_id = tbl_manu.manu_id'
                    // relate the owner to the state of location
                    .' AND tbl_owner_reg.owner_state_id = tbl_states.state_id'
            ;
            break;
        case 'tbl_states':
        default:
            $sql = 'SELECT * FROM `'.$tbl.'` WHERE 1';
            
    }
    return $sql;
}

function get_delete_sql($tbl, $rid, $conn)
{
    // get the columns in the table
    {
        $sql_col = 'SHOW COLUMNS FROM '.$tbl;
        $rs_col = mysql_query($sql_col, $conn) or die(mysql_error());
        $row_col = mysql_fetch_assoc($rs_col);
        // $total_cols = mysql_num_rows($rs_col);
    }
    
    // the first column is (should be) the table primary key field; so we save it
    $key_fld = $row_col['Field'];
    
    // determine where to 'delete' or recover this record
    // using the recycle $_GET parameter
    {
        // get the recycle mode
        $get_recycle = mysql_real_escape_string($_GET['recycle']);
        $get_recycle = ($get_recycle > 0) ? $get_recycle : 0;

        // modify the SQL SET clause
        {
            // delete the record
            $status_value = '3';
            if ($get_recycle > 0)
            {
                // recover the record
                $status_value = '1';
            }
        }
    }

    
    // build the delete sql
    $sql = 'UPDATE'
            .' `'.$tbl.'` SET'
                // set the status value according to the GET recycle variable
                .' `'.$tbl.'`.`status` = "'.$status_value.'"'
            .' WHERE 1'
                // for the selected record
                .' AND `'.$tbl.'`.`'.$key_fld.'` = "'.mysql_real_escape_string($rid).'"'
    ;
    
    // return the sql
    return $sql;
}

function delete_record($tbl, $rid, $conn)
{
    // process the DELETE
    $sql = get_delete_sql('tbl_airports', $rid, $conn);

    // process the sql for table
    $mysql_res = send_sql($sql, $conn);

    // get the first row
    $row = $mysql_res['first_row'];
    
    // the message
    $msg = 'Error deleting record!';
    if ($rs)
    {
        $msg = 'Record successfully deleted!';
    }

    // redirect to the home page
    redir('index.php?&msg='.$msg);
}

?>