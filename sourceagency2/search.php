<?php

######################################################################
# SourceAgency:
# ================================================
#
# Copyright (c) 2001-2003 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Lutz Henckel (lutz.henckel@fokus.fraunhofer.de)
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

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

// When there's a search for a blank line, we look for "xxxxxxxx"
if (!isset($search) || $search=="") {
    $search = "xxxxxxxx";
}

// $iter is a variable for printing the Top Statistics in steps of 10 apps
if (!isset($iter)) $iter=0;
$iter*=10;

// We need to know the total number of apps
$db->query("SELECT * FROM description WHERE project_title LIKE '%$search%'");

if ($db->num_rows() == 0) {
    $bx->box_full($t->translate("Search"),$t->translate("No projects found"));
} else {
    while($db->next_record()) {
	$numiter = (($db->f("COUNT(*)")-1)/10);

  	$query  = ( "SELECT * FROM description,auth_user WHERE "
                    ."description.proid='".$db->f("proid")."' AND "
                    ."description.description_user=auth_user.username "
                    ."AND description.status > '0' GROUP BY "
                    ."description.proid");
	lib_show_description($query);

	if ($numiter > 1) {
            $url = "search.php";
            $urlquery = array("search" => ($search), "by" => $by);
            show_more ($iter,$numiter,$url,$urlquery);
	}
    }
}

end_content();
require("include/footer.inc");
page_close();
?>
