<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
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
# Library with the functions needed during the follow-up process (step 5)
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: followuplib.inc,v 1.11 2002/05/13 10:23:48 riessen Exp $
#
######################################################################

/**
 * This library contains functionality needed during the follow-up
 * process (step5)
 *
 * Decisions taken by sponsors in this step are kept apart 
 * in the decisionlib.inc library
 *
 * This step handles data from the 'follow_up' table in the database
 * Table database contains following data:
 *
 * proid bigint(20) unsigned DEFAULT '0' NOT NULL:
 *      Project Id (Foreign Key)
 * milestone_number int(8) unsigned NOT NULL:
 *      Number of the current milestone
 * iteration int(8) DEFAULT '1' NOT NULL:
 *      Iteration process number. Actions that can be taken depend on the iteration
 *         Step 1: milestone has to be released by the main developer
 *         Step 2: milestone can be accepted or rejected by the referee
 *         Step 3: milestone can be accepted or rejected by the sponsors
 *         Step 4: mileston has been rejected by sponsors. Referees have to decide
 *                 whether there is a a severe or light modification to be done
 *         Step 5: milestone has been delivered succesfully (accepted by sponsors)
 *                 --> next milestone delivery can happen or
 *                 --> if it was the last milestone, step 5 is over
 * location varchar(255) NOT NULL:
 *         URL where the release can be found
 *         Milestones are saved outside the system
 * count int(3) DEFAULT '1' NOT NULL:
 *         Number of times this milestone has been released
 *         when this happens serveral times, sponsors have the right to stop the project
 *
 * @author  Gregorio Robles <grex@scouts-es.org>
 * @author  Gerrit Riessen <Gerrit.Riessen@open-source-consultants.de>
 * @access  public
 * @see html, lib, translate, box, auth
 */


/**
 * Preview of a milestone submitted by a developer
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 * @global  object    $auth          PHPLib authentication object
 *
 * @global  int       $milestone_number   Milestone number of the current milestone
 * @global  string    $location           URL where the release can be found
 * @global  int       $count              Number of times this milestone has been submitted
 *
 * @access  public
 * @see followup_devel_form()
 */

function followup_devel_preview($proid)
{
    global $t, $db, $bx, $auth, $milestone_number, $location, $count;

    $db->query("SELECT * FROM milestones WHERE proid='$proid' "
               ."AND number='$milestone_number'");
    $db->next_record();

    $bx->box_begin();
    $bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
    $bx->box_title($t->translate("Milestone Delivery"));
    $bx->box_body_begin();
    $timestamp = time();
    echo "<b>";
    lib_pnick($auth->auth["uname"]);
    echo " - ".timestr($timestamp)."</b>\n";

    $bx->box_columns_begin(2);

    $bx->box_column("right","30%","","<b>"
                    .$t->translate("Milestone number").":</b>");
    $bx->box_column("left","70%","",$milestone_number);

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Goals").":</b>");
    $bx->box_column("left","70%","",$db->f("goals"));

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>"
                    .$t->translate("Release Date").":</b>");
    $bx->box_column("left","70%","",
                    timestr_middle(mktimestamp($db->f("release"))));

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Product").":</b>");
    $bx->box_column("left","70%","",$db->f("product"));

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Payment").":</b>");
    $bx->box_column("left","70%","",$db->f("payment"));

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Location").":</b>");
    $bx->box_column("left","70%","",html_link($location,array(),$location));

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Count").":</b>");
    $bx->box_column("left","70%","",$count);

    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Preview of a decision taken by a referee on a milestone
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 * @global  object    $auth          PHPLib authentication object
 *
 * @global  int       $milestone_number   Milestone number of the current milestone
 * @global  string    $location           URL where the release can be found
 * @global  int       $count              Number of times this milestone has been submitted
 * @global  string    $decision           Referee decision
 *
 * @access  public
 * @see followup_referee_form()
 */

