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
# include/followuplib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestFollowuplib.inc,v 1.17 2002/07/02 10:40:59 riessen Exp $
#
#######################################################################

include_once( "../constants.php" );

include_once( 'lib.inc' );
include_once( 'box.inc' );
include_once( 'html.inc' );
include_once( 'followuplib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "session.inc" );
    $GLOBALS[ 'sess' ] = new Session;
    // global translation object
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
} 

class UnitTestFollowuplib
extends UnitTest
{
    function UnitTestFollowuplib( $name ) {
        $this->UnitTest( $name );
    }
    
    function tearDown() {
        // ensure that the next test does not have a predefined global
        // database object
        unset_global( 'db', 'bx' );
    }

    function testFollowup_devel_preview() {
        global $db, $milestone_number, $location, $count, $auth, $bx;
        
        $func_name = 'followup_devel_preview';
        $db_config = new mock_db_configure( 2 );
        $db_q = array( 0 => ("SELECT * FROM milestones WHERE proid='%s' "
                             ."AND number='%s'") );

        $proid = $this->_generate_records( array("proid"), 2 );
        $mt = $this->_generate_records( array("milestone"), 2 );

        $db_config->add_query( sprintf( $db_q[0], $proid[0]["proid"],
                                        $mt[0]["milestone"]));
        $db_config->add_query( sprintf( $db_q[0], $proid[1]["proid"],
                                        $mt[1]["milestone"]), 1);

        $row = $this->_generate_array( array( "goals","release","product",
                                              "payment"), 0 );
        $db_config->add_record( false, 0 );
        // because the first call has no records, the database complains
        // when the function tries to retrieve values that aren't actually
        // there, ignore these errors ....
        $db_config->ignore_errors( MKDB_FIELD_SET, 0);
        $db_config->add_record( $row, 1 );

        // first call, no records
        $auth->set_uname("username_one");
        $milestone_number = $mt[0]["milestone"];
        $location = "this is the location";
        $count = "this is the count";
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( $func_name, 3849 + strlen(timestr(time())),
                             array( &$proid[0]["proid"] ) );
        
        $this->_testFor_common_patterns( 'Milestone Delivery' ); 

        // second call, one record
        $auth->set_uname("username_two");
        $db = new DB_SourceAgency;
        $milestone_number = $mt[1]["milestone"];
        $location = "this is the location";
        $count = "this is the count";
        $bx = $this->_create_default_box();
        $this->capture_call( $func_name, 3874+strlen(timestr(time())),
                                               array(&$proid[1]["proid"]));
        $rows = array( ' ', $row['goals'],
                       timestr_middle(mktimestamp($row["release"])),
                       $row['product'], $row['payment'] );
        $this->_testFor_common_patterns("Milestone Delivery",$rows);

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_referee_preview() {
        global $db, $milestone_number, $location, $count, $auth, $decision,$bx;
        
        $func_name = 'followup_referee_preview';
        $db_config = new mock_db_configure( 2 );

        $db_q = array( 0 => ("SELECT * FROM milestones WHERE proid='%s' "
                             ."AND number='%s'") );

        $proid = $this->_generate_records( array( "proid" ), 2 );
        $mt = $this->_generate_records( array( "milestone" ), 2 );

        $db_config->add_query( sprintf( $db_q[0], $proid[0]["proid"],
                                        $mt[0]["milestone"]));
        $db_config->add_query( sprintf( $db_q[0], $proid[1]["proid"],
                                        $mt[1]["milestone"]), 1);

        $row = $this->_generate_array( array( "goals","release","product",
                                              "payment"), 0 );
        $db_config->add_record( false, 0 );
        // because the first call has no records, the database complains
        // when the function tries to retrieve values that aren't actually
        // there, ignore these errors ....
        $db_config->ignore_errors( MKDB_FIELD_SET, 0);
        $db_config->add_record( $row, 1 );
        
        // first call, no records
        $auth->set_uname("username_one");
        $milestone_number = $mt[0]["milestone"];
        $location = "this is the location";
        $count = "this is the count";
        $decision = "this is the decision";
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( $func_name, 4273+strlen(timestr(time())),
                                               array( &$proid[0]["proid"] ) );
        $this->set_msg( "Test 1" );
        $rows = array( ' ', $decision );
        $cols = array( 'Decision' );
        $this->_testFor_common_patterns( "Milestone Delivery"
                                        ." - Referee Decision", $rows, $cols );
        
        // second call, one record
        $auth->set_uname("username_two");
        $db = new DB_SourceAgency;
        $milestone_number = $mt[1]["milestone"];
        $location = "this is the location";
        $count = "this is the count";
        $decision = "this is the decision";
        $bx = $this->_create_default_box();
        $this->capture_call( $func_name, 4298+strlen(timestr(time())),
                                             array( &$proid[1]["proid"] ) );
        $this->set_msg( "Test 2" );

        $rows = array( ' ', $decision, $row['goals'],
                       timestr_middle(mktimestamp($row["release"])),
                       $row['product'], $row['payment'] );
        $cols = array( 'Decision' );
        $this->_testFor_common_patterns( "Milestone Delivery"
                                        ." - Referee Decision", $rows, $cols );
        
        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_current_milestone() {
        $db_config = new mock_db_configure( 4 );

        $func_name = 'followup_current_milestone';
        $db_q = array( 0 => ("SELECT * FROM follow_up WHERE proid='%s' "
                             ."ORDER BY milestone_number DESC"),
                       1 => ("SELECT MAX(number) FROM milestones WHERE "
                             ."proid='%s'"));

        $proid = $this->_generate_records( array( "proid" ), 4 );

        $db_config->add_query( sprintf( $db_q[0], $proid[0]["proid"]), 0 );
        $db_config->add_query( sprintf( $db_q[0], $proid[1]["proid"]), 1 );

        $db_config->add_query( sprintf( $db_q[0], $proid[2]["proid"]), 2 );
        $db_config->add_query( sprintf( $db_q[1], $proid[2]["proid"]), 2 );

        $db_config->add_query( sprintf( $db_q[0], $proid[3]["proid"]), 3 );
        $db_config->add_query( sprintf( $db_q[1], $proid[3]["proid"]), 3 );

        $row=$this->_generate_records(array("milestone_number","iteration"),3);
        $row_q2 = $this->_generate_records( array( "MAX(number)" ), 2 );

        // first call, no records, return value 1
        $db_config->add_num_row( 0, 0 );
        $this->assertEquals( 1,$this->capture_call( $func_name, 0,$proid[0])); 

        // second call, iteration == 0
        $db_config->add_num_row( 1, 1 );
        $row[0]["iteration"] = 1;
        $db_config->add_record( $row[0], 1 );
        $this->assertEquals( $row[0]["milestone_number"],
                               $this->capture_call( $func_name, 0, $proid[1]));

        // third call, iteration == 1, MAX(number) is > than $temp
        $db_config->add_num_row( 1, 2 );
        $row[1]["milestone_number"] = 3;
        $row[1]["iteration"] = 0;
        $row_q2[0]["MAX(number)"] = 4;
        $db_config->add_record( $row[1], 2 );
        $db_config->add_record( $row_q2[0], 2 );
        $this->assertEquals( $row[1]["milestone_number"],
                               $this->capture_call( $func_name, 0, $proid[2]));

        // fourth call, iteration == 1, MAX(number) is < than $temp
        $db_config->add_num_row( 1, 3 );
        $row[2]["milestone_number"] = 4;
        $row[2]["iteration"] = 0;
        $row_q2[1]["MAX(number)"] = 3;
        $db_config->add_record( $row[2], 3 );
        $db_config->add_record( $row_q2[1], 3 );
        $this->assertEquals(-1, $this->capture_call($func_name,0,$proid[3]));

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_current_count() {
        $db_config = new mock_db_configure( 2 );

        $func_name = 'followup_current_count';
        $db_q = array( 0 => ("SELECT count FROM follow_up WHERE "
                             ."milestone_number='%s' AND proid='%s'"));

        $q_dat = $this->_generate_records( array( "proid", "ms_num" ), 2 );

        $db_config->add_query( sprintf( $db_q[0], $q_dat[0]["ms_num"],
                                        $q_dat[0]["proid"]), 0 );
        $db_config->add_query( sprintf( $db_q[0], $q_dat[1]["ms_num"],
                                        $q_dat[1]["proid"]), 1 );

        $row = $this->_generate_records(array("count"),1);

        // first call, no records, return value 1
        $db_config->add_num_row( 0, 0 );
        $this->assertEquals(1,$this->capture_call( $func_name,0,&$q_dat[0]));
        
        // second call, 1 record
        $db_config->add_num_row( 1, 1 );
        $db_config->add_record( $row[0], 1 );
        $this->assertEquals( $row[0]["count"], 
                            $this->capture_call( $func_name, 0, &$q_dat[1] ));
        
        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_location() {
        $db_config = new mock_db_configure( 2 );

        $func_name = 'followup_location';
        $db_q = array( 0 => ("SELECT location FROM follow_up WHERE "
                             ."milestone_number='%s' AND count='%s' "
                             ."AND proid='%s'"));
        $q_dat = $this->_generate_records(array("proid","ms_num","count"),2);

        $db_config->add_query( sprintf( $db_q[0], $q_dat[0]["ms_num"],
                                        $q_dat[0]["count"], 
                                        $q_dat[0]["proid"]), 0 );
        $db_config->add_query( sprintf( $db_q[0], $q_dat[1]["ms_num"],
                                        $q_dat[1]["count"], 
                                        $q_dat[1]["proid"]), 1 );

        $row = $this->_generate_records(array("location"),1);

        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_record( $row[0], 1 );

        $this->assertEquals( 0, $this->capture_call( $func_name,0,$q_dat[0]));

        $this->assertEquals( $row[0]["location"],
                             $this->capture_call( $func_name, 0, $q_dat[1]));

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_insert() {
        global $db;

        $func_name = 'followup_insert';
        $db_config = new mock_db_configure( 1 );
        $db = new DB_SourceAgency;

        $db_q = array(0=>("INSERT follow_up SET proid='%s',milestone_number="
                          ."'%s',iteration='%s',location='%s',count='%s'"),
                      1=>("UPDATE follow_up SET iteration='%s', location="
                          ."'%s', count='%s' WHERE proid='%s' AND "
                          ."milestone_number='%s'"));

        $q_dat = $this->_generate_records( array("proid","ms_num","iteration",
                                                 "location","count"), 4);

        // first call, iteration == 0 
        $q_dat[0]["ms_num"] = 10;
        $q_dat[0]["iteration"] = 0;
        $db_config->add_query( sprintf( $db_q[0], $q_dat[0]["proid"], 
                               $q_dat[0]["ms_num"], $q_dat[0]["iteration"],
                               $q_dat[0]["location"], $q_dat[0]["count"]), 0 );
        $this->capture_call( $func_name, 0, &$q_dat[0] );

        // second call, milestone_number and iteration equal 1
        $q_dat[1]["ms_num"] = 1;
        $q_dat[1]["iteration"] = 1;
        $db_config->add_query( sprintf( $db_q[0], $q_dat[1]["proid"], 
                               $q_dat[1]["ms_num"], $q_dat[1]["iteration"],
                               $q_dat[1]["location"], $q_dat[1]["count"]), 0 );
        $this->capture_call( $func_name, 0, &$q_dat[1] );

        // third call
        $q_dat[2]["ms_num"] = 1;
        $q_dat[2]["iteration"] = 2;
        $db_config->add_query( sprintf( $db_q[1], $q_dat[2]["iteration"], 
                               $q_dat[2]["location"], $q_dat[2]["count"],
                               $q_dat[2]["proid"], $q_dat[2]["ms_num"]), 0 );
        $this->capture_call( $func_name, 0, &$q_dat[2] );

        // fourth call
        $q_dat[3]["ms_num"] = 2;
        $q_dat[3]["iteration"] = 1;
        $db_config->add_query( sprintf( $db_q[1], $q_dat[3]["iteration"], 
                               $q_dat[3]["location"], $q_dat[3]["count"],
                               $q_dat[3]["proid"], $q_dat[3]["ms_num"]), 0 );
        $this->capture_call( $func_name, 0, &$q_dat[3] );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_referee_decision() {
        $exp[0] = "<select name=\"decision\">";
        $exp[1] = "<option value=\"accept\">accept";
        $exp[2] = "<option value=\"minor\">minor";
        $exp[3] = "<option value=\"severe\">severe";
        $exp[4] = "</select>";

        $func_name = 'followup_referee_decision';
        $this->assertEquals( implode("\n",$exp)+"\n", 
                             $this->capture_call($func_name,0,array('fubar')));
        $this->assertEquals( implode("\n",$exp)+"\n", 
                             $this->capture_call($func_name,0,array('')));

        $o = $exp[1]; $exp[1] = "<option selected value=\"accept\">accept";
        $this->assertEquals(implode("\n",$exp)+"\n", 
                            $this->capture_call($func_name,0,array('accept')));
        $exp[1] = $o;

        $o = $exp[2]; $exp[2] = "<option selected value=\"minor\">minor";
        $this->assertEquals( implode("\n",$exp)+"\n", 
                             $this->capture_call($func_name,0,array('minor')));
        $exp[2] = $o;

        $o = $exp[3]; $exp[3] = "<option selected value=\"severe\">severe";
        $this->assertEquals(implode("\n",$exp)+"\n", 
                            $this->capture_call($func_name,0,array('severe')));
        $exp[3] = $o;
    }
    
    function _config_db_followup_milestone_schedule( &$db_config, $inst_nr, 
       $proid, $milestone_number, $days_left, $current_iteration, $count,
       $uname, $is_main_developer, $is_accepted_sponsor, $is_referee ){
        $qs=array( 0=>("SELECT release FROM milestones WHERE number='%s' AND "
                       ."proid='%s'" ),
                   1=>("SELECT milestone_number,iteration FROM follow_up "
                       ."WHERE proid='%s'"),
                   2=>("SELECT * FROM configure WHERE proid='%s' AND "
                       ."developer='%s'"),
                   3=>("SELECT * FROM sponsoring WHERE proid='%s' AND status"
                       ."='A' AND sponsor='%s'"),
                   4=>("SELECT * FROM referees WHERE proid='%s' AND referee"
                       ."='%s'"),
                   5=>( "SELECT * FROM developing WHERE proid='%s' AND "
                        ."status='A' AND developer='%s'"));

        $d=$this->_generate_records( array('release'), 10 );
        $d2=$this->_generate_records(array('milestone_number','iteration'),10);

        /** first query **/
        $db_config->add_query( sprintf( $qs[0], $milestone_number,
                                             $proid), $inst_nr );
        $di = getdate( time() + ($days_left * 3600 * 24) );
        $d[0]['release']=date_to_timestamp($di['mday'],$di['mon'],$di['year']);
        $db_config->add_record( $d[0], $inst_nr );

        /** current_iteration query **/
        $inst_nr++;
        $db_config->add_query( sprintf( $qs[1], $proid), $inst_nr );
        $db_config->add_num_row( 1, $inst_nr );
        $d2[0]['milestone_number'] = 1;
        $d2[0]['iteration'] = $current_iteration;
        $db_config->add_record( $d2[0], $inst_nr );

        if ( $current_iteration == 0 ) {
            /** need to add a query for the is_main_developer call **/
            $inst_nr++;
            $db_config->add_query( sprintf($qs[5],$proid,$uname), $inst_nr);
            $db_config->add_num_row( 1, $inst_nr );
            $inst_nr++;
            $db_config->add_query( sprintf($qs[2], $proid, $uname), $inst_nr);
            $db_config->add_num_row( ($is_main_developer ? 1 : 0), $inst_nr);
        }

        if ( $current_iteration == 1 || $current_iteration == 3 ) {
            /** setup database for a is_accepted_sponsor call **/
            $inst_nr++;
            $db_config->add_query(sprintf( $qs[3], $proid, $uname ), $inst_nr);
            $db_config->add_num_row( ($is_accepted_sponsor ? 1:0), $inst_nr);
        }
        
        if ( $current_iteration == 2 ) {
            /** setup database for a is_referee call **/
            $inst_nr++;
            $db_config->add_query(sprintf( $qs[4], $proid, $uname ), $inst_nr);
            $db_config->add_num_row( ($is_referee ? 1:0), $inst_nr);
        }

        return ++$inst_nr;
    }

    function _checkFor_followup_milestone_schedule( $proid, $milestone_number, 
        $days_left, $current_iteration, $count, $is_main_developer, 
        $is_accepted_sponsor, $is_referee ) {

        $this->_checkFor_a_box( 'Current Milestone Schedule Status' );

        $str = ("<p>Sponsors have deciced that milestone number "
                ."<b>$milestone_number</b> requires minor modifications "
                ."in order to achieve their requirements.");
        if ( $count == 1 ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        $str = ("<p>The milestone has not been posted by the main developer");
        if ( $current_iteration != 0 ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        $str= ( "<p>Number of days left until <b>milestone number "
                ."$milestone_number</b> is released: <b>"
                .floor($days_left)." days</b>.<p>" );
        if ( $current_iteration != 0 || $days_left <= 1 ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        $str =( "<p>Milestone number <b>$milestone_number</b> should have "
                ."been released <b>".ceil(-$days_left)."</b> days ago.<p>");
        if ( $current_iteration != 0 || $days_left >= -0.5 ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) ); 
        
        $str =("<p>Milestone number <b>$milestone_number</b> should be "
               ."released <b>today</b>.<p>");
        if ( $current_iteration != 0 || $days_left < -0.5 || $days_left > 1) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) ); 
        
        $str = ("You are the main developer. You can post the milestone "
                .html_link("step5_devel.php3",array("proid"=>$proid),
                           "in this page") );
        if ( $current_iteration != 0 || !$is_main_developer ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) ); 

        $str = ("<p>The milestone number <b>$milestone_number</b> has been "
                ."posted. Sponsors are studying whether to accept it or not.");
        if ( $current_iteration != 1 ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) ); 
        
        $str = ("<p>You are an accepted sponsor for this project. You can "
                ."decide whether the milestone fullfil its goals or not "
                .html_link("step5_sponsor.php3",array("proid"=>$proid),
                           "in this page"));
        if ( $current_iteration != 1 || !$is_accepted_sponsor ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) ); 

        $str = ("<p>Sponsors have rejected the current milestone (milestone "
                ."number <b>$milestone_number</b>).\n<p>The referee is "
                ."studying it and will give a final decision soon.\n");
        if ( $current_iteration != 2 ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) ); 
        
        $str = ("<p>You are this project's referee. You can decide whether "
                ."the milestone fullfil its goals or not "
                .html_link("step5_referee.php3",array("proid"=>$proid),
                           "in this page"));
        if ( $current_iteration != 2 || !$is_referee ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) ); 

        $str = ("The referee has decided that the milestone number <b>"
                ."$milestone_number</b> posted by the developer does not "
                ."fulfill the promised goals.\n<p>Sponsors are deciding "
                ."what is going to happen to the project.\n");
        if ( $current_iteration != 3 ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) ); 
        
        $str =( "<p>You are an accepted sponsor for this project. You can "
                ."decide what will happen to this project "
                .html_link("step5_sponsor.php3",array("proid"=>$proid),
                           "in this page"));
        if ( $current_iteration != 3 || !$is_accepted_sponsor ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) ); 
        
        $str = "The follow_up process is finished. Now it is time for rating.";
        if ( $current_iteration != 5 ) {
            $this->reverse_next_test();
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) ); 
    }

    function testFollowup_milestone_schedule() {
        global $db, $bx, $auth;

        $db_config = new mock_db_configure( 218 );
        $db_inst_nr = 0;

        $uname = 'this si the username';
        $auth->set_uname( $uname );
        $auth->set_perm( 'hell yes!' );

        /**
           length ---------------------------------.
           is_referee --------------------------.  |
           is_accepted_sponsor --------------.  |  |
           is_main_developer -------------.  |  |  |
           days_left ----------------.    |  |  |  |
           count ----------------.   |    |  |  |  |
           current_iteration -.  |   |    |  |  |  |
                              |  |   |    |  |  |  |
                              V  V  VVVV  V  V  V  V 
        */
        $tests = array( array(0, 0, -1.5, 1, 0, 0, 1102 ),
                        array(0, 0,  1.5, 1, 0, 0, 1111 ),
                        array(0, 0,  0.5, 1, 0, 0, 1090 ),
                        array(0, 0,  1.5, 0, 0, 0, 990 ),
                        array(0, 0,  0.5, 0, 0, 0, 969 ),
                        array(0, 0, -1.5, 0, 0, 0, 981 ),
                        array(0, 1, -1.5, 1, 0, 0, 961 ),
                        array(0, 1,  1.5, 1, 0, 0, 970 ),
                        array(0, 1,  0.5, 1, 0, 0, 949 ),
                        array(0, 1,  1.5, 0, 0, 0, 849 ),
                        array(0, 1,  0.5, 0, 0, 0, 828 ),
                        array(0, 1, -1.5, 0, 0, 0, 840 ),
                        /** current iteration == 1 **/
                        array(1, 0,    0, 0, 1, 0, 1126 ),
                        array(1, 1,    0, 0, 1, 0, 985 ),
                        array(1, 0,    0, 0, 0, 0, 947 ),
                        array(1, 1,    0, 0, 0, 0, 806 ),
                        array(1, 0,    1, 1, 1, 1, 1126 ),
                        array(1, 1,    1, 1, 1, 0, 985 ),
                        array(1, 0,    1, 0, 0, 1, 947 ),
                        array(1, 1,    1, 0, 0, 0, 806 ),
                        array(1, 0,    0, 1, 1, 1, 1126 ),
                        array(1, 1,    0, 1, 1, 0, 985 ),
                        array(1, 0,    0, 0, 0, 1, 947 ),
                        array(1, 1,    0, 0, 0, 0, 806 ),
                        /** current iteration == 2 **/
                        array(2, 0,    0, 0, 0, 1, 1156 ),
                        array(2, 1,    0, 0, 0, 1, 1015 ),
                        array(2, 0,    0, 0, 0, 0, 991 ),
                        array(2, 1,    0, 0, 0, 0, 850 ),
                        array(2, 0,    1, 1, 1, 1, 1156 ),
                        array(2, 1,    1, 1, 0, 1, 1015 ),
                        array(2, 0,    1, 0, 1, 0, 991 ),
                        array(2, 1,    1, 0, 0, 0, 850 ),
                        array(2, 0,    0, 1, 1, 1, 1156 ),
                        array(2, 1,    0, 1, 0, 1, 1015 ),
                        array(2, 0,    0, 0, 1, 0, 991 ),
                        array(2, 1,    0, 0, 0, 0, 850 ),
                        array(2, 0,    1, 1, 1, 1, 1156 ),
                        array(2, 1,    1, 1, 0, 1, 1015 ),
                        array(2, 0,    1, 0, 1, 0, 991 ),
                        array(2, 1,    1, 0, 0, 0, 850 ),
                        /** current iteration == 3 **/
                        array(3, 0,    0, 0, 1, 0, 1197 ),
                        array(3, 1,    0, 0, 1, 0, 1056 ),
                        array(3, 0,    0, 0, 0, 0, 1032 ),
                        array(3, 1,    0, 0, 0, 0, 891 ),
                        array(3, 0,    1, 1, 1, 1, 1197 ),
                        array(3, 1,    1, 1, 1, 0, 1056 ),
                        array(3, 0,    1, 0, 0, 1, 1032 ),
                        array(3, 1,    1, 0, 0, 0, 891 ),
                        array(3, 0,    0, 1, 1, 1, 1197 ),
                        array(3, 1,    0, 1, 1, 0, 1056 ),
                        array(3, 0,    0, 0, 0, 1, 1032 ),
                        array(3, 1,    0, 0, 0, 0, 891 ),
                        array(3, 0,    1, 1, 1, 1, 1197 ),
                        array(3, 1,    1, 1, 1, 0, 1056 ),
                        array(3, 0,    1, 0, 0, 1, 1032 ),
                        array(3, 1,    1, 0, 0, 0, 891 ),
                        /** current iteration == 4 **/
                        array(4, 1,    0, 0, 1, 0, 689 ),
                        array(4, 0,    0, 0, 1, 0, 830 ),
                        array(4, 0,    1, 0, 1, 0, 830 ),
                        array(4, 0,    1, 0, 1, 0, 830 ),
                        array(4, 0,    0, 1, 1, 0, 830 ),
                        array(4, 1,    0, 1, 1, 0, 689 ),
                        array(4, 0,    1, 1, 1, 0, 830 ),
                        array(4, 1,    1, 1, 1, 0, 689 ),
                        array(4, 0,    0, 0, 0, 0, 830 ),
                        array(4, 1,    0, 0, 0, 1, 689 ),
                        array(4, 0,    1, 0, 0, 0, 830 ),
                        array(4, 1,    1, 0, 0, 0, 689 ),
                        array(4, 0,    0, 1, 0, 0, 830 ),
                        /** current iteration == 5 **/
                        array(5, 0,    0, 0, 0, 0, 891 ),
                        array(5, 1,    0, 0, 0, 0, 750 ),
                        array(5, 0,    1, 0, 0, 1, 891 ),
                        array(5, 1,    0, 1, 0, 1, 750 ),
                        array(5, 0,    0, 0, 1, 0, 891 ),
                        array(5, 1,    1, 1, 0, 1, 750 )
                     );
        foreach ( $tests as $tst ) {
            $current_iteration = $tst[0];
            $count = $tst[1];
            $days_left = $tst[2];
            $is_main_developer = $tst[3];
            $is_accepted_sponsor = $tst[4];
            $is_referee = $tst[5];
            $exp_length = $tst[6];
            
            $this->push_msg( "C=$count, CI=$current_iteration, "
                             ."DL=$days_left, MD=$is_main_developer, "
                             ."AS=$is_accepted_sponsor, R=$is_referee");
            $args = $this->_generate_array(array('proid', 'milestone_number',
                                                 'count'), 8 );
            $args['count'] = $count;
            $db_inst_nr = 
                 $this->_config_db_followup_milestone_schedule( 
                     $db_config, $db_inst_nr, $args['proid'], 
                     $args['milestone_number'], $days_left,
                     $current_iteration, $args['count'],  
                     $uname, $is_main_developer, $is_accepted_sponsor,
                     $is_referee);
          
            $db = new DB_SourceAgency;
            $bx = $this->_create_default_box();
            $this->capture_call( 'followup_milestone_schedule', $exp_length, 
                                 $args );

            $this->_checkFor_followup_milestone_schedule(
                $args['proid'],$args['milestone_number'], $days_left,  
                $current_iteration, $args['count'], $is_main_developer, 
                $is_accepted_sponsor, $is_referee );
            
            $this->pop_msg();
        }
        
        $this->_check_db( $db_config );
    }

    function testFollowup_devel_form() {
        global $bx, $db, $t, $sess, $milestone_number, $location, $count;

        $proid = 'this ist he projc';
        $milestone_number = 'this sit he milestonenumber';
        $location = 'thsi is the location';
        $count = 'this isthe count';

        $q = "SELECT * FROM milestones WHERE proid='%s' AND number='%s'";
        $d = $this->_generate_array( array( 'goals','release','product',
                                            'payment' ), 1 );
        $db_config = new mock_db_configure( 1 );
        $db_config->add_query( sprintf( $q, $proid, $milestone_number ), 0 );
        $db_config->add_record( $d, 0 );
        
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'followup_devel_form', 
                             4827 + strlen($sess->self_url()), array($proid));

        $this->_checkFor_a_box( 'Milestone Delivery' );
        $this->_checkFor_a_form( 'PHP_SELF', array('proid'=>$proid), 'POST' );
        $this->_checkFor_columns( 2 );
        $this->_checkFor_submit_preview_buttons();
        
        $tv=array('Milestone Number'=>$milestone_number,
                  'Goals'=>$d['goals'],
                  'Release date' =>timestr_middle(mktimestamp($d['release'])),
                  'Product'=>$d['product'],
                  'Payment'=>$d['payment'].'%',
                  array('Location','<b>%s</b> (255): ')
                             =>html_input_text("location",40,255,$location),
                  'Count'=> $count.(" <i>(this is the number of times you "
                                    ."have submitted this milestone)</i>"));
        while ( list( $key, $val ) = each( $tv ) ) {
            if ( is_array( $key ) ) {
                $this->_checkFor_column_titles( array( $key[0] ),
                                                'right','30%','',$key[1]);
            } else {
                $this->_checkFor_column_titles(array($key), 'right','30%','',
                                                         '<b>%s</b>: ');
            }
            $this->_checkFor_column_values( array( $val ) );
        }
        
        $this->_check_db( $db_config );
    }

    function testFollowup_referee_form() {
        global $bx, $t, $db, $sess;
        global $milestone_number, $location, $count, $decision;

        $proid = 'this is the proid';
        $milestone_number = 'this is the milestone number';
        $location = 'this ios teh location';
        $count = 'thsi is the count';
        $decision = 'this  is the decision';

        $q = "SELECT * FROM milestones WHERE proid='%s' AND number='%s'";
        $d = $this->_generate_array( array('goals','release','product',
                                           'payment'), 1 );

        $db_config = new mock_db_configure( 1 );
        $db_config->add_query( sprintf( $q, $proid, $milestone_number ), 0 );
        $db_config->add_record( $d, 0 );
        
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'followup_referee_form', 
                             5290 + strlen($sess->self_url()), array($proid));
        
        $this->_checkFor_a_box( 'Milestone Delivery - Referee Decision' );
        $this->_checkFor_a_form( 'PHP_SELF', array('proid' => $proid ),'POST');
        $this->_checkFor_columns( 2 );

        $tv=array('Milestone Number'=>$milestone_number,
                  'Goals'=>$d['goals'],
                  'Release date' =>timestr_middle(mktimestamp($d['release'])),
                  'Product'=>$d['product'],
                  'Payment'=>$d['payment'].'%',
                  'Location'=>html_link($location, array(), $location),
                  'Count'=> $count,
                  'Decision' => followup_referee_decision($decision));
        while ( list( $key, $val ) = each( $tv ) ) {
            if ( is_array( $key ) ) {
                $this->_checkFor_column_titles( array( $key[0] ),
                                                'right','30%','',$key[1]);
            } else {
                $this->_checkFor_column_titles(array($key), 'right','30%','',
                                                         '<b>%s</b>: ');
            }
            $this->_checkFor_column_values( array( $val ) );
        }

        $this->_check_db( $db_config );
    }

    function _testFor_common_patterns( $title, $row=array(), $col=array() ) {
        global $t, $auth;
        global $milestone_number, $location, $count;

        $this->_checkFor_a_box( $title );
        $this->_checkFor_a_box('PREVIEW',"<center><b>%s</b></center>");
        $this->_testFor_pattern( 
                     $this->_to_regexp(lib_nick($auth->auth["uname"])));
        $this->_checkFor_columns( 2 );

        foreach ( array_merge( $col, array( 'Milestone number', 'Goals', 
                                            'Release Date','Product',
                                            'Payment', 'Location', 'Count'))
                  as $val ) {
            $this->_testFor_box_column("right","30%","","<b>"
                                                 .$t->translate($val).":</b>");
        }

        $this->_checkFor_column_values(  
                      array_merge( $row, array( $milestone_number, $count,
                      html_link( $location, array(), $location ))));
    }
}

define_test_suite( __FILE__ );

?>
