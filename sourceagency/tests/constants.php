<?php
// constants.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: constants.php,v 1.7 2001/10/18 18:20:02 ger Exp $
//
// php library root directory
$PHP_LIB_DIR = "/www/development/lib/php";

ini_set('include_path', ini_get('include_path') . ':' . $PHP_LIB_DIR );
ini_set('include_path', ini_get('include_path') . ':' . getcwd() );
// php unit test framework
include("phpunit.php");
// strange really: this file is at the same level as the constants BUT
// because constants is included in the tests, need to prefix an "../"
include( "../mock_database.php" );

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

?>
