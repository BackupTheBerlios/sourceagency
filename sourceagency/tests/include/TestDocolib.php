<?php
// TestDocolib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestDocolib.php,v 1.5 2002/06/26 10:29:52 riessen Exp $

include_once( '../constants.php' );

include_once( 'html.inc' );
include_once( 'docolib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

class UnitTestDocolib
extends UnitTest
{
    function UnitTestDocolib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset_global( 'db', 'bx' );
    }

    function testDoco_form() {
        global $sess, $bx, $t, $page, $header, $doco;

        $page = "this is te [page";
        $header = "this is the harda";
        $doco = 'thsi is the doco';
        $bx = $this->_create_default_box();
        $this->capture_call( 'doco_form', 2786 + strlen( $sess->self_url() ));

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
        $this->capture_call( 'doco_mod', 2811 + strlen( $sess->self_url() ),
                             array( &$db ) );
        
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
        $this->capture_call( 'doco_show', 753, array( &$db ) );

        $this->_checkFor_box_full( $d[0]['page'].': '.$d[0]['header'],
                                                                $d[0]['doco']);
        $this->_check_db( $db_config );
    }

}

define_test_suite( __FILE__ );
?>
