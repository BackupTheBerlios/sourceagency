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
# $Id: TestBox.php,v 1.9 2002/02/01 08:40:52 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

include_once( "box.inc" );

class UnitTestBox
extends UnitTest
{
    var $box;

    function UnitTestBox( $name ) {
        $this->UnitTest( $name );
    }

    // this is called before each test method
    function setup() {
        capture_reset_and_start();
        $this->box = new box( "box_width", "frame_color", "frame_width",
                              "title_bgcolor", "title_font_color", 
                              "title_align", "body_bgcolor", "body_font_color",
                              "body_align" );
    }

    function tearDown() {
      // capture stop should be called by each method as required, but
      // in case a method forgets, call it after the tests
      capture_stop();
    }

    // the _testFor_XXXXX methods perform the regular expression matches
    // for the individual tests, the reason for splitting them away from
    // the tests is that some of them are reused.
    function _testFor_box_begin( $text ) {
        $pats =array( 0=>("<table border=0 cellspacing=0 cellpadding=0 "
                          . "bgcolor=\"frame_color\" width=\"box_width\" "
                          . "align=center>"),
                      1=>("<table border=0 cellspacing=\"frame_width\" "
                          . "cellpadding=3 align=\"center\" width=\"100%\">"));
        $this->_testFor_patterns( $text, $pats, 2 );
        
    }
    function _testFor_box_end( $text ) {
        $this->_testFor_pattern( $text, "<\/table>\n<\/td><\/tr><\/table>",
                                 "box end not found" );
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
    function _testFor_box_columns_begin( $text, $nr_cols ) {
        $ps=array( 0=>"<!-- table with " . $nr_cols . " columns -->",
                   1=>("<table border=\"0\" cellspacing=\"0\" cellpadding=\""
                       ."3\" align=\"center\" width=\"100%\" valign=\"top\">"),
                   2=>"<tr colspan=\"".$nr_cols."\">");
        $this->_testFor_patterns( $text, $ps, 3 );
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
    function _testFor_box_colspan( $text, $nr_cols, $align, $bgcolor,
                                   $insert_text ) {
        $this->assertRegexp( "/<!--[^-]+-->\n<td colspan=\"".$nr_cols."\" "
                             ."align=\"".$align."\" bgcolor=\"".$bgcolor."\">"
                             .$insert_text."<\/td>\n<!--[^-]+-->\n/",$text,
                             "box colspan mismatch" );
    }

    // the following the individual test methods
    function testBox_begin() {
        $this->box->box_begin();
        $text = capture_stop_and_get();
        $this->_testFor_length( 212 );
        $this->_testFor_box_begin( $text );
    }

    function testBox_end() {
        $this->box->box_end();
        $text = capture_stop_and_get();
        $this->_testFor_length( 49 );
        $this->_testFor_box_end( $text );
    }


    function testBox_title_begin() {
        $this->box->box_title_begin();
        $text = capture_stop_and_get();
        $this->_testFor_length( 78 );
        $this->_testFor_box_title_begin( $text );
    }


    function testBox_title_end() {
        $this->box->box_title_end();
        $text = capture_stop_and_get();
        $this->_testFor_length( 34 );
        $this->_testFor_box_title_end( $text );
    }

    function testBox_title() {
        $title = "box_title";
        $this->box->box_title($title);
        $text = capture_stop_and_get();
        $this->_testFor_length( 167 );

        $this->_testFor_box_title_begin( $text );
        $this->_testFor_box_title( $text, $title );
        $this->_testFor_box_title_end( $text );
    }

    function testBox_body_begin() {
        $this->box->box_body_begin();
        $text = capture_stop_and_get();
        $this->_testFor_length( 105 );
        $this->_testFor_box_body_begin( $text );
    }

    function testBox_body_end() {
        $this->box->box_body_end();
        $text = capture_stop_and_get();
        $this->_testFor_length( 40 );
        $this->_testFor_box_body_end( $text );
    }

    function testBox_body() {
        $body = "text for body";
        $this->box->box_body($body);
        $text = capture_stop_and_get();
        $this->_testFor_length( 196 );
        $this->_testFor_box_body_begin( $text );
        $this->_testFor_box_body( $text, $body);
        $this->_testFor_box_body_end( $text );
    }

    function testBox_full() {
        $title = "this is the title";
        $body = "this is the body";
        $this->box->box_full($title, $body);
        $text = capture_stop_and_get();
        $this->_testFor_length( 635 );
        $this->_testFor_box_begin( $text );
        $this->_testFor_box_title( $text, $title );
        $this->_testFor_box_body( $text, $body );
        $this->_testFor_box_end( $text );
    }

    function testBox_strip() {
        $title = "thsi is teh title";
        $this->box->box_strip( $title );
        $text = capture_stop_and_get();
        $this->_testFor_length( 436 );
        $this->_testFor_box_begin( $text );
        $this->_testFor_box_title( $text, $title );
        $this->_testFor_box_end( $text );
    }

    function testBox_colums_begin() {
        $nr_cols = "four or five";
        $this->box->box_columns_begin( $nr_cols );
        $text = capture_stop_and_get();
        $this->_testFor_length( 161 );
        $this->_testFor_box_columns_begin( $text, $nr_cols );
    }

    function testBox_column_start() {
        $align = "this is the align";
        $width = "and this is the width";
        $bg_color = "this is the background color";
        $this->box->box_column_start( $align, $width, $bg_color );
        $text = capture_stop_and_get();
        $this->_testFor_length( 126 );
        $this->_testFor_box_column_start( $text, $align, $width, $bg_color );

        capture_reset_and_start();
        $this->box->box_column_start( $align, $width );
        $text = capture_stop_and_get();
        $this->_testFor_length( 105 );
        $this->_testFor_box_column_start( $text, $align, $width );
    }

    function testBox_column_finish() {
        $this->box->box_column_finish();
        $text = capture_stop_and_get();
        $this->_testFor_length( 31 );
        $this->_testFor_box_column_finish( $text );
    }

    function testBox_columns_end() {
        $this->box->box_columns_end();
        $text = capture_stop_and_get();
        $this->_testFor_length( 47 );
        $this->_testFor_box_columns_end( $text );
    }

    function testBox_column() {
        $inserted_text = "this is the text that is being instered";
        $align = "this is the alignment";
        $width = "this is the width";
        $bgcolor = "the is the background color";
        $this->box->box_column($align, $width, $bgcolor, $inserted_text);
        $text = capture_stop_and_get();
        $this->_testFor_length( 195 );
        $this->_testFor_box_column_start( $text, $align, $width, $bgcolor );
        $this->assertRegexp( "/" . $inserted_text . "/", $text, 
                             "box column mismatch");
        $this->_testFor_box_column_finish( $text );
    }

    function testBox_next_row_of_columns() {
        $this->box->box_next_row_of_columns();
        $text = capture_stop_and_get();
        $this->_testFor_length( 50 );
        $this->_testFor_box_next_row_of_columns( $text );
    }

    function testBox_colspan() {
        $nr_cols = "number of columns";
        $align = "this is the alignment";
        $bgcolor = "this is the background color";
        $insert_text = "this is the inserted text";
        $this->box->box_colspan($nr_cols, $align, $bgcolor, $insert_text);
        $text = capture_stop_and_get();
        $this->_testFor_length( 262 );
        $this->_testFor_box_colspan( $text, $nr_cols, $align, $bgcolor, 
                                     $insert_text);
    }
}

define_test_suite( __FILE__ );

?>
