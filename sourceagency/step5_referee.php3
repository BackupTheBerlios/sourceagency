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
$page = "step5_referee";

if (check_permission($proid,$page)) {
    top_bar($proid,$page);

    print $t->translate("This is the page where referees submit the "
                        ."decision on a given milestone").".\n";
  
    $milestone_number = followup_current_milestone($proid);
    $count = followup_current_count($proid,$milestone_number);
    $location = followup_location($proid,$milestone_number,$count);

    if (!isset($submit) || empty($submit)) {
	if (isset($preview) && !empty($preview)) {
            followup_referee_preview($proid);
        }
	followup_referee_form($proid);

        $bx->box_full($t->translate("Information Box"),
                      $t->translate("These are decisions the referee "
                                    ."can take:")
                      ."<p><ul><li>"
                      .$t->translate("<b>Accept</b>: The referee thinks "
                                     ."the posted milestone achieves the "
                                     ."requirements that were given in "
                                     ."the milestone plan.")
                      ."</li><li>"
                      .$t->translate("<b>Minor</b>: The posted milestone "
                                     ."needs minor changes in order to "
                                     ."achieve the requirements that the "
                                     ."sponsor wants. The developer will "
                                     ."have a delay in order to post this "
                                     ."milestone another time.")
                      ."</li><li>"
                      .$t->translate("<b>Severe</b>: The milestone that "
                                     ."has been posted needs heavy work to "
                                     ."achieve the requirements.")
                      ."</li></ul><p>");
    } else {
	switch ($decision) {
            case "accept":
                followup_insert($proid,++$milestone_number,"0","","1");
                print "<p>".$t->translate("You have <b>accepted</b> the milestone").".\n";
                print "<p>".$t->translate("The next milestone is milestone number")." <b>".$milestone_number."</b>\n";
                break;
            case "minor":
                followup_insert($proid,$milestone_number,"0","",$count+1);
                print "<p>".$t->translate("You have decided that the milestone needs <b>minor</b> improvements").".\n";
                print "<p>".$t->translate("Developers have now a short time delay to fix the problems").".\n";
                break;
            case "severe":
                followup_insert($proid,$milestone_number,"3",$location,$count);
                print "<p>".$t->translate("You have decided that the milestone has <b>severe</b> problems to achieve the milestone plan").".\n";
                print "<p>".$t->translate("Sponsors can now decide what will happen to the project").".\n";
                break;
	}
    }
    
}

end_content();
require("footer.inc");
page_close();
?>