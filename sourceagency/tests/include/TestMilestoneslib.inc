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
# include/milestoneslib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestMilestoneslib.inc,v 1.18 2002/05/08 11:42:49 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "session.inc" );
    $sess = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");

    include_once( "box.inc" );
    $bx = new box;
}

include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'security.inc' );
include_once( 'milestoneslib.inc' );

class UnitTestMilestoneslib
extends UnitTest
{
    function UnitTestMilestoneslib( $name ) {
        $this->UnitTest( $name );
    }

    function setup() {
        // Called before each test method.
        // if using the capturing routines then ensure that it's reset,
        // it uses global variables
        capture_reset_text();
    }

    function testMilestones_propose_milestone() {
        global $t;

        $pats = array( 0=> ("<p align=right>[[] "),
                       1=> ("step3_edit.php3[?]proid="),
                       2=> ("[&]a=1"),
                       3=> ($t->translate("Propose Milestones")),
                       4=> (" []]<\/p>") );

        capture_reset_and_start();
        milestones_propose_milestone( "" );
        $text = capture_stop_and_get();
        
        $this->_testFor_captured_length( 98, "test 1" );

        $pats[5] = "[?]proid=[&]a=1";
        $this->_testFor_patterns( $text, $pats, 6 );
        
        capture_reset_and_start();
        milestones_propose_milestone( "this_is_the_proid" );
        $text = capture_stop_and_get();
        
        $this->_testFor_captured_length( 115, "test 2" );

        $pats[5] = "[?]proid=this_is_the_proid[&]a=1";
        $this->_testFor_patterns( $text, $pats, 6 );
    }

    function testShow_milestones() {

        $db_config = new mock_db_configure( 6 );
        $db_q = array( 0 => ("SELECT * FROM sponsoring WHERE proid='%s' "
                             . "AND sponsor='%s'"),
                       1 => ("SELECT developer FROM developing WHERE "
                             ."proid='%s' AND devid='%s'"),
                       2 => ("SELECT * FROM milestones WHERE proid='%s' "
                             . " AND devid='%s' ORDER BY number"),
                       3 => ("SELECT * FROM milestones WHERE proid='%s' AND "
                             . "status='A' AND devid='%s' ORDER BY number"));

        $dat = array(0 => $this->_generate_array(array("proid","devid",
                                                       "sponsor"), 0),
                     1 => $this->_generate_array(array("proid","devid",
                                                       "sponsor"), 1),
                     2 => $this->_generate_array(array("proid","devid",
                                                       "sponsor"), 2),
                     3 => $this->_generate_array(array("number","release",
                                                       "product","payment",
                                                       "status","goals"), 3),
                     4 => $this->_generate_array(array("number","release",
                                                       "product","payment",
                                                       "status","goals"), 4));
        // first call
        $db_config->add_query( sprintf( $db_q[0], $dat[0]["proid"],
                                        $dat[0]["sponsor"]), 0 );
        $db_config->add_query( sprintf( $db_q[1], $dat[0]["proid"],
                                        $dat[0]["devid"]), 0 );
        $db_config->add_query( sprintf( $db_q[2], $dat[0]["proid"],
                                        $dat[0]["devid"]), 1 );
        // second call
        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"],
                                        $dat[1]["sponsor"]), 2 );
        $db_config->add_query( sprintf( $db_q[1], $dat[1]["proid"],
                                        $dat[1]["devid"]), 2 );
        $db_config->add_query( sprintf( $db_q[3], $dat[1]["proid"],
                                        $dat[1]["devid"]), 3 );
        // third call
        $db_config->add_query( sprintf( $db_q[0], $dat[2]["proid"],
                                        $dat[2]["sponsor"]), 4 );
        $db_config->add_query( sprintf( $db_q[1], $dat[2]["proid"],
                                        $dat[2]["devid"]), 4 );
        $db_config->add_query( sprintf( $db_q[3], $dat[2]["proid"],
                                        $dat[2]["devid"]), 5 );

        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 0, 1 );
        $db_config->add_record(array("developer" => $dat[0]["sponsor"]),0);

        $db_config->add_num_row( 0, 2 );
        $db_config->add_num_row( 0, 3 );
        $db_config->add_record( array("developer" => $dat[0]["sponsor"]),2);

        $db_config->add_num_row( 0, 4 );
        $db_config->add_num_row( 2, 5 );
        $db_config->add_record( array("developer" => $dat[0]["sponsor"]),4);

        $dat[3]["number"] = 0;
        $dat[4]["number"] = 1;
        $db_config->add_record( $dat[3], 5 );
        $db_config->add_record( $dat[4], 5 );

        //
        // first call, the status part of the third query should be reset
        //
        capture_start();
        show_milestones( $dat[0]["proid"], $dat[0]["devid"],
                         $dat[0]["sponsor"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0, "test 1" );

        //
        // second call, the status part of the third query is present
        //
        capture_reset_and_start();
        show_milestones( $dat[1]["proid"], $dat[1]["devid"],
                         $dat[1]["sponsor"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0, "test 2" );

        //
        // third call, two records of data
        //
        capture_reset_and_start();
        show_milestones( $dat[2]["proid"], $dat[2]["devid"],
                         $dat[2]["sponsor"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 4710, "test 3" );
        $pats = array( 0=> ("<td align=\"center\" width=\"7%\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b>Number<\/b>[ \n]+<\/td>"),
                       1=> ("<td align=\"\" width=\"53%\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b>Goals<\/b>[ \n]+<\/td>"),
                       2=> ("<td align=\"center\" width=\"16%\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b>Release Date<\/b>"
                            ."[ \n]+<\/td>"),
                       3=> ("<td align=\"center\" width=\"12%\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b>Product<\/b>[ \n]+<\/td>"),
                       4=> ("<td align=\"center\" width=\"8%\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b>Payment<\/b>[ \n]+<\/td>"),
                       5=> ("<td align=\"center\" width=\"14%\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b>Status<\/b>[ \n]+<\/td>"),
                       6=> ("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b>0<\/b>[ \n]+<\/td>"),
                       7=> ("<td align=\"\" width=\"\" bgcolor=\"#FFFFFF\">"
                            ."[ \n]+<b>"
                            ."goals_3<\/b>[ \n]+<\/td>"),
                       8=> ("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b><\/b>[ \n]+<\/td>"),
                       9=> ("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b>product_3<\/b>[ \n]+<\/td>"),
                       10=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b>payment_3% <\/b>[ \n]+<\/td>"),
                       11=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\">[ \n]+<b>Proposed<\/b>[ \n]+<\/td>"),
                       12=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."gold\">[ \n]+<b>1<\/b>[ \n]+<\/td>"),
                       13=>("<td align=\"\" width=\"\" bgcolor=\"gold\">"
                            ."[ \n]+<b>goals_4<\/b>[ \n]+<\/td>"),
                       14=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."gold\">[ \n]+<b><\/b>[ \n]+<\/td>"),
                       15=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."gold\">[ \n]+<b>product_4<\/b>[ \n]+<\/td>"),
                       16=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."gold\">[ \n]+<b>payment_4% <\/b>[ \n]+<\/td>"));
        $this->_testFor_patterns( $text, $pats, 17 );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testMilestones_insert() {
        global $db, $t;

        $db_config = new mock_db_configure( 4 );
        
        $db_q = array( 0 => ("INSERT milestones SET proid='%s',devid='%s',"
                             . "number='%s',goals='%s',release='%s',"
                             . "product='%s',payment='%s',status='P',"
                             . "creation=NOW()"),
                       1 => ("SELECT * FROM milestones WHERE number='%s' "
                             . "AND proid='%s'" ));

        $ln = ( "<p align=right>[[] <a href=\"step3_edit[.]php3[?]proid=%s"
                ."[&]a=1\" class=\"\"><b>Propose Milestones<\/b><\/a> []]"
                ."<\/p>" );

        $dat = $this->_generate_records( array("pid","dev","num","goals",
                                               "rday", "rmon", "ryear",
                                               "prod","pay"), 4);
        //
        // test 1 -- everything is fine
        //
        $db_config->add_query( sprintf( $db_q[1], $dat[0]["num"],
                                        $dat[0]["pid"]), 0 );
        $db_config->add_query( sprintf( $db_q[0], $dat[0]["pid"],
                                        $dat[0]["dev"], $dat[0]["num"], 
                                        $dat[0]["goals"], 
                           date_to_timestamp( $dat[0]["rday"], $dat[0]["rmon"],
                           $dat[0]["ryear"]), $dat[0]["prod"], $dat[0]["pay"]),
                               0 );
        $db_config->add_num_row( 0, 0 );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        milestones_insert( $dat[0]["pid"], $dat[0]["dev"], $dat[0]["num"], 
                           $dat[0]["goals"], $dat[0]["rday"], $dat[0]["rmon"],
                           $dat[0]["ryear"], $dat[0]["prod"], $dat[0]["pay"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 103, "test 1" );
        $pats = array( 0 => (sprintf( $ln, $dat[0]["pid"])));
        $this->_testFor_patterns( $text, $pats, 1 );

        //
        // test 2 -- goals is not set and therefore no database insert happens
        //
        $dat[1]["goals"] = "";

        $db_config->add_query( sprintf( $db_q[1], $dat[1]["num"], 
                                        $dat[1]["pid"]), 1 );
        $db_config->add_num_row( 0, 1 );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        milestones_insert( $dat[1]["pid"], $dat[1]["dev"], $dat[1]["num"], 
                           $dat[1]["goals"], $dat[1]["rday"], $dat[1]["rmon"],
                           $dat[1]["ryear"], $dat[1]["prod"], $dat[1]["pay"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 795, "test 2" );
        $pats = array( 0 => (sprintf( $ln, $dat[1]["pid"])),
                       1 => $t->translate("Insert Failed" ),
                       2 => $t->translate("No Goals defined."));
        $this->_testFor_patterns( $text, $pats, 3 );

        //
        // test 3 -- number used is already taken
        //
        $db_config->add_query( sprintf( $db_q[1], $dat[2]["num"],
                                        $dat[2]["pid"]), 2 );
        $db_config->add_num_row( 1, 2 );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        milestones_insert( $dat[2]["pid"], $dat[2]["dev"], $dat[2]["num"], 
                           $dat[2]["goals"], $dat[2]["rday"], $dat[2]["rmon"],
                           $dat[2]["ryear"], $dat[2]["prod"], $dat[2]["pay"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 810, "test 3" );

        $pats = array( 0 => (sprintf( $ln, $dat[2]["pid"])),
                       1 => $t->translate("Insert Failed" ),
                       2 => $t->translate("Number parameter already in use."));
        $this->_testFor_patterns( $text, $pats, 3 );

        //
        // test 4 -- number parameter is not set, no queries
        //
        $dat[3]["num"] = "";

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        milestones_insert( $dat[3]["pid"], $dat[3]["dev"], $dat[3]["num"], 
                           $dat[3]["goals"], $dat[3]["rday"], $dat[3]["rmon"],
                           $dat[3]["ryear"], $dat[3]["prod"], $dat[3]["pay"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 807, "test 4" );
        $pats = array( 0 => (sprintf( $ln, $dat[3]["pid"])),
                       1 => $t->translate("Insert Failed" ),
                       2 => $t->translate("Number parameter was not set."));
        $this->_testFor_patterns( $text, $pats, 3 );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testSelect_milestone_number() {
        $db_config = new mock_db_configure( 3 );

        $db_q = array( 0 => ("SELECT number FROM milestones WHERE proid='%s' "
                             . "ORDER BY number"));

        $dat = $this->_generate_records( array("proid","devid"), 3 );
        
        for ( $idx = 0; $idx < 3; $idx++ ) {
            $db_config->add_query( sprintf( $db_q[0], $dat[$idx]["proid"]), 
                                   $idx );
        }
        for ( $idx = 0; $idx < 11; $idx++ ) {
            $db_config->add_num_row( 4, 0 );
        }
        for ( $idx = 0; $idx < 7; $idx++ ) {
            $db_config->add_num_row( 0, 1 );
            $db_config->add_num_row( 0, 2 );
        }

        $db_config->add_record(array("number" => 0),0);
        $db_config->add_record(array("number" => 1),0);
        $db_config->add_record(array("number" => 2),0);
        $db_config->add_record(array("number" => 3),0);

        //
        // first call, four values
        //
        $text = select_milestone_number( $dat[0]["proid"], $dat[0]["devid"],0);
        $this->assertEquals( 212, strlen($text), "test 1" );
        for ( $idx = 4; $idx < 10; $idx++ ) {
            $this->_testFor_pattern($text,("<option value=\"".$idx
                                           ."\">".$idx), "idx: $idx");
        }

        //
        // second call, no values
        //
        $text = select_milestone_number( $dat[1]["proid"], $dat[1]["devid"],0);
        $this->assertEquals( 185, strlen($text), "test 2" );
        for ( $idx = 1; $idx < 6; $idx++ ) {
            $this->_testFor_pattern($text,("<option value=\"".$idx
                                           ."\">".$idx), "idx: $idx");
        }

        //
        // third call, no values but a selected option
        //
        $text = select_milestone_number( $dat[2]["proid"], $dat[2]["devid"],5);
        $this->assertEquals( 194, strlen($text), "test 3" );
        for ( $idx = 1; $idx < 5; $idx++ ) {
            $this->_testFor_pattern($text,("<option value=\"".$idx
                                           ."\">".$idx), "idx: $idx");
        }
        $this->_testFor_pattern($text,("<option selected value=\"5\">5"));

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testSelect_milestone_payment() {
        $db_config = new mock_db_configure( 3 );

        $db_q = array( 0 => ("SELECT payment,number FROM milestones WHERE "
                             . "proid='%s' AND devid='%s'"));

        $args = $this->_generate_records( array("proid","devid","selected",
                                                "number"), 3 );
        $dat = $this->_generate_records( array( "number", "payment" ), 6 );

        $db_config->add_query( sprintf( $db_q[0], $args[0]["proid"],
                                        $args[0]["devid"]), 0 );
        $db_config->add_query( sprintf( $db_q[0], $args[1]["proid"],
                                        $args[1]["devid"]), 1 );
        $db_config->add_query( sprintf( $db_q[0], $args[2]["proid"],
                                        $args[2]["devid"]), 2 );

        // we do the following assignments to check the if statement:
        //     	if ($db->f("number")!=$number) {
        // if the if fails, then $max value will be set to zero.

        // instance 0 (test 1): $max = 4
        $dat[0]["number"] = $args[0]["number"];
        $dat[1]["number"] = "dont_match" . $args[0]["number"];
        $dat[0]["payment"] = 4;
        $dat[1]["payment"] = 96; 
        $db_config->add_record( $dat[0], 0 );
        $db_config->add_record( $dat[1], 0 );

        // instance 1 (test 2): $max = 50
        $dat[2]["number"] = $args[1]["number"];
        $dat[3]["number"] = "dont_match" . $args[1]["number"];
        $dat[2]["payment"] = 50;
        $dat[3]["payment"] = 50; 
        $db_config->add_record( $dat[2], 1 );
        $db_config->add_record( $dat[3], 1 );

        // instance 2 (test 3): $max = 100
        $dat[4]["number"] = $args[2]["number"];
        $dat[5]["number"] = "dont_match" . $args[2]["number"];
        $dat[4]["payment"] = 100;
        $dat[5]["payment"] = 0; 
        $db_config->add_record( $dat[4], 2 );
        $db_config->add_record( $dat[5], 2 );

        // test 1
        $args[0]["selected"] = 4;
        $text = select_milestone_payment( $args[0]["proid"], $args[0]["devid"],
                                     $args[0]["selected"], $args[0]["number"]);

        $this->_testFor_string_length( $text, 116, "test 1" );
        for ( $i = 2; $i < 4; $i+=2 ) {
            $this->_testFor_pattern($text, "option value=\"" . $i . "\">" 
                                           . $i . "%");
        }
        $this->_testFor_pattern( $text, "option selected value=\"4\">4%" );

        // test 2
        $args[1]["selected"] = 50;
        $text = select_milestone_payment( $args[1]["proid"], $args[1]["devid"],
                                  $args[1]["selected"], $args[1]["number"]);
        $this->_testFor_string_length( $text, 802, "test 2" );
        for ( $i = 2; $i < 50; $i+=2 ) {
            $this->_testFor_pattern($text, "option value=\"" . $i . "\">" 
                                           . $i . "%");
        }
        $this->_testFor_pattern( $text, "option selected value=\"50\">50%" );

        // test 3
        $args[2]["selected"] = 100;
        $text = select_milestone_payment( $args[2]["proid"], $args[2]["devid"],
                                  $args[2]["selected"], $args[2]["number"]);
        $this->_testFor_string_length( $text, 1554, "test 3" );
        for ( $i = 2; $i < 100; $i+=2 ) {
            $this->_testFor_pattern($text, "option value=\"" . $i . "\">" 
                                           . $i . "%");
        }
        $this->_testFor_pattern( $text, "option selected value=\"100\">100%" );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );
?>
