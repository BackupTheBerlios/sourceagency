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
# $Id: RunAllTests.php,v 1.13 2002/05/07 09:24:10 riessen Exp $
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
// needed to include the config file ....
include_once( "config.inc" );

// define required global variables.
// required for the $sess global variable
include_once( "session.inc" );
$sess = new Session;
global $sess;

// seems to require a logger ....
include_once( "logger.inc" );
$l = new Logger;
global $l;

global $lang;
$lang = "English";
include_once( "lang.inc" );


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
                    && (preg_match( "/[.]php3?$/", $entry)
                        || preg_match( "/[.]inc$/", $entry ))) {
            print "Requiring file ... $entry<br>\n";
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
mkdb_check_did_db_fail_calls();

?>
