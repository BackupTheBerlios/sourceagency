<?php
// TestPersonallib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestPersonallib.php,v 1.10 2001/10/26 13:49:16 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    ini_set('include_path', ini_get('include_path') . ':../../include' );
    include_once( 'box.inc' );
    $bx = new box;
    include_once( 'session.inc' );
    $sess = new session;
} 

class db_sourceagency 
extends mock_database 
{
    function db_sourceagency() {
        // call the constructor of our parent
        $this->mock_database();
    }
}

include_once( 'lib.inc' ); // need this for show_status(..)
include_once( 'html.inc' ); // implicitly required by personallib.inc
include_once( 'personallib.inc' );
//
// REFACTOR: This entire test class is in bad need of refactoring
//
class UnitTestPersonallib
extends TestCase
{
    // can't split the value for p_line_template, it generates a parse error
    // Arg: 1=proid,2=project title,3=status
    var $p_line_template = "Project:[^\\n]*summary[.]php3[?]proid=%s[^\\n]*%s[^\\n]*step <b>%s<\\/b>[^\\n]*<br>";
    // Arg: 1=proid,2=project title,3=proid,4=devid
    var $p_cooperation_line_template = "Project:[^\\n]*step2[.]php3[?]proid=%s[^\\n]*%s<\/a>[^\\n]*[(]to this[^\\n]*step2[.]php3[?]proid=%s&show_proposals=yes&which_proposals=%s[^\\n]*development<\\/a>[)]<br>";
    // Arg: 1=proid,2=project title,3=status
    var $p_referee_line_template = "Project:[^\\n]*step4[.]php3[?]proid=%s[^\\n]*%s<\/a>[^\\n]*[(]step <b>%s<\/b>[)]<br>";
    // Arg: 1=proid,2=project title,3=status
    var $p_consultant_line_template = "Project:[^\\n]*step1[.]php3[?]proid=%s[^\\n]*%s<\/a>[^\\n]*[(]step <b>%s<\/b>[)]<br>";
    // Arg: 1=project id,2=subject news,3=count star,4=reference project id(2),
    // Arg: 5=reference project title
    var $p_news_long_template = "<br><li>News: <b><a href=\"news.php3[?]proid=%s\">%s<\/a><\/b>[^(]*[(]<b>%s<\/b>[ \\n]*comments on it[)][^<]*<br>[^o\\n]*osted to <a href=\"summary.php3[?]proid=%s\">%s<\/a><br>";
    // Arg: 1=type, 2=proid, 3=type, 4=number, 5=reference, 6=subject cmt
    // Arg: 7=count star, 8=proid(from description) 9=project title
      var $p_comment_line_template = "<br><li>Comment [(]%s[)]: <b><a href=\"comments[.]php3[?]proid=%s&type=%s&number=%s&ref=%s\">%s<\/a><\/b>  [(]<b>%s<\/b> comments on it[)][\\n]+<br>&nbsp; &nbsp; &nbsp; posted to <a href=\"summary[.]php3[?]proid=%s\">%s<\/a><br>";
//      var $p_comment_line_template = "<br><li>Comment [(]%s[)]: <b><a href=\"comments[.]php3[?]proid=%s&type=%s";
    function UnitTestPersonallib( $name ) {
        $this->TestCase( $name );
    }
    
    function setup() {
        // Called before each test method.
        // if using the capturing routines then ensure that it's reset,
        // it uses global variables
        capture_reset_text();
    }

    // could actually be defined in phpunit ....
    function assertNotRegexp( $regexp, $actual, $message=false ) {
        if ( preg_match( $regexp, $actual ) ) {
            $this->failNotEquals( $regexp, $actual, "*NOT* pattern",$message );
        }
    }

    function _testFor_length( $length ) {
        $this->assertEquals($length,capture_text_length(),"Length mismatch");
    }
    function _testFor_line( $text, $line ) {
        $this->_testFor_pattern( $text, $line . "\n" );
    }
    function _testFor_pattern( $text, $pattern ) {
        $this->assertRegexp("/" . $pattern . "/", $text, "pattern not found");
    }
    function _testFor_project_link( $text, $proid, $ptitle, $status ) {
        $this->_testFor_line( $text, sprintf( $this->p_line_template,
                                              $proid, $ptitle, $status ));
    }             
    function _testFor_cooperation_project_link( $text, $proid, $ptitle, 
                                                $devid ) {
        $this->_testFor_line($text,sprintf( $this->p_cooperation_line_template,
                                            $proid, $ptitle, $proid,$devid ));
    }             
    function _testFor_referee_project_link( $text, $proid, $ptitle, $status ) {
        $this->_testFor_line($text,sprintf( $this->p_referee_line_template,
                                            $proid, $ptitle, $status ));
    }
    function _testFor_consultant_project_link( $text,$proid,$ptitle,$status ) {
        $this->_testFor_line($text,sprintf( $this->p_consultant_line_template,
                                            $proid, $ptitle, $status ));
    }
    function _testFor_news_link($text,$proid,$sub,$count,$refpid,$refptitle) {
        $this->_testFor_pattern($text,sprintf( $this->p_news_long_template,
                                            $proid, $sub, $count, $refpid,
                                            $refptitle));
    }
    function _testFor_comment_line($text, $type, $proid, $number, $ref, 
                                   $sub_cmt, $count_star, $desc_proid, 
                                   $p_title ) {
        $this->_testFor_pattern($text, sprintf( $this->p_comment_line_template,
                                                $type, $proid, $type, $number,
                                                $ref, $sub_cmt, $count_star, 
                                                $desc_proid, $p_title ));
    }

    //
    // Start of the actual test methods
    //
    function testPersonal_comments_short() {
        $user1 = "fubar";
        $user2 = "snafu";

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 4 );
        $db_q = array( 0 => ("SELECT * FROM comments WHERE user_cmt='%s' "
                             . "AND comments.proid"),
                       1 => ("SELECT COUNT(*) FROM comments WHERE proid='%s'"
                             ." AND type='%s' AND ref='%s'"),
                       2 => ("SELECT * FROM description WHERE proid='%s'"));
        $db_config->add_query( sprintf( $db_q[0], $user1 ), 0 );
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 1 );
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 4 );

        $db_config->add_num_row(0, 0); // fubar generates zero
        $db_config->add_record(false, 0);
        $db_config->add_num_row(2, 1); // snafu generates two
        $db_config->add_num_row(12, 4); // snafu generates two

        $row1 = array( 'id'            => 'id_1',
                       'proid'         => 'proid_1',
                       'type'          => 'type_1',
                       'number'        => 'number_1',
                       'subject_cmt'   => 'subject_cmt_1',
                       'creation_cmt'  => 'creation_cmt_1',
                       'ref'           => 'reference_id_1');
        $db_config->add_record( $row1, 1 );
        $row2 = array( 'id'            => 'id_2',
                       'proid'         => 'proid_2',
                       'type'          => 'type_2',
                       'number'        => 'number_2',
                       'subject_cmt'   => 'subject_cmt_2',
                       'creation_cmt'  => 'creation_cmt_2',
                       'ref'           => 'reference_id_2');
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
        capture_start();
        // here next_record will not be called
        personal_comments_short( $user1 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 563 );
        $this->_testFor_pattern( $text, "Last 10 Comments by " . $user1 );
        $this->_testFor_line( $text, "no comments posted" );
        $this->assertNotRegexp( "/See all the comments.../", $text, 
                                "[User: ".$user1."] should not have link");

        //
        // snafu query
        //
        capture_reset_text();
        capture_start();
        // here next_record will not be called
        personal_comments_short( $user2 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 1120 );

        $this->_testFor_pattern( $text, "Last 10 Comments by " . $user2 );
        $this->assertNotRegexp( "/no comments posted/", $text );
        $this->assertNotRegexp( "/See all the comments.../", $text, 
                                "[User: ".$user1."] should not have link");

        $this->_testFor_comment_line( $text, $row1['type'], $row1['proid'],
                                      $row1['number'],
                                      $row1['ref'], $row1['subject_cmt'], 
                                      $row3['COUNT(*)'], $row4['proid'],
                                      $row4['project_title']);

        $this->_testFor_comment_line( $text, $row2['type'], $row2['proid'],
                                      $row2['number'],
                                      $row2['ref'], $row2['subject_cmt'], 
                                      $row5['COUNT(*)'], $row6['proid'],
                                      $row6['project_title']);

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testPersonal_comments_long() {
        $user1 = "fubar";
        $user2 = "snafu";
        
        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 4 );
        $db_q = array( 0 => ("SELECT * FROM comments WHERE user_cmt='%s' "
                             . "AND comments.proid"),
                       1 => ("SELECT COUNT(*) FROM comments WHERE "
                             ."proid='%s' AND type='%s' AND ref='%s'"),
                       2 => ("SELECT * FROM description WHERE proid='%s'"));
        $db_config->add_query( sprintf( $db_q[0], $user1 ), 0 );
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 1 );

        $db_config->add_num_row(0, 0); // fubar generates zero
        $db_config->add_record(false, 0);

        $db_config->add_num_row(2, 1); // snafu generates two

        $row1 = array( 'id'            => 'id_1',
                       'proid'         => 'proid_1',
                       'type'          => 'type_1',
                       'number'        => 'number_1',
                       'subject_cmt'   => 'subject_cmt_1',
                       'creation_cmt'  => 'creation_cmt_1',
                       'ref'           => 'reference_id_1');
        $db_config->add_record( $row1, 1 );
        $row2 = array( 'id'            => 'id_2',
                       'proid'         => 'proid_2',
                       'type'          => 'type_2',
                       'number'        => 'number_2',
                       'subject_cmt'   => 'subject_cmt_2',
                       'creation_cmt'  => 'creation_cmt_2',
                       'ref'           => 'reference_id_2');
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
        capture_start();
        // here next_record will not be called
        personal_comments_long( $user1 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 559 );
        $this->_testFor_pattern( $text, "All Comments by " . $user1 );
        $this->_testFor_line( $text, "no comments posted" );
        $this->assertNotRegexp( "/See all the comments.../", $text, 
                                "[User: ".$user1."] should not have link");

        //
        // snafu query
        //
        capture_reset_text();
        capture_start();
        // here next_record will not be called
        personal_comments_long( $user2 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 1116 );

        $this->_testFor_pattern( $text, "All Comments by " . $user2 );
        $this->assertNotRegexp( "/no comments posted/", $text );
        $this->assertNotRegexp( "/See all the comments.../", $text, 
                                "[User: ".$user1."] should not have link");

        $this->_testFor_comment_line( $text, $row1['type'], $row1['proid'],
                                      $row1['number'],
                                      $row1['ref'], $row1['subject_cmt'], 
                                      $row3['COUNT(*)'], $row4['proid'],
                                      $row4['project_title']);

        $this->_testFor_comment_line( $text, $row2['type'], $row2['proid'],
                                      $row2['number'],
                                      $row2['ref'], $row2['subject_cmt'], 
                                      $row5['COUNT(*)'], $row6['proid'],
                                      $row6['project_title']);

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testPersonal_news_short() {
        $user1 = "fubar";
        $user2 = "snafu";
        $user3 = "fritz";

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 11 );
        $db_q = array( 0 => ("SELECT * FROM news WHERE user_news='%s'"),
                       1 => ("SELECT COUNT(*) FROM comments WHERE proid="
                             . "'%s' AND type='News' AND ref='%s'"),
                       2 => ("SELECT * FROM description WHERE proid='%s'"));
        $db_config->add_query( sprintf( $db_q[0], $user1 ), 0 );
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 1 );
        $db_config->add_query( sprintf( $db_q[0], $user3 ), 4 );

        $db_config->add_num_row(0, 0); // fubar generates zero
        $db_config->add_record(false, 0 );

        $db_config->add_num_row(2, 1); // snafu generates 2 results
        $db_config->add_num_row(7, 4); // fritz generates 7 results

        $row1 = array( 'id'            => 'reference_id',
                       'proid'         => 'project_id',
                       'subject_news'  => 'subject news',
                       'creation_news' => 'creation_news');
        $db_config->add_record( $row1, 1 );
        $row2 = array( 'id'            => 'reference_id_num_22',
                       'proid'         => 'project_id_333',
                       'subject_news'  => 'subject news 4444',
                       'creation_news' => 'creation_news 55555');
        $db_config->add_record( $row2, 1 );
        $db_config->add_record( false, 1 );

        // the 7 records of fritz query
        $row7 = array( 'id'            => 'reference_id_num_4',
                       'proid'         => 'project_id_4',
                       'subject_news'  => 'subject news 4',
                       'creation_news' => 'creation_news 4');
        $db_config->add_record( $row7, 4 );
        $row8 = array( 'id'            => 'reference_id_num_5',
                       'proid'         => 'project_id_5',
                       'subject_news'  => 'subject news 5',
                       'creation_news' => 'creation_news 5');
        $db_config->add_record( $row8, 4 );
        $row9 = array( 'id'            => 'reference_id_num_6',
                       'proid'         => 'project_id_6',
                       'subject_news'  => 'subject news 6',
                       'creation_news' => 'creation_news 6');
        $db_config->add_record( $row9, 4 );
        $row10 = array('id'            => 'reference_id_num_7',
                       'proid'         => 'project_id_7',
                       'subject_news'  => 'subject news 7',
                       'creation_news' => 'creation_news 7');
        $db_config->add_record( $row10, 4 );
        $row11 = array('id'            => 'reference_id_num_8',
                       'proid'         => 'project_id_8',
                       'subject_news'  => 'subject news 8',
                       'creation_news' => 'creation_news 8');
        $db_config->add_record( $row11, 4 );
        $row12 = array('id'            => 'reference_id_num_9',
                       'proid'         => 'project_id_9',
                       'subject_news'  => 'subject news 9',
                       'creation_news' => 'creation_news 9');
        $db_config->add_record( $row12, 4 );
        $row13 = array('id'            => 'reference_id_num_10',
                       'proid'         => 'project_id_10',
                       'subject_news'  => 'subject news 10',
                       'creation_news' => 'creation_news 10');
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
        capture_start();
        // here next_record will not be called
        personal_news_short( $user1 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 554 );
        $this->_testFor_pattern( $text, "Last 5 News by " . $user1 );
        $this->_testFor_line( $text, "no news posted" );
        $this->assertNotRegexp( "/See all the comments.../", $text, 
                                "[User: ".$user1."] should not have link");

        //
        // snafu query
        //
        capture_reset_text();
        capture_start();
        // here next_record will not be called
        personal_news_short( $user2 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 1006 );
        $this->_testFor_pattern( $text, "Last 5 News by " . $user2 );
        $this->assertNotRegexp( "/no news posted/", $text, 
                                "[User: ".$user2."] has news posted");
        $this->assertNotRegexp( "/See all the comments.../", $text, 
                                "[User: ".$user2."] should not have link");

        $this->_testFor_news_link( $text, $row1['proid'],$row1['subject_news'],
                                   $row3['COUNT(*)'],$row4['proid'],
                                   $row4['project_title']);
        $this->_testFor_news_link( $text, $row2['proid'],$row2['subject_news'],
                                   $row5['COUNT(*)'],$row6['proid'],
                                   $row6['project_title']);
        //
        // fritz query
        //
        capture_reset_text();
        capture_start();
        // here next_record will not be called
        personal_news_short( $user3 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 2027 );
        $this->_testFor_pattern( $text, "Last 5 News by " . $user3 );
        $this->assertNotRegexp( "/no news posted/", $text, 
                                "[User: ".$user3."] has news posted");
        $this->_testFor_pattern( $text, "See all the comments..." );

        $this->_testFor_news_link( $text, $row7['proid'],$row7['subject_news'],
        /* entry one */            $row14['COUNT(*)'],$row15['proid'],
                                   $row15['project_title']);
        $this->_testFor_news_link( $text, $row8['proid'],$row8['subject_news'],
        /* entry two */            $row16['COUNT(*)'],$row17['proid'],
                                   $row17['project_title']);
        $this->_testFor_news_link( $text, $row9['proid'],$row9['subject_news'],
        /* entry three */          $row18['COUNT(*)'],$row19['proid'],
                                   $row19['project_title']);
        $this->_testFor_news_link( $text, $row10['proid'],
        /* entry four */           $row10['subject_news'],
                                   $row20['COUNT(*)'],$row21['proid'],
                                   $row21['project_title']);
        $this->_testFor_news_link( $text, $row11['proid'],
        /* entry five */           $row11['subject_news'],
                                   $row22['COUNT(*)'],$row23['proid'],
                                   $row23['project_title']);

        // FIXME: either the title is wrong or the logic, but i think the 
        // FIXME: following entry should not appear, it's the sixth from 5! 
        $this->_testFor_news_link( $text, $row12['proid'],
                                   $row12['subject_news'],
                                   $row24['COUNT(*)'],$row25['proid'],
                                   $row25['project_title']);

        $this->assertNotRegexp("/" . sprintf( $this->p_news_long_template,
                                   $row12['proid'], $row12['subject_news'], 
                                   $row26['COUNT(*)'], $row27['proid'],
                                   $row27['project_title'])."/", $text );

        $this->_testFor_pattern( $text, "See all the comments..." );

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testPersonal_news_long() {
        $user1 = "fubar";
        $user2 = "snafu";

        $db_config = new mock_db_configure;
        // 4 instances: 1 for the fubar query, 3 for the snafu query (we
        // call personal_news_long twice). The snafu database has two
        // entries, each of which create separate database instances, hence
        // we need a total of 3 instances for the snafu query
        $db_config->set_nr_instance_expected( 4 );

        $db_q = array( 0 => ("SELECT * FROM comments WHERE user_cmt='%s' "
                               ."AND comments.proid"),
                       1 => ("SELECT COUNT(*) FROM comments WHERE proid='%s"
                             ."' AND type='News' AND ref='%s'"),
                       2 => "SELECT * FROM description WHERE proid='%s'");

        $db_config->add_query( sprintf( $db_q[0], $user1 ), 0);
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 1);

        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(2, 1);

        $db_config->add_record( false, 0 );

        $row1 = array( 'id'            => 'reference_id',
                       'proid'         => 'project_id',
                       'project_title' => 'project_title',
                       'subject_news'  => 'subject news',
                       'creation_news' => 'creation_news');
        $db_config->add_record( $row1, 1 );
        $row2 = array( 'id'            => 'reference_id_num_2',
                       'proid'         => 'project_id_222',
                       'project_title' => 'project_title @@3',
                       'subject_news'  => 'subject news 444',
                       'creation_news' => 'creation_news %%%5');
        $db_config->add_record( $row2, 1 );
        $db_config->add_record( false, 1 );

        $row3 = array( 'COUNT(*)'      => 'count star value 2' );
        $db_config->add_record( $row3, 2 );
        $row4 = array( 'project_title' => 'reference_id_instance_2',
                       'proid'         => 'project_id_instance_2');
        $db_config->add_record( $row4, 2 );

        $row5 = array( 'COUNT(*)'      => 'count star value 3' );
        $db_config->add_record( $row5, 3 );
        $row6 = array( 'project_title' => 'reference_id_instance_3',
                       'proid'         => 'project_id_instance_3');
        $db_config->add_record( $row6, 3 );

        $db_config->add_query(sprintf( $db_q[1],$row1['proid'],$row1['id']),2);
        $db_config->add_query(sprintf( $db_q[2], $row1['proid']),2);
        $db_config->add_query(sprintf( $db_q[1],$row2['proid'],$row2['id']),3);
        $db_config->add_query(sprintf( $db_q[2], $row2['proid']),3);

        //
        // fubar query
        //
        capture_start();
        // here next_record will not be called
        personal_news_long( $user1 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 559 );
        $this->_testFor_pattern( $text, "All Comments by " . $user1 );
        $this->_testFor_line( $text, "no comments posted" );

        //
        // snafu query
        //
        capture_reset_text();
        capture_start();
        // here next_record will not be called
        personal_news_long( $user2 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 1022 );
        $this->_testFor_pattern( $text, "All Comments by " . $user2 );

        $this->_testFor_news_link( $text, $row1['proid'],$row1['subject_news'],
                                   $row3['COUNT(*)'],$row4['proid'],
                                   $row4['project_title']);
        $this->_testFor_news_link( $text, $row2['proid'],$row2['subject_news'],
                                   $row5['COUNT(*)'],$row6['proid'],
                                   $row6['project_title']);

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testPersonal_consultants() {
        $user1 = "fubar"; $status1 = "D";
        $user2 = "snafu"; $status2 = "A";
        $user3 = "fritz"; $status3 = "R";
        
        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 3 );
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

        // this is for the snafu query
        $row1 = array( 'status'        => 'project_status',
                       'proid'         => 'project_id',
                       'project_title' => 'project_title' );
        $db_config->add_record( $row1, 1 );
        $db_config->add_record( false, 1 );
        // this is for the fritz query
        $row2 = array( 'status'        => 'project_status2',
                       'proid'         => 'project_id2',
                       'project_title' => 'project_title2' );
        $db_config->add_record( $row2, 2 );
        $row3 = array( 'status'        => 'project_status3',
                       'proid'         => 'project_id3',
                       'project_title' => 'project_title3' );
        $db_config->add_record( $row3, 2 );
        $db_config->add_record( false, 2 );
        
        //
        // fubar query
        //
        capture_start();
        // here next_record will not be called
        personal_consultants( $user1, $status1 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 552 );
        // check title string
        $this->_testFor_pattern( $text, ("Consultant [(]"
                                         .show_status( $status1 )."[)]")); 
        $this->_testFor_line( $text, ("No consultant proposal with this "
                                      ."status"));
        //
        // snafu query
        //
        capture_reset_text();
        capture_start();
        // here next_record will not be called
        personal_consultants( $user2, $status2 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 611 );
        // check title string
        $this->_testFor_pattern( $text, ("Consultant [(]"
                                         .show_status( $status2 )."[)]")); 
        $this->_testFor_consultant_project_link($text,$row1['proid'],
                                                      $row1['project_title'],
                                                      $row1['status']);
        //
        // fubar query
        //
        capture_reset_text();
        capture_start();
        // here next_record will not be called
        personal_consultants( $user3, $status3 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 715 );
        // check title string
        $this->_testFor_pattern( $text, ("Consultant [(]"
                                         .show_status( $status3 )."[)]")); 
        $this->_testFor_consultant_project_link($text,$row2['proid'],
                                                      $row2['project_title'],
                                                      $row2['status']);
        $this->_testFor_consultant_project_link($text,$row3['proid'],
                                                      $row3['project_title'],
                                                      $row3['status']);
        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testPersonal_referees() {
        $user1 = "fubar"; $status1 = "D";
        $user2 = "snafu"; $status2 = "A";
        $user3 = "fritz"; $status3 = "R";
        
        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 3 );

        $db_q = ("SELECT * FROM referees,description WHERE "
                 . "referee='%s' AND referees.status='%s'"
                 . " AND referees.proid=description.proid");
        $db_config->add_query( sprintf( $db_q, $user1, $status1 ), 0);
        $db_config->add_query( sprintf( $db_q, $user2, $status2 ), 1);
        $db_config->add_query( sprintf( $db_q, $user3, $status3 ), 2);

        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(1, 1);
        $db_config->add_num_row(2, 2);

        // this is for the snafu query
        $row1 = array( 'status'        => 'project_status',
                       'proid'         => 'project_id',
                       'project_title' => 'project_title' );
        $db_config->add_record( $row1, 1 );
        $db_config->add_record( false, 1 );
        // this is for the fritz query
        $row2 = array( 'status'        => 'project_status2',
                       'proid'         => 'project_id2',
                       'project_title' => 'project_title2' );
        $db_config->add_record( $row2, 2 );
        $row3 = array( 'status'        => 'project_status3',
                       'proid'         => 'project_id3',
                       'project_title' => 'project_title3' );
        $db_config->add_record( $row3, 2 );
        $db_config->add_record( false, 2 );

        //
        // fubar query
        //
        capture_start();
        // here next_record will not be called
        personal_referees( $user1, $status1 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 546 );
        // check title string
        $this->_testFor_pattern( $text, ("Referee [(]"
                                         .show_status( $status1 )."[)]")); 
        $this->_testFor_line( $text, ("No referee proposal with this status"));

        //
        // snafu query
        //
        capture_reset_text();
        capture_start();
        // here next_record will not be called
        personal_referees( $user2, $status2 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 608 );
        // check title string
        $this->_testFor_pattern( $text, ("Referee [(]"
                                         .show_status( $status2 )."[)]")); 
        $this->_testFor_referee_project_link($text,$row1['proid'],
                                                   $row1['project_title'],
                                                   $row1['status']);
        //
        // fritz query
        //
        capture_reset_text();
        capture_start();
        // here next_record will not be called
        personal_referees( $user3, $status3 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 712 );
        // check title string
        $this->_testFor_pattern( $text, ("Referee [(]"
                                         .show_status( $status3 )."[)]")); 
        $this->_testFor_referee_project_link($text,$row2['proid'],
                                                   $row2['project_title'],
                                                   $row2['status']);
        $this->_testFor_referee_project_link($text,$row3['proid'],
                                                   $row3['project_title'],
                                                   $row3['status']);
        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testPersonal_cooperation() {

        $user1 = "fubar"; $status1 = "D";
        $user2 = "snafu"; $status2 = "A";
        $user3 = "fritz"; $status3 = "R";

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 3 );

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
        
        // this is for the snafu query
        $row1 = array( 'proid'         => 'proid',
                       'devid'         => 'devid',
                       'project_title' => 'project_title' );
        $db_config->add_record( $row1, 1 );
        $db_config->add_record( false, 1 );
        // this is for teh fritz query
        $row2 = array( 'proid'         => 'proid2',
                       'devid'         => 'devid2',
                       'project_title' => 'project_title2' );
        $db_config->add_record( $row2, 2 );
        $row3 = array( 'proid'         => 'proid3',
                       'devid'         => 'devid3',
                       'project_title' => 'project_title3' );
        $db_config->add_record( $row3, 2 );
        $db_config->add_record( false, 2 );
        
        capture_start();
        // here next_record will not be called
        personal_cooperation( $user1, $status1 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 576 );
        // check title string
        $this->_testFor_pattern( $text, ("Developing Cooperation [(]"
                                         .show_status( $status1 )."[)]")); 
        $this->_testFor_line( $text, ("No developing cooperation proposal "
                                      ."with this status") );

        capture_reset_text();
        capture_start();
        personal_cooperation( $user2, $status2 );
        capture_stop();
        
        $this->_testFor_length( 689 );
        $text = capture_text_get();
        // check the title string
        $this->_testFor_pattern( $text, ("Developing Cooperation [(]"
                                         .show_status( $status2 )."[)]")); 

        $this->_testFor_cooperation_project_link($text,$row1['proid'],
                                                       $row1['project_title'],
                                                       $row1['devid'] );

        // fritz query
        capture_reset_text();
        capture_start();
        personal_cooperation( $user3, $status3 );
        capture_stop();
        
        $this->_testFor_length( 861 );
        $text = capture_text_get();
        // check the title string
        $this->_testFor_pattern( $text, ("Developing Cooperation [(]"
                                         .show_status( $status3 )."[)]")); 

        $this->_testFor_cooperation_project_link($text,$row2['proid'],
                                                       $row2['project_title'],
                                                       $row2['devid'] );
        $this->_testFor_cooperation_project_link($text,$row3['proid'],
                                                       $row3['project_title'],
                                                       $row3['devid'] );
        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testPersonal_my_projects() {

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 3 );

        $db_q = ("SELECT * FROM description WHERE "
                 . "description_user='%s' ORDER BY "
                 . "description_creation DESC");
        
        $db_config->add_query( sprintf( $db_q, "fubar" ), 0);
        $db_config->add_query( sprintf( $db_q, "snafu" ), 1);
        $db_config->add_query( sprintf( $db_q, "fritz" ), 2);

        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(1, 1);
        $db_config->add_num_row(2, 2);
        
        // fubar query does not call next_record()
        // this is for the snafu query
        $row = array( 'proid'         => 'proid',
                      'status'        => 'status',
                      'project_title' => 'project_title' );
        $db_config->add_record( $row, 1 );
        $db_config->add_record( false, 1 );

        // this is for the fritz query
        $row = array( 'proid'         => 'proid2',
                      'status'        => 'status2',
                      'project_title' => 'project_title2' );
        $db_config->add_record( $row, 2 );
        $row = array( 'proid'         => 'proid3',
                      'status'        => 'status3',
                      'project_title' => 'project_title3' );
        $db_config->add_record( $row, 2 );
        $db_config->add_record( false, 2 );

        capture_start();
        // here next_record will not be called
        personal_my_projects( "fubar" );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 524 );
        $this->_testFor_pattern( $text, "My Projects" ); // title
        $this->_testFor_line( $text, "No personal projects" );
        
        capture_reset_text();
        capture_start();
        // here next_record should be called once --> num_row == 1
        personal_my_projects( "snafu" );
        capture_stop();
        
        $this->_testFor_length( 590 );
        $text = capture_text_get();
        $this->_testFor_pattern( $text, "My Projects" ); // title
        $this->_testFor_project_link($text,"proid","project_title","status");

        capture_reset_text();
        capture_start();
        // here next_record should be called once --> num_row == 1
        personal_my_projects( "fritz" );
        capture_stop();

        $this->_testFor_length( 683 );
        $text = capture_text_get();
        $this->_testFor_pattern( $text, "My Projects" ); // title
        $this->_testFor_project_link($text,"proid2","project_title2",
                                                                   "status2");
        $this->_testFor_project_link($text,"proid3","project_title3",
                                                                   "status3");

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testPersonal_Monitored_Projects() {

        $db_q = ("SELECT * FROM monitor,description WHERE "
                 ."monitor.proid=description.proid AND monitor"
                 .".username='%s' ORDER BY creation DESC");

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 3 );

        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(1, 1);
        $db_config->add_num_row(2, 2);

        $db_config->add_query( sprintf( $db_q, "fubar" ), 0);
        $db_config->add_query( sprintf( $db_q, "snafu" ), 1);
        $db_config->add_query( sprintf( $db_q, "fritz" ), 2);

        // this is for the snafu query
        $row = array( 'proid'         => 'proid',
                      'status'        => 'status',
                      'project_title' => 'project_title' );
        $db_config->add_record( $row, 1 );
        $db_config->add_record( false, 1 );

        // this is for the fritz query
        $row = array( 'proid'         => 'proid2',
                      'status'        => 'status2',
                      'project_title' => 'project_title2' );
        $db_config->add_record( $row, 2 );
        $row = array( 'proid'         => 'proid3',
                      'status'        => 'status3',
                      'project_title' => 'project_title3' );
        $db_config->add_record( $row, 2 );
        $db_config->add_record( false, 2 );


        capture_start();
        // here next_record will not be called
        personal_monitored_projects( "fubar" );
        capture_stop();

        $text = capture_text_get();

        $this->_testFor_length( 532 );
        $this->_testFor_pattern( $text, "Monitored Projects" ); // title
        $this->_testFor_line( $text, "No monitored projects" );

        capture_reset_text();
        capture_start();
        // here next_record should be called once --> num_row == 1
        personal_monitored_projects( "snafu" );
        capture_stop();
        
        $this->_testFor_length( 597 );
        $text = capture_text_get();
        $this->_testFor_pattern( $text, "Monitored Projects" ); // title
        $this->_testFor_project_link($text,"proid","project_title","status");

        capture_reset_text();
        capture_start();
        // here next_record should be called once --> num_row == 1
        personal_monitored_projects( "fritz" );
        capture_stop();

        $this->_testFor_length( 690 );
        $text = capture_text_get();
        $this->_testFor_pattern( $text, "Monitored Projects" ); // title
        $this->_testFor_project_link($text,"proid2","project_title2",
                                                                   "status2");
        $this->_testFor_project_link($text,"proid3","project_title3",
                                                                   "status3");

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }
}

define_test_suite( __FILE__ );

?>
