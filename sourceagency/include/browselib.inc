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
# TODO: description missing
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: browselib.inc,v 1.24 2002/09/05 08:14:06 helix Exp $
#
######################################################################

/**
 * This library contains functionality needed for browsing through
 * the various categories and types projects can be organized in
 *
 * TODO: specify where the categories and arrays can be!
 */

/**
 * Calls the function that shows the different types that can be browsed through
 *
 * @param   string    $through       type to be listed (license, status...)
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 */
function browse_through($through)
{
    global $bx, $t, $db;

    $bx->box_column_start('','50%','');
    switch ($through) {
    case 'license':
        browse_licenses();
        break;
    case 'type':
        _browse_type();
        break;
    case 'steps':
        _browse_steps();
        break;
    case 'volume':
        _browse_volume();
        break;
    case 'date':
        _browse_not_yet();
        break;
    case 'platform':
        _browse_platform();
        break;
    case 'architecture':
        _browse_architecture();
        break;
    case 'environment':
        _browse_environment();
        break;
    case 'audience':
        _browse_not_yet();
        break;
    case 'os':
        _browse_not_yet();
        break;
    case 'programming':
        _browse_not_yet();
        break;
    case 'project_name':
        _browse_project_name();
        break;
    }
    $bx->box_column_finish();
}

/**
 * Browses through the different possible licenses
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 *
 * @access  public
 */
function browse_licenses()
{
    global $bx, $db, $t;
    
    $db->query('SELECT DISTINCT * FROM licenses');
    
    $bx->box_begin();
    $bx->box_title($t->translate('Licenses'));
    $bx->box_body_begin();
    $i = 1;
    
    $bx->box_columns_begin(3);
    $bx->box_column('right','6%','','<b>'.$t->translate('No.').'</b>');
    $bx->box_column('center','20%','','<b>#&nbsp;'.$t->translate('Projects')
                    .'</b>');
    $bx->box_column('left','70%','','<b>'.$t->translate('License').'</b>');
    $bx->box_next_row_of_columns();
    
    $colors = array( 1 => 'gold', 0 => '#FFFFFF' );

    while($db->next_record()) {
        $bgcolor = $colors[ $i % 2 ];

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM developing WHERE license = '"
                    .$db->f("license")."' AND status='A'");
  	$db2->next_record();
  	$num = '['.sprintf('%03d',$db2->f('COUNT(*)')).']';
  	$bx->box_column('right','',$bgcolor,$i);
	if ($num != '[000]') {
            $bx->box_column('center','',
                            $bgcolor,
                            html_link('browse.php3', 
                                      array('through'=>'license', 
                                            'license' => 
                                            rawurlencode($db->f('license'))),
                                      $num));
        } else {
            $bx->box_column('center','',$bgcolor,$num);
        }
  	$bx->box_column('left','',$bgcolor,html_link($db->f('url'),'',
                                                         $db->f('license')));
  	$bx->box_next_row_of_columns();
  	$i++;
    }
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}


/**
 * Browses through the different possibilities of project types
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 *
 * @access  private
 */
function _browse_type()
{
    global $bx, $t;
    
    require('config.inc');
    
    $bx->box_begin();
    $bx->box_title($t->translate('Type'));
    $bx->box_body_begin();
    
    $bx->box_columns_begin(3);
    $bx->box_column('right','6%','','<b>'.$t->translate('No.').'</b>');
    $bx->box_column('center','20%','','<b>#&nbsp;'.$t->translate('Projects')
                    .'</b>');
    $bx->box_column('left','70%','','<b>'.$t->translate('Type').'</b>');
    $bx->box_next_row_of_columns();
    
    $colors = array( 0 => 'gold', 1 => '#FFFFFF' );

    for($i=0;$i < sizeof($project_types);$i++) {
        $bgcolor = $colors[ $i % 2 ];

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM description WHERE type='"
                    .$project_types[$i]."' AND status>'0'");
  	$db2->next_record();
  	$num = '['.sprintf('%03d',$db2->f('COUNT(*)')).']';
  	$bx->box_column('right','',$bgcolor,$i+1);
	if ($num != '[000]') {
            $bx->box_column('center','',$bgcolor,
                            html_link('browse.php3',
                                      array('through' => 'type', 
                                            'type' => 
                                            $t->translate($project_types[$i])),$num));
        } else {
            $bx->box_column('center','',$bgcolor,$num);
        }
  	$bx->box_column('left','',$bgcolor,$t->translate($project_types[$i]));
  	$bx->box_next_row_of_columns();
    }
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Browses through the different steps (phases / project status)
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 * @global  int       $g_step_count  TODO
 * @global  string    $g_step_text   TODO
 *
 * @access  private
 */
