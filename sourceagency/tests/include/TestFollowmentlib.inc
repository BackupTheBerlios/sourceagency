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
# $Id: TestFollowmentlib.inc,v 1.7 2002/05/28 08:58:28 riessen Exp $
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
        unset( $GLOBALS[ 'bx' ] );
    }
    function testShow_followment() {
        global $db, $bx, $t;

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
        for ( $idx = 0; $idx < 3; $idx++ ) {
            $row[$idx]["number"] = $idx;
        }
        $db_config->add_record( $row[0], 2 );
        $db_config->add_record( $row[1], 2 );
        $db_config->add_record( $row[2], 2 );

        // first call, num_row == 0, next_record == false
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        show_followment($proid[0]["proid"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 73, "test 1" );

        $p = $this->_to_regexp( $t->translate( "There have not been "
                                                     ."posted any milestones "
                                                     ."by the project main "
                                                     ."developer"). ".\n");
        $this->_testFor_pattern( $text, $p, "test 1");

        // second call, num_row == 1, next_record == false
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        show_followment($proid[1]["proid"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 956, "test 2" );

        $this->_checkFor_a_box( $text, 'Milestones' );
        
        foreach ( array( "Number","Goals","Release Date","Product",
                         "Payment", "Status", "Creation" ) as $val ) {
            $p=$this->_to_regexp(  "<td align=center><b>"
                                         .$t->translate($val)."</b></td>");
            $this->_testFor_pattern( $text, $p, "Test $val" );
        }

        // third call, num_row == 3, next_record == rows:0,1,2
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        show_followment($proid[2]["proid"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 1469, "test 3" );

        $this->_checkFor_a_box( $text, 'Milestones' );

        foreach ( array( "Number","Goals","Release Date","Product",
                         "Payment", "Status", "Creation" ) as $val ) {
            $p=$this->_to_regexp(  "<td align=center><b>"
                                         .$t->translate($val)."</b></td>");
            $this->_testFor_pattern( $text, $p, "Test $val" );
        }

        $colors = array( 0 => "gold", 1 => "#FFFFFF" );
        for ( $idx = 0; $idx < 3; $idx++ ) {
            $bgc = $colors[ $idx % 2 ];
            $r = $row[$idx];
            $this->_testFor_pattern( $text, "bgcolor=$bgc" );
            foreach ( array( $r["number"],$r["goals"],$r["product"],
                             timestr_middle(mktimestamp($r["release"])),
                             show_status($r["status"]), $r["payment"],
                             timestr_short(mktimestamp($r["creation"])))
                      as $val ) {
                $p=$this->_to_regexp("<td><b>".$val."</b></td>");
                $this->_testFor_pattern( $text, $p, "Test $val" );
            }
            
        }
        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
