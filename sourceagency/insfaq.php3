<?php

######################################################################
# SourceAgency: Software Announcement & Retrieval System
# ================================================
#
# Copyright (c) 2001 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Create Frequently Asked Questions
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
######################################################################  


page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require("header.inc");
require("faqlib.inc");

$bx = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$be = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();

if (($config_perm_admfaq != "all") 
    && (!isset($perm) || !$perm->have_perm($config_perm_admfaq))) {
  $be->box_full($t->translate("Error"), $t->translate("Access denied"));
} else {
  if (isset($delete)) {

    if ($delete == 1) {
      $query = "SELECT * FROM faq WHERE faqid='$faqid' AND language='$la'";
      $db->query($query);
      $db->next_record();
      faqshow($db);
      $bx->box_begin();
      $bx->box_title($t->translate("Do you really want to delete this FAQ? "
                                   ."There is no way for undeletion."));
      $bx->box_body_begin();	
      echo "<table><tr><td>\n";
      echo "<form action=\"".$sess->self_url()."\" method=\"POST\">\n";
      echo "<input type=\"hidden\" name=\"faqid\" value=\"".$db->f("faqid")
        ."\">\n";
      echo "<input type=\"hidden\" name=\"delete\" value=\"2\">\n";
      echo "<input type=\"hidden\" name=\"modify\" value=\"0\">\n";
      echo "<input type=\"submit\" value=\"".$t->translate("Yes, Delete")
        ."\">\n";

      echo "</form></td><td>\n";
      echo "<form action=\"".$sess->self_url()."\" method=\"POST\">\n";
      echo "<input type=\"hidden\" name=\"faqid\" value=\""
        .$db->f("faqid")."\">\n";
      echo "<input type=\"hidden\" name=\"modify\" value=\"1\">\n";
      echo "<input type=\"hidden\" name=\"delete\" value=\"0\">\n";
      echo "<input type=\"submit\" value=\""
        .$t->translate("No, Just Modify")."\">\n";
      echo "</form></td></tr></table>\n";

      $bx->box_body_end();
      $bx->box_end();
    }

    if ($delete == 2) {
      // We remove it from our DB
      $db->query("DELETE FROM faq WHERE faqid='$faqid' AND language='$la'");
      if ($db->affected_rows() < 1) {
        $be->box_full($t->translate("Error"), $t->translate("Database Error"));
      } else { 
      	$bx->box_full($t->translate("Frequently Asked Questions "
                                    ."Administration"),
        $t->translate("The FAQ has been deleted"));
      }
    } 
  }

  if (isset($modify)) {
    if ($modify == 1) {
      $db->query("SELECT * FROM faq WHERE faqid='$faqid' AND language='$la'");
      $db->next_record();
      faqmod($db);
    }
    
    if ($modify == 2) {
      // We insert it into the DB
      $db->query("UPDATE faq SET question='$question',answer='$answer' "
                 ."WHERE faqid='$faqid'");
      if ($db->affected_rows() < 1) {
        $be->box_full($t->translate("Error"), $t->translate("Database Error"));
      } else {
        // We show what we just have inserted
        $bx->box_full($t->translate("Frequently Asked Questions "
                                    ."Administration"),
        $t->translate("The following FAQ has been modified"));
        $db->query("SELECT * FROM faq WHERE faqid='$faqid'");
        $db->next_record();
        faqshow($db);
      }
    }
  }
  if (isset($create)) {
    if ($create == 1) {
      faqform();
    }
    if ($create == 2) {
      // We insert it into the DB
      $tables = "faq";
      $insert = "question='$question',answer='$answer',language='$la'";
      if (!$db->query("INSERT $tables SET $insert")) {
        // TODO: is mysql_die(..) specific to a mysql database?
        mysql_die($db);
      }
      // We show what we've inserted
      $bx->box_full($t->translate("Frequently Asked Questions "
                                  ."Administration"),
                   $t->translate("The following FAQ has been inserted"));
      $bx->box_full($t->translate("Question").": ".$question,"<b>"
                    .$t->translate("Answer").":</b> ".$answer);
    }
  }
}

end_content();
require("footer.inc");
page_close();
?>
