<?php

######################################################################
# SourceAgency
# ================================================
#
# Copyright (c) 2001-2003 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This file is used to insert a project
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
######################################################################

require("include/prepend.php3");

page_open(array("sess" => "SourceAgency_Session",
                "auth" => "SourceAgency_Auth",
                "perm" => "SourceAgency_Perm"));

require("include/header.inc");
require("include/insertlib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

if ($perm->have_perm("devel_pending") || $perm->have_perm("sponsor_pending")) {
  $be->box_full($t->translate("Error"), $t->translate("Access denied"));
} else {

  $project_title = trim($project_title);
  $description = trim($description);

  if (everything_filled($project_title,$description) 
      && no_other_project_with_same_title($project_title)) {

	insert_into_database($project_title,$description,$type,$volume);
  }
}

end_content();
require("include/footer.inc");
page_close();
?>
