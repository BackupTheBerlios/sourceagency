<?php
// TestViewslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestViewslib.php,v 1.5 2002/05/21 12:56:26 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'viewslib.inc' );

class UnitTestViewslib
extends UnitTest
{
    function UnitTestViewslib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        // ensure that the next test does not have a globally defined
        // database object
        unset( $GLOBALS[ 'db' ] );
    }

    function testViews_form() {

        $this->_test_to_be_completed();
//  strlen( $sess->self_url() )
//                     1=>("<form action=\""
//                         .ereg_replace( "/", "\/", $sess->self_url() )
//                         ."[?]proid=proid_0\" "
//                         ."method=\"POST\">"));

        global $bx, $t, $sess, $db, $preview, $configure, $news, 
            $comments, $history, $step3, $step4, $step5, 
            $cooperation, $views;

        $news = "Project Initiator";
        $configure = "Everybody";
        $comments = "Registered";
        $history = "Sponsors";
        $step3 = "Project Participants";
        $step4 = "Project Developers";
        $step5 = "Project Sponsors";
        $cooperation = "Registered";
        $views = "Sponsors";
        
        $preview = "fubar";

        capture_reset_and_start();
        views_form( "proid" );
        $text = capture_stop_and_get();
        
        $this->_testFor_captured_length( 9483 + strlen( $sess->self_url() ) );
        $this->_testFor_html_form_action( $text, 'PHP_SELF', 
                                            array('proid'=>'proid'), 'POST' );
    }

    function testViews_insert() {
        $this->_test_to_be_completed();
    }

    function testViews_modify() {
        $this->_test_to_be_completed();
    }

    function testViews_preview() {
        $this->_test_to_be_completed();
    }

    function testViews_select_view() {
        $this->_test_to_be_completed();
    }

    function testViews_show() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
