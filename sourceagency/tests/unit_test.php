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
# $Id: unit_test.php,v 1.4 2002/01/28 02:16:46 riessen Exp $
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

    // this should also accept a $text argument instead of taking the
    // length from the captured text ...
    function _testFor_string_length( $str, $len, $msg = "Length mismatch" ) {
        $this->assertEquals( $len, strlen( $str ), $msg );
    }
    function _testFor_length( $length, $msg = "Length mismatch" ) {
        $this->assertEquals( $length, capture_text_length(), $msg );
    }
    function _testFor_line( $text, $line ) {
        $this->_testFor_pattern( $text, $line . "\n" );
    }
    function _testFor_pattern( $text, $pattern, $msg = "pattern not found" ) {
        $this->assertRegexp( "/" . $pattern . "/", $text, $msg);
    }

    function _check_db( $db_config ) {
        $this->assert( !$db_config->did_db_fail(), 
                       $db_config->error_message());
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
