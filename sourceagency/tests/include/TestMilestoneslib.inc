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
# $Id: TestMilestoneslib.inc,v 1.7 2002/01/28 02:22:56 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
//      // required for the $sess global variable
//      include_once( "session.inc" );
//      $sess = new Session;
    
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
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 0 );

        //
        // second call, the status part of the third query is present
        //
        capture_reset_text();
        capture_start();
        show_milestones( $dat[1]["proid"], $dat[1]["devid"],
                         $dat[1]["sponsor"] );
        capture_stop();
        $text = capture_text_get();
        $this->_testFor_length( 0 );

        //
        // third call, two records of data
        //
        capture_reset_text();
        capture_start();
        show_milestones( $dat[2]["proid"], $dat[2]["devid"],
                         $dat[2]["sponsor"] );
        capture_stop();
        $text = capture_text_get();
        $this->_testFor_length( 3575 );
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"7%\""
                                         ." bgcolor=\"#FFFFFF\"><b>Number"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"\" width=\"53%\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Goals"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"16%\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Release "
                                         ."Date<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"12%\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Product"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"8%\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Payment"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"14%\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Status"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>number_3"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>goals_3"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b><\/b>"
                                         ."<\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>product_3"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>payment_3% "
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Proposed"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>number_4"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>goals_4"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b><\/b>"
                                         ."<\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>product_4"
                                         ."<\/b><\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"center\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>payment_4% "
                                         ."<\/b><\/td>"));

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testSelect_milestone_number() {
        $db_config = new mock_db_configure( 3 );

        $db_q = array( 0 => ("SELECT number FROM milestones WHERE proid='%s' "
                             . "AND devid='%s' ORDER BY number"));

        $dat = array(0 => $this->_generate_array(array("proid","devid"),0),
                     1 => $this->_generate_array(array("proid","devid"),1),
                     2 => $this->_generate_array(array("proid","devid"),2));
        
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
