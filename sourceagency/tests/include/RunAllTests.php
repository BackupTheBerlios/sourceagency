<?php
// RunAllTests.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: RunAllTests.php,v 1.4 2001/10/18 18:51:01 riessen Exp $

include_once( "../constants.php" );

// this is really insane but i have to re-edit the include path
// assume that i'm in tests/include, two above me is include
ini_set('include_path', ini_get('include_path') . ':../../include'  );

// indicate to all test files that they are being included.
define( "BEING_INCLUDED", "yes" );

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

// load in all available test files.
$d = opendir( "." );
while ( $entry = readdir( $d ) ) {
  if ( preg_match( "/^[T|t]est/", $entry ) 
       && preg_match( "/[.]php3?$/", $entry ) ) {
    print "Requiring file ... $entry\n";
    require_once( $entry );
  }
}
closedir( $d );

// run all defined tests....
$testRunner = new TestRunner;
$testRunner->run( $suite );

?>
