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
# $Id: English-lang.inc,v 1.14 2002/04/02 15:04:22 riessen Exp $
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
    default:   $tmp = "$phrase";  break;
    }
    return($tmp);
}
?>
