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
# include/milestoneslib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestMilestoneslib.inc,v 1.27 2002/07/17 12:40:49 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

include_once( 'lib.inc' );
include_once( 'html.inc' );
include_once( 'box.inc' );
include_once( 'security.inc' );
include_once( 'milestoneslib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "session.inc" );
    $GLOBALS[ 'sess' ] = new Session;
    
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

class UnitTestMilestoneslib
extends UnitTest
{
    var $queries;

    function UnitTestMilestoneslib( $name ) {
        $this->queries = array( 
            'select_milestone_number' =>
            ("SELECT number FROM milestones WHERE proid='%s' ORDER BY number"),
            'milestones_insert_1' =>
            ("INSERT milestones SET proid='%s',devid='%s',number='%s',goals"
             ."='%s',release='%s',product='%s',payment='%s',status='P',"
             . "creation=NOW()"),
            'milestones_insert_2' =>
            ("SELECT * FROM milestones WHERE number='%s' AND proid='%s'"),
            'show_milestones_1' =>
            ("SELECT * FROM sponsoring WHERE proid='%s' AND sponsor='%s'"),
            'show_milestones_2' =>
            ("SELECT developer FROM developing WHERE proid='%s' AND "
             ."devid='%s'"),
            'show_milestones_3' =>
            ("SELECT * FROM milestones WHERE proid='%s'  AND devid='%s' "
             ."ORDER BY number"),
            'show_milestones_4' =>
            ("SELECT * FROM milestones WHERE proid='%s' AND status='A' AND "
             ."devid='%s' ORDER BY number"),
            'select_milestone_payment' =>
            ("SELECT payment,number FROM milestones WHERE proid='%s' AND "
             ."devid='%s'"),
            'milestones_modify' =>
            ("UPDATE milestones SET goals='%s',release='%s',product='%s',"
             ."payment='%s',status='M' WHERE proid='%s' AND number='%s'"));
        
        $this->UnitTest( $name );
    }

    function setup() {
    }
    
    function tearDown() {
        // ensure that the next test does not have a predefined global
        // database object
        unset_global( 'db', 'bx' );
    }

    function testMilestones_propose_milestone() {
        global $t;

        $pats = array( 0=> ("<p align=right>[[] "),
                       1=> (" []]<\/p>") );
        //
        // test 1, proid is empty
        //
        $proid = '';
        capture_reset_and_start();
        milestones_propose_milestone( $proid );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_html_link( 'step3_edit.php3',
                                   array( 'proid'=>$proid, 'a'=>'1' ),'<b>'
                                   .$t->translate("Propose Milestones")
                                   .'</b>');
        $this->_testFor_string_length( 98);
        $this->_testFor_patterns( $pats, 2 );
        
        //
        // test 2 proid is defined
        //
        $proid = 'this is the proid';
        capture_reset_and_start();
        milestones_propose_milestone( $proid );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );

        $this->_testFor_html_link( 'step3_edit.php3',
                                   array( 'proid'=>$proid, 'a'=>'1' ),'<b>'
                                   .$t->translate("Propose Milestones")
                                   .'</b>');
        $this->_testFor_string_length( 115);
        $this->_testFor_patterns( $pats, 2 );
    }

    function testShow_milestones() {
        global $bx, $t;

        $db_config = new mock_db_configure( 6 );
        $db_q = array( 0 => $this->queries['show_milestones_1'],
                       1 => $this->queries['show_milestones_2'],
                       2 => $this->queries['show_milestones_3'],
                       3 => $this->queries['show_milestones_4']);

        $dat = array(0 => $this->_generate_array(array("proid","devid",
                                                       "sponsor"), 0),
                     1 => $this->_generate_array(array("proid","devid",
                                                       "sponsor"), 1),
                     2 => $this->_generate_array(array("proid","devid",
                                                       "sponsor"), 2),
                     3 => $this->_generate_array(array("number","release",
                                                       "product","payment",
                                                       "status","goals"), 3),
                     4 => $this->_generate_array(array("number","release",
                                                       "product","payment",
                                                       "status","goals"), 4));
        // first call
        $db_config->add_query( sprintf( $db_q[0], $dat[0]["proid"],
                                        $dat[0]["sponsor"]), 0 );
        $db_config->add_query( sprintf( $db_q[1], $dat[0]["proid"],
                                        $dat[0]["devid"]), 0 );
        $db_config->add_query( sprintf( $db_q[2], $dat[0]["proid"],
                                        $dat[0]["devid"]), 1 );
        // second call
        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"],
                                        $dat[1]["sponsor"]), 2 );
        $db_config->add_query( sprintf( $db_q[1], $dat[1]["proid"],
                                        $dat[1]["devid"]), 2 );
        $db_config->add_query( sprintf( $db_q[3], $dat[1]["proid"],
                                        $dat[1]["devid"]), 3 );
        // third call
        $db_config->add_query( sprintf( $db_q[0], $dat[2]["proid"],
                                        $dat[2]["sponsor"]), 4 );
        $db_config->add_query( sprintf( $db_q[1], $dat[2]["proid"],
                                        $dat[2]["devid"]), 4 );
        $db_config->add_query( sprintf( $db_q[3], $dat[2]["proid"],
                                        $dat[2]["devid"]), 5 );

        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( 0, 1 );
        $db_config->add_record(array("developer" => $dat[0]["sponsor"]),0);

        $db_config->add_num_row( 0, 2 );
        $db_config->add_num_row( 0, 3 );
        $db_config->add_record( array("developer" => $dat[0]["sponsor"]),2);

        $db_config->add_num_row( 0, 4 );
        $db_config->add_num_row( 2, 5 );
        $db_config->add_record( array("developer" => $dat[0]["sponsor"]),4);

        $dat[3]["number"] = 0;
        $dat[4]["number"] = 1;
        $db_config->add_record( $dat[3], 5 );
        $db_config->add_record( $dat[4], 5 );

        //
        // first call, the status part of the third query should be reset
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        show_milestones( $dat[0]["proid"], $dat[0]["devid"],
                         $dat[0]["sponsor"] );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 0 );

        //
        // second call, the status part of the third query is present
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        show_milestones( $dat[1]["proid"], $dat[1]["devid"],
                         $dat[1]["sponsor"] );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 0 );

        //
        // third call, two records of data
        //
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        show_milestones( $dat[2]["proid"], $dat[2]["devid"],
                         $dat[2]["sponsor"] );
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 3' );
        $this->_testFor_string_length( 4782 );
        
        $this->_testFor_box_column('center','7%','','<b>'
                                        .$t->translate('Number').'</b>');
        $this->_testFor_box_column('','53%','','<b>'
                                        .$t->translate('Goals').'</b>');
        $this->_testFor_box_column('center','16%','','<b>'
                                        .$t->translate('Release Date').'</b>');
        $this->_testFor_box_column('center','12%','','<b>'
                                        .$t->translate('Product').'</b>');
        $this->_testFor_box_column('center','8%','','<b>'
                                        .$t->translate('Payment').'</b>');
        $this->_testFor_box_column('center','14%','','<b>'
                                        .$t->translate('Status').'</b>');
        
        // TODO: need to check the rest of the table

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testMilestones_insert() {
        global $db, $t;

        $db_config = new mock_db_configure( 4 );
        
        $db_q = array( 0 => $this->queries['milestones_insert_1'],
                       1 => $this->queries['milestones_insert_2']);

        $ln = ( "<p align=right>[[] <a href=\"step3_edit[.]php3[?]proid=%s"
                ."[&]a=1\" class=\"\"><b>Propose Milestones<\/b><\/a> []]"
                ."<\/p>" );

        $dat = $this->_generate_records( array("pid","dev","num","goals",
                                               "rday", "rmon", "ryear",
                                               "prod","pay"), 4);
        //
        // test 1 -- everything is fine
        //
        $db_config->add_query( sprintf( $db_q[1], $dat[0]["num"],
                                        $dat[0]["pid"]), 0 );
        $db_config->add_query( sprintf( $db_q[0], $dat[0]["pid"],
                                        $dat[0]["dev"], $dat[0]["num"], 
                                        $dat[0]["goals"], 
                           date_to_timestamp( $dat[0]["rday"], $dat[0]["rmon"],
                           $dat[0]["ryear"]), $dat[0]["prod"], $dat[0]["pay"]),
                               0 );
        $db_config->add_num_row( 0, 0 );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        milestones_insert( $dat[0]["pid"], $dat[0]["dev"], $dat[0]["num"], 
                           $dat[0]["goals"], $dat[0]["rday"], $dat[0]["rmon"],
                           $dat[0]["ryear"], $dat[0]["prod"], $dat[0]["pay"]);
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 103 );
        $pats = array( 0 => (sprintf( $ln, $dat[0]["pid"])));
        $this->_testFor_patterns( $pats, 1 );

        //
        // test 2 -- goals is not set and therefore no database insert happens
        //
        $dat[1]["goals"] = "";

        $db_config->add_query( sprintf( $db_q[1], $dat[1]["num"], 
                                        $dat[1]["pid"]), 1 );
        $db_config->add_num_row( 0, 1 );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        milestones_insert( $dat[1]["pid"], $dat[1]["dev"], $dat[1]["num"], 
                           $dat[1]["goals"], $dat[1]["rday"], $dat[1]["rmon"],
                           $dat[1]["ryear"], $dat[1]["prod"], $dat[1]["pay"]);
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 795 );
        $pats = array( 0 => (sprintf( $ln, $dat[1]["pid"])),
                       1 => $t->translate("Insert Failed" ),
                       2 => $t->translate("No Goals defined."));
        $this->_testFor_patterns( $pats, 3 );

        //
        // test 3 -- number used is already taken
        //
        $db_config->add_query( sprintf( $db_q[1], $dat[2]["num"],
                                        $dat[2]["pid"]), 2 );
        $db_config->add_num_row( 1, 2 );

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        milestones_insert( $dat[2]["pid"], $dat[2]["dev"], $dat[2]["num"], 
                           $dat[2]["goals"], $dat[2]["rday"], $dat[2]["rmon"],
                           $dat[2]["ryear"], $dat[2]["prod"], $dat[2]["pay"]);
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 3' );
        $this->_testFor_string_length( 810 );

        $pats = array( 0 => (sprintf( $ln, $dat[2]["pid"])),
                       1 => $t->translate("Insert Failed" ),
                       2 => $t->translate("Number parameter already in use."));
        $this->_testFor_patterns( $pats, 3 );

        //
        // test 4 -- number parameter is not set, no queries
        //
        $dat[3]["num"] = "";

        $db = new DB_SourceAgency;
        capture_reset_and_start();
        milestones_insert( $dat[3]["pid"], $dat[3]["dev"], $dat[3]["num"], 
                           $dat[3]["goals"], $dat[3]["rday"], $dat[3]["rmon"],
                           $dat[3]["ryear"], $dat[3]["prod"], $dat[3]["pay"]);
        $this->set_text( capture_stop_and_get() );
        $this->set_msg( 'test 4' );
        $this->_testFor_string_length( 807 );
        $pats = array( 0 => (sprintf( $ln, $dat[3]["pid"])),
                       1 => $t->translate("Insert Failed" ),
                       2 => $t->translate("Number parameter was not set."));
        $this->_testFor_patterns( $pats, 3 );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testSelect_milestone_number() {
        $db_config = new mock_db_configure( 3 );

        $db_q = array( 0 => $this->queries['select_milestone_number']);

        $dat = $this->_generate_records( array("proid","devid"), 3 );
        
        for ( $idx = 0; $idx < 3; $idx++ ) {
            $db_config->add_query( sprintf( $db_q[0], $dat[$idx]["proid"]), 
                                   $idx );
        }
        for ( $idx = 0; $idx < 11; $idx++ ) {
            $db_config->add_num_row( 4, 0 );
        }
        for ( $idx = 0; $idx < 7; $idx++ ) {
            $db_config->add_num_row( 0, 1 );
            $db_config->add_num_row( 0, 2 );
        }

        $db_config->add_record(array("number" => 0),0);
        $db_config->add_record(array("number" => 1),0);
        $db_config->add_record(array("number" => 2),0);
        $db_config->add_record(array("number" => 3),0);

        //
        // first call, four values
        //
        $this->set_text( select_milestone_number( $dat[0]["proid"], 
                                                       $dat[0]["devid"],0));
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 212 );
        for ( $idx = 4; $idx < 10; $idx++ ) {
            $this->set_msg( 'test 1: idx='.$idx );
            $this->_testFor_pattern(("<option value=\"".$idx."\">".$idx));
        }

        //
        // second call, no values
        //
        $this->set_text( select_milestone_number( $dat[1]["proid"], 
                                                          $dat[1]["devid"],0));
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 185 );
        for ( $idx = 1; $idx < 6; $idx++ ) {
            $this->set_msg( 'test 2: idx = '.$idx );
            $this->_testFor_pattern("<option value=\"".$idx."\">".$idx);
        }

        //
        // third call, no values but a selected option
        //
        $this->set_text( select_milestone_number( $dat[2]["proid"], 
                                                      $dat[2]["devid"],5));
        $this->set_msg( 'test 3' );
        $this->_testFor_string_length( 194 );
        $this->_testFor_pattern( "<option selected value=\"5\">5" );
        for ( $idx = 1; $idx < 5; $idx++ ) {
            $this->set_msg( 'test 3: idx = '.$idx );
            $this->_testFor_pattern("<option value=\"".$idx."\">".$idx);
        }

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testSelect_milestone_payment() {
        $db_config = new mock_db_configure( 3 );

        $db_q = array( 0 => $this->queries['select_milestone_payment']);

        $args = $this->_generate_records( array("proid","devid","selected",
                                                "number"), 3 );
        $dat = $this->_generate_records( array( "number", "payment" ), 6 );

        $db_config->add_query( sprintf( $db_q[0], $args[0]["proid"],
                                        $args[0]["devid"]), 0 );
        $db_config->add_query( sprintf( $db_q[0], $args[1]["proid"],
                                        $args[1]["devid"]), 1 );
        $db_config->add_query( sprintf( $db_q[0], $args[2]["proid"],
                                        $args[2]["devid"]), 2 );

        // we do the following assignments to check the if statement:
        //     	if ($db->f("number")!=$number) {
        // if the if fails, then $max value will be set to zero.

        // instance 0 (test 1): $max = 4
        $dat[0]["number"] = $args[0]["number"];
        $dat[1]["number"] = "dont_match" . $args[0]["number"];
        $dat[0]["payment"] = 4;
        $dat[1]["payment"] = 96; 
        $db_config->add_record( $dat[0], 0 );
        $db_config->add_record( $dat[1], 0 );

        // instance 1 (test 2): $max = 50
        $dat[2]["number"] = $args[1]["number"];
        $dat[3]["number"] = "dont_match" . $args[1]["number"];
        $dat[2]["payment"] = 50;
        $dat[3]["payment"] = 50; 
        $db_config->add_record( $dat[2], 1 );
        $db_config->add_record( $dat[3], 1 );

        // instance 2 (test 3): $max = 100
        $dat[4]["number"] = $args[2]["number"];
        $dat[5]["number"] = "dont_match" . $args[2]["number"];
        $dat[4]["payment"] = 100;
        $dat[5]["payment"] = 0; 
        $db_config->add_record( $dat[4], 2 );
        $db_config->add_record( $dat[5], 2 );

        // test 1
        $args[0]["selected"] = 4;
        $this->set_text( call_user_func_array('select_milestone_payment',
                                              $args[0]));
        $this->set_msg( 'test 1' );
        $this->_testFor_string_length( 116 );
        for ( $i = 2; $i < 4; $i+=2 ) {
            $this->_testFor_pattern("option value=\"" . $i . "\">". $i ."%");
        }
        $this->_testFor_pattern( "option selected value=\"4\">4%" );

        // test 2
        $args[1]["selected"] = 50;
        $this->set_text( call_user_func_array('select_milestone_payment',
                                              $args[1]));
        $this->set_msg( 'test 2' );
        $this->_testFor_string_length( 802 );
        for ( $i = 2; $i < 50; $i+=2 ) {
            $this->_testFor_pattern("option value=\"" . $i ."\">". $i . "%");
        }
        $this->_testFor_pattern( "option selected value=\"50\">50%" );

        // test 3
        $args[2]["selected"] = 100;
        $this->set_text( call_user_func_array('select_milestone_payment',
                                              $args[2]));
        $this->_testFor_string_length( 1554 );
        for ( $i = 2; $i < 100; $i+=2 ) {
            $this->_testFor_pattern("option value=\"" . $i . "\">". $i . "%");
        }
        $this->_testFor_pattern( "option selected value=\"100\">100%" );

        // finally check that everything went smoothly with the DB
        $this->_check_db( $db_config );
    }

    function testForm_milestones() {
        global $auth, $bx, $t, $sess;
        global $number, $goals, $release_day, $release_month,
            $release_year, $product, $payment;
        
        $qs=array( 0 => $this->queries['select_milestone_payment'],
                   1 => $this->queries['select_milestone_number']);
        $db_config = new mock_db_configure( 4 );
        
        $number = 'this st the number';
        $goals = 'these are the goals';
        $release_day = 21;
        $release_month = 11;
        $release_year = 2002;
        $product = 'thsi si the projdty';
        $payment = 'this isthe patment';
        $args=$this->_generate_array( array( 'proid', 'devid' ), 10 );
        
        /** select_milestone_number call **/
        $db_config->add_query( sprintf( $qs[1], $args['proid']), 0 );
        $db_config->add_query( sprintf( $qs[1], $args['proid']), 2 );
        for ( $idx = 0; $idx < 7; $idx++ ) {
            $db_config->add_num_row( 0, 0 );
            $db_config->add_num_row( 0, 2 );
        }
        /** select_milestone_payment call **/
        $db_config->add_query(sprintf($qs[0],$args['proid'],$args['devid']),1);
        $db_config->add_query(sprintf($qs[0],$args['proid'],$args['devid']),3);
        $db_config->add_record( false, 1 );
        $db_config->add_record( false, 3 );
        
        $bx = $this->_create_default_box();
        $this->capture_call( 'form_milestones', 
                             7129 + strlen( $sess->self_url() ), $args );
        $this->_checkFor_a_box( 'Proposing milestones' );
        $this->_checkFor_a_form( 'PHP_SELF', array('proid' => $args['proid']),
                                 'POST');
        $this->_testFor_html_form_hidden('devid',$args['devid']);
        $this->_checkFor_columns( 2 );
        $this->_checkFor_submit_preview_buttons();
        $tv=array( select_milestone_number($args['proid'],$args['devid'],
                                              $number)
                   => array( 'Number', '<b>%s</b>: ' ),
                   html_textarea('goals',40,7,'virtual',255, $goals)
                   => array( 'Goals', '<b>%s</b> (255): '),
                   select_date('release',$release_day,$release_month,
                               $release_year)
                   => array( 'Release Date', '<b>%s</b>: ' ),
                   select_from_config('product','milestone_product_array',
                                      $product)
                   => array( 'Product', '<b>%s</b>: ' ),
                   select_milestone_payment($args['proid'],
                                               $args['devid'],$payment,'')
                   => array( 'Payment', '<b>%s %%</b>: ' ));
        while ( list( $val, $key ) = each ( $tv ) ) {
            $this->_checkFor_column_titles( array( $key[0] ), 'right', 
                                            '30%','', $key[1] );
            $this->_checkFor_column_values( array( $val ) );
        }
        
        $this->_check_db( $db_config ); 
    }

    function testMilestones_modify() {
        global $db, $t;

        $fname = 'milestones_modify';
        $qs = array( 0 => $this->queries[ $fname ] );

        $db_config = new mock_db_configure( 1 );

        $args=$this->_generate_array( array('proid', 'number', 'goals',
                                            'release_day', 'release_month',
                                            'release_year', 'product',
                                            'payment'), 10 );
        // test one
        $release = date_to_timestamp($args['release_day'],
                                 $args['release_month'],$args['release_year']);

        $db_config->add_query( sprintf( $qs[0], $args['goals'], $release,
                                          $args['product'], $args['payment'], 
                                          $args['proid'], $args['number']), 0);
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 106, $args );

        $str = ( '<p align=right>[ '
                 .html_link('step3_edit.php3',
                            array('proid' => $args['proid'],'a' => '1'),
                            '<b>'.$t->translate('Propose Milestones')
                            .'</b>').' ]</p>');
        $this->assertEquals( $str, $this->get_text() );
        $this->_check_db( $db_config );
    }

    function testMilestones_modify_form() {
        global $bx, $t, $sess;
        global $number, $goals, $release_day, $release_month,
            $release_year, $product, $payment, $creation;
        
        $number = 'this sit he number';
        $goals = 'this is the goals';
        $release_day = 'thsi siteh releasae fay';
        $release_month = 'this is the release month';
        $release_year = 'this is the release year';
        $product = 'tjhsi is the product';
        $payment = 'thjs isther payment';
        $creation = 'tjhsi site creation';

        $db_config = new mock_db_configure( 4 );
        $qs = array( 0 => $this->queries[ 'select_milestone_number' ],
                     1 => $this->queries[ 'select_milestone_payment' ] );
        $args = $this->_generate_array( array( 'proid', 'devid' ), 10 );

        /** select milestone payment, two calls **/
        $db_config->add_query(sprintf($qs[1],$args['proid'],$args['devid']),1);
        $db_config->add_record( false, 1 );
        $db_config->add_query(sprintf($qs[1],$args['proid'],$args['devid']),3);
        $db_config->add_record( false, 3 );
        /** select milestone number, two calls **/
        $db_config->add_query( sprintf( $qs[0], $args['proid'] ), 0 );
        $db_config->add_query( sprintf( $qs[0], $args['proid'] ), 2 );
        for ( $idx = 0; $idx < 7; $idx++ ) {
            $db_config->add_num_row( 0, 0 );
            $db_config->add_num_row( 0, 2 );
        }

        $bx = $this->_create_default_box();
        $this->capture_call( 'milestones_modify_form', 
                             7168 + strlen( $sess->self_url() ), $args );

        $this->_checkFor_a_form( 'PHP_SELF', array('proid'=>$args['proid']),
                                 'POST' );
        $this->_checkFor_a_box( 'Modifying Milestones' );
        $this->_testFor_html_form_hidden( 'creation', $creation );
        $this->_testFor_html_form_hidden( 'devid', $args['devid'] );
        $this->_checkFor_columns( 2 );
        $this->_checkFor_submit_preview_buttons();

        $tv = array( select_milestone_number($args['proid'],$args['devid'],
                                                    $number)
                     => array( 'Number', '<b>%s</b>: ' ),
                     html_textarea('goals',40,7,'virtual',255,$goals)
                     => array( 'Goals', '<b>%s</b> (255): ' ),
                     select_date('release',$release_day,$release_month,
                                 $release_year)
                     => array( 'Release Date', '<b>%s</b>: ' ),
                     select_from_config('product','milestone_product_array',
                                        $product)
                     => array( 'Product', '<b>%s</b>: ' ),
                     select_milestone_payment($args['proid'],$args['devid'],
                                                         $payment,$number)
                     => array( 'Payment', '<b>%s</b>: ' ));
        while( list( $val, $key ) = each( $tv ) ) {
            $this->_checkFor_column_titles( array( $key[0] ), 'right', '30%',
                                            '', $key[1] );
            $this->_checkFor_column_values( array( $val ) );
        }
        $this->_check_db( $db_config );
    }

    function testMilestones_preview() {
        global $t, $bx;
        global $number, $goals, $release_day, $release_month,
            $release_year, $product, $payment;

        $number = 'this ist he number';
        $goals = 'this sithe goals';
        $release_day = 'this is the rlease day';
        $release_month = 'this sithe release month';
        $release_year = 'tjhis ist he rrealace year';
        $product = 'this sithe priodut';
        $payment = 'thius si the patment';

        $args=$this->_generate_array( array( 'proid', 'devid' ), 10 );

        $bx = $this->_create_default_box();
        $this->capture_call( 'milestones_preview', 3146, $args );

        $this->_checkFor_a_box( 'PREVIEW', '<center><b>%s</b></center>' );
        $this->_checkFor_a_box( 'Proposing Milestones' );
        $this->_checkFor_columns( 6 );
        $tStamp = mktimestamp(date_to_timestamp($release_day,
                                              $release_month, $release_year));
        $tv = array( 1=>array( array( 'Number', 'center', '7%' ),
                               array( "<b>$number</b>", 'center' )),
                     2=>array( array( 'Goals', '', '53%' ),
                               array( "<b>$goals</b>", '' )),
                     3=>array( array( 'Release Date', 'center', '16%' ),
                               array( '<b>'.timestr_middle($tStamp).'</b>', 
                                      'center') ),
                     4=>array( array( 'Product', 'center', '12%' ),
                               array( "<b>$product</b>", 'center' )),
                     5=>array( array( 'Payment', 'center', '8%' ),
                               array( "<b>$payment %</b>", 'center' ) ),
                     6=>array( array( 'Status', 'center', '14%' ),
                               array( "<b>".$t->translate( 'Proposed').
                                      "</b>",'center')));
        while ( list( , $v ) = each( $tv ) ) {
            $key = $v[0];
            $val = $v[1];
            $this->_testFor_box_column( $key[1], $key[2], '',
                              sprintf( '<b>%s</b>', $t->translate( $key[0] )));
            $this->_testFor_box_column( $val[1], '', 'gold', $val[0] );
        }
    }
}

define_test_suite( __FILE__ );
?>
