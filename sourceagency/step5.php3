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
# Description
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

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);

?>

<!-- content -->

<?php

$page = "project followment";

if (check_proid($proid)) {
  top_bar($proid,$page);

  print "You can see in a view what is this project about.<p>\n";

  if(is_main_developer($proid)) htmlp_link("step5_devel.php3",array("proid"=>$proid),"Main developer");
  elseif(is_referee($proid)) htmlp_link("step5_referee.php3",array("proid"=>$proid),"Referee");
  elseif(is_accepted_sponsor($proid)) htmlp_link("step5_sponsor.php3",array("proid"=>$proid),"Project sponsor");

  followup($proid);

}

?>

<!-- end content -->

<?php
require("footer.inc");
@page_close();
?>