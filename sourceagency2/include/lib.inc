<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Gregorio Robles (grex@scouts-es.org),
#                Lutz Henckel (lutz.henckel@fokus.fraunhofer.de) and
#                Gerrit Riessen (Gerrit.Riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Main Library file.
# You'll find in SourceAgency's documentation a good explanation of the
# functions that are coded in this file.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: lib.inc,v 1.1 2003/11/21 12:56:01 helix Exp $
#
######################################################################

/**
 * Returns time in timestamp format 
 *
 * @param   int       $time          TODO
 * @return  int                      Timestamp
 *
 * @access public
 */
function mktimestamp($time)
{
    $timestamp = mktime(substr($time,8,2),substr($time,10,2),
                        substr($time,12,2),substr($time,4,2),
                        substr($time,6,2),substr($time,0,4));
    return $timestamp;
}

/**
 * Returns a time string as this one: Monday, 3. October 2002, 22:02:23 GMT
 *
 * @param   int       $timestamp     Time in timestamp format
 * @return  string                   Time string
 *
 * @access public
 */
function timestr($timestamp)
{
    return strftime('%A, %e. %B %Y, %H:%M:%S %Z', $timestamp);
}

/**
 * Returns a time string as this one: 3. October 2002
 *
 * @param   int       $timestamp     Time in timestamp format
 * @return  string                   Time string
 *
 * @access public
 */
function timestr_middle($timestamp)
{
    return strftime('%e. %B %Y', $timestamp);
}

/**
 * Returns a time string as this one: Mon,3.Oct,22:02:32
 *
 * @param   int       $timestamp     Time in timestamp format
 * @return  string                   Time string
 *
 * @access public
 */
function timestr_short($timestamp)
{
    return strftime('%a,%e.%b,%H:%M:%S', $timestamp);
}

/**
 * Returns a time string as this one: 3. Oct 2002, 22:02
 *
 * @param   int       $timestamp     Time in timestamp format
 * @return  string                   Time string
 *
 * @access public
 */
function timestr_comment($timestamp)
{
    return strftime('%e. %b %Y, %H:%M', $timestamp);
}

/**
 * Returns a time string as this one: 3. Oct
 *
 * @param   int       $timestamp     Time in timestamp format
 * @return  string                   Time string
 *
 * @access public
 */
function timestr_shortest($timestamp)
{
    return strftime('%e. %b', $timestamp);
}

/**
 * Forms to select day, month and year
 *
 * @param   int       $selection_name      Variable name
 * @param   int       $selected_day        Selected day as default
 * @param   int       $selected_month      Selected month as default
 * @param   int       $selected_year       Selected year as default
 *
 * @global  int       $config_first_year   First possible year to choose 
 *                                         (from the config file)
 * @global  int       $config_last_year    Last possible year to choose
 *
 * @return  string                         several HTML selection forms
 *
 * @access public
 */
function select_date($selection_name, $selected_day='',
                     $selected_month='', $selected_year='')
{

    global $config_first_year, $config_last_year;

    $return = html_select($selection_name."_day");

    for ($i=1; $i <= 31; $i++) {
	$return .= html_select_option($i,($i == $selected_day),$i);
    }

    $return .= html_select_end();
    $return .= html_select($selection_name."_month");

    for ($i=1; $i <= 12; $i++) {
        $return .= html_select_option($i,($i == $selected_month),month($i));
    }

    $return .= html_select_end();
    $return .= html_select($selection_name."_year");

    for ($i=$config_first_year; $i <= $config_last_year; $i++) {
	$return .= html_select_option($i,($i == $selected_year),$i);
    }
    $return .= html_select_end();

    return($return);
}

/**
 * Given a number, returns the name of the month
 *
 * @param   int       $number          Month number (Gregorian calendar)
 * @global  object    $t               Translation object
 *
 * @return  string                     Month name
 *
 * @access public
 */
function month($number)
{
    global $t;

    switch ($number) {
    case 1:
 	return($t->translate('January'));
    case 2:
        return($t->translate('February'));
    case 3:
        return($t->translate('March'));
    case 4:
	return($t->translate('April'));
    case 5:
	return($t->translate('May'));
    case 6:
	return($t->translate('June'));
    case 7:
	return($t->translate('July'));
    case 8:
	return($t->translate('August'));
    case 9:
	return($t->translate('September'));
    case 10:
        return($t->translate('October'));
    case 11:
        return($t->translate('November'));
    case 12:
	return($t->translate('December'));
    }
}

/**
 * Given a date, it returns a timestamp
 * Actually it returns the given date at 12 AM
 *
 * @param   int       $day      Day
 * @param   int       $month    Month
 * @param   int       $year     Year
 *
 * @return  int                    Timestamp
 *
 * @access public
 */
function date_to_timestamp($day, $month, $year)
{
    if ($day < 10 && strlen($day) < 2 ) {
        $day = '0' . $day;
    }
    if ($month < 10 && strlen($month) < 2) {
        $month = '0' . $month;
    }
    return ($year . $month . $day . '120000');
}

/**
 * Given a timestamp, it returns a date
 * TODO: precondition ensuring that the timestamp is the right length
 *
 * @param   int       $timestamp   Timestamp
 *
 * @return  array                  items 'year', 'month' and 'day'
 *
 * @access public
 */
