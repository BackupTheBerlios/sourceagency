<?php
// TestDevelopinglib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestDevelopinglib.php,v 1.5 2002/06/26 09:57:26 riessen Exp $

include_once( '../constants.php' );

include_once( 'html.inc' );
include_once( 'developinglib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

class UnitTestDevelopinglib
extends UnitTest
{
    function UnitTestDevelopinglib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset_global( 'db', 'bx' );
    }

    function testDeveloping_select_cooperation() {
        global $t;
        $func_name = 'developing_select_cooperation';
        $v = array( 'No', 'no', 'yes', 'Yes', 'Yes Please', 'NO', 'YES' );
        while ( list( , $val ) = each( $v ) ) {
            $this->push_msg( 'Test '. $val );
            $this->set_text( $this->capture_call( $func_name, 0,array(&$val)));
            $this->_testFor_html_select( 'cooperation' );
            $this->_testFor_html_select_option( 'No', ($val=='No'), 
                                                $t->translate('No'));
            $this->_testFor_html_select_end();
            $this->_testFor_string_length( ($val=='No'? 93 : 84 ) );
            $this->pop_msg();
        }
    }

    function testSelect_duration() {
        $func_name = 'select_duration';
        for ( $idx = -10; $idx < 110; $idx++ ) {
            $this->push_msg( 'Test ' . $idx );
            $this->set_text($this->capture_call($func_name,0,array(&$idx)));
            // if something is selected, then strings is longer
            $this->_testFor_string_length(($idx<1||$idx>100 ? 2936:2945));
            $this->_testFor_html_select( 'duration' );
            for ( $jdx = 1; $jdx < 101; $jdx++ ) {
                $this->_testFor_html_select_option( $jdx, ($jdx==$idx), $jdx );
            }
            $this->_testFor_html_select_end();
            $this->pop_msg();
        }
    }

    function testShow_developings() {
        global $db, $bx, $t;
        
        $db_config = new mock_db_configure( 2 );
        $q=("SELECT * FROM developing,auth_user WHERE proid='%s' AND "
            ."content_id='%s' AND developer=username ORDER BY "
            ."developing.creation DESC");
        $args=$this->_generate_records( array('proid','content_id'), 10 );
        $d=$this->_generate_records( array('creation','username','cost',
                                           'license','cooperation','status',
                                           'valid','start','duration'),10);
        
        $db_config->add_query( sprintf( $q, $args[0]['proid'], 
                                                $args[0]['content_id']), 0 );
        $db_config->add_num_row( 0, 0 );
        $db_config->add_query( sprintf( $q, $args[1]['proid'], 
                                                $args[1]['content_id']), 1 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_record( $d[0], 1 );
        $db_config->add_record( false, 1 );

        // test one: no records
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'show_developings', 71, $args[0] );
        $this->assertEquals("There have not been posted any developement "
                            ."proposals to this project.\n",$this->get_text());
        
        // test two: one record
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'show_developings', 916
                             + strlen(timestr(mktimestamp($d[0]['creation']))),
                             $args[1] );
        $this->_checkFor_a_box( 'Developing Proposal' );
        $this->_testFor_lib_nick( $d[0]['username'] );
        $str = ' - '.timestr( mktimestamp( $d[0]['creation' ] ) )."</b>";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $v=array( 'Cost' => $d[0]['cost']." euros",
                  'License' => $d[0]['license'],
                  'Cooperation' => $d[0]['cooperation'],
                  'Status' => show_status($d[0]["status"]),
                  'Validity' => timestr_middle(mktimestamp($d[0]["valid"])),
                  'Start possible' => 
                                 timestr_middle(mktimestamp($d[0]["start"])),
                  'Duration'=> $d[0]["duration"]." weeks");
        while ( list ( $key, $val ) = each( $v ) ) {
            $str = sprintf( '<b>%s:</b> %s\n', $t->translate($key), $val );
            $this->_testFor_pattern( '[<]..?[>]'.$this->_to_regexp($str));
        }
        $this->_check_db( $db_config );
    }

    function testShow_selected_developing() {
        global $t, $bx, $db;
        
        $db_config = new mock_db_configure( 101 );
        $args = $this->_generate_records( array( 'proid' ), 101 );
        $q = ("SELECT * FROM developing,auth_user WHERE proid='%s' AND "
              ."developer=username AND developing.status='A'");
        // tests 1-100: not exactly one record ...
        for ( $idx = 0; $idx < 100; $idx++ ) {
            if ( $idx == 51 ) {
                $db = new DB_SourceAgency;
                continue;
            }
            $db_config->add_query( sprintf( $q, $args[$idx]['proid'] ), $idx );
            $db_config->add_num_row( $idx - 50, $idx );
            $db = new DB_SourceAgency;
            $this->capture_call('show_selected_developing', 121, $args[$idx]);
            $this->assertEquals( "<b><font color=red>The number of accepted "
                                 ."developing proposals is not correct. SEe "
                                 ."show_selected_developing()</font></b>\n", 
                                 $this->get_text() );
        }

        // test 101: exactly one record
        $d=$this->_generate_records( array('creation','username','cost',
                                           'license','cooperation','status',
                                           'valid','start','duration'),10);
        $db_config->add_query( sprintf( $q, $args[100]['proid'] ), 100 );
        $db_config->add_num_row( 1, 100 );
        $db_config->add_record( $d[0], 100 );
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'show_selected_developing', 922
                             + strlen(timestr(mktimestamp($d[0]['creation']))),
                             $args[100]);
        
        $this->_checkFor_a_box( 'Developing Proposal' );
        $this->_testFor_lib_nick( $d[0]['username'] );
        $str = ' - '.timestr( mktimestamp( $d[0]['creation' ] ) )."</b>";
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $v=array( 'Cost' => $d[0]['cost']." euros",
                  'License' => $d[0]['license'],
                  'Cooperation' => $d[0]['cooperation'],
                  'Status' => show_status($d[0]["status"]),
                  'Validity' => timestr_middle(mktimestamp($d[0]["valid"])),
                  'Start possible' => 
                                 timestr_middle(mktimestamp($d[0]["start"])),
                  'Duration'=> $d[0]["duration"]." weeks");
        while ( list ( $key, $val ) = each( $v ) ) {
            $str = sprintf( '<b>%s:</b> %s\n', $t->translate($key), $val );
            $this->_testFor_pattern( '[<]..?[>]'.$this->_to_regexp($str));
        }

        $this->_check_db( $db_config );
    }

    function testDeveloping_form() {
        global $bx, $t, $sess;
        global $cost, $license, $cooperation, $valid_day, $valid_month, 
            $valid_year, $start_day, $start_month, $start_year, $duration;

        $cost = 'this is the cost';
        $license = 'this is the lcensie';
        $cooperation = 'this is the cooperation';
        $valid_day = 'this sit he valid daty';
        $valid_month = 'this is the valid month';
        $valid_year = 'this is the valid tesa';
        $start_day = 'thjsi si the start_day';
        $start_month = 'this is the start month';
        $start_year = 'thsi thsi the start_year';
        $duration = 'tjsi thst duration';
        
        /** required for the license calls **/
        $db_config = new mock_db_configure( 2 );
        $db_config->add_query('SELECT * FROM licenses ORDER BY license ASC',0);
        $db_config->add_query('SELECT * FROM licenses ORDER BY license ASC',1);
        $db_config->add_num_row( 1, 0 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_record( false, 0 );
        $db_config->add_record( false, 1 );

        $args = $this->_generate_records(array( 'proid', 'content_id' ),1);
        $bx = $this->_create_default_box();
        $this->capture_call( 'developing_form', 
                             10111 + strlen( $sess->self_url() ), $args[0] );
        $this->_checkFor_a_form( 'PHP_SELF',array('proid'=>$args[0]['proid']),
                                 'POST' );
        $this->_checkFor_a_box( 'Development proposal' );
        $this->_checkFor_columns( 2 );
        $this->_testFor_html_form_hidden( 'content_id',$args[0]['content_id']);
        $this->_checkFor_submit_preview_buttons();

        $v=array( 'Cost' => 
                  array('<b>%s</b> (12): ',
                        html_input_text('cost',12,12,$cost)),
                  'License' => 
                  array( '<b>%s</b> (12): ', license($license)),
                  'Developer cooperation wanted?' => 
                  array( '<b>%s</b>', 
                         developing_select_cooperation($cooperation)),
                  'Valid until' => 
                  array( "<b>%s</b>: ",select_date('valid',$valid_day,
                                                   $valid_month,$valid_year)),
                  'Start' =>
                  array( '<b>%s</b>: ', select_date('start',$start_day,
                                                    $start_month,$start_year)),
                  'Duration (in weeks)' =>
                  array( '<b>%s</b>: ', select_duration($duration)));
        while ( list ( $title, $ary ) = each( $v ) ) {
            $this->_checkFor_column_titles( array( $title ), 'right', '30%',
                                            '', $ary[0] );
            $this->_checkFor_column_values( array( $ary[1] ) );
        }
        $this->_check_db( $db_config );
    }

    function testDeveloping_insert() {
        global $db;
        
        $qs=array( 0=>("INSERT developing SET proid='%s',developer='%s',"
                       ."content_id='%s', cost='%s', license='%s', "
                       ."cooperation='%s', valid='%s', start='%s', "
                       ."duration='%s',status='P'"),
                   1=>("SELECT email_usr FROM auth_user,monitor WHERE "
                       ."monitor.username=auth_user.username AND "
                       ."proid='%s' AND importance='high'"),
                   2=>("SELECT * FROM developing,auth_user WHERE proid='%s' "
                       ."AND content_id='%s' AND developer=username ORDER "
                       ."BY developing.creation DESC"));

        $args=$this->_generate_records( array('proid','user','content_id',
                                              'cost','license','cooperation',
                                              'valid_day','valid_month',
                                              'valid_year','start_day',
                                              'start_month','start_year',
                                              'duration'), 1 );

        $valid = date_to_timestamp( $args[0]['valid_day'],
                              $args[0]['valid_month'],$args[0]['valid_year']);
        $start = date_to_timestamp( $args[0]['start_day'],
                              $args[0]['start_month'],$args[0]['start_year']);

        $db_config = new mock_db_configure( 2 );
        $db_config->add_query( sprintf($qs[0], $args[0]['proid'],
                               $args[0]['user'],$args[0]['content_id'],
                               $args[0]['cost'],$args[0]['license'],
                               $args[0]['cooperation'], $valid, $start, 
                               $args[0]['duration']), 0 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['proid']), 1 );
        $db_config->add_record( false, 1 );
        $db_config->add_query( sprintf( $qs[2], $args[0]['proid'],
                                               $args[0]['content_id']), 0 );
        $db_config->add_num_row( 0, 0 );

        $db = new DB_SourceAgency;
        $this->capture_call( 'developing_insert', 71, $args[0]);
        $this->assertEquals("There have not been posted any developement "
                            ."proposals to this project.\n",$this->get_text());
        $this->_check_db( $db_config );
    }

    function testDeveloping_modify() {
        $this->_test_to_be_completed();
    }

    function testDeveloping_modify_form() {
        $this->_test_to_be_completed();
    }

    function testDeveloping_preview() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
