<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This file inserts a new license
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
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

if (($config_perm_admlicens != 'all') 
        && (!isset($perm) || !$perm->have_perm($config_perm_admlicens))) {
    $be->box_full($t->translate('Error'), $t->translate('Access denied'));
} else {
    if (isset($license) && !empty($license)) {
        // Look if License is already in table
        $db->query("SELECT * FROM licenses WHERE license='$license'"); 

        if ($db->num_rows() > 0) {
            if (isset($new_license)) {
                // If license in database and a new name is given, then rename
                if (!empty($new_license)) {
                    $db->query("SELECT proid,creation FROM developing WHERE "
                               ."license = '$license'");
                    // All the affected projects are treated as modified
                    // BUT they are assigned to the new license!!!!
   	            while ($db->next_record()) {
                        $creation = $db->f('creation');
                        $proid = $db->f('proid');
                        $db_rename = new DB_SourceAgency;
                        $db_rename->query("UPDATE developing SET license='$new_license',"
                                          ."creation='$creation' WHERE proid='$proid'");
                    }
                    $affected_projects = $db->num_rows();

                    $db->query("UPDATE licenses SET license='$new_license' WHERE "
                               ."license='$license'");
                    if ($db->affected_rows() == 1) {
 	                $bx->box_full($t->translate('Administration'),
                                      $t->translate('License').' '.$license.' '
                                      .$t->translate('has been renamed to').' '
                                      .$new_license.' '.$t->translate('affecting')
                                      .' $affected_projects '.$t->translate('projects'));
                    }
                } else {
                    // License is a blank line
                    $be->box_full($t->translate('Error'), 
                                  $t->translate('License name not specified'));
                }
            }

            if  (isset($new_url)) {
                // If license in database and a new url is given, then go for it
                if (!empty($new_url)) {
                    $db->query("UPDATE licenses SET url='$new_url' WHERE "
                               ."license='$license'");
	            $bx->box_full($t->translate('Administration'),
                                  $t->translate('License').' '.$license.' '
                                  .$t->translate('has a new URL:').' '.$new_url);
                } else {
                    // URL is a blank line
                    $be->box_full($t->translate('Error'), 
                                  $t->translate('New URL not specified'));
                }
            } 
            if (isset($del_license)) {
                // License in database and we want to delete it
	        if (!strcmp($del_license, 'warning')) {
                    // You've got another chance before it's deleted ;-)
                    // We inform the administrator how many
                    // projects will be affected by this deletion    

                    $db->query("SELECT COUNT(*) FROM developing WHERE "
                               ."license='$license'");
                    $db->next_record();
                    $number_of_projects = $db->f('COUNT(*)');
    
                    $be->box_full($t->translate('Warning!'), 
                                  $t->translate('If you press another time the '
                                               .'Delete-button you will alter')
                                               .' '.$number_of_projects.' '
                                               .$t->translate('projects that have '
                                               .'actually license').' '.$license);

 	            $bx->box_begin();
	            $bx->box_title($t->translate('Delete License'));
	            $bx->box_body_begin();
                    $bx->box_columns_begin(2);
                    $bx->box_column('left', '76%', '', '<b>'.$t->translate('License').'</b>: '.$license);
                    $bx->box_column('right', '12%', '', html_form_action('PHP_SELF')
    	 	                                       .html_form_hidden('license', $license)
    	 	                                       .html_form_hidden('del_license', 'too_late')
                                                       .html_form_submit($t->translate('Delete'))
			        	               .html_form_end());
                    $bx->box_columns_end();
                    $bx->box_body_end();
                    $bx->box_end();
       	            $bx->box_body_end();
	            $bx->box_end();

                } else {
                    $db->query("DELETE from licenses WHERE license='$license'");
  	            $bx->box_full($t->translate('Administration'), 
                                  $t->translate('Deletion succesfully completed.'));
                }
            } else {
                if (empty($new_license) && empty($new_url) && empty($del_license)) { 
                  // It's already in our database
                  // but no rename and no deletion and no new url... ->error
                  $be->box_full($t->translate('Error'), 
                                $t->translate('That license already exists!'));
                }
            }
        } else {
            // If license is not in table, insert it
            if (!empty($url_lic)) {
                $db->query("INSERT INTO licenses VALUES ('$license','$url_lic')");
                $bx->box_full($t->translate('Administration'),
                              $t->translate('License').' '.$license
                             .$t->translate('with URL').' '.$url_lic
                             .$t->translate('has been added succesfully '
                                        .'to the database'));
            } else {
                // URL is a blank line
                $be->box_full($t->translate('Error'), 
                              $t->translate('License URL not specified'));
            }
        }
    } else {
        // License is a blank line or isn't set
        $be->box_full($t->translate('Error'), 
                      $t->translate('License not specified'));
    }
}

end_content();
require('include/footer.inc');
page_close();
?>
