<?php
// TestFaqlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestFaqlib.php,v 1.5 2002/06/26 10:29:52 riessen Exp $

include_once( '../constants.php' );

include_once( 'html.inc' );
include_once( 'faqlib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

class UnitTestFaqlib
extends UnitTest
{
    function UnitTestFaqlib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset_global( 'db', 'bx' );
    }

    function testFaqform() {
        global $sess, $bx, $t;
        global $question, $answer;

        $question = 'this is the question';
        $answer = 'this is he absewe';

        $bx = $this->_create_default_box();
        $this->capture_call( 'faqform', 2315 + strlen( $sess->self_url() ) ); 
        
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
        
        $this->capture_call( 'faqmod', 2356 + strlen( $sess->self_url() ),
                             array( &$db ) );

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

        $this->capture_call( 'faqshow', 778, array( &$db ) );

        $this->_checkFor_box_full( $t->translate('Question').'</B>: '
                             .$d[0]['question'],'<b>'.$t->translate('Answer')
                             .'</b>: '.$d[0]['answer']);
        $this->_check_db( $db_config );
    }

}

define_test_suite( __FILE__ );
?>
