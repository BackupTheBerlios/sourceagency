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
require("include/contentlib.inc");

$bx = new box('100%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = "modify contents";

if (check_proid($proid)) {
    top_bar($proid,$page);

    print $t->translate('Technical contents')."\n<br><p>\n";
    
    if ((!isset($preview) || empty($preview)) 
        && (!isset($submit) || empty($submit))) {
        $db->query("SELECT * FROM tech_content WHERE content_id='4'");
        $db->next_record();
        $content_id = $db->f("content_id");
        $skills = $db->f("skills");
        $platform = $db->f("platform");
        $architecture = $db->f("architecture");
        $environment = $db->f("environment");
        $docs = $db->f("docs");
        $specification = $db->f("specification");
        $creation = $db->f("creation");
    }
    
    if (!isset($submit) || empty($submit)) {
        content_modify_form($proid);
    } else {
        content_modify($proid,"devel",$license,$skills,$platform,
        $architecture,$environment,$docs,$specification,
        $cost,$creation);
    }
}

end_content();
require("include/footer.inc");
@page_close();
?>
