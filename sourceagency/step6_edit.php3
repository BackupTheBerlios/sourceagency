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

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);

?>

<!-- content -->

<?php

$page = "step6_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  if (isset($submit) && !empty($submit)) {
	ratings_insert($proid,$dev_or_spo,$id_number,$auth->auth["uname"]);
	ratings_look_for_next_one($proid,&$id_number);
  }

  if(!isset($dev_or_spo) || empty($dev_or_spo) || empty($id_number) || !isset($id_number)) $id_number = ratings_look_for_first_one($proid);

  print "bla,bla,bla\n";
  if (!isset($finished) && empty($finished)) ratings_form($proid,$dev_or_spo,$id_number);
  else ratings_in_history($proid,$auth->auth["uname"]);
}

?>

<!-- end content -->

<?php
require("footer.inc");
page_close();
?>