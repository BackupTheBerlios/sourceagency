<?php
// TestHistorylib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestHistorylib.php,v 1.3 2002/06/14 09:14:12 riessen Exp $

include_once( '../constants.php' );

include_once( 'historylib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
}

class UnitTestHistorylib
extends UnitTest
{
    function UnitTestHistorylib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testBubblesort() {
        for ( $size = 1; $size <= 100; $size++ ) {
            $v = array();
            for ( $idx = 0; $idx <= $size; $idx++ ) {
                $v[$idx] = rand();
            }
            $v_sorted = $this->_copy_array( $v );
            rsort( $v_sorted );
            $this->capture_call( 'bubblesort', 0, array( &$v ) );
            $this->_compare_arrays( $v, $v_sorted );
        }
    }
    function testHistory_extract_table() {
        $this->_test_to_be_completed();
    }

    function testShow_history() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
