<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Library with the functions needed for consultants (step 1)
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: consultantslib.inc,v 1.6 2002/03/28 00:53:44 grex Exp $
#
######################################################################

/**
 * This library contains functionality needed for the handling
 * consultants (form, show, etc.)
 *
 * This step handles data from the 'consultants' table in the database
 * Table database contains following data:
 *
 *  proid bigint(20) unsigned DEFAULT '0' NOT NULL:
 *      Project Id (Foreign Key)
 *  consultant varchar(16) NOT NULL:
 *      Username of the user who proposes himself as consultant
 *  status char(1) NOT NULL:
 *      Status of his proposal as consultant
 *  creation timestamp(14):
 *      Timestamp of the last time the rating was modified
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, lib, translate, box, auth
 */

/**
 * Displays the Consultant proposals on a project (and its status)
 *
 * Should print something like this
 * +----------------------------------------------------------+
 * | Consultants                                              |
 * +----------------------------------------------------------+
 * | +----------++--------------++-------------++-----------+ |
 * | |  Number  ||   Username   ||    Status   || Creation  | |
 * | +----------++--------------++-------------++-----------+ |
 * | |    1     ||    Devel1    ||   Rejected  || March 12  | |
 * | +----------++--------------++-------------++-----------+ |
 * | |    2     ||    Devel2    ||   Accepted  || March 13  | |
 * | +----------++--------------++-------------++-----------+ |
 * | |    3     ||    Devel3    ||   Rejected  || March 16  | |
 * | +----------++--------------++-------------++-----------+ |
 * +----------------------------------------------------------+
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 */

function show_consultants($proid)
{
    global $t, $bx, $db;

    $query = ("SELECT * FROM consultants,auth_user WHERE proid='$proid' "
              ."AND username=consultant ORDER BY creation");
    $db->query($query);

    if ($db->num_rows() != 0) {
        $bx->box_begin();
        $bx->box_title($t->translate('Consultants'));
        $bx->box_body_begin();
        $bx->box_columns_begin(4);

        $bx->box_column('','','','<b>Number</b>');
        $bx->box_column('','','','<b>Username</b>');
        $bx->box_column('','','','<b>Status</b>');
        $bx->box_column('','','','<b>Creation</b>');

        $i =0;
        $colors = array( 1 => 'gold', 0 => '#FFFFFF' );
        while($db->next_record()) {
            $bgcolor = $colors[ ($i++) % 2 ];

            $bx->box_next_row_of_columns();

            $bx->box_column('','',$bgcolor,'<b>$i</b>');
            $bx->box_column('','',$bgcolor,'<b>'
                            .lib_nick($db->f("username")).'</b>');
            $bx->box_column('','',$bgcolor,'<b>'
                            .show_status($db->f("status")).'</b>');
            $bx->box_column('','',$bgcolor,'<b>'
                            .timestr(mktimestamp($db->f("creation")))
                            .'</b>');
        }

        $bx->box_columns_end();
        $bx->box_body_end();
        $bx->box_end();
    } else {
        print "<p>No developers have offered themselves as consultants yet.<p>\n";
    }
}

/**
 * Form to enter a new consultant proposal
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $auth          PHPLib authentication object
 * @global  object    $sess          PHPLib session object
 *
 * @access  public
 */
function consultants_form($proid)
{
    global $bx, $t, $sess, $auth;

    $bx->box_begin();
    $bx->box_title($t->translate('Offer yourself as project consultant'));
    $bx->box_body_begin();
    print html_form_action('PHP_SELF',array('proid' => $proid),'POST');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','45%','','<b>'.$t->translate('Your username')
                     .'</b>: ');
    $bx->box_column ('left','55%','',$auth->auth["uname"]);

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','45%','','<b>'
                     .$t->translate('Check if you want '
                                    .'to be a consultant')
                     .'</b>: ');
    $bx->box_column ('left','55%','',html_checkbox('check','check',''));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','45%','','&nbsp;');
    $bx->box_column ('left','55%','',html_form_submit('Submit','submit'));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Insert a new consultant into database
 * (after that it shows the consultants for the project)
 *
 * @param   int       $proid         Project id
 * @param   string    $user          Username of the user who does the proposal
 *
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 * @see show_consultants()
 */
function consultants_insert($proid, $user)
{
    global $db;

    $db->query("INSERT consultants SET proid='$proid',consultant='$user',"
               ."status='P'");

    // WISH: config.inc should not be required another time,
    // as it is done at the beginning of every page.
    include('config.inc');
    include('monitorlib.inc');
    monitor_mail($proid,'consultant', 
    'New consultant proposed for project $proid', 'Event has happened');

    show_consultants($proid);
}

/**
 * Returns if the project has been configured to have consultants
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $db            PHPLib database object
 *
 * @return  boolean
 * @access  public
 */
function consultants_wanted($proid)
{
    global $db, $t;

    $db->query("SELECT consultants FROM configure WHERE proid='$proid'");
    $db->next_record();
    if ($db->f("consultants") == 'Yes') {
        return 1;
    } else {
        // WISH: config.inc should not be required another time,
        // as it is done at the beginning of every page.
        require('config.inc');

        $be = new box('80%',$th_box_frame_color,$th_box_frame_width,
                      $th_box_title_bgcolor,$th_box_title_font_color,
                      $th_box_title_align,$th_box_body_bgcolor,
                      $th_box_error_font_color,$th_box_body_align);

        $be->box_full($t->translate('No consultants wanted'),
        $t->translate('This project does not require any consultants'));
        return 0;
    }
}
?>

