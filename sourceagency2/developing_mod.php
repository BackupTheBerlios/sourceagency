<?php

######################################################################
# SourceAgency :Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourcewell.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Editing sponsoring involvements is possible here
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: developing_mod.php,v 1.1 2003/11/21 12:55:58 helix Exp $
#
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
require("include/developinglib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = "developing_edit";

if (check_proid($proid)) {
  top_bar($proid,$page);

  $content_id='1';
  $creation = '20010802192415';

  print $t->translate('Developers can modify their proposals').".\n<br><p>\n";

  if ( is_not_set_or_empty( $preview ) && is_not_set_or_empty($submit) ) {
    $db->query("SELECT * FROM developing WHERE content_id='$content_id' "
               ."AND proid='$proid' AND creation='$creation'");
    $db->next_record();
    $cost = $db->f("cost");
    $license = $db->f("license");
    $cooperation = $db->f("cooperation");
    $start = $db->f("start");
    $duration = $db->f("duration");
    $valid = $db->f("valid");
  }

  if ( is_not_set_or_empty( $submit ) ) {
    developing_modify_form($proid,$content_id);
  } else {
    developing_modify($proid,$content_id,"devel",$cost, $license, 
                  $cooperation, $valid, $start, $duration,$creation);
  }
}
end_content();
require("include/footer.inc");
@page_close();
?>
