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
# $Id: TestHtml.php,v 1.21 2002/05/21 12:55:46 riessen Exp $
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

    // generic tester function for html_XXXX and htmlp_XXXX functions
    function _test_html_function( $name, $args, $exp_length ) {
        for ( $idx = 0; $idx < count($args); $idx++ ) {
            // first test the function that returns a value, i.e. html_XXXX
            $text = call_user_func_array( $name, $args[$idx] );
            // never use arguments for the function that are called 'text'
            // or 'msg', these are arguments to the _testFor_XXXX method
            $args2 = array_merge( array( 'text' => $text ), $args[$idx],
                                  array( 'msg' => "test $idx" ) );
            // call the _testFor_html_XXXX method of the class which
            // generates an expected value for the htmlp_XXXX function call
            // This method is normally defined in the UnitTest class.
            $expect = call_user_method_array('_testFor_'.$name, $this, $args2);
            $this->assertEquals( $expect, $text, "assert 1: test $idx" );
            // call the print variation of the function assuming that it's
            // called htmlp_XXXX instead of html_XXXX
            capture_reset_and_start();
            call_user_func_array( ereg_replace('^html_','htmlp_',$name), 
                                  $args[$idx] );
            $text = capture_stop_and_get();
            $this->_testFor_captured_length( $exp_length[$idx], "test $idx" );
            $this->assertEquals( $expect, $text, "assert 2: test $idx" );
        }
    }

    function testHtml_link() {
        //
        // a total of five tests
        //
        global $sess;
        $args=$this->_generate_records(array('url','paras','txt','css',),5);

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

        $args[0]['txt'] = 'hello world';
        $args[1]['txt'] = 'goodbye cruel world';
        $args[2]['txt'] = 'hello world';
        $args[3]['txt'] = 'hello world';
        $args[4]['txt'] = 'goodbye cruel world';

        $exp_length = array( 0=>49, 
                             1=>43 + strlen( $sess->self_url()), 
                             2=>56 + strlen( $sess->self_url()), 
                             3=>43, 
                             4=>98);

        $this->_test_html_function( 'html_link', $args, $exp_length );
    }

    function testHtml_anchor() {
        $args = array( 0 => array( 'name' => 'hello world' ));
        $this->_test_html_function( 'html_anchor', $args, array( 0=> 26 ) );
    }

    function testHtml_image() {
        $args=$this->_generate_records(array('file','border','width','height',
                                             'alt'),1);
        $this->_test_html_function( 'html_image', $args, array(0=>89));
    }

    function testHtml_form_action() {
        global $sess;
        //
        // 3 tests in total
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
        
        $this->_test_html_function( 'html_form_action', $args, $exp_length );
    }
    
    function testHtml_form_hidden() {
        $args=$this->_generate_records(array("name","value"), 1);

        $this->_test_html_function( 'html_form_hidden', $args, array(0=>56));
    }

    function testHtml_select() {
        $args = $this->_generate_records(array("name",'multi','size'),3);

        $args[0]['size'] = 0;
        $args[0]['multi'] = '';
        $args[1]['size'] = 23;
        $args[1]['multi'] = 0;
        $args[2]['size'] = 23;
        $args[2]['multi'] = 1;
        $exp_length = array( 0=> 36, 1=> 37, 2=> 46);

        $this->_test_html_function( 'html_select', $args, $exp_length );
    }

    function testHtml_select_option() {
        $args = $this->_generate_records( array("value","selected","txt"),4);
        $args[1]['selected'] = '';
        $args[1]['txt'] = '';
        $args[2]['selected'] = false;
        $args[2]['value'] = '';
        $args[3]['value'] = '';
        $args[3]['selected'] = true;
        $args[3]['txt'] = '';
        $exp_length = array( 0=>46,1=>32,2=>30,3=>34);
        
        $this->_test_html_function( 'html_select_option', $args, $exp_length );
    }

    function testHtml_select_end() {
        $text = html_select_end();
        $expect = "[ \n]+<\/select>\n";
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_select_end();
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 14, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );
    }

    function testHtml_input_text() {
        $text = html_input_text( "name", "size", "maxlength", "value" );
        $expect = ( "[ \n]+<input type=\"text\" name=\"name\" size=\"size\" "
                    ."maxlength=\"maxlength\" value=\"value\">" );
        $this->_testFor_pattern( $text, $expect, "p1" );
        htmlp_input_text( "name", "size", "maxlength", "value" );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 83, "test 1" );
        $this->_testFor_pattern( $text, $expect, "p2" );
    }

    function testHtml_form_submit() {
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

    function testHtml_checkbox() {
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

    function testHtml_radio() {
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
    
    function testHtml_textarea() {
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

    function testHtml_form_end() {
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
