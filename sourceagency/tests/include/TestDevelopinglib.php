<?php
// TestDevelopinglib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestDevelopinglib.php,v 1.2 2002/06/06 08:18:27 riessen Exp $

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
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
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
        global $t;
        $v = array( 'No', 'no', 'yes', 'Yes', 'Yes Please', 'NO', 'YES' );
        while ( list( , $val ) = each( $v ) ) {
            capture_reset_and_start();
            $this->set_text( developing_select_cooperation( $val ) );
            $this->assertEquals(0,strlen(capture_stop_and_get()),"Test $val");
            $this->_testFor_html_select( 'cooperation' );
            $this->_testFor_html_select_option( 'No', ($val=='No'), 
                                                $t->translate('No'));
            $this->_testFor_html_select_end();
            $this->_testFor_string_length( ($val=='No'? 93 : 84 ) );
        }
    }

    function testSelect_duration() {
        for ( $idx = -10; $idx < 110; $idx++ ) {
            capture_reset_and_start();
            $this->set_text( select_duration( $idx ) );
            $this->assertEquals(0,strlen(capture_stop_and_get()),"test $idx");
            // if something is selected, then strings is longer
            $this->_testFor_string_length(($idx<1||$idx>100 ? 2936:2945));
            $this->_testFor_html_select( 'duration' );
            for ( $jdx = 1; $jdx < 101; $jdx++ ) {
                $this->_testFor_html_select_option( $jdx, ($jdx==$idx), $jdx );
            }
            $this->_testFor_html_select_end();
        }
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
