<?php
// TestAdmprojectslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestAdmprojectslib.php,v 1.6 2002/06/26 10:29:52 riessen Exp $

include_once( "../constants.php" );

include_once( 'lib.inc');
include_once( 'html.inc' );
include_once( 'box.inc' );
include_once( 'admprojectslib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( "translation.inc" );
    $GLOBALS['t'] = new translation("English");
}

class UnitTestAdmprojectslib
extends UnitTest
{
    function UnitTestAdmprojectslib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    
    function tearDown() {
        unset_global( 'bx', 'db' );
    }
    
    function _testFor_show_admprojects_basics( &$d, $bgc ) {
        global $t;

        $this->_checkFor_a_box( 'Pending Project Administration' );
        $this->_checkFor_columns( 7 );
        $v=array( 'Project Title' => array( '','15%',''),
                  'Type' => array( 'center', '10%', '' ),
                  'Description' => array( 'center', '30%', '' ),
                  'User' => array('center','10%',''),
                  'Volume' => array('center','7%',''),
                  'Configured?' => array('center','7%',''),
                  'Creation' => array('center','12%',''));
        while( list( $key, $va ) = each( $v ) ) {
            $this->_checkFor_column_titles(array($key),$va[0],$va[1],$va[2]);
        }
        $v=array( html_link('summary.php3',
                            array('proid'=>$d['proid']),
                            $d['project_title']) => array( '','',$bgc),
                  $d['type'] => array( '','',$bgc),
                  $d['description'] => array( '','',$bgc ),
                  lib_nick($d['description_user'])=>array('center','',$bgc),
                  $d['volume'] => array('center','',$bgc),
                  timestr_short(mktimestamp($d['description_creation']))=>
                  array('center','',$bgc));
        while ( list( $key, $va ) = each( $v ) ) {
            $this->_checkFor_column_titles(array($key),$va[0],$va[1],$va[2]);
        }

        $this->_testFor_box_colspan( 8, 'center', '#FFFFF', '&nbsp;' );
        $this->_testFor_box_colspan( 8, 'center', '#FEDCBA', 
                                     $t->translate('Sponsored projects') );
        $this->_testFor_box_colspan( 8, 'center', '#ABCDEF', 
                                     $t->translate('Developing projects') );
        $this->_checkFor_a_form('PHP_SELF',array(),'POST');
    }

    function _call_show_admprojects( $string_length, &$data, $bgc ) {
        global $bx;
        $bx = $this->_create_default_box();
        $this->capture_call( 'show_admprojects', $string_length );
        $this->_testFor_show_admprojects_basics( $data, $bgc );
    }

    function testShow_admprojects() {
        global $bx, $t, $sess;

        $db_config = new mock_db_configure( 33 );

        $qs=array( 0=>("SELECT * FROM description,auth_user WHERE status='0' "
                       ."AND description_user=username"),
                   1=>("SELECT * FROM configure WHERE proid = '%s'"),
                   2=>("SELECT perms FROM auth_user WHERE username='%s'"),
                   3=>("SELECT * FROM %s WHERE proid = '%s'"));
        $d=$this->_generate_records( array( 'perms', 'project_title','proid',
                                            'type','description', 'volume',
                                            'description_user',
                                            'description_creation'), 8 );
        $d2=$this->_generate_records( array( 'perms' ), 8 );
        
        $table = array( 0 => 'sponsoring', 1 => 'developing' );
        $bgc = array( 0 => '#FEDCBA', 1 => '#ABCDEF' );

        for ( $idx = 0; $idx < count($d); $idx++ ) {
            // tests 2 to 5 involve the sponsor, 5 to 9 the developer
            $d[$idx]['perms'] = ( $idx < 4 ? 'sponsor' : 'devel' );
            // user permission toggles between sponsor and developer
            $d2[$idx]['perms'] = ( $idx % 2 ? 'devel' : 'sponsor' );
        }

        // for test one
        $db_config->add_query( $qs[0], 0 );
        $db_config->add_num_row( 0, 0 );

        // the following is code for defining the queries which in implicately
        // set the value of the configured variable. The first 4 are for the
        // sponsor, the next four for the developer.
        // configured==  3    2    1    0    1    0    2    3
        $nr_q2=array( 0=>1,1=>0,2=>1,3=>0,4=>1,5=>0,6=>0,7=>1);
        $nr_q4=array( 0=>1,1=>1,2=>0,3=>0,4=>0,5=>0,6=>1,7=>1);
        for ( $idx = 0; $idx < count($d); $idx++ ) {
            $inst_idx = $idx * 4;
            $db_config->add_query( $qs[0],    $inst_idx+1 );
            $db_config->add_record( $d[$idx], $inst_idx+1 );
            $db_config->add_num_row( 1,       $inst_idx+1 );

            $db_config->add_query( sprintf( $qs[1], $d[$idx]['proid']), 
                                                   $inst_idx+2 );
            $db_config->add_num_row( $nr_q2[$idx], $inst_idx+2 );

            $db_config->add_query( sprintf($qs[2], 
                                   $d[$idx]['description_user']), $inst_idx+3);
            $db_config->add_record( $d2[$idx],                    $inst_idx+3);

            $db_config->add_query( sprintf($qs[3], $table[$idx % 2],
                                               $d[$idx]['proid']),$inst_idx+4);
            $db_config->add_num_row( $nr_q4[$idx],                $inst_idx+4);
        }

        // test one, no projects
        $bx = $this->_create_default_box();
        $this->capture_call( 'show_admprojects', 780 );
        $this->_checkFor_box_full( 
            $t->translate('Pending Project Administration'),
            $t->translate('No pending projects') );

        // test two, test using the sponsor, configured == 3
        $this->_call_show_admprojects( 4800 + strlen( $sess->self_url() ), 
                                       $d[0], $bgc[0] );
        $co = '<b>'.$t->translate('Yes')
             .'</b><br>'
             .html_link($table[0].'.php3',
                        array('proid'=>$d[0]['proid']),
                        $t->translate($table[0]))
             .'<br>'
             .html_link('configure.php3',
                        array('proid'=>$d[0]['proid']),
                        $t->translate('Configuration'));
        $this->_testFor_box_column( 'center', '',$bgc[0], $co );
        $this->_testFor_html_form_hidden( 'proid', $d[0]['proid'] );
        $this->_testFor_html_form_submit( $t->translate( 'Review' ), 'review');
        $this->_testFor_html_form_submit( $t->translate( 'Delete' ), 'delete');

        // test three, test using the sponsor, configured == 2
        $this->_call_show_admprojects( 4683 + strlen( $sess->self_url() ),
                                       $d[1], $bgc[0] );
        $co = '<b>'.$t->translate('Partially')
             .'</b><br>'
             .html_link($table[1].'.php3',
                        array('proid'=>$d[1]['proid']),
                        $t->translate($table[1]));
        $this->_testFor_box_column( 'center', '',$bgc[0], $co );
        $this->_testFor_html_form_hidden( 'proid', $d[1]['proid'] );
        $this->_testFor_html_form_submit( $t->translate( 'Delete' ), 'delete');
        $this->reverse_next_test();
        $this->_testFor_html_form_submit( $t->translate( 'Review' ), 'review');

        // test four, test using the sponsor, configured == 1
        $this->_call_show_admprojects( 4685 + strlen( $sess->self_url() ), 
                                       $d[2], $bgc[0] );
        $co= '<b>'.$t->translate('Partially')
             .'</b><br>'
             .html_link('configure.php3',
                        array('proid'=>$d[2]['proid']),
                        $t->translate('Configuration'));
        $this->_testFor_box_column( 'center', '',$bgc[0], $co );
        $this->_testFor_html_form_hidden( 'proid', $d[2]['proid'] );
        $this->_testFor_html_form_submit( $t->translate( 'Delete' ), 'delete');
        $this->reverse_next_test();
        $this->_testFor_html_form_submit( $t->translate( 'Review' ), 'review');

        // test five, test using the sponsor, configured == 0
        $this->_call_show_admprojects( 4613 + strlen( $sess->self_url() ), 
                                       $d[3], $bgc[0] );
        $co = '<b>'.$t->translate('No').'</b><br>'; 
        $this->_testFor_box_column( 'center', '',$bgc[0], $co );
        $this->_testFor_html_form_hidden( 'proid', $d[3]['proid'] );
        $this->_testFor_html_form_submit( $t->translate( 'Delete' ), 'delete');
        $this->reverse_next_test();
        $this->_testFor_html_form_submit( $t->translate( 'Review' ), 'review');

        // test six: test using developer, configured == 1
        $this->_call_show_admprojects( 4733 + strlen( $sess->self_url() ), 
                                       $d[4], $bgc[1] );
        $co = '<b>'.$t->translate('Yes')
             .'</b><br>'
             .html_link('configure.php3',
                        array('proid'=>$d[4]['proid']),
                        $t->translate('Configuration'));
        $this->_testFor_box_column( 'center', '',$bgc[1], $co );
        $this->_testFor_html_form_hidden( 'proid', $d[4]['proid'] );
        $this->_testFor_html_form_submit( $t->translate( 'Delete' ), 'delete');
        $this->_testFor_html_form_submit( $t->translate( 'Accept' ), 'accept');
        $this->reverse_next_test();
        $this->_testFor_html_form_submit( $t->translate( 'Review' ), 'review');

        // test seven, eight, nine: test using developer, configured == {0,2,3}
        for ( $idx = 5; $idx < 8; $idx++ ) {
            $this->_call_show_admprojects( 4613 + strlen( $sess->self_url() ), 
                                           $d[$idx], $bgc[1]);
            $co = '<b>'.$t->translate('No').'</b><br>'; 
            $this->_testFor_box_column( 'center', '',$bgc[1], $co );
            $this->_testFor_html_form_hidden( 'proid', $d[$idx]['proid'] );
            $this->_testFor_html_form_submit( $t->translate( 'Delete' ), 
                                                                    'delete');
            $this->reverse_next_test();
            $this->_testFor_html_form_submit( $t->translate( 'Review' ), 
                                                                    'review');
            $this->reverse_next_test();
            $this->_testFor_html_form_submit( $t->translate( 'Accept' ), 
                                                                    'accept');
        }

        $this->_check_db( $db_config );
    }

    function testAdmprojects_insert() {
        global $db;

        $qs=array( 0=>("UPDATE description SET status = '%s' "
                       ."WHERE proid='%s'" ),
                   1=>("SELECT project_title FROM description "
                       ."WHERE proid='%s'" ),
                   2=>("INSERT history SET proid='%s',history_user="
                       ."'BerliOS editor',type='Review',action="
                       ."'Project reviewed by a SourceAgency Editor'"),
                   3=>("SELECT email_usr FROM auth_user,description "
                       ."WHERE username=description_user AND proid='%s'"));

        $db_config = new mock_db_configure( 11 );
        $args=$this->_generate_records( array( 'proid' ), 11 );
        $d=$this->_generate_records(array('project_title'), 11 );
        $d2=$this->_generate_records(array('email_usr'), 11 );

        for ( $idx = -5; $idx < 6; $idx++ ) {
            $jdx = $idx + 5;
            $db_config->add_query(sprintf($qs[0],$idx,  $args[$jdx]['proid']),
                                  $jdx );
            $db_config->add_query(sprintf($qs[1], $args[$jdx]['proid']), 
                                  $jdx );
            $db_config->add_record( $d[$jdx], $jdx );
            if ( $idx > -1 ) {
                $db_config->add_query( sprintf($qs[2], $args[$jdx]['proid']), 
                                       $jdx );
            }
            $db_config->add_query( sprintf($qs[3],$args[$jdx]['proid']), 
                                   $jdx );
            $d2[$jdx]['email_usr'] = '';
            $db_config->add_record( $d2[$jdx], $jdx );
            $db = new DB_SourceAgency;

            $this->capture_call( 'admprojects_insert', 0, 
                                 array( $args[$jdx]['proid'], $idx) );
        }
        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );
?>
