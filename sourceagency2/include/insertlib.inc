<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Gerrit Riessen (Gerrit.Riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Library with the functions for inserting a new project
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: insertlib.inc,v 1.2 2004/02/02 12:30:16 helix Exp $
#
######################################################################

/**
 * This library contains functionality needed for inserting a new
 * project (this could be seen as step 0)
 *
 * This step handles data from the 'description' table in the database
 * Table database contains following data:
 *
 * proid bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment:
 *      Project Id (Primary Key)
 *  project_title varchar(128) NOT NULL:
 *      Title of the project
 *  type varchar(16) NOT NULL:
 *      Type of project:
 *            developing project (started by a developer looking for sponsorship)
 *            sponsored project (started by sponsors)
 *  description blob NOT NULL:
 *      General description of the project's goals
 *  description_user varchar(16) NOT NULL:
 *      (registered) user that inserts the project
 *  volume varchar(16) NOT NULL:
 *      Volume of the project (depends on the amount of money that should be invested in it)
 *  status int(1) NOT NULL:
 *      Status of the project (accepted, pending, rejected)
 *  creation timestamp(14)
 *      Timestamp of the last time the rating was modified
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, lib, translate, box, auth
 */

/**
 * New project insertion form
 *
 * @global  string    $project_title
 * @global  string    $volume
 * @global  string    $type
 * @global  string    $description
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  public
 */
function insert_form()
{
    global $bx, $t;
    global $project_title, $volume, $type, $description;

    $bx->box_begin();
    $bx->box_title($t->translate('New Project'));
    $bx->box_body_begin();
    htmlp_form_action('insert.php', array(), 'POST');

    $bx->box_columns_begin(2);

    $bx->box_column('right', '30%', '', '<b>'.$t->translate('Project Title')
                     .'</b> (128): ');
    $bx->box_column('left', '70%', '',
                    html_input_text('project_title', 40, 128, $project_title));
 
    $bx->box_next_row_of_columns();

    $bx->box_column('right', '30%', '', '<b>'.$t->translate('Type').'</b>: ');
    $bx->box_column('left', '70%', '',
                    select_from_config('type',  'project_types', $type));

    $bx->box_next_row_of_columns();

    $bx->box_column('right', '30%', '', '<b>'
                    .$t->translate('Brief description')
                    .'</b> (*): ');
    $bx->box_column('left', '70%', '', 
                    html_textarea('description', 40, 7, 'virtual',
                                  255, $description));

    $bx->box_next_row_of_columns();

    $bx->box_column('right', '30%', '', '<b>'.$t->translate('Project Volume')
                    .'</b>: ');
    $bx->box_column('left', '70%', '',
                    select_from_config('volume', 'project_volume', $volume));

    $bx->box_next_row_of_columns();

    $bx->box_column('right', '30%', '', '&nbsp;');
    $bx->box_column('left', '70%', '', 
                    html_form_submit($t->translate('Submit'), 'Submit'));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();  
}

/**
 * We look if the insertion has the fields we require: project title and description
 *
 * @param   string    $project_title
 * @param   string    $description
 *
 * @return  boolean                    Returns if everything is correct
 *                                     (has been filled out)
 *
 * @access  public
 */
function everything_filled($project_title, $description)
{
    if (!isset($project_title) || empty($project_title)) {
	project_title_not_set();
    	return 0;
    }
    if (!isset($description) || empty($description)) {
	description_not_set();
   	return 0;
    }
    return 1;
}

/**
 * Error box that appears when a project has not been given a title
 *
 * @global  string    $volume
 * @global  string    $type
 * @global  string    $description
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  public
 */
function project_title_not_set()
{
    global $t;
    global $description, $type, $volume;

    /* WISH: config.inc should only be included once */
    require('config.inc');
    $be = new box('', $th_box_frame_color, $th_box_frame_width,
		  $th_box_title_bgcolor, $th_box_title_font_color,
		  $th_box_title_align, $th_box_body_bgcolor,
		  $th_box_error_font_color, $th_box_body_align);

    $be->box_full($t->translate('Error'),
		  $t->translate('No Project Title specified').'.'
                  .'<br>'.$t->translate('Please select').' '
		  .html_link('insform.php', 
                             array('description' => $description, 
                                   'type' => $type, 
                                   'volume' => $volume), 
                             $t->translate('New Projects')));
}

/**
 * Error box that appears when a project has not been given a description
 *
 * @global  string    $volume
 * @global  string    $type
 * @global  string    $project_title
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  public
 */
function description_not_set()
{
    global $t;
    global $volume, $type, $project_title;

    require('config.inc');
    $be = new box('', $th_box_frame_color, $th_box_frame_width,
		  $th_box_title_bgcolor, $th_box_title_font_color,
		  $th_box_title_align, $th_box_body_bgcolor,
		  $th_box_error_font_color, $th_box_body_align);

    $be->box_full($t->translate('Error'),
		  $t->translate('No Project Description specified').'.'
                  .'<br>'.$t->translate('Please select').' '
		  .html_link('insform.php', 
                             array('project_title' => $project_title, 
                                   'type' => $type, 'volume' => $volume), 
                             $t->translate('New Projects')));
}

/**
 * Looks that there's no other project with the same title
 *
 * @param   string    $project_title
 *
 * @global  object    $t             Translation object
 * @global  object    $db            PHPLib database object
 * @return  boolean                  True if there's no other project with the give title 
 *
 * @access  public
 */
function no_other_project_with_same_title($project_title)
{
    global $db, $t;

    $query="SELECT * FROM description WHERE project_title='$project_title'";
    $db->query($query);
    if ($db->num_rows() == 1) {
        /* WISH: config.inc should only be include once */ 
        require('config.inc');
	$be = new box('', $th_box_frame_color, $th_box_frame_width,
		      $th_box_title_bgcolor, $th_box_title_font_color,
		      $th_box_title_align, $th_box_body_bgcolor,
		      $th_box_error_font_color, $th_box_body_align);
	$be->box_full($t->translate('Error'), 
                       $t->translate('Another project with that title '
                                     .'already exists').'.'
                      .'<br>'.$t->translate('Please select').' '
                      .html_link('insform.php', array(), 
                                 $t->translate('New Projects')));
    	return 0;
    }
    return 1;
}

/**
 * Inserts a new project into database
 * (and informs the sys admins about it per mail)
 *
 * @param   string    $project_title
 * @param   string    $description
 * @param   string    $type
 * @param   string    $volume
 *
 * @global  string    $ml_notify
 * @global  object    $db            PHPLib database object
 * @global  object    $auth          PHPLib authentication object
 * @global  object    $t             Translation object
 *
 * @access  public
 */
function insert_into_database($project_title, $description, $type, $volume)
{
    global $db, $auth, $t, $ml_notify;

    $status = 0;

    $tables = 'description';
    $set = ( "project_title='$project_title', type='$type', "
             ."description='$description',  description_user='"
             .$auth->auth['uname']."',  volume='$volume', status='$status', "
             ."description_creation=NOW()");
    $db->query("INSERT $tables SET $set");

    if ($db->affected_rows() == 0) {
        lib_die('Database error in insert_into_database() in insertlib.inc'
		.": INSERT $tables SET $set");
    }

    /* Get project index */
    $columns = '*';
    $where = "project_title='$project_title'";
    $db->query("SELECT $columns FROM $tables WHERE $where");
    $db->next_record();

    /* Insert default views for this project */
    $tables = 'views';
    $set = ( "configure='Project Participants', views='Project Participants',"
             ." news='Project Initiator', history='Everybody', "
             ."step3='Everybody', step4='Everybody', step5='Everybody',"
             ."cooperation='Everybody',proid=".$db->f('proid'));
    $db->query("INSERT $tables SET $set");

    /* Insert new counters */
    $tables = 'counter';
    $set = 'proid='.$db->f('proid');
    $db->query("INSERT $tables SET $set");

    /* Select and show new/updated project with counters */
    $columns = '*';
    $tables = 'description,auth_user';
    $where = ( "description.proid='".$db->f("proid").
               "' AND description.description_user=auth_user.username" );
    $group = 'description.proid';

    $query  = "SELECT $columns FROM $tables WHERE $where GROUP BY $group";
    lib_show_description($query);

    echo $t->translate('Congratulations. You have just defined a new project')
      .".\n<br>".$t->translate('The next step is to').' '
      .html_link('configure_edit.php', 
                 array('proid' => $db->f('proid')), 
                 $t->translate('configure your project!'))
      ."<p><br>\n";

    lib_insertion_information();

    if ($ml_notify) {
    	$msg = $t->translate('insert project') . " $project_title "
             .$t->translate('by') . ' ' . $auth->auth['uname'] . '.';
        	mailuser('editor', $t->translate('Project inserted'), $msg);
    }
}
?>
