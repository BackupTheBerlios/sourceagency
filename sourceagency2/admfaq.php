<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Gregorio Robles (grex@scouts-es.org),
#                Lutz Henckel (lutz.henckel@fokus.fraunhofer.de) and
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Administrate Frequently Asked Questions
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: admfaq.php,v 1.1 2003/11/21 12:55:58 helix Exp $
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

$bx = new box('97%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

$be = new box('80%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();

if (($config_perm_admfaq != 'all') 
        && (!isset($perm) || !$perm->have_perm($config_perm_admfaq))) {
    $be->box_full($t->translate('Error'), $t->translate('Access denied'));
} else {
    $db->query("SELECT * FROM faq WHERE language='$la'");
    $bx->box_begin();
    $bx->box_title($t->translate('Frequently Asked Questions Administration'));
    $bx->box_body_begin();

    $bx->box_columns_begin(2);
    $bx->box_column('left', '88%', '', 
                    $t->translate('Enter a New Frequently Asked Question'));
    $bx->box_column('right', '12%', '', 
                    html_form_action('insfaq.php')
                    .html_form_hidden('create', 1)
                    .html_form_submit($t->translate('Insert'), 'Insert')
                    .html_form_end());
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();

    while($db->next_record()) {
        $bx->box_begin();
        $bx->box_title($t->translate('Question').': '.$db->f('question'));
        $bx->box_body_begin();
        $bx->box_columns_begin(2);
        $bx->box_column('left', '76%', '', $db->f('answer'));
        $bx->box_column('right', '12%', '', 
                        html_form_action('insfaq.php')
                        .html_form_hidden('modify', 1)
                        .html_form_hidden('delete', 0)
                        .html_form_hidden('faqid', $db->f('faqid'))
                        .html_form_submit($t->translate('Change'), 'Change')
                        .html_form_end());
        $bx->box_column('right', '12%', '', 
                        html_form_action('insfaq.php')
                        .html_form_hidden('modify', 0)
                        .html_form_hidden('delete', 1)
                        .html_form_hidden('faqid', $db->f('faqid'))
                        .html_form_submit($t->translate('Delete'), 'Delete')
                        .html_form_end());
        $bx->box_columns_end();
        $bx->box_body_end();
        $bx->box_end();
    }
}

end_content();
require('include/footer.inc');
@page_close();
?>
