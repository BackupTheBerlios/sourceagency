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
# $Id: TestLib.php,v 1.27 2002/06/14 09:14:12 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

include_once( 'box.inc' );
include_once( 'security.inc' );
include_once( 'lib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $GLOBALS[ 'sess' ] = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

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
        unset( $GLOBALS[ 'db' ] );
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

        $this->set_text( lib_select_yes_or_no( "fubar", "No" ) );
        $this->set_msg( "test 1" );
        $expect[0] = "[ \n]+<select name=\"fubar\" size=\"0\">";
        $expect[1] = "[ \n]+<option value=\"Yes\">Yes";
        $expect[2] = "[ \n]+<option selected value=\"No\">No";
        $expect[3] =  "[ \n]+<\/select>[ \n]+";
        $this->_testFor_pattern( implode('', $expect) );
        $this->_testFor_string_length( 118 );

        $this->set_text( lib_select_yes_or_no( "fubar", "Yes" ) );
        $this->set_msg( 'test 2' );
        $expect[1] = "[ \n]+<option selected value=\"Yes\">Yes";
        $expect[2] = "[ \n]+<option value=\"No\">No";
        $this->_testFor_pattern( implode('', $expect) );
        $this->_testFor_string_length( 118 );

        $this->set_text( lib_select_yes_or_no( "fubar", "" ) );
        $this->set_msg( 'test 3' );
        $expect[1] = "[ \n]+<option value=\"Yes\">Yes";
        $this->_testFor_pattern( implode('', $expect) );
        $this->_testFor_string_length( 109 );
    }

    function testLib_nick() {
        $uname = 'FUBAR';
        $this->set_text( lib_nick( $uname ) );
        $this->set_msg( 'test 1' );
        $this->assertEquals("<b>by $uname</b>",
                            $this->_testFor_lib_nick($uname));
        $this->_testFor_string_length( 15 );

        $uname = 'SNAFU';
        $this->capture_call( 'lib_pnick', 15, array( $uname ) );
        $this->assertEquals( "<b>by $uname</b>",  
                             $this->_testFor_lib_nick($uname));
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
        
        $this->set_text( select_date( "fubar", "-1", "-1", "-1" ) );
        $this->_testFor_pattern( implode( "", $expect ) );
        $this->_testFor_string_length( 1597 );
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
        $this->assertEquals( $t->translate("Other"),         typestr("What?"));
        $this->assertEquals( $t->translate("Other"),         typestr( "" ) );
    }
    
    function testShow_status() {
        $this->assertEquals( "Proposed",    show_status( 'P' ) );
        $this->assertEquals( "Negotiating", show_status( 'N' ) );
        $this->assertEquals( "Accepted",    show_status( 'A' ) );
        $this->assertEquals( "Rejected",    show_status( 'R' ) );
        $this->assertEquals( "Deleted",     show_status( 'D' ) );
        $this->assertEquals( "Modified",    show_status( 'M' ) );
        $this->assertEquals( "Proposed",    show_status( '' ) );
        $this->assertEquals( "Proposed",    show_status( 'asdasd' ) );
        $this->assertEquals( "Proposed",    show_status( 'm' ) );
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
        $this->set_msg( 'test 1' );
        $this->capture_call( 'calendar_box', 3287, array(0=>$dat["r0"]));

        $this->_checkFor_columns( 2 );

        $titles=array("Project Owner(s)","Project Type","Project Nature",
                      "Project Volume","Current project budget","Creation");
        $this->_checkFor_column_titles( $titles,'left','55%','','<b>%s:</b>');

        $tStamp = mktimestamp($row1['description_creation']);
        $nature = "Unknown";
        $budget = "0";

        $values=array( '&nbsp;'.$row1["description_user"],
                       '&nbsp;'.$row1["type"],
                       '&nbsp;'.$t->translate($nature),
                       '&nbsp;'.$row1['volume'],
                       "$budget euro",
                       '&nbsp;'.timestr_middle($tStamp));
        $this->_checkFor_column_values( $values, 'left', '', '');

        // test two:
        // This run has a budget and the budget value should be printed
        $bx = $this->_create_default_box();
        $this->set_msg( 'test 2' );
        $this->capture_call( 'calendar_box', 3293, array(0=>$dat["r2"]));

        $this->_checkFor_columns( 2 );

        $titles=array("Project Owner(s)","Project Type","Project Nature",
                      "Project Volume","Current project budget","Creation");
        $this->_checkFor_column_titles( $titles,'left','55%','', '<b>%s:</b>');

        $tStamp = mktimestamp($row3['description_creation']);
        $nature = "Developing";
        $budget = $row4["SUM(budget)"];

        $values = array( '&nbsp;'.$row3["description_user"],
                         '&nbsp;'.$row3["type"],
                         '&nbsp;'.$t->translate($nature),
                         '&nbsp;'.$row3['volume'],
                         "$budget euro",
                         '&nbsp;'.timestr_middle($tStamp));
        $this->_checkFor_column_values( $values, 'left','','');

        // check that the database component did not fail
        $this->_check_db( $db_config );
    }

    function testLicense() {
        $this->testLicensep();
    }

    function testLicensep() {
        $db_config = new mock_db_configure( 2 );
        $db_q = array( 0 => ("SELECT * FROM licenses ORDER BY license ASC") );

        $db_config->add_query( $db_q[0], 0 );
        $db_config->add_num_row( 1, 0 );
        $db_config->add_query( $db_q[0], 1 );
        $db_config->add_num_row( 1, 1 );

        $row =$this->_generate_records( array( 'license' ), 10 );
        for ( $idx = 0; $idx < count( $row ); $idx++ ) {
            $db_config->add_record( $row[$idx], 0 );
            $db_config->add_record( $row[$idx], 1 );
        }

        // test one using the licensep function
        $this->set_msg( 'test 1' );
        $this->capture_call( 'licensep', 490, array( 0=>$row[0]["license"]));

        $this->_testFor_html_select( 'license' );
        $this->_testFor_html_select_end();
        for ( $idx = 0; $idx < count( $row ); $idx++ ) {
            $this->push_msg( "row: " . $idx . " missing");
            $this->_testFor_html_select_option( $row[$idx]["license"],
                            $row[$idx]["license"] == $row[0]['license'],
                            $row[$idx]["license"]);
            $this->pop_msg();
        }
        
        // test two using the license function
        $this->set_msg( 'test 2' );
        $this->set_text( $this->capture_call( 'license', 0, 
                                              array( 0=>$row[1]["license"])));
        $this->_testFor_html_select( 'license' );
        $this->_testFor_html_select_end();
        for ( $idx = 0; $idx < count( $row ); $idx++ ) {
            $this->push_msg( "row: " . $idx . " missing");
            $this->_testFor_html_select_option( $row[$idx]["license"],
                            $row[$idx]["license"] == $row[1]['license'],
                            $row[$idx]["license"]);
            $this->pop_msg();
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
        $this->set_msg( 'test 1' );
        $this->capture_call( 'lib_show_description', 828, 
                             array( 0 => sprintf( $db_q[0], "*", "*") ));
        $pats = array( 0=>("<b>by description_user_0<\/b>"),
                       1=>("<a href=\"summary.php3\?proid="
                           ."proid_0\" class=\"\">project_title_0<\/a>" ),
                       2=>("<b>Description<\/b>: description_0"),
                       3=>("<b>Volume<\/b>: volume_0" ));
        $this->_testFor_patterns($pats, 4 );

        $bx = $this->_create_default_box();
        $this->set_msg( 'test 2' );
        $this->capture_call( 'lib_show_description', 0, 
                             array( 0 => sprintf( $db_q[0], "X", "Y")));

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
        $this->set_msg( 'test 1' );
        $this->capture_call( 'lib_show_comments_on_it', 4, $dat[0] );
        $this->assertEquals( "<p>\n", $this->get_text() );

        //
        // this has two data points and does a recursive call ...
        //
        $this->set_msg( 'test 2' );
        $this->capture_call( 'lib_show_comments_on_it', 463, $dat[1] );

        foreach ( array( &$row[0], &$row[1] ) as $rw ) {
            $this->_testFor_html_link( 'comments.php3', 
                 array('proid'=>$dat[1]["proid"], 'type'=>$dat[1]["cmt_type"],
                       'number'=>$dat[1]["num"], 'ref'=>$dat[1]["cmt_id"] ),
                 $rw['subject_cmt'] );

            $str = ' by <b>' . $rw['user_cmt'].'</b> on <b>'
                 . timestr_comment( mktimestamp( $rw['creation_cmt']))
                 . "</b>\n";
            $this->_testFor_pattern( $this->_to_regexp( $str ) );

            // to ensure that the link and the user occur on the same line
            $str = $rw['subject_cmt']."</a> by <b>".$rw['user_cmt'];
            $this->_testFor_pattern( $this->_to_regexp( $str ) );
        }
        foreach ( array( &$row[2] ) as $rw ) {
            $this->_testFor_html_link( 'comments.php3', 
                 array('proid'=>$dat[1]["proid"], 'type'=>$dat[1]["cmt_type"],
                       'number'=>$dat[1]["num"], 'ref'=>$row[0]["id"] ),
                 $rw['subject_cmt'] );

            $str = ' by <b>' . $rw['user_cmt'].'</b> on <b>'
                 . timestr_comment( mktimestamp( $rw['creation_cmt']))
                 . "</b>\n";
            $this->_testFor_pattern( $this->_to_regexp( $str ) );

            // to ensure that the link and the user occur on the same line
            $str = $rw['subject_cmt']."</a> by <b>".$rw['user_cmt'];
            $this->_testFor_pattern( $this->_to_regexp( $str ) );
        }
        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testEnd_content() {
        $this->capture_call( 'end_content', 24 );
        $this->assertEquals("\n\n<!-- end content -->\n\n", $this->get_text());
    }

    function testIs_not_set_or_empty() {
        $m = 'is_not_set_or_empty';

        /** test is set but empty **/
        $empty = "";
        $this->assertEquals(true,$this->capture_call( $m,0,array( &$empty )));
        /** test is not set (value is also empty) */
        $uset = "fubar";
        unset( $uset );
        $this->assertEquals(true,$this->capture_call( $m,0,array( &$uset )));
        /** test value is set and is not empty **/
        $set = "this is set and not empty";
        $this->assertEquals(false,$this->capture_call( $m,0,array( &$set )));
        /* can't test is not set and not empty */
    }

    function testIs_set_and_not_empty() {
        $m = 'is_set_and_not_empty';
        /** test is set but empty **/
        $empty = "";
        $this->assertEquals(false,$this->capture_call( $m,0,array( &$empty )));
        /** test is not set (value is also empty) */
        $unset = "fubar";
        unset( $unset );
        $this->assertEquals(false,$this->capture_call( $m,0,array( &$unset )));
        /** test value is set and is not empty **/
        $set = "this is set and not empty";
        $this->assertEquals(true,$this->capture_call( $m,0,array( &$set )));
        /* can't test is not set and not empty */
    }

    function testLib_comment_it() {
        $dat=$this->_generate_records(array('proid','type','number','ref',
                                           'subject','text'),1);
        $this->capture_call( 'lib_comment_it', 144, $dat[0] );
        $this->_call_method( '_testFor_lib_comment_it', $dat[0]);
    }

    function testLib_count_total() {
        $d=$this->_generate_records( array( 'COUNT(*)' ), 10 );
        $args=$this->_generate_records(array('table','where'), count($d) );
        $db_config = new mock_db_configure( count($d) );
        $m = 'lib_count_total';

        for ( $idx = 0; $idx < count( $d ); $idx++ ) {
            $d[$idx]['COUNT(*)'] = $idx;
            if ( $idx % 2 ) {
                $args[$idx]['where'] = '';
            }
            $q = sprintf( "SELECT COUNT(*) FROM %s %s", $args[$idx]['table'],
                          ( $idx % 2 ? '' : 'WHERE '.$args[$idx]['where']));
            $db_config->add_query( $q, $idx );
            $db_config->add_record( $d[$idx], $idx );
            $this->assertEquals($idx, $this->capture_call($m,0,$args[$idx]));
        }
        $this->_check_db( $db_config );
    }

    function testLib_die() {
        global $db, $t;
        // mailuser queries the database
        $db_config = new mock_db_configure( 1 );
        $db_config->add_query( "SELECT email_usr FROM auth_user WHERE perms "
                               ."LIKE '%admin%'" );
        $db_config->add_record( FALSE );

        $db = new DB_SourceAgency;
        $this->capture_call( 'lib_die', 753, array( ''=>'is error msg' ));

        $this->_testFor_pattern( 'is error msg' );
        $this->_testFor_pattern($t->translate('This error is being mailed to '
                                              .'the system administrators.'));
        $this->_testFor_pattern($t->translate('An error has ocurred'));

        $this->_check_db( $db_config );
    }

    function testLib_get_project_step() {
        global $db;

        $db_config = new mock_db_configure( 3 );
        $q = "SELECT status FROM description WHERE proid='%s'";
        $args = $this->_generate_records( array( 'proid' ), 2 );
        $d = $this->_generate_records( array( 'status' ), 1 );

        $db_config->add_query( sprintf( $q, $args[0]['proid'] ), 1 );
        $db_config->add_query( sprintf( $q, $args[1]['proid'] ), 2 );
        $db_config->add_num_row( 0, 1 );
        $db_config->add_num_row( 1, 2 );
        $db_config->add_record( $d[0], 2 );

        $db_config->add_query( "SELECT email_usr FROM auth_user WHERE perms "
                               ."LIKE '%admin%'", 0 );
        $db_config->add_record( FALSE, 0 );
        
        // test one, no data
        $db = new DB_SourceAgency; // for the lib_die(...) call
        $this->assertEquals( 0, $this->capture_call( 'lib_get_project_step', 
                                                     812, $args[0]));
        $this->_testFor_pattern( 'Error in lib.inc in function lib_in_step: '
                                 .'no step for the given project');

        // test two, one status data point
        $this->assertEquals( $d[0]['status'],
                        $this->capture_call( 'lib_get_project_step', 
                                             0, $args[1]));
        $this->_check_db( $db_config );
    }

    function testStart_content() {
        $this->capture_call( 'start_content', 20 );
        $this->assertEquals( "\n\n<!-- content -->\n\n", $this->get_text() );
    }


    function testLib_insertion_finished() {
        global $auth;
        $auth->set_uname( 'fubar' );
        $this->capture_call( 'lib_insertion_finished', 427 );
        $this->_testFor_html_link( 'personal.php3',
                                   array( 'username' => $auth->auth['uname'] ),
                                   'Personal Page');
    }

    function testLib_in_step() {
        $d = $this->_generate_records( array( 'status' ), 25 );
        $args = $this->_generate_records(array('proid','step_num'),count($d));
        $db_config = new mock_db_configure( count($d) );
        $q = "SELECT status FROM description WHERE proid='%s'";

        for ( $idx = count($d)/-2; $idx < count($d)/2; $idx++ ) {
            $jdx = $idx + count($d)/2;
            $db_config->add_query( sprintf( $q, $args[$jdx]['proid']), $jdx);
            $db_config->add_num_row( 1, $jdx );
            $d[$jdx]['status'] = $idx;
            $db_config->add_record( $d[$jdx], $jdx );
            $args[$jdx]['step_num'] = 0;

            $this->assertEquals( $idx == $args[$jdx]['step_num'],
                                 $this->capture_call( 'lib_in_step', 0, 
                                                      $args[$jdx] ), 
                                 "Test $idx (SN = $args[$jdx]['step_num'])");
        }
        $this->_check_db( $db_config );
    }

    function testLib_past_step() {
        $d = $this->_generate_records( array( 'status' ), 10 );
        $args = $this->_generate_records(array('proid','step_num'),count($d));
        $db_config = new mock_db_configure( count($d) );
        $q = "SELECT status FROM description WHERE proid='%s'";

        for ( $idx = count($d)/-2; $idx < count($d)/2; $idx++ ) {
            $jdx = $idx + count($d)/2;
            $db_config->add_query( sprintf( $q, $args[$jdx]['proid']), $jdx);
            $db_config->add_num_row( 1, $jdx );
            $d[$jdx]['status'] = $idx;
            $db_config->add_record( $d[$jdx], $jdx );
            $args[$jdx]['step_num'] = 0;

            $this->assertEquals( $idx > $args[$jdx]['step_num'],
                                 $this->capture_call( 'lib_past_step', 0, 
                                                      $args[$jdx] ), 
                                 "Test $idx (SN = $args[$jdx]['step_num'])");
        }
        $this->_check_db( $db_config );
    }

    function testLib_pnick() {
        $this->testLib_nick();
    }

    function testLib_insertion_information() {
        global $bx, $t, $auth;

        $title_text = 'Project Insertion process';
        $sponsor_text=('You are logged in in SourceAgency as sponsor <p>In '
                       .'order to insert a project, you will have to follow '
                       .'this steps: <ul><li>Fill out the insertion formular '
                       .'<li>Configure the project parameters <li>Fill out a '
                       .'sponsoring involvement form for your project</ul> '
                       .'<p>After that you should wait for a BerliOS editor to'
                       .' review your project');
        $devel_text=('In order to insert a project, you will have to follow '
                     .'this steps: <ul><li>Fill out the insertion formular '
                     .'<li>Configure the project parameters </ul> <p>After '
                     .'that you should wait for a BerliOS editor to review '
                     .'your project');

        $auth->set_uname( 'this is the username' );
        $db_config = new mock_db_configure( 6 );
        $q = ( "SELECT * FROM auth_user WHERE perms LIKE '%%%s%%'"
               ." AND username='%s'" );

        // first test, nothing is printed because user is neither 
        // sponsor nor developer
        $auth->set_perm( "" );
        $bx = $this->_create_default_box();
        $this->capture_call( 'lib_insertion_information', 0 );
        
        // second test, user is sponsor
        $auth->set_perm( "always" );
        $bx = $this->_create_default_box();
        $db_config->add_query(sprintf( $q,'sponsor',$auth->auth['uname']),0);
        $db_config->add_num_row( 1, 0 );
        $db_config->add_query(sprintf( $q,'devel',$auth->auth['uname']),1);
        $db_config->add_num_row( 0, 1 );
        $this->capture_call( 'lib_insertion_information', 1085);
        $this->_checkFor_box_full( $t->translate($title_text),
                                   $t->translate($sponsor_text));
        $this->reverse_next_test();
        $this->_testFor_pattern($this->_to_regexp($t->translate($devel_text)));

        // third test, user is developer
        $auth->set_perm( "always" );
        $bx = $this->_create_default_box();
        $db_config->add_query(sprintf( $q,'sponsor',$auth->auth['uname']),2);
        $db_config->add_num_row( 0, 2 );
        $db_config->add_query(sprintf( $q,'devel',$auth->auth['uname']),3);
        $db_config->add_num_row( 1, 3 );
        $this->capture_call( 'lib_insertion_information', 978);
        $this->_checkFor_box_full( $t->translate($title_text),
                                   $t->translate($devel_text));
        $this->reverse_next_test();
        $this->_testFor_pattern($this->_to_regexp(
                                             $t->translate($sponsor_text)));
        
        // fourth test, user is both developer and sponsor
        $auth->set_perm( "always" );
        $bx = $this->_create_default_box();
        $db_config->add_query(sprintf( $q,'sponsor',$auth->auth['uname']),4);
        $db_config->add_num_row( 1, 4 );
        $db_config->add_query(sprintf( $q,'devel',$auth->auth['uname']),5);
        $db_config->add_num_row( 1, 5 );
        $this->capture_call( 'lib_insertion_information', 2063);
        $this->_checkFor_box_full( $t->translate($title_text),
                                   $t->translate($devel_text));
        $this->_checkFor_box_full( $t->translate($title_text),
                                   $t->translate($sponsor_text));
        
        $this->_check_db( $db_config );
    }

    function testLib_merge_arrays() {
        // TODO: should also test non-array arguments ...
        // test one
        $ea = array();
        $this->_compare_arrays( 
            $this->capture_call('lib_merge_arrays',0,array(&$ea,&$ea)),
            array_merge( $ea, $ea ));

        // test two
        $a1 = $this->_generate_array( array( 'one','two','three' ) );
        $a2 = $this->_generate_array( array( 'four','five','six' ) );
        $this->_compare_arrays( 
            $this->capture_call('lib_merge_arrays',0,array(&$a1,&$a2)),
            array_merge( $a1, $a2 ));
        $this->assertEquals( 6, count( array_merge( $a1, $a2 ) ) );

        // test three
        $a1 = array( '_0_'=> 'zero', '_2_' => 'two',   '_3_' => 'three' );
        $a2 = array( '_1_'=> 'one',  '_2_' => 'three', '_5_' => 'five' );
        $this->_compare_arrays( 
            $this->capture_call('lib_merge_arrays',0,array(&$a1,&$a2)),
            array_merge( $a1, $a2 ));
        $this->assertEquals( 5, count( array_merge( $a1, $a2 ) ) );

        // test four
        reset( $a1 );
        $this->_compare_arrays( 
            $this->capture_call('lib_merge_arrays',0,array(&$a1,&$ea)), $a1 );

        // test five
        reset( $a1 );
        $this->_compare_arrays( 
            $this->capture_call('lib_merge_arrays',0,array(&$ea,&$a1)), $a1 );
    }

    function testLib_show_more() {
        /** corresponding function is never used and can be removed **/
    }

    function testLib_previous_comment() {
        $m='lib_previous_comment';
        $args=$this->_generate_array(array('proid', 'type','number','ref',
                                           'text'));
        $this->capture_call( $m, 121, $args );
        $this->_testFor_html_link( 'comments.php3', 
                                   array('proid'  => $args['proid'], 
                                         'type'   => $args['type'], 
                                         'number' => $args['number'], 
                                         'ref'    => $args['ref']),
                                   $args['text']);
    }

    function testSelect_from_config() {
        include( 'config.inc' );

        $args = array( 'name'=>'', 'array_name'=>'', 'selected'=>'' );

        $v=array( 'project_types' 
                     => array($project_types,'Expansion',302 ),
                  'project_volume' 
                     => array($project_volume,'< 1 Man Month', 379),
                  'platform_array' 
                     => array($platform_array,'Linux',559),
                  'architecture_array' 
                     => array($architecture_array,'x86',361 ),
                  'environment_array'
                     => array($environment_array,'Distributed',240),
                  'milestone_product_array' 
                     => array($milestone_product_array, 'Beta', 362) );
        $counter=0;
        while ( list( $key, $val ) = each( $v ) ) {
            $this->push_msg( "Test: $key" );
            list ( , $ary ) = each( $val );
            list ( , $selected ) = each( $val );
            list ( , $string_length ) = each( $val );
            $args['selected'] = $selected;
            $args['name'] = "This is the $key";
            $args['array_name'] = $key;
            $this->set_text($this->capture_call('select_from_config',0,$args));
            $this->_testFor_string_length( $string_length );
            $this->_testFor_html_select( $args['name'] );
            $this->_testFor_html_select_end();
            while ( list( , $opt ) = each( $ary ) ) {
                $counter++;
                $this->_testFor_html_select_option($opt, ($selected == $opt),
                                                                $opt);
            }
            reset( $ary );
            $this->pop_msg();
        }
        // paranoia check!!
        $this->assertEquals( 38, $counter, ("Change to the number of items "
                                            ."in the configuration arrays") );
    }

    function testShow_project_milestones() {
        $db_config = new mock_db_configure( 3 );
        $qs=array( 0=>( "SELECT devid FROM developing WHERE proid='%s' "
                        ."AND status='A'" ),
                   1=>( "SELECT * FROM sponsoring WHERE proid='%s' "
                        . "AND sponsor='%s'" ),
                   2=>( "SELECT developer FROM developing WHERE proid='%s' "
                        ."AND devid='%s'" ),
                   3=>( "SELECT * FROM milestones WHERE proid='%s' "
                        ."AND status='A' AND devid='%s' ORDER BY number" ));
        $proid = 'this si the proid';
        $d = array( 'devid' => 'this is the dived' );
        $d2 = array( 'developer' => 'this ios the developer' );

        $db_config->add_query( sprintf( $qs[0], $proid ), 0 );
        $db_config->add_record( $d, 0 );
        $db_config->add_query( sprintf( $qs[1], $proid, '' ), 1 );
        $db_config->add_num_row( 0, 1 );
        $db_config->add_query( sprintf( $qs[2], $proid, $d['devid'] ), 1 );
        $db_config->add_record( $d2, 1 );
        $db_config->add_query( sprintf( $qs[3], $proid, $d['devid'] ), 2 );
        $db_config->add_num_row( 0, 2 );

        $this->capture_call( 'show_project_milestones', 0, array( $proid ) );
        $this->_check_db( $db_config );
    }

    function testShow_project_participants() {
        $this->_test_to_be_completed();
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

    function testFollowup() {
        $this->_test_to_be_completed();
    }
}

define_test_suite( __FILE__ );
?>
