<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Gerrit Riessen (Gerrit.Riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Library with the functions for news
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: newslib.inc,v 1.7 2002/05/28 08:56:47 riessen Exp $
#
######################################################################

/**
 * This library contains functionality needed for editing and
 * displaying news on a project
 *
 * This step handles data from the 'news' table in the database
 * Table database contains following data:
 *
 * proid bigint(20) unsigned DEFAULT '0' NOT NULL:
 *      Project Id (Foreign Key)
 *  user_news varchar(16) NOT NULL:
 *      (Registered) user who submitted the news
 *      Users have to be enabled to submit news on this project
 *      by means of a proper project configuration
 *  subject_news varchar(128) NOT NULL:
 *      Subject (head) of the news
 *  text_news blob NOT NULL:
 *      Body of the news
 *  creation_news timestamp(14):
 *      Timestamp of the last time the rating was modified
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, lib, translate, box, auth
*/

/**
 * Form for inserting a news item
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $bx            Box object
 * @global  object    $t             Translation object
 * @global  object    $sess          PHPLib session object
 *
 * @global  string    $subject       Head of the news
 * @global  string    $text          Text of the news
 *
 * @access  public
 */
function newsform($proid)
{
    global $bx, $t, $sess;
    global $subject, $text;

    $bx->box_begin();
    $bx->box_title($t->translate('Editing News'));
    $bx->box_body_begin();

    htmlp_form_action('PHP_SELF',array('proid' => $proid),'POST');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','',
                     '<b>'.$t->translate('Subject').'</b> (128): ');
    $bx->box_column ('left','70%','',html_input_text('subject',40,128,$subject));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','',
                     '<b>'.$t->translate('Body').'</b> (*): ');
    $bx->box_column ('left','70%','',
                     html_textarea('text',40,7,'virtual',255,$text));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','&nbsp;');
    // NOI18N in html_form_submit calls
    $bx->box_column ('left','70%','',
                     html_form_submit('Preview','preview')
                     .html_form_submit('Submit','submit'));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Form for modifying an existing news item
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $bx            Box object
 * @global  object    $t             Translation object
 * @global  object    $sess          PHPLib session object
 *
 * @global  string    $subject       Head of the news
 * @global  string    $text          Text of the news
 * @global  timestamp $creation      Creation date of the news item
 *
 * @access  public
 */
function news_modify_form($proid)
{
    global $bx, $t, $sess;
    global $subject, $text, $creation;

    $bx->box_begin();
    $bx->box_title($t->translate('Modifying News'));
    $bx->box_body_begin();

    htmlp_form_action('PHP_SELF',array('proid' => $proid),'POST');

    htmlp_form_hidden('creation',$creation);

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','',
                     '<b>'.$t->translate('Subject').'</b> (128): ');
    $bx->box_column ('left','70%','',
                     html_input_text('subject',40,128,$subject));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','','<b>'.$t->translate('Body').'</b> (*): ');
    $bx->box_column ('left','70%','',
                     html_textarea('text',40,7,'virtual',255,$text));

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
 * Preview of a news item
 *
 * @param   int       $proid         Project id
 *                                   Really it is not directly used in the function ....
 *                                   ---> we could leave it out!
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $auth          PHPLib authentication object
 * @global  object    $sess          PHPLib session object
 *
 * @global  string    $subject       Head of the news
 * @global  string    $text          Text of the news
 *
 * @access  public
 */
function news_preview($proid='')
{
    global $t, $bx, $auth, $sess;
    global $subject, $text;

    $bx->box_begin();
    $bx->box_title('<center><b>'.$t->translate('PREVIEW').'</b></center>');
    $bx->box_title($t->translate('News').': '.$subject);
    $bx->box_body_begin();
    lib_pnick($auth->auth['uname']);
    $timestamp = time();
    echo ' - '.timestr($timestamp)."</b>\n<p>".$text."\n";
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows all news for a given project
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database abstraction object
 * @global  object    $sess          PHPLib session object
 *
 * @access  public
 */
function newsshow($proid)
{
    global $t, $bx, $db, $sess;

    $query = ( "SELECT * FROM news,auth_user WHERE proid='$proid' "
               ."AND user_news=username ORDER BY creation_news DESC" );
    $db->query($query);
    while($db->next_record()) {
	$bx->box_begin();
	$bx->box_title($t->translate('News').': '.$db->f('subject_news'));
	$bx->box_body_begin();
   	$timestamp = mktimestamp($db->f('creation_news'));
        echo '<b>';
	lib_pnick($db->f('user_news'));
        echo ' - '.timestr($timestamp)."</b>\n";
    	echo '<p>'.$db->f('text_news')."\n";
    	$bx->box_body_end();
    	$bx->box_end();

	lib_comment_it( $proid,'News',$db->f('creation_news'),'0',
        'Re:' . $db->f('subject_news'), $t->translate('Comment This News!'));

	lib_show_comments_on_it($proid,'News',$db->f('creation_news'),'0');
    }

    if ($db->num_rows() == 0) {
        print ( '<p>'.$t->translate('There have not been posted any news '
                                    .'by the project owner(s)').".<p>\n");
    }
}

/**
 * Inserts the news item into the database
 *
 * @param   int       $proid         Project id
 * @param   string    $user          Username of the user who inserts the news item
 * @param   string    $subject       Head of the news
 * @param   string    $text          Text of the news
 *
 * @global  object    $db            PHPLib database abstraction object
 *
 * @access  public
 */
function news_insert($proid, $user, $subject, $text)
{
    global $db;

    $db->query("INSERT news SET proid='$proid',user_news='$user',"
               . "subject_news='$subject',text_news='$text'");

    if ( floor( phpversion() ) >= 4 ) {
        // this change allows unit test to test this and news_modify
        include_once('monitorlib.inc');
    } else {
        include('monitorlib.inc');
    }
    include('config.inc');

    monitor_mail($proid, 'news', 'New News', "New News on project $proid\n "
                 ."Subject: $subject\n by $user\n\n You can see it at: ");

    newsshow($proid);
}

/**
 * Inserts the news item into the database
 *
 * @param   int       $proid         Project id
 * @param   string    $user          Username of the user who inserts the news item
 * @param   string    $subject       Head of the news
 * @param   string    $text          Text of the news
 * @param   timestamp $creation      Creation date of the news item to modify
 *
 * @global  object    $db            PHPLib database abstraction object
 *
 * @access  public
 */
function news_modify($proid, $user, $subject, $text, $creation)
{
    global $db;

    $db->query("UPDATE news SET user_news='$user', subject_news='$subject', "
               . "text_news='$text' WHERE proid='$proid' AND "
               . "creation_news='$creation'");

    if ( floor( phpversion() ) >= 4 ) {
        // this change allows unt test to test this and news_modify
        include_once('monitorlib.inc');
    } else {
        include('monitorlib.inc');
    }
    /* WISH: config.inc should only be included once */
    include('config.inc');

    monitor_mail($proid,'news', 'News Modified', 'Modification on News on '
                 ."project $proid\n Subject: $subject\n by $user\n\n You can see it ");
    newsshow($proid);
}
?>