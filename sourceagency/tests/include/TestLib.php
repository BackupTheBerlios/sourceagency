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
# include/lib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestLib.php,v 1.8 2002/01/09 16:24:57 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $sess = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");

    include_once( "box.inc" );
    $bx = new box;
}

include_once( 'lib.inc' );

class UnitTestLib
extends UnitTest
{
    function UnitTestLib( $name ) {
        $this->TestCase( $name );
    }

    function setup() {
        // Called before each test method.
        // if using the capturing routines then ensure that it's reset,
        // it uses global variables
        capture_reset_text();
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
        include_once( "html.inc" );
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
        for ( $idx = 2001; $idx <= 2005; $idx++ ) {
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
        setlocale( LC_TIME, "de_DE" );
        $this->assertEquals( "Montag, 15. Oktober 2001, 19:09:48 CEST",
                             timestr( 1003165788 ) );
        $this->assertEquals( "Montag, 15. Oktober 2001, 19:09:58 CEST",
                             timestr( 1003165798 ) );
        
        $this->assertEquals( "15. Oktober 2001",
                             timestr_middle( 1003165798 ) );
        $this->assertEquals( "16. Oktober 2001",
                             timestr_middle( 1003187798 ) );
        
        $this->assertEquals( "Mon,15.Okt,19:09:48",
                             timestr_short( 1003165788 ) );
        $this->assertEquals( "Mon,15.Okt,19:09:58",
                             timestr_short( 1003165798 ) );
        
        $this->assertEquals( "15. Okt 2001, 19:09",
                             timestr_comment( 1003165788 ) );
        $this->assertEquals( "15. Okt 2001, 19:09",
                             timestr_comment( 1003165798 ) );
        
        $this->assertEquals( "15. Okt",
                             timestr_shortest( 1003165788 ) );
        $this->assertEquals( "15. Okt",
                             timestr_shortest( 1003165798 ) );
        
    }
    
    function testTypestr() {
        global $t;
        $this->assertEquals( $t->translate("Adaption"),      typestr( "A" ) );
        $this->assertEquals( $t->translate("Expansion"),     typestr( "E" ) );
        $this->assertEquals( $t->translate("Documentation"), typestr( "C" ) );
        $this->assertEquals( $t->translate("Development"),   typestr( "D" ) );
        $this->assertEquals( $t->translate("Other"),         typestr( "O" ) );
        $this->assertEquals( "Other", typestr( "What?" ) );
        $this->assertEquals( "Other", typestr( "" ) );
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

    function testCalendar_box() {
        $db_config = new mock_db_configure;
        // 4 instances required: 2 for the no budget case, and 2 for the
        // budget is set case.
        $db_config->set_nr_instance_expected( 4 );
        $db_q = array( 0 => ("SELECT * FROM description,auth_user WHERE "
                             . "proid='%s' AND description_user = username"),
                       1 => ("SELECT SUM(budget) FROM sponsoring WHERE "
                             . "proid='%s' AND status='A'"));
        $dat = array( "r0" => "proid1", "e0" => 1,
                      "r1" => "proid1", "e1" => 0,
                      "r2" => "proid2", "e2" => 1,
                      "r3" => "proid2", "e3" => 1 );

        $db_config->add_query( sprintf( $db_q[0], $dat["r0"] ), 0 );
        $db_config->add_query( sprintf( $db_q[1], $dat["r1"] ), 1 );
        $db_config->add_query( sprintf( $db_q[0], $dat["r2"] ), 2 );
        $db_config->add_query( sprintf( $db_q[1], $dat["r3"] ), 3 );
        $db_config->add_num_row( $dat["e0"], 0 );
        $db_config->add_num_row( $dat["e1"], 1 );
        $db_config->add_num_row( $dat["e2"], 2 );
        $db_config->add_num_row( $dat["e3"], 3 );

        $row1 = $this->_generate_array(array("description_user", "volume",
                                            "type","description_creation"),1);
        $db_config->add_record( $row1, 0 );
        $row3 = $this->_generate_array(array("description_user", "volume",
                                            "type","description_creation"),2);
        $db_config->add_record( $row3, 2 );
        $row2 = array( "SUM(budget)" => "INCORRECT" );
        $db_config->add_record( $row2, 1 );
        $row4 = array( "SUM(budget)" => 1000 );
        $db_config->add_record( $row4, 3 );

        //
        // no budget -- this should actually do something else, i.e.
        // display a zero budget amount, however the code is broken and
        // has a fixme note
        //
        capture_start();
        calendar_box( $dat["r0"] );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 2174 );
        $this->_testFor_pattern( $text, ("<b>Project Owner\(s\):<\/b><\/td>\n"
                                         . $this->p_regexp_html_comment . "\n"
                                         . $this->p_regexp_html_comment . "\n"
                                         . "<td align=\"left\" width=\"45%\""
                                         . " bgcolor=\"#FFFFFF\">&nbsp;"
                                         .$row1["description_user"]."<\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Project "
                                         ."Type:<\/b><\/td>\n"
                                         . $this->p_regexp_html_comment . "\n"
                                         . $this->p_regexp_html_comment . "\n"
                                         ."<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\">&nbsp;"
                                         .$row1["type"]."<\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Project "
                                         ."Volume:<\/b><\/td>\n"
                                         .$this->p_regexp_html_comment . "\n"
                                         .$this->p_regexp_html_comment . "\n"
                                         ."<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\">&nbsp;"
                                         .$row1["volume"]
                                         ."<\/td>"));
        // TODO: this is wrong, 100 euro should not appear here ....
        $this->_testFor_pattern( $text, ("<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Current "
                                         ."project budget:<\/b><\/td>\n"
                                         .$this->p_regexp_html_comment . "\n"
                                         .$this->p_regexp_html_comment . "\n"
                                         ."<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\">INCORRECT euro"
                                         ."<\/td>"));

        //
        // This run has a budget and the budget value should be printed
        capture_reset_text();
        capture_start();
        calendar_box( $dat["r2"] );
        capture_stop();

        $text = capture_text_get();
        $this->_testFor_length( 2169 );
        $this->_testFor_pattern( $text, ("<b>Project Owner\(s\):<\/b><\/td>\n"
                                         . $this->p_regexp_html_comment . "\n"
                                         . $this->p_regexp_html_comment . "\n"
                                         . "<td align=\"left\" width=\"45%\""
                                         . " bgcolor=\"#FFFFFF\">&nbsp;"
                                         .$row3["description_user"]."<\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Project "
                                         ."Type:<\/b><\/td>\n"
                                         . $this->p_regexp_html_comment . "\n"
                                         . $this->p_regexp_html_comment . "\n"
                                         ."<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\">&nbsp;"
                                         .$row3["type"]."<\/td>"));
        $this->_testFor_pattern( $text, ("<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Project "
                                         ."Volume:<\/b><\/td>\n"
                                         .$this->p_regexp_html_comment . "\n"
                                         .$this->p_regexp_html_comment . "\n"
                                         ."<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\">&nbsp;"
                                         .$row3["volume"]
                                         ."<\/td>"));
        // TODO: this is wrong, 100 euro should not appear here ....
        $this->_testFor_pattern( $text, ("<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\"><b>Current "
                                         ."project budget:<\/b><\/td>\n"
                                         .$this->p_regexp_html_comment . "\n"
                                         .$this->p_regexp_html_comment . "\n"
                                         ."<td align=\"left\" width=\"\" "
                                         ."bgcolor=\"#FFFFFF\">"
                                         .$row4["SUM(budget)"]
                                         ." euro<\/td>"));

        // check that the database component did not fail
        $this->assertEquals( false, $db_config->did_db_fail(),
                             $db_config->error_message() );
    }

    function testCheckcnt() {
        // need to instantiate a new DB_SourceAgency object for the
        // global $db variable
        global $db;

        $db_config = new mock_db_configure;
        $db_config->set_nr_instance_expected( 2 );
        $db_q = array( 0 => ("DELETE FROM counter_check WHERE "
                             ."DATE_FORMAT(creation_cnt,'%Y-%m-%d') != '"
                             . date("Y-m-d") . "'"),
                       1 => ("SELECT * FROM counter_check WHERE "
                             ."proid='%s' AND cnt_type='%s' AND ipaddr='%s'"),
                       2 => ("INSERT counter_check SET proid='%s',"
                             ."cnt_type='%s',ipaddr='%s'"));
        $dat = array( "p1" => "proid1", "t1" => "type1", "ip1" => "ipaddr1",
                      "p2" => "proid2", "t2" => "type2", "ip2" => "ipaddr2");

        $db_config->add_query( $db_q[0], 0 );
        $db_config->add_query( sprintf( $db_q[1], $dat["p1"], $dat["t1"],
                                        $dat["ip1"] ), 0 );

        $db_config->add_query( sprintf( $db_q[2], $dat["p1"], $dat["t1"],
                                        $dat["ip1"] ), 0 );

        $db_config->add_query( $db_q[0], 1 );
        $db_config->add_query( sprintf( $db_q[1], $dat["p2"], $dat["t2"],
                                        $dat["ip2"] ), 1 );

        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 1, 1 );

        // redefine the global variable $db with the configured database
        $db = new DB_SourceAgency;
        $this->assertEquals( 1, checkcnt( $dat["p1"], $dat["ip1"],$dat["t1"]));

        $db = new DB_SourceAgency;
        $this->assertEquals( 0, checkcnt( $dat["p2"], $dat["ip2"],$dat["t2"]));

        // check that the database component did not fail
        $this->assertEquals( false, $db_config->did_db_fail(),
                             $db_config->error_message() );
    }
}

define_test_suite( __FILE__ );
?>