function timestamp_to_date($timestamp)
{
    $array['year']  = substr($timestamp,0,4);
    $array['month'] = substr($timestamp,4,2);
    $array['day']   = substr($timestamp,6,2);

    return($array);
}

/**
 * Displays the different licenses in a select form
 *
 * @param   string    $selected      License selected by default
 * @return  string                   HTML select box with all the licenses
 *                                   that exist in database
 *
 * @access public
 */
function license($selected)
{
    $db_local = new DB_SourceAgency;
    $db_local->query('SELECT * FROM licenses ORDER BY license ASC');
    if ($db_local->num_rows() == 0) {
        lib_die('Error in function license() in lib.inc: There is no license in database');
    } else {
        $return = html_select('license');
        while ($db_local->next_record()) {
            $return .= html_select_option($db_local->f('license'),
                                         !strcmp($db_local->f('license'),
                                                  $selected),
                                          $db_local->f('license'));
        }
        $return .= html_select_end();
    }
    return $return;
}
function licensep($selected)
{
    print license($selected);
}

/**
 * Merges two arrays in one
 * (not tested yet)
 *
 * @param   array      $array1        
 * @param   array      $array2
 * @return  array                     Merged array
 * @access  public
 * @see PHP4 function array_merge() should do the same
 */
function lib_merge_arrays( $array1, $array2)
{
    $merged = array();
    while (list($key, $value) = each($array1)) {
	$merged[$key] = $value;
    }
    while (list($key, $value) = each($array2)) {
	$merged[$key] = $value;
    }
    return $merged;
}

/**
 * HTML links for the lib_show_more function
 * (not tested yet)
 *
 * @param   int        $start         Gives the starting point of the iteraction
 * @param   int        $end           Gives the end point of the iteraction
 * @access  private
 * @see lib_show_more
 * FIXME: can be removed is not used 
 */
function _lib_show_more_links($start, $end)
{
#    while ($i=$start; $i<$end; $i++) {
        print html_link($link, lib_merge_arrays($query, array('current' => $i*10)), ($i-1).'1-'.($i*10)).' ';
#    }
}

/**
 * Shows items in steps of 10
 * (not tested yet)
 *
 * @param   int       $current       (first) Current item we're showing
 * @param   int       $total         Maximal number of items
 * @param   array     $query         PHPLib session array
 * @param   string    $link          Name of the page (*.php)
 *
 * @global  object    $sess          PHPLib session object
 *
 * @access public
 *
 * FIXME: this is never used, it can be removed along with _lib_show_more_links
 */
function lib_show_more($current, $total, $query = '', $link='PHP_SELF')
{
    if ($total < 50) {
        _lib_show_more_links(1, $total/10);
    } elseif ($current < 31) {
        _lib_show_more_links(1, 5);
        htmlp($link, lib_merge_arrays($query, array('current' => $total+30)), '>');
    } elseif ($current > $total - 30) {
        htmlp($link, lib_merge_arrays($query, array('current' => $total-30)), '<');
        _lib_show_more_links(($total/10)-4, $total/10);
    } else {
        htmlp($link, lib_merge_arrays($query, array('current' => floor($current/10)-3)), '<'). ' ';
	_lib_show_more_links(floor($current/10)-2, floor($current/10)+2);
        htmlp($link, lib_merge_arrays($query, array('current' => floor($current/10)+3)), '>');
    }
}

/**
 * Given a table and optionally the SQL WHERE parameter,
 * it counts the amount of items
 * (not tested yet)
 *
 * @param   string    $table       SQL table
 * @param   string    $where       SQL WHERE
 * @return  int                    Number of items (COUNT(*))
 * @access public
 */
function lib_count_total($table, $where='')
{
    if($where) {
        $where = "WHERE $where";
    }

    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT COUNT(*) FROM $table $where");
    $db_local->next_record();
    return($db_local->f('COUNT(*)'));
}

/**
 * TODO
 *
 * @param   string    $string        TODO
 * @param   int       $width         TODO
 * @param   string    $break         TODO
 *
 * @return  string
 *
 * @access public
 */
function wrap($string, $width=75, $break=' ')
{
    $out = '';
    $lin = '';
    $tok = strtok($string, $break);
    while ($tok) {
        if ((strlen($lin) + strlen($tok)) > $width) {
            $out .= $lin."\n";
            $lin = '';
        }
        if (strlen($lin) > 0) {
            $lin .= ' ';
        }
        $lin .= $tok;
        $tok = strtok (' ');
    }
    $out .= $lin;
    return $out;
}

/**
 * This function prints the long form of the project type from its 
 * shorter form (as it is stored in database)
 *
 * @param   char      $type      Short form of the project type
 * @global  object    $t         Translation Object
 *
 * @return  string               Long form of the project type
 *
 * @access public
 */
function typestr($type)
{
    global $t;

    switch($type) {
        case 'A':
            $str = $t->translate('Adaption');
            break;
        case 'E': 
            $str = $t->translate('Expansion');
            break;
        case 'C': 
            $str = $t->translate('Documentation');
            break;
        case 'D':
            $str = $t->translate('Development');
            break;
        case 'O':
        default:
            $str = $t->translate('Other');
            break;
    }
    return $str;
}

/**
 * Displays the top bar if possible or error message if not
 * 
 * @param   int       $proid        Project ID
 * @param   string    $page         Current page
 *
 * @global  object    $t            Translation Object
 *
 * @access public
 * @see _draw_top_bar()
 */
