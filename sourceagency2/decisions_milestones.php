<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# By means of this page, sponsors decide on the milestones
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: decisions_milestones.php,v 1.1 2003/11/21 12:55:58 helix Exp $
#
######################################################################  

require("include/prepend.php3");

page_open(array('sess' => 'SourceAgency_Session'));
if (isset($auth) && !empty($auth->auth['perm'])) {
  page_close();
  page_open(array('sess' => 'SourceAgency_Session',
                  'auth' => 'SourceAgency_Auth',
                  'perm' => 'SourceAgency_Perm'));
}

require('include/header.inc');
require('include/decisionslib.inc');

$bx = new box('100%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = 'decisions';

if (check_permission($proid, $page)) {
    top_bar($proid, $page);

    print( $t->translate('This is the page where sponsors make '
                         .'their decisions on the proposed milestones').".\n");

    print ('<p align=right>[ '
           .html_link('decisions.php',
                      array('proid' => $proid),
                      '<b>'.$t->translate('Proposal decision').'</b>')
           .' ]');

    if (is_set_and_not_empty($vote)) {
        $db->query("SELECT * FROM milestones WHERE proid='$proid' "
                   ."AND devid='$devid'");
        $count = $db->num_rows() +1;

        for ($i=1; $i<$count; $i++) {
            $milestone_number = 'milestone_'.$i;
            if ($$milestone_number == 'Yes') {
                decision_milestone_insert($proid, $devid, 
                                          $auth->auth['uname'], $i, 'Yes');
            } else {
                decision_milestone_insert($proid, $devid, 
                                          $auth->auth['uname'], $i, 'No');
            }
        }
    } else {
        $db->query("SELECT number,decision FROM decisions_milestones "
                   ."WHERE proid='$proid' AND devid='$devid' AND "
                   ."decision_user='".$auth->auth["uname"]."'");
        while($db->next_record()) {
            $milestone_number = 'milestone_'.$db->f('number');
            $$milestone_number = $db->f('decision');
        }
    }

    show_decision_milestones($proid,$devid);

    your_quota($proid);
    print "<br>\n";
    you_have_already_voted($proid,$project_status);

    print ('<p align=right>'.$t->translate('Not voted yet').': <b>'
           .((round((100 - $voted_yet)*100))/100)."%</b>\n");
    // TODO: add an explanation
    //print "<br><font size=-1>...Explanation...</font>\n";

    $db->query("SELECT quorum FROM configure WHERE proid='$proid'");
    $db->next_record();
    print '<p align=right>'.$t->translate('Decision making')
        .': <b>'.$db->f('quorum')."%</b>\n".'<br><font size=-1>('
        .$t->translate('quota needed for reaching the next step')
        .")</font>\n";
}

end_content();
require('include/footer.inc');
@page_close();
?>
