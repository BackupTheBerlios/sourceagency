<?php

######################################################################
# SourceAgency: 
# ================================================
#
# Copyright (c) 2001-2003 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
#
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
######################################################################  

require("include/prepend.php3");

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require("include/header.inc");
require("include/milestoneslib.inc");
require("include/decisionslib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "milestones";

if (check_permission($proid,$page)) {
    top_bar($proid,$page);
    
    print $t->translate('Milestone suggestions and agreement').".\n";
    
    print ("<p align=right>[ <b>"
           .html_link("step3_edit.php",array("proid" => $proid),
                      "<b>".$t->translate('Propose Milestones')
                      ."</b>")."</b> ]");
    
    $db->query("SELECT DISTINCT(devid) FROM milestones WHERE proid='$proid'");
    while($db->next_record()) {
        if ( is_set_and_not_empty( $auth ) ) {
            $who = $auth->auth["uname"];
        } else {
            $who = "";
        }
	show_milestones($proid,$db->f("devid"),$who);
    }

    print "<p><b>".$t->translate('About what you can see and what not')."</b>\n";
    print "<ul><li>".$t->translate('Non-involved (registered and unregistered) users can see only the accepted milestones').".\n";
    print "<li>".$t->translate('Project owners can see all milestones (proposed and accepted ones)').".\n";
    print "<li>".$t->translate('Involved developers can see their own milestones and the accepted milestones from other developers').".\n";
    print "</ul>\n";
    
    if (is_accepted_sponsor($proid)) {
        create_decision_link( $proid );
    }
}

end_content();
require("include/footer.inc");
page_close();
?>
