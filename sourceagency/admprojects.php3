<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Page for administrating (pending) projects
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: admprojects.php3,v 1.3 2002/04/10 13:02:48 grex Exp $
#
######################################################################

page_open(array('sess' => 'SourceAgency_Session'));
if (isset($auth) && !empty($auth->auth['perm'])) {
  page_close();
  page_open(array('sess' => 'SourceAgency_Session',
                  'auth' => 'SourceAgency_Auth',
                  'perm' => 'SourceAgency_Perm'));
}

require('header.inc');
require('admprojectslib.inc');

$bx = new box('98%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

$be = new box('80%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();

if (($config_perm_admprojects != 'all') 
    && (!isset($perm) || !$perm->have_perm($config_perm_admprojects))) {
  $be->box_full($t->translate('Error'), $t->translate('Access denied'));
} else {

  if (isset($proid) && !empty($proid)) {
      if (isset($review) && !empty($review)) {
          admprojects_insert($proid, '1');
      } elseif (isset($accept) && !empty($accept)) {
          admprojects_insert($proid, '2');
      } elseif (isset($delete) && !empty($delete)) {
          admprojects_insert($proid, '-1');
      }
  }
  
  show_admprojects();
  
}
end_content();
require('footer.inc');
page_close();
?>
