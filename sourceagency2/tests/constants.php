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
# $Id: constants.php,v 1.1 2003/11/21 12:56:02 helix Exp $
#
######################################################################

// php library root directory
$env_php_lib_dir = getenv( "PHP_LIB_DIR" );
if ( ! $env_php_lib_dir || $env_php_lib_dir == "" ) {
  $PHP_LIB_DIR = "/www/development/lib/php";
} else {
  $PHP_LIB_DIR = $env_php_lib_dir;
}

function unset_error_values() {
    unset_global( 'err_no', 'err_msg', 'err_line', 'err_file' );
}
// define an error handler should an error occur
error_reporting( E_ALL );
function unit_test_error_handler($errno, $errmsg, $filename, $linenum, $vars){
    switch ( $errno ) {
        case E_ERROR:
            /* fatal error */
        default:
            /* fall through */
    }
    $GLOBALS['err_no'] = $errno;
    $GLOBALS['err_msg'] = $errmsg;
    $GLOBALS['err_line'] = $linenum;
    $GLOBALS['err_file'] = $filename;
}
set_error_handler("unit_test_error_handler");

// for obtaining time information
function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
} 

// extension to the in_array function which takes regular expressions
function in_array_regexp( $rexp, &$array ) {
    if ( is_array( $array ) ) {
        while ( list ($key, $val) = each ($array)) {
            if ( ereg( $rexp, $val ) ) {
                return TRUE;
            }
        }
    } else {
        return ( ereg( $rexp, $array ) );
    }
    return FALSE;
}

// if brief was given as an option, then supress the printing of
// "requiring file ..." statements, and explicitly displaying failures
// if a test is to be completed.
// To check whether this is set, check the global define variable OPT_BE_BRIEF
if ( in_array_regexp( "[[:<:]]brief[[:>:]]", $argv ) ) {
    define( "OPT_BE_BRIEF", "yes" );
}

// this is were the phpunit class should be located, filename: phpunit.php
ini_set('include_path', $PHP_LIB_DIR . ':' . ini_get('include_path') );
// this is the location of the mock database and other unit test specific
// stuff
ini_set('include_path', getcwd() . ':' . ini_get('include_path') );

if ( !defined("BEING_INCLUDED" ) ) {
    global $start_time;
    $start_time = getmicrotime();

    ini_set('include_path', 
            ini_get('include_path') . ':'.getcwd().'/../../include' );
    ini_set('include_path', 
            ini_get('include_path') . ':'.getcwd().'/../' );

    include_once( "config.inc" );
    include_once( "session.inc" );
    $GLOBALS['sess'] = new Session;
    
    include_once( "logger.inc" );
    $GLOBALS[ 'l' ] = new Logger;
    
    $GLOBALS[ 'lang' ] = "English";
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
        // doing a single test, no global suite
        global $old_error_handler;
        $suite = new TestSuite(_filename_to_classname( $filename ));
        $testRunner = new TestRunner;
        $testRunner->run( $suite );
        mkdb_check_did_db_fail_calls();
        global $start_time;
        $time = getmicrotime() - $start_time;
        print "Completed test in $time seconds\n";
    }
}

function unset_global(/** takes variable number of arguments **/) {
    $args = func_get_args();
    while ( list( , $variable_name ) = each( $args ) ) {
        unset( $GLOBALS[ $variable_name ] );
    }
}
?>
