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
# Base class for all Unit Tests.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: unit_test.php,v 1.21 2002/06/04 10:57:52 riessen Exp $
#
######################################################################


// common base class for all Unit Tests, supplies some common methods
// This class should not be directly instantiated ....
class UnitTest
extends TestCase
{
    // REFACTOR: the $text and $msg arguments to the _testFor_... and
    // REFACTOR: _checkFor_... methods could be removed and replaced
    // REFACTOR: by object variables! But this would require modiying 
    // REFACTOR: every single test method :-(

    var $p_regexp_html_comment = "<!--[^->]*-->";

    // this is set to indicate that the meaning of a test should be
    // reversed, e.g. _testFor_pattern will check that the pattern 
    // doesn't appear in the given text. 
    // Only methods that begin with _testFor_... (or testFor_...) can
    // be reversed. This is because a _checkFor_... method is an aggregation
    // of _testFor_ methods and to test whether a checkFor would fail,
    // you only need to call one of the testFor methods.
    var $reverse_test = false;
    
    // this is the text and message variables containing the string on 
    // which all tests and the message to be printed in case of failure
    // will be performed. At this stage only those methods that do not
    // begin with an underscore '_', use this variable
    var $test_text = '';
    var $test_msg = '';
    var $_msg_stack = array();

    function UnitTest( $name = "" ) {
        $this->TestCase( $name );
    }

    function set_text( &$text ) {
        $this->test_text = $text;
    }
    function &get_text() {
        return $this->test_text;
    }
    function set_msg( $msg ) {
        $this->test_msg = &$msg;
    }
    function push_msg( $msg ) {
        $this->_msg_stack[ count( $this->_msg_stack ) ] = $this->test_msg;
        $this->test_msg = $msg;
    }
    function pop_msg() {
        $this->test_msg = $this->_msg_stack[ count( $this->_msg_stack ) - 1 ];
        unset( $this->_msg_stack[ count( $this->_msg_stack ) - 1 ] );
    }

    function reverse_next_test() {
        // reverse the meaning of the next test, the test will automatically
        // reset the flag which indicates that a test has been reversed
        $this->reverse_test = true;
    }

    // The following two could be defined in phpunit.php
    function assertNotRegexp( $regexp, $actual, 
                              $message="assert not regexp failed" ) {
        if ( preg_match( $regexp, $actual ) ) {
            $this->failNotEquals( $regexp, $actual, "*NOT* pattern",$message );
        }
    }
    function assertNotEquals( $expected, $actual, $message=0 ) {
        if ( $expected == $actual ) {
            $this->failNotEquals($expected, $actual, "*NOT* equal", $message);
        }
    }
    
    // These are the base test functions on which all other test functions
    // should build on
    function _check_length( $exp, $act, $msg = '' ) {
        if ( $this->reverse_test ) {
            $this->assertNotEquals( $exp, $act, $msg . ' (*Length Match*)' );
        } else {
            $this->assertEquals( $exp, $act, $msg . ' (Length Mismatch)' );
        }
        $this->reverse_test = false;
    }

    function _testFor_pattern( $pattern ) {
        if ( $this->reverse_test ) {
            $this->assertNotRegexp( "/" . $pattern . "/", &$this->test_text, 
                                    $this->test_msg . ' (*Pattern Found*)');
        } else {
            $this->assertRegexp( "/" . $pattern . "/", &$this->test_text, 
                                 $this->test_msg . ' (Pattern not Found)');
        }
        $this->reverse_test = false;
    }

    function _testFor_patterns( $pattern_array, $check_size = -1 ) {
        $orig_rev_val = $this->reverse_test;

        reset( $pattern_array );
        if ( $check_size > 0 ) {
            if ( $orig_rev_val ) {
                $this->assertNotEquals( $check_size, count( $pattern_array ), 
                                        $this->test_msg 
                                        . ' (pattern count mismatch)' );
            } else {
                $this->assertEquals( $check_size, count( $pattern_array ), 
                                     $this->test_msg 
                                     . ' (pattern count mismatch)' );
            }
        }

        while ( list( $key, $val ) = each( $pattern_array ) ) {
            $this->reverse_test = $orig_rev_val;
            $this->push_msg( $this->test_msg.(' (Key "'.$key.'")' ) );
            $this->_testFor_pattern( $val );
            $this->pop_msg();
        }
        $this->reverse_test = false;
    }

    // this method has can be given one, two or three arguments when called.
    // If a single argument is passed, then it is assumed to be the length
    // required, not a string. The two argument call assumes that the first
    // argument is the string and the second is the expected length of this
    // string.
    function _testFor_string_length( $str, $len = false, $msg = '' ) {
        if ( is_bool( $len ) ) {
            // $len has not been set, therefore assume that $str is the
            // required length and that $test_text should be used as the
            // string.
            $this->_testFor_string_length( &$this->test_text, $str, 
                                                        &$this->test_msg );
        } else {
            $this->_check_length( $len, strlen( $str ), $msg . ' (string)');
        }
    }

    // function that can be called if a test is to be completed but
    // has yet to be completed
    function _test_to_be_completed( $msg = false ) {
      if ( defined( "PHPUNIT_TO_BE_COMPLETED" ) ) {
        $this->fail( '<font color="red">'. PHPUNIT_TO_BE_COMPLETED
                     .( $msg ? ' ('.$msg.')' : '') . '</font>' );
      } else {
        $this->fail( '<font color="red">Test has not been completed'
                     .( $msg ? ' ('.$msg.')' : '') . '</font>' );
      }
    }

    // passed a mock_db_configure object, this method ensures nothing failed
    // while using the database objects.
    function _check_db( &$db_config ) {
        $this->assert(!$db_config->did_db_fail(),$db_config->error_message());
    }

    function &_generate_records( $keynames = array(), $count = 0 ) {
        $rVal = array();
        for ( $idx = 0; $idx < $count; $idx++ ) {
            $rVal[$idx] = $this->_generate_array( $keynames, $idx );
        }
        return $rVal;
    }
    function &_generate_array( $keynames = array(), $postfix = 0 ) {
        $rVal = array();
        foreach ( $keynames as $val ) {
            $rVal[$val] = $val . "_" . $postfix;
        }
        return $rVal;
    }

    // because version_compare is only available after version 4.1.0,
    // define our own! This only returns true if v1 is greater than v2
    function v_gt( $v1, $v2 ) {
        $a_v1 = explode( ".", $v1 );
        $a_v2 = explode( ".", $v2 );
        return ( $a_v1[0] > $a_v2[0] 
                   || ($a_v1[0] == $a_v2[0] && $a_v1[1] > $a_v2[1])
                   || ($a_v1[0] == $a_v2[0] && $a_v1[1] == $a_v2[1] 
                                                     && $a_v1[2] > $a_v2[2] ));
    }

    // this is a wrapper function because versions 4.0.6 and 4.1.X have
    // different ways of calling methods on objects, and to avoid deprecation
    // warnings from appearing in captured output, this wrapper is required.
    // Before version 4.0.4p1 is the complete functionality not available!
    function _call_method( $method, $args = array(), $obj = 0 ) {
        if ( $this->v_gt( "4.0.5", phpversion() ) ) {
            /* less than 4.0.5 */
            $this->assert(false,"This version of php does not support "
                          . "class introspection (".phpversion().")");
            return;
        }

        if ( !$obj ) {
            // putting this in argument list causes a parse error
            $obj = &$this;
        }

        if ( !method_exists( $obj, $method ) ) {
            $this->assert(false,"Method '$method' does not exist on class '"
                          .get_class($obj)."'");
            return;
        }

        if ( $this->v_gt( "4.1.0", phpversion() ) ) {
            /* between 4.0.5(inclusive) and 4.1.0 */
            return call_user_method_array( $method, &$obj, &$args );
        } else {
            /* greater or equal than 4.1.0 */
            return call_user_func_array( array( &$obj, $method ), &$args );
        }
    }

    //
    // The following are methods to test for common html code
    //
    function _to_regexp( $str ) {
        foreach( array( '[',']','/' ) as $char ) {
            $str = ereg_replace( "[$char]", "\\$char", $str );
        }
        foreach( array( '&','(',')','+','?','.','*') as $char ) {
            $str = ereg_replace( "[$char]", "[$char]", $str );
        }
        return $str;
    }
    // the defaults values for the parameters to these box testers can 
    // be used if the box object is initialised as:
    //     new box( "box_width", "frame_color", "frame_width",
    //              "title_bgcolor", "title_font_color", 
    //              "title_align", "body_bgcolor", "body_font_color",
    //              "body_align" );
    function _create_default_box() {
        return new box( "box_width", "frame_color", "frame_width",
                        "title_bgcolor", "title_font_color", 
                        "title_align", "body_bgcolor", "body_font_color",
                        "body_align" );
    }

    // _checkFor_ methods are intended to be a combination of several
    // _testFor_ methods and allow for the testing of often occuring
    // _testFor_ sequences
    
    function _checkFor_a_box( $title, $title_template='%s' ) {
        $this->push_msg( $this->test_msg . " (_checkFor_a_box)");
        $this->_testFor_box_begin( );
        $this->_testFor_box_title(
            sprintf( $title_template, $GLOBALS['t']->translate($title)));
        $this->_testFor_box_body_begin( );
        $this->_testFor_box_body_end( );
        $this->_testFor_box_end( );
        $this->pop_msg();
    }

    function _checkFor_a_form( $file='PHP_SELF',$query='',$method='POST'){
        $this->push_msg( $this->test_msg . " (_checkFor_a_form)");
        $this->_testFor_html_form_action($file, $query, $method);
        $this->_testFor_html_form_end();
        $this->pop_msg();
    }

    function _checkFor_column_titles( $col_names,$align='right', $width='30%',
                                     $bgcolor='',$title_template='<b>%s</b>') {
        foreach ( $col_names as $val ) {
            $str = sprintf( $title_template, $GLOBALS['t']->translate($val));
            $this->push_msg( "$this->test_msg (Column Title: $val)" );
            $this->_checkFor_column_values( array($str),$align, $width, 
                                             $bgcolor );
            $this->pop_msg();
        }
    }

    function _checkFor_column_values( $values,$align='left',$width='70%',
                                       $bgcolor='' ) {
        $this->push_msg( $this->test_msg . " (_checkFor_column_values)");
        foreach ( $values as $val ) {
            $this->_testFor_box_column($align, $width, $bgcolor, $val );
        }
        $this->pop_msg();
    }

    function _checkFor_submit_preview_buttons( ) {
        global $t;
        $this->push_msg($this->test_msg." (_checkFor_submit_preview_buttons)");
        $this->_testFor_html_form_submit($t->translate('Preview'),'preview');
        $this->_testFor_html_form_submit($t->translate('Submit'),'submit');
        $this->pop_msg();
    }

    function _checkFor_box_full( $title, $body_text ) {
        $this->push_msg( $this->test_msg . " (_checkFor_box_full)");
        $this->_testFor_box_begin( );
        $this->_testFor_box_title( $title );
        $this->_testFor_box_body_begin();
        $this->_testFor_box_body( $body_text );
        $this->_testFor_box_body_end();
        $this->_testFor_box_end( );
        $this->pop_msg();
    }

    function _checkFor_columns( $nr_cols, $valign='top' ) {
        $this->push_msg( $this->test_msg . " (_checkFor_columns)");
        $this->_testFor_box_columns_begin($nr_cols, $valign );
        $this->_testFor_box_columns_end( );
        $this->pop_msg();
    }

    function _testFor_html_link( $addr='PHP_SELF', $paras=array(), 
                                  $link_text='', $css='' ) {
        global $sess;
        $this->push_msg( $this->test_msg . " (_testFor_html_link)");
        $str = sprintf('<a href="%%s" class="%s">%s</a>',$css,$link_text);
        
        $str = sprintf( $str, ($addr == 'PHP_SELF' ? $sess->self_url()
                                                   : $sess->url($addr))
                              . ((is_array($paras) && isset($paras) 
                                 && !empty($paras)) ? $sess->add_query($paras) 
                                 : "" ));

        $this->_testFor_pattern( $this->_to_regexp($str) );
        $this->pop_msg();
        return $str;
    }
    
    function _testFor_html_form_action( $file = 'PHP_SELF',
                                         $query='', $method='POST' ) {
        global $sess;
        $this->push_msg( $this->test_msg . " (_testFor_html_form_action)");
        
        $str = sprintf( '%s<form action="%s" method="%s">', "\n",
                        ($file == 'PHP_SELF' ? $sess->self_url() 
                                             : $sess->url( $file ))
                        .$sess->add_query( $query ), $method );

        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return ($str);
    }

    function _testFor_html_anchor( $name ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_anchor)");
        $str = '<a name="'. $name . '"></a>';
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_image( $file, $border, $width, $height, $alt) {
        $this->push_msg( $this->test_msg . " (_testFor_html_image)");
        $str = ( '<img src="images/'.$file.'" border="'.$border.'"'
                 .' width="'.$width.'" height="'.$height
                 .'" alt="'.$alt.'">' );
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_form_hidden( $name, $value ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_form_hidden)");
        $str = sprintf('%s    <input type="hidden" name="%s" value="%s">',
                       "\n", $name, $value );
        $this->_testFor_pattern( $this->_to_regexp($str) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_select( $name, $multi=0, $size=0 ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_select)");
        $str = sprintf("\n".'   <select name="%s" size="%s"%s>'."\n",
                       $name, $size, ($multi ? ' multiple' : ''));
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_select_option( $value, $selected, $txt ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_select_option)");
        $str = sprintf("\n".'      <option %svalue="%s">%s'."\n",
                       ($selected ? 'selected ':''), $value, $txt );
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_select_end() {
        $this->push_msg( $this->test_msg . " (_testFor_html_select_end)" );
        $str = "\n   </select>\n";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_input_text( $name, $size, $maxlength,$value='' ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_input_text)" );
        $str = sprintf( "\n".'   <input type="text" name="%s" size="%s" '
                        .'maxlength="%s" value="%s">', $name, $size,
                        $maxlength, $value );
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_form_submit( $value, $name='') {
        $this->push_msg( $this->test_msg . " (_testFor_html_form_submit)" );
        $str = sprintf( "\n".'   <input type="submit" value="%s"%s>',
                        $value, ($name ? ' name="'.$name.'"' : ''));
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_checkbox( $name, $value, $checked ){
        $this->push_msg( $this->test_msg . " (_testFor_html_checkbox)" );
        $str = sprintf( "\n".'   <input type="checkbox" name="%s" value="%s"'
                        .'%s', $name, $value, ($checked ? ' checked >':'>'));
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_radio( $name, $value, $checked ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_radio)" );
        $str = sprintf( "\n".'   <input type="radio" name="%s" value="%s"'
                        .'%s', $name, $value, ($checked ? ' checked >':'>'));
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_textarea( $name, $columns, $rows, $wrap='',
                                                    $maxlength='',$value='' ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_textarea)" );
        $str = ( "\n".'   <textarea name="'.$name.'" cols="'.$columns
                 .'" rows="'.$rows.'" wrap="'.$wrap.'" maxlength="'
                 .$maxlength.'">'.$value.'</textarea>' );
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_form_end( ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_form_end)" );
        $str = "\n</form>";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_form_image( $file, $alt ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_form_image)");
        $str = "\n".'   <input type="image" src="'.$file.'" alt="'.$alt.'">';
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }
    
    function _testFor_html_form_reset( $value = 'Reset' ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_form_reset)" );
        $str = "\n".'   <input type="reset" value="'.$value.'">';
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_html_input_password( $name, $size, $maxlength,
                                                               $value = '' ) {
        $this->push_msg( $this->test_msg . " (_testFor_html_input_password)" );
        $str = ( "\n".'   <input type="password" name="'.$name
                 .'" size="'.$size.'" maxlength="'.$maxlength
                 .'" value="'.$value.'">' );
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_box_begin( $frame_color='frame_color',
                                  $width = 'box_width', 
                                  $frame_width = 'frame_width' ) {
        $this->push_msg( $this->test_msg . " (_testFor_box_begin)" );
        $pats =array( 0=>('<table border="0" cellspacing="0" '
                          . 'cellpadding="0" bgcolor="'.$frame_color
                          . '" width="'.$width.'" align="center">'),
                      1=>('<table border="0" cellspacing="'.$frame_width
                          .'" cellpadding="3" align="center" width="100%">'));
        $this->_testFor_patterns($pats, 2);
        $this->pop_msg();
    }

    function _testFor_box_end(  ) {
        $this->push_msg( $this->test_msg . " (_testFor_box_end)" );
        $this->test_msg .= " (_testFor_box_end)";
        $this->_testFor_pattern("<\/table>\n<\/td><\/tr><\/table>" );
        $this->pop_msg();
    }

    function _testFor_box_title_begin( $bgcolor = 'title_bgcolor',
                                                  $align = 'title_align' ){
        $this->push_msg( $this->test_msg . " (_testFor_box_title_begin)" );
	$str = ("   <!-- box title begin -->\n   <tr bgcolor=\"$bgcolor\">"
                ."      <td align=\"$align\">\n" );
        $this->_testFor_pattern( $this->_to_regexp($str) );
        $this->pop_msg();
    }

    function _testFor_box_title_end( ) {
        $this->push_msg( $this->test_msg . " (_testFor_box_title_end)" );
        $str = 	"      </td>\n   </tr>\n   <!-- box title end -->\n";
        $this->_testFor_pattern( $this->_to_regexp($str) );
        $this->pop_msg();
    }

    function _testFor_box_title( $title, 
                                 $title_font_color = 'title_font_color', 
                                 $title_bgcolor = 'title_bgcolor',
                                 $title_align = 'title_align') {
        $this->push_msg( $this->test_msg . " (_testFor_box_title)" );
        $orig_rev = $this->reverse_test;
        $this->_testFor_box_title_begin( $title_bgcolor, $title_align);
        $this->reverse_test = $orig_rev;
        $str="      <font color=\"$title_font_color\"><b>$title</b></font>\n";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->reverse_test = $orig_rev;
        $this->_testFor_box_title_end( );
        $this->pop_msg();
    }

    function _testFor_box_body_begin($bgcolor ='body_bgcolor', 
                                      $body_align ='body_align',
                                      $body_valign = 'top', 
                                      $body_font_color = 'body_font_color') {
        $this->push_msg( $this->test_msg . " (_testFor_box_body_begin)" );
        $str = ("   <!-- box body begin -->\n   <tr bgcolor=\"$bgcolor\">\n"
                ."         <td align=\"$body_align\" valign=\"$body_valign\">"
                ."<font color=\"$body_font_color\">\n");
        $this->_testFor_pattern( $this->_to_regexp( $str ));
        $this->pop_msg();
    }

    function _testFor_box_body_end() {
        $this->push_msg( $this->test_msg . " (_testFor_box_body_end)" );
        $str = ( "      </font>\n      </td>\n   </tr>\n"
                 ."   <!-- box body end -->\n");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
    }

    function _testFor_box_body( $body, $color = 'body_font_color' ){
        $this->push_msg( $this->test_msg . " (_testFor_box_body)" );
        $str = 	("            <font color=\"".$color."\">\n"
                 .'            '.$body."            </font>\n");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->pop_msg();
    }
    
    function _testFor_box_columns_begin( $nr_cols, $valign='top' ) {
        $this->push_msg( $this->test_msg . " (_testFor_box_columns_begin)" );
        $ps=array( 0=>"<!-- table with " . $nr_cols . " columns -->\n",
                   1=>("<table border=\"0\" cellspacing=\"0\" cellpadding=\""
                       ."3\" align=\"center\" width=\"100%\">\n"),
                   2=>'<tr valign="'.$valign."\">\n");
        $this->_testFor_patterns( $ps, 3);
        $this->pop_msg();
    }

    function _testFor_box_column_start($align,$width,$bgcolor="#FFFFFF") {
        $this->push_msg( $this->test_msg . " (_testFor_box_column_start)" );
        $bgcolor=(!isset($bgcolor) || empty($bgcolor) ? "#FFFFFF" : $bgcolor);
        $ps=array( 0=>"[ ]+<!-- New Column starts -->\n",
                   1=>("[ ]+<td align=\"".$align."\" width=\"".$width
                       ."\" bgcolor=\"".$bgcolor."\">"));
        $this->_testFor_patterns( $ps, 2);
        $this->pop_msg();
    }

    function _testFor_box_column_finish() {
        $this->push_msg( $this->test_msg . " (_testFor_box_column_finish)" );
        $ps = array( 0=> "[ ]+<\/td>\n",
                     1=> "[ ]+<!-- Column finishes -->\n");
        $this->_testFor_patterns( $ps, 2 );
        $this->pop_msg();
    }

    function _testFor_box_columns_end() {
        $this->push_msg( $this->test_msg . " (_testFor_box_columns_end)" );
        $ps=array( 0=>"[ ]+<\/tr>\n",
                   1=>"[ ]+<\/table>\n",
                   2=>"[ ]+<!-- end table with columns -->\n" );
        $this->_testFor_patterns( $ps, 3 ); 
        $this->pop_msg();
    }

    function _testFor_box_column( $align,$width,$bgcolor,$txt ){
        // FIXME: this is bad: this checks whether there has been a column
        // FIXME: start _somewhere_ in the text but *not* whether the column
        // FIXME: start and the text actually match up! To fix this,
        // FIXME: need to take the code of _testFor_box_column_start and
        // FIXME: add it this method but that breaks the write once principle!
        $this->push_msg( $this->test_msg . " (_testFor_box_column)" );
        $orig_rev = $this->reverse_test;
        $this->_testFor_box_column_start( $align,$width,$bgcolor );
 
        $this->reverse_test = $orig_rev;
        $this->_testFor_box_column_finish( );
 
        $this->reverse_test = $orig_rev;
        $this->_testFor_pattern( "[ ]+".$this->_to_regexp($txt) );
        $this->pop_msg();
    }

    function _testFor_box_next_row_of_columns(  ) {
        $this->push_msg("$this->test_msg (_testFor_box_next_row_of_columns)");
        $ps=array( 0=>"[ ]+<\/tr>\n",
                   1=>"[ ]+<!-- next row with several columns -->\n",
                   2=>"[ ]+<tr>\n" );
        $this->_testFor_patterns( $ps, 3 );
        $this->pop_msg();
    }

    function _testFor_box_colspan( $nr_cols, $align, $bgcolor,$insert_text){
        $this->push_msg( "$this->test_msg (_testFor_box_colspan)" );
        $ps=array(0=>( "[ ]+<!-- New Column spanned over $nr_cols columns "
                       ."starts -->\n"),
                  1=>('[ ]+<td colspan="'.$nr_cols.'" align="'.$align
                      .'" bgcolor="'.$bgcolor.'">'),
                  2=>'[ ]+'.$this->_to_regexp($insert_text),
                  3=>"[ ]+<\/td>\n",
                  4=>("[ ]+<!-- Column spanned over $nr_cols columns "
                      ."finished -->\n"));

        $this->_testFor_patterns($ps,5);
        $this->pop_msg();
    }

    function _testFor_lib_nick( $uname = '' ) {
        $this->push_msg( "$this->test_msg (_testFor_lib_nick)" );
        $str = '<b>by '.$uname.'</b>';
        $this->_testFor_pattern( $this->_to_regexp($str) );
        $this->pop_msg();
        return $str;
    }

    function _testFor_lib_comment_it( $proid, $type, $number, $ref,
                                                        $subject, $link_text){
        $this->push_msg( "$this->test_msg (_testFor_lib_comment_it)" );
        $orig_rev = $this->reverse_test;
        $this->_testFor_pattern( "<FONT SIZE=-1>[[].*[]]<\/FONT>\n");
 
        $this->reverse_test = $orig_rev;
        $this->_testFor_html_link( 'comments_edit.php3',
                  array( 'proid'=>$proid,'type'=>$type,'number'=>$number,
                         'ref'=>$ref,'subject'=>$subject),$link_text);
        $this->pop_msg();
    }
}
?>
