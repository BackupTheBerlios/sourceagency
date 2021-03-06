<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ================================================
#
# Copyright (c) 2001-2003 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourcewell.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the index file which shows the recent apps
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
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
require("include/consultantslib.inc");
require("include/decisionslib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = "consultants";

if (check_proid($proid)) {
  top_bar($proid,$page);

  print( $t->translate( "A sponsor may require help to submit a project "
                        ."in a proper way. If he wishes, he can ask "
                        ."registered developers to "
                        ."assist him on this topic.").'<p>');

  if (consultants_wanted($proid)) {

	print ( "<p align=right>[ <b>"
                .html_link("step1_edit.php",
                           array("proid" => $proid),
                           $t->translate("Propose yourself as consultant"))
                ."</b> ]<p>\n");

  	show_consultants($proid);
  }

  if ( is_accepted_sponsor($proid) ) {
      create_decision_link( $proid );
  }
}

end_content();
require("include/footer.inc");
@page_close();
?>
