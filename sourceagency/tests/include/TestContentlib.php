<?php
// TestContentlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestContentlib.php,v 1.4 2002/06/20 12:07:16 riessen Exp $

include_once( '../constants.php' );

include_once( 'html.inc' );
include_once( 'lib.inc' );
include_once( 'contentlib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");
}

class UnitTestContentlib
extends UnitTest
{
    function UnitTestContentlib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset( $GLOBALS[ 'bx' ] );
        unset( $GLOBALS[ 'db' ] );
    }

    function testContent_box_footer() {
        // FIXME: this needs to test more of the function, e.g. the if 
        // FIXME: statements and their corresponding html code changes
        global $bx, $db, $sess;
        
        $args = $this->_generate_records(array('proid','cid','which_prop'),2);

        $dat=$this->_generate_records(array('content_id'), 1 );
        $dat2=$this->_generate_records( array( 'COUNT(*)'), 2 );
        $db_config = new mock_db_configure( 2 );
        $q = array( 0 => ("SELECT COUNT(*) FROM developing WHERE proid='%s' "
                          ."AND content_id='%s'"),
                    1 => ("SELECT COUNT(*) FROM comments WHERE proid='%s' AND "
                          ."type='Specifications' AND number='%s'"));
        $db_config->add_record( $dat[0], 0 );
        $db_config->add_query( sprintf( $q[0], $args[0]['proid'], 
                                                     $args[0]['cid'] ), 1 );
        $db_config->add_query( sprintf( $q[1], $args[0]['proid'], 
                                                     $args[0]['cid'] ), 1 );
        $db_config->add_record( $dat2[0], 1 );
        $db_config->add_record( $dat2[1], 1 );
        $db_config->add_query( 'fubar', 0 );
        
        // a global db object is required which has 'content_id' as
        // row value. (content_box_footer is called by show_content which
        // uses the global database).
        $db = new DB_SourceAgency;
        $db->query( 'fubar' );
        $db->next_record();
        $bx = $this->_create_default_box();
        $this->capture_call( 'content_box_footer', 895, $args[0] );
        $this->_check_db( $db_config );
    }
    function testContent_form() {
        $this->_test_to_be_completed();
    }

    function testContent_insert() {
        $this->_test_to_be_completed();
    }

    function testContent_modify() {
        $this->_test_to_be_completed();
    }

    function testContent_modify_form() {
        $this->_test_to_be_completed();
    }

    function testContent_preview() {
        global $t, $bx, $auth, $sess;
        global $skills, $platform, $architecture, $environment;
        global $docs, $specification;

        $auth->set_uname( 'this is the username' );
        $skills = 'this is the skill';
        $platform = 'this is the platform';
        $architecture = 'this si the architecture';
        $environment = 'this is the envirnoment';
        $docs = 'ths is the docs';
        $specification = 'this is the sepcs';
        $proid = 'this is the proid';

        $bx = $this->_create_default_box();
        $this->capture_call( 'content_preview', 1075 + strlen(timestr(time())),
                             array( &$proid ) );
        
        $this->_checkFor_a_box( 'Technical Content' );
        $this->_testFor_lib_nick( $auth->auth['uname'] );
        $v = array( 'Needed Skills'=>$skills,
                    'Plattform' => $platform,
                    'Architecture' => $architecture,
                    'Environment' => $environment,
                    'Documentation' => html_link( $docs, array(), $docs ),
                    'Status' => 'Proposed',
                    'Technical Specification' => $specification );
        while ( list( $key, $val ) = each( $v )) {
            $str = ( $key != 'Technical Specification' ? '<br>' : '<p>' );
            $str .= '<b>'.$t->translate( $key ).':</b> '.$val."\n";
            $this->_testFor_pattern( $this->_to_regexp( $str ) );
        }

        // test two: no documentation data
        $docs = '';
        reset( $v );
        unset( $v['Documentation'] );
        $bx = $this->_create_default_box();
        $this->capture_call( 'content_preview', 994 + strlen(timestr(time())),
                             array( $proid ) );

        $this->_checkFor_a_box( 'Technical Content' );
        $this->_testFor_lib_nick( $auth->auth['uname'] );
        while ( list( $key, $val ) = each( $v )) {
            $str = ( $key != 'Technical Specification' ? '<br>' : '<p>' );
            $str .= '<b>'.$t->translate( $key ).':</b> '.$val."\n";
            $this->_testFor_pattern( $this->_to_regexp( $str ) );
        }
        $this->assertNotRegexp('/'.$t->translate('Documentation').'/',
                                                      $this->get_text());
    }

    function testShow_content() {
        $this->_test_to_be_completed();
    }

    function testShow_proposals() {
        $this->_test_to_be_completed();
    }

    function testshow_selected_content() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
