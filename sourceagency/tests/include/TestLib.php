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
# $Id: TestLib.php,v 1.22 2002/05/28 08:58:28 riessen Exp $
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
        $this->UnitTest( $name );
    }

    function setup() {
    }
    function tearDown() {
        unset( $GLOBALS[ 'bx' ] );
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

        $text = lib_select_yes_or_no( "fubar", "No" );
        $expect[0] = "[ \n]+<select name=\"fubar\" size=\"0\">";
        $expect[1] = "[ \n]+<option value=\"Yes\">Yes";
        $expect[2] = "[ \n]+<option selected value=\"No\">No";
        $expect[3] =  "[ \n]+<\/select>[ \n]+";
        $this->_testFor_pattern( $text, implode('', $expect), "p1" );
        $this->_testFor_string_length( $text, 118, "test 1" );

        $text = lib_select_yes_or_no( "fubar", "Yes" );
        $expect[1] = "[ \n]+<option selected value=\"Yes\">Yes";
        $expect[2] = "[ \n]+<option value=\"No\">No";
        $this->_testFor_pattern( $text, implode('', $expect), "p2" );
        $this->_testFor_string_length( $text, 118, "test 2" );

        $text = lib_select_yes_or_no( "fubar", "" );
        $expect[1] = "[ \n]+<option value=\"Yes\">Yes";
        $this->_testFor_pattern( $text, implode('', $expect), "p3" );
        $this->_testFor_string_length( $text, 109, "test 3" );
    }

    function testLib_nick() {
        $uname = 'FUBAR';
        $text = lib_nick( $uname );
        $this->assertEquals( "<b>by $uname</b>",  
                             $this->_testFor_lib_nick($text,$uname,"test 1"));

        $uname = 'SNAFU';
        capture_reset_and_start();
        lib_pnick( $uname );
        $text = capture_stop_and_get();
        $this->assertEquals( "<b>by $uname</b>",  
                             $this->_testFor_lib_nick($text,$uname,"test 2"));
        $this->_testFor_captured_length( 15 );
    }

    function testSelect_date() {
        $expect = array();
        $expect[] = "[ \n]+<select name=\"fubar_day\" size=\"0\">";
        for ( $idx = 1; $idx < 32; $idx++ ) {
            $expect[] = "[ \n]+<option value=\"" . $idx . "\">" . $idx;
        }
        $expect[] = "[ \n]+<\/select>";
        $expect[] = "[ \n]+<select name=\"fubar_month\" size=\"0\">";
        for ( $idx = 1; $idx < 13; $idx++ ) {
            $expect[] = "[ \n]+<option value=\"" . $idx . "\">" . month($idx);
        }
        $expect[] = "[ \n]+<\/select>";
        $expect[] = "[ \n]+<select name=\"fubar_year\" size=\"0\">";
        for ( $idx = 2001; $idx <= 2005; $idx++ ) {
            $expect[] = "[ \n]+<option value=\"" . $idx . "\">" . $idx;
        }
        $expect[] = "[ \n]+<\/select>[ \n]+";
        
        $text = select_date( "fubar", "-1", "-1", "-1" );
        $this->_testFor_pattern( $text, implode( "", $expect ) );
        $this->_testFor_string_length( $text, 1597, "test 3" );
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
        global $bx, $t;
        // 4 instances required: 2 for the no budget case, and 2 for the
        // budget is set case.
        $db_config = new mock_db_configure( 4 );
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
        $db_config->add_num_row( $dat["e1"], 1 );
        $db_config->add_num_row( $dat["e3"], 3 );

        $row1 = $this->_generate_array(array("description_user", "volume",
                                             "type","description_creation",
                                             "perms"),1);
        $db_config->add_record( $row1, 0 );
        $row3 = $this->_generate_array(array("description_user", "volume",
                                             "type","description_creation",
                                             "perms"),2);
        $row3["perms"] = "devel";
        $db_config->add_record( $row3, 2 );
        $row2 = array( "SUM(budget)" => "INCORRECT" );
        $db_config->add_record( $row2, 1 );
        $row4 = array( "SUM(budget)" => 1000 );
        $db_config->add_record( $row4, 3 );

        // test one: no budget
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        calendar_box( $dat["r0"] );
        $text = capture_stop_and_get();

        $this->_checkFor_columns( $text, 2 );

        $titles=array("Project Owner(s)","Project Type","Project Nature",
                      "Project Volume","Current project budget","Creation");
        $this->_checkFor_column_titles( $text, $titles, '', 'left', '55%',
                                                            '', '<b>%s:</b>' );

        $tStamp = mktimestamp($row1['description_creation']);
        $nature = "Unknown";
        $budget = "0";

        $values=array( '&nbsp;'.$row1["description_user"],
                       '&nbsp;'.$row1["type"],
                       '&nbsp;'.$t->translate($nature),
                       '&nbsp;'.$row1['volume'],
                       "$budget euro",
                       '&nbsp;'.timestr_middle($tStamp));
        $this->_checkFor_column_values( $text, $values, '', 'left','','');

        $this->_testFor_captured_length( 3287, "test 1" );

        // test two:
        // This run has a budget and the budget value should be printed
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        calendar_box( $dat["r2"] );
        $text = capture_stop_and_get();

        $this->_testFor_box_columns_begin( $text, 2 );
        $this->_testFor_box_columns_end( $text );

        $this->_testFor_captured_length( 3293, "test 2" );
        $titles=array("Project Owner(s)","Project Type","Project Nature",
                      "Project Volume","Current project budget","Creation");
        $this->_checkFor_column_titles( $text, $titles, '', 'left', '55%',
                                                            '', '<b>%s:</b>' );

        $tStamp = mktimestamp($row3['description_creation']);
        $nature = "Developing";
        $budget = $row4["SUM(budget)"];

        $values = array( '&nbsp;'.$row3["description_user"],
                         '&nbsp;'.$row3["type"],
                         '&nbsp;'.$t->translate($nature),
                         '&nbsp;'.$row3['volume'],
                         "$budget euro",
                         '&nbsp;'.timestr_middle($tStamp));
        $this->_checkFor_column_values( $text, $values, '', 'left','','');

        // check that the database component did not fail
        $this->_check_db( $db_config );
    }


    function testLicensep() {
        $db_config = new mock_db_configure( 1 );
        $db_q = array( 0 => ("SELECT * FROM licenses ORDER BY license ASC") );

        $db_config->add_query( $db_q[0], 0 );
        $db_config->add_num_row( 1, 0 );

        $row = array();
        $row[0] = array( "license" => "snafu" );
        $db_config->add_record( $row[0], 0 );
        $row[1] = array( "license" => "fritz" );
        $db_config->add_record( $row[1], 0 );
        $row[2] = array( "license" => "fubar" );
        $db_config->add_record( $row[2], 0 );
        $row[3] = array( "license" => "hugo" );
        $db_config->add_record( $row[3], 0 );

        capture_reset_and_start();
        licensep( $row[0]["license"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 198, "test 1" );

        $this->_testFor_pattern( $text, "selected value=\"".$row[0]["license"]
                                        ."\">".$row[0]["license"]."" );

        for ( $idx = 1; $idx < count( $row ); $idx++ ) {
            $this->_testFor_pattern( $text, ("value=\"".$row[$idx]["license"]
                                             . "\">" .$row[$idx]["license"]),
                                     "row: " . $idx . " missing");
        }

        // check that the database component did not fail
        $this->_check_db( $db_config );
    }

    function testLib_show_description() {
        global $bx;

        $db_config = new mock_db_configure( 2 );
        $db_q = array( 0 => "SELECT %s FROM %s" );

        $db_config->add_query( sprintf( $db_q[0], "*", "*"), 0 );
        $db_config->add_query( sprintf( $db_q[0], "X", "Y"), 1 );

        $row = $this->_generate_records( array( "proid", "description",
                                                "description_creation",
                                                "volume", "description_user",
                                                "project_title", "type"), 1 );
        $db_config->add_record( $row[0], 0 );
        $db_config->add_num_row( 1, 0 );
        $db_config->add_num_row( 0, 1 );

        $bx = $this->_create_default_box();
        capture_reset_and_start();
        lib_show_description( sprintf( $db_q[0], "*", "*") );
        $text = capture_stop_and_get();
        $pats = array( 0=>("<b>by description_user_0<\/b>"),
                       1=>("<a href=\"summary.php3\?proid="
                           ."proid_0\" class=\"\">project_title_0<\/a>" ),
                       2=>("<b>Description<\/b>: description_0"),
                       3=>("<b>Volume<\/b>: volume_0" ));
        $this->_testFor_patterns($text, $pats, 4 );
        $this->_testFor_captured_length( 828, "test 1" );

        $bx = $this->_create_default_box();
        capture_reset_and_start();
        lib_show_description( sprintf( $db_q[0], "X", "Y") );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0, "test 2" );

        // check that the database component did not fail
        $this->_check_db( $db_config );
    }

    function testLib_show_comments_on_it() {
        // because lib_show_comments_on_it is a recursive function, this
        // test method is kind of complex.
        $db_config = new mock_db_configure( 5 );
        $db_q = array( 0 => ("SELECT * FROM comments,auth_user WHERE "
                             ."proid='%s' AND type='%s' AND number='%s' "
                             . "AND ref='%s' AND user_cmt=username "
                             . "ORDER BY creation_cmt ASC") );
        // data for 2 calls 
        $dat = $this->_generate_records( array( "proid", "cmt_type","num", 
                                                "cmt_id"), 2 );
        // data records 
        $row = $this->_generate_records( array( "user_cmt", "subject_cmt",
                                                "creation_cmt", "id" ), 3 );
        $db_config->add_record( $row[0], 1 );
        $db_config->add_record( $row[1], 1 );
        $db_config->add_record( $row[2], 2 );

        $db_config->add_query( sprintf( $db_q[0], $dat[0]["proid"],
                                        $dat[0]["cmt_type"], $dat[0]["num"], 
                                        $dat[0]["cmt_id"]), 0 );

        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"],
                                        $dat[1]["cmt_type"], $dat[1]["num"], 
                                        $dat[1]["cmt_id"]), 1 );

        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"],
                                        $dat[1]["cmt_type"], $dat[1]["num"], 
                                        $row[0]["id"]), 2 );

        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"],
                                        $dat[1]["cmt_type"], $dat[1]["num"], 
                                        $row[2]["id"]), 3 );

        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"],
                                        $dat[1]["cmt_type"], $dat[1]["num"], 
                                        $row[1]["id"]), 4 );

        $db_config->add_num_row( 0, 0 ); // first call, 1st instance
        $db_config->add_num_row( 2, 1 ); // second call, 2nd instance
        $db_config->add_num_row( 1, 2 ); //  3rd instance created by 2nd inst
        $db_config->add_num_row( 0, 3 ); //   4th instance created by 3rd
        $db_config->add_num_row( 0, 4 ); //  5th instance created by 2nd inst  


        //
        // no data points and no recursive call
        //
        capture_reset_and_start();
        lib_show_comments_on_it( $dat[0]["proid"],$dat[0]["cmt_type"],
                                 $dat[0]["num"], $dat[0]["cmt_id"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 4, "test 1" );
        $this->assertEquals( "<p>\n", $text );

        //
        // this has two data points and does a recursive call ...
        //
        capture_reset_and_start();
        lib_show_comments_on_it( $dat[1]["proid"],$dat[1]["cmt_type"],
                                 $dat[1]["num"], $dat[1]["cmt_id"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 463, "test 2" );

        foreach ( array( &$row[0], &$row[1] ) as $rw ) {
            $this->_testFor_html_link( $text, 'comments.php3', 
                 array('proid'=>$dat[1]["proid"], 'type'=>$dat[1]["cmt_type"],
                       'number'=>$dat[1]["num"], 'ref'=>$dat[1]["cmt_id"] ),
                 $rw['subject_cmt'], '' );
            $str = ' by <b>' . $rw['user_cmt'].'</b> on <b>'
                 . timestr_comment( mktimestamp( $rw['creation_cmt']))
                 . "</b>\n";
            $this->_testFor_pattern( $text, $this->_to_regexp( $str ) );
            // to ensure that the link and the user occur on the same line
            $str = $rw['subject_cmt']."</a> by <b>".$rw['user_cmt'];
            $this->_testFor_pattern( $text, $this->_to_regexp( $str ) );
        }
        foreach ( array( &$row[2] ) as $rw ) {
            $this->_testFor_html_link( $text, 'comments.php3', 
                 array('proid'=>$dat[1]["proid"], 'type'=>$dat[1]["cmt_type"],
                       'number'=>$dat[1]["num"], 'ref'=>$row[0]["id"] ),
                 $rw['subject_cmt'], '' );
            $str = ' by <b>' . $rw['user_cmt'].'</b> on <b>'
                 . timestr_comment( mktimestamp( $rw['creation_cmt']))
                 . "</b>\n";
            $this->_testFor_pattern( $text, $this->_to_regexp( $str ) );
            // to ensure that the link and the user occur on the same line
            $str = $rw['subject_cmt']."</a> by <b>".$rw['user_cmt'];
            $this->_testFor_pattern( $text, $this->_to_regexp( $str ) );
        }
        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testEnd_content() {
        capture_reset_and_start();
        end_content();
        $text = capture_stop_and_get();
        $this->assertEquals( "\n\n<!-- end content -->\n\n", $text );
    }
    function testFollowup() {
        $this->_test_to_be_completed();
    }

    function testIs_not_set_or_empty() {
        $this->_test_to_be_completed();
    }

    function testIs_set_and_not_empty() {
        $this->_test_to_be_completed();
    }

    function testLib_comment_it() {
        $dat=$this->_generate_records(array('proid','type','number','ref',
                                           'subject','text'),1);
        capture_reset_and_start();
        call_user_func_array( 'lib_comment_it', $dat[0] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 144 );
        $this->_call_method( '_testFor_lib_comment_it', 
                             array_merge( array('txt'=>&$text), $dat[0]));
    }

    function testLib_count_total() {
        $this->_test_to_be_completed();
    }

    function testLib_die() {
        $this->_test_to_be_completed();
    }

    function testLib_get_project_step() {
        $this->_test_to_be_completed();
    }

    function testLib_insertion_finished() {
        $this->_test_to_be_completed();
    }

    function testLib_insertion_information() {
        $this->_test_to_be_completed();
    }

    function testLib_in_step() {
        $this->_test_to_be_completed();
    }

    function testLib_merge_arrays() {
        $this->_test_to_be_completed();
    }

    function testLib_past_step() {
        $this->_test_to_be_completed();
    }

    function testLib_pnick() {
        $this->_test_to_be_completed();
    }

    function testLib_previous_comment() {
        $this->_test_to_be_completed();
    }

    function testLib_show_more() {
        $this->_test_to_be_completed();
    }

    function testLicense() {
        $this->_test_to_be_completed();
    }

    function testSelect_from_config() {
        $this->_test_to_be_completed();
    }

    function testShow_project_milestones() {
        $this->_test_to_be_completed();
    }

    function testShow_project_participants() {
        $this->_test_to_be_completed();
    }

    function testStart_content() {
        capture_reset_and_start();
        start_content();
        $text = capture_stop_and_get();
        $this->assertEquals( "\n\n<!-- content -->\n\n", $text );
    }

    function testStep_information() {
        $this->_test_to_be_completed();
    }

    function testSummary() {
        $this->_test_to_be_completed();
    }

    function testSummary_news() {
        $this->_test_to_be_completed();
    }

    function testTimestr_comment() {
        $this->_test_to_be_completed();
    }

    function testTimestr_middle() {
        $this->_test_to_be_completed();
    }

    function testTimestr_short() {
        $this->_test_to_be_completed();
    }

    function testTimestr_shortest() {
        $this->_test_to_be_completed();
    }

    function testTop_bar() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
