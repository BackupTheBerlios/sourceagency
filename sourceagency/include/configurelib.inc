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
# Library with the functions for news
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
######################################################################

function configure_form($proid) {
  global $bx, $t, $sess, $preview, $quorum, $other_tech_contents, $other_developing_proposals, $consultants;

  if(!configure_first_time($proid) && !isset($preview)) configure_modify_form($proid,$user);

  $bx->box_begin();
  $bx->box_title($t->translate("Configure Project"));
  $bx->box_body_begin();
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  if (is_sponsor()) {
	$bx->box_column ("right","30%","","<b>".$t->translate("Quorum for decision making")."</b>: ");
  	$bx->box_column ("left","70%","",select_quorum($quorum));
  	$bx->box_next_row_of_columns();
  }

  if (is_project_initiator($proid)) {
  $bx->box_column ("right","30%","","<b>".$t->translate("Other tech_contents?")."</b>: ");
  $bx->box_column ("left","70%","",lib_select_yes_or_no("other_tech_contents",$other_tech_contents));
  }

  $bx->box_next_row_of_columns();

  if (is_developer()) {
  	$bx->box_column ("right","30%","","<b>".$t->translate("Other developing proposals?")."</b>: ");
  	$bx->box_column ("left","70%","",lib_select_yes_or_no("other_developing_proposals",$other_developing_proposals));
  	$bx->box_next_row_of_columns();
  }

  if (is_sponsor() && is_project_initiator($proid)) {
  	$bx->box_column ("right","30%","","<b>".$t->translate("Consultants?")."</b>: ");
  	$bx->box_column ("left","70%","",lib_select_yes_or_no("consultants",$consultants));
  }

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}


function configure_preview($proid) {
  global $t, $bx, $auth, $sess, $quorum, $other_tech_contents, $other_developing_proposals, $consultants;

  $bx->box_begin();
  $bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
  $bx->box_title($t->translate("Project Configuration"));
  $bx->box_body_begin();
  $timestamp = time();
  echo "<b>";
  $db_local = new DB_SourceAgency;
  $db_local->query("SELECT email_usr FROM auth_user WHERE username='".$auth->auth["uname"]."'");
  $db_local->next_record();
  lib_pnick($auth->auth["uname"]);
  echo " - ".timestr($timestamp)."</b><br>&nbsp;\n";
  if (is_sponsor()) echo "<br><b>Quorum</b>: $quorum %\n";
  if (is_project_initiator($proid))  echo "<br><b>Other tech_contents</b>: $other_tech_contents\n";
  if (is_developer())  echo "<br><b>Other developing proposals</b>: $other_developing_proposals\n";
  if (is_sponsor() && is_project_initiator($proid))  echo "<br><b>Consultants</b>: $consultants\n";
  $bx->box_body_end();
  $bx->box_end();
}


function configure_show($proid) {
  global $t, $bx, $db, $sess;

  $query = "SELECT * FROM configure WHERE proid='$proid'";
  $db->query($query);
  while($db->next_record()) {
	$bx->box_begin();
	$bx->box_title($t->translate("Project Configuration"));
	$bx->box_body_begin();
        echo "<br>";

    	if ($db->f("quorum") != "") echo "<p><b>Quorum</b>: ".$db->f("quorum")."%\n";
	else echo "<br><b>Quorum</b>: This decision is taken by the sponsors\n";

	echo "<br><b>Consultants</b>: ".$db->f("consultants")."\n";

    	echo "<br><b>Other_tech_contents</b>: ".$db->f("other_tech_contents")."\n";
    	echo "<br><b>Other_developing_proposals</b>: ".$db->f("other_developing_proposals")."\n";

    	if ($db->f("sponsor") != "") echo "<br><b>First Sponsor</b>: ".$db->f("sponsor")."\n";
	else echo "<br><b>First Sponsor</b>: No sponsors yet\n";

    	if ($db->f("developer") != "") echo "<br><b>Developer</b>: ".$db->f("developer")."\n";
	else echo "<br><b>Developer</b>: No main developer yet\n";
    	$bx->box_body_end();
    	$bx->box_end();
  }

  if ($db->num_rows() == 0) {
	print "<p>The project parameters have not been configured by the project owner(s).<p>\n";
  }
}

function configure_modify_form($proid) {
  global $quorum, $consultants, $other_tech_contents, $other_developing_proposals;

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT * FROM configure WHERE proid='$proid'");
	$db_local->next_record();
	$quorum = $db_local->f("quorum");
	$consultants = $db_local->f("consultants");
	$other_tech_contents = $db_local->f("other_tech_contents");
	$other_developing_proposals  = $db_local->f("other_developing_proposals");
}

function configure_insert($proid,$user) {
  global $db,$auth,$quorum,$consultants,$other_tech_contents,$other_developing_proposals;

  if(!configure_first_time($proid)) configure_modify($proid,$user);
  else {
	if (is_sponsor()) $query = "quorum='$quorum',consultants='$consultants',other_tech_contents='$other_tech_contents',other_developing_proposals='Yes',sponsor='$user',developer=''";
	else $query = "consultants='No',other_tech_contents='$other_tech_contents',other_developing_proposals='$other_developing_proposals',developer='$user'";

   	$db->query("INSERT configure SET proid='$proid',$query");
   	configure_show($proid);

	// Insert into history
  	$db->query("INSERT history SET proid='$proid', history_user='$user', type='Configure', action='Project configuration'");

  echo "<b>Congratulations</b>. You have just configured your project.<p>\n";
  if (is_sponsor()) {
  	echo "<br>The last step before waiting to be reviewed by a SourceAgency editor is to ".html_link("sponsoring_edit.php3",array("proid" => $proid),"Sponsor this project");
  } elseif (is_developer()) {
	lib_insertion_finished();
  }
  echo "<p><br>\n";

  lib_insertion_information();

  }
}

function configure_modify($proid,$user) {
  global $db,$auth,$quorum,$consultants,$other_tech_contents,$other_developing_proposals;

  if (project_type($proid)=="sponsored") {
  	if (is_sponsor()) $query = "quorum='$quorum',consultants='$consultants',other_tech_contents='$other_tech_contents',other_developing_proposals='Yes',sponsor='$user'";
	else $query = "developer='$user'";
  } else {
   	if (is_sponsor()) $query = "quorum='$quorum'"; 
  	else $query = "developer='$user'";
  }

  $db->query("UPDATE configure SET $query WHERE proid='$proid'");
  configure_show($proid);

  include("monitorlib.inc");
  include("config.inc");
  monitor_mail($proid,"configure", "Configuration modified for project $proid", "An event has happened.");

	// Insert into history
  $db->query("INSERT history SET proid='$proid', history_user='$user', type='Configure', action='Project configuration modified'");
}

function select_quorum($selected) {
  global $auth;

  $return = html_select("quorum");
  for ($i=11; $i < 20; $i++) {
 	if ($i*5 == $selected) $select="selected";
	else $select="";
	$return .= html_select_option(5*$i,$select,5*$i."%");
  }
  $return .=htmlp_select_end();

  return($return);
}

function configure_first_time($proid) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT * FROM configure WHERE proid='$proid'");
	if($db_local->num_rows() == 0) return 1;
	else return 0;
}

function project_type($proid) {

	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT perms FROM description,auth_user WHERE proid='$proid' AND description_user=username");
	$db_local->next_record();

	if ($db_local->f("perms") == "sponsor") return "sponsored";
	else return "developed";
}

?>