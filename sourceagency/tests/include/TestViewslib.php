<?php
// TestViewslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestViewslib.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'viewslib.inc' );

class UnitTestViewslib
extends UnitTest
{
    function UnitTestViewslib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testViews_form() {
        $this->_test_to_be_completed();
    }

    function testViews_insert() {
        $this->_test_to_be_completed();
    }

    function testViews_modify() {
        $this->_test_to_be_completed();
    }

    function testViews_preview() {
        $this->_test_to_be_completed();
    }

    function testViews_select_view() {
        $this->_test_to_be_completed();
    }

    function testViews_show() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
