<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Library with the functions for the faqs
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: faqlib.inc,v 1.6 2002/07/22 11:26:54 riessen Exp $
#
######################################################################

/**
 * This library contains functionality needed for the faq
 *
 * It handles data from the 'faq' table in the database
 * Table database contains following data:
 *
 *  faqid int(8) unsigned DEFAULT '0' NOT NULL auto_increment:
 *       FAQ ID (Primary Key)
 *  language varchar(24) NOT NULL:
 *       (Human) language in which the FAQ is written
 *  question blob NOT NULL:
 *       Question
 *  answer blob NOT NULL:
 *       Answer
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, translate, box
 */

/**
 * Box with the FAQ form
 *
 * @global  object    $sess          PHPLib session object
 * @global  object    $bx            Box object
 * @global  object    $t             Translation object
 *
 * @access  public
 */
function faqform()
{
    global $sess, $bx, $t;
    global $question, $answer;

    $bx->box_begin();
    $bx->box_title($t->translate('New Frequently Asked Question'));
    $bx->box_body_begin();
    htmlp_form_action('PHP_SELF');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','',
                     '<b>'.$t->translate('Question').'</b> (*): ');
    $bx->box_column ('left', '70%', '',
                     html_textarea('question', 40, 4, 'virtual', 255, 
                                   $question));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right', '30%', '',
                     '<b>'.$t->translate('Answer').'</b> (*): ');
    $bx->box_column ('left','70%','',
                     html_textarea('answer', 40, 7, 'virtual', 255, $answer));

    $bx->box_next_row_of_columns();

    htmlp_form_hidden('create', 2);
    $bx->box_column ('right','30%','','&nbsp;');
    $bx->box_column ('left','70%','',
                     html_form_submit($t->translate('Create')));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Shows a FAQ
 *
 * WISH: it should be better to do not pass this as parameter
 * @param   object    $db            PHPLib database abstraction object
 *
 * @global  object    $bx            Box object
 * @global  object    $t             Translation object
 *
 * @access  public
 */
function faqshow($db)
{
    global $t, $bx;

    $bx->box_full($t->translate('Question').'</B>: '.$db->f('question'),
                  '<b>'.$t->translate('Answer').'</b>: '.$db->f('answer'));
}

/**
 * Form for modifying FAQ
 *
 * WISH: it should be better to do not pass this as parameter
 * @param   object    $db            PHPLib database abstraction object
 *
 * @global  object    $sess          PHPLib session object
 * @global  object    $bx            Box object
 * @global  object    $t             Translation object
 *
 * @access  public
 */
function faqmod($db)
{
    global $sess, $t, $bx;

    $bx->box_begin();
    $bx->box_title($t->translate('Modify a Frequently Asked Question'));
    $bx->box_body_begin();

    htmlp_form_action('PHP_SELF');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','',
                     '<b>'.$t->translate('Question').'</b> (*): ');
    $bx->box_column ('left', '70%', '',
                     html_textarea('question', 40, 4, 'virtual', 255, 
                                   $db->f('question')));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right', '30%', '',
                     '<b>'.$t->translate('Answer').'</b> (*): ');
    $bx->box_column ('left','70%','',
                     html_textarea('answer', 40, 7, 'virtual', 255, 
                                   $db->f('answer')));

    $bx->box_next_row_of_columns();

    htmlp_form_hidden('faqid', $db->f('faqid'));
    htmlp_form_hidden('modify', 2);
    $bx->box_column ('right','30%','','&nbsp;');
    $bx->box_column ('left','70%','',
                     html_form_submit($t->translate('Modify')));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}
?>
