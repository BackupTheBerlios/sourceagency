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
# Configure the unit test environment and include required utilities.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: constants.php,v 1.23 2002/05/13 10:29:42 riessen Exp $
#
######################################################################

// php library root directory
$env_php_lib_dir = getenv( "PHP_LIB_DIR" );
if ( ! $env_php_lib_dir || $env_php_lib_dir == "" ) {
  $PHP_LIB_DIR = "/www/development/lib/php";
} else {
  $PHP_LIB_DIR = $env_php_lib_dir;
}

// this is were the phpunit class should be located, filename: phpunit.php
ini_set('include_path', $PHP_LIB_DIR . ':' . ini_get('include_path') );
// this is the location of the mock database and other unit test specific
// stuff
ini_set('include_path', getcwd() . ':' . ini_get('include_path') );

if ( !defined("BEING_INCLUDED" ) ) {
    ini_set('include_path', 
            ini_get('include_path') . ':'.getcwd().'/../../include' );
    ini_set('include_path', 
            ini_get('include_path') . ':'.getcwd().'/../' );

    include_once( "config.inc" );
    include_once( "session.inc" );
    $sess = new Session;
    global $sess;
    
    include_once( "logger.inc" );
    $l = new Logger;
    global $l;
    
    global $lang;
    $lang = "English";
    include_once( "lang.inc" );
}

// php unit test framework
include_once("phpunit.php");
// strange really: this file is at the same level as the constants BUT
// because constants is included in the tests, need to prefix an "../"
include_once( 'mock_database.php' );
include_once( 'mock_auth.php' );
include_once( 'mock_perm.php' );
include_once( 'unit_test.php' );
include_once( 'capture.php' );

function _filename_to_classname( $filename ) {
  // needed to add this for PHP4.1.0 -- __FILE__ includes the
  // absolute path
  $basename = basename( $filename );
  // .php3, .php, .php4, .inc are all removed
  $basename = preg_replace( "/[.](inc|php).?$/", "", $basename );
  
  return ( "Unit" . $basename );
}

function define_test_suite( $filename ) {
    // using the naming convention that the file name is "TestXXXX.php"
    // and the class that is the unit test class is "UnitTestXXXX"
    if ( defined("BEING_INCLUDED") ) { 
        // we're being included, that implies that a $suite global exists
        global $suite;
        $suite->addTest( new TestSuite( _filename_to_classname($filename) ) );
    } else {
        // do the test.
        $suite = new TestSuite(_filename_to_classname( $filename ));
        $testRunner = new TestRunner;
        $testRunner->run( $suite );
        mkdb_check_did_db_fail_calls();
    }
}

?>
