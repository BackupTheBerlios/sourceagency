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
# $Id: TestSecurity.php,v 1.10 2002/02/07 12:24:17 riessen Exp $
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
extends UnitTest
{
    var $query;

    function UnitTestSecurity( $name ) {
        $this->query = 
             array( "is_accepted_developer" =>
                    ("SELECT * FROM developing WHERE proid='%s' AND "
                     ."status='A' AND developer='%s'"),
                    "is_main_developer" =>
                    ("SELECT * FROM configure WHERE proid='%s' AND "
                     ."developer='%s'"),
                    "is_first_sponsor_or_dev" =>
                    ("SELECT * FROM configure WHERE sponsor='%s'"),
                    "is_accepted_sponsor" =>
                    ("SELECT * FROM sponsoring WHERE proid='%s' AND "
                     . "status='A' AND sponsor='%s'"),
                    "is_accepted_referee" =>
                    ("SELECT * FROM referees WHERE proid='%s' AND status='A' "
                     . "AND referee='%s'"),
                    "is_sponsor" =>
                    ("SELECT * FROM auth_user WHERE perms LIKE '%%sponsor%%' "
                     ."AND username='%s'"),
                    "other_developing_proposals_allowed" =>
                    ("SELECT other_developing_proposals FROM configure "
                     ."WHERE proid='%s'"),
                    "no_other_specification_yet" =>
                    ("SELECT * FROM tech_content WHERE proid='%s'"),
                    "no_other_proposal_yet" =>
                    ("SELECT * FROM developing WHERE proid='%s'"));
        $this->UnitTest( $name );
    }

//      function testAllowed_actions() {
//      }
//      function testCheck_proid() {
//      }
//      function testCheck_permission() {
//      }
//      function testInvalid_project_id() {
//      }
//      function testPermission_denied() {
//      }
//      function testStep_not_open() {
//      }
//      function testProjects_only_by_project_initiator() {
//      }
//      function testProposals_only_by_project_initiator() {
//      }
//      function testIs_project_initiator() {
//      }
//      function testIs_administrator() {
//      }
//      function testIs_developer() {
//      }
//      function testIs_involved_developer() {
//      }
//      function testIs_referee() {
//      }
//      function testAlready_involved_in_this_step() {
//      }
//      function testAlready_involved_in_this_content() {
//      }
//      function testAlready_involved_message() {
//      }
//      function testSecurity_accept_by_view() {
//      }
//      function testStep5_iteration() {
//      }
//      function testStep5_not_your_iteration() {
//      }
//      function testIs_your_milestone() {
//      }
//      function testIs_milestone_possible() {
//      }
//      function testMilestone_not_possible() {
//      }

//      function testOther_specifications_allowed() {
//      }

    function testNo_other_proposal_yet() {
        $db_config = new mock_db_configure( 3 );

        $db_q = array( 0 => $this->query["no_other_proposal_yet"] );
        $db_d = $this->_generate_records( array( "proid" ), 3 );

        $db_config->add_query( sprintf( $db_q[0], $db_d[0]["proid"] ), 0 );
        $db_config->add_num_row( 0, 0 );
        $db_config->add_query( sprintf( $db_q[0], $db_d[1]["proid"] ), 1 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_query( sprintf( $db_q[0], $db_d[2]["proid"] ), 2 );
        $db_config->add_num_row( -1, 2 );

        $this->assertEquals( 1, no_other_proposal_yet($db_d[0]["proid"]));
        $this->assertEquals( 0, no_other_proposal_yet($db_d[1]["proid"]));
        $this->assertEquals( 0, no_other_proposal_yet($db_d[2]["proid"]));

        $this->_check_db( $db_config );
    }

    function testOther_developing_proposals_allowed() {
        $db_config = new mock_db_configure( 3 );
        $db_q = array( 0 =>$this->query["other_developing_proposals_allowed"]);
        $db_d=$this->_generate_records( array( "proid" ), 3 );
        $rows=$this->_generate_records(array("other_developing_proposals"),2);
        
        $db_config->add_query( sprintf( $db_q[0], $db_d[0]["proid"] ), 0 );
        $db_config->add_query( sprintf( $db_q[0], $db_d[1]["proid"] ), 1 );
        $db_config->add_query( sprintf( $db_q[0], $db_d[2]["proid"] ), 2 );

        $rows[0]["other_developing_proposals"] = "No";
        $db_config->add_record( $rows[0], 0 );
        $this->assertEquals( 0, 
                       other_developing_proposals_allowed($db_d[0]["proid"]));

        $rows[1]["other_developing_proposals"] = "Yes";
        $db_config->add_record( $rows[1], 1 );
        $this->assertEquals( 1, 
                       other_developing_proposals_allowed($db_d[1]["proid"]));

        // no data call, complains about other_developing_proposals 
        // not being set, ignore it.
        $db_config->ignore_errors( MKDB_FIELD_SET, 2 );
        $this->assertEquals( 0, 
                       other_developing_proposals_allowed($db_d[2]["proid"]));

        // if using a database, then ensure that it didn't fail
        $this->_check_db( $db_config );
    }

    function testNo_other_specification_yet() {
        $db_config = new mock_db_configure( 3 );
        $db_q = array( 0 => $this->query["no_other_specification_yet"] );

        $db_d=$this->_generate_records( array( "proid" ), 3 );
        
        $db_config->add_query( sprintf( $db_q[0], $db_d[0]["proid"] ), 0 );
        $db_config->add_num_row( 0, 0 );
        $db_config->add_query( sprintf( $db_q[0], $db_d[1]["proid"] ), 1 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_query( sprintf( $db_q[0], $db_d[2]["proid"] ), 2 );
        $db_config->add_num_row( -1, 2 );

        $this->assertEquals( 1, no_other_specification_yet($db_d[0]["proid"]));
        $this->assertEquals( 0, no_other_specification_yet($db_d[1]["proid"]));
        $this->assertEquals( 0, no_other_specification_yet($db_d[2]["proid"]));

        $this->_check_db( $db_config );
    }

    function testIs_sponsor() {
        global $auth;
        $auth = new Auth;

        $d = array( "u0"=>"fubar","p0"=>"p1","e0"=>0,
                    "u1"=>"snafu","p1"=>"p2","e1"=>1,
                    "u2"=>"user3","p2"=>"",  "e2"=>0);

        $db_config = new mock_db_configure( 4 );
        $db_q = array( // Arg: 1=user name
                       0 => $this->query["is_sponsor"]);
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
        $this->_check_db( $db_config );
    }

    function testIs_accepted_sponsor() {
        global $auth;
        $auth = new Auth;

        $d = array( "u0"=>"fubar","r0"=>"proid1","p0"=>"p1","e0"=>1,
                    "u1"=>"snafu","r1"=>"proid2","p1"=>"p2","e1"=>0,
                    "u2"=>"user3","r2"=>"proid3","p2"=>"",  "e2"=>0);
        $proid4 = "proid";

        // REFACTOR: need four instances instead of 2 because the
        // REFACTOR: instantiation of the database class happens before
        // REFACTOR: the if in is_accepted_sponsor
        $db_config = new mock_db_configure( 4 );
        $db_q = array( // Arg: 1=proid, 2=sponsor name
                       0 => $this->query["is_accepted_sponsor"]);

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
        $this->_check_db( $db_config );
    }

    function testIs_accepted_referee() {
        global $auth;
        $auth = new Auth;

        $d = array( "u0"=>"fubar","r0"=>"proid1","p0"=>"p1","e0"=>1,
                    "u1"=>"snafu","r1"=>"proid2","p1"=>"p2","e1"=>0,
                    "u2"=>"user3","r2"=>"proid3","p2"=>"",  "e2"=>0);
        $proid4 = "proid";

        // REFACTOR: need four instances instead of 2 because the
        // REFACTOR: instantiation of the database class happens before
        // REFACTOR: the if in is_accepted_referee
        $db_config = new mock_db_configure( 4 );
        $db_q = array( // Arg: 1=proid, 2=sponsor name
                       0 => $this->query["is_accepted_referee"]);

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
        $this->_check_db( $db_config );
    }

    function testIs_accepted_developer() {
        global $auth;
        $auth = new Auth;
        
        $d = array( "u0"=>"fubar","r0"=>"proid1","p0"=>"p1","e0"=>1,
                    "u1"=>"snafu","r1"=>"proid2","p1"=>"p2","e1"=>0,
                    "u2"=>"user3","r2"=>"proid3","p2"=>"",  "e2"=>0);
        $proid4 = "proid";

        // REFACTOR: 4 DB instances instead of 2 -- should move the creation
        // REFACTOR: of the DB in is_accepted_developer inside the if statement
        $db_config = new mock_db_configure( 4 );
        $db_q = array( // Arg: 1=proid, 2=developer name
                       0 => ($this->query["is_accepted_developer"]));
        
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
        $this->_check_db( $db_config );
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

        // require four instances of the DB_SourceAgency class
        $db_config = new mock_db_configure( 4 );

        $db_q = array( // Arg: 1=proid, 2=proid, 3=developer name
                       0 => $this->query["is_main_developer"],
                       1 => $this->query["is_accepted_developer"]);
        
        $db_config->add_query( sprintf( $db_q[1], $d["r0"], $d["u0"]),0);
        $db_config->add_query( sprintf( $db_q[0], $d["r0"], $d["u0"]),1);
        $db_config->add_num_row( $d["e0"], 0 );
        $db_config->add_num_row( $d["e0"], 1 );

        $db_config->add_query( sprintf( $db_q[1], $d["r1"], $d["u1"]),2);
        $db_config->add_query( sprintf( $db_q[0], $d["r1"], $d["u1"]),3);
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
        $this->_check_db( $db_config );
    }

    function testIs_first_sponsor_or_dev() {
        global $auth;
        
        $auth->set_perm( "this is the permission" );
        $auth->set_uname( "this is the user name" );

        $db_config = new mock_db_configure( 10 );

        $db_q = array( 0 => $this->query["is_main_developer"],
                       1 => $this->query["is_first_sponsor_or_dev"],
                       2 => $this->query["is_accepted_sponsor"],
                       3 => $this->query["is_accepted_developer"] );
        $args = $this->_generate_records( array("proid"), 4 );

        // case 1: is_main_developer => true
        $db_config->add_query( sprintf( $db_q[3], $args[0]["proid"],
                                        $auth->auth["uname"]), 0 );
        $db_config->add_num_row( 1, 0 );
        $db_config->add_query( sprintf( $db_q[0], $args[0]["proid"],
                                        $auth->auth["uname"]), 1 );
        $db_config->add_num_row( 1, 1 );
        $this->assertEquals( 1, is_first_sponsor_or_dev( $args[0]["proid"]));
        
        // case 2: is_main_developer => false, is_accepted_sponsor => false
        $db_config->add_query( sprintf( $db_q[3], $args[1]["proid"],
                                        $auth->auth["uname"]), 2 );
        $db_config->add_num_row( 0, 2 ); // is_main_developer fails
        $db_config->add_query( sprintf( $db_q[2], $args[1]["proid"],
                                        $auth->auth["uname"]), 3 );
        $db_config->add_num_row( 0, 3 ); // is_accepted_sponsor fails
        // FIXME: this is a bug, is_first_sponsor_or_dev should 
        // FIXME: return something
        $this->assertEquals( "", is_first_sponsor_or_dev( $args[1]["proid"]));

        // case 3: is_main_developer => false, is_accepted_sponser => true 
        // case 3: and num_rows returns value greater than zero
        $db_config->add_query( sprintf( $db_q[3], $args[2]["proid"],
                                        $auth->auth["uname"]), 4 );
        $db_config->add_num_row( 0, 4 ); // is_main_developer fails
        $db_config->add_query( sprintf( $db_q[2], $args[2]["proid"],
                                        $auth->auth["uname"]), 5 );
        $db_config->add_num_row( 1, 5 ); // is_accepted_sponsor succeeds
        $db_config->add_query( sprintf( $db_q[1], $auth->auth["uname"]), 6 );
        $db_config->add_num_row( 1, 6 ); // is_first_sponsor_or_dev succeeds

        $this->assertEquals( 1, is_first_sponsor_or_dev( $args[2]["proid"]));
        
        // case 4: is_main_developer => false, is_accepted_sponser => true 
        // case 4: and num_rows returns zero or less.
        $db_config->add_query( sprintf( $db_q[3], $args[3]["proid"],
                                        $auth->auth["uname"]), 7 );
        $db_config->add_num_row( 0, 7 ); // is_main_developer fails
        $db_config->add_query( sprintf( $db_q[2], $args[3]["proid"],
                                        $auth->auth["uname"]), 8 );
        $db_config->add_num_row( 1, 8 ); // is_accepted_sponsor succeeds
        $db_config->add_query( sprintf( $db_q[1], $auth->auth["uname"]), 9 );
        $db_config->add_num_row( 0, 9 ); // is_first_sponsor_or_dev succeeds

        $this->assertEquals( 0, is_first_sponsor_or_dev( $args[3]["proid"]));

        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
