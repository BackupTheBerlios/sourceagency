<?php
// TestContentlib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestContentlib.php,v 1.1 2003/11/21 12:56:03 helix Exp $

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
    var $queries;

    function UnitTestContentlib( $name ) {
        $this->queries = array( 
          'content_box_footer_1' =>
          ("SELECT COUNT(*) FROM developing WHERE proid='%s' "
           ."AND content_id='%s'"),
          'content_box_footer_2' =>
          ("SELECT COUNT(*) FROM comments WHERE proid='%s' AND "
           ."type='Specifications' AND number='%s'"),
          'show_content' =>
          ("SELECT * FROM tech_content,auth_user WHERE proid='%s' "
           ."AND content_user=username ORDER BY creation"),
          'content_insert' =>
          ("INSERT tech_content SET proid='%s',content_user='%s',"
           ."skills='%s',platform='%s',architecture='%s',"
           ."environment='%s',docs='%s',specification='%s',"
           ."status='P'"),
          'content_modify' =>
          ("UPDATE tech_content SET proid='%s',content_user='%s',"
           ."skills='%s',platform='%s',architecture='%s',"
           ."environment='%s',docs='%s',specification='%s', status"
           ."='M' WHERE creation='%s'"),
          'show_selected_content' =>
          ("SELECT * FROM tech_content,auth_user WHERE proid='%s' "
           ."AND content_user=username AND tech_content.status='A'"),
          'show_proposals_1' =>
          ("SELECT * FROM developing,auth_user WHERE proid='%s' AND "
           ."content_id='%s' AND developer=username ORDER BY "
           ."developing.creation"),
          'show_proposals_2' =>
          ("SELECT COUNT(*) FROM cooperation WHERE devid='%s'"),
          'show_proposals_3' =>
          ("SELECT COUNT(*) FROM comments WHERE proid='%s' AND type="
           ."'proposals' AND number='%s'")
          );
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
        unset_global( 'bx', 'db' );
    }

    function testContent_box_footer() {
        global $bx, $db, $sess;
        
        $args = $this->_generate_records(array('proid','content_id',
                                               'which_proposals'), 4);

        $dat=$this->_generate_records(array('content_id'), 4 );
        $dat2=$this->_generate_records( array( 'COUNT(*)'), 8 );
        $db_config = new mock_db_configure( 8 );
        $q = array( 0 => $this->queries['content_box_footer_1'],
                    1 => $this->queries['content_box_footer_2']);

        // test one, ensure that both if statements are false
        $dat[0]['content_id'] = $args[0]['which_proposals'];
        $dat2[1]['COUNT(*)'] = -1;
        // test two: ensure that both if statements are true
        $dat[1]['content_id'] = $args[1]['which_proposals'] . "DONT MATCH";
        $dat2[3]['COUNT(*)'] = 10;
        // test three: first if true, second if false
        $dat[2]['content_id'] = $args[2]['which_proposals'] . "DONT MATCH";
        $dat2[5]['COUNT(*)'] = -1;
        // test four: first if false, second if true
        $dat[3]['content_id'] = $args[3]['which_proposals'];
        $dat2[7]['COUNT(*)'] = 10;

        // test one
        $db_config->add_record( $dat[0], 0 );
        $db_config->add_query( sprintf( $q[0], $args[0]['proid'], 
                                           $args[0]['content_id'] ), 1 );
        $db_config->add_query( sprintf( $q[1], $args[0]['proid'], 
                                           $args[0]['content_id'] ), 1 );
        $db_config->add_record( $dat2[0], 1 );
        $db_config->add_record( $dat2[1], 1 );
        $db_config->add_query( 'fubar', 0 );
        
        // test two
        $db_config->add_record( $dat[1], 2 );
        $db_config->add_query( sprintf( $q[0], $args[1]['proid'], 
                                           $args[1]['content_id'] ), 3 );
        $db_config->add_query( sprintf( $q[1], $args[1]['proid'], 
                                           $args[1]['content_id'] ), 3 );
        $db_config->add_record( $dat2[2], 3 );
        $db_config->add_record( $dat2[3], 3 );
        $db_config->add_query( 'fubar', 2 );

        // test three
        $db_config->add_record( $dat[2], 4 );
        $db_config->add_query( sprintf( $q[0], $args[2]['proid'], 
                                           $args[2]['content_id'] ), 5 );
        $db_config->add_query( sprintf( $q[1], $args[2]['proid'], 
                                           $args[2]['content_id'] ), 5 );
        $db_config->add_record( $dat2[4], 5 );
        $db_config->add_record( $dat2[5], 5 );
        $db_config->add_query( 'fubar', 4 );

        // test four
        $db_config->add_record( $dat[3], 6 );
        $db_config->add_query( sprintf( $q[0], $args[3]['proid'], 
                                           $args[3]['content_id'] ), 7 );
        $db_config->add_query( sprintf( $q[1], $args[3]['proid'], 
                                           $args[3]['content_id'] ), 7 );
        $db_config->add_record( $dat2[6], 7 );
        $db_config->add_record( $dat2[7], 7 );
        $db_config->add_query( 'fubar', 6 );

        // a global db object is required which has 'content_id' as
        // row value. (content_box_footer is called by show_content which
        // uses the global database).
        // test one: both if statements are false
        $db = new DB_SourceAgency;
        $db->query( 'fubar' );
        $db->next_record();
        $bx = $this->_create_default_box();
        $this->capture_call( 'content_box_footer', 852, $args[0] );
        $this->_checkFor_content_box_footer($args[0],$dat2[0],
                                                          $dat2[1],$dat[0]);

        // test two: both if statements are true
        $db = new DB_SourceAgency;
        $db->query( 'fubar' );
        $db->next_record();
        $bx = $this->_create_default_box();
        $this->capture_call( 'content_box_footer', 1018, $args[1] );
        $this->_checkFor_content_box_footer($args[1],$dat2[2],
                                                          $dat2[3],$dat[1]);

        // test three: first if true, second if false
        $db = new DB_SourceAgency;
        $db->query( 'fubar' );
        $db->next_record();
        $bx = $this->_create_default_box();
        $this->capture_call( 'content_box_footer', 925, $args[2] );
        $this->_checkFor_content_box_footer($args[2],$dat2[4],
                                                          $dat2[5],$dat[2]);

        // test four: first if false, second if true
        $db = new DB_SourceAgency;
        $db->query( 'fubar' );
        $db->next_record();
        $bx = $this->_create_default_box();
        $this->capture_call( 'content_box_footer', 945, $args[3] );
        $this->_checkFor_content_box_footer($args[3],$dat2[6],
                                                          $dat2[7],$dat[3]);

        $this->_check_db( $db_config );
    }

    function _checkFor_content_box_footer( &$args, &$dat_query_1,
                                           &$dat_query_2, &$dat_db ) {
        global $t;
        // args: 3 values ==> 'proid', 'content_id', 'which_proposals'
        // dat_query_1: 1 value ==> 'COUNT(*)'
        // dat_query_2: 1 value ==> 'COUNT(*)'
        // dat_db: 1 value ==> 'content_id'
        $this->_checkFor_columns( 3 );

        if ( !strcmp( $dat_db['content_id'], $args['which_proposals'] ) ){
            $this->_testFor_html_link( 'step2.php3',
                                       array('proid' => $args['proid']),
                                       'Proposals');
        } else {
            $this->_testFor_html_link( 'step2.php3',
                                       array('proid' => $args['proid'], 
                                             'show_proposals' => 'yes', 
                                             'which_proposals' => 
                                             $dat_db['content_id']),
                                       'Proposals');
        }
        $str = ' ['.$dat_query_1["COUNT(*)"].'] &nbsp;|&nbsp; ';
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        if ( $dat_query_2['COUNT(*)'] > 0 ) {
            $this->_testFor_html_link( 'comments.php3',
                                       array('proid' => $args['proid'], 
                                             'type' => 'Specifications', 
                                             'number' => $args['content_id']),
                                       'Comments');
            $str = ' ['.$dat_query_2["COUNT(*)"]."]\n";
        } else {
            $str = $t->translate('No Comments')."\n";
        }
        $this->_testFor_pattern( $this->_to_regexp( $str ) );

        $this->_testFor_html_link('developing_edit.php3',
                                  array('proid' => $args['proid'], 
                                        'content_id'=>$dat_db['content_id']),
                                  '[ <b>'.$t->translate('Make a Proposal to '
                                                        .'this content!')
                                  .'</b> ]&nbsp;');
    }

    function testContent_form() {
        global $bx, $sess, $docs, $specification;
        global $skills, $platform, $architecture, $environment;

        $skills = 'thsi si th sill';
        $platform = 'thsi is the pplatform';
        $architecture = 'tjsio is the archtierdt';
        $environment = 'tjis is te enforment';
        $docs = 'thsi is the docs';
        $specification = 'this is the specification';

        $proid = 'this is the proid';
        $bx = $this->_create_default_box();
        $this->capture_call( 'content_form', 
                             5098 + strlen( $sess->self_url() ), $proid );

        $this->_checkFor_a_box( 'Suggesting a technical content' );
        $this->_checkFor_a_form( 'PHP_SELF', array('proid' => $proid),'POST');
        $this->_checkFor_columns( 2 );
        $this->_checkFor_submit_preview_buttons();
        $titles=array( 'Specification' => '<b>%s</b> (*): ',
                       'Needed Skills' => '<b>%s</b> (64): ',
                       'Platform' => '<b>%s</b>: ',
                       'Architecture' => '<b>%s</b>: ',
                       'Environment' => '<b>%s</b>: ',
                       'Further Documentation' => '<b>%s</b> (255): ' );
        while ( list( $title, $temp ) = each( $titles ) ) {
            $this->_checkFor_column_titles( array( $title ), 'right', '30%',
                                            '', $temp );
        }
        
        $v=array( html_textarea('specification',40,7,'virtual',255,
                                $specification),
                  html_input_text('skills',40,64,$skills),
                  select_from_config('platform','platform_array',$platform),
                  select_from_config('architecture','architecture_array',
                                     $architecture),
                  select_from_config('environment','environment_array',
                                     $environment),
                  html_input_text('docs',40,255,$docs) );
        $this->_checkFor_column_values( $v );
    }

    function testContent_insert() {
        global $db;
        
        $db_config = new mock_db_configure( 1 );
        $qs=array(0=>$this->queries['show_content'],
                  1=>$this->queries['content_insert']);

        $args=$this->_generate_records( array( 'proid', 'user', 'skills',
                                               'platform', 'architecture',
                                               'environment', 'docs', 
                                               'specification'), 1 );
        $db_config->add_query( sprintf( $qs[1], $args[0]['proid'],
                                 $args[0]['user'],$args[0]['skills'],
                                 $args[0]['platform'],$args[0]['architecture'],
                                 $args[0]['environment'],$args[0]['docs'],
                                 $args[0]['specification']), 0 );
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid'] ), 0 );
        $db_config->add_num_row( 0, 0 );

        $db = new DB_SourceAgency;
        $this->capture_call( 'content_insert', 67, $args[0] );
        $this->assertEquals( 'No technical content suggestions have been '
                             ."posted to this project.\n", $this->get_text() );
        $this->_check_db( $db_config );
    }

    function testContent_modify() {
        global $db;
        
        $db_config = new mock_db_configure( 1 );
        $qs=array(0=>$this->queries['show_content'],
                  1=>$this->queries['content_modify']);

        $args=$this->_generate_records( array( 'proid', 'user', 'skills',
                                               'platform', 'architecture',
                                               'environment', 'docs', 
                                               'specification', 'creation'),1);
        $db_config->add_query( sprintf( $qs[1], $args[0]['proid'],
                                 $args[0]['user'],$args[0]['skills'],
                                 $args[0]['platform'],$args[0]['architecture'],
                                 $args[0]['environment'],$args[0]['docs'],
                                 $args[0]['specification'],
                                 $args[0]['creation']), 0 );
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid'] ), 0 );
        $db_config->add_num_row( 0, 0 );

        $db = new DB_SourceAgency;
        $this->capture_call( 'content_modify', 67, $args[0] );
        $this->assertEquals( 'No technical content suggestions have been '
                             ."posted to this project.\n", $this->get_text() );
        $this->_check_db( $db_config );
    }

    function testContent_modify_form() {
        global $bx, $t, $sess, $skills, $platform, $architecture;
        global $environment, $docs, $specification, $creation;

        $proid = 'this isth proid';
        $skills = 'this is the shill';
        $platform = 'this is the platfomr';
        $architecture = 'this is the architecture';
        $environment = 'this is the envoronment';
        $docs = 'this is the doxcs';
        $specification = 'tis is he speictifion';
        $creation = 'tis is the creation';

        $bx = $this->_create_default_box();
        $this->capture_call( 'content_modify_form', 
                             5178 + strlen($sess->self_url()), array($proid));
        $this->_checkFor_a_box( 'Modifying a technical content suggestion');
        $this->_checkFor_a_form( 'PHP_SELF', array('proid'=>$proid),'POST');
        $this->_testFor_html_form_hidden( 'creation', $creation );
        $this->_checkFor_columns( 2 );
        $this->_checkFor_submit_preview_buttons();
        $titles=array( 'Specification' => '<b>%s</b> (*): ',
                       'Skills' => '<b>%s</b> (64): ',
                       'Platform' => '<b>%s</b> (SELECT): ',
                       'Architecture' => '<b>%s</b> (SELECT): ',
                       'Environment' => '<b>%s</b> (SELECT): ',
                       'Docs' => '<b>%s</b> (255): ' );
        while ( list( $title, $temp ) = each( $titles ) ) {
            $this->_checkFor_column_titles( array( $title ), 'right', '30%',
                                            '', $temp );
        }
        
        $v=array( html_textarea('specification',40,7,'virtual',255,
                                $specification),
                  html_input_text('skills',40,64,$skills),
                  select_from_config('platform','platform_array',$platform),
                  select_from_config('architecture','architecture_array',
                                     $architecture),
                  select_from_config('environment','environment_array',
                                     $environment),
                  html_input_text('docs',40,255,$docs) );
        $this->_checkFor_column_values( $v );
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
        $this->reverse_next_test();
        $this->_testFor_pattern($this->_to_regexp(
                                         $t->translate('Documentation')));
    }

    function testShow_content() {
        global $db, $t, $bx, $sess;
        $db_config = new mock_db_configure( 5 );
        $qs=array( 0 => $this->queries['show_content'],
                   1 => $this->queries['content_box_footer_1'],
                   2 => $this->queries['content_box_footer_2'] );

        $dat=$this->_generate_records( array('creation','skills','platform',
                                             'architecture','environment',
                                             'docs','status','content_user',
                                             'content_id','specification'),2);
        $args=$this->_generate_records(array('proid','show_proposals',
                                             'which_proposals'), 3 );
        $dat2=$this->_generate_records(array( "COUNT(*)" ), 4 );

        // test one
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid']), 0 );
        $db_config->add_num_row( 0, 0 );

        // test two
        $db_config->add_query( sprintf( $qs[0], $args[1]['proid']), 1 );
        $db_config->add_num_row( 1, 1 );
        $dat[0]['docs'] = '';
        $db_config->add_record( $dat[0], 1 );
        $db_config->add_record( false, 1 );
        $db_config->add_query( sprintf( $qs[1], $args[1]['proid'],
                                                $dat[0]['content_id']), 2 );
        $db_config->add_query( sprintf( $qs[2], $args[1]['proid'],
                                                $dat[0]['content_id']), 2 );
        $db_config->add_record( $dat2[0], 2 );
        $db_config->add_record( $dat2[1], 2 );

        // test three
        $db_config->add_query( sprintf( $qs[0], $args[2]['proid']), 3 );
        $db_config->add_num_row( 1, 3 );
        $dat[1]['docs'] = 'thsi si the documentation';
        $db_config->add_record( $dat[1], 3 );
        $db_config->add_record( false, 3 );
        $db_config->add_query( sprintf( $qs[1], $args[2]['proid'],
                                                $dat[1]['content_id']), 4 );
        $db_config->add_query( sprintf( $qs[2], $args[2]['proid'],
                                                $dat[1]['content_id']), 4 );
        $db_config->add_record( $dat2[2], 4 );
        $db_config->add_record( $dat2[3], 4 );

        // test one: no results
        $db = new DB_SourceAgency;
        $this->capture_call( 'show_content', 67, $args[0] );
        $this->assertEquals( 'No technical content suggestions have been '
                             ."posted to this project.\n", $this->get_text());

        // test two: one row, no documentation
        $args[1]['show_proposals'] = 'yes'; // but which_proposal is not set
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        $this->capture_call( 'show_content', 2048, $args[1] );
        $this->_checkFor_show_content($args[1], $dat[0]);
        $args[1]['content_id'] = $dat[0]['content_id'];
        $this->_checkFor_content_box_footer($args[1],$dat2[0],
                                                           $dat2[1],$dat[0]);

        // test three: one row, with documentation
        $args[1]['show_proposals'] = 'yes'; // but which_proposal is not set
        $bx = $this->_create_default_box();
        $db = new DB_SourceAgency;
        $this->capture_call( 'show_content', 2149, $args[2] );
        $this->_checkFor_show_content($args[2], $dat[1]);
        $args[2]['content_id'] = $dat[1]['content_id'];
        $this->_checkFor_content_box_footer($args[2],$dat2[2],
                                                           $dat2[3],$dat[1]);

        $this->_check_db( $db_config );
    }

    function _checkFor_show_content( &$args, &$dat ) {
        global $t;
        $this->_checkFor_a_box( 'Technical Content' );
        $str = ' - '.timestr( mktimestamp( $dat['creation'] ) ).'</b><p>';
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->_testFor_lib_nick( $dat['content_user'] );

        $v=array( 'Needed Skills' => $dat['skills'],
                  'Plattform' => $dat['platform'],
                  'Architecture' => $dat['architecture'],
                  'Environment' => $dat['environment'],
                  'Status' => show_status($dat['status']),
                  'Technical Specification' => $dat['specification']);
        $template = "<b>%s:</b> %s\n";
        while ( list( $key, $val ) = each( $v ) ) {
            $str = sprintf( $template, $t->translate($key), $val);
            $this->_testFor_pattern( "[<]..?[>]".$this->_to_regexp( $str ) );
        }

        if ( $dat['docs'] == '' ) {
            $this->reverse_next_test();
        } 
        $str = sprintf( $template, $t->translate( 'Documentation'),
                               html_link($dat['docs'], array(), $dat['docs']));
        $this->_testFor_pattern( $this->_to_regexp( "<br>".$str ) );

        $this->_testFor_lib_comment_it( $args['proid'], 'Specifications',
                          $dat['content_id'],'0','Comment on Specification #'
                          .$dat['content_id'], 'Comment This Specification');
    }
    function _checkFor_show_proposals( $proid, &$row, $count_star_1, 
                                                              $count_star_2 ) {
        global $t, $sess;
        $strings=array( '><b>'.$t->translate('Developing Proposal').'</b><',
                        '<b>'.lib_nick($row['username']).' - '
                        .timestr(mktimestamp($row['creation']))."</b>\n" );
        foreach ( $strings as $str ) {
            $this->_testFor_pattern( $this->_to_regexp( $str ) );
        }
                    
        $tv=array( 'Cost' => $row['cost']." euros",
                   'License' => $row['license'],
                   'Status' => show_status($row['status']),
                   'Valid' => timestr_middle(mktimestamp($row['valid'])),
                   'Start' => timestr_middle(mktimestamp($row['start'])),
                   'Duration' => $row['duration']." weeks" );
        while ( list( $key, $val ) = each( $tv ) ) {
            $str = sprintf( "<b>%s:</b> %s\n", $t->translate($key), $val );
            $this->_testFor_pattern( "[<]..?[>]".$this->_to_regexp( $str ) );
        }

        /** check for a cooperation link or not .... **/
        $str1 = html_link('cooperation.php3',
                          array('proid' => $proid, 'devid' => $row['devid']),
                          'Cooperation') . ' ['. $count_star_1 . ']';
        $str2 = 'Cooperation [' . $count_star_1 . ']';
        if ( $row['cooperation'] != 'No' ) {
            if ( $count_star_1 ) {
                $this->_testFor_pattern( $this->_to_regexp( $str1 ) );
                $this->reverse_next_test();
                $this->_testFor_pattern( $this->_to_regexp( $str2 ) );
            } else {
                $this->reverse_next_test();
                $this->_testFor_pattern( $this->_to_regexp( $str1 ) );
                $this->_testFor_pattern( $this->_to_regexp( $str2 ) );
            }
        } else {
            $this->reverse_next_test();
            $this->_testFor_pattern( $this->_to_regexp( $str1 ) );
            $this->reverse_next_test();
            $this->_testFor_pattern( $this->_to_regexp( $str2 ) );
        }
        
        /** check for a comments link **/
        $str1 = ( html_link('comments.php3',array('proid' => $proid, 
                                                  'type' => 'proposals', 
                                                  'number' => $row['devid']),
                            'Comments')
                  . ' ['.$count_star_2."]\n");
        $str2 = 'Comments [0]';
        if ( $count_star_2 > 0 ) {
            $this->_testFor_pattern( $this->_to_regexp( $str1 ) );
            $this->reverse_next_test();
            $this->_testFor_pattern( $this->_to_regexp( $str2 ) );
        } else {
            $this->reverse_next_test();
            $this->_testFor_pattern( $this->_to_regexp( $str1 ) );
            $this->_testFor_pattern( $this->_to_regexp( $str2 ) );
        }

	$str = ( "<FONT SIZE=-1>[ <a href=\"".$sess->url("comments_edit.php3")
                 .$sess->add_query(array("proid" => $proid, 
                                         "type" => "proposal", 
                                         "number" => $row["devid"]))
                 ."\">Comment This Proposal</a> ]</FONT><p>\n");
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
    }

    function _config_db_show_proposals( &$db_config, $inst_nr, &$args,
                                        &$content_id, &$row_data, 
                                        $count_star_1, $count_star_2 ) {
        global $qs;
        $db_config->add_record( $content_id, $inst_nr );

        $inst_nr++;
        $db_config->add_query( sprintf( $qs[0], $args['proid'], 
                                                $content_id['content_id']), 
                               $inst_nr);
        $db_config->add_record( $row_data, $inst_nr );
        $db_config->add_record( false, $inst_nr );
        
        $inst_nr++;
        if ( $row_data['cooperation'] != 'No' ) {
            $db_config->add_query( sprintf( $qs[1], $row_data['devid']), 
                                   $inst_nr );
            $db_config->add_record( array( 'COUNT(*)'=>$count_star_1 ),
                                    $inst_nr );
        }
        $db_config->add_query( sprintf( $qs[2], $args['proid'], 
                                                $row_data['devid']), $inst_nr);
        $db_config->add_record( array('COUNT(*)' => $count_star_2), $inst_nr);

        return ++$inst_nr;
    }

    function testShow_proposals() {
        global $db, $qs;
        
        $fname = 'show_proposals';
        $qs=array( 0 => $this->queries[ $fname . '_1' ],
                   1 => $this->queries[ $fname . '_2' ],
                   2 => $this->queries[ $fname . '_3' ] );

        $db_config = new mock_db_configure( 20 );
        $args=$this->_generate_records( array( 'proid', 'content_id' ), 10 );
        $d1=$this->_generate_records( array( 'content_id' ), 10 );
        $d2=$this->_generate_records( array( 'creation', 'username', 'cost',
                                             'license','status', 'cooperation',
                                             'devid', 'valid', 'start',
                                             'duration'), 10 );
        $inst_nr = 0;

        // test one, no records
        $db_config->add_record( $d1[0], 0 );
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid'], 
                                                $d1[0]['content_id']), 1 );
        $db_config->add_record( false, 1 );
        $db = new DB_SourceAgency;
        $db->next_record();
        $this->capture_call( $fname, 0, $args[0] );
        $inst_nr = 2;

        // test two: one record, cooperation == No, COUNT(*) == 0
        $d2[0]['cooperation'] = 'No';
        $inst_nr = $this->_config_db_show_proposals( $db_config, $inst_nr, 
                                            $args[1],$d1[1], $d2[0], -1, 0 );
        $db = new DB_SourceAgency;
        $db->next_record();
        $this->capture_call( $fname, 1108, $args[1] );
        $this->_checkFor_show_proposals( $args[1]['proid'], $d2[0], -1, 0 );

        // test three: one record, cooperation == Yes, both COUNT(*) == 0
        $d2[1]['cooperation'] = 'Yes';
        $inst_nr = $this->_config_db_show_proposals( $db_config, $inst_nr, 
                                              $args[2],$d1[2], $d2[1], 0, 0 );
        $db = new DB_SourceAgency;
        $db->next_record();
        $this->capture_call( $fname, 1274, $args[2] );
        $this->_checkFor_show_proposals( $args[2]['proid'], $d2[1], 0, 0 );
        
        // test four: one record, cooperation == No, COUNT(*) == 1
        $d2[2]['cooperation'] = 'No';
        $inst_nr = $this->_config_db_show_proposals( $db_config, $inst_nr, 
                                     $args[3],$d1[3], $d2[2], -1, 1 );
        $db = new DB_SourceAgency;
        $db->next_record();
        $this->capture_call( $fname, 1224, $args[3] );
        $this->_checkFor_show_proposals( $args[3]['proid'], $d2[2], -1, 1 );

        // test five: one record, cooperation == Yes, both COUNT(*) == 1
        $d2[3]['cooperation'] = 'Yes';
        $inst_nr = $this->_config_db_show_proposals( $db_config, $inst_nr, 
                                               $args[4],$d1[4], $d2[3], 1, 1 );
        $db = new DB_SourceAgency;
        $db->next_record();
        $this->capture_call( $fname, 1424, $args[4] );
        $this->_checkFor_show_proposals( $args[4]['proid'], $d2[3], 1, 1 );

        // test six: one record, cooperation == Yes ...
        $d2[4]['cooperation'] = 'Yes';
        $inst_nr = $this->_config_db_show_proposals( $db_config, $inst_nr, 
                                              $args[5],$d1[5], $d2[4], 0, 1 );
        $db = new DB_SourceAgency;
        $db->next_record();
        $this->capture_call( $fname, 1356, $args[5] );
        $this->_checkFor_show_proposals( $args[5]['proid'], $d2[4], 0, 1 );

        // test seven: one record, cooperation == Yes ...
        $d2[5]['cooperation'] = 'Yes';
        $inst_nr = $this->_config_db_show_proposals( $db_config, $inst_nr, 
                                               $args[6],$d1[6], $d2[5], 1, 0 );
        $db = new DB_SourceAgency;
        $db->next_record();
        $this->capture_call( $fname, 1342, $args[6] );
        $this->_checkFor_show_proposals( $args[6]['proid'], $d2[5], 1, 0 );

        $this->_check_db( $db_config );
    }

    function testshow_selected_content() {
        global $t, $bx, $db;
        $qs=array( 0 => $this->queries['show_selected_content'] );
        $db_config = new mock_db_configure( 1 );
        $args=$this->_generate_records( array( 'proid' ), 1 );
        $dat=$this->_generate_records(array('creation','username','skills',
                                            'platform','architecture','docs',
                                            'environment','specification'),1);
        $db_config->add_query( sprintf( $qs[0], $args[0]['proid']), 0 );
        $db_config->add_record( $dat[0], 0 );

        $db = new DB_SourceAgency;
        $bx = $this->_create_default_box();
        $this->capture_call( 'show_selected_content', 944, $args[0] );
        
        $str = '</a> - '.timestr(mktimestamp($dat[0]['creation'])).'</b>';
        $this->_testFor_pattern( $this->_to_regexp( $str ) );
        $this->_testFor_lib_nick( $dat[0]['username'] );

        $template = "<b>%s:</b> %s\n";
        $v=array( 'Skills' => $dat[0]['skills'],
                  'Plattform' => $dat[0]['platform'],
                  'Architecture' => $dat[0]['architecture'],
                  'Environment' => $dat[0]['environment'],
                  'Documentation' => $dat[0]['docs'],
                  'Technical Specification' => $dat[0]['specification']);
        while ( list( $key, $val ) = each( $v ) ) {
            $str = sprintf( $template, $t->translate($key), $val );
            $this->_testFor_pattern( '[<]..?[>]'. $this->_to_regexp($str));
        }
        $this->_check_db( $db_config );
    }

}

define_test_suite( __FILE__ );
?>
