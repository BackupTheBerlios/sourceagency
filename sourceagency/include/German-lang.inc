<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org) and
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
# $Id: German-lang.inc,v 1.19 2002/05/07 10:34:03 grex Exp $
#
######################################################################

if ( function_exists( "translate_real" ) ) {
  return;
}

function translate_real($phrase) {
    switch ( $phrase ) {

        case 'About us': $tmp = '&Uuml;ber uns'; break;
        case 'Action': $tmp = 'Aktion'; break;
        case 'Actions by involved users': $tmp = 'Actions by involved users'; break;
        case 'AdmDoco': $tmp = 'Dokumentationverwaltung'; break;
        case 'Administration': $tmp = 'Administration'; break;
        case 'All': $tmp = 'Alle'; break;
        case 'All Comments by': $tmp = 'Alle Kommentare von'; break;
        case 'All the rating on ': $tmp = 'Alle Bewertungen von'; break;
        case 'An error has ocurred': $tmp = 'Ein Fehler ist aufgetretten'; break;
        case 'Answer': $tmp = 'Anwort'; break;
        case 'Architecture': $tmp = 'Architektur'; break;
        case 'Backend': $tmp = 'Backend'; break;
        case 'Be informed': $tmp = 'Wird informiert'; break;
        case 'Brief description': $tmp = 'Kurze beschreibung'; break;
        case 'Browse': $tmp = 'Blätter'; break;
        case 'Categories': $tmp = 'Kategorien'; break;
        case 'Change User Info': $tmp = 'Benutzer Info. ändern'; break;
        case 'Change User Parameters': $tmp = 'Benutzer Parameter ändern'; break;
        case 'Change User': $tmp = 'Benutzer ändern'; break;
        case 'Change a License URL': $tmp = 'Lizenz URL ändern'; break;
        case 'Change': $tmp = 'Ändern'; break;
        case 'Comments': $tmp = 'Kommentare'; break;
        case "Configure the information access to the project's data": $tmp = "Information zugriffsrechte ändern für diese Projekt"; break;
        case 'Configure the project parameters': $tmp = 'Konfiguriere Parameter für das Projekt'; break;
        case 'Confirm Password': $tmp = 'Passwort bestätigen'; break;
        case 'Consultancy': $tmp = 'Beratung'; break;
        case 'Consultants Wanted': $tmp = 'Berater gesucht'; break;
        case 'Contact': $tmp = 'Kontakt'; break;
        case 'Create User': $tmp = 'Benutzer anlegen'; break;
        case 'Creation': $tmp = 'Anlegen'; break;
        case 'Current project budget': $tmp = 'Aktuelle Projekt Etat'; break;
        case 'Decisions on this step': $tmp = 'Entscheidungen für diesen Schritt'; break;
        case 'Delete a License': $tmp = 'Lizenz löschen'; break;
        case 'Delete': $tmp = 'Löschen'; break;
        case 'Description': $tmp = 'Beschreibung'; break;
        case 'Developer Cooperation Wanted': $tmp = 'Entwickler gesucht'; break;
        case 'Developers Wanted': $tmp = 'Entwickler gesucht'; break;
        case 'Developers': $tmp = 'Entwickler'; break;
        case 'Developing': $tmp = 'In Entwicklung'; break;
        case 'Developing Cooperation': $tmp = 'Entwicklungskooperation'; break;
        case 'E-Mail': $tmp = 'E-Mail'; break;
        case 'Either your Username or Password are invalid': $tmp = 'Entweder haben Sie den falschen Benutzernamen oder Passwort eingegeben'; break;
        case 'English': $tmp = 'Englisch'; break;
        case 'Enter a New Frequently Asked Question': $tmp = 'Neue Frage eingeben'; break;
        case 'Enter a new documentation entry for a page': $tmp = 'Neue Dokumentation für eine Seite'; break;
        case 'Environment': $tmp = 'Umgebung'; break;
        case 'Error': $tmp = 'Fehler'; break;
        case 'Expires': $tmp = 'Läuft ab'; break;
        case 'Finished projects': $tmp = 'Abgeschlossene Projekte'; break;
        case 'Forgot Password': $tmp = 'Passwort vergessen'; break;
        case 'French': $tmp = 'Französisch'; break;
        case 'Frequently Asked Questions Administration': $tmp = 'Administration der häufig gestellte Fragen'; break;
        case 'Frequently Asked Questions': $tmp = 'Häufig gestellte Fragen'; break;
        case 'General Actions (anytime and anywhere)': $tmp = 'Generelle Aktionen'; break;
        case 'General Comments': $tmp = 'Generelle Kommentare'; break;
        case 'General comments can be posted': $tmp = 'Generelle Kommentare können angegeben'; break;
        case 'German': $tmp = 'Deutsch'; break;
        case 'Global personal rating: ': $tmp = 'Globale persönliche Bewertung'; break;
        case 'Go': $tmp = 'Los'; break;
        case 'Hi': $tmp = $phrase; break;
        case 'Here you have to enter all the <b>project related</b> data. This should only give an idea to other people what you are planning.': $tmp = 'Bitte beschreiben Sie jetzt das Projekt. Die Information sollte andere leute über das Projekt Informieren'; break;
        case 'Home': $tmp = 'Home'; break;
        case 'If you are not yet a registered User, please select': $tmp = 'Falls Sie ein nicht bekannter Nutzer sind, bitte wählen'; break;
        case "In this page you'll find all the information related to you": $tmp = 'In dieser Seite werden sie alle Informationen die mit ihnen in Beziehung stehen finden'; break;
        case 'Index': $tmp = 'Index'; break;
        case 'Info box': $tmp = 'Information Kasten'; break;
        case 'Information access configuration': $tmp = 'Konfiguration von Information zugriffrechte'; break;
        case 'Insert a License': $tmp = 'Lizenz angeben'; break;
        case 'Insert': $tmp = 'Einfügen'; break;
        case 'Language': $tmp = 'Sprache'; break;
        case 'Last 10 Comments by': $tmp = 'Letzte 10 Kommentare von'; break;
        case 'Last 5 News by': $tmp = 'Letzte 5 Nueigkeiten von'; break;
        case 'Latest News': $tmp = 'Nueste Neuigkeiten'; break;
        case 'License Administration': $tmp = 'Lizenz Administration'; break;
        case 'License URL': $tmp = 'Lizenz URL'; break;
        case 'License description is not available': $tmp = 'Lizenzbeschreibung nicht verfügbar'; break;
        case 'License': $tmp = 'Lizenz'; break;
        case 'Licenses': $tmp = 'Lizenzen'; break;
        case 'Logged in': $tmp = 'Eingeloggt an'; break;
        case 'Login': $tmp = 'Einloggen'; break;
        case 'Logout': $tmp = 'Ausloggen'; break;
        case 'Milestones': $tmp = 'Meilensteine'; break;
        case 'Modification': $tmp = 'Änderung'; break;
        case 'Monitor This Project': $tmp = 'Projektüberwachung'; break;
        case 'Monitored Projects': $tmp = 'Überwachte Projekte'; break;
        case 'My Projects': $tmp = 'Meine Projekte'; break;
        case 'New Frequently Asked Question': $tmp = 'Neue Häufig gestellte Frage'; break;
        case 'New License Name': $tmp = 'Neuen Lizenzname'; break;
        case 'New License URL': $tmp = 'Neue Lizenz URL'; break;
        case 'New License': $tmp = 'Neue Lizenz'; break;
        case 'New Project': $tmp = 'Neues Projekt'; break;
        case 'New Projects': $tmp = 'Neue Projekte'; break;
        case 'New User': $tmp = 'Neuer Benutzer'; break;
        case 'News': $tmp = 'Nachrichten'; break;
        case 'Newsletter': $tmp = 'Newsletter'; break;
        case 'No consultant proposal with this status': $tmp = 'Kein Beraterangebot mit diesem Status'; break;
        case 'No consultants wanted': $tmp = 'Keine Berater erwünscht'; break;
        case 'No developing cooperation proposal with this status': $tmp = 'Kein Entwicklungsangebot mit diesem Status'; break;
        case 'No monitored projects': $tmp = 'Keine überwachte Projekte'; break;
        case 'No personal projects': $tmp = 'Keine persönliche Projekte'; break;
        case 'No rating yet': $tmp = 'Noch keine Bewertung'; break;
        case 'No': $tmp = 'Nein'; break;
        case 'No.': $tmp = 'Nr.'; break;
        case 'Not Logged in': $tmp = 'Nicht eingeloggt'; break;
        case 'Not rated yet': $tmp = 'Noch nicht bewertet'; break;
        case 'Not related to any project with': $tmp = 'Keine Relation mit Projekten mit'; break;
        case 'Open Source Project Mediation & Management': $tmp = 'Open Source Projekt Vermittlung & Leitung'; break;
        case 'Open Source Software Projects': $tmp = 'Open-Source-Software-Projekte'; break;
        case 'Our apologies. Documentation only available in English.': $tmp = 'Die Dokumentation ist leider nicht in Deutsch verfügbar.'; break;
        case 'Page Documentation Administration': $tmp = 'Seiten Dokumentation Verwaltung'; break;
        case 'Partners': $tmp = 'PartnerInnen'; break;
        case 'Password': $tmp = 'Passwort'; break;
        case 'Pending Proj.': $tmp = 'Anstehend Projekt'; break;
        case 'Permission denied': $tmp = 'Zugriff verweigert'; break;
        case 'Permission': $tmp = 'Zugriff'; break;
        case 'Personal Page': $tmp = 'Persönliche Seite'; break;
        case 'Platform': $tmp = 'Plattform'; break;
        case 'Please try again': $tmp = 'Bitte versuchen Sie es nochmal'; break;
        case 'Project': $tmp = 'Projekt'; break;
        case 'Project Follow-up': $tmp = 'Projekt Nachgehen'; break;
        case 'Project History': $tmp = 'Projekt Historie'; break;
        case 'Project Information': $tmp = 'Projekt Information'; break;
        case 'Project Insertion process': $tmp = 'Projekteingabeprozess'; break;
        case 'Project Name': $tmp = 'Projektname'; break;
        case 'Project Nature': $tmp = 'Projekt Natur'; break;
        case 'Project Owner(s)': $tmp = 'Projektbesitzer'; break;
        case 'Project Title': $tmp = 'Projekt Titel'; break;
        case 'Project Type': $tmp = 'Projekt Typ'; break;
        case 'Project Volume': $tmp = 'Projekt Volumen'; break;
        case 'Project configurations': $tmp = 'Projekt Konfigurationen'; break;
        case 'Projects ordered alphabetically': $tmp = 'Projekte alphabetisch sortiert'; break;
        case 'Projects': $tmp = 'Projekte'; break;
        case 'Q&amp;A': $tmp = 'F&amp;A'; break;
        case 'Question': $tmp = 'Frage'; break;
        case 'Rated': $tmp = 'Bewerted'; break;
        case 'Rating': $tmp = 'Bewertung'; break;
        case 'Realname': $tmp = 'Namen'; break;
        case 'Receive an email update when something happens in this project': $tmp = 'Schick mir eine Mail wenn sich das Projekt ändert'; break;
        case 'Referees Wanted': $tmp = 'Sachverständige gesucht'; break;
        case 'Referees': $tmp = 'Sachverständige'; break;
        case 'Register as a new User': $tmp = 'Neuer Benutzeranmeldung'; break;
        case 'Register': $tmp = 'Anmelden'; break;
        case 'Remind me': $tmp = 'Erinnern'; break;
        case 'Rename a License': $tmp = 'Lizenz umbenennen'; break;
        case 'Rename': $tmp = 'Umbenennen'; break;
        case 'Search': $tmp = 'Suche'; break;
        case 'See all the comments': $tmp = 'Alle Kommentare sehen'; break;
        case 'See complete ratings...': $tmp = 'Alle Bewertungen sehen'; break;
        case 'SourceAgency Login': $tmp = 'Einloggen zu SourceAgency'; break;
        case 'SourceAgency': $tmp = 'SourceAgency'; break;
        case 'Spanish': $tmp = 'Spanisch'; break;
        case 'Sponsor Collaboration': $tmp = 'Sponsor Zusammenarbeit'; break;
        case 'Sponsor Involvement': $tmp = 'Finanzelle Beteiligung'; break;
        case 'Sponsor this project': $tmp = 'Fördere diese Projekt'; break;
        case 'Sponsoring collaboration': $tmp = 'Förderungenzusammenarbeit'; break;
        case 'Sponsoring': $tmp = 'Finanzelle Förderungen'; break;
        case 'Sponsors Wanted': $tmp = 'Sponsoren gesucht'; break;
        case 'Sponsors can make the current decision following this link': $tmp = 'Sponsoren können diesem Link folgen um eine Entscheidung zu treffen'; break;
        case 'Sponsors': $tmp = 'Sponsoren'; break;
        case 'Step information': $tmp = 'Schrittinformationen'; break;
        case 'Step specific actions (only at its time)': $tmp = 'Schritt spezielle Aktionen'; break;
        case 'Step': $tmp = 'Schritt'; break;
        case 'Steps': $tmp = 'Schritte'; break;
        case 'Submit News': $tmp = 'Neuigkeiten einreichen'; break;
        case 'Summary': $tmp = 'Zusammenfassung'; break;
        case 'System Credits': $tmp = 'Systembeschreibung'; break;
        case 'Technical Content': $tmp = 'Technische Inhalte'; break;
        case 'Technical Specifications': $tmp = 'Technische Spezifikationen'; break;
        case 'There have not been posted any news by the project owner(s)': $tmp = 'Keine Neuigkeiten sind von die Projektbesitzer aufgegeben wurden'; break;
        case 'This is all over now. You have been logged out': $tmp = 'Sie sind jetzt ausgeloggt'; break;
        case 'This is interesting if you are a sponsor and you are interested in joining this project': $tmp = 'Dies ist von Interesse wenn Sie ein Sponsor sind und möchten diese Projekt unterstützen'; break;
        case 'This is your personal page.': $tmp = 'Diese ist ihre persönliche Seite'; break;
        case 'Type': $tmp = 'Typ'; break;
        case 'Unknown': $tmp = 'Unbekannt'; break;
        case 'User Administration': $tmp = 'Benutzer Administration'; break;
        case 'Username': $tmp = 'Benutzer'; break;
        case 'Users': $tmp = 'Benutzer'; break;
        case 'Volume': $tmp = 'Umfang'; break;
        case 'Welcome to SourceAgency': $tmp = 'Willkommen zu SourceAgency'; break;
        case 'What is this?': $tmp = 'Was ist das?'; break;
        case 'You are logged in as': $tmp = 'Sie sind einloggt als'; break;
        case 'You have been logged in as': $tmp = 'Sie waren eingeloggt als'; break;
        case "You'll find here all what has been posted to the project (news, comments, content suggestions, etc.) in chronological order": $tmp = "Alle Neuigkeiten, Kommentare, usw. sind in zeitliche Reihenfolge hier aufgeliste"; break;
        case 'Your authentication is valid until': $tmp = 'Ihre Authentifikation ist gültig bis'; break;
        case 'Your authentication was valid until': $tmp = 'Ihre Authentifikation war gültig bis'; break;
        case 'accepted sponsoring collaborations': $tmp = 'angenommmene finanzille Beteiligung'; break;
        case 'by': $tmp = 'von'; break;
        case 'comments': $tmp = 'Kommentare'; break;
        case 'comments on it': $tmp = 'Kommentare'; break;
        case 'configure': $tmp = 'Konfigurieren'; break;
        case 'consultants': $tmp = 'Berater'; break;
        case 'content suggestions': $tmp = 'Inhaltsvorschläge'; break;
        case 'feedback': $tmp = 'Rückmeldung'; break;
        case 'general comments': $tmp = 'Generelle Kommentare'; break;
        case 'history': $tmp = 'Historie'; break;
        case 'in': $tmp = $phrase; break;
        case 'milestones': $tmp = 'Meilensteine'; break;
        case 'news for this project': $tmp = 'Neuigkeiten des Projektes'; break;
        case 'news': $tmp = 'Neuigkeiten'; break;
        case 'news_edit': $tmp = 'Editieren von Neuigkeiten'; break;
        case 'no comments posted': $tmp = 'Keine Kommentare'; break;
        case 'no news posted': $tmp = 'Keine Neuigkeiten'; break;
        case 'non-accepted sponsoring proposals for this project': $tmp = 'nicht angenommmene Finanzierungangebote für dieses Projekt'; break;
        case 'on project': $tmp = 'an Projekt'; break;
        case 'other comments on this project': $tmp = 'andere Kommentare zu diesem Projekt'; break;
        case 'permission': $tmp = 'Berechtigung'; break;
        case 'posted to': $tmp = 'geschickt zu'; break;
        case 'project': $tmp = 'Projekt'; break;
        case 'project_name': $tmp = 'Projektname'; break;
        case 'rated': $tmp = 'bewertet'; break;
        case 'sponsoring': $tmp = 'Finanzielle Unterstützung'; break;
        case 'status': $tmp = 'Status'; break;
        case 'step': $tmp = "schritt"; break;
        case 'step1_edit': $tmp = 'Neue Berater vorschlagen'; break;
        case 'step3_edit': $tmp = 'Neue Meilensteine definieren'; break;
        case 'summary': $tmp = 'Zusammenfassung'; break;
        case 'times': $tmp = 'mal'; break;
        case 'to this': $tmp = 'zu diesem'; break;
        case 'views': $tmp = 'Zugriff auf Projekt Informationen'; break;
        case 'with': $tmp = 'mit'; break;

            /** explanations for the various steps a project has **/
        case "step_1_explanation": {
            $tmp = "Hilfe für Sponsoren um einen richtigen Projektverlauf zu folgen."; 
            break;
        }
        case "step_2_explanation": {
            $tmp = ( "Dieses Projekt kann verschiedene Spezifikationen haben. "
                     ."Entwickler können ihre eigenen Entwicklungsangebote "
                     ."zu diese Spezifikationen anbieten." );
            break;
        }
        case "step_3_explanation": {
	    $tmp = "Von den Entwicklern angebotene Projektmeilensteine.";
            break;
        }
        case "step_4_explanation": {
            $tmp = ( "Entwickler können sich selbst als Sachverst&auml;dige anbieten. "
                     ."Sponsoren und Projektentwickler werden dann einen w&auml;hlen." );
            break;
        }
        case "step_5_explanation": {
  	    $tmp = ( "Meilensteinabgabe und andere Aufgaben, die mit dem Projektnachgehen zu tun haben." );
            break;
        }
        case "step_6_explanation": {
            $tmp = "Schlussbewertung zwischen Sponsoren und Entwickler.";
            break;
        }
        
        default: {
            $tmp = "$phrase"; 
            $l = new Logger;
            $l->log( "translating (German unknown) '" . $phrase . "'" );
            break;
        }
    }
    return($tmp);
}
?>
