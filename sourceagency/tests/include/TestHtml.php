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
# include/html.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestHtml.php,v 1.16 2002/04/23 12:07:45 riessen Exp $
#
######################################################################

// unit test for testing the html.inc file.
include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // need to define a global session
    include_once( "session.inc" );
    $sess = new Session;
}

include_once("html.inc");

class UnitTestHtml
extends UnitTest
{
    function UnitTestHtml( $name ) {
        $this->UnitTest( $name );
    }

    function setup() {
        /* Called before each test method */
        capture_reset_and_start();
    }
    function tearDown() {
        /* Called after each test method */
        capture_stop();
    }

    function testhtml_link() {
        //
        // test 1
        //
        $actual = html_link('fubar',array( 'one' => 'what'),'hello world' );
        $expect = "<a href=\"fubar?one=what\">hello world</a>";
        $this->assertEquals( $expect, $actual );
        htmlp_link('fubar',array( 'one' => 'what'),'hello world' );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 40, "test 1" );
        $this->assertEquals( $expect, $text );
        
        // 
        // test 2
        //
        $actual = html_link( 'snafu', "", 'goodbye cruel world' );
        $expect = "<a href=\"snafu\">goodbye cruel world</a>";
        $this->assertEquals( $expect, $actual );
        capture_reset_and_start();
        htmlp_link( 'snafu', "", 'goodbye cruel world' );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 39, "test 2" );
        $this->assertEquals( $expect, $text );

        //
        // test 3
        //
        $actual = html_link('fubar',array( 'one' => 'what the hell'),
                            'hello world' );
        $expect = "<a href=\"fubar?one=what+the+hell\">hello world</a>";
        $this->assertEquals( $expect, $actual );
        capture_reset_and_start();
        htmlp_link('fubar',array( 'one' => 'what the hell'),
                  'hello world' );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 49, "test 3" );
        $this->assertEquals( $expect, $text );

        //
        // test 4
        //
        $actual = html_link('fubar',array( 'one' => 'what+the+hell'),
                            'hello world' );
        $expect = "<a href=\"fubar?one=what%2Bthe%2Bhell\">hello world</a>";
        $this->assertEquals( $expect, $actual );
        capture_reset_and_start();
        htmlp_link('fubar',array( 'one' => 'what+the+hell'),
                   'hello world' );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 53, "test 4" );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_anchor() {
        $actual = html_anchor( "hello world" );
        $expect = "<a name=\"hello world\"></a>";
        $this->assertEquals( $expect, $actual );

        // test the print variation of the same function
        htmlp_anchor( "hello world" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 26, "test 1" );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_image() {
        $actual = html_image("file", "border", "width", "height", "alternate");
        $expect = ("<img src=\"images/file\" border=\"border\" width=\"width\""
                   . " height=\"height\" alt=\"alternate\">");
        $this->assertEquals( $expect, $actual );
        
        // test the print variation of the same function
        htmlp_image("file", "border", "width", "height", "alternate");
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 85, "test 1" );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_form_action() {
        global $sess;
        //
        // test 1
        //
        $text = html_form_action( "PHP_SELF", "query", "type" );
        $expect = ( "[ \n]+<form action=\""
                    .ereg_replace( "/", "\/", $sess->self_url() )
                    ."\" method=\"type\">" );
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_form_action( "PHP_SELF", "query", "type" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 31 +  strlen( $sess->self_url() ), 
                                         "test 1");
        $this->_testFor_pattern( $text, $expect, "p2" );

        // 
        // test 2
        //
        $text = html_form_action( "file", "query", "type" );
        $expect = "[ \n]+<form action=\"file\" method=\"type\">";
        $this->_testFor_pattern( $text, $expect, "p3" );
        capture_reset_and_start();
        htmlp_form_action( "file", "query", "type" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 35, "test 2" );
        $this->_testFor_pattern( $text, $expect, "p4" );
    }

    function testhtml_form_hidden() {
        $text = html_form_hidden( "name", "value" );
        $expect = ( "[ \n]+<input type=\"hidden\" name=\"name\" "
                    ."value=\"value\">" );
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_form_hidden( "name", "value" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 52, 'test 1' );
        $this->_testFor_pattern( $text, $expect, "p2" );
    }

    function testhtml_select() {
        // test 1: single name argument tests
        $text = html_select( "name" );
        $expect = "[ \n]+".'<select name="name" size="0">'."\n";
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_select( "name" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 34, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );

        // test2: test the name and size arguments
        $text = html_select( "name", 0, 23 );
        $expect = "[ \n]+".'<select name="name" size="23">'."\n";
        $this->_testFor_pattern( $text, $expect, "p3" );
        capture_reset_and_start();
        htmlp_select( "name", 0, 23 );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 35, "test 2" );
        $this->_testFor_pattern( $text, $expect, "p4" );

        // test3: test the size and multiple argument
        $text = html_select( "name", 1, 23 );
        $expect = "[ \n]+".'<select name="name" size="23" multiple>'."\n";
        $this->_testFor_pattern( $text, $expect, "p5" );
        capture_reset_and_start();
        htmlp_select( "name", 1, 23 );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 44, "test 3" );
        $this->_testFor_pattern( $text, $expect, "p6" );
    }

    function testhtml_select_option() {
        //
        // test 1
        //
        $text = html_select_option( "value", "selected", "text" );
        $expect = "[ \n]+<option selected value=\"value\">text\n";
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_select_option( "value", "selected", "text" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 43, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );

        //
        // test 2
        //
        $text = html_select_option( "value", "", "" );
        $expect = "[ \n]+<option value=\"value\">\n";
        $this->_testFor_pattern( $text, $expect, "p3" );
        capture_reset_and_start();
        htmlp_select_option( "value", "", "" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 30, "test 2" );
        $this->_testFor_pattern( $text, $expect, "p4" );

        //
        // test 3
        //
        $text = html_select_option( "", false, "text" );
        $expect = "[ \n]+<option value=\"\">text\n";
        $this->_testFor_pattern( $text, $expect, "p5" );
        capture_reset_and_start();
        htmlp_select_option( "", false, "text" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 29, "test 3" );
        $this->_testFor_pattern( $text, $expect, "p6" );

        //
        // test 4
        //
        $text = html_select_option( "", true, "" );
        $expect = "[ \n]+<option selected value=\"\">\n";
        $this->_testFor_pattern( $text, $expect, "p7" );
        capture_reset_and_start();
        htmlp_select_option( "", true, "" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 34, "test 4" );
        $this->_testFor_pattern( $text, $expect, "p8" );
    }

    function testhtml_select_end() {
        $text = html_select_end();
        $expect = "[ \n]+<\/select>\n";
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_select_end();
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 14, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );
    }

    function testhtml_input_text() {
        $text = html_input_text( "name", "size", "maxlength", "value" );
        $expect = ( "[ \n]+<input type=\"text\" name=\"name\" size=\"size\" "
                    ."maxlength=\"maxlength\" value=\"value\">" );
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_input_text( "name", "size", "maxlength", "value" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 83, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );
    }

    function testhtml_form_submit() {
        // test 1 with name
        $text = html_form_submit( "value", "name" );
        $expect = ( "[ \n]+<input type=\"submit\" value=\"value\" "
                    ."name=\"name\">" );
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_form_submit( "value", "name" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 51, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );

        // test 2 without name
        $text = html_form_submit( "value" );
        $expect = ( "[ \n]+<input type=\"submit\" value=\"value\">");
        $this->_testFor_pattern( $text, $expect, "p3" );
        capture_reset_and_start();
        htmlp_form_submit( "value" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 39, "test 2" );
        $this->_testFor_pattern( $text, $expect, "p4" );
    }

    function testhtml_checkbox() {
        //
        // test 1
        //
        $text = html_checkbox( "name", "value", "checked" );
        $expect = ("[ \n]+<input type=\"checkbox\" name=\"name\" "
                   ."value=\"value\" checked >");
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_checkbox( "name", "value", "checked" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 62, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );

        //
        // test 2
        //
        $text = html_checkbox( "name", "value", "" );
        $expect = ("[ \n]+<input type=\"checkbox\" name=\"name\" "
                   ."value=\"value\">");
        $this->_testFor_pattern( $text, $expect, "p3" );
        capture_reset_and_start();
        htmlp_checkbox( "name", "value", "" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 53, "test 2" );
        $this->_testFor_pattern( $text, $expect, "p4" );

        //
        // test 3
        //
        $text = html_checkbox( "name", "value", false );
        $expect = ("[ \n]+<input type=\"checkbox\" name=\"name\" "
                   ."value=\"value\">");
        $this->_testFor_pattern( $text, $expect, "p5" );
        capture_reset_and_start();
        htmlp_checkbox( "name", "value", false );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 53, "test 3" );
        $this->_testFor_pattern( $text, $expect, "p6" );

        //
        // test 4
        //
        $text = html_checkbox( "name", "value", true );
        $expect = ("[ \n]+<input type=\"checkbox\" name=\"name\" "
                   ."value=\"value\" checked >");
        $this->_testFor_pattern( $text, $expect, "p7" );
        capture_reset_and_start();
        htmlp_checkbox( "name", "value", true );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 62, "test 4" );
        $this->_testFor_pattern( $text, $expect, "p8" );
    }

    function testhtml_radio() {
        //
        // test 1
        //
        $text = html_radio( "name", "value", "checked" );
        $expect = ("<input type=\"radio\" name=\"name\" value=\"value\" "
                   ."checked >");
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_radio( "name", "value", "checked" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 59, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );

        //
        // test 2
        //
        $text = html_radio( "name", "value", "" );
        $expect = ("<input type=\"radio\" name=\"name\" value=\"value\">");
        $this->_testFor_pattern( $text, $expect, "p3" );
        capture_reset_and_start();
        htmlp_radio( "name", "value", "" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 50, "test 2" );
        $this->_testFor_pattern( $text, $expect, "p4" );

        //
        // test 3
        //
        $text = html_radio( "name", "value", false );
        $expect = ("<input type=\"radio\" name=\"name\" value=\"value\">");
        $this->_testFor_pattern( $text, $expect, "p5" );
        capture_reset_and_start();
        htmlp_radio( "name", "value", false );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 50, "test 3" );
        $this->_testFor_pattern( $text, $expect, "p6" );

        //
        // test 4
        //
        $text = html_radio( "name", "value", true );
        $expect = ("<input type=\"radio\" name=\"name\" value=\"value\""
                   ." checked >");
        $this->_testFor_pattern( $text, $expect, "p7" );
        capture_reset_and_start();
        htmlp_radio( "name", "value", true );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 59, "test 4" );
        $this->_testFor_pattern( $text, $expect, "p8" );
    }
    
    function testhtml_textarea() {
        $text = html_textarea( "name", "columns", "rows", "wrap", 
                                 "maxlength", "value" );
        $expect = ("[ \n]+<textarea name=\"name\" cols=\"columns\" "
                   ."rows=\"rows\" wrap=\"wrap\" maxlength=\"maxlength\">value"
                   . "<\/textarea>");
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_textarea( "name", "columns", "rows", "wrap", "maxlength", 
                        "value");
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 103, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );
    }

    function testhtml_form_end() {
        $text = html_form_end( );
        $expect = "\n<\/form>";
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_form_end();
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 8, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );
    }
}

define_test_suite( __FILE__ );
?>
