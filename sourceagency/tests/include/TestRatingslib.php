<?php
// TestRatingslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestRatingslib.php,v 1.7 2002/07/09 11:15:34 riessen Exp $

include_once( '../constants.php' );

include_once( 'html.inc' );
include_once( 'ratingslib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    include_once( 'session.inc' );
    $GLOBALS['sess'] = new session;
    include_once( "translation.inc" );
    $GLOBALS['t'] = new translation("English");
}

class UnitTestRatingslib
extends UnitTest
{
    var $queries;

    function UnitTestRatingslib( $name ) {
        $this->queries = array (
            'ratings_rated_yet' =>
            ("SELECT * FROM ratings WHERE proid='%s' AND to_whom='%s' "
             ."AND by_whom='%s'"),
            'ratings_insert_1' =>
            ("SELECT %s FROM %s WHERE proid='%s' AND %s='%s'"),
            'ratings_insert_2' =>
            ("UPDATE ratings SET rating='%s'  WHERE proid='%s' AND "
             ."to_whom='%s' AND by_whom='%s' AND on_what='%s'"),
            'ratings_insert_3' =>
            ("SELECT volume FROM description WHERE proid='%s'"),
            'ratings_insert_4' =>
            ("INSERT ratings SET proid='%s', to_whom='%s', by_whom='%s', "
             ."rating='%s', on_what='%s', project_importance='%s'"),
            'ratings_look_for_first_one_1' =>
            ("SELECT developer,devid FROM developing WHERE proid='%s' AND "
             ."status='A' AND developer!='%s' ORDER BY devid"),
            'ratings_look_for_first_one_2' =>
            ("SELECT sponsor,spoid FROM sponsoring WHERE proid='%s' AND "
             ."status='A' AND sponsor!='%s' ORDER BY spoid"),
            'ratings_look_for_next_one' =>
            ("SELECT %s,%s FROM %s WHERE proid='%s' AND status='A' AND %s!="
             ."'%s' AND %s>'%s' ORDER BY %s"),
            'show_personal_rating' =>
            ("SELECT rating FROM ratings WHERE proid='%s' AND to_whom='%s'"),
            'show_participants_rating' =>
            ("SELECT %s FROM %s WHERE proid='%s' AND status='A'")
            );
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset_global( 'bx', 'db' );
    }

    function _checkFor_commonality( &$args ) {
        $this->_checkFor_a_box( '', '%s'.$args['username'] );
        $this->_testFor_html_form_action( 'PHP_SELF', 
                                          array('proid' => $args['proid']),
                                          'POST' );
        $this->_testFor_html_form_hidden('dev_or_spo',$args['dev_or_spo']);
        $this->_testFor_html_form_hidden('id_number',$args['number']);
        $this->_testFor_box_column( '','','white','&nbsp;');
        $this->_testFor_box_column( '','','white','&nbsp;');
    }

    function _checkFor_ratings_form_empty( &$args ) {
        $this->_checkFor_commonality( $args );
        $this->_testFor_box_column( '','','gold','&nbsp;');
        $this->_testFor_box_column( '','','#FFFFFF','&nbsp;');
        $this->_testFor_html_form_submit( 'Rate Me', 'rateme' );
    }

    function _checkFor_ratings_form_rated( &$args ) {
        $this->_checkFor_commonality( $args );
        $this->_testFor_html_form_submit( 'Modify', 'rateme' );
    }

    function testRatings_form_empty() {
        global $bx, $t, $sess;
        
        $fname = 'ratings_form_empty';
        $args=$this->_generate_records( array( 'proid','username',
                                               'dev_or_spo', 'number' ), 10 );
        // test one: array variable is undefined 
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        call_user_func_array( $fname, $args[0] );
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_pattern( 'Undefined variable:  array in');
        $file = $this->get_file_line_from_warning();
        $sleng = ( strlen( $file[1] ) + strlen( $file[2] ) 
                   + strlen( $sess->self_url()) );
        $sleng += ( $this->v_gt( "4.1.0", phpversion()) ? 1531 : 1535 );
        $this->_testFor_string_length( $sleng );

        // test two: sponsor
        $args[1]['dev_or_spo'] = 'sponsor';
        $bx = $this->_create_default_box();
        $this->capture_call($fname,2091 + strlen($sess->self_url()),$args[1]);
        $this->_checkFor_ratings_form_empty( $args[1] );

        // test three: developer
        $args[2]['dev_or_spo'] = 'developer';
        $bx = $this->_create_default_box();
        $this->capture_call($fname,2093 + strlen($sess->self_url()),$args[2]);
        $this->_checkFor_ratings_form_empty( $args[2] );
    }

    function testRatings_form_finish() {
        global $sess, $t;

        $proid = "this is the proid";
        $this->capture_call( 'ratings_form_finish', 
                             233 + strlen( $sess->self_url() ),array( $proid));
        $this->_checkFor_a_form( 'PHP_SELF', array('proid' => $proid) );
        $this->_testFor_html_form_hidden( 'dev_or_spo', '' );
        $this->_testFor_html_form_hidden( 'id_number', '' );
        $this->_testFor_html_form_submit( $t->translate('Rating finished'),
                                                                   'finished');
    }

    function testRatings_form_rated() {
        global $bx, $t, $sess;
        
        $fname = 'ratings_form_rated';
        $args=$this->_generate_records( array( 'proid','username',
                                               'dev_or_spo', 'number' ), 10 );
        // test one: array variable is undefined 
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        call_user_func_array( $fname, $args[0] );
        $this->set_text( capture_stop_and_get() );
        $this->_testFor_pattern( 'Undefined variable:  array in');
        $file = $this->get_file_line_from_warning();
        $sleng = ( strlen( $file[1] ) + strlen( $file[2] ) 
                   + strlen( $sess->self_url()) );
        $sleng += ( $this->v_gt( "4.1.0", phpversion()) ? 1530 : 1534 );
        $this->_testFor_string_length( $sleng );

        // test two: sponsor
        $args[1]['dev_or_spo'] = 'sponsor';
        $bx = $this->_create_default_box();
        $this->capture_call($fname,2121 + strlen($sess->self_url()),$args[1]);
        $this->_checkFor_ratings_form_rated( $args[1] );

        // test three: developer
        $args[2]['dev_or_spo'] = 'developer';
        $bx = $this->_create_default_box();
        $this->capture_call($fname,2123 + strlen($sess->self_url()),$args[2]);
        $this->_checkFor_ratings_form_rated( $args[2] );
    }

    function _config_db_ratings_insert( &$db_config, $inst_nr, &$args,
                                                      $to_whom, $rated_yet ) {
        global $qs, $auth;

        if ( $args['dev_or_spo'] == 'developer' ) {
            $table = 'developing';
            $type_of_id = 'devid';
        } else {
            $table = 'sponsoring';
            $type_of_id = 'spoid';
        }

        $db_config->add_query( sprintf( $qs[0], $args['dev_or_spo'], $table,
                                        $args['proid'], $type_of_id, 
                                        $args['number'] ), $inst_nr );
        $db_config->add_record( array( $args['dev_or_spo'] => $to_whom ), 
                                $inst_nr );

        require( 'config.inc' );
        if ( $auth->auth['perm'] == 'developer' ) {
            $ary = $developer_rates_sponsor;
        } else {
            $ary = $sponsor_rates_developer;
        }

        $db_config->add_query( sprintf( $qs[4], $args['proid'], $to_whom,
                                        $args['by_whom']), $inst_nr + 1);
        $db_config->add_num_row( $rated_yet, $inst_nr + 1 );

        if ( $rated_yet ) {
            for ( $idx = 0; $idx < count( $ary ); $idx++ ) {
                $db_config->add_query( sprintf( $qs[1], $GLOBALS[$ary[$idx]],
                                                $args['proid'], $to_whom, 
                                                $args['by_whom'], $ary[$idx]), 
                                       $inst_nr );
            }
        } else {
            $vol = 'this is the volume';
            $db_config->add_query( sprintf( $qs[2], $args['proid']), $inst_nr);
            $db_config->add_record( array( 'volume' => $vol ), $inst_nr );
            for ( $idx = 0; $idx < count( $ary ); $idx++ ) {
                $db_config->add_query( sprintf( $qs[3], $args['proid'], 
                                                $to_whom, $args['by_whom'], 
                                                $GLOBALS[$ary[$idx]], 
                                                $ary[$idx], $vol ), 
                                       $inst_nr );
            }
        }
        return ($inst_nr + 2);
    }

    function testRatings_insert() {
        global $db, $t, $auth, $qs;

        // need to define these on the global-plane 
        require( 'config.inc' );
        for ( $idx = 0; $idx < count( $sponsor_rates_developer ); $idx++ ) {
            $GLOBALS[$sponsor_rates_developer[$idx]] = 
                 $sponsor_rates_developer[$idx] . ' rating value';
        }
        for ( $idx = 0; $idx < count( $developer_rates_sponsor  ); $idx++ ) {
            $GLOBALS[$developer_rates_sponsor[$idx]] = 
                 $developer_rates_sponsor[$idx] . ' rating value';
        }

        $fname = 'ratings_insert';
        $qs=array( 0 => $this->queries[ $fname . '_1' ],
                   1 => $this->queries[ $fname . '_2' ],
                   2 => $this->queries[ $fname . '_3' ],
                   3 => $this->queries[ $fname . '_4' ],
                   4 => $this->queries[ 'ratings_rated_yet' ] );
        $db_config = new mock_db_configure( 16 );
        $args=$this->_generate_records( array( 'proid', 'dev_or_spo', 'number',
                                               'by_whom'), 8 );
        $inst_nr = 0;

        // test 1: auth->perm == developer, dev_or_spo == developer, not rated
        $auth->set_perm( 'developer' );
        $args[0]['dev_or_spo'] = 'developer';

        // test 2: auth->perm == developer, dev_or_spo == developer, rated b4
        $auth->set_perm( 'developer' );
        $args[1]['dev_or_spo'] = 'developer';

        // test 3: auth->perm == sponsor, dev_or_spo == developer, not rated
        $auth->set_perm( 'sponsor' );
        $args[2]['dev_or_spo'] = 'developer';

        // test 4: auth->perm == sponsor, dev_or_spo == developer, rated b4
        $auth->set_perm( 'sponsor' );
        $args[3]['dev_or_spo'] = 'developer';

        // test 5: auth->perm == developer, dev_or_spo == sponsor, not rated
        $auth->set_perm( 'developer' );
        $args[4]['dev_or_spo'] = 'sponsor';

        // test 6: auth->perm == developer, dev_or_spo == sponsor, rated b4
        $auth->set_perm( 'developer' );
        $args[5]['dev_or_spo'] = 'sponsor';

        // test 7: auth->perm == sponsor, dev_or_spo == sponsor, not rated
        $auth->set_perm( 'sponsor' );
        $args[6]['dev_or_spo'] = 'sponsor';

        // test 8: auth->perm == sponsor, dev_or_spo == sponsor, rated b4
        $auth->set_perm( 'sponsor' );
        $args[7]['dev_or_spo'] = 'sponsor';

        for ( $idx = 0; $idx < 8; $idx++ ) {
            $inst_nr = $this->_config_db_ratings_insert( $db_config, $inst_nr,
                                           $args[$idx], 'fubar', $idx % 2 );
            $db = new DB_SourceAgency;
            $this->capture_call( $fname, 0, $args[$idx] );
        }

        // TODO: need to extend this test to test failure cases
        $this->_check_db( $db_config );
    }

    function _config_db_ratings_look_for_next_one( &$db_config, $inst_nr,
                                              $dev_or_spo, &$args, 
                                              $find_developer, $find_sponsor ){
        global $qs, $auth;
        $id_array    =array('developer'=>'devid',     'sponsor'=>'spoid');
        $table_array =array('developer'=>'developing','sponsor'=>'sponsoring');
        $id = $id_array[$dev_or_spo];
        $table = $table_array[$dev_or_spo];

        $orig_inst_nr = $inst_nr;
        $db_config->add_query(sprintf($qs[0], $dev_or_spo,$id,$table, 
                              $args['proid'],$dev_or_spo,$auth->auth['uname'],
                              $id, $args['number'],$id), $orig_inst_nr);

        $d1 = $this->_generate_records( array( $dev_or_spo, $id ), 3);
        $db_config->add_record_array( $d1, $orig_inst_nr );

        if ( $dev_or_spo == 'sponsor' ) {
            $find_developer = $find_sponsor;
        }

        $inst_nr++;
        for ( $idx = 0; $idx < count( $d1 ); $idx++, $inst_nr++ ) {
            $db_config->add_query( sprintf( $qs[1], $args['proid'],
                                            $d1[$idx][$dev_or_spo], 
                                            $auth->auth['uname'] ), 
                                            $inst_nr );
            if ( $find_developer && ($idx == (count( $d1 ) - 1)) ) {
                $db_config->add_num_row( 0, $inst_nr );
            } else {
                $db_config->add_num_row( 1, $inst_nr );
            }
        }

        if ( $dev_or_spo == 'sponsor' && !$find_sponsor ) {
            $db_config->add_record( false, $orig_inst_nr );
        }

        if ( !$find_developer && $dev_or_spo != 'sponsor' ) {
            $db_config->add_record( false, $orig_inst_nr );
            $dev_or_spo = 'sponsor';
            $id = $id_array[$dev_or_spo];
            $table = $table_array[$dev_or_spo];
            
            $db_config->add_query(sprintf($qs[0], $dev_or_spo,$id,$table, 
                              $args['proid'],$dev_or_spo,$auth->auth['uname'],
                              $id, '1',$id), $orig_inst_nr);

            $d1 = $this->_generate_records( array( $dev_or_spo, $id ), 3);
            $db_config->add_record_array( $d1, $orig_inst_nr );

            for ( $idx = 0; $idx < count( $d1 ); $idx++, $inst_nr++ ) {
                $db_config->add_query( sprintf( $qs[1], $args['proid'],
                                                $d1[$idx][$dev_or_spo], 
                                                $auth->auth['uname'] ), 
                                                $inst_nr );
                if ( $find_sponsor && ($idx == (count( $d1 ) - 1)) ) {
                    $db_config->add_num_row( 0, $inst_nr );
                } else {
                    $db_config->add_num_row( 1, $inst_nr );
                }
            } 

            if ( !$find_sponsor ) {
                $db_config->add_record( false, $orig_inst_nr );
            }
        }
        return $inst_nr;
    }

    function _config_db_ratings_look_for_first_one( &$db_config, $inst_nr,
                                        &$args, $uname, $num_devel, $num_spon, 
                                        $find_devel, $find_spon ) {
        global $qs;
        
        $orig_inst_nr = $inst_nr;
        $db_config->add_query(sprintf($qs[0],$args['proid'],$uname),
                              $orig_inst_nr);
        
        $d = $this->_generate_records(array('developer','devid' ),$num_devel);
        $db_config->add_record_array( $d, $orig_inst_nr );
        $inst_nr++;
        $limit = ( $find_devel ? $num_devel - 1 : $num_devel );
        for ( $idx = 0; $idx < $limit; $idx++, $inst_nr++ ) {
            $db_config->add_query( sprintf( $qs[2], $args['proid'],
                                            $d[$idx]['developer'], $uname ), 
                                            $inst_nr );
            $db_config->add_num_row( 1, $inst_nr );
        }

        if ( $find_devel ) {
            $idx = $num_devel - 1;
            $db_config->add_query( sprintf( $qs[2], $args['proid'],
                                            $d[$idx]['developer'], $uname ), 
                                            $inst_nr );
            $db_config->add_num_row( 0, $inst_nr );
            /** don't need to configure the sponsor call **/
            return ++$inst_nr;
        }

        /** ensure that the developer while loop is exited **/
        $db_config->add_record( false, $orig_inst_nr );

        $db_config->add_query(sprintf($qs[1],$args['proid'],$uname),
                              $orig_inst_nr);
        
        $d = $this->_generate_records(array('sponsor','spoid' ), $num_spon);
        $db_config->add_record_array( $d, $orig_inst_nr );
        $limit = ( $find_spon ? $num_spon - 1 : $num_spon );
        for ( $idx = 0; $idx < $limit; $idx++, $inst_nr++ ) {
            $db_config->add_query( sprintf( $qs[2], $args['proid'],
                                            $d[$idx]['sponsor'], $uname ), 
                                            $inst_nr );
            $db_config->add_num_row( 1, $inst_nr );
        }

        if ( $find_spon ) {
            $idx = $num_spon - 1;
            $db_config->add_query( sprintf( $qs[2], $args['proid'],
                                            $d[$idx]['sponsor'], $uname ), 
                                            $inst_nr );
            $db_config->add_num_row( 0, $inst_nr );
        } else {
            $db_config->add_record( false, $orig_inst_nr );
        }

        return ++$inst_nr;
    }

    function testRatings_look_for_first_one() {
        global $db, $auth, $dev_or_spo, $qs;

        $fname = 'ratings_look_for_first_one';
        $uname = 'this is the username';
        $auth->set_uname( $uname );

        $qs=array( 0 => $this->queries[ $fname . '_1' ],
                   1 => $this->queries[ $fname . '_2' ],
                   2 => $this->queries[ 'ratings_rated_yet' ] );
        $db_config = new mock_db_configure( 43 );
        $args=$this->_generate_records( array( 'proid' ), 10 );

        // test one: return a devloper id after 5 records
        $inst_nr = 
             $this->_config_db_ratings_look_for_first_one( $db_config, 0, 
                                        $args[0], $uname, 5, -1, true, false );
        $dev_or_spo = '';
        $db = new DB_SourceAgency;
        $this->assertEquals('devid_4',$this->capture_call($fname,0,$args[0]));
        $this->assertEquals( 'developer', $dev_or_spo );

        // test two: return a developer id after 2 records
        $inst_nr = 
             $this->_config_db_ratings_look_for_first_one($db_config, $inst_nr,
                                        $args[1], $uname, 2, -1, true, false );
        $dev_or_spo = '';
        $db = new DB_SourceAgency;
        $this->assertEquals('devid_1',$this->capture_call($fname,0,$args[1]));
        $this->assertEquals( 'developer', $dev_or_spo );

        // test three: return a sponsor id after 10 records
        $inst_nr = 
             $this->_config_db_ratings_look_for_first_one($db_config, $inst_nr,
                                        $args[2], $uname, 6, 10, false, true );
        $dev_or_spo = '';
        $db = new DB_SourceAgency;
        $this->assertEquals('spoid_9',$this->capture_call($fname,0,$args[2]));
        $this->assertEquals( 'sponsor', $dev_or_spo );

        // test four: neither developer nor sponsor id is returned
        $inst_nr = 
             $this->_config_db_ratings_look_for_first_one($db_config, $inst_nr,
                                        $args[2], $uname, 6, 10, false, false);
        $dev_or_spo = '';
        $db = new DB_SourceAgency;
        $this->assertEquals('',$this->capture_call($fname,0,$args[2]));
        $this->assertEquals( 'sponsor', $dev_or_spo );

        $this->_check_db( $db_config );
    }

    function testRatings_rated_yet() {
        $args=$this->_generate_records(array('proid', 'to_whom', 'by_whom'),3);

        $db_config = new mock_db_configure( 3 );

        $q = $this->queries['ratings_rated_yet'];
        
        for ( $idx = 0; $idx < 3; $idx++ ) {
            $db_config->add_query( sprintf( $q, $args[$idx]['proid'],
                        $args[$idx]['to_whom'],$args[$idx]['by_whom']), $idx );
            $db_config->add_num_row( $idx-1, $idx );
            $r = ( $idx > 1 || $idx < 1 ? 1 : 0 ); 
            $this->assertEquals( $r,$this->capture_call( 'ratings_rated_yet', 
                                                         0, $args[$idx] ));
        }

        $this->_check_db( $db_config );
    }

    function testRatings_look_for_next_one() {
        global $db, $auth, $dev_or_spo, $qs;
        
        $fname = 'ratings_look_for_next_one';
        $uname = 'this is the username';
        $auth->set_uname( $uname );
        $db_config = new mock_db_configure( 27 );
        $args = $this->_generate_records( array( 'proid', 'number' ), 10 );

        $qs = array( 0 => $this->queries[ $fname ],
                     1 => $this->queries[ 'ratings_rated_yet' ] );

        // test one: dev_or_spo is not set ==> $id and $table not defined
        $dev_or_spo = '';
        $db_config->add_query(sprintf($qs[0], '','','', $args[0]['proid'],
                                    '', $uname, '', $args[0]['number'],''), 0);
        $db_config->add_record( false, 0 );
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        call_user_func_array( $fname, $args[0] );
        $this->set_text( capture_stop_and_get() );
        $this->assertEquals( '', $dev_or_spo );
        $this->_testFor_pattern( "<\/b>:  Undefined variable:  table in <b>" );
        $this->_testFor_pattern( "<\/b>:  Undefined variable:  id in <b>" );
        $file = $this->get_file_line_from_warning();
        $slen = 4 * ( strlen( $file[1] ) + strlen( $file[2] ) );
        $slen += ( $this->v_gt( "4.1.0", phpversion()) ? 311 : 327 );
        $this->_testFor_string_length( $slen );
        
        // test two: dev_or_spo == developer, and we find a developer
        $dev_or_spo = 'developer';
        $inst_nr =
             $this->_config_db_ratings_look_for_next_one( $db_config, 1,
                                          $dev_or_spo, $args[1], true, false );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[1] );
        $this->assertEquals( 'developer', $dev_or_spo );

        // test three: dev_or_spo == developer, no developer but sponsor found
        $dev_or_spo = 'developer';
        $inst_nr =
             $this->_config_db_ratings_look_for_next_one( $db_config, $inst_nr,
                                          $dev_or_spo, $args[2], false, true );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[2] );
        $this->assertEquals( 'sponsor', $dev_or_spo );

        // test four: dev_or_spo == developer, no developer & no sponsor found
        $dev_or_spo = 'developer';
        $inst_nr =
             $this->_config_db_ratings_look_for_next_one( $db_config, $inst_nr,
                                         $dev_or_spo, $args[3], false, false );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[3] );
        $this->assertEquals( '', $dev_or_spo );

        // test five: dev_or_spo == sponsor, sponsor is found
        $dev_or_spo = 'sponsor';
        $inst_nr =
             $this->_config_db_ratings_look_for_next_one( $db_config, $inst_nr,
                                         $dev_or_spo, $args[4], false, true );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[4] );
        $this->assertEquals( 'sponsor', $dev_or_spo );

        // test six: dev_or_spo == sponsor, no sponsor found 
        $dev_or_spo = 'sponsor';
        $inst_nr =
             $this->_config_db_ratings_look_for_next_one( $db_config, $inst_nr,
                                         $dev_or_spo, $args[5], false, false );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 0, $args[5] );
        $this->assertEquals( '', $dev_or_spo );

        $this->_check_db( $db_config );
    }

    function testShow_personal_rating() {
        global $t;

        $fname = 'show_personal_rating';
        $qs=array( 0 => $this->queries[ $fname ] );

        $db_config = new mock_db_configure( 3 );
        $args=$this->_generate_records( array( 'proid', 'username' ), 10 );
        $d1 = $this->_generate_records( array( 'rating' ), 11 );

        // test one: no rating
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid'],
                                                 $args[0]['username']), 0 );
        $db_config->add_num_row( 0, 0 );
        $this->capture_call( $fname, 36, $args[0] );
        $str = ( "<p><b>".$args[0]['username']."</b>: "
                 . $t->translate('Not rated yet')."\n" );
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        // test two: one rating
        $db_config->add_query( sprintf( $qs[0], $args[1]['proid'],
                                                 $args[1]['username']), 1 );
        $db_config->add_num_row( 1, 1 );
        $d1[0]['rating'] = 234;
        $db_config->add_record( $d1[0], 1 );
        $db_config->add_record( false, 1 );
        $this->capture_call( $fname, 42, $args[1] );
  	$str = ( "<p><b>".$args[1]['username']."</b>: "
                 .(round(234*100/1)/100)." ("
                 .$t->translate('rated')." 1 ".$t->translate('times').")\n");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        // test three: 10 ratings
        $db_config->add_query( sprintf( $qs[0], $args[2]['proid'],
                                                 $args[2]['username']), 2 );
        $db_config->add_num_row( 1, 2 );
        $total = 0;
        for ( $idx = 1; $idx < 11; $idx++ ) {
            $d1[$idx]['rating'] = $idx;
            $db_config->add_record( $d1[$idx], 2 );
            $total += $idx;
        }
        $db_config->add_record( false, 2 );
        $this->capture_call( $fname, 43, $args[2] );
  	$str = ( "<p><b>".$args[2]['username']."</b>: "
                 .(round($total*100/10)/100)." ("
                 .$t->translate('rated')." 10 ".$t->translate('times').")\n");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        
        $this->_check_db( $db_config );
    }

    function testShow_participants_rating() {
        global $bx, $db;

        $fname = 'show_participants_rating';
        $qs = array( 0 => $this->queries[ $fname ] );
        $args = $this->_generate_records( array( 'proid', 'part_type' ), 10 );
        $db_config = new mock_db_configure( 4 );

        // test one: unknown participant_type, Warning message
        $args[0]['part_type'] = 'UNKNOWN';
        $db_config->add_query( sprintf( $qs[0], $args[0]['part_type'], 
                                                 '', $args[0]['proid'] ), 0);
        $db_config->add_record( false, 0 );
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        capture_reset_and_start();
        call_user_func_array( $fname, $args[0] );
        $this->set_text( capture_stop_and_get() );
        $this->_checkFor_a_box( $args[0]['part_type'] );
        $file = $this->get_file_line_from_warning();
        $slen = strlen( $file[1] ) + strlen( $file[2] );
        $slen += ( $this->v_gt( "4.1.0", phpversion()) ? 754 : 758 );
        $this->_testFor_string_length( $slen );
        $str = '</b>:  Undefined variable:  table in <b>';
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        // test two: participant_type = 'developer'
        $args[1]['part_type'] = 'developer';
        $db_config->add_query( sprintf( $qs[0], $args[1]['part_type'], 
                                        'developing', $args[1]['proid'] ), 1);
        $db_config->add_record( false, 1 );
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 676, $args[1] );
        $this->_checkFor_a_box( $args[1]['part_type'] );

        // test three: participant_type = 'referee'
        $args[2]['part_type'] = 'referee';
        $db_config->add_query( sprintf( $qs[0], $args[2]['part_type'], 
                                        'referees', $args[2]['proid'] ), 2);
        $db_config->add_record( false, 2 );
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 674, $args[2] );
        $this->_checkFor_a_box( $args[2]['part_type'] );

        // test four: participant_type = 'sponsor'
        $args[3]['part_type'] = 'sponsor';
        $db_config->add_query( sprintf( $qs[0], $args[3]['part_type'], 
                                        'sponsoring', $args[3]['proid'] ), 3);
        $db_config->add_record( false, 3 );
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 674, $args[3] );
        $this->_checkFor_a_box( $args[3]['part_type'] );

        $this->_check_db( $db_config );
    }

    function testRatings_form() {
        $this->_test_to_be_completed();
    }
    
    function testRatings_form_full() {
        $this->_test_to_be_completed();
    }

    function testRatings_in_history() {
        // TODO: need to implement the affected_rows() method for the
        // TODO: mock_database class in order to test this function
        $this->_test_to_be_completed();
    }
}

define_test_suite( __FILE__ );
?>
