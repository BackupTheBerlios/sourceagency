<?php
// TestDevelopinglib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestDevelopinglib.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'developinglib.inc' );

class UnitTestDevelopinglib
extends UnitTest
{
    function UnitTestDevelopinglib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testDeveloping_form() {
        $this->_test_to_be_completed();
    }
    function testDeveloping_insert() {
        $this->_test_to_be_completed();
    }

    function testDeveloping_modify() {
        $this->_test_to_be_completed();
    }

    function testDeveloping_modify_form() {
        $this->_test_to_be_completed();
    }

    function testDeveloping_preview() {
        $this->_test_to_be_completed();
    }

    function testDeveloping_select_cooperation() {
        $this->_test_to_be_completed();
    }

    function testSelect_duration() {
        $this->_test_to_be_completed();
    }

    function testShow_developings() {
        $this->_test_to_be_completed();
    }

    function testShow_selected_developing() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
