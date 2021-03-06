<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
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
# $Id: German-lang.inc,v 1.2 2004/02/02 12:30:16 helix Exp $
#
######################################################################

if ( function_exists( 'translate_real' ) ) {
  return;
}

function translate_real($phrase) {
    switch ( $phrase ) {

    case '< 0.25 Man Month': $tmp = '< 0.25 Mannmonate'; break;
    case '< 1 Man Month': $tmp = '< 1 Mannmonat'; break;
    case '< 2 Man Months': $tmp = '< 2 Mannmonate'; break;
    case '< 6 Man Months': $tmp = '< 6 Mannmonate'; break;
    case '> 6 Man Months': $tmp = '> 6 Mannmonate'; break;
    case 'A sponsor may require help to submit a project in a proper way. If he wishes, he can ask registered developers to assist him on this topic.': $tmp = 'Ein Sponsor ben�tigt Beratung bei der Definition eines Projekts. Ist dies erforderlich, k�nnen registrierte Entwickler um Hilfe gebeten werden.'; break;
    case 'About us': $tmp = '�ber uns'; break;
    case 'About what you can see and what not': $tmp = 'Was ist hier sichtbar und was nicht'; break;
    case 'Accepted': $tmp = 'Akzeptiert'; break;
    case 'Action': $tmp = 'Aktion'; break;
    case 'Actions by involved users': $tmp = 'Aktionen durch beteiligte Benutzer'; break;
    case 'AdmDoco': $tmp = 'Dokumentverwaltung'; break;
    case 'Adaption': $tmp = 'Anpassung'; break;
    case 'Administration': $tmp = 'Administration'; break;
    case 'After that you should wait for a BerliOS editor to review your project': $tmp = 'Danach warten Sie bitte bis ein BerliOS Editor Ihr Projekt begutachtet hat'; break;
    case 'All': $tmp = 'Alle'; break;
    case 'All Comments by': $tmp = 'Alle Kommentare von'; break;
    case 'All the rating on ': $tmp = 'Alle Bewertungen von'; break;
    case 'already exists':$tmp='existiert bereits';break;
    case 'An error has ocurred': $tmp = 'Ein Fehler ist aufgetretten'; break;
    case 'Answer': $tmp = 'Anwort'; break;
    case 'Architecture': $tmp = 'Architektur'; break;
    case 'At your': $tmp = 'Auf Ihrer'; break;
    case 'Backend': $tmp = 'Backend'; break;
    case 'Begin': $tmp = 'Beginn'; break;
    case 'Begin wished': $tmp = 'Gew�nschter Beginn'; break;
    case 'Body': $tmp = 'Inhalt'; break;
    case 'Brief description': $tmp = 'Kurze Beschreibung'; break;
    case 'Browse': $tmp = 'Bl�ttern'; break;
    case 'Budget (in Euro)': $tmp = 'Budget (in Euro)'; break;
    case 'Budget must be greater than zero': $tmp = 'Das Budget muss gr�sser als Null sein'; break;
    case 'Categories': $tmp = 'Kategorien'; break;
    case 'Change User Info': $tmp = 'Benutzer Info. �ndern'; break;
    case 'Change User Parameters': $tmp = 'Benutzer Parameter �ndern'; break;
    case 'Change User': $tmp = 'Benutzer �ndern'; break;
    case 'Change a License URL': $tmp = 'Lizenz URL �ndern'; break;
    case 'Change': $tmp = '�ndern'; break;
    case 'Check if you want to be a consultant':$tmp='Markieren Sie, ob Sie Berater sein m�chten';break;
    case 'Comment': $tmp = 'Kommentar'; break;
    case 'Comment on Sponsor Involvement':$tmp='Kommentar zu Sponsorenbeteiligung';break;
    case 'Comment it!': $tmp = 'Kommentiere es!'; break;
    case 'Comment this News!': $tmp = 'Kommentiere diese Neuigkeit!'; break;
    case 'Comment this Proposal': $tmp = 'Kommentiere dieses Angebot'; break;
    case 'Comment this Specification': $tmp = 'Kommentiere diese Spezifikation'; break;
    case 'Comments': $tmp = 'Kommentare'; break;
    case 'Comments on the views?': $tmp = 'Kommentare dazu?'; break;
    case 'Comment to the involvement': $tmp = 'Kommentar zur Beteiligung'; break;
    case "Configured?": $tmp = 'Konfiguriert?'; break;    
    case "Configure the information access to the project's data": $tmp = 'Information zugriffsrechte �ndern f�r diese Projekt'; break;
    case 'Configure Project': $tmp = 'Konfiguriere Projekt'; break;
    case 'Configure Information Access Rights for this Project': $tmp = 'Konfiguriere die Zugriffsrechte auf Informationen diese Projektes'; break;
    case 'Configure access rights for this project': $tmp = 'Konfiguriere die Zugriffsrechte auf Projektinformationen'; break;
    case "Configure access rights to project's information": $tmp = 'Konfiguriere die Zugriffsrechte auf Projektinformationen'; break;
    case 'Configure the project parameters': $tmp = 'Konfigurieren Sie die Projektparameter'; break;
    case 'Configure this project': $tmp = 'Konfiguriere das Projekt'; break;
    case 'Confirm Password': $tmp = 'Passwort best�tigen'; break;
    case 'Congratulations': $tmp = 'Gratulation'; break;
    case 'Congratulations. You have just defined a new project': $tmp = 'Gratulation. Sie haben soeben ein neues Projekt definiert'; break;
    case 'Consultancy': $tmp = 'Beratung'; break;
    case 'Consultant': $tmp = 'Berater'; break;
    case 'Consultants': $tmp = 'Berater'; break;
    case 'Consultants Wanted': $tmp = 'Berater gesucht'; break;
    case 'Contact': $tmp = 'Kontakt'; break;    
    case 'Content proposed': $tmp = 'Spezifikation vorgeschlagen'; break;
    case 'Cooperation': $tmp = 'Kooperation'; break;
    case 'Cost': $tmp = 'Kosten'; break;
    case 'Create': $tmp = 'Anlegen'; break;
    case 'Create User': $tmp = 'Benutzer anlegen'; break;
    case 'Creation': $tmp = 'Angelegt'; break;
    case 'Current project budget': $tmp = 'Aktueller Projektetat'; break;
    case 'Current Milestone Schedule Status': $tmp = 'Aktueller Meilenstein-Stand'; break;
    case 'Date': $tmp = 'Datum'; break;
    case 'Decision making': $tmp = 'Entscheidungsfindung'; break;
    case 'Delete a License': $tmp = 'Lizenz l�schen'; break;
    case 'Delete': $tmp = 'L�schen'; break;
    case 'Deleted': $tmp = 'Gel�scht'; break;
    case 'Description': $tmp = 'Beschreibung'; break;
    case 'Developer Cooperation Wanted': $tmp = 'Entwicklerkooperation gesucht'; break;
    case 'Developer cooperation wanted?': $tmp = 'Entwicklerkooperation gesucht?'; break;
    case 'Developing Proposals Wanted': $tmp = 'Entwicklungsangebote gesucht'; break;
    case 'Developers can propose their implementations using this form':$tmp='Entwickler k�nnen �ber dieses Formular Implementierungsvorschl�ge machen';break;
    case 'Developers': $tmp = 'Entwickler'; break;
    case 'Developer': $tmp = 'Entwickler'; break;
    case 'Developing': $tmp = 'In Entwicklung'; break;
    case 'Developing Cooperation': $tmp = 'Entwicklungskooperation'; break;
    case 'Developing projects': $tmp = 'Entwickler-Projekte'; break;    
    case 'Developing Proposal': $tmp = 'Entwicklungsangebot'; break;
    case 'Development proposal': $tmp = 'Entwicklungsangebot'; break;
    case 'Developing Version': $tmp = 'Entwicklungsversion'; break;
    case 'Development': $tmp = 'Entwicklung'; break;
    case 'Documentation': $tmp = 'Dokumentation'; break;
    case 'Duration': $tmp = 'Dauer'; break;
    case 'Duration (in weeks)': $tmp = 'Dauer (in Wochen)'; break;
    case 'E-Mail': $tmp = 'E-Mail'; break;
    case 'Editing News': $tmp = 'Neuigkeit erstellen'; break;
    case 'Either your Username or Password are invalid': $tmp = 'Entweder haben Sie den falschen Benutzernamen oder Passwort eingegeben'; break;
    case 'Enter a New Frequently Asked Question': $tmp = 'Neue Frage eingeben'; break;
    case 'Enter a new documentation entry for a page': $tmp = 'Neue Dokumentation f�r eine Seite'; break;
    case 'Environment': $tmp = 'Umgebung'; break;
    case 'Error': $tmp = 'Fehler'; break;    
    case 'Everybody': $tmp = 'Jeder'; break;
    case 'Expansion': $tmp = 'Erweiterung'; break;
    case 'Expires': $tmp = 'L�uft ab'; break;
    case 'Fill out a sponsoring involvement form for your project': $tmp = 'F�llen Sie ein Formular f�r die finanzielle F�rderung an Ihrem Projekt aus'; break;
    case 'Fill out the definition form': $tmp = 'F�llen Sie das Definitionsformular aus'; break;
    case 'Finish': $tmp = 'Ende'; break;
    case 'Finish before': $tmp = 'Ende vor'; break;
    case 'Finished projects': $tmp = 'Abgeschlossene Projekte'; break;
    case 'First Sponsor': $tmp = 'Erster Sponsor'; break;
    case 'Forgot Password': $tmp = 'Passwort vergessen'; break;
    case 'Frequently Asked Questions Administration': $tmp = 'Administration der h�ufig gestellte Fragen'; break;
    case 'Frequently Asked Questions': $tmp = 'H�ufig gestellte Fragen'; break;
    case 'Further Documentation': $tmp = 'Weitere Dokumentation'; break;
    case 'General': $tmp = 'Generell'; break;
    case 'General Actions (anytime and anywhere)': $tmp = 'Allgemeine Aktionen'; break;
    case 'General Comments': $tmp = 'Allgemeine Kommentare'; break;
    case 'General comments can be posted': $tmp = 'Allgemeine Kommentare k�nnen abgegeben werden'; break;
    case 'Global personal rating: ': $tmp = 'Globale pers�nliche Bewertung'; break;
    case 'Go': $tmp = 'Los'; break;
    case 'Goals': $tmp = 'Ziel'; break;
    case 'Have a look at the': $tmp = 'Schauen Sie nach den'; break;
    case 'Header': $tmp = '�berschrift'; break;
    case 'Hi': $tmp = 'Hallo'; break;
    case 'High importance': $tmp = 'Hohe Wichtigkeit'; break;
    case 'Here sponsors can involve themselves in projects': $tmp = 'Hier k�nnen Sie sich als Sponsor an der Finanzierung des Projektes beteiligen'; break;
    case 'Here you have to enter all the <b>project related</b> data. This should only give an idea to other people what you are planning.': $tmp = 'Bitte beschreiben Sie jetzt das Projekt. Die Beschreibung soll Anderen ein kurze Vorstellung geben, was Sie mit dem Projekt planen.'; break;
    case 'Home': $tmp = 'Home'; break;
    case 'If you are interested in this project, you can monitor it': $tmp = 'Wenn Sie an diesem Projekt interessiert sind, k�nnen Sie es beobachten'; break;
    case 'If you are not yet a registered User, please select': $tmp = 'Falls Sie noch kein registrierter Benutzer sind, bitte w�hlen'; break;
    case 'Here you will find all the information related to you': $tmp = 'Hier finden Sie alle Informationen, die mit Ihnen in Beziehung stehen'; break;
    case 'If you are a registered developer, you can propose yourself as a consultant for this project....':$tmp='Wenn Sie sich als Benutzer registrieren, k�nnen Sie sich als Berater f�r dieses Projekt vorschlagen.';break;
    case 'implementation proposal':$tmp='Entwicklungsangebot';break;
    case 'Importance': $tmp = 'Wichtigkeit'; break;
    case 'Importance filter': $tmp = 'Wichtigkeitsfilter'; break;
    case 'Index': $tmp = 'Hauptseite'; break;
    case 'Info box': $tmp = 'Information'; break;
    case 'Information access rights': $tmp = 'Zugriffrechte auf Informationen'; break;
    case 'In order to define a new project, you will have to follow these steps': $tmp = 'Um ein neues Projekt zu definieren, m�ssen Sie die folgenden Schritte ausf�hren'; break;
    case 'Insert a License': $tmp = 'Lizenz angeben'; break;
    case 'Insert': $tmp = 'Einf�gen'; break;
    case 'is changed': $tmp = 'wurde ge�ndert'; break;
    case 'Invalid Project Id': $tmp = 'Unzul�ssige Projektidentifikation'; break;
    case 'Involved developers can see their own milestones and the accepted milestones from other developers': $tmp = 'Involvierte Entwickler sehen ihre eigenen Meilensteine und die vereinbarten von anderen Entwicklern'; break;
    case 'Project Participantions': $tmp = 'Projektbeteiligungen'; break;
    case 'Language': $tmp = 'Sprache'; break;
    case 'Last 10 Comments by': $tmp = 'Letzte 10 Kommentare von'; break;
    case 'Last 5 News by': $tmp = 'Letzte 5 Neuigkeiten von'; break;
    case 'Last Modification': $tmp = 'Modifiziert'; break;
    case 'Latest News': $tmp = 'Neuigkeiten'; break;
    case 'License Administration': $tmp = 'Lizenz Administration'; break;
    case 'License URL': $tmp = 'Lizenz URL'; break;
    case 'License description is not available': $tmp = 'Lizenzbeschreibung nicht verf�gbar'; break;
    case 'License': $tmp = 'Lizenz'; break;
    case 'Licenses': $tmp = 'Lizenzen'; break;
    case 'Logged in': $tmp = 'Eingeloggt als'; break;
    case 'Login': $tmp = 'Einloggen'; break;
    case 'Logout': $tmp = 'Ausloggen'; break;
    case 'Low importance': $tmp = 'Geringe Wichtigkeit'; break;
    case 'Make a Proposal to this specification!': $tmp = 'Erstelle Angebot zu dieser Spezifikation'; break;
    case 'Max. sum of money': $tmp = 'Max. Betrag'; break;
    case 'Milestones': $tmp = 'Meilensteine'; break;
    case 'Milestones proposed by': $tmp = 'Meilensteine vorgeschlagen von'; break;
    case 'Milestone suggestions and agreement': $tmp = 'Meilensteinvorschl�ge und Vereinbarung'; break;
    case 'Modification': $tmp = '�nderung'; break;
    case 'Modified': $tmp = 'Modifiziert'; break;
    case 'Monitor this Project': $tmp = 'Projektbeobachtung'; break;
    case 'Monitoring this Project': $tmp = 'Projektbeobachtung'; break;
    case 'Monitored Projects': $tmp = 'Beobachtete Projekte'; break;
    case 'My Projects': $tmp = 'Meine Projekte'; break;
    case 'Needed Skills': $tmp = 'Erforderliche Kenntnisse'; break;
    case 'Negotiating': $tmp = 'in Verhandlung'; break;
    case 'New Frequently Asked Question': $tmp = 'Neue h�ufig gestellte Frage'; break;
    case 'New License Name': $tmp = 'Neuer Lizenzname'; break;
    case 'New License URL': $tmp = 'Neue Lizenz URL'; break;
    case 'New License': $tmp = 'Neue Lizenz'; break;
    case 'New Project': $tmp = 'Neues Projekt'; break;
    case 'New User': $tmp = 'Neuer Benutzer'; break;
    case 'News': $tmp = 'Neuigkeiten'; break;
    case 'News are posted by the project initiator. Any other contribution can be done by filling out a General Comment': $tmp = 'Neuigkeiten werden durch den Projektinitiator erstellt. Alle andere k�nnen allgemeine Kommentare abgeben'; break;
    case 'News can be posted by the project owner(s)': $tmp = 'Neuigkeiten k�nnen von den Projekteignern erstellt werden'; break;
    case 'Newsletter': $tmp = 'Newsletter'; break;
    case 'No Comments': $tmp = 'Keine Kommentare'; break;
    case 'No consultant proposal with this status': $tmp = 'Kein Beraterangebot mit diesem Status'; break;
    case 'No consultants wanted': $tmp = 'Keine Berater erw�nscht'; break;
    case 'No developers have offered themselves as consultants yet':$tmp='Keine Entwickler haben sich bisher als Berater angeboten';break;
    case 'No developing cooperation proposal with this status': $tmp = 'Kein Entwicklungsangebot mit diesem Status'; break;
    case 'No monitored projects': $tmp = 'Keine beobachteten Projekte'; break;
    case 'No personal projects': $tmp = 'Keine pers�nliche Projekte'; break;
    case 'No rating yet': $tmp = 'Noch keine Bewertung'; break;
    case 'No referee proposal with this status': $tmp = 'Kein Sachverst�ndigenangebot mit diesem Status'; break;
    case 'No': $tmp = 'Nein'; break;
    case 'No.': $tmp = 'Nr.'; break;
    case 'Non-involved (registered and unregistered) users can see only the accepted milestones': $tmp = 'Nicht-involvierte (registrierte und nicht-registrierte) Benutzer k�nnen nur die vereinbarten Meilensteine sehen'; break;
    case 'Not Logged in': $tmp = 'Nicht eingeloggt'; break;
    case 'Not rated yet': $tmp = 'Noch nicht bewertet'; break;
    case 'No project with this id.': $tmp = 'Kein Projekt mit dieser Identifikation vorhanden.'; break;
    case 'No participantions in projects with this status': $tmp = 'Keine Beteiligungen an Projekten mit diesem Status'; break;
    case 'No technical specification suggestions have been posted to this project.': $tmp = 'Es wurde noch keine Vorschl�ge f�r eine technische Spezifikation des Projektes abgegeben.'; break;
    case 'Offer yourself as project consultant':$tmp='Bieten Sie sich als Projektberater an';break;
    case 'Once this is done, you will receive an e-mail': $tmp = 'Wenn diese geschehen ist, erhalten Sie eine E-Mail'; break;
    case 'Open Source Project Mediation & Management': $tmp = 'Open Source Projekt-Vermittlung & Management'; break;
    case 'Open Source Software Projects': $tmp = 'Open-Source-Software-Projekte'; break;
    case 'Other': $tmp = 'Anderes'; break;
    case 'Other Developing Proposals': $tmp = 'Weitere Entwicklungsvorschl�ge'; break;
    case 'Other developing proposals': $tmp = 'Weitere Entwicklungsvorschl�ge'; break;
    case 'Other Technical Contents': $tmp = 'Andere technische Inhalte'; break;
    case 'Other Technical Specifications': $tmp = 'Weitere technische Spezifikationen'; break;
    case 'Other technical specifications': $tmp = 'Weitere technische Spezifikationen'; break;
    case 'Our apologies. Documentation only available in English.': $tmp = 'Die Dokumentation ist leider nicht in Deutsch verf�gbar.'; break;
    case 'Page (without extension)': $tmp = 'Seite (ohne .php Erweiterung)'; break;
    case 'Page Documentation Administration': $tmp = 'Seiten Dokumentation Verwaltung'; break;
    case 'Partners': $tmp = 'PartnerInnen'; break;
    case 'Password': $tmp = 'Passwort'; break;
    case 'Payment': $tmp = 'Zahlung'; break;
    case 'Pending Proj.': $tmp = 'Anstehende Projekte'; break;
    case 'Pending Project Administration': $tmp = 'Verwaltung anstehender Projekte'; break;
    case 'Permission denied': $tmp = 'Zugriff verweigert'; break;
    case 'Permission': $tmp = 'Rechte'; break;
    case 'Personal Page': $tmp = 'Pers�nliche Seite'; break;
    case 'Platform': $tmp = 'Plattform'; break;
    case 'Please select a different Username':$tmp='Bitte w�hlen Sie einen anderen Benutzernamen';break;
    case 'Please try again': $tmp = 'Bitte versuchen Sie es nochmal'; break;
    case 'PREVIEW': $tmp = 'VORSCHAU'; break;
    case 'Preview': $tmp = 'Vorschau'; break;
    case 'Previous': $tmp = 'Vorherige'; break;
    case 'Product': $tmp = 'Produkt'; break;
    case 'Project': $tmp = 'Projekt'; break;
    case 'Project Configuration': $tmp = 'Projektkonfiguration'; break;
    case 'Project configuration form': $tmp = 'Projektkonfigurationsformular'; break;
    case 'Project configuration modified': $tmp = 'Projektkonfiguration ver�ndert'; break;
    case 'Project Definition': $tmp = 'Projektdefinition'; break;
    case 'Project Developers': $tmp = 'Projektentwickler'; break;
    case 'Project description': $tmp = 'Projektbeschreibung'; break;
    case 'Project Follow-up': $tmp = 'Projektdurchf�hrung'; break;
    case 'Project History': $tmp = 'Projekthistorie'; break;
    case 'Project Information': $tmp = 'Projektinformation'; break;
    case 'Project Information Access Rights': $tmp = 'Zugriffsrechte auf Projektinformationen'; break;
    case 'Project information access rights configuration': $tmp = 'Konfiguration der Zugriffsrechte auf Projektinformationen'; break;
    case 'Project Initiator': $tmp = 'Projektinitiator'; break;
    case 'Project Insertion process': $tmp = 'Projektdefinitionsprozess'; break;
    case strpr($phrase,'/Project is now in phase .*/'):	 
	 $tmp=preg_replace('/Project is now in phase (.*)/','Projekt ist nun in Phase \\1',$phrase);
	 break;        
    case 'Project Name': $tmp = 'Projektname'; break;    
    case 'Project Nature': $tmp = 'Projektnatur'; break;
    case 'Project Owner(s)': $tmp = 'Projekteigner'; break;
    case 'Project owners can see all milestones (proposed and accepted ones)': $tmp = 'Projekteigner sehen alle Meilensteine (vorgeschlagene und vereinbarte)'; break;
    case 'Project Participants': $tmp = 'Projektteilnehmer'; break;
    case 'Project Sponsors': $tmp = 'Projektsponsoren'; break;
    case 'Project Title': $tmp = 'Projekttitel'; break;
    case 'Project Type': $tmp = 'Projekttyp'; break;
    case 'Project Volume': $tmp = 'Projektvolumen'; break;
    case 'Project configuration': $tmp = 'Projektkonfiguration'; break;
    case 'Projects ordered alphabetically': $tmp = 'Projekte alphabetisch sortiert'; break;
    case 'Project reviewed by a SourceAgency Editor': $tmp = 'Projekt durch SourceAgency-Redaktion gepr�ft'; break;
    case 'Projects': $tmp = 'Projekte'; break;
    case 'Propose yourself as referee': $tmp = 'Schlagen Sie sich selbst als Sachverst�ndiger vor'; break;    
    case 'Proposals': $tmp = 'Angebote'; break;
    case 'Propose Milestones': $tmp = 'Machen Sie Vorschl�ge f�r Meilensteine'; break;
    case 'Propose yourself as consultant': $tmp = 'Bieten Sie sich selbst als Berater an'; break;
    case 'Proposed': $tmp = 'Angeboten'; break;
    case 'Prototype': $tmp = 'Prototyp'; break;
    case 'Q&amp;A': $tmp = 'Fragen &amp; Antworten'; break;
    case 'Q&A': $tmp = 'Fragen &amp; Antworten'; break;
    case 'Question': $tmp = 'Frage'; break;
    case 'Rated': $tmp = 'Bewerted'; break;
    case 'Rating': $tmp = 'Bewertung'; break;
    case 'Realname': $tmp = 'Name'; break;
    case 'Receive an email update when something happens in this project': $tmp = 'Sie erhalten eine Mail, wenn sich das Projekt ge�ndert hat'; break;    
    case 'Recent Developers': $tmp = 'Neue Entwickler'; break;
    case 'Recent Projects': $tmp = 'Neue Projekte'; break;
    case 'Recent Sponsors': $tmp = 'Neue Sponsoren'; break;
    case 'Recommended': $tmp = 'Empfohlen'; break;
    case 'Referee registration and selection. Any developer who has the skills to be a referee can propose himself as one': $tmp = 'Anmeldung und Auswahl von Sachverst�ndigen. Jeder Entwickler mit den notwendigen F�higkeiten kann sich selbst als Sachverst�ndiger vorschlagen'; break;
    case 'Referee offered': $tmp = 'Schiedsrichter angeboten'; break;
    case 'Referees Wanted': $tmp = 'Sachverst�ndige gesucht'; break;
    case 'Referee': $tmp = 'Sachverst�ndiger'; break;
    case 'Referees': $tmp = 'Sachverst�ndige'; break;
    case 'Register as a new User': $tmp = 'Registrierung als neuer Benutzer'; break;
    case 'Register': $tmp = 'Anmelden'; break;
    case 'Registered': $tmp = 'Registrierter Benutzer'; break;
    case 'Registered developers can offer themselves as consultants':$tmp='Registrierte Entwickler k�nnen sich als Berater anbieten';break;
    case 'Rejected': $tmp = 'Abgelehnt'; break;
    case 'Release Date': $tmp = 'Fertigstellung'; break;
    case 'Remind me': $tmp = 'Erinnern'; break;
    case 'Rename a License': $tmp = 'Lizenz umbenennen'; break;
    case 'Rename': $tmp = 'Umbenennen'; break;
    case 'Search': $tmp = 'Suche'; break;
    case 'See all the comments': $tmp = 'Alle Kommentare sehen'; break;
    case 'See complete ratings...': $tmp = 'Alle Bewertungen sehen'; break;
    case 'SourceAgency Login': $tmp = 'Einloggen bei SourceAgency'; break;
    case 'SourceAgency': $tmp = 'SourceAgency'; break;
    case 'Specification': $tmp = 'Spezifikation'; break;
    case 'Specifications': $tmp = 'Spezifikationen'; break;
    case strpr($phrase,'/Sponsor .* accepted as sponsor/'):	 
	 $tmp=preg_replace('/Sponsor (.*) accepted as sponsor/','Sponsor \\1 als Sponsor akzeptiert',$phrase);
	 break;    
    case 'Sponsor Collaboration': $tmp = 'Sponsorenzusammenarbeit'; break;
    case 'Sponsor Involvement': $tmp = 'Finanzielle F�rderung'; break;
    case 'Sponsor this project': $tmp = 'F�rdere dieses Projekt'; break;
    case 'Sponsored projects': $tmp = 'Sponsoren-Projekte'; break;
    case 'Sponsoring': $tmp = 'Finanzielle F�rderungen'; break;
    case 'Sponsoring Collaboration': $tmp = 'Sponsorenzusammenarbeit'; break;
    case 'Sponsoring Comment': $tmp = 'Kommentar zur F�rderung'; break;
    case 'Sponsoring Involvement': $tmp = 'Finanzielle F�rderung'; break;
    case 'Sponsoring wish': $tmp = 'Sponsorenschaft gew�nscht'; break;
    case 'Sponsors Wanted': $tmp = 'Sponsor gesucht'; break;
    case 'Sponsors can make their decisions': $tmp = 'Sponsoren treffen ihre Entscheidungen'; break;
    case 'Sponsors can modify their sponsoring wish using this form': $tmp = 'Sponsoren k�nnen ihre F�rderungsangebote abgeben'; break;
    case 'Sponsors': $tmp = 'Sponsoren'; break;
    case 'Start': $tmp = 'Start'; break;
    case 'Status': $tmp = 'Status'; break;
    case 'Stay informed': $tmp = 'Bleibe informiert'; break;
    case 'Step information': $tmp = 'Schrittinformation'; break;
    case 'Step specific actions (only at its time)': $tmp = 'Schrittspezifische Aktionen'; break;
    case 'Step': $tmp = 'Schritt'; break;
    case 'Steps': $tmp = 'Schritte'; break;
    case 'Subject': $tmp = 'Thema'; break;
    case 'Submit': $tmp = 'Erstellen'; break;
    case 'Submit News': $tmp = 'Neuigkeit erstellen'; break;
    case 'Suggest a Technical Specification': $tmp = 'Erstelle Vorschlag f�r technische Spezifikation'; break;
    case 'Suggesting a Technical Specification': $tmp = 'Vorschlag f�r technische Spezifikation'; break;
    case 'Summary': $tmp = 'Zusammenfassung'; break;
    case 'System Credits': $tmp = 'Systembeschreibung'; break;
    case 'Technical content suggestions. They can be made either by developers or by a sponsor (if the sponsor is owner of the project).': $tmp = 'Vorschl�ge f�r technische Inhalte. Vorschl�ge k�nnen entweder von Entwicklern oder von einem Sponsor abgegeben werden (wenn der Sponsor der Projekteigner ist).'; break;
    case 'Technical Specification': $tmp = 'Technische Spezifikation'; break;
    case 'Technical Specifications': $tmp = 'Technische Spezifikationen'; break;
    case 'Technical specification suggestions. They can be made either by developers or by a sponsor (if the sponsor is owner of the project).': $tmp = 'Technische Spezifikationen k�nnen entweder von Entwicklern oder einem Sponsor erstellt werden (wenn der Sponsor Eigner des Projekts ist).'; break;
    case 'There are no developers that have offered themselves as referees': $tmp = 'Keine Entwickler haben sich als Sachverst�ndige angeboten'; break;
    case 'There have not been posted any news by the project owner(s)': $tmp = 'Es sind keine Neuigkeiten von den Projekteignern erstellt worden'; break;
    case 'There have not been posted any sponsoring involvement wishes to this project': $tmp = 'Es sind keine F�rderungsangebote f�r dieses Projekt abgegeben worden'; break;
    case 'These users are monitoring the project': $tmp = 'Diese Benutzer beobachten derzeit das Projekt'; break;
    case 'The next step is to': $tmp = 'Im n�chsten Schritt'; break;
    case 'This action can not be made at this time.': $tmp = 'Diese Aktion kann zum jetzigen Zeitpunkt nicht ausgef�hrt werden.'; break;
    case 'This is all over now. You have been logged out': $tmp = 'Sie sind jetzt ausgeloggt'; break;
    case 'This is all over now. You have been logged out': $tmp = 'Sie sind jetzt ausgeloggt'; break;
    case 'This is interesting if you are a sponsor and you are interested in joining this project': $tmp = 'Dies ist von Interesse wenn Sie ein Sponsor sind und dieses Projekt unterst�tzen m�chten'; break;
    case 'This is the chronological list of all the actions that have affected the current project': $tmp = 'Dies ist die chronologische Liste aller Aktionen, die im Projekt durchgef�hrt wurden'; break;
    case 'This is your personal page': $tmp = 'Dies ist Ihre pers�nliche Seite'; break;
    case 'This project does not require any consultants': $tmp = 'Dieses Projekt ben�tigt keinen Berater'; break;
    case 'The project parameters have not been configured by project owner(s)': $tmp = 'Die Projektparameter wurden noch nicht von den Projekteignern konfiguriert'; break;
    case 'Type': $tmp = 'Typ'; break;
    case 'Unknown': $tmp = 'Unbekannt'; break;
    case 'User Administration': $tmp = 'Benutzer Administration'; break;
    case 'Username': $tmp = 'Benutzername'; break;
    case 'User': $tmp = 'Benutzer'; break;
    case 'User Change': $tmp = 'Benutzer�nderung'; break;
    case 'Users': $tmp = 'Benutzer'; break;
    case 'Users that are monitoring this project': $tmp = 'Benutzern, die das Projekt derzeit beobachten'; break;
    case 'Users that monitor this Project': $tmp = 'Benutzern, die das Projekt beobachten'; break;
    case 'Valid until': $tmp = 'G�ltig bis'; break;
    case 'View Project Configuration': $tmp = 'Anzeige der Projektkonfiguration'; break;
    case 'View Project History': $tmp = 'Anzeige der Projekthistorie'; break;
    case 'View Step 3 (Milestones)': $tmp = 'Anzeige von Schritt 3 (Meilensteine)'; break;
    case 'View Step 4 (Referees)': $tmp = 'Anzeige von Schritt 4 (Sachverst�ndige)'; break;
    case 'View Step 5 (Project Follow-up)': $tmp = 'Anzeige von Schritt 5 (Projektdurchf�hrung)'; break;
    case 'View Developing Cooperation Proposals': $tmp = 'Anzeige von Entwicklerkooperationsvorschl�gen'; break;
    case 'Project Permission Access': $tmp = 'Projektzugriffsrechte'; break;
    case 'Valid': $tmp = 'G�ltig bis'; break;
    case 'Validity': $tmp = 'G�ltigkeit'; break;
    case 'Volume': $tmp = 'Umfang'; break;
    case 'Week(s)': $tmp = 'Woche(n)'; break;
    case 'Welcome to SourceAgency': $tmp = 'Willkommen bei SourceAgency'; break;
    case 'Welcome to ': $tmp = 'Willkommen bei '; break;
    case 'What is this?': $tmp = 'Was ist das?'; break;
    case 'When the project is reviewed this will also change': $tmp = 'Wenn das Projekt begutachtet wurde, dann �ndert sich auch der Status'; break;
    case 'Write and Modify News': $tmp = 'Erstellen und &Auml;ndern von Neuigkeiten'; break;
    case 'Write Comments': $tmp = 'Erstellen von Kommentaren'; break;
    case 'Yes': $tmp = 'Ja'; break;
    case 'You are logged in as': $tmp = 'Sie sind einloggt als'; break;
    case 'You are registrated as sponsor': $tmp = 'Sie sind als Sponsor registriert'; break;
    case 'You do not have rights to enter this page.': $tmp = 'Sie besitzen keine Rechte, um auf diese Seite zu gehen.'; break;
    case 'You have been logged in as': $tmp = 'Sie waren eingeloggt als'; break;
    case 'You have just configured your project': $tmp = 'Sie haben gerade Ihr Projekt konfiguriert'; break;
    case "You'll find here all what has been posted to the project (news, comments, content suggestions, etc.) in chronological order": $tmp = 'Alle Neuigkeiten, Kommentare, usw. sind in zeitliche Reihenfolge hier aufgeliste'; break;
    case 'You are finished with the project definition process': $tmp = 'Sie haben den Projektdefinitionsprozess abgeschlossen'; break;
    case 'Your authentication is valid until': $tmp = 'Ihre Authentifikation ist g�ltig bis'; break;
    case 'Your authentication was valid until': $tmp = 'Ihre Authentifikation war g�ltig bis'; break;
    case 'Your Comment': $tmp = 'Ihr Kommentar'; break;
    case 'Your username': $tmp= 'Ihr Benutzername';break;
    case 'You have not yet confirmed your account. Please check your e-mail, there should be a message that contains a confirmation link. Only after you have clicked this link you can start using SourceAgency.' : $tmp = 'Sie haben Ihren Zugang noch nicht aktiviert. Bitte pr�fen Sie Ihr Postfach, Sie sollten eine Nachricht erhalten haben, die einen Aktivierungs-Link (URL) enth�lt. Erst nach Anklicken dieses Links k�nnen Sie ihren SourceAgency-Zugang nutzen.'; break;
    case 'You will now have to wait for a SourceAgency editor to review your pending project': $tmp = 'Sie m�ssen sich nun gedulden, bis ein SourceAgency Editor Ihr Projekt begutachtet hat'; break;
    case 'You will receive an e-mail for every action in this project that has the importance you specify': $tmp = 'Sie erhalten eine E-Mail bei jeder Aktion des Projekts, die der angegebenen Wichtigkeit entspricht'; break;
    case 'You will recieve an email for every action in this project that has': $tmp = 'Sie erhalten eine E-Mail bei jeder Aktion des Projekts von'; break;
    case 'accepted sponsoring collaborations': $tmp = 'angenommmene finanzielle F�rderungen'; break;
    case 'by': $tmp = 'von'; break;
    case 'by registered users of the system': $tmp = 'von registrierten Benutzern'; break;
    case 'comments': $tmp = 'Kommentare'; break;
    case 'comments on it': $tmp = 'Kommentare'; break;
    case 'configure': $tmp = 'Konfigurieren'; break;
    case 'configure your project!': $tmp = 'konfigurieren Sie bitte Ihr Projekt!'; break;
    case 'consultant offerings': $tmp = 'Beratungsangebote'; break;
    case 'consultants': $tmp = 'Berater'; break;
    case 'created': $tmp = 'angelegt'; break;
    case 'devel': $tmp = 'Entwickler'; break;
    case 'developing proposals': $tmp = 'Entwicklungsangebote'; break;
    case 'feedback': $tmp = 'R�ckmeldung'; break;
    case 'general comments': $tmp = 'Allgemeine Kommentare'; break;
    case 'high': $tmp = 'hoch'; break;
    case 'history': $tmp = 'Historie'; break;
    case 'importance': $tmp = 'Wichtigkeit'; break;
    case 'in': $tmp = $phrase; break;
    case 'low': $tmp = 'gering'; break;
    case 'means you will receive all the events that happen to this project (high traffic)': $tmp = 'bedeutet, dass Sie bei jedem Ereignis eine Benachrichtigung erhalten (hoher Verkehr)'; break;
    case 'means you will receive only an e-mail whenever a very important event happens to this project (low traffic)': $tmp = 'bedeutet, dass Sie nur bei sehr wichtigen Ereignissen eine Benachrichtigung erhalten (geringer Verkehr)'; break;
    case 'medium': $tmp = 'mittel'; break;
    case 'milestones': $tmp = 'Meilensteine'; break;
    case 'milestones fulfilled': $tmp = 'Meilensteinen erreicht'; break;
    case 'milestones of totally': $tmp = 'Meilensteine von insgesamt'; break;
    case 'monitor': $tmp = 'beobachten'; break;
    case 'news for this project': $tmp = 'Projekt-Neuigkeiten'; break;
    case 'news': $tmp = 'Neuigkeiten'; break;
    case 'news_edit': $tmp = 'Editieren von Neuigkeiten'; break;
    case 'no comments posted': $tmp = 'Keine Kommentare'; break;
    case 'no news posted': $tmp = 'Keine Neuigkeiten'; break;
    case 'Nobody is monitoring this project': $tmp = 'Keiner beobachtet derzeit das Projekt'; break;
    case 'non-accepted sponsoring proposals for this project': $tmp = 'nicht angenommmene Finanzierungangebote f�r dieses Projekt'; break;
    case 'on': $tmp = 'am'; break;
    case 'on it': $tmp = ''; break;
    case 'on project': $tmp = 'an Projekt'; break;
    case 'other comments on this project': $tmp = 'andere Kommentare zu diesem Projekt'; break;
    case 'permission': $tmp = 'Berechtigung'; break;
    case 'please send any and all': $tmp = 'bitte senden Sie uns eine'; break;
    case 'posted to': $tmp = 'geschickt zu'; break;
    case 'project': $tmp = 'Projekt'; break;
    case 'project_name': $tmp = 'Projektname'; break;
    case 'rated': $tmp = 'bewertet'; break;
    case 'referees offered': $tmp = 'sich anbietende Sachvers�ndige'; break;
    case 'specifications': $tmp = 'Spezifikationen'; break;
    case 'sponsor': $tmp = 'Sponsor'; break;
    case 'sponsoring': $tmp = 'Finanzielle Unterst�tzung'; break;
    case 'sponsoring_edit': $tmp = 'Finanzielle F�rderung'; break;
    case 'status': $tmp = 'Status'; break;
    case 'step': $tmp = 'Schritt'; break;
    case 'step1_edit': $tmp = 'Neue Berater vorschlagen'; break;
    case 'step3_edit': $tmp = 'Neue Meilensteine definieren'; break;
    case 'suggested milestones': $tmp = 'vorgeschlagene Meilensteine'; break;
    case 'suggested specifications': $tmp = 'vorgeschlagene Spezifikationen'; break;
    case 'summary': $tmp = 'Zusammenfassung'; break;
    case 'this project is configured to have no consultants': $tmp = 'Die Konfigurartion dieses Projekts erlaubt keine Berater'; break;
    case 'This site is being beta tested': $tmp = 'Diese Website befindet sich im Beta-Test'; break;
    case 'tech_content': $tmp = 'Technische Spez.'; break;    
    case 'developing': $tmp = 'Entwicklung'; break;    
    case 'times': $tmp = 'mal'; break;
    case 'to this': $tmp = 'zu diesem'; break;
    case 'views': $tmp = 'Zugriffrechte auf Projektinformationen'; break;
    case 'with': $tmp = 'mit'; break;
    case 'you will be now able to see your project in Step 0 (pending)': $tmp = 'erscheint jetzt Ihr Projekt im Status Schritt 0 (wartend)'; break;

    case 'January':$tmp='Januar';break;
    case 'February':$tmp='Februar';break;
    case 'March':$tmp='M�rz';break;
    case 'April':$tmp='April';break;
    case 'May':$tmp='Mai';break;
    case 'June':$tmp='Juni';break;
    case 'July':$tmp='Juli';break;
    case 'August':$tmp='August';break;
    case 'September':$tmp='September';break;
    case 'October':$tmp='Oktober';break;
    case 'November':$tmp='November';break;
    case 'December':$tmp='Dezember';break;

    /** explanations for the various steps a project has **/
    case 'step_0_explanation':
        $tmp = 'Bitte warten Sie bis ein SourceAgency Editor Ihr Projekt begutachtet hat.'; 
        break;
    case 'step_1_explanation':
        $tmp = 'Hilfe f�r Sponsoren, um einen korrekten Projektverlauf zu gew�hrleisten.'; 
        break;
    case 'step_2_explanation':
        $tmp = ( 'Dieses Projekt kann verschiedene Spezifikationen besitzen. '
                 .'Entwickler k�nnen ihre eigenen Entwicklungsangebote '
                 .'zu den Spezifikationen abgeben.' );
        break;
    case 'step_3_explanation':
        $tmp = 'Von den Entwicklern angebotene Projektmeilensteine.';
        break;
    case 'step_4_explanation':
        $tmp = ( 'Entwickler bieten sich als Sachverst�ndige an. '
                 .'Sponsoren und Entwickler des Projektes entscheiden sich f�r einen Sachverst�ndigen.' );
        break;
    case 'step_5_explanation':
         $tmp = ( 'Meilensteinabgabe und andere Aufgaben, die mit der Projektdurchf�hrung zu tun haben.' );
        break;
    case 'step_6_explanation':
        $tmp = 'Schlussbewertung zwischen Sponsoren und Entwickler.';
        break;

    /* Languages */
    case 'English': $tmp = 'English'; break;
    case 'German': $tmp = 'Deutsch'; break;
    case 'Spanish': $tmp = 'Espa&ntilde;ol'; break;
    case 'French': $tmp = 'Fran&ccedil;ais'; break;
       
    default:
        $tmp = $phrase; 
        $l = new Logger;
        $l->log( "translating (German unknown) '" . $phrase . "'" );
        break;
    }
    return($tmp);
}

# function for translating complex strings in case statements
# usage:
#
# case strpr($phrase,'/I am looking for .*/'):
#  $tmp=preg_replace($search,$replace,$phrase);
#  break;
function strpr($phrase,$regex) {
	
	if(preg_match($regex,$phrase)) {
		return $phrase;
	}
	return 'abc123456';	#unique string that is never found
}
?>
