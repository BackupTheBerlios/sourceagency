<?php
// TestBox.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestBox.php,v 1.1 2001/10/23 16:36:12 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    ini_set('include_path', ini_get('include_path') . ':../../include' );
} 

include_once( "box.inc" );

class UnitTestBox
extends TestCase
{
    var $box;

    function UnitTestBox( $name ) {
        $this->TestCase( $name );
    }

    // this is called before each test method
    function setup() {
        capture_reset_text();
        capture_start();
        $this->box = new box( "box_width", "frame_color", "frame_width",
                              "title_bgcolor", "title_font_color", 
                              "title_align", "body_bgcolor", "body_font_color",
                              "body_align" );
    }
    
    // the _testFor_XXXXX methods perform the regular expression matches
    // for the individual tests, the reason for splitting them away from
    // the tests is that some of them are reused.
    function _testFor_box_begin( $text ) {
        $this->assertRegexp( "/<table border=0 cellspacing=0 cellpadding=0 "
                             . "bgcolor=\"frame_color\" width=\"box_width\" "
                             . "align=center>/", $text, 
                             "box begin (0) table line" );
        
        $this->assertRegexp( "/<table border=0 cellspacing=\"frame_width\""
                             . " cellpadding=3 align=\"center\" width=\""
                             . "100%\">/", $text, "box begin (1) table line" );
    }
    function _testFor_box_end( $text ) {
        $this->assertRegexp("/<\/table>\n<\/td><\/tr><\/table>/", $text,
                            "box end text mismatch" );
    }

    function _testFor_box_title_begin( $text ) {
        $this->assertRegexp("/<tr bgcolor=\"title_bgcolor\"><td align=\""
                            . "title_align\">\n/", $text,
                            "box title begin missing text" );
    }
    function _testFor_box_title_end( $text ) {
        $this->assertRegexp( "/<\/td><\/tr>/", $text, 
                             "box title end text mismatch" );
    }
    function _testFor_box_title( $text, $title ) {
        $this->assertRegexp("/<b>".$title."<\/b>/",$text,
                            "box title text mismatch");
    }
    function _testFor_box_body_begin( $text ) {
        $this->assertRegexp( "/<tr bgcolor=\"body_bgcolor\"><td align=\""
                             . "body_align\"><font color="
                             . "\"body_font_color\">/",$text, 
                             "box body begin text mismatch");
    }
    function _testFor_box_body_end( $text ) {
        $this->assertRegexp( "/<\/font><\/td><\/tr>/", $text, 
                             "box body end text mismatch");
    }
    function _testFor_box_body( $text, $body ) {
        $this->assertRegexp( "/<font color=\"body_font_color\">" . $body
                             . "<\/font>/", $text, "box body end");
    }
    function _thisFor_box_columns_begin( $text, $nr_cols ) {
        $this->assertRegexp( "/<!-- table with " . $nr_cols . " columns -->/",
                             $text, "(0) box columns begin mismatch" );
        $this->assertRegexp( "/<table border=\"0\" cellspacing=\"0\" "
                             ."cellpadding=\"3\" align=\"center\" width=\""
                             ."100%\" valign=\"top\">/", $text,
                             "(1) box colums begin mismatch" );
        $this->assertRegexp( "/<tr colspan=\"".$nr_cols."\">/", $text,
                             "(2) box colums begin mismatch" );
    }
    function _testFor_box_column_start($text,$align,$width,$bgcolor="#FFFFFF"){
        $this->assertRegexp( "/<td align=\"".$align."\" width=\"".$width
                             ."\" bgcolor=\"".$bgcolor."\">/", 
                             $text, "box column start mismatch" );
    }

    // the following the individual test methods
    function testBox_begin() {
        $this->box->box_begin();
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 212, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_begin( $text );
    }

    function testBox_end() {
        $this->box->box_end();
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 49, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_end( $text );
    }


    function testBox_title_begin() {
        $this->box->box_title_begin();
        capture_stop();
        
        $text = capture_text_get();
        $this->assertEquals( 78, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_title_begin( $text );
    }


    function testBox_title_end() {
        $this->box->box_title_end();
        capture_stop();
        
        $text = capture_text_get();
        $this->assertEquals( 34, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_title_end( $text );
    }

    function testBox_title() {
        $title = "box_title";
        $this->box->box_title($title);
        capture_stop();
        
        $text = capture_text_get();
        $this->assertEquals( 167, capture_text_length(), "Length mismatch" );

        $this->_testFor_box_title_begin( $text );
        $this->_testFor_box_title( $text, $title );
        $this->_testFor_box_title_end( $text );
    }

    function testBox_body_begin() {
        $this->box->box_body_begin();
        capture_stop();
        
        $text = capture_text_get();
        $this->assertEquals( 105, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_body_begin( $text );
    }

    function testBox_body_end() {
        $this->box->box_body_end();
        capture_stop();
        
        $text = capture_text_get();
        $this->assertEquals( 40, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_body_end( $text );
    }

    function testBox_body() {
        $body = "text for body";
        $this->box->box_body($body);
        capture_stop();
        
        $text = capture_text_get();
        $this->assertEquals( 196, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_body_begin( $text );
        $this->_testFor_box_body( $text, $body);
        $this->_testFor_box_body_end( $text );
    }

    function testBox_full() {
        $title = "this is the title";
        $body = "this is the body";
        $this->box->box_full($title, $body);
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 635, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_begin( $text );
        $this->_testFor_box_title( $text, $title );
        $this->_testFor_box_body( $text, $body );
        $this->_testFor_box_end( $text );
    }

    function testBox_strip() {
        $title = "thsi is teh title";
        $this->box->box_strip( $title );
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 436, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_begin( $text );
        $this->_testFor_box_title( $text, $title );
        $this->_testFor_box_end( $text );
    }

    function testBox_colums_begin() {
        $nr_cols = "four or five";
        $this->box->box_columns_begin( $nr_cols );
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 161, capture_text_length(), "Length mismatch" );
        $this->_thisFor_box_columns_begin( $text, $nr_cols );
    }

    function testBox_column_start() {
        $align = "this is the align";
        $width = "and this is the width";
        $bg_color = "this is the background color";
        $this->box->box_column_start( $align, $width, $bg_color );
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 126, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_column_start( $text, $align, $width, $bg_color );

        capture_reset_text();
        capture_start();
        $this->box->box_column_start( $align, $width );
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 105, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_column_start( $text, $align, $width );
    }
}

define_test_suite( __FILE__ );

?>
