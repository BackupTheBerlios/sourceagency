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
require("configurelib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
?>

<!-- content -->

<?php

$page = "configure";

if (check_permission($proid,$page)) {
  top_bar($proid,$page);

  print "Project configuration.\n";

  print "<p align=right>[ <b>".html_link("configure_edit.php3",array("proid" => $proid),"Configure this project")."</b> ] &nbsp;<p>\n";

  configure_show($proid);

  echo "<p align=right>[ Have a look at the&nbsp;".html_link("monitor.php3",array("proid" => $proid),"users that monitor this project")."&nbsp; ]\n";
}

?>

<!-- end content -->

<?php
require("footer.inc");
@page_close();
?>