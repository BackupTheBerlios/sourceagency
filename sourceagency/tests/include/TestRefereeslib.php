<?php
// TestRefereeslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestRefereeslib.php,v 1.5 2002/07/02 10:40:59 riessen Exp $

include_once( '../constants.php' );

include_once( 'lib.inc');
include_once( 'html.inc' );
include_once( 'refereeslib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS['t'] = new translation("English");
}

class UnitTestRefereeslib
extends UnitTest
{
    var $queries = array();

    function UnitTestRefereeslib( $name ) {
        $this->UnitTest( $name );
        $this->queries =
             array( 'referees_insert' =>
                    ("INSERT referees SET proid='%s',referee='%s',status='P'"),
                    'show_referees' =>
                    ("SELECT * FROM referees,auth_user WHERE proid='%s' "
                     ."AND username=referee ORDER BY creation"));
    }
    
    function setup() {
    }
    function tearDown() {
        unset_global( 'bx', 'db' );
    }

    function testReferees_form() {
        global $bx, $t, $sess, $auth;
        
    }

    function testReferees_insert() {
        global $t, $bx, $db;

        $db_config = new mock_db_configure( 2 );

        $args = $this->_generate_records( array( 'proid','user' ), 1 );

        $q = array( 0 => $this->queries['show_referees'],
                    1 => $this->queries['referees_insert']);

        $db_config->add_query( sprintf( $q[1], $args[0]['proid'],
                                                      $args[0]['user']), 0 );
        // for the show_referees call
        $db_config->add_query( sprintf( $q[0], $args[0]['proid'] ), 0 );
        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 0, 0 );
        // this is the database created by the monitor_mail call -- ignore
        // all errors
        $db_config->ignore_all_errors( 1 );

        // one and only test ....
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        call_user_func_array( 'referees_insert', $args[0] );
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_String_length( 72 );
        $this->_check_db( $db_config );
    }

    function testShow_referees() {
        global $t, $bx, $db;
        $db_config = new mock_db_configure( 2 );

        $pid1 = "this sit he proid";
        $pid2 = "this sit he proid two";
        $q = $this->queries['show_referees'];

        $db_config->add_query( sprintf( $q, $pid1 ), 0 );
        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 0, 0 );

        $db_config->add_query( sprintf( $q, $pid2 ), 1 );
        $dat=$this->_generate_records(array('username','status','creation'),5);
        $db_config->add_num_row( count($dat), 1 );
        $db_config->add_num_row( count($dat), 1 );
        for ( $idx = 0; $idx < count( $dat ); $idx++ ) {
            $db_config->add_record( $dat[$idx], 1 );
        }

        // test one, no record/data
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        show_referees( $pid1 );
        $this->set_text( capture_stop_and_get() );
        $this->push_msg( "Test One" );
        $this->_testFor_string_length( 72 );
        $msg=$t->translate("There are no developers that have "
                           ."offered themselves as referees");
        $this->_testFor_pattern( $this->_to_regexp( $msg ) );
        $this->pop_msg();

        // test two, data is defined
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        show_referees( $pid2 );
        $this->set_text( capture_stop_and_get() );
        $this->push_msg( "Test Two" );
        $this->_testFor_string_length( 4940 );
        $msg=$t->translate("There are no developers that have "
                           ."offered themselves as referees");
        $this->reverse_next_test();
        $this->_testFor_pattern( $this->_to_regexp( $msg ) );
        
        $this->_checkFor_a_box( 'Referees' );
        $this->_checkFor_columns( 4 );
        $this->_checkFor_column_titles( array( 'Number','Username','Status',
                                               'Creation'),'','','');

        $colors = array( 0 => '#FFFFFF', 1 => 'gold' );
        for ( $idx = 0; $idx < count( $dat ); $idx++ ) {
            $v=array("<b>".($idx+1)."</b>",
                     '<b>'.lib_nick($dat[$idx]['username']).'</b>',
                     '<b>'.show_status($dat[$idx]['status']).'</b>',
                     '<b>'.timestr(mktimestamp($dat[$idx]['creation']))
                     .'</b>');
            $this->push_msg( "Test $idx" );
            $this->_checkFor_column_values( $v, '', '', $colors[$idx%2] );
            $this->pop_msg();
        }
        $this->pop_msg();

        $this->_check_db( $db_config );
    }

}

define_test_suite( __FILE__ );
?>
