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
# This is the index file which shows the recent apps
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
require("include/consultantslib.inc");

$bx = new box("80%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "step1_edit";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print $t->translate('Registered developers can offer themselves '
                      .'as consultants');
  print ".\n<br><p>\n";
  
  if (consultants_wanted($proid)) {
      $bx->box_full($t->translate("Info box"), 
                     $t->translate("If you are a registered developer, "
                                   ."you can propose yourself as a "
                                   ."consultant for this project...."));
      
      if ( is_not_set_or_empty( $submit ) ) {
          consultants_form($proid);
      } else {
          consultants_insert($proid,$auth->auth["uname"]);
      }
  }
}

end_content();
require("include/footer.inc");
@page_close();
?>
