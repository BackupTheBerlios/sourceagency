<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Lutz Henckel (lutz.henckel@fokus.fraunhofer.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This file is usefull for administrating (registered) users
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: admuser.php,v 1.1 2003/11/21 12:55:58 helix Exp $
#
######################################################################

require("include/prepend.php3");

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require("include/header.inc");

$bx = new box("98%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$be = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();

if (($config_perm_admuser != "all") 
    && (!isset($perm) || !$perm->have_perm($config_perm_admuser))) {
  $be->box_full($t->translate("Error"), $t->translate("Access denied"));
} else {

    // Check if there was a submission
    while (is_array($HTTP_POST_VARS) 
           && list($key, $val) = each($HTTP_POST_VARS)) {
        switch ($key) {
            case "create": // Create a new user
                if (empty($username) || empty($password) 
                    || empty($email_usr)) { 
                    // Do we have all necessary data?
                    $be->box_full($t->translate("Error"), 
                                  $t->translate("Please enter")." <B>"
                                  .$t->translate("Username")   ."</B>, <B>"
                                  .$t->translate("Password")   ."</B> "
                                  .$t->translate("and")        ." <B>"
                                  .$t->translate("E-Mail")     ."</B>!");
                    break;
                }

                /* Does the user already exist?
                   NOTE: This should be a transaction, but it isn't... */
                $db->query("select * from auth_user where "
                           ."username='$username'");
                if ($db->nf()>0) {
                    $be->box_full($t->translate("Error"), 
                                  $t->translate("User")." <B>$username</B> "
                                  .$t->translate("already exists").".");
                    break;
                }

                // Create a uid and insert the user...
                $u_id=md5(uniqid($hash_secret));
                $permlist = addslashes(implode($perms,","));
                $modification_usr = "NOW()";
                $creation_usr = "NOW()";
                $query = ("insert into auth_user values('$u_id','$username',"
                          ."'$password','$realname','$email_usr',"
                          ."$modification_usr,$creation_usr,'$permlist')");
                $db->query($query);
                if ($db->affected_rows() == 0) {
                    $be->box_full($t->translate("Error"), "<b>"
                                  .$t->translate("Database Access failed")
                                  .":</b> $query");
                    break;
                }
                $bx->box_full($t->translate("User Creation"), 
                              $t->translate("User")." \"$username\" "
                              .$t->translate("created").".<BR>");
                break;
                
            case "u_edit": // Change user parameters
                if (empty($username) || empty($password) 
                    || empty($email_usr)) { 
                    // Do we have all necessary data?
                    $be->box_full($t->translate("Error"), 
                                  $t->translate("Please enter")." <B>"
                                  .$t->translate("Username")."</B>, <B>"
                                  .$t->translate("Password")."</B> "
                                  .$t->translate("and")." <B>"
                                  .$t->translate("E-Mail")."</B>!");
                    break;
                }
                // Handles all user contributions to the system
                // so that we don't loose them when changing username
                if ($username != $old_username) {
			// WISH: all the user insertions in the database under the old username
			// should be changed into the new username to avoid loosing them
                }
                // Update user information.
                $permlist = addslashes(implode($perms,","));
                $query = ("update auth_user set username='$username', "
                          ."password='$password', realname='$realname', "
                          ."email_usr='$email_usr', modification_usr=NOW(), "
                          ."perms='$permlist' where user_id='$u_id'");
                $db->query($query);
                if ($db->affected_rows() == 0) {
                    $be->box_full($t->translate("Error"), 
                                  $t->translate("User Change failed")
                                  .":<br>$query");
                    break;
                }
                $bx->box_full($t->translate("User Change"), 
                              $t->translate("User")." <b>$username</b> "
                              .$t->translate("is changed").".<br>");
                break;
                
            case "u_kill":{
                // Delete that user.
                // All contributions of the current user to the system will be made transparent!
                $query = ("delete from auth_user where user_id='$u_id' "
                          ."and username='$username'");
                $db->query($query);
                if ($db->affected_rows() == 0) {
                    $be->box_full($t->translate("Error"), 
                    $t->translate("User Deletion failed").":<br>$query");
                    break;
                }
                $bx->box_full($t->translate("User Deletion"), 
                              $t->translate("User")." <b>$username</b> "
                              .$t->translate("has been deleted"));
                break;
            }
            default:
                break;
        }
    }

/* Output user administration forms, including all updated
   information, if we come here after a submission...
*/


$bx->box_begin();
$bx->box_title($t->translate("User Administration"));
$bx->box_body_begin();
$bx->box_columns_begin(8);

$bx->box_column("center","","",$t->translate("Username"));
$bx->box_column("center","","",$t->translate("Password"));
$bx->box_column("center","","",$t->translate("Realname"));
$bx->box_column("center","","",$t->translate("E-Mail"));
$bx->box_column("center","","",$t->translate("Modification"));
$bx->box_column("center","","",$t->translate("Creation"));
$bx->box_column("center","","",$t->translate("Permission"));
$bx->box_column("center","","",$t->translate("Action"));

$bx->box_next_row_of_columns();

// Create a new user

htmlp_form_action("PHP_SELF",array(),"POST");

$bx->box_column("center","","",html_input_text("username", 12, 32, ""));
$bx->box_column("center","","",html_input_password("password", 12, 32, ""));
$bx->box_column("center","","",html_input_text("realname", 12, 32, ""));
$bx->box_column("center","","",html_input_text("email_usr", 12, 32, ""));
$bx->box_column("center","","","");
$bx->box_column("center","","","");
$bx->box_column("center","","",$perm->perm_sel("perms","devel"));
$bx->box_column("center","","",html_form_submit($t->translate("Create User"),"create"));
htmlp_form_end();

// Traverse the result set
$db->query("SELECT * FROM auth_user ORDER BY username");
while ($db->next_record()) {

    $bx->box_next_row_of_columns();

    htmlp_form_action("PHP_SELF",array(),"POST");
    htmlp_form_hidden("u_id",$db->f("user_id"));
    htmlp_form_hidden("old_username",$db->f("username"));

    $bx->box_column("center","","",html_input_text("username", 12, 32, $db->f("username")));
    $bx->box_column("center","","",html_input_password("password", 12, 32, $db->f("password")));
    $bx->box_column("center","","",html_input_text("realname", 12, 32, $db->f("realname")));
    $bx->box_column("center","","",html_input_text("email_usr", 12, 32, $db->f("email_usr")));
    $bx->box_column("center","","",timestr_short($db->f("creation_usr")));
    $bx->box_column("center","","",timestr_short($db->f("modification_usr")));
    $bx->box_column("center","","",$perm->perm_sel("perms",$db->f("perms")));
    $bx->box_column("center","","",html_form_submit($t->translate("Delete"),"u_kill").html_form_submit($t->translate("Change"),"u_edit"));

    htmlp_form_end();
}

$bx->box_columns_end();
$bx->box_body_end();
$bx->box_end();

/*


<table border="0" cellspacing="0" cellpadding="0" 
     bgcolor="<?php echo $th_box_frame_color;?>" align="center">

<tr>
<td>
<table width="100%" border="0" align="center" cellspacing="1" cellpadding="3">
 <tr bgcolor="<?php echo $th_box_title_bgcolor;?>" valign="top" align="left">
<td>
<B><?php echo $t->translate("User Administration");?></B></td></tr>
</table></td></tr>

<tr>
<td>
<table border="0" align="center" cellspacing="1" cellpadding="3">
 <tr bgcolor="<?php echo $th_box_title_bgcolor;?>" valign="top" align="left">
<?php
  echo "  <th>".$t->translate("Username")."</th>";
  echo "  <th>".$t->translate("Password")."</th>";
  echo "  <th>".$t->translate("Realname")."</th>";
  echo "  <th>".$t->translate("E-Mail")."</th>";
  echo "  <th>".$t->translate("Modification")."</th>";
  echo "  <th>".$t->translate("Creation")."</th>";
  echo "  <th>".$t->translate("Permission")."</th>";
  echo "  <th>".$t->translate("Action")."</th>";
?>
</tr>

<!-- create a new user -->

<form method="post" action="<?php $sess->pself_url() ?>">
<tr bgcolor="<?php echo $th_box_body_bgcolor;?>" valign="middle" align="left">
<td><input type="text" name="username" size="12" maxlength="32" value=""></td>
<td><input type="text" name="password" size="12" maxlength="32" value=""></td>
<td><input type="text" name="realname" size="12" maxlength="64" value=""></td>
<td><input type="text" name="email_usr" size="12" maxlength="128" value="">
</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><?php print $perm->perm_sel("perms","user");?></td>
<?php
  echo "  <td align=\"right\"><input type=\"submit\" name=\"create\" "
       ."value=\"".$t->translate("Create User")."\"></td>\n";
?>
</tr>
</form>
<?php
  ## Traverse the result set
  $db->query("select * from auth_user order by username");
  while ($db->next_record()) {
?>

<!-- existing user -->

<form method="post" action="<?php $sess->pself_url() ?>">
<tr bgcolor="<?php echo $th_box_body_bgcolor;?>" valign="middle" align="left">
<td><input type="text" name="username" size="12" maxlength="32" 
     value="<?php $db->p("username") ?>"></td>
<td><input type="text" name="password" size="12" maxlength="32" 
     value="<?php $db->p("password") ?>"></td>
<td><input type="text" name="realname" size="12" maxlength="64" 
     value="<?php $db->p("realname") ?>"></td>
<td><input type="text" name="email_usr" size="12" maxlength="32" 
     value="<?php $db->p("email_usr") ?>"></td>
<?php
  $time = mktimestamp($db->f("modification_usr"));
  echo "  <td>".timestr($time)."</td>\n";
  $time = mktimestamp($db->f("creation_usr"));
  echo "  <td>".timestr($time)."</td>\n";
?>
<td><?php print $perm->perm_sel("perms", $db->f("perms")) ?></td>
<td align="right">
<input type="hidden" name="u_id" 
     value="<?php echo $db->p("user_id"); ?>">
<input type="hidden" name="old_username" 
     value="<?php echo $db->p("username"); ?>">
<?php
     echo "   <input type=\"submit\" name=\"u_kill\" value=\""
         .$t->translate("Delete")."\">\n";
     echo "   <input type=\"submit\" name=\"u_edit\" value=\""
         .$t->translate("Change")."\">\n";
?>
</td>
</tr>
</form>
<?php
  }
?>
</table>
</td>
</tr>
</table>
<?php
*/
}

end_content();
require("include/footer.inc");
page_close();
?>
