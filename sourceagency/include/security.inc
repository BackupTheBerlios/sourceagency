<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Lutz Henckel (lutz.henckel@fokus.gmd.de)
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Security and Access control library
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: security.inc,v 1.7 2001/11/14 12:26:40 riessen Exp $
#
######################################################################


		// allowed_actions is a function used
		// in the summary page

function allowed_actions ($project_status, $action_number, $proid) {
    global $g_step_text, $t;

  $db_allowed = new DB_SourceAgency;

    // FIXME: this is a potential bug when action_number > 6 or < 1
    $text = $t->translate( $g_step_text[ $action_number ] );

    // NOI18N: all the text here should be i18n
    // TODO: the descriptions for steps should be stored in the database
    // TODO: or the config file but not hardcored here ....
  switch($action_number) {
	case 1: 	$link = "step1.php3?proid=".$proid;
			$db_allowed->query("SELECT consultants FROM configure WHERE proid='$proid'");
			$db_allowed->next_record();
			if($db_allowed->f("consultants") == "Yes") {		
				$db_allowed->query("SELECT COUNT(*) FROM consultants WHERE proid='$proid'");
				$db_allowed->next_record();
				$statistics = "( <B>".$db_allowed->f("COUNT(*)")."</B> consultant offerings)";
				$explanation = "Help for sponsors on making a propper project submission.";
			} else {
				$statistics = "( this project is configured to have <b>no</b> consultants)";
				$explanation = "Help for sponsors on making a propper project submission.";
			}
			break;
	case 2: 	$link = "step2.php3?proid=".$proid;
					// Sponsor Involvement
			$db_allowed->query("SELECT COUNT(*) FROM tech_content WHERE proid='$proid'");
			$db_allowed->next_record();
			$statistics = "( <B>".$db_allowed->f("COUNT(*)")."</B> suggested project contents";
			$db_allowed->query("SELECT COUNT(*) FROM developing WHERE proid='$proid'");
			$db_allowed->next_record();
			$statistics .= ", <B>".$db_allowed->f("COUNT(*)")."</B> developing proposals )";
			$explanation = "This project may have different specifications. Developers can make their developing proposals to that specifications.";
			break;
	case 3:	 	$link = "step3.php3?proid=".$proid;
			$db_allowed->query("SELECT COUNT(*) FROM milestones WHERE proid='$proid'");
			$db_allowed->next_record();
			$statistics = "( <B>".$db_allowed->f("COUNT(*)")."</B> suggested milestones )";
			$db_allowed->query("SELECT COUNT(*) FROM milestones WHERE proid='$proid'");
			$explanation = "Project milestones proposed by developers.";
			break;
	case 4: 	$link = "step4.php3?proid=".$proid;
			$db_allowed->query("SELECT COUNT(*) FROM referees WHERE proid='$proid'");
			$db_allowed->next_record();
			$statistics = "( <B>".$db_allowed->f("COUNT(*)")."</B> referees offered )";
			$explanation = "Developers can propose themselves as referees. Sponsors and developers will elect one of them.";
			break;
	case 5: 	$link = "step5.php3?proid=".$proid;
			$statistics = "( <B>x</B> milestones of <b>x</b> total milestones fulfilled )";
			$explanation = "Milestone delivery and other tasks related to the project follow-up.";
			break;
	case 6: 	$link = "step6.php3?proid=".$proid;
			$statistics = "";
			$explanation = "Final rating between sponsors and developers.";
			break;
        /** FIXME: this case has no default case **/
  }

  if ($action_number > 1) {
      print "<hr>\n";
  }
  print ( $project_status >= $action_number ? "<a href=\"$link\">" 
                                               : "<font color=\"#CCCCCC\">");
  print "<IMG src=\"images/ic/".$action_number;
  if ($project_status < $action_number) {
      print "grey";
  }

  // FIXME: should height and width be hardcoded here?
  print ".png\" border=0 width=\"48\" height=\"48\" alt=\"1\">&nbsp;"
      . $text . ( $project_status >= $action_number ? "</a>" : "</font>" );

  if ($project_status >= $action_number) {
      print " ".$statistics;
  }
  print "<br><i>".$explanation."</i>\n";
}

