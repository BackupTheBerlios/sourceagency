<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ================================================
#
# Copyright (c) 2001 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourcewell.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This page enables (authenticated) users to change their parameters
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: chguser.php3,v 1.13 2002/05/10 12:32:22 grex Exp $
#
######################################################################

page_open(array('sess' => 'SourceAgency_Session',
                'auth' => 'SourceAgency_Auth',
                'perm' => 'SourceAgency_Perm'));

require('header.inc');
require('monitorlib.inc');

$bx = new box('',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$bi = new box('80%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);
$be = new box('80%',$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_error_font_color,$th_box_body_align);

start_content();

// Check if there was a submission
while (is_array($HTTP_POST_VARS) 
       && list($key, $val) = each($HTTP_POST_VARS)) {
    switch ($key) {
    case 'u_edit': // Change user parameters
        if($auth->auth['uid'] == $u_id) { // user changes his own account
            $password = trim($password);
            $cpassword = trim($cpassword);
            $realname = trim($realname);
            $email_usr = trim($email_usr);

            if (strcmp($password,$cpassword)) { // password are identical?
            	$be->box_full($t->translate('Error'), 
                              $t->translate('The passwords are not identical')
                              .'. '.$t->translate('Please try again').'!');
            	break;
            }

            $query = ("UPDATE auth_user SET password='$password', "
                      ."realname='$realname', email_usr='$email_usr', "
                      ."modification_usr=NOW() WHERE user_id='$u_id'");
            $db->query($query);

            if ($db->affected_rows() == 0) {
                lib_die('Change User Parameters failed'.":<br>$query");
                break;
            }

            $bi->box_full($t->translate('Change User Parameters'), 
                          $t->translate('Password and/or E-Mail Address of')
                          .' <b>'. $auth->auth['uname'] .'</b> '
                          .$t->translate('is changed').'.');
            if ($ml_notify) {
                $message  = $t->translate('Username')
                                .': '.$auth->auth['uname']."\n";
                $message .= $t->translate('Realname').": $realname\n";
                $message .= $t->translate('E-Mail').":   $email_usr\n";

                mailuser('admin', 
                         $t->translate('User parameters has changed'), 
                         $message);
            }
        } else {
            $be->box_full($t->translate('Error'), 
                          $t->translate('Access denied'));
        }
        break;
    default:
        break;
    }
}

$bx->box_begin();
$bx->box_title($t->translate('Change User Parameters'));
$bx->box_body_begin();

htmlp_form_action();
$bx->box_columns_begin();

$db->query("SELECT * FROM auth_user WHERE username='".$auth->auth['uname']."'");
$db->next_record();

$bx->box_column('right',  '50%', '', '<b>'.$t->translate('Username').':</b>');
$bx->box_column('left', '50%', '', html_input_text('username', 20, 32, $db->f('username')));

$bx->box_next_row_of_columns();

$bx->box_column('right', '50%', '', '<b>'.$t->translate('Password').':</b>');
$bx->box_column('left', '50%', '', html_input_password('password', 20, 32, $db->f('password')));

$bx->box_next_row_of_columns();

$bx->box_column('right', '50%', '', '<b>'.$t->translate('Confirm Password').':</b>');
$bx->box_column('left', '50%', '', html_input_password('cpassword', 20, 32, $db->f('password')));

$bx->box_next_row_of_columns();

$bx->box_column('right', '50%', '', '<b>'.$t->translate('Real Name').':</b>');
$bx->box_column('left', '50%', '', html_input_text('realname', 20, 64, $db->f('realname')));

$bx->box_next_row_of_columns();

$bx->box_column('right', '50%', '', '<b>'.$t->translate('E-mail').':</b>');
$bx->box_column('left', '50%', '', html_input_text('email_usr', 20, 128, $db->f('email_usr')));

$bx->box_next_row_of_columns();

$bx->box_column('right', '50%', '', '<b>'.$t->translate('Creation').':</b>');
$bx->box_column('left', '50%', '', timestr(mktimestamp($db->f('creation_usr'))));

$bx->box_next_row_of_columns();

$bx->box_column('right', '50%', '', '<b>'.$t->translate('Last Modification').':</b>');
$bx->box_column('left', '50%', '', timestr(mktimestamp($db->f('modification_usr'))));

$bx->box_next_row_of_columns();

$bx->box_column('right', '50%', '', '<b>'.$t->translate('Permisions').':</b>');
$bx->box_column('left', '50%', '', $db->f('perms'));

$bx->box_next_row_of_columns();

$bx->box_colspan( 2, 'center', '', html_form_submit($t->translate('Change'), 'u_edit'));

$bx->box_columns_end();
htmlp_form_hidden('u_id', $db->f('user_id'));
htmlp_form_end();
$bx->box_body_end();
$bx->box_end();
$bx->box_body_end();
$bx->box_end();

end_content();
require('footer.inc');
@page_close();
?>
