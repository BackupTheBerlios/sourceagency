<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Create Frequently Asked Questions
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: insfaq.php3,v 1.7 2002/05/07 11:26:58 riessen Exp $
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
require('faqlib.inc');

$bx = new box('80%',$th_box_frame_color,$th_box_frame_width,
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
    if (isset($delete)) {
        if ($delete == 1) {
            $query = "SELECT * FROM faq WHERE faqid='$faqid' AND "
               ."language='$la'";
            $db->query($query);
            $db->next_record();
            faqshow($db);
            $bx->box_begin();
            $bx->box_title($t->translate('Do you really want to '
                                         .'delete this FAQ? '
                                         .'There is no way for undeletion.'));
            $bx->box_body_begin();
            $bx->box_columns_begin(2);
            $bx->box_column('left', '76%', '', '<b>'.$db->f('question')
                            .'</b><p>'.$db->f('answer'));
            $bx->box_column('right', '12%', '', 
                            html_form_action('PHP_SELF')
                            .html_form_hidden('modify', 0)
                            .html_form_hidden('delete', 2)
                            .html_form_hidden('faqid', $db->f('faqid'))
                            .html_form_submit($t->translate('Yes, Delete'),
                                              'Delete')
                            .html_form_end()
                            .html_form_action('PHP_SELF')
                            .html_form_hidden('modify', 1)
                            .html_form_hidden('delete', 0)
                            .html_form_hidden('faqid', $db->f('faqid'))
                            .html_form_submit($t->translate('No, just modify'),
                                              'modify')
                            .html_form_end());
            $bx->box_columns_end();
            $bx->box_body_end();
            $bx->box_end();
        }

        if ($delete == 2) {
            // We remove it from our DB
            $db->query("DELETE FROM faq WHERE faqid='$faqid' AND "
                       ."language='$la'");
            if ($db->affected_rows() < 1) {
                $be->box_full($t->translate('Error'), 
                              $t->translate('Database Error'));
            } else { 
      	        $bx->box_full($t->translate('Frequently Asked Questions '
                                            .'Administration'),
                $t->translate('The FAQ has been deleted'));
            }
        }  
    }

    if (isset($modify)) {
         if ($modify == 1) {
             $db->query("SELECT * FROM faq WHERE faqid='$faqid' AND "
                        ."language='$la'");
             $db->next_record();
             faqmod($db);
         }
    
        if ($modify == 2) {
            // We insert it into the DB
            $db->query("UPDATE faq SET question='$question',answer='$answer' "
                       ."WHERE faqid='$faqid'");
            if ($db->affected_rows() < 1) {
                $be->box_full($t->translate('Error'), 
                                $t->translate('Database Error'));
            } else {
                // We show what we just have inserted
                $bx->box_full($t->translate('Frequently Asked Questions '
                                            .'Administration'),
                $t->translate('The following FAQ has been modified'));
                $db->query("SELECT * FROM faq WHERE faqid='$faqid'");
                $db->next_record();
                faqshow($db);
            }
        }
    }
    if (isset($create)) {
        if ($create == 1) {
            faqform();
        }
        if ($create == 2) {
            // We insert it into the DB
            $tables = 'faq';
            $insert = "question='$question',answer='$answer',language='$la'";
            if (!$db->query("INSERT $tables SET $insert")) {
	        lib_die('Error in insfaq.php3: Database insertion not '
                        .'completed');
            }
            // We show what we've inserted
            $bx->box_full($t->translate('Frequently Asked Questions '
                                        .'Administration'),
                          $t->translate('The following FAQ has been inserted'));
            $bx->box_full($t->translate('Question').': '.$question,'<b>'
                          .$t->translate('Answer').':</b> '.$answer);
        }
    }
}

end_content();
require('footer.inc');
@page_close();
?>
