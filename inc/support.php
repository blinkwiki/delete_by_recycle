<?php
/**
* Support functions for CRUD.
*
* @package    support.php
* @subpackage 
* @author     BlinkWiki
* @version    1.0
* Copyright 2016 BlinkWiki
* 
*/
?>
<?php

// perform redirection
function redir($url)
{
    echo '<script type="text/javascript">window.location = "'.$url.'";</script>';
}

// function that retrieves the last index in a table
function rec_get_next($tbl, $tblid, $conn)
{
    $sql = get_read_sql($tbl)
        .' ORDER BY `'.$tblid.'`'
        .' DESC LIMIT 1'
    ;
    $rs = mysql_query($sql, $conn) or die(mysql_error());
    $row = mysql_fetch_assoc($rs);
    $total_rows = mysql_num_rows($rs);
    
    return intval($row[$tblid] + 1);
}
    
// function to populate selectboxes
function select_box($tbl, $opt_value, $opt_text, $rid, $conn)
{
    // build the read query
    $sql = get_read_sql($tbl);
    $rs = mysql_query($sql, $conn) or die(mysql_error());
    $row = mysql_fetch_assoc($rs);
    $total_rows = mysql_num_rows($rs);
    
    $sel = ($rid == 0) ? 'selected' : '';
    $html = '<option value="0" '.$sel.'>--select--</option>';
    
    for ($i=0; $i<$total_rows; $i++)
    {
        $sel = ($row[$opt_value] == $rid) ? 'selected' : '';
        
        $html .= '<option value="'.$row[$opt_value].'" '.$sel.'>'.$row[$opt_text].'</option>';
        
        // get the next row
        $row = mysql_fetch_assoc($rs);
    }
    
    return $html;
}

// function to ensure all values in an array are submitted
function ensure_submit($post, $mand_str)
{
    // make the string an array
    $post_arr = explode(',', $mand_str);
    
    // initialise the result to true
    $res = true;
    
    // if mandantory fields were entered
    if (count($post_arr) > 0)
    {
        // loop through looking for empty strings
        foreach ($post_arr as $key => $value)
        {
            if (
                strlen($post[$value]) <= 0
                || $post[$value] == 0
               )
            {
                // submitted value is empty
                $res = false;
            }
        }
    }
    
    return $res;
}

// function to submit $_POST values
function submit_form($post, $conn)
{
    $msg = 'error submitting form!';
    if (isset($post['hd_frm']))
    {
        echo 'form submitted....<br>';

        // make sure all the mandatory fields are fields
        $form_is_valid = false;
        if (isset($_POST['mand_flds']))
        {
            // make sure mandatory fields are set
            $form_is_valid = ensure_submit($post, $_POST['mand_flds']);
        }
        else
        {
            // set form submission as valid
            $form_is_valid = true;
        }
        if (
            $form_is_valid == true
        )
        {
            $msg = 'all mandatory fields submitted!';
        }

        if ($form_is_valid)
        {
            // get the query
            if ($post['hd_frm'] == 'frm_u')
            {
                $SQL = get_update_sql($post, $tbl, $conn);
            }
            else
            {
                $SQL = get_create_sql($post, $tbl, $conn);
            }

            // submit the query
            $rs = mysql_query($SQL, $conn) or die(mysql_error());
            if ($rs)
            {
                $row = mysql_fetch_assoc($rs);
                $msg = 'form submitted successfully!';
            }
            else
            {
                $msg = 'error submitting form!';
            }
        }

        // redirect: the javascript way
        echo redir('?msg='.$msg);

    }
}
    
// submit the form
submit_form($_POST, $conn);
    
?>