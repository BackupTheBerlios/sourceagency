<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Page to see project configuration
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: configure.php,v 1.1 2003/11/21 12:55:58 helix Exp $
#
######################################################################  

require("include/prepend.php3");

page_open(array('sess' => 'SourceAgency_Session'));
if (isset($auth) && !empty($auth->auth['perm'])) {
  page_close();
  page_open(array('sess' => 'SourceAgency_Session',
                  'auth' => 'SourceAgency_Auth',
                  'perm' => 'SourceAgency_Perm'));
}

require('include/header.inc');
require('include/configurelib.inc');

$bx = new box('100%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = 'configure';

if (check_permission($proid, $page)) {
    top_bar($proid, $page);

    print ( $t->translate('Project configuration').".\n"
            . '<p align=right>[ <b>'
            . html_link('configure_edit.php',array('proid' => $proid),
                        $t->translate('Configure this project'))
            ."</b> ] &nbsp;<p>\n" );

    configure_show($proid);

    echo ( '<p align=right>[ '
           .$t->translate('Have a look at the').'&nbsp;'
           .html_link('monitor.php',array('proid' => $proid),
                      $t->translate('Users that monitor this Project'))
           ."&nbsp; ]\n");
}

end_content();
require('include/footer.inc');
@page_close();
?>
