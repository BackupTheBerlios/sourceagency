<?php
// TestLib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestLib.php,v 1.3 2001/10/16 12:47:45 ger Exp $

require_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // LIB_ROOT is the base of my libraries and is defined in constants.php
    ini_set('include_path', ini_get('include_path') . ':../../include' );

    // required for the $sess global variable
    require_once( "session.inc" );
    $sess = new Session;
    
    // global translation object
    require_once( "translation.inc" );
    $t = new translation("English");
}

require_once( 'lib.inc' );

class UnitTestLib
extends TestCase
{
    function UnitTestLib( $name ) {
        $this->TestCase( $name );
    }

    function testMonth() {
        global $t;
        $this->assertEquals($t->translate("January"),   month( 1  ) );
        $this->assertEquals($t->translate("February"),  month( 2  ) );
        $this->assertEquals($t->translate("March"),     month( 3  ) );
        $this->assertEquals($t->translate("April"),     month( 4  ) );
        $this->assertEquals($t->translate("May"),       month( 5  ) );
        $this->assertEquals($t->translate("June"),      month( 6  ) );
        $this->assertEquals($t->translate("July"),      month( 7  ) );
        $this->assertEquals($t->translate("August"),    month( 8  ) );
        $this->assertEquals($t->translate("September"), month( 9  ) );
        $this->assertEquals($t->translate("October"),   month( 10 ) );
        $this->assertEquals($t->translate("November"),  month( 11 ) );
        $this->assertEquals($t->translate("December"),  month( 12 ) );

        $this->assertEquals($t->translate("January"),   month( "1"  ) );
        $this->assertEquals($t->translate("February"),  month( "2"  ) );
        $this->assertEquals($t->translate("March"),     month( "3"  ) );
        $this->assertEquals($t->translate("April"),     month( "4"  ) );
        $this->assertEquals($t->translate("May"),       month( "5"  ) );
        $this->assertEquals($t->translate("June"),      month( "6"  ) );
        $this->assertEquals($t->translate("July"),      month( "7"  ) );
        $this->assertEquals($t->translate("August"),    month( "8"  ) );
        $this->assertEquals($t->translate("September"), month( "9"  ) );
        $this->assertEquals($t->translate("October"),   month( "10" ) );
        $this->assertEquals($t->translate("November"),  month( "11" ) );
        $this->assertEquals($t->translate("December"),  month( "12" ) );
    }

    function testDate_to_Timestamp() {
        $this->assertEquals( "20010914120000", 
                             date_to_timestamp( "14",  "9", "2001" ) );
        $this->assertEquals( "20011914120000", 
                             date_to_timestamp( "14", "19", "2001" ) );
        $this->assertEquals( "20010908120000", 
                             date_to_timestamp( "08",  "9", "2001" ) );
        $this->assertEquals( "20010907120000", 
                             date_to_timestamp(  "7",  "9", "2001" ) );
        $this->assertEquals( "20010907120000", 
                             date_to_timestamp( "07", "09", "2001" ) );
    }
    
    function testTimestamp_to_date() {
        $ary = timestamp_to_date( "20010907120000" );
        $this->assertEquals( "2001", $ary['year'] );
        $this->assertEquals( "09",   $ary['month'] );
        $this->assertEquals( "07",   $ary['day'] );

        $ary = timestamp_to_date( "20211214120000" );
        $this->assertEquals( "2021", $ary['year'] );
        $this->assertEquals( "12",   $ary['month'] );
        $this->assertEquals( "14",   $ary['day'] );
    }

    function testLib_select_yes_or_no() {
        // this requires html
        include( "html.inc" );
        $ary = array();
        $expect[0] = "<select name=\"fubar\">\n";
        $expect[1] = "<option value=\"Yes\">Yes\n";
        $expect[2] = "<option selected value=\"No\">No\n";
        $expect[3] =  "</select>\n";
        $this->assertEquals(implode('', $expect), 
                            lib_select_yes_or_no( "fubar", "No" ) );

        $expect[1] = "<option selected value=\"Yes\">Yes\n";
        $expect[2] = "<option value=\"No\">No\n";
        $this->assertEquals(implode('', $expect), 
                            lib_select_yes_or_no( "fubar", "Yes" ) );

        $expect[1] = "<option value=\"Yes\">Yes\n";
        $this->assertEquals(implode('', $expect), 
                            lib_select_yes_or_no( "fubar", "" ) );
    }

    function testLib_nick() {
        $this->assertEquals( "<b>by FUBAR</b>", lib_nick( "FUBAR" ) );
    }

    function testSelect_date() {
        $expect = array();
        $expect[] = "<select name=\"fubar_day\">";
        for ( $idx = 1; $idx < 32; $idx++ ) {
            $expect[] = "<option value=\"" . $idx . "\">" . $idx;
        }
        $expect[] = "</select>";
        $expect[] = "<select name=\"fubar_month\">";
        for ( $idx = 1; $idx < 13; $idx++ ) {
            $expect[] = "<option value=\"" . $idx . "\">" . month($idx);
        }
        $expect[] = "</select>";
        $expect[] = "<select name=\"fubar_year\">";
        for ( $idx = 2001; $idx < 2005; $idx++ ) {
            $expect[] = "<option value=\"" . $idx . "\">" . $idx;
        }
        $expect[] = "</select>";

        $this->assertEquals( implode( "\n", $expect ) . "\n",
                             select_date( "fubar", "-1", "-1", "-1" ));
    }

    function testMktimestamp() {
        $this->assertEquals( 999856800, mktimestamp( "20010907120000" ) );
        $this->assertEquals( 999856812, mktimestamp( "20010907120012" ) );
        $this->assertEquals( 999856813, mktimestamp( "20010907120013" ) );
    }

    function testTimestr() {
        $this->assertEquals( "Monday, 15. October 2001, 19:09:48 CEST",
                             timestr( 1003165788 ) );
        $this->assertEquals( "Monday, 15. October 2001, 19:09:58 CEST",
                             timestr( 1003165798 ) );
        
        $this->assertEquals( "15. October 2001",
                             timestr_middle( 1003165798 ) );
        $this->assertEquals( "16. October 2001",
                             timestr_middle( 1003187798 ) );
        
        $this->assertEquals( "Mon,15.Oct,19:09:48",
                             timestr_short( 1003165788 ) );
        $this->assertEquals( "Mon,15.Oct,19:09:58",
                             timestr_short( 1003165798 ) );
        
        $this->assertEquals( "15. Oct 2001, 19:09",
                             timestr_comment( 1003165788 ) );
        $this->assertEquals( "15. Oct 2001, 19:09",
                             timestr_comment( 1003165798 ) );
        
        $this->assertEquals( "15. Oct",
                             timestr_shortest( 1003165788 ) );
        $this->assertEquals( "15. Oct",
                             timestr_shortest( 1003165798 ) );
        
    }
    
    function testTypestr() {
        global $t;
        $this->assertEquals( $t->translate("Adaption"),      typestr( "A" ) );
        $this->assertEquals( $t->translate("Expansion"),     typestr( "E" ) );
        $this->assertEquals( $t->translate("Documentation"), typestr( "C" ) );
        $this->assertEquals( $t->translate("Development"),   typestr( "D" ) );
        $this->assertEquals( $t->translate("Other"),         typestr( "O" ) );
        $this->assertEquals( "", typestr( "What?" ) );
        $this->assertEquals( "", typestr( "" ) );
    }
    
    function testShow_status() {
        $this->assertEquals( "Proposed", show_status( 'P' ) );
        $this->assertEquals( "Negotiating", show_status( 'N' ) );
        $this->assertEquals( "Accepted", show_status( 'A' ) );
        $this->assertEquals( "Rejected", show_status( 'R' ) );
        $this->assertEquals( "Deleted", show_status( 'D' ) );
        $this->assertEquals( "Modified", show_status( 'M' ) );
        $this->assertEquals( "Proposed", show_status( '' ) );
        $this->assertEquals( "Proposed", show_status( 'asdasd' ) );
        $this->assertEquals( "Proposed", show_status( 'm' ) );
    }

    function testWrap() {
        $expect = array();
        $expect[] = "fubar"; 
        $expect[] = "snafu";
        $expect[] = "fritz";
        $expect[] = "hello";
        $expect[] = "world";
        
        $this->assertEquals( implode( " ", $expect ), 
                             wrap( implode(" ", $expect) ));
        $this->assertEquals( implode( "\n", $expect ),
                             wrap( implode(" ", $expect), 5 ));
        $this->assertEquals( implode( "\n", $expect ),
                             wrap( implode("\n", $expect), 5, "\n" ));
        
        $this->assertEquals( "a\nbc\n12345\nabc",
                             wrap( "a bc 12345 abc", 2, " " ));
    }
}

define_test_suite( __FILE__ );
?>