function followup_referee_preview($proid)
{
    global $t, $bx, $db, $auth, $milestone_number, 
      $location, $count, $decision;

    $db->query("SELECT * FROM milestones WHERE proid='$proid' "
               ."AND number='$milestone_number'");
    $db->next_record();

    $bx->box_begin();
    $bx->box_title("<center><b>".$t->translate("PREVIEW")."</b></center>");
    $bx->box_title($t->translate("Milestone Delivery - Referee Decision"));
    $bx->box_body_begin();
    $timestamp = time();
    echo "<b>";
    lib_pnick($auth->auth["uname"]);
    echo " - ".timestr($timestamp)."</b>\n";

    $bx->box_columns_begin(2);

    $bx->box_column("right","30%","","<b>".
                    $t->translate("Milestone number").":</b>");
    $bx->box_column("left","70%","",$milestone_number);

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Goals").":</b>");
    $bx->box_column("left","70%","",$db->f("goals"));

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".
                    $t->translate("Release Date").":</b>");
    $bx->box_column("left","70%","",
                    timestr_middle(mktimestamp($db->f("release"))));

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Product").":</b>");
    $bx->box_column("left","70%","",$db->f("product"));

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Payment").":</b>");
    $bx->box_column("left","70%","",$db->f("payment"));

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Location").":</b>");
    $bx->box_column("left","70%","",html_link($location,array(),$location));

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Count").":</b>");
    $bx->box_column("left","70%","",$count);

    $bx->box_next_row_of_columns();

    $bx->box_column("right","30%","","<b>".$t->translate("Decision").":</b>");
    $bx->box_column("left","70%","",$decision);

    $bx->box_columns_end();
    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Form to submit a milestone URL by a developer
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 *
 * @global  int       $milestone_number   Milestone number of the current milestone
 * @global  string    $location           URL where the release can be found
 * @global  int       $count              Number of times this milestone has been submitted
 *
 * @access  public
 * @see followup_devel_preview()
 */

