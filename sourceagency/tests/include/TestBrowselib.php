<?php
// TestBrowselib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestBrowselib.php,v 1.7 2002/06/20 12:07:16 riessen Exp $

include_once( '../constants.php' );

include_once( 'html.inc' );
include_once( 'security.inc' );
include_once( 'box.inc' );
include_once( "translation.inc" );
include_once( 'browselib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    $GLOBALS['t'] = new translation("English");
}

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

    function _checkFor_common_column_titles( $title ) {
        global $t;
        $this->_checkFor_columns( 3 );
        $this->_checkFor_column_titles(array('No.'),'right','6%','');
        $this->_checkFor_column_titles(array($title),'left','70%','');
        $this->_testFor_box_column( 'center','20%','','<b>#&nbsp;'
                                    .$t->translate('Projects').'</b>');
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
        $this->capture_call( $func_name, $captured_length );

        // check the contents of the output
        $this->_checkFor_a_box( $list_title );
        $this->_checkFor_common_column_titles( $list_title );
        
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
        global $bx, $t;
        
        $db_config = new mock_db_configure( 26 );
        $q=( 'SELECT COUNT(*) FROM description WHERE status > 0'
             ." AND  project_title LIKE '%s%%'" );
        $alphabet=array('A','B','C','D','E','F','G','H','I','J','K','L','M',
                        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $idx = 0;
        $d=$this->_generate_array( array( 'COUNT(*)' ), 26 );
        foreach ( $alphabet as $let ) {
            $db_config->add_query( sprintf( $q, $let ), $idx );
            $d[$idx]['COUNT(*)'] = $idx % 3;
            $db_config->add_record( $d[$idx], $idx );
            $idx++;
        }

        $bx = $this->_create_default_box();
        $this->capture_call( '_browse_project_name', 16058 );

        $this->_checkFor_a_box('Projects ordered alphabetically');
        $this->_checkFor_common_column_titles( 'Project Name' );

        reset( $alphabet );
        $idx = 0;
        $colors = array( 0 => 'gold', 1 => '#FFFFFF' );
        foreach ( $alphabet as $let ) {
            $num = '['.sprintf('%03d',$d[$idx]['COUNT(*)']).']';
            if ( $num == "[000]" ) {
                /** ensure that the link is *not* found **/
                $this->reverse_next_test();
            } 
            $this->_testFor_html_link('browse.php3', 
                                      array('through'=>'project_name', 
                                            'project_name'=>$let),$num);
            $this->_testFor_box_column( 'left','',$colors[$idx%2],$let);
            $idx++;
        }
        $this->_check_db( $db_config );
    }

    function test_browse_steps() {
        global $auth, $bx, $t, $g_step_count, $g_step_text;

        // ensure that is_administrator returns 0 and does not query the db
        $auth->set_perm( '' );

        $db_config = new mock_db_configure( $g_step_count );
        $q = "SELECT COUNT(*) FROM description WHERE status = '%d'";
        $d=$this->_generate_records( array( 'COUNT(*)' ), $g_step_count  );

        for ( $idx = 1; $idx <= $g_step_count; $idx++ ) {
            $db_config->add_query( sprintf( $q, $idx ), $idx-1);
            $d[$idx-1]['COUNT(*)'] = $idx % 2;
            $db_config->add_record( $d[$idx-1], $idx-1 );
        }

        $bx = $this->_create_default_box();
        $this->capture_call( '_browse_steps', 4805 );

        $this->_checkFor_a_box( 'Steps' );
        $this->_checkFor_common_column_titles( 'Step' );

        $colors = array( 1 => 'gold', 0 => '#FFFFFF', -1 => 'gold' );
        for ( $idx = 1; $idx <= $g_step_count; $idx++ ) {
            $num = '[' . sprintf('%03d',$d[$idx-1]['COUNT(*)']) . ']';
            if ( $num == "[000]" ) {
                $this->reverse_next_test();
            }
            $this->_testFor_html_link('browse.php3',array('through'=>'steps', 
                                                          'steps'=>$idx),$num);
            $this->_testFor_box_column('left','',$colors[$idx%2],
                                       $t->translate('Step') . " $idx ("
                                       .$t->translate($g_step_text[$idx]).')');
        }
        
        $this->_check_db( $db_config );
    }

    function test_browse_not_yet() {
        global $bx;
        $bx = $this->_create_default_box();
        $this->capture_call( '_browse_not_yet', 680 );
        $this->_checkFor_a_box( 'Not yet available' );
    }

    function testBrowse_licenses() {
        global $bx, $db, $t;

        $db_config = new mock_db_configure( 11 );
        $qs=array( 0=>'SELECT DISTINCT * FROM licenses',
                   1=>( "SELECT COUNT(*) FROM developing WHERE license = '%s'"
                        ." AND status='A'"));

        $d=$this->_generate_records( array( 'license', 'url' ), 10 );
        $d2=$this->_generate_records( array( 'COUNT(*)' ), 10 );
        $db_config->add_query( $qs[0], 0 );

        for ( $idx = 0; $idx < sizeof( $d ); $idx++ ) {
            $db_config->add_record( $d[$idx], 0 );
            $db_config->add_query( sprintf($qs[1],$d[$idx]['license']),$idx+1);
            $d2[$idx]['COUNT(*)'] = $idx % 3;
            $db_config->add_record( $d2[$idx], $idx + 1 );
        }

        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        $this->capture_call( 'browse_licenses', 7359 );
        
        $this->_checkFor_a_box( 'Licenses' );
        $this->_checkFor_common_column_titles( 'License' );

        for ( $idx = 0; $idx < count( $d ); $idx++ ) {
            $num = '['.sprintf('%03d',$d2[$idx]['COUNT(*)']).']';
            if ( $num == "[000]" ) {
                $this->reverse_next_test();
            }
            $this->_testFor_html_link('browse.php3', 
                                      array('through'=>'license', 
                                            'license' => 
                                            rawurlencode($d[$idx]['license'])),
                                       $num);
            $this->_testFor_html_link( $d[$idx]['url'],'',$d[$idx]['license']);
        }
        $this->_check_db( $db_config );
    }

    function testBrowse_list() {
        global $db;

        $q="SELECT * FROM description WHERE %s='%%s'";
        $q2=("SELECT * FROM description,tech_content WHERE "
             ."description.proid=tech_content.proid AND "
             ."%s='%%s'");

        $qs=array( 'license'=>( "SELECT * FROM description,developing WHERE "
                                ."description.proid=developing.proid AND "
                                ."license = '%s'"),
                   'project_name'=>('SELECT * FROM description WHERE status'
                                    ." > 0 AND  project_title LIKE '%s%%'"),
                   'type'         => sprintf( $q, 'type' ),
                   'steps'        => sprintf( $q, 'status' ),
                   'volume'       => sprintf( $q, 'volume' ),
                   'date'         => sprintf( $q, 'creation' ),
                   'audience'     => sprintf( $q, '' ),
                   'os'           => sprintf( $q, '' ),
                   'language'     => sprintf( $q, '' ),
                   'platform'     => sprintf( $q2, 'platform' ), 
                   'architecture' => sprintf( $q2, 'architecture' ),
                   'environment'  => sprintf( $q2, 'environment' ));

        $db_config = new mock_db_configure( 1 );
        $args = array( 'by' => '', 'what' => 'this si teh waht value' );

        $db = new DB_SourceAgency;
        while ( list( $by, $query ) = each( $qs ) ) {
            $args['by'] = $by;
            $db_config->add_query( sprintf( $query, $args['what'] ), 0 );
            $db_config->add_record( FALSE, 0 );
            $this->capture_call( 'browse_list', 0, $args, true );
        }
        $this->_check_db( $db_config );
    }

    function testBrowse_through() {
        /** Does not need to be tested??? **/
    }

}

define_test_suite( __FILE__ );
?>
