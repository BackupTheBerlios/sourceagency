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
# Library with the functions for sponsoring involvements
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: sponsoringlib.inc,v 1.12 2002/12/09 10:27:34 erik Exp $
#
######################################################################

/**
 * Functions for sponsoring suggestions
 * This type of suggestions can be done all through the project
 * (more money is always welcome)
 *
 * This step handles data from the 'sponsoring' table in the database
 * Table database contains following data:
 *
 *  spoid bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment:
 *      Sponsoring Id (Primary Key)
 *  proid bigint(20) unsigned DEFAULT '0' NOT NULL:
 *      Project Id (Foreign Key)
 *  sponsor varchar(16) NOT NULL:
 *      Username of the sponsor with this sponsoring suggestion
 *  budget int(8) NOT NULL:
 *      Sum of money with which the sponsor wants to sponsor the project
 *  status char(1) NOT NULL:
 *      Status of this developing proposal (accepted, rejected, pending)
 *  sponsoring_text blob:
 *      Any comments on his sponsoring suggestion
 *  valid bigint(14):
 *      Date until this developing proposal is valid
 *  begin bigint(14):
 *      Start date for project development
 *  finish bigint(14):
 *      Deadline for the project
 *  creation timestamp(14):
 *      Timestamp of the last time the rating was modified
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, lib, translate, box, auth
 */

/**
 * Form to enter a sponsoring suggestion (by a sponsor, of course)
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $sess          PHPLib session object
 *
 * @global  string    $sponsoring_text
 * @global  int       $budget
 * @global  int       $valid_day
 * @global  int       $valid_month
 * @global  int       $valid_year
 * @global  int       $begin_day
 * @global  int       $begin_month
 * @global  int       $begin_year
 * @global  int       $finish_day
 * @global  int       $finish_month
 * @global  int       $fisnish_year
 *
 * @access  public
 */
function sponsoring_form($proid)
{
    global $bx, $t, $sess;
    global $sponsoring_text, $budget, $valid_day, $valid_month, $valid_year, $begin_day, $begin_month, $begin_year, $finish_day, $finish_month, $finish_year;

    $bx->box_begin();
    $bx->box_title($t->translate('Sponsoring Involvement'));
    $bx->box_body_begin();
    print html_form_action('PHP_SELF',array('proid' => $proid),'POST');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','','<b>'
                     .$t->translate('Valid until').'</b>: ');
    $bx->box_column ('left','70%','',select_date('valid',$valid_day,
                                               $valid_month,$valid_year));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Begin').'</b>: ');
    $bx->box_column ('left','70%','',select_date('begin',$begin_day,
                                                 $begin_month,$begin_year));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Finish').'</b>: ');
    $bx->box_column ('left','70%','',select_date('finish',$finish_day,
                                                 $finish_month,$finish_year));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Budget (in Euro)')
                     .'</b> (12): ');
    $bx->box_column ('left','70%','',html_input_text('budget',12,12,$budget));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'
                     .$t->translate('Sponsoring Comment').'</b> (*): ');
    $bx->box_column ('left','70%','',html_textarea('sponsoring_text',40,7,
                                                   'virtual',255,
                                                   $sponsoring_text));

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
 * Displays all the sponsoring suggestions
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 * @global  object    $sess          PHPLib session object
 * @global  object    $auth          PHPLib authentication object
 *
 * @access  public
 */
