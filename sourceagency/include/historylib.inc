<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Library with functionality that allows to track project history
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: historylib.inc,v 1.4 2002/04/10 23:58:25 grex Exp $
#
######################################################################

/**
 * This library contains functionality to track the history of the projects
 *
 * This step handles data from the 'history' table in the database
 * Table database contains following data:
 *
 * FIXME: seems that this library has nothing really to do with this table!
 *
 * proid bigint(20) unsigned DEFAULT '0' NOT NULL:
 *      Project Id (Foreign Key)
 *  history_user varchar(16) NOT NULL:
 *      Username of the (registered) user who has commited this action
 *  type varchar(16) NOT NULL:
 *      TODO: description missing
 *  action varchar(255) NOT NULL:
 *      TODO: description missing
 *  creation timestamp(14):
 *      Timestamp of the last time the rating was modified
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, lib, translate, box, auth
 */

/**
 * TODO: description missing
 *
 * @param   string    $table_name      TODO: description missing
 * @param   string    $table_creation  TODO: description missing
 * @param   string    $table_user      TODO: description missing
 * @param   int       $table_subject   TODO: description missing
 *
 * @global  object    $db            PHPLib database object
 *
 * @global  array     $history       Actions done on the project
 * @global  int       $i             TODO: description missing
 * @global  int       $proid         Project ID
 * @global  string    $what          Action that has taken place
 * @global  string    $who           Username of the user who commited the action
 * @global  string    $subject       TODO: description missing
 *
 * @access  public
 */
function history_extract_table($table_name, $table_creation, $table_user, $table_subject)
{
    global $db;
    global $history, $i, $proid, $what, $who, $subject;

    $db->query("SELECT * FROM $table_name WHERE proid='$proid'");
    while($db->next_record()) {
	$history[++$i] = $db->f($table_creation);
	$string = substr($history[$i], -9);
	$what[$string] = $table_name;
	if ($table_user == 'milestone_user') {
	    $db_milestone = new DB_SourceAgency;
	    $db_milestone->query("SELECT developer FROM developing WHERE developing.devid='".$db->f('devid')."'");
	    $db_milestone->next_record();
	    $who[$string] = $db_milestone->f('developer');
        } else {
            $who[$string] = $db->f($table_user);
	}

	$action_subject = $db->f($table_subject);
	if (!strcmp($action_subject,'')) {
            $action_subject = $table_subject;
	}
	$subject[$string] = $action_subject;
    }
}

/**
 * Bubblesort function: orders the items in the history array chronologically
 *
 * @param   array      $history
 *
 * @access  public
 */
function bubblesort(&$history)
{
    for ($i = sizeof($history); $i >= 0; $i--) {
	for ($j = 1; $j <= $i; $j++) {
	    if ($history[$j-1] < $history[$j]) {
		$temp = $history[$j-1];
		$history[$j-1] = $history[$j];
		$history[$j] = $temp;
	    }
	}
    }
}

/**
 * Shows the project history
 *
 * @param   array     $history       Actions commited on the project
 *
 * @global  object    $bx            Box object
 * @global  object    $t             Translation object
 *
 * @global  string    $what          Action that has taken place
 * @global  string    $who           Username of the user who commited the action
 * @global  string    $subject       TODO: description missing
 *
 * @access  public
 */
function show_history($history)
{
    global $bx, $t;
    global $what, $who, $subject;

    $bx->box_begin();
    $bx->box_title($t->translate('Project History'));
    $bx->box_body_begin();
    $bx->box_columns_begin(4);

    $bx->box_column('','','','<b>Date</b>');
    $bx->box_column('','','','<b>Type</b>');
    $bx->box_column('','','','<b>&nbsp;</b>');
    $bx->box_column('','','','<b>User</b>');

    for ($i = sizeof($history)-1; $i > -1; $i--) {
	if ($i%2 != 0) {
            $bgcolor = 'gold';
	} else {
            $bgcolor = '#FFFFFF';
	}

	$bx->box_next_row_of_columns();

	$bx->box_column('','',$bgcolor,'<b>'.timestr(mktimestamp($history[$i])).'</b>');
	$key = substr ($history[$i], -9);
	$bx->box_column('','',$bgcolor,'<b>'.$what[$key].'</b>');
	$bx->box_column('','',$bgcolor,'<b>'.$subject[$key].'</b>');
	$bx->box_column('','',$bgcolor,'<b>'.$who[$key].'</b>');
    }
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}
?>