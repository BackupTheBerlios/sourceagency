<?php
// constants.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: constants.php,v 1.11 2001/10/30 14:49:24 riessen Exp $
//
// php library root directory
$env_php_lib_dir = getenv( "PHP_LIB_DIR" );
if ( ! $env_php_lib_dir || $env_php_lib_dir == "" ) {
  $PHP_LIB_DIR = "/www/development/lib/php";
} else {
  $PHP_LIB_DIR = $env_php_lib_dir;
}

ini_set('include_path', ini_get('include_path') . ':' . $PHP_LIB_DIR );
ini_set('include_path', ini_get('include_path') . ':' . getcwd() );

// php unit test framework
include_once("phpunit.php");
// strange really: this file is at the same level as the constants BUT
// because constants is included in the tests, need to prefix an "../"
include_once( "../mock_database.php" );
include_once( "../mock_auth.php" );

function define_test_suite( $filename ) {
    // using the naming convention that the file name is "TestXXXX.php"
    // and the class that is the unit test class is "UnitTestXXXX"
    if ( defined("BEING_INCLUDED") ) { 
        // we're being included, that implies that a $suite global exists
        global $suite;
        $suite->addTest( new TestSuite( "Unit"
                                        . preg_replace( "/[.]php$/", "", 
                                                        $filename )));
    } else {
        // do the test.
        $suite = new TestSuite("Unit" . preg_replace("/[.]php$/", "", 
                                                     $filename));
        $testRunner = new TestRunner;
        $testRunner->run( $suite );
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
?>
