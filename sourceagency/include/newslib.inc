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
# Library with the functions for news
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: newslib.inc,v 1.5 2002/01/28 02:20:00 riessen Exp $
#
######################################################################

function newsform($proid) {
  global $bx, $t, $sess, $subject, $text;

  $bx->box_begin();
  $bx->box_title($t->translate("Editing News"));
  $bx->box_body_begin();

  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","",
                   "<b>".$t->translate("Subject")."</b> (128): ");
  $bx->box_column ("left","70%","",html_input_text("subject",40,128,$subject));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","",
                   "<b>".$t->translate("Body")."</b> (*): ");
  $bx->box_column ("left","70%","",
                   html_textarea("text",40,7,"virtual",255,$text));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  // NOI18N in html_form_submit calls
  $bx->box_column ("left","70%","",
                   html_form_submit("Preview","preview")
                   .html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function news_modify_form($proid) {
  global $bx, $t, $sess, $subject, $text, $creation;

  $bx->box_begin();
  $bx->box_title($t->translate("Modifying News"));
  $bx->box_body_begin();

  print html_form_action("PHP_SELF",array("proid" => $proid),"POST");

  htmlp_form_hidden("creation",$creation);

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","",
                   "<b>".$t->translate("Subject")."</b> (128): ");
  $bx->box_column ("left","70%","",
                   html_input_text("subject",40,128,$subject));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Body")."</b> (*): ");
  $bx->box_column ("left","70%","",
                   html_textarea("text",40,7,"virtual",255,$text));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  // NOI18N in html_form_submit calls
  $bx->box_column ("left","70%","",
                   html_form_submit("Preview","preview")
                   . html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

// FIXME: $proid argument is not directly used in the function ....
function news_preview($proid) {
  global $t, $bx, $auth, $sess, $subject, $text;;

  $bx->box_begin();
  $bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
  $bx->box_title($t->translate("News").": ".$subject);
  $bx->box_body_begin();
  $timestamp = time();
  echo "<b>";
  // FIXME: what is the purpose of doing a db query here?
  $db_local = new DB_SourceAgency;
  $db_local->query("SELECT email_usr FROM auth_user WHERE username='"
                   . $auth->auth["uname"]."'");
  $db_local->next_record();
  lib_pnick($auth->auth["uname"]);
  echo " - ".timestr($timestamp)."</b>\n";
  echo "<p>$text\n";
  $bx->box_body_end();
  $bx->box_end();
}


function newsshow($proid) {
  global $t, $bx, $db, $sess;

  $query = ( "SELECT * FROM news,auth_user WHERE proid='$proid' "
             ."AND user_news=username ORDER BY creation_news DESC" );
  $db->query($query);
  while($db->next_record()) {
	$bx->box_begin();
	$bx->box_title($t->translate("News").": ".$db->f("subject_news"));
	$bx->box_body_begin();
   	$timestamp = mktimestamp($db->f("creation_news"));
        echo "<b>";
	lib_pnick($db->f("user_news"));
        echo " - ".timestr($timestamp)."</b>\n";
    	echo "<p>".$db->f("text_news")."\n";
    	$bx->box_body_end();
    	$bx->box_end();

	lib_comment_it( $proid,"News",$db->f("creation_news"),"0",
        "Re:" . $db->f("subject_news"), "Comment This News!");

	lib_show_comments_on_it($proid,"News",$db->f("creation_news"),"0");
  }

  if ($db->num_rows() == 0) {
	print ( "<p>There have not been posted any news by the "
                . "project owner(s).<p>\n" );
  }
}

function news_insert($proid,$user,$subject,$text) {
  global $db;

  $db->query("INSERT news SET proid='$proid',user_news='$user',"
             . "subject_news='$subject',text_news='$text'");

  if ( floor( phpversion() ) >= 4 ) {
    // this change allows unt test to test this and news_modify
    include_once("monitorlib.inc");
  } else {
    include("monitorlib.inc");
  }
  include("config.inc");

  monitor_mail($proid,"news", "New News", "New News on project $proid\n "
  . "Subject: $subject\n by $user\n\n You can see it at: ");
  // FIXME: the variables used in the following aren't defined ....
  // FIXME: $type_cmt, $number and $id
//  . "$sys_url"."comments.php3?proid=$proid&type=$type_cmt&number=$number&id=$id");

  newsshow($proid);
}

function news_modify($proid,$user,$subject,$text,$creation) {
  global $db;

  $db->query("UPDATE news SET user_news='$user', subject_news='$subject', "
             . "text_news='$text' WHERE proid='$proid' AND "
             . "creation_news='$creation'");

  if ( floor( phpversion() ) >= 4 ) {
    // this change allows unt test to test this and news_modify
    include_once("monitorlib.inc");
  } else {
    include("monitorlib.inc");
  }
    include("config.inc");

  monitor_mail($proid,"news", "News Modified", "Modification on News on "
  . "project $proid\n Subject: $subject\n by $user\n\n You can see it ");
  // FIXME: $type_cmt, $number and $id all undefined
//    . "at: $sys_url"."comments.php3?proid=$proid&type=$type_cmt"
//    . "&number=$number&id=$id");

  newsshow($proid);
}

?>