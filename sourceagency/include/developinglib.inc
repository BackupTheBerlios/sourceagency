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
# Library with the functions for developing proposals (step 2)
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: developinglib.inc,v 1.5 2002/03/27 17:04:41 grex Exp $
#
######################################################################

/**
 * Functions for developing proposal submission, display, update, etc. (Step 2)
 *
 * This step handles data from the 'developing' table in the database
 * Table database contains following data:
 *
 *  devid bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment:
 *      Developing Id (Primary Key)
 *  proid bigint(20) unsigned DEFAULT '0' NOT NULL:
 *      Project Id (Foreign Key)
 *  content_id bigint(20) unsigned DEFAULT '0' NOT NULL:
 *      Developing proposals have to be referred on contents (aka specifications)
 *  developer varchar(16) NOT NULL:
 *      Username of the developer who proposes it
 *  cost int(8) NOT NULL:
 *      Total amount of money required for developing this project
 *  license varchar(64) NOT NULL:
 *      Project License (@see license table)
 *  status char(1) NOT NULL:
 *      Status of this developing proposal (accepted, rejected, pending)
 *  cooperation varchar(64) NOT NULL:
 *      If cooperation is wanted (not functional yet) (TODO)
 *  valid bigint(14) NOT NULL:
 *      Date until this developing proposal is valid
 *  start bigint(14) NOT NULL:
 *      Start date for the development
 *  duration int(4) NOT NULL:
 *      Project duration (in weeks)
 *  creation timestamp(14):
 *      Timestamp of the last time the rating was modified
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, lib, translate, box, auth
 */

/**
 * Displays one developing proposal
 *
 * @param   int       $proid         Project id
 * @param   int       $content_id    Content (aka specification) id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 */
function show_developings($proid, $content_id)
{
    global $t, $bx, $db;

    $query = "SELECT * FROM developing,auth_user WHERE proid='$proid' AND content_id='$content_id' AND developer=username ORDER BY developing.creation DESC";
    $db->query($query);
    if ($db->num_rows() != 0) {
        while($db->next_record()) {
	    $bx->box_begin();
	    $bx->box_title($t->translate('Developing Proposal'));
	    $bx->box_body_begin();
   	    $timestamp = mktimestamp($db->f("creation"));
	    lib_pnick($db->f("username"));
            echo ' - '.timestr($timestamp)."</b>\n";
    	    echo '<p><b>Cost:</b> '.$db->f("cost")." euros\n";
    	    echo '<br><b>License:</b> '.$db->f("license")."\n";
    	    echo '<br><b>Cooperation:</b> '.$db->f("cooperation")."\n";
    	    echo '<br><b>Status:</b> '.show_status($db->f("status"))."\n";
    	    echo '<br><b>Validity:</b> '.timestr_middle(mktimestamp($db->f("valid")))."\n";
    	    echo '<br><b>Start possible:</b> '.timestr_middle(mktimestamp($db->f("start")))."\n";
    	    echo '<br><b>Duration:</b> '.$db->f("duration")." weeks\n";
    	    $bx->box_body_end();
    	    $bx->box_end();
        }
    } else {
	print "There have not been posted any developement proposals to this project.\n";
    }
}

/**
 * Displays one developing proposal for preview
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $auth          PHPLib authentication object
 *
 * @global  int       $cost
 * @global  string    $license
 * @global  string    $cooperation
 * @global  int       $valid_day
 * @global  int       $valid_month
 * @global  int       $valid_year
 * @global  int       $start_day
 * @global  int       $start_month
 * @global  int       $start_year
 * @global  int       $duration
 *
 * @access  public
 */
