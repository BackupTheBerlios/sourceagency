<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Comments page por a given project (given by its id)
# This page only shows comments. It is not for insertion/update
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: comments.php3,v 1.5 2002/05/07 10:24:29 riessen Exp $
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
require ('commentslib.inc');

$bx = new box('100%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = 'comments';

if (check_permission($proid, $page)) {
    top_bar($proid, $page);

    if (is_not_set_or_empty($type)) {
        $type = 'General';
    }
    if (is_not_set_or_empty($ref)) {
        $ref = '';
    }

    htmlp_image('ic/c.png',0,48,48,'Summary');

    print( $t->translate( 'General comments can be posted') . ' '
           . $t->translate( 'by registered users of the system' ) 
           . ".\n<br><p>\n" );

    comments_show($proid, $type, $number, $cmt_id, $ref);

    lib_comment_it($proid,'General','0','0','',
                   $t->translate('General Comments'));
}

end_content();
require('footer.inc');
@page_close();
?>