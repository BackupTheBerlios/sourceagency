<?php
// TestPersonallib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestPersonallib.php,v 1.3 2001/10/18 18:51:01 riessen Exp $

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
  }
}

include_once( 'personallib.inc' );

class UnitTestPersonallib
extends TestCase
{
    function UnitTestPersonallib( $name ) {
        $this->TestCase( $name );
    }

    function testPersonal_Monitored_Projects() {

        ob_start("capture_text");
        personal_monitored_projects( "fubar" );
        ob_end_flush();

        printf( "Total length of data captured: %d\n", capture_text_length() );
        print capture_text_get();
    }
}

define_test_suite( __FILE__ );

?>
