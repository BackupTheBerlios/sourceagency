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
# $Id: TestInsertlib.inc,v 1.2 2002/02/07 12:24:17 riessen Exp $
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
        $this->_testFor_length( 2877 );

        $patterns = array( 0=>("<font color=\"#000000\"><b>New "
                               ."Project<\/b><\/font>"),
                           1=>"<form action=\"insert.php3\" method=\"POST\">",
                           2=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                               ."#FFFFFF\"><b>Project Title<\/b> [(]128[)]: "
                               ."<\/td>"),
                           3=>("<td align=\"left\" width=\"70%\" bgcolor=\""
                               ."#FFFFFF\"><input type=\"text\" name=\""
                               ."project_title\" size=\"40\" maxlength=\"128\""
                               ." value=\"this is the project_title\">"),
                           4=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                               ."#FFFFFF\"><b>Type<\/b>: <\/td>"),
                           5=>("<td align=\"left\" width=\"70%\" bgcolor=\""
                               ."#FFFFFF\"><select name=\"type\">"),
        
                           6=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                               ."#FFFFFF\"><b>Brief description<\/b> [(][*]"
                               ."[)]: <\/td>\n<!-- Column finishes -->\n<!-- "
                               ."New Column starts -->\n<td align=\"left\" "
                               ."width=\"70%\" bgcolor=\"#FFFFFF\"><textarea "
                               ."cols=\"40\" rows=\"7\" name=\"description\" "
                               ."wrap=\"virtual\" maxlength=\"255\">this is "
                               ."the description<\/textarea>\n<\/td>"),
                           7=>("<td align=\"right\" width=\"30%\" bgcolor=\""
                               ."#FFFFFF\"><b>Project Volume<\/b>: <\/td>\n"
                               ."<!-- Column finishes -->\n<!-- New Column "
                               ."starts -->\n<td align=\"left\" width=\"70%\""
                               ." bgcolor=\"#FFFFFF\"><select name="
                               ."\"volume\">"),
                           8=>("<td align=\"left\" width=\"70%\" bgcolor=\""
                               ."#FFFFFF\"><input type=\"submit\" value=\""
                               ."Submit\" name=\"Submit\">"));
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
        $this->_testFor_length( 703 );

        $patterns = array( 0=>"<font color=\"#000000\"><b>Error<\/b><\/font>",

                           1=>("<font color=\"#FF2020\">No Project Title "
                               ."specified.<br>Please select <a href=\""
                               ."insform.php3[?]description=this[+]is[+]the"
                               ."[+]description[&]type=this[+]is[+]the[+]type"
                               ."[&]volume=this[+]is[+]the[+]volume\">New "
                               ."Projects<\/a><\/font>"));
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
        $this->_testFor_length( 713 );
        
        $patterns = array( 0=>"<font color=\"#000000\"><b>Error<\/b><\/font>",
                           1=>("<font color=\"#FF2020\">No Project "
                               ."Description specified.<br>Please select <a "
                               ."href=\"insform.php3[?]project_title=this[+]is"
                               ."[+]the[+]project[+]title[&]type=this[+]is[+]"
                               ."the[+]type[&]volume=this[+]is[+]the[+]"
                               ."volume\">New Projects<\/a><\/font>"));
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
        $this->_testFor_length( 0 );


        //
        // second call, num_row == 1
        //
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        $this->assertEquals( 0, no_other_project_with_same_title( 
            $pt[1]["project_title"] ));
        $text = capture_stop_and_get();
        $this->_testFor_length( 640 );
        $this->_testFor_pattern( $text, ("<font color=\"#FF2020\">Another "
                                         ."project with that title already "
                                         ."exists.<br>Please select <a href"
                                         ."=\"insform.php3\">New Projects"
                                         ."<\/a>"));

        //
        // third call, num_row == 2
        //
        capture_reset_and_start();
        $db = new DB_SourceAgency;
        $this->assertEquals( 1, no_other_project_with_same_title( 
            $pt[2]["project_title"] ));
        $text = capture_stop_and_get();
        $this->_testFor_length( 0 );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    // TODO: need to implement the affected rows method of mock_database
    // TODO: in order to implement the test method for insert_into_database
}

define_test_suite( __FILE__ );

?>
