<?php
// TestDecisionslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestDecisionslib.php,v 1.1 2003/11/21 12:56:03 helix Exp $

include_once( '../constants.php' );

include_once( 'html.inc' );
include_once( 'decisionslib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

class UnitTestDecisionslib
extends UnitTest
{
    function UnitTestDecisionslib( $name ) {
        $GLOBALS['queries'] = array ( 
            'project_budget' =>
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
             ."number='%s'"),
            'decision_accepted_milestones_1' =>
            ("SELECT number,payment FROM milestones WHERE proid='%s' "
             ."AND devid='%s'"),
            'decision_accepted_milestones_2' =>
            ("SELECT COUNT(*) FROM decisions_milestones WHERE proid="
             ."'%s' AND devid='%s' AND number='%s' AND decision='Yes'"
             ." AND decision_user='%s'"),
            'decision_milestone_insert_1' =>
            ("SELECT * FROM decisions_milestones WHERE proid='%s' "
             ."AND devid='%s' AND decision_user='%s' AND number='%s'"),
            'decision_milestone_insert_2' =>
            ("INSERT decisions_milestones SET proid='%s', devid='%s',"
             ." decision_user='%s', number='%s', decision='%s'"),
            'decision_milestone_insert_3' =>
            ("UPDATE decisions_milestones SET decision='%s' WHERE "
             ."proid='%s' AND devid='%s' AND decision_user='%s' AND "
             ."number='%s'"),
            'decisions_decision_met_1' =>
            ("SELECT status FROM description WHERE proid='%s'"),
            'decisions_decision_met_2' =>
            ("SELECT step FROM decisions WHERE proid='%s'"),
            'decisions_decision_met_3' =>
            ("SELECT consultants FROM configure WHERE proid='%s'"),
            'decisions_decision_met_on_step5' =>
            ("SELECT iteration FROM follow_up WHERE proid='%s' AND "
             ."milestone_number='%s'"),
            'decision_developer_voted_1' =>
            ("SELECT developer FROM developing WHERE proid='%s' AND "
             ."status='A'"),
            'decision_developer_voted_2' =>
            ("SELECT * FROM decisions WHERE proid='%s' AND decision="
             ."'%s' AND decision_user='%s'"),
            'decision_insert_main_developer_1' =>
            ("SELECT developer FROM configure WHERE proid='%s'"),
            'decision_insert_main_developer_2' =>
            ("SELECT developer FROM developing WHERE proid='%s' AND "
             ."status='A'"),
            'decision_insert_main_developer_3' =>
            ("UPDATE configure SET developer='%s' WHERE proid='%s'"),
            'decisions_step5_sponsors_1' =>
            ("SELECT quorum FROM configure WHERE proid='%s'"),
            'decisions_step5_sponsors_2' =>
            ("SELECT SUM(budget) FROM sponsoring,decisions_step5 "
             ."WHERE sponsor=decision_user AND decision='accept' "
             ."AND count='%s' AND sponsoring.proid='%s' "
             ."AND decisions_step5.proid='%s' AND "
             ."decisions_step5.number='%s'"),
            'decisions_step5_sponsors_3' =>
            ("UPDATE follow_up SET iteration='5' WHERE "
             ."proid='%s' AND milestone_number='%s'"),
            'decisions_step5_sponsors_4' =>
            ("SELECT MAX(number) FROM milestones WHERE proid='%s'"),
            'decisions_step5_sponsors_5' =>
            ("INSERT follow_up SET iteration='0',proid='%s',"
             ."milestone_number='%s',count='1'"),
            'decisions_step5_sponsors_6' =>
            ("UPDATE description SET status='6' WHERE proid='%s'"),
            'decisions_step5_sponsors_7' =>
            ("SELECT SUM(budget) FROM sponsoring,decisions_step5 "
             ."WHERE sponsor=decision_user AND decision='minor' "
             ."AND count='%s' AND sponsoring.proid='%s' AND "
             ."sponsoring.proid='%s' AND decisions_step5.number='%s'"),
            'decisions_step5_sponsors_8' =>
            ("SELECT * FROM follow_up WHERE proid='%s' "
             ."AND milestone_number='%s'"),
            'decisions_step5_sponsors_9' =>
            ("UPDATE follow_up SET iteration='0',count='%s',location"
             ."='' WHERE proid='%s' AND milestone_number='%s'"),
            'decisions_step5_sponsors_10' =>
            ("SELECT SUM(budget) FROM sponsoring,decisions_step5 "
             ."WHERE sponsor=decision_user AND decision='severe'  "
             ."AND count='%s' AND sponsoring.proid='%s' AND "
             ."sponsoring.proid='%s' AND decisions_step5.number='%s'"),
            'decisions_step5_sponsors_11' =>
            ("UPDATE follow_up SET iteration='2' WHERE proid='%s' AND "
             ."milestone_number='%s'"),
            'decisions_step5_votes' =>
            ("SELECT SUM(budget) AS sum_step5 FROM sponsoring,"
             ."decisions_step5 WHERE decisions_step5.proid='%s' AND "
             ."sponsoring.proid='%s' AND sponsoring.sponsor="
             ."decisions_step5.decision_user AND decisions_step5."
             ."number='%s' AND count='%s' AND decision='%s' GROUP "
             ."BY decision"),
            'put_decision_into_database_1' =>
            ("SELECT DISTINCT(%s) FROM %s"),
            'put_decision_into_database_2' =>
            ("SELECT * FROM decisions WHERE proid='%s' AND step = "
             ."'%s' AND decision_user='%s'"),
            'put_decision_into_database_3' =>
            ("INSERT decisions SET proid='%s', step ='%s', "
             ."decision_user='%s', decision='%s'"),
            'put_decision_into_database_4' =>
            ("UPDATE decisions SET  decision='%s' WHERE proid='%s' AND"
             ." step='%s' AND decision_user='%s'"),
            'monitor_mail' =>
            ("SELECT email_usr FROM auth_user,monitor WHERE "
             ."monitor.username=auth_user.username AND proid='%s' %s"),
            'put_decision_step5_into_database_1' =>
            ("SELECT * FROM decisions_step5 WHERE proid='%s' AND "
             ."decision_user='%s' AND number='%s' AND count='%s'"),
            'put_decision_step5_into_database_2' =>
            ("INSERT decisions_step5 SET proid='%s', decision_user="
             ."'%s', number='%s', count='%s', decision='%s'"),
            'put_decision_step5_into_database_3' =>
            ("UPDATE decisions_step5 SET  decision='%s' WHERE proid="
             ."'%s' AND decision_user='%s' AND number='%s' AND "
             ."count='%s'"),
            'put_into_next_step_1' =>
            ("SELECT %s,creation FROM %s WHERE proid='%s'"),
            'put_into_next_step_2' =>
            ("SELECT SUM(budget) FROM sponsoring,decisions WHERE sponsor="
             ."decision_user AND decision='%s' AND sponsoring.proid='%s' AND "
             ."decisions.proid='%s' AND step='%s'"),
            'put_into_next_step_3' =>
            ("UPDATE %s SET status='A', creation='%s' WHERE %s='%s' AND "
             ."proid='%s'"),
            'put_into_next_step_4' =>
            ("UPDATE %s SET status='R', creation='%s' WHERE %s='%s' AND "
             ."proid='%s'"),
            'put_into_next_step_5' =>
            ("SELECT status,description_creation FROM description WHERE "
             ."proid='%s'"),
            'put_into_next_step_6' =>
            ("UPDATE description SET status='%s',description_creation='%s' "
             ."WHERE proid='%s'"),
            'put_into_next_step_7' =>
            ("INSERT history SET proid='%s', history_user='Project Owners', "
             ."type='decision', action='Project is now in phase %s'"),
            'put_into_next_step_8' =>
            ("SELECT email_usr FROM auth_user,monitor WHERE monitor."
             ."username=auth_user.username AND proid='%s' AND "
             ."importance='high'"),
            'show_decision_consultants_1' =>
            ("SELECT consultants FROM configure WHERE proid='%s'"),
            'show_decision_consultants_2' =>
            ("SELECT * FROM consultants,auth_user WHERE proid='%s' AND "
             ."consultant=username ORDER BY creation"),
            'show_decision_consultants_3' =>
            ("SELECT SUM(budget) AS sum_consultant FROM sponsoring,decisions "
             ."WHERE decisions.proid='%s' AND sponsoring.proid='%s' AND "
             ."sponsoring.sponsor=decisions.decision_user AND step='1' AND "
             ."decision='%s' GROUP BY decision"),
            'show_decision_contents_1' =>
            ("SELECT * FROM tech_content,auth_user WHERE proid='%s' AND "
             ."content_user=username ORDER BY creation"),
            'show_decision_contents_2' =>
            ("SELECT SUM(budget) AS sum_content FROM sponsoring,decisions "
             ."WHERE decisions.proid='%s' AND sponsoring.proid='%s' AND "
             ."sponsoring.sponsor=decisions.decision_user AND decision='%s' "
             ."AND step='2' GROUP BY decision"),
            'show_decision_milestones_1' =>
            ("SELECT * FROM milestones WHERE proid='%s' AND devid='%s' "
             ."ORDER BY number"),
            'show_decision_milestones_2' =>
            ("SELECT SUM(budget) AS sum_milestone FROM sponsoring,"
             ."decisions_milestones WHERE decisions_milestones.proid='%s' "
             ."AND sponsoring.proid='%s' AND decisions_milestones.devid='%s' "
             ."AND sponsoring.sponsor=decisions_milestones.decision_user AND "
             ."number='%s' AND decision='Yes' GROUP BY decision"),
            'show_decision_proposals_1' =>
            ("SELECT * FROM tech_content,developing,auth_user WHERE "
             ."tech_content.proid='%s' AND developer=username AND "
             ."tech_content.content_id=developing.content_id AND "
             ."tech_content.status='A' ORDER BY developing.creation"),
            'show_decision_proposals_2' =>
            ("SELECT SUM(budget) AS sum_content FROM sponsoring,decisions "
             ."WHERE decisions.proid='%s' AND sponsoring.proid='%s' AND "
             ."sponsoring.sponsor=decisions.decision_user AND decision='%s' "
             ."AND step='3' GROUP BY decision"),
            'show_decision_referees_1' =>
            ("SELECT * FROM referees,auth_user WHERE proid='%s' AND "
             ."referee=username ORDER BY creation"),
            'show_decision_referees_2' =>
            ("SELECT SUM(budget) AS sum_referee FROM sponsoring,decisions "
             ."WHERE decisions.proid='%s' AND sponsoring.proid='%s' AND "
             ."sponsoring.sponsor=decisions.decision_user AND decision='%s' "
             ."AND step='4' GROUP BY decision"),
            'show_decision_step5' =>
            ("SELECT * FROM milestones WHERE proid='%s' AND number='%s'"),
            );
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset_global( 'db', 'bx' );
    }

    function testAre_you_sure_message() {
        global $bx, $t, $sess;
        $proid = 'this is the proid';
        $bx = $this->_create_default_box();

        capture_reset_and_start();
        are_you_sure_message( $proid );
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_string_length( 1500 + strlen( $sess->self_url() ));

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
    }

    function testAre_you_sure_message_step5() {
        global $bx, $t, $sess;
        
        $proid = 'this is the proid';
        $bx = $this->_create_default_box();
        $this->capture_call( 'are_you_sure_message_step5', 
                             1479 + strlen($sess->self_url()), array($proid));
        $this->_checkFor_a_form('PHP_SELF', array('proid'=>$proid), 'POST');
        $this->_checkFor_a_box('Warning! The decision has been made',
                               '<b>%s</b>');
        $this->_checkFor_columns( 2 );
        $this->_testFor_box_column( "left","70%","",
                                    $t->translate("Are you sure you want to "
                                                  ."decide yet?<br>Press <b>"
                                                  ."Yes</b> to put into the "
                                                  ."next step and <b>No</b> "
                                                  ."to stay in the current "
                                                  ."one."));
	$this->_testFor_box_column("right","30%","",
                                   html_form_submit("Yes","Yes")
                                   .html_form_submit("No","No"));
    }

    function testCreate_decision_link() {
        global $t;
        
        $proid = 'this is the proid';
        $this->capture_call( 'create_decision_link', 98, array( $proid ) );
        $str = "<p align=\"right\">[ <b>";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->_testFor_html_link( "decisions.php3",array("proid" => $proid),
                                   $t->translate("Decide!"));
        $str = "</b> ]\n";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
    }

    function testDecision_accepted_milestones() {
        global $auth, $queries;

        $qs=array( 1 => $queries['decision_accepted_milestones_1'],
                   2 => $queries['decision_accepted_milestones_2'] );

        $uname = 'tjhis is the username';
        $auth->set_uname( $uname );

        $db_config = new mock_db_configure( 4 );

        $args=$this->_generate_records( array( 'proid', 'devid' ), 2 );
        $dat=$this->_generate_records( array( 'payment', 'number' ), 10 );
        $dat2=$this->_generate_records( array( 'COUNT(*)' ), 10 );
        // test one
        $db_config->add_query( sprintf( $qs[1], $args[0]['proid'], 
                                                     $args[0]['devid'] ), 0 );
        $db_config->add_record( false, 0 );
        // test two
        $dat[0]['payment'] = 10; $dat2[0]['COUNT(*)'] = 3;
        $dat[1]['payment'] = 20; $dat2[1]['COUNT(*)'] = 2;
        $dat[2]['payment'] = 30; $dat2[2]['COUNT(*)'] = 1;

        $db_config->add_query( sprintf( $qs[1], $args[1]['proid'], 
                                                     $args[1]['devid'] ), 2 );
        $db_config->add_record( $dat[0], 2 );
        $db_config->add_record( $dat[1], 2 );
        $db_config->add_record( $dat[2], 2 );
        $db_config->add_record( false, 2 );
        $db_config->add_query( sprintf( $qs[2], $args[1]['proid'], 
                                                $args[1]['devid'],
                                                $dat[0]['number'], $uname), 3);
        $db_config->add_query( sprintf( $qs[2], $args[1]['proid'], 
                                                $args[1]['devid'],
                                                $dat[1]['number'], $uname), 3);
        $db_config->add_query( sprintf( $qs[2], $args[1]['proid'], 
                                                $args[1]['devid'],
                                                $dat[2]['number'], $uname), 3);
        $db_config->add_record( $dat2[0], 3 );
        $db_config->add_record( $dat2[1], 3 );
        $db_config->add_record( $dat2[2], 3 );

        // test one: no data points, nothing to do
        $rval=$this->capture_call('decision_accepted_milestones',0,$args[0]);
        $this->assertEquals( 0, $rval, "test 1" );

        // test two: three data points, return value should be ...
        $rval=$this->capture_call('decision_accepted_milestones',0,$args[1]);
        $this->assertEquals( 100, $rval, "test 1" );

        $this->_check_db( $db_config );
    }

    function testDecision_milestone_insert() {
        global $queries;
        $db_config = new mock_db_configure( 4 );
        $qs=array( 0 => $queries['decision_milestone_insert_1'],
                   1 => $queries['decision_milestone_insert_2'],
                   2 => $queries['decision_milestone_insert_3'] );
        $args=$this->_generate_records( array('proid','devid','decision_user',
                                              'number','decision'), 10 );

        // test one
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid'], 
                                 $args[0]['devid'], $args[0]['decision_user'],
                                 $args[0]['number']), 0 );
        $db_config->add_num_row( 0, 0 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['proid'], 
                                 $args[0]['devid'], $args[0]['decision_user'],
                                 $args[0]['number'],$args[0]['decision']), 0);
        // test two 
        $db_config->add_query( sprintf( $qs[0], $args[1]['proid'], 
                                 $args[1]['devid'], $args[1]['decision_user'],
                                 $args[1]['number']), 1 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_query( sprintf( $qs[2], $args[1]['decision'], 
                                 $args[1]['proid'], $args[1]['devid'],
                                 $args[1]['decision_user'],
                                 $args[1]['number']), 1);
        // test three 
        $db_config->add_query( sprintf( $qs[0], $args[2]['proid'], 
                                 $args[2]['devid'], $args[2]['decision_user'],
                                 $args[2]['number']), 2 );
        $db_config->add_num_row( -1, 2 );
        $db_config->add_query( sprintf( $qs[2], $args[2]['decision'], 
                                 $args[2]['proid'], $args[2]['devid'],
                                 $args[2]['decision_user'],
                                 $args[2]['number']), 2);
        
        // test four
        $db_config->add_query( sprintf( $qs[0], $args[3]['proid'], 
                                 $args[3]['devid'], $args[3]['decision_user'],
                                 $args[3]['number']), 3 );
        $db_config->add_num_row( 2, 3 );
        $db_config->add_query( sprintf( $qs[2], $args[3]['decision'], 
                                 $args[3]['proid'], $args[3]['devid'],
                                 $args[3]['decision_user'],
                                 $args[3]['number']), 3);

        // test one: do the insert query, num_row == 0
        $this->capture_call( 'decision_milestone_insert', 0, $args[0] );

        // test two: do the update query, num_row == 1
        $this->capture_call( 'decision_milestone_insert', 0, $args[1] );

        // test three: update query, num_row == -1
        $this->capture_call( 'decision_milestone_insert', 0, $args[2] );

        // test four: update query, num_row == 2
        $this->capture_call( 'decision_milestone_insert', 0, $args[3] );

        $this->_check_db( $db_config );
    }

    function testDecisions_decision_met() {
        global $queries;
        $db_config = new mock_db_configure( 4 );
        $qs=array( 0 => $queries['decisions_decision_met_1'],
                   1 => $queries['decisions_decision_met_2'],
                   2 => $queries['decisions_decision_met_3'] );
        $args=$this->_generate_records( array( 'proid' ), 10 );
        $d = $this->_generate_records( array( 'status' ), 10 );
        $d2 = $this->_generate_records( array( 'step' ), 10 );
        $d3 = $this->_generate_records( array( 'consultants' ), 10 );

        // test one
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid']), 0 );
        $d[0]['status'] = 0;
        $db_config->add_record( $d[0], 0 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['proid']), 0 );
        $db_config->add_record( false, 0 );

        // test two
        $db_config->add_query( sprintf( $qs[0], $args[1]['proid']), 1 );
        $d[1]['status'] = 0;
        $db_config->add_record( $d[1], 1 );
        $db_config->add_query( sprintf( $qs[1], $args[1]['proid']), 1 );
        $d2[0]['step'] = 1; 
        $d2[1]['step'] = 5; 
        $d2[2]['step'] = 10; 
        $db_config->add_record( $d2[0], 1 );
        $db_config->add_record( $d2[1], 1 );
        $db_config->add_record( $d2[2], 1 );
        $db_config->add_record( false, 1 );

        // test three
        $db_config->add_query( sprintf( $qs[0], $args[2]['proid']), 2 );
        $d[2]['status'] = 1;
        $db_config->add_record( $d[2], 2 );
        $db_config->add_query( sprintf( $qs[1], $args[2]['proid']), 2 );
        $d2[3]['step'] = 1; 
        $d2[4]['step'] = 5; 
        $d2[5]['step'] = 10; 
        $db_config->add_record( $d2[3], 2 );
        $db_config->add_record( $d2[4], 2 );
        $db_config->add_record( $d2[5], 2 );
        $db_config->add_record( false, 2 );
        $db_config->add_query( sprintf( $qs[2], $args[2]['proid']), 2 );
        $d3[0]['consultants'] = 'No';
        $db_config->add_record( $d3[0], 2 );

        // test four
        $db_config->add_query( sprintf( $qs[0], $args[3]['proid']), 3 );
        $d[3]['status'] = 1;
        $db_config->add_record( $d[3], 3 );
        $db_config->add_query( sprintf( $qs[1], $args[3]['proid']), 3 );
        $d2[6]['step'] = 1; 
        $d2[7]['step'] = 5; 
        $d2[8]['step'] = 10; 
        $db_config->add_record( $d2[6], 3 );
        $db_config->add_record( $d2[7], 3 );
        $db_config->add_record( $d2[8], 3 );
        $db_config->add_record( false, 3 );
        $db_config->add_query( sprintf( $qs[2], $args[3]['proid']), 3 );
        $d3[1]['consultants'] = 'Yes';
        $db_config->add_record( $d3[1], 3 );

        // test one: first == 0 and second == 0, return value == 1
        $this->assertEquals( 1, $this->capture_call( 'decisions_decision_met',
                                                     0, $args[0] ), 'test 1' );

        // test two: first == 0, and second == 10, return value == 0
        $this->assertEquals( 0, $this->capture_call( 'decisions_decision_met',
                                                     0, $args[1] ), 'test 2' );

        // test three: first == 1, and second == 1, return value == 1
        $this->assertEquals( 1, $this->capture_call( 'decisions_decision_met',
                                                     0, $args[2] ), 'test 3' );

        // test four: first == 1, and second == 10, return value == 0
        $this->assertEquals( 0, $this->capture_call( 'decisions_decision_met',
                                                     0, $args[3] ), 'test 4' );
        $this->_check_db( $db_config );
    }

    function testDecisions_decision_met_on_step5 () {
        global $db, $queries;

        $qs=array(0=>$queries['decisions_decision_met_on_step5']);
        $db_config= new mock_db_configure( 20 );

        $args=$this->_generate_records(array('proid','milestone_number',
                                             'count'),20);
        $d=$this->_generate_records(array('iteration'), 20 );
        
        $msgs=array( 0=>"The milestone has been <b>accepted</b>",
                     1=>("<b>Severe</b> problems have been found.<p>The "
                         ."referee has been switched to decide."),
                     2=>("<b>Minor</b> changes are required.<p>Developers have"
                         ." some days to enhance their program propperly."));
        for ( $idx = 0; $idx < 20; $idx++ ) {
            $db_config->add_query( sprintf( $qs[0], $args[$idx]['proid'],
                                      $args[$idx]['milestone_number']), $idx );
            $jdx = $idx - 10;
            $d[$idx]['iteration'] = $jdx;
            $db_config->add_record( $d[$idx], $idx );
            $db = new DB_SourceAgency;
            $this->assertEquals( $msgs[($jdx == 5 ? 0 : ($jdx == 2 ? 1 : 2))],
                       $this->capture_call( 'decisions_decision_met_on_step5',
                                            0, $args[$idx]), "test $idx");
        }
        $this->_check_db( $db_config );
    }

    function testDecisions_milestone_into_db() {
        global $queries;
        $db_config = new mock_db_configure( 1 );
        $qs=array(0 => $queries['decisions_milestone_into_db_select'],
                  1 => $queries['decisions_milestone_into_db_update']);
        $args=$this->_generate_records( array( 'proid','devid','number',
                                               'status' ), 1 );
        $d = $this->_generate_records(array( 'creation','release'), 1 );
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid'],
                                   $args[0]['devid'], $args[0]['number']), 0 );
        $db_config->add_record( $d[0], 0 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['status'], 
                       $d[0]['creation'], $d[0]['release'], $args[0]['proid'], 
                       $args[0]['devid'], $args[0]['number']), 0 );

        $this->capture_call( 'decisions_milestone_into_db', 0, $args[0] );
        $this->_check_db( $db_config );
    }

    function testProject_budget() {
        global $db, $queries;
        $db_config = new mock_db_configure( 1 );
        
        $proid = 'this is teh proid';
        $dat=$this->_generate_records( array('SUM(budget)'), 1 );

        $q = $queries[ 'project_budget' ];

        $db_config->add_query( sprintf( $q, $proid ), 0 );
        $db_config->add_record( $dat[0], 0 );
        
        $db = new DB_SourceAgency;
        
        $this->assertEquals( $dat[0]['SUM(budget)'], 
                   $this->capture_call( 'project_budget', 0, array(&$proid)));
        
        $this->_check_db( $db_config );
    }

    function testYou_have_already_voted() {
        global $db, $auth, $t, $queries;
        $db_config = new mock_db_configure( 3 );
        $auth->set_uname( 'this si the username' );
        $q=$queries['you_have_already_voted'];
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
        $this->capture_call( 'you_have_already_voted', 44, &$args[0] );
        $this->assertEquals( $not_voted, $this->get_text(), "test 1");

        // test two: num rows returns 1
        $db = new DB_SourceAgency;
        $this->capture_call( 'you_have_already_voted', 44, $args[1] );
        $this->assertEquals( $voted, $this->get_text(), "test 2" );

        // FIXME: this is a error: num_rows == 2 and the function tells
        // FIXME: us that we haven't voted ....
        $db = new DB_SourceAgency;
        $this->capture_call( 'you_have_already_voted', 44, $args[2] );
        $this->assertEquals( $not_voted, $this->get_text(), "test 3" );

        $this->_check_db( $db_config );
    }

    function testYour_quota() {
        global $db, $auth, $queries;
        
        $db_config = new mock_db_configure( 2 );
        $auth->set_uname( 'this is the username' );
        $proid = 'this is the projd';
        $qs = array( 0 => $queries[ 'your_quota' ],
                     1 => $queries[ 'project_budget' ] );
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
        $this->capture_call( 'your_quota', 79, array( &$proid ) );
        
        $str = ( "<p>Your quota: <b>".$d[0]['budget']."</b> euros (<b>"
                 .(round(($d[0]['budget']/$d2[0]['SUM(budget)'])*10000)/100)
                 ."%</b> of the total project budget)\n" );
        $this->assertEquals( $str, $this->get_text() );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        call_user_func_array( 'your_quota', array( &$proid ) );
        $this->set_text( capture_stop_and_get() );

        $this->_testFor_string_length( 74 );
        $this->assertRegexp( '/Division by zero/', $GLOBALS['err_msg']);
        $ps = array( 0 => ( '<p>Your quota: <b>'.$d[1]['budget']
                            .'<\/b> euros [(]<b>0%<\/b> of the total' ) );

        $this->_testFor_patterns( $ps, 1 );

        $this->_check_db( $db_config );
    }

    function testDecision_developer_voted() {
        global $queries;
        $db_config = new mock_db_configure( 20 );
        $qs = array( 0 => $queries['decision_developer_voted_1'],
                     1 => $queries['decision_developer_voted_2'] );
        $args = $this->_generate_records( array( 'proid', 'referee' ), 20 );
        $d = $this->_generate_records( array( 'developer' ), 20 );

        for ( $idx = 0; $idx < 20; $idx++ ) {
            $db_config->add_query(sprintf($qs[0],$args[$idx]['proid']),$idx);
            $db_config->add_record($d[$idx],$idx);
            $db_config->add_query(sprintf($qs[1],$args[$idx]['proid'],
                                          $args[$idx]['referee'],
                                          $d[$idx]['developer']),$idx);
            $jdx = $idx - 10;
            $db_config->add_num_row( $jdx, $idx );
            $this->assertEquals(($jdx == 1 ? 1 : 0),
                                $this->capture_call('decision_developer_voted',
                                                    0,$args[$idx]),
                                "test $idx");
        }
        $this->_check_db( $db_config );
    }

    function testDecision_insert_main_developer() {
        global $queries;
        $qs=array( 0 => $queries['decision_insert_main_developer_1'],
                   1 => $queries['decision_insert_main_developer_2'],
                   2 => $queries['decision_insert_main_developer_3']);

        $db_config = new mock_db_configure( 2 );
        $args=$this->_generate_records( array('proid'), 10 );
        $d=$this->_generate_records( array('developer'), 10 );

        // test one
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid']), 0);
        $db_config->add_record( $d[0], 0 );
        
        // test two
        $db_config->add_query( sprintf( $qs[0], $args[1]['proid']), 1);
        $d[1]['developer'] = '';
        $db_config->add_record( $d[1], 1 );
        $db_config->add_query( sprintf( $qs[1], $args[1]['proid']), 1);
        $db_config->add_record( $d[2], 1 );
        $db_config->add_query( sprintf( $qs[2], $d[2]['developer'],
                                                       $args[1]['proid']), 1 );
        $fname = 'decision_insert_main_developer';
        // test one: developer is not empty
        $this->assertEquals( 0, $this->capture_call( $fname, 0, $args[0]) );

        // test two: developer is empty
        $this->assertEquals( '', $this->capture_call( $fname, 0, $args[1]) );
        
        $this->_check_db( $db_config );
    }

    function testDecisions_step5_sponsors() {
        global $queries;
        // decision_step5_sponsors uses 11 queries
        $fname = 'decisions_step5_sponsors';
        $qs=array();
        for ( $idx = 0, $jdx = 1; $idx < 11; $idx++, $jdx++ ) {
            $qs[ $idx ] = $queries['decisions_step5_sponsors_'.$jdx];
        }
        $db_config = new mock_db_configure( 5 );
        $args=$this->_generate_records(array('proid','milestone_number',
                                             'count' ), 10 );
        /** decision value for each test **/
        $dv=array(  0=>0,  1=>0 , 2=>10, 3=>10, 4=>10 ); 
        /** accepted SUM(budget) for each test **/
        $asb=array( 0=>10, 1=>10, 2=>0,  3=>0,  4=>0 );
        /** minor SUM(budget) for tests that require it, i.e. asb < dv **/
        $msb=array( 0=>-1, 1=>-1, 2=>20, 3=>0,  4=>0);
        /** severe SUM(budget) for tests that require it, i.e. msb < dv **/
        $ssb=array( 0=>-1, 1=>-1, 2=>-1, 3=>0,  4=>20 );
        for ( $idx = 0; $idx < count($dv); $idx++ ) {
            $db_config->add_query( sprintf($qs[0],$args[$idx]['proid']),$idx);
            $db_config->add_record( array( 'quorum' => $dv[$idx]), $idx );

            $db_config->add_query( sprintf( $qs[1], $args[$idx]['count'],
                                   $args[$idx]['proid'], $args[$idx]['proid'],
                                   $args[$idx]['milestone_number']),$idx);
            $db_config->add_record( array('SUM(budget)' => $asb[$idx]), $idx);
            if ( $asb[$idx] <= $dv[$idx] ) {
                $db_config->add_query( sprintf( $qs[6], $args[$idx]['count'],
                                   $args[$idx]['proid'], $args[$idx]['proid'],
                                   $args[$idx]['milestone_number']),$idx);
                $db_config->add_record(array('SUM(budget)'=>$msb[$idx]),$idx);
                if ( $msb[$idx] <= $dv[$idx] ) {
                    $db_config->add_query(sprintf($qs[9],$args[$idx]['count'],
                                   $args[$idx]['proid'], $args[$idx]['proid'],
                                   $args[$idx]['milestone_number']),$idx);
                    $db_config->add_record( array('SUM(budget)' => $ssb[$idx]),
                                            $idx );
                }
            }
        }

        // test one: accepted budget is greater than decision value,
        // test one: MAX(number) does not equal milestone number
        $db_config->add_query( sprintf( $qs[2], $args[0]['proid'],
                                           $args[0]['milestone_number']),0);
        $db_config->add_query( sprintf( $qs[3], $args[0]['proid']), 0 );
        $a=array('MAX(number)' => $args[0]['milestone_number'] . "NOT EQUAL");
        $db_config->add_record( $a, 0 );
        $tmp = $args[0]['milestone_number'];
        $db_config->add_query( sprintf( $qs[4], $args[0]['proid'],++$tmp), 0);

        // test two: accepted budget is greater than decision value,
        // test two: MAX(number) does equal milestone number
        $db_config->add_query( sprintf( $qs[2], $args[1]['proid'],
                                           $args[1]['milestone_number']),1);
        $db_config->add_query( sprintf( $qs[3], $args[1]['proid']), 1 );
        $a=array('MAX(number)' => $args[1]['milestone_number'] );
        $db_config->add_record( $a, 1 );
        $db_config->add_query( sprintf( $qs[5], $args[1]['proid']), 1 );

        // test three: accepted budget is less than decision value,
        // test three: minor budget is greater than decision value
        $db_config->add_query( sprintf( $qs[7], $args[2]['proid'],
                                           $args[2]['milestone_number']), 2 );
        $db_config->add_record( array( 'count' => 11 ), 2 );
        $db_config->add_query( sprintf( $qs[8], '12', $args[2]['proid'],
                                           $args[2]['milestone_number']), 2 );

        // test four: accepted budget is less than decision value,
        // test four: minor budget is also less than decision value,
        // test four: severe budget is also less than decision value

        // test five: accepted budget is less than decision value,
        // test five: minor budget is also less than decision value,
        // test five: severe budget is greater than decision value
        $db_config->add_query( sprintf( $qs[10], $args[4]['proid'],
                                           $args[4]['milestone_number']), 4 );
        // perform tests
        for ( $idx = 0; $idx < 5; $idx++ ) {
            $this->assertEquals('',$this->capture_call($fname,0,$args[$idx]));
        }
        $this->_check_db( $db_config );
    }

    function testDecisions_step5_votes() {
        global $db, $queries;
        $fname = 'decisions_step5_votes';
        $qs=array( 0 => $queries['decisions_step5_votes'],
                   1 => $queries['project_budget'] );

        $db_config = new mock_db_configure( 8 );
        $args=$this->_generate_records( array( 'proid', 'milestone_number',
                                               'count', 'decision' ), 4 );
        for ( $idx = 0, $jdx=1; $idx < count($args); $idx++, $jdx+=2 ) {
            $db_config->add_query( sprintf( $qs[0], $args[$idx]['proid'], 
                                            $args[$idx]['proid'], 
                                            $args[$idx]['milestone_number'],
                                            $args[$idx]['count'], 
                                            $args[$idx]['decision']), $jdx);
        }

        // test one: num rows == 0 
        $db_config->add_num_row( 0, 1 );
        $db = new DB_SourceAgency;
        $this->assertEquals( 0, $this->capture_call( $fname,0,$args[0]));

        // test two: project budget == 0, division by zero warning
        $db_config->add_num_row( 1, 3 );
        $db_config->add_record( array( 'sum_step5' => -1 ), 3 );
        $db_config->add_query( sprintf( $qs[1], $args[1]['proid']), 2 );
        $db_config->add_record( array('SUM(budget)'=> 0 ), 2);
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        $this->assertEquals( 0, call_user_func_array( $fname, $args[1] ) );
        $this->set_text( capture_stop_and_get() );

        $this->_testFor_string_length( 0 );

        $this->assertRegexp( '/Division by zero/', $GLOBALS['err_msg'] );

        // test three: sum_step5 == 0
        $db_config->add_num_row( 1, 5 );
        $db_config->add_record( array( 'sum_step5' => 0 ), 5 );
        $db_config->add_query( sprintf( $qs[1], $args[2]['proid']), 4 );
        $db_config->add_record( array('SUM(budget)'=>10), 4);
        $db = new DB_SourceAgency;
        $this->assertEquals( 0, $this->capture_call( $fname,0,$args[2]));

        // test four: all clear
        $db_config->add_num_row( 1, 7 );
        $db_config->add_record( array( 'sum_step5' =>10), 7 );
        $db_config->add_query( sprintf( $qs[1], $args[3]['proid']), 6 );
        $db_config->add_record( array('SUM(budget)'=>10), 6);
        $db = new DB_SourceAgency;
        $this->assertEquals( 100, $this->capture_call( $fname,0,$args[3]));

        $this->_check_db( $db_config );
    }

    function testPut_decision_into_database() {
        global $db, $auth, $queries;

        $uname = 'this is the username';
        $auth->set_uname( $uname );
        $qs=array( 0 => $queries['put_decision_into_database_1'],
                   1 => $queries['put_decision_into_database_2'],
                   2 => $queries['put_decision_into_database_3'],
                   3 => $queries['put_decision_into_database_4'],
                   4 => $queries['monitor_mail'] );
        
        $args=$this->_generate_records( array('proid','step','your_vote',
                                              'what','table'), 10 );

        $db_config = new mock_db_configure( 10 );

        // test one: no records
        $db_config->add_query( sprintf( $qs[0], $args[0]['what'],
                                                     $args[0]['table']), 0);
        $db_config->add_record( false, 0 );
        /* monitor_mail query */
        $db_config->add_query( sprintf( $qs[4], $args[0]['proid'],''), 1);
        $db_config->add_record( false, 1 );
        $db = new DB_SourceAgency;
        $this->capture_call('put_decision_into_database',0,$args[0]);

        // test two: one records, but your_vote does not match what
        $db_config->add_query( sprintf( $qs[0], $args[1]['what'],
                                                     $args[1]['table']), 2);
        $a=array( $args[1]['what'] => $args[1]['your_vote']."NOT EQUAL" );
        $db_config->add_record( $a, 2 );
        $db_config->add_record( false, 2 );
        /* monitor_mail query */
        $db_config->add_query( sprintf( $qs[4], $args[1]['proid'],''), 3);
        $db_config->add_record( false, 3 );
        $db = new DB_SourceAgency;
        $this->capture_call('put_decision_into_database',0,$args[1]);

        // test three: one records, your_vote matches what, num_rows == 0
        $db_config->add_query( sprintf( $qs[0], $args[2]['what'],
                                                     $args[2]['table']), 4);
        $a=array( $args[2]['what'] => $args[2]['your_vote'] );
        $db_config->add_record( $a, 4 );
        $db_config->add_record( false, 4 );
        $db_config->add_query( sprintf( $qs[1], $args[2]['proid'], 
                                            $args[2]['step'], $uname ), 4 );
        $db_config->add_num_row( 0, 4 );
        $db_config->add_query( sprintf( $qs[2], $args[2]['proid'], 
                         $args[2]['step'], $uname, $args[2]['your_vote']), 4 );
        /* monitor_mail query */
        $db_config->add_query( sprintf( $qs[4], $args[2]['proid'],''), 5);
        $db_config->add_record( false, 5 );
        $db = new DB_SourceAgency;
        $this->capture_call('put_decision_into_database',0,$args[2]);
        
        // test four: one records, your_vote matches what, num_rows == 1,
        // test four: decision matches your_vote
        $db_config->add_query( sprintf( $qs[0], $args[3]['what'],
                                                     $args[3]['table']), 6);
        $a=array( $args[3]['what'] => $args[3]['your_vote'] );
        $db_config->add_record( $a, 6 );
        $db_config->add_record(array('decision'=>$args[3]['your_vote']),6);
        $db_config->add_record( false, 6 );
        $db_config->add_query( sprintf( $qs[1], $args[3]['proid'], 
                                            $args[3]['step'], $uname ), 6 );
        $db_config->add_num_row( 1, 6 );
        /* monitor_mail query */
        $db_config->add_query( sprintf( $qs[4], $args[3]['proid'],''), 7);
        $db_config->add_record( false, 7 );
        $db = new DB_SourceAgency;
        $this->capture_call('put_decision_into_database',32,$args[3]);
        $this->assertEquals( "You have already voted that guy\n",
                             $this->get_text());

        // test five: one records, your_vote matches what, num_rows == 1,
        // test five: decision does not match your_vote
        $db_config->add_query( sprintf( $qs[0], $args[4]['what'],
                                                     $args[4]['table']), 8);
        $a=array( $args[4]['what'] => $args[4]['your_vote'] );
        $db_config->add_record( $a, 8 );
        $db_config->add_record(array('decision'=>$args[4]['your_vote']
                                     ."NOT EQUAL"),8);
        $db_config->add_record( false, 8 );
        $db_config->add_query( sprintf( $qs[1], $args[4]['proid'], 
                                            $args[4]['step'], $uname ), 8 );
        $db_config->add_num_row( 1, 8 );
        $db_config->add_query( sprintf( $qs[3], $args[4]['your_vote'],
                                        $args[4]['proid'], $args[4]['step'],
                                        $uname), 8 );
        /* monitor_mail query */
        $db_config->add_query( sprintf( $qs[4], $args[4]['proid'],''), 9);
        $db_config->add_record( false, 9 );
        $db = new DB_SourceAgency;
        $this->capture_call('put_decision_into_database', 70, $args[4]);
        $this->assertEquals("Vote changed. Old: <b>".$args[4]['your_vote']
                            ."NOT EQUAL"."</b> New: <b>".$args[4]['your_vote']
                            ."</b>", $this->get_text());

        $this->_check_db( $db_config );
    }

    function testPut_decision_step5_into_database() {
        global $db, $auth, $queries;

        $uname = 'this is the username';
        $auth->set_uname( $uname );
        $fname = 'put_decision_step5_into_database';
         
        $qs=array( 0 => $queries[$fname."_1"],
                   1 => $queries[$fname."_2"],
                   2 => $queries[$fname."_3"],
                   3 => $queries['monitor_mail']);
        $db_config = new mock_db_configure( 6 );
        $args=$this->_generate_records(array( 'proid','your_vote',
                                              'milestone_number','count'), 10);
        // test one no records
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid'],$uname,
                         $args[0]['milestone_number'], $args[0]['count']), 0 );
        $db_config->add_num_row( 0, 0 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['proid'],$uname,
                             $args[0]['milestone_number'], $args[0]['count'],
                             $args[0]['your_vote']), 0 );
        /* monitor_mail query */
        $db_config->add_query( sprintf( $qs[3], $args[0]['proid'],''), 1);
        $db_config->add_record( false, 1 );
        $db = new DB_SourceAgency;
        $this->capture_call($fname,0,$args[0]);
        
        // test two: one record, decision equals your_vote
        $db_config->add_query( sprintf( $qs[0], $args[1]['proid'],$uname,
                         $args[1]['milestone_number'], $args[1]['count']), 2 );
        $db_config->add_num_row( 1, 2 );
        $db_config->add_record(array('decision'=>$args[1]['your_vote']),2);
        /* monitor_mail query */
        $db_config->add_query( sprintf( $qs[3], $args[1]['proid'],''), 3);
        $db_config->add_record( false, 3 );
        $db = new DB_SourceAgency;
        $this->capture_call($fname,28,$args[1]);
        $this->assertEquals("You have already voted that\n",$this->get_text());

        // test three: one record, decision does not equal your_vote
        $db_config->add_query( sprintf( $qs[0], $args[2]['proid'],$uname,
                         $args[2]['milestone_number'], $args[2]['count']), 4 );
        $db_config->add_num_row( 1, 4 );
        $db_config->add_record(array('decision'=>$args[2]['your_vote']
                                     ."NOT EQUALS"),4);
        $db_config->add_query( sprintf( $qs[2], $args[2]['your_vote'],
                               $args[2]['proid'], $uname, 
                               $args[2]['milestone_number'], 
                               $args[2]['count']), 4 );
        /* monitor_mail query */
        $db_config->add_query( sprintf( $qs[3], $args[2]['proid'],''), 5);
        $db_config->add_record( false, 5 );
        $db = new DB_SourceAgency;
        $this->capture_call($fname,71,$args[2]);
        $this->assertEquals( "Vote changed. Old: <b>".$args[2]['your_vote']
                             ."NOT EQUALS"."</b> New: <b>"
                             .$args[2]['your_vote']."</b>", $this->get_text());

        $this->_check_db( $db_config );
    }
    function _config_db_put_into_next_step( &$db_config, $inst_nr, &$args,
                                            $p_status, $rec_cnt = 0, 
                                            $p_bud = false, $q_p_bud = false ){
        global $qs;

        $db_i_global = $inst_nr++;
        $db_i_local  = $inst_nr++;
        $db_i_quota  = $inst_nr++;

        $d1=$this->_generate_records(array($args['what'],'creation'),$rec_cnt);
        $db_config->add_query(sprintf($qs[0],$args['what'],$args['table'],
                                          $args['proid']), $db_i_local );
        $db_config->add_record_array( $d1, $db_i_local );
        $db_config->add_record( false, $db_i_local );

        for ( $idx = 0; $idx < $rec_cnt; $idx++ ) {
            $db_config->add_query( sprintf( $qs[1], $d1[$idx][$args['what']],
                                   $args['proid'], $args['proid'], 
                                   $args['status']), $db_i_quota);
            $db_config->add_record( array('SUM(budget)' => $q_p_bud[$idx]),
                                    $db_i_quota );
            $db_config->add_query( sprintf( $qs[9], $args['proid']), 
                                   $db_i_global );
            $db_config->add_record( array( 'SUM(budget)' => $p_bud[$idx]),
                                    $db_i_global );

            if ( $q_p_bud[$idx] > ($p_bud[$idx]/2) ) {
                $db_config->add_query( sprintf( $qs[2], $args['table'],
                                     $d1[$idx]['creation'],$args['what'],
                                     $d1[$idx][$args['what']], $args['proid']),
                                     $db_i_quota );
            } else {
                $db_config->add_query( sprintf( $qs[3], $args['table'],
                                     $d1[$idx]['creation'],$args['what'],
                                     $d1[$idx][$args['what']], $args['proid']),
                                     $db_i_quota );
            }
        }

        $d2=$this->_generate_array(array('status','description_creation'),10);
        $d2['status'] = $p_status;
        $p_status++;
        $db_config->add_query(sprintf( $qs[4], $args['proid'] ), $db_i_local);
        $db_config->add_record( $d2, $db_i_local );
        
        $db_config->add_query( sprintf( $qs[5], $p_status, 
                                        $d2['description_creation'], 
                                        $args['proid']), $db_i_local );
        $db_config->add_query( sprintf( $qs[6],$args['proid'],$p_status),
                               $db_i_local );

        if ( $p_status == 4 ) {
            $db_config->add_query( sprintf($qs[8], $args['proid'] ), $inst_nr);
            $db_config->add_record( array( 'developer'=>'fubar'), $inst_nr);
            $inst_nr++;
        }
        
        $db_config->add_query( sprintf( $qs[7], $args['proid']), $inst_nr );
        $db_config->add_record( false, $inst_nr );
        return ++$inst_nr;
    }

    function testPut_into_next_step() {
        global $bx, $qs, $db, $queries;

        $fname = 'put_into_next_step';
        $qs = array( 8 =>$queries['decision_insert_main_developer_1'],
                     9 =>$queries['project_budget']);
        for ( $idx = 0; $idx < 8; $idx++ ) {
            $qs[$idx] = $queries[ $fname . '_' . ($idx+1) ];
        }
        $args=$this->_generate_records(array( 'proid', 'status', 'what',
                                              'table' ), 10 );

        $db_config = new mock_db_configure( 26 ); 
        $inst_nr = 0;
        // test one: first query returns no values, project_status 1 ==> 2
        $inst_nr = $this->_config_db_put_into_next_step( $db_config, $inst_nr,
                                                         $args[0], 1, 0 );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[0] );

        // test two: first query returns no values, project_status 3 ==> 4
        $inst_nr = $this->_config_db_put_into_next_step( $db_config, $inst_nr,
                                                         $args[1], 3, 0 );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[1] );

        // test three: first query returns two value, project_status 2 ==> 3
        $inst_nr = $this->_config_db_put_into_next_step( $db_config, $inst_nr,
                                                         $args[2], 2, 2,
                                               array( 10, 20 ), array( 6, 9 ));
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[2] );

        // test four: first query returns three value, project_status 3 ==> 4
        $inst_nr = $this->_config_db_put_into_next_step( $db_config, $inst_nr,
                                                         $args[3], 3, 3,
                                        array( 10, 20, 10 ), array( 6, 9, 2 ));
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[3] );

        // test five: first query returns four value, project_status 4 ==> 5
        $inst_nr = $this->_config_db_put_into_next_step( $db_config, $inst_nr,
                                                         $args[4], 4, 4,
                                        array( 1,1,1,1 ), array( 2,2,2,2 ));
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[4] );

        // test six: first query returns four value, project_status 5 ==> 6
        $inst_nr = $this->_config_db_put_into_next_step( $db_config, $inst_nr,
                                                         $args[5], 5, 4,
                                          array(10,10,10,10),array( 1,1,1,1 ));
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[5] );

        $this->_check_db( $db_config );
    }

    function _config_db_show_decision_consultants( &$db_config, $inst_nr, &$qs,
                       $proid, $quorum, $project_budget, $has_consultants, 
                       &$row_data, &$sum_budget ) {
        $db_i_global = $inst_nr;
        /** quorum query **/
        $db_config->add_query( sprintf( $qs[0], $proid ), $db_i_global );
        $db_config->add_record( array( 'quorum' => $quorum ), $db_i_global );
        /** project budget **/
        $db_config->add_query( sprintf( $qs[4], $proid ), $db_i_global );
        $db_config->add_record( array( 'SUM(budget)' => $project_budget),
                                $db_i_global );
        /** configured to have consultants ? **/
        $db_config->add_query( sprintf( $qs[1], $proid ), $db_i_global );
        $db_config->add_record( array( 'consultants' =>
                                       ($has_consultants ? 'Yes' : 'No')),
                                $db_i_global );
        $row_cnt = count( $row_data );
        /** retrieve consultant data query **/
        $db_config->add_query( sprintf( $qs[2], $proid ), $db_i_global );
        $db_config->add_num_row( $row_cnt, $db_i_global );
        if ( $row_cnt != 0 ) {
            for ( $idx = 0; $idx < $row_cnt; $idx++ ) {
                $db_config->add_record( $row_data[$idx], $db_i_global );
                $inst_nr++;
                $db_config->add_query( sprintf( $qs[3], $proid, $proid, 
                                    $row_data[$idx]['consultant']), $inst_nr );
                $db_config->add_record( $sum_budget[$idx], $inst_nr );
            }
            $db_config->add_record( false, $db_i_global );
        }

        /** num_row call if the 'flag' variable was not set **/
        if ( $has_consultants ) {
            $db_config->add_num_row( $row_cnt, $db_i_global );
        }
        return ++$inst_nr;
    }

    function _checkFor_show_decision_consultants() {
        // TODO: fill in this method .....
    }

    function testShow_decision_consultants() {
        global $t, $bx, $db, $sess;
        global $voted_yet, $your_vote, $queries;
        
        $voted_yet = 0;
        $your_vote = 'adsad';

        $fname = 'show_decision_consultants';
        $qs = array( 0 => $queries[ 'decisions_step5_sponsors_1' ],
                     1 => $queries[ $fname . '_1' ],
                     2 => $queries[ $fname . '_2' ],
                     3 => $queries[ $fname . '_3' ],
                     4 => $queries[ 'project_budget' ]);
        $db_config = new mock_db_configure( 10 );
        $inst_nr = 0;
        $args = $this->_generate_records( array( 'proid' ), 10 );

        // test one: no consultant data, no consultants required
        $voted_yet = 0;
        $your_vote = '';
        $d1 = array();
        $d2 = array();
        $inst_nr = 
             $this->_config_db_show_decision_consultants( $db_config, $inst_nr,
                          $qs, $args[1]['proid'], 10, 10, false, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 163, $args[1] );
        $this->assertEquals( 0, $voted_yet );
        $this->assertEquals( 100, $rval );
        $this->_checkFor_show_decision_consultants();

        // test two: one data point, consultants required, voting less than
        // test two: decision_value, your_vote does not match
        $voted_yet = 0;
        $your_vote = '';
        $d1 = $this->_generate_records( array( 'consultant', 'status',
                                               'creation' ), 1 );
        $d2 = $this->_generate_records( array( 'sum_consultant' ), 1 );
        $d2[0]['sum_consultant'] = -10;
        $inst_nr = 
             $this->_config_db_show_decision_consultants( $db_config, $inst_nr,
                          $qs, $args[0]['proid'], 10, 10, true, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3285 + strlen($sess->self_url()),
                                     $args[0] );
        $this->assertEquals( -100, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_consultants();

        // test three: one data point, consultants not required, voting less 
        // test three: than decision_value, your_vote does not match
        $voted_yet = 0;
        $your_vote = '';
        $d1 = $this->_generate_records( array( 'consultant', 'status',
                                               'creation' ), 1 );
        $d2 = $this->_generate_records( array( 'sum_consultant' ), 1 );
        $d2[0]['sum_consultant'] = -10;
        $inst_nr = 
             $this->_config_db_show_decision_consultants( $db_config, $inst_nr,
                          $qs, $args[2]['proid'], 10, 10, false, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3399 + strlen($sess->self_url()),
                                     $args[2] );
        $this->assertEquals( -100, $voted_yet );
        $this->assertEquals( 100, $rval );
        $this->_checkFor_show_decision_consultants();

        // test four: one data point, consultants not required, voting less 
        // test four: than decision_value, your_vote matches consultant
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'consultant', 'status',
                                               'creation' ), 1 );
        $d2 = $this->_generate_records( array( 'sum_consultant' ), 1 );
        $your_vote = $d1[0]['consultant'];
        $d2[0]['sum_consultant'] = -10;
        $inst_nr = 
             $this->_config_db_show_decision_consultants( $db_config, $inst_nr,
                          $qs, $args[3]['proid'], 10, 10, false, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3408 + strlen($sess->self_url()),
                                     $args[3] );
        $this->assertEquals( -100, $voted_yet );
        $this->assertEquals( 100, $rval );
        $this->_checkFor_show_decision_consultants();

        // test five: one data point, consultants not required, voting greater 
        // test five: than decision_value, your_vote matches consultant
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'consultant', 'status',
                                               'creation' ), 1 );
        $d2 = $this->_generate_records( array( 'sum_consultant' ), 1 );
        $your_vote = $d1[0]['consultant'];
        $d2[0]['sum_consultant'] = 10;
        $inst_nr = 
             $this->_config_db_show_decision_consultants( $db_config, $inst_nr,
                          $qs, $args[3]['proid'], 1, 10, false, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3407 + strlen($sess->self_url()),
                                     $args[3] );
        $this->assertEquals( 100, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_consultants();

        // test six: no consultant data, but consultants required
        $voted_yet = 0;
        $your_vote = '';
        $d1 = array();
        $d2 = array();
        $inst_nr = 
             $this->_config_db_show_decision_consultants( $db_config, $inst_nr,
                          $qs, $args[1]['proid'], 10, 10, true, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 110, $args[1] );
        $this->assertEquals( 0, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_consultants();
        
        $this->_check_db( $db_config );
    }

    function _config_db_show_decision_contents( &$db_config, $inst_nr,
                                      &$qs, $proid, $quorum, $project_budget, 
                                      &$row_data, &$sum_budget ) {

        $db_i_global = $inst_nr;
        /** quorum query **/
        $db_config->add_query( sprintf( $qs[0], $proid ), $db_i_global );
        $db_config->add_record( array( 'quorum' => $quorum ), $db_i_global );
        /** project budget **/
        $db_config->add_query( sprintf( $qs[1], $proid ), $db_i_global );
        $db_config->add_record( array( 'SUM(budget)' => $project_budget),
                                $db_i_global );
        /** tech content query **/
        $db_config->add_query( sprintf( $qs[2], $proid ), $db_i_global );
        $row_cnt = count( $row_data );
        $db_config->add_num_row( $row_cnt, $db_i_global );
        if ( $row_cnt > 0 ) {
            for ( $idx = 0; $idx < $row_cnt; $idx++ ) {
                $db_config->add_record( $row_data[$idx], $db_i_global );
                $inst_nr++;
                $db_config->add_query( sprintf( $qs[3], $proid, $proid,
                                    $row_data[$idx]['content_id']), $inst_nr );
                $db_config->add_record( $sum_budget[$idx], $inst_nr );
            }
            $db_config->add_record( false, $db_i_global );
        }

        $db_config->add_num_row( $row_cnt, $db_i_global );
        return ++$inst_nr;
    }


    function testShow_decision_contents() {
        global $t, $bx, $db, $sess;
        global $voted_yet, $your_vote, $queries;

        $fname = 'show_decision_contents';
        $qs = array( 0 => $queries[ 'decisions_step5_sponsors_1' ],
                     1 => $queries[ 'project_budget' ],
                     2 => $queries[ $fname . '_1' ],
                     3 => $queries[ $fname . '_2' ]);
        $db_config = new mock_db_configure( 9 );
        $inst_nr = 0;
        $args=$this->_generate_records( array( 'proid' ), 10 );
        
        // test one: no data points
        $your_vote = '';
        $voted_yet = 0;
        $d1 = array();
        $d2 = array();
        $inst_nr =
             $this->_config_db_show_decision_contents( $db_config, $inst_nr,
                                    $qs, $args[0]['proid'], 10, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 119, $args[0] );
        $this->assertEquals( 0, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_contents();

        // test two: one data point, voting less than decision value, 
        // test two: your_vote does not match content_id
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'content_id', 'platform', 
                                               'architecture', 'environment',
                                               'creation' ), 1 );
        $d2 = $this->_generate_records( array( 'sum_content' ), 1 );
        $your_vote = $d1[0]['content_id'] . 'DONT MATCH';
        $d2[0]['sum_content'] = -10;
        $inst_nr =
             $this->_config_db_show_decision_contents( $db_config, $inst_nr,
                                    $qs, $args[1]['proid'], 10, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3939 +strlen( $sess->self_url()), 
                                     $args[1] );
        $this->assertEquals( -100, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_contents();

        // test three: one data point, voting greater than decision_value,
        // test three: your_vote does not match content_id
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'content_id', 'platform', 
                                               'architecture', 'environment',
                                               'creation' ), 1 );
        $d2 = $this->_generate_records( array( 'sum_content' ), 1 );
        $your_vote = $d1[0]['content_id'] . 'DONT MATCH';
        $d2[0]['sum_content'] = 20;
        $inst_nr =
             $this->_config_db_show_decision_contents( $db_config, $inst_nr,
                                    $qs, $args[2]['proid'], 10, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3938+ strlen( $sess->self_url()), 
                                      $args[2] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_contents();

        // test four: one data point, voting greater than decision_value,
        // test four: your_vote matches content_id
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'content_id', 'platform', 
                                               'architecture', 'environment',
                                               'creation' ), 1 );
        $d2 = $this->_generate_records( array( 'sum_content' ), 1 );
        $your_vote = $d1[0]['content_id'];
        $d2[0]['sum_content'] = 20;
        $inst_nr =
             $this->_config_db_show_decision_contents( $db_config, $inst_nr,
                                    $qs, $args[3]['proid'], 10, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3947+ strlen( $sess->self_url()), 
                                      $args[3] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_contents();

        // test five: one data point, voting less than decision value,
        // test five: your_vote matches content_id
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'content_id', 'platform', 
                                               'architecture', 'environment',
                                               'creation' ), 1 );
        $d2 = $this->_generate_records( array( 'sum_content' ), 1 );
        $your_vote = $d1[0]['content_id'];
        $d2[0]['sum_content'] = -20;
        $inst_nr =
             $this->_config_db_show_decision_contents( $db_config, $inst_nr,
                                    $qs, $args[4]['proid'], 10, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3948+ strlen( $sess->self_url()), 
                                      $args[4] );
        $this->assertEquals( -200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_contents();
        
        $this->_check_db( $db_config );
    }

    function _checkFor_show_decision_contents() {
        // TODO: complete this function
    }

    function _config_db_show_decision_milestones( &$db_config, $inst_nr,
                   &$qs, $proid, $devid, $quorum, $project_budget, 
                   &$row_data, &$sum_budget ) {
        $db_i_global = $inst_nr;
        /** quorum query **/
        $db_config->add_query( sprintf( $qs[0], $proid ), $db_i_global );
        $db_config->add_record( array( 'quorum' => $quorum ), $db_i_global );
        /** project budget **/
        $db_config->add_query( sprintf( $qs[1], $proid ), $db_i_global );
        $db_config->add_record( array( 'SUM(budget)' => $project_budget),
                                $db_i_global );
        /** query milestone table **/
        $db_config->add_query(sprintf( $qs[2], $proid, $devid ), $db_i_global);
        $row_cnt = count( $row_data );
        $db_config->add_num_row( $row_cnt, $db_i_global );
        if ( $row_cnt > 0 ) {
            for ( $idx = 0; $idx < $row_cnt; $idx++ ) {
                $db_config->add_record( $row_data[$idx], $db_i_global );
                $inst_nr++;
                $db_config->add_query( sprintf( $qs[3], $proid, $proid, 
                                $devid, $row_data[$idx]['number']), $inst_nr );
                $db_config->add_record( $sum_budget[$idx], $inst_nr );

                /** decision milestone into db call **/
                $voting = ((round(($sum_budget[$idx]['sum_milestone']
                                               /$project_budget)*10000))/100);
                if (($voting > $quorum && $row_data[$idx]['status'] != 'A')
                    ||($voting <= $quorum && $row_data[$idx]['status']!='P')) {
                    $inst_nr++;
                    $db_config->add_query( sprintf( $qs[4], $proid, $devid,
                    $row_data[$idx]['number']), $inst_nr);
                    $db_config->add_record(array('creation' => 'c',
                                                 'release' => 'r'), $inst_nr);
                    $status = ( $row_data[$idx]['status'] != 'A' ? 'A' : 'P');
                    $db_config->add_query( sprintf( $qs[5], $status,'c','r',
                                   $proid, $devid, $row_data[$idx]['number']),
                                           $inst_nr);
                }
            }
            $db_config->add_record( false, $db_i_global );
        }

        $db_config->add_num_row( $row_cnt, $db_i_global );
        return ++$inst_nr;
    }
    
    function testShow_decision_milestones() {
        global $t, $bx, $db, $sess;
        global $voted_yet, $your_vote, $queries;

        $fname = 'show_decision_milestones';
        $qs = array( 0 =>$queries[ 'decisions_step5_sponsors_1' ],
                     1 =>$queries[ 'project_budget' ],
                     2 =>$queries[ $fname . '_1' ],
                     3 =>$queries[ $fname . '_2' ],
                     4 =>$queries['decisions_milestone_into_db_select'],
                     5 =>$queries['decisions_milestone_into_db_update']);

        $db_config = new mock_db_configure( 21 );
        $inst_nr = 0;
        $args = $this->_generate_records( array( 'proid','devid'), 10 );
        
        // test one: no data points
        $your_vote = '';
        $voted_yet = 0;
        $d1 = array();
        $d2 = array();
        $inst_nr =
             $this->_config_db_show_decision_milestones( $db_config, $inst_nr,
                                                  $qs, $args[0]['proid'], 
                                          $args[0]['devid'], 5, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 116, $args[0] );
        $this->assertEquals( 0, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_milestones();

        // test two: one data point, voting greater than decision_value,
        // test two: status == 'A', milestone number is 'No'
        $your_vote = '';
        $voted_yet = 0;
        $d1 = $this->_generate_records(array('number','goals','release',
                                             'product','payment','status'),1);
        $d2 = $this->_generate_records( array( 'sum_milestone' ), 1 );
        $d1[0]['status'] = 'A';
        $GLOBALS['milestone_'.$d1[0]['number']] = 'No';
        $d2[0]['sum_milestone'] = 20;
        $inst_nr =
             $this->_config_db_show_decision_milestones( $db_config, $inst_nr,
                                          $qs, $args[1]['proid'], 
                                          $args[1]['devid'], 5, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3993 + strlen($sess->self_url()), 
                                     $args[1] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_milestones();

        // test three: one data point, voting greater than decision_value,
        // test three: status == 'P', milestone number is 'No'
        $your_vote = '';
        $voted_yet = 0;
        $d1 = $this->_generate_records(array('number','goals','release',
                                             'product','payment','status'),1);
        $d2 = $this->_generate_records( array( 'sum_milestone' ), 1 );
        $d1[0]['status'] = 'P';
        $GLOBALS['milestone_'.$d1[0]['number']] = 'No';
        $d2[0]['sum_milestone'] = 20;
        $inst_nr =
             $this->_config_db_show_decision_milestones( $db_config, $inst_nr,
                                          $qs, $args[2]['proid'], 
                                          $args[2]['devid'], 5, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3993 + strlen($sess->self_url()), 
                                     $args[2] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_milestones();

        // test four: one data point, voting greater than decision_value,
        // test four: status == 'A', milestone number is 'Yes'
        $your_vote = '';
        $voted_yet = 0;
        $d1 = $this->_generate_records(array('number','goals','release',
                                             'product','payment','status'),1);
        $d2 = $this->_generate_records( array( 'sum_milestone' ), 1 );
        $d1[0]['status'] = 'A';
        $GLOBALS['milestone_'.$d1[0]['number']] = 'Yes';
        $d2[0]['sum_milestone'] = 20;
        $inst_nr =
             $this->_config_db_show_decision_milestones( $db_config, $inst_nr,
                                          $qs, $args[3]['proid'], 
                                          $args[3]['devid'], 5, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 4002 + strlen($sess->self_url()), 
                                     $args[3] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_milestones();

        // test five: one data point, voting greater than decision_value,
        // test five: status == 'P', milestone number is 'Yes'
        $your_vote = '';
        $voted_yet = 0;
        $d1 = $this->_generate_records(array('number','goals','release',
                                             'product','payment','status'),1);
        $d2 = $this->_generate_records( array( 'sum_milestone' ), 1 );
        $d1[0]['status'] = 'P';
        $GLOBALS['milestone_'.$d1[0]['number']] = 'Yes';
        $d2[0]['sum_milestone'] = 20;
        $inst_nr =
             $this->_config_db_show_decision_milestones( $db_config, $inst_nr,
                                          $qs, $args[4]['proid'], 
                                          $args[4]['devid'], 5, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 4002 + strlen($sess->self_url()), 
                                     $args[4] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_milestones();

        // test six: one data point, voting less than decision_value,
        // test six: status == 'A', milestone number is 'No'
        $your_vote = '';
        $voted_yet = 0;
        $d1 = $this->_generate_records(array('number','goals','release',
                                             'product','payment','status'),1);
        $d2 = $this->_generate_records( array( 'sum_milestone' ), 1 );
        $d1[0]['status'] = 'A';
        $GLOBALS['milestone_'.$d1[0]['number']] = 'No';
        $d2[0]['sum_milestone'] = -20;
        $inst_nr =
             $this->_config_db_show_decision_milestones( $db_config, $inst_nr,
                                          $qs, $args[5]['proid'], 
                                          $args[5]['devid'], 5, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3994 + strlen($sess->self_url()), 
                                     $args[5] );
        $this->assertEquals( -200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_milestones();

        // test seven: one data point, voting less than decision_value,
        // test seven: status == 'P', milestone number is 'No'
        $your_vote = '';
        $voted_yet = 0;
        $d1 = $this->_generate_records(array('number','goals','release',
                                             'product','payment','status'),1);
        $d2 = $this->_generate_records( array( 'sum_milestone' ), 1 );
        $d1[0]['status'] = 'P';
        $GLOBALS['milestone_'.$d1[0]['number']] = 'No';
        $d2[0]['sum_milestone'] = -20;
        $inst_nr =
             $this->_config_db_show_decision_milestones( $db_config, $inst_nr,
                                          $qs, $args[6]['proid'], 
                                          $args[6]['devid'], 5, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3994 + strlen($sess->self_url()), 
                                     $args[6] );
        $this->assertEquals( -200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_milestones();

        // test eight: one data point, voting less than decision_value,
        // test eight: status == 'A', milestone number is 'Yes'
        $your_vote = '';
        $voted_yet = 0;
        $d1 = $this->_generate_records(array('number','goals','release',
                                             'product','payment','status'),1);
        $d2 = $this->_generate_records( array( 'sum_milestone' ), 1 );
        $d1[0]['status'] = 'A';
        $GLOBALS['milestone_'.$d1[0]['number']] = 'Yes';
        $d2[0]['sum_milestone'] = -20;
        $inst_nr =
             $this->_config_db_show_decision_milestones( $db_config, $inst_nr,
                                          $qs, $args[7]['proid'], 
                                          $args[7]['devid'], 5, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 4003 + strlen($sess->self_url()), 
                                     $args[7] );
        $this->assertEquals( -200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_milestones();

        // test nine: one data point, voting less than decision_value,
        // test nine: status == 'P', milestone number is 'Yes'
        $your_vote = '';
        $voted_yet = 0;
        $d1 = $this->_generate_records(array('number','goals','release',
                                             'product','payment','status'),1);
        $d2 = $this->_generate_records( array( 'sum_milestone' ), 1 );
        $d1[0]['status'] = 'P';
        $GLOBALS['milestone_'.$d1[0]['number']] = 'Yes';
        $d2[0]['sum_milestone'] = -20;
        $inst_nr =
             $this->_config_db_show_decision_milestones( $db_config, $inst_nr,
                                          $qs, $args[8]['proid'], 
                                          $args[8]['devid'], 5, 10, $d1, $d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 4003 + strlen($sess->self_url()), 
                                     $args[8] );
        $this->assertEquals( -200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_milestones();

        $this->_check_db( $db_config );
    }

    function _checkFor_show_decision_milestones() {
        // TODO: define this function
    }

    function _config_db_show_decision_proposals( &$db_config, $inst_nr,
                                     &$qs, $proid, $quorum, $project_budget, 
                                     &$row_data, &$sum_content, 
                                     &$ams_call_1, &$ams_call_2 ) {
        global $auth;
        $db_i_global = $inst_nr;
        /** quorum query **/
        $db_config->add_query( sprintf( $qs[0], $proid ), $db_i_global );
        $db_config->add_record( array( 'quorum' => $quorum ), $db_i_global );
        /** project budget querry **/
        $db_config->add_query( sprintf( $qs[1], $proid ), $db_i_global );
        $db_config->add_record( array( "SUM(budget)" => $project_budget),
                                $db_i_global );
        /** tech_content query **/
        $row_cnt = count( $row_data );
        $db_config->add_query( sprintf( $qs[2], $proid ), $db_i_global );
        $db_config->add_num_row( $row_cnt, $db_i_global );
        if ( $row_cnt > 0 ) {
            for ( $idx = 0; $idx < $row_cnt; $idx++ ) {
                $db_config->add_record( $row_data[$idx], $db_i_global );
                $inst_nr++;
                $devid = $row_data[$idx]['devid'];
                $db_config->add_query( sprintf( $qs[3], $proid, $proid, 
                                                            $devid), $inst_nr);
                $db_config->add_record( $sum_content[$idx], $inst_nr );
                /** call 1 to decision accepted milestone **/
                $inst_nr++;
                $db_config->add_query( sprintf( $qs[4], $proid, $devid ), 
                                       $inst_nr );
                $db_config->add_record(array('number'=>1,'payment'=>1),
                                       $inst_nr);
                $db_config->add_record( false, $inst_nr );
                $inst_nr++; 
                $db_config->add_query( sprintf( $qs[5], $proid, $devid,'1',
                                        $auth->auth['uname']), $inst_nr );
                $db_config->add_record( array('COUNT(*)'=>$ams_call_1[$idx]),
                                        $inst_nr);
                /** call 2 to decision accepted milestone **/
                $inst_nr++;
                $db_config->add_query( sprintf( $qs[4], $proid, $devid ), 
                                       $inst_nr );
                $db_config->add_record( array('number'=>1,'payment'=>1),
                                        $inst_nr);
                $db_config->add_record( false, $inst_nr );
                $inst_nr++;  
                $db_config->add_query( sprintf( $qs[5], $proid, $devid,'1',
                                        $auth->auth['uname']), $inst_nr );
                $db_config->add_record( array('COUNT(*)'=>$ams_call_2[$idx]),
                                        $inst_nr);
            }
            $db_config->add_record( false, $db_i_global );
        }

        $db_config->add_num_row( $row_cnt, $db_i_global );
        return ++$inst_nr;
    }

    function _checkFor_show_decision_proposals() {
        // TODO: definne this function
    }

    function testShow_decision_proposals() {
        global $t, $bx, $db, $sess;
        global $voted_yet, $your_vote, $queries;
        
        $fname = 'show_decision_proposals';
        $args = $this->_generate_records( array( 'proid' ), 10 );
        $qs = array( 0 => $queries[ 'decisions_step5_sponsors_1' ],
                     1 => $queries[ 'project_budget' ],
                     2 => $queries[ $fname . '_1' ],
                     3 => $queries[ $fname . '_2' ],
                     4 => $queries[ 'decision_accepted_milestones_1'],
                     5 => $queries[ 'decision_accepted_milestones_2'] );
                     
        $db_config = new mock_db_configure( 37 );
        $inst_nr = 0;

        // test one: no data points
        $voted_yet = 0;
        $your_vote = '';
        $d1 = array();
        $d2 = $d1;
        $d3 = $d1;
        $d4 = $d1;
        $inst_nr =
             $this->_config_db_show_decision_proposals( $db_config, $inst_nr,
                          $qs, $args[0]['proid'], 10, 10, $d1, $d2, $d3, $d4 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 220, $args[0] );
        $this->assertEquals( 0, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_proposals();

        // test two: one data point, voting is less than decision_value,
        // test two: decision_accepted_milestones returns 100, your_vote
        // test two: does not match devid
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'devid', 'developer', 'license',
                                               'cost','start','duration',
                                               'valid','creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_content' ), 1 );
        $d3 = array( 10 );
        $d4 = array( 100 );
        $your_vote = $d1[0]['devid'] . 'NO MATCH';
        $d2[0]['sum_content'] = -10;
        $inst_nr =
             $this->_config_db_show_decision_proposals( $db_config, $inst_nr,
                          $qs, $args[1]['proid'], 10, 10, $d1, $d2, $d3, $d4 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 5315 + strlen($sess->self_url()), 
                                     $args[1] );
        $this->assertEquals( -100, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_proposals();

        // test three: one data point, voting is less than decision_value,
        // test three: decision_accepted_milestones returns 100, your_vote
        // test three: matches devid
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'devid', 'developer', 'license',
                                               'cost','start','duration',
                                               'valid','creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_content' ), 1 );
        $d3 = array( 10 );
        $d4 = array( 100 );
        $your_vote = $d1[0]['devid'];
        $d2[0]['sum_content'] = -10;
        $inst_nr =
             $this->_config_db_show_decision_proposals( $db_config, $inst_nr,
                          $qs, $args[2]['proid'], 10, 10, $d1, $d2, $d3, $d4 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 5324 + strlen($sess->self_url()), 
                                     $args[2] );
        $this->assertEquals( -100, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_proposals();

        // test four: one data point, voting is greater than decision_value,
        // test four: decision_accepted_milestones returns 100, your_vote
        // test four: matches devid
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'devid', 'developer', 'license',
                                               'cost','start','duration',
                                               'valid','creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_content' ), 1 );
        $d3 = array( 10 );
        $d4 = array( 100 );
        $your_vote = $d1[0]['devid'];
        $d2[0]['sum_content'] = 20;
        $inst_nr =
             $this->_config_db_show_decision_proposals( $db_config, $inst_nr,
                          $qs, $args[3]['proid'], 10, 10, $d1, $d2, $d3, $d4 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 5323 + strlen($sess->self_url()), 
                                     $args[3] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_proposals();

        // test five: one data point, voting is greater than decision_value,
        // test five: decision_accepted_milestones returns 100, your_vote
        // test five: does not match devid
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'devid', 'developer', 'license',
                                               'cost','start','duration',
                                               'valid','creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_content' ), 1 );
        $d3 = array( 10 );
        $d4 = array( 100 );
        $your_vote = $d1[0]['devid'] . 'NO MATCH';
        $d2[0]['sum_content'] = 20;
        $inst_nr =
             $this->_config_db_show_decision_proposals( $db_config, $inst_nr,
                          $qs, $args[4]['proid'], 10, 10, $d1, $d2, $d3, $d4 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 5314 + strlen($sess->self_url()), 
                                     $args[4] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_proposals();

        // test six: one data point, voting is greater than decision_value,
        // test six: decision_accepted_milestones does not returns 100
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'devid', 'developer', 'license',
                                               'cost','start','duration',
                                               'valid','creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_content' ), 1 );
        $d3 = array( 10 );
        $d4 = array( 10 );
        $your_vote = '';
        $d2[0]['sum_content'] = 20;
        $inst_nr =
             $this->_config_db_show_decision_proposals( $db_config, $inst_nr,
                          $qs, $args[5]['proid'], 10, 10, $d1, $d2, $d3, $d4 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 5257 + strlen($sess->self_url()), 
                                     $args[5] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_proposals();

        // test seven: one data point, voting is less than decision_value,
        // test seven: decision_accepted_milestones does not returns 100
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'devid', 'developer', 'license',
                                               'cost','start','duration',
                                               'valid','creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_content' ), 1 );
        $d3 = array( 10 );
        $d4 = array( 10 );
        $your_vote = '';
        $d2[0]['sum_content'] = -10;
        $inst_nr =
             $this->_config_db_show_decision_proposals( $db_config, $inst_nr,
                          $qs, $args[6]['proid'], 10, 10, $d1, $d2, $d3, $d4 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 5258 + strlen($sess->self_url()), 
                                     $args[6] );
        $this->assertEquals( -100, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_proposals();

        $this->_check_db( $db_config );
    }

    function _checkFor_show_decision_referees() {
        // TODO: complete this method
    }
    function _config_db_show_decision_referees( &$db_config, $inst_nr, &$qs,
                                 $proid, $quorum, $project_budget, &$row_data,
                                 &$sum_referee, &$d_dev_voted ) {
        $db_i_global = $inst_nr;
        /** quorum **/
        $db_config->add_query( sprintf( $qs[0], $proid ), $db_i_global );
        $db_config->add_record( array( 'quorum' => $quorum ), $db_i_global );
        /** project budget **/
        $db_config->add_query( sprintf( $qs[1], $proid ), $db_i_global );
        $db_config->add_record( array( 'SUM(budget)' => $project_budget),
                                $db_i_global );
        $row_cnt = count( $row_data );
        $db_config->add_query( sprintf( $qs[2], $proid ), $db_i_global );
        $db_config->add_num_row( $row_cnt, $db_i_global );
        if ( $row_cnt > 0 ) {
            for ( $idx = 0; $idx < $row_cnt; $idx++ ) {
                $db_config->add_record( $row_data[$idx], $db_i_global );
                $inst_nr++;
                $referee = $row_data[$idx]['referee'];
                $uname = $row_data[$idx]['username'];
                $db_config->add_query( sprintf( $qs[3], $proid, $proid, 
                                                      $referee), $inst_nr );
                $db_config->add_record( $sum_referee[$idx], $inst_nr );
                /** decision developer voted call **/
                $inst_nr++;
                $db_config->add_query( sprintf( $qs[4], $proid ), $inst_nr );
                $db_config->add_record( array('developer'=>'d'), $inst_nr );
                $db_config->add_query( sprintf( $qs[5], $proid, $uname, 'd'),
                                       $inst_nr );
                $db_config->add_num_row( $d_dev_voted[$idx] ? 1 : 0, $inst_nr);
            }
            $db_config->add_record( false, $db_i_global );
        }
        $db_config->add_num_row( $row_cnt, $db_i_global );
        return ++$inst_nr;
    }
    function testShow_decision_referees() {
        global $t, $bx, $db, $sess;
        global $voted_yet, $your_vote, $queries;
        
        $fname = 'show_decision_referees';
        $args = $this->_generate_records( array( 'proid' ), 10 );
        $db_config = new mock_db_configure( 25 );
        $inst_nr = 0;
        $qs = array( 0 => $queries[ 'decisions_step5_sponsors_1' ],
                     1 => $queries[ 'project_budget' ],
                     2 => $queries[ $fname . '_1' ],
                     3 => $queries[ $fname . '_2' ],
                     4 => $queries[ 'decision_developer_voted_1' ],
                     5 => $queries[ 'decision_developer_voted_2' ] );
                     
        // test one: no data points
        $voted_yet = 0;
        $your_vote = '';
        $d1 = array();
        $d2 = $d1;
        $d3 = $d1;
        $inst_nr = 
             $this->_config_db_show_decision_referees( $db_config, $inst_nr,
                               $qs, $args[0]['proid'], 10, 10, $d1, $d2, $d3 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 125, $args[0] );
        $this->assertEquals( 0, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_referees();

        // test two: one data point, decision_developer_voted returns true,
        // test two: voting is less than decision_value, your_vote does not
        // test two: match referee
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'referee','username','status',
                                               'creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_referee' ), 1 );
        $d3 = array( true );
        $d2[0]['sum_referee'] = -10;
        $your_vote = $d1[0]['referee'] . 'NO MATCH';
        $inst_nr = 
             $this->_config_db_show_decision_referees( $db_config, $inst_nr,
                               $qs, $args[1]['proid'], 10, 10, $d1, $d2, $d3 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3274 + strlen($sess->self_url()), 
                                     $args[1] );
        $this->assertEquals( -50, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_referees();

        // test three: one data point, decision_developer_voted returns true,
        // test three: voting is greater than decision_value, your_vote 
        // test three: matches referee
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'referee','username','status',
                                               'creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_referee' ), 1 );
        $d3 = array( true );
        $d2[0]['sum_referee'] = 20;
        $your_vote = $d1[0]['referee'];
        $inst_nr = 
             $this->_config_db_show_decision_referees( $db_config, $inst_nr,
                               $qs, $args[2]['proid'], 10, 10, $d1, $d2, $d3 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3285 + strlen($sess->self_url()), 
                                     $args[2] );
        $this->assertEquals( 250, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_referees();

        // test four: one data point, decision_developer_voted returns true,
        // test four: voting is greater than decision_value, your_vote does
        // test four: not match referee
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'referee','username','status',
                                               'creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_referee' ), 1 );
        $d3 = array( true );
        $d2[0]['sum_referee'] = 20;
        $your_vote = $d1[0]['referee'] . "NO MATCH";
        $inst_nr = 
             $this->_config_db_show_decision_referees( $db_config, $inst_nr,
                               $qs, $args[3]['proid'], 10, 10, $d1, $d2, $d3 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3276 + strlen($sess->self_url()), 
                                     $args[3] );
        $this->assertEquals( 250, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_referees();

        // test five: one data point, decision_developer_voted returns true,
        // test five: voting is less than decision_value, your_vote 
        // test five: matches referee
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'referee','username','status',
                                               'creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_referee' ), 1 );
        $d3 = array( true );
        $d2[0]['sum_referee'] = -20;
        $your_vote = $d1[0]['referee'] . "NO MATCH";
        $inst_nr = 
             $this->_config_db_show_decision_referees( $db_config, $inst_nr,
                               $qs, $args[4]['proid'], 10, 10, $d1, $d2, $d3 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3276 + strlen($sess->self_url()), 
                                     $args[4] );
        $this->assertEquals( -150, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_referees();

        // test six: one data point, decision_developer_voted returns false,
        // test six: voting is less than decision_value, your_vote does not
        // test six: match referee
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'referee','username','status',
                                               'creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_referee' ), 1 );
        $d3 = array( false );
        $d2[0]['sum_referee'] = -20;
        $your_vote = $d1[0]['referee'] . "NO MATCH";
        $inst_nr = 
             $this->_config_db_show_decision_referees( $db_config, $inst_nr,
                               $qs, $args[5]['proid'], 10, 10, $d1, $d2, $d3 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3277 + strlen($sess->self_url()), 
                                     $args[5] );
        $this->assertEquals( -200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_referees();

        // test seven: one data point, decision_developer_voted returns false,
        // test seven: voting is greater than decision_value, your_vote does
        // test seven: not match referee
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'referee','username','status',
                                               'creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_referee' ), 1 );
        $d3 = array( false );
        $d2[0]['sum_referee'] = 20;
        $your_vote = $d1[0]['referee'] . "NO MATCH";
        $inst_nr = 
             $this->_config_db_show_decision_referees( $db_config, $inst_nr,
                               $qs, $args[6]['proid'], 10, 10, $d1, $d2, $d3 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3276 + strlen($sess->self_url()), 
                                     $args[6] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_referees();

        // test eight: one data point, decision_developer_voted returns false,
        // test eight: voting is greater than decision_value, your_vote 
        // test eight: matches referee
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'referee','username','status',
                                               'creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_referee' ), 1 );
        $d3 = array( false );
        $d2[0]['sum_referee'] = 20;
        $your_vote = $d1[0]['referee'];
        $inst_nr = 
             $this->_config_db_show_decision_referees( $db_config, $inst_nr,
                               $qs, $args[7]['proid'], 10, 10, $d1, $d2, $d3 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3285 + strlen($sess->self_url()), 
                                     $args[7] );
        $this->assertEquals( 200, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_referees();

        // test nine: one data point, decision_developer_voted returns false,
        // test nine: voting is less than decision_value, your_vote 
        // test nine: matches referee
        $voted_yet = 0;
        $d1 = $this->_generate_records( array( 'referee','username','status',
                                               'creation'), 1 );
        $d2 = $this->_generate_records( array( 'sum_referee' ), 1 );
        $d3 = array( false );
        $d2[0]['sum_referee'] = -20;
        $your_vote = $d1[0]['referee'];
        $inst_nr = 
             $this->_config_db_show_decision_referees( $db_config, $inst_nr,
                               $qs, $args[7]['proid'], 10, 10, $d1, $d2, $d3 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 3286 + strlen($sess->self_url()), 
                                     $args[7] );
        $this->assertEquals( -200, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_referees();

        $this->_check_db( $db_config );
    }

    function _checkFor_show_decision_step5() {
        // TODO: fill in this method
    }
    function _config_db_show_decision_step5( &$db_config, $inst_nr, &$qs,
                           $proid, $m_num, $count, $quorum, $project_budget, 
                           &$row_data, &$sum_step5 ) {
        $db_i_global = $inst_nr;
        /** quorum **/
        $db_config->add_query( sprintf( $qs[0], $proid ), $db_i_global );
        $db_config->add_record( array( 'quorum' => $quorum ), $db_i_global );
        /** project budget **/
        $db_config->add_query( sprintf( $qs[1], $proid ), $db_i_global );
        $db_config->add_record( array( 'SUM(budget)' => $project_budget ),
                                $db_i_global );
        /** referee query **/
        $db_config->add_query(sprintf( $qs[2], $proid, $m_num ), $db_i_global);
        if ( count( $row_data ) ) {
            $db_config->add_record( $row_data, $db_i_global );
        } else {
            $db_config->add_record( false, $db_i_global );
        }

        /** 5 calls to decisions_step5_votes **/
        $dec=array(0=>'accept',1=>'minor',2=>'minor',3=>'severe',4=>'severe');
        for ( $idx = 0; $idx < 5; $idx++ ) {
            $inst_nr++;
            $db_config->add_query( sprintf( $qs[3], $proid, $proid, $m_num,
                                            $count, $dec[$idx] ), $inst_nr );
            $row_cnt = count( $sum_step5[$idx] );
            $db_config->add_num_row( $row_cnt, $inst_nr );
            if ( $row_cnt ) {
                $db_config->add_record( $sum_step5[$idx], $inst_nr );
                $db_config->add_query(sprintf( $qs[1], $proid ), $db_i_global);
                $db_config->add_record( array('SUM(budget)'=>$project_budget),
                                        $db_i_global );
            }
        }
        return ++$inst_nr;
    }

    function testShow_decision_step5() {
        global $t, $bx, $db, $sess;
        global $voted_yet, $decision, $queries;

        $fname = 'show_decision_step5';
        $qs = array( 0 => $queries[ 'decisions_step5_sponsors_1' ],
                     1 => $queries[ 'project_budget' ],
                     2 => $queries[ $fname ],
                     3 => $queries[ 'decisions_step5_votes' ]);
                     
        $args=$this->_generate_records( array('proid', 'm_num', 'count'), 10);
        $db_config = new mock_db_configure( 12 );
        $inst_nr = 0;

        // test one: one data point, voting is greater than decision_value
        $voted_yet = 0;
        $decision = '';
        $d1 = $this->_generate_array( array( 'release', 'product','payment',
                                             'goals' ), 10 );
        $d2 = $this->_generate_records( array( 'sum_step5' ), 5 );
        $d2[0]['sum_step5'] = -10;
        $d2[1]['sum_step5'] = -10;
        $d2[2]['sum_step5'] = -10;
        $d2[3]['sum_step5'] = -10;
        $d2[4]['sum_step5'] = -10;
        $inst_nr = 
             $this->_config_db_show_decision_step5( $db_config, $inst_nr, $qs,
                                         $args[0]['proid'], $args[0]['m_num'], 
                                         $args[0]['count'],10,10,$d1,$d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 4488 + strlen($sess->self_url()), 
                                     $args[0] );
        $this->assertEquals( 0, $voted_yet );
        $this->assertEquals( 0, $rval );
        $this->_checkFor_show_decision_step5();

        // test two: one data point, voting is less than decision_value
        $voted_yet = 0;
        $decision = '';
        $d1 = $this->_generate_array( array( 'release', 'product','payment',
                                             'goals' ), 10 );
        $d2 = $this->_generate_records( array( 'sum_step5' ), 5 );
        $d2[0]['sum_step5'] = 10;
        $d2[1]['sum_step5'] = 10;
        $d2[2]['sum_step5'] = 10;
        $d2[3]['sum_step5'] = 10;
        $d2[4]['sum_step5'] = 10;
        $inst_nr = 
             $this->_config_db_show_decision_step5( $db_config, $inst_nr, $qs,
                                         $args[1]['proid'], $args[1]['m_num'], 
                                         $args[1]['count'],10,10,$d1,$d2 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $rval = $this->capture_call( $fname, 4485 + strlen($sess->self_url()), 
                                     $args[1] );
        $this->assertEquals( 0, $voted_yet );
        $this->assertEquals( 1, $rval );
        $this->_checkFor_show_decision_step5();

        $this->_check_db( $db_config );
    }

}

define_test_suite( __FILE__ );
?>
