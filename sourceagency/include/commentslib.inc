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
# Library with the functions for comments
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: commentslib.inc,v 1.9 2002/06/20 12:05:27 riessen Exp $
#
######################################################################

/**
 * Shows comments on a project
 *
 * Function comments_show() can be used in several ways
 * it just depends on the number of arguments that are passed to it
 *
 * * * * * * * * *
 *
 * comments_show($proid, $type, $number)
 *
 * This will show all the comments of the type $type made on number $number
 * There will be no tree structure. All comments (originals and responses are treated equally)
 *
 * * * * * * * * *
 *
 * comments_show($proid, $type, $number, '', $ref)
 *
 * This will show comments of the type $type made on number $number in tree form
 * This should show the tree having $ref as root
 * For seeing the whole tree, $ref should be 0
 *
 * * * * * * * * *
 *
 * comments_show($proid, $type, $number, $id)
 *
 * This shows only that specific comment with that id
 * (and that referes to the specified type and to the given number)
 *
 * * * * * * * * *
 *
 * comments_show($proid, $type, $number, $id, $ref)
 *
 * This will show all comments of the same level (given by ref) and its subree
 * By means of an anchor, the displayed comment is that with the given id
 *
 * * * * * * * * *
 *
 * @param   int       $proid         Project id
 * @param   string    $type_cmt      Comment type (on a news item, on a proposal etc.)
 * @param   int       $number        Item number (depends on comment type value)
 * @param   int       $cmt_id        Comment ID
 * @param   int       $ref           Reference to the comment parent
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 * @global  object    $sess          PHPLib session object
 *
 * @access  public
 */
function comments_show($proid, $type_cmt, $number, $cmt_id, $ref)
{
    global $t, $bx, $db, $sess;

    $number_selection = ("AND number='"
                         .(is_set_and_not_empty( $number ) ? $number : 0)."'");

    $id_selection = (is_set_and_not_empty($cmt_id) ? "AND id='$cmt_id'" : '');

    $ref_selection = "AND ref='".(is_set_and_not_empty($ref) ? $ref:0)."'";

    $db->query("SELECT * FROM comments, auth_user WHERE proid='$proid' "
               ."AND type='$type_cmt' ".$number_selection." ".$id_selection
               ." ".$ref_selection." AND user_cmt=username ORDER BY "
               ."creation_cmt ASC");

    while($db->next_record()) {
	htmlp_anchor($db->f('id'));
    	$bx->box_begin();
    	$bx->box_title($t->translate('Comment')." #".$db->f('id').": "
                       .stripslashes($db->f('subject_cmt')));
    	$bx->box_body_begin();
    	$timestamp = mktimestamp($db->f('creation_cmt'));
        echo '<b>';
	lib_pnick($db->f('user_cmt'));
        echo ' - '.timestr($timestamp)."</b>\n";
    	echo '<p>'.$db->f('text_cmt')."\n";

   	$bx->box_body_end();
   	$bx->box_end();

	lib_comment_it($proid, $db->f('type'), $db->f('number'), $db->f('id'), 
                    'Re:'.$db->f('subject_cmt'), $t->translate('Comment it!'));

	$db_ref = new DB_SourceAgency;
	$db_ref->query("SELECT ref FROM comments WHERE proid='$proid' "
                       ."AND type='".$db->f("type")."' AND id='"
                       .$db->f('ref')."'");

	$db_ref->next_record();
	lib_previous_comment($proid, $db->f('type'), $db->f('number'), 
                             $db_ref->f('ref'), $t->translate('Previous'));

  	lib_show_comments_on_it($proid, $type_cmt, $number, $db->f('id'));
    }
}

/**
 * Form to insert a comment
 *
 * @param   int       $proid         Project id
 *
 * @global  string    $subject       Comment subject (head)
 * @global  string    $text          Comment text (body)
 * @global  int       $number        Item number (depends on comment type value)
 * @global  int       $ref           Reference to the comment parent
 * @global  string    $type          Comment type
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $auth          PHPLib authentication object
 * @global  object    $sess          PHPLib session object
 *
 * @access  public
 */
