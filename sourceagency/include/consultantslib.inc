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

function show_consultants($proid) {
  global $t, $bx, $db;

  $query = "SELECT * FROM consultants,auth_user WHERE proid='$proid' AND username=consultant ORDER BY creation";
  $db->query($query);

  if ($db->num_rows() != 0) {
	$bx->box_begin();
	$bx->box_title($t->translate("Consultants"));
	$bx->box_body_begin();
	$bx->box_columns_begin(4);

	$bx->box_column("","","","<b>Number</b>");
	$bx->box_column("","","","<b>Username</b>");
	$bx->box_column("","","","<b>Status</b>");
	$bx->box_column("","","","<b>Creation</b>");

	$i =0;

	while($db->next_record()) {
		$i++;
		if ($i%2 != 0) $bgcolor = "gold";
		else $bgcolor = "#FFFFFF";

		$bx->box_next_row_of_columns();

		$bx->box_column("","",$bgcolor,"<b>$i</b>");
		$bx->box_column("","",$bgcolor,"<b>".lib_nick($db->f("username"))."</b>");
		$bx->box_column("","",$bgcolor,"<b>".show_status($db->f("status"))."</b>");
		$bx->box_column("","",$bgcolor,"<b>".timestr(mktimestamp($db->f("creation")))."</b>");
	}

	$bx->box_columns_end();
	$bx->box_body_end();
	$bx->box_end();
  }

  if ($db->num_rows() == 0) {
	print "<p>No developers have offered themselves as consultants yet.<p>\n";
  }
}

function consultants_form($proid) {
  global $bx, $t, $sess, $auth;

  $bx->box_begin();
  $bx->box_title($t->translate("Offer yourself as project consultant"));
  $bx->box_body_begin();
  print html_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","45%","","<b>".$t->translate("Your username")."</b>: ");
  $bx->box_column ("left","55%","",$auth->auth["uname"]);

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","45%","","<b>".$t->translate("Check if you want to be a consultant")."</b>: ");
  $bx->box_column ("left","55%","",html_checkbox("check","check",""));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","45%","","&nbsp;");
  $bx->box_column ("left","55%","",html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function consultants_insert($proid,$user) {
  global $db;

  $db->query("INSERT consultants SET proid='$proid',consultant='$user',status='P'");

  include("monitorlib.inc");
  include("config.inc");
  monitor_mail($proid,"consultant", "New consultant proposed for project $proid", "Event has happened");

  show_consultants($proid);
}

function consultants_wanted($proid) {
  global $db;

  $db->query("SELECT consultants FROM configure WHERE proid='$proid'");
  $db->next_record();
  if ($db->f("consultants") == "Yes") return 1;
  else {
  	require("config.inc");

  	$be = new box("",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);

  	$be->box_full("No consultants wanted", "This project does not require any consultants");
  	return 0;
  }

}

?>

