<?php
// TestMonitorlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestMonitorlib.php,v 1.2 2002/02/01 08:40:52 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $sess = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");
    global $t;

    include_once( "box.inc" );
    $bx = new box;
}

include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'monitorlib.inc' );

class UnitTestMonitorlib
extends UnitTest
{
    function UnitTestMonitorlib( $name ) {
        $this->UnitTest( $name );
    }

    function setup() {
        // Called before each test method.
        // if using the capturing routines then ensure that it's reset,
        // it uses global variables
        capture_reset_text();
    }

    function testSelect_importence() {

        $ret = select_importance( "low" );
        $this->_testFor_string_length( $ret, 126 );
        $pats=array(0=>"option selected value=\"low\"",
                    1=>"option value=\"medium\"",
                    2=>"option value=\"high\"" );
        $this->_testFor_patterns( $ret, $pats, 3 );

        $ret = select_importance( "medium" );
        $this->_testFor_string_length( $ret, 126 );
        $pats[0] = "option value=\"low\"";
        $pats[1] = "option selected value=\"medium\"";
        $this->_testFor_patterns( $ret, $pats, 3 );

        $ret = select_importance( "high" );
        $this->_testFor_string_length( $ret, 126 );
        $pats[1] = "option value=\"medium\"";
        $pats[2] = "option selected value=\"high\"";
        $this->_testFor_patterns( $ret, $pats, 3 );

        $ret = select_importance( "fubar" );
        $this->_testFor_string_length( $ret, 117 );
        $pats[2] = "option value=\"high\"";
        $this->_testFor_patterns( $ret, $pats, 3 );
    }

    function testMonitor_mail() {
        // ASSUME: this does not test the mail function, this is assumed to 
        // ASSUME: work
        $db_config = new mock_db_configure( 3 );
        $db_q = array( 0 => ("SELECT email_usr FROM auth_user,monitor WHERE "
                             ."monitor.username=auth_user.username AND "
                             ."proid='%s' AND importance='high'"),
                       2 => ("SELECT email_usr FROM auth_user,monitor WHERE "
                             ."monitor.username=auth_user.username AND "
                             ."proid='%s' "),
                       1 => ("SELECT email_usr FROM auth_user,monitor WHERE "
                             ."monitor.username=auth_user.username AND "
                             ."proid='%s' AND (importance='middle' OR "
                             ."importance='high')"));

        $row = array( 0 => $this->_generate_array( array( "proid", "subject",
                                                          "message" ), 0),
                      1 => $this->_generate_array( array( "proid", "subject",
                                                          "message" ), 1),
                      2 => $this->_generate_array( array( "proid", "subject",
                                                          "message" ), 2));
        $row[0]["type"] = "milestone_delivery"; // high propriety
        $row[1]["type"] = "configure"; // middle 
        $row[2]["type"] = "monitor"; // low

        $db_config->add_record( false, 0 );
        $db_config->add_record( false, 1 );
        $db_config->add_record( false, 2 );

        $db_config->add_query( sprintf( $db_q[0], $row[0]["proid"] ), 0 );
        $db_config->add_query( sprintf( $db_q[1], $row[1]["proid"] ), 1 );
        $db_config->add_query( sprintf( $db_q[2], $row[2]["proid"] ), 2 );

        // 
        // first call, high propriety
        //
        capture_reset_and_start();
        monitor_mail( $row[0]["proid"], $row[0]["type"], $row[0]["subject"],
                      $row[0]["message"]);
        $text = capture_stop_and_get();
        $this->_testFor_length( 0 );

        // 
        // second call, middle propriety
        //
        capture_reset_and_start();
        monitor_mail( $row[1]["proid"], $row[1]["type"], $row[1]["subject"],
                      $row[1]["message"]);
        $text = capture_stop_and_get();
        $this->_testFor_length( 0 );
        
        // 
        // third call, middle propriety
        //
        capture_reset_and_start();
        monitor_mail( $row[2]["proid"], $row[2]["type"], $row[2]["subject"],
                      $row[2]["message"]);
        $text = capture_stop_and_get();
        $this->_testFor_length( 0 );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testMonitor_show() {
        global $db, $t;
        
        $db_config = new mock_db_configure( 2 );
        $proid = array( 0 => "proid_0",
                        1 => "proid_1" );

        $db_q = array( 0 => ("SELECT * FROM monitor,auth_user WHERE proid="
                             ."'%s' AND monitor.username=auth_user.username "
                             ."ORDER BY creation DESC"));
        
        $db_config->add_query( sprintf( $db_q[0], $proid[0] ), 0 );
        $db_config->add_query( sprintf( $db_q[0], $proid[1] ), 1 );
        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 4, 1 );

        $row = array( 0 => $this->_generate_array( array( "username", "perms",
                                                          "importance", 
                                                          "creation" ), 0 ),
                      1 => $this->_generate_array( array( "username", "perms",
                                                          "importance", 
                                                          "creation" ), 1 ),
                      2 => $this->_generate_array( array( "username", "perms",
                                                          "importance", 
                                                          "creation" ), 2 ),
                      3 => $this->_generate_array( array( "username", "perms",
                                                          "importance", 
                                                          "creation" ), 3 ));
        $db_config->add_record( $row[0], 1 );
        $db_config->add_record( $row[1], 1 );
        $db_config->add_record( $row[2], 1 );
        $db_config->add_record( $row[3], 1 );

        // 
        // first call, no records
        //
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        monitor_show( $proid[0] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 41 );
        $this->_testFor_pattern( $text, ("<p>Nobody is monitoring this "
                                         ."project[.]<p>\n"));
        // 
        // second call, 4 records
        //
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        monitor_show( $proid[1] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 3880 );
        
        $color = array( 0 => "gold", 1 => "#FFFFFF" );

        for ( $idx = 0; $idx < 4; $idx++ ) {
            $this->_testFor_pattern( $text, ("<td align=\"center\" width="
                                             ."\"\" bgcolor=\""
                                             .$color[$idx%2]."\"><b>"
                                             .($idx+1)."<\/b><\/td>"));

            $this->_testFor_pattern( $text, ("<td align=\"center\" width="
                                             ."\"\" bgcolor=\"".$color[$idx%2]
                                             ."\"><b><b>by "
                                             . $row[$idx]["username"]
                                             ."<\/b><\/b><\/td>"));

            $this->_testFor_pattern( $text, ("<td align=\"center\" width="
                                             ."\"\" bgcolor=\"".$color[$idx%2]
                                             ."\"><b>"
                                             .$row[$idx]["perms"]
                                             ."<\/b><\/td>"));

            $this->_testFor_pattern( $text, ("<td align=\"center\" width="
                                             ."\"\" bgcolor=\"".$color[$idx%2]
                                             ."\"><b>"
                                             .$row[$idx]["importance"]
                                             ."<\/b><\/td>"));
        }

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testMonitor_preview() {
        global $importance, $auth;
        
        $db_config = new mock_db_configure( 1 );
        $row = array( 0 => $this->_generate_array(array("proid","uname"),0));
        
        $db_q = array( 0 => ("SELECT email_usr FROM auth_user WHERE "
                             ."username='%s'"));
        
        $db_config->add_query( sprintf( $db_q[0], $row[0]["uname"] ), 0 );
        
        //
        // first call
        //
        $importance = "middle";
        $auth->set_uname( $row[0]["uname"] );
        capture_reset_and_start();
        monitor_preview( $row[0]["proid"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 803 + strlen( timestr( time() )));

        $this->_testFor_pattern( $text, "<b><b>by uname_0<\/b>" );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }
    
    function testMonitor_form() {
        global $importance;
        
        //
        // first call
        //
        capture_reset_and_start();
        $importance = "low";
        monitor_form( "proid_0" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 1477 );

        $this->_testFor_pattern( $text, ("<select name=\"importance\">\n"
                                         ."<option selected value=\"low\">"
                                         ."low\n<option value=\"medium\">"
                                         ."medium\n<option value=\"high\">"
                                         ."high\n<\/select>"));
        $this->_testFor_pattern( $text, ("<form action=\"[?]proid=proid_0\" "
                                         ."method=\"POST\">"));

        //
        // second call
        //
        capture_reset_and_start();
        $importance = "medium";
        monitor_form( "proid_1" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 1477 );

        $this->_testFor_pattern( $text, ("<select name=\"importance\">\n"
                                         ."<option value=\"low\">"
                                         ."low\n<option selected "
                                         ."value=\"medium\">"
                                         ."medium\n<option value=\"high\">"
                                         ."high\n<\/select>"));
        $this->_testFor_pattern( $text, ("<form action=\"[?]proid=proid_1\" "
                                         ."method=\"POST\">"));
        //
        // third call
        //
        capture_reset_and_start();
        $importance = "fubar";
        monitor_form( "proid_2" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 1468 );

        $this->_testFor_pattern( $text, ("<select name=\"importance\">\n"
                                         ."<option value=\"low\">"
                                         ."low\n<option value=\"medium\">"
                                         ."medium\n<option value=\"high\">"
                                         ."high\n<\/select>"));
        $this->_testFor_pattern( $text, ("<form action=\"[?]proid=proid_2\" "
                                         ."method=\"POST\">"));
    }
}

define_test_suite( __FILE__ );

?>
