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
# $Id: TestInsertlib.inc,v 1.11 2002/06/04 10:57:52 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( 'box.inc' );
    $bx = new box;

    include_once( 'session.inc' );
    $sess = new session;
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");
} 

include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'insertlib.inc' );

class UnitTestInsertlib
extends UnitTest
{
    function UnitTestInsertlib( $name ) {
        $this->UnitTest( $name );
    }

    function tearDown() {
        // ensure that the next test does not have a predefined global
        // database object
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
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
        $this->_test_to_be_completed();
    }
    function testInsert_into_database() {
        // TODO: need to implement the affected rows method of mock_database
        // TODO: in order to implement the test method for insert_into_database
        $this->_test_to_be_completed();
    }
}

define_test_suite( __FILE__ );

?>
