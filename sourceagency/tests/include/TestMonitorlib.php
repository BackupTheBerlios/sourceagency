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
# include/monitorlib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestMonitorlib.php,v 1.12 2002/05/31 12:41:50 riessen Exp $
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
    }

    function tearDown() {
        // ensure that the next test does not have a predefined global
        // database object
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
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

        $row=$this->_generate_records( array( 'proid','type','subject',
                                              'message' ), 3 );
        $row[0]["type"] = "milestone_delivery"; // high propriety
        $row[1]["type"] = "configure"; // middle 
        $row[2]["type"] = "monitor"; // low

        for ( $idx = 0; $idx < 3; $idx++ ) {
          $db_config->add_record( false, $idx );
          $db_config->add_query( sprintf( $db_q[$idx], $row[$idx]["proid"] ), 
                                 $idx );
        }

        // 
        // three calls testing the three different priorities
        //
        for ( $idx = 0; $idx < 3; $idx++ ) {
          capture_reset_and_start();
          call_user_func_array( 'monitor_mail', $row[$idx] );
          $this->assert( strlen( capture_stop_and_get() ) == 0, 'test '.$idx );
        }

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testMonitor_show() {
        global $db, $t, $bx;
        
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

        $row=$this->_generate_records( array( "username", "perms","importance",
                                              "creation" ), 4 );
        $db_config->add_record( $row[0], 1 );
        $db_config->add_record( $row[1], 1 );
        $db_config->add_record( $row[2], 1 );
        $db_config->add_record( $row[3], 1 );

        // 
        // first call, no records
        //
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        monitor_show( $proid[0] );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 41 );
        $this->__testFor_pattern( "<p>"
                                  .$t->translate("Nobody is monitoring this "
                                                 ."project")."[.]<p>\n");
        // 
        // second call, 4 records
        //
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        monitor_show( $proid[1] );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 5255 );
        
        $color = array( 0 => "gold", 1 => "#FFFFFF" );

        $this->__checkFor_a_box( 'All these users are monitor this project');
        $this->__checkFor_columns( 5 );
    
        $w=array( 'Number'=>'10%','Username'=>'20%','Type'=>'20%',
                  'Importance filter'=>'20%','Creation'=>'30%');
        while ( list( $key, $val ) = each( $w ) ) {
            $this->__checkFor_column_titles( array( $key ),'center', $val, '');
        }
        for ( $idx = 0; $idx < 4; $idx++ ) {
            $this->set_msg( 'test '. $idx );
            $this->__testFor_box_column( 'center','',$color[$idx%2],
                                          '<b>'.($idx+1).'</b>');
            $this->__testFor_box_column( 'center','',$color[$idx%2],
                                        '<b>'.lib_nick($row[$idx]['username'])
                                        .'</b>');
            $this->__testFor_box_column( 'center','',$color[$idx%2],
                                        '<b>'.$row[$idx]['perms']
                                        .'</b>');
            $this->__testFor_box_column( 'center','',$color[$idx%2],
                                        '<b>'.$row[$idx]['importance']
                                        .'</b>');
            $str = timestr_middle(mktimestamp($row[$idx]['creation']));
            $this->__testFor_box_column( 'center','',$color[$idx%2],
                                        '<b>'.$str.'</b>');
            
        }

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testMonitor_preview() {
        global $importance, $auth, $bx;
        $row = array( 0 => $this->_generate_array(array("proid","uname"),0));
                
        //
        // first call
        //
        $importance = "middle";
        $auth->set_uname( $row[0]["uname"] );
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        monitor_preview( $row[0]["proid"] );
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_string_length( 1021 + strlen( timestr( time() )) );
        $this->__testFor_pattern( "<b>by uname_0<\/b>" );
    }
    
    function testMonitor_form() {
        global $importance, $sess, $bx, $t;
        
        $importance = "low";
        $proid = "proid_0";
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        monitor_form( $proid );
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_string_length( 1897 + strlen( $sess->self_url()));

        $this->__checkFor_a_box( 'Monitor this project' );
        $this->__checkFor_a_form( 'PHP_SELF', array('proid' => $proid) );
        $this->__checkFor_columns( 2 );
        $this->__checkFor_column_titles( array( 'Importance' ));
        $this->__checkFor_submit_preview_buttons( );
    }

    function testMailuser() {
        $this->_test_to_be_completed();
    }

    function testMonitor_insert() {
        $this->_test_to_be_completed();
    }

    function testMonitor_modify() {
        $this->_test_to_be_completed();
    }

    function testSelect_importance() {
        foreach( array( 'fubar','low','medium','high', 'snafu' ) as $val ) {
            $this->set_text( select_importance( $val ) );
            $this->set_msg( 'Test '. $val );
            $this->__testFor_html_select( 'importance' );
            $sed = false; // set if something was selected
            foreach( array( 'low','medium','high') as $imp ) {
                $this->__testFor_html_select_option($imp, $imp==$val, $imp);
                $sed = $sed || ( $imp == $val );
            }
            $this->__testFor_html_select_end();
            $this->_testFor_string_length( ($sed ? 164 : 155) );
        }
    }

}

define_test_suite( __FILE__ );

?>
