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
# $Id: TestSecurity.php,v 1.24 2002/06/26 10:29:52 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

include_once( 'box.inc' );
include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'security.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( 'session.inc' );
    $GLOBALS[ 'sess' ] = new session;
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
} 

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
                    ("SELECT %s FROM views WHERE proid='%s'"),
                    'allowed_actions_1' =>
                    ("SELECT consultants FROM configure WHERE proid='proid'"),
                    'allowed_actions_1b' =>
                    ("SELECT COUNT(*) FROM consultants WHERE proid='proid'"),
                    'allowed_actions_2' =>
                    ("SELECT COUNT(*) FROM tech_content WHERE proid='proid'"),
                    'allowed_actions_2b' =>
                    ("SELECT COUNT(*) FROM developing WHERE proid='proid'"),
                    'allowed_actions_3' =>
                    ("SELECT COUNT(*) FROM milestones WHERE proid='proid'"),
                    'allowed_actions_4' =>
                    ("SELECT COUNT(*) FROM referees WHERE proid='proid'")
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

        $this->set_msg( $funct );

        $this->capture_call( $funct, $len, &$dat[0] );
                                          
        $this->_test_error_box( $head_text, $body_text );
        $this->_check_db( $db_config );
    }

    function _test_error_box( $head_text, $body_text ) {
        global $t;

        $pats = array( 0 => ("<font color=\"#000000\"><b>"
                             .$t->translate($head_text)
                             ."<\/b><\/font>"), 
                       1 => ("<font color=\"#FF2020\">[ \n]+"
                             .$t->translate($body_text)."[ \n]+<\/font>") );

        $this->_testFor_patterns( $pats, 2 );
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
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_test_error_box( "Error", "No project with this id." );
        $this->_testFor_string_length( 691 );

        // second test: num_rows == 0
        capture_reset_and_start();
        $this->assertEquals( 0, check_proid( $dat[1]['proid'] ),
                             "return value test 2" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_test_error_box( "Error", "No project with this id." );
        $this->_testFor_string_length( 691 );

        // third test: status value is zero and permission editor is not set
        $GLOBALS['perm']->remove_perm( 'editor' );
        capture_reset_and_start();
        $this->assertEquals( 0, check_proid( $dat[2]['proid'] ), 
                             "return value test 3" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 3' );
        $this->_test_error_box( "Error", "Project pending for review "
                                ."by an editor" );
        $this->_testFor_string_length( 706 );

        // fourth test: status value is zero and permission editor is set
        $GLOBALS['perm']->add_perm( 'editor' );
        capture_reset_and_start();
        $this->assertEquals( 1, check_proid( $dat[3]['proid'] ), 
                             "return value test 4" );
        $this->assert( strlen( capture_stop_and_get() ) == 0, 'test 4' );

        // fifth test: status value is minus one & perm editor is not set
        $GLOBALS['perm']->remove_perm( 'editor' );
        capture_reset_and_start();
        $this->assertEquals( 0, check_proid( $dat[4]['proid'] ), 
                             "return value test 5" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 5' );
        $this->_test_error_box( "Error", "Project was not accepted." );
        $this->_testFor_string_length( 692 );

        // sixth test: status value is minus one & perm editor is set
        $GLOBALS['perm']->add_perm( 'editor' );
        capture_reset_and_start();
        $this->assertEquals( 1, check_proid( $dat[5]['proid'] ), 
                             "return value test 6" );
        $this->assert( strlen( capture_stop_and_get() ) == 0, 'test 6' );

        // seventh test: status value is one, num_rows > 0, proid is not empty
        capture_reset_and_start();
        $this->assertEquals( 1, check_proid( $dat[6]['proid'] ), 
                             "return value test 7" );
        $this->assert( strlen( capture_stop_and_get() ) == 0, 'test 7' );
        
        // check database object
        $this->_check_db( $db_config );
    }
    function testAllowed_actions() {
        global $t, $g_step_explanation, $g_step_text;

        // some common patterns
        $p_i = 'src="images\/ic\/%s.png"';
        $p_i_g = 'src="images\/ic\/%sgrey.png"';
        $p_f_g = '<font color="#CCCCCC">';
        $p_l = '<a href="step%s.php3[?]proid=proid">';
        $p_exp = ( "return ('<i>'.\$t->translate( \$g_step_explanation[%s] )"
                   .".'<\\/i>');" );
        $p_text = ( "return ('&nbsp;'.\$t->translate( \$g_step_text[%s] )"
                    .".'<\/');");

        $db_config = new mock_db_configure( 24 );
        
        //************************************************************
        //******* tests 1 to 9: test action number = 0 and 1 *********
        //************************************************************
        // test one: project status = 0, action_number = 0, no consultants
        $db_config->add_query($this->query['allowed_actions_1'], 0 );
        $rows=$this->_generate_records(array("consultants"), 1 );
        $rows[0]["consultants"] = "No";
        $db_config->add_record( $rows[0], 0 );
        capture_reset_and_start();
        allowed_actions( 0, 0, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 185 );
        $ps=array(0=>sprintf( $p_i_g, '1' ),
                  1=>$p_f_g,
                  2=>eval( sprintf( $p_exp, "1" ) ),
                  3=>eval( sprintf( $p_text, "1" )));
        $this->_testFor_patterns( $ps, 4 );
        $text_0_0 = $this->get_text();

        // test two: project status = -1, action number = 0, no consultants
        $db_config->add_query($this->query['allowed_actions_1'], 1 );
        $rows[0]["consultants"] = "No";
        $db_config->add_record( $rows[0], 1 );
        capture_reset_and_start();
        allowed_actions( -1, 0, "proid" );
        $this->assertEquals( $text_0_0, capture_stop_and_get(), "test 2" );

        // test three: project status = 0, action number = 1, no consultants
        $db_config->add_query($this->query['allowed_actions_1'], 2 );
        $rows[0]["consultants"] = "No";
        $db_config->add_record( $rows[0], 2 );
        capture_reset_and_start();
        allowed_actions( 0, 1, "proid" );
        $this->assertEquals( $text_0_0, capture_stop_and_get(), "test 3" );

        // test four: project status = 0, action number = 1, yes consultants
        $db_config->add_query($this->query['allowed_actions_1'], 3 );
        $rows[0]["consultants"] = "Yes";
        $db_config->add_record( $rows[0], 3 );
        $db_config->add_query( $this->query['allowed_actions_1b'], 3 );
        $row0=$this->_generate_records(array("COUNT(*)"), 1 );
        $db_config->add_record( $row0[0], 3 );
        capture_reset_and_start();
        allowed_actions( 0, 1, "proid" );
        $this->assertEquals( $text_0_0, capture_stop_and_get(), "test 4" );

        // test five: project status = 1, action_number = 1, no consultants
        $db_config->add_query($this->query['allowed_actions_1'], 4 );
        $rows[0]["consultants"] = "No";
        $db_config->add_record( $rows[0], 4 );
        capture_reset_and_start();
        allowed_actions( 1, 1, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 5' );
        $this->_testFor_string_length( 249 );
        $ps[0] = sprintf( $p_i, "1" );
        $ps[1] = sprintf( $p_l, "1" );
        $ps[4] = $t->translate('this project is configured to have '
                               .'<b>no<\/b> consultants');
        $this->_testFor_patterns( $ps, 5 );
        $text_1_1 = $this->get_text();

        // test six: project status = 2, action num = 1, no consultants
        $db_config->add_query($this->query['allowed_actions_1'], 5 );
        $rows[0]["consultants"] = "No";
        $db_config->add_record( $rows[0], 5 );
        capture_reset_and_start();
        allowed_actions( 2, 1, "proid" );
        $this->assertEquals( $text_1_1, capture_stop_and_get(), "test 6" );

        // test seven: project status = 1, action num = 1, yes consultants
        $db_config->add_query($this->query['allowed_actions_1'], 6 );
        $rows[0]["consultants"] = "Yes";
        $db_config->add_record( $rows[0], 6 );
        $db_config->add_query( $this->query['allowed_actions_1b'], 6 );
        $row0=$this->_generate_records(array("COUNT(*)"), 1 );
        $db_config->add_record( $row0[0], 6 );
        capture_reset_and_start();
        allowed_actions( 1, 1, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 7' );
        $ps[4] = ( '<B>COUNT[(][*][)]_0<\/B> '
                   .$t->translate('consultant offerings') );
        $this->_testFor_patterns( $ps, 5 );
        $this->_testFor_string_length( 231 );
        $text_1_1_yes = $this->get_text();

        // test eight: project status = 2, action num = 1, yes consultants
        $db_config->add_query($this->query['allowed_actions_1'], 7 );
        $rows[0]["consultants"] = "Yes";
        $db_config->add_record( $rows[0], 7 );
        $db_config->add_query( $this->query['allowed_actions_1b'], 7 );
        $row0=$this->_generate_records(array("COUNT(*)"), 1 );
        $db_config->add_record( $row0[0], 7 );
        capture_reset_and_start();
        allowed_actions( 2, 1, "proid" );
        $this->assertEquals( $text_1_1_yes, capture_stop_and_get(), "test 8" );

        // test nine: project status = 2, action num = 0, yes consultants
        $db_config->add_query($this->query['allowed_actions_1'], 8 );
        $rows[0]["consultants"] = "Yes";
        $db_config->add_record( $rows[0], 8 );
        $db_config->add_query( $this->query['allowed_actions_1b'], 8 );
        $row0=$this->_generate_records(array("COUNT(*)"), 1 );
        $db_config->add_record( $row0[0], 8 );
        capture_reset_and_start();
        allowed_actions( 2, 0, "proid" );
        $this->assertEquals( $text_1_1_yes, capture_stop_and_get(), "test 9" );

        //************************************************************
        //********** tests 10 to 12: test action number = 2 **********
        //************************************************************
        // test 10: project status 1, action number 2
        $db_config->add_query($this->query['allowed_actions_2'], 9 );
        $rows=$this->_generate_records(array("COUNT(*)"), 2 );
        $db_config->add_record( $rows[0], 9 );
        $db_config->add_query( $this->query['allowed_actions_2b'], 9 );
        $db_config->add_record( $rows[1], 9 );
        capture_reset_and_start();
        allowed_actions( 1, 2, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 10' );
        $ps=array(0=>sprintf( $p_i_g, '2' ),
                  1=>$p_f_g,
                  2=>eval( sprintf( $p_exp, "2" ) ),
                  3=>eval( sprintf( $p_text, "2" )));
        $this->_testFor_patterns( $ps, 4 );
        $this->_testFor_string_length( 265 );

        // test 11: project status 2, action number 2
        $db_config->add_query($this->query['allowed_actions_2'], 10 );
        $rows=$this->_generate_records(array("COUNT(*)"), 2 );
        $db_config->add_record( $rows[0], 10 );
        $db_config->add_query( $this->query['allowed_actions_2b'], 10 );
        $db_config->add_record( $rows[1], 10 );
        capture_reset_and_start();
        allowed_actions( 2, 2, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 11' );
        $ps[0] = sprintf( $p_i, "2" );
        $ps[1] = sprintf( $p_l, "2" );
        $ps[4] = ( '<B>COUNT[(][*][)]_0<\/B> '
                   .$t->translate('suggested project contents').', '
                   .'<B>COUNT[(][*][)]_1<\/B> '
                   .$t->translate('developing proposals') );
        $this->_testFor_patterns( $ps, 5 );
        $this->_testFor_string_length( 358 );
        $text_2_2 = $this->get_text();

        // test 12: project status 3, action number 2
        $db_config->add_query($this->query['allowed_actions_2'], 11 );
        $rows=$this->_generate_records(array("COUNT(*)"), 2 );
        $db_config->add_record( $rows[0], 11 );
        $db_config->add_query( $this->query['allowed_actions_2b'], 11 );
        $db_config->add_record( $rows[1], 11 );
        capture_reset_and_start();
        allowed_actions( 3, 2, "proid" );
        $this->assertEquals( $text_2_2, capture_stop_and_get(), "test 12" );

        //************************************************************
        //********** tests 13 to 15: test action number = 3 **********
        //************************************************************
        // test 13: project status 2, action number 3
        $db_config->add_query($this->query['allowed_actions_3'], 12 );
        $rows=$this->_generate_records(array("COUNT(*)"), 2 );
        $db_config->add_record( $rows[0], 12 );
        capture_reset_and_start();
        allowed_actions( 2, 3, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 13' );
        $ps=array(0=>sprintf( $p_i_g, '3' ),
                  1=>$p_f_g,
                  2=>eval( sprintf( $p_exp, "3" ) ),
                  3=>eval( sprintf( $p_text, "3" )));
        $this->_testFor_patterns( $ps, 4 );
        $this->_testFor_string_length( 175 );

        // test 14: project status 3, action number 3
        $db_config->add_query($this->query['allowed_actions_3'], 13 );
        $rows = $this->_generate_records(array("COUNT(*)"), 1 );
        $db_config->add_record( $rows[0], 13 );
        capture_reset_and_start();
        allowed_actions( 3, 3, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 14' );
        $ps[0] = sprintf( $p_i, '3' );
        $ps[1] = sprintf( $p_l, '3' );
        $ps[4] = "<B>COUNT[(][*][)]_0<\/B> "
           .$t->translate("suggested milestones");
        $this->_testFor_patterns( $ps, 5 );
        $this->_testFor_string_length( 222 );
        $text_3_3 = $this->get_text();

        // test 15: project status 4, action number 3
        $db_config->add_query($this->query['allowed_actions_3'], 14 );
        $rows = $this->_generate_records(array("COUNT(*)"), 1 );
        $db_config->add_record( $rows[0], 14 );
        capture_reset_and_start();
        allowed_actions( 4, 3, "proid" );
        $this->assertEquals( $text_3_3, capture_stop_and_get(), "test 15" );

        //************************************************************
        //********** tests 16 to 18: test action number = 4 **********
        //************************************************************
        // test 16: project status 3, action number 4
        $db_config->add_query($this->query['allowed_actions_4'], 15 );
        $rows=$this->_generate_records(array("COUNT(*)"), 1 );
        $db_config->add_record( $rows[0], 15 );
        capture_reset_and_start();
        allowed_actions( 3, 4, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 16' );
        $ps=array(0=>sprintf( $p_i_g, '4' ),
                  1=>$p_f_g,
                  2=>eval( sprintf( $p_exp, "4" ) ),
                  3=>eval( sprintf( $p_text, "4" )));
        $this->_testFor_patterns( $ps, 4 );
        $this->_testFor_string_length( 225 );

        // test 17: project status 4, action number 4
        $db_config->add_query($this->query['allowed_actions_4'], 16 );
        $rows=$this->_generate_records(array("COUNT(*)"), 1 );
        $db_config->add_record( $rows[0], 16 );
        capture_reset_and_start();
        allowed_actions( 4, 4, "proid" );
        $this->set_text( capture_stop_and_get() );
        $ps[0] = sprintf( $p_i, "4" );
        $ps[1] = sprintf( $p_l, "4" );
        $ps[4] = "<B>COUNT[(][*][)]_0<\/B> ".$t->translate("referees offered");
        $this->_testFor_patterns( $ps, 5 );
        $this->_testFor_string_length( 268 );
        $text_4_4 = $this->get_text();

        // test 18: project status 5, action number 4
        $db_config->add_query($this->query['allowed_actions_4'], 17 );
        $rows=$this->_generate_records(array("COUNT(*)"), 1 );
        $db_config->add_record( $rows[0], 17 );
        capture_reset_and_start();
        allowed_actions( 5, 4, "proid" );
        $this->assertEquals( $text_4_4, capture_stop_and_get(), "test 18" );

        //************************************************************
        //********** tests 19 to 21: test action number = 5 **********
        //************************************************************
        // test 19: project status 4, action number 5
        capture_reset_and_start();
        allowed_actions( 4, 5, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 19' );
        $ps=array(0=>sprintf( $p_i_g, '5' ),
                  1=>$p_f_g,
                  2=>eval( sprintf( $p_exp, "5" ) ),
                  3=>eval( sprintf( $p_text, "5" )));
        $this->_testFor_patterns( $ps, 4 );
        $this->_testFor_string_length( 208 );

        // test 20: project status 5, action number 5
        capture_reset_and_start();
        allowed_actions( 5, 5, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 20' );
        $ps[0] = sprintf( $p_i, "5" );
        $ps[1] = sprintf( $p_l, "5" );
        $ps[4] = ( "<B>x<\/B> ".$t->translate("milestones of")
                   ." <b>x<\/b> ".$t->translate("total milestones fulfilled"));
        $this->_testFor_patterns( $ps, 5 );
        $this->_testFor_string_length( 275 );
        $text_5_5 = $this->get_text();

        // test 21: project status 6, action number 5
        capture_reset_and_start();
        allowed_actions( 6, 5, "proid" );
        $this->assertEquals( $text_5_5, capture_stop_and_get(), "test 21" );

        //************************************************************
        //********** tests 22 to 24: test action number = 6 **********
        //************************************************************
        // test 22: project status 5, action number 6
        capture_reset_and_start();
        allowed_actions( 5, 6, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 22' );
        $ps=array(0=>sprintf( $p_i_g, '6' ),
                  1=>$p_f_g,
                  2=>eval( sprintf( $p_exp, "6" ) ),
                  3=>eval( sprintf( $p_text, "6" )));
        $this->_testFor_patterns( $ps, 4 );
        $this->_testFor_string_length( 174 );

        // test 23: project status 6, action number 6
        capture_reset_and_start();
        allowed_actions( 6, 6, "proid" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 23' );
        $ps[0] = sprintf( $p_i, "6" );
        $ps[1] = sprintf( $p_l, "6" );
        $ps[4] = ( "<br>" );
        $this->_testFor_string_length( 179 );
        $this->_testFor_patterns( $ps, 5 );
        $text_6_6 = $this->get_text();

        // test 24: project status 7, action number 6
        capture_reset_and_start();
        allowed_actions( 7, 6, "proid" );
        $this->assertEquals( $text_6_6, capture_stop_and_get(), "test 24" );

        $this->_check_db( $db_config );
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
        global $auth;

        $db_config = new mock_db_configure( 23 );

        $db_q=array(0=>$this->query['security_accept_by_view']);

        $args=$this->_generate_records( array("proid", "page" ), 11 );
        $rows=$this->_generate_records( array("news"), 1 );

        $args[0]["page"] = "news_edit";
        $args[1]["page"] = "news_mod";
        $args[2]["page"] = "comments_edit";

        $rows[0]["news"] = "Everybody";

        // test one: page news_edit becomes news, DB instance: 0
        $db_config->add_query( sprintf( $db_q[0], "news",$args[0]["proid"]),0);
        $db_config->add_record( $rows[0], 0 );
        $this->assertEquals( 1, security_accept_by_view( $args[0]["proid"],
                                        $args[0]["page"]), "test 1" );
        
        // test two: page news_mod becomes news, DB instance: 1
        $db_config->add_query( sprintf( $db_q[0], "news",$args[1]["proid"]),1);
        $db_config->add_record( $rows[0], 1 );
        $this->assertEquals( 1, security_accept_by_view( $args[1]["proid"],
                                        $args[1]["page"]), "test 2");

        // test three: page comments_edit becomes comments, DB instance: 2
        $rows=$this->_generate_records( array("comments"), 1 );
        $rows[0]["comments"] = "Everybody";
        $db_config->add_query( sprintf( $db_q[0], "comments",
                                        $args[2]["proid"]),2);
        $db_config->add_record( $rows[0], 2 );
        $this->assertEquals( 1, security_accept_by_view( $args[2]["proid"],
                                        $args[2]["page"]), "test 3" );

        auth_set();
        $GLOBALS['auth']->set_perm( 'fubar' );
        $GLOBALS['auth']->set_uname( 'username' );
        
        //
        // what we test is that all or calls are made to the sub-conditions,
        // i.e. is_XXXXX(). These are normalled OR'ed together, and
        // what we do is make sure that all calls return false and therefore
        // we ensure that all calls are made. 
        //
        // test four: case Developers, DB instances: 3,4,5
        // is_developer call, returns 0
        $db_config->add_query( "SELECT * FROM auth_user WHERE perms LIKE "
                               ."'%devel%' AND username='username'", 4 );
        $db_config->add_num_row( 0, 4 );
        // is_accepted_sponsor call, returns 0
        $db_config->add_query( "SELECT * FROM sponsoring WHERE proid="
                               ."'proid_3' AND status='A' AND "
                               ."sponsor='username'", 5 );
        $db_config->add_num_row( 0, 5 );
        
        $rows=$this->_generate_records( array($args[3]["page"]), 1 );
        $rows[0][$args[3]["page"]] = "Developers";
        $db_config->add_query( sprintf( $db_q[0], $args[3]["page"],
                                        $args[3]["proid"]),3);
        $db_config->add_record( $rows[0], 3 );
        $this->assertEquals( 0, security_accept_by_view( $args[3]["proid"],
                                        $args[3]["page"]), "test 4" );
        
        // test five: case Sponsors, DB instances 6,7,8
        // is_sponser call returns 0
        $db_config->add_query( "SELECT * FROM auth_user WHERE perms LIKE "
                               ."'%sponsor%' AND username='username'", 7 );
        $db_config->add_num_row( 0, 7 );
        // is_involved_developer call returns 0
        $db_config->add_query( "SELECT * FROM developing WHERE proid="
                               ."'proid_4' AND developer='username'", 8 );
        $db_config->add_num_row( 0, 8 );

        $rows=$this->_generate_records( array($args[4]["page"]), 1 );
        $rows[0][$args[4]["page"]] = "Sponsors";
        $db_config->add_query( sprintf( $db_q[0], $args[4]["page"],
                                        $args[4]["proid"]), 6);
        $db_config->add_record( $rows[0], 6 );
        $this->assertEquals( 0, security_accept_by_view( $args[4]["proid"],
                                        $args[4]["page"]), "test 5" );
        
        // test six: case "Project Participants", DB instances: 9,10,11,12
        // is_involved_developer returns 0
        $db_config->add_query( "SELECT * FROM developing WHERE proid="
                               ."'proid_5' AND developer='username'", 10 );
        $db_config->add_num_row( 0, 10 );
        // is_accepted_sponsor returns 0
        $db_config->add_query( "SELECT * FROM sponsoring WHERE proid="
                               ."'proid_5' AND status='A' AND sponsor="
                               ."'username'", 11 );
        $db_config->add_num_row( 0, 11 );
        // is_project_initiator returns 0
        $db_config->add_query( "SELECT * FROM description WHERE proid="
                               ."'proid_5' AND description_user="
                               ."'username'", 12 );
        $db_config->add_num_row( 0, 12 );

        $rows=$this->_generate_records( array($args[5]["page"]), 1 );
        $rows[0][$args[5]["page"]] = "Project Participants";
        $db_config->add_query( sprintf( $db_q[0], $args[5]["page"],
                                        $args[5]["proid"]), 9);
        $db_config->add_record( $rows[0], 9 );
        $this->assertEquals( 0, security_accept_by_view( $args[5]["proid"],
                                        $args[5]["page"]), "test 6" );

        // test seven: case "Project Developers", DB instance 13, 14
        // is_involved_developer returns 0
        $db_config->add_query( "SELECT * FROM developing WHERE proid="
                               ."'proid_6' AND developer='username'", 14 );
        $db_config->add_num_row( 0, 14 );

        $rows=$this->_generate_records( array($args[6]["page"]), 1 );
        $rows[0][$args[6]["page"]] = "Project Developers";
        $db_config->add_query( sprintf( $db_q[0], $args[6]["page"],
                                        $args[6]["proid"]), 13);
        $db_config->add_record( $rows[0], 13 );
        $this->assertEquals( 0, security_accept_by_view( $args[6]["proid"],
                                        $args[6]["page"]), "test 7" );

        // test eight: case "Project Sponsors", DB instance: 15, 16
        // is_accepted_sponsor returns 0
        $db_config->add_query( "SELECT * FROM sponsoring WHERE proid="
                               ."'proid_7' AND status='A' AND sponsor="
                               ."'username'", 16 );
        $db_config->add_num_row( 0, 16 );

        $rows=$this->_generate_records( array($args[7]["page"]), 1 );
        $rows[0][$args[7]["page"]] = "Project Sponsors";
        $db_config->add_query( sprintf( $db_q[0], $args[7]["page"],
                                        $args[7]["proid"]), 15);
        $db_config->add_record( $rows[0], 15 );
        $this->assertEquals( 0, security_accept_by_view( $args[7]["proid"],
                                        $args[7]["page"]), "test 8" );
        
        // test nine: case "Project Initiator", DB instances: 17, 18
        $db_config->add_query( "SELECT * FROM description WHERE proid="
                               ."'proid_8' AND description_user="
                               ."'username'", 18 );
        $db_config->add_num_row( 0, 18 );

        $rows=$this->_generate_records( array($args[8]["page"]), 1 );
        $rows[0][$args[8]["page"]] = "Project Initiator";
        $db_config->add_query( sprintf( $db_q[0], $args[8]["page"],
                                        $args[8]["proid"]), 17);
        $db_config->add_record( $rows[0], 17 );
        $this->assertEquals( 0, security_accept_by_view( $args[8]["proid"],
                                        $args[8]["page"]), "test 9" );

        // test ten: case "Registered", returns 1, DB instance 19
        $rows=$this->_generate_records( array($args[9]["page"]), 1 );
        $rows[0][$args[9]["page"]] = "Registered";
        $db_config->add_query( sprintf( $db_q[0], $args[9]["page"],
                                        $args[9]["proid"]), 19);
        $db_config->add_record( $rows[0], 19 );
        $this->assertEquals( 1, security_accept_by_view( $args[9]["proid"],
                                        $args[9]["page"]), "test 10" );

        // test eleven: case "Registered", returns 0, DB instances: 20
        $GLOBALS['auth']->set_perm( 'devel_pending' );
        $db_config->add_query("SELECT * FROM auth_user WHERE perms LIKE "
                              ."'%devel%' AND username='username'", 21 );
        $db_config->add_num_row( 0, 21 );
        $db_config->add_query( "SELECT * FROM sponsoring WHERE proid="
                               ."'proid_10' AND status='A' AND sponsor="
                               ."'username'", 22 );
        $db_config->add_num_row( 0, 22 );

        $rows=$this->_generate_records( array($args[10]["page"]), 1 );
        $rows[0][$args[10]["page"]] = "Registered";
        $db_config->add_query( sprintf( $db_q[0], $args[10]["page"],
                                        $args[10]["proid"]), 20);
        $db_config->add_record( $rows[0], 20 );
        $this->assertEquals( 0, security_accept_by_view( $args[10]["proid"],
                                        $args[10]["page"]), "test 11" );

        $this->_check_db( $db_config );
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
        global $t;

        $db_config = new mock_db_configure( 14 );
        
        for ( $idx = 0; $idx < 14; $idx+=2 ) {
            // query required for top_bar(...)
            $db_config->add_query("SELECT * FROM description WHERE "
                                  ."proid='proid'", $idx);
            $db_config->add_num_row( 0, $idx ); // invalid project id

            // query required for step5_iteration(...)
            $db_config->add_query( "SELECT milestone_number,iteration FROM "
                                   ."follow_up WHERE proid='proid'", $idx+1 );
            $db_config->add_num_row( 1, $idx+1 );

            $rows=$this->_generate_records(array("milestone_number",
                                                 "iteration"), 1);
            $rows[0]["milestone_number"] = 1;
            $rows[0]["iteration"] = $idx/2;
            $db_config->add_record( $rows[0], $idx+1 );
        }

        $ps=array( 0=>'<b>'.$t->translate('Not your turn').'<\/b>',1=>"");
        $p = '<font color="#FF2020">[ \n]*%s[ \n]*<\/font>';

        // step5_iteration(..) returns 0
        capture_reset_and_start();
        $this->assertEquals( 0, step5_not_your_iteration( "proid", "page" ),
                             "test 1" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $ps[1] = sprintf( $p, $t->translate("The milestone has not "
                                            ."been posted by the developer"));
        $this->_testFor_patterns( $ps, 2 );
        $this->_testFor_string_length( 2978 );

        // step5_iteration(..) returns 1
        capture_reset_and_start();
        $this->assertEquals( 0, step5_not_your_iteration( "proid", "page" ),
                             "test 2" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $ps[1] = sprintf( $p, $t->translate('The milestone has been posted. '
                                            .'Sponsors are studying whether '
                                            .'to accept it or not.'));
        $this->_testFor_patterns( $ps, 2 );
        $this->_testFor_string_length( 3009 );

        // step5_iteration(..) returns 2
        capture_reset_and_start();
        $this->assertEquals( 0, step5_not_your_iteration( "proid", "page" ),
                             "test 3" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 3' );
        $ps[1] = sprintf( $p, $t->translate('Sponsors have rejected the '
                                            .'current milestone. The referee '
                                            .'is studying it.'));
        $this->_testFor_patterns( $ps, 2 );
        $this->_testFor_string_length( 3001 );

        // step5_iteration(..) returns 3
        capture_reset_and_start();
        $this->assertEquals( 0, step5_not_your_iteration( "proid", "page" ),
                             "test 4" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 4' );
        $ps[1] = sprintf( $p, $t->translate('The referee has decided that '
                                            .'the milestone posted by the '
                                            .'developer does not fulfill '
                                            .'the promised goals. Sponsors '
                                            .'are deciding what is going to '
                                            .'happen to the project'));
        $this->_testFor_patterns( $ps, 2 );
        $this->_testFor_string_length( 3092 );

        // step5_iteration(..) returns 4
        capture_reset_and_start();
        $this->assertEquals( 0, step5_not_your_iteration( "proid", "page" ),
                             "test 5" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 5' );
        $ps[1] = sprintf( $p, $t->translate('Unknown iteration'));
        $this->_testFor_patterns( $ps, 2);
        $this->_testFor_string_length( 2945 );

        // step5_iteration(..) returns 5
        capture_reset_and_start();
        $this->assertEquals( 0, step5_not_your_iteration( "proid", "page" ),
                             "test 6" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 6' );
        $ps[1] = sprintf( $p, $t->translate('The follow_up process is '
                                            .'finished'));
        $this->_testFor_patterns( $ps, 2 );
        $this->_testFor_string_length( 2961 );

        // step5_iteration(..) returns 6
        capture_reset_and_start();
        $this->assertEquals( 0, step5_not_your_iteration( "proid", "page" ),
                             "test 7" );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 7' );
        $ps[1] = sprintf( $p, $t->translate('Unknown iteration'));
        $this->_testFor_patterns( $ps, 2 );
        $this->_testFor_string_length( 2945 );
        
        $this->_check_db( $db_config );
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
        $this->set_text( capture_stop_and_get() );
        
        $ps=array(0=>$t->translate('Milestone not possible'),
                  1=>$t->translate('Your milestones already reach 100%. '
                                   .'You should modify your existing '
                                   .'milestones before creating a new one.'));

        $this->_testFor_patterns( $ps, 2 );
        $this->_testFor_string_length( 3245 );
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
                                 "Index was " . $idx, "Test $idx" );
        }

        // unset auth and check again.
        unset( $GLOBALS['auth'] );
        $this->assertEquals(true, isset($auth),'Line: '.__LINE__ );
        $this->assertEquals(false, isset($GLOBALS['auth']),'Line: '.__LINE__ );
        $this->assertEquals( 0,is_main_developer($proid4),'Line: '.__LINE__);

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

    function testGenerate_failed_box() {
        $this->_test_to_be_completed();
    }

    function testGenerate_permission_denied_box() {
        $this->_test_to_be_completed();
    }
}

define_test_suite( __FILE__ );

?>