function check_proid ($proid) {
  global $perm;

  $db_check = new DB_SourceAgency;

  $db_check->query("SELECT * FROM description WHERE proid='$proid'"); 
  $db_check->next_record();
  if ($db_check->num_rows() == 0 || empty($proid)) {
	// FIXME - FIXME - FIXME
	require("config.inc");
	$be = new box("",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);
	$be->box_full("Error", "No project with this id.");
	return 0;
  } elseif ($db_check->f("status") == 0) {
  	if (isset($perm) && ($perm->have_perm("editor") || $perm->have_perm("editor"))) return 1;

	require("config.inc");
	$be = new box("",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);
	$be->box_full("Error", "Project pending for review by a SourceAgency editor.");
	return 0;
  } elseif ($db_check->f("status") == "-1") {
  	if (isset($perm) && ($perm->have_perm("editor") || $perm->have_perm("editor"))) return 1;

	require("config.inc");
	$be = new box("",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);
	$be->box_full("Error", "Project not accepted by a SourceAgency editor.");
	return 0;
  } else {
    	return 1;
  }
}

function check_permission($proid,$page) {
  global $perm, $auth;

  if (isset($perm) && ($perm->have_perm("editor") 
       || $perm->have_perm("editor"))) {
      return 1;
  }

  $db_check = new DB_SourceAgency;

  $db_check->query("SELECT * FROM description WHERE proid='$proid'"); 
  if ( $db_check->num_rows() == 0 ) {
      return invalid_project_id($proid,$page);    
  }

  $db_check->next_record();

  $project_initiator = $db_check->f("description_user");
  $current_step = $db_check->f("status");

  if ($db_check->num_rows() == 0 || empty($proid) 
      || (($db_check->f("status") == 0 || $db_check->f("status") == "-1") 
          && !is_project_initiator($proid))) {
      if (isset($auth) && !strcmp($db_check->f("description_user"),
                                  $auth->auth["uname"])) {
		// FIXME - FIXME - FIXME what? fix what?
		require("config.inc");
		$be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                              $th_box_title_bgcolor,$th_box_title_font_color,
                              $th_box_title_align,$th_box_body_bgcolor,
                              $th_box_error_font_color,$th_box_body_align);
		$be->box_full("Error", "No project id or project pending");
		return 0;
	}
  } else {
	switch($page) {
		case "news_edit":
		case "news_mod":
		case "comments_edit":
		case "history":
		case "step3":
		case "step4":
		case "step5":
		case "cooperation":
		case "views":
			if (security_accept_by_view($proid,$page)) return 1;
			else return permission_denied($proid,$page);
		case "configure":
			if (is_project_initiator($proid)) return 1;
			elseif (security_accept_by_view($proid,$page)) return 1;
			else return permission_denied($proid,$page);
		case "configure_edit":
			if (is_project_initiator($proid) || is_first_sponsor_or_dev($proid)) return 1;
			else return permission_denied($proid,$page);
		case "views_edit":
			if (is_project_initiator($proid)) return 1;
			else return permission_denied($proid,$page);
		case "monitor":
			if (is_accepted_sponsor($proid) || is_accepted_developer($proid)) return 1;
			else return permission_denied($proid,$page);
		case "monitor_edit":
			if (is_sponsor() || is_developer()) return 1;
			else return permission_denied($proid,$page);
		case "summary":
			break;
		case "news_mod":
			if (is_project_initiator($proid)) return 1;
			else return permission_denied($proid,$page);
			break;
		case "comments":
			break;
		case "comments_mod":
			if (is_project_initiator($proid)) return 1;
			else return permission_denied($proid,$page);
			break;
		case "sponsoring":
			break;
		case "sponsoring_edit":
			if (is_sponsor()) {
				if (!already_involved_in_this_step($proid,"sponsoring_edit",$auth->auth["uname"])) return 1;
				else return already_involved_message($proid,$page);
			} else return permission_denied($proid,$page);
			break;
		case "sponsoring_mod":
			if (is_project_initiator($proid)) return 1;
			else return permission_denied($proid,$page);
			break;
		case "sponsoring_accepted":
			if (is_accepted_sponsor($proid)) return 1;
			else return permission_denied($proid,$page);
		case "step1":
			break;
		case "step1_edit":
			if (is_developer()) {
				if ($current_step == 1) {;
					if (!already_involved_in_this_step($proid,"step1_edit",$auth->auth["uname"])) return 1;
					else return already_involved_message($proid,$page);
				} else return step_not_open($proid,$page);
			} else return permission_denied($proid,$page);
			break;
		case "step2":
			break;
		case "step2_edit":
			if (is_developer() || is_project_initiator($proid)) { 
				if ($current_step == 2) {
					if (other_specifications_allowed($proid) || (is_project_initiator($proid) && no_other_specification_yet($proid))) return 1;
					else return projects_only_by_project_initiator($proid,$page);
				} else return step_not_open($proid,$page);
			} else return permission_denied($proid,$page);
			break;
		case "developing_edit":
			if (is_developer()) { 
				if ($current_step == 2) {
					if (!already_involved_in_this_content($proid,"developing_edit",$auth->auth["uname"],$GLOBALS["content_id"])) {
						if (other_developing_proposals_allowed($proid) || (is_project_initiator($proid) && no_other_proposal_yet($proid))) return 1;
						else return proposals_only_by_project_initiator($proid,$page);
					} else return already_involved_message($proid,$page);
				} else return step_not_open($proid,$page);
			} else return permission_denied($proid,$page);
			break;
		case "developing_mod":
			if (is_project_initiator($proid)) return 1;
			else return permission_denied($proid,$page);
			break;
		case "step3_edit":
			if (is_developer()) { 
				if ($current_step == 3) {
					if (is_involved_developer($proid)) {
						if (is_milestone_possible($proid,$devid)) return 1;
						else return milestone_not_possible($proid,$page);
					} else return permission_denied($proid,$page);
				} else return step_not_open($proid,$page);
			} else return permission_denied($proid,$page);
			break;
		case "step3_mod":
			if (is_your_milestone($proid,$devid)) return 1;
			else return permission_denied($proid,$page);
			break;
		case "step4_edit":
			if (is_developer() && !is_main_developer($proid)) { 
				if ($current_step == 4) {
					if (!already_involved_in_this_step($proid,"step4_edit",$auth->auth["uname"])) return 1;
					return already_involved_message($proid,$page);
				} else return step_not_open($proid,$page);
			} else return permission_denied($proid,$page);
			break;
		case "step5_devel":
			if (is_involved_developer($proid)) { 
				if ($current_step == 5) {
					if (step5_iteration($proid) == 0) return 1;
					else return step5_not_your_iteration($proid,$page);
				} else return step_not_open($proid,$page);
			} else return permission_denied($proid,$page);
			break;
		case "step5_sponsor":
			if (is_accepted_sponsor($proid)) { 
				if ($current_step == 5) {
					if (step5_iteration($proid) == 1 || step5_iteration($proid) == 3) return 1;
					else return step5_not_your_iteration($proid,$page);
				} else return step_not_open($proid,$page);
			} else return permission_denied($proid,$page);
			break;
		case "step5_referee":
			if (is_accepted_referee($proid)) { 
				if ($current_step == 5) {
					if (step5_iteration($proid) == 2) return 1;
					else return step5_not_your_iteration($proid,$page);
				} else return step_not_open($proid,$page);
			} else return permission_denied($proid,$page);
			break;
		case "step6":
			break;
		case "step6_edit":
			if (is_accepted_developer($proid) || is_accepted_sponsor($proid)) return 1;
			else return permission_denied($proid,$page);
			break;
		case "decisions":
			if (is_accepted_sponsor($proid) || (is_main_developer($proid) && $current_step == 4)) return 1;
			else return permission_denied($proid,$page);
			break;
		case "personal":
			if ($username == $auth->auth["uname"]) return 1;
			else return permission_denied($proid,$page);
			break;
		case "sponsoring_accepted":
			if (is_accepted_sponsor($proid)) return 1;
			else return permission_denied($proid,$page);
			break;
		case "cooperation_edit":
			if (is_developer()) return 1;
			else return permission_denied($proid,$page);
			break;
	}
    	return 1;
  }
}

