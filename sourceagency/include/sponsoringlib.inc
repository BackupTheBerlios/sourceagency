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
# Library with the functions for sponsoring involvements
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: sponsoringlib.inc,v 1.3 2001/11/20 16:42:30 riessen Exp $
#
######################################################################

function sponsoring_form($proid) {
  global $bx, $t, $sess, $sponsoring_text, $budget, $valid_day, $valid_month, $valid_year, $begin_day, $begin_month, $begin_year, $finish_day, $finish_month, $finish_year;

  $bx->box_begin();
  $bx->box_title($t->translate("Sponsoring involvement"));
  $bx->box_body_begin();
  print html_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Valid until")."</b>: ");
  $bx->box_column ("left","70%","",select_date("valid",$valid_day,
                                               $valid_month,$valid_year));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Begin")."</b>: ");
  $bx->box_column ("left","70%","",select_date("begin",$begin_day,$begin_month,$begin_year));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Finish")."</b>: ");
  $bx->box_column ("left","70%","",select_date("finish",$finish_day,$finish_month,$finish_year));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Budget (in euro)")."</b> (12): ");
  $bx->box_column ("left","70%","",html_input_text("budget",12,12,$budget));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Sponsoring Comment")."</b> (*): ");
  $bx->box_column ("left","70%","",html_textarea("sponsoring_text",40,7,"virtual",255,$sponsoring_text));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}


function show_sponsorings($proid) {
  global $t, $bx, $db, $sess, $auth;

  $query = "SELECT * FROM sponsoring,auth_user WHERE proid='$proid' AND sponsor=username ORDER BY sponsoring.creation ASC";
  $db->query($query);
  while($db->next_record()) {
	$bx->box_begin();
	$bx->box_title($t->translate("Sponsor Involvement"));
	$bx->box_body_begin();
   	$timestamp = mktimestamp($db->f("creation"));
	lib_pnick($db->f("username"));
        echo " - ".timestr($timestamp)."</b>\n";
    	echo "<p><b>Max. sum of money:</b> ".$db->f("budget")." euros\n";
    	echo "<br><b>Status:</b> ".show_status($db->f("status"))."\n";
    	echo "<br><b>Validity</b> ".timestr_middle(mktimestamp($db->f("valid")))."\n";
    	echo "<br><b>Begin wished:</b> ".timestr_middle(mktimestamp($db->f("begin")))."\n";
    	echo "<br><b>Finish before:</b> ".timestr_middle(mktimestamp($db->f("finish")))."\n";
	if (($db->f("sponsoring_text"))) echo "<p><b>Comments to the involvement:</b> ".$db->f("sponsoring_text")."\n";
	if($db->f("status") == "P" && is_accepted_sponsor($proid)) {
		$bx->box_title(html_link("sponsoring_accepted.php3",array("proid" => $proid, "sponsor" => $db->f("username")),"Accept this sponsor involvement"));		
	}
    	$bx->box_body_end();
    	$bx->box_end();

	lib_comment_it($proid,"Sponsoring",$db->f("spoid"),"0","Comment on Sponsor Involvement #".$db->f("spoid"),"Comment it!");

	lib_show_comments_on_it($proid,"Sponsoring",$db->f("spoid"),"0");
  }

  if ($db->num_rows() == 0) {
	print "<p>There have not been posted any sponsoring involvement wishes to this project.<p>\n";
  }
}

function sponsoring_preview($proid) {
  global $t, $bx, $auth, $sess, $sponsoring_text, $budget, $valid_day, $valid_month, $valid_year, $begin_day, $begin_month, $begin_year, $finish_day, $finish_month, $finish_year;

	$bx->box_begin();
	$bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
	$bx->box_title($t->translate("Sponsor Involvement"));
	$bx->box_body_begin();
	$timestamp = time();
	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT email_usr FROM auth_user WHERE username='".$auth->auth["uname"]."'");
	$db_local->next_record();
	lib_pnick($auth->auth["uname"]);
        echo " - ".timestr($timestamp)."</b>\n";
    	echo "<p><b>Max. sum of money:</b> $budget euros\n";
    	echo "<br><b>Status:</b> Proposed\n";
    	echo "<br><b>Validity</b> ".timestr_middle(mktimestamp(date_to_timestamp($valid_day,$valid_month,$valid_year)))."\n";
    	echo "<br><b>Begin wished:</b> ".timestr_middle(mktimestamp(date_to_timestamp($begin_day,$begin_month,$begin_year)))."\n";
    	echo "<br><b>Finish before:</b> ".timestr_middle(mktimestamp(date_to_timestamp($finish_day,$finish_month,$finish_year)))."\n";
	if ($sponsoring_text) echo "<p><b>Comments to the involvement:</b> $sponsoring_text\n";
    	$bx->box_body_end();
    	$bx->box_end();
}


function sponsoring_insert($proid,$user,$sponsoring_text,$budget,$valid_day,$valid_month,$valid_year,$begin_day,$begin_month,$begin_year,$finish_day,$finish_month,$finish_year) {
  global $db,$auth;

  $valid = date_to_timestamp($valid_day, $valid_month, $valid_year);
  $begin = date_to_timestamp($begin_day, $begin_month, $begin_year);
  $finish = date_to_timestamp($finish_day, $finish_month, $finish_year);

  $db->query("SELECT COUNT(*) FROM sponsoring WHERE proid='$proid'");
  $db->next_record();
  if ($db->f("COUNT(*)") > 0) $status = "P";
  else $status = "A";

  $db->query("INSERT sponsoring SET proid='$proid',sponsor='$user',sponsoring_text='$sponsoring_text',budget='$budget', status='$status', valid='$valid',begin='$begin',finish='$finish'");

		// If first sponsor on a developing project
		// That means if he is sponsor but not project initiator
  if ($status == "A" && !is_project_initiator($proid)) {
	  $db->query("UPDATE configure SET sponsor='".$auth->auth["uname"]."' WHERE proid='$proid'");
	  echo "<p><b>Congratulations</b>. You are the first sponsor. You can ";
	  htmlp_link("configure_edit.php3",array("proid" => $proid),"configure this project");
  }

  include("monitorlib.inc");
  include("config.inc");
  monitor_mail($proid,"sponsoring", "New Sponsor for project $proid", "Event has happened");

  show_sponsorings($proid);

  if (is_project_initiator($proid)) {
	lib_insertion_finished();
  }
}

?>