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
# TODO: description missing
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: historylib.inc,v 1.3 2001/11/08 16:17:41 riessen Exp $
#
######################################################################

function history_extract_table($table_name,$table_creation,$table_user,$table_subject) {
  global $history,$i,$db,$proid,$what,$who,$subject;

  $db->query("SELECT * FROM $table_name WHERE proid='$proid'");
  while($db->next_record()) {
	$history[++$i] = $db->f($table_creation);
	$string = substr($history[$i], -9);
	$what[$string] = $table_name;
	if ($table_user == "milestone_user") {
		$db_milestone = new DB_SourceAgency;
		$db_milestone->query("SELECT developer FROM developing WHERE developing.devid='".$db->f("devid")."'");
		$db_milestone->next_record();
		$who[$string] = $db_milestone->f("developer");
        }
	else $who[$string] = $db->f($table_user);

	$action_subject = $db->f($table_subject);
	if (!strcmp($action_subject,"")) $action_subject = $table_subject;
	$subject[$string] = $action_subject;
  }
}

function bubblesort(&$history) {
  for ($i = sizeof($history); $i >= 0; $i--) {
	for ($j = 1; $j <= $i; $j++) {
		if ($history[$j-1] < $history[$j]) {
			$temp = $history[$j-1];
			$history[$j-1] = $history[$j];
			$history[$j] = $temp;
		}
	}
  }
}

function show_history($history) {
  global $bx,$t,$what,$who,$subject;

  $bx->box_begin();
  $bx->box_title($t->translate("Project History"));
  $bx->box_body_begin();
  $bx->box_columns_begin(4);

  $bx->box_column("","","","<b>Date</b>");
  $bx->box_column("","","","<b>Type</b>");
  $bx->box_column("","","","<b>&nbsp;</b>");
  $bx->box_column("","","","<b>User</b>");

  for ($i = sizeof($history)-1; $i > -1; $i--) {

	if ($i%2 != 0) $bgcolor = "gold";
	else $bgcolor = "#FFFFFF";

	$bx->box_next_row_of_columns();

	$bx->box_column("","",$bgcolor,"<b>".timestr(mktimestamp($history[$i]))."</b>");
	$pepe = substr ($history[$i], -9);
	$bx->box_column("","",$bgcolor,"<b>".$what[$pepe]."</b>");
	$bx->box_column("","",$bgcolor,"<b>".$subject[$pepe]."</b>");
	$bx->box_column("","",$bgcolor,"<b>".$who[$pepe]."</b>");
  }

  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();

}

?>