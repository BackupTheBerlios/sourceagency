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
# $Id: TestFollowmentlib.inc,v 1.10 2002/06/14 09:14:12 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

include_once( 'lib.inc' );
include_once( 'box.inc' );
include_once( 'html.inc' );
include_once( 'followmentlib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS['t'] = new translation("English");
} 

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

        $db_config->add_record( false, 1 );
        for ( $idx = 0; $idx < 3; $idx++ ) {
            $db_config->add_num_row( ($idx > 1 ? 3 : $idx), $idx );
            $db_config->add_num_row( ($idx > 1 ? 3 : $idx), $idx );
            $row[$idx]["number"] = $idx;
            $db_config->add_record( $row[$idx], 2 );
        }

        // first call, num_row == 0, next_record == false
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call('show_followment',73,array(&$proid[0]["proid"]));

        $this->set_msg( "Test 1" );

        $p = $this->_to_regexp( $t->translate( "There have not been "
                                               ."posted any milestones "
                                               ."by the project main "
                                               ."developer"). ".\n");
        $this->_testFor_pattern( $p );

        // second call, num_row == 1, next_record == false
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call('show_followment',956,array( &$proid[1]["proid"]));
        $this->set_msg( "Test 2" );

        $this->_checkFor_a_box( 'Milestones' );
        
        foreach ( array( "Number","Goals","Release Date","Product",
                         "Payment", "Status", "Creation" ) as $val ) {
            $p=$this->_to_regexp(  "<td align=center><b>"
                                         .$t->translate($val)."</b></td>");
            $this->set_msg( "Test $val" );
            $this->_testFor_pattern( $p );
        }

        // third call, num_row == 3, next_record == rows:0,1,2
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call('show_followment',1469,array(&$proid[2]["proid"]));
        $this->set_msg( "Test 3" );

        $this->_checkFor_a_box( 'Milestones' );

        foreach ( array( "Number","Goals","Release Date","Product",
                         "Payment", "Status", "Creation" ) as $val ) {
            $p=$this->_to_regexp(  "<td align=center><b>"
                                         .$t->translate($val)."</b></td>");
            $this->set_msg( "Test $val" );
            $this->_testFor_pattern( $p );
        }

        $colors = array( 0 => "gold", 1 => "#FFFFFF" );
        for ( $idx = 0; $idx < 3; $idx++ ) {
            $bgc = $colors[ $idx % 2 ];
            $r = $row[$idx];
            $this->_testFor_pattern( "bgcolor=$bgc" );
            foreach ( array( $r["number"],$r["goals"],$r["product"],
                             timestr_middle(mktimestamp($r["release"])),
                             show_status($r["status"]), $r["payment"],
                             timestr_short(mktimestamp($r["creation"])))
                      as $val ) {
                $p = $this->_to_regexp("<td><b>".$val."</b></td>");
                $this->set_msg( "Test $val" );
                $this->_testFor_pattern( $p );
            }
        }
        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
