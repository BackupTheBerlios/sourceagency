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
# $Id: TestSecurity.php,v 1.18 2002/05/13 10:29:01 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( 'box.inc' );
    $bx = new box;
    include_once( 'session.inc' );
    $sess = new session;
    include_once( "translation.inc" );
    $t = new translation("English");
} 

include_once( 'lib.inc' );
include_once( 'html.inc' );
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
                    "_check_permission" =>
                    ("SELECT * FROM auth_user WHERE perms LIKE '%%%s%%' "
                     ."AND username='%s'"),
                    "other_developing_proposals_allowed" =>
                    ("SELECT other_developing_proposals FROM configure "
                     ."WHERE proid='%s'"),
                    "no_other_specification_yet" =>
                    ("SELECT * FROM tech_content WHERE proid='%s'"),
                    "no_other_proposal_yet" =>
                    ("SELECT * FROM developing WHERE proid='%s'"),
                    "valid_proid" =>
                    ("SELECT * FROM description WHERE proid='%s'"),
                    "check_proid" =>
                    ("SELECT * FROM description WHERE proid='%s'"),
                    "is_project_initiator" =>
                    ("SELECT * FROM description WHERE proid='%s' "
                     ."AND description_user='%s'"),
                    'is_involved_developer' =>
                    ("SELECT * FROM developing WHERE proid='%s' "
                     ."AND developer='%s'"),
                    'is_referee' =>
                    ("SELECT * FROM referees WHERE proid='%s' "
                     ."AND referee='%s'"),
                    'is_your_milestone' =>
                    ("SELECT * FROM developing WHERE proid='%s' AND "
                     ."developer='%s'"),
                    'is_milestone_possible' =>
                    ("SELECT SUM(payment) FROM milestones,developing "
                     ."WHERE developing.proid='%s' AND "
                     ."milestones.devid=developing.devid AND "
                     ."developer='%s'"),
                    'already_involved_in_this_step' =>
                    ("SELECT * FROM %s WHERE proid='%s' AND %s='%s'"),
                    'already_involved_in_this_content' =>
                    ("SELECT * FROM developing WHERE proid='%s' AND "
                     ."developer='%s' AND content_id='%s'"),
                    'step5_iteration' =>
                    ("SELECT milestone_number,iteration FROM follow_up "
                     ."WHERE proid='%s'"),
                    'other_specifications_allowed' =>
                    ("SELECT other_tech_contents FROM configure WHERE "
                     ."proid='%s'"),
                    'security_accept_by_view' =>
                    ("SELECT %s FROM views WHERE proid='%s'")
                 );
        $this->UnitTest( $name );
    }

    function setup() {
        auth_set();
        perm_set();
    }

    function _test_error_message_boxes( $funct, $head_text, 
                                        $body_text, $len ) {
        global $t;

        $db_config = new mock_db_configure( 1 );

        $db_q = array( 0 => $this->query["valid_proid"] );
        $dat = $this->_generate_records( array( "proid", "page" ), 3 );

        $db_config->add_query( sprintf( $db_q[0], $dat[0]["proid"] ), 0 );
        $db_config->add_num_row( 0, 0 );

        capture_reset_and_start();
        $funct( $dat[0]["proid"], $dat[0]["page"] );
        $text = capture_stop_and_get();

        $this->_testFor_captured_length( $len );
                                          
        $this->_test_error_box( $text, $head_text, $body_text, $funct );
        $this->_check_db( $db_config );
    }

    function _test_error_box( $text, $head_text, $body_text, $msg ) {
        global $t;

        $pats = array( 0 => ("<font color=\"#000000\"><b>"
                             .$t->translate($head_text)
                             ."<\/b><\/font>"), 
                       1 => ("<font color=\"#FF2020\">[ \n]+"
                             .$t->translate($body_text)."[ \n]+<\/font>") );

        $this->_testFor_patterns( $text, $pats, 2, $msg );
    }

    function testInvalid_project_id() {
        $this->_test_error_message_boxes( "invalid_project_id", 
                                          "Permission denied",
                                          "Project does not exist",
                                          2959 );
    }
    function testPermission_denied() {
        $this->_test_error_message_boxes( "permission_denied", 
                                          "Permission denied",
                                          "You do not have rights to enter "
                                          ."this page.",
                                          2979 );
    }
    function testStep_not_open() {
        $this->_test_error_message_boxes( "step_not_open", 
                                          "Permission denied",
                                          "This action can not be made "
                                          ."at this time.",
                                          2978 );
    }
    function testProjects_only_by_project_initiator() {
        $this->_test_error_message_boxes( "projects_only_by_project_initiator",
                                          "Permission denied",
                                          "The project has been configured "
                                          . "so that "
                                          . "only the project initiator "
                                          . "can post one "
                                          . "specification.",
                                          3031 );
    }
    function testProposals_only_by_project_initiator() {
        $this->_test_error_message_boxes("proposals_only_by_project_initiator",
                                         "Permission denied",
                                         "The project has been configured "
                                         ."so that "
                                         ."only the project initiator "
                                         ."can post one "
                                         ."developing proposal.",
                                         3037 );
    }
    function testAlready_involved_message() {
        $this->_test_error_message_boxes("already_involved_message",
                                         "Permission denied",
                                         "You are not allowed to make "
                                         ."this action "
                                         ."more than one time.",
                                         2996 );
    }

    function testCheck_proid() {
        global $t;

        $db_config = new mock_db_configure( 7 );
        
        $db_q = array( 0 => $this->query['check_proid']);
        
        $dat = $this->_generate_records( array( "proid" ), 7 );
        $rows = $this->_generate_records( array( "status" ), 7 );

        $dat[0]["proid"] = '';   // first test
        $rows[2]["status"] = 0;  // third test
        $rows[3]["status"] = 0;  // fourth test
        $rows[4]["status"] = -1; // fifth test
        $rows[5]["status"] = -1; // sixth test
        $rows[6]["status"] = 1;  // seventh test

        for ( $idx = 0; $idx < 7; $idx++ ) {
            $db_config->add_query(sprintf($db_q[0],$dat[$idx]["proid"]),$idx);
            $db_config->add_record( $rows[$idx], $idx );
            $db_config->add_num_row( ($idx != 1 ? 1 : 0), $idx );
        }

        // first test: proid is empty
        capture_reset_and_start();
        $this->assertEquals( 0, check_proid( $dat[0]['proid'] ),
                             "return value test 1" );
        $text = capture_stop_and_get();
        $this->_test_error_box( $text, "Error", "No project with this id.",
                                "test 1" );
        $this->_testFor_captured_length( 691, "test 1" );

        // second test: num_rows == 0
        capture_reset_and_start();
        $this->assertEquals( 0, check_proid( $dat[1]['proid'] ),
                             "return value test 2" );
        $text = capture_stop_and_get();
        $this->_test_error_box( $text, "Error", "No project with this id.",
                                "test 2" );
        $this->_testFor_captured_length( 691, "test 2" );

        // third test: status value is zero and permission editor is not set
        $GLOBALS['perm']->remove_perm( 'editor' );
        capture_reset_and_start();
        $this->assertEquals( 0, check_proid( $dat[2]['proid'] ), 
                             "return value test 3" );
        $text = capture_stop_and_get();
        $this->_test_error_box( $text, "Error", "Project pending for review "
                                ."by an editor", "test 3" );
        $this->_testFor_captured_length( 706, "test 3" );

        // fourth test: status value is zero and permission editor is set
        $GLOBALS['perm']->add_perm( 'editor' );
        capture_reset_and_start();
        $this->assertEquals( 1, check_proid( $dat[3]['proid'] ), 
                             "return value test 4" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0, "test 4" );

        // fifth test: status value is minus one & perm editor is not set
        $GLOBALS['perm']->remove_perm( 'editor' );
        capture_reset_and_start();
        $this->assertEquals( 0, check_proid( $dat[4]['proid'] ), 
                             "return value test 5" );
        $text = capture_stop_and_get();
        $this->_test_error_box( $text, "Error", "Project was not accepted.",
                                "test 5" );
        $this->_testFor_captured_length( 692, "test 5" );

        // sixth test: status value is minus one & perm editor is set
        $GLOBALS['perm']->add_perm( 'editor' );
        capture_reset_and_start();
        $this->assertEquals( 1, check_proid( $dat[5]['proid'] ), 
                             "return value test 6" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0, "test 6" );

        // seventh test: status value is one, num_rows > 0, proid is not empty
        capture_reset_and_start();
        $this->assertEquals( 1, check_proid( $dat[6]['proid'] ), 
                             "return value test 7" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0, "test 7" );
        
        // check database object
        $this->_check_db( $db_config );
    }
    function testAllowed_actions() {
        $this->_test_to_be_completed();
    }
    function testCheck_permission() {
        $this->_test_to_be_completed();
    }

    // this is a general function for testing some of the is_...() functions
    // in security. They only differ in their queries, otherwise their
    // identical hence we can nearly write identical tests for them.
    function _test_is_something( $funct, $query = false, $proid_dat = false ){

        $db_config = new mock_db_configure( 2 );
        $db_q = array( 0 => $this->query[ $query ? $query : $funct ] );
        $dat = $this->_generate_records( array("proid", "uname" ), 4 );

        if ( $proid_dat ) {
            $dat[2]["proid"] = $proid_dat[0];
            $dat[3]["proid"] = $proid_dat[1];
        }

        // first test: auth is not set
        auth_unset();
        $this->assertEquals(0,$funct($dat[0]["proid"]),"test 1");

        // second test: auth is set but does not have perm set
        auth_set();
        $GLOBALS['auth']->unset_perm();
        $this->assertEquals(0,$funct($dat[1]["proid"]),"test 2");
        
        // third test: auth is set, has perm set but num_rows == 0
        $GLOBALS['auth']->set_perm( "this is not empty" );
        $GLOBALS['auth']->set_uname( $dat[2]["uname"] );
        $db_config->add_num_row( 0, 0 );
        $db_config->add_query( sprintf( $db_q[0], $dat[2]["proid"],
                                        $dat[2]["uname"]), 0 );
        $this->assertEquals(0,$funct($dat[2]["proid"]),"test 3");

        // fourth test: auth is set, has perm set, and num_rows > 0
        $GLOBALS['auth']->set_perm( "this is not empty" );
        $GLOBALS['auth']->set_uname( $dat[3]["uname"] );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_query( sprintf( $db_q[0], $dat[3]["proid"],
                                        $dat[3]["uname"]), 1 );
        $this->assertEquals(1,$funct($dat[3]["proid"]),"test 4");

        // check database objects
        $this->_check_db( $db_config );
    }

    function testIs_project_initiator() {
        $this->_test_is_something( 'is_project_initiator' );
    }

    function testIs_administrator() {
        $this->_test_is_something( 'is_administrator', '_check_permission',
                                   array(0=>'admin',1=>'admin'));
    }
    function testIs_developer() {
        $this->_test_is_something( 'is_developer', '_check_permission',
                                   array(0=>'devel',1=>'devel'));
    }
    function testIs_involved_developer() {
        $this->_test_is_something( 'is_involved_developer' );
    }
    function testIs_referee() {
        $this->_test_is_something( 'is_referee' );
    }
    function testAlready_involved_in_this_step() {
        
        $db_config = new mock_db_configure( 8 ); // 4 pages, 2 cases each
        $db_q=array(0=>$this->query['already_involved_in_this_step']);
        
        $pages=array( 0=>"sponsoring_edit", 1=>"step1_edit",
                      2=>"developing_edit", 3=>"step4_edit" );
        $tables=array( 0=>"sponsoring", 1=>"consultants",
                       2=>"developing", 3=>"referees" );
        $who = array( 0=>"sponsor", 1=>"consultant",
                      2=>"developer", 3=>"referee" );

        // first test: unknown page
        $this->assertEquals(0,already_involved_in_this_step('','',''),
                            "test 1");

        // tests 1 to 9: check each page in turn ...
        for ( $page_idx = 0; $page_idx < 4; $page_idx++ ) {
            for ( $jdx = 0; $jdx < 2; $jdx++ ) {
                $db_config->add_query( sprintf($db_q[0], $tables[$page_idx], 
                                               'p', $who[$page_idx], 'u'), 
                                      ($page_idx*2) + $jdx );
                $db_config->add_num_row( $jdx, ($page_idx*2) + $jdx );
     
                $this->assertEquals( $jdx, 
                      already_involved_in_this_step('p',$pages[$page_idx],'u'),
                      "PageIdx = $page_idx, Jdx = $jdx");
                
            }
        }
        $this->_check_db( $db_config );
    }
    function testAlready_involved_in_this_content() {

        $db_config = new mock_db_configure( 2 );
        $db_q=array(0=>$this->query['already_involved_in_this_content']);
        $dat=$this->_generate_records( array( "proid", "page", "username",
                                              "content_id"), 2 );

        for ( $idx = 0; $idx < 2; $idx++ ) {
            $db_config->add_query(sprintf($db_q[0], $dat[$idx]["proid"],
                                          $dat[$idx]["username"], 
                                          $dat[$idx]["content_id"]), $idx );
            $db_config->add_num_row( $idx, $idx );
            $this->assertEquals( $idx, 
              already_involved_in_this_content($dat[$idx]["proid"],
                                               $dat[$idx]["page"],
                                               $dat[$idx]["username"], 
                                               $dat[$idx]["content_id"]),
              "index was $idx");
        }
        $this->_check_db( $db_config );
    }
    function testSecurity_accept_by_view() {
        $this->_test_to_be_completed();
//          $db_config = new mock_db_configure( 1 );

//          $db_q=array(0=>$this->query['security_accept_by_view']);
//          $this->_check_db( $db_config );
    }

    function testStep5_iteration() {
        $db_config = new mock_db_configure( 3 );
        $db_q=array(0=>$this->query['step5_iteration']);
        $dat=$this->_generate_records(array("proid"), 3 );
        $rows=$this->_generate_records(array("milestone_number",
                                             "iteration"), 8 );

        for ( $idx = 0; $idx < 3; $idx++ ) {
            $db_config->add_query(sprintf($db_q[0],$dat[$idx]["proid"]),$idx);
            $db_config->add_num_row( $idx*2, $idx );
        }

        // first test: no rows
        $this->assertEquals( 0, step5_iteration( $dat[0]["proid"]),"test 1");

        // second test: 2 rows: milestone_number -1 and -2
        $rows[0]["milestone_number"] = -1; $rows[0]["iteration"] = 10;
        $rows[1]["milestone_number"] = -2; $rows[1]["iteration"] = 20;
        $db_config->add_record( $rows[0], 1 );
        $db_config->add_record( $rows[1], 1 );
        $this->assertEquals( 0, step5_iteration( $dat[1]["proid"]),"test 2");
        
        // third test: 4 rows: milestone_number 2, 1, 0, -1
        $rows[2]["milestone_number"] = 2;  $rows[2]["iteration"] = 10;
        $rows[3]["milestone_number"] = 1;  $rows[3]["iteration"] = 20; 
        $rows[4]["milestone_number"] = 0;  $rows[4]["iteration"] = 30;
        $rows[5]["milestone_number"] = -1; $rows[5]["iteration"] = 40;
        $db_config->add_record( $rows[2], 2 );
        $db_config->add_record( $rows[3], 2 );
        $db_config->add_record( $rows[4], 2 );
        $db_config->add_record( $rows[5], 2 );
        $this->assertEquals( 20, step5_iteration( $dat[2]["proid"]),"test 3");

        // check the database
        $this->_check_db( $db_config );
    }

    function testStep5_not_your_iteration() {
        $this->_test_to_be_completed();
    }
    function testIs_your_milestone() {
        $this->_test_is_something( 'is_your_milestone' );
    }
    function testIs_milestone_possible() {

        $db_config = new mock_db_configure( 2 );
        $db_q=array(0=>$this->query['is_milestone_possible']);

        $dat=$this->_generate_records( array( "proid", "uname" ), 3 );
        $rows=$this->_generate_records( array( "SUM(payment)" ), 3 );

        $rows[0]["SUM(payment)"] = 99;
        $rows[1]["SUM(payment)"] = 100;

        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"],
                                        $dat[1]["uname"]), 0);
        $db_config->add_query( sprintf( $db_q[0], $dat[2]["proid"],
                                        $dat[2]["uname"]), 1);
        $db_config->add_record( $rows[0], 0 );
        $db_config->add_record( $rows[1], 1 );

        // first test: auth is not set
        auth_unset();
        $this->assertEquals( 0, is_milestone_possible($dat[0]["proid"]),
                             "test 1" );

        // second test: auth is set, sum is less than 100
        auth_set();
        $GLOBALS['auth']->set_uname( $dat[1]["uname"] );
        $this->assertEquals( 1, is_milestone_possible($dat[1]["proid"]),
                             "test 2");

        // third test: auth is set, sum is 100
        auth_set();
        $GLOBALS['auth']->set_uname( $dat[2]["uname"] );
        $this->assertEquals( 0, is_milestone_possible($dat[2]["proid"]),
                             "test 3");
        
        $this->_check_db( $db_config );
    }
    function testMilestone_not_possible() {
        global $t;

        $db_config = new mock_db_configure(1); // top_bar() uses a database
        $db_config->ignore_all_errors( 0 );

        capture_reset_and_start();
        $this->assertEquals(0, milestone_not_possible( "proid", "page" ),"rv");
        $text = capture_stop_and_get();

        $ps=array(0=>$t->translate('Milestone not possible'),
                  1=>$t->translate('Your milestones already reach 100%. '
                                   .'You should modify your existing '
                                   .'milestones before creating a new one.'));

        $this->_testFor_patterns( $text, $ps, 2 );
        $this->_testFor_captured_length( 3245 );
        $this->_check_db( $db_config );
    }

    function testOther_specifications_allowed() {
        $db_config = new mock_db_configure( 2 );
        $db_q=array(0=>$this->query['other_specifications_allowed']);

        $dat=$this->_generate_records(array("proid"),2);
        $rows=$this->_generate_records(array("other_tech_contents"),2);
        $rows[0]["other_tech_contents"] = "No";
        $rows[1]["other_tech_contents"] = "Yes";

        $db_config->add_query(sprintf($db_q[0],$dat[0]["proid"]),0);
        $db_config->add_record( $rows[0], 0 );
        $db_config->add_query(sprintf($db_q[0],$dat[1]["proid"]),1);
        $db_config->add_record( $rows[1], 1 );
        
        // first test: other_tech_contents == No
        $this->assertEquals(0,other_specifications_allowed($dat[0]["proid"]));
        // second test: other_tech_contents == Yes
        $this->assertEquals(1,other_specifications_allowed($dat[1]["proid"]));
        
        $this->_check_db( $db_config );
    }

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
        $this->_test_is_something( 'is_sponsor', '_check_permission',
                                   array( 0 => "sponsor", 1 => "sponsor" ));
    }

    function testIs_accepted_sponsor() {
        $this->_test_is_something( 'is_accepted_sponsor' );
    }

    function testIs_accepted_referee() {
        $this->_test_is_something( 'is_accepted_referee' );
    }

    function testIs_accepted_developer() {
        $this->_test_is_something( 'is_accepted_developer' );
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
