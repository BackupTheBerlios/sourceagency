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
# $Id: TestMilestoneslib.inc,v 1.9 2002/03/19 17:08:03 riessen Exp $
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
        $db_config->add_record( $dat[3], 5 );
        $db_config->add_record( $dat[4], 5 );

        //
        // first call, the status part of the third query should be reset
        //
        capture_start();
        show_milestones( $dat[0]["proid"], $dat[0]["devid"],
                         $dat[0]["sponsor"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 0 );

        //
        // second call, the status part of the third query is present
        //
        capture_reset_and_start();
        show_milestones( $dat[1]["proid"], $dat[1]["devid"],
                         $dat[1]["sponsor"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 0 );

        //
        // third call, two records of data
        //
        capture_reset_and_start();
        show_milestones( $dat[2]["proid"], $dat[2]["devid"],
                         $dat[2]["sponsor"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 3575 );
        $pats = array( 0=> ("<td align=\"center\" width=\"7%\" bgcolor=\""
                            ."#FFFFFF\"><b>Number<\/b><\/td>"),
                       1=> ("<td align=\"\" width=\"53%\" bgcolor=\""
                            ."#FFFFFF\"><b>Goals<\/b><\/td>"),
                       2=> ("<td align=\"center\" width=\"16%\" bgcolor=\""
                            ."#FFFFFF\"><b>Release Date<\/b><\/td>"),
                       3=> ("<td align=\"center\" width=\"12%\" bgcolor=\""
                            ."#FFFFFF\"><b>Product<\/b><\/td>"),
                       4=> ("<td align=\"center\" width=\"8%\" bgcolor=\""
                            ."#FFFFFF\"><b>Payment<\/b><\/td>"),
                       5=> ("<td align=\"center\" width=\"14%\" bgcolor=\""
                            ."#FFFFFF\"><b>Status<\/b><\/td>"),
                       6=> ("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\"><b>number_3<\/b><\/td>"),
                       7=> ("<td align=\"\" width=\"\" bgcolor=\"#FFFFFF\"><b>"
                            ."goals_3<\/b><\/td>"),
                       8=> ("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\"><b><\/b><\/td>"),
                       9=> ("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\"><b>product_3<\/b><\/td>"),
                       10=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\"><b>payment_3% <\/b><\/td>"),
                       11=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\"><b>Proposed<\/b><\/td>"),
                       12=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\"><b>number_4<\/b><\/td>"),
                       13=>("<td align=\"\" width=\"\" bgcolor=\"#FFFFFF\">"
                            ."<b>goals_4<\/b><\/td>"),
                       14=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\"><b><\/b><\/td>"),
                       15=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\"><b>product_4<\/b><\/td>"),
                       16=>("<td align=\"center\" width=\"\" bgcolor=\""
                            ."#FFFFFF\"><b>payment_4% <\/b><\/td>"));
        $this->_testFor_patterns( $text, $pats, 17 );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testMilestones_insert() {
        global $db;

        $db_config = new mock_db_configure( 4 );
        
        $db_q = array( 0 => ("INSERT milestones SET proid='%s',devid='%s',"
                             . "number='%s',goals='%s',release='%s',"
                             . "product='%s',payment='%s',status='P',"
                             . "creation=NOW()"),
                       1 => ("SELECT * FROM milestones WHERE number='%s'"));

        $ln = ( "<p align=right>[[] <a href=\"step3_edit[.]php3[?]proid=%s"
                ."[&]a=1\"><b>Propose Milestones<\/b><\/a>\n []]<\/p>" );

        $dat = $this->_generate_records( array("pid","dev","num","goals",
                                               "rday", "rmon", "ryear",
                                               "prod","pay"), 4);
        //
        // test 1 -- everything is fine
        //
        $db_config->add_query( sprintf( $db_q[1], $dat[0]["num"]), 0 );
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
        $this->_testFor_length( 95 );
        $pats = array( 0 => (sprintf( $ln, $dat[0]["pid"])));
        $this->_testFor_patterns( $text, $pats, 1 );

        //
        // test 2 -- goals is not set and therefore no database insert happens
        //
        $dat[1]["goals"] = "";

        $db_config->add_query( sprintf( $db_q[1], $dat[1]["num"]), 1 );
        $db_config->add_num_row( 0, 1 );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        milestones_insert( $dat[1]["pid"], $dat[1]["dev"], $dat[1]["num"], 
                           $dat[1]["goals"], $dat[1]["rday"], $dat[1]["rmon"],
                           $dat[1]["ryear"], $dat[1]["prod"], $dat[1]["pay"]);
        $text = capture_stop_and_get();
        $this->_testFor_length( 95 );
        $pats = array( 0 => (sprintf( $ln, $dat[1]["pid"])));
        $this->_testFor_patterns( $text, $pats, 1 );

        //
        // test 3 -- number used is already taken
        //
        $db_config->add_query( sprintf( $db_q[1], $dat[2]["num"]), 2 );
        $db_config->add_num_row( 1, 2 );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        milestones_insert( $dat[2]["pid"], $dat[2]["dev"], $dat[2]["num"], 
                           $dat[2]["goals"], $dat[2]["rday"], $dat[2]["rmon"],
                           $dat[2]["ryear"], $dat[2]["prod"], $dat[2]["pay"]);
        $text = capture_stop_and_get();
        $this->_testFor_length( 95 );
        $pats = array( 0 => (sprintf( $ln, $dat[2]["pid"])));
        $this->_testFor_patterns( $text, $pats, 1 );

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
        $this->_testFor_length( 95 );
        $pats = array( 0 => (sprintf( $ln, $dat[3]["pid"])));
        $this->_testFor_patterns( $text, $pats, 1 );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testSelect_milestone_number() {
        $db_config = new mock_db_configure( 3 );

        $db_q = array( 0 => ("SELECT number FROM milestones WHERE proid='%s' "
                             . "AND devid='%s' ORDER BY number"));

        $dat = $this->_generate_records( array("proid","devid"), 3 );
        
        for ( $idx = 0; $idx < 3; $idx++ ) {
            $db_config->add_query( sprintf( $db_q[0], $dat[$idx]["proid"],
                                            $dat[$idx]["devid"]), $idx );
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
        $this->assertEquals( 153, strlen($text), "String length mismatch" );
        for ( $idx = 4; $idx < 10; $idx++ ) {
            $this->_testFor_pattern($text,("<option value=\"".$idx
                                           ."\">".$idx));
        }

        //
        // second call, no values
        //
        $text = select_milestone_number( $dat[1]["proid"], $dat[1]["devid"],0);
        $this->assertEquals( 133, strlen($text), "String length mismatch" );
        for ( $idx = 1; $idx < 6; $idx++ ) {
            $this->_testFor_pattern($text,("<option value=\"".$idx
                                           ."\">".$idx));
        }

        //
        // third call, no values but a selected option
        //
        $text = select_milestone_number( $dat[2]["proid"], $dat[2]["devid"],5);
        $this->assertEquals( 142, strlen($text), "String length mismatch" );
        for ( $idx = 1; $idx < 5; $idx++ ) {
            $this->_testFor_pattern($text,("<option value=\"".$idx
                                           ."\">".$idx));
        }
        $this->_testFor_pattern($text,("<option selected value=\"5\">5"));

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

}

define_test_suite( __FILE__ );
?>
