<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Library with the functions needed for referees
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: refereeslib.inc,v 1.1 2003/11/21 12:56:02 helix Exp $
#
######################################################################

/**
 * This library contains functionality needed for the referees
 * which offer themselves and are selected in step 4
 *
 * This step handles data from the 'referees' table in the database
 * Table database contains following data:
 *
 *  proid bigint(20) unsigned DEFAULT '0' NOT NULL:
 *      Project Id (Foreign Key)
 *  referee varchar(16) NOT NULL:
 *      Registered developer that has offered himself as referee
 *  status char(1) NOT NULL:
 *      Status of this developing proposal (accepted, rejected, pending)
 *  creation timestamp(14)
 *      Timestamp of the last time the rating was modified
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
< * @see html, lib, translate, box, auth
 */

/**
 * Shows the referees offered on a given project
 *
 * Should print something like this
 * +----------------------------------------------------------+
 * | Referees                                                 |
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
function show_referees($proid)
{
    global $t, $bx, $db;

    $query = ( "SELECT * FROM referees,auth_user WHERE proid='$proid' "
               ."AND username=referee ORDER BY creation" );
    $db->query($query);

    if ($db->num_rows() != 0) {
	$bx->box_begin();
	$bx->box_title($t->translate('Referees'));
	$bx->box_body_begin();
	$bx->box_columns_begin(4);

	$bx->box_column('','','','<b>'.$t->translate('Number').'</b>');
	$bx->box_column('','','','<b>'.$t->translate('Username').'</b>');
	$bx->box_column('','','','<b>'.$t->translate('Status').'</b>');
	$bx->box_column('','','','<b>'.$t->translate('Creation').'</b>');

	$i =0;

	while($db->next_record()) {
	    $i++;
            $bgcolor = ( $i%2 != 0 ? 'gold' : '#FFFFFF' );

 	    $bx->box_next_row_of_columns();

            $bx->box_column('','',$bgcolor,"<b>$i</b>");
            $bx->box_column('','',$bgcolor,'<b>'
                            .lib_nick($db->f('username')).'</b>');
	    $bx->box_column('','',$bgcolor,'<b>'
                            .show_status($db->f('status')).'</b>');
            $bx->box_column('','',$bgcolor,'<b>'
                            .timestr(mktimestamp($db->f('creation'))).'</b>');
	}

	$bx->box_columns_end();
	$bx->box_body_end();
	$bx->box_end();
    }

    if ($db->num_rows() == 0) {
	print "<p>".$t->translate("There are no developers that have "
                                  ."offered themselves as referees").".<p>\n";
    }
}

/**
 * Form to offer oneself as referee
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
function referees_form($proid)
{
    global $bx, $t, $sess, $auth;

    $bx->box_begin();
    $bx->box_title($t->translate('Offer yourself as project referee'));
    $bx->box_body_begin();
    print html_form_action('PHP_SELF',array('proid' => $proid),'POST');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','45%','','<b>'
                     .$t->translate('Your username').'</b>: ');
    $bx->box_column ('left','55%','',$auth->auth['uname']);

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','45%','','<b>'
                     .$t->translate('Check if you want to be a referee')
                     .'</b>: ');
    $bx->box_column ('left','55%','',html_checkbox('check','check',''));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','45%','','&nbsp;');
    $bx->box_column ('left','55%','',html_form_submit($t->translate('Submit'),
                                                      'submit'));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Insert a new referee into database
 * (after that it shows the referees for the project)
 *
 * @param   int       $proid         Project id
 * @param   string    $user          Username of the user who does the proposal
 *
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 * @see show_referees()
 */
function referees_insert($proid, $user)
{
    global $db;

    $db->query("INSERT referees SET proid='$proid',referee='$user',"
               ."status='P'");

    if ( !function_exists( 'monitor_mail' ) ) {
        include('monitorlib.inc');
    }
    // WISH: config.inc should not be required another time,
    // as it is done at the beginning of every page.
    include('config.inc');
    monitor_mail($proid, 'referee', 'New referee proposed for project $proid',
                 'Event has happened');

    show_referees($proid);
}
?>