function invalid_project_id($proid,$page) {
  global $t;
  top_bar($proid,$page);

  require("config.inc");
  $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                $th_box_title_bgcolor,$th_box_title_font_color,
                $th_box_title_align,$th_box_body_bgcolor,
                $th_box_error_font_color,$th_box_body_align);

  $be->box_full($t->translate("Permission denied"),
                $t->translate("Project does not exist"));
  return 0;	
}

function permission_denied($proid,$page) {
  global $t;
  top_bar($proid,$page);

  require("config.inc");
  $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                $th_box_title_bgcolor,$th_box_title_font_color,
                $th_box_title_align,$th_box_body_bgcolor,
                $th_box_error_font_color,$th_box_body_align);

  $be->box_full($t->translate("Permission denied"),
                $t->translate("You do not have rights to enter this page."));
  return 0;	
}

function step_not_open($proid,$page) {
  global $t;

  top_bar($proid,$page);

  require("config.inc");

  $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                $th_box_title_bgcolor,$th_box_title_font_color,
                $th_box_title_align,$th_box_body_bgcolor,
                $th_box_error_font_color,$th_box_body_align);

  $be->box_full($t->translate("Permission denied"),
                $t->translate("This action can not be made at this time."));
  return 0;
}

function projects_only_by_project_initiator($proid,$page) {
  global $t;
  top_bar($proid,$page);

  require("config.inc");

  $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                $th_box_title_bgcolor,$th_box_title_font_color,
                $th_box_title_align,$th_box_body_bgcolor,
                $th_box_error_font_color,$th_box_body_align);

  $be->box_full($t->translate("Permission denied"), 
                $t->translate("The project has been configured so that "
                              . "only the project initiator can post one "
                              ."specification."));
  return 0;
}

