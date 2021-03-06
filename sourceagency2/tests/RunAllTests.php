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
# Class for locating all UnitTests in subdirectories and executing them.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: RunAllTests.php,v 1.1 2003/11/21 12:56:02 helix Exp $
#
######################################################################

if ( floor( phpversion() ) < 4 ) {
    print( "Require atleast version 4 of php to run tests\n" );
    exit;
}
    
ini_set( 'include_path', 
         getcwd() . '/../include' . ':' . ini_get('include_path') );

// indicate to all test files that they are being included.
define( "BEING_INCLUDED", "yes" );

include_once( "constants.php" );
$time_start = getmicrotime();

// needed to include the config file ....
include_once( "config.inc" );

// define required global variables.
// required for the $sess global variable
include_once( "session.inc" );
$sess = new Session;
global $sess;

// seems to require a logger ....
include_once( "logger.inc" );
$GLOBALS['l'] = new Logger;

global $lang;
$lang = "English";
include_once( "lang.inc" );

// defines the global translation object
include_once( "translation.inc" );
$GLOBALS['t'] = new translation( "English" );

// define a box object
include_once( "box.inc" );

// define the global TestSuite
$suite = new TestSuite;
$testRunner = new TestRunner;
$total_tests = 0;
$total_failures = 0;

// function for scanning a directory and including all 
// files that begin with 'Test'
function scan_directory( ) {
    global $suite, $total_tests, $total_failures, $testRunner;
    $d = opendir( "." );
    while ( $entry = readdir( $d ) ) {
        if ( $entry == "." || $entry == ".." ) {
            /** ignore these directories **/
        } else if ( is_dir($entry ) ) {
            chdir( $entry );
            scan_directory( );
            chdir( ".." );
        } else if ( preg_match( "/^[T|t]est/", $entry ) 
                    && (preg_match( "/[.]php3?$/", $entry)
                        || preg_match( "/[.]inc$/", $entry ))) {
            if ( !defined( "OPT_BE_BRIEF" ) ) {
                print "Requiring file ... $entry<br>\n";
            }
            require_once( $entry );

            // because we run into memory problems if we include
            // all files and then run the tests, we run the test suite
            // after each include.
            $result = $testRunner->run( $suite );
            mkdb_check_did_db_fail_calls();
            $total_tests += $result->countTests();
            $total_failures += $result->countFailures();

            unset_global( 'queries' );
            $result = '';
            $suite = new TestSuite;
        }
    }
    closedir( $d );
}

// load in all available test files.
scan_directory( );

// summary information 
print "<br>\n ----- Summary ----- <br>\n";
print "Total tests/failures: <b>$total_tests</b>/<b>$total_failures</b><br>\n";
// finally print information about how long the tests took
$time = getmicrotime() - $time_start;
// this causes problems with Phester calls which detect this as
// a change ....
//print "Executed all tests in $time seconds";
?>
