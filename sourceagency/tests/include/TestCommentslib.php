<?php
// TestCommentslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestCommentslib.php,v 1.5 2002/05/28 08:58:28 riessen Exp $

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
        // remove the globally defined database object because it may
        // intefer with other tests
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
    }

    function testComments_form() {
        global $bx, $t;
        global $subject, $text, $number, $ref, $type;
        
        $proid = 'proid';
        $subject = "this si the subject";
        $text = "this is teh erst";
        $number = "this is the number";
        $ref = "this is the der";
        $type = " this is the type:";

        $bx = $this->_create_default_box();
        capture_reset_and_start();
        comments_form( $proid );
        $txt = capture_stop_and_get();

        $this->_checkFor_a_box( $txt, 'Your Comment' );
        $this->_checkFor_a_form( $txt, 'PHP_SELF',
                                          array('proid'=>$proid), 'POST' );
        $this->_testFor_html_form_hidden( $txt, 'type', $type);
        $this->_testFor_html_form_hidden( $txt, 'number', $number );
        $this->_testFor_html_form_hidden( $txt, 'ref', $ref);
        $this->_testFor_box_columns_begin( $txt, 2 );
        $this->_testFor_box_columns_end( $txt );

        $this->_checkFor_column_titles( $txt, array("Subject","Body") );
        $this->_checkFor_column_values( $txt, 
          array( html_input_text('subject', 40, 128, stripslashes($subject)),
               html_textarea('text',40, 7,'virtual',255,stripslashes($text))));
  
        $this->_testFor_html_form_submit( $txt, $t->translate( 'Preview' ),
                                                                   "preview");
        $this->_testFor_html_form_submit( $txt, $t->translate( 'Submit' ),
                                                                   "submit");

        $this->_testFor_captured_length( 2500 );
    }

    function testComments_preview() {
        global $t, $bx, $auth, $subject, $text;

        $auth->set_uname('this is the username');
        $subject = 'this is the subject';
        $text = 'this is the text//\\//\\fubar[[]][*](?)';

        $db_config = new mock_db_configure( 1 );
        $db_config->add_query("SELECT email_usr FROM auth_user WHERE "
                              ."username='".$auth->auth["uname"]."'");
        
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        comments_preview( '' );
        $txt = capture_stop_and_get();
        
        $this->_checkFor_a_box($txt,'Comment','','%s '.stripslashes($subject));
        $this->_checkFor_a_box($txt,'PREVIEW','','<center><b>%s</b></center>');

        $this->_testFor_pattern( $txt, 
                        $this->_to_regexp( lib_nick( $auth->auth['uname'] )));
        $this->_testFor_pattern( $txt, 
                           $this->_to_regexp("<p>".stripslashes($text)."\n"));
        $this->_check_db( $db_config );
        $this->_testFor_captured_length( 1018 );
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
        global $db, $bx;
        
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
            $bx = $this->_create_default_box();
            capture_reset_and_start();
            comments_show( $dat[$idx]["proid"], $dat[$idx]["type_cmt"],
                           $dat[$idx]["number"], $dat[$idx]["cmt_id"],
                           $dat[$idx]["ref"]);
            $text = capture_stop_and_get();
            $this->_testFor_captured_length( 0 );
        }

        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );
?>
