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
# Editing news
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
require ("cooperationlib.inc");

$bx = new box("80%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);

?>

<!-- content -->

<?php

$page = "cooperation_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print "Developers can cooperate in developments if other developers require it.\n";
  print "<br><p>\n";

  if (!isset($submit) || empty($submit)) {
	if (isset($preview) && !empty($preview)) cooperation_preview($proid,$devid);
	cooperation_form($proid,$devid);
  } else {
	cooperation_insert($devid,$auth->auth["uname"],$cost);
  }

}

?>

<!-- end content -->

<?php
require("footer.inc");
@page_close();
?>