<?php
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Unit test class for the functions contained in the 
# include/security.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestSecurity.php,v 1.6 2001/11/20 10:51:02 riessen Exp $
#
######################################################################

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
    var $query_is_accepted_developer = 
        "SELECT * FROM developing WHERE proid='%s' AND status='A' AND developer='%s'";

    function UnitTestSecurity( $name ) {
        $this->TestCase( $name );
    }

    function testIs_sponsor() {
        global $auth;
        $auth = new Auth;

        $d = array( "u0"=>"fubar","p0"=>"p1","e0"=>0,
                    "u1"=>"snafu","p1"=>"p2","e1"=>1,
                    "u2"=>"user3","p2"=>"",  "e2"=>0);

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 4 );
        $db_q = array( // Arg: 1=user name
                       0 => ("SELECT * FROM auth_user WHERE perms "
                             ."LIKE '%%sponsor%%' AND username='%s'"));
        // Database instances:
        //    0 created by fubar (user1)
        //    1 created by snafu (user2)
        //    2 created by fritz (user3) but has no query
        //    3 created by the unsetting of $auth
        $db_config->add_query( sprintf( $db_q[0], $d["u0"] ), 0 ); 
        $db_config->add_query( sprintf( $db_q[0], $d["u1"] ), 1 ); 

        $db_config->add_num_row( $d["e0"], 0 ); // fubar is not sponsor
        $db_config->add_num_row( $d["e1"], 1 ); // snafu is sponsor

        for ( $idx = 0; $idx < sizeof( $d )/3; $idx++ ) {
            $auth->set_uname( $d["u".$idx] );
            $auth->set_perm( $d["p".$idx] );
            $this->assertEquals($d["e".$idx],is_sponsor(),"Index was ".$idx );
        }
        
        // unset auth
        unset( $auth );
        $this->assertEquals( 0, is_sponsor() );
        
        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testIs_accepted_sponsor() {
        global $auth;
        $auth = new Auth;

        $d = array( "u0"=>"fubar","r0"=>"proid1","p0"=>"p1","e0"=>1,
                    "u1"=>"snafu","r1"=>"proid2","p1"=>"p2","e1"=>0,
                    "u2"=>"user3","r2"=>"proid3","p2"=>"",  "e2"=>0);
        $proid4 = "proid";

        $db_config = new mock_db_configure;
        // REFACTOR: need four instances instead of 2 because the
        // REFACTOR: instantiation of the database class happens before
        // REFACTOR: the if in is_accepted_sponsor
        $db_config->set_nr_instance_expected( 4 );
        $db_q = array( // Arg: 1=proid, 2=sponsor name
                       0 => ("SELECT * FROM sponsoring WHERE proid='%s'"
                             . " AND status='A' AND sponsor='%s'"));

        $db_config->add_query( sprintf( $db_q[0], $d["r0"], $d["u0"]  ), 0 ); 
        $db_config->add_query( sprintf( $db_q[0], $d["r1"], $d["u1"]  ), 1 ); 
        $db_config->add_num_row( $d["e0"], 0 );
        $db_config->add_num_row( $d["e1"], 1 );

        for ( $idx = 0; $idx < sizeof( $d )/4; $idx++ ) {
            $auth->set_uname( $d["u".$idx] );
            $auth->set_perm( $d["p".$idx] );
            $this->assertEquals( $d["e".$idx], 
                                 is_accepted_sponsor( $d["r".$idx] ), 
                                 "Index was " . $idx );
        }
        
        // unset the auth
        unset( $auth );
        $this->assertEquals( 0, is_accepted_sponsor( $proid4 ) );

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testIs_accepted_referee() {
        global $auth;
        $auth = new Auth;

        $d = array( "u0"=>"fubar","r0"=>"proid1","p0"=>"p1","e0"=>1,
                    "u1"=>"snafu","r1"=>"proid2","p1"=>"p2","e1"=>0,
                    "u2"=>"user3","r2"=>"proid3","p2"=>"",  "e2"=>0);
        $proid4 = "proid";

        $db_config = new mock_db_configure;
        // REFACTOR: need four instances instead of 2 because the
        // REFACTOR: instantiation of the database class happens before
        // REFACTOR: the if in is_accepted_referee
        $db_config->set_nr_instance_expected( 4 );
        $db_q = array( // Arg: 1=proid, 2=sponsor name
                       0 => ("SELECT * FROM referees WHERE proid='%s' AND"
                             . " status='A' AND referee='%s'"));

        $db_config->add_query( sprintf( $db_q[0], $d["r0"], $d["u0"]),0);
        $db_config->add_query( sprintf( $db_q[0], $d["r1"], $d["u1"]),1);
        $db_config->add_num_row( $d["e0"], 0 );
        $db_config->add_num_row( $d["e1"], 1 );

        for ( $idx = 0; $idx < sizeof( $d )/4; $idx++ ) {
            $auth->set_uname( $d["u".$idx] );
            $auth->set_perm( $d["p".$idx] );
            $this->assertEquals( $d["e".$idx], 
                                 is_accepted_referee( $d["r".$idx] ), 
                                 "Index was " . $idx );
        }

        // unset auth and check again.
        unset( $auth );
        $this->assertEquals( 0, is_accepted_referee( $proid4 ), __LINE__ );
        
        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testIs_accepted_developer() {
        global $auth;
        $auth = new Auth;
        
        $d = array( "u0"=>"fubar","r0"=>"proid1","p0"=>"p1","e0"=>1,
                    "u1"=>"snafu","r1"=>"proid2","p1"=>"p2","e1"=>0,
                    "u2"=>"user3","r2"=>"proid3","p2"=>"",  "e2"=>0);
        $proid4 = "proid";

        $db_config = new mock_db_configure;
        // REFACTOR: 4 DB instances instead of 2 -- should move the creation
        // REFACTOR: of the DB in is_accepted_developer inside the if statement
        $db_config->set_nr_instance_expected( 4 );
        $db_q = array( // Arg: 1=proid, 2=developer name
                       0 => ($this->query_is_accepted_developer));
        
        $db_config->add_query( sprintf( $db_q[0], $d["r0"], $d["u0"]),0);
        $db_config->add_query( sprintf( $db_q[0], $d["r1"], $d["u1"]),1);
        $db_config->add_num_row( $d["e0"], 0 );
        $db_config->add_num_row( $d["e1"], 1 );

        for ( $idx = 0; $idx < sizeof( $d )/4; $idx++ ) {
            $auth->set_uname( $d["u".$idx] );
            $auth->set_perm( $d["p".$idx] );
            $this->assertEquals( $d["e".$idx], 
                                 is_accepted_developer( $d["r".$idx] ), 
                                 "Index was " . $idx );
        }

        // unset auth and check again.
        unset( $auth );
        $this->assertEquals( 0, is_accepted_developer( $proid4 ), __LINE__ );

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testIs_main_developer() {
        // TODO: find a way to better to combine tests, i.e. this function
        // TODO: uses the is_accepted_developer and don't need to include
        // TODO: those queries in the mock database, but we have to!
        global $auth;
        $auth = new Auth;

        $d = array( "u0"=>"fubar","r0"=>"proid1","p0"=>"p1","e0"=>1,
                    "u1"=>"snafu","r1"=>"proid2","p1"=>"p2","e1"=>0,
                    "u2"=>"user3","r2"=>"proid3","p2"=>"",  "e2"=>0);
        $proid4 = "proid";

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 4 );
        $db_q = array( // Arg: 1=proid, 2=proid, 3=developer name
                       0 => ("SELECT * FROM configure WHERE proid='%s' "
                             . "AND proid='%s' AND developer='%s'"),
                       1 => ($this->query_is_accepted_developer));
        
        $db_config->add_query( sprintf( $db_q[1], $d["r0"], $d["u0"]),0);
        $db_config->add_query( sprintf( $db_q[0], $d["r0"], $d["r0"], 
                                        $d["u0"]),1);
        $db_config->add_num_row( $d["e0"], 0 );
        $db_config->add_num_row( $d["e0"], 1 );

        $db_config->add_query( sprintf( $db_q[1], $d["r1"], $d["u1"]),2);
        $db_config->add_query( sprintf( $db_q[0], $d["r1"], $d["r1"], 
                                        $d["u1"]),3);
        // is.accepted.developer returns true
        $db_config->add_num_row( 1, 2 );
        $db_config->add_num_row( $d["e1"], 3 );

        for ( $idx = 0; $idx < sizeof( $d )/4; $idx++ ) {
            $auth->set_uname( $d["u".$idx] );
            $auth->set_perm( $d["p".$idx] );
            $this->assertEquals( $d["e".$idx], 
                                 is_main_developer( $d["r".$idx] ), 
                                 "Index was " . $idx );
        }

        // unset auth and check again.
        unset( $auth );
        $this->assertEquals( false, isset( $auth ) );
        $this->assertEquals( 0, is_main_developer( $proid4 ), __LINE__ );

        // if using a database, then ensure that it didn't fail
        $this->assertEquals(false, $db_config->did_db_fail(),
                            $db_config->error_message() );
    }

    function testIs_first_sponsor_or_dev() {
//          global $auth;
//          $auth = new Auth;
        
//          $proid1 = "pro1";
//          $user1 = "fubar";
//          $perm1 = "perm1";

//          $db_config = new mock_db_configure;
//          $db_config->set_nr_instance_expected( 4 );
//          $db_q = array( // Arg: 1=user name
//                         0 => ("SELECT * FROM configure WHERE sponsor='%s'"));

//          //
//          // user1
//          //
//          $auth->set_uname( $user1 );
//          $auth->set_perm( $perm1 );
//          $this->assertEquals( 1, is_first_sponsor_or_dev( $proid1 ));

//          // if using a database, then ensure that it didn't fail
//          $this->assertEquals(false, $db_config->did_db_fail(),
//                              $db_config->error_message() );
    }
}

define_test_suite( __FILE__ );

?>
