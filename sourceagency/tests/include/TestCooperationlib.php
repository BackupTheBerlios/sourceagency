<?php
// TestCooperationlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestCooperationlib.php,v 1.2 2002/06/06 08:18:27 riessen Exp $

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
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
    }

    function testCooperation_form() {
        global $cost, $bx, $t;

        $args=$this->_generate_records( array( 'proid', 'devid' ), 2 );
        $cost = 'this is the cost';

        $bx = $this->_create_default_box();
        capture_reset_and_start();
        call_user_func_array( 'cooperation_form', $args[0] );
        $this->set_text( capture_stop_and_get() );

        $this->_checkFor_a_box( 'Cooperation' );
        $this->_checkFor_columns( 2 );
        $this->_checkFor_a_form('PHP_SELF',array('proid'=>$args[0]['proid'],
                                                 'devid'=>$args[0]['devid']));
        $this->_checkFor_column_titles( array( 'Cost in euro' ) );
        $this->_checkFor_column_values( array( html_input_text('cost',7,
                                                               7,$cost)));
        $this->_checkFor_submit_preview_buttons();
        $this->_testFor_string_length( 1827 );
    }

    function testCooperation_insert() {
        $this->_test_to_be_completed();
    }
    
    function testCooperation_modify() {
        $this->_test_to_be_completed();
    }

    function testCooperation_preview() {
        global $t, $bx, $auth, $sess, $cost;
        
        $auth->set_uname( 'this is the username' );
        $args=$this->_generate_records( array( 'proid', 'devid' ), 2 );
        $cost = 'this is the cost';
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        call_user_func_array( 'cooperation_preview', $args[0] );
        $this->set_text( capture_stop_and_get() );
        
        $this->_checkFor_a_box( 'PREVIEW', '<center><b>%s</b></center>' );
        $this->_checkFor_a_box( 'Cooperation' );
        $this->_testFor_pattern( $this->_to_regexp( "<p><b>Cost</b>: $cost "
                                                    ."euro\n"));
        $this->_testFor_lib_nick( $auth->auth['uname'] );
        $this->_testFor_string_length( 966 + strlen( timestr( time() ) ) );
    }

    function testCooperation_show() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
