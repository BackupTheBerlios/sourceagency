<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#             Gregorio Robles (grex@scouts-es.org)
#             Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Comment update (not insertion)
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: comments_mod.php3,v 1.7 2002/05/07 11:26:58 riessen Exp $
#
######################################################################  

/* TODO: Comment update does not work yet */

page_open(array('sess' => 'SourceAgency_Session'));
if (isset($auth) && !empty($auth->auth['perm'])) {
  page_close();
  page_open(array('sess' => 'SourceAgency_Session',
                  'auth' => 'SourceAgency_Auth',
                  'perm' => 'SourceAgency_Perm'));
}

require('header.inc');
require ('commentslib.inc');

$bx = new box('80%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = 'comments_mod';

if (check_permission($proid, $page)) {
    top_bar($proid, $page);

    /* The next lines are debugging information */
    $type = 'General';
    $number = 0;
    $cmt_id = 1;
    $ref = 0;

    if (is_not_set_or_empty($type) || is_not_set_or_empty($number)
          || is_not_set_or_empty( $cmt_id ) || is_not_set_or_empty($ref)) {
        comments_missing_parameters();
    } else {
        // WISH: It would be nice, that comments could only be modified by 
        // WISH: its author and the project initiator!
        // WISH: Modification should be appended to the original comment
        print $t->translate('Comments can be modified by the '
                            .'project initator') . ".\n<br><p>\n";

        if ((!isset($preview) || empty($preview)) 
              && (!isset($submit) || empty($submit))) {
            $db->query("SELECT * FROM comments WHERE proid='$proid' "
                       ."AND type='$type' AND number='$number' AND "
                       ."id='$cmt_id' AND ref='$ref'");
            $db->next_record();
            $subject = $db->f('subject_cmt');
            $text = $db->f('text_cmt');
            $creation = $db->f('creation_cmt');
        }
      
        if (!isset($submit) || empty($submit)) {
            comments_modify_form($proid);
        } else {
            comments_modify($proid, $auth->auth['uname'], $type, $number,
                            $cmt_id, $ref, $subject, $text, $creation);
        }
    }
}

end_content();
require('footer.inc');
@page_close();
?>
