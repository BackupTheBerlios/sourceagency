<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Sponsor decision page for step5 (project followup)
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: step5_sponsor.php3,v 1.9 2002/05/07 11:48:54 riessen Exp $
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
require('followuplib.inc');
require('decisionslib.inc');

$bx = new box('100%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = 'step5_sponsor';

if (check_permission($proid, $page)) {
    top_bar($proid, $page);

    if (step5_iteration($proid) != 3) {
	print $t->translate("This is the page where sponsors accept or reject milestones").".\n";

	$db->query("SELECT quorum FROM configure WHERE proid='$proid'");
	$db->next_record();
	$decision_value = $db->f('quorum');

	$milestone_number = followup_current_milestone($proid);
	$count = followup_current_count($proid,$milestone_number);
	$location = followup_location($proid,$milestone_number,$count);

	if(!isset($Yes) || empty($Yes)) {
	    $voted_yet=0;
	    if (!strcmp($vote,'vote')) {
		put_decision_step5_into_database($proid,$decision,$milestone_number,$count);
   	    }
   	    // If the sponsor has already voted, then we look for his vote
  	    if(!isset($decision) || empty($decision)) {
		$db->query("SELECT decision FROM decisions_step5 WHERE proid='$proid' AND decision_user='".$auth->auth["uname"]."' AND number='$milestone_number' AND count='$count'");
		$db->next_record();
		$decision=$db->f('decision');
	    }
   	    $quorum = show_decision_step5($proid,$milestone_number,$count);
        }
	if ($quorum || (isset($Yes) && !empty($Yes))) {
	    if ($No || !isset($Yes) || empty($Yes)) {
                are_you_sure_message_step5($proid);
            } else {
                decisions_step5_sponsors($proid,$milestone_number,$count);
                $bx->box_full('<b>'.$t->translate('A decision has been made').'</b>',$t->translate('And this is the decision').': '.decisions_decision_met_on_step5 ($proid,$milestone_number,$count));
            }
	}
        $bx->box_full($t->translate('Information Box'),
		      $t->translate('Sponsors can decide themselves for three different choices:').'<p><ul>'.
		      '<li>'.$t->translate('<b>Accept</b>: This means that they are satisfied with the milestone the developer has posted.').
		      '</li><li>'.$t->translate('<b>Minor</b>: The posted milestone needs minor changes in order to achieve the requirements that the sponsor wants. The developer will have a delay in order to post this milestone another time.').
		      '</li><li>'.$t->translate('<b>Severe</b>: The sponsors is very unhappy with the results of the current milestone and rejects it. The project referee will be switched.').
		      '</li></ul><p>');
     } else {
         // TODO: add explanation
         //print '<b>TODO: explanation</b> If you see this line... then developers are in trouble!\n';
//           print '<p>possible actions:\n';
//           print "<ul>\n";
//           print "<li>Delay all milestones x days</li>\n";
//           print "<li>stop proyect</li>\n";
//           print "<li>....</li>\n";
//           print "</ul>\n";
//           print "And everything has to be done in a democratic way!!!\n";
    }

/*
// TODO: show quota
  your_quota($proid);
  print "<br>\n";
  you_have_already_voted($proid, $project_status);

  print "<p align=right>Not voted yet: <b>".((round((100 - $voted_yet)*100))/100)."%</b>\n";
  print "<br><font size=-1>Explanation</font>\n";
  print "<p align=right>Decision making: <b>$decision_value</b>%\n";
  print "<br><font size=-1>(quota needed for a decision)</font>\n";
*/
}

end_content();
require('footer.inc');
page_close();
?>