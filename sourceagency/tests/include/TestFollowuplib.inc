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
# $Id: TestFollowuplib.inc,v 1.2 2002/02/07 12:24:17 riessen Exp $
#
######################################################################\

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "session.inc" );
    $sess = new Session;
    include_once( 'box.inc' );
    $bx = new box;
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");
} 

include_once( 'lib.inc' );
include_once( 'box.inc' );
include_once( 'html.inc' );
include_once( 'followuplib.inc' );

class UnitTestFollowuplib
extends UnitTest
{
    var $_com_pats_1, $_com_pats_2;

    function UnitTestFollowuplib( $name ) {
        $this->UnitTest( $name );
    
        // common patterns for the first two tests ...
        $this->_com_pats_1 = 
             array(0=>("<font color=\"#000000\"><b><center><b>PREVIEW<\/b>"
                       ."<\/center><\/b><\/font>"),
                   1=>("<b><b>by username_one<\/b>"),
                   2=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                       ."#FFFFFF\"><b>Milestone number:<\/b><\/td>\n<!-- "
                       ."Column finishes -->\n<!-- New Column starts -->"
                       ."\n<td align=\"left\" width=\"70%\" bgcolor=\""
                       ."#FFFFFF\">milestone_0<\/td>"),
                   3=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                       ."#FFFFFF\"><b>Goals:<\/b><\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td "
                       ."align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF"
                       ."\"><\/td>"),
                   4=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                       ."#FFFFFF\"><b>Release Date:<\/b><\/td>\n<!-- "
                       ."Column finishes -->\n<!-- New Column starts -->"
                       ."\n<td align=\"left\" width=\"70%\" bgcolor=\""
                       ."#FFFFFF\"><\/td>"),
                   5=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                       ."#FFFFFF\"><b>Product:<\/b><\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td "
                       ."align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF"
                       ."\"><\/td>"),
                   6=>("<td align=\"right\" width=\"30%\" bgcolor="
                       ."\"#FFFFFF\"><b>Payment:<\/b><\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td "
                       ."align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF"
                       ."\"><\/td>"),
                   7=>("<td align=\"right\" width=\"30%\" bgcolor="
                       ."\"#FFFFFF\"><b>Location:<\/b><\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td "
                       ."align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\""
                       ."><a href=\"this is the location\">this is the "
                       ."location<\/a>"),
                   8=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                       ."#FFFFFF\"><b>Count:<\/b><\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td "
                       ."align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF"
                       ."\">this is the count<\/td>"));

        $this->_com_pats_2 =
             array(0=>("<font color=\"#000000\"><b><center><b>PREVIEW<\/b>"
                       ."<\/center><\/b><\/font>"),
                   1=>("<b><b>by username_two<\/b>"),
                   2=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Count:<\/b><\/td>\n<!-- Column finishes -->"
                       ."\n<!-- New Column starts -->\n<td align=\"left\" "
                       ."width=\"70%\" bgcolor=\"#FFFFFF\">this is the "
                       ."count<\/td>"),
                   3=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Location:<\/b><\/td>\n<!-- Column finishes "
                       ."-->\n<!-- New Column starts -->\n<td align=\"left\""
                       ." width=\"70%\" bgcolor=\"#FFFFFF\"><a href=\"this "
                       ."is the location\">this is the location<\/a>"),
                   4=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Payment:<\/b><\/td>\n<!-- Column finishes -->"
                       ."\n<!-- New Column starts -->\n<td align=\"left\" "
                       ."width=\"70%\" bgcolor=\"#FFFFFF\">payment_0<\/td>"),
                   5=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Product:<\/b><\/td>\n<!-- Column finishes -->"
                       ."\n<!-- New Column starts -->\n<td align=\"left\" "
                       ."width=\"70%\" bgcolor=\"#FFFFFF\">product_0"
                       ."<\/td>\n"),
                   6=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Release Date:<\/b><\/td>\n<!-- Column finishes"
                       ." -->\n<!-- New Column starts -->\n<td align=\"left\""
                       ." width=\"70%\" bgcolor=\"#FFFFFF\"><\/td>"),
                   7=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Goals:<\/b><\/td>\n<!-- Column finishes -->"
                       ."\n<!-- New Column starts -->\n<td align=\"left\" "
                       ."width=\"70%\" bgcolor=\"#FFFFFF\">goals_0<\/td>"),
                   8=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Milestone number:<\/b><\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td "
                       ."align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\">"
                       ."milestone_1<\/td>"));
    }
    
    function testFollowup_devel_preview() {
        global $db, $milestone_number, $location, $count, $auth;
        
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
        capture_reset_and_start();
        $milestone_number = $mt[0]["milestone"];
        $location = "this is the location";
        $count = "this is the count";
        $db = new DB_SourceAgency;
        followup_devel_preview($proid[0]["proid"]);
        $text = capture_stop_and_get();
        $this->_testFor_length( 2869 + strlen(timestr(time())));
        
        $pats = array_merge( $this->_com_pats_1, array( 
            9=>("<font color=\"#000000\"><b>Milestone Delivery"
                ."<\/b><\/font>")) );
        $this->_testFor_patterns( $text, $pats, 10 );

        // second call, one record
        $auth->set_uname("username_two");
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        $milestone_number = $mt[1]["milestone"];
        $location = "this is the location";
        $count = "this is the count";
        followup_devel_preview($proid[1]["proid"]);
        $text = capture_stop_and_get();
        $this->_testFor_length( 2894 + strlen(timestr(time())) );
 
        $pats = array_merge( $this->_com_pats_2, array( 
            9=>("<font color=\"#000000\"><b>Milestone Delivery<\/b>"
                ."<\/font>")) );
        $this->_testFor_patterns( $text, $pats, 10 );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_referee_preview() {
        global $db, $milestone_number, $location, $count, $auth, $decision;
        
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
        capture_reset_and_start();
        $milestone_number = $mt[0]["milestone"];
        $location = "this is the location";
        $count = "this is the count";
        $decision = "this is the decision";
        $db = new DB_SourceAgency;
        followup_referee_preview($proid[0]["proid"]);
        $text = capture_stop_and_get();
        $this->_testFor_length( 3185 + strlen(timestr(time())));

        $pats = array_merge( $this->_com_pats_1, array( 
            9=>("<font color=\"#000000\"><b>Milestone Delivery"
                ." - Referee Decision<\/b><\/font>"),
            10=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF\"><b>"
                 ."Decision:<\/b><\/td>\n<!-- Column finishes -->\n<!-- New "
                 ."Column starts -->\n<td align=\"left\" width=\"70%\" "
                 ."bgcolor=\"#FFFFFF\">this is the decision<\/td>\n")));
        $this->_testFor_patterns( $text, $pats, 11 );

        // second call, one record
        $auth->set_uname("username_two");
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        $milestone_number = $mt[1]["milestone"];
        $location = "this is the location";
        $count = "this is the count";
        $decision = "this is the decision";
        followup_referee_preview($proid[1]["proid"]);
        $text = capture_stop_and_get();
        $this->_testFor_length( 3210 + strlen(timestr(time())) );

        $pats = array_merge( $this->_com_pats_2, array( 
            9=>("<font color=\"#000000\"><b>Milestone Delivery"
                ." - Referee Decision<\/b><\/font>"),
            10=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF\"><b>"
                 ."Decision:<\/b><\/td>\n<!-- Column finishes -->\n<!-- New "
                 ."Column starts -->\n<td align=\"left\" width=\"70%\" "
                 ."bgcolor=\"#FFFFFF\">this is the decision<\/td>\n")));
        $this->_testFor_patterns( $text, $pats, 11 );
        
        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

