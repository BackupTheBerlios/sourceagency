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
require("decisionslib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
?>

<!-- content -->

<?php

$page = "decisions";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print "This is the page where sponsors make their decisions.\n";

  $db->query("SELECT status FROM description WHERE proid='$proid'");
  $db->next_record();
  $project_status = $db->f("status");

  if(isset($Yes) && !empty($Yes) && decisions_decision_met($proid)) {

	$project_status +=1;
	$bx->box_full("<b>The next step has been reached</b>","You are now in step ".$project_status);
	$project_status -=1;

  } else {

	$voted_yet=0;

	if (!strcmp($vote,"vote")) {
		switch($project_status) {
			case '1': $what = "consultant"; $table = "consultants"; break;
			case '2': $what = "content_id"; $table = "tech_content"; break;
			case '3': $what = "devid"; $table = "developing"; break;
			case '4': $what = "referee"; $table = "referees"; break;
		}
		put_decision_into_database($proid,$project_status,$your_vote,$what,$table);
  	}

  	// If the sponsor has already voted, then we look for his vote

  	if(!isset($your_vote) || empty($your_vote)) {
		$db->query("SELECT decision FROM decisions WHERE proid='$proid' AND decision_user='".$auth->auth["uname"]."' AND step='$project_status'");
		$db->next_record();
		$your_vote=$db->f("decision");
  	}

	  switch($project_status) {
		case '1':  $quorum = show_decision_consultants($proid); break;
		case '2':  $quorum = show_decision_contents($proid); break;
		case '3':  $quorum = show_decision_proposals($proid); break;
		case '4':  $quorum = show_decision_referees($proid); break;
		case '5':  echo "<p>"; htmlp_link("step5_sponsor.php3",array("proid" => $proid),"Decision on step 5 (follow-up)"); break;
		case '6':  echo "<p>"; htmlp_link("step6_edit.php3",array("proid" => $proid),"Decision on step 6 (rating)"); break;
	}
  }
  if ($quorum || (isset($Yes) && !empty($Yes))) {
	if ($No || !isset($Yes) || empty($Yes)) are_you_sure_message($proid);
	else {
		switch($project_status) {
			case '1': $what = "consultant"; $table = "consultants"; break;
			case '2': $what = "content_id"; $table = "tech_content"; break;
			case '3': $what = "devid"; $table = "developing"; break;
			case '4': $what = "referee"; $table = "referees"; break;
  		}
		if (decisions_decision_met($proid)) put_into_next_step($proid,$project_status,$what,$table);
	}
  }

  your_quota($proid);
  print "<br>\n";
  you_have_already_voted($proid,$project_status);

  if ($project_status!=4) print "<p align=right>Not voted yet: <b>".((round((100 - $voted_yet)*100))/100)."%</b>\n";
  else print "<p align=right>Not voted yet: <b>".((round((100 - $voted_yet)*100))/50)."%</b>\n";

  print "<br><font size=-1>...Explanation...</font>\n";

  $db->query("SELECT quorum FROM configure WHERE proid='$proid'");
  $db->next_record();

  if ($project_status!=4) print "<p align=right>Decision making: <b>".$db->f("quorum")."%</b>\n";
  else {
	$quorum = $db->f("quorum")/2 +50;
	print "<p align=right>Decision making: <b>".$quorum."%</b>\n";
  }
  print "<br><font size=-1>(quota needed for reaching the next step)</font>\n";
}

?>

<!-- end content -->

<?php
require("footer.inc");
page_close();
?>