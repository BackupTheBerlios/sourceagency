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
# Library with the functions for the personal start page every user has
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: personallib.inc,v 1.16 2002/05/02 12:02:22 riessen Exp $
#
######################################################################

/**
 * This library contains functionality needed for a personal start
 * page where any (registered) user can see the projects she is involved
 * in as well as his contributions (news, comments, etc.)
 *
 * This library interacts with many tables in the database, mainly
 * searching for the author(s) of the database entries
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, translate, box
 */

// NOI18N The whole library requires i18n

/**
 * Shows the global rating for the given user
 * (mean of all ratings)
 * Provides a link to page with all the ratings to the user
 *
 * @param   string    $username      Username
 * @global  object    $bx            Box object
 * @access  public
 * @see personal_ratings_long()
 */
function personal_ratings_short($username)
{
    global $bx;

    $bx->box_begin();
    $bx->box_title('Rating');
    $bx->box_body_begin();
    $i=0;

    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT rating FROM ratings WHERE "
                     ."to_whom='$username'");
    if ($db_local->num_rows() == 0) {
        print "Not rated yet\n";
    } else {
	while ($db_local->next_record()) {
	    $i+=1;
	    $sum += $db_local->f('rating');
	}
	print ( 'Global personal rating: ' . $sum/$i
               . " (rated $i times)<p align=right>\n" );
	htmlp_link('personal_ratings.php3',
                   array('username' => $username),
                   'See complete ratings...');
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows alls ratings for the given user
 *
 * @param   string    $username      Username
 * @global  object    $bx            Box object
 * @access  public
 * @see personal_ratings_long()
 */
function personal_ratings_long($username)
{
    global $bx;

    $bx->box_begin();
    $bx->box_title('All the rating on '.$username);
    $bx->box_body_begin();
    $i=0;

    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM ratings,description WHERE "
                     . "to_whom='$username' AND ratings.proid="
                     . "description.proid");
    if ($db_local->num_rows() == 0) {
        print "No rating yet\n";
    } else {
        while ($db_local->next_record()) {
            print ('Rated <b>'.$db_local->f('rating')
                   . '</b> in <b>'.$db_local->f('on_what')
                   . '</b> by <b>'.$db_local->f('by_whom')
                   . '</b> on project '
                   . html_link('summary.php3',
                               array('proid' => $db_local->f('proid')),
                               $db_local->f('project_title'))
                   . "<br>\n");
        }
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows the projects a (registered) user is involved int
 * and the status of the user to this projects (accepted, denied, etc.)
 *
 * ASSUME: user is either a sponsor or developer, if neither
 * ASSUME: then this function generates an invalid query.
 *
 * @param   string    $username      Username
 * @param   string    $status        Project status
 * @global  object    $bx            Box object
 * @access  public
 */
function personal_related_projects($username, $status)
{
    global $bx,$t;

    $bx->box_begin();
    $bx->box_title($t->translate('Involved Projects')
                    .' ('.show_status($status).')');
    $bx->box_body_begin();

    $db_local = new DB_SourceAgency;

    if (is_sponsor()) {
        $user_type = 'sponsor';
        $table = 'sponsoring';
    } elseif (is_developer()) { 
        $user_type = 'developer';
        $table = 'developing';
    } else {
        lib_die('error in personallib.inc in function '
                .'personal_related_projects(): User is whether editor '
                .'or sponsor. Users cannot have multiple permissions');
    }

    $db_local->query("SELECT * FROM $table,description WHERE "
                     . "$user_type='$username' AND $table.status='$status'"
                     . " AND $table.proid=description.proid  ORDER BY "
                     . "creation DESC");
    if ($db_local->num_rows() == 0) {
        print ( 'Not related to any project with '
               . show_status($status)." status\n" );
    }
    while ($db_local->next_record()) {
	print ( 'Project: ' 
               . html_link('summary.php3',
                           array('proid' => $db_local->f('proid')), 
                           $db_local->f('project_title'))
               . ' (step <b>' . $db_local->f('status') 
               . "</b>)<br>\n");
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows the 10 most recent comments a given user has submitted
 *
 * WISH: number of shown comments should be configurable
 *
 * REFACTOR: personal_comments_short only differ in their use of the variable
 * REFACTOR: $i, there really should be refactored with a single function 
 * REFACTOR: whether specifying whether long or short is required.
 *
 * @param   string    $username      Username
 * @global  object    $bx            Box object
 * @access  public
 * @see personal_comments_long()
 */
function personal_comments_short($username)
{
    global $bx;

    $bx->box_begin();
    $bx->box_title('Last 10 Comments by '.$username);
    $bx->box_body_begin();

    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM comments WHERE user_cmt='$username' "
                     . "AND comments.proid");
    if ($db_local->num_rows() == 0) {
        print "no comments posted\n";
    }
    $i=0;
    print "<ul>\n";
    while ($db_local->next_record() AND $i<11) {
	print '<br><li>';
	$db_count = new DB_SourceAgency;
	$db_count->query("SELECT COUNT(*) FROM comments WHERE proid='"
                         .$db_local->f("proid")."' AND type='"
                         .$db_local->f("type")."' AND ref='"
                         .$db_local->f("id")."'");
      	$db_count->next_record();
       	print ( 'Comment ('.$db_local->f('type').'): <b>'
                .html_link('comments.php3',
                           array('proid' => $db_local->f('proid'), 
                           'type' => $db_local->f('type'), 
                           'number' => $db_local->f('number'), 
                           'ref' => $db_local->f('ref')),
                           $db_local->f('subject_cmt'))
                .'</b> '
                .timestr_comment(mktimestamp($db_local->f('creation_cmt')))
                .' (<b>'.$db_count->f('COUNT(*)')
                ."</b> comments on it)\n" );
	$db_count->query("SELECT * FROM description WHERE proid='"
                         .$db_local->f('proid')."'");
	$db_count->next_record();
	print( '<br>&nbsp; &nbsp; &nbsp; posted to '
              .html_link('summary.php3',
                         array('proid' => $db_count->f('proid')), 
                         $db_count->f('project_title'))."<br>\n");
        $i++;
    }
    print"</ul>\n<p align=right>\n";
    if ($i>10) {
        htmlp_link('personal_comments.php3',
                   array('username' => $username),
                   'See all the comments...');
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows all comments a given user has submitted
 *
 * REFACTOR: replace the followng and above with a single common function
 *
 * @param   string    $username      Username
 * @global  object    $bx            Box object
 * @access  public
 * @see personal_comments_long()
 */
function personal_comments_long($username)
{
    global $bx;

    $bx->box_begin();
    $bx->box_title('All Comments by '.$username);
    $bx->box_body_begin();

    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM comments WHERE user_cmt='$username' "
                     . "AND comments.proid");
    if ($db_local->num_rows() == 0) {
        print "no comments posted\n";
    }

    $count=0;
    print "<ul>\n";
    while ($db_local->next_record() && $count < 10) {
	print '<br><li>';
	$db_count = new DB_SourceAgency;
	$db_count->query("SELECT COUNT(*) FROM comments WHERE "
                         ."proid='".$db_local->f("proid")
                         ."' AND type='".$db_local->f("type")
                         ."' AND ref='".$db_local->f("id")."'");
	$db_count->next_record();
	print ('Comment ('.$db_local->f('type').'): <b>'
               .html_link('comments.php3',
                          array('proid' => $db_local->f('proid'), 
                                'type' => $db_local->f('type'), 
                                'number' => $db_local->f('number'), 
                                'ref' => $db_local->f('ref')),
                          $db_local->f('subject_cmt'))
               .'</b> '
               .timestr_comment(mktimestamp($db_local->f('creation_cmt')))
               .' (<b>'.$db_count->f('COUNT(*)')
               ."</b> comments on it)\n");
	$db_count->query("SELECT * FROM description WHERE proid='"
                         .$db_local->f("proid")."'");
	$db_count->next_record();
	print ('<br>&nbsp; &nbsp; &nbsp; posted to '
               .html_link('summary.php3',
                          array('proid' => $db_count->f('proid')), 
                          $db_count->f('project_title'))
               ."<br>\n");
	$count++;
    }
    print "</ul>\n<p align=right>\n";

    if ($db_local->num_rows() > 10) {
        htmlp_link('personal_comments.php3',
                   array('username' => $username),
                   'See all the comments...');
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows the 5 most recent news a given user has submitted
 *
 * WISH: number of shown news should be configurable
 *
 * REFACTOR: personal_news_short only differ in their use of the variable
 * REFACTOR: $i, there really should be refactored with a single function 
 * REFACTOR: whether specifying whether long or short is required.
 *
 * @param   string    $username      Username
 * @global  object    $bx            Box object
 * @access  public
 * @see personal_news_long()
 */
function personal_news_short($username)
{
    global $bx;

    $bx->box_begin();
    $bx->box_title('Last 5 News by '.$username);
    $bx->box_body_begin();

    // ASSUME: that DB_sourceagency returns false for a next_record
    // ASSUME: if num_row == 0 .... ok, this is probably very likely!
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM news WHERE user_news='$username'");
    if ($db_local->num_rows() == 0) {
        print "no news posted\n";
    }

    $count=0;
    print "<ul>\n";

    while ($db_local->next_record() AND $count<5) {
	print '<br><li>';
	$db_count = new DB_SourceAgency;
	$db_count->query("SELECT COUNT(*) FROM comments WHERE proid='"
                         . $db_local->f("proid")
                         . "' AND type='News' AND ref='"
                         . $db_local->f("id") . "'");
	$db_count->next_record();
	print ( 'News: <b>'
               . html_link('news.php3',
                           array('proid' => $db_local->f('proid'))
                           ,$db_local->f('subject_news'))
               . '</b> ' 
               . timestr_comment(mktimestamp($db_local->f('creation_news')))
               .' (<b>'.$db_count->f('COUNT(*)')
               ."</b> comments on it)\n" );
	$db_count->query("SELECT * FROM description WHERE proid='"
                         .$db_local->f("proid")."'");
	$db_count->next_record();
	print ( '<br>&nbsp; &nbsp; &nbsp; posted to '
               . html_link('summary.php3',
                           array('proid' => $db_count->f('proid')), 
                $db_count->f('project_title'))."<br>\n");
        $count++;
    }
    print "</ul>\n<p align=right>\n";

    if ($db_local->num_rows() > 5) {
        htmlp_link('personal_news.php3',
                   array('username' => $username),
                   'See all the comments...');
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows all news a given user has submitted
 *
 * REFACTOR: personal_news_short only differ in their use of the variable
 * REFACTOR: $i, there really should be refactored with a single function 
 * REFACTOR: whether specifying whether long or short is required.
 *
 * @param   string    $username      Username
 * @global  object    $bx            Box object
 * @access  public
 * @see personal_news_long()
 */
function personal_news_long($username)
{
    global $bx;

    $bx->box_begin();
    $bx->box_title('All news by '.$username);
    $bx->box_body_begin();

    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM news WHERE user_news='$username' "
                     ."AND news.proid");
    if ($db_local->num_rows() == 0) {
        print "no news posted\n";
    }

    print "<ul>\n";
    while ($db_local->next_record()) {
	print '<br><li>';
	$db_count = new DB_SourceAgency;
	$db_count->query("SELECT COUNT(*) FROM comments WHERE proid='"
                         .$db_local->f("proid")
                         ."' AND type='News' AND ref='"
                         .$db_local->f("id")."'");
	$db_count->next_record();
	print ('News: <b>'
               .html_link('news.php3',
                          array('proid' => $db_local->f('proid')),
                          $db_local->f('subject_news'))
               .'</b> '
               .timestr_comment(mktimestamp($db_local->f('creation_news')))
               .' (<b>'.$db_count->f('COUNT(*)')
               ."</b> comments on it)\n");
	$db_count->query("SELECT * FROM description WHERE proid='"
                        .$db_local->f("proid")."'");
	$db_count->next_record();
	print( '<br>&nbsp; &nbsp; &nbsp; posted to '
              .html_link('summary.php3',
                         array('proid' => $db_count->f('proid')), 
                         $db_count->f('project_title'))."<br>\n");
    }
    print"</ul>\n<p align=right>\n";
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows all projects the given user has proposed himself as a consultant
 *
 * @param   string    $username      Username
 * @param   string    $status        Status of the consultancy proposal
 * @global  object    $bx            Box object
 * @access  public
 */
function personal_consultants($username, $status)
{
    global $bx, $t;

    $bx->box_begin();
    $bx->box_title($t->translate('Consultant').' ('.show_status($status).')');
    $bx->box_body_begin();
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM consultants,description WHERE "
                     . "consultant='$username' AND consultants.status"
                     . "='$status' AND consultants.proid="
                     . "description.proid");
    if ($db_local->num_rows() == 0) {
        print "No consultant proposal with this status\n";
    } else {
	while($db_local->next_record()) {
            print ('Project: '
                   .html_link('step1.php3',
                              array('proid' => $db_local->f('proid')), 
                              $db_local->f('project_title'))
                   .' (step <b>'.$db_local->f('status')."</b>)<br>\n");
        }
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows all projects the given user has proposed himself as a referee
 *
 * REFACTOR: above and below into a common function
 *
 * @param   string    $username      Username
 * @param   string    $status        Status of the consultancy proposal
 * @global  object    $bx            Box object
 * @access  public
 */
function personal_referees($username, $status)
{
    global $bx, $t;

    $bx->box_begin();
    $bx->box_title($t->translate('Referee').' ('.show_status($status).')');
    $bx->box_body_begin();
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM referees,description WHERE "
                     . "referee='$username' AND referees.status='$status'"
                     . " AND referees.proid=description.proid");
    if ($db_local->num_rows() == 0) {
        print "No referee proposal with this status\n";
    } else {
	while($db_local->next_record()) {
            print( 'Project: '
                  .html_link('step4.php3',
                             array('proid' => $db_local->f('proid')), 
                             $db_local->f('project_title'))
                  .' (step <b>'.$db_local->f('status')."</b>)<br>\n");
        }
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows all projects the given user has proposed himself to cooperate
 * as a developer
 *
 * REFACTOR: above and below into a common function
 *
 * @param   string    $username      Username
 * @param   string    $status        Status of the consultancy proposal
 * @global  object    $bx            Box object
 * @access  public
 */
function personal_cooperation($username, $status)
{
    global $bx;

    $bx->box_begin();
    $bx->box_title('Developing Cooperation ('.show_status($status).')');
    $bx->box_body_begin();
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM cooperation,description,developing "
                     . "WHERE cooperation.developer='$username' AND "
                     . "cooperation.status='$status' AND "
                     . "cooperation.devid = developing.devid AND "
                     . "developing.proid=description.proid");
    if ($db_local->num_rows() == 0) {
        print "No developing cooperation proposal with this status\n";
    } else {
        while($db_local->next_record()) {
            print ( 'Project: '
                   .html_link('step2.php3',
                              array('proid' => $db_local->f('proid')), 
                              $db_local->f('project_title'))
                   .' (to this '
                   .html_link('step2.php3',
                              array('proid' => $db_local->f('proid'), 
                                    'show_proposals' => 'yes', 
                                    'which_proposals' => $db_local->f('devid')),
                                     'development').")<br>\n");
	  }
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows all projects the given user is their project initiator
 *
 * @param   string    $username      Username
 * @global  object    $bx            Box object
 * @access  public
 */
function personal_my_projects($username)
{
    global $bx;

    $bx->box_begin();
    $bx->box_title('My Projects');
    $bx->box_body_begin();
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM description WHERE "
                     . "description_user='$username' ORDER BY "
                     . "description_creation DESC");
    if ($db_local->num_rows() == 0) {
        print "No personal projects\n";
    } else {
	while($db_local->next_record()) {
            print( 'Project: '
                  .html_link('summary.php3',
                             array('proid' => $db_local->f('proid')), 
                             $db_local->f('project_title'))
                  .' (step <b>'.$db_local->f('status')."</b>)<br>\n");
	}
    }
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows all projects the given user is monitoring
 *
 * @param   string    $username      Username
 * @global  object    $bx            Box object
 * @access  public
 */
function personal_monitored_projects($username)
{
    global $bx;

    $bx->box_begin();
    $bx->box_title('Monitored Projects');
    $bx->box_body_begin();
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM monitor,description WHERE "
                     . "monitor.proid=description.proid AND "
                     . "monitor.username='$username' ORDER BY "
                     . "creation DESC");
    if ($db_local->num_rows() == 0) {
        print "No monitored projects\n";
    } else {
	while($db_local->next_record()) {
	    print( 'Project: '
                  .html_link('summary.php3',
                             array('proid' => $db_local->f('proid')), 
                             $db_local->f('project_title'))
                  .' (step <b>'.$db_local->f('status')."</b>)<br>\n");
	}
    }
    $bx->box_body_end();
    $bx->box_end();
}
?>