function comments_form($proid)
{
    global $bx, $t, $auth, $sess;
    global $subject, $text, $number, $ref, $type;

    $bx->box_begin();
    $bx->box_title($t->translate('Your Comment'));
    $bx->box_body_begin();
    htmlp_form_action('PHP_SELF', array('proid' => $proid), 'POST');
    htmlp_form_hidden('type', $type);
    htmlp_form_hidden('number', $number);
    htmlp_form_hidden('ref', $ref);

    $bx->box_columns_begin(2);

    $bx->box_column ('right', '30%', '', '<b>'
                     .stripslashes($t->translate('Subject')).'</b> (128): ');
    $bx->box_column ('left', '70%', '', html_input_text('subject', 40, 128,
                                                        stripslashes($subject)));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right', '30%', '', '<b>'
                     .stripslashes($t->translate('Body')).'</b> (*): ');
    $bx->box_column ('left', '70%', '', html_textarea('text', 40, 7, 'virtual', 255,
                                                      stripslashes($text)));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right', '30%', '', '&nbsp;');
    $bx->box_column ('left', '70%', '', 
                     html_form_submit($t->translate('Preview'), 'preview')
                     .html_form_submit($t->translate('Submit'), 'submit'));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Enables to preview the comment form before submitting it
 *
 * @param   int       $proid         Project id
 *
 * @global  string    $subject       Comment subject (head)
 * @global  string    $text          Comment text (body)
 * @global  int       $number        Item number (depends on comment type value)
 * @global  int       $ref           Reference to the comment parent
 * @global  string    $type          Comment type
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $auth          PHPLib authentication object
 * @global  object    $sess          PHPLib session object
 *
 * @access  public
 */
function comments_preview($proid)
{
    // FIXME: proid argument is not used, it can be removed.
    global $t, $bx, $auth, $sess;
    global $subject, $text;

    $bx->box_begin();
    $bx->box_title('<center><b>'.$t->translate('PREVIEW').'</b></center>');
    $bx->box_title($t->translate('Comment').' '.stripslashes($subject));
    $bx->box_body_begin();
    $timestamp = time();
    echo '<b>';
    // FIXME: this database query does nothing. What is its purpose?
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT email_usr FROM auth_user WHERE username='"
                     .$auth->auth["uname"]."'");
    $db_local->next_record();
    lib_pnick($auth->auth['uname']);
    echo ' - '.timestr($timestamp)."</b>\n";
    echo '<p>'.stripslashes($text)."\n";

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Inserts a comment into database
 * (and shows it afterwards)
 *
 * @param   int       $proid         Project id
 * @param   string    $user          Comment author (registered user)
 * @param   string    $type_cmt      Comment type
 * @param   int       $number        Item number (depends on comment type value)
 * @param   int       $ref           Reference to the comment parent
 * @param   string    $subject       Comment subject (head)
 * @param   string    $text          Comment text (body)
 *
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 */
function comments_insert($proid, $user, $type_cmt, $number, 
                         $ref, $subject, $text)
{
    global $db, $t;

    $db->query("SELECT * FROM comments WHERE proid='$proid' AND "
               ."type='$type_cmt' AND number='$number'");
    $db->next_record();
    $id = $db->num_rows()+1;

    $db->query("INSERT comments SET proid='$proid',user_cmt='$user',"
               ."type='$type_cmt',number='$number',id='$id',ref='$ref',"
               ."subject_cmt='$subject',text_cmt='$text'");

    if ( !function_exists( 'monitor_mail' ) ) {
        include('monitorlib.inc');
    }
    /* WISH: config.inc included only once */
    include('config.inc');

    monitor_mail($proid, 'comments',  $t->translate('New Comment'),  
                 $t->translate('New Comment on project')
                 ." $proid\n Subject: $subject\n "
                 .$t->translate("by")." $user\n\n "
                 .$t->translate("You can see it at")
                 .": $sys_url/comments.php3?proid=$proid&type=$type_cmt&"
                 ."number=$number&id=$id");

    comments_show($proid, $type_cmt, $number, $id, $ref);
}

