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
# $Id: German-lang.inc,v 1.11 2002/05/02 13:20:09 riessen Exp $
#
######################################################################

if ( function_exists( "translate_real" ) ) {
  return;
}

function translate_real($phrase) {
    switch ( $phrase ) {

        case 'About us': $tmp = '&Uuml;ber uns'; break;
        case 'Action': $tmp = 'Action'; break;
        case 'Actions by involved users': $tmp = 'Actions by involved users'; break;
        case 'AdmDoco': $tmp = 'AdmDoco'; break;
        case 'Administration': $tmp = 'Administration'; break;
        case 'All': $tmp = 'All'; break;
        case 'An error has ocurred': $tmp = 'An error has ocurred'; break;
        case 'Answer': $tmp = 'Answer'; break;
        case 'Architecture': $tmp = 'Architektur'; break;
        case 'Backend': $tmp = 'Backend'; break;
        case 'Be informed': $tmp = 'Be informed'; break;
        case 'Brief description': $tmp = 'Brief description'; break;
        case 'Browse': $tmp = 'Browse'; break;
        case 'Categories': $tmp = 'Kategorien'; break;
        case 'Change User Info': $tmp = 'Change User Info'; break;
        case 'Change User Parameters': $tmp = 'Change User Parameters'; break;
        case 'Change User': $tmp = 'Change User'; break;
        case 'Change a License URL': $tmp = 'Change a License URL'; break;
        case 'Change': $tmp = 'Change'; break;
        case 'Comments': $tmp = 'Comments'; break;
        case "Configure the information access to the project's data": $tmp = "Configure the information access to the project's data"; break;
        case 'Configure the project parameters': $tmp = 'Configure the project parameters'; break;
        case 'Confirm Password': $tmp = 'Confirm Password'; break;
        case 'Consultancy': $tmp = 'Consultancy'; break;
        case 'Consultants Wanted': $tmp = 'Berater gesucht'; break;
        case 'Contact': $tmp = 'Kontakt'; break;
        case 'Create User': $tmp = 'Create User'; break;
        case 'Creation': $tmp = 'Creation'; break;
        case 'Current project budget': $tmp = 'Current project budget'; break;
        case 'Decisions on this step': $tmp = 'Decisions on this step'; break;
        case 'Delete a License': $tmp = 'Delete a License'; break;
        case 'Delete': $tmp = 'Delete'; break;
        case 'Description': $tmp = 'Description'; break;
        case 'Developer Cooperation Wanted': $tmp = 'Entwickler gesucht'; break;
        case 'Developers Wanted': $tmp = 'Entwickler gesucht'; break;
        case 'Developers': $tmp = 'Entwickler'; break;
        case 'Developing': $tmp = 'Developing'; break;
        case 'E-Mail': $tmp = 'E-Mail'; break;
        case 'Either your Username or Password are invalid': $tmp = 'Either your Username or Password are invalid'; break;
        case 'English': $tmp = 'English'; break;
        case 'Enter a New Frequently Asked Question': $tmp = 'Enter a New Frequently Asked Question'; break;
        case 'Enter a new documentation entry for a page': $tmp = 'Enter a new documentation entry for a page'; break;
        case 'Environment': $tmp = 'Umgebung'; break;
        case 'Environment': $tmp = 'Environment'; break;
        case 'Error': $tmp = 'Fehler'; break;
        case 'Expires': $tmp = 'Expires'; break;
        case 'Finished projects': $tmp = 'Abgeschlossene Projekte'; break;
        case 'Forgot Password': $tmp = 'Passwort Vergessen'; break;
        case 'French': $tmp = 'Französisch'; break;
        case 'Frequently Asked Questions Administration': $tmp = 'Frequently Asked Questions Administration'; break;
        case 'Frequently Asked Questions': $tmp = 'Frequently Asked Questions'; break;
        case 'General Actions (anytime and anywhere)': $tmp = 'General Actions (anytime and anywhere)'; break;
        case 'General Comments': $tmp = 'General Comments'; break;
        case 'General comments can be posted': $tmp = 'General comments can be posted'; break;
        case 'German': $tmp = 'Deutsch'; break;
        case 'Go': $tmp = 'Los'; break;
        case 'Here you have to enter all the <b>project related</b> data. This should only give an idea to other people what you are planning.': $tmp = 'Here you have to enter all the <b>project related</b> data. This should only give an idea to other people what you are planning.'; break;
        case 'Home': $tmp = 'Home'; break;
        case 'If you are not yet a registered User, please select': $tmp = 'If you are not yet a registered User, please select'; break;
        case 'Index': $tmp = 'Index'; break;
        case 'Info box': $tmp = 'Info box'; break;
        case 'Information access configuration': $tmp = 'Information access configuration'; break;
        case 'Insert a License': $tmp = 'Insert a License'; break;
        case 'Insert': $tmp = 'Insert'; break;
        case 'Language': $tmp = 'Sprache'; break;
        case 'Latest News': $tmp = 'Latest News'; break;
        case 'License Administration': $tmp = 'License Administration'; break;
        case 'License URL': $tmp = 'License URL'; break;
        case 'License description is not available': $tmp = 'Lizenzbeschreibung nicht verfügbar'; break;
        case 'License': $tmp = 'Lizenz'; break;
        case 'Licenses': $tmp = 'Lizenzen'; break;
        case 'Logged in': $tmp = 'Logged in'; break;
        case 'Login': $tmp = 'Einloggen'; break;
        case 'Logout': $tmp = 'Logout'; break;
        case 'Milestones': $tmp = 'Milestones'; break;
        case 'Modification': $tmp = 'Modification'; break;
        case 'Monitor This Project': $tmp = 'Monitor This Project'; break;
        case 'New Frequently Asked Question': $tmp = 'New Frequently Asked Question'; break;
        case 'New License Name': $tmp = 'New License Name'; break;
        case 'New License URL': $tmp = 'New License URL'; break;
        case 'New License': $tmp = 'New License'; break;
        case 'New Project': $tmp = 'Neues Projekt'; break;
        case 'New Projects': $tmp = 'Neue Projekte'; break;
        case 'New User': $tmp = 'Neuer Benutzer'; break;
        case 'News': $tmp = 'Nachrichten'; break;
        case 'Newsletter': $tmp = 'Newsletter'; break;
        case 'No consultants wanted': $tmp = 'No consultants wanted'; break;
        case 'No': $tmp = 'Nein'; break;
        case 'No.': $tmp = 'Nr.'; break;
        case 'Not Logged in': $tmp = 'Nicht eingeloggt'; break;
        case 'Open Source Project Mediation & Management': $tmp = 'Open Source Project Mediation & Management'; break;
        case 'Open Source Software Projects': $tmp = 'Open-Source-Software-Projekte'; break;
        case 'Our apologies. Documentation only available in English.': $tmp = 'Our apologies. Documentation only available in English.'; break;
        case 'Page Documentation Administration': $tmp = 'Page Documentation Administration'; break;
        case 'Partners': $tmp = 'PartnerInnen'; break;
        case 'Password': $tmp = 'Password'; break;
        case 'Pending Proj.': $tmp = 'Pending Proj.'; break;
        case 'Permission denied': $tmp = 'Permission denied'; break;
        case 'Permission': $tmp = 'Permission'; break;
        case 'Personal Page': $tmp = 'Personal Page'; break;
        case 'Platform': $tmp = 'Plattform'; break;
        case 'Please try again': $tmp = 'Please try again'; break;
        case 'Project Follow-up': $tmp = 'Project Follow-up'; break;
        case 'Project History': $tmp = 'Project History'; break;
        case 'Project Information': $tmp = 'Project Information'; break;
        case 'Project Insertion process': $tmp = 'Project Insertion process'; break;
        case 'Project Name': $tmp = 'Projektname'; break;
        case 'Project Nature': $tmp = 'Project Nature'; break;
        case 'Project Owner(s)': $tmp = 'Project Owner(s)'; break;
        case 'Project Title': $tmp = 'Project Title'; break;
        case 'Project Type': $tmp = 'Project Type'; break;
        case 'Project Volume': $tmp = 'Project Volume'; break;
        case 'Project configurations': $tmp = 'Project configurations'; break;
        case 'Projects ordered alphabetically': $tmp = 'Projects ordered alphabetically'; break;
        case 'Projects': $tmp = 'Projekte'; break;
        case 'Q&amp;A': $tmp = 'F&amp;A'; break;
        case 'Question': $tmp = 'Question'; break;
        case 'Rating': $tmp = 'Rating'; break;
        case 'Realname': $tmp = 'Realname'; break;
        case 'Receive an email update when something happens in this project': $tmp = 'Receive an email update when something happens in this project'; break;
        case 'Referees Wanted': $tmp = 'Referee gesucht'; break;
        case 'Referees': $tmp = 'Referees'; break;
        case 'Register as a new User': $tmp = 'Register as a new User'; break;
        case 'Register': $tmp = 'Register'; break;
        case 'Remind me': $tmp = 'Remind me'; break;
        case 'Rename a License': $tmp = 'Rename a License'; break;
        case 'Rename': $tmp = 'Rename'; break;
        case 'Search': $tmp = 'Suche'; break;
        case 'SourceAgency Login': $tmp = 'SourceAgency Login'; break;
        case 'SourceAgency': $tmp = 'SourceAgency'; break;
        case 'Spanish': $tmp = 'Spanisch'; break;
        case 'Sponsor Collaboration': $tmp = 'Sponsor Collaboration'; break;
        case 'Sponsor Involvement': $tmp = 'Sponsor Involvement'; break;
        case 'Sponsor this project': $tmp = 'Sponsor this project'; break;
        case 'Sponsoring collaboration': $tmp = 'Sponsoring collaboration'; break;
        case 'Sponsoring': $tmp = 'Sponsoring'; break;
        case 'Sponsors Wanted': $tmp = 'Sponsoren gesucht'; break;
        case 'Sponsors can make the current decision following this link': $tmp = 'Sponsors can make the current decision following this link'; break;
        case 'Sponsors': $tmp = 'Sponsoren'; break;
        case 'Step information': $tmp = 'Step information'; break;
        case 'Step specific actions (only at its time)': $tmp = 'Step specific actions (only at its time)'; break;
        case 'Step': $tmp = 'Schritt'; break;
        case 'Steps': $tmp = 'Schritte'; break;
        case 'Submit News': $tmp = 'Submit News'; break;
        case 'Summary': $tmp = 'Summary'; break;
        case 'System Credits': $tmp = 'Systembeschreibung'; break;
        case 'Technical Content': $tmp = 'Technical Content'; break;
        case 'Technical Specifications': $tmp = 'Technical Specifications'; break;
        case 'There have not been posted any news by the project owner(s)': $tmp = 'There have not been posted any news by the project owner(s)'; break;
        case 'This is all over now. You have been logged out': $tmp = 'This is all over now. You have been logged out'; break;
        case 'This is interesting if you are a sponsor and you are interested in joining this project': $tmp = 'This is interesting if you are a sponsor and you are interested in joining this project'; break;
        case 'This site is being beta tested': $tmp = 'This site is being beta tested'; break;
        case 'Type': $tmp = 'Typ'; break;
        case 'Unknown': $tmp = 'Unknown'; break;
        case 'User Administration': $tmp = 'User Administration'; break;
        case 'Username': $tmp = 'Username'; break;
        case 'Users': $tmp = 'Benutzter'; break;
        case 'Volume': $tmp = 'Umfang'; break;
        case 'Welcome to SourceAgency': $tmp = 'Welcome to SourceAgency'; break;
        case 'What is this?': $tmp = 'Was ist das?'; break;
        case 'You are logged in as': $tmp = 'You are logged in as'; break;
        case 'You have been logged in as': $tmp = 'You have been logged in as'; break;
        case "You'll find here all what has been posted to the project (news, comments, content suggestions, etc.) in chronological order": $tmp = "You'll find here all what has been posted to the project (news, comments, content suggestions, etc.) in chronological order"; break;
        case 'Your authentication is valid until': $tmp = 'Your authentication is valid until'; break;
        case 'Your authentication was valid until': $tmp = 'Your authentication was valid until'; break;
        case 'accepted sponsoring collaborations': $tmp = 'accepted sponsoring collaborations'; break;
        case 'by': $tmp = 'by'; break;
        case 'comments': $tmp = 'comments'; break;
        case 'configure': $tmp = 'configure'; break;
        case 'consultants': $tmp = 'consultants'; break;
        case 'content suggestions': $tmp = 'content suggestions'; break;
        case 'feedback': $tmp = 'feedback'; break;
        case 'general comments': $tmp = 'general comments'; break;
        case 'history': $tmp = 'history'; break;
        case 'index-doco': $tmp = 'index-doco'; break;
        case 'index-page-name': $tmp = 'index-page-name'; break;
        case 'milestones': $tmp = 'milestones'; break;
        case 'news for this project': $tmp = 'news for this project'; break;
        case 'news': $tmp = 'news'; break;
        case 'news_edit': $tmp = 'news_edit'; break;
        case 'non-accepted sponsoring proposals for this project': $tmp = 'non-accepted sponsoring proposals for this project'; break;
        case 'other comments on this project': $tmp = 'other comments on this project'; break;
        case 'permission': $tmp = 'permission'; break;
        case 'please send any and all': $tmp = 'please send any and all'; break;
        case 'project_name': $tmp = 'project_name'; break;
        case 'sponsoring': $tmp = 'sponsoring'; break;
        case 'step1_edit': $tmp = 'step1_edit'; break;
        case 'step3_edit': $tmp = 'step3_edit'; break;
        case 'summary': $tmp = 'summary'; break;
        case 'views': $tmp = 'views'; break;
        case 'with': $tmp = 'with'; break;

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
