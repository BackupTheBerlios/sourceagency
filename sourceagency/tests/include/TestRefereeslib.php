<?php
// TestRefereeslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestRefereeslib.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'refereeslib.inc' );

class UnitTestRefereeslib
extends UnitTest
{
    function UnitTestRefereeslib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testReferees_form() {
        $this->_test_to_be_completed();
    }

    function testReferees_insert() {
        $this->_test_to_be_completed();
    }

    function testShow_referees() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
