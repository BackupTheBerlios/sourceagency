<?php
// TestDecisionslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestDecisionslib.php,v 1.1 2002/05/15 15:46:17 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'decisionslib.inc' );

class UnitTestDecisionslib
extends UnitTest
{
    function UnitTestDecisionslib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testAre_you_sure_message() {
        $this->_test_to_be_completed();
    }
    function testAre_you_sure_message_step5() {
        $this->_test_to_be_completed();
    }

    function testCreate_decision_link() {
        $this->_test_to_be_completed();
    }

    function testDecision_accepted_milestones() {
        $this->_test_to_be_completed();
    }

    function testDecision_developer_voted() {
        $this->_test_to_be_completed();
    }

    function testDecision_insert_main_developer() {
        $this->_test_to_be_completed();
    }

    function testDecision_milestone_insert() {
        $this->_test_to_be_completed();
    }

    function testDecisions_decision_met() {
        $this->_test_to_be_completed();
    }

    function testDecisions_decision_met_on_step5 () {
        $this->_test_to_be_completed();
    }

    function testDecisions_milestone_into_db() {
        $this->_test_to_be_completed();
    }

    function testDecisions_step5_sponsors() {
        $this->_test_to_be_completed();
    }

    function testDecisions_step5_votes() {
        $this->_test_to_be_completed();
    }

    function testProject_budget() {
        $this->_test_to_be_completed();
    }

    function testPut_decision_into_database() {
        $this->_test_to_be_completed();
    }

    function testPut_decision_step5_into_database() {
        $this->_test_to_be_completed();
    }

    function testPut_into_next_step() {
        $this->_test_to_be_completed();
    }

    function testShow_decision_consultants() {
        $this->_test_to_be_completed();
    }

    function testShow_decision_contents() {
        $this->_test_to_be_completed();
    }

    function testShow_decision_milestones() {
        $this->_test_to_be_completed();
    }

    function testShow_decision_proposals() {
        $this->_test_to_be_completed();
    }

    function testShow_decision_referees() {
        $this->_test_to_be_completed();
    }

    function testShow_decision_step5() {
        $this->_test_to_be_completed();
    }

    function testYou_have_already_voted() {
        $this->_test_to_be_completed();
    }

    function testYour_quota() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
