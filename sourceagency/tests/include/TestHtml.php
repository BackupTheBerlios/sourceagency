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
# $Id: TestHtml.php,v 1.11 2002/01/28 02:11:11 riessen Exp $
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
        $this->_reset_capture();
    }
    function tearDown() {
        /* Called after each test method */
        capture_stop();
    }

    function _reset_capture() {
        capture_reset_text();
        capture_start();
    }

    function testhtml_link() {
        //
        // test 1
        //
        $actual = html_link('fubar',array( 'one' => 'what'),'hello world' );
        $expect = "<a href=\"fubar?one=what\">hello world</a>\n";
        $this->assertEquals( $expect, $actual );
        htmlp_link('fubar',array( 'one' => 'what'),'hello world' );
        $text = capture_stop_and_get();
        $this->_testFor_length( 41 );
        $this->assertEquals( $expect, $text );
        
        // 
        // test 2
        //
        $actual = html_link( 'snafu', "", 'goodbye cruel world' );
        $expect = "<a href=\"snafu\">goodbye cruel world</a>\n";
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_link( 'snafu', "", 'goodbye cruel world' );
        $text = capture_stop_and_get();
        $this->_testFor_length( 40 );
        $this->assertEquals( $expect, $text );

        //
        // test 3
        //
        $actual = html_link('fubar',array( 'one' => 'what the hell'),
                            'hello world' );
        $expect = "<a href=\"fubar?one=what+the+hell\">hello world</a>\n";
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_link('fubar',array( 'one' => 'what the hell'),
                  'hello world' );
        $text = capture_stop_and_get();
        $this->_testFor_length( 50 );
        $this->assertEquals( $expect, $text );

        //
        // test 4
        //
        $actual = html_link('fubar',array( 'one' => 'what+the+hell'),
                            'hello world' );
        $expect = "<a href=\"fubar?one=what%2Bthe%2Bhell\">hello world</a>\n";
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_link('fubar',array( 'one' => 'what+the+hell'),
                   'hello world' );
        $text = capture_stop_and_get();
        $this->_testFor_length( 54 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_anchor() {
        $actual = html_anchor( "hello world" );
        $expect = "<a name=\"hello world\"></a>\n";
        $this->assertEquals( $expect, $actual );

        // test the print variation of the same function
        htmlp_anchor( "hello world" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 27 );
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
        $this->_testFor_length( 85 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_form_action() {
        global $sess;
        //
        // test 1
        //
        $actual = html_form_action( "PHP_SELF", "query", "type" );
        $expect = "<form action=\"".$sess->self_url()."\" method=\"type\">";
        $this->assertEquals( $expect, $actual );
        htmlp_form_action( "PHP_SELF", "query", "type" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 30 +  strlen( $sess->self_url() ));
        $this->assertEquals( $expect, $text );

        // 
        // test 2
        //
        $actual = html_form_action( "file", "query", "type" );
        $expect = "<form action=\"file\" method=\"type\">";
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_form_action( "file", "query", "type" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 34 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_form_hidden() {
        $actual = html_form_hidden( "name", "value" );
        $expect = "<input type=\"hidden\" name=\"name\" value=\"value\">";
        $this->assertEquals( $expect, $actual );
        htmlp_form_hidden( "name", "value" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 47 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_select() {
        $actual = html_select( "name" );
        $expect = "<select name=\"name\">\n";
        $this->assertEquals( $expect, $actual );
        htmlp_select( "name" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 21 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_select_option() {
        //
        // test 1
        //
        $actual = html_select_option( "value", "selected", "text" );
        $expect = "<option selected value=\"value\">text\n";
        $this->assertEquals( $expect, $actual );
        htmlp_select_option( "value", "selected", "text" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 36 );
        $this->assertEquals( $expect, $text );

        //
        // test 2
        //
        $actual = html_select_option( "value", "", "text" );
        $expect = "<option value=\"value\">text\n";
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_select_option( "value", "", "text" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 27 );
        $this->assertEquals( $expect, $text );

        //
        // test 3
        //
        $actual = html_select_option( "value", false, "text" );
        $expect = "<option value=\"value\">text\n";
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_select_option( "value", false, "text" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 27 );
        $this->assertEquals( $expect, $text );

        //
        // test 4
        //
        $actual = html_select_option( "value", true, "text" );
        $expect = "<option selected value=\"value\">text\n";
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_select_option( "value", true, "text" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 36 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_select_end() {
        $actual = html_select_end();
        $expect = "</select>\n";
        $this->assertEquals( $expect, $actual );
        htmlp_select_end();
        $text = capture_stop_and_get();
        $this->_testFor_length( 10 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_input_text() {
        $actual = html_input_text( "name", "size", "maxlength", "value" );
        $expect = ( "<input type=\"text\" name=\"name\" size=\"size\" "
                    ."maxlength=\"maxlength\" value=\"value\">\n" );
        $this->assertEquals( $expect, $actual );
        htmlp_input_text( "name", "size", "maxlength", "value" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 80 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_form_submit() {
        $actual = html_form_submit( "value", "name" );
        $expect = "<input type=\"submit\" value=\"value\" name=\"name\">\n";
        $this->assertEquals( $expect, $actual );
        htmlp_form_submit( "value", "name" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 48 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_checkbox() {
        //
        // test 1
        //
        $actual = html_checkbox( "name", "value", "checked" );
        $expect = ("<input type=\"checkbox\" name=\"name\" value=\"value\" "
                   ."checked >");
        $this->assertEquals( $expect, $actual );
        htmlp_checkbox( "name", "value", "checked" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 58 );
        $this->assertEquals( $expect, $text );

        //
        // test 2
        //
        $actual = html_checkbox( "name", "value", "" );
        $expect = ("<input type=\"checkbox\" name=\"name\" value=\"value\">");
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_checkbox( "name", "value", "" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 49 );
        $this->assertEquals( $expect, $text );

        //
        // test 3
        //
        $actual = html_checkbox( "name", "value", false );
        $expect = ("<input type=\"checkbox\" name=\"name\" value=\"value\">");
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_checkbox( "name", "value", false );
        $text = capture_stop_and_get();
        $this->_testFor_length( 49 );
        $this->assertEquals( $expect, $text );

        //
        // test 4
        //
        $actual = html_checkbox( "name", "value", true );
        $expect = ("<input type=\"checkbox\" name=\"name\" value=\"value\""
                   ." checked >");
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_checkbox( "name", "value", true );
        $text = capture_stop_and_get();
        $this->_testFor_length( 58 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_radio() {
        //
        // test 1
        //
        $actual = html_radio( "name", "value", "checked" );
        $expect = ("<input type=\"radio\" name=\"name\" value=\"value\" "
                   ."checked >");
        $this->assertEquals( $expect, $actual );
        htmlp_radio( "name", "value", "checked" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 55 );
        $this->assertEquals( $expect, $text );

        //
        // test 2
        //
        $actual = html_radio( "name", "value", "" );
        $expect = ("<input type=\"radio\" name=\"name\" value=\"value\">");
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_radio( "name", "value", "" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 46 );
        $this->assertEquals( $expect, $text );

        //
        // test 3
        //
        $actual = html_radio( "name", "value", false );
        $expect = ("<input type=\"radio\" name=\"name\" value=\"value\">");
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_radio( "name", "value", false );
        $text = capture_stop_and_get();
        $this->_testFor_length( 46 );
        $this->assertEquals( $expect, $text );

        //
        // test 4
        //
        $actual = html_radio( "name", "value", true );
        $expect = ("<input type=\"radio\" name=\"name\" value=\"value\""
                   ." checked >");
        $this->assertEquals( $expect, $actual );
        $this->_reset_capture();
        htmlp_radio( "name", "value", true );
        $text = capture_stop_and_get();
        $this->_testFor_length( 55 );
        $this->assertEquals( $expect, $text );
    }
    
    function testhtml_textarea() {
        $actual = html_textarea( "name", "columns", "rows", "wrap", 
                                 "maxlength", "value" );
        $expect = ("<textarea cols=\"columns\" rows=\"rows\" name=\"name\" "
                   . "wrap=\"wrap\" maxlength=\"maxlength\">value"
                   . "</textarea>\n");
        $this->assertEquals( $expect, $actual );
        htmlp_textarea( "name", "columns", "rows", "wrap", "maxlength", 
                        "value");
        $text = capture_stop_and_get();
        $this->_testFor_length( 100 );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_form_end() {
        $actual = html_form_end( );
        $expect = "</form>\n";
        $this->assertEquals( $expect, $actual );
        htmlp_form_end();
        $text = capture_stop_and_get();
        $this->_testFor_length( 8 );
        $this->assertEquals( $expect, $text );
    }
}

define_test_suite( __FILE__ );
?>
