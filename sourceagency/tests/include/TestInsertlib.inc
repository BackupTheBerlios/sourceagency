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
# include/insertlib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestInsertlib.inc,v 1.4 2002/04/24 16:39:05 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( 'box.inc' );
    $bx = new box;

    include_once( 'session.inc' );
    $sess = new session;
    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");
} 

include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'insertlib.inc' );

class UnitTestInsertlib
extends UnitTest
{
    function UnitTestInsertlib( $name ) {
        $this->UnitTest( $name );
    }

    function testInsert_form() {
        global $volume, $type, $description, $project_title;

        $volume = "this is the volume";
        $type = "this is the type";
        $description = "this is the description";
        $project_title = "this is the project_title";

        //
        // first and only call....
        //
        capture_reset_and_start();
        insert_form();
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 3605, "test 1" );

        $patterns = array( 0=>("<font color=\"#000000\"><b>New "
                               ."Project<\/b><\/font>"),
                           1=>"<form action=\"insert.php3\" method=\"POST\">",
                           2=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                               ."#FFFFFF\">[ \n]+<b>Project Title<\/b> "
                               ."[(]128[)]:[ \n]+<\/td>"),
                           3=>("<td align=\"left\" width=\"70%\" bgcolor=\""
                               ."#FFFFFF\">[ \n]+<input type=\"text\" name=\""
                               ."project_title\" size=\"40\" maxlength=\"128\""
                               ." value=\"this is the project_title\">"),
                           4=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                               ."#FFFFFF\">[ \n]+<b>Type<\/b>:[ \n]+<\/td>"),
                           5=>("<td align=\"left\" width=\"70%\" bgcolor=\""
                               ."#FFFFFF\">[ \n]+<select name=\"type\" "
                               ."size=\"0\">"),
        
                           6=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                               ."#FFFFFF\">[ \n]+<b>Brief description<\/b> "
                               ."[(][*][)]:[ \n]+<\/td>[ \n]+<!-- Column "
                               ."finishes -->[ \n]+<!-- "
                               ."New Column starts -->[ \n]+<td align="
                               ."\"left\" width=\"70%\" bgcolor=\"#FFFFFF\">"
                               ."[ \n]+<textarea "
                               ."name=\"description\" cols=\"40\" rows=\"7\" "
                               ."wrap=\"virtual\" maxlength=\"255\">this is "
                               ."the description<\/textarea>[ \n]+<\/td>"),
                           7=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                               ."#FFFFFF\">[ \n]+<b>Project Volume<\/b>:"
                               ."[ \n]+<\/td>[ \n]+"
                               ."<!-- Column finishes -->[ \n]+<!-- "
                               ."New Column "
                               ."starts -->[ \n]+<td align=\"left\" "
                               ."width=\"70%\""
                               ." bgcolor=\"#FFFFFF\">[ \n]+<select name="
                               ."\"volume\" size=\"0\">"),
                           8=>("<td align=\"left\" width=\"70%\" bgcolor=\""
                               ."#FFFFFF\">[ \n]+<input type=\"submit\" "
                               ."value=\"Submit\" name=\"Submit\">"));
        $this->_testFor_patterns( $text, $patterns, 9 );
    }

    function testProject_title_not_set() {
        global $description, $type, $volume;
        
        $type = "this is the type";
        $volume = "this is the volume";
        $description = "this is the description";

        capture_reset_and_start();
        project_title_not_set();
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 816, "test 1" );

        $patterns = array( 0=>"<font color=\"#000000\"><b>Error<\/b><\/font>",

                           1=>("<font color=\"#FF2020\">[ \n]+No Project Title"
                               ." specified.<br>Please select <a href=\""
                               ."insform.php3[?]description=this[+]is[+]the"
                               ."[+]description[&]type=this[+]is[+]the[+]type"
                               ."[&]volume=this[+]is[+]the[+]volume\" "
                               ."class=\"\">New "
                               ."Projects<\/a>[ \n]+<\/font>"));
        $this->_testFor_patterns( $text, $patterns, 2 );
    }

    function testDescription_not_set() {
        global $project_title, $type, $volume;

        $type = "this is the type";
        $volume = "this is the volume";
        $project_title = "this is the project title";

        capture_reset_and_start();
        description_not_set();
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 826, "test 1" );
        
        $patterns = array( 0=>"<font color=\"#000000\"><b>Error<\/b><\/font>",
                           1=>("<font color=\"#FF2020\">[ \n]+No Project "
                               ."Description specified.<br>Please select <a "
                               ."href=\"insform.php3[?]project_title=this[+]is"
                               ."[+]the[+]project[+]title[&]type=this[+]is[+]"
                               ."the[+]type[&]volume=this[+]is[+]the[+]"
                               ."volume\" class=\"\">New Projects"
                               ."<\/a>[ \n]+<\/font>"));
        $this->_testFor_patterns( $text, $patterns, 2 );
    }

    function testNo_other_project_with_same_title() {
        global $db;

        $db_config = new mock_db_configure( 3 );

        $db_q = array( 0 => ("SELECT * FROM description WHERE project_title"
                             ."='%s'") );

        $pt = array(0=>$this->_generate_array(array("project_title"),0),
                    1=>$this->_generate_array(array("project_title"),1),
                    2=>$this->_generate_array(array("project_title"),2));

        $db_config->add_query( sprintf( $db_q[0], $pt[0]["project_title"] ),0);
        $db_config->add_query( sprintf( $db_q[0], $pt[1]["project_title"] ),1);
        $db_config->add_query( sprintf( $db_q[0], $pt[2]["project_title"] ),2);

        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_num_row( 2, 2 );

        //
        // first call, num rows == 0
        //
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        $this->assertEquals( 1, no_other_project_with_same_title( 
            $pt[0]["project_title"] ));
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0, "test 1" );


        //
        // second call, num_row == 1
        //
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        $this->assertEquals( 0, no_other_project_with_same_title( 
            $pt[1]["project_title"] ));
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 752, "test 2" );
        $this->_testFor_pattern( $text, ("<font color=\"#FF2020\">[ \n]+"
                                         ."Another "
                                         ."project with that title already "
                                         ."exists.<br>Please select <a href"
                                         ."=\"insform.php3\" class=\"\">"
                                         ."New Projects<\/a>"), "test 2");

        //
        // third call, num_row == 2
        //
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        $this->assertEquals( 1, no_other_project_with_same_title( 
            $pt[2]["project_title"] ));
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0, "test 3" );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    // TODO: need to implement the affected rows method of mock_database
    // TODO: in order to implement the test method for insert_into_database
}

define_test_suite( __FILE__ );

?>
