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
require("followuplib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
?>

<!-- content -->

<?php

$page = "step5_referee";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print "This is the page where referees submit the decision on a given milestone.\n";

  $milestone_number = followup_current_milestone($proid);
  $time = followup_current_time($proid,$milestone_number);
  $location = followup_location($proid,$milestone_number);

  if (!isset($submit) || empty($submit)) {
	if (isset($preview) && !empty($preview)) followup_referee_preview($proid);
	followup_referee_form($proid);
  } else {
	switch ($decision) {
		case "accept":
			followup_insert($proid,$milestone_number,"0","","1");
			break;
		case "light":
			followup_insert($proid,$milestone_number,"0","",$time+1);
			break;
		case "severe":
			followup_insert($proid,$milestone_number,"3",$location,$time);
			break;
	}
  }

}

?>

<!-- end content -->

<?php
require("footer.inc");
page_close();
?>