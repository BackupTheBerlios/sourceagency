<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ================================================
#
# Copyright (c) 2001-2003 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourcewell.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# 
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
require("include/monitorlib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "monitor_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print $t->translate('If you are interested in this project, '
                      .'you can monitor it').".<p>\n";
  
  print "<p>"
        .$t->translate("You will recieve an e-mail for every action in "
        ."this project that has the importance you specify")."."
        ."<ul><li><b>"
        .$t->translate("Low importance")."</b> "
        .$t->translate("means you will receive "
        ."all the events that happen to this project (high "
        ."traffic)")
        ."<li><b>"
        .$t->translate("High importance")."</b> "
        .$t->translate("means you will "
        ."receive only an e-mail whenever a very important "
        ."event happens to this project (low traffic)")
        ."</ul><p>";

  if (!isset($submit) || empty($submit)) {
      if (isset($preview) && !empty($preview)) {
          monitor_preview($proid);
      }
      monitor_form($proid);
  } else {
      monitor_insert($proid,$auth->auth["uname"],$importance);
  }
  
  echo "<p align=right>[ "
       .$t->translate("Have a look at the")
       ."&nbsp;"
       .html_link("monitor.php",array("proid" => $proid),
                 $t->translate("Users that are monitoring this project"))
       ."&nbsp; ]\n";
  
}

end_content();
require("include/footer.inc");
@page_close();
?>