function top_bar($proid, $page)
{
    global $t;

    $db_top = new DB_SourceAgency;
    $db_top->query("SELECT * FROM description WHERE proid='$proid'");

    if ( $db_top->num_rows() == 0 ) {
        _draw_top_bar( $t->translate('Invalid Project Id'), $page );
    } else {
        $db_top->next_record();
        _draw_top_bar( $db_top->f('project_title'), $page, 
                       $proid, $db_top->f('status') );
    
    }
}

/**
 * Draws the Project Top Bar
 * this bar enables to browse through the whole project
 * It depends on the current status of the project (future steps are 
 * not available)
 *
 * @param   string    $project_title    Title of the project
 * @param   string    $page             Current page
 * @param   int       $proid            Project ID
 * @param   int       $project_status   Project Status
 *
 * @global  object    $t            Translation Object
 * @global  string    $g_step_text  TODO
 * @global  int       $g_step_count TODO
 *
 * @access private
 */
function _draw_top_bar( $project_title, $page, $proid = '', 
                       $project_status = 0 )
{
    global $t, $g_step_text, $g_step_count;
    /* WISH: to do not have to include config.inc another time */
    require('config.inc');
    $btop = new box('100%',$th_box_frame_color,1,$th_box_title_bgcolor,
                    $th_box_title_font_color,$th_box_title_align,
                    $th_box_body_bgcolor,$th_box_body_font_color,
                    $th_box_body_align);

    $btop->box_begin();
    $btop->box_body_begin();
    $btop->box_columns_begin(2);

    // 1st column (left one)
    $btop->box_column('left','','',"<font size=\"+2\"><b> "
                      . $project_title . ' - ' . $t->translate($page) 
                      . '</b></font>');

    // 2nd column (right one)
    $btop->box_column_start('right','','');

    // Actions listed and linked
    // The current page has border = 1

    if ( empty( $proid ) ) {
        htmlp_image('ic/agrey.png',abs(!strcmp('summary',$page)),
                    24,24,$t->translate('Summary'));
        htmlp_image('ic/bgrey.png',abs(!strcmp('news',$page)),
                    24,24,$t->translate('News'));
        htmlp_image('ic/cgrey.png',abs(!strcmp('comments',$page)),
                    24,24,$t->translate('General Comments'));
        htmlp_image('ic/dgrey.png',abs(!strcmp('sponsoring',$page)),
                    24,24,$t->translate('Sponsor Collaboration'));
        htmlp_image('ic/egrey.png',abs(!strcmp('history',$page)),
                    24,24,$t->translate('Project History'));
    } else {
        htmlp_link('summary.php',array('proid' => $proid),
                   html_image('ic/ax.png',abs(!strcmp('summary',$page)),
                              37,36,$t->translate('Summary')));
        htmlp_link('news.php',array('proid' => $proid),
                   html_image('ic/bx.png',abs(!strcmp('news',$page)),
                              36,36,$t->translate('News')));
        htmlp_link('comments.php',array('proid' => $proid),
                   html_image('ic/cx.png',abs(!strcmp('comments',$page)),
                              41,36,$t->translate('General Comments')));
        htmlp_link('sponsoring.php',array('proid' => $proid),
                   html_image('ic/dx.png',abs(!strcmp('sponsoring',$page)),
                              41,36,$t->translate('Sponsor Collaboration')));
        htmlp_link('history.php',array('proid' => $proid),
                   html_image('ic/ex.png',abs(!strcmp('history',$page)),
                              42,36,$t->translate('Project History')));
    }

    // Blank space that
    // limits actions from states
    htmlp_image('blank.gif',0,36,36,'');

    // Project steps
    // In grey future steps

    for( $i = 1; $i < $g_step_count + 1; $i++) {
        $alt_text = $t->translate( $g_step_text[ $i ] );
	if ($i <= $project_status ) {
            htmlp_link("step$i.php",array('proid' => $proid),
                       html_image("ic/$i.png",abs(!strcmp($i,$page)),
                                  36,36,$alt_text));
	} else {
            htmlp_image('ic/'.$i.'grey.png',abs(!strcmp($i,$page)),
                        24,24,$alt_text);
	}
    }

    $btop->box_next_row_of_columns();

    $btop->box_colspan (2,'','#000000',html_image('blank.gif',0,1,1,''));
    $btop->box_columns_end();
    $btop->box_body_end();
    $btop->box_end();

    print "<br><p>\n";
}

/**
 * The summary of the project contains the most important information 
 * of the project
 * as well as links to all possible actions
 *
 * @param   int       $proid        Project ID
 *
 * @global  object    $bx           Box Object
 * @global  object    $sess         PHPLib session object
 * @global  object    $t            Translation Object
 * @global  int       $g_step_count TODO
 *
 * @access public
 */
