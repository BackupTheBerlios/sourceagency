<?php
// TestCooperationlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestCooperationlib.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'cooperationlib.inc' );

class UnitTestCooperationlib
extends UnitTest
{
    function UnitTestCooperationlib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testCooperation_form() {
        $this->_test_to_be_completed();
    }

    function testCooperation_insert() {
        $this->_test_to_be_completed();
    }
    
    function testCooperation_modify() {
        $this->_test_to_be_completed();
    }

    function testCooperation_preview() {
        $this->_test_to_be_completed();
    }

    function testCooperation_show() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
