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
# $Id: TestHtml.php,v 1.20 2002/05/21 09:51:04 riessen Exp $
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
        // a total of five tests
        //
        global $sess;
        $args=$this->_generate_records(array('url','paras','text','css',),5);
        $args[0]['url'] = 'fubar';           $args[0]['css'] = '';
        $args[1]['url'] = 'PHP_SELF';        $args[1]['css'] = '';
        $args[2]['url'] = 'PHP_SELF';        $args[2]['css'] = 'css';
        $args[3]['url'] = 'fubar';           $args[3]['css'] = 'css';
        $args[4]['url'] = 'snafu and fubar'; $args[4]['css'] = 'css';

        $args[0]['paras'] = array( 'one' => 'what' );
        $args[1]['paras'] = '';
        $args[2]['paras'] = array( 'one' => 'what the hell');
        $args[3]['paras'] = '';
        $args[4]['paras'] = array( 'one'=>'what+the+hell','two'=>'argument 2');

        $args[0]['text'] = 'hello world';
        $args[1]['text'] = 'goodbye cruel world';
        $args[2]['text'] = 'hello world';
        $args[3]['text'] = 'hello world';
        $args[4]['text'] = 'goodbye cruel world';

        $exp_length = array( 0=>49, 
                             1=>43 + strlen( $sess->self_url()), 
                             2=>56 + strlen($sess->self_url()), 
                             3=>43, 
                             4=>98);

        for ( $idx = 0; $idx < count($args); $idx++ ) {
            if ( $args[$idx]['css'] ) {
                $actual = call_user_func_array( 'html_link',$args[$idx] );
            } else {
                $actual = html_link($args[$idx]['url'], $args[$idx]['paras'], 
                                     $args[$idx]['text']);
            }
            $expect = $this->_testFor_html_link( $actual, $args[$idx]['url'], 
                                     $args[$idx]['paras'], $args[$idx]['text'],
                                     $args[$idx]['css'], "test $idx");
            $this->assertEquals( $expect, $actual, "assert1: test $idx" );
            capture_reset_and_start();
            if ( $args[$idx]['css'] ) {
                call_user_func_array( 'htmlp_link',$args[$idx] );
            } else {
                htmlp_link($args[$idx]['url'], $args[$idx]['paras'], 
                                                        $args[$idx]['text']);
            }
            $text = capture_stop_and_get();
            $this->_testFor_captured_length($exp_length[$idx],"test $idx");
            $this->assertEquals( $expect, $text, "assert2: test $idx" );
        }
    }

    function _testFor_html_anchor( $text, $name, $msg = '') {
        $str = '<a name="'. $name . '"></a>';
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                     '_testFor_html_anchor'.($msg == '' ? '': ' ('.$msg.')'));
        return $str;
    }

    function testhtml_anchor() {
        $name = 'hello world';
        $actual = html_anchor( $name );
        $expect = $this->_testFor_html_anchor( $actual, $name );
        $this->assertEquals( $expect, $actual );

        // test the print variation of the same function
        htmlp_anchor( $name );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 26, "test 1" );
        $this->assertEquals( $expect, $text );
    }

    function _testFor_html_image( $text, $file, $border, $width, $height,
                                  $alt, $msg = '' ) {
        $str = ( '<img src="images/'.$file.'" border="'.$border.'"'
                 .' width="'.$width.'" height="'.$height
                 .'" alt="'.$alt.'">' );
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                           "_testFor_html_image" 
                           . ($msg == '' ? '' : ' (' . $msg . ')'));
        return $str;
    }
    function testhtml_image() {
        $args=$this->_generate_records(array('file','border','width','height',
                                             'alt'),1);
        $actual = call_user_func_array( 'html_image', $args[0] );
        $expect = $this->_testFor_html_image( $actual,$args[0]["file"], 
                                        $args[0]["border"], $args[0]["width"], 
                                        $args[0]["height"], $args[0]["alt"]);
        $this->assertEquals( $expect, $actual );
        
        // test the print version of the function
        call_user_func_array( 'htmlp_image', $args[0] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 89, "test 1" );
        $this->assertEquals( $expect, $text );
    }

    function testhtml_form_action() {
        global $sess;
        //
        // test 1
        //
        $args = $this->_generate_records(array("file","query","method"),3);
        $args[0]["file"] = 'PHP_SELF';
        $args[1]["file"] = 'file';
        $args[2]["file"] = 'fubar';
        $args[0]["query"] = 'query';
        $args[1]["query"] = '';
        $args[2]["query"] = array( 'one' => 'two three four' );
        $args[0]["method"] = 'type';
        $args[1]["method"] = 'POST';
        $args[2]["method"] = 'GET';

        $exp_length = array( 0 => 31 +  strlen( $sess->self_url()), 
                             1 => 35, 2 => 54 );
        
        for ( $idx = 0; $idx < count( $args ); $idx++ ) {
            $text = html_form_action( $args[$idx]["file"],$args[$idx]["query"],
                                      $args[$idx]["method"] );
            $expect = $this->_testFor_html_form_action( $text,
                                      $args[$idx]["file"],$args[$idx]["query"],
                                      $args[$idx]["method"], "test $idx" );
            $this->assertEquals( $expect, $text, "assert1: test $idx" );
            capture_reset_and_start();
            htmlp_form_action( $args[$idx]["file"],$args[$idx]["query"],
                                                       $args[$idx]["method"]);
            $text = capture_stop_and_get();
            $this->_testFor_captured_length( $exp_length[$idx], "test $idx");
            $this->assertEquals( $expect, $text, "assert2: test $idx" );
        }
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

    function testHtml_form_image() {
        $expect = "\n".'   <input type="image" src="file" alt="alternative">';
        $text = html_form_image( "file", "alternative" );
        $this->assertEquals( $expect, $text, "test 1" );
        htmlp_form_image( "file", "alternative" );
        $text = capture_stop_and_get();
        $this->assertEquals( $expect, $text, "test 2" );
    }

    function testHtml_form_reset() {
        $text[0] = html_form_reset();
        $text[1] = html_form_reset( "Reset" );
        capture_reset_and_start();
        htmlp_form_reset();
        $text[2] = capture_stop_and_get();
        capture_reset_and_start();
        htmlp_form_reset("Reset");
        $text[3] = capture_stop_and_get();
        $expect = "\n".'   <input type="reset" value="Reset">';

        for ( $idx = 0; $idx < 4; $idx++ ) {
            $this->assertEquals( $expect, $text[$idx], "test(E) $idx" );
            for ( $jdx = 0; $jdx < 4; $jdx++ ) {
                $this->assertEquals( $text[$idx], $text[$jdx], 
                                     "test $idx, $jdx" );
            }
        }
    }
      
    function testHtml_input_password() {
        $exp1 = ( "\n".'   <input type="password" name="name'
                  .'" size="size" maxlength="maxlength" value="">');
        $exp2 = ( "\n".'   <input type="password" name="name'
                  .'" size="size" maxlength="maxlength" value="value">');

        $text[0] = html_input_password( "name", "size", "maxlength" );
        $text[1] = html_input_password( "name", "size", "maxlength", "value" );

        capture_reset_and_start();
        htmlp_input_password( "name", "size", "maxlength" );
        $text[2] = capture_stop_and_get();

        capture_reset_and_start();
        htmlp_input_password( "name", "size", "maxlength", "value" );
        $text[3] = capture_stop_and_get();

        $this->assertEquals( $exp1, $text[0], "test 1" );
        $this->assertEquals( $exp1, $text[2], "test 2" );
        $this->assertEquals( $exp2, $text[1], "test 3" );
        $this->assertEquals( $exp2, $text[3], "test 4" );
        $this->assertEquals( $text[0], $text[2], "test 5" );
        $this->assertEquals( $text[1], $text[3], "test 6" );
    }

}

define_test_suite( __FILE__ );
?>
