<?php
// TestContentlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestContentlib.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'contentlib.inc' );

class UnitTestContentlib
extends UnitTest
{
    function UnitTestContentlib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testContent_box_footer() {
        $this->_test_to_be_completed();
    }
    function testContent_form() {
        $this->_test_to_be_completed();
    }

    function testContent_insert() {
        $this->_test_to_be_completed();
    }

    function testContent_modify() {
        $this->_test_to_be_completed();
    }

    function testContent_modify_form() {
        $this->_test_to_be_completed();
    }

    function testContent_preview() {
        $this->_test_to_be_completed();
    }

    function testShow_content() {
        $this->_test_to_be_completed();
    }

    function testShow_proposals() {
        $this->_test_to_be_completed();
    }

    function testshow_selected_content() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
