<?php
// RunAllTests.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: RunAllTests.php,v 1.1 2001/10/31 12:23:15 riessen Exp $

// this is really insane but i have to re-edit the include path
// assume that i'm in tests/include, two above me is include
ini_set( 'include_path', ini_get('include_path') . ':../include'  );
ini_set( 'include_path', ini_get('include_path') . ':../../include'  );

// indicate to all test files that they are being included.
define( "BEING_INCLUDED", "yes" );

include_once( "constants.php" );

// define required global variables.
// required for the $sess global variable
include_once( "session.inc" );
$sess = new Session;
global $sess;

// defines the global translation object
include_once( "translation.inc" );
$t = new translation( "English" );
global $t;

// define a box object
include_once( "box.inc" );
$bx = new box;
global $bx;

// define the global TestSuite
$suite = new TestSuite;

// function for scanning a directory and including all 
// files that begin with 'Test'
function scan_directory( ) {

  $d = opendir( "." );
  while ( $entry = readdir( $d ) ) {
    if ( $entry == "." || $entry == ".." ) {
      /** ignore these directories **/
    } else if ( is_dir($entry ) ) {
      chdir( $entry );
      scan_directory( );
      chdir( ".." );
    } else if ( preg_match( "/^[T|t]est/", $entry ) 
                && preg_match( "/[.]php3?$/", $entry ) ) {
      print "Requiring file ... $entry\n";
      require_once( $entry );
    }
  }
  closedir( $d );
}

// load in all available test files.
scan_directory( );

// run all defined tests....
$testRunner = new TestRunner;
$testRunner->run( $suite );

?>
