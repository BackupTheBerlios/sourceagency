<?php
// TestBrowselib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestBrowselib.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'browselib.inc' );

class UnitTestBrowselib
extends UnitTest
{
    function UnitTestBrowselib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testBrowse_licenses() {
        $this->_test_to_be_completed();
    }

    function testBrowse_list() {
        $this->_test_to_be_completed();
    }

    function testBrowse_through() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