function proposals_only_by_project_initiator($proid,$page) {
  global $t;
  top_bar($proid,$page);

  require("config.inc");

  $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                $th_box_title_bgcolor,$th_box_title_font_color,
                $th_box_title_align,$th_box_body_bgcolor,
                $th_box_error_font_color,$th_box_body_align);

  $be->box_full($t->translate( "Permission denied" ), 
                $t->translate("The project has been configured so that "
                              ."only the project initiator can post one "
                              ."developing proposal."));
  return 0;
}

function is_project_initiator($proid) {
  global $auth;

  $db_local = new DB_SourceAgency;

  if (isset($auth) && !empty($auth->auth["perm"])) { 
	$db_local->query("SELECT * FROM description WHERE proid='$proid' "
                         ."AND description_user='".$auth->auth["uname"]."'");
	if ($db_local->num_rows() > 0) return 1;
  }
  return 0;
}

function is_sponsor() {
  global $auth;

  $db_local = new DB_SourceAgency;

  if (isset($auth) && !empty($auth->auth["perm"])) { 
	$db_local->query("SELECT * FROM auth_user WHERE perms='sponsor' "
                         ."AND username='".$auth->auth["uname"]."'");
	if ($db_local->num_rows() > 0) return 1;
  }
  return 0;
}

function is_accepted_sponsor($proid) {
  global $auth;

  $db_local = new DB_SourceAgency;

  if (isset($auth) && !empty($auth->auth["perm"])) { 
	$db_local->query("SELECT * FROM sponsoring WHERE proid='$proid' "
                         . "AND status='A' AND sponsor='"
                         . $auth->auth["uname"]."'");
	if ($db_local->num_rows() > 0) return 1;
  }
  return 0;
}

function is_accepted_referee($proid) {
  global $auth;

  $db_local = new DB_SourceAgency;

  if (isset($auth) && !empty($auth->auth["perm"])) { 
	$db_local->query("SELECT * FROM referees WHERE proid='$proid' "
                         ."AND status='A' AND referee='"
                         .$auth->auth["uname"]."'");
	if ($db_local->num_rows() > 0) return 1;
  }
  return 0;
}

function is_developer() {
  global $auth;

  $db_local = new DB_SourceAgency;

  if (isset($auth) && !empty($auth->auth["perm"])) { 
	$db_local->query("SELECT * FROM auth_user WHERE perms='devel' "
                         ."AND username='".$auth->auth["uname"]."'");
	if ($db_local->num_rows() > 0) return 1;
  }
  return 0;
}

