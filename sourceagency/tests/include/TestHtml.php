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
# $Id: TestHtml.php,v 1.4 2001/11/08 16:17:42 riessen Exp $
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
extends TestCase
{
    function UnitTestHtml( $name ) {
        $this->TestCase( $name );
    }

    function setup() {
        /* Called before each test method */
    }
    function tearDown() {
        /* Called after each test method */
    }

    function test__html_link() {
        $actual = html_link('fubar','what','hello world' );
        // TODO: isn't this wrong? the query doesn't even appear
        $expect = "<a href=\"fubar\">hello world</a>";
        $this->assertEquals( $expect, $actual );
        
        $actual = html_link( 'snafu', "", 'goodbye cruel world' );
        $expect = "<a href=\"snafu\">goodbye cruel world</a>";
        $this->assertEquals( $expect, $actual );
    }

    function test__html_anchor() {
        $actual = html_anchor( "hello world" );
        $expect = "<a name=\"hello world\"></a>\n";
        $this->assertEquals( $expect, $actual );
    }

    function test__html_image() {
        $actual = html_image("file", "border", "width", "height", "alternate");
        $expect = ("<img src=\"images/file\" border=\"border\" width=\"width\""
                   . " height=\"height\" alt=\"alternate\">");
        $this->assertEquals( $expect, $actual );
    }

    function test__html_form_action() {
        $actual = html_form_action( "PHP_SELF", "query", "type" );
        $expect = "<form action=\"\" method=\"type\">";
        $this->assertEquals( $expect, $actual );

        $actual = html_form_action( "file", "query", "type" );
        $expect = "<form action=\"file\" method=\"type\">";
        $this->assertEquals( $expect, $actual );
    }
}

define_test_suite( __FILE__ );
?>
