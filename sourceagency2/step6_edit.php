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
# Description
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
require("include/ratingslib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "step6_edit";

/*
// Debugging information
print "<br><b>Dev_or_spo: </b>".$dev_or_spo;
// ID number contains devid for developers (table developing) and spoid for sponsors (table sponsoring)
print "<br><b>ID_number: </b>".$id_number;
*/

if (check_permission($proid,$page)) {
    top_bar($proid,$page);
    
    if (isset($submit) && !empty($submit)) {
        ratings_insert($proid,$dev_or_spo,$id_number,$auth->auth["uname"]);
        ratings_look_for_next_one($proid,&$id_number);
    }
    
    if(!isset($dev_or_spo) || empty($dev_or_spo) 
       || is_not_set_or_empty( $id_number )) {
        $id_number = ratings_look_for_first_one($proid);
    }

    print $t->translate("Project participants have the opportunity to rate "
                        ."the other project members").".\n";

/*
// Debugging information
print "<p><b>Dev_or_spo2: </b>".$dev_or_spo;
print "<br><b>ID_number2: </b>".$id_number;
*/
    
    if (!isset($finished) && empty($finished)) {
        ratings_form($proid,$dev_or_spo,$id_number);
    } else {
        ratings_in_history($proid,$auth->auth["uname"]);
    }
}

end_content();
require("include/footer.inc");
@page_close();
?>
