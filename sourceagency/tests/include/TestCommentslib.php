<?php
// TestCommentslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestCommentslib.php,v 1.7 2002/05/31 12:41:50 riessen Exp $

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
      global $bx, $t, $sess;
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
        $this->set_text( capture_stop_and_get() );

        $this->__checkFor_a_box( 'Your Comment' );
        $this->__checkFor_a_form( 'PHP_SELF', array('proid' => $proid) );
        $this->__testFor_html_form_hidden( 'type', $type);
        $this->__testFor_html_form_hidden( 'number', $number );
        $this->__testFor_html_form_hidden( 'ref', $ref);
        $this->__checkFor_columns( 2 );

        $this->__checkFor_column_titles( array("Subject","Body") );
        $v=array( html_input_text('subject', 40, 128, stripslashes($subject)),
                  html_textarea('text',40, 7,'virtual',255,
                                stripslashes($text)));
        $this->__checkFor_column_values( $v );
  
        $this->__checkFor_submit_preview_buttons();

        $this->_testFor_string_length( 2500 + strlen( $sess->self_url() ));
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
        $this->set_text( capture_stop_and_get() );

        $this->__checkFor_a_box('Comment','%s '.stripslashes($subject));
        $this->__checkFor_a_box('PREVIEW','<center><b>%s</b></center>');

        $ps=array( 0=>$this->_to_regexp( lib_nick( $auth->auth['uname'])),
                   1=>$this->_to_regexp("<p>".stripslashes($text)."\n"));
        $this->__testFor_patterns( $ps, 2 );
        $this->_check_db( $db_config );
        $this->_testFor_string_length( 983 + strlen( timestr( time() ) ));
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
            $this->assert( strlen( capture_stop_and_get() ) == 0,'test '.$idx);
        }

        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );
?>