/**
 * Updates a comment in database
 * (and shows it afterwards)
 *
 * @param   int       $proid         Project id
 * @param   string    $user          Comment author (registered user)
 * @param   string    $type_cmt      Comment type
 * @param   int       $number        Item number (depends on comment type value)
 * @param   int       $cmt_id        Comment id
 * @param   int       $ref           Reference to the comment parent
 * @param   string    $subject       Comment subject (head)
 * @param   string    $text          Comment text (body)
 *
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 */
function comments_modify($proid, $user, $type_cmt, $number,
                         $cmt_id, $ref, $subject, $text)
{
    global $db, $t;

    $db->query("UPDATE comments SET user_cmt='devel', subject_cmt='$subject', "
               ."text_cmt='$text' WHERE proid='$proid' AND type='$type_cmt'  "
               ."AND number='$number' AND id='$cmt_id' AND ref='$ref'");

    if ( !function_exists( 'monitor_mail' ) ) {
        include('monitorlib.inc');
    }
    /* WISH: config.inc included only once */
    include('config.inc');

    monitor_mail($proid, 'comments', $t->translate('Comment modified'), 
                 $t->translate('Modification on a comment on project')
                 ." $proid\n Subject: $subject\n ".$t->translate("by")
                 ." $user\n\n "
                 .$t->translate("You can see it at")
                 .": $sys_url/comments.php3?"
                 ."proid=$proid&type=$type_cmt&number=$number&id=$cmt_id");

    comments_show($proid, $type_cmt, $number, $cmt_id, $ref);
}

/**
 * Form to update an already existing comment in database
 *
 * @param   int       $proid         Project id
 *
 * @global  string    $subject       Comment subject (head)
 * @global  string    $text          Comment text (body)
 * @global  int       $number        Item number (depends on comment type value)
 * @global  int       $cmt_id        Comment id *NOT USED*
 * @global  int       $ref           Reference to the comment parent *NOT USED*
 * @global  string    $type          Comment type *NOT USED*
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $sess          PHPLib session object *NOT USED*
 *
 * @access  public
 */
function comments_modify_form($proid)
{
    global $bx, $t;
    global $subject, $text, $number, $creation;

    $bx->box_begin();
    $bx->box_title($t->translate('Modifying Comments'));
    $bx->box_body_begin();
    htmlp_form_action('PHP_SELF', array('proid' => $proid), 'POST');
    htmlp_form_hidden('creation', $creation);
    htmlp_form_hidden('number', $number);

    $bx->box_columns_begin(2);

    $bx->box_column ('right', '30%', '', '<b>'
                     .stripslashes($t->translate('Subject')).'</b> (128): ');
    $bx->box_column ('left', '70%', '', html_input_text('subject', 40, 128,
                                                     stripslashes($subject)));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right', '30%', '', '<b>'
                     .stripslashes($t->translate('Body'))
                     .'</b> (*): ');
    $bx->box_column ('left', '70%', '', 
                     html_textarea('text', 40, 7, 'virtual', 255,
                                   stripslashes($text)));
    $bx->box_next_row_of_columns();

    $bx->box_column ('right', '30%', '', '&nbsp;');
    $bx->box_column ('left', '70%', '', 
                     html_form_submit($t->translate('Preview'), 'preview')
                     .html_form_submit($t->translate('Submit'), 'submit'));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * There are parameters missing for a succesfully comment
 * insertion into database
 *
 * @global  object    $t             Translation object
 *
 * @access  public
 */
function comments_missing_parameters()
{
    global $t;

    /* WISH: config.inc included only once */
    require('config.inc');
    $be = new box('80%', $th_box_frame_color, $th_box_frame_width, 
                  $th_box_title_bgcolor, $th_box_title_font_color, 
                  $th_box_title_align, $th_box_body_bgcolor, 
                  $th_box_error_font_color, $th_box_body_align);

    $be->box_full($t->translate('Error'), $t->translate('Missing Parameters'));
}

?>