function is_involved_developer($proid) {
  global $auth;

  $db_local = new DB_SourceAgency;

  if (isset($auth) && !empty($auth->auth["perm"])) { 
	$db_local->query("SELECT * FROM developing WHERE proid='$proid' "
                         ."AND developer='".$auth->auth["uname"]."'");
	if ($db_local->num_rows() > 0) return 1;
  }
  return 0;
}

function is_referee($proid) {
  global $auth;

  $db_local = new DB_SourceAgency;

  if (isset($auth) && !empty($auth->auth["perm"])) { 
	$db_local->query("SELECT * FROM referees WHERE proid='$proid' "
                         ."AND referee='".$auth->auth["uname"]."'");
	if ($db_local->num_rows() > 0) return 1;
  }
  return 0;
}

function is_accepted_developer($proid) {
  global $auth;

  $db_local = new DB_SourceAgency;

  if (isset($auth) && !empty($auth->auth["perm"])) { 
	$db_local->query("SELECT * FROM developing WHERE proid='$proid' "
                         ."AND status='A' AND developer='"
                         .$auth->auth["uname"]."'");
	if ($db_local->num_rows() > 0) return 1;
  }
  return 0;
}

function is_main_developer($proid) {
  global $auth;

  if (isset($auth) && !empty($auth->auth["perm"]) 
      && is_accepted_developer($proid)) {
	$db_local = new DB_SourceAgency;
        // FIXME: proid='..' appears _twice_ in the following query
	$db_local->query("SELECT * FROM configure WHERE proid='$proid' "
                         ."AND proid='$proid' AND developer='"
                         .$auth->auth["uname"]."'");
	if ($db_local->num_rows() > 0) return 1;
  }
  return 0;
}

function already_involved_in_this_step($proid,$page,$username) {

  switch ($page) {
	case "sponsoring_edit":	$table = "sponsoring"; $who = "sponsor"; 
          break;
	case "step1_edit": 	$table = "consultants"; $who = "consultant"; 
          break;
	case "developing_edit": $table = "developing"; $who = "developer"; 
          break;
	case "step4_edit": 	$table = "referees"; $who = "referee"; 
          break;
  }

  $db_local = new DB_SourceAgency;
  $db_local->query("SELECT * FROM $table WHERE proid='$proid' AND "
                   ."$who='$username'");
  if ($db_local->num_rows() > 0) return 1;
  else return 0;
}

function already_involved_in_this_content($proid,$page,$username,$content_id) {

  	$db_local = new DB_SourceAgency;
  	$db_local->query("SELECT * FROM developing WHERE proid='$proid' AND "
                         ."developer='$username' AND "
                         ."content_id='$content_id'");
  	if ($db_local->num_rows() > 0) return 1;
  	else return 0;
}

function already_involved_message($proid,$page) {
  global $t;
  top_bar($proid,$page);
  require("config.inc");

  $be = new box("",$th_box_frame_color,$th_box_frame_width,
                $th_box_title_bgcolor,$th_box_title_font_color,
                $th_box_title_align,$th_box_body_bgcolor,
                $th_box_error_font_color,$th_box_body_align);

  $be->box_full($t->translate("Permission denied"),
                $t->translate("You are not allowed to make this action "
                              ."more than one time."));
  return 0;
}


function security_accept_by_view($proid,$page) {
  global $auth;

  if ($page=="news_edit" || $page=="news_mod") $page="news";
  if ($page=="comments_edit") $page="comments";

  $db_local = new DB_SourceAgency;
  $db_local->query("SELECT $page FROM views WHERE proid='$proid'");
  $db_local->next_record();

  switch ($db_local->f($page)) {
	case "Everybody":
		return 1; break;
	case "Registered":
		if (isset ($auth) && isset($auth->auth["uname"]) 
                    && !empty($auth->auth["uname"]) 
                    && $auth->auth["perm"]!="devel_pending" 
                    && $auth->auth["perm"]!="sponsor_pending") {
                    return 1;
                }
	case "Developers":
		if (is_developer() || is_accepted_sponsor($proid)) return 1;
		else return 0;
	case "Sponsors":  
		if (is_sponsor() || is_involved_developer($proid)) return 1;
		else return 0;
	case "Project Participants": 
 		if (is_involved_developer($proid) 
                    || is_accepted_sponsor($proid) 
                    || is_project_initiator($proid)) {
                    return 1;
                } else {
                    return 0;
                }
	case "Project Developers":
		if (is_involved_developer($proid)) return 1;
		else return 0;
	case "Project Sponsors":
		if (is_accepted_sponsor($proid)) return 1;
		echo "hola";
	case "Project Initiator":
		if (is_project_initiator($proid)) return 1;
		else return 0;
	default:
		return 0;
  }
}

