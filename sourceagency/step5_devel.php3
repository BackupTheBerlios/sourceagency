<?php

######################################################################
# SourceAgency:
# ================================================
#
# Copyright (c) 2001 by
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

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
    page_close();
    page_open(array("sess" => "SourceAgency_Session",
                    "auth" => "SourceAgency_Auth",
                    "perm" => "SourceAgency_Perm"));
}

require("header.inc");
require("followuplib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "step5_devel";

if (check_permission($proid,$page)) {
    top_bar($proid,$page);

    print $t->translate("This is the page where developers can submit "
                        ."their milestones and follow the decisions "
                        ."made by referees and sponsors") . ".\n";

    $milestone_number = followup_current_milestone($proid);
    $count = followup_current_count($proid,$milestone_number);
    
    if (!isset($submit) || empty($submit)) {
        followup_milestone_schedule($proid,$milestone_number,$count);
	if (isset($preview) && !empty($preview)) {
            followup_devel_preview($proid);
        }
	followup_devel_form($proid);
    } else {
	followup_insert($proid,$milestone_number,"1",$location,$count);
	print "<p>".$t->translate("The milestone location has been "
                                  ."posted correctly").".\n";

	print "<p>".$t->translate("You can keep on working on the next "
                                  ."milestone "
                                  ."while sponsors have a look at the "
                                  ."one you have just "
                                  ."submitted")
            .".\n";
    }

}

end_content();
require("footer.inc");
page_close();
?>