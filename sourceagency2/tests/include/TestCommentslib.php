<?php
// TestCommentslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestCommentslib.php,v 1.1 2003/11/21 12:56:03 helix Exp $

include_once( "../constants.php" );

include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'commentslib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $GLOBALS[ 'sess' ] = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}


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
        unset_global( 'db', 'bx' );
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
        $this->capture_call( 'comments_form', 2500 + strlen($sess->self_url()),
                             array( &$proid ));

        $this->_checkFor_a_box( 'Your Comment' );
        $this->_checkFor_a_form( 'PHP_SELF', array('proid' => $proid) );
        $this->_testFor_html_form_hidden( 'type', $type);
        $this->_testFor_html_form_hidden( 'number', $number );
        $this->_testFor_html_form_hidden( 'ref', $ref);
        $this->_checkFor_columns( 2 );

        $this->_checkFor_column_titles( array("Subject","Body") );
        $v=array( html_input_text('subject', 40, 128, stripslashes($subject)),
                  html_textarea('text',40, 7,'virtual',255,
                                stripslashes($text)));
        $this->_checkFor_column_values( $v );
  
        $this->_checkFor_submit_preview_buttons();

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
        $this->capture_call( 'comments_preview', 983 + strlen(timestr(time())),
                             array( '' ) );

        $this->_checkFor_a_box('Comment','%s '.stripslashes($subject));
        $this->_checkFor_a_box('PREVIEW','<center><b>%s</b></center>');

        $ps=array( 0=>$this->_to_regexp( lib_nick( $auth->auth['uname'])),
                   1=>$this->_to_regexp("<p>".stripslashes($text)."\n"));
        $this->_testFor_patterns( $ps, 2 );
        $this->_check_db( $db_config );
    }
    function testComments_insert() {
        global $db, $t;
        $db_config = new mock_db_configure( 2 );

        $qs=array( 0=>("SELECT * FROM comments WHERE proid='%s' AND "
                       ."type='%s' AND number='%s'"),
                   1=>("INSERT comments SET proid='%s',user_cmt='%s',"
                       ."type='%s',number='%s',id='%s',ref='%s',"
                       ."subject_cmt='%s',text_cmt='%s'"),
                   2=>("SELECT * FROM comments, auth_user WHERE proid='%s' "
                       ."AND type='%s' AND number='%s' AND id='%s' AND "
                       ."ref='%s' AND user_cmt=username ORDER BY "
                       ."creation_cmt ASC"),
                   3=>("SELECT email_usr FROM auth_user,monitor WHERE "
                       ."monitor.username=auth_user.username AND "
                       ."proid='%s' AND (importance='middle' OR "
                       ."importance='high')"));
        $args=$this->_generate_records( array( 'proid', 'user', 'type_cmt',
                                               'number', 'ref', 'subject',
                                               'text'), 1 );
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid'], 
                                        $args[0]['type_cmt'], 
                                        $args[0]['number']), 0 );
        $db_config->add_num_row( 1, 0 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['proid'], 
                                       $args[0]['user'], $args[0]['type_cmt'],
                                     $args[0]['number'], '2', $args[0]['ref'],
                                   $args[0]['subject'], $args[0]['text']),0);

        // ensure that monitor_mail sends no mail out
        $db_config->add_query( sprintf( $qs[3], $args[0]['proid'] ), 1 );
        $db_config->add_record( false, 1 );
        
        // ensure that comments_show(..) shows no comments
        $db_config->add_query( sprintf( $qs[2], $args[0]['proid'],
                                 $args[0]['type_cmt'],$args[0]['number'],'2',
                                 $args[0]['ref']), 0 );
        $db_config->add_record( false, 0 );

        $db = new DB_SourceAgency;
        $this->capture_call( 'comments_insert', 0, $args[0] );
        $this->_check_db( $db_config );
    }
    function testComments_modify() {
        global $db, $t;
        $db_config = new mock_db_configure( 2 );

        $qs=array( 1=>("UPDATE comments SET user_cmt='devel', "
                       ."subject_cmt='%s', text_cmt='%s' WHERE proid='%s' "
                       ."AND type='%s'  AND number='%s' AND id='%s' AND "
                       ."ref='%s'"),
                   2=>("SELECT * FROM comments, auth_user WHERE proid='%s' "
                       ."AND type='%s' AND number='%s' AND id='%s' AND "
                       ."ref='%s' AND user_cmt=username ORDER BY "
                       ."creation_cmt ASC"),
                   3=>("SELECT email_usr FROM auth_user,monitor WHERE "
                       ."monitor.username=auth_user.username AND "
                       ."proid='%s' AND (importance='middle' OR "
                       ."importance='high')"));
        $args=$this->_generate_records( array( 'proid', 'user', 'type_cmt',
                                               'number', 'cmt_id', 'ref', 
                                               'subject', 'text'), 1 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['subject'], 
                                       $args[0]['text'], $args[0]['proid'],
                                     $args[0]['type_cmt'], $args[0]['number'],
                                   $args[0]['cmt_id'], $args[0]['ref']),0);

        // ensure that monitor_mail sends no mail out
        $db_config->add_query( sprintf( $qs[3], $args[0]['proid'] ), 1 );
        $db_config->add_record( false, 1 );
        
        // ensure that comments_show(..) shows no comments
        $db_config->add_query( sprintf( $qs[2], $args[0]['proid'],
                                 $args[0]['type_cmt'],$args[0]['number'],
                                 $args[0]['cmt_id'], $args[0]['ref']), 0 );
        $db_config->add_record( false, 0 );

        $db = new DB_SourceAgency;
        $this->capture_call( 'comments_modify', 0, $args[0] );
        $this->_check_db( $db_config );
    }

    function testComments_modify_form() {
        global $bx, $t, $sess;
        global $subject, $text, $number, $creation;
        
        $subject = 'this is the subkecct';
        $text = 'this isteh text';
        $number = 'this is the number ';
        $creation = 'this is the cration';
        $args=$this->_generate_records( array('proid'), 1 );
        $bx = $this->_create_default_box();
        $this->capture_call( 'comments_modify_form', 
                             2453 + strlen($sess->self_url()), $args[0] );

        $this->_checkFor_a_box( 'Modifying Comments' );
        $this->_checkFor_a_form( 'PHP_SELF', array('proid'=>$args[0]['proid']),
                                 'POST' );
        $this->_testFor_html_form_hidden( 'creation', $creation );
        $this->_testFor_html_form_hidden( 'number', $number );
        $this->_checkFor_columns( 2 );
        
        $this->_checkFor_column_titles( array( 'Subject'),'right','30%','',
                                        '<b>%s</b> (128): ');
        $this->_checkFor_column_titles( array( 'Body'),'right','30%','',
                                        '<b>%s</b> (*): ');
        $v = array( html_textarea('text', 40, 7, 'virtual', 255,
                                  stripslashes($text)),
                    html_input_text('subject', 40, 128,
                                    stripslashes($subject)));
        $this->_checkFor_column_values( $v );

        $this->_checkFor_submit_preview_buttons();
        
    }

    function testComments_missing_parameters() {
        $this->capture_call( 'comments_missing_parameters', 685 );
        $this->_checkFor_error_box( 'Error', 'Missing Parameters' );
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
            $this->push_msg( 'test '.$idx );
            $this->capture_call( 'comments_show', 0, &$dat[$idx] );
            $this->pop_msg();
        }

        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );
?>
