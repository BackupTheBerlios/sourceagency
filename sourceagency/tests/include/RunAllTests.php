<?php
// RunAllTests.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: RunAllTests.php,v 1.1 2001/10/11 09:19:37 ger Exp $

require_once( "../constants.php" );

//
// PHP code for collecting all the test files in one directory together,
// and collecting all their tests into a single test suite and calling
// that suite.
//
ini_set('include_path', ini_get('include_path') . ':../../include' );
ini_set('include_path', ini_get('include_path') . ':'.$LIB_ROOT.'/php' );

require_once("phpunit.php");

// indicate to all test files that they are being included.
define( "BEING_INCLUDED", "yes" );

// define required global variables.
// required for the $sess global variable
require_once( "session.inc" );
$sess = new Session;

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
