<?php

######################################################################
# SourceAgency: Software Announcement & Retrieval System
# ================================================
#
# Copyright (c) 2001 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourcewell.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the index file which shows the recent apps
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
require("refereeslib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
?>

<!-- content -->

<?php

$page = "referees";

if (check_proid($proid)) {
  top_bar($proid,$page);

  print "Referee registration and selection. Any developer who has the skills to be a referee can propose himself as one.\n";

  print "<p align=right>[ <b>".html_link("step4_edit.php3",array("proid" => $proid),"Propose yourself as referee")."</b> ]\n";

  show_referees($proid);

  if (is_accepted_sponsor($proid) || is_main_developer($proid)) {
      create_decision_link( $proid );
  }

}

?>

<!-- end content -->

<?php
require("footer.inc");
@page_close();
?>