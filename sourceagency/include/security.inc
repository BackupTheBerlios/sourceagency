<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org),
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Security and Access control library
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: security.inc,v 1.23 2002/05/15 09:32:04 riessen Exp $
#
######################################################################

/**
 * This library contains the functions that allow access to 
 * web pages and data during the whole process
 *
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @access  public
 * @see html, lib, translate, box, auth
 */

/**
 * Right box in the Summary page
 * Contains a (big) image of the step and a drescription as well as some
 * statistics. If the step (a link to the page for the actions to do in that step)
 * can be chosen or not depends on the status of the project
 *
 * Allowed_actions is a function used in the summary page
 *
 * @param   int       $project_status  Step of the project (-1 = denied, 0 = pending)
 * @param   int       $action_number   
 * @param   int       $proid           Project id
 *
 * @global  array     $g_step_text             Contains strings with a text about the step
 * @global  object    $t                       translation object
 * @global  string    $g_step_explanation      Contains strings with the explanation
 *                                             of what can be done in the step
 * @global  string    $g_step_count            TODO
 *
 * @access  public
 */
function allowed_actions ($project_status, $action_number, $proid)
{
    global $g_step_text, $t, $g_step_explanation, $g_step_count;

    $db_allowed = new DB_SourceAgency;
    
    if ( $action_number < 1 ) {
        $action_number = 1;
    } else if ( $action_number > $g_step_count ) {
        $action_number = $g_step_count;
    }

    $text = $t->translate( $g_step_text[ $action_number ] );

    switch($action_number) {
        case 1: {
            $link = 'step1.php3?proid='.$proid;
            $db_allowed->query("SELECT consultants FROM configure "
                               ."WHERE proid='$proid'");
            $db_allowed->next_record();
            if($db_allowed->f("consultants") == 'Yes') {                
                $db_allowed->query("SELECT COUNT(*) FROM consultants "
                                   ."WHERE proid='$proid'");
                $db_allowed->next_record();
                $statistics = ( '( <B>'.$db_allowed->f("COUNT(*)")
                                .'</B> '
                                .$t->translate('consultant offerings').')' );
            } else {
              $statistics = ( '( '.$t->translate('this project is configured '
                                                 .'to have <b>no</b> '
                                                 .'consultants')
                              .')' );
            }
            $explanation = $g_step_explanation[1];
            break;
        }
        case 2: {
            $link = 'step2.php3?proid='.$proid;
            // Sponsor Involvement
            $db_allowed->query("SELECT COUNT(*) FROM tech_content "
                               ."WHERE proid='$proid'");
            $db_allowed->next_record();
            $statistics = ('( <B>'.$db_allowed->f("COUNT(*)").'</B> '
                           .'suggested project contents');
            $db_allowed->query("SELECT COUNT(*) FROM developing "
                               ."WHERE proid='$proid'");
            $db_allowed->next_record();
            $statistics .= (', <B>'.$db_allowed->f("COUNT(*)")
                            .'</B> '.$t->translate('developing proposals')
                            .' )');
            $explanation = $g_step_explanation[2];
            break;
        }
        case 3: {
            $link = 'step3.php3?proid='.$proid;
            $db_allowed->query("SELECT COUNT(*) FROM milestones "
                               ."WHERE proid='$proid'");
            $db_allowed->next_record();
            $statistics = ( '( <B>'.$db_allowed->f("COUNT(*)")
                            .'</B> '.$t->translate('suggested milestones')
                            .' )');
            $explanation = $g_step_explanation[3];
            break;
        }
        case 4: {
            $link = 'step4.php3?proid='.$proid;
            $db_allowed->query("SELECT COUNT(*) FROM referees "
                               ."WHERE proid='$proid'");
            $db_allowed->next_record();
            $statistics = ( '( <B>'.$db_allowed->f("COUNT(*)")
                            .'</B> '.$t->translate('referees offered').' )' );
            $explanation = $g_step_explanation[4];
            break;
        }
        case 5: {
            $link = 'step5.php3?proid='.$proid;
            $statistics = ( '( <B>x</B> '.$t->translate('milestones of')
                            .' <b>x</b> '
                            .$t->translate('total milestones fulfilled')
                            .' )' );
            $explanation = $g_step_explanation[5];
            break;
        }
        case 6: {
            $link = 'step6.php3?proid='.$proid;
            $statistics = '';
            $explanation = $g_step_explanation[6];
            break;
        }

        default:
            if ( $g_step_count != 6 ) {
                lib_die("security.inc::allowed_actions: It appears that "
                        ."g_step_count(= $g_step_count) doesn't equal 6 and "
                        ."action_number(= $action_number) is greater than 6");
            }
    }

    if ($action_number > 1) {
        print "<hr>\n";
    }
    print ( $project_status >= $action_number ? "<a href=\"$link\">" 
                                                : "<font color=\"#CCCCCC\">");
    print "<IMG src=\"images/ic/".$action_number;
    if ($project_status < $action_number) {
        print 'grey';
    }

    // TODO: should height and width be hardcoded here?
    print ".png\" border=0 width=\"48\" height=\"48\" alt=\"1\">&nbsp;"
        . $text . ( $project_status >= $action_number ? "</a>" : "</font>" );

    if ($project_status >= $action_number) {
        print ' '.$statistics;
    }
    print '<br><i>'.$t->translate($explanation)."</i>\n";
}

