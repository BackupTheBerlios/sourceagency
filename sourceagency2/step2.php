<?php

######################################################################
# SourceAgency:
# ================================================
#
# Copyright (c) 2001-2003 by
#             Gregorio Robles (grex@scouts-es.org) and
#             Gerrit Riessen (Gerrit.Riessen@open-source-consultants.de)
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

require('include/prepend.php3');

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require('include/header.inc');
require('include/contentlib.inc');
require('include/developinglib.inc');
require('include/decisionslib.inc');

$bx = new box('100%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();
$page = 'specifications';

if (check_proid($proid)) {
    top_bar($proid,$page);

    print ( $t->translate('Technical specification suggestions. They can '
                          .'be made either by developers or by a sponsor '
                          .'(if the sponsor is owner of the project).')."\n");

    print ( '<p align=right>[<b> '
            . html_link('step2_edit.php',
                        array('proid' => $proid),
                        $t->translate('Suggest a Technical Specification'))
            ." </b>] &nbsp;<p>\n" );

    show_content($proid, $show_proposals, $which_proposals);

    if (is_accepted_sponsor($proid)) {
        create_decision_link($proid);
    }
}

end_content();
require('include/footer.inc');
@page_close();
?>