//      function testFollowup_devel_form() {
//      }
//      function testFollowup_referee_form() {
//      }

    function testFollowup_current_milestone() {
        $db_config = new mock_db_configure( 4 );

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
        $this->assertEquals( 1, 
                             followup_current_milestone($proid[0]["proid"]));

        // second call, iteration == 0
        $db_config->add_num_row( 1, 1 );
        $row[0]["iteration"] = 1;
        $db_config->add_record( $row[0], 1 );
        $this->assertEquals( $row[0]["milestone_number"],
                             followup_current_milestone($proid[1]["proid"]));

        // third call, iteration == 1, MAX(number) is > than $temp
        $db_config->add_num_row( 1, 2 );
        $row[1]["milestone_number"] = 3;
        $row[1]["iteration"] = 0;
        $row_q2[0]["MAX(number)"] = 4;
        $db_config->add_record( $row[1], 2 );
        $db_config->add_record( $row_q2[0], 2 );
        $this->assertEquals( $row[1]["milestone_number"],
                             followup_current_milestone($proid[2]["proid"]));

        // fourth call, iteration == 1, MAX(number) is < than $temp
        $db_config->add_num_row( 1, 3 );
        $row[2]["milestone_number"] = 4;
        $row[2]["iteration"] = 0;
        $row_q2[1]["MAX(number)"] = 3;
        $db_config->add_record( $row[2], 3 );
        $db_config->add_record( $row_q2[1], 3 );
        $this->assertEquals( -1,
                             followup_current_milestone($proid[3]["proid"]));

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_current_count() {
        $db_config = new mock_db_configure( 2 );

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
        $this->assertEquals( 1, followup_current_count( $q_dat[0]["proid"],
                                                        $q_dat[0]["ms_num"]));
        
        // second call, 1 record
        $db_config->add_num_row( 1, 1 );
        $db_config->add_record( $row[0], 1 );
        $this->assertEquals( $row[0]["count"], 
                             followup_current_count( $q_dat[1]["proid"],
                                                     $q_dat[1]["ms_num"]));
        
        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_location() {
        $db_config = new mock_db_configure( 1 );

        $db_q = array( 0 => ("SELECT location FROM follow_up WHERE "
                             ."milestone_number='%s' AND count='%s'"));

        $q_dat = $this->_generate_records( array( "ms_num", "count" ), 1 );

        $db_config->add_query( sprintf( $db_q[0], $q_dat[0]["ms_num"],
                                        $q_dat[0]["count"]), 0 );

        $row = $this->_generate_records(array("location"),1);

        $db_config->add_record( $row[0], 0 );

        // only one call, although we should also test what is returned
        // when no record exists
        $this->assertEquals( $row[0]["location"],
                             followup_location( "does get used", 
                                                $q_dat[0]["ms_num"],
                                                $q_dat[0]["count"]));

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_insert() {
        global $db;

        $db_config = new mock_db_configure( 1 );
        $db = new DB_SourceAgency;

        $db_q = array(0=>("INSERT follow_up SET proid='%s',milestone_number="
                          ."'%s',iteration='%s',location='%s',count='%s'"),
                      1=>("UPDATE follow_up SET iteration='%s', location="
                          ."'%s', count='%s' WHERE proid='%s' AND "
                          ."milestone_number='%s'"));

        $q_dat = $this->_generate_records( array("proid","ms_num","count",
                                                 "iteration", "location"), 4);

        // first call, iteration == 0 
        $q_dat[0]["ms_num"] = 10;
        $q_dat[0]["iteration"] = 0;
        $db_config->add_query( sprintf( $db_q[0], $q_dat[0]["proid"], 
                               $q_dat[0]["ms_num"], $q_dat[0]["iteration"],
                               $q_dat[0]["location"], $q_dat[0]["count"]), 0 );
        followup_insert( $q_dat[0]["proid"], $q_dat[0]["ms_num"],
                         $q_dat[0]["iteration"], $q_dat[0]["location"],
                         $q_dat[0]["count"]);

        // second call, milestone_number and iteration equal 1
        $q_dat[1]["ms_num"] = 1;
        $q_dat[1]["iteration"] = 1;
        $db_config->add_query( sprintf( $db_q[0], $q_dat[1]["proid"], 
                               $q_dat[1]["ms_num"], $q_dat[1]["iteration"],
                               $q_dat[1]["location"], $q_dat[1]["count"]), 0 );
        followup_insert( $q_dat[1]["proid"], $q_dat[1]["ms_num"],
                         $q_dat[1]["iteration"], $q_dat[1]["location"],
                         $q_dat[1]["count"]);

        // third call
        $q_dat[2]["ms_num"] = 1;
        $q_dat[2]["iteration"] = 2;
        $db_config->add_query( sprintf( $db_q[1], $q_dat[2]["iteration"], 
                               $q_dat[2]["location"], $q_dat[2]["count"],
                               $q_dat[2]["proid"], $q_dat[2]["ms_num"]), 0 );
        followup_insert( $q_dat[2]["proid"], $q_dat[2]["ms_num"],
                         $q_dat[2]["iteration"], $q_dat[2]["location"],
                         $q_dat[2]["count"]);

        // fourth call
        $q_dat[3]["ms_num"] = 2;
        $q_dat[3]["iteration"] = 1;
        $db_config->add_query( sprintf( $db_q[1], $q_dat[3]["iteration"], 
                               $q_dat[3]["location"], $q_dat[3]["count"],
                               $q_dat[3]["proid"], $q_dat[3]["ms_num"]), 0 );
        followup_insert( $q_dat[3]["proid"], $q_dat[3]["ms_num"],
                         $q_dat[3]["iteration"], $q_dat[3]["location"],
                         $q_dat[3]["count"]);

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testFollowup_referee_decision() {
        $dat = $this->_generate_records( array( "input" ), 10 );
        $exp[0] = "<select name=\"decision\">";
        $exp[1] = "<option value=\"accept\">accept";
        $exp[2] = "<option value=\"minor\">minor";
        $exp[3] = "<option value=\"severe\">severe";
        $exp[4] = "</select>";
        
        $this->assertEquals( implode("\n",$exp)+"\n", 
                             followup_referee_decision("fubar"));
        $this->assertEquals( implode("\n",$exp)+"\n", 
                             followup_referee_decision(""));

        $o = $exp[1]; $exp[1] = "<option selected value=\"accept\">accept";
        $this->assertEquals( implode("\n",$exp)+"\n", 
                             followup_referee_decision("accept" ));
        $exp[1] = $o;

        $o = $exp[2]; $exp[2] = "<option selected value=\"minor\">minor";
        $this->assertEquals( implode("\n",$exp)+"\n", 
                             followup_referee_decision("minor" ));
        $exp[2] = $o;

        $o = $exp[3]; $exp[3] = "<option selected value=\"severe\">severe";
        $this->assertEquals( implode("\n",$exp)+"\n", 
                             followup_referee_decision("severe"));
        $exp[3] = $o;
    }

//      function testFollowup_milestone_schedule() {
//      }
}

define_test_suite( __FILE__ );

?>