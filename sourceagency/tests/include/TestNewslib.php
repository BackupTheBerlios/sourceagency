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
# include/newslib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestNewslib.php,v 1.17 2002/05/28 08:58:28 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $sess = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");

    include_once( "box.inc" );
    $bx = new box;
}

include_once( 'html.inc' );
include_once( 'lib.inc' );
include_once( 'newslib.inc' );

class UnitTestNewslib
extends UnitTest
{
    function UnitTestNewslib( $name ) {
        $this->UnitTest( $name );
    }

    function setup() {
    }

    function tearDown() {
        // Ensure that the global db is predefined for the next test
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
    }

    function testNewsform() {
        global $text, $subject, $sess, $bx, $t;

        $text = "this is the text";
        $subject = "this is the subject";
        $proid = "proid";

        $bx = $this->_create_default_box();
        capture_reset_and_start();
        newsform( $proid ); 
        $txt = capture_stop_and_get();

        $this->_checkFor_a_box( $txt, 'Editing News' );
        $this->_checkFor_a_form($txt,'PHP_SELF',array('proid'=>$proid),'POST');
        $this->_checkFor_columns( $txt, 2 );

        $this->_checkFor_column_titles( $txt, array( 'Body', 'Subject' ));
        $this->_checkFor_column_values( $txt,
            array( html_input_text('subject',40,128,$subject),
                   html_textarea('text',40,7,'virtual',255,$text )));

        $this->_checkFor_submit_preview_buttons( $txt );
        $this->_testFor_captured_length( 2307 + strlen( $sess->self_url() ) );
    }

    function testNews_modify_form() {
        global $text, $subject, $creation, $sess, $bx, $t;

        $text = "this is the text";
        $subject = "this is the subject";
        $creation = "asdasd";
        $proid = 'proid';
        
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        news_modify_form( $proid ); 
        $txt = capture_stop_and_get();

        $this->_checkFor_a_box( $txt, 'Modifying News' );
        $this->_checkFor_a_form($txt,'PHP_SELF',array('proid'=>$proid),'POST');
        $this->_testFor_html_form_hidden( $txt, 'creation', $creation );
        $this->_checkFor_columns( $txt, 2 );
        $this->_checkFor_column_titles( $txt, array( 'Subject', 'Body' ) );
        $this->_checkFor_column_values( $txt, 
                 array(html_textarea('text',40,7,'virtual',255,$text),
                       html_input_text('subject',40,128,$subject)));
        
        $this->_checkFor_submit_preview_buttons( $txt );
        $this->_testFor_captured_length( 2366 + strlen( $sess->self_url() ));
    }

    function testNews_preview() {
        global $subject, $text, $auth, $sess, $bx, $t;
                
        $text = "this is the text";
        $subject = "this is the subject";
        $auth->set_uname( "username" );
        $proid = 'fubar';

        $bx = $this->_create_default_box();
        capture_reset_and_start();
        news_preview( $proid );
        $txt = capture_stop_and_get();

        $this->_checkFor_a_box($txt,'PREVIEW','','<center><b>%s</b></center>');
        $this->_checkFor_a_box($txt,'News','','%s: '.$subject);
        
        $this->_testFor_lib_nick( $txt, $auth->auth['uname'] );

        $this->_testFor_captured_length( 947 + strlen(timestr(time()))
                                         + strlen( $sess->self_url() ));
    }

    function testNewsshow() {
        global $db, $bx, $t;

        $db_config = new mock_db_configure( 7 );
        $proid = array( 0 => "proid_0", 
                        1 => "proid_1",
                        2 => "proid_2");

        $db_q = array( 0 => ("SELECT * FROM news,auth_user WHERE proid='%s' "
                             ."AND user_news=username ORDER BY creation_news "
                             ."DESC"),
                       1 => ("SELECT * FROM comments,auth_user WHERE proid="
                             ."'%s' AND type='%s' AND number='%s' AND ref='%s'"
                             ." AND user_cmt=username ORDER BY creation_cmt "
                             ."ASC"));
        
        $row = array( 0 => $this->_generate_array( 
            array("subject_news","creation_news","user_news","text_news"), 0),
                      1 => $this->_generate_array(
            array("subject_news","creation_news","user_news","text_news"), 1),
                      2 => $this->_generate_array(
            array("subject_news","creation_news","user_news","text_news"), 2),
                      3 => $this->_generate_array(
            array("user_cmt","creation_cmt","subject_cmt","id"), 3));
        
        $db_config->add_query( sprintf( $db_q[0], $proid[0] ), 0);
        $db_config->add_query( sprintf( $db_q[0], $proid[1] ), 1);
        $db_config->add_query( sprintf( $db_q[1], $proid[1], "News",
                                        $row[0]["creation_news"], "0"), 2);

        $db_config->add_query( sprintf( $db_q[0], $proid[2] ), 3);

        $db_config->add_query( sprintf( $db_q[1], $proid[2], "News",
                                        $row[1]["creation_news"], "0"), 4);
        $db_config->add_query( sprintf( $db_q[1], $proid[2], "News",
                                        $row[2]["creation_news"], "0"), 5);

        $db_config->add_query( sprintf( $db_q[1], $proid[2], "News",
                                        $row[2]["creation_news"], 
                                        $row[3]["id"]), 6);

        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_num_row( 0, 2 );

        $db_config->add_num_row( 2, 3 );
        $db_config->add_num_row( 0, 4 );
        $db_config->add_num_row( 1, 5 );
        $db_config->add_num_row( 0, 6 );

        $db_config->add_record( $row[0], 1 );
        $db_config->add_record( $row[1], 3 );
        $db_config->add_record( $row[2], 3 );
        $db_config->add_record( $row[3], 5 );
        
        //
        // first call, no records
        //
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        newsshow( $proid[0] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 67, "test 1" );
        $this->_testFor_pattern( $text, ("<p>There have not been posted any "
                                         ."news by the project owner[(]s[)]."
                                         ."<p>"));
        
        //
        // second call, one record but no comment on it.
        //
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        newsshow( $proid[1] );
        $txt = capture_stop_and_get();
        $this->_testFor_captured_length( 894, "test 2" );

        $this->_checkFor_a_box($txt,'News','','%s: '.$row[0]['subject_news']);

        $this->_testFor_lib_nick( $txt, $row[0]['user_news']);
        
        $this->_testFor_lib_comment_it( $txt,$proid[1],'News',
                                        $row[0]['creation_news'],'0',
                                        'Re:'.$row[0]['subject_news'], 
                                        $t->translate('Comment This News!'));
        //
        // third call, two records, the second has a comment which makes
        // a recursive call to show_comments_on_it which in turn has no rows
        //
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        newsshow( $proid[2] );
        $txt = capture_stop_and_get();
        $this->_testFor_captured_length( 1943, "test 3" );

        $this->_checkFor_a_box($txt,'News','','%s: '.$row[1]['subject_news']);
        $this->_checkFor_a_box($txt,'News','','%s: '.$row[2]['subject_news']);

        $this->_testFor_lib_nick( $txt, $row[1]['user_news']);
        $this->_testFor_lib_nick( $txt, $row[2]['user_news']);

        $this->_testFor_lib_comment_it( $txt,$proid[2],'News',
                                        $row[2]['creation_news'],'0',
                                        'Re:'.$row[2]['subject_news'], 
                                        $t->translate( 'Comment This News!'));
        // check that the database component did not fail
        $this->_check_db( $db_config );
    }

    function testNews_insert() {
        global $db;
        
        $db_config = new mock_db_configure( 2 );
        $row = array( 0 => $this->_generate_array(array("proid", "user",
                                                        "subject", "text"),0));

        $db_q = array( 0 => ("INSERT news SET proid='%s',user_news='%s',"
                             ."subject_news='%s',text_news='%s'"),
                       1 => ("SELECT email_usr FROM auth_user,monitor "
                             ."WHERE monitor.username=auth_user.username "
                             ."AND proid='%s' AND importance='high'"),
                       2 => ("SELECT * FROM news,auth_user WHERE proid='%s' "
                             ."AND user_news=username ORDER BY "
                             ."creation_news DESC"));
        
        $db_config->add_query( sprintf( $db_q[0], $row[0]["proid"],
                                        $row[0]["user"], $row[0]["subject"], 
                                        $row[0]["text"]), 0);
        $db_config->add_query( sprintf( $db_q[2], $row[0]["proid"] ), 0);
        $db_config->add_query( sprintf( $db_q[1], $row[0]["proid"] ), 1);


        $db_config->add_num_row( 0, 0 );

        $db_config->add_record( false, 0 );
        $db_config->add_record( false, 1 );
        
        //
        // first call, no records
        //
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        news_insert( $row[0]["proid"],$row[0]["user"],$row[0]["subject"],
                     $row[0]["text"] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 67, "test 1" );
        $this->_testFor_pattern($text,("<p>There have not been posted any "
                                       ."news by the project owner[(]s[)]"
                                       ."[.]<p>\n"));

        // check that the database component did not fail
        $this->_check_db( $db_config );
    }

    function testNews_modify() {
        global $db;
        
        $db_config = new mock_db_configure( 2 );
        $row = array( 0 => $this->_generate_array(array("proid", "user",
                                                        "subject", "text",
                                                        "creation"),0));

        $db_q = array( 0 => ("UPDATE news SET user_news='%s', subject_news="
                             ."'%s', text_news='%s' WHERE proid='%s' AND "
                             ."creation_news='%s'"),
                       1 => ("SELECT email_usr FROM auth_user,monitor "
                             ."WHERE monitor.username=auth_user.username "
                             ."AND proid='%s' AND importance='high'"),
                       2 => ("SELECT * FROM news,auth_user WHERE proid='%s' "
                             ."AND user_news=username ORDER BY "
                             ."creation_news DESC"));
        
        $db_config->add_query( sprintf( $db_q[0], $row[0]["user"],
                                        $row[0]["subject"], $row[0]["text"], 
                                        $row[0]["proid"], $row[0]["creation"]),
                               0 );
        $db_config->add_query( sprintf( $db_q[1], $row[0]["proid"] ), 1);
        $db_config->add_query( sprintf( $db_q[2], $row[0]["proid"] ), 0);

        $db_config->add_num_row( 0, 0 );
        $db_config->add_record( false, 0 );
        $db_config->add_record( false, 1 );
        
        //
        // first call, no records
        //
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        news_modify( $row[0]["proid"],$row[0]["user"],$row[0]["subject"],
        $row[0]["text"], $row[0]["creation"]);
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 67, "test 1");
        $this->_testFor_pattern($text,("<p>There have not been posted any "
                                       ."news by the project owner[(]s[)]"
                                       ."[.]<p>\n"));

        // check that the database component did not fail
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