function developing_preview($proid)
{
    global $t, $bx, $auth;
    global $cost, $license, $cooperation, $valid_day, $valid_month, $valid_year, $start_day, $start_month, $start_year, $duration;

    $bx->box_begin();
    $bx->box_title('<center><b>'.$t->translate('PREVIEW').'</b></center>');
    $bx->box_title($t->translate('Developing Proposal'));
    $bx->box_body_begin();
    $timestamp = time();
    echo '<b>';
    lib_pnick($auth->auth["uname"]);
    echo ' - '.timestr($timestamp)."</b>\n";
    echo "<p><b>Cost:</b> $cost euros\n";
    echo "<br><b>License:</b> $license\n";
    echo "<br><b>Cooperation:</b> $cooperation\n";
    echo "<br><b>Status:</b> Proposed\n";
    echo '<br><b>Validity</b> '.timestr_middle(mktimestamp(date_to_timestamp($valid_day,$valid_month,$valid_year)))."\n";
    echo '<br><b>Start possible:</b> '.timestr_middle(mktimestamp(date_to_timestamp($start_day,$start_month,$start_year)))."\n";
    echo "<br><b>Duration:</b> $duration weeks\n";
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Form to enter a developing proposal (by a developer)
 *
 * @param   int       $proid         Project id
 * @param   int       $content_id    Content (aka. specification) id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $sess          PHPLib session object
 *
 * @global  int       $cost
 * @global  string    $license
 * @global  string    $cooperation
 * @global  int       $valid_day
 * @global  int       $valid_month
 * @global  int       $valid_year
 * @global  int       $start_day
 * @global  int       $start_month
 * @global  int       $start_year
 * @global  int       $duration
 *
 * @access  public
 */
function developing_form($proid, $content_id)
{
    global $bx, $t, $sess;
    global $cost, $license, $cooperation, $valid_day, $valid_month, $valid_year, $start_day, $start_month, $start_year, $duration;

    $bx->box_begin();
    $bx->box_title($t->translate('Developement proposal'));
    $bx->box_body_begin();
    print html_form_action('PHP_SELF',array('proid' => $proid),'POST');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','','<b>'.$t->translate('Cost').'</b> (12): ');
    $bx->box_column ('left','70%','',html_input_text('cost',12,12,$cost));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('License').'</b> (12): ');
    $bx->box_column ('left','70%','',license($license));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Developer cooperation wanted?').'</b>');
    $bx->box_column ('left','70%','',developing_select_cooperation($cooperation));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Valid until').'</b>: ');
    $bx->box_column ('left','70%','', select_date('valid',$valid_day,$valid_month,$valid_year));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Start').'</b>: ');
    $bx->box_column ('left','70%','',select_date('start',$start_day,$start_month,$start_year));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Duration (in weeks)').'</b>: ');
    $bx->box_column ('left','70%','',select_duration($duration));

    $bx->box_next_row_of_columns();

    htmlp_form_hidden('content_id',$content_id);

    $bx->box_column ('right','30%','','&nbsp;');
    $bx->box_column ('left','70%','',html_form_submit('Preview','preview').html_form_submit('Submit','submit'));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Form to modify an existing developing proposal (by the developer who inserted it)
 *
 * @param   int       $proid         Project id
 * @param   int       $content_id    Content (aka. specification) id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $sess          PHPLib session object
 *
 * @global  int       $cost
 * @global  string    $license
 * @global  string    $cooperation
 * @global  int       $valid_day
 * @global  int       $valid_month
 * @global  int       $valid_year
 * @global  int       $start_day
 * @global  int       $start_month
 * @global  int       $start_year
 * @global  int       $duration
 * @global  int       $creation
 *
 * @access  public
 */
function developing_modify_form($proid, $content_id)
{
    global $bx, $t, $sess;
    global $cost, $license, $cooperation, $valid_day, $valid_month, $valid_year, $start_day, $start_month, $start_year, $duration, $creation;

    $bx->box_begin();
    $bx->box_title($t->translate('Developement proposal modification'));
    $bx->box_body_begin();
    print html_form_action('PHP_SELF',array('proid' => $proid),'POST');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','','<b>'.$t->translate('Cost').'</b> (12): ');
    $bx->box_column ('left','70%','',html_input_text('cost',12,12,$cost));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('License').'</b> (12): ');
    $bx->box_column ('left','70%','',license($license));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Developer cooperation wanted?').'</b> (SELECT BOX)');
    $bx->box_column ('left','70%','',html_input_text('cooperation',12,12,$cooperation));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Valid until (select box)').'</b>: ');
    $bx->box_column ('left','70%','',html_input_text('valid',14,14,$valid));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Start (Select box)').'</b>: ');
    $bx->box_column ('left','70%','',html_input_text('start',14,14,$start));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Duration (Select box)').'</b>: ');
    $bx->box_column ('left','70%','',html_input_text('duration',3,3,$duration));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','&nbsp;');
    $bx->box_column ('left','70%','',html_form_submit('Preview','preview').html_form_submit('Submit','submit'));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Developing proposal is inserted into the database
 * (also displays the proposal after that)
 *
 * @param   int       $proid         Project id
 * @param   string    $user          Developer who proposes it
 * @param   int       $content_id    Content (aka. specification) id
 * @param   int       $cost
 * @param   string    $license
 * @param   string    $cooperation
 * @param   int       $valid_day
 * @param   int       $valid_month
 * @param   int       $valid_year
 * @param   int       $start_day
 * @param   int       $start_month
 * @param   int       $start_year
 * @param   int       $duration
 *
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 */
function developing_insert($proid, $user,$content_id, $cost, $license, $cooperation, $valid_day, $valid_month, $valid_year, $start_day, $start_month, $start_year, $duration)
{
    global $db;

    $valid = date_to_timestamp($valid_day, $valid_month, $valid_year);
    $start = date_to_timestamp($start_day, $start_month, $start_year);

    $db->query("INSERT developing SET proid='$proid',developer='$user',content_id='$content_id', cost='$cost', license='$license', cooperation='$cooperation', valid='$valid', start='$start', duration='$duration',status='P'");

    // WISH: config.inc should not be required another time,
    // as it is done at the beginning of every page.
    include('config.inc');
    include('monitorlib.inc');
    monitor_mail($proid, 'developing', "New Developing proposal for project $proid", 'Event has happened');

    show_developings($proid, $content_id);
}

/**
 * Modified developing proposal is updated in the database
 * (also displays the proposal after that)
 *
 * @param   int       $proid         Project id
 * @param   int       $content_id    Content (aka. specification) id
 * @param   string    $developer     Developer who does the modification
 * @param   int       $cost
 * @param   string    $license
 * @param   string    $cooperation
 * @param   int       $valid
 * @param   int       $start
 * @param   int       $duration
 * @param   int       $creation
 *
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 */
function developing_modify($proid, $content_id, $developer, $cost, $license, $cooperation, $valid, $start, $duration, $creation) {
    global $db;

    $db->query("UPDATE developing SET developer='$developer', cost='$cost', license='$license', cooperation='$cooperation', valid='$valid', start='$start', duration='$duration',status='M' WHERE proid='$proid' AND content_id='$content_id' AND creation='$creation'");
    show_developings($proid, $content_id);
}

/**
 * Shows the (unique) developing proposal that has been accepted for a project
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 */
function show_selected_developing($proid)
{
    global $t, $bx, $db;

    $query = "SELECT * FROM developing,auth_user WHERE proid='$proid' AND developer=username AND developing.status='A'";
    $db->query($query);
    if ($db->num_rows() != 1) {
        echo "<b><font color=red>The number of accepted developing proposals is not correct. SEe show_selected_developing()</font></b>\n";
    } else {
        $db->next_record();

        $bx->box_begin();
        $bx->box_title($t->translate('Developing Proposal'));
        $bx->box_body_begin();
        $timestamp = mktimestamp($db->f('creation'));
        echo '<b>by ';
        lib_pnick($db->f('username'));
        echo ' - '.timestr($timestamp)."</b>\n";
        echo '<p><b>Cost:</b> '.$db->f("cost")." euros\n";
        echo '<br><b>License:</b> '.$db->f("license")."\n";
        echo '<br><b>Cooperation:</b> '.$db->f("cooperation")."\n";
        echo '<br><b>Status:</b> '.show_status($db->f("status"))."\n";
        echo '<br><b>Validity</b> '.timestr_middle(mktimestamp($db->f("valid")))."\n";
        echo '<br><b>Start possible:</b> '.timestr_middle(mktimestamp($db->f("start")))."\n";
        echo '<br><b>Duration:</b> '.$db->f("duration")." weeks\n";
        $bx->box_body_end();
        $bx->box_end();
    }
}

/**
 * Select box for the duration field
 *
 * @param   int       $selected        Default number of weeks    
 * @return  string                     HTML select box
 * @access  public
 */
function select_duration($selected)
{
    $return = html_select('duration');

    for ($i=1; $i <= 100; $i++) {
 	if ($i == $selected) {
            $select='selected';
	} else {
            $select='';
	}
	$return .= html_select_option($i, $select, $i);
    }
    $return .= html_select_end();

    return($return);
}

/**
 * Select box for the cooperation field
 * TODO: this function requires refactoring once developing cooperation is allowed
 *
 * @param   string    $selected        Default value ('Yes', 'No', '')
 * @return  string                     HTML select box
 * @access  public
 */
function developing_select_cooperation($selected)
{
    $return = html_select('cooperation');
    if($selected=='No') {
        $select='selected';
    } else {
        $select = '';
    }
    $return .= html_select_option('No',$select,'No');
    $return .=htmlp_select_end();

    return($return);
}
?>