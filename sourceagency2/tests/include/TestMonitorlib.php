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
# $Id: TestMonitorlib.php,v 1.1 2003/11/21 12:56:02 helix Exp $
#
######################################################################

include_once( "../constants.php" );

include_once( 'box.inc' );
include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'monitorlib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $GLOBALS['sess'] = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

class UnitTestMonitorlib
extends UnitTest
{
    var $queries;

    function UnitTestMonitorlib( $name ) {
        $this->queries = array(
            'monitor_mail_1' =>
            ("SELECT email_usr FROM auth_user,monitor WHERE monitor.username"
             ."=auth_user.username AND proid='%s' AND importance='high'"),
            'monitor_mail_2' =>
            ("SELECT email_usr FROM auth_user,monitor WHERE monitor.username"
             ."=auth_user.username AND proid='%s' AND (importance='middle' OR "
             ."importance='high')"),
            'monitor_mail_3' =>
            ("SELECT email_usr FROM auth_user,monitor WHERE monitor.username"
             ."=auth_user.username AND proid='%s' "),
            'monitor_show' =>
            ("SELECT * FROM monitor,auth_user WHERE proid='%s' AND monitor."
             ."username=auth_user.username ORDER BY creation DESC"),
            'mailuser' =>
            ("SELECT email_usr FROM auth_user WHERE perms LIKE '%%%s%%'"),
            'monitor_modify' =>
            ("UPDATE monitor SET importance='%s' WHERE proid='%s' AND "
             ."username='%s'"),
            'monitor_insert_1' =>
            ("SELECT * FROM monitor WHERE proid='%s' AND username='%s'"),
            'monitor_insert_2' =>
            ("INSERT monitor SET proid='%s',username='%s',importance='%s'")
            );
        $this->UnitTest( $name );
    }

    function setup() {
    }

    function tearDown() {
        // ensure that the next test does not have a predefined global
        // database object
        unset_global( 'db', 'bx' );
    }

    function testMonitor_mail() {
        // ASSUME: this does not test the mail function, this is assumed to 
        // ASSUME: work
        $fname = 'monitor_mail';
        $db_config = new mock_db_configure( 3 );
        $db_q = array( 0 => $this->queries[ $fname . '_1' ],
                       1 => $this->queries[ $fname . '_2' ],
                       2 => $this->queries[ $fname . '_3' ]);

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

        $db_q = array( 0 => $this->queries[ 'monitor_show' ]);
        
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
        $this->_testFor_pattern( "<p>"
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

        $this->_checkFor_a_box( 'All these users are monitor this project');
        $this->_checkFor_columns( 5 );
    
        $w=array( 'Number'=>'10%','Username'=>'20%','Type'=>'20%',
                  'Importance filter'=>'20%','Creation'=>'30%');
        while ( list( $key, $val ) = each( $w ) ) {
            $this->_checkFor_column_titles( array( $key ),'center', $val, '');
        }
        for ( $idx = 0; $idx < 4; $idx++ ) {
            $this->set_msg( 'test '. $idx );
            $this->_testFor_box_column( 'center','',$color[$idx%2],
                                          '<b>'.($idx+1).'</b>');
            $this->_testFor_box_column( 'center','',$color[$idx%2],
                                        '<b>'.lib_nick($row[$idx]['username'])
                                        .'</b>');
            $this->_testFor_box_column( 'center','',$color[$idx%2],
                                        '<b>'.$row[$idx]['perms']
                                        .'</b>');
            $this->_testFor_box_column( 'center','',$color[$idx%2],
                                        '<b>'.$row[$idx]['importance']
                                        .'</b>');
            $str = timestr_middle(mktimestamp($row[$idx]['creation']));
            $this->_testFor_box_column( 'center','',$color[$idx%2],
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
        $this->_testFor_pattern( "<b>by uname_0<\/b>" );
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

        $this->_checkFor_a_box( 'Monitor this project' );
        $this->_checkFor_a_form( 'PHP_SELF', array('proid' => $proid) );
        $this->_checkFor_columns( 2 );
        $this->_checkFor_column_titles( array( 'Importance' ));
        $this->_checkFor_submit_preview_buttons( );
    }

    function testSelect_importance() {
        foreach( array( 'fubar','low','medium','high', 'snafu' ) as $val ) {
            $this->set_text( select_importance( $val ) );
            $this->set_msg( 'Test '. $val );
            $this->_testFor_html_select( 'importance' );
            $sed = false; // set if something was selected
            foreach( array( 'low','medium','high') as $imp ) {
                $this->_testFor_html_select_option($imp, $imp==$val, $imp);
                $sed = $sed || ( $imp == $val );
            }
            $this->_testFor_html_select_end();
            $this->_testFor_string_length( ($sed ? 164 : 155) );
        }
    }

    function testMailuser() {
        global $db;
        
        $q = $this->queries[ 'mailuser' ];
        $db_config = new mock_db_configure( 1 );
        $args=$this->_generate_array( array( 'perms','subj','message' ), 10);
        $db_config->add_query( sprintf( $q, $args['perms']), 0 );
        $db_config->add_record( false, 0 );
        $db = new DB_SourceAgency;
        $this->capture_call( 'mailuser', 0, $args );
        $this->_check_db( $db_config );
    }

    function testMonitor_modify() {
        global $db, $t;

        $fname = 'monitor_modify';
        $qs = array( 0 => $this->queries[ $fname ],
                     1 => $this->queries[ 'monitor_mail_3' ],
                     2 => $this->queries[ 'monitor_show' ] );

        $db_config = new mock_db_configure( 2 );
        $args = $this->_generate_array( array( 'proid', 'username',
                                               'importance', 'creation'), 10);
        // test one
        $db_config->add_query( sprintf( $qs[0], $args['importance'],
                                       $args['proid'], $args['username'] ), 0);
        $db_config->add_query( sprintf( $qs[1], $args['proid'] ), 1 );
        $db_config->add_record( false, 1 );
        $db_config->add_query( sprintf( $qs[2], $args['proid']), 0);
        $db_config->add_num_row( 0, 0 );

        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 41, $args );
        $this->assertEquals( "<p>".$t->translate("Nobody is monitoring this "
                                                 ."project").".<p>\n",
                             $this->get_text() );
        
        $this->_check_db( $db_config );
    }

    function testMonitor_insert() {
        global $db, $t;

        $fname = 'monitor_insert';
        $qs = array( 0 => $this->queries[ $fname . '_1' ],
                     1 => $this->queries[ $fname . '_2' ],
                     2 => $this->queries[ 'monitor_modify' ],
                     3 => $this->queries[ 'monitor_mail_3' ],
                     4 => $this->queries[ 'monitor_show' ] );

        $db_config = new mock_db_configure( 4 );
        $args = $this->_generate_records( array( 'proid', 'username',
                                                 'importance' ), 10 );
        $creation = 'thisd ithe creation';
        // test one: number of rows > 0 ==> monitor_modify called
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid'],
                                              $args[0]['username']), 0 );
        $db_config->add_num_row( 10, 0 );
        $db_config->add_record( array( 'creation' => $creation ), 0 );

        $db_config->add_query( sprintf( $qs[2], $args[0]['importance'],
                                  $args[0]['proid'], $args[0]['username']), 0);
        /** monitor mail call **/
        $db_config->add_query( sprintf( $qs[3], $args[0]['proid'] ), 1 );
        $db_config->add_record( false, 1 );
        /** monitor show call **/
        $db_config->add_query( sprintf( $qs[4], $args[0]['proid'] ), 0 );
        $db_config->add_num_row( 0, 0 );

        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 41, $args[0] );

        // test two: number of rows == 0, insert and monitor_{mail,show} called
        $db_config->add_query( sprintf( $qs[0], $args[1]['proid'],
                                              $args[1]['username']), 2 );
        $db_config->add_num_row( 0, 2 );
        $db_config->add_query( sprintf( $qs[1], $args[1]['proid'],
                             $args[1]['username'], $args[1]['importance']), 2);
        
        /** monitor mail call **/
        $db_config->add_query( sprintf( $qs[3], $args[1]['proid'] ), 3 );
        $db_config->add_record( false, 3 );
        /** monitor show call **/
        $db_config->add_query( sprintf( $qs[4], $args[1]['proid'] ), 2 );
        $db_config->add_num_row( 0, 2 );

        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 41, $args[1] );
        
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
