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
require("historylib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
?>

<!-- content -->

<?php

$page = "history";

if (check_proid($proid)) {
  top_bar($proid,$page);

  htmlp_image("ic/e.png",0,48,48,"Summary");
  print "This is the chronological list of all the actions that have affected the current project.\n";

  $i=0;

  history_extract_table("description","description_creation","description_user","project_title");
  history_extract_table("consultants","creation","consultant","Consultant offered");
  history_extract_table("comments","creation_cmt","user_cmt","subject_cmt");
  history_extract_table("news","creation_news","user_news","subject_news");
  history_extract_table("tech_content","creation","content_user","Content proposed");
  history_extract_table("history","creation","history_user","action");
  history_extract_table("developing","creation","developer","Developing Proposal");
  history_extract_table("sponsoring","creation","sponsor","Sponsoring wish");
  history_extract_table("milestones","creation","milestone_user","product");
  history_extract_table("referees","creation","referee","Referee offered");


  bubblesort($history);

  show_history($history);

  lib_comment_it($proid,"General","0","0","Comment on the Project History","General Comments");
}

?>

<!-- end content -->

<?php
require("footer.inc");
@page_close();
?>