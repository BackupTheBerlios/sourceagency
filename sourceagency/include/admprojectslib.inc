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
# Library with the functions needed to administrate pending projects
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: admprojectslib.inc,v 1.6 2002/04/08 21:32:47 grex Exp $
#
######################################################################

/**
 * Library with the functions needed to administrate pending projects
 *
 * Pending projects are thos projects who have its status set to '0'
 * in the 'description' table
 *
 * Accepting the project means to put its status to '1'
 * Rejecting the project means to put its status to '-1'
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, lib, translate, box, auth
 */

/**
 * Displays the projects to administrate
 * If projects are configured, they can be accepted/rejected
 * if not, they can only be deleted
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  public
 */
function show_admprojects()
{
    global $t, $bx;

    $db = new DB_SourceAgency;
    $query = ( "SELECT * FROM description,auth_user WHERE status='0' "
               ."AND description_user=username" );
    $db->query($query);

    if ($db->num_rows() != 0) {
        $bx->box_begin();
        $bx->box_title($t->translate('Pending Project Administration'));
        $bx->box_body_begin();
        $bx->box_columns_begin(7);

        $bx->box_column('','15%','','<b>Project Title</b>');
        $bx->box_column('center','10%','','<b>Type</b>');
        $bx->box_column('center','30%','','<b>Description</b>');
        $bx->box_column('center','10%','','<b>User</b>');
        $bx->box_column('center','7%','','<b>Volume</b>');
        $bx->box_column('center','7%','','<b>Configured?</b>');
        $bx->box_column('center','12%','','<b>Creation</b>');
        $bx->box_column('center','9%','','&nbsp;');

        while($db->next_record()) {
            if ($db->f('perms') != 'sponsor') {
                $bgcolor = '#ABCDEF';
            } else {
                $bgcolor = '#FEDCBA';
            }

            // initialization
            $configured = 0;

            $bx->box_next_row_of_columns();

            $bx->box_column('','',$bgcolor,'<b>'
                            .html_link('summary.php3',
                                       array('proid'=>$db->f('proid')),
                                       $db->f('project_title')).'</b>');
            $bx->box_column('','',$bgcolor,'<b>'.$db->f('type').'</b>');
            $bx->box_column('','',$bgcolor,'<b>'.$db->f('description').'</b>');
            $bx->box_column('center','',$bgcolor,'<b>'
                            .lib_nick($db->f('description_user')).'</b>');
            $bx->box_column('center','',$bgcolor,'<b>'.$db->f('volume')
                            .' </b>');

            $db_local = new DB_SourceAgency;
            $db_local->query("SELECT * FROM configure WHERE proid = '"
                             .$db->f("proid")."'");
            if($db_local->num_rows() > 0) {
                $configured +=1;
            }

            $db_local = new DB_SourceAgency;
            $db_local->query("SELECT perms FROM auth_user WHERE username='"
                             .$db->f("description_user")."'");
            $db_local->next_record();

            $table = ( $db_local->f('perms') == 'sponsor' ? 'sponsoring'
            : 'developing' );

            $db_local = new DB_SourceAgency;
            $db_local->query("SELECT * FROM $table WHERE proid = '"
                             .$db->f("proid")."'");
            if ($db_local->num_rows() > 0) {
                $configured +=2;
            }

	    /* initialization */
            $configured_output = '<b>No</b><br>'; 
            // TODO: should use reg exp instead of ==: user may have
            // TODO: multiple permissions
            if ($db->f('perms') == 'sponsor') {
                switch ($configured) {
                    case 3: {
                        $configured_output = '<b>Yes</b><br>'
                             .html_link($table.'.php3',
                             array('proid'=>$db->f('proid')),$table)
                             .'<br>'.html_link('configure.php3',
                                               array('proid'=>$db->f('proid')),
                                               'Configuration');  
                        break;
                    }
                    case 2: {
                        $configured_output = '<b>Partially</b><br>'
                             .html_link($table.'.php3',
                             array('proid'=>$db->f('proid')),$table); 
                        break;
                    }
                    case 1: {
                        $configured_output = '<b>Partially</b><br>'
                             .html_link('configure.php3',
                                        array('proid'=>$db->f('proid')),
                                        'Configuration'); 
                        break;
                    }
                    case 0: 
                    default: {
                        $configured_output = '<b>No</b><br>'; 
                        break;
                    }
                }
            } elseif ($db->f('perms') == 'devel') {
                switch ($configured) {
                    case 1: {
                        $configured_output = '<b>Yes</b><br>'
                             .html_link('configure.php3',
                                        array('proid'=>$db->f('proid')),
                                        'Configuration'); 
                        break;
                    }
                    case 0: 
                    default: {
                        $configured_output = '<b>No</b><br>'; 
                        break;
                    }
                }
            } else {
  	        /* User who submitted the project is neither developer nor sponsor */
  	        echo '<b><font color=red>Error in function show_admprojects()</b></font>';
            }

            $tstamp = mktimestamp($db->f('description_creation'));
            $bx->box_column('center','',$bgcolor, $configured_output);
            $bx->box_column('center','',$bgcolor,'<b>'
                            .timestr_short($tstamp)
                            .'</b>');
            if ($configured == 3 && $db->f('perms') == 'sponsor') {
                $bx->box_column('center','',$bgcolor,
                                html_form_action($PHP_SELF,array(), 'POST')
                                .html_form_hidden('proid', $db->f('proid'))
                                .html_form_submit('Review','review').'<br>'
                                .html_form_submit('Delete','delete')
                                .html_form_end());
            } elseif  ($configured == 1 && $db->f('perms') == 'devel') {
                $bx->box_column('center','',$bgcolor,
                                html_form_action($PHP_SELF,array(), 'POST')
                                .html_form_hidden('proid', $db->f('proid'))
                                .html_form_submit('Accept','accept').'<br>'
                                .html_form_submit('Delete','delete')
                                .html_form_end());
            } else {
                $bx->box_column('center','',$bgcolor,
                                html_form_action($PHP_SELF,array(), 'POST')
                                .html_form_hidden('proid', $db->f('proid'))
                                .'<br>'.html_form_submit('Delete','delete')
                                .html_form_end());
            }
        }

	/* The next rows show the colors that are used 
	   as background color for sponsoring / developing projects */
        $bx->box_next_row_of_columns();
        $bx->box_colspan(8,'center','#FFFFF','&nbsp;');
        $bx->box_next_row_of_columns();
        $bx->box_colspan(8,'center','#FEDCBA','Sponsored projects');
        $bx->box_next_row_of_columns();
        $bx->box_colspan(8,'center','#ABCDEF','Developing projects');

        $bx->box_columns_end();
        $bx->box_body_end();
        $bx->box_end();
    } else {
        $bx->box_full($t->translate('Pending Project Administration'),
                      $t->translate('No pending projects'));
    }
}

