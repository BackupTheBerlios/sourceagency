<?php
// TestStatslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestStatslib.php,v 1.1 2003/11/21 12:56:03 helix Exp $

include_once( '../constants.php' );

include_once( 'statslib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS['t'] = new translation("English");
}

class UnitTestStatslib
extends UnitTest
{
    function UnitTestStatslib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testStats_display() {
        /** FIXME: corresponding is not used, can it be removed? **/
    }

    function testStats_display_alt() {
        /** FIXME: corresponding is not used, can it be removed? **/
    }

    function testStats_end() {
        $a=array( "</table>\n",
                  "</TD></TR></TABLE>\n",
                  "</TD></TR></TABLE></CENTER>\n",
                  "<BR>\n" );
        $this->capture_call( 'stats_end', strlen(implode( '', $a ) ) );
        $this->assertEquals( implode( '', $a ), $this->get_text() );
    }

    function testStatslib_top() {
        /** FIXME: corresponding function is never used, it can be removed? **/
    }

    function testStats_subtitle() {
        $msg = 'thsi is the message';
        include_once( 'config.inc' );
        $color = $GLOBALS['th_box_title_bgcolor'];
        $a=array( "<tr><td bgcolor=\"".$color."\"><B>".$msg."</B></td>\n",
                  "<td bgcolor=\"".$color."\">&nbsp;</td>",
                  "<td bgcolor=\"".$color."\">&nbsp;</td>",
                  "<td bgcolor=\"".$color."\">&nbsp;</td>",
                  "<td bgcolor=\"".$color."\">&nbsp;</td></tr>" );
        $this->capture_call( 'stats_subtitle', strlen(implode( '', $a ) ) ,
                             array( &$msg ) );
        $this->assertEquals( implode( '', $a ), $this->get_text() );
    }

    function testStats_title() {
        global $t;
        $msg = 'this is the message';

        include_once( 'config.inc' );
        $a=array( "<center>\n",
                  "<table width=600 border=0 cellspacing=0 cellpadding=0 "
                  ."bgcolor=\"".$GLOBALS["th_box_frame_color"]
                  ."\" align=center>\n",
                  "<tr><td>\n",
                  "<table width=100% border=0 cellspacing=1 cellpadding=3>\n",
                  "<tr bgcolor=\"".$GLOBALS["th_box_title_bgcolor"]."\">\n",
                  "<td><b>",
                  $t->translate($msg),
                  "</b></td>\n",
                  "</tr><tr bgcolor=\"".$GLOBALS["th_box_body_bgcolor"]
                  ."\"><td>\n",
                  "<table border=0 width=100% cellspacing=0>\n");
        $this->capture_call( 'stats_title', strlen(implode( '', $a ) ),
                             array( &$msg ) );
        $this->assertEquals( implode( '', $a ), $this->get_text() );
    }
}

define_test_suite( __FILE__ );
?>