function summary($proid)
{
    global $bx, $sess, $t, $g_step_count;

    $db_summary = new DB_SourceAgency;
    $db_summary->query("SELECT * FROM description WHERE proid='$proid'");
    $db_summary->next_record();
    $status = $db_summary->f('status');

    $bx->box_begin();
    $bx->box_body_begin();
    $bx->box_columns_begin(2, 'top');

    $bx->box_column_start('right','65%','');

    $bx->box_begin();
    $bx->box_title($t->translate('General Actions (anytime and anywhere)'));
    $bx->box_body_begin();


    // Actions listed, linked and explained
    // News

    $db_summary->query("SELECT COUNT(*) FROM news WHERE proid='$proid'");
    $db_summary->next_record();
    htmlp_link('news.php',array('proid' => $proid),
               html_image('ic/b.png',0,60,60,
                          $t->translate('News')) . '&nbsp;'
               . $t->translate('News'));
    print ( ' ( <B>' . $db_summary->f('COUNT(*)')
            . '</B> '
            . $t->translate('news for this project')
            . " )<hr>\n" );

    // Commments
    htmlp_link('comments.php',array('proid' => $proid),
               html_image('ic/c.png',0,60,53,
                          $t->translate('Comments'))
               . '&nbsp;'.$t->translate('General Comments'));

    $db_summary->query("SELECT COUNT(*) FROM comments WHERE proid='$proid' "
                       . "AND type='general'");
    $db_summary->next_record();
    print ' ( <B>'.$db_summary->f('COUNT(*)').'</B> '
        . $t->translate('general comments');

    $db_summary->query("SELECT COUNT(*) FROM comments WHERE proid='$proid' "
                       . "AND type!='general'");
    $db_summary->next_record();
    print ( ', <B>'.$db_summary->f('COUNT(*)')
            . '</B> '
            . $t->translate('other comments on this project')
            . " )<hr>\n" );

    // Sponsor Involvement

    htmlp_link('sponsoring.php',array('proid' => $proid),
               html_image('ic/d.png',0,60,53,$t->translate('News'))
               . '&nbsp;'.$t->translate('Sponsoring Collaboration'));
    $db_summary->query("SELECT COUNT(*) FROM sponsoring WHERE proid='$proid' "
                       . "AND status='A'");
    $db_summary->next_record();
    print ( ' ( <B>' . $db_summary->f('COUNT(*)')
            . '</B> '
            . $t->translate('accepted sponsoring collaborations')
            .', ' );

    $db_summary->query("SELECT COUNT(*) FROM sponsoring WHERE proid='$proid' "
                       . "AND status='P'");
    $db_summary->next_record();
    print (' <B>'
           .$db_summary->f('COUNT(*)')
           . '</B> '
           .$t->translate('non-accepted sponsoring proposals for this project')
           .' )<BR><I>'
           .$t->translate('This is interesting if you are a sponsor '
                          .'and you are interested in joining this project')
           .".</I><hr>\n");

    // Project History
    htmlp_link('history.php',array('proid' => $proid),
               html_image('ic/e.png',0,60,51,'News').'&nbsp;'
               .$t->translate('Project History'));
    print ('<BR><i>'
           .$t->translate("You'll find here all what has been posted to "
                          .'the project (news, comments, content suggestions'
                          .', etc.) in chronological order')
           ."</i>.<br>&nbsp;\n" );

    $bx->box_body_end();

    $bx->box_title($t->translate('Step specific actions (only at its time)'));
    $bx->box_body_begin();

    // Project steps linked and explained
    // In grey future steps and unlinked
    for ($i = 1; $i < $g_step_count+1; $i++) {
	allowed_actions($status, $i, $proid);
    }
    $bx->box_body_end();
    $bx->box_end();

    $bx->box_column_finish();
    $bx->box_column_start('right','35%','');

    $bx->box_begin();
    $bx->box_title($t->translate('Step information'));
    $bx->box_body_begin();
    step_information ($proid,$status);

    $bx->box_body_end();

    $bx->box_title($t->translate('Stay informed'));
    $bx->box_body_begin();

    print ( '<br>' . html_link('monitor_edit.php',array('proid' => $proid),
                               html_image('ic/check.png',0,16,15,
                                          'ic/check.png')
                               . ' '.$t->translate('Monitor this Project'))
            . '<br>'. $t->translate('Receive an email update when something '
                                 .'happens in this project')
            . "<p>\n" );

    $bx->box_body_end();

    $bx->box_title($t->translate('Project Information'));
    $bx->box_body_begin();
    calendar_box($proid);
    $bx->box_body_end();

    $bx->box_title($t->translate('Actions by involved users'));
    $bx->box_body_begin();

    print ('<br>' . html_link('configure.php',array('proid' => $proid),
                              html_image('ic/check.png',0,16,15,'ic/check.png')
                              . ' '
                              .$t->translate('Project configuration'))
           . '<br>'
           .$t->translate('Configure the project parameters')
           .".\n&nbsp;<p>" );

    print ( html_link('views.php',array('proid' => $proid),
                      html_image('ic/check.png',0,16,15,'ic/check.png')
                      . ' '
                      .$t->translate('Information access rights'))
            . '<br>'
            . $t->translate('Configure access rights to '
                            ."project's information")
            .".\n&nbsp;<p>" );

    print ( html_link('decisions.php',array('proid' => $proid),
                      html_image('ic/check.png',0,16,15,'ic/check.png')
                      . ' '
                      .$t->translate('Decision making'))
            . '<br>'
            .$t->translate('Sponsors can make their decisions')
            .".\n&nbsp;<p><br>" );

    $bx->box_body_end();

    $bx->box_title($t->translate('Latest News'));
    $bx->box_body_begin();
    summary_news($proid);
    $bx->box_body_end();
    $bx->box_end();

    $bx->box_column_finish();
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Gives information on the step given by $step
 * (what the step is and what can be done)
 *
 * @param   int       $proid        Project ID
 * @param   int       $step         Current step
 *
 * @global  object    $bx           Box Object
 * @global  object    $t            Translation Object
 * @global  string    $g_step_explanation TODO
 *
 * @access public
 */
function step_information($proid, $step)
{
    global $bx, $t, $g_step_explanation;

    $bx->box_begin();
    $bx->box_body_begin();
    $bx->box_columns_begin(2);
  
    $bx->box_column('','','','<b>'
                    .$t->translate('Step')." $step:</b><br>\n"
                    . $t->translate( $g_step_explanation[$step] ));

    $bx->box_column('','','',html_link('step'.$step.'.php',
                                       array('proid' => $proid),
                                       html_image('ic/'.$step.'.png',0,48,48,
                                                  $step)));

    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Box with the project calendar
 * (this function is used in the summary page)
 *
 * @param   int       $proid        Project ID
 *
 * @global  object    $bx           Box Object
 * @global  object    $t            Translation Object
 *
 * @access public
 */
function calendar_box($proid)
{
    global $bx, $t;

    $db_general = new DB_SourceAgency;
    $db_general->query("SELECT * FROM description,auth_user WHERE "
                       . "proid='$proid' AND description_user = username");
    $db_general->next_record();
    
    print "<br>\n";
    
    $bx->box_columns_begin(2);
    
    $bx->box_column ('left','55%','','<b>'
                     .$t->translate('Project Owner(s)')
                     .':</b>');
    $bx->box_column ('left','45%','',
                     $db_general->f('description_user'));
    
    $bx->box_next_row_of_columns();
    $bx->box_colspan (2,'','','');
    $bx->box_next_row_of_columns();
    
    $bx->box_column ('left','','','<b>'
                     .$t->translate('Project Type')
                     .':</b>');
    $bx->box_column ('left','','',$t->translate($db_general->f('type')));
    
    $bx->box_next_row_of_columns();

    $bx->box_column ('left','','','<b>'
                     .$t->translate('Project Nature')
                     .':</b>');

    // the nature is whether the project is a developing or a sponsored
    // project. we work it out from the permissions on the project initiator
    // if they are a devel, then it must be a developing.
    $perms = $db_general->f('perms');
    $nature = ( ereg( 'devel', $perms ) ? 1 : 0 );
    $nature += ( ereg( 'sponsor', $perms ) ? 2 : 0 );

    switch ($nature) {
    case 1:
        $nature = 'Developing'; 
        break;
    case 2:
        $nature = 'Sponsoring';
        break;
    case 3:
        $nature = 'Developing and Sponsoring'; 
        break;
    default:
        $nature = 'Unknown';
    }
    $bx->box_column ('left','','', $t->translate($nature));
    
    $bx->box_next_row_of_columns();

    $bx->box_column ('left','','','<b>'
                     .$t->translate('Project Volume')
                     .':</b>');
    $bx->box_column ('left','','',$t->translate($db_general->f('volume')));
    
    $bx->box_next_row_of_columns();
    $bx->box_colspan (2,'','','');
    $bx->box_next_row_of_columns();
    
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT SUM(budget) FROM sponsoring WHERE "
                     . "proid='$proid' AND status='A'");
    $db_local->next_record();
    $budget = ( $db_local->num_rows() == 0 ? 0 : $db_local->f("SUM(budget)") );
    
    $bx->box_column ('left','','','<b>'
                     .$t->translate('Current project budget')
                     .':</b>');
    $bx->box_column ('left','','',"$budget Euro");
    
    $bx->box_next_row_of_columns();
    $bx->box_colspan (2,'','','');
    $bx->box_next_row_of_columns();
    
    $bx->box_column ('left','','','<b>'
                     .$t->translate('Creation')
                     .':</b>');
    
    $tStamp = mktimestamp($db_general->f('description_creation'));
    $bx->box_column ('left','','',timestr_middle($tStamp));
    
/* 
// TODO: Not finished yet
$bx->box_next_row_of_columns();

$bx->box_column ('left','','','<b>Propossed Calendars</b>');
$bx->box_column ('left','','','&nbsp;');
*/
    
    $bx->box_columns_end();

    print "<br>\n";
}

/**
 * Shows the header of the last three news in the summary page
 *
 * @param   int       $proid        Project ID
 *
 * @global  object    $sess         PHPLib session object
 * @global  object    $t            Translation Object
 *
 * @access public
 */
function summary_news($proid)
{
    global $sess, $t;

    $db_summ_news = new DB_SourceAgency;
    $db_summ_news->query("SELECT * FROM news,auth_user WHERE "
                         . "proid='$proid' AND user_news=username "
                         . "ORDER BY creation_news DESC LIMIT 3");
    print "<br>\n";
    
    if ($db_summ_news->num_rows() == 0) {
	print ($t->translate("There have not been posted any news by the "
           . "project owner(s)")
           .".\n" );
    } else {
        while ($db_summ_news->next_record()) {
            $db_comments_on_news = new DB_SourceAgency;
            $db_comments_on_news->query("SELECT * FROM comments WHERE "
                                        . "proid='$proid' AND type='News' "
                                        . "AND number='" 
                                        . $db_summ_news->f("creation_news") 
                                        . "'");
            
            print '&nbsp;';
            htmlp_link('news.php',array('proid' => $proid),
                       '<b>' . $db_summ_news->f('subject_news') . '</b><br>');
            
            $timestamp = mktimestamp($db_summ_news->f('creation_news'));
            
            print ( "<b><font size=\"-1\">&nbsp;"
                    . lib_nick($db_summ_news->f('user_news')) . ' - '
                    . timestr_comment($timestamp) . "</b></font><br>\n" );
            
            if ($db_comments_on_news->num_rows() > 0) {
                print ( "&nbsp;<font size=-1>[ "
                        . $db_comments_on_news->num_rows() . " ");
                htmlp_link('comments.php',
                           array('proid'  => $proid, 
                                 'type'   => 'News', 
                                 'number' => $db_summ_news->f('creation_news'),
                                 'ref'    => '0'), 
                           $t->translate('comments'));
                print( " ".$t->translate('on it')." ]</font>\n" );
            }
            print "<p>\n";
        }
    }

    print ( "<p align=right><a href=\"" . $sess->url("news_edit.php")
            . $sess->add_query(array("proid" => $proid))
            . "\"><FONT SIZE=\"-1\">["
            .$t->translate("Submit News")
            ."]</FONT></a>&nbsp;&nbsp;"
            . "<br>&nbsp;\n" );
}

/**
 * Short description of a project
 *
 * @param   string    $query        SQL query
 *
 * @global  object    $bx           Box object
 * @global  object    $t            Translation Object
 * @global  object    $sess         PHPLib session object
 *
 * @access public
 */
function lib_show_description($query) {
    global $bx, $t, $sess;
    
    $db_local = new DB_SourceAgency;
    
    $db_local->query($query);
    $db_local->next_record();
    
    if ($db_local->num_rows() > 0) {
	$bx->box_begin();
  	$bx->box_title(html_link("summary.php",
                                 array("proid" => $db_local->f("proid")),
                                 $db_local->f("project_title")) 
                       . " (".$t->translate($db_local->f("type")).")");
  	$bx->box_body_begin();
 	echo "<b>";
	lib_pnick($db_local->f("description_user"));
  	echo " - ".timestr(mktimestamp($db_local->f("description_creation")))."</b>";
  	echo ( "<p><b>".$t->translate("Description")."</b>: "
               . $db_local->f("description")."\n" );
  	echo ( "<p><b>".$t->translate("Volume")."</b>: "
               . $t->translate($db_local->f("volume"))."\n" );
  	$bx->box_body_end();
  	$bx->box_end();
    }
}

/**
 * Takes an array from config.inc and displays it as a HTML select form
 *
 * @param   int       $name         variable name
 * @param   string    $array_name   array name as it is in config.inc
 * @param   string    $selected     default value (selected one)
 *
 * @return  string                  HTML select form
 *
 * @access public
 */
function select_from_config($name, $array_name, $selected)
{
	global $t;

    $array_value='';
    
    // WISH: config.inc is included once again! This needs not to be 
    // WISH: done as it should have occured at the beginning of the page
    include('config.inc');

    // WISH: It would be nice if the parameter $array_name could 
    // WISH: attack directly the array
    // WISH: then this switch would be obsolete
    // WISH: the problem is that I have not encountered how it works!
    switch ($array_name) {
    case 'project_types':
        $array = $project_types; 
        break;
    case 'project_volume':
        $array = $project_volume; 
        break;
    case 'platform_array':
        $array = $platform_array; 
        break;
    case 'architecture_array':
        $array = $architecture_array; 
        break;
    case 'environment_array':
        $array = $environment_array; 
        break;
    case 'milestone_product_array':
        $array = $milestone_product_array; 
        break;
    default:
        lib_die('Error in function select_from_config() in '
                .'lib.inc. Specified type has no array');
    }
    
    $output = html_select($name)."\n";
    while (list(, $array_value) = each($array)) {
        $output .= html_select_option( $array_value,
                                       !strcmp($array_value,$selected), 
        $t->translate($array_value) ) . "\n";
    }
    $output .= html_select_end() . "\n";
    
    return($output);
}

/**
 * Summary for the followup page (step 5)
 * Gives in a look how the project looks like (participants, milestones, etc.)
 *
 * @param   int       $proid        Project id
 * @global  object    $bx           Box object
 * @global  object    $sess         PHPLib session object
 * @global  object    $t            Translation object
 * @global  object    $auth         PHPLib authentication object
 *
 * @access public
 */
function followup($proid)
{
    global $bx,$sess,$t,$auth;
    
    $db_followment = new DB_SourceAgency;
    
    $bx->box_begin();
    $bx->box_body_begin();
    $bx->box_columns_begin(2);
    
    $bx->box_column_start('right','65%','');
    
    $query  = ( "SELECT * FROM description,auth_user WHERE "
                . "description.proid='$proid' "
                . "AND description.description_user=auth_user.username "
                . "GROUP BY description.proid" );
    
    lib_show_description($query);
    
    if ( !function_exists( 'show_selected_content' ) ) {
        include('contentlib.inc');
    }
    show_selected_content($proid);
    
    if ( !function_exists( 'show_selected_developing' ) ) {
        include('developinglib.inc');
    }
    show_selected_developing($proid);
    
    $bx->box_column_finish();
    $bx->box_column_start('right','35%','');
    
    show_project_participants($proid,'sponsor');
    show_project_participants($proid,'developer');
    show_project_participants($proid,'referee');
    
    $bx->box_column_finish();
    $bx->box_columns_end();
    
    show_project_milestones($proid);
    
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Given a char returns the complete string for the status
 *
 * @param   char      $char         Status in a char
 * @global  object    $t            Translation object
 *
 * @return  string                  Status in a string
 *
 * @access public
 */
function show_status($char)
{
    global $t;
    switch ($char) {
    case 'P':
        return $t->translate('Proposed');
        break;
    case 'N':
        return $t->translate('Negotiating');
        break;
    case 'A':
        return $t->translate('Accepted');
        break;
    case 'R':
        return $t->translate('Rejected');
        break;
    case 'D':
        return $t->translate('Deleted');
        break;
    case 'M':
        return $t->translate('Modified');
        break;
    default:
        return $t->translate('Proposed');
        break;
    }
}

/**
 * Shows the sponsors, developers and referees that participate 
 * in a given project
 *
 * @param   int       $proid               Project ID
 * @param   string    $participant_type    'sponsor', 'developer' or 'referee'
 *
 * @global  object    $t            Translation object
 * @global  object    $bx           Box object
 * @global  object    $db           PHPLib database object
 *
 * @access public
 */
function show_project_participants($proid, $participant_type)
{
    global $t, $bx, $db;
    
    $bx->box_begin();
    $bx->box_title($t->translate($participant_type));
    $bx->box_body_begin();
    
    switch ($participant_type) {
    case 'sponsor':
        $table = 'sponsoring'; 
        break;
    case 'developer':
        $table = 'developing'; 
        break;
    case 'referee':
        $table = 'referees'; 
        break;
    default:
        lib_die('Error in function show_project_participants() in '
                .'lib.inc: No correct project participant passed');  
    }
    
    $query = ( "SELECT $participant_type FROM $table WHERE "
               . "proid='$proid' AND status='A'" );
    $db->query($query);
    while($db->next_record()) {
        echo '<p><b>'.$participant_type.':</b> ' 
            . $db->f($participant_type) . "\n";
    }
    
    echo "<br>&nbsp;\n";
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Yes/No select form
 *
 * @param    string     $name     Name of the variable
 * @param    string     $selected Default value
 *
 * @global   object     $t        Translation object
 *
 * @return   string               HTML select box with 'yes' and 'no'
 *
 * @access public
 */
function lib_select_yes_or_no($name, $selected)
{
    global $t;

    $return = html_select($name);
    $return .= html_select_option('Yes',($selected == 'Yes'),
                                  $t->translate('Yes'));

    $return .= html_select_option('No',($selected == 'No'),
                                  $t->translate('No'));
    $return .= html_select_end();

    return($return);
}

/**
 * Prints the nickname
 *
 * This function can be changed to display the user's email
 * and/or its personal homepage
 *
 * @param    string     $nick     User nickname
 * @global   object     $t        Translation object
 * 
 * @access public
 */
function lib_nick($nick)
{
    global $t;     
    return $t->translate('by').' '.$nick;
}

function lib_pnick($nick) {
    print lib_nick($nick);
}

/**
 * Shows the project milestones
 *
 * @param    int        $proid    Project ID
 * 
 * @access public
 * @see show_milestones() in milestoneslib.inc
 */
function show_project_milestones($proid)
{
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT devid FROM developing WHERE proid='$proid' "
                     ."AND status='A'");
    $db_local->next_record(); 

    if ( !function_exists( 'show_milestones' ) ) {
        include('milestoneslib.inc');
    }
    show_milestones($proid,$db_local->f('devid'),'');
}

/**
 * Displays information on the definition process for a project
 * the information to be shown depends on if the user is a developer or
 * a sponsor
 *
 * @global   object     $bx       Box object
 * @global   object     $auth     PHPLib Authentication objtect
 * @global   object     $t        Translation object
 * 
 * @access public
 */
function lib_insertion_information()
{
    global $bx, $t;

    if (is_sponsor()) {
        $bx->box_full( $t->translate('Project Definition'), 
            $t->translate('You are registrated as sponsor')
            .'<p>'
            .$t->translate('In order to define a new project,'
                .' you will have to follow these steps')
            .': <ul><li>'
            .$t->translate('Fill out the definition form')
            .'<li>'
            .$t->translate('Configure the project parameters')
            .'<li>'
            .$t->translate('Fill out a sponsoring involvement'
                .' form for your project')
            .'</ul><p>'
            .$t->translate('After that you should wait for a BerliOS editor to'
                .' review your project')
            .'.');
    }

    if (is_developer()) {
        $bx->box_full($t->translate('Project Definition'), 
            $t->translate('In order to define a new project, you will have'
                .' to follow these steps')
            .': <ul><li>'
            .$t->translate('Fill out the definition form')
            .'<li>'
            .$t->translate('Configure the project parameters')
            .'</ul><p>'
            .$t->translate('After that you should wait for a BerliOS editor'
                .' to review your project')
            .'.');
    }
}

/**
 * Displays a message when the insertion process has been 
 * successfully completed
 * with information about the ongoing reviewing process
 *
 * @global   object     $auth     PHPLib Authentication objtect
 * 
 * @access public
 */
function lib_insertion_finished()
{
    global $auth,$t;
    echo '<b>'
        .$t->translate('Congratulations')
        .'. '
        .$t->translate('You are finished with the project definition '
        . 'process')
        .'</b>.<br>'
        .$t->translate('You will now have to wait for a '
            .'SourceAgency editor to review your pending project')
        .'.<br>'
        .$t->translate('Once this is done, you will receive an e-mail')
        .'.<p>'
        .$t->translate('At your').' ' 
        .html_link('personal.php',
                    array( 'username' => $auth->auth['uname'] ),
                    'Personal Page')
        .' '
        .$t->translate('you will be now able to see your project in '
        . 'Step 0 (pending)')
        .'.<br>'
        .$t->translate('When the project is reviewed this will also change')
        .'.';
}

/**
 * This is defined so that the pages don't have to drop out of php
 * mode in order to write the html comment and also so that all pages
 * write the same thing
 *
 * @access public
 * @see end_content()
 */
function start_content() {
    print "\n\n<!-- content -->\n";
}

/**
 * See comment for start_content
 *
 * @access public
 * @see start_content()
 */
function end_content()
{
    print "\n<!-- end content -->\n\n";
}

/**
 * Sees if a value is not set or is empty
 *
 * @param  foo          $val     Value that has to be checked
 *
 * @return boolean                 
 * @access public
 * @see is_set_and_not_empty(&$val)
 */
function is_not_set_or_empty(&$val)
{
    return ( !isset( $val ) || empty( $val ) );
}

/**
 * Sees if a value has been set and is not empty
 *
 * @param  foo          $val     Value that has to be checked
 *
 * @return boolean                 
 * @access public
 * @see is_not_set_or_empty(&$val)
 */
function is_set_and_not_empty(&$val)
{
    return !is_not_set_or_empty( $val );
}

/**
 * Returns true if the project with proid is in step number $step_num
 *
 * @param   int         $proid       Project id
 * @param   int         $step_num    Required step number
 *
 * @return  boolean                 
 * @access public
 */
function lib_get_project_step( $proid ) 
{
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT status FROM description WHERE proid='$proid'");

    if ($db_local->num_rows() == 0) {
        lib_die('Error in lib.inc in function lib_in_step: no step '
            .'for the given project');
        return 0;
    } else {
        $db_local->next_record();
        return $db_local->f('status');
    }
}

/**
 * Return true if the specified project is past the specified step.
 */
function lib_past_step( $proid, $step_num ) 
{
    return ( lib_get_project_step( $proid ) > $step_num );
}

/*
 * Return true if project is in specific step.
 */
function lib_in_step($proid, $step_num)
{
    return ( lib_get_project_step( $proid ) == $step_num );
}

// REFACTOR: could be moved to html.inc ????
function lib_comment_it($proid, $type, $number, $ref, $subject, $text)
{
    print '<FONT SIZE=-1>[ ';
    htmlp_link('comments_edit.php', array('proid'   => $proid, 
                                           'type'    => $type, 
                                           'number'  => $number, 
                                           'ref'     => $ref, 
                                           'subject' => $subject), $text);
    print " ]</FONT>\n";
}

// REFACTOR: could be moved to html.inc ????
function lib_previous_comment($proid, $type, $number, $ref, $text)
{
    global $sess;
    
    print '<FONT SIZE=-1>[ ';

    htmlp_link('comments.php',array('proid'  => $proid, 
                                     'type'   => $type, 
                                     'number' => $number, 
                                     'ref'    => $ref), $text);
    print " ]</FONT>\n";
}

function lib_show_comments_on_it($proid, $type_cmt, $number, $cmt_id)
{
	global $t;

    $number_selection = ( is_set_and_not_empty( $number ) ? $number : '0' );

    $db2 = new DB_SourceAgency;
    $db2->query("SELECT * FROM comments,auth_user WHERE proid='$proid' AND "
               ."type='" . $type_cmt . "' AND number='" . $number_selection
               . "' AND ref='$cmt_id' AND user_cmt=username "
               . "ORDER BY creation_cmt ASC");

    if ($db2->num_rows() > 0) {
	echo "<ul>\n";
  	while($db2->next_record()) {
            echo '<li>'. html_link('comments.php',
                                   array('proid'  => $proid, 
                                         'type'   => $type_cmt, 
                                         'number' => $number, 
                                         'ref'    => $cmt_id),
                                   $db2->f('subject_cmt'))
                . ' '
				.$t->translate('by')
				.' <b>'
				. $db2->f('user_cmt')
				.'</b> '
				.$t->translate('on')
				.' <b>'
                . timestr_comment( mktimestamp($db2->f('creation_cmt') ))
                . "</b>\n";
            lib_show_comments_on_it($proid,$type_cmt,$number,$db2->f('id'));
  	}
  	echo "</ul>\n";
    }
    echo "<p>\n";
}

/**
 * Our 'die' function
 * Should be called when something anomalous happens
 * It mails the sys admins with the error message
 *
 * @param   string    $error_message    Error message
 * @global  object    $t                Translation object
 * @access public
 */
function lib_die($error_message)
{
    global $t;
    /* WISH: to do not have to include config.inc another time */
    require('config.inc');
    $be = new box('80%',$th_box_frame_color,$th_box_frame_width,
                  $th_box_title_bgcolor,$th_box_title_font_color,
                  $th_box_title_align,$th_box_body_bgcolor,
                  $th_box_error_font_color,$th_box_body_align);

    $be->box_full($t->translate('An error has ocurred'),
		  $error_message
		  .'<p>'
		  .$t->translate('This error is being mailed to the '
                                 .'system administrators.'));

    /* Mails the sys admins */
    if ( !function_exists( 'mailuser' ) ) {
        include('monitorlib.inc');
    }
    mailuser('admin', 'An error has ocurred', $error_message);
}
?>
