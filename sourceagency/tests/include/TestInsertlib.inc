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
# include/insertlib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestInsertlib.inc,v 1.19 2002/07/23 14:26:06 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

include_once( 'box.inc' );
include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'insertlib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( 'session.inc' );
    $GLOBALS[ 'sess' ] = new session;
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
} 

class UnitTestInsertlib
extends UnitTest
{
    function UnitTestInsertlib( $name ) {
        $this->UnitTest( $name );
    }

    function tearDown() {
        // ensure that the next test does not have a predefined global
        // database object
        unset_global( 'db', 'bx' );
    }

    function testInsert_form() {
        global $volume, $type, $description, $project_title, $bx, $t;

        $volume = "this is the volume";
        $type = "this is the type";
        $description = "this is the description";
        $project_title = "this is the project_title";

        //
        // first and only call....
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        insert_form();
        $this->set_text( capture_stop_and_get() );

        $this->_checkFor_a_box( 'New Project' );
        $this->_checkFor_a_form( 'insert.php3', array() );

        $this->_checkFor_columns( 2 );

        $this->_checkFor_column_titles( array( 'Project Title', 'Type', 
                                                'Brief description',
                                                'Project Volume' ));
        $this->_checkFor_column_values( 
            array( html_input_text('project_title', 40, 128, 
                                   $project_title),
                   select_from_config('type',  'project_types', $type),
                   html_textarea('description', 40, 7, 'virtual',
                                 255, $description),
                   select_from_config('volume', 'project_volume', 
                                      $volume)));
        $this->_testFor_html_form_submit( $t->translate('Submit'), 'Submit');
        $this->_testFor_string_length( 3690 );
    }

    function testProject_title_not_set() {
        global $description, $type, $volume, $t;
        
        $type = "this is the type";
        $volume = "this is the volume";
        $description = "this is the description";

        capture_reset_and_start();
        project_title_not_set();
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( "Test 1" );
        $this->_testFor_string_length( 841 );

        require( 'config.inc' );

        $body_text = $t->translate('No Project Title specified').'.'
             .'<br>'.$t->translate('Please select').' '
             .html_link('insform.php3', 
                        array('description' => $description, 
                              'type' => $type, 
                              'volume' => $volume), 
                        $t->translate('New Projects'));

        $this->_testFor_box_begin( $th_box_frame_color,
                                    '', $th_box_frame_width );
        $this->_testFor_box_title( $t->translate('Error'),
                            $th_box_title_font_color, $th_box_title_bgcolor,
                            $th_box_title_align);
        $this->_testFor_box_body_begin( $th_box_body_bgcolor,
                            $th_box_body_align,'top',$th_box_error_font_color);
        $this->_testFor_box_body( $body_text, $th_box_error_font_color );
        $this->_testFor_box_body_end( );
        $this->_testFor_box_end( );
    }

    function testDescription_not_set() {
        global $project_title, $type, $volume, $t;

        $type = "this is the type";
        $volume = "this is the volume";
        $project_title = "this is the project title";

        capture_reset_and_start();
        description_not_set();
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 851 );
        
        require( 'config.inc' );

        $body_text = $t->translate('No Project Description specified').'.'
                  .'<br>'.$t->translate('Please select').' '
		  .html_link('insform.php3', 
                             array('project_title' => $project_title, 
                                   'type' => $type, 'volume' => $volume), 
                             $t->translate('New Projects'));

        $this->_testFor_box_begin($th_box_frame_color,'',$th_box_frame_width);
        $this->_testFor_box_title( $t->translate('Error'),
                            $th_box_title_font_color, $th_box_title_bgcolor,
                            $th_box_title_align);
        $this->_testFor_box_body_begin( $th_box_body_bgcolor,
                            $th_box_body_align,'top',$th_box_error_font_color);
        $this->_testFor_box_body( $body_text,$th_box_error_font_color );
        $this->_testFor_box_body_end();
        $this->_testFor_box_end();
    }

    function testNo_other_project_with_same_title() {
        global $db, $t;

        $db_config = new mock_db_configure( 3 );

        $db_q = array( 0 => ("SELECT * FROM description WHERE project_title"
                             ."='%s'") );

        $pt = array(0=>$this->_generate_array(array("project_title"),0),
                    1=>$this->_generate_array(array("project_title"),1),
                    2=>$this->_generate_array(array("project_title"),2));

        $db_config->add_query( sprintf( $db_q[0], $pt[0]["project_title"] ),0);
        $db_config->add_query( sprintf( $db_q[0], $pt[1]["project_title"] ),1);
        $db_config->add_query( sprintf( $db_q[0], $pt[2]["project_title"] ),2);

        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_num_row( 2, 2 );

        //
        // first call, num rows == 0
        //
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        $this->assertEquals( 1, no_other_project_with_same_title( 
                                                   $pt[0]["project_title"] ));
        $this->assert( strlen( capture_stop_and_get() ) == 0, 'test 1' );

        //
        // second call, num_row == 1
        //
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        $this->assertEquals( 0, no_other_project_with_same_title( 
                                                  $pt[1]["project_title"] ));
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'Test 2' );
        $this->_testFor_string_length( 777 );
        require( 'config.inc' );

        $body_text = $t->translate('Another project with that title '
                                     .'already exists').'.'
                      .'<br>'.$t->translate('Please select').' '
                      .html_link('insform.php3', array(), 
                                 $t->translate('New Projects'));

        $this->_testFor_box_begin( $th_box_frame_color,
                                    '', $th_box_frame_width );
        $this->_testFor_box_title( $t->translate('Error'),
                            $th_box_title_font_color, $th_box_title_bgcolor,
                            $th_box_title_align);
        $this->_testFor_box_body_begin( $th_box_body_bgcolor,
                            $th_box_body_align,'top',$th_box_error_font_color);
        $this->_testFor_box_body( $body_text,$th_box_error_font_color );
        $this->_testFor_box_body_end();
        $this->_testFor_box_end();

        //
        // third call, num_row == 2
        //
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        $this->assertEquals( 1, no_other_project_with_same_title( 
                                                    $pt[2]["project_title"] ));
        $this->assert( strlen( capture_stop_and_get() ) == 0, 'test 3' );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testEverything_filled() {
        $fname = 'everything_filled';
        $args = $this->_generate_records( array('project_title',
                                                'description' ), 3 );
        // test one
        $this->assertEquals( 1, $this->capture_call( $fname, 0, $args[0] ) );

        // test two
        $args[1]['project_title'] = '';
        $this->assertEquals( 0, $this->capture_call( $fname, 841, $args[1] ) );

        // test three: project title is unset 
        $project_title = 'this is the project title'; 
        unset( $project_title );
        $description = 'tis is the description';
        capture_reset_and_start();
        $this->assertEquals( 0, $fname( $project_title, $description ) );
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_string_length( 841 );

        // test four: description is empty
        $args[2]['description'] = '';
        $this->assertEquals( 0, $this->capture_call( $fname, 851, $args[2]));

        // test three: unset project description
        $description = 'this is the project title'; 
        unset( $description );
        $project_title = 'tis is the description';
        // can't use capture call here because the array call_user_func_array
        // already complains about description not being set
        capture_reset_and_start();
        $this->assertEquals( 0, $fname( $project_title, $description ) );
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_string_length( 851 );
    }

    function testInsert_into_database() {
        global $db, $auth, $t;
        
        $fname = 'insert_into_database';
        $uname = 'this is the uname';
        $auth->set_uname( $uname );
        $qs = array( 0 => ("INSERT description SET project_title='%s', "
                           ."type='%s', description='%s',  description_user="
                           ."'$uname',  volume='%s', status='0', "
                           ."description_creation=NOW()"),
                     1 => ("SELECT * FROM description WHERE project_title="
                           ."'%s'"),
                     2 => ("INSERT views SET configure='Project Participants',"
                           ." views='Project Participants',"
                           ." news='Project Initiator', history='Everybody', "
                           ."step3='Everybody', step4='Everybody', "
                           ."step5='Everybody',"
                           ."cooperation='Everybody',proid=%s"),
                     3 => ("INSERT counter SET proid=%s"),
                     4 => ("SELECT * FROM description,auth_user WHERE "
                           ."description.proid='%s' AND description."
                           ."description_user=auth_user.username GROUP BY " 
                           ."description.proid"));
        $args = $this->_generate_records( array( 'project_title','description',
                                                 'type', 'volume'), 10 );
        $db_config = new mock_db_configure( 2 );
        
        $db_config->add_query( sprintf( $qs[0], $args[0]['project_title'],
                                $args[0]['type'], $args[0]['description'], 
                                $args[0]['volume']), 0 );
        $db_config->add_affected_rows( 1, 0 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['project_title']),0);
        $proid = 'this si the proid';
        $db_config->add_record( array( 'proid' => $proid ), 0 );
        $db_config->add_query( sprintf( $qs[2], $proid ), 0 );
        $db_config->add_query( sprintf( $qs[3], $proid ), 0 );

        /** lib show description **/
        $db_config->add_query( sprintf( $qs[4], $proid ), 1 );
        $db_config->add_record( false, 1 );
        $db_config->add_num_row( 0, 1 );

        /** lib_insertion_information --> is sponsor **/
        /** lib_insertion_information --> is developer **/
        $auth->set_perm( '' );

        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 175, $args[0] );

        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
