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
# $Id: contentlib.inc,v 1.2 2001/11/07 18:48:52 riessen Exp $
#
######################################################################

function show_content($proid,$show_proposals,$which_proposals) {
  global $t, $bx, $db, $sess;

  $query = "SELECT * FROM tech_content,auth_user WHERE proid='$proid' AND content_user=username ORDER BY creation";
  $db->query($query);
  while($db->next_record()) {
	$bx->box_begin();
	$bx->box_title($t->translate("Technical Content"));
	$bx->box_body_begin();
   	$timestamp = mktimestamp($db->f("creation"));
        echo "<b>";
	lib_pnick($db->f("content_user"));
	echo " - ".timestr($timestamp)."</b><p>\n";
    	echo "<br><b>Needed Skills:</b> ".$db->f("skills")."\n";
    	echo "<br><b>Plattform:</b> ".$db->f("platform")."\n";
    	echo "<br><b>Architecture:</b> ".$db->f("architecture")."\n";
    	echo "<br><b>Environment:</b> ".$db->f("environment")."\n";
	if ($db->f("docs") != "") echo "<br><b>Documentation:</b> ".html_link($db->f("docs"),array(),$db->f("docs"))."\n";
    	echo "<br><b>Status:</b> ".show_status($db->f("status"))."\n";
    	echo "<p><b>Technical Specification:</b> ".$db->f("specification")."\n";
        echo "<br>&nbsp;\n";
    	$bx->box_body_end();

	content_box_footer($proid,$db->f("content_id"),$which_proposals);
    	$bx->box_end();

	lib_comment_it($proid,"Specifications",$db->f("content_id"),"0","Comment on Specification #".$db->f("content_id"),"Comment This Specification");

	echo "<p>";

	if (!strcmp($show_proposals,"yes") && !strcmp($which_proposals,$db->f("content_id"))) show_proposals($proid,$db->f("content_id"));
  }

  if ($db->num_rows() == 0) {
	print "There have not been posted any technical content suggestionsto this project.\n";
  }
}

function content_preview($proid) {
  global $t, $bx, $auth, $sess,$skills,$platform,$architecture,$environment,$docs,$specification;

	$bx->box_begin();
	$bx->box_title($t->translate("Technical Content"));
	$bx->box_body_begin();
   	$timestamp = time();
	echo "<b>";
	lib_pnick($auth->auth["uname"]);
    	echo " - ".timestr($timestamp)."</b><p>\n";
    	echo "<br><b>Needed Skills:</b> $skills\n";
    	echo "<br><b>Plattform:</b> $platform\n";
    	echo "<br><b>Architecture:</b> $architecture\n";
    	echo "<br><b>Environment:</b> $environment\n";
	if ($docs) echo "<br><b>Documentation:</b> ".html_link($docs,array(),$docs)."\n";
    	echo "<br><b>Status:</b> Proposed\n";
    	echo "<p><b>Technical Specification:</b> $specification\n";
        echo "<br>&nbsp;\n";
    	$bx->box_body_end();

//	content_box_footer($proid,$db->f("content_id"),$which_proposals);
    	$bx->box_end();
}

function content_box_footer($proid,$content_id,$which_proposals) {
 global $bx,$db,$sess;

 $db_number = new DB_SourceAgency;
 $db_number->query("SELECT COUNT(*) FROM developing WHERE proid='$proid' AND content_id='$content_id'");
 $db_number->next_record();

 $bx->box_title_begin();

 $bx->box_columns_begin(3);
 $bx->box_column_start("left","50%",$th_box_title_bgcolor); 

 if (!strcmp($db->f("content_id"),$which_proposals)) htmlp_link("step2.php3",array(proid => $proid),"Proposals");
 else htmlp_link("step2.php3",array(proid => $proid, show_proposals => "yes", which_proposals => $db->f("content_id")),"Proposals");
 echo " [".$db_number->f("COUNT(*)")."] &nbsp;|&nbsp; ";

 $db_number->query("SELECT COUNT(*) FROM comments WHERE proid='$proid' AND type='Specifications' AND number='$content_id'");
 $db_number->next_record();

 if ($db_number->f("COUNT(*)") > 0) {
 	htmlp_link("comments.php3",array("proid" => $proid, "type" => "Specifications", "number" => $content_id),"Comments");
 	echo " [".$db_number->f("COUNT(*)")."]\n";
 } else {
	print "No Comments\n";
 }

 $bx->box_column_finish();
 $bx->box_column_start("right","50%",$th_box_title_bgcolor);
 htmlp_link("developing_edit.php3",array("proid" => $proid, "content_id" => $db->f("content_id")),"[ <b>Make a Proposal to this content!</b> ]&nbsp;");
 $bx->box_column_finish();
 $bx->box_columns_end();
 $bx->box_title_end();
}

