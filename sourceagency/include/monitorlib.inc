<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org),
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gerrit Riessen (Gerrit.Riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Project monitoring library
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: monitorlib.inc,v 1.6 2002/05/28 08:56:06 riessen Exp $
#
######################################################################

/**
 * This library contains functionality needed for the monitoring
 * a project. Monitoring will allow users to be informed immediately
 * of events happening for a given project. There will be different
 * levels of monitoring (high, medium and low traffic).
 *
 * This step handles data from the 'monitor' table in the database
 * Table database contains following data:
 *
 *  proid bigint(20) unsigned DEFAULT '0' NOT NULL:
 *      Project Id (Foreign Key)
 *  username varchar(16) NOT NULL:
 *      Registed user that monitors the project
 *  importance varchar(16) NOT NULL:
 *       Levels of monitoring (high, medium and low traffic).
 *  creation timestamp(14):
 *      Timestamp of the last time the rating was modified
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, lib, translate, box, auth
 */

/**
 * Form that has to be filled out to monitor a project
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $bx            Box object
 * @global  object    $t             Translation object
 * @global  object    $sess          PHPLib session object
 * @global  object    $importance    importance of the events to be monitored
 *
 * @access  public
 */
function monitor_form($proid)
{
    global $bx, $t, $sess, $importance;

    $bx->box_begin();
    $bx->box_title($t->translate('Monitor this project'));
    $bx->box_body_begin();
    htmlp_form_action('PHP_SELF',array('proid' => $proid),'POST');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','','<b>'.$t->translate('Importance').'</b>: ');
    $bx->box_column ('left','70%','',select_importance($importance));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','&nbsp;');
    $bx->box_column ('left','70%','',html_form_submit('Preview','preview').html_form_submit('Submit','submit'));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Preview for the monitor form
 *
 * @param   int       $proid         Project id
 *                                   strictly $proid is not used
 *                                      ---> it could be removed!!
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $auth          PHPLib authentication project
 * @global  object    $sess          PHPLib session object
 * @global  object    $importance    importance of the events to be monitored
 *
 * @access  public
 */
function monitor_preview($proid = '')
{
    global $t, $bx, $auth, $sess, $importance;

    $bx->box_begin();
    $bx->box_title('<center><b>'.$t->translate('PREVIEW').'</b></center>');
    $bx->box_title($t->translate('Monitoring this project'));
    $bx->box_body_begin();
    lib_pnick($auth->auth['uname']);
    $timestamp = time();
    echo ' - '.timestr($timestamp)."</b>\n";
    echo "<p>You will recieve an email for every action in this project that has <b>$importance</b> importance.\n";
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows all the users that monitor a project
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $sess          PHPLib session object
 * @global  object    $importance    importance of the events to be monitored
 *
 * @access  public
 */
function monitor_show($proid)
{
    global $t, $bx, $db, $sess;

    $query = "SELECT * FROM monitor,auth_user WHERE proid='$proid' AND monitor.username=auth_user.username ORDER BY creation DESC";
    $db->query($query);
    if ($db->num_rows() != 0) {
  	$bx->box_begin();
  	$bx->box_title($t->translate('All these users are monitor this project'));
  	$bx->box_body_begin();
  	$bx->box_columns_begin(5);

  	$bx->box_column('center','10%','','<b>'
                        .$t->translate('Number').'</b>');
  	$bx->box_column('center','20%','','<b>'
                        .$t->translate('Username').'</b>');
  	$bx->box_column('center','20%','','<b>'
                        .$t->translate('Type').'</b>');
  	$bx->box_column('center','20%','','<b>'
                        .$t->translate('Importance filter').'</b>');
  	$bx->box_column('center','30%','','<b>'
                        .$t->translate('Creation').'</b>');
	$i=1;

        $colors = array( 1 => 'gold', 0 => '#FFFFFF' );
  	while($db->next_record()) {
            $bgcolor = $colors[ $i % 2 ];

	    $bx->box_next_row_of_columns();

	    $bx->box_column('center','',$bgcolor,'<b>'.$i.'</b>');
	    $bx->box_column('center','',$bgcolor,'<b>'.lib_nick($db->f('username')).'</b>');
	    $bx->box_column('center','',$bgcolor,'<b>'.$db->f('perms').'</b>');
	    $bx->box_column('center','',$bgcolor,'<b>'.$db->f('importance').'</b>');
	    $bx->box_column('center','',$bgcolor,'<b>'.timestr_middle(mktimestamp($db->f('creation'))).'</b>');
	    $i++;
  	}
	$bx->box_columns_end();
  	$bx->box_body_end();
  	$bx->box_end();

    } else {
        print "<p>".$t->translate("Nobody is monitoring this project").
            ".<p>\n";
    }
}

/**
 * Insertion of a monitoring whish into database
 *
 * @param   int       $proid         Project id
 * @param   string    $username      Username of the user who is monitoring
 * @param   string    $importance    'high', 'medium', 'low'
 *                                   level of monitored events
 *
 * @global  object    $db            PHPLib database abstraction
 *
 * @access  public
 */
function monitor_insert($proid, $username, $importance)
{
    global $db;

    $db->query("SELECT * FROM monitor WHERE proid='$proid' AND username='$username'");
    if ($db->num_rows() >0) {
	$db->next_record();
	monitor_modify($proid,$username,$importance,$db->f('creation'));
    } else { 
	$db->query("INSERT monitor SET proid='$proid',username='$username',importance='$importance'");

	/* config.inc should be included only once */
	include('config.inc');
	monitor_mail($proid,'monitor', "User $username monitors project $proid", 'Event has happened');

	monitor_show($proid);
    }
}

/**
 * Modification of monitoring in database
 *
 * @param   int       $proid         Project id
 * @param   string    $username      Username of the user who is monitoring
 * @param   string    $importance    'high', 'medium', 'low'
 *                                   level of monitored events
 * @param   timestamp $creation      Creation of the monitoring entry to be modified
 *
 * @global  object    $db            PHPLib database abstraction
 *
 * @access  public
 */
function monitor_modify($proid, $username, $importance, $creation)
{
    global $db;

    $db->query("UPDATE monitor SET importance='$importance' WHERE proid='$proid' AND username='$username'");

    /* WISH: config.inc should only be included once */
    include('config.inc');
    monitor_mail($proid,'monitor', "User $username does not monitor project $proid any more", 'Event has happened');

    monitor_show($proid);
}

/**
 * Sends an email with a given subject and message to all users with
 * certain permissions
 *
 * @param   string    $perms         Targets registered users with this permission
 * @param   string    $subject       Subject of the message
 * @param   string    $message       Body of the message
 *
 * @global  object    $t             Translation object
 * @global  object    $db            PHPLib database abstraction
 *
 * @access  public
 */
function mailuser($perms, $subj, $message)
{
    global $t, $db;

    $db->query("SELECT email_usr FROM auth_user WHERE perms LIKE '%$perms%'");
    while($db->next_record()) {
        mail($db->f('email_usr'),'['.$GLOBALS['sys_name'].'] '.$subj,$message,'From: '.$GLOBALS['ml_fromaddr']."\nReply-To: ".$GLOBALS['ml_replyaddr']."\nX-Mailer: PHP");
    }
}

/**
 * Sends an email to users who are monitoring a project
 * The importance of each event is taken from the config.inc file
 * and depends on its type (i.e. comments have a different importance level
 * than milestone submissions)
 *
 * @param   int       $proid         Project id
 * @param   string    $type          List of types with certain monitoring importance
 *                                   @see config.inc for a list of them
 *                                   they all start with $config_monitor_importance
 * @param   string    $subject       Subject of the message
 * @param   string    $message       Body of the message
 *
 * @global  object    $t             Translation object
 *
 * @access  public
 */
function monitor_mail($proid, $type, $subj, $message)
{
    global $t;

    $db_monitor = new DB_SourceAgency;

    /* WISH: config.inc should be included only once */
    include('config.inc');
    $variable = 'config_monitor_importance_'.$type;
    $importance = $$variable;

    $message .= "\n\nImportance of this event: $importance\n\nYou receive this e-mail because you monitor this project\n\nBerliOS SourceAgency http://sourceagency.berlios.de\n";

    if ($importance == 'high') {
        $importance = "AND importance='$importance'";
    } elseif ($importance == 'middle') {
        $importance = "AND (importance='$importance' OR importance='high')";
    } else {
        $importance = '';
    }

    $db_monitor->query("SELECT email_usr FROM auth_user,monitor WHERE monitor.username=auth_user.username AND proid='$proid' $importance");
    while($db_monitor->next_record()) {
    	mail($db_monitor->f('email_usr'),'['.$GLOBALS['sys_name'].'] '.$subj,$message,'From: '.$GLOBALS['ml_fromaddr']."\nReply-To: ".$GLOBALS['ml_replyaddr']."\nX-Mailer: PHP");
    }
}

/**
 * HTML Select box Form for importance selection
 *
 * @param   string    $selected      Option selected by default
 *
 * @global  object    $auth          PHPLib authentication object
 *
 * @access  public
 */
function select_importance($selected)
{
    global $auth;

    $return = html_select('importance');
    $return .= html_select_option('low', $selected=='low', 'low');
    $return .= html_select_option('medium', $selected=='medium', 'medium');
    $return .= html_select_option('high', $selected=='high', 'high');
    $return .= html_select_end();

    return($return);
}
?>
