<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ================================================
#
# Copyright (c) 2001-2003 by
#                Lutz Henckel (lutz.henckel@fokus.fraunhofer.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourcewell.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This file sends the forgotten password to the user
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
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

$bx = new box("",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$be = new box("",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();
$db->query("SELECT * FROM auth_user WHERE username='$username' AND "
           ."email_usr='$email_usr'");

if ($db->num_rows() > 0) {
  $db->next_record();

  $message = $t->translate("Your Username and Password for $sys_name is")
       .":\n\n"
       ."        ".$t->translate("User").": ".$db->f("username")."\n"
       ."        ".$t->translate("Password").": ".$db->f("password")."\n\n"
       .$t->translate("Please keep this e-mail for further reference").".\n\n"
       .$t->translate(" -- the $sys_name crew")."\n";

  mail($email_usr, "[$sys_name] ".$t->translate("Remind me"), $message,
  "From: $ml_newsfromaddr\nReply-To: $ml_newsreplyaddr\nX-Mailer: PHP");
  $bx->box_full($t->translate("Remind me"), 
                $t->translate("You will get your Password by e-mail in "
                              ."a couple of minutes"));
} else {
    $be->box_full($t->translate("Error"), 
                  $t->translate("Either your Username or E-Mail Address "
                                ."is unknown").".<br>"
                                .$t->translate("Please try again")."!");
}
end_content();
require("include/footer.inc");
page_close();
?>
