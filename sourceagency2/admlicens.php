<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Lutz Henckel (lutz.henckel@fokus.fraunhofer.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the license administration file
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: admlicens.php,v 1.1 2003/11/21 12:55:59 helix Exp $
#
######################################################################

require("include/prepend.php3");

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require("include/header.inc");

$bx = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

$be = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

$bs = new box("100%",$th_strip_frame_color,$th_strip_frame_width,
              $th_strip_title_bgcolor,$th_strip_title_font_color,
              $th_strip_title_align,$th_strip_body_bgcolor,
              $th_strip_body_font_color,$th_strip_body_align);

start_content();

if (($config_perm_admlicens != "all") 
    && (!isset($perm) || !$perm->have_perm($config_perm_admlicens))) {
  $be->box_full($t->translate("Error"), $t->translate("Access denied"));
} else {

  $bx->box_begin();
  $bx->box_title($t->translate("License Administration"));
  $bx->box_body_begin();

  // Insert a new License

  $bs->box_strip($t->translate("Insert a License"));
  echo "<form action=\"".$sess->url("inslic.php")."\" method=\"POST\">\n";
  echo "<table border=0 cellspacing=0 cellpadding=3 width=100%>\n";
  echo "<tr><td align=right width=30%>".$t->translate("New License")
    . " (64):</td><td width=70%><input type=\"TEXT\" name=\"license\" "
    . "size=40 maxlength=64>\n";
  echo "<tr><td align=right>".$t->translate("License URL")
    ." (255):</td><td><input type=\"TEXT\" name=\"url_lic\" size=40 "
    ."maxlength=255>\n";
  echo "</td></tr>\n";
  echo "<tr><td>&nbsp;</td>\n";
  echo "<td><input type=\"submit\" value=\"".$t->translate("Insert")."\">";
  echo "</td></tr>\n";
  echo "</form>\n";
  echo "</table>\n";
  echo "<BR>\n";

  // Rename a License

  $bs->box_strip($t->translate("Rename a License"));
  echo "<form action=\"".$sess->url("inslic.php")."\" method=\"POST\">\n";
  echo "<table border=0 cellspacing=0 cellpadding=3 width=100%>\n";
  echo "<tr><td align=right width=30%>".$t->translate("License")
    .":</td><td width=70%>\n";
  print license("The GNU General Public License (GPL)");
  // We select the first one to avoid having a blank line
  echo "</td></tr>\n";
  echo "<tr><td align=right>".$t->translate("New License Name")
    ." (64):</td><td><input type=\"TEXT\" name=\"new_license\" size=40 "
    ."maxlength=64>\n";
  echo "</td></tr>\n";
  echo "<tr><td>&nbsp;</td>\n";
  echo "<td><input type=\"submit\" value=\"".$t->translate("Rename")."\">";
  echo "</td></tr>\n";
  echo "</form>\n";
  echo "</table>\n";
  echo "<BR>\n";

  // Change a License's URL

  $bs->box_strip($t->translate("Change a License URL"));
  echo "<form action=\"".$sess->url("inslic.php")."\" method=\"POST\">\n";
  echo "<table border=0 cellspacing=0 cellpadding=3 width=100%>\n";
  echo "<tr><td align=right width=30%>".$t->translate("License")
    .":</td><td width=70%>\n";
  print license("The GNU General Public License (GPL)");
  // We select the first one to avoid having a blank line
  echo "</td></tr>\n";
  echo "<tr><td align=right>".$t->translate("New License URL")
    ." (255):</td><td><input type=\"TEXT\" name=\"new_url\" size=40 "
    ."maxlength=255>\n";
  echo "</td></tr>\n";
  echo "<tr><td>&nbsp;</td>\n";
  echo "<td><input type=\"submit\" value=\"".$t->translate("Change")."\">";
  echo "</td></tr>\n";
  echo "</form>\n";
  echo "</table>\n";
  echo "<BR>\n";

  // Delete a License

  $bs->box_strip($t->translate("Delete a License"));
  echo "<form action=\"".$sess->url("inslic.php")."\" method=\"POST\">\n";
  echo "<table border=0 cellspacing=0 cellpadding=3 width=100%>\n";
  echo "<tr><td align=right width=30%>".$t->translate("License")
    .":</td><td width=70%>\n";
  print license("The GNU General Public License (GPL)");
  // We select the first one to avoid having a blank line
  echo "</td></tr>\n";
  echo "</td></tr>\n";
  echo "<tr><td>&nbsp;</td>\n";
  echo "<input type=\"hidden\" name=\"del_license\" value=\"warning\">\n";
  echo "<td><input type=\"submit\" value=\"".$t->translate("Delete")."\">";
  echo "</td></tr>\n";
  echo "</form>\n";
  echo "</table>\n";
  $bx->box_body_end();
  $bx->box_end();
}

end_content();
require("include/footer.inc");
page_close();
?>