function show_sponsorings($proid)
{
    global $t, $bx, $db, $sess, $auth;

    $query = "SELECT * FROM sponsoring,auth_user WHERE proid='$proid' "
       ."AND sponsor=username ORDER BY sponsoring.creation ASC";
    $db->query($query);
    while($db->next_record()) {
        $bx->box_begin();
        $bx->box_title($t->translate('Sponsor Involvement'));
        $bx->box_body_begin();
		echo '<b>';
        $timestamp = mktimestamp($db->f('creation'));
        lib_pnick($db->f('username'));

        echo ' - '.timestr($timestamp)."</b>\n";
        echo '<p><b>'.$t->translate('Max. sum of money').':</b> '
          .$db->f('budget')." Euro\n";
        echo '<br><b>'.$t->translate('Status').':</b> '
          .show_status($db->f('status'))."\n";
        echo '<br><b>'.$t->translate('Validity').':</b> '
          .timestr_middle(mktimestamp($db->f('valid')))."\n";
        echo '<br><b>'.$t->translate('Begin wished').':</b> '
          .timestr_middle(mktimestamp($db->f('begin')))."\n";
        echo '<br><b>'.$t->translate('Finish before').':</b> '
          .timestr_middle(mktimestamp($db->f('finish')))."\n";
        if (($db->f('sponsoring_text'))) {
            echo '<p><b>'.$t->translate('Comment to the involvement')
              .':</b> '.$db->f('sponsoring_text')."\n";
        }
        if($db->f('status') == 'P' && is_accepted_sponsor($proid)) {
          $bx->box_title(html_link('sponsoring_accepted.php3',
                                   array('proid' => $proid, 
                                         'sponsor' => $db->f('username')),
                                   $t->translate('Accept this sponsor '
                                                 .'involvement')));
        }
        $bx->box_body_end();
        $bx->box_end();

        lib_comment_it($proid,'Sponsoring',$db->f('spoid'),'0',
                       'Comment on Sponsor Involvement #'.$db->f('spoid'),
                       $t->translate('Comment it!'));

        lib_show_comments_on_it($proid,'Sponsoring',$db->f('spoid'),'0');
    }

    if ($db->num_rows() == 0) {
        print "<p>".$t->translate("There have not been posted any "
                                  ."sponsoring involvement wishes to "
                                  ."this project").".<p>\n";
    }
}

/**
 * Preview of a sponsoring suggestion
 *
 * @param   int       $proid         Project id
 *                                   this argument is not really used in the function
 *                                   ---> We could leave it out
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $auth          PHPLib authentication object
 * @global  object    $sess          PHPLib session object
 *
 * @global  string    $sponsoring_text
 * @global  int       $budget
 * @global  int       $valid_day
 * @global  int       $valid_month
 * @global  int       $valid_year
 * @global  int       $begin_day
 * @global  int       $begin_month
 * @global  int       $begin_year
 * @global  int       $finish_day
 * @global  int       $finish_month
 * @global  int       $fisnish_year
 *
 * @access  public
 */
