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
# $Id: viewslib.inc,v 1.8 2002/09/11 11:27:37 helix Exp $
#
######################################################################

function views_form($proid) {
  global $bx, $t, $sess, $db, $preview, $configure, $news, $comments, $history, $step3, $step4, $step5, $cooperation, $views;

  // FIXME: the following can be removed, the results of the query
  // FIXME: are never used
  if(!isset($preview)) {
      $db->query("SELECT * FROM views WHERE proid='$proid'");
      $db->next_record();
  }

  $bx->box_begin();
  $bx->box_title($t->translate("Configure Information Access Rights for "
                               ."this Project"));
  $bx->box_body_begin();
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  $nbsp = ' &nbsp; &nbsp; &nbsp; ';
  $reco = $t->translate( 'Recommended' );
  $ebody = $t->translate( 'Everybody' );
  $ppart = $t->translate( 'Project Participants' );
  $reged = $t->translate( 'Registered' );
  $pinit = $t->translate( 'Project Initiator' );

  // 1
  $bx->box_column ("right","30%","","<b>"
                   .$t->translate("View Project Configuration")."</b>: ");
  $bx->box_column ("left","70%","",
                   views_select_view($proid,"configure",
                   $configure).$nbsp."(".$reco . ": <i>".$ppart."</i>)");
  $bx->box_next_row_of_columns();

  // 2
  $bx->box_column ("right","30%","","<b>"
                   .$t->translate("Write and Modify News")."</b>: ");
  $bx->box_column ("left","70%","",
                   views_select_view($proid,"news",$news).$nbsp."("
                   .$reco . ": <i>".$pinit."</i>)");
  $bx->box_next_row_of_columns();

  // 3
  $bx->box_column ("right","30%","","<b>"
                   .$t->translate("Write Comments")."</b>: ");
  $bx->box_column ("left","70%","",
                   views_select_view($proid,"comments",$comments).$nbsp
                   ."(".$reco . ": <i>".$reged."</i>)");
  $bx->box_next_row_of_columns();

  // 4
  $bx->box_column ("right","30%","","<b>"
                   .$t->translate("View Project History")."</b>: ");
  $bx->box_column ("left","70%","",
                   views_select_view($proid,"history",$history).$nbsp."("
                   .$reco . ": <i>".$ebody."</i>)");
  $bx->box_next_row_of_columns();

  // 5
  $bx->box_column ("right","30%","","<b>"
                   .$t->translate("View Step 3 (Milestones)")."</b>: ");
  $bx->box_column ("left","70%","",
                   views_select_view($proid,"step3",$step3).$nbsp."(".$reco 
                   . ": <i>".$ebody."</i>)");
  $bx->box_next_row_of_columns();

  // 6
  $bx->box_column ("right","30%","","<b>"
                   .$t->translate("View Step 4 (Referees)")."</b>: ");
  $bx->box_column ("left","70%","",
                   views_select_view($proid,"step4",$step4).$nbsp."(".$reco 
                   . ": <i>".$ebody."</i>)");
  $bx->box_next_row_of_columns();

  // 7
  $bx->box_column ("right","30%","","<b>"
                   .$t->translate("View Step 5 (Project Follow-up)")."</b>: ");
  $bx->box_column ("left","70%","",
                   views_select_view($proid,"step5",$step5).$nbsp."(".$reco 
                   . ": <i>".$ebody."</i>)");
  $bx->box_next_row_of_columns();

  // 8
  $bx->box_column ("right","30%","","<b>"
                   .$t->translate("View Developing Cooperation Proposals")
                   ."</b>: ");
  $bx->box_column ("left","70%","",
                   views_select_view($proid,"cooperation",$cooperation).$nbsp
                   ."(".$reco . ": <i>".$ebody."</i>)");
  $bx->box_next_row_of_columns();

  // 9
  $bx->box_column ("right","30%","","<b>"
                   .$t->translate("Project Permission Access")."</b>: ");
  $bx->box_column ("left","70%","",views_select_view($proid,"views",$views)
                   .$nbsp."(".$reco . ": <i>".$ppart."</i>)");
  $bx->box_next_row_of_columns();

  $bx->box_column("right","30%","","&nbsp;");
  $bx->box_column("left","70%","",
                  html_form_submit($t->translate("Preview"),"preview")
                  .html_form_submit($t->translate("Submit"),"submit"));
  htmlp_form_end();
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}

function views_preview($proid) {
  global $t, $auth, $bx, $configure, $news, $comments, $history, $step3, $step4, $step5, $cooperation, $views;

  $bx->box_begin();
  $bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
  $bx->box_title($t->translate("Project Information Access Rights"));
  $bx->box_body_begin();
  $timestamp = time();
  echo "<b>";
  lib_pnick($auth->auth["uname"]);
  echo " - ".timestr($timestamp)."</b><br>&nbsp;\n";

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("View Project Configuration")."</b>: ");
  $bx->box_column ("left","70%","",$t->translate($configure));
  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Write and Modify News")."</b>: ");
  $bx->box_column ("left","70%","",$t->translate($news));
  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Write Comments")."</b>: ");
  $bx->box_column ("left","70%","",$t->translate($comments));
  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("View Project History")."</b>: ");
  $bx->box_column ("left","70%","",$t->translate($history));
  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("View Step 3 (Milestones)")."</b>: ");
  $bx->box_column ("left","70%","",$t->translate($step3));
  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("View Step 4 (Referees)")."</b>: ");
  $bx->box_column ("left","70%","",$t->translate($step4));
  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("View Step 5 (Project Follow-up)")."</b>: ");
  $bx->box_column ("left","70%","",$t->translate($step5));
  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("View Developing Cooperation Proposals")."</b>: ");
  $bx->box_column ("left","70%","",$t->translate($cooperation));
  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Project Permission Access")."</b>: ");
  $bx->box_column ("left","70%","",$t->translate($views));

  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();

  lib_comment_it($proid,"Views","","0","",$t->translate("Comments on the views?"));

  lib_show_comments_on_it($proid,"Views", 0, 0);
}

