<?php
######################################################################
# SourceAgency
# ================================================
#
# Copyright (c) 2001-2003 by
#                Gregorio Robles (grex@scouts-es.org),
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This file contains the verification procedure when registering
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
######################################################################

require("include/prepend.php3");

page_open(array("sess" => "SourceAgency_Session"));

require("include/header.inc");

$bx = new box("",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$be = new box("",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();
$db->query("UPDATE auth_user SET perms='devel' WHERE user_id='$confirm_hash'");

if ($db->affected_rows() == 0) {
  $be->box_full($t->translate("Error"), 
                $t->translate("Verification of Registration failed")
                    .":<br>$query");
} else {
  $msg = $t->translate("Your account is now activated. Please login").".";
  $bx->box_full($t->translate("Verification of Registration"), $msg);
}

end_content();
require("include/footer.inc");
page_close();
?>
