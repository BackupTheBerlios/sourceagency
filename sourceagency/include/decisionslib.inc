<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# TODO: Description is missing 
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: decisionslib.inc,v 1.4 2001/11/08 16:17:41 riessen Exp $
#
######################################################################

/* FIXME: the colors have to be changed into a configurable colors */

#####
#
# function show_decision_consultants($proid)
#
# shows the consultants that can be elected
# returns '1' if one of the consultants has sufficient quorum
# else returns '0'
#
#####

function show_decision_consultants($proid) {
  global $t, $bx, $db, $voted_yet, $your_vote;

  $db->query("SELECT quorum FROM configure WHERE proid='$proid'");
  $db->next_record();
  $decision_value = $db->f("quorum");

  $return_value = 0;
  $flag = 0; // $flag is one <=> the project is configured to not have consultants

  print "<p align=right>You can only vote for one of them\n";

  $total_budget = project_budget($proid);

  $db->query("SELECT consultants FROM configure WHERE proid='$proid'");
  $db->next_record();
  if ($db->f("consultants") == "No") {
	$flag = 1;
	$return_value = 100;
  }

  $query = "SELECT * FROM consultants,auth_user WHERE proid='$proid' AND consultant=username ORDER BY creation";
  $db->query($query);

  if ($db->num_rows() != 0) {
	$bx->box_begin();
	$bx->box_title($t->translate("Consultants"));
	$bx->box_body_begin();
	$bx->box_columns_begin(6);

	$bx->box_column("","","","<b>Number</b>");
	$bx->box_column("","","","<b>Username</b>");
	$bx->box_column("","","","<b>Status</b>");
	$bx->box_column("","","","<b>Creation</b>");
	$bx->box_column("center","","","<b>Percentage</b>");
	$bx->box_column("center","","","<b>Your vote</b>");

	$i =0;

	htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

	while($db->next_record()) {
		$i++;
		if ($i%2 != 0) $bgcolor = "gold";
		else $bgcolor = "#FFFFFF";

		$db_consultant = new DB_SourceAgency;
		$db_consultant->query("SELECT SUM(budget) AS sum_consultant FROM sponsoring,decisions WHERE decisions.proid='$proid' AND sponsoring.proid='$proid' AND sponsoring.sponsor=decisions.decision_user AND step='1' AND decision='".$db->f("consultant")."' GROUP BY decision");
		$db_consultant->next_record();

		$bx->box_next_row_of_columns();

		$bx->box_column("","",$bgcolor,"<b>$i</b>");
		$bx->box_column("","",$bgcolor,"<b>".lib_nick($db->f("consultant"))."</b>");
		$bx->box_column("","",$bgcolor,"<b>".show_status($db->f("status"))."</b>");
		$bx->box_column("","",$bgcolor,"<b>".timestr(mktimestamp($db->f("creation")))."</b>");
		$voted_yet += ($db_consultant->f("sum_consultant")/$total_budget)*100;

		$voting = ((round(($db_consultant->f("sum_consultant")/$total_budget)*10000))/100);
		$bx->box_column("center","",$bgcolor,"<b>".$voting."%</b>");

		if ($voting > $decision_value) $return_value = 1;

		if (!strcmp($your_vote,$db->f("consultant"))) $checked = "checked";
		else $checked = "";
		$bx->box_column("center","",$bgcolor,html_radio("your_vote",$db->f("consultant"),$checked));
	}

	$bx->box_next_row_of_columns();
	$bx->box_colspan (6,"right","white",html_form_submit("vote","vote"));

	$bx->box_columns_end();
	$bx->box_body_end();
	$bx->box_end();
  }

  if ($flag) {
	print "<p><b>This project is configured to have no consultants.</b>\n";
	print "<br>You can reach the next step whenever you want.<p>\n";
  } elseif ($db->num_rows() == 0) {
	print "<p><b>No decision possible as there are no offerings.</b><p>\n";
  }
  return $return_value;
}


