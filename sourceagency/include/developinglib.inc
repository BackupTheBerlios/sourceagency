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
# Library with the functions for developing proposals
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: developinglib.inc,v 1.2 2001/11/07 18:48:52 riessen Exp $
#
######################################################################

function show_developings($proid,$content_id) {
  global $t, $bx, $db;

  $query = "SELECT * FROM developing,auth_user WHERE proid='$proid' AND content_id='$content_id' AND developer=username ORDER BY developing.creation DESC";
  $db->query($query);
  while($db->next_record()) {
	$bx->box_begin();
	$bx->box_title($t->translate("Developing Proposal"));
	$bx->box_body_begin();
   	$timestamp = mktimestamp($db->f("creation"));
	lib_pnick($db->f("username"));
        echo " - ".timestr($timestamp)."</b>\n";
    	echo "<p><b>Cost:</b> ".$db->f("cost")." euros\n";
    	echo "<br><b>License:</b> ".$db->f("license")."\n";
    	echo "<br><b>Cooperation:</b> ".$db->f("cooperation")."\n";
    	echo "<br><b>Status:</b> ".show_status($db->f("status"))."\n";
    	echo "<br><b>Validity</b> ".timestr_middle(mktimestamp($db->f("valid")))."\n";
    	echo "<br><b>Start possible:</b> ".timestr_middle(mktimestamp($db->f("start")))."\n";
    	echo "<br><b>Duration:</b> ".$db->f("duration")." weeks\n";
    	$bx->box_body_end();
    	$bx->box_end();
  }

  if ($db->num_rows() == 0) {
	print "There have not been posted any developement proposals to this project.\n";
  }
}

function developing_preview($proid) {
  global $t, $bx, $auth, $cost, $license, $cooperation, $valid_day, $valid_month, $valid_year, $start_day, $start_month, $start_year, $duration;

	$bx->box_begin();
	$bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
	$bx->box_title($t->translate("Developing Proposal"));
	$bx->box_body_begin();
   	$timestamp = time();
        echo "<b>";
	lib_pnick($auth->auth["uname"]);
        echo " - ".timestr($timestamp)."</b>\n";
    	echo "<p><b>Cost:</b> $cost euros\n";
    	echo "<br><b>License:</b> $license\n";
    	echo "<br><b>Cooperation:</b> $cooperation\n";
    	echo "<br><b>Status:</b> Proposed\n";
    	echo "<br><b>Validity</b> ".timestr_middle(mktimestamp(date_to_timestamp($valid_day,$valid_month,$valid_year)))."\n";
    	echo "<br><b>Start possible:</b> ".timestr_middle(mktimestamp(date_to_timestamp($start_day,$start_month,$start_year)))."\n";
    	echo "<br><b>Duration:</b> $duration weeks\n";
    	$bx->box_body_end();
    	$bx->box_end();
}


function developing_form($proid,$content_id) {
  global $bx, $t, $sess, $cost, $license, $cooperation, $valid_day, $valid_month, $valid_year, $start_day, $start_month, $start_year, $duration;

  $bx->box_begin();
  $bx->box_title($t->translate("Developement proposal"));
  $bx->box_body_begin();
  print html_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Cost")."</b> (12): ");
  $bx->box_column ("left","70%","",html_input_text("cost",12,12,$cost));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("License")."</b> (12): ");
  $bx->box_column ("left","70%","",license($license));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Developer cooperation wanted?")."</b>");
  $bx->box_column ("left","70%","",developing_select_cooperation($cooperation));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Valid until")."</b>: ");
  $bx->box_column ("left","70%","", select_date("valid",$valid_day,$valid_month,$valid_year));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Start")."</b>: ");
  $bx->box_column ("left","70%","",select_date("start",$start_day,$start_month,$start_year));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Duration (in weeks)")."</b>: ");
  $bx->box_column ("left","70%","",select_duration($duration));

  $bx->box_next_row_of_columns();

  htmlp_form_hidden("content_id",$content_id);

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function developing_modify_form($proid,$content_id) {
  global $bx, $t, $sess, $cost, $license, $cooperation, $valid_day, $valid_month, $valid_year, $start_day, $start_month, $start_year, $duration, $creation;

  $bx->box_begin();
  $bx->box_title($t->translate("Developement proposal modification"));
  $bx->box_body_begin();
  print html_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Cost")."</b> (12): ");
  $bx->box_column ("left","70%","",html_input_text("cost",12,12,$cost));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("License")."</b> (12): ");
  $bx->box_column ("left","70%","",license($license));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Developer cooperation wanted?")."</b> (SELECT BOX)");
  $bx->box_column ("left","70%","",html_input_text("cooperation",12,12,$cooperation));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Valid until (select box)")."</b>: ");
  $bx->box_column ("left","70%","",html_input_text("valid",14,14,$valid));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Start (Select box)")."</b>: ");
  $bx->box_column ("left","70%","",html_input_text("start",14,14,$start));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Duration (Select box)")."</b>: ");
  $bx->box_column ("left","70%","",html_input_text("duration",3,3,$duration));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function developing_insert($proid,$user,$content_id, $cost, $license, $cooperation, $valid_day, $valid_month, $valid_year, $start_day, $start_month, $start_year, $duration) {
  global $db;

  $valid = date_to_timestamp($valid_day, $valid_month, $valid_year);
  $start = date_to_timestamp($start_day, $start_month, $start_year);

  $db->query("INSERT developing SET proid='$proid',developer='$user',content_id='$content_id', cost='$cost', license='$license', cooperation='$cooperation', valid='$valid', start='$start', duration='$duration',status='P'");

  include("monitorlib.inc");
  include("config.inc");
  monitor_mail($proid,"developing", "New Developing proposal for project $proid", "Event has happened");

  show_developings($proid,$content_id);
}

