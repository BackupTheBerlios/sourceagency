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
require("decisionslib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
?>

<!-- content -->

<?php

$page = "step5_sponsor";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print "This is the page where sponsors accept or reject milestones.\n";

  $db->query("SELECT quorum FROM configure WHERE proid='$proid'");
  $db->next_record();
  $decision_value = $db->f("quorum");

  $milestone_number = followup_current_milestone($proid);
  $time = followup_current_time($proid,$milestone_number);
  $location = followup_location($proid,$milestone_number);

  if(isset($Yes) && !empty($Yes)) {

	$bx->box_full("<b>A decision has been made</b>","And this is the decision: FIXME");

  } else {

	$voted_yet=0;

	if (!strcmp($vote,"vote")) {
		put_decision_step5_into_database($proid,$decision,$milestone_number);
  	}

  	// If the sponsor has already voted, then we look for his vote

  	if(!isset($decision) || empty($decision)) {
		$db->query("SELECT decision FROM decisions_step5 WHERE proid='$proid' AND decision_user='".$auth->auth["uname"]."' AND number='$milestone_number'");
		$db->next_record();
		$decision=$db->f("decision");
  	}

	$quorum = show_decision_step5($proid,$milestone_number);
  }
  if ($quorum || (isset($Yes) && !empty($Yes))) {
	if ($No || !isset($Yes) || empty($Yes)) are_you_sure_message_step5($proid);
	else decisions_step5_sponsors($proid,$milestone_number,$decision_value);
  }

/*
  your_quota($proid);
  print "<br>\n";
  you_have_already_voted($proid,$project_status);

  print "<p align=right>Not voted yet: <b>".((round((100 - $voted_yet)*100))/100)."%</b>\n";
  print "<br><font size=-1>Explanation</font>\n";
  print "<p align=right>Decision making: <b>$decision_value</b>%\n";
  print "<br><font size=-1>(quota needed for a decision)</font>\n";
*/
}

?>

<!-- end content -->

<?php
require("footer.inc");
page_close();
?>