function views_show($proid) {
  global $t, $bx, $db, $sess;

  $query = "SELECT * FROM views,description WHERE views.proid=description.proid AND views.proid='$proid'";
  $db->query($query);
  if ($db->num_rows() == 0) {
      print "<p>The views have not been configured yet.<p>\n";
  } else {
      while($db->next_record()) {
          $bx->box_begin();
          $bx->box_title($t->translate("Project Information Access Rights"));
          $bx->box_body_begin();
          $timestamp = mktimestamp($db->f("creation"));
          echo "<b>";
          lib_pnick($db->f("description_user"));
          echo " - ".timestr($timestamp)."</b><br>&nbsp;\n";
          
          $bx->box_columns_begin(2);
          
          $bx->box_column ("right","30%","","<b>"
                           .$t->translate("View Project Configuration")
                           ."</b>: ");
          $bx->box_column ("left","70%","",$t->translate($db->f("configure")));
          $bx->box_next_row_of_columns();
          
          $bx->box_column ("right","30%","","<b>".$t->translate("Write and Modify News")."</b>: ");
          $bx->box_column ("left","70%","",$t->translate($db->f("news")));
          $bx->box_next_row_of_columns();
          
          $bx->box_column ("right","30%","","<b>".$t->translate("Write Comments")."</b>: ");
          $bx->box_column ("left","70%","",$t->translate($db->f("comments")));
          $bx->box_next_row_of_columns();
          
          $bx->box_column ("right","30%","","<b>".$t->translate("View Project History")."</b>: ");
          $bx->box_column ("left","70%","",$t->translate($db->f('history')));
          $bx->box_next_row_of_columns();
          
          $bx->box_column ("right","30%","","<b>".$t->translate("View Step 3 (Milestones)")."</b>: ");
          $bx->box_column ("left","70%","",$t->translate($db->f("step3")));
          $bx->box_next_row_of_columns();

          $bx->box_column ("right","30%","","<b>".$t->translate("View Step 4 (Referees)")."</b>: ");
          $bx->box_column ("left","70%","",$t->translate($db->f("step4")));
          $bx->box_next_row_of_columns();
          
          $bx->box_column ("right","30%","","<b>".$t->translate("View Step 5 (Project Follow-up)")."</b>: ");
          $bx->box_column ("left","70%","",$t->translate($db->f("step5")));
          $bx->box_next_row_of_columns();
          
          $bx->box_column ("right","30%","","<b>".$t->translate("View Developing Cooperation Proposals")."</b>: ");
          $bx->box_column ("left","70%","",$t->translate($db->f("cooperation")));
          $bx->box_next_row_of_columns();
          
          $bx->box_column ("right","30%","","<b>".$t->translate("Project Permission Access")."</b>: ");
          $bx->box_column ("left","70%","",$t->translate($db->f("views")));
          
          $bx->box_columns_end();
          
          $bx->box_body_end();
          $bx->box_end();
          
          lib_comment_it($proid,"Views","","0","",$t->translate("Comments on the views?"));
          
          lib_show_comments_on_it($proid,"Views", 0, 0);
      }
  }
}

function views_insert($proid,$configure,$views,$news,$comments,
                        $history,$step3,$step4,$step5,$cooperation) {
  global $db;

  views_modify($proid,$configure,$views,$news,$comments,$history,
               $step3,$step4,$step5,$cooperation);
}

function views_modify($proid,$configure,$views,$news,$comments,$history,
                     $step3,$step4,$step5,$cooperation) {
  global $db;

  $db->query("UPDATE views SET  configure='$configure', views='$views', "
             ."news='$news', comments='$comments', history='$history', "
             ."step3='$step3', step4='$step4', step5='$step5',"
             ."cooperation='$cooperation' WHERE proid='$proid'");
  views_show($proid);
}

function views_select_view($proid,$on_what,$selected) {
    global $t;
    
    $return = html_select($on_what);
    
    if (!isset($selected) || empty($selected)) {
        $db_local = new DB_SourceAgency;
        $db_local->query("SELECT $on_what FROM views WHERE proid='$proid'");
        $db_local->next_record();
        $selected = $db_local->f($on_what);
    }
    
    // WISH: config.inc should be not required another time as it has 
    // WISH: already been done at the beginning of the page
    include("config.inc");
    
    while (list(, $value) = each($views_array)) {
        if ($on_what=="news") {
            if ($value!="Project Participants" 
            && $value!="Project Sponsors" 
            && $value!="Project Developers" 
            && $value !="Project Initiator") {
                continue; 
            }
        } else if ($on_what=="comments") {
            if ($value=="Everybody") { 
                continue; 
            }
        }
        
        $return .= html_select_option( $value,( $value == $selected ), 
                                                       $t->translate($value));
    }
    $return .= html_select_end();
    
    return($return);
}

?>