function show_proposals($proid,$content_id) {
  global $db,$sess,$t;

// FIXME, FIXME, FIXME!!!
  require("config.inc");

  $bx_small = new box("80%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);

  $db_proposal = new DB_SourceAgency;
  $db_proposal->query("SELECT * FROM developing,auth_user WHERE proid='$proid' AND content_id='".$db->f("content_id")."' AND developer=username ORDER BY developing.creation");

  while($db_proposal->next_record()) {
  	$bx_small->box_begin();
	$bx_small->box_title($t->translate("Developing Proposal"));
	$bx_small->box_body_begin();
	$timestamp = mktimestamp($db_proposal->f("creation"));
	echo "<b>".lib_nick($db_proposal->f("username"))." - ".timestr($timestamp)."</b>\n";
	echo "<p><b>Cost:</b> ".$db_proposal->f("cost")." euros\n";
    	echo "<br><b>License:</b> ".$db_proposal->f("license")."\n";
    	echo "<br><b>Status:</b> ".show_status($db_proposal->f("status"))."\n";
	if ($db_proposal->f("cooperation") != "No") echo "<br><b>Cooperation:</b> ".html_link("cooperation_edit.php3",array("proid" => $proid, "devid" => $db_proposal->f("devid")),$db_proposal->f("cooperation") ."  &nbsp; [ <b>Cooperate</b> ]")."\n";
	else echo "<br><b>Cooperation:</b> ".$db_proposal->f("cooperation")."\n";
    	echo "<br><b>Valid:</b> ".timestr_middle(mktimestamp($db_proposal->f("valid")))."\n";
    	echo "<br><b>Start:</b> ".timestr_middle(mktimestamp($db_proposal->f("start")))."\n";
    	echo "<br><b>Duration:</b> ".$db_proposal->f("duration")." weeks\n";
        echo "<br>&nbsp;\n";
    	$bx_small->box_body_end();
        $bx_small->box_title_begin();

	$db_number = new DB_SourceAgency;
    	if ($db_proposal->f("cooperation") != "No") {
		$db_number->query("SELECT COUNT(*) FROM cooperation WHERE devid='".$db_proposal->f("devid")."'");
		$db_number->next_record();
		if ($db_number->f("COUNT(*)")) {
			htmlp_link("cooperation.php3",array("proid" => $proid, "devid" => $db_proposal->f("devid")),"Cooperation");
			echo " [".$db_number->f("COUNT(*)")."]";
		} else echo "Cooperation [".$db_number->f("COUNT(*)")."]";
	echo "&nbsp;|&nbsp; ";
  	}

	$db_number->query("SELECT COUNT(*) FROM comments WHERE proid='$proid' AND type='proposals' AND number='".$db_proposal->f("devid")."'");
 $db_number->next_record();
	if ($db_number->f("COUNT(*)") > 0) {
		htmlp_link("comments.php3",array("proid" => $proid, "type" => "proposals", "number" => $db_proposal->f("devid")),"Comments");
		echo " [".$db_number->f("COUNT(*)")."]\n";
	} else echo "Comments [0]";
   	$bx_small->box_title_end();
	$bx_small->box_end();

	print "<FONT SIZE=-1>[ <a href=\"".$sess->url("comments_edit.php3").$sess->add_query(array("proid" => $proid, "type" => "proposal", "number" => $db_proposal->f("devid")))."\">Comment This Proposal</a> ]</FONT><p>\n";
  }
}

function content_form($proid) {
  global $bx, $t, $sess,$skills,$platform,$architecture,$environment,$docs,$specification;

  $bx->box_begin();
  $bx->box_title($t->translate("Suggesting a technical content"));
  $bx->box_body_begin();
  print html_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Specification")."</b> (*): ");
  $bx->box_column ("left","70%","",html_textarea("specification",40,7,"virtual",255,$specification));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Neded Skills")."</b> (64): ");
  $bx->box_column ("left","70%","",html_input_text("skills",40,64,$skills));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Platform")."</b>: ");
  $bx->box_column ("left","70%","",select_from_config("platform","platform_array",$platform));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Architecture")."</b>: ");
  $bx->box_column ("left","70%","",select_from_config("architecture","architecture_array",$architecture));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Environment")."</b>: ");
  $bx->box_column ("left","70%","",select_from_config("environment","environment_array",$environment));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Further Documentation")."</b> (255): ");
  $bx->box_column ("left","70%","",html_input_text("docs",40,255,$docs));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function content_modify_form($proid) {
  global $bx, $t, $sess, $skills,$platform,$architecture,$environment,$docs,$specification,$creation;

  $bx->box_begin();
  $bx->box_title($t->translate("Modifying a technical content suggestion"));
  $bx->box_body_begin();
  print html_form_action("PHP_SELF",array("proid" => $proid),"POST");
  htmlp_form_hidden("creation",$creation);

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Specification")."</b> (*): ");
  $bx->box_column ("left","70%","",html_textarea("specification",40,7,"virtual",255,$specification));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Skills")."</b> (64): ");
  $bx->box_column ("left","70%","",html_input_text("skills",40,64,$skills));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Platform")."</b> (SELECT): ");
  $bx->box_column ("left","70%","",select_from_config("platform","platform_array",$platform));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Architecture")."</b> (SELECT): ");
  $bx->box_column ("left","70%","",select_from_config("architecture","architecture_array",$architecture));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Environment")."</b> (SELECT): ");
  $bx->box_column ("left","70%","",select_from_config("environment","environment_array",$environment));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Docs")."</b> (255): ");
  $bx->box_column ("left","70%","",html_input_text("docs",40,255,$docs));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function content_insert($proid,$user,$skills,$platform,$architecture,$environment,$docs,$specification) {
  global $db;

  $db->query("INSERT tech_content SET proid='$proid',content_user='$user',skills='$skills',platform='$platform',architecture='$architecture',environment='$environment',docs='$docs',specification='$specification',status='P'");
  show_content($proid,"","");
}

function content_modify($proid,$user,$skills,$platform,$architecture,$environment,$docs,$specification,$creation) {
  global $db;

  $db->query("UPDATE tech_content SET proid='$proid',content_user='$user',skills='$skills',platform='$platform',architecture='$architecture',environment='$environment',docs='$docs',specification='$specification', status='M' WHERE creation='$creation'");
  show_content($proid,"","");
}

function show_selected_content($proid) {
  global $t, $bx, $db;

  $query = "SELECT * FROM tech_content,auth_user WHERE proid='$proid' AND content_user=username AND tech_content.status='A'";
  $db->query($query);
  $db->next_record();

  $bx->box_begin();
  $bx->box_title($t->translate("Technical Content"));
  $bx->box_body_begin();
  $timestamp = mktimestamp($db->f("creation"));
  echo "<b>by ".lib_nick($db->f("username"))."</a> - ".timestr($timestamp)."</b>\n";
  echo "<p><b>Skills:</b> ".$db->f("skills")."\n";
  echo "<br><b>Plattform:</b> ".$db->f("platform")."\n";
  echo "<br><b>Architecture:</b> ".$db->f("architecture")."\n";
  echo "<br><b>Environment:</b> ".$db->f("environment")."\n";
  echo "<br><b>Documentation:</b> ".$db->f("docs")."\n";
  echo "<p><b>Technical Specification:</b> ".$db->f("specification")."\n";
  echo "<br>&nbsp;\n";
  $bx->box_body_end();
  $bx->box_end();
}

?>