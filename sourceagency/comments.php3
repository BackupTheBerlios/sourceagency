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
require ("commentslib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);

?>

<!-- content -->

<?php

$page = "comments";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  if (!isset($type) || empty($type)) $type = "General";
  if (!isset($ref) || empty($ref)) $ref ="";

  htmlp_image("ic/c.png",0,48,48,"Summary");
  print "General comments can be posted by any registered user (developer or sponsor) in the system.\n";
  print "<br><p>\n";

  comments_show($proid,$type,$number,$cmt_id,$ref);

  lib_comment_it($proid,"General","0","0","","General Comments");

}

?>

<!-- end content -->

<?php
require("footer.inc");
@page_close();
?>