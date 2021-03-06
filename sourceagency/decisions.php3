<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#             Gregorio Robles (grex@scouts-es.org) and
#             Gerrit Riessen (Gerrit.Riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Page where sponsors make their decisions
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: decisions.php3,v 1.8 2002/05/07 10:24:29 riessen Exp $
#
######################################################################  

page_open(array('sess' => 'SourceAgency_Session'));
if (isset($auth) && !empty($auth->auth['perm'])) {
  page_close();
  page_open(array('sess' => 'SourceAgency_Session',
                  'auth' => 'SourceAgency_Auth',
                  'perm' => 'SourceAgency_Perm'));
}

require('header.inc');
require('decisionslib.inc');

$bx = new box('100%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = 'decisions';

if (check_permission($proid, $page)) {
    top_bar($proid, $page);

    print $t->translate('This is the page where sponsors make '
                        .'their decisions').".\n";

    $db->query("SELECT status FROM description WHERE proid='$proid'");
    $db->next_record();
    $project_status = $db->f('status');

    if (is_set_and_not_empty($Yes) && decisions_decision_met($proid)) {
        $project_status +=1;
        $bx->box_full('<b>'.$t->translate('The next step has been reached')
                      .'</b>',
                      $t->translate('You are now in step ').$project_status);
	$project_status -=1;
    } else {
	$voted_yet=0;
	if (!strcmp($vote, 'vote')) {
            switch($project_status) {
            case '1':
                $what = 'consultant';
                $table = 'consultants';
                break;
            case '2':
                $what = 'content_id';
                $table = 'tech_content';
                break;
            case '3':
                $what = 'devid';
                $table = 'developing';
                break;
            case '4':
                $what = 'referee';
                $table = 'referees';
                break;
            default:
	        lib_die('Error in decisions.php3: No decision can be '
                        .'put into database');
            }
            put_decision_into_database($proid,$project_status,$your_vote,
                                       $what,$table);
    	}

  	// If the sponsor has already voted, then we look for his vote
        if ( is_not_set_or_empty( $your_vote ) ) {
            $db->query("SELECT decision FROM decisions WHERE proid='$proid' "
                       ."AND decision_user='".$auth->auth["uname"]
                       ."' AND step='$project_status'");
            $db->next_record();
            $your_vote=$db->f('decision');
  	}

        switch($project_status) {
        case '1':
            $quorum = show_decision_consultants($proid); 
            break;
        case '2':
            $quorum = show_decision_contents($proid);
            break;
        case '3':
            $quorum = show_decision_proposals($proid);
            break;
        case '4':
            $quorum = show_decision_referees($proid);
            break;
        case '5':  
            echo '<p>'; 
            global $g_step_text;
            // NOI18N: 'Decision on step 5 (follow-up)'
            htmlp_link('step5_sponsor.php3',array('proid' => $proid),
                       $t->translate( 'Decision on step 5')
                       .' ('.$g_step_text[5].')'); 
            break;
        case '6':  
            echo '<p>'; 
            global $g_step_text;
            // NOI18N: 'Decision on step 6 (rating)'
            htmlp_link('step6_edit.php3',array('proid' => $proid),
                       $t->translate('Decision on step 6')
                       .' ('.$g_step_text[6].')'); 
            break;
        default:
           lib_die('Error in decisions.php3: given project status '
                   .'is not possible');
	}
    }

    if ($quorum || is_set_and_not_empty($Yes) ) {
        if ( $No || is_not_set_or_empty( $Yes ) ) {
            are_you_sure_message($proid);
        } else {
            switch($project_status) {
            case '1':
                $what = 'consultant';
                $table = 'consultants';
                break;
            case '2':
                $what = 'content_id';
                $table = 'tech_content';
                break;
            case '3':
                $what = 'devid';
                $table = 'developing';
                break;
            case '4':
                $what = 'referee';
                $table = 'referees';
                break;
            default:
                lib_die('Error in decisions.php3: No next step possible');
            }
            if (decisions_decision_met($proid)) {
                put_into_next_step($proid,$project_status,$what,$table);
            }
        }
    }

    your_quota($proid);
    print "<br>\n";
    you_have_already_voted($proid,$project_status);

    if ($project_status!=4) {
        print ('<p align=right>'.$t->translate('Not voted yet').': <b>'
               .((round((100 - $voted_yet)*100))/100)."%</b>\n");
    } else {
        print ( '<p align=right>'.$t->translate('Not voted yet').': <b>'
                .((round((100 - $voted_yet)*100))/50)."%</b>\n");
    }

    // TODO: add an explanation
    //print "<br><font size=-1>...Explanation...</font>\n";

    $db->query("SELECT quorum FROM configure WHERE proid='$proid'");
    $db->next_record();

    if ($project_status!=4) {
        print '<p align=right>'.$t->translate('Decision making')
          .': <b>'.$db->f('quorum')."%</b>\n";
    } else {
        $quorum = $db->f('quorum')/2 +50;
        print '<p align=right>'.$t->translate('Decision making')
          .': <b>'.$quorum."%</b>\n";
    }
    print "<br><font size=-1>("
          .$t->translate("quota needed for reaching the next step")
          .")</font>\n";
}

end_content();
require('footer.inc');
page_close();
?>