<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org)
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Contains the English(sic) translation of the SourceAgency portal.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: English-lang.inc,v 1.16 2002/04/17 14:09:40 riessen Exp $
#
######################################################################

if ( function_exists( "translate_real" ) ) {
  return;
}
// Ins Englischen muessen wir nicht uebersetzen!
// (es ist ja alles auf English bis jetzt!)
function translate_real($phrase) {
    switch($phrase) {
    // Languages
    case "English": $tmp = "English"; break;
    case "German": $tmp = "Deutsch"; break;
    case "Spanish": $tmp = "Espa&ntilde;ol"; break;
    case "French": $tmp = "Fran&ccedil;ais"; break;
    
    /** these are various page titles **/
    case "comments_edit":       $tmp = "Edit Comments";       break;
    case "comments_mod":        $tmp = "Modify Comments";     break;
    case "consultants":         $tmp = "Consultants";         break;
    case "summary":             $tmp = "Summary";             break;
    case "configure_edit":      $tmp = "Edit Configuration";  break;
    case "content suggestions": $tmp = "Content Suggestions"; break;

    /** explanations for the various steps a project has **/
    case "step_1_explanation": 
        $tmp = "Help for sponsors on making a proper project submission."; 
        break;
    case "step_2_explanation": 
        $tmp =  "This project may have different specifications. "
               ."Developers can make their developing proposals to "
               ."that specifications.";
        break;
    case "step_3_explanation": 
        $tmp = "Project milestones proposed by developers.";
        break;
    case "step_4_explanation": 
        $tmp = "Developers can propose themselves as referees. Sponsors "
              ."and developers will elect one of them.";
        break;
    case "step_5_explanation": 
        $tmp = "Milestone delivery and other tasks related to the "
              ."project follow-up.";
        break;
    case "step_6_explanation":
        $tmp = "Final rating between sponsors and developers.";
        break;

    /** Documentation for pages. *-page-name are the one line 
        names of a page, *-doco is the documentation for that
        page **/
    case "index-page-name":
        $tmp = "SourceAgency: Front Page";
        break;
    case "index-doco":
        $tmp = ("SourceAgency is a Open Source project exchange. It is the "
                ."place where developers meet sponsors, and sponsors can "
                ."describe the software requirements.<p>" 
                ."Two nature-types of projects are available: developing and "
                ."sponsoring.<ul><li>Developing are those projects that are "
                ."being developed by developers and are looking for "
                ."finanical sponsorship."
                ."<li>Sponsoring projects are ones where sponsor(s) are "
                ."looking for a developer group to develop a needed "
                ."solution for them.</ul><p>" 
                ."All projects developed using the SourceAgency/Berlios "
                ."platform are Open Source projects and use Open Source "
                ."Licenses." );
        break;

    case "faq-page-name":
        $tmp = "Frequently Asked Questions";
        break;
    case "faq-doco":
        $tmp = ( "The FAQ page provides answers to some common questions.<p>"
                 ."Questions can be asked by sending the SourceAgency "
                 ."developers <a href=\"mailto:sourceagency-support@"
                 ."lists.berlios.de?subject=FAQ Question\">feedback</a>!");
        break;
    case "login-page-name":
        $tmp = ("Login Page");
        break;
    case "login-doco":
        $tmp = ("Here registered users may login. If you are not a registered "
                ."user, then you can register <a href=\"register.php3\">here"
                ."</a>");
        break;
    case "doco-page-name":
        $tmp = ("Documentation Page");
        break;
    case "doco-doco":
        $tmp = ("Page specific documentation is generated using this page. "
                ."Each pages documentation can be accessed using the \"What "
                ."is this\" link on the left hand side menubar.");
        break;
    case "users-page-name":
        $tmp = ("User Listing");
        break;
    case "users-doco":
        $tmp = ("Listing of the different types of registered users. This "
                ."allows developers to directly contact sponsors and sponsors "
                ."to directly contact developers.<p> Three listings "
                . "available:<ul>"
                ."<li><a href=\"users.php3\">All Users</a>"
                ."<li><a href=\"users.php3?type=devel\">All Developers</a>"
                ."<li><a href=\"users.php3?type=sponsor\">All Sponsors</a>"
                ."</ul>");
        break;
    case "browse-page-name":
        $tmp = ("Project Browsing");
        break;
    case "browse-doco":
        $tmp = ("All currently registered and accepted projects can be "
                ."browsed based on several different categories based on "
                ."the projects configuration.<p>" 
                ."Projects matching a category are listed below the category "
                ."listing.");
        break;
    case "licenses-page-name":
        $tmp = ("Open Source License Listing");
        break;
    case "licenses-doco":
        $tmp = ("Provides a listing of all accepted Open Source licenses. "
                ."Each project must choice a license for each Technical "
                ."Content made to a project. <p>"
                ."As SourceAgency only provides support for the development "
                ."of Open Source Software, therefore all licenses are Open "
                ."Source licenses approved by the <a href=\"http://opensource."
                ."org\">Open Source Initiative (OSI)</a>.");
        break;
    case "insform-page-name":
        $tmp = ("Project Register Form");
        break;
    case "insform-doco":
        $tmp = ("Here registered user may enter new project descriptions.<p>"
                ."The nature of the project is defined by the type of user: "
                ."developer enter descriptions of developing projects, while "
                ."sponsors enter descriptions of sponsoring projects." );
        break;
    case "remind-page-name":
        $tmp = ("Password Reminder");
        break;
    case "remind-doco":
        $tmp = ("Registered user may have their forgotten password sent to "
                ."their email address.");
        break;
    case "chguser-page-name":
        $tmp = ("Change User Information");
        break;
    case "chguser-doco":
        $tmp = ("Registered users may change their personal information "
                ."using this page.");
        break;
//      case "-page-name":
//          $tmp = ("");
//          break;
//      case "-doco":
//          $tmp = ("");
//          break;
//      case "-page-name":
//          $tmp = ("");
//          break;
//      case "-doco":
//          $tmp = ("");
//          break;
//      case "-page-name":
//          $tmp = ("");
//          break;
//      case "-doco":
//          $tmp = ("");
//          break;
//      case "-page-name":
//          $tmp = ("");
//          break;
//      case "-doco":
//          $tmp = ("");
//          break;
//      case "-page-name":
//          $tmp = ("");
//          break;
//      case "-doco":
//          $tmp = ("");
//          break;
    /****************** Everything else is default ***************/
    default:   
        $tmp = "$phrase";  
        break;
    }
    return($tmp);
}
?>
