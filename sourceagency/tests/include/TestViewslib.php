<?php
// TestViewslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestViewslib.php,v 1.7 2002/05/28 08:58:28 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the html functions
    include_once( 'html.inc');

    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");

    // required for the $bx global variable
    include_once( "box.inc" );
    $bx = new box;
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
        unset( $GLOBALS[ 'bx' ] );
    }

    function testViews_form() {

        $this->_test_to_be_completed();

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
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        views_form( "proid" );
        $text = capture_stop_and_get();
        
        $this->_testFor_captured_length( 9555 + strlen( $sess->self_url() ) );

        $this->_checkFor_a_box( $text, "Configure Information Access in this "
                               ."Project");
        $this->_testFor_html_form_action( $text, 'PHP_SELF', 
                                            array('proid'=>'proid'), 'POST' );
        $this->_testFor_box_columns_begin( $text, 2);
        // following needs to be done 9 times
        //$this->_testFor_box_column
        //$this->_testFor_box_column
        //$this->_testFor_box_next_row_of_columns
        
        //$this->_testFor_box_columns_end
        $this->_testFor_html_form_submit( $text, "Preview", "preview" );
        $this->_testFor_html_form_submit( $text, "Submit", "submit" );
        $this->_testFor_html_form_end( $text );
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