function followup_devel_form($proid)
{
    global $bx, $db, $t, $milestone_number, $location, $count;

    /* ASSUME: there is a single record of information */
    /* (this is controlled at the time records are inserted/updated in database */
    $db->query("SELECT * FROM milestones WHERE proid='$proid' AND number='$milestone_number'");
    $db->next_record();

    $bx->box_begin();
    $bx->box_title($t->translate("Milestone Delivery"));
    $bx->box_body_begin();
    htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

    $bx->box_columns_begin(2);

    $bx->box_column ("right","30%","","<b>".$t->translate("Milestone Number")."</b>: ");
    $bx->box_column ("left","70%","",$milestone_number);

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Goals")."</b>: ");
    $bx->box_column ("left","70%","",$db->f("goals"));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Release date")."</b>: ");
    $bx->box_column ("left","70%","",timestr_middle(mktimestamp($db->f("release"))));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Product")."</b>: ");
    $bx->box_column ("left","70%","",$db->f("product"));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Payment")."</b>: ");
    $bx->box_column ("left","70%","",$db->f("payment")."%");

    $bx->box_next_row_of_columns();
 
    $bx->box_colspan ("2","","white","&nbsp;");

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Location")."</b> (255): ");
    $bx->box_column ("left","70%","",html_input_text("location",40,255,$location));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Count")."</b>: ");
    $bx->box_column ("left","70%","",$count." <i>(this is the number of times you have submitted this milestone)</i>");
  
    $bx->box_next_row_of_columns();

    $bx->box_colspan ("2","","white","&nbsp;");

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","&nbsp;");
    $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Form to submit a decision on a milestone by a referee
 *
 * @param   int       $proid         Project id
 *
 * @global  object    $t             Translation object
 * @global  object    $bx            Box object
 * @global  object    $db            PHPLib database object
 *
 * @global  int       $milestone_number   Milestone number of the current milestone
 * @global  string    $location           URL where the release can be found
 * @global  int       $count              Number of times this milestone has been submitted
 * @global  string    $decision           Referee decision
 *
 * @access  public
 * @see followup_referee_preview()
 */

function followup_referee_form($proid)
{
    global $bx, $t, $db, $milestone_number, $location, $count, $decision;

    /* ASSUME: there is a single record of information */
    /* (this is controlled at the time records are inserted/updated in database */
    $db->query("SELECT * FROM milestones WHERE proid='$proid' AND number='$milestone_number'");
    $db->next_record();

    $bx->box_begin();
    $bx->box_title($t->translate("Milestone Delivery - Referee Decision"));
    $bx->box_body_begin();
    htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

    $bx->box_columns_begin(2);

    $bx->box_column ("right","30%","","<b>".$t->translate("Milestone Number")."</b>: ");
    $bx->box_column ("left","70%","",$milestone_number);

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Goals")."</b>: ");
    $bx->box_column ("left","70%","",$db->f("goals"));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Release date")."</b>: ");
    $bx->box_column ("left","70%","",timestr_middle(mktimestamp($db->f("release"))));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Product")."</b>: ");
    $bx->box_column ("left","70%","",$db->f("product"));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Payment")."</b>: ");
    $bx->box_column ("left","70%","",$db->f("payment")."%");

    $bx->box_next_row_of_columns();

    $bx->box_colspan ("2","","white","&nbsp;");

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Location")."</b>: ");
    $bx->box_column ("left","70%","",html_link($location,array(),$location));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Count")."</b>: ");
    $bx->box_column ("left","70%","",$count);

    $bx->box_next_row_of_columns();

    $bx->box_colspan ("2","","white","&nbsp;");

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","<b>".$t->translate("Decision")."</b>: ");
    $bx->box_column ("left","70%","",followup_referee_decision($decision));

    $bx->box_next_row_of_columns();

    $bx->box_column ("right","30%","","&nbsp;");
    $bx->box_column ("left","70%","",html_form_submit("Preview","preview").html_form_submit("Submit","submit"));
    htmlp_form_end();
    $bx->box_columns_end();

    $bx->box_body_end();
    $bx->box_end();
}

/**
 * Gets the milestone that has to be submitted
 * (this is always the milestone with greatest milestone number in database)
 *
 * @param   int       $proid         Project id
 * @return  int                      milestone number or step 5 is finished (-1) 
 *                                   if no milestone number is found for this project id
 *                                   in the database, then it is assumed that we're
 *                                   dealing with the first one
 *
 * @access  public
 * @see followup_current_count()
 */

function followup_current_milestone($proid)
{
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT * FROM follow_up WHERE proid='$proid' ORDER BY milestone_number DESC");
    if ($db_local->num_rows() == 0) {
        /* if no milestone exists in the database, then we're dealing with the first one */
        return 1;
    }
    $db_local->next_record();
    $temp = $db_local->f("milestone_number");
    if ($db_local->f("iteration") == 0) {
        $db_local->query("SELECT MAX(number) FROM milestones WHERE proid='$proid'");
        $db_local->next_record();
        if ($db_local->f("MAX(number)") < $temp) {
            $temp = -1;     // No more milestones!!! Step 5 has finished!
        }
    }
    return $temp;
}

/**
 * Gets the number of times the current milestone is being submitted
 * (this means that it returns the number of times submitted + 1 (the current time))
 *
 * @param   int       $proid         Project id
 * @param   int       $milestone_number     Current milestone
 *                                          from followup_current_milestone()
 *
 * @return  int                      Returns the number of times milestone is being submitted
 *                                   TODO: returns 0 in case of error.
 *
 * @access  public
 * @see followup_current_milestone()
 */

function followup_current_count($proid, $milestone_number)
{
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT count FROM follow_up WHERE milestone_number='$milestone_number' AND proid='$proid'");
    // TODO: why do we return 1 here instead of 0? if we returned 1 we
    // TODO: could use this function to test whether a project has any
    // TODO: milestones.
    if ($db_local->num_rows() == 0) {
        return 1;
    }
    $db_local->next_record();
    return $db_local->f("count");
}

/**
 * Gets the URL where the milestone has been put
 *
 * @param   int       $proid         Project id
 * @param   int       $milestone_number     Current milestone
 *                                          from followup_current_milestone()
 * @param   int       $count         Number of times the current milestone has been submitted
 *                                   from followup_current_count()
 *
 * @return  string                   Returns the URL of the milestone
 *                                   or 0 if error (no record)
 *
 * @access  public
 * @see followup_current_milestone(), followup_current_count()
 */

function followup_location($proid, $milestone_number, $count)
{
    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT location FROM follow_up WHERE milestone_number='$milestone_number' AND count='$count' AND proid='$proid'");
    if ($db_local->num_rows() == 0) {
        return 0;
    }
    $db_local->next_record();
    return $db_local->f("location");
}

/**
 * Database Insertion/Update for a follow-up process 
 *
 * @param   int       $proid         Project id
 * @param   int       $milestone_number     Current milestone
 *                                          from followup_current_milestone()
 * @param   int       $iteration     number that gives the status of the follow-up process
 *                                   within the current milestone
 * @param   string    $location      URL
 * @param   int       $count         Number of times the current milestone has been submitted
 *                                   from followup_current_count()
 *
 * @global  object    $db            PHPLib database object
 *
 * @return  boolean                  TODO: should return if the insertion succeeded
 * @access  public
 */

function followup_insert($proid, $milestone_number, $iteration, $location, $count)
{
    global $db;

    if ($iteration == 0 || ($milestone_number == 1 && $iteration == 1)) {
        /* New entry when there's a new process starting for the next milestone */
        /* Or it is just the first iteration of the first milestone */
        $db->query("INSERT follow_up SET proid='$proid',milestone_number='$milestone_number',iteration='$iteration',location='$location',count='$count'");
    } else {
	$db->query("UPDATE follow_up SET iteration='$iteration', location='$location', count='$count' WHERE proid='$proid' AND milestone_number='$milestone_number'");
    }
}

/**
 * Select box with the decisions a referee can make
 * (accept, minor, major)
 *
 * @param   string    $selected   Default value for the select box
 * @return  string                HTML select box with the different possibilities
 * @access  public
 */

function followup_referee_decision($selected)
{
    $return = html_select("decision");
    $return .= html_select_option("accept",$selected=="accept","accept");
    $return .= html_select_option("minor",$selected=="minor","minor");
    $return .= html_select_option("severe",$selected=="severe","severe");
    $return .=html_select_end();

    return($return);
}

/**
 * Prints the current status of the milestone schedule plan
 *
 * @param   int       $proid         Project id
 * @param   int       $milestone_number     Current milestone
 *                                          from followup_current_milestone()
 * @param   int       $count         Number of times the current milestone has been submitted
 *                                   from followup_current_count()
 *
 * @global  object    $db            PHPLib database object
 * @global  object    $bx            Box object
 *
 * @access  public
 */

function followup_milestone_schedule($proid, $milestone_number, $count)
{
    global $db, $bx;

    $db->query("SELECT release FROM milestones WHERE number='$milestone_number' AND proid='$proid'");
    $db->next_record();

    $release_date = mktimestamp($db->f("release"));
    $current_time = time();

    $days_left = ($release_date - $current_time)/(3600 * 24);

    $bx->box_begin();
    $bx->box_title("Current Milestone Schedule Status");
    $bx->box_body_begin();

    switch($count) {
    case '1':
        break;
    default:
        print "<p>Sponsors have deciced that milestone number <b>$milestone_number</b> requires minor modifications in order to achieve their requirements.";
        break;
    }

    switch (_current_iteration($proid)) {
    case '0':
        print "<p>The milestone has not been posted by the main developer";

        if ($days_left > 1) {
            print "<p>Number of days left until <b>milestone number $milestone_number</b> is released: <b>".floor($days_left)." days</b>.<p>";
        } elseif ($days_left < -0.5) {
             print "<p>Milestone number <b>$milestone_number</b> should have been released <b>".ceil(-$days_left)."</b> days ago.<p>";
        } else {
            print "<p>Milestone number <b>$milestone_number</b> should be released <b>today</b>.<p>";
        }
	if(is_main_developer($proid)) {
            print "You are the main developer. You can post the milestone ".html_link("step5_devel.php3",array("proid"=>$proid),"in this page");
        }
	break;
    case '1':
        print "<p>The milestone number <b>$milestone_number</b> has been posted. Sponsors are studying whether to accept it or not.";
	if(is_accepted_sponsor($proid)) {
            print "<p>You are an accepted sponsor for this project. You can decide whether the milestone fullfil its goals or not ".html_link("step5_sponsor.php3",array("proid"=>$proid),"in this page");
        }
        break;
    case '2':
        print "<p>Sponsors have rejected the current milestone (milestone number <b>$milestone_number</b>).\n";
        print "<p>The referee is studying it and will give a final decision soon.\n";
	if(is_referee($proid)) {
            print "<p>You are this project´s referee. You can decide whether the milestone fullfil its goals or not ".html_link("step5_referee.php3",array("proid"=>$proid),"in this page");
        }
        break;
    case '3':
        print "The referee has decided that the milestone number <b>$milestone_number</b> posted by the developer does not fulfill the promised goals.\n";
	print "<p>Sponsors are deciding what is going to happen to the project.\n";
	if(is_accepted_sponsor($proid)) {
            print "<p>You are an accepted sponsor for this project. You can decide what will happen to this project ".html_link("step5_sponsor.php3",array("proid"=>$proid),"in this page");
        }
        break;
     case '5':
        print "The follow_up process is finished. Now it is time for rating.";
        break;
    }

    $bx->box_body_end();
    $bx->box_end();

}

/**
 * Gets the current iteration of the project's milestone delivery schedule
 *
 * @param   int       $proid         Project id
 * @return  int                      Returns the current iteration or 0 if error
 * @access  private
 */

function _current_iteration($proid)
{
    $current_milestone = 0;
    $current_iteration = 0;

    $db_local = new DB_SourceAgency;
    $db_local->query("SELECT milestone_number,iteration FROM follow_up WHERE proid='$proid'");
    if ($db_local->num_rows() == 0) {
        return 0;
    } else {
	while ($db_local->next_record()) {
	    if ($current_milestone < $db_local->f("milestone_number")) {
                $current_iteration = $db_local->f("iteration");
	    }
	}
    }
    return $current_iteration;
}
?>