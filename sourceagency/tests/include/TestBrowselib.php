<?php
// TestBrowselib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestBrowselib.php,v 1.4 2002/06/04 10:57:52 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
}

include_once( 'browselib.inc' );

class UnitTestBrowselib
extends UnitTest
{
    function UnitTestBrowselib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
        unset( $GLOBALS['bx'] );
        unset( $GLOBALS['db'] );
    }
    function tearDown() {
    }
    function _checkFor_browse_list( $func_name, $list_type, $list_title,
                                    &$ary, $captured_length, $query=false ) {
        global $bx, $t;

        // setup the database objects
        $cnt = count( $ary );
        $q = ( $query ? $query : ( "SELECT COUNT(*) FROM tech_content "
                                   ."WHERE $list_type='%s' AND status='A'"));
            
        $db_config = new mock_db_configure( $cnt );
        $row=$this->_generate_records( array( "COUNT(*)" ), $cnt );
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $db_config->add_query(sprintf($q,$ary[$idx]),$idx);
            $row[$idx]['COUNT(*)'] = $idx;
            $db_config->add_record( $row[$idx], $idx );
        }

        // create a box and call the function
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        call_user_func( $func_name );
        $this->set_text( capture_stop_and_get() );

        // check the contents of the output
        $this->_checkFor_columns( 3 );
        $this->_checkFor_a_box( $list_title );
        $this->_checkFor_column_titles(array('No.'),'right','6%','');
        $this->_checkFor_column_titles(array($list_title),'left','70%','');
        $this->_testFor_box_column( 'center','20%','','<b>#&nbsp;'
                                          .$t->translate('Projects').'</b>');
        
        $colors = array( 0 => 'gold', 1 => '#FFFFFF' );
        for ( $idx = 0; $idx < $cnt; $idx++ ) {
            $bgc = $colors[ $idx % 2 ];
            $num = sprintf('[%03d]',$row[$idx]['COUNT(*)']);
            $this->_testFor_box_column( 'right', '', $bgc, $idx+1);
            if ( $num != "[000]" ) {
                $this->_testFor_box_column('center','',$bgc,
                                             html_link('browse.php3',
                                                       array('through' => 
                                                         $list_type, 
                                                         $list_type => 
                                                         $ary[$idx]),$num));
            } else {
                $this->_testFor_box_column('center','',$bgc,$num);
            }
            $this->_testFor_box_column( 'left', '', $bgc, $ary[$idx]);
        }

        $this->_testFor_string_length( $captured_length );
        $this->_check_db( $db_config );
    }

    function test_browse_environment() {
        require( 'config.inc' );
        $this->_checkFor_browse_list( '_browse_environment', 'environment',
                                      'Environment',&$environment_array,3743);
    }
    function test_browse_architecture() {
        require( 'config.inc' );
        $this->_checkFor_browse_list('_browse_architecture', 'architecture',
                                     'Architecture',&$architecture_array,5539);
    }
    function test_browse_platform() {
        require( 'config.inc' );
        $this->_checkFor_browse_list('_browse_platform', 'platform',
                                     'Platform',&$platform_array,7883);
    }
    function test_browse_volume() {
        require( 'config.inc' );
        $query = ("SELECT COUNT(*) FROM description WHERE volume='%s' "
                  ."AND status>'0'");
        $this->_checkFor_browse_list('_browse_volume', 'volume', 'Volume',
                                     &$project_volume, 4368, $query);
    }
    function test_browse_type() {
        require( 'config.inc' );
        $query = ("SELECT COUNT(*) FROM description WHERE type='%s' "
                  ."AND status>'0'");
        $this->_checkFor_browse_list('_browse_type', 'type', 'Type',
                                     &$project_types, 4285, $query);
    }

    function test_browse_project_name() {
        $this->_test_to_be_completed();
    }
    function test_browse_steps() {
        $this->_test_to_be_completed();
    }

    function test_browse_not_yet() {
        global $bx;
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        _browse_not_yet();
        $this->set_text( capture_stop_and_get() );
        $this->_checkFor_a_box( 'Not yet available' );
    }
    function testBrowse_licenses() {
        $this->_test_to_be_completed();
    }

    function testBrowse_list() {
        $this->_test_to_be_completed();
    }

    function testBrowse_through() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
