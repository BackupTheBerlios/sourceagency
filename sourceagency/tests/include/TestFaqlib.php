<?php
// TestFaqlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestFaqlib.php,v 1.2 2002/06/06 08:18:27 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'faqlib.inc' );

class UnitTestFaqlib
extends UnitTest
{
    function UnitTestFaqlib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
    }

    function testFaqform() {
        global $sess, $bx, $t;
        global $question, $answer;

        $question = 'this is the question';
        $answer = 'this is he absewe';

        $bx = $this->_create_default_box();
        capture_reset_and_start();
        faqform();
        $this->set_text( capture_stop_and_get() );
        
        $this->_checkFor_a_box( 'New Frequently Asked Question' );
        $this->_checkFor_a_form( 'PHP_SELF' );
        $this->_checkFor_columns( 2 );

        $v=array( 'Question' =>
                  html_textarea('question', 40, 4, 'virtual', 255, $question),
                  'Answer' =>
                  html_textarea('answer', 40, 7, 'virtual', 255, $answer));
        while( list( $key, $val ) = each( $v ) ) {
            $this->push_msg( "Test $key" );
            $this->_checkFor_column_titles( array( $key ), 'right','30%',
                                            '',"<b>%s</b> (*): ");
            $this->_checkFor_column_values( array( $val ) );
            $this->pop_msg();
        }
        $this->_testFor_html_form_hidden( 'create', 2 );
        $this->_testFor_html_form_submit( $t->translate( 'Create' ) );
        $this->_testFor_string_length( 2315 );
    }

    function testFaqmod() {
        global $sess, $t, $bx;

        $db_config = new mock_db_configure( 1 );
        $d=$this->_generate_records( array('question','answer','faqid' ), 1);
        
        $db_config->add_query( 'fubar', 0 );
        $db_config->add_record( $d[0], 0 );
        $db = new DB_SourceAgency;
        $db->query( 'fubar' );
        $db->next_record();
        $bx = $this->_create_default_box();
        
        capture_reset_and_start();
        faqmod( $db );
        $this->set_text( capture_stop_and_get() );

        $this->_checkFor_a_box( 'Modify a Frequently Asked Question' );
        $this->_checkFor_a_form( 'PHP_SELF' );
        $this->_checkFor_columns( 2 );

        $v=array( 'Question' =>
                  html_textarea('question', 40, 4, 'virtual', 255, 
                                $d[0]['question']),
                  'Answer' =>
                  html_textarea('answer', 40, 7, 'virtual', 255, 
                                $d[0]['answer']));
        while( list( $key, $val ) = each( $v ) ) {
            $this->push_msg( "Test $key" );
            $this->_checkFor_column_titles( array( $key ), 'right','30%',
                                            '',"<b>%s</b> (*): ");
            $this->_checkFor_column_values( array( $val ) );
            $this->pop_msg();
        }
        
        $this->_testFor_html_form_hidden( 'modify', 2 );
        $this->_testFor_html_form_hidden( 'faqid', $d[0]['faqid'] );
        $this->_testFor_html_form_submit( $t->translate( 'Modify' ) );
        $this->_testFor_string_length( 2356 );
        $this->_check_db( $db_config );
    }

    function testFaqshow() {
        global $t, $bx;
        
        $db_config = new mock_db_configure( 1 );
        $d=$this->_generate_records( array( 'question', 'answer' ), 1 );
        
        $db_config->add_query( 'fubar', 0 );
        $db_config->add_record( $d[0], 0 );
        $db = new DB_SourceAgency;
        $db->query( 'fubar' );
        $db->next_record();
        $bx = $this->_create_default_box();

        capture_reset_and_start();
        faqshow( $db );
        $this->set_text( capture_stop_and_get() );

        $this->_checkFor_box_full( $t->translate('Question').'</B>: '
                             .$d[0]['question'],'<b>'.$t->translate('Answer')
                             .'</b>: '.$d[0]['answer']);
        $this->_testFor_string_length( 778 );
        $this->_check_db( $db_config );
    }

}

define_test_suite( __FILE__ );
?>
