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
# Browse through project categorization
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: browse.php,v 1.1 2003/11/21 12:55:58 helix Exp $
#
######################################################################  

require('include/prepend.php3');

page_open(array('sess' => 'SourceAgency_Session'));
if (isset($auth) && !empty($auth->auth['perm'])) {
  page_close();
  page_open(array('sess' => 'SourceAgency_Session',
                  'auth' => 'SourceAgency_Auth',
                  'perm' => 'SourceAgency_Perm'));
}

require('include/header.inc');
require('include/browselib.inc');

$bx = new box('100%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$page = 'browse';

$bx->box_begin();
$bx->box_body_begin();  
$bx->box_columns_begin(2, 'top');

$bx->box_column_start('','50%','');
$bx->box_begin();
$bx->box_title($t->translate('Categories'));
$bx->box_body_begin();

echo html_link('browse.php',array('through' => 'license'),
               $t->translate('License'));
echo '<br>'.html_link('browse.php',array('through' => 'type'),
                      $t->translate( 'Type' ));
echo '<br>'.html_link('browse.php',array('through' => 'steps'),
                      $t->translate( 'Steps' ));
echo '<br>'.html_link('browse.php',array('through' => 'volume'),
                      $t->translate( 'Volume' ));
echo '<br>'.html_link('browse.php',array('through' => 'platform'),
                      $t->translate( 'Platform' ));
echo '<br>'.html_link('browse.php',array('through' => 'architecture'),
                      $t->translate( 'Architecture' ));
echo '<br>'.html_link('browse.php',array('through' => 'environment'),
                      $t->translate( 'Environment' ));
echo '<br>'.html_link('browse.php',array('through' => 'project_name'),
                      $t->translate( 'Project Name' ));
$bx->box_body_end();
$bx->box_end();
$bx->box_column_finish();

if (isset($through)) {
    browse_through($through);
} else {
    $bx->box_column_start('','50%','');
    echo "&nbsp;\n";
    $bx->box_column_finish();
}

$bx->box_columns_end();
$bx->box_body_end();
$bx->box_end();

if (isset($$through)) {
    browse_list($through,$$through);
}

end_content();

require('include/footer.inc');
@page_close();
?>
