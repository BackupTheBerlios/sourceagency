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
# include/box.php
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestBox.php,v 1.1 2003/11/21 12:56:02 helix Exp $
#
######################################################################

include_once( "../constants.php" );

include_once( "box.inc" );

if ( !defined("BEING_INCLUDED" ) ) {
}

class UnitTestBox
extends UnitTest
{
    var $box;

    function UnitTestBox( $name ) {
        $this->UnitTest( $name );
    }

    // this is called before each test method
    function setup() {
        $this->box = $this->_create_default_box();
    }

    function tearDown() {
      // capture stop should be called by each method as required, but
      // in case a method forgets, call it after the tests
    }

    // shortcut methods ...
    function _test_box_method( $name, $exp_length, &$args ) {
        capture_reset_and_start();
        $this->_call_method( $name, $args, &$this->box );
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_string_length( $exp_length );
        $this->_call_method( '_testFor_'.$name, $args );
    }

    function _test_no_arg_method( $name, $exp_length ) {
        $this->_test_box_method( $name, $exp_length, $arg=array() );
    }


    // the following the individual test methods
    function testBox_begin() {
        $this->_test_no_arg_method( 'box_begin', 224 );
    }

    function testBox_end() {
        $this->_test_no_arg_method( 'box_end', 49 );
    }


    function testBox_title_begin() {
        $this->_test_no_arg_method( 'box_title_begin', 90 );
    }


    function testBox_title_end() {
        $this->_test_no_arg_method( 'box_title_end', 47 );
    }

    function testBox_title() {
        $title = "box_title";
        capture_reset_and_start();
        $this->box->box_title($title);
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_string_length( 198 );

        $this->_testFor_box_title_begin( );
        $this->_testFor_box_title( $title );
        $this->_testFor_box_title_end( );
    }

    function testBox_body_begin() {
        $this->_test_no_arg_method('box_body_begin', 134 );
    }

    function testBox_body_end() {
        $this->_test_no_arg_method( 'box_body_end', 60 );
    }

    function testBox_body() {
        $body = "text for body";
        capture_reset_and_start();
        $this->box->box_body($body);
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_string_length( 282 );

        $this->_testFor_box_body_begin();
        $this->_testFor_box_body($body);
        $this->_testFor_box_body_end();
    }

    function testBox_full() {
        $title = "this is the title";
        $body = "this is the body";
        capture_reset_and_start();
        $this->box->box_full($title, $body);
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_string_length( 764 );

        $this->_testFor_box_begin();
        $this->_testFor_box_title($title );
        $this->_testFor_box_body($body );
        $this->_testFor_box_end();
    }

    function testBox_strip() {
        $title = "thsi is teh title";
        capture_reset_and_start();
        $this->box->box_strip( $title );
        $this->set_text( capture_stop_and_get() );

        $this->_testFor_string_length( 479 );
        $this->_testFor_box_begin();
        $this->_testFor_box_title($title);
        $this->_testFor_box_end();
    }

    function testBox_columns_begin() {
        $args = array( "nr_cols" => "four or five" );
        $this->_test_box_method( 'box_columns_begin', 151, $args );
    }

    function testBox_column_start() {
        $args=array("align" => "this is the align",
                    "width" => "and this is the width",
                    "bg_color" => "this is the background color" );
        $this->_test_box_method( 'box_column_start', 144, &$args );

        $args=array("align" => "this is the align",
                    "width" => "and this is the width" );
        $this->_test_box_method( 'box_column_start', 123, &$args );
    }

    function testBox_column_finish() {
        $this->_test_no_arg_method( 'box_column_finish', 49 );
    }

    function testBox_columns_end() {
        $this->_test_no_arg_method( 'box_columns_end', 59 );
    }

    function testBox_column() {
        $args=$this->_generate_records(array("align","width","bgcolor","txt"),
                                       1);
        $this->_test_box_method( 'box_column', 164, $args[0] );
    }

    function testBox_next_row_of_columns() {
        $this->_test_no_arg_method( 'box_next_row_of_columns', 68 );
    }

    function testBox_colspan() {
        $args=array( "nr_cols" => "number of columns",
                     "align" => "this is the alignment",
                     "bgcolor" => "this is the background color",
                     "insert_text" => "this is the inserted text" );
        $this->_test_box_method( 'box_colspan', 307, $args );
    }

    function testBox_set_body_valign() {
        $this->box->box_set_body_valign( "left" );
        $this->assertEquals( "left", $this->box->box_body_valign, "test 1" );
        $this->box->box_set_body_valign( );
        $this->assertEquals( "top", $this->box->box_body_valign, "test 2" );
    }
}

define_test_suite( __FILE__ );

?>
