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
# include/sponsoringlib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestSponsoringlib.php,v 1.2 2002/02/07 12:24:17 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    global $lang;
    // need to define a global session
    include_once( "session.inc" );
    $sess = new Session;

    include_once( "translation.inc" );
    $t = new translation("English");

    include_once( "box.inc" );
    $bx = new box;
}

include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'security.inc' );
include_once( 'sponsoringlib.inc' );

class UnitTestSponsoringlib
extends UnitTest
{
    function UnitTestSponsoringlib( $name ) {
        $this->UnitTest( $name );
    }

    function setup() {
    }

    function testSponsoring_form() {
        global $sponsoring_text, $budget, $valid_day, $valid_month, 
            $valid_year, $begin_day, $begin_month, $begin_year, 
            $finish_day, $finish_month, $finish_year;
        
        $sponsoring_text = "this is the sponsoring text";
        $budget = "this is the budget";
        
        $valid_day = 3;     $begin_day = 4;     $finish_day = 5;
        $valid_month = 4;   $begin_month = 5;   $finish_month = 6;
        $valid_year = 2001; $begin_year = 2002; $finish_year = 2003;

        capture_reset_and_start();
        sponsoring_form( "proid" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 6408 );

        $ps=array( 0=>("<td align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF"
                       ."\"><select name=\"valid_day\">\n<option value=\"1"
                       ."\">1\n<option value=\"2\">2\n<option selected "
                       ."value=\"3\">3\n"),
                   1=>("<select name=\"valid_month\">\n<option value=\"1\">"
                       ."January\n<option value=\"2\">February\n<option "
                       ."value=\"3\">March\n<option selected value=\"4\""
                       .">April\n"),
                   2=>("<select name=\"valid_year\">\n<option selected "
                       ."value=\"2001\">2001\n"),
                   3=>("<td align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\""
                       ."><select name=\"begin_day\">\n<option value=\"1\">"
                       ."1\n<option value=\"2\">2\n<option value=\"3\">3\n"
                       ."<option selected value=\"4\">4\n"),
                   4=>("<select name=\"begin_month\">\n<option value=\"1\""
                       .">January\n<option value=\"2\">February\n<option "
                       ."value=\"3\">March\n<option value=\"4\">April\n"
                       ."<option selected value=\"5\">May\n"),
                   5=>("<select name=\"begin_year\">\n<option value=\"2001"
                       ."\">2001\n<option selected value=\"2002\">2002\n"),
                   6=>("<td align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF"
                       ."\"><select name=\"finish_day\">\n<option value=\""
                       ."1\">1\n<option value=\"2\">2\n<option value=\"3\""
                       .">3\n<option value=\"4\">4\n<option selected "
                       ."value=\"5\">5\n"),
                   7=>("<select name=\"finish_month\">\n<option value=\"1"
                       ."\">January\n<option value=\"2\">February\n<option "
                       ."value=\"3\">March\n<option value=\"4\">April\n"
                       ."<option value=\"5\">May\n<option selected value"
                       ."=\"6\">June\n"),
                   8=>("<select name=\"finish_year\">\n<option value=\"2001"
                       ."\">2001\n<option value=\"2002\">2002\n<option "
                       ."selected value=\"2003\">2003\n"),
                   9=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Budget [(]in euro[)]<\/b> [(]12[)]: <\/td>"),
                   10=>("<td align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF"
                        ."\"><input type=\"text\" name=\"budget\" size=\"12"
                        ."\" maxlength=\"12\" value=\"this is the budget\""
                        .">\n<\/td>\n"),
                   11=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                        ."\"><b>Sponsoring Comment<\/b> [(][*][)]: <\/td>\n"),
                   12=>("<td align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF"
                        ."\"><textarea cols=\"40\" rows=\"7\" name=\""
                        ."sponsoring_text\" wrap=\"virtual\" maxlength=\""
                        ."255\">this is the sponsoring text<\/textarea>\n"
                        ."<\/td>\n"),
                   13=>("<td align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF"
                        ."\"><input type=\"submit\" value=\"Preview\" name"
                        ."=\"preview\">\n<input type=\"submit\" value="
                        ."\"Submit\" name=\"submit\">\n"));
        $this->_testFor_patterns( $text, $ps, 14 );
    }

    function testShow_sponsorings() {
        global $db, $auth;

        $auth->set_uname("this is the username");
        $auth->set_perm("this is the permission");

        $db_config = new mock_db_configure( 9 );
        $db_q = array( 0 => ("SELECT * FROM sponsoring,auth_user WHERE "
                             ."proid='%s' AND sponsor=username ORDER "
                             ."BY sponsoring.creation ASC"),
                       1 => ("SELECT * FROM sponsoring WHERE proid='%s' "
                             . "AND status='A' AND sponsor='%s'"));

        $db_d = $this->_generate_records( array( "proid" ), 4 );
        $rows = $this->_generate_records( array( "creation", "username",
                                                 "budget", "status", "valid",
                                                 "begin", "finish", "spoid",
                                                 "sponsoring_text" ), 3 );
        // first call initialisations
        $db_config->add_query( sprintf( $db_q[0], $db_d[0]["proid"]), 0 );
        $db_config->add_record( false, 0 );
        $db_config->add_num_row( 0, 0 );
        
        // second call configurations
        $db_config->add_query( sprintf( $db_q[0], $db_d[1]["proid"]), 1);
        $rows[0]["status"] = "D"; // status is deleted
        $db_config->add_record( $rows[0], 1 );
        $db_config->add_record( false, 1 );
        $db_config->add_num_row( 1, 1 );
        // instance two of the database is the one used for doing
        // the lib_show_comments_on_it on the second call which is already 
        // tested so there is no need to test it here, therefore configure 
        // it's database to ignore all errors.
        $db_config->ignore_errors( MKDB_ALL_ERRORS, 2 );

        // third call configuration, here is_accepted_sponsor is called and
        // returns true
        $db_config->add_query( sprintf( $db_q[0], $db_d[2]["proid"]), 3);
        $rows[1]["status"] = "P";
        $db_config->add_record( $rows[1], 3 );
        $db_config->add_record( false, 3 );
        $db_config->add_num_row( 1, 3 );
        $db_config->add_query( sprintf( $db_q[1], $db_d[2]["proid"], 
                                                  $auth->auth["uname"]), 4);
        $db_config->add_num_row( 1, 4 ); // is_accepted_sponsor returns true
        // instance for the lib_show_comments_on_it call
        $db_config->ignore_errors( MKDB_ALL_ERRORS, 5 );

        // fourth call configuration, is_accepted_sponsor returns false
        $db_config->add_query( sprintf( $db_q[0], $db_d[3]["proid"]), 6);
        $rows[2]["status"] = "P";
        $db_config->add_record( $rows[2], 6 );
        $db_config->add_record( false, 6 );
        $db_config->add_num_row( 1, 6 );
        $db_config->add_query( sprintf( $db_q[1], $db_d[3]["proid"], 
                                                  $auth->auth["uname"]), 7);
        $db_config->add_num_row( 0, 7 ); // is_accepted_sponsor returns true
        // instance for the lib_show_comments_on_it call
        $db_config->ignore_errors( MKDB_ALL_ERRORS, 8 );
        
        // first call, no records
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        show_sponsorings( $db_d[0]["proid"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 84 );
        $this->_testFor_pattern( $text, ("<p>There have not been posted any"
                                         ." sponsoring involvement wishes "
                                         ."to this project.<p>"));

        // second call, one record but don't do is_accepted_sponsor call
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        show_sponsorings( $db_d[1]["proid"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 917 );
        $ps=array( 0=>"<b>by username_0<\/b> - <\/b>\n",
                   1=>"<p><b>Max. sum of money:<\/b> budget_0 euros\n",
                   2=>"<br><b>Status:<\/b> Deleted\n",
                   3=>("<br><b>Validity<\/b> " 
                       . timestr_middle(mktimestamp($rows[0]["valid"]))),
                   4=>("<br><b>Begin wished:<\/b> "
                       . timestr_middle(mktimestamp($rows[0]["begin"]))),
                   5=>("<br><b>Finish before:<\/b> "
                       . timestr_middle(mktimestamp($rows[0]["finish"]))),
                   6=>("<font color=\"#000000\"><b>Sponsor Involvement"
                       ."<\/b><\/font>\n"),
                   7=>("<p><b>Comments to the involvement:<\/b> "
                       ."sponsoring_text_0\n"),
                   8=>("<a href=\"comments_edit.php3[?]proid=proid_1&type="
                       ."Sponsoring&number=spoid_0&ref=0&subject=Comment"
                       ."[+]on[+]Sponsor[+]Involvement[+]%23spoid_0\">"
                       ."Comment it!<\/a>\n"));
        $this->_testFor_patterns( $text, $ps, 9 );

        // third call, is_accepted_sponsor returns true after being called
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        show_sponsorings( $db_d[2]["proid"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 1188 );
        $ps=array(0=>("<font color=\"#000000\"><b>Sponsor Involvement<\/b>"
                      ."<\/font>\n"),
                  1=>"<b>by username_1<\/b> - <\/b>\n",
                  2=>"<p><b>Max. sum of money:<\/b> budget_1 euros\n",
                  3=>"<br><b>Status:<\/b> Proposed\n",
                  4=>("<br><b>Validity<\/b> " 
                      . timestr_middle(mktimestamp($rows[1]["valid"]))),
                  5=>("<br><b>Begin wished:<\/b> "
                      . timestr_middle(mktimestamp($rows[1]["begin"]))),
                  6=>("<br><b>Finish before:<\/b> "
                      . timestr_middle(mktimestamp($rows[1]["finish"]))),
                  7=>("<p><b>Comments to the involvement:<\/b> "
                      ."sponsoring_text_1\n"),
                  8=>("<b><a href=\"sponsoring_accepted.php3[?]proid="
                      ."proid_2&sponsor=username_1\">Accept this sponsor "
                      ."involvement<\/a>\n"),
                  9=>("<a href=\"comments_edit.php3[?]proid=proid_2&type="
                      ."Sponsoring&number=spoid_1&ref=0&subject=Comment[+]"
                      ."on[+]Sponsor[+]Involvement[+]%23spoid_1\">Comment "
                      ."it!<\/a>\n"));
        $this->_testFor_patterns( $text, $ps, 10 );

        // fourth call, is_accepted_sponsor is called and returns false
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        show_sponsorings( $db_d[3]["proid"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 952 );
        $ps=array( 0=>("<font color=\"#000000\"><b>Sponsor Involvement<\/b>"
                       ."<\/font>\n"),
                   1=>"<b>by username_2<\/b> - <\/b>\n",
                   2=>"<p><b>Max. sum of money:<\/b> budget_2 euros\n",
                   3=>"<br><b>Status:<\/b> Proposed\n",
                   4=>("<br><b>Validity<\/b> " 
                       . timestr_middle(mktimestamp($rows[2]["valid"]))),
                   5=>("<br><b>Begin wished:<\/b> "
                       . timestr_middle(mktimestamp($rows[2]["begin"]))),
                   6=>("<br><b>Finish before:<\/b> "
                       . timestr_middle(mktimestamp($rows[2]["finish"]))),
                   7=>("<p><b>Comments to the involvement:<\/b> "
                       ."sponsoring_text_2\n"),
                   8=>("<a href=\"comments_edit.php3[?]proid=proid_3&type"
                       ."=Sponsoring&number=spoid_2&ref=0&subject=Comment"
                       ."[+]on[+]Sponsor[+]Involvement[+]%23spoid_2\">"
                       ."Comment it!<\/a>\n"));
        $this->_testFor_patterns( $text, $ps, 9 );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testSponsoring_preview() {
        global $auth, $sponsoring_text, $budget, $valid_day, $valid_month, 
            $valid_year, $begin_day, $begin_month, $begin_year, 
            $finish_day, $finish_month, $finish_year;


        $db_config = new mock_db_configure( 1 );
        $db_q = array( 0 => ("SELECT email_usr FROM auth_user WHERE "
                             ."username='%s'"));

        $auth->set_uname("this is the username");
        $sponsoring_text = "this is the sponsoring text";
        $budget = "this is the budget";
        
        $valid_day = 3;     $begin_day = 4;     $finish_day = 5;
        $valid_month = 4;   $begin_month = 5;   $finish_month = 6;
        $valid_year = 2001; $begin_year = 2002; $finish_year = 2003;

        $db_config->add_query( sprintf( $db_q[0], $auth->auth["uname"]), 0 );
        $db_config->add_record( false, 0 );

        capture_reset_and_start();
        sponsoring_preview( "dasdsa" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 974 + strlen( timestr( time() ) ) );
        $ps=array( 0=>("<font color=\"#000000\"><b><center><b>PREVIEW<\/b>"
                       ."<\/center><\/b><\/font>"),
                   1=>("<font color=\"#000000\"><b>Sponsor Involvement<\/b>"
                       ."<\/font>"),
                   2=>("<b>by this is the username<\/b>"),
                   3=>("<p><b>Max. sum of money:<\/b> this is the "
                       ."budget euros"),
                   4=>"<br><b>Status:<\/b> Proposed",
                   5=>("<br><b>Validity<\/b> "
                       .timestr_middle(mktimestamp(
                           date_to_timestamp($valid_day,$valid_month,
                           $valid_year)))),
                   6=>("<br><b>Begin wished:<\/b> "
                       .timestr_middle(mktimestamp(
                           date_to_timestamp($begin_day,$begin_month,
                           $begin_year)))),
                   7=>("<br><b>Finish before:<\/b> "
                       .timestr_middle(mktimestamp(
                           date_to_timestamp($finish_day,$finish_month,
                           $finish_year)))),
                   8=>("<p><b>Comments to the involvement:<\/b> this is "
                       ."the sponsoring text"));
        $this->_testFor_patterns( $text, $ps, 9 );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testSponsoring_insert() {
        global $db, $auth;
        
        $auth->set_uname( "this is the username" );
        $auth->set_perm( "this is the permission" );

        $db_config = new mock_db_configure( 7 );
        $db_q = array( 0 => ("SELECT COUNT(*) FROM sponsoring WHERE "
                             ."proid='%s'"),
                       1 => ("INSERT sponsoring SET proid='%s',sponsor='%s'"
                             .",sponsoring_text='%s',budget='%s', status="
                             ."'%s', valid='%s',begin='%s',finish='%s'"),
                       2 => ("UPDATE configure SET sponsor='%s' WHERE "
                             ."proid='%s'"),
                       3 => ("SELECT * FROM description WHERE proid='%s' "
                             ."AND description_user='%s'"),
                       4 => ("SELECT * FROM sponsoring,auth_user WHERE "
                             ."proid='%s' AND sponsor=username ORDER BY "
                             ."sponsoring.creation ASC"));

        $args = $this->_generate_records( array( "proid", "user", "s_text",
                                                "budget", "v_day", "v_month",
                                                "v_year", "b_day", "b_month",
                                                "b_year", "f_day", "f_month",
                                                "f_year"), 2 );
        $rows = $this->_generate_records( array( "COUNT(*)" ), 2 );

        // first call
        $db_config->add_query( sprintf( $db_q[0], $args[0]["proid"] ), 0 );
        $v = date_to_timestamp( $args[0]["v_day"], $args[0]["v_month"], 
                                $args[0]["v_year"] );
        $b = date_to_timestamp( $args[0]["b_day"], $args[0]["b_month"],
                                $args[0]["b_year"]);
        $f = date_to_timestamp( $args[0]["f_day"], $args[0]["f_month"], 
                                $args[0]["f_year"]);
        $db_config->add_query( sprintf( $db_q[1], $args[0]["proid"],
               $args[0]["user"], $args[0]["s_text"], $args[0]["budget"], "P",
               $v, $b, $f ), 0 );
        // query for the show_sponsorings call
        $db_config->add_query( sprintf( $db_q[4], $args[0]["proid"]), 0 );

        // instance for the monitor_mail call
        $db_config->ignore_errors( MKDB_ALL_ERRORS, 1 );
        // instance created by the is_project_initiator
        $db_config->add_query( sprintf( $db_q[3], $args[0]["proid"],
                                        $auth->auth["uname"]), 2 );

        $rows[0]["COUNT(*)"] = 1; // status is 'P' (proposed)
        $db_config->add_record( $rows[0], 0 ); // sponsoring_insert
        $db_config->add_record( false, 0 ); // show_sponsorings
        $db_config->add_num_row( 1, 0 ); // show_sponsorings
        $db_config->add_num_row( 1, 2 ); // 2nd call to is_project_initiator

        // second call
        $db_config->add_query( sprintf( $db_q[0], $args[1]["proid"] ), 3 );
        $v = date_to_timestamp( $args[1]["v_day"], $args[1]["v_month"], 
                                $args[1]["v_year"] );
        $b = date_to_timestamp( $args[1]["b_day"], $args[1]["b_month"],
                                $args[1]["b_year"]);
        $f = date_to_timestamp( $args[1]["f_day"], $args[1]["f_month"], 
                                $args[1]["f_year"]);
        $db_config->add_query( sprintf( $db_q[1], $args[1]["proid"],
               $args[1]["user"], $args[1]["s_text"], $args[1]["budget"], "A",
               $v, $b, $f ), 3 );
        // this is the update query
        $db_config->add_query( sprintf( $db_q[2], $auth->auth["uname"],
                                        $args[1]["proid"]), 3 );
        // query for the show_sponsorings call
        $db_config->add_query( sprintf( $db_q[4], $args[1]["proid"]), 3 );
        // 1st call to is_project_initiator
        $db_config->add_query( sprintf( $db_q[3], $args[1]["proid"],
                                        $auth->auth["uname"]), 4 );
        // instance for the monitor_mail call
        $db_config->ignore_errors( MKDB_ALL_ERRORS, 5 );
        // instance created by the second call to is_project_initiator
        $db_config->add_query( sprintf( $db_q[3], $args[1]["proid"],
                                        $auth->auth["uname"]), 6 );

        $rows[1]["COUNT(*)"] = 0; // status is 'A'
        $db_config->add_record( $rows[1], 3 ); // sponsoring_insert
        $db_config->add_record( false, 3 ); // show_sponsorings
        $db_config->add_num_row( 1, 3 ); // show_sponsorings
        $db_config->add_num_row( 0, 4 ); // 1st call to is_project_initiator
        $db_config->add_num_row( 0, 6 ); // 2nd call to is_project_initiator

        // first call, no output
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        sponsoring_insert( $args[0]["proid"], $args[0]["user"],
                           $args[0]["s_text"], $args[0]["budget"], 
                           $args[0]["v_day"], $args[0]["v_month"],
                           $args[0]["v_year"],
                           $args[0]["b_day"], $args[0]["b_month"],
                           $args[0]["b_year"],
                           $args[0]["f_day"], $args[0]["f_month"],
                           $args[0]["f_year"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 434 );
        $this->_testFor_pattern( $text, ("<a href=\"personal[.]php3[?]"
                                         ."username=this[+]is[+]the[+]"
                                         ."username\">Personal Page<\/a>"));

        // second call, status = A and is not project initiator
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        sponsoring_insert( $args[1]["proid"], $args[1]["user"],
                           $args[1]["s_text"], $args[1]["budget"], 
                           $args[1]["v_day"], $args[1]["v_month"],
                           $args[1]["v_year"],
                           $args[1]["b_day"], $args[1]["b_month"],
                           $args[1]["b_year"],
                           $args[1]["f_day"], $args[1]["f_month"],
                           $args[1]["f_year"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 133 );
        $this->_testFor_pattern( $text, ("<p><b>Congratulations<\/b>. You "
                                         ."are the first sponsor. You can "
                                         ."<a href=\"configure_edit.php3[?]"
                                         ."proid=proid_1\">configure this "
                                         ."project<\/a>"));
        
        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>