<?php
// TestRatingslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestRatingslib.php,v 1.3 2002/05/31 12:41:50 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'ratingslib.inc' );

class UnitTestRatingslib
extends UnitTest
{
    function UnitTestRatingslib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testRatings_form() {
        $this->_test_to_be_completed();
    }

    function testRatings_form_empty() {
        $this->_test_to_be_completed();
    }

    function testRatings_form_finish() {
        global $sess, $t;

        $proid = "this is the proid";
        capture_reset_and_start();
        ratings_form_finish( $proid );
        $this->set_text( capture_stop_and_get() );

        $this->__checkFor_a_form( 'PHP_SELF', array('proid' => $proid) );
        $this->__testFor_html_form_hidden( 'dev_or_spo', '' );
        $this->__testFor_html_form_hidden( 'id_number', '' );
        $this->__testFor_html_form_submit( $t->translate('Rating finished'),
                                                                   'finished');
        $this->_testFor_string_length( 233 + strlen( $sess->self_url() ) );
        
    }

    function testRatings_form_full() {
        $this->_test_to_be_completed();
    }

    function testRatings_form_rated() {
        $this->_test_to_be_completed();
    }

    function testRatings_in_history() {
        $this->_test_to_be_completed();
    }

    function testRatings_insert() {
        $this->_test_to_be_completed();
    }

    function testRatings_look_for_first_one() {
        $this->_test_to_be_completed();
    }

    function testRatings_look_for_next_one() {
        $this->_test_to_be_completed();
    }

    function testRatings_rated_yet() {
        $args=$this->_generate_records(array('proid', 'to_whom', 'by_whom'),3);

        $db_config = new mock_db_configure( 3 );

        $q = "SELECT * FROM ratings WHERE proid='%s' AND to_whom='%s' "
             ."AND by_whom='%s'";
        
        for ( $idx = 0; $idx < 3; $idx++ ) {
            $db_config->add_query( sprintf( $q, $args[$idx]['proid'],
                        $args[$idx]['to_whom'],$args[$idx]['by_whom']), $idx );
            $db_config->add_num_row( $idx-1, $idx );
            $r = ( $idx > 1 || $idx < 1 ? 1 : 0 ); 
            $this->assertEquals( $r, call_user_func_array('ratings_rated_yet',
                                                          $args[$idx]));
        }

        $this->_check_db( $db_config );
    }

    function testShow_participants_rating() {
        $this->_test_to_be_completed();
    }

    function testShow_personal_rating() {
        $this->_test_to_be_completed();
    }
}

define_test_suite( __FILE__ );
?>
