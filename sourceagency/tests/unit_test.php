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
# $Id: unit_test.php,v 1.15 2002/05/22 13:10:23 riessen Exp $
#
######################################################################


// common base class for all Unit Tests, supplies some common methods
// This class should not be directly instantiated ....
class UnitTest
extends TestCase
{
    var $p_regexp_html_comment = "<!--[^->]*-->";

    function UnitTest( $name = "" ) {
        $this->TestCase( $name );
    }

    // could actually be defined in phpunit ....
    function assertNotRegexp( $regexp, $actual, 
                              $message="assert not regexp failed" ) {
        if ( preg_match( $regexp, $actual ) ) {
            $this->failNotEquals( $regexp, $actual, "*NOT* pattern",$message );
        }
    }

    function _check_length( $exp, $act, $msg = '' ) {
        $this->assertEquals( $exp, $act, $msg . ' (Length Mismatch)' );
    }
    function _testFor_string_length( $str, $len, $msg = '' ) {
        $this->_check_length( $len, strlen( $str ), $msg . ' (string)');
    }
    function _testFor_captured_length( $length, $msg = '' ) {
        $this->_check_length( $length, capture_text_length(),
                              $msg . ' (captured)');
    }
    function _testFor_line( $text, $line, $msg = '' ) {
        $this->_testFor_pattern( $text, $line . "\n", $msg );
    }
    // test for a specific regular expression in a given text
    function _testFor_pattern( $text, $pattern, $msg = '' ) {
        $this->assertRegexp( "/" . $pattern . "/", $text, 
                                                $msg . ' (Pattern not Found)');
    }
    function _testFor_patterns( $text, $pattern_array, $check_size = -1, 
                                $msg = '' ) {
        reset( $pattern_array );
        if ( $check_size > 0 ) {
            $this->assertEquals( $check_size, count( $pattern_array ), 
                                 $msg . ' (pattern count mismatch)' );
        }
        while ( list( $key, $val ) = each( $pattern_array ) ) {
            $this->_testFor_pattern( $text, $val, $msg.(' (Key "'.$key.'")'));
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
    function _check_db( $db_config ) {
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
            return call_user_method_array( $method, &$obj, $args );
        } else {
            /* greater or equal than 4.1.0 */
            return call_user_func_array( array( &$obj, $method ), $args );
        }
    }

    //
    // The following are methods to test for common html code
    //
    function _query_to_regexp( $str ) {
        return ( ereg_replace( "[+]", "[+]",
                               ereg_replace( "[?]", "[?]", 
                                             ereg_replace( "/", "\/", $str))));
    }
    function _testFor_html_link( $text, $addr='PHP_SELF', $paras=array(), 
                                 $link_text='', $css='', $msg='') {
        global $sess;
        $str = sprintf('<a href="%%s" class="%s">%s</a>',$css,$link_text);
        
        $str = sprintf( $str, ($addr == 'PHP_SELF' ? $sess->self_url()
                                                   : $sess->url($addr))
                              . ((is_array($paras) && isset($paras) 
                                 && !empty($paras)) ? $sess->add_query($paras) 
                                 : "" ));

        $this->_testFor_pattern( $text, $this->_query_to_regexp($str),
                                             $msg . " (_testFor_html_link)");
        return $str;
    }

    function _testFor_html_form_action( $text, $file = 'PHP_SELF',
                                        $query='', $method='POST', $msg='') {
        global $sess;
        
        $str = sprintf( '%s<form action="%s" method="%s">', "\n",
                        ($file == 'PHP_SELF' ? $sess->self_url() 
                                             : $sess->url( $file ))
                        .$sess->add_query( $query ), $method );

        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                        $msg . " (_testFor_html_form_action)");
        return ($str);
    }
    function _testFor_html_anchor( $text, $name, $msg = '') {
        $str = '<a name="'. $name . '"></a>';
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                            $msg .' (_testFor_html_anchor)');
        return $str;
    }

    function _testFor_html_image( $text, $file, $border, $width, $height,
                                  $alt, $msg = '' ) {
        $str = ( '<img src="images/'.$file.'" border="'.$border.'"'
                 .' width="'.$width.'" height="'.$height
                 .'" alt="'.$alt.'">' );
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                               $msg." (_testFor_html_image)" );
        return $str;
    }

    function _testFor_html_form_hidden( $text, $name, $value, $msg = '' ) {
        $str = sprintf('%s    <input type="hidden" name="%s" value="%s">',
                       "\n", $name, $value );
        $this->_testFor_pattern( $text, $this->_query_to_regexp($str),
                                 $msg .' (_testFor_html_form_hidden)');
        return $str;
    }
    function _testFor_html_select( $text, $name, $multi, $size, $msg = '' ) {
        $str = sprintf("\n".'   <select name="%s" size="%s"%s>'."\n",
                       $name, $size, ($multi ? ' multiple' : ''));
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                             $msg . ' (_testFor_html_select)');
        return $str;
    }
    function _testFor_html_select_option( $text, $value, $selected, 
                                          $txt, $msg = '' ) {
        $str = sprintf("\n".'      <option %svalue="%s">%s'."\n",
                       ($selected ? 'selected ':''), $value, $txt );
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                 $msg . ' (_testFor_html_select_option)');
        return $str;
    }
    function _testFor_html_select_end( $text, $msg = '') {
        $str = "\n   </select>\n";
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                         $msg . ' (_testFor_html_select_end)');
        return $str;
    }
    function _testFor_html_input_text( $text, $name, $size, $maxlength, 
                                                       $value='', $msg = '' ) {
        $str = sprintf( "\n".'   <input type="text" name="%s" size="%s" '
                        .'maxlength="%s" value="%s">', $name, $size,
                        $maxlength, $value );
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                        $msg . ' (_testFor_html_input_text)');
        return $str;
    }
    function _testFor_html_form_submit( $text, $value, $name='', $msg = '' ) {
        $str = sprintf( "\n".'   <input type="submit" value="%s"%s>',
                        $value, ($name ? ' name="'.$name.'"' : ''));
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                       $msg . ' (_testFor_html_form_submit)');
        return $str;
    }
    function _testFor_html_checkbox( $text, $name, $value, $checked, $msg=''){
        $str = sprintf( "\n".'   <input type="checkbox" name="%s" value="%s"'
                        .'%s', $name, $value, ($checked ? ' checked >':'>'));
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                          $msg . ' (_testFor_html_checkbox)');
        return $str;
    }
    function _testFor_html_radio( $text, $name, $value, $checked, $msg = '' ) {
        $str = sprintf( "\n".'   <input type="radio" name="%s" value="%s"'
                        .'%s', $name, $value, ($checked ? ' checked >':'>'));
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                          $msg . ' (_testFor_html_checkbox)');
        return $str;
    }
    function _testFor_html_textarea( $text, $name, $columns, $rows, $wrap='',
                                           $maxlength='',$value='',$msg='') {
        $str = ( "\n".'   <textarea name="'.$name.'" cols="'.$columns
                 .'" rows="'.$rows.'" wrap="'.$wrap.'" maxlength="'
                 .$maxlength.'">'.$value.'</textarea>' );
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                         $msg . ' (_testFor_html_textarea)');
        return $str;
    }
    function _testFor_html_form_end( $text, $msg = '' ) {
        $str = "\n</form>";
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                         $msg . ' (_testFor_html_form_end)');
        return $str;
    }
    function _testFor_html_form_image( $text, $file, $alt, $msg = '' ) {
        $str = "\n".'   <input type="image" src="'.$file.'" alt="'.$alt.'">';
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                       $msg . ' (_testFor_html_form_image)');
        return $str;
    }
    function _testFor_html_form_reset( $text, $value = 'Reset', $msg = '' ){
        $str = "\n".'   <input type="reset" value="'.$value.'">';
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                       $msg . ' (_testFor_html_form_reset)');
        return $str;
    }
    function _testFor_html_input_password( $text, $name, $size, $maxlength,
                                                    $value = '', $msg = '' ) {
        $str = ( "\n".'   <input type="password" name="'.$name
                 .'" size="'.$size.'" maxlength="'.$maxlength
                 .'" value="'.$value.'">' );
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                   $msg . ' (_testFor_html_input_password)');
        return $str;
    }
    function _testFor_box_begin( $text, $frame_color='frame_color',
                                 $width = 'box_width', 
                                 $frame_width = 'frame_width', $msg = '' ) {
        $pats =array( 0=>('<table border="0" cellspacing="0" '
                          . 'cellpadding="0" bgcolor="'.$frame_color
                          . '" width="'.$width.'" align="center">'),
                      1=>('<table border="0" cellspacing="'.$frame_width
                          .'" cellpadding="3" align="center" width="100%">'));
        $this->_testFor_patterns($text,$pats,2,$msg.' (_testFor_box_begin)');
    }
    function _testFor_box_end( $text, $msg = '' ) {
        $this->_testFor_pattern( $text, "<\/table>\n<\/td><\/tr><\/table>",
                                 "$msg (_testFor_box_end)" );
    }

    function _testFor_box_title_begin( $text, $bgcolor = 'title_bgcolor',
                                          $align = 'title_align', $msg = '') {
	$str = ("   <!-- box title begin -->\n   <tr bgcolor=\"$bgcolor\">"
                ."      <td align=\"$align\">\n" );
        $this->_testFor_pattern( $text, $this->_query_to_regexp($str),
                                           "$msg (_testFor_box_title_begin)");
    }
    function _testFor_box_title_end( $text, $msg = '' ) {
        $str = 	"      </td>\n   </tr>\n   <!-- box title end -->\n";
        $this->_testFor_pattern( $text, $this->_query_to_regexp($str),
                                             "$msg (_testFor_box_title_end)" );
    }
    function _testFor_box_title( $text, $title, $color = 'title_font_color', 
                                 $msg = '' ) {
        $str = "      <font color=\"$color\"><b>$title</b></font>\n";
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                                  "$msg (_testFor_box_title)");
    }
    function _testFor_box_body_begin( $text, $bgcolor ='body_bgcolor', 
                                      $body_align ='body_align',
                                      $body_valign = 'top', 
                                      $body_font_color = 'body_font_color', 
                                      $msg = '') {
        $str = ("   <!-- box body begin -->\n   <tr bgcolor=\"$bgcolor\">\n"
                ."         <td align=\"$body_align\" valign=\"$body_valign\">"
                ."<font color=\"$body_font_color\">\n");
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                             "$msg (_testFor_box_body_begin)");
    }
    function _testFor_box_body_end( $text, $msg = '' ) {
        $str = ( "      </font>\n      </td>\n   </tr>\n"
                 ."   <!-- box body end -->\n");
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                              "$msg (_testFor_box_body_end)");

    }
    function _testFor_box_body( $text, $body, $color = 'body_font_color', 
                                                                  $msg = '' ) {
        $str = 	("            <font color=\"".$color."\">\n"
                 .'            '.$body."            </font>\n");
        $this->_testFor_pattern( $text, $this->_query_to_regexp( $str ),
                                                  "$msg (_testFor_box_body)");
    }
    function _testFor_box_columns_begin( $text, $nr_cols, $valign='top',
                                         $msg = '') {
        $ps=array( 0=>"<!-- table with " . $nr_cols . " columns -->\n",
                   1=>("<table border=\"0\" cellspacing=\"0\" cellpadding=\""
                       ."3\" align=\"center\" width=\"100%\">\n"),
                   2=>"<tr valign=\"$valign\">\n");
        $this->_testFor_patterns( $text, $ps, 3, 
                                       "$msg (_testFor_box_columns_begin)" );
    }
    function _testFor_box_column_start($text,$align,$width,$bgcolor="#FFFFFF",
                                       $msg = ''){
        $ps=array( 0=>"[ ]+<!-- New Column starts -->\n",
                   1=>("[ ]+<td align=\"".$align."\" width=\"".$width
                       ."\" bgcolor=\"".$bgcolor."\">"));
        $this->_testFor_patterns( $text, $ps, 2, 
                                          "$msg (_testFor_box_column_start)");
    }
    function _testFor_box_column_finish( $text, $msg = '' ) {
        $ps = array( 0=> "[ ]+<\/td>\n",
                     1=> "[ ]+<!-- Column finishes -->\n");
        $this->_testFor_patterns( $text, $ps, 2, 
                                       "$msg (_testFor_box_column_finish)");
    }
    function _testFor_box_columns_end( $text, $msg = '' ) {
        $ps=array( 0=>"[ ]+<\/tr>\n",
                   1=>"[ ]+<\/table>\n",
                   2=>"[ ]+<!-- end table with columns -->\n" );

        $this->_testFor_patterns( $text, $ps, 3, 
                                       "$msg (_testFor_box_columns_end)");
    }
    function _testFor_box_column( $text, $align,$width,$bgcolor,$txt,$msg=''){
        $this->_testFor_box_column_start($text,$align,$width,$bgcolor,$msg);
        $this->_testFor_box_column_finish( $text, $msg );
        $this->_testFor_pattern( $text, "[ ]+".$this->_query_to_regexp($txt),
                                                 "$msg (_testFor_box_column)");
    }
    function _testFor_box_next_row_of_columns( $text, $msg = '' ) {
        $ps=array( 0=>"[ ]+<\/tr>\n",
                   1=>"[ ]+<!-- next row with several columns -->\n",
                   2=>"[ ]+<tr>\n" );
        $this->_testFor_patterns( $text, $ps, 
                                   "$msg (_testFor_box_next_row_of_columns)");
    }
    function _testFor_box_colspan( $text, $nr_cols, $align, $bgcolor,
                                   $insert_text, $msg = '' ) {
        $ps=array(0=>( "[ ]+<!-- New Column spanned over $nr_cols columns "
                       ."starts -->\n"),
                  1=>('[ ]+<td colspan="'.$nr_cols.'" align="'.$align
                      .'" bgcolor="'.$bgcolor.'">'),
                  2=>'[ ]+'.$this->_query_to_regexp($insert_text),
                  3=>"[ ]+<\/td>\n",
                  4=>("[ ]+<!-- Column spanned over $nr_cols columns "
                      ."finished -->\n"));
        $this->_testFor_patterns($text,$ps,5,"$msg (_testFor_box_colspan)");
    }
}
?>
