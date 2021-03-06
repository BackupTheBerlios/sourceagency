<?php
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Unit test class for the functions contained in the 
# include/personallib.php
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestPersonallib.php,v 1.32 2002/07/02 10:40:59 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

include_once( 'box.inc' );
include_once( 'lib.inc' ); // need this for show_status(..)
include_once( 'html.inc' ); // implicitly required by personallib.inc
include_once( 'security.inc' ); // required for personal_related_projects

include_once( 'personallib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( 'session.inc' );
    $GLOBALS['sess'] = new session;
    include_once( "translation.inc" );
    $GLOBALS['t'] = new translation("English");
} 

//
// REFACTOR: This entire test class is in bad need of refactoring
//
class UnitTestPersonallib
extends UnitTest
{
    // can't split the value for p_line_template, it generates a parse error
    // Arg: 1=proid,2=project title,3=status
    var $p_line_template = "Project:[^\\n]*summary[.]php3[?]proid=%s[^\\n]*%s[^\\n]* [(]step <b>%s<\\/b>[^\\n]*<br>\n";
    // Arg: 1=proid,2=project title,3=proid,4=devid
    var $p_cooperation_line_template = "Project:[^\\n]*step2[.]php3[?]proid=%s[^\\n]*%s<\/a>[^\\n]*[(]to this[^\\n]*step2[.]php3[?]proid=%s&show_proposals=yes&which_proposals=%s[^\\n]*development<\/a>[)]<br>\n";
    // Arg: 1=proid,2=project title,3=status
    var $p_referee_line_template = "Project:[^\\n]*step4[.]php3[?]proid=%s[^\\n]*%s<\/a>[^\\n]* [(]step <b>%s<\/b>[)]<br>\n";
    // Arg: 1=proid,2=project title,3=status
    var $p_consultant_line_template = "Project:[^\\n]*step1[.]php3[?]proid=%s[^\\n]*%s<\/a>[^\\n]* [(]step <b>%s<\/b>[)]<br>\n";
    // Arg: 1=project id,2=subject news,3=count star,4=reference project id(2),
    // Arg: 5=reference project title
    var $p_news_long_template = "<br><li>News: <b><a href=\"news.php3[?]proid=%s\" class=\"\">%s<\/a><\/b>[ ]*[(]<b>%s<\/b>[ ]*comments on it[)][ \\n]*<br>[^o\\n]*osted to <a href=\"summary.php3[?]proid=%s\" class=\"\">%s<\/a><br>";
    // Arg: 1=type, 2=proid, 3=type, 4=number, 5=reference, 6=subject cmt
    // Arg: 7=count star, 8=proid(from description) 9=project title
    var $p_comment_line_template = "<br><li>Comment [(]%s[)]: <b><a href=\"comments[.]php3[?]proid=%s&type=%s&number=%s&ref=%s\" class=\"\">%s<\/a><\/b>  [(]<b>%s<\/b> comments on it[)][\\n]+<br>&nbsp; &nbsp; &nbsp; posted to <a href=\"summary[.]php3[?]proid=%s\" class=\"\">%s<\/a><br>";

    function UnitTestPersonallib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset_global( 'bx' );
    }

    function _testFor_project_link( $proid, $ptitle, $status ) {
        $this->_testFor_pattern( sprintf( $this->p_line_template,
                                           $proid, $ptitle, $status ));
    }             
    function _testFor_cooperation_project_link( $proid, $ptitle, $devid ) {
        $this->_testFor_pattern(sprintf( $this->p_cooperation_line_template,
                                          $proid, $ptitle, $proid,$devid ));
    }             
    function _testFor_referee_project_link( $proid, $ptitle, $status ) {
        $this->_testFor_pattern(sprintf( $this->p_referee_line_template,
                                          $proid, $ptitle, $status ));
    }
    function _testFor_consultant_project_link( $proid,$ptitle,$status ) {
        $this->_testFor_pattern(sprintf( $this->p_consultant_line_template,
                                          $proid, $ptitle, $status ));
    }
    function _testFor_news_link($proid,$sub,$count,$refpid,$refptitle) {
        $this->_testFor_pattern(sprintf( $this->p_news_long_template,
                                          $proid, $sub, $count, $refpid,
                                          $refptitle));
    }
    function _testFor_comment_line($type, $proid, $number, $ref, 
                                   $sub_cmt, $count_star, $desc_proid, 
                                   $p_title ) {
        $this->_testFor_pattern(sprintf( $this->p_comment_line_template,
                                          $type, $proid, $type, $number,$ref, 
                                          $sub_cmt, $count_star, $desc_proid, 
                                          $p_title ));
    }

    //
    // Start of the actual test methods
    //
    function testPersonal_related_projects() {
        global $auth, $bx;
        // status can be one of: P(Proposed),N(Negotiating),A(Accepted),
        //                       R(Rejected),D(Delete),M(Modified)
        $user1 = "fubar";
        $user2 = "snafu";
        $user3 = "fritz";
        
        $table1 = "sponsoring";
        $user_type1 = "sponsor";
        $status1 = "P";

        $table2 = "developing";
        $user_type2 = "developer";
        $status2 = "D";

        $db_config = new mock_db_configure( 5 );
        $db_q = array( // Arg: 1=user name
                       0 => ("SELECT * FROM auth_user WHERE perms "
                             ."LIKE '%%sponsor%%' "
                             . "AND username='%s'"),
                       // Arg: 1=table name,2=user_type,3=user name
                       // Arg: 4=table name,5=status, 6=table name,
                       1 => ("SELECT * FROM %s,description WHERE "
                             . "%s='%s' AND %s.status='%s' AND %s.proid="
                             . "description.proid  ORDER BY creation DESC"),
                       // Arg: 1=user name
                       2 => ("SELECT * FROM auth_user WHERE perms "
                             . "LIKE '%%devel%%' "
                             . "AND username='%s'"));

        // Database instances: 
        //  fubar:
        //    0 created by personal_related_projects
        //    1 created by is_sponsor
        //  snafu:
        //    2 created by personal_related_projects
        //    3 created by is_sponsor
        //    4 created by is_developer
        $db_config->add_query( sprintf( $db_q[1], $table1, $user_type1, 
                                        $user1, $table1, $status1, 
                                        $table1), 0 );
        $db_config->add_query( sprintf( $db_q[0], $user1 ), 1 );

        $db_config->add_num_row(0, 0); // fubar not related to any projects
        $db_config->add_record(false, 0);
        $db_config->add_num_row(1, 1); // return one, fubar is sponsor

        $db_config->add_query( sprintf( $db_q[1], $table2, $user_type2, 
                                        $user2, $table2, $status2, 
                                        $table2), 2 );
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 3 ); //is_sponsor
        $db_config->add_query( sprintf( $db_q[2], $user2 ), 4 ); //is_sponsor
        $db_config->add_num_row( 0, 2 ); // snafu also has no relate projects
        $db_config->add_record( false, 2 );
        $db_config->add_num_row( 0, 3 );
        $db_config->add_num_row( 1, 4 );
        
        //
        // fubar 
        //
        $auth->set_uname( $user1 );
        $auth->set_perm( "hell yes!" );
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        personal_related_projects( $auth->auth['uname'], $status1 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 732 );
        $this->_testFor_pattern( "<b>Involved Projects [(]"
                                  .show_status($status1) ."[)]<\/b>" );
        $this->_testFor_pattern( "Not related to any project with "
                                  . show_status($status1)." status\n");
        $this->reverse_next_test();
        $this->_testFor_pattern( 'summary.php3' );

        // 
        // snafu
        //
        $auth->set_uname( $user2 );
        $auth->set_perm( "hell yes!" );
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        personal_related_projects( $auth->auth['uname'], $status2 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 730 );
        $this->_testFor_pattern( "<b>Involved Projects [(]"
                                  .show_status($status2)."[)]<\/b>" );
        $this->_testFor_pattern( "Not related to any project with "
                                  . show_status($status2)." status\n");
        $this->reverse_next_test();
        $this->_testFor_pattern( 'summary.php3' );

        // TODO: this test requires another test for fritz where projects
        // TODO: actually exist in the database

        $this->_check_db( $db_config );
    }

    function testPersonal_comments_short() {
        global $bx;

        $user1 = "fubar";
        $user2 = "snafu";
        $user3 = "fritz";

        $db_config = new mock_db_configure( 4 );
        $db_q = array( 0 => ("SELECT * FROM comments WHERE user_cmt='%s' "
                             . "AND comments.proid"),
                       1 => ("SELECT COUNT(*) FROM comments WHERE proid='%s'"
                             ." AND type='%s' AND ref='%s'"),
                       2 => ("SELECT * FROM description WHERE proid='%s'"));
        $db_config->add_query( sprintf( $db_q[0], $user1 ), 0 );
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 1 );
        $db_config->add_query( sprintf( $db_q[0], $user3 ), 4 );

        $db_config->add_num_row(0, 0); // fubar generates zero
        $db_config->add_record(false, 0);
        $db_config->add_num_row(2, 1); // snafu generates two
        $db_config->add_num_row(12, 4); // fritz generates 12

        $cols = array( 'id', 'proid', 'type', 'number','subject_cmt', 
                       'creation_cmt','ref');
        // queries for snafu
        $row1 = $this->_generate_array( $cols, 1 );
        $db_config->add_record( $row1, 1 );
        $row2 = $this->_generate_array( $cols, 2 );
        $db_config->add_record( $row2, 1 );
        $db_config->add_record( false, 1 );

        // queries for fritz
        $row7 = $this->_generate_array( $cols, 7 );
        $db_config->add_record( $row7, 4 );
        $row8 = $this->_generate_array( $cols, 8 );
        $db_config->add_record( $row8, 4 );
        $row9 = $this->_generate_array( $cols, 9 );
        $db_config->add_record( $row9, 4 );
        $row10 = $this->_generate_array( $cols, 10 );
        $db_config->add_record( $row10, 4 );
        $row11 = $this->_generate_array( $cols, 11 );
        $db_config->add_record( $row11, 4 );
        $row12 = $this->_generate_array( $cols, 12 );
        $db_config->add_record( $row12, 4 );
        $row13 = $this->_generate_array( $cols, 13 );
        $db_config->add_record( $row13, 4 );
        $row14 = $this->_generate_array( $cols, 14 );
        $db_config->add_record( $row14, 4 );
        $row15 = $this->_generate_array( $cols, 15 );
        $db_config->add_record( $row15, 4 );
        $row16 = $this->_generate_array( $cols, 16 );
        $db_config->add_record( $row16, 4 );
        $row17 = $this->_generate_array( $cols, 17 );
        $db_config->add_record( $row17, 4 );
        $row18 = $this->_generate_array( $cols, 18 );
        $db_config->add_record( $row18, 4 );

        // sub-queries for snafu
        $db_config->add_query( sprintf( $db_q[1], $row1['proid'],$row1['type'],
                                        $row1['id']), 2 );
        $row3 = array('COUNT(*)' => 'this is count start row3' );
        $db_config->add_record( $row3, 2 );

        $db_config->add_query( sprintf( $db_q[2], $row1['proid']), 2 );
        $row4 = array('proid' => 'proid_row4',                      
                      'project_title' => 'project title row4' );
        $db_config->add_record( $row4, 2 );

        $db_config->add_query( sprintf( $db_q[1], $row2['proid'],$row2['type'],
                                        $row2['id']), 3 );
        $row5 = array('COUNT(*)' => 'this is count start row5' );
        $db_config->add_record( $row5, 3 );

        $db_config->add_query( sprintf( $db_q[2], $row2['proid']), 3 );
        $row6 = array('proid' => 'proid_row6',                      
                      'project_title' => 'project title row6' );
        $db_config->add_record( $row6, 3 );
        
        //
        // fubar query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_comments_short( $user1 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 727 );
        $this->_testFor_pattern( "Last 10 Comments by " . $user1 );
        $this->_testFor_pattern( "no comments posted\n" );
        $this->reverse_next_test();
        $this->_testFor_pattern( 'See all the comments...' );

        //
        // snafu query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_comments_short( $user2 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 1302 );

        $this->_testFor_pattern( "Last 10 Comments by " . $user2 );
        $this->reverse_next_test();
        $this->_testFor_pattern( 'no comments posted' );
        $this->reverse_next_test();
        $this->_testFor_pattern( 'See all the comments...' );

        $this->_testFor_comment_line( $row1['type'], $row1['proid'],
                                      $row1['number'],
                                      $row1['ref'], $row1['subject_cmt'], 
                                      $row3['COUNT(*)'], $row4['proid'],
                                      $row4['project_title']);

        $this->_testFor_comment_line( $row2['type'], $row2['proid'],
                                      $row2['number'],
                                      $row2['ref'], $row2['subject_cmt'], 
                                      $row5['COUNT(*)'], $row6['proid'],
                                      $row6['project_title']);

        // if using a database, then ensure that it didn't fail
        $this->_check_db( $db_config );
    }

    function testPersonal_comments_long() {
        global $bx;

        $user1 = "fubar";
        $user2 = "snafu";
        
        $db_config = new mock_db_configure( 4 );
        $db_q = array( 0 => ("SELECT * FROM comments WHERE user_cmt='%s' "
                             . "AND comments.proid"),
                       1 => ("SELECT COUNT(*) FROM comments WHERE "
                             ."proid='%s' AND type='%s' AND ref='%s'"),
                       2 => ("SELECT * FROM description WHERE proid='%s'"));
        $db_config->add_query( sprintf( $db_q[0], $user1 ), 0 );
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 1 );

        $db_config->add_num_row(0, 0); // fubar generates zero
        // comments_long now does two calls to num_row
        $db_config->add_num_row(0, 0); 
        $db_config->add_record(false, 0);

        $db_config->add_num_row(2, 1); // snafu generates two
        // comments_long now makes two calls to num_row
        $db_config->add_num_row(2, 1);

        $row1 = $this->_generate_array( array( 'id', 'proid', 'type','number',
                                               'subject_cmt', 'creation_cmt',
                                               'ref'), 1 );
        $db_config->add_record( $row1, 1 );
        $row2 = $this->_generate_array( array('id','proid','type','number',
                                              'subject_cmt','creation_cmt',
                                              'ref'), 2 );
        $db_config->add_record( $row2, 1 );
        $db_config->add_record( false, 1 );

        // sub-queries
        $db_config->add_query( sprintf( $db_q[1], $row1['proid'],$row1['type'],
                                        $row1['id']), 2 );
        $row3 = array('COUNT(*)' => 'this is count start row3' );
        $db_config->add_record( $row3, 2 );

        $db_config->add_query( sprintf( $db_q[2], $row1['proid']), 2 );
        $row4 = array('proid' => 'proid_row4',                      
                      'project_title' => 'project title row4' );
        $db_config->add_record( $row4, 2 );

        $db_config->add_query( sprintf( $db_q[1], $row2['proid'],$row2['type'],
                                        $row2['id']), 3 );
        $row5 = array('COUNT(*)' => 'this is count start row5' );
        $db_config->add_record( $row5, 3 );

        $db_config->add_query( sprintf( $db_q[2], $row2['proid']), 3 );
        $row6 = array('proid' => 'proid_row6',                      
                      'project_title' => 'project title row6' );
        $db_config->add_record( $row6, 3 );
        
        //
        // fubar query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_comments_long( $user1 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 723 );
        $this->_testFor_pattern( "All Comments by " . $user1 );
        $this->_testFor_pattern( "no comments posted\n" );
        $this->reverse_next_test();
        $this->_testFor_pattern( 'See all the comments...' );

        //
        // snafu query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_comments_long( $user2 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 1298);

        $this->_testFor_pattern( "All Comments by " . $user2 );
        $this->reverse_next_test();
        $this->_testFor_pattern( 'no comments posted' );
        $this->reverse_next_test();
        $this->_testFor_pattern( 'See all the comments...' );

        $this->_testFor_comment_line( $row1['type'], $row1['proid'],
                                      $row1['number'],
                                      $row1['ref'], $row1['subject_cmt'], 
                                      $row3['COUNT(*)'], $row4['proid'],
                                      $row4['project_title']);

        $this->_testFor_comment_line( $row2['type'], $row2['proid'],
                                      $row2['number'],
                                      $row2['ref'], $row2['subject_cmt'], 
                                      $row5['COUNT(*)'], $row6['proid'],
                                      $row6['project_title']);

        // if using a database, then ensure that it didn't fail
        $this->_check_db( $db_config );
    }

    function testPersonal_news_short() {
        global $bx;

        $user1 = "fubar";
        $user2 = "snafu";
        $user3 = "fritz";

        $db_config = new mock_db_configure( 10 );
        $db_q = array( 0 => ("SELECT * FROM news WHERE user_news='%s'"),
                       1 => ("SELECT COUNT(*) FROM comments WHERE proid="
                             . "'%s' AND type='News' AND ref='%s'"),
                       2 => ("SELECT * FROM description WHERE proid='%s'"));
        $db_config->add_query( sprintf( $db_q[0], $user1 ), 0 );
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 1 );
        $db_config->add_query( sprintf( $db_q[0], $user3 ), 4 );

        // news_short calls the num_row method twice instead of once
        $db_config->add_num_row(0, 0); // fubar generates zero
        $db_config->add_num_row(0, 0); // fubar generates zero
        $db_config->add_record(false, 0 );

        $db_config->add_num_row(2, 1); // snafu generates 2 results
        $db_config->add_num_row(2, 1); // snafu generates 2 results
        
        $db_config->add_num_row(7, 4); // fritz generates 7 results
        $db_config->add_num_row(7, 4); // fritz generates 7 results
        // instance 4 does not use all of its defined records because 
        // news_short only displays the first 5 records, this means that
        // instance four has 2 too many records, ignore that error.
        $db_config->ignore_errors( MKDB_RECORD_COUNT, 4 );

        $cols=array( 'id', 'proid', 'subject_news', 'creation_news');
        $row1 = $this->_generate_array( $cols, 1 );
        $db_config->add_record( $row1, 1 );
        $row2 = $this->_generate_array( $cols, 2 );
        $db_config->add_record( $row2, 1 );
        $db_config->add_record( false, 1 );

        // the 7 records of fritz query
        $row7 = $this->_generate_array( $cols, 7 );
        $db_config->add_record( $row7, 4 );
        $row8 = $this->_generate_array( $cols, 8 );
        $db_config->add_record( $row8, 4 );
        $row9 = $this->_generate_array( $cols, 9 );
        $db_config->add_record( $row9, 4 );
        $row10 = $this->_generate_array( $cols, 10 );
        $db_config->add_record( $row10, 4 );
        $row11 = $this->_generate_array( $cols, 11 );
        $db_config->add_record( $row11, 4 );
        $row12 = $this->_generate_array( $cols, 12 );
        $db_config->add_record( $row12, 4 );
        $row13 = $this->_generate_array( $cols, 13 );
        $db_config->add_record( $row13, 4 );

        // third&fourth instances created as part of the snafu query
        $db_config->add_query(sprintf($db_q[1],$row1['proid'],$row1['id']),2);
        $db_config->add_query(sprintf($db_q[2],$row1['proid']),2);
        $db_config->add_query(sprintf($db_q[1],$row2['proid'],$row2['id']),3);
        $db_config->add_query(sprintf($db_q[2],$row2['proid']),3);
        $row3 = array( 'COUNT(*)'      => 'count star value row3');
        $db_config->add_record( $row3, 2 );
        $row4 = array( 'proid'         => 'project_id_row4',
                       'project_title' => 'project title row4');
        $db_config->add_record( $row4, 2 );
        $row5 = array( 'COUNT(*)'      => 'count star value row5');
        $db_config->add_record( $row5, 3 );
        $row6 = array( 'proid'         => 'project_id_row6',
                       'project_title' => 'project title row6');
        $db_config->add_record( $row6, 3 );
        
        // fritz sub-queries
        $db_config->add_query(sprintf($db_q[1],$row7['proid'],$row7['id']),5);
        $db_config->add_query(sprintf($db_q[2],$row7['proid']),5);
        $db_config->add_query(sprintf($db_q[1],$row8['proid'],$row8['id']),6);
        $db_config->add_query(sprintf($db_q[2],$row8['proid']),6);
        $db_config->add_query(sprintf($db_q[1],$row9['proid'],$row9['id']),7);
        $db_config->add_query(sprintf($db_q[2],$row9['proid']),7);
        $db_config->add_query(sprintf($db_q[1],$row10['proid'],
                                      $row10['id']),8);
        $db_config->add_query(sprintf($db_q[2],$row10['proid']),8);
        $db_config->add_query(sprintf($db_q[1],$row11['proid'],
                                      $row11['id']),9);
        $db_config->add_query(sprintf($db_q[2],$row11['proid']),9);
        $db_config->add_query(sprintf($db_q[1],$row12['proid'],
                                      $row12['id']),10);
        $db_config->add_query(sprintf($db_q[2],$row12['proid']),10);
        $db_config->add_query(sprintf($db_q[1],$row13['proid'],
                                      $row13['id']),11);
        $db_config->add_query(sprintf($db_q[2],$row13['proid']),11);

        $row14 = array('COUNT(*)'      => 'count star value row14');
        $db_config->add_record( $row14, 5 );
        $row15 = array('proid'         => 'project_id_row15',
                       'project_title' => 'project title row15');
        $db_config->add_record( $row15, 5 );

        $row16 = array('COUNT(*)'      => 'count star value row16');
        $db_config->add_record( $row16, 6 );
        $row17 = array('proid'         => 'project_id_row17',
                       'project_title' => 'project title row17');
        $db_config->add_record( $row17, 6 );

        $row18 = array('COUNT(*)'      => 'count star value row18');
        $db_config->add_record( $row18, 7 );
        $row19 = array('proid'         => 'project_id_row19',
                       'project_title' => 'project title row19');
        $db_config->add_record( $row19, 7 );

        $row20 = array('COUNT(*)'      => 'count star value row20');
        $db_config->add_record( $row20, 8 );
        $row21 = array('proid'         => 'project_id_row21',
                       'project_title' => 'project title row21');
        $db_config->add_record( $row21, 8 );

        $row22 = array('COUNT(*)'      => 'count star value row22');
        $db_config->add_record( $row22, 9 );
        $row23 = array('proid'         => 'project_id_row23',
                       'project_title' => 'project title row23');
        $db_config->add_record( $row23, 9 );

        $row24 = array('COUNT(*)'      => 'count star value row24');
        $db_config->add_record( $row24, 10 );
        $row25 = array('proid'         => 'project_id_row25',
                       'project_title' => 'project title row25');
        $db_config->add_record( $row25, 10 );

        $row26 = array('COUNT(*)'      => 'count star value row26');
        $db_config->add_record( $row26, 11 );
        $row27 = array('proid'         => 'project_id_row27',
                       'project_title' => 'project title row27');
        $db_config->add_record( $row27, 11 );

        //
        // fubar query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_news_short( $user1 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 718);
        $this->_testFor_pattern( "Last 5 News by " . $user1 );
        $this->_testFor_pattern( "no news posted\n" );
        $this->reverse_next_test();
        $this->_testFor_pattern( 'See all the comments...' );

        //
        // snafu query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_news_short( $user2 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 1195 );
        $this->_testFor_pattern( "Last 5 News by " . $user2 );
        $this->reverse_next_test();
        $this->_testFor_pattern( 'no news posted' );
        $this->reverse_next_test();
        $this->_testFor_pattern( 'See all the comments...' );

        $this->_testFor_news_link( $row1['proid'],$row1['subject_news'],
                                   $row3['COUNT(*)'],$row4['proid'],
                                   $row4['project_title']);
        $this->_testFor_news_link( $row2['proid'],$row2['subject_news'],
                                   $row5['COUNT(*)'],$row6['proid'],
                                   $row6['project_title']);
        //
        // fritz query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_news_short( $user3 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 3' );
        $this->_testFor_string_length( 2032 );
        $this->_testFor_pattern( "Last 5 News by " . $user3 );
        $this->reverse_next_test();
        $this->_testFor_pattern( 'no news posted' );
        $this->_testFor_pattern( "See all the comments..." );

        $this->_testFor_news_link( $row7['proid'],$row7['subject_news'],
        /* entry one */            $row14['COUNT(*)'],$row15['proid'],
                                   $row15['project_title']);
        $this->_testFor_news_link( $row8['proid'],$row8['subject_news'],
        /* entry two */            $row16['COUNT(*)'],$row17['proid'],
                                   $row17['project_title']);
        $this->_testFor_news_link( $row9['proid'],$row9['subject_news'],
        /* entry three */          $row18['COUNT(*)'],$row19['proid'],
                                   $row19['project_title']);
        $this->_testFor_news_link( $row10['proid'],
        /* entry four */           $row10['subject_news'],
                                   $row20['COUNT(*)'],$row21['proid'],
                                   $row21['project_title']);
        $this->_testFor_news_link( $row11['proid'],
        /* entry five */           $row11['subject_news'],
                                   $row22['COUNT(*)'],$row23['proid'],
                                   $row23['project_title']);

        $this->reverse_next_test();
        $this->_testFor_pattern( sprintf( $this->p_news_long_template,
                                     $row12['proid'], $row12['subject_news'], 
                                     $row26['COUNT(*)'], $row27['proid'],
                                     $row27['project_title']) );

        $this->_testFor_pattern( "See all the comments..." );

        // if using a database, then ensure that it didn't fail
        $this->_check_db( $db_config );
    }

    function testPersonal_news_long() {
        global $bx;

        $user1 = "fubar";
        $user2 = "snafu";

        // 4 instances: 1 for the fubar query, 3 for the snafu query (we
        // call personal_news_long twice). The snafu database has two
        // entries, each of which create separate database instances, hence
        // we need a total of 3 instances for the snafu query
        $db_config = new mock_db_configure( 4 );

        $db_q = array( 0 => ("SELECT * FROM news WHERE user_news='%s' "
                               ."AND news.proid"),
                       1 => ("SELECT COUNT(*) FROM comments WHERE proid='%s"
                             ."' AND type='News' AND ref='%s'"),
                       2 => "SELECT * FROM description WHERE proid='%s'");

        $db_config->add_query( sprintf( $db_q[0], $user1 ), 0);
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 1);

        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(2, 1);

        $db_config->add_record( false, 0 );

        $cols=array('id','subject_news','project_title','proid',
                    'creation_news');
        $row1 = $this->_generate_array($cols, 1 );
        $db_config->add_record( $row1, 1 );
        $row2 = $this->_generate_array($cols, 2 );
        $db_config->add_record( $row2, 1 );
        $db_config->add_record( false, 1 );

        $row3 = array( 'COUNT(*)'      => 'count star value 3' );
        $db_config->add_record( $row3, 2 );
        $row4 = array( 'project_title' => 'reference_id_instance_4',
                       'proid'         => 'project_id_instance_4');
        $db_config->add_record( $row4, 2 );

        $row5 = array( 'COUNT(*)'      => 'count star value 5' );
        $db_config->add_record( $row5, 3 );
        $row6 = array( 'project_title' => 'reference_id_instance_6',
                       'proid'         => 'project_id_instance_6');
        $db_config->add_record( $row6, 3 );

        $db_config->add_query(sprintf( $db_q[1],$row1['proid'],$row1['id']),2);
        $db_config->add_query(sprintf( $db_q[2], $row1['proid']),2);
        $db_config->add_query(sprintf( $db_q[1],$row2['proid'],$row2['id']),3);
        $db_config->add_query(sprintf( $db_q[2], $row2['proid']),3);

        //
        // fubar query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_news_long( $user1 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 715 );
        $this->_testFor_pattern( "All news by " . $user1 );
        $this->_testFor_pattern( "no news posted\n" );

        //
        // snafu query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_news_long( $user2 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 1208 );
        $this->_testFor_pattern( "All news by " . $user2 );

        $this->_testFor_news_link( $row1['proid'],$row1['subject_news'],
                                   $row3['COUNT(*)'],$row4['proid'],
                                   $row4['project_title']);
        $this->_testFor_news_link( $row2['proid'],$row2['subject_news'],
                                   $row5['COUNT(*)'],$row6['proid'],
                                   $row6['project_title']);

        // if using a database, then ensure that it didn't fail
        $this->_check_db( $db_config );
    }

    function testPersonal_consultants() {
        global $bx;

        $user1 = "fubar"; $status1 = "D";
        $user2 = "snafu"; $status2 = "A";
        $user3 = "fritz"; $status3 = "R";
        
        $db_config = new mock_db_configure( 3 );
        $db_q = ("SELECT * FROM consultants,description WHERE "
                 . "consultant='%s' AND consultants.status"
                 . "='%s' AND consultants.proid="
                 . "description.proid");
        $db_config->add_query( sprintf( $db_q, $user1, $status1 ), 0);
        $db_config->add_query( sprintf( $db_q, $user2, $status2 ), 1);
        $db_config->add_query( sprintf( $db_q, $user3, $status3 ), 2);
        
        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(1, 1);
        $db_config->add_num_row(2, 2);

        $cols = array('status','proid','project_title');
        // this is for the snafu query
        $row1 = $this->_generate_array($cols,1);
        $db_config->add_record( $row1, 1 );
        $db_config->add_record( false, 1 );
        // this is for the fritz query
        $row2 = $this->_generate_array($cols,2);
        $db_config->add_record( $row2, 2 );
        $row3 = $this->_generate_array($cols,3);
        $db_config->add_record( $row3, 2 );
        $db_config->add_record( false, 2 );
        
        //
        // fubar query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_consultants( $user1, $status1 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 716 );
        // check title string
        $this->_testFor_pattern( "Consultant [(]"
                                  .show_status( $status1 )."[)]" ); 
        $this->_testFor_pattern( "No consultant proposal with this "
                                  ."status\n");
        //
        // snafu query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_consultants( $user2, $status2 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 777 );
        // check title string
        $this->_testFor_pattern( "Consultant [(]"
                                  .show_status( $status2 )."[)]" );
        $this->_testFor_consultant_project_link($row1['proid'],
                                                $row1['project_title'],
                                                $row1['status']);
        //
        // fubar query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_consultants( $user3, $status3 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 3' );
        $this->_testFor_string_length( 877 );
        // check title string
        $this->_testFor_pattern( "Consultant [(]"
                                  .show_status( $status3 )."[)]" );
        $this->_testFor_consultant_project_link($row2['proid'],
                                                $row2['project_title'],
                                                $row2['status']);
        $this->_testFor_consultant_project_link($row3['proid'],
                                                $row3['project_title'],
                                                $row3['status']);
        // if using a database, then ensure that it didn't fail
        $this->_check_db( $db_config );
    }

    function testPersonal_referees() {
        global $bx;

        $user1 = "fubar"; $status1 = "D";
        $user2 = "snafu"; $status2 = "A";
        $user3 = "fritz"; $status3 = "R";
        
        $db_config = new mock_db_configure( 3 );

        $db_q = ("SELECT * FROM referees,description WHERE "
                 . "referee='%s' AND referees.status='%s'"
                 . " AND referees.proid=description.proid");
        $db_config->add_query( sprintf( $db_q, $user1, $status1 ), 0);
        $db_config->add_query( sprintf( $db_q, $user2, $status2 ), 1);
        $db_config->add_query( sprintf( $db_q, $user3, $status3 ), 2);

        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(1, 1);
        $db_config->add_num_row(2, 2);

        $cols = array('status', 'proid', 'project_title');
        // this is for the snafu query
        $row1 = $this->_generate_array($cols,1);
        $db_config->add_record( $row1, 1 );
        $db_config->add_record( false, 1 );
        // this is for the fritz query
        $row2 = $this->_generate_array($cols,2);
        $db_config->add_record( $row2, 2 );
        $row3 = $this->_generate_array($cols,3);
        $db_config->add_record( $row3, 2 );
        $db_config->add_record( false, 2 );

        //
        // fubar query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_referees( $user1, $status1 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 710 );
        // check title string
        $this->_testFor_pattern("Referee [(]".show_status( $status1 )."[)]"); 
        $this->_testFor_pattern("No referee proposal with this status\n");

        //
        // snafu query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_referees( $user2, $status2 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 774 );
        // check title string
        $this->_testFor_pattern( "Referee [(]".show_status( $status2 )."[)]");
        $this->_testFor_referee_project_link($row1['proid'],
                                             $row1['project_title'],
                                             $row1['status']);
        //
        // fritz query
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_referees( $user3, $status3 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 3' );
        $this->_testFor_string_length( 874 );
        // check title string
        $this->_testFor_pattern("Referee [(]".show_status( $status3 )."[)]");
        $this->_testFor_referee_project_link($row2['proid'],
                                             $row2['project_title'],
                                             $row2['status']);
        $this->_testFor_referee_project_link($row3['proid'],
                                             $row3['project_title'],
                                             $row3['status']);
        // if using a database, then ensure that it didn't fail
        $this->_check_db( $db_config );
    }

    function testPersonal_cooperation() {
        global $bx;

        $user1 = "fubar"; $status1 = "D";
        $user2 = "snafu"; $status2 = "A";
        $user3 = "fritz"; $status3 = "R";

        $db_config = new mock_db_configure( 3 );

        $db_q = ("SELECT * FROM cooperation,description,developing "
                 . "WHERE cooperation.developer='%s' AND "
                 . "cooperation.status='%s' AND "
                 . "cooperation.devid = developing.devid AND "
                 . "developing.proid=description.proid");
        
        $db_config->add_query( sprintf( $db_q, $user1, $status1 ), 0);
        $db_config->add_query( sprintf( $db_q, $user2, $status2 ), 1);
        $db_config->add_query( sprintf( $db_q, $user3, $status3 ), 2);

        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(1, 1);
        $db_config->add_num_row(2, 2);
        
        $cols = array('devid','proid','project_title');
        // this is for the snafu query
        $row1 = $this->_generate_array($cols,1);
        $db_config->add_record( $row1, 1 );
        $db_config->add_record( false, 1 );
        // this is for teh fritz query
        $row2 = $this->_generate_array($cols,2);
        $db_config->add_record( $row2, 2 );
        $row3 = $this->_generate_array($cols,3);
        $db_config->add_record( $row3, 2 );
        $db_config->add_record( false, 2 );
        
        // test one
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_cooperation( $user1, $status1 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 740 );
        // check title string
        $this->_testFor_pattern( "Developing Cooperation [(]"
                                  .show_status( $status1 )."[)]");
        $this->_testFor_pattern( "No developing cooperation proposal "
                                  ."with this status\n");

        // test two
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        personal_cooperation( $user2, $status2 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 879 );
        // check the title string
        $this->_testFor_pattern( "Developing Cooperation [(]"
                                  .show_status( $status2 )."[)]");

        $this->_testFor_cooperation_project_link($row1['proid'],
                                                 $row1['project_title'],
                                                 $row1['devid'] );
        // fritz query
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        personal_cooperation( $user3, $status3 );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 3' );
        $this->_testFor_string_length( 1069 );
        // check the title string
        $this->_testFor_pattern( "Developing Cooperation [(]"
                                 .show_status( $status3 )."[)]");

        $this->_testFor_cooperation_project_link($row2['proid'],
                                                 $row2['project_title'],
                                                 $row2['devid'] );
        $this->_testFor_cooperation_project_link($row3['proid'],
                                                 $row3['project_title'],
                                                 $row3['devid'] );
        // if using a database, then ensure that it didn't fail
        $this->_check_db( $db_config );
    }

    function testPersonal_my_projects() {
        global $bx;

        $db_config = new mock_db_configure( 3 );

        $db_q = ("SELECT * FROM description WHERE "
                 . "description_user='%s' ORDER BY "
                 . "description_creation DESC");
        
        $db_config->add_query( sprintf( $db_q, "fubar" ), 0);
        $db_config->add_query( sprintf( $db_q, "snafu" ), 1);
        $db_config->add_query( sprintf( $db_q, "fritz" ), 2);

        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(1, 1);
        $db_config->add_num_row(2, 2);
        
        $cols = array('status','proid','project_title');
        // fubar query does not call next_record()
        // this is for the snafu query
        $row1 = $this->_generate_array($cols,1);
        $db_config->add_record( $row1, 1 );
        $db_config->add_record( false, 1 );

        // this is for the fritz query
        $row2 = $this->_generate_array($cols,2);
        $db_config->add_record( $row2, 2 );
        $row3 = $this->_generate_array($cols,3);
        $db_config->add_record( $row3, 2 );
        $db_config->add_record( false, 2 );

        // test one
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_my_projects( "fubar" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 688 );
        $this->_testFor_pattern( "My Projects" ); // title
        $this->_testFor_pattern( "No personal projects\n" );
        
        // test two
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record should be called once --> num_row == 1
        personal_my_projects( "snafu" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 769 );
        $this->_testFor_pattern( "My Projects" ); // title
        $this->_testFor_project_link($row1['proid'],$row1['project_title'], 
                                     $row1['status']);

        // test three
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record should be called once --> num_row == 1
        personal_my_projects( "fritz" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 3' );
        $this->_testFor_string_length( 871);
        $this->_testFor_pattern( "My Projects" ); // title
        $this->_testFor_project_link($row2['proid'],
                                     $row2['project_title'],$row2['status']);
        $this->_testFor_project_link($row3['proid'],
                                     $row3['project_title'],$row3['status']);

        // if using a database, then ensure that it didn't fail
        $this->_check_db( $db_config );
    }

    function testPersonal_monitored_projects() {
        global $bx;

        $db_q = ("SELECT * FROM monitor,description WHERE "
                 ."monitor.proid=description.proid AND monitor"
                 .".username='%s' ORDER BY creation DESC");

        $db_config = new mock_db_configure( 3 );

        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(1, 1);
        $db_config->add_num_row(2, 2);

        $db_config->add_query( sprintf( $db_q, "fubar" ), 0);
        $db_config->add_query( sprintf( $db_q, "snafu" ), 1);
        $db_config->add_query( sprintf( $db_q, "fritz" ), 2);

        // this is for the snafu query
        $row1 = $this->_generate_array(array('status','proid',
                                             'project_title'),1);
        $db_config->add_record( $row1, 1 );
        $db_config->add_record( false, 1 );

        // this is for the fritz query
        $row2 = $this->_generate_array(array('status','proid',
                                             'project_title'),2);
        $db_config->add_record( $row2, 2 );
        $row3 = $this->_generate_array(array('status','proid',
                                             'project_title'),3);
        $db_config->add_record( $row3, 2 );
        $db_config->add_record( false, 2 );

        // test one
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record will not be called
        personal_monitored_projects( "fubar" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 696 );
        $this->_testFor_pattern( "Monitored Projects" ); // title
        $this->_testFor_pattern( "No monitored projects\n" );

        // test two
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record should be called once --> num_row == 1
        personal_monitored_projects( "snafu" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 776 );
        $this->_testFor_pattern( "Monitored Projects" ); // title
        $this->_testFor_project_link($row1['proid'],
                                     $row1['project_title'], $row1['status']);

        // test three
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        // here next_record should be called once --> num_row == 1
        personal_monitored_projects( "fritz" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 3' );
        $this->_testFor_string_length( 878 );
        $this->_testFor_pattern( "Monitored Projects" ); // title
        $this->_testFor_project_link($row2['proid'],
                                     $row2['project_title'], $row2['status']);
        $this->_testFor_project_link($row3['proid'],
                                     $row3['project_title'], $row3['status']);

        // if using a database, then ensure that it didn't fail
        $this->_check_db( $db_config );
    }

    function testPersonal_ratings_long() {
        global $bx, $t;
        $fname = 'personal_ratings_long';
        $args = $this->_generate_records( array( 'username' ), 2 );
        $q = ( "SELECT * FROM ratings,description WHERE to_whom='%s' "
               ."AND ratings.proid=description.proid");
        $d1=$this->_generate_records( array( 'rating', 'on_what', 'by_whom',
                                             'proid', 'project_title'), 10);
        $db_config = new mock_db_configure( 2 );

        // test one: no data
        $db_config->add_query( sprintf( $q, $args[0]['username']), 0 );
        $db_config->add_num_row( 0, 0 );
        $bx = $this->_create_default_box();
        $this->capture_call( $fname, 698, $args[0] );
        $this->_checkFor_a_box('All the rating on ','%s'.$args[0]['username']);
        $str = $t->translate( 'No rating yet' ) . "\n";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        // test two: one piece of data
        $db_config->add_query( sprintf( $q, $args[1]['username']), 1 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_record( $d1[0], 1 );
        $db_config->add_record( false, 1 );
        $bx = $this->_create_default_box();
        $this->capture_call( $fname, 827, $args[1] );
        $this->_checkFor_a_box('All the rating on ','%s'.$args[1]['username']);
        $str = ( $t->translate('Rated').' <b>'.$d1[0]['rating']
                 . '</b> '.$t->translate('in').' <b>'.$d1[0]['on_what']
                 . '</b> '.$t->translate('by').' <b>'.$d1[0]['by_whom']
                 . '</b> '.$t->translate('on project').' '
                 . html_link('summary.php3', array('proid' => $d1[0]['proid']),
                             $d1[0]['project_title'])
                 . "<br>\n");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        $this->_check_db( $db_config );
    }

    function testPersonal_ratings_short() {
        global $bx, $t;
        
        $fname = 'personal_ratings_short';
        $q = "SELECT rating FROM ratings WHERE to_whom='%s'";
        $db_config = new mock_db_configure( 2 );
        $args=$this->_generate_records( array( 'username' ), 10 );
        $d1=$this->_generate_records( array( 'rating' ), 10 );
        
        // test one: no records
        $db_config->add_query( sprintf( $q, $args[0]['username']), 0 );
        $db_config->add_num_row( 0, 0 );
        $bx = $this->_create_default_box();
        $this->capture_call( $fname, 676, $args[0] );
        $this->_checkFor_a_box( 'Rating' );
        $str = $t->translate( 'Not rated yet' );
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        // test two: records 
        $db_config->add_query( sprintf( $q, $args[1]['username']), 1 );
        $db_config->add_num_row( 1, 1 );
        $avg = 0;
        for ( $idx = 0; $idx < count( $d1 ); $idx++ ) {
            $d1[$idx]['rating'] = $idx;
            $db_config->add_record( $d1[$idx], 1 );
            $avg += $idx;
        }
        $avg /= count( $d1 );
        $db_config->add_record( false, 1 );
        $bx = $this->_create_default_box();
        $this->capture_call( $fname, 810, $args[1] );
        $this->_checkFor_a_box( 'Rating' );
        $str = ( $t->translate( 'Global personal rating: ' ). $avg
                  . " (rated ".count($d1)." times)<p align=right>\n");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->_testFor_html_link( 'personal_ratings.php3',
                                   array('username' => $args[1]['username']),
                                   $t->translate('See complete ratings...'));
        
        $this->_check_db( $db_config );
    }
    
}

define_test_suite( __FILE__ );

?>
