<?php
// TestViewslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestViewslib.php,v 1.8 2002/05/29 14:57:54 riessen Exp $

include_once( '../constants.php' );

if ( !defined("BEING_INCLUDED" ) ) {
    // required for the html functions
    include_once( 'html.inc');

    // global translation object
    include_once( "translation.inc" );
    $t = new translation("English");

    // required for the $bx global variable
    include_once( "box.inc" );
    $bx = new box;
}

include_once( 'viewslib.inc' );

class UnitTestViewslib
extends UnitTest
{
    function UnitTestViewslib( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        // ensure that the next test does not have a globally defined
        // database object
        unset( $GLOBALS[ 'db' ] );
        unset( $GLOBALS[ 'bx' ] );
    }

    function testViews_form() {

        global $bx, $t, $sess, $db, $preview, $configure, $news, 
            $comments, $history, $step3, $step4, $step5, 
            $cooperation, $views;

        $news = "Project Initiator";
        $configure = "Everybody";
        $comments = "Registered";
        $history = "Sponsors";
        $step3 = "Project Participants";
        $step4 = "Project Developers";
        $step5 = "Project Sponsors";
        $cooperation = "Registered";
        $views = "Sponsors";
        $proid = 'proid';
        $preview = "this is set";
        
        $db_config = new mock_db_configure( 0 );
        $bx = $this->_create_default_box();
        capture_reset_and_start();
        views_form( $proid );
        $text = capture_stop_and_get();
        
        $this->_testFor_captured_length( 9555 + strlen( $sess->self_url() ) );

        $this->_checkFor_a_box( $text, "Configure Information Access in this "
                               ."Project");
        $this->_checkFor_a_form( $text, 'PHP_SELF', 
                                            array('proid'=>'proid'), 'POST' );
        $this->_checkFor_columns( $text, 2 );

        $nbsp = ' &nbsp; &nbsp; &nbsp; ';
        $reco = $t->translate( 'Recommended' );
        $ebody = $t->translate( 'Everybody' );
        $ppart = $t->translate( 'Project Participants' );
        $reged = $t->translate( 'Registered' );
        $pinit = $t->translate( 'Project Initiator' );

        $v=array("View Project Configuration" => 
                 (views_select_view($proid,"configure",$configure)
                  .$nbsp."(".$reco.": <i>".$ppart."</i>)"),
                 "Write and Modify news" =>
                 (views_select_view($proid,"news",$news)
                  .$nbsp."(".$reco.": <i>".$pinit."</i>)"),
                 "Write comments" =>
                 (views_select_view($proid,"comments",$comments)
                  .$nbsp."(".$reco.": <i>".$reged."</i>)"),
                 "See Project History" =>
                 (views_select_view($proid,"history",$history)
                  .$nbsp."(".$reco.": <i>".$ebody."</i>)"),
                 "See Step 3 (Milestones)" =>
                 (views_select_view($proid,"step3",$step3)
                  .$nbsp."(".$reco.": <i>".$ebody."</i>)"),
                 "See Step 4 (Referees)" =>
                 (views_select_view($proid,"step4",$step4)
                  .$nbsp."(".$reco.": <i>".$ebody."</i>)"),
                 "See Step 5 (Project Follow-up)" =>
                 (views_select_view($proid,"step5",$step5)
                  .$nbsp."(".$reco.": <i>".$ebody."</i>)"),
                 "See Developing Cooperation Proposals" =>
                 (views_select_view($proid,"cooperation",$cooperation)
                  .$nbsp."(".$reco.": <i>".$ebody."</i>)"),
                 "Project Permission Access" =>
                 (views_select_view($proid,"views",$views)
                  .$nbsp."(".$reco.": <i>".$ppart."</i>)"));

        while ( list( $key, $val ) = each( $v ) ) {
            $this->_checkFor_column_titles( $text, array( $key ), "Test $key",
                                            'right','30%','','<b>%s</b>: ');
            $this->_checkFor_column_values( $text, array( $val ) );
        }
                 
        $this->_checkFor_submit_preview_buttons( $text );
        $this->_check_db( $db_config );
    }

    function testViews_insert() {
        // this is an alias for views_modify, no need to test it?
    }

    function testViews_modify() {
        global $db;
        
        $db_config = new mock_db_configure( 1 );
        $args=$this->_generate_records( array( 'proid','configure','views',
                                               'news','comments','history',
                                               'step3','step4','step5',
                                               'cooperation'), 1 );

        $q = ("UPDATE views SET  configure='%s', views='%s', news='%s', "
              ."comments='%s', history='%s', step3='%s', step4='%s', "
              ."step5='%s',cooperation='%s' WHERE proid='%s'" );

        $db_config->add_query( sprintf( $q, $args[0]['configure'],
                      $args[0]['views'],$args[0]['news'],$args[0]['comments'],
                      $args[0]['history'],$args[0]['step3'],$args[0]['step4'],
                      $args[0]['step5'],$args[0]['cooperation'],
                      $args[0]['proid']), 0);

        // required for views_show
        $db_config->add_query( "SELECT * FROM views,description WHERE "
                               ."views.proid=description.proid AND "
                               ."views.proid='".$args[0]['proid']."'", 0 );
        // ensures that error message isn't printed by views_show
        $db_config->add_num_row( 1, 0 );
        // ensures no data is printed by views_show
        $db_config->add_record( false, 0 );

        capture_reset_and_start();
        $db = new DB_SourceAgency;
        call_user_func_array( 'views_modify', $args[0] );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 0 );
        $this->_check_db( $db_config );
    }

    function testViews_preview() {
        global $t, $auth, $bx, $configure, $news, $comments, $history, 
            $step3, $step4, $step5, $cooperation, $views;

        $auth->set_uname('this is the username');
        $configure = "this is the configure";
        $news = "this is the news";
        $comments = "these are the comments";
        $history = 'this is the history';
        $step3 = "tjos si step 3";
        $step4 = "tjsp ios tje step 4";
        $step5 = 'this is the stpe 5';
        $cooperation = "tjhs is the cooperation";
        $views = "this is the virw";
        $proid = 'thsi is th proid';

        // created by the call to the lib_show_comments_on_it function
        $db_config = new mock_db_configure( 1 );
        $db_config->add_query("SELECT * FROM comments,auth_user WHERE proid"
                              ."='$proid' AND type='Views' AND number='0' "
                              ."AND ref='0' AND user_cmt=username "
                              ."ORDER BY creation_cmt ASC", 0 );
        $db_config->add_num_row( 0, 0 );
        $bx = $this->_create_default_box();

        capture_reset_and_start();
        views_preview( $proid );
        $text = capture_stop_and_get();
        $this->_testFor_captured_length( 5020 + strlen(timestr(time())));

        $this->_checkFor_a_box($text,'PREVIEW','',
                                        "<center><b>%s</b></center>");
        $this->_checkFor_a_box($text,'Project Information Access' );
        $this->_testFor_pattern( $text, 
                          $this->_to_regexp(lib_nick( $auth->auth['uname'])));
        $v=array( "View Project Configuration"=>$configure,
                  "Write and Modify news"=>$news,
                  "Write comments" => $comments,
                  "See Project History" => $history,
                  "See Step 3 (Milestones)" => $step3,
                  "See Step 4 (Referees)"=>$step4,
                  "See Step 5 (Project Follow-up)"=>$step5,
                  "See Developing Cooperation Proposals"=>$cooperation,
                  "Project Permission Access"=>$views );
        while ( list( $key, $val ) = each( $v ) ) {
            $this->_checkFor_column_titles( $text, array( $key ), '', 'right',
                                                    '30%', '', "<b>%s</b>: " );
            $this->_checkFor_column_values( $text, array( $val ) );
        }
        
        $this->_testFor_lib_comment_it( $text, $proid, 'Views', '', '0',
                                 '', $t->translate( 'Comments on the views?'));

        $this->_check_db( $db_config );
    }

    function testViews_select_view() {
        global $t;
        require( 'config.inc' );

        $db_config = new mock_db_configure( 3 );
        $q = "SELECT %s FROM views WHERE proid='%s'";

        // 6 tests:  three different on_what values, each of which is
        // once selected and once not 
        $args=$this->_generate_records(array('proid','on_what','selected'),6);
        $args[0]['on_what'] = 'fubar';    $args[0]['selected'] = '';
        $args[1]['on_what'] = 'fubar';    $args[1]['selected'] = 'Developers';
        $args[2]['on_what'] = 'news';     $args[2]['selected'] = '';
        $args[3]['on_what'] = 'news'; $args[3]['selected']='Project Initiator';
        $args[4]['on_what'] = 'comments'; $args[4]['selected'] = '';
        $args[5]['on_what'] = 'comments'; $args[5]['selected'] = 'Sponsors';

        for ( $idx = 0; $idx < 6; $idx+=2 ) {
            $db_config->add_query( sprintf( $q, $args[$idx]['on_what'],
                                             $args[$idx]['proid']), $idx / 2 );
            $db_config->add_record( array( $args[$idx]['on_what'] => 'fubar'),
                                    $idx / 2 );
        }
        
        $lens=array(0=>465, 1=>474, 2=>290, 3=>299, 4=>425, 5=>434);
        for ( $idx = 0; $idx < 6; $idx++ ) {

            $text = call_user_func_array( 'views_select_view', $args[$idx] );

            $this->_testFor_string_length( $text, $lens[$idx], "Test $idx" );
            $this->_testFor_html_select($text, $args[$idx]['on_what']);
            $this->_testFor_html_select_end($text);

            reset( $views_array );
            while (list(, $value) = each($views_array)) {
                if ($args[$idx]['on_what']=="news") {
                    if ($value!="Project Participants" 
                    && $value!="Project Sponsors" 
                    && $value!="Project Developers" 
                    && $value !="Project Initiator") {
                        $this->reverse_next_test();
                        $this->_testFor_html_select_option( $text, $value, 
                                               false,$t->translate($value));
                        $this->reverse_next_test();
                        $this->_testFor_html_select_option( $text, $value, 
                                               true,$t->translate($value));
                        continue; 
                    }
                } else if ($args[$idx]['on_what']=="comments") {
                    if ($value=="Everybody") {
                        $this->reverse_next_test();
                        $this->_testFor_html_select_option( $text, $value, 
                                                  false,$t->translate($value));
                        $this->reverse_next_test();
                        $this->_testFor_html_select_option( $text, $value, 
                                                   true,$t->translate($value));
                        continue;
                    }
                }
                
                $selected = ( $idx % 2 ? $args[$idx]['selected'] : 'fubar' );

                $this->_testFor_html_select_option( $text, $value, 
                                                    ($value==$selected),
                                                    $t->translate($value));
            }
        }

        $this->_check_db( $db_config );
    }

    function testViews_show() {
        $this->_test_to_be_completed();
    }

}

define_test_suite( __FILE__ );
?>
