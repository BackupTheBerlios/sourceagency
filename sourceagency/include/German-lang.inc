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
# This file shows a bar with a title at the top of the pages
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: German-lang.inc,v 1.3 2001/11/08 14:19:21 riessen Exp $
#
######################################################################

switch ( $phrase ) {

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

    default: 
        $tmp = "$phrase"; 
        $l = new Logger;
        $l->log( "translating (German unknown) '" . $phrase . "'" );
        break;
}
?>
