<?php
// TestConsultantslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestConsultantslib.php,v 1.10 2002/06/14 09:14:12 riessen Exp $

include_once( "../constants.php" );

include_once( 'lib.inc' );
include_once( 'html.inc');
include_once( "box.inc" );
include_once( 'consultantslib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $GLOBALS[ 'sess' ] = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

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
        unset( $GLOBALS['bx'] );
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
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( "test 1" );

        $this->_testFor_string_length( 64 );
        $this->_testFor_pattern( "No developers have offered "
                                  ."themselves as consultants yet" );

        // second test: three pieces of data
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        show_consultants( $dat[1]["proid"] );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_checkFor_a_box( 'Consultants' );
        $this->_checkFor_columns( 4 );
        $this->_checkFor_column_titles(array("Number","Username",
                                              "Status","Creation"), '','','' );

        $colors = array( 1 => 'gold', 0 => '#FFFFFF' );
        for ( $idx = 1; $idx < 4; $idx++ ) {
            $row = $rows[ $idx - 1 ];
            $this->set_msg( "Test $idx" );
            $this->_testFor_box_next_row_of_columns();
            $this->_checkFor_column_values(  
                         array( '<b>'.$idx.'</b>',
                                '<b>'.lib_nick($row['username']),
                                '<b>'.show_status($row["status"]).'</b>',
                                '<b>'.timestr(mktimestamp($row["creation"]))
                                .'</b>'), '', '', $colors[ $idx % 2] );
        }

        $this->_testFor_string_length( 3573 );
        $this->_check_db( $db_config );
    }

    function testConsultants_form() {
        global $bx, $auth, $sess, $t;

        $proid = "proid_0";
        $uname = "this is the username";
        $auth->set_uname($uname);
        $auth->set_perm("this is the permission");

        $bx = $this->_create_default_box();
        capture_reset_and_start();
        consultants_form( $proid );
        $this->set_text( capture_stop_and_get() );

        $this->_checkFor_a_box( 'Offer yourself as project consultant' );

        $this->_checkFor_a_form( 'PHP_SELF', array('proid'=>$proid));
        $this->_checkFor_columns( 2 );

        $this->_checkFor_column_titles( array("Your username","Check if you "
                                               ."want to be a consultant"),
                                         'right', '45%', '' );
        $this->_checkFor_column_values( array( $uname, 
                         html_checkbox('check','check',''),
                         html_form_submit($t->translate('Submit'),'submit')),
                         'left', '55%', '' );
        $this->_testFor_box_next_row_of_columns();
        $this->_testFor_string_length( 2184 + strlen( $sess->self_url() ));
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
        $this->assert( strlen( capture_stop_and_get() ) == 0, 'test 1' );

        // second test: project is configured to have no consultants
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        $this->assertEquals(0, consultants_wanted($dat[1]["proid"]),"test 2");
        $this->set_text( capture_stop_and_get() );

        include( 'config.inc' ); // for th_box_{error|title}_font_color
        $this->_testFor_box_title($t->translate('No consultants wanted'),
                                    $th_box_title_font_color,
                                    $th_box_title_bgcolor,
                                    $th_box_title_align );
        $this->_testFor_box_body( $t->translate("This project does "
                                                 ."not require "
                                                 ."any consultants"), 
                                   $th_box_error_font_color );
        $this->_testFor_string_length( 728 );
        $this->_check_db( $db_config );
    }

    function testConsultants_insert() {
        global $db, $bx, $t;
        
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
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        consultants_insert( $dat[0]["proid"], $dat[0]["user"]);
        $this->set_text( capture_stop_and_get() );
        
        // the basics for show_consultants(...), assume that the rest
        // is also present
        $this->_checkFor_a_box( 'Consultants' );
        $this->_checkFor_columns( 4 );

        $this->_testFor_string_length( 1494 );
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );
?>
