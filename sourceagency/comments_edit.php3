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
require ("commentslib.inc");

$bx = new box("80%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
?>

<!-- content -->

<?php

$page = "comments_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  if (!isset($type) || empty($type)) $type = "General";
  if (!isset($number) || empty($number)) $number = 0;

  print "Comments can be posted by everybody who is registered.\n";
  print "<br><p>\n";

  if (!isset($submit) || empty($submit)) {
	if (isset($preview) && !empty($preview)) comments_preview($proid);
	comments_form($proid);
  } else {
	comments_insert($proid,$auth->auth["uname"],$type,$number,$ref,$subject,$text);
  }
}

?>

<!-- end content -->

<?php
require("footer.inc");
@page_close();
?>