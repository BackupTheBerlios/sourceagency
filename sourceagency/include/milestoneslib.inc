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
# Library with the functions for milestones
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: milestoneslib.inc,v 1.10 2002/03/20 11:09:28 riessen Exp $
#
######################################################################

function show_milestones($proid,$devid,$who_wants_to_see) {
    global $t, $bx;

    $status="AND status='A'";

    $db = new DB_SourceAgency;
    $query = ( "SELECT * FROM sponsoring WHERE proid='$proid' "
               . "AND sponsor='$who_wants_to_see'" );

    $db->query($query);
    if ($db->num_rows() > 0) {
        $status="";
    }

    $query = ( "SELECT developer FROM developing WHERE proid='$proid' "
               ."AND devid='$devid'" );
    $db->query($query);
    $db->next_record();
    $milestone_user = $db->f("developer");
    // reset the status if the milestone user is the same as the
    // user trying to view the milestones
    if (!strcmp($milestone_user,$who_wants_to_see))  {
        $status=""; 
    }

    $db = new DB_SourceAgency;
    $query = ( "SELECT * FROM milestones WHERE proid='$proid' "
               . $status . " AND devid='$devid' ORDER BY number" );
    $db->query($query);

    if ($db->num_rows() != 0) {

        $total_payment = 0;

        $bx->box_begin();
        // TODO: it would be nice to have a link to the proposal these milestones refere to
        $bx->box_title($t->translate("Milestones proposed by "
                                     . $milestone_user));
        $bx->box_body_begin();
        $bx->box_columns_begin(6);
      
        $bx->box_column("center","7%","","<b>Number</b>");
        $bx->box_column("","53%","","<b>Goals</b>");
        $bx->box_column("center","16%","","<b>Release Date</b>");
        $bx->box_column("center","12%","","<b>Product</b>");
        $bx->box_column("center","8%","","<b>Payment</b>");
        $bx->box_column("center","14%","","<b>Status</b>");
      
        while($db->next_record()) {
            $bgcolor = ( $db->f("number")%2 ? "gold" : "#FFFFFF" );
          
            $bx->box_next_row_of_columns();
          
            if (!strcmp($milestone_user,$who_wants_to_see)) {
                $bx->box_column("center","",$bgcolor,
                                "<b>".$db->f("number")." "
                                .html_link("step3_mod.php3",
                                           array("proid" => $proid, 
                                                 "devid" => $devid, 
                                                 "number"=>$db->f("number")),
                                           "mod") . "</b>");
            } else {
                $bx->box_column("center","",$bgcolor,
                                "<b>".$db->f("number")."</b>");
            }
            $bx->box_column("","",$bgcolor,"<b>".$db->f("goals")."</b>");
            $bx->box_column("center","",$bgcolor,"<b>"
                            .timestr_middle(mktimestamp($db->f("release")))
                            ."</b>");
            $bx->box_column("center","",$bgcolor,"<b>"
                            .$db->f("product")."</b>");
            $bx->box_column("center","",$bgcolor,"<b>"
                            .$db->f("payment")."% </b>");
            $bx->box_column("center","",$bgcolor,"<b>"
                            .show_status($db->f("status"))."</b>");
            $total_payment += $db->f("payment");
        }
      
        $bx->box_next_row_of_columns();
      
        $bx->box_colspan("6","","white","&nbsp;");
      
        $bx->box_next_row_of_columns();
      
        $bx->box_colspan("4","right","#CCCCCC","<b>Total:</b>");
        $bx->box_column("center","","#CCCCCC","<b>".$total_payment."% </b>");
        $bx->box_column("center","","#CCCCCC","&nbsp;");
      
        $bx->box_columns_end();
        $bx->box_body_end();
        $bx->box_end();
    }
}


function milestones_preview($proid,$devid) {
    global $t, $bx, $number, $goals, $release_day, 
        $release_month, $release_year, $product, $payment;

    $bx->box_begin();
    $bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
    $bx->box_title($t->translate("Proposing Milestones"));
    $bx->box_body_begin();
    $bx->box_columns_begin(6);

    $bx->box_column("center","7%","","<b>Number</b>");
    $bx->box_column("","53%","","<b>Goals</b>");
    $bx->box_column("center","16%","","<b>Release Date</b>");
    $bx->box_column("center","12%","","<b>Product</b>");
    $bx->box_column("center","8%","","<b>Payment</b>");
    $bx->box_column("center","14%","","<b>Status</b>");

    $bgcolor = "gold";

    $bx->box_next_row_of_columns();

    $bx->box_column("center","",$bgcolor,"<b>$number</b>");
    $bx->box_column("","",$bgcolor,"<b>$goals</b>");
    $tStamp = mktimestamp(date_to_timestamp($release_day,
    $release_month, $release_year));
    $bx->box_column("center","",$bgcolor,
                    "<b>".timestr_middle($tStamp)."</b>");
    $bx->box_column("center","",$bgcolor,"<b>$product</b>");
    $bx->box_column("center","",$bgcolor,"<b>$payment %</b>");
    $bx->box_column("center","",$bgcolor,"<b>Proposed</b>");

    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}


