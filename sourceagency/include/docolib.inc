<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Lutz Henckel (lutz.henckel@fokus.gmd.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Library with the functions for the page documentations
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: docolib.inc,v 1.1 2002/04/19 10:20:59 grex Exp $
#
######################################################################

/**
 * This library contains functionality needed for the page documentations
 * 
 * It handles data from the 'doco' table in the database
 * Table database contains following data:
 *
 *  docoid int(8) unsigned DEFAULT '0' NOT NULL auto_increment:
 *       Documentation ID (Primary Key)
 *  language varchar(24) NOT NULL:
 *       (Human) language in which the DOCO is written
 *  page varchar(64) NOT NULL:
 *       Page that is going to be described (without extension)
 *  header varchar(255) NOT NULL:
 *       Title
 *  doco blob NOT NULL:
 *       Description
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, translate, box
 */

/**
 * Box with the documentation insertion form
 *
 * @global  object    $sess          PHPLib session object
 * @global  object    $bx            Box object
 * @global  object    $t             Translation object
 *
 * @access  public
 */
function doco_form()
{
    global $sess, $bx, $t;

    $bx->box_begin();
    $bx->box_title($t->translate('New Page Documentation Entry'));
    $bx->box_body_begin();
    htmlp_form_action('PHP_SELF');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','',
                     '<b>'.$t->translate('Page (without extension)').'</b> (64): ');
    $bx->box_column ('left', '70%', '',
                     html_input_text('page', 40, 64, $page));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right','30%','',
                     '<b>'.$t->translate('Header').'</b> (255): ');
    $bx->box_column ('left', '70%', '',
                     html_input_text('header', 40, 255, $header));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right', '30%', '',
                     '<b>'.$t->translate('Description').'</b> (*): ');
    $bx->box_column ('left','70%','',
                     html_textarea('doco', 40, 7, 'virtual', 255, $doco));

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
 * Shows a page documentation entry
 *
 * WISH: it should be better to do not pass this as parameter
 * @param   object    $db            PHPLib database abstraction object
 *
 * @global  object    $bx            Box object
 * @global  object    $t             Translation object
 *
 * @access  public
 */
function doco_show($db)
{
    global $t, $bx;

    $bx->box_full($db->f('page').': '.$db->f('header'), $db->f('doco'));
}

/**
 * Form for modifying a page documentation entry
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
function doco_mod($db)
{
    global $sess, $t, $bx;

    $bx->box_begin();
    $bx->box_title($t->translate('Modify a Page documentation entry'));
    $bx->box_body_begin();

    htmlp_form_action('PHP_SELF');

    $bx->box_columns_begin(2);

    $bx->box_column ('right','30%','',
                     '<b>'.$t->translate('Page (without extension)').'</b> (64): ');
    $bx->box_column ('left', '70%', '',
                     html_input_text('page', 40, 64, $db->f('page')));

    $bx->box_column ('right','30%','',
                     '<b>'.$t->translate('Header').'</b> (255): ');
    $bx->box_column ('left', '70%', '',
                     html_input_text('header', 40, 64, $db->f('header')));

    $bx->box_next_row_of_columns();

    $bx->box_column ('right', '30%', '',
                     '<b>'.$t->translate('doco').'</b> (*): ');
    $bx->box_column ('left','70%','',
                     html_textarea('doco', 40, 7, 'virtual', 255, $db->f('doco')));

    $bx->box_next_row_of_columns();

    htmlp_form_hidden('docoid', $db->f('docoid'));
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
