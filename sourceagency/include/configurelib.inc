<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Gerrit Riessen (Gerrit.Riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Library with the funcionality needed to configure a project
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: configurelib.inc,v 1.7 2002/06/20 12:06:01 riessen Exp $
#
######################################################################

function configure_form($proid) {
  global $bx, $t, $sess, $preview, $quorum, $other_tech_contents, 
    $other_developing_proposals, $consultants;

  if(!configure_first_time($proid) && !isset($preview)) {
      configure_modify_form($proid,$user);
  }

  $bx->box_begin();
  $bx->box_title($t->translate("Configure Project"));
  $bx->box_body_begin();
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

  $bx->box_columns_begin(2);

  if (is_sponsor()) {
      $bx->box_column ("right","30%","","<b>".
                       $t->translate("Quorum for decision making")."</b>: ");
      $bx->box_column ("left","70%","",select_quorum($quorum));
      $bx->box_next_row_of_columns();
  }

  if (is_project_initiator($proid)) {
      $bx->box_column ("right","30%","","<b>"
                       .$t->translate("Other technical contents?")."</b>: ");
      $bx->box_column ("left","70%","",
                       lib_select_yes_or_no("other_tech_contents",
                                            $other_tech_contents));
  }

  $bx->box_next_row_of_columns();

  if (is_developer()) {
      $bx->box_column ("right","30%","","<b>"
                       .$t->translate("Other developing proposals?")."</b>: ");
      $bx->box_column ("left","70%","",
                       lib_select_yes_or_no("other_developing_proposals",
                                            $other_developing_proposals));
      $bx->box_next_row_of_columns();
  }

  if (is_sponsor() && is_project_initiator($proid)) {
      $bx->box_column ("right","30%","",
                       "<b>".$t->translate("Consultants?")."</b>: ");
      $bx->box_column ("left","70%","",
                       lib_select_yes_or_no("consultants",$consultants));
  }

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",
                   html_form_submit($t->translate("Preview"),"preview")
                   .html_form_submit($t->translate("Submit"),"submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}


function configure_preview($proid) {
    global $t, $bx, $auth, $sess, $quorum, $other_tech_contents, 
        $other_developing_proposals, $consultants;

    $bx->box_begin();
    $bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
    $bx->box_title($t->translate("Project Configuration"));
    $bx->box_body_begin();
    $timestamp = time();
    echo "<b>";
    // TODO: results are not used, can be removed.
//      $db_local = new DB_SourceAgency;
//      $db_local->query("SELECT email_usr FROM auth_user WHERE username='"
//                       .$auth->auth["uname"]."'");
//      $db_local->next_record();
    lib_pnick($auth->auth["uname"]);
    echo " - ".timestr($timestamp)."</b><br>&nbsp;\n";
    if (is_sponsor()) {
        echo "<br><b>".$t->translate("Quorum")."</b>: "."$quorum"." %\n";
    }
    if (is_project_initiator($proid))  {
        echo "<br><b>".$t->translate("Other tech_contents")
          ."</b>: ".$t->translate($other_tech_contents)."\n";
    }
    if (is_developer())  {
        echo "<br><b>".$t->translate("Other developing proposals")."</b>: "
            .$t->translate($other_developing_proposals)."\n";
    }
    if (is_sponsor() && is_project_initiator($proid))  {
        echo "<br><b>".$t->translate("Consultants")."</b>: "
            .$t->translate($consultants)."\n";
    }
    $bx->box_body_end();
    $bx->box_end();
}


function configure_show($proid) {
  global $t, $bx, $db, $sess;

  $query = "SELECT * FROM configure WHERE proid='$proid'";
  $db->query($query);

  if ($db->num_rows() == 0) {
      print "<p>"
          .$t->translate("The project parameters have not been configured by "
                         ."the project owner(s)").".<p>\n";
  } else {

      while($db->next_record()) {
          $bx->box_begin();
          $bx->box_title($t->translate("Project Configuration"));
          $bx->box_body_begin();
          echo "<br>";
          
          $tmp_q = $db->f("quorum");
          echo "<p><b>".$t->translate("Quorum")."</b>: " 
              . ( $tmp_q != "" ? $tmp_q . "%"
              : $t->translate("Decision to be taken by Sponsors"))."\n";
          
          echo "<br><b>".$t->translate("Consultants")."</b>: "
              .$t->translate($db->f("consultants"))."\n";
          
          echo "<br><b>".$t->translate("Other Technical Contents")."</b>: "
              .$t->translate($db->f("other_tech_contents"))."\n";
          echo "<br><b>".$t->translate("Other Developing Proposals")."</b>: "
              .$t->translate($db->f("other_developing_proposals"))."\n";
          
          $tmp_s = $db->f("sponsor");
          echo "<br><b>".$t->translate("First Sponsor")."</b>: " 
              . ( $tmp_s != "" ? $tmp_s : $t->translate("No sponsors")) . "\n";
          $tmp_d = $db->f("developer");
          echo "<br><b>".$t->translate("Developer")."</b>: " 
              . ( $tmp_d != "" ? $tmp_d: $t->translate("No main developer")) 
              . "\n";
          $bx->box_body_end();
          $bx->box_end();
      }
  }
}

function configure_modify_form($proid) {
    global $quorum, $consultants, $other_tech_contents, 
        $other_developing_proposals;
    
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM configure WHERE proid='$proid'");
    $db_local->next_record();
    $quorum = $db_local->f("quorum");
    $consultants = $db_local->f("consultants");
    $other_tech_contents = $db_local->f("other_tech_contents");
    $other_developing_proposals  = $db_local->f("other_developing_proposals");
}

function configure_insert($proid,$user) {
  global $db,$auth,$quorum,$consultants,$other_tech_contents,
      $other_developing_proposals, $t, $sys_name;

  if(!configure_first_time($proid)) {
      configure_modify($proid,$user);
  } else {
      if (is_sponsor()) {
          $query = ("quorum='$quorum',consultants='$consultants',"
                    ."other_tech_contents='$other_tech_contents',"
                    ."other_developing_proposals='Yes',sponsor='$user',"
                    ."developer=''");
	} else {
            $query =("consultants='No',other_tech_contents='"
                     ."$other_tech_contents',other_developing_proposals="
                     ."'$other_developing_proposals',developer='$user'");
        }
   	$db->query("INSERT configure SET proid='$proid',$query");
   	configure_show($proid);

	// Insert into history
  	$db->query("INSERT history SET proid='$proid', history_user='$user',"
                   ." type='Configure', action='Project configuration'");

        echo "<b>".$t->translate("Congratulations")."</b>. "
            .$t->translate("You have just configured your project")
            .".<p>\n";

        if (is_sponsor()) {
            echo "<br>".
                $t->translate("The last step before waiting to be "
                              ."reviewed by an editor is to")
                . " "
                .html_link("sponsoring_edit.php3",array("proid" => $proid),
                           $t->translate("Sponsor this project"));
        } elseif (is_developer()) {
            lib_insertion_finished();
        }
        echo "<p><br>\n";

        lib_insertion_information();
  }
}

function configure_modify($proid,$user) {
    global $db,$auth,$quorum,$consultants,$other_tech_contents,
        $other_developing_proposals, $t;

    if (project_type($proid)=="sponsored") {
  	if ( is_sponsor() ) {
            $query = "quorum='$quorum',consultants="
                 ."'$consultants',other_tech_contents="
                 ."'$other_tech_contents',"
                 ."other_developing_proposals='Yes',"
                 ."sponsor='$user'";
        }
	else {
            $query = "developer='$user'";
        }
    } else {
      $query = ( is_sponsor() ? "quorum='$quorum'" : "developer='$user'" );
    }

    $db->query("UPDATE configure SET $query WHERE proid='$proid'");
    configure_show($proid);
    
    include("monitorlib.inc");
    include("config.inc");
    monitor_mail($proid,"configure", 
                 $t->translate('Configuration modified for project')." $proid",
                 $t->translate("An event has happened."));
    
    // Insert into history
    $db->query("INSERT history SET proid='$proid', history_user='$user', "
               ."type='Configure', action='Project configuration modified'");
}

function select_quorum($selected) {
  global $auth;

  $ret = html_select("quorum");
  for ( $i = 55; $i < 100; $i+=5 ) {
      $ret .= html_select_option($i, ($i == $selected), $i."%" );
  }
  $ret .= html_select_end();

  return($ret);
}

function configure_first_time($proid) {
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM configure WHERE proid='$proid'");
    return ( $db_local->num_rows() == 0 ? 1 : 0 );
}

function project_type($proid) {
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT perms FROM description,auth_user "
                     ."WHERE proid='$proid' AND description_user=username");
    $db_local->next_record();
    
    return ( $db_local->f("perms") == "sponsor" ? "sponsored" : "developed" );
}

?>