function _browse_steps()
{
    global $bx, $t, $g_step_count, $g_step_text;
    
    $bx->box_begin();
    $bx->box_title($t->translate('Steps'));
    $bx->box_body_begin();

    $bx->box_columns_begin(3);
    $bx->box_column('right','6%','','<b>'.$t->translate('No.').'</b>');
    $bx->box_column('center','20%','','<b>#&nbsp;'
                    .$t->translate('Projects').'</b>');
    $bx->box_column('left','70%','','<b>'.$t->translate('Step').'</b>');
    $bx->box_next_row_of_columns();

    $colors = array( 1 => 'gold', 0 => '#FFFFFF', -1 => 'gold' );

    for($i = (is_administrator() ? -1 : 1); $i <= $g_step_count; $i++) {
        $bgcolor = $colors[ $i%2 ];

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM description WHERE status = '$i'");
  	$db2->next_record();
  	$num = '['.sprintf('%03d',$db2->f('COUNT(*)')).']';
  	$bx->box_column('right','',$bgcolor,$i);
	if ($num != '[000]') {
            $bx->box_column('center','',$bgcolor,
                            html_link('browse.php3',
                                      array('through' => 'steps', 
                                            'steps' => $i),$num));
        } else {
            $bx->box_column('center','',$bgcolor,$num);
        }
  	$bx->box_column('left','',$bgcolor,$t->translate('Step'). " $i"
                        .' ('.$t->translate($g_step_text[$i]).')');
  	$bx->box_next_row_of_columns();
    }
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Browses through the different project volumes
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  private
 */
// REFACTOR: _browse_volume and _browse_types should be refactored into 
// REFACTOR: a common base function ... this can be extended for the
// REFACTOR: other _browse_* functions
function _browse_volume()
{
    global $bx, $t;

    // defines the variable project_volume
    require('config.inc');
    
    $bx->box_begin();
    $bx->box_title($t->translate('Volume'));
    $bx->box_body_begin();
    
    $bx->box_columns_begin(3);
    $bx->box_column('right','6%','','<b>'.$t->translate('No.').'</b>');
    $bx->box_column('center','20%','','<b>#&nbsp;'.$t->translate('Projects')
                    .'</b>');
    $bx->box_column('left','70%','','<b>'.$t->translate('Volume').'</b>');
    $bx->box_next_row_of_columns();

    $colors = array( 0 => 'gold', 1 => '#FFFFFF' );

    for( $i=0; $i < sizeof($project_volume); $i++) {
        $bgcolor = $colors[ $i % 2 ];

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM description WHERE volume='"
                    .$project_volume[$i]."' AND status>'0'");
  	$db2->next_record();
  	$num = '['.sprintf('%03d',$db2->f('COUNT(*)')).']';
  	$bx->box_column('right','',$bgcolor,$i+1);
	if ($num != '[000]') {
            $bx->box_column('center','',$bgcolor,
                            html_link('browse.php3',
                                      array('through' => 'volume', 
                                            'volume' => 
                                            $project_volume[$i]),$num));
        } else {
            $bx->box_column('center','',$bgcolor,$num);
        }
  	$bx->box_column('left','',$bgcolor,$project_volume[$i]);
  	$bx->box_next_row_of_columns();
    }
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Browses through the different computer architectures projects are done for
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  private
 */
function _browse_platform()
{
    global $bx, $t;
    
    // defines the variable platform_array
    require('config.inc');
    
    $bx->box_begin();
    $bx->box_title($t->translate('Platform'));
    $bx->box_body_begin();

    $bx->box_columns_begin(3);
    $bx->box_column('right','6%','','<b>'.$t->translate('No.').'</b>');
    $bx->box_column('center','20%','','<b>#&nbsp;'.$t->translate('Projects')
                    .'</b>');
    $bx->box_column('left','70%','','<b>'.$t->translate('Platform').'</b>');
    $bx->box_next_row_of_columns();

    $colors = array( 0 => 'gold', 1 => '#FFFFFF' );

    for($i=0;$i < sizeof($platform_array);$i++) {
        $bgcolor = $colors[ $i % 2 ];

  	$db2 = new DB_SourceAgency;
  	$db2->query("SELECT COUNT(*) FROM tech_content WHERE platform='"
                    .$platform_array[$i]."' AND status='A'");
  	$db2->next_record();
  	$num = '['.sprintf('%03d',$db2->f('COUNT(*)')).']';
  	$bx->box_column('right','',$bgcolor,$i+1);
	if ($num != "[000]") {
            $bx->box_column('center','',$bgcolor,
                            html_link('browse.php3',
                                      array('through' => 'platform', 
                                            'platform' => 
                                            $platform_array[$i]),$num));
        } else {
            $bx->box_column('center','',$bgcolor,$num);
        }
        $bx->box_column('left','',$bgcolor,$platform_array[$i]);
        $bx->box_next_row_of_columns();
    }
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Browses through the different computer architectures projects are done for
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  private
 */
function _browse_architecture()
{
    global $bx, $t;
    
    // defines the variable architecture_array
    require('config.inc');
    
    $bx->box_begin();
    $bx->box_title($t->translate('Architecture'));
    $bx->box_body_begin();
    
    $bx->box_columns_begin(3);
    $bx->box_column('right','6%','','<b>'.$t->translate('No.').'</b>');
    $bx->box_column('center','20%','','<b>#&nbsp;'
                    .$t->translate('Projects').'</b>');
    $bx->box_column('left','70%','','<b>'.$t->translate('Architecture')
                    .'</b>');
    $bx->box_next_row_of_columns();

    $colors = array( 0 => 'gold', 1 => '#FFFFFF' );

    for($i=0;$i < sizeof($architecture_array);$i++) {
        $bgcolor = $colors[ $i % 2 ];

        $db2 = new DB_SourceAgency;
        $db2->query("SELECT COUNT(*) FROM tech_content WHERE architecture='"
                    .$architecture_array[$i]."' AND status='A'");
        $db2->next_record();
        $num = '['.sprintf('%03d',$db2->f('COUNT(*)')).']';
        $bx->box_column('right','',$bgcolor,$i+1);
        if ($num != '[000]') {
            $bx->box_column('center','',$bgcolor,
                            html_link('browse.php3',
                                      array('through' => 'architecture', 
                                            'architecture' => 
                                            $architecture_array[$i]),$num));
        } else {
            $bx->box_column('center','',$bgcolor,$num);
        }
        $bx->box_column('left','',$bgcolor,$architecture_array[$i]);
        $bx->box_next_row_of_columns();
  }
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}

/**
 * Browses through the different computer environments projects are done for
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  private
 */
function _browse_environment()
{
    global $bx, $t;
    
    // defines the varible environment_array
    require('config.inc');
    
    $bx->box_begin();
    $bx->box_title($t->translate('Environment'));
    $bx->box_body_begin();
    
    $bx->box_columns_begin(3);
    $bx->box_column('right','6%','','<b>'.$t->translate('No.').'</b>');
    $bx->box_column('center','20%','','<b>#&nbsp;'
                    .$t->translate('Projects').'</b>');
    $bx->box_column('left','70%','','<b>'.$t->translate('Environment').'</b>');
    $bx->box_next_row_of_columns();
    
    $colors = array( 0 => 'gold', 1 => '#FFFFFF' );

    for($i=0;$i < sizeof($environment_array);$i++) {
        $bgcolor = $colors[ $i % 2 ];

        $db2 = new DB_SourceAgency;
        $db2->query("SELECT COUNT(*) FROM tech_content WHERE environment='"
                    .$environment_array[$i]."' AND status='A'");
        $db2->next_record();
        $num = '['.sprintf('%03d',$db2->f('COUNT(*)')).']';
        $bx->box_column('right','',$bgcolor,$i+1);
        if ($num != '[000]') {
            $bx->box_column('center','',$bgcolor,
                            html_link('browse.php3',
                                      array('through' => 'environment', 
                                            'environment' => 
                                            $environment_array[$i]),$num));
        } else {
            $bx->box_column('center','',$bgcolor,$num);
        }
  	$bx->box_column('left','',$bgcolor,$environment_array[$i]);
  	$bx->box_next_row_of_columns();
    }
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * There's no way to browse through this category yet
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  private
 */
function _browse_not_yet()
{
    global $bx, $t;
    
    $bx->box_begin();
    $bx->box_title($t->translate('Not yet available'));
    $bx->box_body_begin();
    
    echo "&nbsp;\n";
    
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * This function is called once a category has been selected and an item from this
 * category. The projects that have these characteristics will be displayed.
 *
 * @param   string    $by            Category to browse through (i.e. license, etc.)
 * @param   string    $what          subcategory (i.e. GPL, etc.)
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 *
 * @access  public
 */
function browse_list($by, $what)
{
    // WISH: this might cause problems but it helps when the license
    // WISH: name contains brackets -- their not decoded, i.e. come
    // WISH: out as %28 and %29
    // WISH: We'll have to look for a better solution
    $what = rawurldecode($what);
    
    switch($by) {
    case 'license':
        $query = ("SELECT * FROM description,developing WHERE "
                  ."description.proid=developing.proid AND "
                  ."license = '$what'");
        break;
    case 'type':
        $query = "SELECT * FROM description WHERE type='$what'";
        break;
    case 'steps':
        $query = "SELECT * FROM description WHERE status='$what'";
        break;
    case 'volume':
        $query = "SELECT * FROM description WHERE volume='$what'";
        break;
    case 'date':
        $query = "SELECT * FROM description WHERE creation='$what'";
        break;
    case 'platform':
        $query = ("SELECT * FROM description,tech_content WHERE "
                  ."description.proid=tech_content.proid AND "
                  ."platform='$what'");
        break;
    case 'architecture':
        $query = ("SELECT * FROM description,tech_content WHERE "
                  ."description.proid=tech_content.proid AND "
                  ."architecture='$what'");
        break;
    case 'environment':
        $query = ("SELECT * FROM description,tech_content WHERE "
                  ."description.proid=tech_content.proid AND "
                  ."environment='$what'");
        break;
    case 'audience': // FIXME: broken query
        $query = "SELECT * FROM description WHERE ='$what'";
        break;
    case 'os': // FIXME: broken query
        $query = "SELECT * FROM description WHERE ='$what'";
        break;
    case 'language': // FIXME: broken query
        $query = "SELECT * FROM description WHERE ='$what'";
        break;
    case 'project_name':
        $query = 'SELECT * FROM description WHERE status > 0'
                     ." AND  project_title LIKE '". $what . "%'";
        break;
    default:
        lib_die('Error in browselib.inc function browse_list. '.$by
                .' not specified');  
    }
    _browse_output($query);
}

/**
 * Shows the contents for the browse_list() function given its SQL query
 *
 * @param   string    $query         SQL query 
 *
 * @global  object    $db            PHPLib database object
 * @global  object    $bx            Box object
 *
 * @access  private
 */
function _browse_output($query)
{
    global $db;
    
    $db->query($query);
    while($db->next_record()) {
        lib_show_description("SELECT * FROM description WHERE proid='"
                             .$db->f("proid")."'");
    }
}

/**
 * Browses through the projects by its name
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 *
 * @access  private
 */
function _browse_project_name()
{
    global $bx, $t;

    $alphabet=array ('A','B','C','D','E','F','G','H','I','J','K','L',
                     'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    
    $bx->box_begin();
    $bx->box_title($t->translate('Projects ordered alphabetically'));
    $bx->box_body_begin();
    $i = 1;
    
    $bx->box_columns_begin(3);
    $bx->box_column('right','6%','','<b>'.$t->translate('No.').'</b>');
    $bx->box_column('center','20%','','<b>#&nbsp;'.$t->translate('Projects')
                    .'</b>');
    $bx->box_column('left','70%','',
                    '<b>'.$t->translate('Project Name').'</b>');
    $bx->box_next_row_of_columns();
    
    $colors = array( 1 => 'gold', 0 => '#FFFFFF' );

    while (list(, $ltr) = each($alphabet)) {
        $bgcolor = $colors[ $i % 2 ];

  	$db2 = new DB_SourceAgency;
  	$db2->query('SELECT COUNT(*) FROM description WHERE status > 0'
                     ." AND  project_title LIKE '" . $ltr . "%'");
  	$db2->next_record();
  	$num = '['.sprintf('%03d',$db2->f('COUNT(*)')).']';
  	$bx->box_column('right','',$bgcolor,$i);
	if ($num != '[000]') {
            $bx->box_column('center','',
                            $bgcolor,
                            html_link('browse.php3', 
                                      array('through'=>'project_name', 
                                            'project_name' => $ltr),
                                      $num));
        } else {
            $bx->box_column('center','', $bgcolor, $num);
        }
  	$bx->box_column('left','', $bgcolor, $ltr );
  	$bx->box_next_row_of_columns();
  	$i++;
    }
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}
?>