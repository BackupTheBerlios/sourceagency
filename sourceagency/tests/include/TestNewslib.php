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
# $Id: TestNewslib.php,v 1.9 2002/04/12 13:35:34 riessen Exp $
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
        // Called before each test method.
        // if using the capturing routines then ensure that it's reset,
        // it uses global variables
        capture_reset_text();
    }

    function testNewsform() {
        global $text, $subject, $sess;
        $text = "this is the text";
        $subject = "this is the subject";
        
        capture_start();
        newsform( "proid" ); 
        $text = capture_stop_and_get();
        $this->_testFor_length( 1811 + strlen( $sess->self_url() ));

        $ps=array( 0=> "<font color=\"#000000\"><b>Editing News<\/b><\/font>",
                   1=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF\">"
                       ."<b>Subject<\/b> [(]128[)]: <\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td "
                       ."align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\">"
                       ."<input type=\"text\" name=\"subject\" size=\"40\" "
                       ."maxlength=\"128\" value=\"this is the subject"
                       ."\">\n<\/td>\n"),
                   2=>("<form action=\""
                       . ereg_replace( "/", "\/", $sess->self_url() )
                       ."[?]proid=proid\" method=\"POST\">"),
                   3=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Body<\/b> [(][*][)]: <\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td align"
                       ."=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\">"
                       ."<textarea cols=\"40\" rows=\"7\" name=\"text\" wrap"
                       ."=\"virtual\" maxlength=\"255\">this is the text<\/"
                       ."textarea>\n<\/td>\n"),
                   4=>("<td align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF"
                       ."\"><input type=\"submit\" value=\"Preview\" "
                       ."name=\"preview\">\n<input type=\"submit\" value="
                       ."\"Submit\" name=\"submit\">\n<\/td>\n"));
        $this->_testFor_patterns( $text, $ps, 5 );

    }

    function testNews_modify_form() {
        global $text, $subject, $creation, $sess;
        $text = "this is the text";
        $subject = "this is the subject";
        $creation = "asdasd";
        
        capture_start();
        news_modify_form( "proid" ); 
        $text = capture_stop_and_get();
        $this->_testFor_length( 1865 + strlen( $sess->self_url() ));

        $ps=array( 0=>("<font color=\"#000000\"><b>Modifying News<\/b>"
                       ."<\/font>"),
                   1=>("<form action=\""
                       .ereg_replace( "/", "\/", $sess->self_url() )
                       ."[?]proid=proid\" method=\"POST\"><input type=\""
                       ."hidden\" name=\"creation\" value=\"asdasd\">"),
                   2=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Subject<\/b> [(]128[)]: <\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td align"
                       ."=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\">"
                       ."<input type=\"text\" name=\"subject\" size=\"40\" "
                       ."maxlength=\"128\" value=\"this is the subject\">\n"
                       ."<\/td>\n"),
                   3=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Body<\/b> [(][*][)]: <\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td align"
                       ."=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\">"
                       ."<textarea cols=\"40\" rows=\"7\" name=\"text\" wrap"
                       ."=\"virtual\" maxlength=\"255\">this is the "
                       ."text<\/textarea>\n<\/td>\n"),
                   4=>("<td align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\""
                       ."><input type=\"submit\" value=\"Preview\" name="
                       ."\"preview\">\n<input type=\"submit\" value=\"Submit"
                       ."\" name=\"submit\">\n<\/td>\n"));
        $this->_testFor_patterns( $text, $ps, 5 );
    }

    function testNews_preview() {
        global $subject, $text, $auth, $sess;
                
        $auth->set_uname( "username" );

        capture_start();
        news_preview( "fubar" );
        $text = capture_stop_and_get();
        $this->_testFor_length( 2575 + strlen(timestr(time()))
                                + strlen( $sess->self_url() ) );
        $ps=array( 0=>("<font color=\"#000000\"><b><center><b>PREVIEW<\/b>"
                       ."<\/center><\/b><\/font>"),
                   1=>("<tr bgcolor=\"#CCCCCC\"><td align=\"\">\n<font color="
                       ."\"#000000\"><b>News: this is the subject<\/b><\/"
                       ."font>\n<\/td><\/tr>\n"),
                   2=>("<tr bgcolor=\"#FFFFFF\"><td align=\"\"><font color="
                       ."\"#000000\">\n<b>by username<\/b> -"),
                   3=>("<tr bgcolor=\"#CCCCCC\"><td align=\"\">\n<font color"
                       ."=\"#000000\"><b>Modifying News<\/b><\/font>\n"
                       ."<\/td><\/tr>\n"),
                   4=>("<tr bgcolor=\"#FFFFFF\"><td align=\"\"><font color="
                       ."\"#000000\">\n<form action=\""
                       .ereg_replace( "/", "\/", $sess->self_url() )
                       ."[?]proid=proid\" method=\"POST\"><input type=\""
                       ."hidden\" name=\"creation\" value=\"asdasd\"><!-- "
                       ."table with 2 columns -->\n<table border=\"0\" "
                       ."cellspacing=\"0\" cellpadding=\"3\" align=\"center"
                       ."\" width=\"100%\" valign=\"top\">\n<tr colspan=\"2\""
                       .">\n"),
                   5=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Subject<\/b> [(]128[)]: <\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td align"
                       ."=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\">"
                       ."<input type=\"text\" name=\"subject\" size=\"40\" "
                       ."maxlength=\"128\" value=\"this is the subject\">\n"
                       ."<\/td>\n"),
                   6=>("<td align=\"right\" width=\"30%\" bgcolor=\"#FFFFFF"
                       ."\"><b>Body<\/b> [(][*][)]: <\/td>\n<!-- Column "
                       ."finishes -->\n<!-- New Column starts -->\n<td align"
                       ."=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\">"
                       ."<textarea cols=\"40\" rows=\"7\" name=\"text\" wrap"
                       ."=\"virtual\" maxlength=\"255\">this is the text<\/"
                       ."textarea>\n<\/td>\n"),
                   7=>("<td align=\"left\" width=\"70%\" bgcolor=\"#FFFFFF\">"
                       ."<input type=\"submit\" value=\"Preview\" name"
                       ."=\"preview\">\n<input type=\"submit\" value=\"Submit"
                       ."\" name=\"submit\">\n<\/td>\n"));

        $this->_testFor_patterns( $text, $ps, 8 );
    }

    function testNewsshow() {
        global $db;

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
        $db = new DB_SourceAgency;
        capture_start();
        newsshow( $proid[0] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 67 );
        $this->_testFor_pattern( $text, ("<p>There have not been posted any "
                                         ."news by the project owner[(]s[)]."
                                         ."<p>"));
        
        //
        // second call, one record but no comment on it.
        //
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        newsshow( $proid[1] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 722 );

        $this->_testFor_pattern( $text, ("<font color=\"#000000\"><b>News: "
                                         ."subject_news_0<\/b><\/font>"));

        $this->_testFor_pattern( $text, ("<tr bgcolor=\"#FFFFFF\"><td align"
                                         ."=\"\"><font color=\"#000000\">\n"
                                         ."<b><b>by user_news_0<\/b> - <\/b>"
                                         ."\n<p>text_news_0\n<\/font><\/td>"
                                         ."<\/tr>\n"));

        $this->_testFor_pattern( $text, ("<FONT SIZE=-1>[[] <a href=\""
                                         ."comments_edit.php3[?]proid="
                                         ."proid_1[&]type=News[&]number="
                                         ."creation_news_0[&]ref=0[&]subject="
                                         ."Re%3Asubject_news_0\">Comment "
                                         ."This News!<\/a>\n []]<\/FONT>\n"));
        //
        // third call, two records, the second has a comment which makes
        // a recursive call to show_comments_on_it which in turn has no rows
        //
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        newsshow( $proid[2] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 1591 );

        $this->_testFor_pattern( $text, ("<font color=\"#000000\"><b>News: "
                                         ."subject_news_1<\/b><\/font>"));

        $this->_testFor_pattern( $text, ("<tr bgcolor=\"#FFFFFF\"><td align"
                                         ."=\"\"><font color=\"#000000\">\n"
                                         ."<b><b>by user_news_1<\/b> - <\/b>"
                                         ."\n<p>text_news_1\n<\/font><\/td>"
                                         ."<\/tr>\n"));

        $this->_testFor_pattern( $text, ("<FONT SIZE=-1>[[] <a href=\""
                                         ."comments_edit.php3[?]proid="
                                         ."proid_2[&]type=News[&]number="
                                         ."creation_news_1[&]ref=0[&]subject="
                                         ."Re%3Asubject_news_1\">Comment "
                                         ."This News!<\/a>\n []]<\/FONT>\n"));
        
        $this->_testFor_pattern( $text, ("<font color=\"#000000\"><b>News: "
                                         ."subject_news_2<\/b><\/font>"));
        
        $this->_testFor_pattern( $text, ("<tr bgcolor=\"#FFFFFF\"><td align"
                                         ."=\"\"><font color=\"#000000\">\n"
                                         ."<b><b>by user_news_2<\/b> - <\/b>"
                                         ."\n<p>text_news_2\n<\/font><\/td>"
                                         ."<\/tr>\n"));

        $this->_testFor_pattern( $text, ("<FONT SIZE=-1>[[] <a href=\""
                                         ."comments_edit.php3[?]proid="
                                         ."proid_2[&]type=News[&]number="
                                         ."creation_news_2[&]ref=0[&]subject="
                                         ."Re%3Asubject_news_2\">Comment "
                                         ."This News!<\/a>\n []]<\/FONT>\n"));
        
        $this->_testFor_pattern( $text, ("<li><a href=\"comments.php3[?]"
                                         ."proid=proid_2[&]type=News[&]"
                                         ."number=creation_news_2[&]ref=0\">"
                                         ."subject_cmt_3<\/a>\n by <b>"
                                         ."user_cmt_3<\/b> on <b><\/b>\n"));
        
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
        capture_start();
        news_insert( $row[0]["proid"],$row[0]["user"],$row[0]["subject"],
                     $row[0]["text"] );
        $text = capture_stop_and_get();
        $this->_testFor_length( 67 );
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
        capture_start();
        news_modify( $row[0]["proid"],$row[0]["user"],$row[0]["subject"],
        $row[0]["text"], $row[0]["creation"]);
        $text = capture_stop_and_get();
        $this->_testFor_length( 67 );
        $this->_testFor_pattern($text,("<p>There have not been posted any "
                                       ."news by the project owner[(]s[)]"
                                       ."[.]<p>\n"));

        // check that the database component did not fail
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
