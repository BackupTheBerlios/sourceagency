<?php

######################################################################
# SourceAgency: Software Announcement & Retrieval System
# ================================================
#
# Copyright (c) 2001 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Administrate Frequently Asked Questions
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

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
$be = new box("",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);
$bs = new box("100%",$th_strip_frame_color,$th_strip_frame_width,$th_strip_title_bgcolor,$th_strip_title_font_color,$th_strip_title_align,$th_strip_body_bgcolor,$th_strip_body_font_color,$th_strip_body_align);
?>

<!-- content -->
<?php
if (($config_perm_admfaq != "all") && (!isset($perm) || !$perm->have_perm($config_perm_admfaq))) {
  $be->box_full($t->translate("Error"), $t->translate("Access denied"));
} else {
  $db->query("SELECT * FROM faq WHERE language='$la'");
  $bx->box_begin();
  $bx->box_title($t->translate("Frequently Asked Questions Administration"));
  $bx->box_body_begin();
  echo "<table width=100% border=0><tr><td width=88%>\n";
  echo "<b>".$t->translate("Enter a New Frequently Asked Question")."</b>\n";
  echo "<form action=\"".$sess->url("insfaq.php3")."\" method=\"POST\">\n";
  echo "</td><td width=12% align=right>\n";
				// Create Button
  echo "<input type=\"hidden\" name=\"create\" value=\"1\">\n";
  echo "<input type=\"submit\"value=\"".$t->translate("Insert")."\">\n";
  echo "</td></tr></form></table>\n";

  $i = 1;
  while($db->next_record()) {
    $i++;  
    $bs->box_strip($t->translate("Question").": ".$db->f("question"));
    echo "<table width=100% border=0><tr><td width=76%>";
    echo "<b>".$t->translate("Answer").":</b> ".$db->f("answer");
    echo "</td><td width=12% align=right><form action=\"".$sess->url("insfaq.php3")."\" method=\"POST\">"
				// Modify Button
    ."<input type=\"hidden\" name=\"modify\" value=\"1\">"
    ."<input type=\"hidden\" name=\"delete\" value=\"0\">"
    ."<input type=\"hidden\" name=\"faqid\" value=\"".$db->f("faqid")
    ."\"><input type=\"submit\"value=\"".$t->translate("Change")."\">"
    ."</form>"
				// Delete Button
    ."</td><td width=12% align=right><form action=\"".$sess->url("insfaq.php3")."\" method=\"POST\">"
    ."<input type=\"hidden\" name=\"modify\" value=\"0\">"
    ."<input type=\"hidden\" name=\"delete\" value=\"1\">"
    ."<input type=\"hidden\" name=\"faqid\" value=\"".$db->f("faqid")
    ."\"><input type=\"submit\"value=\"".$t->translate("Delete")."\">"
    ."</form></td></tr></table>";
  }
  $bx->box_body_end();
  $bx->box_end();
}
?>
<!-- end content -->

<?php
require("footer.inc");
page_close();
?>