/**
 * Changes the status in the database
 * informs project initiator by mail about it
 *
 * @param   int       $proid         Project ID
 * @param   char      $status        '1' if accepted, '-1' if rejected
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  public
 */
function admprojects_insert($proid, $status)
{
    global $db, $sys_name;

    $db->query("UPDATE description SET status = '$status' "
               ."WHERE proid='$proid'");
    $db->query("SELECT project_title FROM description "
               ."WHERE proid='$proid'");	
    $db->next_record();
    $project_title = $db->f('project_title');

    if ( $status > -1 ) {
        $db->query("INSERT history SET proid='$proid',"
                   ."history_user='BerliOS editor',type='Review',"
                   ."action='Project reviewed by a SourceAgency Editor'");

        $message = ( "Project $project_title has been reviewed succesfully "
                     ."by a ".$GLOBALS["sys_name"]." editor." );
    } else {
        $message = ( "Project $project_title has not been accepted in "
                     . $GLOBALS["sys_name"]
                     .".\nPlease, contact the system "
                     ."administrators for furhter details.");
    }

    /* Informs the project initiator about this action */

    $db->query("SELECT email_usr FROM auth_user,description "
               ."WHERE username=description_user AND proid='$proid'");	
    $db->next_record();

    mail($db->f("email_usr"),"[".$GLOBALS["sys_name"]
    ."] Project $project_title reviewed", $message,"From: "
    .$GLOBALS["ml_fromaddr"]."\nReply-To: ".$GLOBALS["ml_replyaddr"]
    ."\nX-Mailer: PHP");
}
?>