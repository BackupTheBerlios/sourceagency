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
# include/lang.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestLang.php,v 1.6 2002/01/28 02:11:11 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $sess = new Session;
}

class UnitTestLang
extends UnitTest
{
    function UnitTestHtml( $name ) {
        $this->UnitTest( $name );
    }
    
    function testDefault() {
        global $sess;
        include( "lang.inc" );
        $this->assertEquals( "en_EN", $locale );
    }

    function testGerman() {
        global $sess;
        $lang = "German";
        include( "lang.inc" );
        $this->assertEquals( "de_DE", $locale );
    }

    function testEnglish() {
        global $sess;
        $lang = "English";
        include( "lang.inc" );
        $this->assertEquals( "en_EN", $locale );
    }

    function testSpanish() {
        global $sess;
        $lang = "Spanish";
        include( "lang.inc" );
        $this->assertEquals( "es_ES", $locale );
    }

    function testUnknown() {
        global $sess;
        $lang = "fubar";
        include( "lang.inc" );
        $this->assertEquals( "en_EN", $locale );
    }
}

define_test_suite( __FILE__ );
?>
