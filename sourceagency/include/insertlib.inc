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
# $Id: insertlib.inc,v 1.3 2001/11/15 18:07:44 riessen Exp $
#
######################################################################

function insert_form() {
  global $bx, $t, $project_title, $volume, $type, $description;

  $bx->box_begin();
  $bx->box_title($t->translate("New Project"));
  $bx->box_body_begin();
  htmlp_form_action("insert.php3",array(),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Project Title")
                   ."</b> (128): ");
  $bx->box_column ("left","70%","",
                   html_input_text("project_title",40,128,$project_title));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Type")."</b>: ");
  $bx->box_column ("left","70%","",
                   select_from_config("type","project_types",$type));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Brief description")
                   ."</b> (*): ");
  $bx->box_column ("left","70%","",html_textarea("description",40,7,"virtual",
                                                 255,$description));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Project Volume")
                   ."</b>: ");
  $bx->box_column ("left","70%","",
                   select_from_config("volume","project_volume",$volume));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Submit","Submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();  
}

/* We look if the insertion has all the fields we require */

function everything_filled($project_title,$description) {

  if (!isset($project_title) || empty($project_title)) {
	project_title_not_set();
    	return 0;
  }
  if (!isset($description) || empty($description)) {
	description_not_set();
   	return 0;
  }
  return 1;
}

function project_title_not_set() {
  global $t, $sess, $description,$type,$volume;

  require("config.inc");
  $be = new box("",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);

  $be->box_full($t->translate("Error"), $t->translate("No Project Title specified")."."
  ."<br>".$t->translate("Please select")." <a href=\"".$sess->url("insform.php3").$sess->add_query(array("description" => $description, "type" => $type, "volume" => $volume))."\">".$t->translate("New Projects")."</a>");
}

function description_not_set() {
  global $t, $sess, $project_title,$type,$volume;

  require("config.inc");
  $be = new box("",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);

  $be->box_full($t->translate("Error"), $t->translate("No Project Description specified")."."
  ."<br>".$t->translate("Please select")." <a href=\"".$sess->url("insform.php3").$sess->add_query(array("project_title" => $project_title, "type" => $type, "volume" => $volume))."\">".$t->translate("New Projects")."</a>");
}

	/* We look if everything that has been submitted is ok:
		that means: no other project exists with the same title */

function no_other_project_with_same_title($project_title) {
  global $db, $t;

  $query="SELECT * FROM description WHERE project_title='$project_title'";
  $db->query($query);
  if ($db->num_rows() == 1) {
	$be = new box("",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);
	$be->box_full($t->translate("Error"), $t->translate("Another project with that title already exists")."."
    	."<br>".$t->translate("Please select")." ".html_link("insform.php3",array(),$t->translate("New Projects")));
    	return 0;
  }
  return 1;
}

function insert_into_database($project_title,$description,$type,$volume) {
  global $db, $auth, $t;

  $status = 0;

  $tables = "description";
  $set = "project_title='$project_title',type='$type',description='$description', description_user='".$auth->auth["uname"]."', volume='$volume', status='$status', description_creation=NOW()";
  $db->query("INSERT $tables SET $set");
  if ($db->affected_rows =0) $be->box_full($t->translate("Error"), $t->translate("Database error"));
					// Get project index
  $columns = "*";
  $where = "project_title='$project_title'";

  $db->query("SELECT $columns FROM $tables WHERE $where");
  $db->next_record();

				// Insert default views
  $tables = "views";
  $set = "configure='Project Participants', views='Project Participants', news='Project Initiator', history='Everybody', step3='Everybody', step4='Everybody', step5='Everybody',cooperation='Everybody',proid=".$db->f("proid");
  $db->query("INSERT $tables SET $set");

				// Insert new counters
  $tables = "counter";
  $set = "proid=".$db->f("proid");
  $db->query("INSERT $tables SET $set");

				// Select and show new/updated project with counters
  $columns = "*";
  $tables = "description,auth_user";
  $where = "description.proid='".$db->f("proid")."' AND description.description_user=auth_user.username";
  $group = "description.proid";



  $query  = "SELECT $columns FROM $tables WHERE $where GROUP BY $group";
  lib_show_description($query);

  echo "Congratulations. You have just inserted the project.\n";
  echo "<br>The next step is to ".html_link("configure_edit.php3",array("proid" => $db->f("proid")),"configure your project!");
  echo "<p><br>\n";

  lib_insertion_information();

  if ($ml_notify) {
  	$msg = "insert project $project_title by ".$auth->auth["uname"].".";
      	mailuser("editor", "Project inserted", $msg);
  }
}

?>