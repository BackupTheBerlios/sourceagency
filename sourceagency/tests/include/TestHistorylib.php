<?php
// TestHistorylib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestHistorylib.php,v 1.2 2002/06/06 14:27:33 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'historylib.inc' );

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

    function _compare_arrays( &$a, &$b ) {
        reset( $a );
        reset( $b );
        $this->assertEquals( count($a),count($b), "Array Size Mismatch" );
        for ( $idx = 0; $idx < min( count( $a ), count( $b ) ); $idx++ ) {
            $this->assertEquals($a[$idx], $b[$idx],"Compare Array idx=$idx");
        }
    }
    function &_copy_array( &$a ) {
        $b = array( );
        for ( $idx = 0; $idx < count( $a ); $idx++ ) {
            $b[$idx] = $a[$idx];
        }
        return $b;
    }
    function testBubblesort() {
        for ( $size = 1; $size <= 100; $size++ ) {
            $v = array();
            for ( $idx = 0; $idx <= $size; $idx++ ) {
                $v[$idx] = rand();
            }
            $v_sorted = $this->_copy_array( $v );
            rsort( $v_sorted );
            capture_reset_and_start();
            bubblesort( $v );
            $this->assertEquals(0,strlen(capture_stop_and_get()),
                                "test $idx: ".capture_text_get());
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
