<?php
// constants.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: constants.php,v 1.5 2001/10/11 10:44:39 ger Exp $
//
// php library root directory
$LIB_ROOT = "/www/development/lib";

ini_set('include_path', ini_get('include_path') . ':'.$LIB_ROOT.'/php' );
//  php unit test framework
require_once("phpunit.php");

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

?>
