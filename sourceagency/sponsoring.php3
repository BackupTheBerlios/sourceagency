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
# This is the index file which shows the recent apps
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
require ("sponsoringlib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = "sponsoring";

if (check_proid($proid)) {
    top_bar($proid,$page);
    
    htmlp_image("ic/d.png",0,48,48,"Summary");
    print "Here sponsors can involve themselves in projects... \n";

    print "<p align=right>[ <b><a href=\""
        .$sess->url("sponsoring_edit.php3")
        .$sess->add_query(array("proid" => $proid))
        ."\">Sponsor this project</a></b> ] &nbsp;<p>\n";
    
    show_sponsorings($proid);
    
    lib_comment_it($proid,"General","0","0","","General Comments");
}

end_content();
require("footer.inc");
@page_close();
?>