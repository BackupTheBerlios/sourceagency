<?php
// TestConfigurelib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestConfigurelib.php,v 1.8 2002/06/26 09:57:26 riessen Exp $

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
    function UnitTestConfigurelib( $name ) {
        $this->UnitTest( $name );
    }

    function setup() {
    }

    function tearDown() {
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
        $db_config = new mock_db_configure( 3 );
        $db_q = array( 0 => ("SELECT * FROM configure WHERE proid='%s'"));
        
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
        $db_config = new mock_db_configure( 3 );
        $db_q = array( 0 => ("SELECT perms FROM description,auth_user "
                             ."WHERE proid='%s' AND "
                             ."description_user=username"));
        
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
        $qs=array( 0 => ("SELECT * FROM description WHERE proid='%s' "
                         ."AND description_user='%s'"),
                   1 => ("SELECT * FROM auth_user WHERE perms LIKE '%%%s%%' "
                         ."AND username='%s'") );
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
          $other_developing_proposals, $consultants, $auth;

        $uname = 'this is the usernmae';
        $auth->set_uname( $uname );

        $preview = 'this is the preview';
        $quorum = 'this is the qurom';
        $other_tech_contents = 'this is the other tech contents';
        $other_developing_proposals = 'this is the other developing proposals';
        $consultants = 'this is the consulants';

        $db_config = new mock_db_configure( 178 );
        $args = $this->_generate_records( array( 'proid' ), 50 );

        $qs=array( 0 => ("SELECT * FROM configure WHERE proid='%s'") );

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
        $this->capture_call( 'configure_form', 
                             1478 + strlen( $sess->self_url() ), $args[0] );
        $this->_configure_form_checkfor_form_and_box( $args[0] );

        // test two: the same as above, except configure_first_time(..) returns
        // false and therefore it is necessary to set preview
        $auth->set_perm( '' );
        $GLOBALS['preview'] = 'is set and not empty ...';
        $bx = $this->_create_default_box();
        $this->capture_call( 'configure_form', 
                             1478 + strlen( $sess->self_url() ), $args[1] );
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
            $this->capture_call( 'configure_form', 
                                 $len + strlen( $sess->self_url() ), 
                                 $args[$jdx] );

            $this->_configure_form_checkfor_form_and_box( $args[$jdx] );
            $this->_configure_form_checkfor_parts( $cfmask );
        }
        $this->_check_db( $db_config );
    }
    function testConfigure_modify_form() {
        global $quorum, $consultants, $other_tech_contents, 
            $other_developing_proposals;

        $quorum = 'this is the quro';
        $consultants = 'this is the consulatnts';
        $other_tech_contents = 'this is the other tech contents';
        $other_developing_proposals = 'this is the other tech proposals';

        $q = "SELECT * FROM configure WHERE proid='%s'";
        $row=$this->_generate_array( array( 'quorum', 'consultants',
                                            'other_tech_contents',
                                            'other_developing_proposals'),'1');
        $args=$this->_generate_array( array( 'proid' ), '2' );
        $db_config = new mock_db_configure( 1 );
        $db_config->add_query( sprintf( $q, $args['proid'] ), 0 );
        $db_config->add_record( $row, 0 );

        $this->capture_call( 'configure_modify_form', 0, $args );
        
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
        global $t, $bx, $db, $sess;
        
        $qs=array( 0 => "SELECT * FROM configure WHERE proid='%s'" );
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
    
    function testConfigure_insert() {
        $this->_test_to_be_completed();
    }

    function testConfigure_modify() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );

?>
