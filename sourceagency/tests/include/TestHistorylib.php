<?php
// TestHistorylib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestHistorylib.php,v 1.4 2002/07/02 10:40:59 riessen Exp $

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
        global $db;
        global $history, $i, $proid, $what, $who, $subject;

        $q = "SELECT * FROM %s WHERE proid='%s'";
        $q2 = "SELECT developer FROM developing WHERE developing.devid='%s'";
        
        $i = -1;
        $proid = 'this os the proid';
        $what = array();
        $who = array();
        $subject = array();
        $history = array();
        $db_config = new mock_db_configure( 4 );
        $args=$this->_generate_records(array('table_name','table_creation',
                                             'table_user','table_subject'),3);
        
        // test one
        $db_config->add_query( sprintf($q,$args[0]['table_name'],$proid),0);
        $db_config->add_record( false, 0 );
        $db = new DB_SourceAgency;
        $this->capture_call( 'history_extract_table', 0, $args[0] );
        $this->assertEquals( 0, count( $history ) );
        
        // test two
        $db_config->add_query( sprintf($q, $args[1]['table_name'],$proid),1);
        $d=$this->_generate_array( array( $args[1]['table_creation'],
                                          $args[1]['table_user'], 
                                          $args[1]['table_subject']), 1 );
        $db_config->add_record( $d, 1 );
        $db_config->add_record( false, 1 );

        $db = new DB_SourceAgency;
        $this->capture_call( 'history_extract_table', 0, $args[1] );
        
        $key = substr($history[0], -9);
        $this->assertEquals( $d[$args[1]['table_creation']], $history[0] );
        $this->assertEquals( $d[$args[1]['table_user']], $who[$key] );
        $this->assertEquals( $d[$args[1]['table_subject']], $subject[$key] );
        $this->assertEquals( $args[1]['table_name'], $what[$key] );
        
        // test three
        $i = -1;
        $proid = 'this os the proid';
        $what = array();
        $who = array();
        $subject = array();
        $history = array();

        $args[2]['table_user'] = 'milestone_user';
        $db_config->add_query( sprintf($q, $args[2]['table_name'],$proid),2);
        $d=$this->_generate_array( array( $args[2]['table_creation'],
                                          $args[2]['table_user'], 
                                          $args[2]['table_subject'],
                                          'devid'), 2 );
        $db_config->add_record( $d, 2 );
        $db_config->add_record( false, 2 );

        $db_config->add_query( sprintf( $q2, $d['devid']), 3 );
        $d2 = $this->_generate_array( array('developer' ), 1 );
        $db_config->add_record( $d2, 3 );

        $db = new DB_SourceAgency;
        $this->capture_call( 'history_extract_table', 0, $args[2] );

        $key = substr($history[0], -9);
        $this->assertEquals( $d[$args[2]['table_creation']], $history[0] );
        $this->assertEquals( $d2['developer'], $who[$key] );
        $this->assertEquals( $d[$args[2]['table_subject']], $subject[$key] );
        $this->assertEquals( $args[2]['table_name'], $what[$key] );
        
        $this->_check_db( $db_config );
    }

    function testShow_history() {
        global $bx, $t;
        global $what, $who, $subject;
        
        $history = array( "abc__key01__", "def__key02__" );
        $what = array( '__key01__' => 'key one what',
                       '__key02__' => 'key two what');
        $who = array(  '__key01__' => 'key one who',
                       '__key02__' => 'key two who');
        $subject = array(  '__key01__' => 'key one subject',
                           '__key02__' => 'key two subject');

        $bx = $this->_create_default_box();
        $this->capture_call( 'show_history', 2888, array( $history ) );

        $this->_checkFor_a_box( 'Project History' );

        $titles = array( 'Date', 'Type', 'User' );
        $this->_checkFor_column_titles( $titles, '', '', '', '<b>%s</b>' );

        foreach ( array( '__key01__', '__key02__' ) as $key ) {
            $this->_testFor_box_column( '', '', '#FFFFFF', "<b>"
                                        .$what[$key] ."</b>" );
            $this->_testFor_box_column( '', '', '#FFFFFF', "<b>"
                                        .$subject[$key] ."</b>" );
            $this->_testFor_box_column( '', '', '#FFFFFF', "<b>"
                                        .$who[$key] ."</b>" );
        }
        
    }

}

define_test_suite( __FILE__ );
?>
