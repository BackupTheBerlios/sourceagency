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
# Editing news
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
require("include/newslib.inc");

$bx = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "news_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print $t->translate('News can be posted by the project owner(s)')
    .".\n<br><p>\n";

  if (!isset($submit) || empty($submit)) {
      if (isset($preview) && !empty($preview)) {
          news_preview($proid);
      }
      newsform($proid);
  } else {
      news_insert($proid,$auth->auth["uname"],$subject,$text);
  }
  
}

end_content();
require("include/footer.inc");
@page_close();
?>
