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
# $Id: TestHtml.php,v 1.22 2002/05/22 11:50:33 riessen Exp $
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
    }
    function tearDown() {
    }

    // generic tester function for html_XXXX and htmlp_XXXX functions.
    // This assumes that a method exists on this object that is 
    // called '_testFor_$name' which takes the same arguments as the
    // function to be tested plus two other parameters (at the beginning
    // and end of the argument list). This is called to generated the exact
    // desired output for the function being tested.
    function _test_html_function( $name, $args, $exp_length ) {
        $test_for_func = '_testFor_' . $name;
        $print_func = ereg_replace('^html_','htmlp_',$name);
        $no_chance = 0;

        if ( !function_exists( $name ) && ($no_chance=1) ) {
            $this->assertEquals(1,0,"Function not defined '$name' "
                                ."(_test_html_function)");
        }
        if ( !function_exists( $print_func ) && ($no_chance=1) ) {
            $this->assertEquals(1,0,"Print function not defined '$print_func' "
                                ."(_test_html_function)");
        }
        if ( !method_exists( $this, $test_for_func ) && ($no_chance=1) ) {
            $this->assertEquals(1,0,"Test for function not defined "
                                ."'$test_for_func' on this object "
                                ."(_test_html_function)");
        }

        // no_chance flag is set if one or more of the required functions 
        // are not defined, then there is no point in continuing
        if ( $no_chance ) {
            return;
        }

        for ( $idx = 0; $idx < count($args); $idx++ ) {
            // first test the function that returns a value, i.e. html_XXXX
            $text = call_user_func_array( $name, $args[$idx] );
            // never use arguments for the function that are called 'text'
            // or 'msg', these are arguments to the _testFor_XXXX method
            $args2 = array_merge( array( 'text' => &$text ), 
                                  $args[$idx],
                                  array( 'msg' => "test $idx" ) );
            // call the _testFor_html_XXXX method of the class which
            // generates an expected value for the htmlp_XXXX function call
            // This method is normally defined in the UnitTest class.
            $expect = $this->_call_method( $test_for_func, $args2 );
            $this->assertEquals( $expect, $text, "assert 1: test $idx" );
            // call the print variation of the function assuming that it's
            // called htmlp_XXXX instead of html_XXXX
            capture_reset_and_start();
            call_user_func_array( $print_func, $args[$idx] );
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
        $args = array( 0 => array() );
        $exp_length = array( 0 => 14 );
        $this->_test_html_function( 'html_select_end', $args, $exp_length );
    }

    function testHtml_input_text() {
        $args=$this->_generate_records( array( "name", "size", "maxlength",
                                               "value" ), 1 );
        $exp_length = array( 0 => 91 );
        $this->_test_html_function( 'html_input_text', $args, $exp_length );
    }

    function testHtml_form_submit() {
        $args = $this->_generate_records( array( "value", "name" ), 3 );
        $exp_length = array( 0=>55, 1=>41, 2=>34 );
        $args[1]['name'] = '';
        $args[2]['value'] = '';
        $args[2]['name'] = '';
        $this->_test_html_function( 'html_form_submit', $args, $exp_length );
    }

    function testHtml_checkbox() {
        $args=$this->_generate_records( array("name","value","checked"), 5 );
        $args[1]['checked'] = '';
        $args[2]['checked'] = false;
        $args[3]['checked'] = true;
        $args[4]['checked'] = 'checked';
        $exp_length=array(0=>66,1=>57,2=>57,3=>66,4=>66);
        
        $this->_test_html_function('html_checkbox', $args, $exp_length );
    }

    function testHtml_radio() {
        $args=$this->_generate_records( array("name","value","checked"), 5 );
        $args[1]['checked'] = '';
        $args[2]['checked'] = false;
        $args[3]['checked'] = true;
        $args[4]['checked'] = 'checked';
        $exp_length=array(0=>63,1=>54,2=>54,3=>63,4=>63);
        
        $this->_test_html_function('html_radio', $args, $exp_length );
    }

    function testHtml_textarea() {
        $args = $this->_generate_records(array("name","columns","rows", 
                                               "wrap","maxlength","value"),1);
        $exp_length=array( 0=>115 );
        $this->_test_html_function( 'html_textarea', $args, $exp_length );
    }

    function testHtml_form_end() {
        $args = array( 0 => array() );
        $exp_length = array( 0 => 8 );
        $this->_test_html_function( 'html_form_end', $args, $exp_length );
    }

    function testHtml_form_image() {
        $args = $this->_generate_records( array( "name", "alt" ), 1 );
        $exp_length = array( 0 => 49 );
        $this->_test_html_function( 'html_form_image', $args, $exp_length );
    }

    function testHtml_form_reset() {
        $args=$this->_generate_records( array( 'reset' ), 2 );
        $args[1]['reset'] = '';
        $exp_length = array( 0=>40, 1=>33 );
        $this->_test_html_function( 'html_form_reset', $args, $exp_length );
    }
      
    function testHtml_input_password() {
        $args = $this->_generate_records( array("name", "size", "password",
                                                "value"), 1);
        $exp_length = array( 0 => 94 );
        $this->_test_html_function( 'html_input_password', $args, $exp_length);
    }

}

define_test_suite( __FILE__ );
?>