function form_milestones($proid,$devid) {
    global $auth,$bx,$t,$sess, $number, $goals,
        $release_day, $release_month, $release_year, $product, $payment;

    $bx->box_begin();
    $bx->box_title($t->translate("Proposing milestones"));
    $bx->box_body_begin();

    htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");
    htmlp_form_hidden("devid",$devid);

    $bx->box_columns_begin(2);

    $bx->box_column ("right","30%","","<b>".$t->translate("Number")."</b>: ");
    $bx->box_column ("left","70%","",
                     select_milestone_number($proid,$devid,$number));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Goals")
                     . "</b> (255): ");
    $bx->box_column ("left","70%","",html_textarea("goals",40,7,"virtual",255,
                                                   $goals));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Release Date")
                     ."</b>: ");
    $bx->box_column ("left","70%","",select_date("release",$release_day,
                                                 $release_month,$release_year));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Product")."</b>: ");
    $bx->box_column ("left","70%","",
                     select_from_config("product","milestone_product_array",
                                        $product));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Payment")." %</b>: ");
    $bx->box_column ("left","70%","",
                     select_milestone_payment($proid,$devid,$payment,""));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","&nbsp;");
    $bx->box_column ("left","70%","",
                     html_form_submit("Preview","preview")
                     . html_form_submit("Submit","submit"));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}


function milestones_propose_milestone( $proid ) {
  global $t;

  print ( "<p align=right>[ ".html_link("step3_edit.php3",
                                        array("proid" => $proid,"a" => "1"),
                                        "<b>"
                                        .$t->translate("Propose Milestones")
                                        ."</b>")." ]</p>");
}

function milestones_insert($proid,$devid,$number,$goals,$release_day,
                           $release_month,$release_year,$product,$payment) {
  global $db;
  if ( is_set_and_not_empty( $number ) ) {
      
      $db->query( "SELECT * FROM milestones WHERE number='$number' AND "
                  . "proid='$proid'" );

      if ( $db->num_rows() > 0 ) {
          generate_failed_box( "Insert Failed", 
                               "Number parameter already in use." );
      } else {

          if ( is_set_and_not_empty( $goals ) ) {

              $release = date_to_timestamp($release_day,
                                           $release_month,$release_year);
              
              $db->query("INSERT milestones SET proid='$proid',devid='$devid',"
                         . "number='$number',goals='$goals',"
                         . "release='$release',"
                         . "product='$product',payment='$payment',status='P',"
                         . "creation=NOW()");
          } else {
              generate_failed_box( "Insert Failed", 
                                   "No Goals defined." );
          }
      } 
  } else {
      generate_failed_box( "Insert Failed", 
                           "Number parameter was not set." );
  }

  milestones_propose_milestone( $proid );
}


function milestones_modify($proid,$number,$goals,$release_day,
                           $release_month,$release_year,$product,$payment) {
  global $db;

  $release = date_to_timestamp($release_day,$release_month,$release_year);

  $db->query("UPDATE milestones SET goals='$goals',release='$release',"
             . "product='$product',payment='$payment',status='M' WHERE "
             . "proid='$proid' AND number='$number'");

  milestones_propose_milestone( $proid );
}


function milestones_modify_form($proid,$devid) {
  global $bx, $t, $sess, $number,$goals, $release_day, 
      $release_month, $release_year,$product,$payment,$creation;

  $bx->box_begin();
  $bx->box_title($t->translate("Modifying Milestones"));
  $bx->box_body_begin();
  print html_form_action("PHP_SELF",array("proid" => $proid),"POST");
  htmlp_form_hidden("creation",$creation);
  htmlp_form_hidden("devid",$devid);

  $bx->box_columns_begin(2);

  $bx->box_column ("right","30%","","<b>".$t->translate("Number")."</b>: ");
  $bx->box_column ("left","70%","",
                   select_milestone_number($proid,$devid,$number));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","",
                   "<b>".$t->translate("Goals")."</b> (255): ");
  $bx->box_column ("left","70%","",
                   html_textarea("goals",40,7,"virtual",255,$goals));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","",
                   "<b>".$t->translate("Release Date")."</b>: ");
  $bx->box_column ("left","70%","",
                   select_date("release",$release_day,$release_month,
                               $release_year));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Product")."</b>: ");
  $bx->box_column ("left","70%","",
                   select_from_config("product","milestone_product_array",
                                      $product));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","<b>".$t->translate("Payment")."</b>: ");
  $bx->box_column ("left","70%","",
                   select_milestone_payment($proid,$devid,$payment,$number));

  $bx->box_next_row_of_columns();

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_column ("left","70%","",
                   html_form_submit("Preview","preview")
                   .html_form_submit("Submit","submit"));

  htmlp_form_end();
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}


function select_milestone_number($proid,$devid,$selected) {

  $db = new DB_SourceAgency;
  $db->query("SELECT number FROM milestones WHERE proid='$proid' ".
             "AND devid='$devid' ORDER BY number");

  if ($db->num_rows() == 0) {
      $array[] = 0;
  }
  else {
      while($db->next_record()) {
          $array[] = $db->f("number");
      }
  }

  $return = html_select("number");

  // TODO: why is there a '5 +' here ?!?!
  for ($i=1; $i <= 5 + $db->num_rows(); $i++) {
	$ok = 1;
	reset($array);
	while (list(, $value) = each($array)) {
		if ($value == $i && !($i == $selected)) {
                    $ok= 0;
                }
	}

	if ($i == $selected) {
            $select="selected";
        }
	else {
            $select="";
        }

	if ($ok) {
            $return .= html_select_option($i,$select,$i);
        }
  }
  $return .= html_select_end();

  return($return);
}

function select_milestone_payment($proid,$devid,$selected,$number) {
  global $auth;

  $db = new DB_SourceAgency;
  $db->query("SELECT payment,number FROM milestones WHERE "
             . "proid='$proid' AND devid='$devid'");

  $total=0;
  while($db->next_record()) {
      if ($db->f("number")!=$number) {
          $total += $db->f("payment");
      }
  }

  $max = 100 - $total;

  $return = html_select("payment");

  for ($i=1; $i <= $max/2; $i++) {
      $return .= html_select_option($i*2, ($i == $selected/2), 2*$i."%");
  }
  
  $return .= html_select_end();
  
  return($return);
}

?>