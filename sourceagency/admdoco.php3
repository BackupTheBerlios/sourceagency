<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Administrate Page documentation
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: admdoco.php3,v 1.3 2002/04/19 11:03:58 riessen Exp $
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

$bx = new box('97%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

$be = new box('80%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();

if (($config_perm_admdoco != 'all') 
    && (!isset($perm) || !$perm->have_perm($config_perm_admdoco))) {
    $be->box_full($t->translate('Error'), $t->translate('Access denied'));
} else {
    $db->query("SELECT * FROM doco WHERE language='$la'");
    $bx->box_begin();
    $bx->box_title($t->translate('Page Documentation Administration'));
    $bx->box_body_begin();
    $bx->box_columns_begin(2);
    $bx->box_column('left', '88%', '', $t->translate('Enter a new '
                                                     .'documentation entry '
                                                     .'for a page'));
    $bx->box_column('right', '12%', '', html_form_action('insdoco.php3')
    	 	                       .html_form_hidden('create', 1)
                                       .html_form_submit('Insert', 'Insert')
                                       .html_form_end());
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();

    while($db->next_record()) {
        $bx->box_begin();
        $bx->box_title($t->translate('Page').': '.$db->f('page') . ', '
                        .$t->translate('Header').': '.$db->f('header'));
        $bx->box_body_begin();
        $bx->box_columns_begin(2);
        $bx->box_column('left', '76%', '', $db->f('doco'));
        $bx->box_column('right', '12%', '', html_form_action('insdoco.php3')
    	 	                           .html_form_hidden('modify', 1)
    	 	                           .html_form_hidden('delete', 0)
    	 	                           .html_form_hidden('docoid', 
                                                             $db->f('docoid'))
                                           .html_form_submit('Change', 
                                                             'Change')
                                           .html_form_end());
        $bx->box_column('right', '12%', '', html_form_action('insdoco.php3')
    	 	                           .html_form_hidden('modify', 0)
    	 	                           .html_form_hidden('delete', 1)
    	 	                           .html_form_hidden('docoid', 
                                                             $db->f('docoid'))
                                           .html_form_submit('Delete', 
                                                             'Delete')
                                           .html_form_end());
        $bx->box_columns_end();
        $bx->box_body_end();
        $bx->box_end();
    }
}

end_content();
require('footer.inc');
page_close();
?>