/**
 * Check if a project is not pending or has not been denied
 * Only editors and project initiator will be able to access it then
 *
 * @param   int       $proid           Project id
 *
 * @global  object    $t               translation object
 * @global  object    $perm            PHPLib permission object
 *
 * @return  boolean                    1 if access is possible, 0 otherwise
 * 
 * @access  public
 */
function check_proid($proid)
{
    global $perm, $t;
    
    $db_check = new DB_SourceAgency;
    
    $db_check->query("SELECT * FROM description WHERE proid='$proid'"); 
    $db_check->next_record();
    if ($db_check->num_rows() == 0 || empty($proid)) {
        // WISH: config.inc should not be required another time, 
        // WISH: as it is done at the beginning of every page.
        require("config.inc");
        $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                      $th_box_title_bgcolor,$th_box_title_font_color,
                      $th_box_title_align,$th_box_body_bgcolor,
                      $th_box_error_font_color,$th_box_body_align);
        $be->box_full($t->translate("Error"), 
                      $t->translate("No project with this id."));
        return 0;
    } elseif ($db_check->f("status") == 0) {
        /* Project pending. It is waiting for an editor to review it */
        if ( (isset($perm) && $perm->have_perm("editor"))
             || is_project_initiator($proid)) {
            /* only editors and the project initiator have access to it */
            return 1;
        }

        require("config.inc");
        $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                      $th_box_title_bgcolor,$th_box_title_font_color,
                      $th_box_title_align,$th_box_body_bgcolor,
                      $th_box_error_font_color,$th_box_body_align);
        $be->box_full($t->translate("Error"), 
                      $t->translate("Project pending for review by "
                                    ."an editor"));
        return 0;
    } elseif ($db_check->f("status") == "-1") {
        /* project was denied by a system editor */
        if (isset($perm) && $perm->have_perm("editor") ) {
            return 1;
        }

        require("config.inc");
        $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                      $th_box_title_bgcolor,$th_box_title_font_color,
                      $th_box_title_align,$th_box_body_bgcolor,
                      $th_box_error_font_color,$th_box_body_align);
        $be->box_full($t->translate("Error"), 
                      $t->translate("Project was not accepted."));
        return 0;
  } else {
        return 1;
  }
}

/**
 * This function manages all the page accesses in the system
 * All pages have an entry in the switch where the conditions can be read out
 *
 * @param   int       $proid           Project id
 * @param   string    $page            HTML page of the submission
 *
 * @global  object    $auth            PHPLib authentication object
 * @global  object    $perm            PHPLib permission object
 *
 * @return  boolean                    1 if access is possible, 0 otherwise
 *
 * @access  public
 */
