<?php
// TestLogger.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestLogger.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'logger.inc' );

class UnitTestLogger
extends UnitTest
{
    function UnitTestLogger( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testClose() {
        $this->_test_to_be_completed();
    }

    function testGetLogFile() {
        $this->_test_to_be_completed();
    }

    function testLog() {
        $this->_test_to_be_completed();
    }

    function testOpen() {
        $this->_test_to_be_completed();
    }

    function testSetLogFile() {
        $this->_test_to_be_completed();
    }
}

define_test_suite( __FILE__ );
?>
