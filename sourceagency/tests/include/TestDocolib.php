<?php
// TestDocolib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestDocolib.php,v 1.3 2002/06/06 09:31:37 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'docolib.inc' );

class UnitTestDocolib
extends UnitTest
{
    function UnitTestDocolib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
    }

    function testDoco_form() {
        global $sess, $bx, $t, $page, $header, $doco;

        $page = "this is te [page";
        $header = "this is the harda";
        $doco = 'thsi is the doco';
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        doco_form();
        $this->set_text( capture_stop_and_get() );

        $this->_checkFor_a_form( 'PHP_SELF' );
        $this->_checkFor_a_box( 'New Page Documentation Entry' );
        $this->_checkFor_columns( 2 );

        $v=array( 'Page (without extension)' =>
                  html_input_text('page', 40, 64, $page),
                  'Header' => 
                  html_input_text('header', 40, 255, $header),
                  'Description' =>
                  html_textarea('doco', 40, 7, 'virtual', 255, $doco) );
        while ( list( $key, $val ) = each( $v ) ) {
            $this->push_msg( "Test $key" );
            $this->_checkFor_column_titles( array( $key ) );
            $this->_checkFor_column_values( array( $val ) );
            $this->pop_msg();
        }
        $this->_testFor_html_form_submit( $t->translate( 'Create' ) );
        $this->_testFor_string_length( 2786 + strlen( $sess->self_url() ));
    }

    function testDoco_mod() {
        global $sess, $t, $bx;

        $d=$this->_generate_records(array('page','header','doco','docoid'),1);
        $db_config = new mock_db_configure( 1 );
        $db_config->add_query( 'fubar', 0 );
        $db_config->add_record( $d[0], 0 );
        
        $db = new DB_SourceAgency;
        $db->query( 'fubar' );
        $db->next_record();
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        doco_mod( $db );
        $this->set_text( capture_stop_and_get() );
        
        $v=array( 'Page (without extension)' =>
                  html_input_text('page', 40, 64, $d[0]['page']),
                  'Header' =>
                  html_input_text('header', 40, 64, $d[0]['header']),
                  'doco' =>
                  html_textarea('doco', 40, 7, 'virtual', 255, $d[0]['doco']));
        while( list( $key, $val ) = each( $v ) ) {
            $this->push_msg( "Test $key" );
            $this->_checkFor_column_titles( array( $key ) );
            $this->_checkFor_column_values( array( $val ) );
            $this->pop_msg();
        }
        $this->_testFor_html_form_hidden( 'docoid', $d[0]['docoid'] );
        $this->_testFor_html_form_hidden( 'modify', 2 );
        $this->_testFor_html_form_submit( $t->translate( 'Modify' ) );

        $this->_testFor_string_length( 2811 + strlen( $sess->self_url() ));
        $this->_check_db( $db_config );
    }

    function testDoco_show() {
        global $bx;

        $d=$this->_generate_records(array('page','header','doco'), 1 );
        $db_config = new mock_db_configure( 1 );
        $db_config->add_query( 'fubar', 0 );
        $db_config->add_record( $d[0], 0 );
        
        $db = new DB_SourceAgency;
        $db->query( 'fubar' );
        $db->next_record();
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        doco_show( $db );
        $this->set_text( capture_stop_and_get() );
        $this->_checkFor_box_full( $d[0]['page'].': '.$d[0]['header'],
                                                                $d[0]['doco']);
        $this->_testFor_string_length( 753 );
        $this->_check_db( $db_config );
    }

}

define_test_suite( __FILE__ );
?>
