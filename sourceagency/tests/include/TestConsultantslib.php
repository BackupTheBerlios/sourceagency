<?php
// TestConsultantslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestConsultantslib.php,v 1.4 2002/05/16 15:04:16 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for lib_nick(...)
    include_once( 'lib.inc' );

    // required for the html functions
    include_once( 'html.inc');

    // required for the $sess global variable
    include_once( "session.inc" );
    $sess = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");

    // required for the $bx global variable
    include_once( "box.inc" );
    $bx = new box;
}

include_once( 'consultantslib.inc' );

class UnitTestConsultantslib
extends UnitTest
{
    function UnitTestConsultantslib( $name ) {
        $this->UnitTest( $name );
    }

    function setup() {
    }

    function tearDown() {
        // remove the globally defined database object, it can affect 
        // other tests
        unset( $GLOBALS['db'] );
    }

    function testShow_consultants() {
        global $db, $t, $bx;

        $db_config = new mock_db_configure( 2 );

        $db_q = array( 0 => ("SELECT * FROM consultants,auth_user WHERE "
                             ."proid='%s' AND username=consultant ORDER "
                             ."BY creation") );

        $dat = $this->_generate_records( array("proid"), 2 );
        $rows = $this->_generate_records( array( "username", "status", 
                                                 "creation"), 3 );

        $db_config->add_query( sprintf( $db_q[0], $dat[0]["proid"] ), 0 );
        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"] ), 1 );
        
        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 3, 1 );

        $db_config->add_record( $rows[0], 1 );
        $db_config->add_record( $rows[1], 1 );
        $db_config->add_record( $rows[2], 1 );


        // first test: no data to list, print error message
        $db = new DB_SourceAgency;
        $bx = new box;
        capture_reset_and_start();
        show_consultants( $dat[0]["proid"] );
        $text = capture_stop_and_get();
        $this->_testFor_string_length( $text, 64, "test 1" );
        $this->_testFor_pattern( $text, "No developers have offered "
                                 ."themselves as consultants yet" );

        // second test: three pieces of data
        $db = new DB_SourceAgency;
        $bx = new box;
        capture_reset_and_start();
        show_consultants( $dat[1]["proid"] );
        $text = capture_stop_and_get();

        $p = '<td align="" width="" bgcolor="%s">[ ]+<b>%s<\/b>[ ]+<\/td>';
        $p_white = sprintf( $p, "#FFFFFF", "%s" );
        $p_gold = sprintf( $p, "gold", "%s" );
        $p_u = "<b>by %s<\/b>"; // pattern for user name

        $ps=array( 0=>"<font color=\"#000000\"><b>Consultants<\/b><\/font>",
                   1=>sprintf( $p_white, $t->translate("Number") ),
                   2=>sprintf( $p_white, $t->translate("Username") ),
                   3=>sprintf( $p_white, $t->translate("Status") ),
                   4=>sprintf( $p_white, $t->translate("Creation") ),
                   5=>sprintf( $p_white, "1" ),
                   6=>sprintf( $p_gold,  "2" ),
                   7=>sprintf( $p_white, "3" ),
                   8=>sprintf( $p_white, sprintf( $p_u, $rows[0]["username"])),
                   9=>sprintf( $p_gold, sprintf( $p_u,$rows[1]["username"] )),
                   10=>sprintf( $p_white, sprintf( $p_u,$rows[2]["username"])),
                   11=>sprintf( $p_white, "Proposed" ),
                   12=>sprintf( $p_gold, "Proposed" ));

        $this->_testFor_patterns( $text, $ps, 13, "test 2" );
        $this->_testFor_captured_length( 3501, "test 2");

        $this->_check_db( $db_config );
    }

    function testConsultants_form() {
        global $bx, $auth, $sess, $t;

        $uname = "this is the username";
        $auth->set_uname($uname);
        $auth->set_perm("this is the permission");

        capture_reset_and_start();
        consultants_form( "proid_0" );
        $text = capture_stop_and_get();
        $p = '<td align="%s" width="%s" bgcolor="%s">[ ]+%s[ ]+<\/td>';

        $ps=array(0=>"<b>Offer yourself as project consultant<\/b>",
                  1=>('<form action="'
                      . ereg_replace( "/", "\/", $sess->self_url() )
                      .'[?]proid=proid_0" method="POST">'),
                  2=>sprintf( $p,"right","45%","#FFFFFF",'<b>'
                              .$t->translate("Your username")."<\/b>:"),
                  3=>sprintf( $p,"left","55%","#FFFFFF",$uname),
                  4=>sprintf( $p,"right","45%","#FFFFFF","<b>"
                              .$t->translate("Check if you want to be a "
                                             ."consultant")."<\/b>:"),
                  5=>'<input type="checkbox" name="check" value="check">',
                  6=>('<input type="submit" value="'.$t->translate("Submit")
                      .'" name="submit">'),
                  7=>"<\/form>" );
             
        $this->_testFor_patterns( $text, $ps, 8 );
    }

    function testConsultants_wanted() {
        global $db, $t;
        
        $db_config = new mock_db_configure( 2 );

        $db_q=array(0=>"SELECT consultants FROM configure WHERE proid='%s'");
        
        $dat = $this->_generate_records( array("proid"), 2 );
        $rows= $this->_generate_records( array("consultants" ), 2 );
        
        $rows[0]["consultants"] = "Yes";
        $rows[1]["consultants"] = "No";
        
        for ( $idx = 0; $idx < 2; $idx++ ) {
            $db_config->add_query(sprintf($db_q[0],$dat[$idx]["proid"]),$idx);
            $db_config->add_record($rows[$idx],$idx);
        }

        // first test: project is configured to have consultants
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        $this->assertEquals(1, consultants_wanted($dat[0]["proid"]),"test 1");
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0 );

        // second test: project is configured to have no consultants
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        $this->assertEquals(0, consultants_wanted($dat[1]["proid"]),"test 2");
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 728 );

        $ps=array(0=>$t->translate("This project does not require "
                                   ."any consultants"),
                  1=>$t->translate('No consultants wanted'));

        $this->_testFor_patterns( $text, $ps, 2 );

        $this->_check_db( $db_config );
    }

    function testConsultants_insert() {
        global $db;
        
        $db_config = new mock_db_configure( 2 );
        
        $db_q = array( 0 => ( "INSERT consultants SET proid='%s',"
                              ."consultant='%s',status='P'"));

        $dat = $this->_generate_records( array( "proid", "user" ), 2 );

        $db_config->add_query( sprintf( $db_q[0], $dat[0]["proid"], 
                                        $dat[0]["user"]), 0 );

        // show_consultants also uses the global db and show_consultants
        // is called by consultants_insert.
        $db_config->ignore_all_errors( 0 );
        // instance 1 is created by monitor_mail(...) which is also called
        // by consultants_insert
        $db_config->ignore_all_errors( 1 );

        $db = new DB_SourceAgency;

        capture_reset_and_start();
        consultants_insert( $dat[0]["proid"], $dat[0]["user"]);
        $text = capture_stop_and_get();
        
        $this->_testFor_captured_length( 1422 );

        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );
?>
