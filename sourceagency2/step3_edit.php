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

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "step3_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print $t->translate("The main developer can propose the milestone planning");
  print ".\n<br><p>\n";

  if ( is_not_set_or_empty( $devid ) ) {
      $db->query("SELECT devid FROM developing WHERE proid='$proid' "
                 ."AND developer='".$auth->auth["uname"]."'");
      $db->next_record();
      $devid = $db->f("devid");
  }

  if ( is_not_set_or_empty( $submit ) ) {
      if ( is_set_and_not_empty( $preview )) {
          milestones_preview($proid,$devid);
      }
      form_milestones($proid,$devid);
  } else {
      milestones_insert($proid,$devid,$number,$goals,$release_day,
                        $release_month,$release_year,$product,$payment);
      show_milestones($proid,$devid,$auth->auth["uname"]);
  }
}

end_content();
require("include/footer.inc");
@page_close();
?>
