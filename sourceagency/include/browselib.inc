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
# $Id: browselib.inc,v 1.2 2001/11/07 18:48:52 riessen Exp $
#
######################################################################

function browse_through ($through) {
  global $bx, $t, $db;

  $bx->box_column_start("","50%","");
  $bx->box_begin();
  $bx->box_body_begin();
  switch ($through) {
	case "license": browse_licenses(); break;
	case "type": browse_type(); break;
	case "steps": browse_steps(); break;
	case "volume": browse_volume(); break;
	case "date": browse_not_yet(); break;
	case "platform": browse_platform(); break;
	case "architecture": browse_architecture(); break;
	case "environment": browse_environment(); break;
	case "audience": browse_not_yet(); break;
	case "os": browse_not_yet(); break;
	case "programming": browse_not_yet(); break;
  }
  $bx->box_body_end();
  $bx->box_end();
  $bx->box_column_finish();
}


function browse_licenses() {
  global $bx, $db, $t;

  $db->query("SELECT DISTINCT * FROM licenses");

  $bx->box_begin();
  $bx->box_title($t->translate("Licenses"));
  $bx->box_body_begin();
  $i = 1;

  $bx->box_columns_begin(3);
  $bx->box_column("right","6%","","<b>".$t->translate("No")."</b>");
  $bx->box_column("center","20%","","<b>#&nbsp;".$t->translate("Projects")."</b>");
  $bx->box_column("left","70%","","<b>".$t->translate("License")."</b>");
  $bx->box_next_row_of_columns();

  while($db->next_record()) {
  	if ($i%2 != 0) $bgcolor = "gold";
  	else $bgcolor = "#FFFFFF";

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM developing WHERE license='".$db->f("license")."' AND status='A'");
  	$db2->next_record();
  	$num = "[".sprintf("%03d",$db2->f("COUNT(*)"))."]";
  	$bx->box_column("right","",$bgcolor,$i);
	if ($num != "[000]") $bx->box_column("center","",$bgcolor,html_link("browse.php3",array("through" => "license", "license" => rawurlencode($db->f("license"))),$num));
	else $bx->box_column("center","",$bgcolor,$num);
  	$bx->box_column("left","",$bgcolor,html_link($db->f("url"),"",$db->f("license")));
  	$bx->box_next_row_of_columns();
  	$i++;
  }
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}


function browse_type() {
  global $bx, $t;

  require("config.inc");

  $bx->box_begin();
  $bx->box_title($t->translate("Type"));
  $bx->box_body_begin();

  $bx->box_columns_begin(3);
  $bx->box_column("right","6%","","<b>".$t->translate("No")."</b>");
  $bx->box_column("center","20%","","<b>#&nbsp;".$t->translate("Projects")."</b>");
  $bx->box_column("left","70%","","<b>".$t->translate("Type")."</b>");
  $bx->box_next_row_of_columns();

  for($i=0;$i < sizeof($project_types);$i++) {
  	if ($i%2 != 1) $bgcolor = "gold";
  	else $bgcolor = "#FFFFFF";

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM description WHERE type='".$project_types[$i]."' AND status>'0'");
  	$db2->next_record();
  	$num = "[".sprintf("%03d",$db2->f("COUNT(*)"))."]";
  	$bx->box_column("right","",$bgcolor,$i+1);
	if ($num != "[000]") $bx->box_column("center","",$bgcolor,html_link("browse.php3",array("through" => "type", "type" => $project_types[$i]),$num));
	else $bx->box_column("center","",$bgcolor,$num);
  	$bx->box_column("left","",$bgcolor,$project_types[$i]);
  	$bx->box_next_row_of_columns();
  }
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}


