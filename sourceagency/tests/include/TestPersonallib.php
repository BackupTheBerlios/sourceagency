<?php
// TestPersonallib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestPersonallib.php,v 1.8 2001/10/24 17:09:31 riessen Exp $

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

    function UnitTestPersonallib( $name ) {
        $this->TestCase( $name );
    }
    
    function setup() {
        // Called before each test method.
        // if using the capturing routines then ensure that it's reset,
        // it uses global variables
        capture_reset_text();
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

    //
    // Start of the actual test methods
    //
    function testPersonal_news_long() {
        $user1 = "fubar";
        $user2 = "snafu";

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 3 );

        $db_q = ("SELECT * FROM comments WHERE user_cmt='%s' "
                 ."AND comments.proid");
        $db_config->add_query( sprintf( $db_q, $user1 ), 0);
        $db_config->add_query( sprintf( $db_q, $user2 ), 1);

        $db_config->add_num_row(0, 0);
        $db_config->add_num_row(1, 1);

        $db_config->add_record( false, 0 );
        $row2 = array( 'id'            => 'reference_id',
                       'proid'         => 'project_id',
                       'project_title' => 'project_title',
                       'subject_news'  => 'subject news',
                       'creation_news' => 'creation_news');
        $db_config->add_record( $row2, 1 );
        $db_config->add_record( false, 1 );

        $row1 = array( 'id'            => 'reference_id_instance_2',
                       'proid'         => 'project_id_instance_2',
                       'COUNT(*)'      => 'count star value' );
        $db_config->add_record( $row1, 2 );
        $row3 = array( 'project_title' => 'reference_id_instance_2',
                       'proid'         => 'project_id_instance_2');
        $db_config->add_record( $row3, 2 );

        $db_q = ("SELECT COUNT(*) FROM comments WHERE proid='%s"
                 ."' AND type='News' AND ref='%s'");
        $db_config->add_query(sprintf( $db_q, $row2['proid'], $row2['id']),2);
        $db_q = ("SELECT * FROM description WHERE proid='%s'");
        $db_config->add_query(sprintf( $db_q, $row2['proid']),2);

        //
        // fubar query
        //
        capture_start();
        // here next_record will not be called
        personal_news_long( $user1 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 559 );
        //
        // TODO: need to complete this test ....
        //

        //
        // snafu query
        //
        capture_reset_text();
        capture_start();
        // here next_record will not be called
        personal_news_long( $user2 );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 775 );

        //
        // TODO: need to complete this test ....
        //

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
