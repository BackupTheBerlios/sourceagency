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
# TODO: description missing
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: configure_edit.php3,v 1.2 2001/11/12 13:00:05 riessen Exp $
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
require("configurelib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = "configure_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  // NOI18N
  print "Project configuration form.\n";

  if ( is_not_set_or_empty( $submit ) ) {
    if ( is_set_and_not_empty( $preview ) ) {
      configure_preview($proid);
    } 
    configure_form($proid);
  } else {
    configure_insert($proid,$auth->auth["uname"]);
  }
  
}

end_content();
require("footer.inc");
@page_close();
?>