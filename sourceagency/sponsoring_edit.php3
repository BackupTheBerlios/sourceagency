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
# Editing sponsoring involvements is possible here
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
require ("sponsoringlib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
?>

<!-- content -->

<?php

$page = "sponsoring_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print "Sponsors can modify their sponsoring wish using this form.\n";
  print "<br><p>\n";

  if (!isset($submit) || empty($submit)) {
	if (isset($preview) && !empty($preview)) sponsoring_preview($proid);
	else {
	 	if (!is_project_initiator($proid)) {
			require("configurelib.inc");
			configure_show($proid);
			print "<br><p>\n";
		}
	}
	sponsoring_form($proid);
  } else {
	sponsoring_insert($proid,$auth->auth["uname"],$sponsoring_text,$budget,$valid_day,$valid_month,$valid_year,$begin_day,$begin_month,$begin_year,$finish_day,$finish_month,$finish_year);
  }

}

?>

<!-- end content -->

<?php
require("footer.inc");
@page_close();
?>