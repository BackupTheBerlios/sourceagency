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
# include/followmentlib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestFollowmentlib.inc,v 1.6 2002/05/16 15:04:16 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( 'box.inc' );
    $bx = new box;
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");
} 

include_once( 'lib.inc' );
include_once( 'box.inc' );
include_once( 'html.inc' );
include_once( 'followmentlib.inc' );

class UnitTestFollowmentlib
extends UnitTest
{
    function UnitTestFollowmentlib( $name ) {
        $this->UnitTest( $name );
    }

    function tearDown() {
        // unset the global database object, it can interfer with other tests
        unset( $GLOBALS[ 'db' ] );
    }
    function testShow_followment() {
        global $db;

        $db_config = new mock_db_configure( 3 );

        $db_q = array( 0 => ("SELECT * FROM milestones WHERE proid='%s' "
                             ."ORDER BY number") );

        $proid = $this->_generate_records( array( "proid" ), 3 );

        $row = $this->_generate_records( array("number","goals","release",
                                               "product","payment","status",
                                               "creation"), 3 );

        $db_config->add_query( sprintf( $db_q[0], $proid[0]["proid"] ),0);
        $db_config->add_query( sprintf( $db_q[0], $proid[1]["proid"] ),1);
        $db_config->add_query( sprintf( $db_q[0], $proid[2]["proid"] ),2);

        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_num_row( 3, 2 );
        $db_config->add_num_row( 3, 2 );
        
        $db_config->add_record( false, 1 );
        $db_config->add_record( $row[0], 2 );
        $db_config->add_record( $row[1], 2 );
        $db_config->add_record( $row[2], 2 );

        // first call, num_row == 0, next_record == false
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        show_followment($proid[0]["proid"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 73, "test 1" );

        $this->_testFor_pattern( $text, ("There have not been posted any "
                                         ."milestones by the project main "
                                         ."developer[.]"), "test 1");

        // second call, num_row == 1, next_record == false
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        show_followment($proid[1]["proid"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 884, "test 2" );

        $patterns=array(0=>("<font color=\"#000000\"><b>Milestones<\/b>"
                            ."<\/font>"),
                        1=>("<td align=center><b>Number<\/b><\/td><td align="
                            ."center><b>Goals<\/b><\/td><td align=center><b>"
                            ."Release Date<\/b><\/td><td align=center><b>"
                            ."Product<\/b><\/td><td align=center><b>Payment"
                            ."<\/b><\/td><td align=center><b>Status<\/b>"
                            ."<\/td><td align=center><b>Creation<\/b><\/td>"
                            ."<\/tr>[ \n]+<\/table>[ \n]+<\/font>[ \n]+<\/td>[ \n]+<\/tr>[ \n]+<!--"
                            ." box body end -->[ \n]+<\/table>[ \n]+<\/td><\/tr>"
                            ."<\/table><br>[ \n]+<!-- box end -->"));
        $this->_testFor_patterns( $text, $patterns, 2, "test 2" );

        // third call, num_row == 3, next_record == rows:0,1,2
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        show_followment($proid[2]["proid"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 1424, "test 3" );

        $this->_testFor_pattern( $text, ("<td align=center><b>Number<\/b>"
                                         ."<\/td><td align=center><b>Goals"
                                         ."<\/b><\/td><td align=center><b>"
                                         ."Release Date<\/b><\/td><td align="
                                         ."center><b>Product<\/b><\/td><td "
                                         ."align=center><b>Payment<\/b><\/td>"
                                         ."<td align=center><b>Status<\/b>"
                                         ."<\/td><td align=center><b>Creation"
                                         ."<\/b><\/td><\/tr>\n<tr bgcolor="
                                         ."#FFFFFF>\n<td><b>number_0<\/b>"
                                         ."<\/td><td><b>goals_0<\/b><\/td>"
                                         ."<td><b><\/b><\/td><td><b>product_0"
                                         ."<\/b><\/td><td><b>payment_0<\/b>"
                                         ."<\/td><td><b>Proposed<\/b><\/td>"
                                         ."<td><b><\/b><\/td><\/tr>\n<tr "
                                         ."bgcolor=#FFFFFF>\n<td><b>number_1"
                                         ."<\/b><\/td><td><b>goals_1<\/b>"
                                         ."<\/td><td><b><\/b><\/td><td><b>"
                                         ."product_1<\/b><\/td><td><b>"
                                         ."payment_1<\/b><\/td><td><b>"
                                         ."Proposed<\/b><\/td><td><b><\/b>"
                                         ."<\/td><\/tr>\n<tr bgcolor=#FFFFFF>"
                                         ."\n<td><b>number_2<\/b><\/td><td>"
                                         ."<b>goals_2<\/b><\/td><td><b><\/b>"
                                         ."<\/td><td><b>product_2<\/b><\/td>"
                                         ."<td><b>payment_2<\/b><\/td><td>"
                                         ."<b>Proposed<\/b><\/td><td><b><\/b>"
                                         ."<\/td><\/tr>\n<\/table>\n"), 
                                 "test 3");

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