function check_permission($proid, $page)
{
    global $perm, $auth;

    if (isset($perm) && ($perm->have_perm("editor"))) {
        return 1;
    }

    /* We get the project initatior and the project status */
    $db_check = new DB_SourceAgency;
    $db_check->query("SELECT * FROM description WHERE proid='$proid'"); 
    if ( $db_check->num_rows() == 0 ) {
        return invalid_project_id($proid,$page);    
    }
    $db_check->next_record();
    $project_initiator = $db_check->f("description_user");
    $current_step = $db_check->f("status");

    /* We look if no special conditions happen */  
    if ($db_check->num_rows() == 0 || empty($proid) 
        || (($db_check->f("status") == 0 || $db_check->f("status") == "-1") 
        && !is_project_initiator($proid))) {
        if (isset($auth) && !strcmp($db_check->f("description_user"),
                              $auth->auth["uname"])) {
            // WISH: config.inc should be not required another 
            // WISH: time as it should have been done at the 
            // WISH: beginning of every page
            require("config.inc");
            $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                          $th_box_title_bgcolor,$th_box_title_font_color,
                          $th_box_title_align,$th_box_body_bgcolor,
                          $th_box_error_font_color,$th_box_body_align);
            $be->box_full("Error", "No project id or project pending");
            return 0;
        }
    } else {
        /* Access conditions depending on the page */
        switch($page) {
        case "news_edit":
        case "news_mod":
        case "comments_edit":
        case "history":
        case "step3":
        case "step4":
        case "step5":
        case "cooperation":
        case "views":
            if (security_accept_by_view($proid,$page)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
       case "configure":
            if (is_project_initiator($proid)) {
                return 1;
            } elseif (security_accept_by_view($proid,$page)) {
                  return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "configure_edit":
            if (is_project_initiator($proid) 
                || is_first_sponsor_or_dev($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "views_edit":
            if (is_project_initiator($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "monitor":
            if (is_accepted_sponsor($proid) 
                || is_accepted_developer($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "monitor_edit":
            if (is_sponsor() || is_developer()) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "summary":
            break;
        case "news_mod":
            if (is_project_initiator($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "comments":
            break;
        case "comments_mod":
            if (is_project_initiator($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "sponsoring":
            break;
        case "sponsoring_edit":
            if ( is_sponsor() || is_project_initiator($proid) ) {
              /* nothing to do */
              return 1;
//                  if (!already_involved_in_this_step($proid,
//                                 "sponsoring_edit",$auth->auth["uname"])) {
//                      return 1;
//                  } else {
//                      return already_involved_message($proid,$page);
//                  }
            } else {
                return permission_denied($proid,$page);
            }
        case "sponsoring_mod":
            if (is_project_initiator($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "sponsoring_accepted":
            if (is_accepted_sponsor($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "step1":
            break;
        case "step1_edit":
            if (is_developer()) {
                if ($current_step == 1) {
                    if (!already_involved_in_this_step($proid,
                    "step1_edit",$auth->auth["uname"])) {
                        return 1;
                    } else {
                        return already_involved_message($proid,$page);
                    }
                } else {
                    return step_not_open($proid,$page);
                }
            } else {
                return permission_denied($proid,$page);
            }
        case "step2":
            break;
        case "step2_edit":
            if (is_developer() || is_project_initiator($proid)) { 
                if ($current_step == 2) {
                    if (other_specifications_allowed($proid) 
                        || (is_project_initiator($proid) 
                            && no_other_specification_yet($proid))) {
                        return 1;
                    } else {
                        return projects_only_by_project_initiator($proid,
                        $page);
                    }
                } else {
                    return step_not_open($proid,$page);
                }
            } else {
                return permission_denied($proid,$page);
            }
        case "developing_edit":
            if (is_developer()) { 
                if ($current_step == 2) {
                    if (!already_involved_in_this_content($proid,
                    "developing_edit",$auth->auth["uname"],
                    $GLOBALS["content_id"])) {
                        if (other_developing_proposals_allowed($proid) 
                            || (is_project_initiator($proid) 
                                && no_other_proposal_yet($proid))) {
                            return 1;
                        } else {
                            return proposals_only_by_project_initiator($proid,$page);
                        }
                    } else {
                        return already_involved_message($proid,$page);
                    }
                } else {
                    return step_not_open($proid,$page);
                }
            } else {
                return permission_denied($proid,$page);
            }
        case "developing_mod":
            if (is_project_initiator($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "step3_edit":
            if (is_developer()) { 
                if ($current_step == 3) {
                    if (is_involved_developer($proid)) {
                        if (is_milestone_possible($proid,$devid)) {
                            return 1;
                        } else {
                           return milestone_not_possible($proid,$page);
                        }
                    } else {
                        return permission_denied($proid,$page);
                    }
                } else {
                    return step_not_open($proid,$page);
                }
            } else {
                return permission_denied($proid,$page);
            }
        case "step3_mod":
            if (is_your_milestone($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "step4_edit":
            if (is_developer() && !is_main_developer($proid)) { 
                if ($current_step == 4) {
                    if (!already_involved_in_this_step($proid,
                    "step4_edit",$auth->auth["uname"])) {
                        return 1;
                    }
                    return already_involved_message($proid,$page);
                } else {
                    return step_not_open($proid,$page);
                }
            } else {
                return permission_denied($proid,$page);
            }
        case "step5_devel":
            if (is_involved_developer($proid)) { 
                if ($current_step == 5) {
                    if (step5_iteration($proid) == 0) {
                        return 1;
                    } else {
                        return step5_not_your_iteration($proid,$page);
                    }
                } else {
                    return step_not_open($proid,$page);
                }
            } else {
                return permission_denied($proid,$page);
            }
        case "step5_sponsor":
            if (is_accepted_sponsor($proid)) { 
                if ($current_step == 5) {
                    if (step5_iteration($proid) == 1 
                        || step5_iteration($proid) == 3) {
                        return 1;
                    } else {
                        return step5_not_your_iteration($proid,$page);
                    }
                } else {
                    return step_not_open($proid,$page);
                }
            } else {
                return permission_denied($proid,$page);
            }
        case "step5_referee":
            if (is_accepted_referee($proid)) { 
                if ($current_step == 5) {
                    if (step5_iteration($proid) == 2) {
                        return 1;
                    } else {
                        return step5_not_your_iteration($proid,$page);
                    }
                } else {
                    return step_not_open($proid,$page);
                }
            } else {
                return permission_denied($proid,$page);
            }
        case "step6":
            break;
        case "step6_edit":
            if (is_accepted_developer($proid) 
                || is_accepted_sponsor($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "decisions":
            if (is_accepted_sponsor($proid) 
                || (is_main_developer($proid) && $current_step == 4)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "personal":
            if ($username == $auth->auth["uname"]) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "sponsoring_accepted":
            if (is_accepted_sponsor($proid)) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        case "cooperation_edit":
            if (is_developer()) {
                return 1;
            } else {
                return permission_denied($proid,$page);
            }
        }
        return 1;
    }
}

/**
 * Prints an error message in a box
 *
 * @param   string    $error           Error (head of the erro box)
 * @param   string    $msg             Error Message (body of the error box)
 *
 * @global  object    $t               Translation object
 *
 * @access  public
 */
function generate_failed_box($error, $msg)
{
    global $t;

    require("config.inc");
    $be = new box("80%",$th_box_frame_color,$th_box_frame_width,
                  $th_box_title_bgcolor,$th_box_title_font_color,
                  $th_box_title_align,$th_box_body_bgcolor,
                  $th_box_error_font_color,$th_box_body_align);
  
    $be->box_full($t->translate( $error ), $t->translate( $msg ));
}

/**
 * Permission denied error box
 *
 * @param   string    $msg             Error Message (body of the error box)
 * @access  public
 */
function generate_permission_denied_box($msg)
{
    generate_failed_box('Permission denied', $msg);
}

/**
 * Invalid project id error
 * (also prints project top-bar to facilitate navigation)
 *
 * @param   int     $proid             Project identifier
 * @param   string  $page              Page to be accessed
 * @return  constant                   always 0 (FALSE) => access denied
 * @access  public
 */
function invalid_project_id($proid, $page)
{
    top_bar($proid,$page);
    generate_permission_denied_box("Project does not exist");
    return 0;     
}

/**
 * Permission denied error
 * (also prints project top-bar to facilitate navigation)
 *
 * @param   int     $proid             Project identifier
 * @param   string  $page              Page to be accessed
 * @return  constant                   always 0 (FALSE) => access denied
 * @access  public
 */
function permission_denied($proid, $page)
{
    top_bar($proid, $page);
    generate_permission_denied_box('You do not have rights to enter this page.');
    return 0;     
}

/**
 * Tried to access to a step that is not open yet
 * (also prints project top-bar to facilitate navigation)
 *
 * @param   int     $proid             Project identifier
 * @param   string  $page              Page to be accessed
 * @return  constant                   always 0 (FALSE) => access denied
 * @access  public
 */
function step_not_open($proid, $page)
{
    top_bar($proid, $page);
    generate_permission_denied_box('This action can not be made at this time.');
    return 0;
}

/**
 * Project only accepts specification by project initiator
 * (also prints project top-bar to facilitate navigation)
 *
 * @param   int     $proid             Project identifier
 * @param   string  $page              Page to be accessed
 * @return  constant                   always 0 (FALSE) => access denied
 * @access  public
 */
function projects_only_by_project_initiator($proid, $page)
{
    top_bar($proid,$page);
    generate_permission_denied_box('The project has been configured so that '
                                   . 'only the project initiator can post one '
                                   .'specification.');
    return 0;
}

/**
 * Project only accepts proposals by project initiator
 * (also prints project top-bar to facilitate navigation)
 *
 * @param   int     $proid             Project identifier
 * @param   string  $page              Page to be accessed
 * @return  constant                   always 0 (FALSE) => access denied
 * @access  public
 */
function proposals_only_by_project_initiator($proid, $page)
{
    top_bar($proid, $page);
    generate_permission_denied_box('The project has been configured so that '
                                   .'only the project initiator can post one '
                                   .'developing proposal.');
    return 0;
}

/**
 * The user already has submitted this action. And this action can be only done once
 * (also prints project top-bar to facilitate navigation)
 *
 * @param   int     $proid             Project identifier
 * @param   string  $page              Page to be accessed
 * @return  constant                   always 0 (FALSE) => access denied
 * @access  public
 */
function already_involved_message($proid, $page)
{
    top_bar($proid, $page);
    generate_permission_denied_box('You are not allowed to make this action '
                                   .'more than one time.');
    return 0;
}

/**
 * If the visitor is the project initiator of this project
 *
 * @param   int     $proid             Project identifier
 * @global  object  $auth              PHPLib authentication object
 * @return  boolean
 * @access  public
 */
function is_project_initiator($proid)
{
    global $auth;

    if (isset($auth) && !empty($auth->auth["perm"])) {   
        $db_local = new DB_SourceAgency;

        $db_local->query("SELECT * FROM description WHERE proid='$proid' "
                         ."AND description_user='".$auth->auth["uname"]."'");

        if ($db_local->num_rows() > 0) {
            return 1;
        }
    }
    return 0;
}

/**
 * If the visitor is an accepted sponsor for this project
 *
 * @param   int     $proid             Project identifier
 * @global  object  $auth              PHPLib authentication object
 * @return  boolean
 * @access  public
 */
function is_accepted_sponsor($proid)
{
    global $auth;

    if (isset($auth) && !empty($auth->auth["perm"])) { 
        $db_local = new DB_SourceAgency;
        $db_local->query("SELECT * FROM sponsoring WHERE proid='$proid' "
                           . "AND status='A' AND sponsor='"
                           . $auth->auth["uname"]."'");
          if ($db_local->num_rows() > 0) {
              return 1;
	  }
    }
    return 0;
}

/**
 * If the visitor is an accepted referee for this project
 *
 * @param   int     $proid             Project identifier
 * @global  object  $auth              PHPLib authentication object
 * @return  boolean
 * @access  public
 */
function is_accepted_referee($proid)
{
    global $auth;

    if (isset($auth) && !empty($auth->auth["perm"])) { 
        $db_local = new DB_SourceAgency;
        $db_local->query("SELECT * FROM referees WHERE proid='$proid' "
                          ."AND status='A' AND referee='"
                           .$auth->auth["uname"]."'");
        if ($db_local->num_rows() > 0) {
            return 1;
	}
    }
    return 0;
}

/**
 * Checks permissions
 * this information is already stored in the perm object
 * you can also use perm->have_perm('....')
 *
 * @param   string     $perm           Permission required
 * @global  object     $auth           PHPLib authentication object
 * @return  boolean
 * @access  private
 */
function _check_permissions($perm)
{
    global $auth;

    // modification to allow for multiple permissions, problems
    // occur if other permissions _contain_ the string 'devel'  
    if (isset($auth) && !empty($auth->auth["perm"])) { 
        $db_local = new DB_SourceAgency;

        $db_local->query("SELECT * FROM auth_user WHERE perms LIKE '%"
                         .$perm."%' AND username='".$auth->auth["uname"]."'");

        return $db_local->num_rows();
    }
    return 0;
}

/**
 * Returns if the visitor has sponsor permissions
 * 
 * @return  boolean
 * @access  public
 */
function is_sponsor()
{
    return _check_permissions('sponsor');
}

/**
 * Returns if the visitor has administrator permissions
 * 
 * @return  boolean
 * @access  public
 */
function is_administrator()
{
    return _check_permissions('admin');
}

/**
 * Returns if the visitor has developer permissions
 * 
 * @return  boolean
 * @access  public
 */
function is_developer()
{
    return _check_permissions('devel');
}

/**
 * Returns if the visitor is a developer involved in the project given by proid
 * 
 * @param   int     $proid             Project identifier
 * @global  object  $auth              PHPLib authentication object
 * @return  boolean
 * @access  public
 */
function is_involved_developer($proid)
{
    global $auth;

    if (isset($auth) && !empty($auth->auth["perm"])) { 
        $db_local = new DB_SourceAgency;
        $db_local->query("SELECT * FROM developing WHERE proid='$proid' "
                         ."AND developer='".$auth->auth["uname"]."'");
        if ($db_local->num_rows() > 0) {
            return 1;
	}
    }
    return 0;
}

/**
 * Returns if the visitor is a referee in the project given by proid
 * 
 * @param   int     $proid             Project identifier
 * @global  object  $auth              PHPLib authentication object
 * @return  boolean
 * @access  public
 */
function is_referee($proid)
{
    global $auth;

    if (isset($auth) && !empty($auth->auth["perm"])) { 
        $db_local = new DB_SourceAgency;
        $db_local->query("SELECT * FROM referees WHERE proid='$proid' "
                         ."AND referee='".$auth->auth["uname"]."'");
        if ($db_local->num_rows() > 0) {
	    return 1;
        }
    }
    return 0;
}

/**
 * Returns if the visitor is an accepted sponsor in the project given by proid
 * 
 * @param   int     $proid             Project identifier
 * @global  object  $auth              PHPLib authentication object
 * @return  boolean
 * @access  public
 */
function is_accepted_developer($proid)
{
    global $auth;

    if (isset($auth) && !empty($auth->auth["perm"])) { 
        $db_local = new DB_SourceAgency;
        $db_local->query("SELECT * FROM developing WHERE proid='$proid' "
                         ."AND status='A' AND developer='"
                         .$auth->auth["uname"]."'");
        if ($db_local->num_rows() > 0) {
            return 1;
	}
    }
    return 0;
}

/**
 * Returns if the visitor is the main developer of the project given by proid
 * 
 * @param   int     $proid             Project identifier
 * @global  object  $auth              PHPLib authentication object
 * @return  boolean
 * @access  public
 */
function is_main_developer($proid)
{
  global $auth;

  if (isset($auth) && !empty($auth->auth["perm"]) 
      && is_accepted_developer($proid)) {
      $db_local = new DB_SourceAgency;
      
      $db_local->query("SELECT * FROM configure WHERE proid='$proid' "
                       ."AND developer='" . $auth->auth["uname"] . "'");
      if ($db_local->num_rows() > 0) {
          return 1;
      }
  }
  return 0;
}

/**
 * Returns true if the given username is already involved in the actions listed
 * 
 * @param   int     $proid             Project identifier
 * @param   string  $page
 * @param   string  $username
 * @return  boolean
 * @access  public
 */
function already_involved_in_this_step($proid, $page, $username)
{
    switch ($page) {
    case "sponsoring_edit":
        $table = "sponsoring"; 
        $who = "sponsor"; 
        break;
    case "step1_edit":  
        $table = "consultants"; 
        $who = "consultant"; 
        break;
    case "developing_edit": 
        $table = "developing"; 
        $who = "developer"; 
        break;
    case "step4_edit":  
        $table = "referees"; 
        $who = "referee"; 
        break;
    default:
        return 0;
    }

    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM $table WHERE proid='$proid' AND "
                     ."$who='$username'");
    if ($db_local->num_rows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * Returns true if the given username is already involved in the content listed
 * 
 * @param   int     $proid             Project identifier
 * @param   string  $page *NOT USED* FIXME
 * @param   string  $username
 * @param  int     $content_id        Content identifier
 * @return  boolean
 * @access  public
 */
function already_involved_in_this_content($proid, $page, $username, $content_id)
{
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM developing WHERE proid='$proid' AND "
                     ."developer='$username' AND "
                     ."content_id='$content_id'");
    if ($db_local->num_rows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * Depending on the views specified, access to certain types of
 * users is enabled or not
 * 
 * @param   int     $proid             Project identifier
 * @param   string  $page
 *
 * @global  object  $auth              PHPLib authentication object
 * @return  boolean
 * @access  public
 */
function security_accept_by_view($proid, $page)
{
    global $auth;

    if ($page=="news_edit" || $page=="news_mod") {
        $page="news";
    }
    if ($page=="comments_edit") {
        $page="comments";
    }

    // FIXME: will cause a parse error or database error if the
    // FIXME: page variable isn't a column defined in the table 'views'
    // FIXME: Any idea of how I can solve this problem?
    // FIXME: making an array and looking if $page is in the array?
    // FIXME:    --> but that will make configuration harder as adding
    // FIXME:        something means to change the SQL and the config.inc file
    // FIXME: ====> then do it! <====
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT $page FROM views WHERE proid='$proid'");
    $db_local->next_record();

    switch ($db_local->f($page)) {
    case "Everybody":
        return 1;
    case "Registered":
        if (isset ($auth) && isset($auth->auth["uname"]) 
           && !empty($auth->auth["uname"]) 
           && $auth->auth["perm"]!="devel_pending" 
           && $auth->auth["perm"]!="sponsor_pending") {
            return 1;
        }
        // FIXME: should there be a 'break' here?
    case "Developers":
        if (is_developer() || is_accepted_sponsor($proid)) {
            return 1;
	} 
        break;
    case "Sponsors":  
        if (is_sponsor() || is_involved_developer($proid)) {
            return 1;
	} 
        break;
    case "Project Participants": 
        if (is_involved_developer($proid) 
            || is_accepted_sponsor($proid) 
            || is_project_initiator($proid)) {
            return 1;
        } 
        break;
    case "Project Developers":
        if (is_involved_developer($proid)) {
            return 1;
	} 
        break;
    case "Project Sponsors":
        if (is_accepted_sponsor($proid)) {
            return 1;
	} 
        break;
    case "Project Initiator":
        if (is_project_initiator($proid)) {
            return 1;
        } 
        break;
    default:
    }
    return 0;
}

/**
 * Returns current iteration from the process in step 5 (project follow-up)
 * 
 * @param   int     $proid             Project identifier
 * @return  int                        Iteration number (0 if error)
 * @access  public
 */
function step5_iteration($proid)
{
    $current_iteration = 0;

    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT milestone_number,iteration FROM "
                     ."follow_up WHERE proid='$proid'");
    if ($db_local->num_rows() == 0) { 
        return 0;
    } else {
        while ($db_local->next_record()) {
            if ( $db_local->f("milestone_number") > 0 ) {
                $current_iteration = $db_local->f("iteration");
            }
        }
        return $current_iteration;
    }
}

/**
 * Returns the error message "not your turn" for the iteration in step5 (project follow-up)
 * (to make navigation through the project easier, the top bar is also printed)
 * 
 * @param   int     $proid             Project identifier
 * @param   string  $page              Page to be accessed
 * @return  constant                   0 (FALSE)
 * @access  public
 */
function step5_not_your_iteration($proid, $page)
{
    global $t;

    top_bar($proid,$page);
    // WISH: config.inc should not be required another time, as it 
    // WISH: is done at the beginning of every page.
    require('config.inc');

    $be = new box('',$th_box_frame_color,$th_box_frame_width,
                  $th_box_title_bgcolor,$th_box_title_font_color,
                  $th_box_title_align,$th_box_body_bgcolor,
                  $th_box_error_font_color,$th_box_body_align);

    switch (step5_iteration($proid)) {
        case '0':
            $message = 'The milestone has not been posted by the developer';
            break;
        case '1':
            $message = 'The milestone has been posted. Sponsors are '
                 .'studying whether to accept it or not.';
            break;
        case '2':
            $message = 'Sponsors have rejected the current milestone. '
                 .'The referee is studying it.';
            break;
        case '3':
            $message = 'The referee has decided that the milestone '
                 .'posted by the developer does not fulfill the promised '
                 .'goals. Sponsors are deciding what is going to happen '
                 .'to the project';
            break;
        // FIXME: case 4 is missing ....?????
        case '5':
            $message = 'The follow_up process is finished';
            break;
        default:
            $message = 'Unknown iteration';
    }
                
    $be->box_full($t->translate('Not your turn'), $t->translate($message));
    return 0;
}

/**
 * Returns if it is the visitors milestone
 * 
 * @param   int     $proid             Project identifier
 * @global  object  $auth              PHPLib authetication object
 * @return  boolean
 * @access  public
 */
function is_your_milestone($proid)
{
    global $auth;

    if (isset($auth) && !empty($auth->auth["perm"])) { 
        $db_local = new DB_SourceAgency;
        $db_local->query("SELECT * FROM developing WHERE proid='$proid' "
                         ."AND developer='".$auth->auth["uname"]."'");
        if ($db_local->num_rows() > 0) { 
            return 1;
	}
    }
    return 0;
}

/**
 * Returns if another milestone is possible
 * this only happens if the summed percentage of all the other milestones is less than 100%
 * 
 * @param   int     $proid             Project identifier
 * @global  object  $auth              PHPLib authetication object
 * @return  boolean
 * @access  public
 */
function is_milestone_possible($proid)
{
    global $auth;

    if ( isset( $auth ) ) {
        $db_local = new DB_SourceAgency;

        $db_local->query("SELECT SUM(payment) FROM milestones,developing "
                         ."WHERE developing.proid='$proid' AND "
                         ."milestones.devid=developing.devid AND "
                         ."developer='".$auth->auth["uname"]."'");

        $db_local->next_record();
        
        if ($db_local->f("SUM(payment)") < 100) {
            return 1;
        } else {
            return 0;
        }
    }
    return 0;
}

/**
 * Returns the error box when somebody tries to add a milestone
 * and the summed percentage of the rest of milestones is higher than 100%
 * (also prints the top bar to facilitate project navigation)
 * 
 * @param   int     $proid             Project identifier
 * @param   string  $page              Page to be accessed
 * @return  constant                   0 (FALSE)
 * @access  public
 */
function milestone_not_possible($proid, $page)
{
    global $t;
    top_bar($proid, $page);

    // WISH: config.inc should not be required another time, 
    // as it is done at the beginning of every page.
    require('config.inc');

    $be = new box('',$th_box_frame_color,$th_box_frame_width,
                  $th_box_title_bgcolor,$th_box_title_font_color,
                  $th_box_title_align,$th_box_body_bgcolor,
                  $th_box_error_font_color,$th_box_body_align);

    $be->box_full($t->translate('Milestone not possible'), 
                  $t->translate( 'Your milestones already '
                                 .'reach 100%. You should modify your '
                                 .'existing milestones before creating '
                                 .'a new one.'));
    return 0;     
}

/**
 * Returns if other specifications are allowed to the project given by $proid
 * 
 * @param   int     $proid             Project identifier
 * @return  boolean
 * @access  public
 */
function other_specifications_allowed($proid)
{
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT other_tech_contents FROM configure WHERE proid='$proid'");
    $db_local->next_record();
    if ($db_local->f("other_tech_contents") == "Yes") {
        return 1;
    } else {
        return 0;
    }
}

/**
 * Returns if other proposals are allowed to the project given by $proid
 * 
 * @param   int     $proid             Project identifier
 * @return  boolean
 * @access  public
 */
function other_developing_proposals_allowed($proid)
{
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT other_developing_proposals FROM configure WHERE proid='$proid'");
    $db_local->next_record();
    if ($db_local->f("other_developing_proposals") == "Yes") {
        return 1;
    } else {
        return 0;
    }
}

/**
 * Returns True if there are no other specifications yet
 * False if there are
 * 
 * @param   int     $proid             Project identifier
 * @return  boolean
 * @access  public
 */
function no_other_specification_yet($proid)
{
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM tech_content WHERE proid='$proid'");
    if ($db_local->num_rows() == 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * Returns True if there are no other proposals yet
 * False if there are
 * 
 * @param   int     $proid             Project identifier
 * @return  boolean
 * @access  public
 */
function no_other_proposal_yet($proid)
{
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM developing WHERE proid='$proid'");
    if ($db_local->num_rows() == 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * Returns if the visitor is the first sponsor or developer of the project
 * 
 * @param   int     $proid             Project identifier
 * @global  object  $auth              PHPLib authentication object
 * @return  boolean
 * @access  public
 */
function is_first_sponsor_or_dev($proid)
{
    global $auth;

    if (is_main_developer($proid)) {
        return 1;
    } else {
        if (is_accepted_sponsor($proid)) {
            $db_local = new DB_SourceAgency;
            $db_local->query("SELECT * FROM configure WHERE sponsor='".$auth->auth["uname"]."'");
            if ($db_local->num_rows() > 0) {
                return 1;
            } else { 
                return 0;
            }
        } else {
  	    return 0;
        }
    }
}
?>