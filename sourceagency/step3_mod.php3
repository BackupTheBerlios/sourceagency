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
require("milestoneslib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "step3_mod";

if (check_permission($proid,$page)) {
    top_bar($proid,$page);

    print "The main developer can modify the milestone planning.\n";
    print "<br><b>Warning</b>: Any modified milestone will change its status to \"Proposed\".\n";
    print "<br><p>\n";

    if ( is_not_set_or_empty( $preview ) && is_not_set_or_empty( $submit ) ) {
	$db->query("SELECT * FROM milestones WHERE number='$number' AND "
                   ."proid='$proid' AND devid='$devid'");
	$db->next_record();
	$goals = $db->f("goals");
	$release = $db->f("release");
	$array = timestamp_to_date($release);
	$release_day =  $array["day"];
	$release_month =  $array["month"];
	$release_year =  $array["year"];
	$product = $db->f("product");
	$payment = $db->f("payment");
	$creation = $db->f("creation");
    }

    if (!isset($submit) || empty($submit)) {
	if (isset($preview) && !empty($preview)) {
            milestones_preview($proid,$devid);
        }
	milestones_modify_form($proid,$devid);
    } else {
	milestones_modify($proid,$number,$goals,$release_day,
                          $release_month,$release_year,$product,$payment);
    }
}

end_content();
require("footer.inc");
@page_close();
?>