<?php
// TestStatslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestStatslib.php,v 1.2 2002/05/29 15:42:08 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'statslib.inc' );

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
        $this->_test_to_be_completed();
    }

    function testStats_display_alt() {
        $this->_test_to_be_completed();
    }

    function testStats_end() {
        capture_reset_and_start();
        stats_end();
        $text = capture_stop_and_get();
        $a=array( "</table>\n",
                  "</TD></TR></TABLE>\n",
                  "</TD></TR></TABLE></CENTER>\n",
                  "<BR>\n" );
        $this->assertEquals( implode( '', $a ), $text );
        $this->_testFor_captured_length( strlen(implode( '', $a ) ) );
    }

    function testStatslib_top() {
        $this->_test_to_be_completed();
    }

    function testStats_subtitle() {
        $msg = 'thsi is the message';
        $color = $GLOBALS['th_box_title_bgcolor'];

        capture_reset_and_start();
        stats_subtitle($msg);
        $text = capture_stop_and_get();

        $a=array( "<tr><td bgcolor=\"".$color."\"><B>".$msg."</B></td>\n",
                  "<td bgcolor=\"".$color."\">&nbsp;</td>",
                  "<td bgcolor=\"".$color."\">&nbsp;</td>",
                  "<td bgcolor=\"".$color."\">&nbsp;</td>",
                  "<td bgcolor=\"".$color."\">&nbsp;</td></tr>" );
        $this->assertEquals( implode( '', $a ), $text );
        $this->_testFor_captured_length( strlen(implode( '', $a ) ) );
    }

    function testStats_title() {
        global $t;
        $msg = 'this is the message';

        capture_reset_and_start();
        stats_title($msg);
        $text = capture_stop_and_get();

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
        $this->assertEquals( implode( '', $a ), $text );
        $this->_testFor_captured_length( strlen(implode( '', $a ) ) );
    }
}

define_test_suite( __FILE__ );
?>
