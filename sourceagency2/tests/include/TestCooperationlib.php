<?php
// TestCooperationlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestCooperationlib.php,v 1.1 2003/11/21 12:56:03 helix Exp $

include_once( '../constants.php' );

include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'cooperationlib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

class UnitTestCooperationlib
extends UnitTest
{
    function UnitTestCooperationlib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset_global( 'db', 'bx' );
    }

    function testCooperation_form() {
        global $cost, $bx, $t, $sess;

        $args=$this->_generate_records( array( 'proid', 'devid' ), 2 );
        $cost = 'this is the cost';

        $bx = $this->_create_default_box();
        $this->capture_call( 'cooperation_form', 
                             1827 + strlen( $sess->self_url() ), $args[0] );

        $this->_checkFor_a_box( 'Cooperation' );
        $this->_checkFor_columns( 2 );
        $this->_checkFor_a_form('PHP_SELF',array('proid'=>$args[0]['proid'],
                                                 'devid'=>$args[0]['devid']));
        $this->_checkFor_column_titles( array( 'Cost in euro' ) );
        $this->_checkFor_column_values( array( html_input_text('cost',7,
                                                               7,$cost)));
        $this->_checkFor_submit_preview_buttons();
    }

    function testCooperation_preview() {
        global $t, $bx, $auth, $sess, $cost;
        
        $auth->set_uname( 'this is the username' );
        $args=$this->_generate_records( array( 'proid', 'devid' ), 2 );
        $cost = 'this is the cost';
        $bx = $this->_create_default_box();
        $this->capture_call( 'cooperation_preview', 
                             966 + strlen(timestr(time())), $args[0] );
        
        $this->_checkFor_a_box( 'PREVIEW', '<center><b>%s</b></center>' );
        $this->_checkFor_a_box( 'Cooperation' );
        $this->_testFor_pattern( $this->_to_regexp( "<p><b>Cost</b>: $cost "
                                                    ."euro\n"));
        $this->_testFor_lib_nick( $auth->auth['uname'] );
    }

    function testCooperation_show() {
        global $t, $bx, $db, $sess;
        
        $qs=array( 0 => ("SELECT * FROM cooperation,auth_user WHERE devid='%s'"
                         ." AND developer=username ORDER BY creation DESC"),
                   1 => ("SELECT * FROM comments,auth_user WHERE proid='%s' "
                         ."AND type='%s' AND number='%s' AND ref='%s' AND "
                         ."user_cmt=username ORDER BY creation_cmt ASC") );
        $db_config = new mock_db_configure( 3 );

        $args=$this->_generate_records( array( 'proid', 'devid' ), 10 );
        $dat=$this->_generate_records( array('creation', 'cost', 'status',
                                             'developer' ), 20 );
        // test one
        $db_config->add_query( sprintf( $qs[0], $args[0]['devid']), 0 );
        $db_config->add_num_row( 0, 0 );

        // test two 
        $db_config->add_query( sprintf( $qs[0], $args[1]['devid']), 1 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_record( $dat[0], 1 );
        $db_config->add_record( false, 1 );
        $db_config->add_query( sprintf($qs[1],$args[1]['proid'],'Cooperation',
                                                $dat[0]['creation'], '0'), 2 );
        $db_config->add_num_row( 0, 2 );
        // test one: no records
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'cooperation_show', 77, $args[0] );
        $this->assertEquals( "<p>There have not been posted any cooperation "
                             ."proposals by any developer.<p>\n", 
                             $this->get_text());

        // test two: one row of data
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'cooperation_show', 916, $args[1] );
        
        $this->_checkFor_a_box( 'Cooperation' );
        $this->_testFor_lib_nick( $dat[0]['developer'] );
        $str = ' - '.timestr( mktimestamp( $dat[0]['creation'] )) ."</b>";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $str = '<p><b>Cost</b>: '.$dat[0]['cost']." euro\n";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $str = '<br><b>Status</b>: '.show_status($dat[0]['status'])."\n";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->_testFor_lib_comment_it( $args[1]['proid'], 'Cooperation',
                                 $dat[0]['creation'], 0, '', 
                                 $t->translate('Comment This Cooperation!'));
        $this->_check_db( $db_config );
    }

    function testCooperation_insert() {
        global $db;
        
        $qs=array( 0=>("INSERT cooperation SET devid='%s',developer='%s',"
                       ."cost='%s',status='P'" ),
                   1=>("SELECT * FROM cooperation,auth_user WHERE devid='%s' "
                       ."AND developer=username ORDER BY creation DESC"));
        $args=$this->_generate_records(array( 'devid', 'developer', 'cost'),2);
        
        $db_config = new mock_db_configure( 1 );
        $db_config->add_query( sprintf( $qs[0], $args[0]['devid'], 
                                 $args[0]['developer'], $args[0]['cost']), 0 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['devid']), 0 );
        $db_config->add_num_row( 0, 0 );

        $str = "<p>There have not been posted any cooperation proposals "
             ."by any developer.<p>\n";
        $db = new DB_SourceAgency;
        $this->capture_call( 'cooperation_insert', strlen( $str ), $args[0] );
        $this->assertEquals( $str, $this->get_text() );
        $this->_check_db( $db_config );
    }
    
    function testCooperation_modify() {
        global $db;
        
        $qs=array( 0=>("UPDATE cooperation SET developer='%s', cost='%s' "
                       ."WHERE devid='%s' AND creation='%s'" ),
                   1=>("SELECT * FROM cooperation,auth_user WHERE devid='%s' "
                       ."AND developer=username ORDER BY creation DESC"));
        $args=$this->_generate_records(array( 'devid', 'developer', 'cost',
                                              'creation'),2);
        
        $db_config = new mock_db_configure( 1 );
        $db_config->add_query( sprintf( $qs[0], $args[0]['developer'], 
                                        $args[0]['cost'], $args[0]['devid'],
                                        $args[0]['creation']), 0 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['devid']), 0 );
        $db_config->add_num_row( 0, 0 );

        $str = "<p>There have not been posted any cooperation proposals "
             ."by any developer.<p>\n";
        $db = new DB_SourceAgency;
        $this->capture_call( 'cooperation_modify', strlen( $str ), $args[0] );
        $this->assertEquals( $str, $this->get_text() );
        $this->_check_db( $db_config );
    }

}

define_test_suite( __FILE__ );
?>
