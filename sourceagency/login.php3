<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Lutz Henckel (lutz.henckel@fokus.gmd.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the login page: here authenticated sessions start
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
######################################################################  

page_open(array('sess' => 'SourceAgency_Session',
                'auth' => 'SourceAgency_Auth',
                'perm' => 'SourceAgency_Perm'));

require('header.inc');

$bx = new box('80%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$be = new box('80%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();

if ($perm->have_perm('user_pending')) {
    $be->box_full($t->translate('Error'), $t->translate('Access denied'));
    $auth->logout();
} else {
    $msg = $t->translate('You are logged in as').' <b>'.$auth->auth['uname']
           .'</b> '.$t->translate('with').' '
           .'<b>'.$auth->auth['perm'].'</b> '.$t->translate('permission').'.'
           .'<br>'.$t->translate('Your authentication is valid until')
           .' <b>'.timestr($auth->auth['exp']).'</b>';
    $bx->box_full($t->translate('Welcome to ').$sys_name, $msg);
}

end_content();
require('footer.inc');
@page_close();
?>