function show_decision_contents($proid) {
  global $t, $bx, $db, $voted_yet, $your_vote;

  $db->query("SELECT quorum FROM configure WHERE proid='$proid'");
  $db->next_record();
  $decision_value = $db->f("quorum");

  $return_value = 0;

  print "<p align=right>You can only vote for one of them\n";

  $total_budget = project_budget($proid);

  $query = "SELECT * FROM tech_content,auth_user WHERE proid='$proid' AND content_user=username ORDER BY creation";
  $db->query($query);

  if ($db->num_rows() != 0) {
	$bx->box_begin();
	$bx->box_title($t->translate("Proposed contents"));
	$bx->box_body_begin();
	$bx->box_columns_begin(8);

	$bx->box_column("","","","<b>Number</b>");
	$bx->box_column("","","","<b>Platform</b>");
	$bx->box_column("","","","<b>Architecture</b>");
	$bx->box_column("","","","<b>Environment</b>");
	$bx->box_column("","","","<b>Specification</b>");
	$bx->box_column("","","","<b>Creation</b>");
	$bx->box_column("center","","","<b>Percentage</b>");
	$bx->box_column("center","","","<b>Your vote</b>");

	$i =0;

	htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

	while($db->next_record()) {
		$i++;
		if ($i%2 != 0) $bgcolor = "gold";
		else $bgcolor = "#FFFFFF";

		$db_content = new DB_SourceAgency;
		$db_content->query("SELECT SUM(budget) AS sum_content FROM sponsoring,decisions WHERE decisions.proid='$proid' AND sponsoring.proid='$proid' AND sponsoring.sponsor=decisions.decision_user AND decision='".$db->f("content_id")."' AND step='2' GROUP BY decision");
		$db_content->next_record();

		$bx->box_next_row_of_columns();

		$bx->box_column("","",$bgcolor,"<b>".$db->f("content_id")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".$db->f("platform")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".$db->f("architecture")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".$db->f("environment")."</b>");
		$bx->box_column("","",$bgcolor,"<b>pop up</b>");
		$bx->box_column("","",$bgcolor,"<b>".timestr_short(mktimestamp($db->f("creation")))."</b>");
		$voted_yet += ($db_content->f("sum_content")/$total_budget)*100;

		$voting = ((round(($db_content->f("sum_content")/$total_budget)*10000))/100);
		$bx->box_column("center","",$bgcolor,"<b>".$voting."%</b>");

		if ($voting > $decision_value) $return_value = 1;

		if (!strcmp($your_vote,$db->f("content_id"))) $checked = "checked";
		else $checked = "";
		$bx->box_column("center","",$bgcolor,html_radio("your_vote",$db->f("content_id"),$checked));
	}

	$bx->box_next_row_of_columns();
	$bx->box_colspan (6,"right","white",html_form_submit("vote","vote"));

	$bx->box_columns_end();
	$bx->box_body_end();
	$bx->box_end();
  }

  if ($db->num_rows() == 0) {
	print "<p>There have not been posted any technical specification suggestion.\n";
  }
  return $return_value;
}


function show_decision_proposals($proid) {
  global $t, $bx, $db, $voted_yet, $your_vote;

  $db->query("SELECT quorum FROM configure WHERE proid='$proid'");
  $db->next_record();
  $decision_value = $db->f("quorum");

  print "<p align=right>You can only vote for one of them\n";
  print "<p align=right>You can only vote those proposals that have all their milestones accepted by you";

  $return_value = 0;

  $total_budget = project_budget($proid);

  $query = "SELECT * FROM tech_content,developing,auth_user WHERE tech_content.proid='$proid' AND developer=username AND tech_content.content_id=developing.content_id AND tech_content.status='A' ORDER BY developing.creation";
  $db->query($query);

  if ($db->num_rows() != 0) {
	$bx->box_begin();
	$bx->box_title($t->translate("Suggested proposals"));
	$bx->box_body_begin();
	$bx->box_columns_begin(10);

	$bx->box_column("","","","<b>Number</b>");
	$bx->box_column("","","","<b>Developer</b>");
	$bx->box_column("","","","<b>License</b>");
	$bx->box_column("","","","<b>Cost</b>");
	$bx->box_column("","","","<b>&nbsp;</b>");
	$bx->box_column("","","","<b>Accepted</b>");
	$bx->box_column("","","","<b>Start</b>");
	$bx->box_column("","","","<b>Duration</b>");
	$bx->box_column("","","","<b>Valid</b>");
	$bx->box_column("","","","<b>Creation</b>");
	$bx->box_column("center","","","<b>Percentage</b>");
	$bx->box_column("center","","","<b>Your vote</b>");

	$i =0;

	htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

	while($db->next_record()) {
		$i++;
		if ($i%2 != 0) $bgcolor = "gold";
		else $bgcolor = "#FFFFFF";

		$db_content = new DB_SourceAgency;
		$db_content->query("SELECT SUM(budget) AS sum_content FROM sponsoring,decisions WHERE decisions.proid='$proid' AND sponsoring.proid='$proid' AND sponsoring.sponsor=decisions.decision_user AND decision='".$db->f("devid")."' AND step='3' GROUP BY decision");
		$db_content->next_record();

		$bx->box_next_row_of_columns();

		$bx->box_column("","",$bgcolor,"<b>".$db->f("devid")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".$db->f("developer")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".$db->f("license")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".$db->f("cost")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".html_link("decisions_milestones.php3",array("proid" => $proid, "devid" => $db->f("devid")),"Milestones")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".decision_accepted_milestones($proid,$db->f("devid"))."%</b>");
		$bx->box_column("","",$bgcolor,"<b>".timestr_shortest(mktimestamp($db->f("start")))."</b>");
		$bx->box_column("","",$bgcolor,"<b>".$db->f("duration")." Weeks</b>");
		$bx->box_column("","",$bgcolor,"<b>".timestr_shortest(mktimestamp($db->f("valid")))."</b>");
		$bx->box_column("","",$bgcolor,"<b>".timestr_comment(mktimestamp($db->f("creation")))."</b>");
		$voted_yet += ($db_content->f("sum_content")/$total_budget)*100;

		$voting = ((round(($db_content->f("sum_content")/$total_budget)*10000))/100);
		$bx->box_column("center","",$bgcolor,"<b>".$voting."%</b>");

		if ($voting > $decision_value) $return_value = 1;

		if (decision_accepted_milestones($proid,$db->f("devid")) == "100") {
			if (!strcmp($your_vote,$db->f("devid"))) $checked = "checked";
			else $checked = "";
			$bx->box_column("center","",$bgcolor,html_radio("your_vote",$db->f("devid"),$checked));
		} else $bx->box_column("center","",$bgcolor,"");
	}

	$bx->box_next_row_of_columns();
	$bx->box_colspan (12,"right","right",html_form_submit("vote","vote"));

	$bx->box_columns_end();
	$bx->box_body_end();
	$bx->box_end();
  }

  if ($db->num_rows() == 0) {
	print "<p>There have not been posted any milestones by the project main developer.\n";
  }
  return $return_value;
}


function show_decision_milestones($proid,$devid) {
  global $t, $bx, $db, $voted_yet, $your_vote;

  $db->query("SELECT quorum FROM configure WHERE proid='$proid'");
  $db->next_record();
  $decision_value = $db->f("quorum");

  $return_value = 0;

  $total_budget = project_budget($proid);

  print "<p align=right>You can accept all of them if you want\n";

  $query = "SELECT * FROM milestones WHERE proid='$proid' AND devid='$devid' ORDER BY number";
  $db->query($query);

  if ($db->num_rows() != 0) {
	$bx->box_begin();
	$bx->box_title($t->translate("Milestones proposed by ".$milestone_user)." (FIXME: Link to proposal)");
	$bx->box_body_begin();
	$bx->box_columns_begin(8);

	$bx->box_column("center","7%","","<b>Number</b>");
	$bx->box_column("","43%","","<b>Goals</b>");
	$bx->box_column("center","16%","","<b>Release Date</b>");
	$bx->box_column("center","12%","","<b>Product</b>");
	$bx->box_column("center","8%","","<b>Payment</b>");
	$bx->box_column("center","14%","","<b>Status</b>");
	$bx->box_column("center","5%","","<b>Percentage</b>");
	$bx->box_column("center","5%","","<b>Your vote</b>");

	$i =0;

	htmlp_form_action("PHP_SELF",array("proid" => $proid, "devid" => $devid),"POST");

	while($db->next_record()) {
		$i++;
		if ($i%2 != 0) $bgcolor = "gold";
		else $bgcolor = "#FFFFFF";

		$bx->box_next_row_of_columns();

		$db_milestones = new DB_SourceAgency;
		$db_milestones->query("SELECT SUM(budget) AS sum_milestone FROM sponsoring,decisions_milestones WHERE decisions_milestones.proid='$proid' AND sponsoring.proid='$proid' AND decisions_milestones.devid='$devid' AND sponsoring.sponsor=decisions_milestones.decision_user AND number='".$db->f("number")."' AND decision='Yes' GROUP BY decision");
		$db_milestones->next_record();

		$milestone_number = $db->f("number");

		$bx->box_column("","",$bgcolor,"<b>".$db->f("number")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".$db->f("goals")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".timestr_middle(mktimestamp($db->f("release")))."</b>");
		$bx->box_column("","",$bgcolor,"<b>".$db->f("product")."</b>");
		$bx->box_column("","",$bgcolor,"<b>".$db->f("payment")."%</b>");

		$voted_yet += ($db_milestones->f("sum_milestone")/$total_budget)*100;

		$voting = ((round(($db_milestones->f("sum_milestone")/$total_budget)*10000))/100);

		if ($voting > $decision_value) { 
			$bx->box_column("center","",$bgcolor,"<b>Accepted</b>");
			if ($db->f("status") != "A") decisions_milestone_into_db($proid,$devid,$db->f("number"),"A");
		} else {
			$bx->box_column("center","",$bgcolor,"<b>Proposed</b>");
			if ($db->f("status") != "P") decisions_milestone_into_db($proid,$devid,$db->f("number"),"P");
		}
		$bx->box_column("center","",$bgcolor,"<b>".$voting."%</b>");

		if (!strcmp($GLOBALS["milestone_".$milestone_number],"Yes")) $checked = "checked";
		else $checked = "";
		$bx->box_column("center","",$bgcolor,html_checkbox("milestone_".$milestone_number,"Yes",$checked));
	}

	$bx->box_next_row_of_columns();
	$bx->box_colspan (8,"right","white",html_form_submit("vote","vote"));

	$bx->box_columns_end();
	$bx->box_body_end();
	$bx->box_end();
  }

  if ($db->num_rows() == 0) {
	print "<p><b>No decision possible as there are no offerings.</b><p>\n";
  }
  return $return_value;
}


function show_decision_referees($proid) {
  global $t, $bx, $db, $voted_yet, $your_vote;


  $db->query("SELECT quorum FROM configure WHERE proid='$proid'");
  $db->next_record();
  $decision_value = $db->f("quorum")/2 + 50;

  $return_value = 0;

  print "<p align=right>You can only vote for one of them\n";

  $total_budget = project_budget($proid);

  $query = "SELECT * FROM referees,auth_user WHERE proid='$proid' AND referee=username ORDER BY creation";
  $db->query($query);

  if ($db->num_rows() != 0) {
	$bx->box_begin();
	$bx->box_title($t->translate("Referees"));
	$bx->box_body_begin();
	$bx->box_columns_begin(6);

	$bx->box_column("","","","<b>Number</b>");
	$bx->box_column("","","","<b>Username</b>");
	$bx->box_column("","","","<b>Status</b>");
	$bx->box_column("","","","<b>Creation</b>");
	$bx->box_column("center","","","<b>Percentage</b>");
	$bx->box_column("center","","","<b>Your vote</b>");

	$i =0;

	htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

	while($db->next_record()) {
		$i++;
		if ($i%2 != 0) $bgcolor = "gold";
		else $bgcolor = "#FFFFFF";

		$db_referee = new DB_SourceAgency;
		$db_referee->query("SELECT SUM(budget) AS sum_referee FROM sponsoring,decisions WHERE decisions.proid='$proid' AND sponsoring.proid='$proid' AND sponsoring.sponsor=decisions.decision_user AND decision='".$db->f("referee")."' AND step='4' GROUP BY decision");
		$db_referee->next_record();

		$bx->box_next_row_of_columns();

		$bx->box_column("","",$bgcolor,"<b>$i</b>");
		$bx->box_column("","",$bgcolor,"<b>".lib_nick($db->f("username"))."</b>");
		$bx->box_column("","",$bgcolor,"<b>".show_status($db->f("status"))."</b>");
		$bx->box_column("","",$bgcolor,"<b>".timestr(mktimestamp($db->f("creation")))."</b>");

		if (decision_developer_voted($proid,$db->f("username"))) {
			$voted_yet += ($db_referee->f("sum_referee")/$total_budget)*100 + 50;
			$voting = ((round(($db_referee->f("sum_referee")/$total_budget)*10000))/200) + 50;
		} else {
			$voted_yet += ($db_referee->f("sum_referee")/$total_budget)*100;
			$voting = ((round(($db_referee->f("sum_referee")/$total_budget)*10000))/200);
		}

		$bx->box_column("center","",$bgcolor,"<b>".$voting."%</b>");

		if ($voting > $decision_value) $return_value = 1;

		if (!strcmp($your_vote,$db->f("referee"))) $checked = "checked";
		else $checked = "";
		$bx->box_column("center","",$bgcolor,html_radio("your_vote",$db->f("referee"),$checked));
	}

	$bx->box_next_row_of_columns();
	$bx->box_colspan (6,"right","white",html_form_submit("vote","vote"));

	$bx->box_columns_end();
	$bx->box_body_end();
	$bx->box_end();
  }

  if ($db->num_rows() == 0) {
	print "<p>There have not been posted any milestones by the project main developer.\n";
  }
  return $return_value;
}


#####
#
# function show_decision_step5($proid,$milestone_number)
#
# returns '1' if one if the decision has sufficient quorum
# else returns '0'
#
#####

function show_decision_step5($proid,$milestone_number,$time) {
  global $t, $bx, $db, $voted_yet, $decision;

  $db->query("SELECT quorum FROM configure WHERE proid='$proid'");
  $db->next_record();
  $decision_value = $db->f("quorum");

  $return_value = 0;
  $total_budget = project_budget($proid);

  $db->query("SELECT * FROM milestones WHERE proid='$proid' AND number='$milestone_number'");
  $db->next_record();

  $bx->box_begin();
  $bx->box_title("Decision on milestone number ".$milestone_number);
  $bx->box_body_begin();
  $bx->box_columns_begin(2);

  $bx->box_column("right","40%","","<b>Release date</b>:");
  $bx->box_column("left","","",timestr_middle(mktimestamp($db->f("release"))));

  $bx->box_next_row_of_columns();

  $bx->box_column("right","","","<b>Product</b>:");
  $bx->box_column("left","","",$db->f("product"));

  $bx->box_next_row_of_columns();

  $bx->box_column("right","","","<b>Payment</b>:");
  $bx->box_column("left","","",$db->f("payment")."%");

  $bx->box_next_row_of_columns();

  $bx->box_column("right","","","<b>Goals</b>");
  $bx->box_column("left","","",$db->f("goals"));

  $bx->box_next_row_of_columns();

  $voting=decisions_step5_votes($proid,$milestone_number,$time,"accept");
  $bx->box_column("right","","","<b>Accepted</b>");
  $bx->box_column("left","","",$voting."%");

  $bx->box_next_row_of_columns();

  $voting+=decisions_step5_votes($proid,$milestone_number,$time,"light");
  $bx->box_column("right","","","<b>Light modifications should be done</b>:");
  $bx->box_column("left","","",decisions_step5_votes($proid,$milestone_number,$time,"light")."%");

  $bx->box_next_row_of_columns();

  $voting+=decisions_step5_votes($proid,$milestone_number,$time,"severe");
  $bx->box_column("right","","","<b>Severe modifications should be done</b>:");
  $bx->box_column("left","","",decisions_step5_votes($proid,$milestone_number,$time,"severe")."%");

  if ($voting > $decision_value) $return_value =1;

  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Decision")."</b>: ");
  $bx->box_column ("left","70%","",followup_referee_decision($decision));

  $bx->box_next_row_of_columns();
  $bx->box_colspan (2,"center","white",html_form_submit("vote","vote"));
  htmlp_form_end();

  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();

  return $return_value;
}

function project_budget($proid) {
  global $db;

  $query = "SELECT SUM(budget) FROM sponsoring WHERE proid='$proid' AND status='A'";
  $db->query($query);
  $db->next_record();
  return $db->f("SUM(budget)");
}

function your_quota($proid) {
  global $db, $auth;

  $query = "SELECT budget FROM sponsoring WHERE proid='$proid' AND status='A' AND sponsor='".$auth->auth["uname"]."'";
  $db->query($query);
  $db->next_record();

  print "<p>Your quota: <b>".$db->f("budget")."</b> euros (<b>".(round(($db->f("budget")/project_budget($proid))*10000)/100)."%</b> of the total project budget)\n";
}

function put_decision_into_database($proid,$step,$your_vote,$what,$table) {
	global $db,$auth;

	$db->query("SELECT DISTINCT($what) FROM $table");
	while ($db->next_record()) {
		if (!strcmp($your_vote,$db->f($what))) { 
			$db->query("SELECT * FROM decisions WHERE proid='$proid' AND step = '$step' AND decision_user='".$auth->auth["uname"]."'");
			if ($db->num_rows() == 0) $db->query("INSERT decisions SET proid='$proid', step ='$step', decision_user='".$auth->auth["uname"]."', decision='$your_vote'"); 
			else {
				$db->next_record();
				if(!strcmp($your_vote,$db->f("decision"))) print "You have already voted that guy\n";
				else {
					$db->query("UPDATE decisions SET  decision='$your_vote' WHERE proid='$proid' AND step='$step' AND decision_user='".$auth->auth["uname"]."'");
					print "Vote changed. Old: <b>".$db->f("decision")."</b> New: <b>".$your_vote."</b>";
				}
			}
		}
	}

  include("monitorlib.inc");
  include("config.inc");
  monitor_mail($proid,"decisions", "Decision", "Decision made in project $proid\n Sponsor ".$auth->auth["uname"]." has made a decision (step $step)\n\n You can see it at: $sys_urldecisions.php3?proid=$proid");
}

function put_into_next_step($proid,$project_status,$what,$table) {

  $db_local = new DB_SourceAgency;
  $db_quota = new DB_SourceAgency;

  $db_local->query("SELECT $what,creation FROM $table WHERE proid='$proid'");
  while ($db_local->next_record()) {
	$db_quota->query("SELECT SUM(budget) FROM sponsoring,decisions WHERE sponsor=decision_user AND decision='".$db_local->f($what)."' AND sponsoring.proid='$proid' AND decisions.proid='$proid' AND step='$project_status'");
	$db_quota->next_record();
	if ($db_quota->f("SUM(budget)") > project_budget($proid)/2) { 
		$db_quota->query("UPDATE $table SET status='A', creation='".$db_local->f("creation")."' WHERE $what='".$db_local->f($what)."' AND proid='$proid'");
	} else {
		$db_quota->query("UPDATE $table SET status='R', creation='".$db_local->f("creation")."' WHERE $what='".$db_local->f($what)."' AND proid='$proid'");
	}
  }

  $db_local->query("SELECT status,description_creation FROM description WHERE proid='$proid'");
  $db_local->next_record();
  $project_status = $db_local->f("status") + 1;

  $db_local->query("UPDATE description SET status='$project_status',description_creation='".$db_local->f("description_creation")."' WHERE proid='$proid'");

  $db_local->query("INSERT history SET proid='$proid', history_user='Project Owners', type='decision', action='Project is now in phase $project_status'");

	// If sponsored project and new step is 4
	// we have to introduce the main developer in the configure table!

  if ($project_status == 4) decision_insert_main_developer($proid);

  include("monitorlib.inc");
  include("config.inc");
  monitor_mail($proid,"next_step", "Next Step for project $proid", "The next step on project $proid has been reached\n\n");

}

function you_have_already_voted($proid,$step) {
  global $db, $auth;

  $db->query("SELECT * FROM decisions WHERE proid='$proid' AND step='$step' AND decision_user='".$auth->auth["uname"]."'");
  if ($db->num_rows() == 1) print "You <b>have already</b> voted in this step.\n";
  else print "You have <b>not voted</b> in this step yet.\n";
}

function are_you_sure_message($proid) {
  global $bx, $t;

	$bx->box_begin();
 	$bx->box_title("<b>".$t->translate("Warning! The next step has been reached")."</b>");
	$bx->box_body_begin();
	$bx->box_columns_begin(2);

	htmlp_form_action("PHP_SELF", array("proid" => $proid), "POST");
	$bx->box_column("left","70%","",$t->translate("Are you sure you want to put the project into the next step?<br>Press <b>Yes</b> to put into the next step and <b>No</b> to stay in the current one."));
	$bx->box_column("right","30%","",html_form_submit("Yes","Yes").html_form_submit("No","No"));
	htmlp_form_end();	
	$bx->box_columns_end();
	$bx->box_body_end();
	$bx->box_end();
}

function decision_accepted_milestones($proid,$devid) {
 	global $auth;

	$db_local = new DB_SourceAgency;
	$db_local2 = new DB_SourceAgency;

	$total = 0;
	$db_local->query("SELECT * FROM sponsoring WHERE proid='$proid'");
	$number_of_sponsors = $db_local->num_rows();

	$db_local->query("SELECT number,payment FROM milestones WHERE proid='$proid' AND devid='$devid'");
	while ($db_local->next_record()) {
		$db_local2->query("SELECT COUNT(*) FROM decisions_milestones WHERE proid='$proid' AND devid='$devid' AND number='".$db_local->f("number")."' AND decision='Yes' AND decision_user='".$auth->auth["uname"]."'");
		$db_local2->next_record();
		$total +=  $db_local2->f("COUNT(*)") * $db_local->f("payment");
	}

	if ($total != 0) return (round(($total*100))/100);
	else return 0;
}

function decision_milestone_insert($proid,$devid,$decision_user,$number,$decision) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT * FROM decisions_milestones WHERE proid='$proid' AND devid='$devid' AND decision_user='$decision_user' AND number='$number'");
	if ($db_local->affected_rows() == 0) {
		$db_local->query("INSERT decisions_milestones SET proid='$proid', devid='$devid', decision_user='$decision_user', number='$number', decision='$decision'");
	} else {
		$db_local->query("UPDATE decisions_milestones SET decision='$decision' WHERE proid='$proid' AND devid='$devid' AND decision_user='$decision_user' AND number='$number'");
	}

/*
  	include("monitorlib.inc");
	include("config.inc");
	monitor_mail($proid,"decision_milestone", "Decision met on a Milestone", "Decision met on a Milestone in project $proid\n By sponsor $decision_user\n\n You can see it at: $sys_urldecisions.php3?proid=$proid");
*/
}

function put_decision_step5_into_database($proid,$your_vote,$milestone_number,$time) {
	global $db,$auth;

	$db->query("SELECT * FROM decisions_step5 WHERE proid='$proid' AND decision_user='".$auth->auth["uname"]."' AND number='$milestone_number' AND time='$time'");

	if ($db->num_rows() == 0) $db->query("INSERT decisions_step5 SET proid='$proid', decision_user='".$auth->auth["uname"]."', number='$milestone_number', time='$time', decision='$your_vote'"); 
	else {
		$db->next_record();
		if(!strcmp($your_vote,$db->f("decision"))) print "You have already voted that\n";
		else {
			$db->query("UPDATE decisions_step5 SET  decision='$your_vote' WHERE proid='$proid' AND decision_user='".$auth->auth["uname"]."' AND number='$milestone_number' AND time='$time'");
			print "Vote changed. Old: <b>".$db->f("decision")."</b> New: <b>".$your_vote."</b>";
		}
	}

  	include("monitorlib.inc");
	include("config.inc");
	monitor_mail($proid,"decision_step_5", "Decision met in step 5 (follow-up) on project $proid", "Decision met by $decision_user\n\n You can see it at: $sys_urldecisions.php3?proid=$proid");
}

function decisions_step5_votes($proid,$milestone_number,$time,$decision) {

  $db_local = new DB_SourceAgency;
  $db_local->query("SELECT SUM(budget) AS sum_step5 FROM sponsoring,decisions_step5 WHERE decisions_step5.proid='$proid' AND sponsoring.proid='$proid' AND sponsoring.sponsor=decisions_step5.decision_user AND decisions_step5.number='$milestone_number' AND time='$time' AND decision='$decision' GROUP BY decision");
  if ($db_local->num_rows() == 0) return 0;
  $db_local->next_record();
  return 100*$db_local->f("sum_step5")/project_budget($proid);
}

function are_you_sure_message_step5($proid) {
  global $bx, $t;

	$bx->box_begin();
 	$bx->box_title("<b>".$t->translate("Warning! The decision has been made")."</b>");
	$bx->box_body_begin();
	$bx->box_columns_begin(2);

	htmlp_form_action("PHP_SELF", array("proid" => $proid), "POST");
	$bx->box_column("left","70%","",$t->translate("Are you sure you want to decide yet?<br>Press <b>Yes</b> to put into the next step and <b>No</b> to stay in the current one."));
	$bx->box_column("right","30%","",html_form_submit("Yes","Yes").html_form_submit("No","No"));
	htmlp_form_end();
	$bx->box_columns_end();
	$bx->box_body_end();
	$bx->box_end();
}

function decisions_step5_sponsors($proid,$milestone_number,$time) {

  $db_quota = new DB_SourceAgency;

		// accepted

  $db_quota->query("SELECT SUM(budget) FROM sponsoring,decisions_step5 WHERE sponsor=decision_user AND decision='accept' AND time='$time' AND sponsoring.proid='$proid' AND decisions_step5.proid='$proid' AND decisions_step5.number='$milestone_number'");
  $db_quota->next_record();
  if ($db_quota->f("SUM(budget)") > $decision_value) { 
	$db_quota->query("UPDATE follow_up SET iteration='5' WHERE proid='$proid' AND milestone_number='$milestone_number'");
	$milestone_number++;
	$db_quota->query("INSERT follow_up SET iteration='0',proid='$proid',milestone_number='$milestone_number',time='1'");
  } else {

		// light problems

	 $db_quota->query("SELECT SUM(budget) FROM sponsoring,decisions_step5 WHERE sponsor=decision_user AND decision='light' AND time='$time' AND sponsoring.proid='$proid' AND sponsoring.proid='$proid' AND decisions_step5.number='$milestone_number'");
  	$db_quota->next_record();
	if ($db_quota->f("SUM(budget)") > $decision_value) { 
		$db_quota->query("SELECT * FROM follow_up WHERE proid='$proid' AND milestone_number='$milestone_number'");
		$db_quota->next_record();
		$time = $db_quota->f("time") + 1;
		$db_quota->query("UPDATE follow_up SET iteration='0',time='$time',location='' WHERE proid='$proid' AND milestone_number='$milestone_number'");
  	} else {

		// severe problems

		 $db_quota->query("SELECT SUM(budget) FROM sponsoring,decisions_step5 WHERE sponsor=decision_user AND decision='severe'  AND time='$time' AND sponsoring.proid='$proid' AND sponsoring.proid='$proid' AND decisions_step5.number='$milestone_number'");
  		$db_quota->next_record();
		if ($db_quota->f("SUM(budget)") > $decision_value) { 
			$db_quota->query("UPDATE follow_up SET iteration='2' WHERE proid='$proid' AND milestone_number='$milestone_number'");
		}
	}
  }
}

function decisions_milestone_into_db($proid,$devid,$number,$status) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT creation,release FROM milestones WHERE proid='$proid' AND devid='$devid' AND number='$number'");
	$db_local->next_record();
	$creation=$db_local->f("creation");
	$release=$db_local->f("release");

	$db_local->query("UPDATE milestones SET status='$status',creation='$creation',release='$release' WHERE proid='$proid' AND devid='$devid' AND number='$number'");

/*
  	include("monitorlib.inc");
	include("config.inc");
	monitor_mail($proid,"decision_milestone", "Decision met on a milestone on project $proid", "An event has happened"
);
*/
}

