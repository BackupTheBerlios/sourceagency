<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org) and
#                Gerrit Riessen (Gerrit.Riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This page lists the developers registered in our system
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: users.php3,v 1.8 2002/10/08 15:27:39 helix Exp $
#
######################################################################

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require 'header.inc';

$bx = new box('100%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$bs = new box('100%',$th_strip_frame_color,$th_strip_frame_width,
              $th_strip_title_bgcolor,$th_strip_title_font_color,
              $th_strip_title_align,$th_strip_body_bgcolor,
              $th_strip_body_font_color,$th_strip_body_align);
$be = new box('',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);


start_content();

/* Access control: can be configured in include/config.inc via the
   variable config_perm_users */
if ( ($config_perm_users != 'all') 
     && (!isset($perm) || !$perm->have_perm($config_perm_users))) {
    $be->box_full($t->translate('Error'), $t->translate('Access denied'));
} else {

    if ( is_not_set_or_empty( $by ) ) {
        $by = '%';
    }

    $alphabet = array ('A','B','C','D','E','F','G','H','I','J','K','L',
	   	       'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $msg = '[ ';

    while (list(, $ltr) = each($alphabet)) {
        $msg .= "<a href=\"".$sess->self_url()
                .$sess->add_query(array('type' => $type, 'by' => $ltr.'%'))
                ."\">".( $ltr.'%' == $by ? "<font color=\"red\">$ltr</font>":"$ltr")
                .'</a> | ';
    }

    $msg .= ( "<a href=\"".$sess->self_url()
            .$sess->add_query(array("type" => $type, "by" => "%"))."\">"
            .( $by == "%" ? ("<font color=\"red\">".$t->translate("All")
                             ."</font>"):$t->translate("All"))."</a> ]");

    $where = ( ($type=='devel'||$type=='sponsor' ? "perms LIKE '%$type%' AND "
                                               : '')
               . "username LIKE '$by'");

    $bs->box_strip($msg);
    $db->query("SELECT * FROM auth_user WHERE $where ORDER BY username ASC");
    $bx->box_begin();
    $bx->box_title($t->translate('Users').': '.$t->translate($type));
    $bx->box_body_begin();

    $bx->box_columns_begin(5);
    $bx->box_column('right','5%','','<b>'.$t->translate('No.').'</b>');
    $bx->box_column('center','25%','','<b>#&nbsp;'.$t->translate('Projects').'</b>');
    $bx->box_column('center','25%','','<b>'.$t->translate('Username').'</b>');
    $bx->box_column('center','25%','','<b>'.$t->translate('Realname').'</b>');
    $bx->box_column('center','25%','','<b>'.$t->translate('E-Mail').'</b>');
    $bx->box_next_row_of_columns();

    $i = 1;
    $colors = array( 0 => '#FFFFFF', 1 => 'gold' );
    while($db->next_record()) {
        $bgcolor = $colors[ $i%2 ];

        $db2 = new DB_SourceAgency;
        $db2->query("SELECT COUNT(*) FROM description "
                    ."WHERE description_user='"
                    .$db->f("username")."' AND status>'0'");
        $db2->next_record();
        $num = '['.sprintf('%03d',$db2->f("COUNT(*)")).']';
        $bx->box_column('right','',$bgcolor,$i);
        $bx->box_column('center','',$bgcolor,$num);

        $user = $db->f('username');
        $bx->box_column('center','',$bgcolor, 
                        sprintf( "<a target=\"_blank\" href=\""
                                 . $g_dev_counter_url . "\">%s</a>", 
                                 $user, $user));

        $bx->box_column('center','',$bgcolor,$db->f('realname'));
        $bx->box_column('center','',$bgcolor,
                        html_link('mailto:'.$db->f('email_usr'),'',
                                  ereg_replace('@',' at ',
                                               htmlentities($db->f('email_usr')))));
        $bx->box_next_row_of_columns();
        $i++;
    }
    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}

end_content();
require('footer.inc');
@page_close();
?>