function developing_modify($proid,$content_id,$developer,$cost, $license, $cooperation, $valid, $start, $duration,$creation) {
  global $db;

  $db->query("UPDATE developing SET developer='$developer', cost='$cost', license='$license', cooperation='$cooperation', valid='$valid', start='$start', duration='$duration',status='M' WHERE proid='$proid' AND content_id='$content_id' AND creation='$creation'");
  show_developings($proid,$content_id);
}

function show_selected_developing($proid) {
  global $t, $bx, $db;

  $query = "SELECT * FROM developing,auth_user WHERE proid='$proid' AND developer=username AND developing.status='A'";
  $db->query($query);
  $db->next_record();

  $bx->box_begin();
  $bx->box_title($t->translate("Developing Proposal"));
  $bx->box_body_begin();
  $timestamp = mktimestamp($db->f("creation"));
  echo "<b>by ";
  lib_pnick($db->f("username"));
  echo " - ".timestr($timestamp)."</b>\n";
  echo "<p><b>Cost:</b> ".$db->f("cost")." euros\n";
  echo "<br><b>License:</b> ".$db->f("license")."\n";
  echo "<br><b>Cooperation:</b> ".$db->f("cooperation")."\n";
  echo "<br><b>Status:</b> ".show_status($db->f("status"))."\n";
  echo "<br><b>Validity</b> ".timestr_middle(mktimestamp($db->f("valid")))."\n";
  echo "<br><b>Start possible:</b> ".timestr_middle(mktimestamp($db->f("start")))."\n";
  echo "<br><b>Duration:</b> ".$db->f("duration")." weeks\n";
  $bx->box_body_end();
  $bx->box_end();
}

function select_duration($selected) {

  $return = html_select("duration");

  for ($i=1; $i <= 100; $i++) {
 	if ($i == $selected) $select="selected";
	else $select="";
	$return .= html_select_option($i,$select,$i);
  }
  $return .= html_select_end();

  return($return);
}

function developing_select_cooperation($selected) {

  $return = html_select("cooperation");
  if($selected=="No") $select="selected";
  else $select = "";
  $return .= html_select_option("No",$select,"No");

/*
  for ($i=1;$i<10;$i++) {
	if($selected==5*$i."%") $select="selected";
	else $select = "";
	$return .= html_select_option(5*$i."%",$select,5*$i."%");
  }
*/

  $return .=htmlp_select_end();

  return($return);
}

function developing_milestone_delivery_preview($proid) {
  global $t, $bx, $auth, $milestone_number, $url, $time;

	$bx->box_begin();
	$bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
	$bx->box_title($t->translate("Milestone Delivery"));
	$bx->box_body_begin();
   	$timestamp = time();
        echo "<b>";
	lib_pnick($auth->auth["uname"]);
        echo " - ".timestr($timestamp)."</b>\n";
   	echo "<p><b>Milestone number:</b> $milestone_number\n";
    	echo "<br><b>URL:</b> ".html_link($url,array(),$url)."\n";
    	echo "<br><b>Time:</b> $time\n";
    	$bx->box_body_end();
    	$bx->box_end();
}

function developing_milestone_delivery_form($proid) {
  global $bx, $t, $milestone_number, $url, $time;

  $bx->box_begin();
  $bx->box_title($t->translate("Milestone Delivery"));
  $bx->box_body_begin();
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Milestone Number")."</b>: ");
  $bx->box_column ("left","70%","",$milestone_number);

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("URL")."</b> (255): ");
  $bx->box_column ("left","70%","",html_input_text("url",40,255,$url));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Time")."</b>: ");
  $bx->box_column ("left","70%","",$time);

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function developing_milestone_delivery_current_milestone($proid) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT * FROM follow_up WHERE proid='$proid' ORDER BY milestone_number DESC");
	if ($db_local->num_rows() == 0) return 1;
	$db_local->next_record();
	$temp = $db_local->f("milestone_number");
	if ($db_local->f("iteration") == 3) $temp += 1;
	return $temp;
}

function developing_milestone_delivery_current_time($proid,$milestone_number) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT time FROM follow_up WHERE milestone_number='$milestone_number'");
	if ($db_local->num_rows() == 0) return 1;
	$db_local->next_record();
	return $db_local->f("time");
}

function developing_milestone_delivery_insert($proid,$milestone_number,$url,$time) {
  global $db;

  $db->query("INSERT follow_up SET proid='$proid',milestone_number='$milestone_number',iteration='1',url='$url',time='$time'");

  include("monitorlib.inc");
  include("config.inc");
  monitor_mail($proid,"milestone_delivery", "Milestone $milestone_number has been delivered on project $proid", "Event has happened");
}

?>