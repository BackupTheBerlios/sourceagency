<?php

######################################################################
# SourceAgency:
# ===================================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Lutz Henckel (lutz.henckel@fokus.gmd.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Project monitoring library
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
######################################################################

function monitor_form($proid) {
  global $bx, $t, $sess, $importance;

  $bx->box_begin();
  $bx->box_title($t->translate("Monitor this project"));
  $bx->box_body_begin();
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Importance")."</b>: ");
  $bx->box_column ("left","70%","",select_importance($importance));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function monitor_preview($proid) {
  global $t, $bx, $auth, $sess, $importance;

  $bx->box_begin();
  $bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
  $bx->box_title($t->translate("Monitoring this project"));
  $bx->box_body_begin();
  $timestamp = time();
  echo "<b>";
  $db_local = new DB_SourceAgency;
  $db_local->query("SELECT email_usr FROM auth_user WHERE username='".$auth->auth["uname"]."'");
  $db_local->next_record();
  lib_pnick($auth->auth["uname"]);
  echo " - ".timestr($timestamp)."</b>\n";
  echo "<p>You will recieve an email for every action in this project that has <b>$importance</b> importance.\n";
  $bx->box_body_end();
  $bx->box_end();
}

function monitor_show($proid) {
  global $t, $bx, $db, $sess;

  $query = "SELECT * FROM monitor,auth_user WHERE proid='$proid' AND monitor.username=auth_user.username ORDER BY creation DESC";
  $db->query($query);
  if ($db->num_rows() != 0) {
  	$bx->box_begin();
  	$bx->box_title($t->translate("All this users are monitor this project"));
  	$bx->box_body_begin();
  	$bx->box_columns_begin(5);

  	$bx->box_column("center","10%","","<b>Number</b>");
  	$bx->box_column("center","20%","","<b>Username</b>");
  	$bx->box_column("center","20%","","<b>Type</b>");
  	$bx->box_column("center","20%","","<b>Importance filter</b>");
  	$bx->box_column("center","30%","","<b>Creation</b>");
	$i=1;

  	while($db->next_record()) {
		if ($i%2 != 0) $bgcolor = "gold";
		else $bgcolor = "#FFFFFF";

		$bx->box_next_row_of_columns();

		$bx->box_column("center","",$bgcolor,"<b>$i</b>");
		$bx->box_column("center","",$bgcolor,"<b>".lib_nick($db->f("username"))."</b>");
		$bx->box_column("center","",$bgcolor,"<b>".$db->f("perms")."</b>");
		$bx->box_column("center","",$bgcolor,"<b>".$db->f("importance")."</b>");
		$bx->box_column("center","",$bgcolor,"<b>".timestr_middle(mktimestamp($db->f("creation")))."</b>");
		$i++;
  	}
	$bx->box_columns_end();
  	$bx->box_body_end();
  	$bx->box_end();

  } else print "<p>Nobody is monitoring this project.<p>\n";
}

function monitor_insert($proid,$username,$importance) {
  global $db;

  $db->query("SELECT * FROM monitor WHERE proid='$proid' AND username='$username'");
  if ($db->num_rows() >0) {
	$db->next_record();
	monitor_modify($proid,$username,$importance,$db->f("creation"));
  } else { 
	$db->query("INSERT monitor SET proid='$proid',username='$username',importance='$importance'");

	include("config.inc");
	monitor_mail($proid,"monitor", "User $username monitors project $proid", "Event has happened");

	monitor_show($proid);
  }
}

function monitor_modify($proid,$username,$importance,$creation) {
  global $db;

  $db->query("UPDATE monitor SET importance='$importance' WHERE proid='$proid' AND username='$username'");

  include("config.inc");
  monitor_mail($proid,"monitor", "User $username does not monitor project $proid any more", "Event has happened");

  monitor_show($proid);
}

function nlmsg($period) {
  global $db;

  switch ($period) {
    case "weekly":
      $lastday = time() - 7 * 24 * 60 * 60;
      $where = "DATE_FORMAT(projects.creation,'%Y-%m-%d')<=\"".date("Y-m-d")."\" AND DATE_FORMAT(projects.creation,'%Y-%m-%d')>\"".date("Y-m-d",$lastday)."\"";
      break;
    case "daily":
    default:
      $where = "DATE_FORMAT(projects.creation,'%Y-%m-%d')=\"".date("Y-m-d")."\"";
      break;
  }

  $db->query("SELECT *,SUM(pro_cnt+docs_cnt) AS sum_cnt FROM projects, counter, auth_user WHERE $where AND projects.proid=counter.proid AND projects.user=auth_user.username AND projects.status='A' GROUP BY projects.proid ORDER BY projects.creation DESC"); 

  if ($db->num_rows() <= 0) return 0;

  $msg = $GLOBALS["sys_name"]." $period newsletter for ".date("l, dS of F Y, H:i:s T")."\n";
  $msg .= "Number of announcements: ".$db->num_rows()."\n";
  $msg .= "\n               -----------------------------\n";
  $msg .= "                   Annoucements Headlines\n";
  $msg .= "               -----------------------------\n\n";
	
  $i = 1;
  while($db->next_record()) {
    $msg .= "$i: ".$db->f("title")." ";
    $msg .= "(".typestr($db->f("type")).")\n";
    $i++;
  }

  $msg .= "\n               -----------------------------\n";
  $msg .= "                   Annoucements Details\n";
  $msg .= "               -----------------------------\n";

//  @mysql_data_seek($result, 0);
  $db->seek(0);

  $i = 1;
  while ($db->next_record()) {
    $timestamp = mktimestamp($db->f("creation"));
    $msg .= "\nAnnouncement : $i\n";
    $msg .= "Name         : ".$db->f("title")."\n";
    $msg .= "Date         : ".date("l, dS of F Y, H:i:s T", $timestamp)."\n";
    $msg .= "Type         : ";
    $msg .= typestr($db->f("type"))."\n";
    if ($period == "daily") {
      $msg .= "License      : ".$db->f("license")."\n";
      $msg .= "Section      : ".$db->f("section")."\n";
      $msg .= "Category     : ".$db->f("category")."\n";
      $msg .= "Importance   : ".$db->f("sum_cnt")."\n";
      $msg .= "Urgency      : ".urgency($db->f("urgency"))."\n";
      $db_homepage = $db->f("homepage");
      $db_download = $db->f("download");
      $db_changelog = $db->f("changelog");
      $db_rpm = $db->f("rpm");
      $db_deb = $db->f("deb");
      $db_tgz = $db->f("tgz");
      $db_cvs = $db->f("cvs");
      $db_screenshots = $db->f("screenshots");
      $db_mailarch = $db->f("mailarch");
      $db_email = $db->f("email");
      $db_depend = $db->f("depend");
      if (!empty($db_homepage))
	$msg .= "\nHomepage     : ".$db->f("homepage")."\n";
      if (!empty($db_download))
	$msg .= "Download     : ".$db->f("download")."\n";
      if (!empty($db_changelog))
	$msg .= "Changelog    : ".$db->f("changelog")."\n";
      if (!empty($db_rpm))
	$msg .= "RPM Package  : ".$db->f("rpm")."\n";
      if (!empty($db_deb))
	$msg .= "Deb Package  : ".$db->f("deb")."\n";
      if (!empty($db_tgz))
	$msg .= "tgz Package  : ".$db->f("tgz")."\n";
      if (!empty($db_cvs))
	$msg .= "CVS Tree     : ".$db->f("cvs")."\n";
      if (!empty($db_screenshots))
	$msg .= "Screenshots  : ".$db->f("screenshots")."\n";
      if (!empty($db_mailarch))
	$msg .= "Mailing List Archive : ".$db->f("mailarch")."\n";
      $msg .= "\nDescription : \n".wrap($db->f("description"))."\n";
      $msg .= "\nAuthor       : ".$db->f("developer")."\n";
      if (!empty($db_email))
	$msg .= "Email        : ".$db->f("email")."\n";
      if (!empty($db_depend))
	$msg .= "Depens on    : ".$db->f("depend")."\n";
      $msg .= "\n";
    }
    $msg .= $GLOBALS["sys_name"]."   : ".$GLOBALS["sys_url"]."appbyid.php3?id=".$db->f("proid")."\n";
    $msg .= "\n               -----------------------------\n";
    $i++;
  }
  $msg .= "\nYou get this ".$GLOBALS["sys_name"]." $period newsletter,"
  ."\nbecause you have subscribed to the mailing list ";

  switch ($period) {
    case "weekly":
      $msg .= "\n".$GLOBALS["ml_weeklynewstoaddr"]."."
      ."\nTo unsubscribe from this mailing list,"
      ."\nsend a message by email to"
      ."\n".$GLOBALS["ml_weeklynewsreqaddr"]
      ."\nwith \"unsubscribe <password>\" as subject or visit"
      ."\n".$GLOBALS["ml_weeklylisturl"];
      break;
    case "daily":
    default:
      $msg .= "\n".$GLOBALS["ml_newstoaddr"]."."
      ."\nTo unsubscribe from this mailing list,"
      ."\nsend a message by email to"
      ."\n".$GLOBALS["ml_newsreqaddr"]
      ."\nwith \"unsubscribe <password>\" as subject or visit"
      ."\n".$GLOBALS["ml_listurl"];
      break;
  }
  $msg .= "\nand follow the instructions there."
  ."\n\n - ".$GLOBALS["sys_name"]." crew";

  return $msg;
}

function mailuser($perms, $subj, $message) {
  global $t, $db;
  $db->query("SELECT email_usr FROM auth_user WHERE perms LIKE '%$perms%'");
  while($db->next_record()) {
    mail($db->f("email_usr"),"[".$GLOBALS["sys_name"]."] ".$subj,$message,"From: ".$GLOBALS["ml_fromaddr"]."\nReply-To: ".$GLOBALS["ml_replyaddr"]."\nX-Mailer: PHP");
  }
}

function monitor_mail($proid,$type, $subj, $message) {
  global $t;

  $db_monitor = new DB_SourceAgency;

  include("config.inc");
  $variable = "config_monitor_importance_".$type;
  $importance = $$variable;

  $message .= "\n\nImportance of this event: $importance\n\nYou receive this e-mail because you monitor this project\n\nBerliOS SourceAgency http://sourceagency.berlios.de\n";

  if($importance == "high") $importance = "AND importance='$importance'";
  elseif ($importance == "middle") $importance = "AND (importance='$importance' OR importance='high')";
  else $importance = "";

  $db_monitor->query("SELECT email_usr FROM auth_user,monitor WHERE monitor.username=auth_user.username AND proid='$proid' $importance");
  while($db_monitor->next_record()) {
    	mail($db_monitor->f("email_usr"),"[".$GLOBALS["sys_name"]."] ".$subj,$message,"From: ".$GLOBALS["ml_fromaddr"]."\nReply-To: ".$GLOBALS["ml_replyaddr"]."\nX-Mailer: PHP");
  }
}

function select_importance($selected) {
  global $auth;

  $return = html_select("importance");
  if($selected=="low") $select="selected";
  $return .= html_select_option("low",$select,"low");
  if($selected=="medium") $select="selected";
  $return .= html_select_option("medium",$select,"medium");
  if($selected=="high") $select="selected";
  $return .= html_select_option("high",$select,"high");
  $return .=htmlp_select_end();

  return($return);
}
?>
