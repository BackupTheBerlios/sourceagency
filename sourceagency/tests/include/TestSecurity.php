<?php
// TestSecurity.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestSecurity.php,v 1.2 2001/10/31 12:23:16 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( 'box.inc' );
    $bx = new box;
    include_once( 'session.inc' );
    $sess = new session;
} 

include_once( 'security.inc' );

class UnitTestSecurity
extends TestCase
{
    function UnitTestSecurity( $name ) {
        $this->TestCase( $name );
    }

    function testIs_sponsor() {
        global $auth;

        $user1 = "fubar";
        $user2 = "snafu";
        $user3 = "fritz";

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 4 );
        $db_q = array( // Arg: 1=user name
                       0 => ("SELECT * FROM auth_user WHERE perms='sponsor' "
                             . "AND username='%s'"));
        // Database instances:
        //    0 created by fubar (user1)
        //    1 created by snafu (user2)
        //    2 created by fritz (user3) but has no query
        //    3 created by the unsetting of $auth
        $db_config->add_query( sprintf( $db_q[0], $user1 ), 0 ); 
        $db_config->add_query( sprintf( $db_q[0], $user2 ), 1 ); 

        $db_config->add_num_row( 0, 0 ); // fubar is not sponsor
        $db_config->add_num_row( 1, 1 ); // snafu is sponsor

        //
        // fubar
        //
        $auth->set_uname( $user1 );
        $auth->set_perm( "Hell Yes!" );
        $this->assertEquals( 0, is_sponsor() );
        //
        // snafu
        //
        $auth->set_uname( $user2 );
        $auth->set_perm( "Hell Yes!" );
        $this->assertEquals( 1, is_sponsor() );
        
        //
        // fritz
        //
        $auth->set_uname( $user3 );
        $auth->set_perm( "" );
        $this->assertEquals( 0, is_sponsor() );
        
        //
        // no user, unset auth
        //
        unset( $auth );
        $this->assertEquals( 0, is_sponsor() );
        
        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testIs_accepted_sponsor() {
        global $auth;

        $user1 = "fubar"; $proid1 = "proid1";
        $user2 = "snafu"; $proid2 = "proid2";

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 2 );
        $db_q = array( // Arg: 1=proid, 2=sponsor name
                       0 => ("SELECT * FROM sponsoring WHERE proid='%s'"
                             . " AND status='A' AND sponsor='%s'"));

        $db_config->add_query( sprintf( $db_q[0], $proid1, $user1  ), 0 ); 
        $db_config->add_query( sprintf( $db_q[0], $proid2, $user2  ), 1 ); 
        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 1, 1 );

        //
        // user1
        //
        $auth->set_uname( $user1 );
        $auth->set_perm( "Hell Yes!" );
        $this->assertEquals( 0, is_accepted_sponsor( $proid1 ) );
        //
        // user2
        //
        $auth->set_uname( $user2 );
        $auth->set_perm( "Hell Yes!" );
        $this->assertEquals( 1, is_accepted_sponsor( $proid2 ) );

        // TODO: complete test: test unset cases: auth and 'perm'

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testIs_accepted_referee() {
        global $auth;

        $user1 = "fubar"; $proid1 = "proid1";
        $user2 = "snafu"; $proid2 = "proid2";

        //
        // TODO: do this test! (it was number 50!)
        //

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 0 );
        $db_q = array( // Arg: 1=proid, 2=sponsor name
                       0 => ("SELECT * FROM referees WHERE proid='%s' AND"
                             . " status='A' AND referee='%s'"));

        $db_config->add_query( sprintf( $db_q[0], $proid1, $user1  ), 0 ); 
        $db_config->add_query( sprintf( $db_q[0], $proid2, $user2  ), 1 ); 
        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 1, 1 );

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }
}

define_test_suite( __FILE__ );

?>
