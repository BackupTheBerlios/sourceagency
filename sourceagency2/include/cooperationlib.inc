<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Library with the functions needed for developer cooperation
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: cooperationlib.inc,v 1.1 2003/11/21 12:56:01 helix Exp $
#
######################################################################

/**
 * This library contains functionality used for the developer
 * cooperation.
 *
 * This step handles data from the 'cooperation' table in the database
 * Table database contains following data:
 *
 * proid devint(20) unsigned DEFAULT '0' NOT NULL:
 *      Developing Id (Foreign Key)
 *  developer varchar(16) NOT NULL:
 *      Username of the developer who wants to cooperate
 *  cost int(8) NOT NULL:
 *      Cost (in EUR) the developer wants to assume
 *  status char(1) NOT NULL:
 *      Status of this proposal (accepted, pending, denied...)
 *  creation timestamp(14):
 *      Timestamp of the last time the rating was modified
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, lib, translate, box, auth
 */

/**
 * Form for submitting a cooperation proposal on a development 
 *
 * @param   int       $proid         Project id
 * @param   int       $devid         Developing id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  int       $cost          Cost (in EUR) the developer wants to assume
 *
 * @access  public
 */
function cooperation_form($proid, $devid)
{
    global $bx, $t;
    global $cost;

    $bx->box_begin();
    $bx->box_title($t->translate('Cooperation'));
    $bx->box_body_begin();
    htmlp_form_action('PHP_SELF',array('proid' => $proid, 
                                       'devid' => $devid),'POST');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','','<b>'
                     .$t->translate('Cost in euro').'</b> (7): ');
    $bx->box_column ('left','70%','',html_input_text('cost',7,7,$cost));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','&nbsp;');
    $bx->box_column ('left','70%','',
                     html_form_submit($t->translate('Preview'),'preview')
                     .html_form_submit($t->translate('Submit'),'submit'));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Preview cooperation proposal on a development 
 *
 * @param   int       $proid         Project id
 * @param   int       $devid         Developing id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $auth          PHPLib authentication object
 * @global  object    $sess          PHPLib session object
 * @global  int       $cost          Cost (in EUR) the developer wants to assume
 *
 * @access  public
 */
function cooperation_preview($proid, $devid)
{
    global $t, $bx, $auth, $sess;
    global $cost;

    $bx->box_begin();
    $bx->box_title('<center><b>'.$t->translate('PREVIEW').'</b></center>');
    $bx->box_title($t->translate('Cooperation'));
    $bx->box_body_begin();
    $timestamp = time();
    echo '<b>';
    lib_pnick($auth->auth['uname']);
    echo ' - '.timestr($timestamp)."</b>\n";
    echo "<p><b>Cost</b>: $cost euro\n";
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows all the cooperation proposals on a development
 *
 * @param   int       $proid         Project id
 * @param   int       $devid         Developing id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $auth          PHPLib authentication object
 * @global  object    $sess          PHPLib session object
 *
 * @access  public
 */
function cooperation_show($proid, $devid)
{
    global $t, $bx, $db, $sess;

    $query = "SELECT * FROM cooperation,auth_user WHERE devid='$devid' "
         ."AND developer=username ORDER BY creation DESC";
    $db->query($query);

    if ($db->num_rows() == 0) {
        print "<p>There have not been posted any cooperation proposals "
            ."by any developer.<p>\n";
    } else {
        while($db->next_record()) {
            $bx->box_begin();
            $bx->box_title($t->translate('Cooperation'));
            $bx->box_body_begin();
            $timestamp = mktimestamp($db->f('creation'));
            echo '<b>';
            lib_pnick($db->f('developer'));
            echo ' - '.timestr($timestamp)."</b>\n";
            echo '<p><b>'.$t->translate('Cost').'</b>: '.$db->f('cost')
                ." euro\n";
            echo '<br><b>'.$t->translate('Status').'</b>: '
                .show_status($db->f('status'))."\n";
            $bx->box_body_end();
            $bx->box_end();
            
            lib_comment_it($proid, 'Cooperation', $db->f('creation'), 0,
                           '', $t->translate('Comment This Cooperation!'));
            
            lib_show_comments_on_it($proid, 'Cooperation', 
                                            $db->f('creation'), 0);
        }
    }
}

/**
 * Inserts the development proposal into the database
 *
 * @param   int       $devid         Developing id
 * @param   string    $developer     Username of the developer who proposes it
 * @param   int       $cost          Cost (in EUR) for the development proposal
 *
 * @global  object    $db            PHPLib database abstraction object
 *
 * @access  public
 */
function cooperation_insert($devid, $developer, $cost)
{
    global $db;

    $db->query("INSERT cooperation SET devid='$devid',developer='$developer',"
               ."cost='$cost',status='P'");
    //FIXME: proid is not defined in this method ....
    cooperation_show(/** $proid **/ -1, $devid);
}

/**
 * Updates the development proposal in database
 *
 * @param   int       $devid         Developing id
 * @param   string    $developer     Username of the developer who proposes it
 * @param   int       $cost          Cost (in EUR) for the development proposal
 * @param   timestamp $creation      Creation date
 *
 * @global  object    $db            PHPLib database abstraction object
 *
 * @access  public
 */
function cooperation_modify($devid, $developer, $cost, $creation)
{
    global $db;

    $db->query("UPDATE cooperation SET developer='$developer', cost='$cost' "
               ."WHERE devid='$devid' AND creation='$creation'");
    // FIXME: proid is not defined in this function ...
    cooperation_show(/** $proid **/ -1, $devid);
}
?>
