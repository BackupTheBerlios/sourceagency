<?php
// TestAdmprojectslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestAdmprojectslib.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'admprojectslib.inc' );

class UnitTestAdmprojectslib
extends UnitTest
{
    function UnitTestAdmprojectslib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    
    function tearDown() {
    }
    
    function testAdmprojects_insert() {
        $this->_test_to_be_completed();
    }
    
    function testShow_admprojects() {
        $this->_test_to_be_completed();
    }
}

define_test_suite( __FILE__ );
?>
