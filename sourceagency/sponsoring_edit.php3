<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Software Announcement & Retrieval System
# ================================================
#
# Copyright (c) 2001 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourcewell.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Editing sponsoring involvements is possible here
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: sponsoring_edit.php3,v 1.7 2002/04/11 13:23:53 riessen Exp $
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
require ('sponsoringlib.inc');

$bx = new box('100%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = 'sponsoring_edit';

if (check_permission($proid, $page)) {
    top_bar($proid, $page);

    if ( is_not_set_or_empty( $submit ) ) {
        if ( is_set_and_not_empty( $preview ) ) {
            sponsoring_preview( $proid );
        } else {
            if (!is_project_initiator( $proid )) {
                require('configurelib.inc');
                configure_show($proid);
                print "<br><p>\n";
            }
        }

        /* now check whether the user is already sponsoring */
        if ( already_involved_in_this_step($proid,$page,$auth->auth['uname'])){
            $db->query("SELECT * FROM sponsoring WHERE proid='$proid' AND "
                       ."sponsor='".$auth->auth['uname']."'" );
            $db->next_record();
            global $budget, $sponsoring_text;
            $budget = $db->f( 'budget' );
            $sponsoring_text = $db->f('sponsoring_text');

            $ary = timestamp_to_date( $db->f( 'valid' ) );
            global $valid_day, $valid_month, $valid_year;
            $valid_day = $ary['day'];
            $valid_month = $ary['month'];
            $valid_year = $ary['year'];

            $ary = timestamp_to_date( $db->f( 'begin' ) );
            global $begin_day, $begin_month, $begin_year;
            $begin_day = $ary['day'];
            $begin_month = $ary['month'];
            $begin_year = $ary['year'];

            $ary = timestamp_to_date( $db->f( 'finish' ) );
            global $finish_day, $finish_month, $finish_year;
            $finish_day = $ary['day'];
            $finish_month = $ary['month'];
            $finish_year = $ary['year'];
        }

        print "Sponsors can modify their sponsoring wish using this form.\n";
        print "<br><p>\n";

        sponsoring_form($proid);
    } else {
        /** Check that budget is positive **/
        if ( $budget <= 0 ) {
            generate_failed_box( "Sponsoring", 
                                 "Budget must be greater than zero" );
        } else {
            sponsoring_insert($proid, $auth->auth['uname'], $sponsoring_text,
                              $budget, $valid_day, $valid_month, $valid_year,
                              $begin_day, $begin_month, $begin_year, 
                              $finish_day,$finish_month, $finish_year);
        }
    }
}

end_content();
require('footer.inc');
@page_close();
?>