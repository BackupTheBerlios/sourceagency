<?php
// TestPersonallib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestPersonallib.php,v 1.4 2001/10/22 10:06:16 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    ini_set('include_path', ini_get('include_path') . ':../../include' );
    
    include_once( "session.inc" );
    $sess = new Session;
    
    include_once( 'box.inc' );
    $bx = new box;
} 

class db_sourceagency 
extends mock_database 
{
    function db_sourceagency() {
        // call the constructor of our parent
        $this->mock_database();
    }
}

include_once( 'html.inc' ); // implicitly required by personallib.inc
include_once( 'personallib.inc' );

class UnitTestPersonallib
extends TestCase
{
    function UnitTestPersonallib( $name ) {
        $this->TestCase( $name );
    }

    function testPersonal_Monitored_Projects() {

        $db_q = ("SELECT * FROM monitor,description WHERE "
                 ."monitor.proid=description.proid AND monitor"
                 .".username='%s' ORDER BY creation DESC");

        $db_config = new mock_db_configure;

        $db_config->add_num_row(0);
        $db_config->add_num_row(1);
        $db_config->add_num_row(2);

        $db_config->add_query( sprintf( $db_q, "fubar" ));
        $db_config->add_query( sprintf( $db_q, "snafu" ));
        $db_config->add_query( sprintf( $db_q, "fritz" ));

        // this is for the snafu query
        $row = array( 'proid' => 'proid',
                      'status' => 'status',
                      'project_title' => 'project_title' );
        $db_config->add_record( $row );
        $db_config->add_record( false );

        // this is for the fritz query
        $row = array( 'proid' => 'proid2',
                      'status' => 'status2',
                      'project_title' => 'project_title2' );
        $db_config->add_record( $row );
        $row = array( 'proid' => 'proid3',
                      'status' => 'status3',
                      'project_title' => 'project_title3' );
        $db_config->add_record( $row );
        $db_config->add_record( false );

        capture_start();
        // here next_record will not be called
        personal_monitored_projects( "fubar" );
        capture_stop();

        $text = capture_text_get();
        $this->assertRegexp( "/No monitored projects/", $text );

        capture_reset_text();

        capture_start();
        // here next_record should be called once --> num_row == 1
        personal_monitored_projects( "snafu" );
        capture_stop();
        
        $text = capture_text_get();
        $this->assertRegexp( "/Project:[^\n]*summary[.]php3[?]proid=proid"
                             ."[^\n]*project_title[^\n]*status[^\n]*[<]br[>]/",
                             $text );

        capture_start();
        // here next_record should be called once --> num_row == 1
        personal_monitored_projects( "fritz" );
        capture_stop();

        $text = capture_text_get();
        $this->assertRegexp( "/Project:[^\n]*summary[.]php3[?]proid=proid2"
                             ."[^\n]*project_title2[^\n]*status2[^\n]*"
                             ."[<]br[>]/",
                             $text );
        $this->assertRegexp( "/Project:[^\n]*summary[.]php3[?]proid=proid3"
                             ."[^\n]*project_title3[^\n]*status3[^\n]*"
                             ."[<]br[>]/",
                             $text );

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, 
                            $db_config->did_db_fail(),
                            $db_config->error_message() );
    }
}

define_test_suite( __FILE__ );

?>
