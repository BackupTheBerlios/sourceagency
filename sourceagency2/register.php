<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Gregorio Robles (grex@scouts-es.org),
#		 Susanne Gruenbaum (gruenbaum@fokus.fraunhofer.de)
#                Lutz Henckel (lutz.henckel@fokus.fraunhofer.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Registration form for users
# This is the configuration file
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: register.php,v 1.1 2003/11/21 12:55:58 helix Exp $
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
require("include/monitorlib.inc");

$bx = new box("",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$be = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();

// Check if there was a submission
$reg = 0;
while (is_array($HTTP_POST_VARS) 
       && list($key, $val) = each($HTTP_POST_VARS)) {
    switch ($key) {
    case "register": // Register a new user
		$username = trim($username);
		$password = trim($password);
		$cpassword = trim($cpassword);
		$realname = trim($realname);
		$email_usr = trim($email_usr);
        if (empty($username) || empty($password)  
            || empty($cpassword) || empty($email_usr)) { 
            // Do we have all necessary data?
            $be->box_full($t->translate("Error"), 
                           $t->translate("Please enter")." <b>"
                          .$t->translate("Username")."</b>, <b>"
                          .$t->translate("Password")."</b> "
                          .$t->translate("and")." <b>"
                          .$t->translate("E-Mail")."</b>!");
            break;
        }
        if (strcmp($password,$cpassword)) { // password are identical?
            $be->box_full($t->translate("Error"), 
                           $t->translate("The passwords $password vs. $cpassword are not identical")
                           .". ".$t->translate("Please try again")."!");
            break;
        }

        /* Does the user already exist?
           NOTE: This should be a transaction, but it isn't... */
        $db->query("select * from auth_user where username='$username'");
        if ($db->nf()>0) {
            $be->box_full($t->translate("Error"), 
                           $t->translate("User")." <B>$username</B> "
                           .$t->translate("already exists")."!<br>"
                           .$t->translate("Please select a different Username")
                           .".");
            break;
        }
        // Create a uid and insert the user...
        $u_id=md5(uniqid($hash_secret));
        $modification_usr = "NOW()";
        $creation_usr = "NOW()";
        $permlist = $user_type."_pending";
        $query = ("insert into auth_user values('$u_id','$username',"
                  ."'$password','$realname','$email_usr',$modification_usr,"
                  ."$creation_usr,'$permlist')");
        $db->query($query);
        if ($db->affected_rows() == 0) {
            $be->box_full($t->translate("Error"), 
                          $t->translate("Registration of new User failed")
                          .":<br> $query");
            break;
        }
        // send mail
        $message = $t->translate("Thank you for registering on the "
                                 ."$sys_name Site. In order")."\n";
        if ($user_type == "devel") {
            $message = $message
                 .$t->translate("to complete your registration, visit "
                                ."the following URL").": \n\n"
                 .$sys_url."verify.php?confirm_hash=$u_id\n\n"
                 .$t->translate("Enjoy the site").".\n\n"
                 .$t->translate(" -- the $sys_name crew")."\n";
        } else {
            $message = $message
                 .$t->translate("to complete your registration, "
                                ."the $sys_name crew will contact you "
                                ."soon via this email address").".\n\n"
                 .$t->translate(" -- the $sys_name crew")."\n";
        }

        if ($user_type == "devel") {
            mail($email_usr,"[$sys_name] ".
                 $t->translate("Developer Registration"),
                 $message,"From: $ml_newsfromaddr\nReply-To: "
                 ."$ml_newsreplyaddr\nX-Mailer: PHP");

            $msg = $t->translate("Congratulations")."! "
                 .$t->translate("You have registered on $sys_name")."."
                 ."<p>".$t->translate("Your new username is")
                 .": <b>$username</b>"
                 ."<p>".$t->translate("You are now being sent a confirmation "
                                      ."email to verify your email address")
                 ."."
                 ."<br>".$t->translate("Visiting the link sent to you in this "
                                       ."email will activate your account")
                 .".";
        } else {
            mail($email_usr,"[$sys_name] "
                 .$t->translate("Registration as Sponsor in BerliOS "
                                ."SourceAgency"),$message,
                 "From: $ml_newsfromaddr\nReply-To: $ml_newsreplyaddr\n"
                 ."X-Mailer: PHP");

            $msg = $t->translate("Congratulations")."! "
                 .$t->translate("You have registered on $sys_name")."."
                 ."<p>".$t->translate("Your new username is")
                 .": <b>$username</b>"
                 ."<p>".$t->translate("You are now being sent further "
                                      ."information via email").".";
        }

        if ($ml_notify) {
            $message  = $t->translate("Username").": $username\n";
            if ($user_type == "devel") {
                $message .= $t->translate("User type").": "
                     .$t->translate("Developer")."\n";
            } else {
                $message .= $t->translate("User type").": "
                     .$t->translate("Sponsor")."\n";
            }
            $message .= $t->translate("Realname").": $realname\n";
            $message .= $t->translate("E-Mail").":   $email_usr\n";
            mailuser("admin", $t->translate("New User has registered"), 
                     $message);
        }
        $bx->box_full($t->translate("User Registration"), $msg);
        $reg = 1;
        break;
        default:
            break;
    }
}

if (!$reg) {
	$bx->box_begin();
	$bx->box_title($t->translate("Register as a new User"));
	$bx->box_body_begin();
?>
<table border="0" cellspacing="0" cellpadding="3">
<tr>
<form method="post" action="<?php $sess->pself_url() ?>">
<td align="right"><?php echo $t->translate("Username") ?>:</td><td>
<input type="text" name="username" size="20" maxlength="32" value=""></td>
</tr>
<td align="right"><?php echo $t->translate("Type") ?>:</td><td nowrap>
<input type="radio" name="user_type" value="devel" checked><?php echo $t->translate("Developer") ?> 
<input type="radio" name="user_type" value="sponsor"><?php echo $t->translate("Sponsor") ?>
</td>
</tr>
<tr valign="middle" align="left">
<td align="right"><?php echo $t->translate("Password") ?>:</td><td>
<input type="password" name="password" size="20" maxlength="32" value=""></td>
</tr>
<tr valign="middle" align="left">
<td align="right"><?php echo $t->translate("Confirm Password") ?>:</td><td>
<input type="password" name="cpassword" size="20" maxlength="32" value=""></td>
</tr>
<tr valign="middle" align="left">
<td align="right"><?php echo $t->translate("Realname") ?>:</td><td>
<input type="text" name="realname" size="20" maxlength="64" value=""></td>
</tr>
<tr valign="middle" align="left">
<td align="right"><?php echo $t->translate("E-Mail") ?>:</td><td>
<input type="text" name="email_usr" size="20" maxlength="128" value=""></td>
</tr>
<tr valign="middle" align="left">
<td></td>
<td>
<input type="submit" name="register" 
   value="<?php echo $t->translate("Register"); ?>">
</td>
</tr>
</form>
</table>

<?php
	$bx->box_body_end();
	$bx->box_end();
}

end_content();

require("include/footer.inc");
@page_close();
?>
