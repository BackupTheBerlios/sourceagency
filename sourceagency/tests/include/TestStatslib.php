<?php
// TestStatslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestStatslib.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'statslib.inc' );

class UnitTestStatslib
extends UnitTest
{
    function UnitTestStatslib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testStats_display() {
        $this->_test_to_be_completed();
    }

    function testStats_display_alt() {
        $this->_test_to_be_completed();
    }

    function testStats_end() {
        $this->_test_to_be_completed();
    }

    function testStatslib_top() {
        $this->_test_to_be_completed();
    }

    function testStats_subtitle() {
        $this->_test_to_be_completed();
    }

    function testStats_title() {
        $this->_test_to_be_completed();
    }
}

define_test_suite( __FILE__ );
?>
