<?php
// TestConfigurelib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestConfigurelib.php,v 1.1 2002/05/08 09:47:19 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $sess = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");

    include_once( 'html.inc' );
}

include_once( 'configurelib.inc' );

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

        $ps = array();
        for ( $idx = 55; $idx < 100; $idx += 5 ) {
            $ps[ $idx/5 - 11 ] = '<option value="'.$idx.'">'.$idx.'%';
        }
        $ps[] = '<select name="quorum" size="0">';
        $ps[] = '<\/select>';

        for ( $idx = 55; $idx < 100; $idx += 5 ) {
            $text = select_quorum( $idx );
            $jdx = $idx/5 - 11;
            $old_p = $ps[ $jdx ];
            $ps[ $jdx ] = '<option selected value="'.$idx.'">'.$idx.'%';
            $this->_testFor_patterns( $text, $ps, 11, $idx );
            $ps[ $jdx ] = $old_p;
            $this->_testFor_string_length( $text, 329, $idx );
        }

        // check some values that should not case something to be selected
        $text = select_quorum( 0 );
        $this->_testFor_patterns( $text, $ps, 11, "0" );
        $this->_testFor_string_length( $text, 320, "0" );
        $text = select_quorum( -10 );
        $this->_testFor_patterns( $text, $ps, 11, "-10" );
        $this->_testFor_string_length( $text, 320, "-10" );
        $text = select_quorum( 50 );
        $this->_testFor_patterns( $text, $ps, 11, "50" );
        $this->_testFor_string_length( $text, 320, "50" );
        $text = select_quorum( 100 );
        $this->_testFor_patterns( $text, $ps, 11, "100" );
        $this->_testFor_string_length( $text, 320, "100" );
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

        $this->assertEquals( configure_first_time( $dat[0]["proid"]), 1, "1" );
        $this->assertEquals( configure_first_time( $dat[1]["proid"]), 0, "2" );
        $this->assertEquals( configure_first_time( $dat[2]["proid"]), 0, "3" );

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
        
        $this->assertEquals( project_type( $dat[0]["proid"]),"sponsored","1");
        $this->assertEquals( project_type( $dat[1]["proid"]),"developed","2");
        $this->assertEquals( project_type( $dat[2]["proid"]),"developed","3");

        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
