<?php

######################################################################
# SourceAgency: Software Announcement & Retrieval System
# ================================================
#
# Copyright (c) 2001 by
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

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require("header.inc");
require("monitorlib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "monitor_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print $t->translate('If you are interested in this project, '
                      .'you can monitor it').".\n";
  
  if (!isset($submit) || empty($submit)) {
      if (isset($preview) && !empty($preview)) {
          monitor_preview($proid);
      }
      monitor_form($proid);
  } else {
      monitor_insert($proid,$auth->auth["uname"],$importance);
  }
  

  print $t->translate("<p>"."You will recieve an e-mail for every action in "
                      ."this project that has the importance you specify."
                      ."<br><b>Low importance</b> means you will receive "
                      ."all the events that happen to this project (high "
                      ."traffic).<br><b>High importance</b> means you will "
                      ."receive only an e-mail whenever a very important "
                      ."event happens to this project (low traffic).");

  echo "<p align=right>[ Have a look at the&nbsp;"
      .html_link("monitor.php3",array("proid" => $proid),
                 $t->translate("users that monitor this project"))
    ."&nbsp; ]\n";
  
}

end_content();
require("footer.inc");
@page_close();
?>