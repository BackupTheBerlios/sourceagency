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
# include/security.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: constants.php,v 1.18 2002/02/01 08:40:52 riessen Exp $
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
ini_set('include_path', ini_get('include_path') . ':' . $PHP_LIB_DIR );
// this is the location of the mock database and other unit test specific
// stuff
ini_set('include_path', ini_get('include_path') . ':' . getcwd() );

if ( !defined("BEING_INCLUDED" ) ) {
    ini_set('include_path', 
            ini_get('include_path') . ':'.getcwd().'/../../include' );
    ini_set('include_path', 
            ini_get('include_path') . ':'.getcwd().'/../' );
    include_once( "config.inc" );
}

// php unit test framework
include_once("phpunit.php");
// strange really: this file is at the same level as the constants BUT
// because constants is included in the tests, need to prefix an "../"
include_once( "mock_database.php" );
include_once( "mock_auth.php" );
include_once( "unit_test.php" );

function _filename_to_classname( $filename ) {
  // needed to add this for PHP4.1.0 -- __FILE__ includes the
  // absolute path
  $basename = basename( $filename );
  // .php3, .php, .php4 are all removed
  $basename = preg_replace( "/[.]php.?$/", "", $basename );
  // remove .inc extension
  $basename = preg_replace( "/[.]inc$/", "", $basename );
  
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

// global (g_) variable for storing the data that would normally
// have been outtputed through echo's or print's. Avoiding using
// this directly, instead use the functions provided below.
$g_cap_text="";

// this function is passed to the ob_start function to capture text
// somehow the description of ob_start is incorrect: no buffer is 
// created and the data isn't stored ... no idea!
function capture_text( $str ) {
    global $g_cap_text;
    $g_cap_text .= $str;
    return "";
}

// replaces the ob_get_length function
function capture_text_length() {
    global $g_cap_text;
    return ( strlen( $g_cap_text ) );
}

// replaces the ob_get_content function
function capture_text_get() {
    global $g_cap_text;
    return ($g_cap_text);
}

// this should be called to begin output capturing
function capture_start() {
    ob_start("capture_text");
}

// this must be called to stop output capturing
function capture_stop() {
    ob_end_flush();
}

// resets the contents of the capture buffer to zero
function capture_reset_text() {
    global $g_cap_text;
    $g_cap_text="";
}

// stop the capturing and return the captured text
function capture_stop_and_get() {
  capture_stop();
  return capture_text_get();
}

// short cut: one call instead of two
function capture_reset_and_start() {
    capture_reset_text();
    capture_start();
}

?>
