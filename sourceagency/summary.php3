<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
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
#
# $Id: summary.php3,v 1.3 2002/05/06 12:46:35 riessen Exp $
#
######################################################################  

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require("header.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = "summary";

if (check_proid($proid)) {
  top_bar($proid,$page);

  $db->query("SELECT * FROM description WHERE proid='$proid'");
  $db->next_record();
  htmlp_image("ic/a.png",0,48,48,"Summary");
  print '<b>'.$t->translate('Project description').':</b> ';
  $db->p("description");

//  step_information ($db->f("status"));
  summary($proid);

  lib_comment_it($proid,"General","0","0","",
                  $t->translate('General Comments'));
}

end_content();
require("footer.inc");
@page_close();
?>