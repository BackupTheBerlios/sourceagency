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
require ("newslib.inc");

$bx = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$be = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);
$bs = new box("100%",$th_strip_frame_color,$th_strip_frame_width,
              $th_strip_title_bgcolor,$th_strip_title_font_color,
              $th_strip_title_align,$th_strip_body_bgcolor,
              $th_strip_body_font_color,$th_strip_body_align);

start_content();
$page = "news_mod";

if (check_proid($proid)) {
  top_bar($proid,$page);

  print $t->translate("News can be modified by the project owner(s)")
    .".\n<br><p>\n";

  if ((!isset($preview) || empty($preview)) 
      && (!isset($submit) || empty($submit))) {
      $db->query("SELECT * FROM news WHERE subject_news='First Comment!'");
      $db->next_record();
      $subject = $db->f("subject_news");
      $text = $db->f("text_news");
      $creation = $db->f("creation_news");
  }

  if (!isset($submit) || empty($submit)) {
      news_modify_form($proid);
  } else {
      news_modify($proid,"devel",$subject,$text,$creation);
  }
}

end_content();
require("footer.inc");
@page_close();
?>