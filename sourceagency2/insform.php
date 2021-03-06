<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Gregorio Robles (grex@scouts-es.org),
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the form for inserting projects
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: insform.php,v 1.1 2003/11/21 12:55:58 helix Exp $
#
######################################################################

require("include/prepend.php3");

page_open(array("sess" => "SourceAgency_Session",
                "auth" => "SourceAgency_Auth",
                "perm" => "SourceAgency_Perm"));

require("include/header.inc");
require("include/insertlib.inc");

$bx = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$be = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();
$page = "insform";

if ($perm->have_perm("devel_pending") || $perm->have_perm("sponsor_pending")) {
  $be->box_full($t->translate("Error"), $t->translate("Access denied"));
} else {

  $bx->box_full($t->translate("Info box"), 
                 $t->translate("Here you have "
                               ."to enter all the <b>project related</b> data."
                               ." This should only give an idea to other "
                               ."people what you are planning."));

  insert_form();

  lib_insertion_information();

}

end_content();
require("include/footer.inc");
page_close();
?>
