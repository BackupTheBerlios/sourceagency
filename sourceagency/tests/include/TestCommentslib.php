<?php
// TestCommentslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestCommentslib.php,v 1.2 2002/05/15 13:23:58 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( 'lib.inc' );

    // required for the $sess global variable
    include_once( "session.inc" );
    $sess = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");
}

include_once( 'commentslib.inc' );

class UnitTestCommentslib
extends UnitTest
{
    function UnitTestCommentslib( $name ) {
        $this->UnitTest( $name );
    }

    function setup() {
    }

    function tearDown() {
    }

    function testComments_form() {
        $this->_test_to_be_completed();
    }
    function testComments_preview() {
        $this->_test_to_be_completed();
    }
    function testComments_insert() {
        $this->_test_to_be_completed();
    }
    function testComments_modify() {
        $this->_test_to_be_completed();
    }
    function testComments_modify_form() {
        $this->_test_to_be_completed();
    }
    function testComments_missing_parameters() {
        $this->_test_to_be_completed();
    }

    function testComments_show() {
        // FIXME: only tests whether the arguments are checked, not
        // FIXME: whether the displaying of the data is working
        global $db;
        
        $db_config = new mock_db_configure( 6 );

        $db_q = array( 0 => ("SELECT * FROM comments, auth_user WHERE "
                             ."proid='%s' AND type='%s' AND "
                             ."number='%s' AND id='%s' AND ref='%s' "
                             ."AND user_cmt=username ORDER BY "
                             ."creation_cmt ASC"),
                       1 => ("SELECT * FROM comments, auth_user WHERE "
                             ."proid='%s' AND type='%s' AND "
                             ."number='%s' %s AND ref='%s' "
                             ."AND user_cmt=username ORDER BY "
                             ."creation_cmt ASC"));

        // 6 calls in total
        $dat = $this->_generate_records( array("proid","type_cmt", "number",
                                               "cmt_id", "ref"), 6 );

        $dat[1]["number"] = ""; 
        $dat[2]["cmt_id"] = ""; $dat[2]["number"] = "";
        $dat[3]["cmt_id"] = ""; $dat[3]["ref"] = "";
        $dat[4]["ref"] = "";    $dat[4]["number"] = ""; 
        $dat[5]["ref"] = ""; $dat[5]["number"] = ""; $dat[5]["cmt_id"] = "";

        $db_config->add_query( sprintf( $db_q[0], $dat[0]["proid"],
                                        $dat[0]["type_cmt"],
                                        $dat[0]["number"],
                                        $dat[0]["cmt_id"], $dat[0]["ref"]), 0);
        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"],
                                        $dat[1]["type_cmt"],"0",
                                        $dat[1]["cmt_id"], $dat[1]["ref"]), 1);
        $db_config->add_query( sprintf( $db_q[1], $dat[2]["proid"],
                                        $dat[2]["type_cmt"], "0",
                                        $dat[2]["cmt_id"], $dat[2]["ref"]), 2);
        $db_config->add_query( sprintf( $db_q[1], $dat[3]["proid"],
                                        $dat[3]["type_cmt"],$dat[3]["number"],
                                        $dat[3]["cmt_id"], "0"), 3);
        $db_config->add_query( sprintf( $db_q[0], $dat[4]["proid"],
                                        $dat[4]["type_cmt"],"0",
                                        $dat[4]["cmt_id"], "0"), 4);
        $db_config->add_query( sprintf( $db_q[1], $dat[5]["proid"],
                                        $dat[5]["type_cmt"],"0",
                                        $dat[5]["cmt_id"], "0"), 5);

        // make calls
        for ( $idx = 0; $idx < 6; $idx++ ) {
            $db = new DB_SourceAgency;

            comments_show( $dat[$idx]["proid"], $dat[$idx]["type_cmt"],
                           $dat[$idx]["number"], $dat[$idx]["cmt_id"],
                           $dat[$idx]["ref"]);
        }

        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );
?>
