<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2002 by
#                Gregorio Robles (grex@scouts-es.org) and
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
# $Id: doco.php3,v 1.8 2002/05/07 11:26:58 riessen Exp $
#
######################################################################

page_open(array('sess' => 'SourceAgency_Session'));
if (isset($auth) && !empty($auth->auth['perm'])) {
  page_close();
  page_open(array('sess' => 'SourceAgency_Session',
                  'auth' => 'SourceAgency_Auth',
                  'perm' => 'SourceAgency_Perm'));
}

require('header.inc');

$be = new box('80%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

$box_doco = new box('80%',$th_box_frame_color,$th_box_frame_width,
                    $th_box_title_bgcolor,$th_box_title_font_color,
                    $th_box_title_align,$th_box_body_bgcolor,
                    $th_box_body_font_color,$th_box_body_align);

start_content();

if ( is_not_set_or_empty( $page ) ) {
    generate_failed_box( 'Page not Specified', 'Page was not specified for '
                         .'documentation is required' );
} else {

    $basename = basename( $page );
    /* remove any extensions that may be left over (inc, php, php3) */
    $basename = preg_replace('/[.](inc|php).?$/', '', $basename);

    $db->query("SELECT * FROM doco WHERE page='$basename' AND language='$la'");
    if ($db->num_rows() == 0) {
        /* no doc in that language */
        /* let's see if there is at least some documentation in English */
        $db->query("SELECT * FROM doco WHERE page='$basename' "
                   ."AND language='English'");
        if ($db->num_rows() == 0) {
            $be->box_full($page, $t->translate('Has no documentation'));
        } else {
            $db->next_record();
            $box_doco->box_strip($t->translate('Our apologies. Documentation '
                                               .'only available in English.'));
            $box_doco->box_full( $db->f('header'), $db->f('doco') );
	}
    } else {
        $db->next_record();
        $box_doco->box_full( $db->f('header'), $db->f('doco') );
    }
}

end_content();
require('footer.inc');
@page_close();
?>
