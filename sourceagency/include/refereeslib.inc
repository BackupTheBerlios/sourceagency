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
# Library with the functions for comments and faq
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: refereeslib.inc,v 1.2 2001/11/08 16:17:41 riessen Exp $
#
######################################################################

function show_referees($proid) {
  global $t, $bx, $db;

  $query = "SELECT * FROM referees,auth_user WHERE proid='$proid' AND username=referee ORDER BY creation";
  $db->query($query);

  if ($db->num_rows() != 0) {
	$bx->box_begin();
	$bx->box_title($t->translate("Referees"));
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
	print "<p>There are no developers that have offered themselves as referees.<p>\n";
  }
}

function referees_form($proid) {
  global $bx, $t, $sess, $auth;

  $bx->box_begin();
  $bx->box_title($t->translate("Offer yourself as project referee"));
  $bx->box_body_begin();
  print html_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","45%","","<b>".$t->translate("Your username")."</b>: ");
  $bx->box_column ("left","55%","",$auth->auth["uname"]);

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","45%","","<b>".$t->translate("Check if you want to be a referee")."</b>: ");
  $bx->box_column ("left","55%","",html_checkbox("check","check",""));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","45%","","&nbsp;");
  $bx->box_column ("left","55%","",html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function referees_insert($proid,$user) {
  global $db;

  $db->query("INSERT referees SET proid='$proid',referee='$user',status='P'");

  include("monitorlib.inc");
  include("config.inc");
  monitor_mail($proid,"referees", "New referee proposed for project $proid", "Event has happened");

  show_referees($proid);
}

?>