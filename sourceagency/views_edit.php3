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
require("viewslib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "views_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print $t->translate("Project information access rights configuration").".<p>\n";

  if ( is_not_set_or_empty( $submit ) ) {
      if ( is_set_and_not_empty( $preview ) ) {
          views_preview($proid);
      }
      views_form($proid);
  } else {
      views_insert($proid,$configure,$views,$news,$comments,
                   $history,$step3,$step4,$step5,$cooperation);
  }
}

end_content();
require("footer.inc");
@page_close();
?>