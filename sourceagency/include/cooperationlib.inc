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
# 
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: cooperationlib.inc,v 1.2 2001/11/07 18:48:52 riessen Exp $
#
######################################################################

function cooperation_form($proid,$devid) {
  global $bx, $t, $cost;

  $bx->box_begin();
  $bx->box_title($t->translate("Cooperation"));
  $bx->box_body_begin();
  htmlp_form_action("PHP_SELF",array("proid" => $proid, "devid" => $devid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Cost in euro")."</b> (7): ");
  $bx->box_column ("left","70%","",html_input_text("cost",7,7,$cost));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function cooperation_preview($proid,$devid) {
  global $t, $bx, $auth, $sess, $cost;

  $bx->box_begin();
  $bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
  $bx->box_title($t->translate("Cooperation"));
  $bx->box_body_begin();
  $timestamp = time();
  echo "<b>";
  lib_pnick($auth->auth["uname"]);
  echo " - ".timestr($timestamp)."</b>\n";
  echo "<p><b>Cost</b>: $cost euro\n";
  $bx->box_body_end();
  $bx->box_end();
}


function cooperation_show($proid,$devid) {
  global $t, $bx, $db, $sess;

  $query = "SELECT * FROM cooperation,auth_user WHERE devid='$devid' AND developer=username ORDER BY creation DESC";
  $db->query($query);
  while($db->next_record()) {
	$bx->box_begin();
	$bx->box_title($t->translate("Cooperation"));
	$bx->box_body_begin();
   	$timestamp = mktimestamp($db->f("creation"));
        echo "<b>";
	lib_pnick($db->f("developer"));
        echo " - ".timestr($timestamp)."</b>\n";
    	echo "<p><b>Cost</b>: ".$db->f("cost")." euro\n";
    	echo "<br><b>Status</b>: ".show_status($db->f("status"))."\n";
    	$bx->box_body_end();
    	$bx->box_end();

	lib_comment_it($proid,"Cooperation",$db->f("creation"),"0","","Comment This Cooperation!");

	lib_show_comments_on_it($proid,"Cooperation",$db->f("creation"),"0");
  }

  if ($db->num_rows() == 0) {
	print "<p>There have not been posted any cooperation proposals by any developer.<p>\n";
  }
}

function cooperation_insert($devid,$developer,$cost) {
  global $db;

  $db->query("INSERT cooperation SET devid='$devid',developer='$developer',cost='$cost',status='P'");
  cooperation_show($proid,$devid);
}

function cooperation_modify($devid,$developer,$cost,$creation) {
  global $db;

  $db->query("UPDATE cooperation SET developer='$developer', cost='$cost' WHERE devid='$devid' AND creation='$creation'");
  cooperation_show($proid,$devid);
}

?>