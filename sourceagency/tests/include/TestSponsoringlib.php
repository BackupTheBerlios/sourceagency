<?php
// TestSponsoringlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestSponsoringlib.php,v 1.1 2002/02/01 08:36:42 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // need to define a global session
    include_once( "session.inc" );
    $sess = new Session;

    include_once( "translation.inc" );
    $t = new translation("English");

    include_once( "box.inc" );
    $bx = new box;
}

include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once("sponsoringlib.inc");

class UnitTestSponsoringlib
extends UnitTest
{
    function UnitTestSponsoringlib( $name ) {
        $this->UnitTest( $name );
    }

    function setup() {
    }

//      function testSponsoring_form() {
//      }

//      function testShow_sponsorings() {
//          global $db, $auth;

//          $db_config = new mock_db_configure( 1 );
//          $db = new DB_SourceAgency;
//          $db_q = array( 0 => ("SELECT * FROM sponsoring,auth_user WHERE "
//                               ."proid='%s' AND sponsor=username ORDER "
//                               ."BY sponsoring.creation ASC"),
//                         1 => ("SELECT * FROM comments,auth_user WHERE "
//                               ."proid='%s' AND type='%s' AND number='%s' "
//                               . "AND ref='%s' AND user_cmt=username "
//                               . "ORDER BY creation_cmt ASC"));

//          $db_d = $this->_generate_records( array( "proid" ), 2 );
        
        
//          // first call
//          capture_reset_and_start();
//          $text = capture_stop_and_get();

//          $this->_testFor_length( 439 );
        
//          // finally check that everything went smoothly with the DB
//          $this->_check_db( $db_config );
//      }
}

define_test_suite( __FILE__ );

?>
