<?php
// TestCooperationlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestCooperationlib.php,v 1.4 2002/06/14 09:14:12 riessen Exp $

include_once( '../constants.php' );

include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'cooperationlib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

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
        global $cost, $bx, $t, $sess;

        $args=$this->_generate_records( array( 'proid', 'devid' ), 2 );
        $cost = 'this is the cost';

        $bx = $this->_create_default_box();
        $this->capture_call( 'cooperation_form', 
                             1827 + strlen( $sess->self_url() ), $args[0] );

        $this->_checkFor_a_box( 'Cooperation' );
        $this->_checkFor_columns( 2 );
        $this->_checkFor_a_form('PHP_SELF',array('proid'=>$args[0]['proid'],
                                                 'devid'=>$args[0]['devid']));
        $this->_checkFor_column_titles( array( 'Cost in euro' ) );
        $this->_checkFor_column_values( array( html_input_text('cost',7,
                                                               7,$cost)));
        $this->_checkFor_submit_preview_buttons();
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
        $this->capture_call( 'cooperation_preview', 
                             966 + strlen(timestr(time())), $args[0] );
        
        $this->_checkFor_a_box( 'PREVIEW', '<center><b>%s</b></center>' );
        $this->_checkFor_a_box( 'Cooperation' );
        $this->_testFor_pattern( $this->_to_regexp( "<p><b>Cost</b>: $cost "
                                                    ."euro\n"));
        $this->_testFor_lib_nick( $auth->auth['uname'] );
    }

    function testCooperation_show() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