function step5_iteration($proid) {

	$current_milestone = 0;
	$current_iteration = 0;

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT milestone_number,iteration FROM follow_up WHERE proid='$proid'");
	if ($db_local->num_rows() == 0) return 0;
	else {
		while ($db_local->next_record()) {
			if ($current_milestone < $db_local->f("milestone_number")) $current_iteration = $db_local->f("iteration");
		}
	}
	return $current_iteration;
}

function step5_not_your_iteration($proid,$page) {

  top_bar($proid,$page);

  require("config.inc");

  $be = new box("",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);

  switch (step5_iteration($proid)) {
	case '0':
		$message = "The milestone has not been posted by the developer";
		break;
	case '1':
		$message = "The milestone has been posted. Sponsors are studying whether to accept it or not.";
		break;
	case '2':
		$message = "Sponsors have rejected the current milestone. The referee is studying it.";
		break;
	case '3':
		$message = "The referee has decided that the milestone posted by the developer does not fulfill the promised goals. Sponsors are deciding what is going to happen to the project";
		break;
	case '5':
		$message = "The follow_up process is finished";
		break;
  }
		
  $be->box_full("Not your turn", $message);
  return 0;
}

function is_your_milestone($proid,$devid) {
  global $auth;

  $db_local = new DB_SourceAgency;

  if (isset($auth) && !empty($auth->auth["perm"])) { 
	$db_local->query("SELECT * FROM developing WHERE proid='$proid' AND developer='".$auth->auth["uname"]."'");
	if ($db_local->num_rows() > 0) return 1;
  }
  return 0;
}

function is_milestone_possible($proid) {
  global $auth;

  $db_local = new DB_SourceAgency;
  $db_local->query("SELECT SUM(payment) FROM milestones,developing WHERE developing.proid='$proid' AND milestones.devid=developing.devid AND developer='".$auth->auth["uname"]."'");
  $db_local->next_record();
  if ($db_local->f("SUM(payment)") < 100) return 1;
}

function milestone_not_possible($proid,$page) {

  top_bar($proid,$page);

  require("config.inc");

  $be = new box("",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);

  $be->box_full("Milestone not possible", "Your milestones already reach 100%. You should modify your existing milestones before creating a new one.");
  return 0;	
}

function other_specifications_allowed($proid) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT other_tech_contents FROM configure WHERE proid='$proid'");
	$db_local->next_record();
	if ($db_local->f("other_tech_contents") == "Yes") return 1;
	else return 0;
}

function other_developing_proposals_allowed($proid) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT other_developing_proposals FROM configure WHERE proid='$proid'");
	$db_local->next_record();
	if ($db_local->f("other_developing_proposals") == "Yes") return 1;
	else return 0;
}

function no_other_specification_yet($proid) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT * FROM tech_content WHERE proid='$proid'");
	if ($db_local->num_rows() == 0) return 1;
	return 0;
}

function no_other_proposal_yet($proid) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT * FROM developing WHERE proid='$proid'");
	if ($db_local->num_rows() == 0) return 1;
	return 0;
}

function is_first_sponsor_or_dev($proid) {
	global $auth;

	if (is_main_developer($proid)) return 1;
	else {
		if (is_accepted_sponsor($proid)) {
			$db_local = new DB_SourceAgency;
			$db_local->query("SELECT * FROM configure WHERE sponsor='".$auth->auth["uname"]."'");
			if ($db_local->num_rows() > 0) return 1;
			else return 0;
		}
	}
}

?>