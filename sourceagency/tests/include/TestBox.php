<?php
// TestBox.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestBox.php,v 1.2 2001/10/24 11:16:57 riessen Exp $

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
                             "(0) box begin mismatch" );
        
        $this->assertRegexp( "/<table border=0 cellspacing=\"frame_width\""
                             . " cellpadding=3 align=\"center\" width=\""
                             . "100%\">/", $text, 
                             "(1) box begin mismatch" );
    }
    function _testFor_box_end( $text ) {
        $this->assertRegexp("/<\/table>\n<\/td><\/tr><\/table>/", $text,
                            "box end mismatch" );
    }

    function _testFor_box_title_begin( $text ) {
        $this->assertRegexp("/<tr bgcolor=\"title_bgcolor\"><td align=\""
                            . "title_align\">\n/", $text,
                            "box title begin mismatch" );
    }
    function _testFor_box_title_end( $text ) {
        $this->assertRegexp( "/<\/td><\/tr>/", $text,"box title end mismatch");
    }
    function _testFor_box_title( $text, $title ) {
        $this->assertRegexp("/<b>".$title."<\/b>/",$text,"box title mismatch");
    }
    function _testFor_box_body_begin( $text ) {
        $this->assertRegexp( "/<tr bgcolor=\"body_bgcolor\"><td align=\""
                             . "body_align\"><font color="
                             . "\"body_font_color\">/",$text, 
                             "box body begin mismatch");
    }
    function _testFor_box_body_end( $text ) {
        $this->assertRegexp( "/<\/font><\/td><\/tr>/", $text, 
                             "box body end mismatch");
    }
    function _testFor_box_body( $text, $body ) {
        $this->assertRegexp( "/<font color=\"body_font_color\">" . $body
                             . "<\/font>/", $text, "box body mismatch");
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
    function _testFor_box_column_finish( $text ) {
      $this->assertRegexp( "/<\/td>\n/", $text, 
                           "box column finish mismatch");
    }
    function _testFor_box_columns_end( $text ) {
        $this->assertRegexp( "/<\/tr>\n<\/table>\n/", $text,
                             "box columns end mismatch" );
    }
    function _testFor_box_next_row_of_columns( $text ) {
        $this->assertRegexp( "/<\/tr>\n<!--[^-]+-->\n<tr>\n/",
                             $text, "box next row of columns mismatch" );
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

    function testBox_column_finish() {
        $this->box->box_column_finish();
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 31, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_column_finish( $text );
    }

    function testBox_columns_end() {
        $this->box->box_columns_end();
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 47, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_columns_end( $text );
    }

    function testBox_column() {
        $inserted_text = "this is the text that is being instered";
        $align = "this is the alignment";
        $width = "this is the width";
        $bgcolor = "the is the background color";
        $this->box->box_column($align, $width, $bgcolor, $inserted_text);
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 195, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_column_start( $text, $align, $width, $bgcolor );
        $this->assertRegexp( "/" . $inserted_text . "/", $text, 
                             "box column mismatch");
        $this->_testFor_box_column_finish( $text );
    }

    function testBox_next_row_of_columns() {
        $this->box->box_next_row_of_columns();
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 50, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_next_row_of_columns( $text );
    }

    function _testFor_box_colspan( $text, $nr_cols, $align, $bgcolor,
                                   $insert_text ) {
        $this->assertRegexp( "/<!--[^-]+-->\n<td colspan=\"".$nr_cols."\" "
                             ."align=\"".$align."\" bgcolor=\"".$bgcolor."\">"
                             .$insert_text."<\/td>\n<!--[^-]+-->\n/",$text,
                             "box colspan mismatch" );
    }

    function testBox_colspan() {
        $nr_cols = "number of columns";
        $align = "this is the alignment";
        $bgcolor = "this is the background color";
        $insert_text = "this is the inserted text";
        $this->box->box_colspan($nr_cols, $align, $bgcolor, $insert_text);
        capture_stop();

        $text = capture_text_get();
        $this->assertEquals( 262, capture_text_length(), "Length mismatch" );
        $this->_testFor_box_colspan( $text, $nr_cols, $align, $bgcolor, 
                                     $insert_text);
    }
}

define_test_suite( __FILE__ );

?>
