<?php
// TestConfigurelib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestConfigurelib.php,v 1.6 2002/06/14 09:14:12 riessen Exp $

include_once( "../constants.php" );

include_once( 'html.inc' );
include_once( 'configurelib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $GLOBALS[ 'sess' ] = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");

}

class UnitTestConfigurelib
extends UnitTest
{
    function UnitTestConfigurelib( $name ) {
        $this->UnitTest( $name );
    }

    function setup() {
    }

    function tearDown() {
    }
    
    function testSelect_quorum() {
        global $auth;

        $auth->set_uname("this is the username");
        $auth->set_perm("this is the permission");

        for ( $idx = -10; $idx < 120; $idx += 5 ) {
            $this->set_text( select_quorum( $idx ) );
            $this->set_msg( "Test $idx" );
            $this->_testFor_html_select( "quorum", 0, 0 );
            for ( $jdx = 55; $jdx < 100; $jdx += 5 ) {
              $this->_testFor_html_select_option( $jdx, $jdx==$idx, $jdx.'%');
            }
            $this->_testFor_html_select_end();
            // length various by 9 according to whether something was selected
            // or not. For values under 55 or over 95 nothing will be selected.
            $this->_testFor_string_length( ( $idx < 55 || $idx > 95 
                                                    ? 320 : 329));
        }
    }

    function testConfigure_first_time() {
        $db_config = new mock_db_configure( 3 );
        $db_q = array( 0 => ("SELECT * FROM configure WHERE proid='%s'"));
        
        $dat = $this->_generate_records( array( "proid" ), 3 );
        
        $db_config->add_query( sprintf( $db_q[0], $dat[0]["proid"]), 0 );
        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"]), 1 );
        $db_config->add_query( sprintf( $db_q[0], $dat[2]["proid"]), 2 );

        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( -1, 1 );
        $db_config->add_num_row( 1, 2 );

        $this->assertEquals( 1, configure_first_time( $dat[0]["proid"]), "1" );
        $this->assertEquals( 0, configure_first_time( $dat[1]["proid"]), "2" );
        $this->assertEquals( 0, configure_first_time( $dat[2]["proid"]), "3" );

        $this->_check_db( $db_config );
    }

    function testProject_type() {
        $db_config = new mock_db_configure( 3 );
        $db_q = array( 0 => ("SELECT perms FROM description,auth_user "
                             ."WHERE proid='%s' AND "
                             ."description_user=username"));
        
        $dat = $this->_generate_records( array( "proid" ), 3 );
        $rows = $this->_generate_records( array( "perms" ), 3 );

        $rows[0]["perms"] = "sponsor";
        $rows[1]["perms"] = "fubader";
        $rows[2]["perms"] = "devel";

        for ( $idx = 0; $idx < 3; $idx++ ) {
            $db_config->add_query( sprintf( $db_q[0], $dat[$idx]["proid"]), 
                                   $idx );
            $db_config->add_record( $rows[$idx], $idx );
        }
        
        $this->assertEquals( "sponsored",project_type( $dat[0]["proid"]),"1");
        $this->assertEquals( "developed",project_type( $dat[1]["proid"]),"2");
        $this->assertEquals( "developed",project_type( $dat[2]["proid"]),"3");

        $this->_check_db( $db_config );
    }

    function testConfigure_form() {
        $this->_test_to_be_completed();
    }
    function testConfigure_insert() {
        $this->_test_to_be_completed();
    }
    function testConfigure_modify() {
        $this->_test_to_be_completed();
    }
    function testConfigure_modify_form() {
        $this->_test_to_be_completed();
    }
    function testConfigure_preview() {
        $this->_test_to_be_completed();
    }
    function testConfigure_show() {
        $this->_test_to_be_completed();
    }
    
}

define_test_suite( __FILE__ );

?>
