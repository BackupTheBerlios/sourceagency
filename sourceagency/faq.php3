<?php

######################################################################
# SourceAgency: Software Announcement & Retrieval System
# ================================================
#
# Copyright (c) 2001 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the file with the Frequently Asked Questions
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

$bx = new box("80%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
?>

<!-- content -->
<?php
$db->query("SELECT * FROM faq");
$i=1;
while($db->next_record()) {
  $msg .= "<li><a href=#".$i++.">".$db->f("question")."</a>";
}
$bx->box_full($t->translate("Frequently Asked Questions"), $msg);
$db->seek(0);
while($db->next_record()) {
  echo "<a name=".$i++.">\n";
  $bx->box_full($t->translate("Question").": ".$db->f("question"), "<b>".$t->translate("Answer").":</b> ".$db->f("answer"));
}
?>
<!-- end content -->

<?php
require("footer.inc");
page_close();
?>
