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
# This contains the German translation of the SourceAgency portal.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: German-lang.inc,v 1.8 2002/04/02 12:43:44 grex Exp $
#
######################################################################

function translate_real($phrase) {
    switch ( $phrase ) {

    // Languages
    case "English": $tmp = "English"; break;
    case "German": $tmp = "Deutsch"; break;
    case "Spanish": $tmp = "Espa&ntilde;ol"; break;
    case "French": $tmp = "Fran&ccedil;ais"; break;

    case "Backend": $tmp = "Backend"; break;
    case "Browse": $tmp = "Browse"; break;
    case "Categories": $tmp = "Categories"; break;
    case "License description is not available": 
      $tmp = "License description is not available"; break;
    case "Developing cooperants Wanted": 
      $tmp = "Developing cooperants Wanted"; break;
    case "Open Source Software Projects": 
      $tmp = "Open Source Software Projects"; break;
    case "Referees Wanted": $tmp = "Referees Wanted"; break;
    case "System Credits": $tmp = "System Credits"; break;

    case "About us": $tmp = "&Uuml;ber uns"; break;
    case "Consultants Wanted": $tmp = "Berater Gesucht"; break;
    case "Contact": $tmp = "Verbindung"; break;
    case "Developers": $tmp = "Entwickler"; break;
    case "Developers Wanted": $tmp = "Entwickler Gesucht"; break;
    case "English": $tmp = "Englisch"; break;
    case "Error": $tmp = "Fehler"; break;
    case "Finished projects": $tmp = "Beendet Projekte"; break;
    case "Forgot Password": $tmp = "Kennwort Vergessen"; break;
    case "Go": $tmp = "Los"; break;
    case "Home": $tmp = "Ausgangpunkt"; break;
    case "Index": $tmp = "Index"; break;
    case "Language": $tmp = "Sprache"; break;
    case "License": $tmp = "Lizenz"; break;
    case "Licenses": $tmp = "Lizenzen"; break;
    case "Login": $tmp = "Einloggen"; break;
    case "New Project": $tmp = "Neues Projekt"; break;
    case "New Projects": $tmp = "Neue Projekte"; break;
    case "New User": $tmp = "Neuer Benutzer"; break;
    case "News": $tmp = "Nachrichten"; break;
    case "Newsletter": $tmp = "Mitteilungsblatt"; break;
    case "No": $tmp = "Nein"; break;
    case "Not Logged in": $tmp = "Nicht eingeloggt"; break;
    case "Partners": $tmp = "PartnerInnen"; break;
    case "Projects": $tmp = "Projekten"; break;
    case "Q&amp;A": $tmp = "F&amp;A"; break;
    case "Search": $tmp = "Suche"; break;
    case "Sponsors": $tmp = "Sponsoren"; break;
    case "Sponsors Wanted": $tmp = "Sponsoren Gesucht"; break;
    case "Users": $tmp = "Benutzter"; break;

    /** explanations for the various steps a project has **/
    case "step_1_explanation": {
        $tmp = "Help for sponsors on making a propper project submission."; 
        break;
    }
    case "step_2_explanation": {
        $tmp = ( "This project may have different specifications. "
                 ."Developers can make their developing proposals to "
                 ."that specifications." );
        break;
    }
    case "step_3_explanation": {
        $tmp = "Project milestones proposed by developers.";
        break;
    }
    case "step_4_explanation": {
        $tmp = ( "Developers can propose themselves as referees. Sponsors "
                 ."and developers will elect one of them." );
        break;
    }
    case "step_5_explanation": {
        $tmp = ( "Milestone delivery and other tasks related to the "
                 ."project follow-up." );
        break;
    }
    case "step_6_explanation": {
        $tmp = "Final rating between sponsors and developers.";
        break;
    }

    default: 
        $tmp = "$phrase"; 
        $l = new Logger;
        $l->log( "translating (German unknown) '" . $phrase . "'" );
        break;
    }
    return($tmp);
}
?>
