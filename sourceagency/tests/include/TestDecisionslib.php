<?php
// TestDecisionslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestDecisionslib.php,v 1.3 2002/06/06 09:31:37 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'decisionslib.inc' );

class UnitTestDecisionslib
extends UnitTest
{
    var $queries;

    function UnitTestDecisionslib( $name ) {
        $this->UnitTest( $name );
        
        $this->queries =
             array( 'project_budget' =>
                    ("SELECT SUM(budget) FROM sponsoring WHERE proid='%s' "
                     ."AND status='A'"),
                    'your_quota' =>
                    ("SELECT budget FROM sponsoring WHERE proid='%s' AND "
                     ."status='A' AND sponsor='%s'"),
                    'you_have_already_voted' =>
                    ("SELECT * FROM decisions WHERE proid='%s' AND step='%s' "
                     ."AND decision_user='%s'"),
                    'decisions_milestone_into_db_select' =>
                    ("SELECT creation,release FROM milestones WHERE proid='%s'"
                     ." AND devid='%s' AND number='%s'"),
                    'decisions_milestone_into_db_update' =>
                    ("UPDATE milestones SET status='%s',creation='%s',"
                     ."release='%s' WHERE proid='%s' AND devid='%s' AND "
                     ."number='%s'"));
    }
    
    function setup() {
    }
    function tearDown() {
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
    }

    function testAre_you_sure_message() {
        global $bx, $t, $sess;
        $proid = 'this is the proid';
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        are_you_sure_message( $proid );
        $this->set_text( capture_stop_and_get() );

        $this->_checkFor_a_box( 'Warning! The next step has been reached' );
        $this->_checkFor_a_form( 'PHP_SELF', array('proid'=>$proid) );
        $this->_checkFor_columns( 2 );
        $str = ("Are you sure you want to put the project into the next step?"
                ."<br>Press <b>Yes</b> to put into the next step and <b>No"
                ."</b> to stay in the current one.");
        $str = $t->translate( $str );
        $this->_checkFor_column_values( array( $str ) );
        $this->_testFor_html_form_submit($t->translate('Yes'),'Yes');
        $this->_testFor_html_form_submit($t->translate('No'),'No');
        $this->_testFor_string_length( 1500 + strlen( $sess->self_url() ));
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
        $db_config = new mock_db_configure( 1 );
        $qs=array(0 => $this->queries['decisions_milestone_into_db_select'],
                  1 => $this->queries['decisions_milestone_into_db_update']);
        $args=$this->_generate_records( array( 'proid','devid','number',
                                               'status' ), 1 );
        $d = $this->_generate_records(array( 'creation','release'), 1 );
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid'],
                                   $args[0]['devid'], $args[0]['number']), 0 );
        $db_config->add_record( $d[0], 0 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['status'], 
                       $d[0]['creation'], $d[0]['release'], $args[0]['proid'], 
                       $args[0]['devid'], $args[0]['number']), 0 );

        capture_reset_and_start();
        call_user_func_array( 'decisions_milestone_into_db', $args[0] );
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_string_length( 0 );
        $this->_check_db( $db_config );
    }

    function testDecisions_step5_sponsors() {
        $this->_test_to_be_completed();
    }

    function testDecisions_step5_votes() {
        $this->_test_to_be_completed();
    }

    function testProject_budget() {
        global $db;
        $db_config = new mock_db_configure( 1 );
        
        $proid = 'this is teh proid';
        $dat=$this->_generate_records( array('SUM(budget)'), 1 );

        $q = $this->queries[ 'project_budget' ];

        $db_config->add_query( sprintf( $q, $proid ), 0 );
        $db_config->add_record( $dat[0], 0 );
        
        $db = new DB_SourceAgency;
        
        $this->assertEquals( $dat[0]['SUM(budget)'], project_budget( $proid ));
        
        $this->_check_db( $db_config );
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
        global $db, $auth, $t;
        $db_config = new mock_db_configure( 3 );
        $auth->set_uname( 'this si the username' );
        $q=$this->queries['you_have_already_voted'];
        $args=$this->_generate_records( array( 'proid','step' ), 3 );
        for ( $idx = 0; $idx < count( $args ); $idx++ ) {
            $db_config->add_query( sprintf( $q, $args[$idx]['proid'],
                            $args[$idx]['step'], $auth->auth['uname']), $idx );
            $db_config->add_num_row( $idx, $idx );
        }

        $voted = $t->translate("You <b>have already</b> voted in this step.")
             ."\n";
        $not_voted = $t->translate("You have <b>not voted</b> in "
                                   ."this step yet.")."\n";

        // test one: num rows returns zero
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        call_user_func_array( 'you_have_already_voted', $args[0] );
        $this->assertEquals( $not_voted, capture_stop_and_get(), "test 1" );

        // test two: num rows returns 1
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        call_user_func_array( 'you_have_already_voted', $args[1] );
        $this->assertEquals( $voted, capture_stop_and_get(), "test 2" );

        // FIXME: this is a error: num_rows == 2 and the function tells
        // FIXME: us that we haven't voted ....
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        call_user_func_array( 'you_have_already_voted', $args[2] );
        $this->assertEquals( $not_voted, capture_stop_and_get(), "test 3" );

        $this->_check_db( $db_config );
    }

    function testYour_quota() {
        global $db, $auth;
        
        $db_config = new mock_db_configure( 2 );
        $auth->set_uname( 'this is the username' );
        $proid = 'this is the projd';
        $qs = array( 0 => $this->queries[ 'your_quota' ],
                     1 => $this->queries[ 'project_budget' ] );
        $d = $this->_generate_records( array( "budget" ), 2 );
        $d2 = $this->_generate_records( array( "SUM(budget)" ), 2 );

        $d[0]['budget'] = 10000;
        $d2[0]['SUM(budget)'] = 3234;
        $d[1]['budget'] = 10000;
        $d2[1]['SUM(budget)'] = 0; /* divide by zero error */

        $db_config->add_query(sprintf($qs[0],$proid,$auth->auth['uname']),0);
        $db_config->add_query(sprintf($qs[1],$proid),0);
        $db_config->add_record( $d[0], 0 );
        $db_config->add_record( $d2[0], 0 );
        $db_config->add_query(sprintf($qs[0],$proid,$auth->auth['uname']),1);
        $db_config->add_query(sprintf($qs[1],$proid),1);
        $db_config->add_record( $d[1], 1 );
        $db_config->add_record( $d2[1], 1 );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        your_quota( $proid );
        $this->set_text( capture_stop_and_get() );

        $this->_testFor_string_length( 79 );
        
        $str = ( "<p>Your quota: <b>".$d[0]['budget']."</b> euros (<b>"
                 .(round(($d[0]['budget']/$d2[0]['SUM(budget)'])*10000)/100)
                 ."%</b> of the total project budget)\n" );
        $this->assertEquals( $str, $this->get_text() );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        your_quota( $proid );
        $this->set_text( capture_stop_and_get() );

        $search=array( "/.* in <b>/s", "/<\/b> on .*/s" );
        $replace=array( "", "" );
        $file = preg_replace( $search, $replace, $this->get_text() );

        $ps = array( 0 => '<b>Warning<\/b>:  Division by zero in <b>',
                     1 => ( '<p>Your quota: <b>'.$d[1]['budget']
                            .'<\/b> euros [(]<b>0%<\/b> of the total' ) );

        $this->_testFor_patterns( $ps, 2 );
        $this->_testFor_string_length( 151 + strlen( $file ));

        $this->_check_db( $db_config );
    }

}

define_test_suite( __FILE__ );
?>
