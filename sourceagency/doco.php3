<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2002 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de),
#                Gregorio Robles (grex@scouts-es.org)
#                Gerrit Riessen (Gerrit.Riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Retrieve the documentation for a particular page.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: doco.php3,v 1.1 2002/04/15 13:33:20 riessen Exp $
#
######################################################################

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require("header.inc");

start_content();

if ( is_not_set_or_empty( $page ) ) {
    generate_failed_box( "Page not Specified", "Page was not specified for "
                         ."documentation is required" );
} else {
    $basename = basename( $page, ".php3" );
    $page_name = $t->translate( $basename . "-page-name" );
    $doco = $t->translate( $basename . "-doco" );
    
    require("config.inc");

    if ( $doco == $basename . "-doco" ) {
        $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                      $th_box_title_bgcolor,$th_box_title_font_color,
                      $th_box_title_align,$th_box_body_bgcolor,
                      $th_box_error_font_color,$th_box_body_align);

        $be->box_full( $page_name, $t->translate( "Has no documentation" ));
    } else {
        $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                      $th_box_title_bgcolor,$th_box_title_font_color,
                      $th_box_title_align,$th_box_body_bgcolor,
                      $th_box_body_font_color,$th_box_body_align);
        $be->box_full( $page_name, $text );
    }
}

end_content();
require("footer.inc");
page_close();

?>
