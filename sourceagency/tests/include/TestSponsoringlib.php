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
# $Id: TestSponsoringlib.php,v 1.14 2002/05/28 08:58:28 riessen Exp $
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
    
    function tearDown() {
        // ensure that the next test doesn't have a predefined global
        // database object
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
    }

    function testSponsoring_form() {
        global $sponsoring_text, $budget, $valid_day, $valid_month, 
            $valid_year, $begin_day, $begin_month, $begin_year, 
            $finish_day, $finish_month, $finish_year, $sess, $bx, $t;
        
        $sponsoring_text = "this is the sponsoring text";
        $budget = "this is the budget";
        
        $valid_day = 3;     $begin_day = 4;     $finish_day = 5;
        $valid_month = 4;   $begin_month = 5;   $finish_month = 6;
        $valid_year = 2001; $begin_year = 2002; $finish_year = 2003;

        $proid = 'proid';
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        sponsoring_form( $proid );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 8389 + strlen( $sess->self_url() ),
                                         "test 1" );

        $this->_checkFor_a_box( $text, 'Sponsoring involvement' );

        $this->_checkFor_a_form($text,'PHP_SELF',
                                              array('proid'=>$proid),'POST');
        $this->_testFor_box_columns_begin( $text, 2 );
        $this->_testFor_box_columns_end( $text );

        $this->_checkFor_column_titles( $text, 
                    array("Valid until","Begin","Finish","Budget (in euro)",
                          "Sponsoring Comment"));
        foreach ( array(select_date('valid',$valid_day,$valid_month,
                                    $valid_year),
                        select_date('begin',$begin_day,
                                    $begin_month,$begin_year),
                        select_date('finish',$finish_day,
                                    $finish_month,$finish_year),
                        html_input_text('budget',12,12,$budget),
                        html_textarea('sponsoring_text',40,7,'virtual',255,
                                      $sponsoring_text),
                        html_form_submit($t->translate('Preview'),'preview')
                        .html_form_submit($t->translate('Submit'),'submit')) 
                  as $val ) {
          $this->_testFor_box_column($text, 'left','70%','', $val);
        }
    }

    function testShow_sponsorings() {
        global $db, $auth, $bx, $t;

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
        // its database to ignore all errors.
        $db_config->ignore_all_errors( 2 );

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
        $db_config->ignore_all_errors( 5 );

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
        $db_config->ignore_all_errors( 8 );
        
        // first call, no records
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        show_sponsorings( $db_d[0]["proid"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 84, "test 1" );
        $this->_testFor_pattern( $text, ("<p>There have not been posted any"
                                         ." sponsoring involvement wishes "
                                         ."to this project.<p>"), "test 1");

        // second call, one record but don't do is_accepted_sponsor call
        // i.e. status is not 'P'
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        show_sponsorings( $db_d[1]["proid"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 1090, "test 2" );
        
        $this->_checkFor_a_box( $text, 'Sponsor Involvement' );
        $this->_testFor_lib_nick( $text, $rows[0]['username']);

        $v=array( 'Status'=>show_status($rows[0]['status']),
                  'Validity'=>timestr_middle(mktimestamp($rows[0]['valid'])),
                  'Finish before'=>
                  timestr_middle(mktimestamp($rows[0]['finish'])),
                  'Begin wished'=>
                  timestr_middle(mktimestamp($rows[0]['begin'])),
                  'Max. sum of money'=>$rows[0]['budget']." euros",
                  'Comments to the involvement'=>$rows[0]['sponsoring_text']);

        while ( list( $key, $val ) = each( $v ) ) {
            $this->_testFor_pattern( $text, 
                               $this->_to_regexp('<b>'.$t->translate($key)
                                                 .':</b> '.$val));
        }

        $this->_testFor_lib_comment_it( $text, $db_d[1]["proid"], 'Sponsoring',
                    $rows[0]['spoid'],'0','Comment on Sponsor Involvement #'
                    .$rows[0]['spoid'],$t->translate('Comment it!'));

        // third call, is_accepted_sponsor returns true after being called
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        show_sponsorings( $db_d[2]["proid"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 1426, "test 3" );

        $this->_checkFor_a_box( $text, 'Sponsor Involvement' );
        $this->_testFor_lib_nick( $text, $rows[1]['username']);

        $v=array( 'Status'=>show_status($rows[1]['status']),
                  'Validity'=>timestr_middle(mktimestamp($rows[1]['valid'])),
                  'Finish before'=>
                  timestr_middle(mktimestamp($rows[1]['finish'])),
                  'Begin wished'=>
                  timestr_middle(mktimestamp($rows[1]['begin'])),
                  'Max. sum of money'=>$rows[1]['budget']." euros",
                  'Comments to the involvement'=>$rows[1]['sponsoring_text']);
        while( list($key, $val) = each( $v ) ) {
            $this->_testFor_pattern( $text, 
                            $this->_to_regexp('<b>'.$t->translate($key)
                                              .':</b> '.$val));
        }
        
        $ps=array(0=>("<b><a href=\"sponsoring_accepted.php3[?]proid="
                      ."proid_2&sponsor=username_1\" class=\"\">"
                      .$t->translate("Accept this sponsor involvement")
                      ."<\/a>"));
        $this->_testFor_patterns( $text, $ps, 1, "test 3" );

        $this->_testFor_lib_comment_it( $text, $db_d[2]["proid"], 'Sponsoring',
                    $rows[1]['spoid'],'0','Comment on Sponsor Involvement #'
                    .$rows[1]['spoid'],$t->translate('Comment it!'));

        // fourth call, is_accepted_sponsor is called and returns false
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        show_sponsorings( $db_d[3]["proid"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 1125, "test 4" );

        $this->_checkFor_a_box( $text, 'Sponsor Involvement' );
        $this->_testFor_lib_nick( $text, $rows[2]['username']);

        $v=array( 'Status'=>show_status($rows[2]['status']),
                  'Validity'=>timestr_middle(mktimestamp($rows[2]['valid'])),
                  'Finish before'=>
                  timestr_middle(mktimestamp($rows[2]['finish'])),
                  'Begin wished'=>
                  timestr_middle(mktimestamp($rows[2]['begin'])),
                  'Max. sum of money'=>$rows[2]['budget']." euros",
                  'Comments to the involvement'=>$rows[2]['sponsoring_text']);

        while ( list( $key, $val ) = each( $v ) ) {
            $this->_testFor_pattern( $text, 
                            $this->_to_regexp('<b>'.$t->translate($key)
                                              .':</b> '.$val));
        }
        
        $this->_testFor_lib_comment_it( $text, $db_d[3]["proid"], 'Sponsoring',
                    $rows[2]['spoid'],'0','Comment on Sponsor Involvement #'
                    .$rows[2]['spoid'],$t->translate('Comment it!'));

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testSponsoring_preview() {
        global $auth, $sponsoring_text, $budget, $valid_day, $valid_month, 
            $valid_year, $begin_day, $begin_month, $begin_year, 
            $finish_day, $finish_month, $finish_year, $bx, $t;

        $auth->set_uname("this is the username");
        $sponsoring_text = "this is the sponsoring text";
        $budget = "this is the budget";
        
        $valid_day = 3;     $begin_day = 4;     $finish_day = 5;
        $valid_month = 4;   $begin_month = 5;   $finish_month = 6;
        $valid_year = 2001; $begin_year = 2002; $finish_year = 2003;

        $bx = $this->_create_default_box();
        capture_reset_and_start();
        sponsoring_preview( "dasdsa" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 1196 + strlen( timestr( time() ) ),
                                         "test 1" );

        $this->_testFor_box_title( $text, '<center><b>'
                                    .$t->translate('PREVIEW').'</b></center>');
        $this->_checkFor_a_box( $text, 'Sponsor Involvement' );
        $this->_testFor_lib_nick( $text, $auth->auth['uname']);

        $v=array( 'Status'=>'Proposed',
                  'Validity'=>timestr_middle(
                      mktimestamp(date_to_timestamp($valid_day,$valid_month,
                      $valid_year))),
                  'Finish before'=>timestr_middle(
                      mktimestamp(date_to_timestamp($finish_day,$finish_month,
                      $finish_year))),
                  'Begin wished'=>timestr_middle(
                      mktimestamp(date_to_timestamp($begin_day,$begin_month,
                      $begin_year))),
                  'Max. sum of money'=>"$budget euros",
                  'Comments to the involvement'=>"$sponsoring_text");

        while ( list( $key, $val ) = each( $v ) ) {
            $this->_testFor_pattern( $text, 
                          $this->_to_regexp('<b>'.$t->translate($key)
                                            .':</b> '.$val));
        }
    }

    function testSponsoring_insert() {
        global $db, $auth;
        
        $auth->set_uname( "this is the username" );
        $auth->set_perm( "this is the permission" );

        $db_config = new mock_db_configure( 14 );
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
                             ."sponsoring.creation ASC"),
                       5 => ("SELECT * FROM sponsoring WHERE proid='%s' AND "
                             . "sponsor='%s'"),
                       6 => ("UPDATE sponsoring SET sponsoring_text='%s', "
                             ."budget='%s', status='%s', valid='%s',"
                             ."begin='%s', finish='%s' "
                             ."WHERE proid='%s' AND sponsor='%s'"));

        $args = $this->_generate_records( array( "proid", "user", "s_text",
                                                "budget", "v_day", "v_month",
                                                "v_year", "b_day", "b_month",
                                                "b_year", "f_day", "f_month",
                                                "f_year"), 4 );
        $rows = $this->_generate_records( array( "COUNT(*)" ), 4 );

        // first call
        $db_config->add_query( sprintf( $db_q[0], $args[0]["proid"] ), 0 );
        $v = date_to_timestamp( $args[0]["v_day"], $args[0]["v_month"], 
                                $args[0]["v_year"] );
        $b = date_to_timestamp( $args[0]["b_day"], $args[0]["b_month"],
                                $args[0]["b_year"]);
        $f = date_to_timestamp( $args[0]["f_day"], $args[0]["f_month"], 
                                $args[0]["f_year"]);
        // query for checking the number of sponsoring the user has made
        $db_config->add_query( sprintf( $db_q[5], $args[0]["proid"],
                                 $args[0]["user"]), 0 );

        // insert query
        $db_config->add_query( sprintf( $db_q[1], $args[0]["proid"],
               $args[0]["user"], $args[0]["s_text"], $args[0]["budget"], "P",
               $v, $b, $f ), 0 );
        // query for the show_sponsorings call
        $db_config->add_query( sprintf( $db_q[4], $args[0]["proid"]), 0 );

        // instance for the monitor_mail call
        $db_config->ignore_all_errors( 1 );
        // instance created by the is_project_initiator
        $db_config->add_query( sprintf( $db_q[3], $args[0]["proid"],
                                        $auth->auth["uname"]), 2 );

        $rows[0]["COUNT(*)"] = 1; // status is 'P' (proposed)
        $db_config->add_record( $rows[0], 0 ); // sponsoring_insert
        $db_config->add_record( false, 0 ); // show_sponsorings
        // two if statements
        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 0, 0 );
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
        // query for checking the number of sponsoring the user has made
        $db_config->add_query( sprintf( $db_q[5], $args[1]["proid"],
                                 $args[1]["user"]), 3 );

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
        $db_config->ignore_all_errors( 5 );
        // instance created by the second call to is_project_initiator
        $db_config->add_query( sprintf( $db_q[3], $args[1]["proid"],
                                        $auth->auth["uname"]), 6 );

        $rows[1]["COUNT(*)"] = 0; // status is 'A'
        $db_config->add_record( $rows[1], 3 ); // sponsoring_insert
        $db_config->add_record( false, 3 ); // show_sponsorings
        $db_config->add_num_row( 0, 3 ); // if ( $db->num_rows() == 1 ) {
        $db_config->add_num_row( 0, 3 ); // if ( $db->num_rows() == 0 ) {
        $db_config->add_num_row( 1, 3 ); // show_sponsorings
        $db_config->add_num_row( 0, 4 ); // 1st call to is_project_initiator
        $db_config->add_num_row( 0, 6 ); // 2nd call to is_project_initiator

        // third call, check that an update query is executed if the
        // user already has a sponsorship.
        $db_config->add_query( sprintf( $db_q[0], $args[2]["proid"] ), 7 );
        $v = date_to_timestamp( $args[2]["v_day"], $args[2]["v_month"], 
                                $args[2]["v_year"] );
        $b = date_to_timestamp( $args[2]["b_day"], $args[2]["b_month"],
                                $args[2]["b_year"]);
        $f = date_to_timestamp( $args[2]["f_day"], $args[2]["f_month"], 
                                $args[2]["f_year"]);
        // query for checking the number of sponsoring the user has made
        $db_config->add_query( sprintf( $db_q[5], $args[2]["proid"],
                                 $args[2]["user"]), 7 );

        // update query because user has already contributed
        $args[2]["budget"] = 1;
        $db_config->add_query( sprintf( $db_q[6], $args[2]["s_text"], 
               $args[2]["budget"], "P", $v, $b, $f, $args[2]["proid"], 
               $args[2]["user"] ), 7 );

        // query for the show_sponsorings call
        $db_config->add_query( sprintf( $db_q[4], $args[2]["proid"]), 7 );

        // instance for the monitor_mail call
        $db_config->ignore_all_errors( 8 );
        $db_config->ignore_all_errors( 9 );
        // instance created by the is_project_initiator
        $db_config->add_query( sprintf( $db_q[3], $args[2]["proid"],
                                        $auth->auth["uname"]), 10 );

        $rows[2]["COUNT(*)"] = 1; // status is 'P' (proposed)
        $rows[2]["budget"] = 0; // old_budget should be less than new budget
        $db_config->add_record( $rows[2], 7 ); // sponsoring_insert
        $db_config->add_record( $rows[2], 7 ); // sponsoring_insert
        $db_config->add_record( false, 7 ); // show_sponsorings
        $db_config->add_num_row( 1, 7 ); // user has already contributed
        $db_config->add_num_row( 1, 7 ); // show_sponsorings
        $db_config->add_num_row( 0, 10 ); // call to is_project_initiator
        
        // fourth call, this time the user has more than one sponsorship
        $db_config->add_query( sprintf( $db_q[0], $args[3]["proid"] ), 11 );
        $v = date_to_timestamp( $args[3]["v_day"], $args[3]["v_month"], 
                                $args[3]["v_year"] );
        $b = date_to_timestamp( $args[3]["b_day"], $args[3]["b_month"],
                                $args[3]["b_year"]);
        $f = date_to_timestamp( $args[3]["f_day"], $args[3]["f_month"], 
                                $args[3]["f_year"]);
        // query for checking the number of sponsoring the user has made
        $db_config->add_query( sprintf( $db_q[5], $args[3]["proid"],
                                 $args[3]["user"]), 11 );

        // query for the show_sponsorings call
        $db_config->add_query( sprintf( $db_q[4], $args[3]["proid"]), 11 );

        // instance for the monitor_mail call
        $db_config->ignore_all_errors( 11 );
        $db_config->ignore_all_errors( 12 );
        // instance created by the is_project_initiator
        $db_config->add_query( sprintf( $db_q[3], $args[3]["proid"],
                                        $auth->auth["uname"]), 13 );

        $rows[3]["COUNT(*)"] = 1; // status is 'P' (proposed)
        $db_config->add_record( $rows[3], 11 ); // sponsoring_insert
        $db_config->add_record( false, 11 ); // show_sponsorings
        $db_config->add_num_row( 2, 11 ); // user has already contributed
        $db_config->add_num_row( 2, 11 ); // user has already contributed
        $db_config->add_num_row( 1, 11 ); // show_sponsorings
        $db_config->add_num_row( 0, 13 ); // call to is_project_initiator

        // ********************************************************************
        // first call
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
        $this->_testFor_captured_length( 442, "test 1" );
        $this->_testFor_pattern( $text, ("<a href=\"personal[.]php3[?]"
                                         ."username=this[+]is[+]the[+]"
                                         ."username\" class=\"\">Personal "
                                         ."Page<\/a>"));

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
        $this->_testFor_captured_length( 141, "test 2" );
        $this->_testFor_pattern( $text, ("<p><b>Congratulations<\/b>. You "
                                         ."are the first sponsor. You can "
                                         ."<a href=\"configure_edit.php3[?]"
                                         ."proid=proid_1\" class=\"\">"
                                         ."configure this "
                                         ."project<\/a>"));
        
        
        // third call: user has already contributed and wishes to make
        // a change to that contribution
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        sponsoring_insert( $args[2]["proid"], $args[2]["user"],
                           $args[2]["s_text"], $args[2]["budget"], 
                           $args[2]["v_day"], $args[2]["v_month"],
                           $args[2]["v_year"],
                           $args[2]["b_day"], $args[2]["b_month"],
                           $args[2]["b_year"],
                           $args[2]["f_day"], $args[2]["f_month"],
                           $args[2]["f_year"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0, "test 3" );

        // fourth call: user has made too many contributions
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        sponsoring_insert( $args[3]["proid"], $args[3]["user"],
                           $args[3]["s_text"], $args[3]["budget"], 
                           $args[3]["v_day"], $args[3]["v_month"],
                           $args[3]["v_year"],
                           $args[3]["b_day"], $args[3]["b_month"],
                           $args[3]["b_year"],
                           $args[3]["f_day"], $args[3]["f_month"],
                           $args[3]["f_year"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 137, "test 4" );
        $this->_testFor_pattern( $text, ("<p><b>Database Failure:<\/b> it "
                                         ."seems you have more than one "
                                         ."sponsorship! Please advice the "
                                         ."administrator and have the "
                                         ."database corrected."));
        
        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
