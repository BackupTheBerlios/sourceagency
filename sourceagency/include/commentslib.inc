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
# Library with the functions for comments
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: commentslib.inc,v 1.3 2001/11/09 20:33:31 riessen Exp $
#
######################################################################

#######
#
# Function comments_show() can be used in several ways
#
# it just depends on the number of arguments that are passed to it
#
#########
#
# comments_show($proid,$type,$number,"","")
#
# This will show all the comments of the type $type made on number $number
# There will be no tree structure. All comments (originals and responses are treated equally)
#
#########
#
# comments_show($proid,$type,$number,"",$ref)
#
# This will show comments of the type $type made on number $number in tree form
# This should show the tree having $ref as root
# For seeing the whole tree, $ref should be 0
#
#########
#
# comments_show($proid,$type,$number,$id,"")
#
# This shows only that specific comment with that id
# (and that referes to the specified type and to the given number)
#
#########
#
# comments_show($proid,$type,$number,$id,$ref)
#
# This will show all comments of the same level (given by ref) and its subree
# By means of an anchor, the displayed comment is that with the given id
#
#########

function comments_show($proid,$type_cmt,$number,$cmt_id,$ref) {
  global $t, $bx, $db, $sess;

  if (isset($number) && !empty($number)) {
    $number_selection = "AND number='$number'";
  } else {
    $number_selection = "AND number='0'";
  }

  if (isset($cmt_id) && !empty($cmt_id)) $id_selection = "AND id='$cmt_id'";
  else $id_selection = "";

  if (isset($ref) && !empty($ref)) $ref_selection = "AND ref='$ref'";
  else $ref_selection = "AND ref='0'";

  $db->query("SELECT * FROM comments,auth_user WHERE proid='$proid' "
             ."AND type='$type_cmt' ".$number_selection." ".$id_selection
             ." ".$ref_selection." AND user_cmt=username ORDER BY "
             ."creation_cmt ASC");
  while($db->next_record()) {
	htmlp_anchor($db->f("id"));
    	$bx->box_begin();
    	$bx->box_title($t->translate("Comment")." #".$db->f("id").": "
                       .stripslashes($db->f("subject_cmt")));
    	$bx->box_body_begin();
    	$timestamp = mktimestamp($db->f("creation_cmt"));
        echo "<b>";
	lib_pnick($db->f("user_cmt"));
        echo " - ".timestr($timestamp)."</b>\n";
    	echo "<p>".$db->f("text_cmt")."\n";

   	$bx->box_body_end();
   	$bx->box_end();

	lib_comment_it($proid,$db->f("type"),$db->f("number"),$db->f("id"),
                       "Re:".$db->f("subject_cmt"),"Comment it!");

	$db_ref = new DB_SourceAgency;
	$db_ref->query("SELECT ref FROM comments WHERE proid='$proid' "
                       ."AND type='".$db->f("type")."' AND id='"
                       .$db->f("ref")."'");
	$db_ref->next_record();
	lib_previous_comment($proid,$db->f("type"),$db->f("number"),
                             $db_ref->f("ref"),"Previous");

  	lib_show_comments_on_it($proid,$type_cmt,$number,$db->f("id"));
  }
}


function comments_form($proid) {
  global $bx, $t, $auth, $sess, $subject, $text, $number, $ref, $type;

 $bx->box_begin();
  $bx->box_title($t->translate("Your Comment"));
  $bx->box_body_begin();
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");
  htmlp_form_hidden("type",$type);
  htmlp_form_hidden("number",$number);
  htmlp_form_hidden("ref",$ref);

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>"
                   .stripslashes($t->translate("Subject"))."</b> (128): ");
  $bx->box_column ("left","70%","",html_input_text("subject",40,128,
                                                   stripslashes($subject)));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>"
                   .stripslashes($t->translate("Body"))."</b> (*): ");
  $bx->box_column ("left","70%","",html_textarea("text",40,7,"virtual",255,
                                                 stripslashes($text)));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview")
                   .html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function comments_preview($proid) {
  global $t, $bx, $auth, $sess,  $subject, $text, $number, $ref, $type;

    	$bx->box_begin();
	$bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
    	$bx->box_title($t->translate("Comment")." ".stripslashes($subject));
    	$bx->box_body_begin();
	$timestamp = time();
        echo "<b>";
	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT email_usr FROM auth_user WHERE username='"
                         .$auth->auth["uname"]."'");
	$db_local->next_record();
	lib_pnick($auth->auth["uname"]);
        echo " - ".timestr($timestamp)."</b>\n";
    	echo "<p>".stripslashes($text)."\n";

   	$bx->box_body_end();
   	$bx->box_end();
}


function comments_insert($proid,$user,$type_cmt,$number,$ref,$subject,$text) {
  global $db;

  $db->query("SELECT * FROM comments WHERE proid='$proid' AND "
             ."type='$type_cmt' AND number='$number'");
  $db->next_record();
  $id = $db->num_rows()+1;

  $db->query("INSERT comments SET proid='$proid',user_cmt='$user',"
             ."type='$type_cmt',number='$number',id='$id',ref='$ref',"
             ."subject_cmt='$subject',text_cmt='$text'");

  include("monitorlib.inc");
  include("config.inc");
  // FIXME: is 'numbe=' a typo in the following url?
  monitor_mail($proid,"comments", "New Comment", "New Comment on project "
               ."$proid\n Subject: $subject\n by $user\n\n You can see it "
               ."at: $sys_urlcomments.php3?proid=$proid&type=$type_cmt&"
               ."numbe=$number&id=$id");

  comments_show($proid,$type_cmt,$number,$id,$ref);
}

function comments_modify($proid,$user,$type_cmt,$number,$cmt_id,
                         $ref,$subject,$text) {
  global $db;

  $db->query("UPDATE comments SET user_cmt='devel', subject_cmt='$subject', "
             ."text_cmt='$text' WHERE proid='$proid' AND type='$type_cmt'  "
             ."AND number='$number' AND id='$cmt_id' AND ref='$ref'");

  include("monitorlib.inc");
  include("config.inc");
  // FIXME: is 'numbe=' a typo in the following
  monitor_mail($proid,"comments", "Comment modified", "Modification on a "
               ."comment on project $proid\n Subject: $subject\n by "
               ."$user\n\n You can see it at: $sys_urlcomments.php3?"
               ."proid=$proid&type=$type_cmt&numbe=$number&id=$id");

  comments_show($proid,$type_cmt,$number,$cmt_id,$ref);
}


function comments_modify_form($proid) {
  global $bx, $t, $sess, $subject, $text, $type, $number, $cmt_id, $ref, $creation;

  $bx->box_begin();
  $bx->box_title($t->translate("Modifying Comments"));
  $bx->box_body_begin();
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");
  htmlp_form_hidden("creation",$creation);
  htmlp_form_hidden("number",$number);

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>"
                   .stripslashes($t->translate("Subject"))."</b> (128): ");
  $bx->box_column ("left","70%","",html_input_text("subject",40,128,
                                                   stripslashes($subject)));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".stripslashes($t->translate("Body"))
                   ."</b> (*): ");
  $bx->box_column ("left","70%","",html_textarea("text",40,7,"virtual",255,
                                                 stripslashes($text)));
  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",html_form_submit("Preview","preview")
                   .html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function comments_missing_parameters() {
  global $t;

  require("config.inc");
  $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                $th_box_title_bgcolor,$th_box_title_font_color,
                $th_box_title_align,$th_box_body_bgcolor,
                $th_box_error_font_color,$th_box_body_align);

  $be->box_full($t->translate("Error"), $t->translate("Missing Parameters"));
}

?>