<?php

######################################################################
# SourceAgency: 
# ================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
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

function followup_devel_preview($proid) {
  global $t, $db, $bx, $auth, $milestone_number, $location, $time;

	  $db->query("SELECT * FROM milestones WHERE proid='$proid' AND number='$milestone_number'");
	  $db->next_record();

	$bx->box_begin();
	$bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
	$bx->box_title($t->translate("Milestone Delivery"));
	$bx->box_body_begin();
   	$timestamp = time();
        echo "<b>";
	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT email_usr FROM auth_user WHERE username='".$auth->auth["uname"]."'");
	$db_local->next_record();
	lib_pnick($auth->auth["uname"]);
        echo " - ".timestr($timestamp)."</b>\n";
   	echo "<p><b>Milestone number:</b> $milestone_number\n";
   	echo "<br><b>Goals:</b> ".$db->f("goals")."\n";
   	echo "<br><b>Release date:</b> ".timestr_middle(mktimestamp($db->f("release")))."\n";
   	echo "<br><b>Product</b> ".$db->f("product")."\n";
   	echo "<br><b>Payment</b> ".$db->f("payment")."\n";
    	echo "<p><b>Location:</b> ".html_link($location,array(),$location)."\n";
    	echo "<br><b>Time:</b> $time\n";
    	$bx->box_body_end();
    	$bx->box_end();
}

function followup_referee_preview($proid) {
  global $t, $bx, $auth, $milestone_number, $location, $time, $decision;

	$bx->box_begin();
	$bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
	$bx->box_title($t->translate("Milestone Delivery - Referee Decision"));
	$bx->box_body_begin();
   	$timestamp = time();
        echo "<b>";
	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT email_usr FROM auth_user WHERE username='".$auth->auth["uname"]."'");
	$db_local->next_record();
	lib_pnick($auth->auth["uname"]);
        echo " - ".timestr($timestamp)."</b>\n";
   	echo "<p><b>Milestone number:</b> $milestone_number\n";
    	echo "<br><b>Location:</b> ".html_link($location,array(),$location)."\n";
    	echo "<br><b>Time:</b> $time\n";
    	echo "<br><b>Decision:</b> $decision\n";
    	$bx->box_body_end();
    	$bx->box_end();
}

function followup_devel_form($proid) {
  global $bx, $db, $t, $milestone_number, $location, $time;

  $db->query("SELECT * FROM milestones WHERE proid='$proid' AND number='$milestone_number'");
  $db->next_record();

  $bx->box_begin();
  $bx->box_title($t->translate("Milestone Delivery"));
  $bx->box_body_begin();
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Milestone Number")."</b>: ");
  $bx->box_column ("left","70%","",$milestone_number);

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Goals")."</b>: ");
  $bx->box_column ("left","70%","",$db->f("goals"));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Release date")."</b>: ");
  $bx->box_column ("left","70%","",timestr_middle(mktimestamp($db->f("release"))));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Product")."</b>: ");
  $bx->box_column ("left","70%","",$db->f("product"));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Payment")."</b>: ");
  $bx->box_column ("left","70%","",$db->f("payment")."%");

  $bx->box_next_row_of_columns();

  $bx->box_colspan ("2","","white","&nbsp;");

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Location")."</b> (255): ");
  $bx->box_column ("left","70%","",html_input_text("location",40,255,$location));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Time")."</b>: ");
  $bx->box_column ("left","70%","",$time." <i>(this is the number of times you have submitted this milestone)</i>");

  $bx->box_next_row_of_columns();

  $bx->box_colspan ("2","","white","&nbsp;");

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function followup_referee_form($proid) {
  global $bx, $t, $milestone_number, $location, $time, $decision;

  $bx->box_begin();
  $bx->box_title($t->translate("Milestone Delivery - Referee Decision"));
  $bx->box_body_begin();
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Milestone Number")."</b>: ");
  $bx->box_column ("left","70%","",$milestone_number);

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Location")."</b>: ");
  $bx->box_column ("left","70%","",html_link($location,array(),$location));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Time")."</b>: ");
  $bx->box_column ("left","70%","",$time);

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Decision")."</b>: ");
  $bx->box_column ("left","70%","",followup_referee_decision($decision));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function followup_current_milestone($proid) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT * FROM follow_up WHERE proid='$proid' ORDER BY milestone_number DESC");
	if ($db_local->num_rows() == 0) return 1;
	$db_local->next_record();
	$temp = $db_local->f("milestone_number");
	if ($db_local->f("iteration") == 3) $temp += 1;
	return $temp;
}

function followup_current_time($proid,$milestone_number) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT time FROM follow_up WHERE milestone_number='$milestone_number'");
	if ($db_local->num_rows() == 0) return 1;
	$db_local->next_record();
	return $db_local->f("time");
}

function followup_location($proid,$milestone_number) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT location FROM follow_up WHERE milestone_number='$milestone_number'");
	$db_local->next_record();
	return $db_local->f("location");
}

function followup_insert($proid,$milestone_number,$iteration,$location,$time) {
  global $db;

  if ($iteration=="1" && $time == 1) {
	$db->query("INSERT follow_up SET proid='$proid',milestone_number='$milestone_number',iteration='$iteration',location='$location',time='$time'");
  } else {
	$db->query("UPDATE follow_up SET iteration='$iteration', location='$location', time='$time' WHERE proid='$proid' AND milestone_number='$milestone_number'");
  }
}

function followup_referee_decision($selected) {

  $return = html_select("decision");
  if($selected=="accept") $select="selected";
  else $select="";
  $return .= html_select_option("accept",$select,"accept");
  if($selected=="light") $select="selected";
  else $select="";
  $return .= html_select_option("light",$select,"light");
  if($selected=="severe") $select="selected";
  else $select="";
  $return .= html_select_option("severe",$select,"severe");
  $return .=html_select_end();

  return($return);
}

?>