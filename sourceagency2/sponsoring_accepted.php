<?php

######################################################################
# SourceAgency:
# ================================================
#
# Copyright (c) 2001-2003 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the index file which shows the recent apps
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
require("include/sponsoringlib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "sponsoring_accepted";

if (check_proid($proid)) {
  top_bar($proid,$page);

  $db->query("SELECT creation FROM sponsoring WHERE proid='$proid' "
             ."AND sponsor='$sponsor'");
  $db->next_record();
  $creation = $db->f("creation");

  $db->query("UPDATE sponsoring SET status='A',creation='$creation' "
             ."WHERE proid='$proid' AND sponsor='$sponsor'");

  if ($db->affected_rows()) {
	print ( $t->translate('Sponsoring involvement by')
                 ." <b>$sponsor</b> "
                 .$t->translate('has been accepted')
                 .".<p>\n" );
  } else {
	print $t->translate('There has been a database error').".<p>\n";
  }

  htmlp_link("sponsoring.php",array("proid" => $proid),
             $t->translate("Back to the Sponsoring Involvement Page"));

  // Send mail to the ones that monitor this project
  include("monitorlib.inc");
  include("config.inc");
  monitor_mail($proid, "sponsoring", 
               $t->translate('Sponsoring involvement accepted for project')
               ." $proid", $t->translate('An event has happened').".");

  // mail new sponsor that he has been accepted
  $db->query("SELECT email_usr FROM auth_user WHERE username='$sponsor'");
  $db->next_record();
  $message = ("\n".$t->translate("Hi").",$sponsor\n\n"
              .$t->translate("Your sponsoring involvement for project")
              ." $proid "
              .$t->translate("has been accepted").".\n\n");

  mail($db->f("email_usr"),"[".$GLOBALS["sys_name"]
  ."] ".$t->translate("Sponsoring involvement accepted"), $message,"From: "
  .$GLOBALS["ml_fromaddr"]."\nReply-To: "
  .$GLOBALS["ml_replyaddr"]."\nX-Mailer: PHP");  

  // Insert into history
  $db->query("INSERT history SET proid='$proid', history_user='"
             .$auth->auth["uname"]."', type='sponsoring', "
             ."action='Sponsor $sponsor accepted as sponsor'");
  
}

end_content();
require("include/footer.inc");
@page_close();
?>
