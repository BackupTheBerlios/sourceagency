<?php

######################################################################
# SourceAgency:
# ================================================
#
# Copyright (c) 2001 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Description
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
require("ratingslib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = "rating";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print ( "The sponsor rates the developers and the developers rates "
          ."the sponsors. Sponsors and developers can rate also sponsor "
          ."and developers that have taken part in the project. Everybody "
          ."is able to make his rating private.\n");

  print ( "<p align=right>[ <b>".html_link("step6_edit.php3",
                                           array("proid" => $proid),
                                           "<b>Rate!</b>")."</b> ]");

  $bx->box_begin();
  $bx->box_title("Project Participants");
  $bx->box_body_begin();
  $bx->box_columns_begin(3);
  $bx->box_column_start("right","33%","");
  show_participants_rating($proid,"sponsor");
  $bx->box_column_finish();
  $bx->box_column_start("right","33%","");
  show_participants_rating($proid,"developer");
  $bx->box_column_finish();
  $bx->box_column_start("right","34%","");
  show_participants_rating($proid,"referee");
  $bx->box_column_finish();
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();

}

end_content();

require("footer.inc");
@page_close();
?>