function sponsoring_preview($proid='')
{
    global $t, $bx, $auth, $sess;
    global $sponsoring_text, $budget, $valid_day, $valid_month, $valid_year, $begin_day, $begin_month, $begin_year, $finish_day, $finish_month, $finish_year;

    $bx->box_begin();
    $bx->box_title('<center><b>'.$t->translate('PREVIEW').'</b></center>');
    $bx->box_title($t->translate('Sponsor Involvement'));
    $bx->box_body_begin();
    $timestamp = time();
    lib_pnick($auth->auth['uname']);
    echo ' - '.timestr($timestamp)."</b>\n";
    echo '<p><b>'.$t->translate('Max. sum of money').":</b> $budget Euro\n";
    echo '<br><b>'.$t->translate('Status').":</b> ".$t->translate('Proposed')."\n";
    echo '<br><b>'.$t->translate('Validity').':</b> '
      .timestr_middle(mktimestamp(date_to_timestamp($valid_day,$valid_month,
      $valid_year)))."\n";
    echo '<br><b>'.$t->translate('Begin wished').':</b> '
      .timestr_middle(mktimestamp(date_to_timestamp($begin_day,$begin_month,
      $begin_year)))."\n";
    echo '<br><b>'.$t->translate('Finish before').':</b> '
      .timestr_middle(mktimestamp(date_to_timestamp($finish_day,$finish_month,
      $finish_year)))."\n";

    if ($sponsoring_text) {
        echo "<p><b>".$t->translate("Comment to the involvement")
          .":</b> $sponsoring_text\n";
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Inserts the sponsoring suggestion into database
 * (shows the sponsoring suggestion just inserted after it)
 *
 * @param   int       $proid         Project id
 * @param   int       $user          Sponsor
 * @param   string    $sponsoring_text
 * @param   int       $budget
 * @param   int       $valid_day
 * @param   int       $valid_month
 * @param   int       $valid_year
 * @param   int       $begin_day
 * @param   int       $begin_month
 * @param   int       $begin_year
 * @param   int       $finish_day
 * @param   int       $finish_month
 * @param   int       $fisnish_year
 *
 * @global  object    $db            PHPLiba database object
 * @global  object    $auth          PHPLib authentication object
 *
 * @access  public
 */
function sponsoring_insert($proid, $user, $sponsoring_text, $budget, $valid_day, $valid_month, $valid_year, $begin_day, $begin_month, $begin_year, $finish_day, $finish_month, $finish_year)
{
    global $db, $auth, $t;

    $valid = date_to_timestamp($valid_day, $valid_month, $valid_year);
    $begin = date_to_timestamp($begin_day, $begin_month, $begin_year);
    $finish = date_to_timestamp($finish_day, $finish_month, $finish_year);

    $db->query("SELECT COUNT(*) FROM sponsoring WHERE proid='$proid'");
    $db->next_record();
    $status = ($db->f("COUNT(*)") > 0 ? 'P' : 'A');
    
    $db->query( "SELECT * FROM sponsoring WHERE proid='$proid' AND "
                . "sponsor='$user'" );
    
    if ( $db->num_rows() == 1 ) {
        /** Retrieve the current budget and check whether the new budget
            is greater than the previous budget value **/
        $db->next_record();
        $old_budget = $db->f( "budget" );
        if ( lib_past_step( $proid, 2 ) && $old_budget > $budget ) {
            generate_failed_box( $t->translate("Sponsoring"), 
              $t->translate("After Project Step 2, budget may not be "
                            . "decreased")." ("
              .$t->translate("Old budget").": $old_budget, "
              .$t->translate("New budget").": $budget)" );
        } else {
            $db->query("UPDATE sponsoring SET "
                       . "sponsoring_text='$sponsoring_text', "
                       . "budget='$budget', status='$status', valid='$valid',"
                       . "begin='$begin', finish='$finish' "
                       . "WHERE proid='$proid' AND sponsor='$user'");
        }
    } else if ( $db->num_rows() == 0 ) {
        $db->query("INSERT sponsoring SET proid='$proid',sponsor='$user',"
                   ."sponsoring_text='$sponsoring_text',budget='$budget', "
                   ."status='$status', valid='$valid',begin='$begin',"
                   ."finish='$finish'");
    } else {
        echo "<p><b>".$t->translate("Database Failure").":</b> "
            .$t->translate("it seems you have more than one sponsorship! "
                           ."Please advice the administrator and have the "
                           . "database corrected.");
    }

    // If first sponsor on a developing project
    // That means if he is sponsor but not project initiator
    if ($status == 'A' && !is_project_initiator($proid)) {
        $db->query("UPDATE configure SET sponsor='".$auth->auth["uname"]
                   ."' WHERE proid='$proid'");
        echo '<p><b>'.$t->translate('Congratulations').'</b>. '
            .$t->translate('You are the first sponsor. You can').' ';
        htmlp_link('configure_edit.php3',array('proid' => $proid),
                   $t->translate('configure this project'));
    }

    if ( floor( phpversion() ) >= 4 ) {
        include_once('monitorlib.inc');
    } else {
        include('monitorlib.inc');
    }

    // WISH: config.inc should not be required another time,
    // as it is done at the beginning of every page.
    include('config.inc');
    monitor_mail($proid,'sponsoring', 
                 $t->translate('New Sponsor for project')." $proid", 
                 $t->translate('Event has happened'));

    show_sponsorings($proid);

    if (is_project_initiator($proid)) {
        lib_insertion_finished();
    }
}
?>