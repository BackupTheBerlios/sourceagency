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
# $Id: unit_test.php,v 1.8 2002/04/23 11:50:36 riessen Exp $
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
//          $this->assertEquals( false, true, "Class UnitTest should not be "
//                               . "directly instantiated" );
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
        $this->_check_length( $len, strlen( $str ), $msg );
    }
    function _testFor_captured_length( $length, $msg = '' ) {
        $this->_check_length( capture_text_length(), $length, $msg );
    }
    function _testFor_line( $text, $line ) {
        $this->_testFor_pattern( $text, $line . "\n" );
    }
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

    function _check_db( $db_config ) {
        $this->assert( !$db_config->did_db_fail(), 
                       $db_config->error_message());
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
}
?>