function browse_steps() {
  global $bx, $t;

  $bx->box_begin();
  $bx->box_title($t->translate("Steps"));
  $bx->box_body_begin();

  $bx->box_columns_begin(3);
  $bx->box_column("right","6%","","<b>".$t->translate("No")."</b>");
  $bx->box_column("center","20%","","<b>#&nbsp;".$t->translate("Projects")."</b>");
  $bx->box_column("left","70%","","<b>".$t->translate("Step")."</b>");
  $bx->box_next_row_of_columns();

  for($i=1;$i < 7;$i++) {
  	if ($i%2 != 0) $bgcolor = "gold";
  	else $bgcolor = "#FFFFFF";

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM description WHERE status = '$i'");
  	$db2->next_record();
  	$num = "[".sprintf("%03d",$db2->f("COUNT(*)"))."]";
  	$bx->box_column("right","",$bgcolor,$i);
	if ($num != "[000]") $bx->box_column("center","",$bgcolor,html_link("browse.php3",array("through" => "steps", "steps" => $i),$num));
	else $bx->box_column("center","",$bgcolor,$num);
  	$bx->box_column("left","",$bgcolor,"Step ".$i);
  	$bx->box_next_row_of_columns();
  }
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}


function browse_volume() {
  global $bx, $t;

  require("config.inc");

  $bx->box_begin();
  $bx->box_title($t->translate("Volume"));
  $bx->box_body_begin();

  $bx->box_columns_begin(3);
  $bx->box_column("right","6%","","<b>".$t->translate("No")."</b>");
  $bx->box_column("center","20%","","<b>#&nbsp;".$t->translate("Projects")."</b>");
  $bx->box_column("left","70%","","<b>".$t->translate("Volume")."</b>");
  $bx->box_next_row_of_columns();

  for($i=0;$i < sizeof($project_volume);$i++) {
  	if ($i%2 != 1) $bgcolor = "gold";
  	else $bgcolor = "#FFFFFF";

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM description WHERE volume='".$project_volume[$i]."' AND status>'0'");
  	$db2->next_record();
  	$num = "[".sprintf("%03d",$db2->f("COUNT(*)"))."]";
  	$bx->box_column("right","",$bgcolor,$i+1);
	if ($num != "[000]") $bx->box_column("center","",$bgcolor,html_link("browse.php3",array("through" => "volume", "volume" => $project_volume[$i]),$num));
	else $bx->box_column("center","",$bgcolor,$num);
  	$bx->box_column("left","",$bgcolor,$project_volume[$i]);
  	$bx->box_next_row_of_columns();
  }
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}


function browse_platform() {
  global $bx, $t;

  require("config.inc");

  $bx->box_begin();
  $bx->box_title($t->translate("Platform"));
  $bx->box_body_begin();

  $bx->box_columns_begin(3);
  $bx->box_column("right","6%","","<b>".$t->translate("No")."</b>");
  $bx->box_column("center","20%","","<b>#&nbsp;".$t->translate("Projects")."</b>");
  $bx->box_column("left","70%","","<b>".$t->translate("Volume")."</b>");
  $bx->box_next_row_of_columns();

  for($i=0;$i < sizeof($platform_array);$i++) {
  	if ($i%2 != 1) $bgcolor = "gold";
  	else $bgcolor = "#FFFFFF";

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM tech_content WHERE platform='".$platform_array[$i]."' AND status='A'");
  	$db2->next_record();
  	$num = "[".sprintf("%03d",$db2->f("COUNT(*)"))."]";
  	$bx->box_column("right","",$bgcolor,$i+1);
	if ($num !=0) $bx->box_column("center","",$bgcolor,html_link("browse.php3",array("through" => "platform", "platform" => $platform_array[$i]),$num));
	else $bx->box_column("center","",$bgcolor,$num);
  	$bx->box_column("left","",$bgcolor,$platform_array[$i]);
  	$bx->box_next_row_of_columns();
  }
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}