function decision_insert_main_developer($proid) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT developer FROM configure WHERE proid='$proid'");
	$db_local->next_record();
	if ($db_local->f("developer") != "") return 0;
	else {
		$db_local->query("SELECT developer FROM developing WHERE proid='$proid' AND status='A'");
		$db_local->next_record();
		$developer = $db_local->f("developer");

		$db_local->query("UPDATE configure SET developer='$developer' WHERE proid='$proid'");
	}
}

function decision_developer_voted($proid,$referee) {

	$db_local = new DB_SourceAgency;

	$db_local->query("SELECT developer FROM developing WHERE proid='$proid' AND status='A'");		
	$db_local->next_record();
	$developer = $db_local->f("developer");

	$db_local->query("SELECT * FROM decisions WHERE proid='$proid' AND decision='$referee' AND decision_user='$developer'");
	if ($db_local->num_rows() == 1) return 1;
	else return 0;
}

function decisions_decision_met($proid) {

	$first=0;
	$second=0;

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT status FROM description WHERE proid='$proid'");
	$db_local->next_record();
	$first = $db_local->f("status");

	$db_local->query("SELECT step FROM decisions WHERE proid='$proid'");
	while ($db_local->next_record()) {
		if ($second < $db_local->f("step")) $second =$db_local->f("step");
	}

	if ($first == 1) {
		$db_local->query("SELECT consultants FROM configure WHERE proid='$proid'");
		$db_local->next_record();
		if ($db_local->f("consultants") == No) $second =1;
	}

	if ($first == $second) return 1;
	else return 0;
}

?>