<?php
// TestLang.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestLang.php,v 1.1 2001/10/11 09:19:37 ger Exp $

require_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // LIB_ROOT is the base of my libraries and is defined in constants.php
    ini_set('include_path', ini_get('include_path') . ':../../include' );

    // required for the $sess global variable
    require_once( "session.inc" );
    $sess = new Session;
}

class UnitTestLang
extends TestCase
{
    function UnitTestHtml( $name ) {
        $this->TestCase( $name );
    }
    
    function testDefault() {
        include( "lang.inc" );
        $this->assertEquals( "en_EN", $locale );
    }

    function testGerman() {
        $lang = "German";
        include( "lang.inc" );
        $this->assertEquals( "de_DE", $locale );
    }

    function testEnglish() {
        $lang = "English";
        include( "lang.inc" );
        $this->assertEquals( "en_EN", $locale );
    }

    function testSpanish() {
        $lang = "Spanish";
        include( "lang.inc" );
        $this->assertEquals( "es_ES", $locale );
    }

    function testUnknown() {
        $lang = "fubar";
        include( "lang.inc" );
        $this->assertEquals( "en_EN", $locale );
    }
}

define_test_suite( __FILE__ );
?>
