<?php
// TestRatingslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestRatingslib.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

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
        $this->_test_to_be_completed();
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
        $this->_test_to_be_completed();
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
