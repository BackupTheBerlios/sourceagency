<?php
// TestConfigurelib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestConfigurelib.php,v 1.10 2002/07/23 14:09:40 riessen Exp $

include_once( "../constants.php" );

include_once( 'html.inc' );
include_once( 'configurelib.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the $sess global variable
    include_once( "session.inc" );
    $GLOBALS[ 'sess' ] = new Session;
    
    // global translation object
    include_once( "translation.inc" );
    $GLOBALS[ 't' ] = new translation("English");

}

class UnitTestConfigurelib
extends UnitTest
{
    var $queries;

    function UnitTestConfigurelib( $name ) {
        $GLOBALS['queries'] = array (
            'project_type' =>
            ("SELECT perms FROM description,auth_user WHERE proid='%s' AND "
             ."description_user=username"),
            'configure_preview_1' =>
            ("SELECT * FROM description WHERE proid='%s' "
             ."AND description_user='%s'"),
            'configure_preview_2' =>
            ("SELECT * FROM auth_user WHERE perms LIKE '%%%s%%' "
             ."AND username='%s'"),
            'configure_form' =>
            ("SELECT * FROM configure WHERE proid='%s'"),
            'configure_modify_1' =>
            ("UPDATE configure SET %s WHERE proid='%s'"),
            'configure_modify_2' =>
            ("SELECT * FROM auth_user WHERE perms LIKE '%%sponsor%%' "
             ."AND username='%s'"),
            'configure_modify_3' =>
            ("INSERT history SET proid='%s', history_user='%s', "
             ."type='Configure', action='Project configuration modified'"),
            'configure_modify_4' =>
            ("SELECT email_usr FROM auth_user,monitor WHERE monitor.username"
             ."=auth_user.username AND proid='%s' AND (importance='middle' "
             ."OR importance='high')"),
            'configure_insert_1' =>
            ("INSERT configure SET proid='%s',%s"),
            'configure_insert_2' =>
            ("INSERT history SET proid='%s', history_user='%s',"
             ." type='Configure', action='Project configuration'"),
            'configure_insert_3' =>
            ("SELECT * FROM auth_user WHERE perms LIKE '%%devel%%' "
             ."AND username='%s'")
            );
        $this->UnitTest( $name );
    }

    function setup() {
    }

    function tearDown() {
        unset_global( 'bx', 'db' );
    }
    
    function testSelect_quorum() {
        global $auth;

        $auth->set_uname("this is the username");
        $auth->set_perm("this is the permission");

        for ( $idx = -10; $idx < 120; $idx += 5 ) {
            $this->set_text( select_quorum( $idx ) );
            $this->set_msg( "Test $idx" );
            $this->_testFor_html_select( "quorum", 0, 0 );
            for ( $jdx = 55; $jdx < 100; $jdx += 5 ) {
              $this->_testFor_html_select_option( $jdx, $jdx==$idx, $jdx.'%');
            }
            $this->_testFor_html_select_end();
            // length various by 9 according to whether something was selected
            // or not. For values under 55 or over 95 nothing will be selected.
            $this->_testFor_string_length( ( $idx < 55 || $idx > 95 
                                                    ? 320 : 329));
        }
    }

    function testConfigure_first_time() {
        global $queries;
        $db_config = new mock_db_configure( 3 );
        $db_q = array( 0 => $queries['configure_form']);
        
        $dat = $this->_generate_records( array( "proid" ), 3 );
        
        $db_config->add_query( sprintf( $db_q[0], $dat[0]["proid"]), 0 );
        $db_config->add_query( sprintf( $db_q[0], $dat[1]["proid"]), 1 );
        $db_config->add_query( sprintf( $db_q[0], $dat[2]["proid"]), 2 );

        $db_config->add_num_row( 0, 0 );
        $db_config->add_num_row( -1, 1 );
        $db_config->add_num_row( 1, 2 );

        $this->assertEquals( 1, configure_first_time( $dat[0]["proid"]), "1" );
        $this->assertEquals( 0, configure_first_time( $dat[1]["proid"]), "2" );
        $this->assertEquals( 0, configure_first_time( $dat[2]["proid"]), "3" );

        $this->_check_db( $db_config );
    }

    function testProject_type() {
        global $queries;
        $db_config = new mock_db_configure( 3 );
        $db_q = array( 0 => $queries['project_type']);
        
        $dat = $this->_generate_records( array( "proid" ), 3 );
        $rows = $this->_generate_records( array( "perms" ), 3 );

        $rows[0]["perms"] = "sponsor";
        $rows[1]["perms"] = "fubader";
        $rows[2]["perms"] = "devel";

        for ( $idx = 0; $idx < 3; $idx++ ) {
            $db_config->add_query( sprintf( $db_q[0], $dat[$idx]["proid"]), 
                                   $idx );
            $db_config->add_record( $rows[$idx], $idx );
        }
        
        $this->assertEquals( "sponsored",project_type( $dat[0]["proid"]),"1");
        $this->assertEquals( "developed",project_type( $dat[1]["proid"]),"2");
        $this->assertEquals( "developed",project_type( $dat[2]["proid"]),"3");

        $this->_check_db( $db_config );
    }

    function _configure_preview_checkfor_parts( $mask ) {
        global $quorum, $other_tech_contents, $other_developing_proposals, 
            $consultants, $t;

        $v=array( 0=> ("<br><b>".$t->translate("Quorum")."</b>: ".$quorum
                       ." %\n"),
                  1=> ("<br><b>".$t->translate("Other tech_contents")
                       ."</b>: ".$t->translate($other_tech_contents)."\n"),
                  2=> ("<br><b>".$t->translate("Other developing proposals")
                       ."</b>: ".$t->translate($other_developing_proposals)
                       ."\n"),
                  3=> ("<br><b>".$t->translate("Consultants")."</b>: "
                       .$t->translate($consultants)."\n"));

        for ( $idx = 0; $idx < 4; $idx++ ) {
            if ( !$mask[$idx] ) {
                $this->reverse_next_test();
            } 
            $this->_testFor_pattern( $this->_to_regexp( $v[$idx] ) );
        } 
    }
    
    /**
     * add queries to the db_config object which represent a sequence of
     * calls to the is_sponsor[s], is_project_initiator[pi], and 
     * is_developer[d].
     *
     * sinst is the start instance for the db_config object.
     * vals is an array containing the values for the num_rows calls
     */
    function _config__s_pi_d_s_pi__db( &$db_config, $sinst, $proid, $uname, 
                                       $vals ) {
        global $queries;
        $qs=array( 0 => $queries['configure_preview_1'],
                   1 => $queries['configure_preview_2']);
        // 1. first call to is_sponsor
        $db_config->add_query( sprintf( $qs[1], 'sponsor', $uname ), $sinst );
        $db_config->add_num_row( $vals[0], $sinst++ );
        // 2. first call to is_project_initiator(..)
        $db_config->add_query(sprintf( $qs[0], $proid, $uname),$sinst);
        $db_config->add_num_row( $vals[1], $sinst++ );
        // 3. only call to is_developer
        $db_config->add_query( sprintf( $qs[1], 'devel', $uname ), $sinst );
        $db_config->add_num_row( $vals[2], $sinst++ );
        // 4. second call to is_sponsor
        $db_config->add_query( sprintf( $qs[1], 'sponsor', $uname ), $sinst );
        $db_config->add_num_row( $vals[3], $sinst++ );
        // 5. second call to is_project_initiator(..) only happens if second
        // call to is_sponsor returned true
        if ( $vals[3] ) {
            $db_config->add_query( sprintf( $qs[0], $proid, $uname), $sinst);
            $db_config->add_num_row( $vals[4], $sinst++ );
        }
        return $sinst;
    }

    function _configure_form_config_db( $sinst, &$db_config, &$args, &$qs, 
                                        $uname, $vals ) {
        // 0. call to configure_first_time(..)
        $db_config->add_query( sprintf( $qs[0], $args['proid']), $sinst );
        $db_config->add_num_row( $vals[0], $sinst++ );

        array_shift( $vals );
        return $this->_config__s_pi_d_s_pi__db( $db_config, $sinst, 
                                               $args['proid'], $uname, $vals );
    }
    
    function _configure_form_checkfor_form_and_box( &$args ) {
        $this->_checkFor_columns( 2 );
        $this->_checkFor_submit_preview_buttons();
        $this->_checkFor_a_box( 'Configure Project' );
        $this->_checkFor_a_form( 'PHP_SELF', array( 'proid' => 
                                                    $args['proid']),'POST');
    }

    function _configure_form_checkfor_parts( $mask ) {
        global $quorum, $other_tech_contents, $other_developing_proposals, 
            $consultants, $t;
        $tit = array( 0 => 'Quorum for decision making',
                      1 => 'Other technical contents?',
                      2 => 'Other developing proposals?',
                      3 => 'Consultants?' );
        $val = array( 0 => select_quorum( $quorum ),
                      1 => lib_select_yes_or_no("other_tech_contents",
                                                $other_tech_contents),
                      2 => lib_select_yes_or_no("other_developing_proposals",
                                                $other_developing_proposals),
                      3 => lib_select_yes_or_no("consultants",$consultants) );

        for ( $idx = 0; $idx < 4; $idx++ ) {
            if ( $mask[$idx] ) {
                $this->_checkFor_column_titles( array( $tit[$idx] ), 'right', 
                                                '30%', '', '<b>%s</b>: ');
                $this->_checkFor_column_values( array( $val[$idx] ) );
            } else {
                $this->reverse_next_test();
                $this->_testFor_pattern( $this->_to_regexp( $val[$idx] ) );
                $this->reverse_next_test();
                $this->_testFor_pattern( $this->_to_regexp( 
                                                $t->translate($tit[$idx])));
            }
        }
    }
    function testConfigure_form() {
        global $bx, $t, $sess, $preview, $quorum, $other_tech_contents, 
            $other_developing_proposals, $consultants, $auth, $queries;

        $uname = 'this is the usernmae';
        $auth->set_uname( $uname );

        $preview = 'this is the preview';
        $quorum = 'this is the qurom';
        $other_tech_contents = 'this is the other tech contents';
        $other_developing_proposals = 'this is the other developing proposals';
        $consultants = 'this is the consulants';

        $db_config = new mock_db_configure( 178 );
        $args = $this->_generate_records( array( 'proid' ), 50 );
        $fname = 'configure_form';
        $qs=array( 0 => $queries[$fname] );

        // test1: configure_first_time(..) returns 1 (true)
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid']), 0 );
        $db_config->add_num_row( 0, 0 );
        // test2: configure_first_time(..) returns 0 (false)
        $db_config->add_query( sprintf( $qs[0], $args[1]['proid']), 1 );
        $db_config->add_num_row( 1, 1 );

        // test one, all queries to is_sponsor and is_project_initiator and
        // is_developer return false because the perm attribute of auth
        // is empty.
        $auth->set_perm( '' );
        unset_global( 'preview' );
        $bx = $this->_create_default_box();
        $this->capture_call( $fname, 
                             1478 + strlen( $sess->self_url() ), $args[0] );
        $this->_configure_form_checkfor_form_and_box( $args[0] );

        // test two: the same as above, except configure_first_time(..) returns
        // false and therefore it is necessary to set preview
        $auth->set_perm( '' );
        $GLOBALS['preview'] = 'is set and not empty ...';
        $bx = $this->_create_default_box();
        $this->capture_call( $fname, 1478 + strlen( $sess->self_url() ), 
                             $args[1] );
        $this->_configure_form_checkfor_form_and_box( $args[1] );

        // switch authorisation for the tests to follow
        $auth->set_perm( 'hell yes!' );

        // now do an exhaustive test, i.e. test all possible combination
        // of query return values
        $qmask = array( 0 => 0 );
        $cfmask = array();
        $sinst = 2;
        for ( $idx = 0, $jdx = 2; $idx < 32; $idx++, $jdx++ ) {
            $cfmask[0] = ($qmask[1] = (($idx & 0x01) > 0));
            $cfmask[1] = ($qmask[2] = (($idx & 0x02) > 0));
            $cfmask[2] = ($qmask[3] = (($idx & 0x04) > 0));
            $qmask[4] = (($idx & 0x08) > 0);
            $qmask[5] = (($idx & 0x10) > 0);
            $cfmask[3] =  ($qmask[4] && $qmask[5]);

            $this->set_msg( 'Test ' . $jdx . " 0=>" . $qmask[1]
                            . " 1=>" . $qmask[2]. " 2=>" . $qmask[3]
                            . " 3=>" . $qmask[4]. " 4=>" . $qmask[5]);
            $len = ( 1478 + ($cfmask[0] ? 724 : 0) + ($cfmask[1] ? 458 : 0)
                     + ($cfmask[2] ? 535 : 0) + ($cfmask[3] ? 438 : 0) );
            // FIXME: why do we add one more to the length if the second
            // FIXME: call to is_sponsor _or_ the second call to is_project_
            // FIXME: initiator returns true *but* not when both are true!!!
            if ( ($qmask[4] && !$qmask[5]) 
                 || (!$qmask[4] && $qmask[5]) ) $len++;

            $sinst = $this->_configure_form_config_db( $sinst, $db_config, 
                                            $args[$jdx], $qs, $uname, $qmask );
            $bx = $this->_create_default_box();
            $this->capture_call( $fname, $len + strlen( $sess->self_url() ), 
                                 $args[$jdx] );

            $this->_configure_form_checkfor_form_and_box( $args[$jdx] );
            $this->_configure_form_checkfor_parts( $cfmask );
        }
        $this->_check_db( $db_config );
    }
    function testConfigure_modify_form() {
        global $quorum, $consultants, $other_tech_contents, 
            $other_developing_proposals, $queries;

        $quorum = 'this is the quro';
        $consultants = 'this is the consulatnts';
        $other_tech_contents = 'this is the other tech contents';
        $other_developing_proposals = 'this is the other tech proposals';

        $fname = 'configure_modify_form';
        $q = $queries[ 'configure_form' ];
        $row=$this->_generate_array( array( 'quorum', 'consultants',
                                            'other_tech_contents',
                                            'other_developing_proposals'),'1');
        $args=$this->_generate_array( array( 'proid' ), '2' );
        $db_config = new mock_db_configure( 1 );
        $db_config->add_query( sprintf( $q, $args['proid'] ), 0 );
        $db_config->add_record( $row, 0 );

        $this->capture_call( $fname, 0, $args );
        
        while( list( $key, $val ) = each( $row ) ) {
            $this->assertEquals( $val, $$key, "Key = $key" );
        }
        $this->_check_db( $db_config );
    }

    function testConfigure_preview() {
        global $t, $bx, $auth, $quorum, $other_tech_contents, 
            $other_developing_proposals, $consultants;

        $args=$this->_generate_records( array( 'proid' ), 32 );
        $uname = 'this is the username';
        $auth->set_uname( $uname );

        $quorum = 'this is the quro';
        $consultants = 'this is the consulatnts';
        $other_tech_contents = 'this is the other tech contents';
        $other_developing_proposals = 'this is the other tech proposals';

        $db_config = new mock_db_configure( 144 );
        $sinst = 0;
        $mask = array();
        $cf_mask = array();
        for ( $idx = 0; $idx < 32; $idx++ ) {
            $cf_mask[0] = ($mask[0] = (($idx & 0x01) > 0));
            $cf_mask[1] = ($mask[1] = (($idx & 0x02) > 0));
            $cf_mask[2] = ($mask[2] = (($idx & 0x04) > 0));
            $mask[3] = (($idx & 0x08) > 0);
            $mask[4] = (($idx & 0x10) > 0);
            $cf_mask[3] =  ($mask[3] && $mask[4]);

            $this->set_msg( 'Test ' . $idx . " 0=>" . $mask[0]
                            . " 1=>" . $mask[1]. " 2=>" . $mask[2]
                            . " 3=>" . $mask[3]. " 4=>" . $mask[4]);

            $len = ( 948 + ($cf_mask[0] ? 38 : 0) + ($cf_mask[1] ? 64 : 0)
                     + ($cf_mask[2] ? 72 : 0) + ($cf_mask[3] ? 48 : 0) );
            $sinst = $this->_config__s_pi_d_s_pi__db( $db_config, $sinst, 
                                         $args[$idx]['proid'], $uname, $mask );
            $bx = $this->_create_default_box();
            $this->capture_call( 'configure_preview', 
                                 $len + strlen(timestr(time())), $args[$idx] );
            $this->_checkFor_a_box( 'PREVIEW', '<center><b>%s</b></center>' );
            $this->_checkFor_a_box( 'Project Configuration' );
            $this->_configure_preview_checkfor_parts( $cf_mask );
        }

        $this->_check_db( $db_config );
    }

    function testConfigure_show() {
        global $t, $bx, $db, $sess, $queries;
        
        $qs=array( 0 => $queries['configure_form'] );
        $args=$this->_generate_records( array( 'proid' ), 3 );
        $dat=$this->_generate_records( array( 'quorum', 'consultants',
                                              'other_tech_contents','sponsor',
                                              'other_developing_proposals',
                                              'developer'), 2 );
        $db_config = new mock_db_configure( 3 );
        // test one
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid']), 0 );
        $db_config->add_num_row( 0, 0 );
        // test two
        $db_config->add_query( sprintf( $qs[0], $args[1]['proid']), 1 );
        $db_config->add_num_row( 1, 1 );
        $db_config->add_record( $dat[0], 1 );
        $db_config->add_record( false, 1 );
        // test three
        $db_config->add_query( sprintf( $qs[0], $args[2]['proid']), 2 );
        $db_config->add_num_row( 1, 2 );
        $dat[1]['quorum'] = '';
        $dat[1]['sponsor'] = '';
        $dat[1]['developer'] = '';
        $db_config->add_record( $dat[1], 2 );
        $db_config->add_record( false, 2 );

        // test one: no results
        $str = "<p>".$t->translate("The project parameters have not been "
                                   ."configured by the project owner(s)")
             .".<p>\n";
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'configure_show', strlen( $str ), $args[0] );
        $this->assertEquals( $str, $this->get_text(), "test 1" );

        // test two: one record with all values set
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'configure_show', 944, $args[1] );
        $this->_checkFor_configure_show( $dat[0] );

        // test three: one record with unset values
        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'configure_show', 975, $args[2] );
        $this->_checkFor_configure_show( $dat[1] );

        $this->_check_db( $db_config );
    }

    function _checkFor_configure_show( &$dat ) {
        global $t;
        $this->_checkFor_a_box( 'Project Configuration' );
        $titles = array('Developer' => ($dat['developer'] != '' ? 
                     $dat['developer'] : $t->translate( 'No main developer' )),
                        'Consultants' =>   $t->translate( $dat['consultants']),
                        'Other Technical Contents'=>
                                   $t->translate( $dat['other_tech_contents']),
                        'Other Developing Proposals'=>
                            $t->translate( $dat['other_developing_proposals']),
                        'First Sponsor'=> ($dat['sponsor'] != '' ? 
                              $dat['sponsor'] : $t->translate( 'No sponsors')),
                        'Quorum'=> ($dat['quorum'] != '' ? $dat['quorum']."%" :
                          $t->translate("Decision to be taken by Sponsors")));
        while ( list( $title, $val ) = each( $titles ) ) {
            $str = "<b>" . $t->translate($title) . "</b>: " . $val . "\n";
            $this->_testFor_pattern( "[<]..?[>]".$this->_to_regexp( $str ) );
        }
    }
    
    function _config_db_configure_modify( &$db_config, $inst_nr, &$args, 
                                                      $p_type, $is_sponsor ) {
        global $auth, $quorum, $consultants, $other_tech_contents,
            $other_developing_proposals, $t, $qs;

        $db_i_global = $inst_nr++;
        $db_i_p_type = $inst_nr++;
        $db_i_is_spo = $inst_nr++;
        $db_i_mail = $inst_nr++;

        /* set project type */
        $db_config->add_query(sprintf( $qs[0], $args['proid']), $db_i_p_type);
        $db_config->add_record( array( 'perms' => $p_type ), $db_i_p_type );
        /* is_sponsor call */
        $db_config->add_query( sprintf( $qs[2], $auth->auth['uname']),
                               $db_i_is_spo );
        $db_config->add_num_row( $is_sponsor ? 1 : 0, $db_i_is_spo );
        
        if ( $p_type == 'sponsor' ) {
            if ( $is_sponsor ) {
                $query = ( "quorum='$quorum',consultants="
                           ."'$consultants',other_tech_contents="
                           ."'$other_tech_contents',"
                           ."other_developing_proposals='Yes',"
                           ."sponsor='".$args['user']."'" );
            } else {
                $query = "developer='".$args['user']."'";
            }
        } else {
            if ( $is_sponsor ) {
                $query = "quorum='$quorum'";
            } else {
                $query = "developer='".$args['user']."'";
            }
        }
        /** update query **/
        $db_config->add_query( sprintf( $qs[1], $query, $args['proid']), 
                               $db_i_global );
        /** configure_show call **/
        $db_config->add_query( sprintf( $qs[5], $args['proid'] ),$db_i_global);
        $db_config->add_num_row( 0, $db_i_global );
        /** monitor_mail call **/
        $db_config->add_query( sprintf( $qs[4], $args['proid']), $db_i_mail );
        $db_config->add_record( false, $db_i_mail );
        /** insert into the history table **/
        $db_config->add_query( sprintf( $qs[3], $args['proid'], $args['user']),
                               $db_i_global );
        
        return $inst_nr;
    }

    function testConfigure_modify() {
        global $db, $auth, $quorum, $consultants, $other_tech_contents,
            $other_developing_proposals, $t, $qs, $queries;

        $fname = 'configure_modify';
        $qs = array( 0 => $queries['project_type'],
                     1 => $queries[ $fname . '_1' ],/*update*/
                     2 => $queries[ $fname . '_2' ],/*is_sponsor*/
                     3 => $queries[ $fname . '_3' ],/*history insert*/
                     4 => $queries[ $fname . '_4' ],/*monitor_mail*/
                     5 => $queries['configure_form'] );/*config. show*/
                     
        $db_config = new mock_db_configure( 16 );
        $inst_nr = 0;
        $auth->set_uname( 'this is the username' );
        $auth->set_perm( 'hell yes' );
        $args = $this->_generate_records( array( 'proid', 'user' ), 10 );
        
        $quorum = 'thsi si the quorum';
        $consultants = 'these are rthe consultants';
        $other_tech_contents = 'this si the other contents';
        $other_developing_proposals = 'this is the other proposals';
        
        // test one: project type == sponsored, user is sponsor
        $inst_nr = $this->_config_db_configure_modify( $db_config, $inst_nr, 
                                          $args[0], 'sponsor', true );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 79, $args[0] );
        $str = $t->translate("The project parameters have not been "
                             ."configured by the project owner(s)");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        // test two: project type == sponsored, user is not sponsor
        $inst_nr = $this->_config_db_configure_modify( $db_config, $inst_nr, 
                                          $args[1], 'sponsor', false );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 79, $args[1] );
        $str = $t->translate("The project parameters have not been "
                             ."configured by the project owner(s)");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        // test three: project type != sponsored, user is not sponsor
        $inst_nr = $this->_config_db_configure_modify( $db_config, $inst_nr, 
                                          $args[2], 'NOT sponsor', false );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 79, $args[2] );
        $str = $t->translate("The project parameters have not been "
                             ."configured by the project owner(s)");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        // test three: project type != sponsored, user is sponsor
        $inst_nr = $this->_config_db_configure_modify( $db_config, $inst_nr, 
                                          $args[3], 'NOT sponsor', true );
        $db = new DB_SourceAgency;
        $this->capture_call( $fname, 79, $args[3] );
        $str = $t->translate("The project parameters have not been "
                             ."configured by the project owner(s)");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        $this->_check_db( $db_config );
    }

    function _checkFor_configure_insert( &$args, $is_sponsor, $is_developer ){
        global $t, $auth;
        $strings = array( 
            $t->translate("The project parameters have not been "
                          ."configured by the project owner(s)").".<p>\n",
            "<b>".$t->translate("Congratulations")."</b>. "
            .$t->translate("You have just configured your project")
            .".<p>\n");

        if ( $is_developer ) {
            $this->_checkFor_box_full(
                $t->translate('Project Insertion process'),
                $t->translate('In order to insert a project, you will have'
                              .' to follow this steps: <ul><li>Fill out '
                              .'the insertion formular <li>Configure the '
                              .'project parameters </ul> <p>After that you'
                              .' should wait for a BerliOS editor to '
                              .'review your project'));
            
            if ( !$is_sponsor ) {
                $strings[]=('<b>Congratulations. You are finished with the '
                            . 'insertion '
                            . "process.</b>\n<br>You will now have to wait "
                            . "for a SourceAgency editor to review your "
                            . "pending project.\n<br>Once this is done, you "
                            . "will receive an e-mail.\n"
                            . '<p>At your ' 
                            . html_link('personal.php3', 
                                        array( 'username'=>
                                               $auth->auth['uname'] ),
                                        'Personal Page')
                            . ' you will be <b>now</b> be able to see your '
                            . 'project in '
                            . "<b>step 0</b> (<b>pending</b>).\n"
                            . "<br>When the project is reviewed this will "
                            ."also change.\n");
            }
                
        } else if ( $is_sponsor ) {
            $this->_checkFor_box_full( 
                $t->translate('Project Insertion process'),
                $t->translate('You are logged in in SourceAgency as '
                              .'sponsor <p>In order to insert a project,'
                              .' you will have to follow this steps: '
                              .'<ul><li>Fill out the insertion formular'
                              .' <li>Configure the project parameters'
                              .' <li>Fill out a sponsoring involvement'
                              .' form for your project</ul> <p>After that'
                              .' you should wait for a BerliOS editor to'
                              .' review your project'));
            $strings[] = ("<br>".
                          $t->translate("The last step before waiting to be "
                                        ."reviewed by an editor is to")
                          . " "
                          .html_link("sponsoring_edit.php3",
                                     array("proid" => $args['proid']),
                                     $t->translate("Sponsor this project")));
        }

        foreach ( $strings as $str ) {
            $this->_testFor_pattern( $this->_to_regexp( $str ) );
        }
    }

    function _config_db_configure_insert( &$db_config, $inst_nr, &$args,
                                          $is_sponsor, $is_developer ) {
        global $auth, $quorum, $consultants, $other_tech_contents,
            $other_developing_proposals, $qs;

        $db_i_global = $inst_nr;
        /* configure_first_time call, always returns false */
        $inst_nr++;
        $db_config->add_query( sprintf( $qs[0], $args['proid']), $inst_nr );
        $db_config->add_num_row( 0, $inst_nr );
        /* is sponsor call */
        $inst_nr++;
        $db_config->add_query( sprintf($qs[4],$auth->auth['uname']),$inst_nr);
        $db_config->add_num_row( $is_sponsor ? 1 : 0, $inst_nr );
        if ( $is_sponsor ) {
            $query = ("quorum='$quorum',consultants='$consultants',"
                      ."other_tech_contents='$other_tech_contents',"
                      ."other_developing_proposals='Yes',sponsor='".
                      $args['user']."',developer=''");
        } else {
            $query =("consultants='No',other_tech_contents='"
                     ."$other_tech_contents',other_developing_proposals="
                     ."'$other_developing_proposals',developer='"
                     .$args['user']."'");
        }
        /** insert query **/
        $db_config->add_query( sprintf( $qs[1], $args['proid'], $query),
                               $db_i_global );
        /** configure show call **/
        $db_config->add_query(sprintf( $qs[0], $args['proid'] ), $db_i_global);
        $db_config->add_num_row( 0, $db_i_global );
        /** insert into history **/
        $db_config->add_query(sprintf($qs[2],$args['proid'],$args['user']),
                              $db_i_global );
        /** is sponsor call **/
        $inst_nr++;
        $db_config->add_query( sprintf($qs[4],$auth->auth['uname']),$inst_nr);
        $db_config->add_num_row( $is_sponsor ? 1 : 0, $inst_nr );
        if ( !$is_sponsor ) {
            $inst_nr++;
            $db_config->add_query( sprintf($qs[3],$auth->auth['uname']),
                                   $inst_nr);
            $db_config->add_num_row( $is_developer ? 1 : 0, $inst_nr );
        }
        /** lib_insertion_information call **/
        $inst_nr++; /** is sponsor call **/
        $db_config->add_query( sprintf($qs[4],$auth->auth['uname']),$inst_nr);
        $db_config->add_num_row( $is_sponsor ? 1 : 0, $inst_nr );
        $inst_nr++; /** is developer call **/
        $db_config->add_query( sprintf($qs[3],$auth->auth['uname']),$inst_nr);
        $db_config->add_num_row( $is_developer ? 1 : 0, $inst_nr );
        
        return ++$inst_nr;
    }

    function testConfigure_insert() {
        global $db,$auth,$quorum,$consultants,$other_tech_contents,
            $other_developing_proposals, $t, $qs, $bx, $queries;
        // TODO: this test does not test whether configure_modify is
        // TODO: called when configure_first_time returns false ...
        $fname = 'configure_insert';
        $qs = array( 0 => $queries['configure_form'],/*conf. first time*/
                     1 => $queries[ $fname . '_1' ],/*insert*/
                     2 => $queries[ $fname . '_2' ],/*history insert*/
                     3 => $queries[ $fname . '_3' ],/*is developer*/
                     4 => $queries['configure_modify_2']);/*is sponsor*/
        $args=$this->_generate_records( array( 'proid', 'user' ), 10 );
        $db_config = new mock_db_configure( 26 );
        $inst_nr = 0;

        // test one: is sponsor but not developer
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        $inst_nr = $this->_config_db_configure_insert( $db_config, $inst_nr, 
                                                      $args[0], false, true );
        $this->capture_call( $fname, 1573, $args[0] );
        $this->_checkFor_configure_insert( $args[0], false, true );

        // test two: is neither a sponsor nor a developer
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        $inst_nr = $this->_config_db_configure_insert( $db_config, $inst_nr, 
                                                      $args[1], false, false );
        $this->capture_call( $fname, 153, $args[1] );
        $this->_checkFor_configure_insert( $args[1], false, false );

        // test three: is developer but not sponsor
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        $inst_nr = $this->_config_db_configure_insert( $db_config, $inst_nr, 
                                                      $args[2], true, false );
        $this->capture_call( $fname, 1383, $args[2] );
        $this->_checkFor_configure_insert( $args[2], true, false );

        // test four: is both a developer and a sponsor
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        $inst_nr = $this->_config_db_configure_insert( $db_config, $inst_nr, 
                                                      $args[3], true, true );
        $this->capture_call( $fname, 2361, $args[3] );
        $this->_checkFor_configure_insert( $args[3], true, true );

        $this->_check_db( $db_config );
    }
}

define_test_suite( __FILE__ );

?>