function browse_architecture() {
  global $bx, $t;

  require("config.inc");

  $bx->box_begin();
  $bx->box_title($t->translate("Architecture"));
  $bx->box_body_begin();

  $bx->box_columns_begin(3);
  $bx->box_column("right","6%","","<b>".$t->translate("No")."</b>");
  $bx->box_column("center","20%","","<b>#&nbsp;".$t->translate("Projects")."</b>");
  $bx->box_column("left","70%","","<b>".$t->translate("Architecture")."</b>");
  $bx->box_next_row_of_columns();

  for($i=0;$i < sizeof($architecture_array);$i++) {
  	if ($i%2 != 1) $bgcolor = "gold";
  	else $bgcolor = "#FFFFFF";

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM tech_content WHERE architecture='".$architecture_array[$i]."' AND status='A'");
  	$db2->next_record();
  	$num = "[".sprintf("%03d",$db2->f("COUNT(*)"))."]";
  	$bx->box_column("right","",$bgcolor,$i+1);
	if ($num != "[000]") $bx->box_column("center","",$bgcolor,html_link("browse.php3",array("through" => "architecture", "architecture" => $architecture_array[$i]),$num));
	else $bx->box_column("center","",$bgcolor,$num);
  	$bx->box_column("left","",$bgcolor,$architecture_array[$i]);
  	$bx->box_next_row_of_columns();
  }
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}


function browse_environment() {
  global $bx, $t;

  require("config.inc");

  $bx->box_begin();
  $bx->box_title($t->translate("Environment"));
  $bx->box_body_begin();

  $bx->box_columns_begin(3);
  $bx->box_column("right","6%","","<b>".$t->translate("No")."</b>");
  $bx->box_column("center","20%","","<b>#&nbsp;".$t->translate("Projects")."</b>");
  $bx->box_column("left","70%","","<b>".$t->translate("Environment")."</b>");
  $bx->box_next_row_of_columns();

  for($i=0;$i < sizeof($environment_array);$i++) {
  	if ($i%2 != 1) $bgcolor = "gold";
  	else $bgcolor = "#FFFFFF";

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM tech_content WHERE environment='".$environment_array[$i]."' AND status='A'");
  	$db2->next_record();
  	$num = "[".sprintf("%03d",$db2->f("COUNT(*)"))."]";
  	$bx->box_column("right","",$bgcolor,$i+1);
	if ($num != "[000]") $bx->box_column("center","",$bgcolor,html_link("browse.php3",array("through" => "environment", "environment" => $environment_array[$i]),$num));
	else $bx->box_column("center","",$bgcolor,$num);
  	$bx->box_column("left","",$bgcolor,$environment_array[$i]);
  	$bx->box_next_row_of_columns();
  }
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}


function browse_not_yet() {
  global $bx, $t;

  require("config.inc");

  $bx->box_begin();
  $bx->box_title($t->translate("Not yet available"));
  $bx->box_body_begin();

  echo "&nbsp;\n";

  $bx->box_body_end();
  $bx->box_end();
}

function browse_list($by,$what) {

  switch($by) {
	case "license":
		$query = "SELECT * FROM description,developing WHERE description.proid=developing.proid AND license='$what'";
		break;
	case "type":
		$query = "SELECT * FROM description WHERE type='$what'";
		break;
	case "steps":
		$query = "SELECT * FROM description WHERE status='$what'";
		break;
	case "volume":
		$query = "SELECT * FROM description WHERE volume='$what'";
		break;
	case "date":
		$query = "SELECT * FROM description WHERE creation='$what'";
		break;
	case "platform":
		$query = "SELECT * FROM description,tech_content WHERE description.proid=tech_content.proid AND platform='$what'";
		break;
	case "architecture":
		$query = "SELECT * FROM description,tech_content WHERE description.proid=tech_content.proid AND architecture='$what'";
		break;
	case "environment":
		$query = "SELECT * FROM description,tech_content WHERE description.proid=tech_content.proid AND environment='$what'";
		break;
	case "audience":
		$query = "SELECT * FROM description WHERE ='$what'";
		break;
	case "os":
		$query = "SELECT * FROM description WHERE ='$what'";
		break;
	case "language":
		$query = "SELECT * FROM description WHERE ='$what'";
		break;
  }
  browse_output($query);
}

function browse_output($query) {
  global $db, $bx;

  $db->query($query);
  while($db->next_record()) {
	lib_show_description("SELECT * FROM description WHERE proid='".$db->f("proid")."'");
  }
}

?>
