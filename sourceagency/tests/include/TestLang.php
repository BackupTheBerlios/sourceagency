<?php
// TestLang.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestLang.php,v 1.4 2001/10/31 12:23:16 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $sess = new Session;
}

class UnitTestLang
extends TestCase
{
    function UnitTestHtml( $name ) {
        $this->TestCase( $name );
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
