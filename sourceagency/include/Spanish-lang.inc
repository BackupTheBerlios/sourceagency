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
# This contains the Spanish translation of the SourceAgency portal.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: Spanish-lang.inc,v 1.3 2002/05/07 10:28:53 grex Exp $
#
######################################################################

if ( function_exists( "translate_real" ) ) {
  return;
}

function translate_real($phrase) {
    switch ( $phrase ) {

        case 'About us': $tmp = 'Sobre BerliOS'; break;
        case 'Action': $tmp = 'Acci&oacute;n'; break;
        case 'Actions by involved users': $tmp = 'Acciones por usuarios involucrados'; break;
        case 'AdmDoco': $tmp = 'Administraci&oacute;n de documentación'; break;
        case 'Administration': $tmp = 'Administración'; break;
        case 'All': $tmp = 'Todos'; break;
        case 'All Comments by': $tmp = 'Todos los comentarios de'; break;
        case 'All the rating on ': $tmp = 'Todas las puntuaciones a'; break;
        case 'An error has ocurred': $tmp = 'Ha ocurrido un error'; break;
        case 'Answer': $tmp = 'Respuesta'; break;
        case 'Architecture': $tmp = 'Arquitectura'; break;
        case 'Backend': $tmp = 'Backend'; break;
        case 'Be informed': $tmp = 'Esté informado'; break;
        case 'Brief description': $tmp = 'Descripción corta'; break;
        case 'Browse': $tmp = 'Navegue'; break;
        case 'Categories': $tmp = 'Categorías'; break;
        case 'Change User Info': $tmp = 'Modificar información de usuario'; break;
        case 'Change User Parameters': $tmp = 'Modificar parámetros de usuario'; break;
        case 'Change User': $tmp = 'Modificar usuario'; break;
        case 'Change a License URL': $tmp = 'Modificar URL de licencia'; break;
        case 'Change': $tmp = 'Modificar'; break;
        case 'Comments': $tmp = 'Comentarios'; break;
        case "Configure the information access to the project's data": $tmp = "Configure el acceso a la información para este proyecto"; break;
        case 'Configure the project parameters': $tmp = 'Configure los parámetros para este proyecto'; break;
        case 'Confirm Password': $tmp = 'Confirme la contraseña'; break;
        case 'Consultancy': $tmp = 'Consultoría'; break;
        case 'Consultants Wanted': $tmp = 'Se buscan consultores'; break;
        case 'Contact': $tmp = 'Contacto'; break;
        case 'Create User': $tmp = 'Crear usuario'; break;
        case 'Creation': $tmp = 'Creación'; break;
        case 'Current project budget': $tmp = 'Presupuesto actual del proyecto'; break;
        case 'Decisions on this step': $tmp = 'Decisiones en este paso'; break;
        case 'Delete a License': $tmp = 'Borrar licencia'; break;
        case 'Delete': $tmp = 'Borrar'; break;
        case 'Description': $tmp = 'Descripción'; break;
        case 'Developer Cooperation Wanted': $tmp = 'Se busca colaboración por parte de desarrolladores'; break;
        case 'Developers Wanted': $tmp = 'Se buscan desarrolladores'; break;
        case 'Developers': $tmp = 'Desarrolladores'; break;
        case 'Developing': $tmp = 'En desarrollo'; break;
        case 'Developing Cooperation': $tmp = 'Cooperación en el desarrollo'; break;
        case 'E-Mail': $tmp = 'Correo-e'; break;
        case 'Either your Username or Password are invalid': $tmp = 'El par nombre de usuario - contraseña que ha introducido es incorrecto'; break;
        case 'English': $tmp = 'Inglés'; break;
        case 'Enter a New Frequently Asked Question': $tmp = 'Introducir nueva pregunta frecuente'; break;
        case 'Enter a new documentation entry for a page': $tmp = 'Introducir nueva documentaci&oacute;n para una p&aacute;gina'; break;
        case 'Environment': $tmp = 'Entorno'; break;
        case 'Error': $tmp = 'Error'; break;
        case 'Expires': $tmp = 'Caduca'; break;
        case 'Finished projects': $tmp = 'Proyectos finalizados'; break;
        case 'Forgot Password': $tmp = 'Contraseña olvidada'; break;
        case 'French': $tmp = 'Franc&eacute;s'; break;
        case 'Frequently Asked Questions Administration': $tmp = 'Administraci&oacute;n de Preguntas Frecuentes'; break;
        case 'Frequently Asked Questions': $tmp = 'Preguntas Frecuentes'; break;
        case 'General Actions (anytime and anywhere)': $tmp = 'Acciones generales (realizables en cualquier momento)'; break;
        case 'General Comments': $tmp = 'Comentarios generales'; break;
        case 'General comments can be posted': $tmp = 'Se pueden mandar comentarios generales'; break;
        case 'German': $tmp = 'Alem&aacute;n'; break;
        case 'Global personal rating: ': $tmp = 'Puntuación personal global'; break;
        case 'Go': $tmp = 'Ir'; break;
        case 'Hi': $tmp = 'Hola'; break;
        case 'Here you have to enter all the <b>project related</b> data. This should only give an idea to other people what you are planning.': $tmp = 'Por favor, describa a continuación el proyecto. Esta información ayudará a otras personas que se quieran informar sobre este proyecto.'; break;
        case 'Home': $tmp = 'Inicio'; break;
        case 'If you are not yet a registered User, please select': $tmp = 'Si no es un usuario registrado, por favor, escoja'; break;
        case "In this page you'll find all the information related to you": $tmp = 'En esta p&aacute;gina encontrar&aacute; toda la informaci&oacute;n relacionada con usted'; break;
        case 'Index': $tmp = '&Iacute;ndice'; break;
        case 'Info box': $tmp = 'Tabla de informaci&oacute;n'; break;
        case 'Information access configuration': $tmp = 'Configuraci&oacute;n de los permisos de acceso a la informaci&oacute;n'; break;
        case 'Insert a License': $tmp = 'Insertar una licencia'; break;
        case 'Insert': $tmp = 'Insertar'; break;
        case 'Language': $tmp = 'Idioma'; break;
        case 'Last 10 Comments by': $tmp = '&Uacute;ltimos 10 comentarios por'; break;
        case 'Last 5 News by': $tmp = '&Uacute;ltimas 5 noticias por'; break;
        case 'Latest News': $tmp = '&Uacute;ltimas noticias'; break;
        case 'License Administration': $tmp = 'Administraci&oacute;n de licencias'; break;
        case 'License URL': $tmp = 'URL de la licencia'; break;
        case 'License description is not available': $tmp = 'La descripci&oacute;n de esta licencia no est&aacute; disponible'; break;
        case 'License': $tmp = 'Licencia'; break;
        case 'Licenses': $tmp = 'Licencias'; break;
        case 'Logged in': $tmp = 'Logged in'; break;
        case 'Login': $tmp = 'Login'; break;
        case 'Logout': $tmp = 'Logout'; break;
        case 'Milestones': $tmp = 'Hitos'; break;
        case 'Modification': $tmp = 'Modificaci&oacute;n'; break;
        case 'Monitor This Project': $tmp = 'Seguir este proyecto'; break;
        case 'Monitored Projects': $tmp = 'Proyectos seguidos'; break;
        case 'My Projects': $tmp = 'Mis proyectos'; break;
        case 'New Frequently Asked Question': $tmp = 'Nueva Pregunta Frecuente'; break;
        case 'New License Name': $tmp = 'Nuevo Nombre de Licencia'; break;
        case 'New License URL': $tmp = 'Nueva URL de Licencia'; break;
        case 'New License': $tmp = 'Nueva Lizencia'; break;
        case 'New Project': $tmp = 'Nuevo Proyecto'; break;
        case 'New Projects': $tmp = 'Nuevos Proyectos'; break;
        case 'New User': $tmp = 'Nuevo Usuario'; break;
        case 'News': $tmp = 'Noticias'; break;
        case 'Newsletter': $tmp = 'Lista de Noticias'; break;
        case 'No consultant proposal with this status': $tmp = 'No existen propuestas de consultores con este estado'; break;
        case 'No consultants wanted': $tmp = 'No se requieren consultores'; break;
        case 'No developing cooperation proposal with this status': $tmp = 'No existen propuestas de colaboraciones en el desarrollo con este estado'; break;
        case 'No monitored projects': $tmp = 'No se sigue ning&uacute;n proyecto'; break;
        case 'No personal projects': $tmp = 'No existen proyectos personales'; break;
        case 'No rating yet': $tmp = 'No ha sido puntuado todav&iacute;a'; break;
        case 'No': $tmp = 'No'; break;
        case 'No.': $tmp = 'N&uacute;m.'; break;
        case 'Not Logged in': $tmp = 'No se ha autentificado'; break;
        case 'Not rated yet': $tmp = 'Todav&iacute;a no ha sido puntuado'; break;
        case 'Not related to any project with': $tmp = 'No est&aacute; relacionado con ning&uacute;n proyecto'; break;
        case 'Open Source Project Mediation & Management': $tmp = 'Mediaci&oacute;n y Gesti&oacute;n de proyectos Open Source'; break;
        case 'Open Source Software Projects': $tmp = 'Proyectos Open Source'; break;
        case 'Our apologies. Documentation only available in English.': $tmp = 'Rogamos acepten nuestras disculpas. La documentaci&oacute;n no se encuentra disponible en español.'; break;
        case 'Page Documentation Administration': $tmp = 'Administraci&oacute;n de la documentaci&oacute;n de las p&aacute;ginas'; break;
        case 'Partners': $tmp = ''; break;
        case 'Password': $tmp = 'Contraseña'; break;
        case 'Pending Proj.': $tmp = 'Proyectos en espera'; break;
        case 'Permission denied': $tmp = 'Permiso denegado'; break;
        case 'Permission': $tmp = 'Permiso'; break;
        case 'Personal Page': $tmp = 'Página personal'; break;
        case 'Platform': $tmp = 'Plataforma'; break;
        case 'Please try again': $tmp = 'Por favor, inténtelo otra vez'; break;
        case 'Project': $tmp = 'Proyecto'; break;
        case 'Project Follow-up': $tmp = 'Seguimiento del proyecto'; break;
        case 'Project History': $tmp = 'Historia del proyecto'; break;
        case 'Project Information': $tmp = 'Información del proyecto'; break;
        case 'Project Insertion process': $tmp = 'Proceso de inserción de un proyecto'; break;
        case 'Project Name': $tmp = 'Nombre del proyecto'; break;
        case 'Project Nature': $tmp = 'Naturaleza del proyecto'; break;
        case 'Project Owner(s)': $tmp = 'Propietario del proyecto'; break;
        case 'Project Title': $tmp = 'Título del proyecto'; break;
        case 'Project Type': $tmp = 'Tipo de proyecto'; break;
        case 'Project Volume': $tmp = 'Volumen del proyecto'; break;
        case 'Project configurations': $tmp = 'Configuraciones del proyecto'; break;
        case 'Projects ordered alphabetically': $tmp = 'Proyectos ordenados alfabéticamente'; break;
        case 'Projects': $tmp = 'Proyectos'; break;
        case 'Q&amp;A': $tmp = 'P&amp;R'; break;
        case 'Question': $tmp = 'Pregunta'; break;
        case 'Rated': $tmp = 'Puntuado'; break;
        case 'Rating': $tmp = 'Puntuación'; break;
        case 'Realname': $tmp = 'Nombre'; break;
        case 'Receive an email update when something happens in this project': $tmp = 'Reciba una notificación de suceso en este proyecto vía correo electrónico'; break;
        case 'Referees Wanted': $tmp = 'Se buscan árbitros'; break;
        case 'Referees': $tmp = 'Árbitros'; break;
        case 'Register as a new User': $tmp = 'Regístrese como usuario'; break;
        case 'Register': $tmp = 'Registrarse'; break;
        case 'Remind me': $tmp = 'Recordar'; break;
        case 'Rename a License': $tmp = 'Cambiar nombre de licencia'; break;
        case 'Rename': $tmp = 'Renombrar'; break;
        case 'Search': $tmp = 'Buscar'; break;
        case 'See all the comments': $tmp = 'Ver todos los comentarios'; break;
        case 'See complete ratings...': $tmp = 'Ver todas las puntuaciones'; break;
        case 'SourceAgency Login': $tmp = 'Login en SourceAgency'; break;
        case 'SourceAgency': $tmp = 'SourceAgency'; break;
        case 'Spanish': $tmp = 'Español'; break;
        case 'Sponsor Collaboration': $tmp = 'Colaboración entre patrocinadores'; break;
        case 'Sponsor Involvement': $tmp = 'Patrocinio'; break;
        case 'Sponsor this project': $tmp = 'Patrocine este proyecto'; break;
        case 'Sponsoring collaboration': $tmp = 'Colaboración patrocinadora'; break;
        case 'Sponsoring': $tmp = 'Patrocinio'; break;
        case 'Sponsors Wanted': $tmp = 'Se buscan patrocinadores'; break;
        case 'Sponsors can make the current decision following this link': $tmp = 'Los patrocinadores pueden seguir este enlace para llevar a cabo decisiones'; break;
        case 'Sponsors': $tmp = 'Patrocinadores'; break;
        case 'Step information': $tmp = 'Informaciones sobre este paso'; break;
        case 'Step specific actions (only at its time)': $tmp = 'Acciones específicas de un paso (sólo en su debido momento)'; break;
        case 'Step': $tmp = 'Paso'; break;
        case 'Steps': $tmp = 'Pasos'; break;
        case 'Submit News': $tmp = 'Enviar noticias'; break;
        case 'Summary': $tmp = 'Resumen'; break;
        case 'System Credits': $tmp = 'Créditos'; break;
        case 'Technical Content': $tmp = 'Contenido técnico'; break;
        case 'Technical Specifications': $tmp = 'Especificaciones técnicas'; break;
        case 'There have not been posted any news by the project owner(s)': $tmp = 'Los propietarios del proyecto no han puesto ninguna noticia'; break;
        case 'This is all over now. You have been logged out': $tmp = 'Su sesión ha terminado.'; break;
        case 'This is interesting if you are a sponsor and you are interested in joining this project': $tmp = 'Esto puede ser interesante si usted es un patrocinador y está interesado en formar parte de este proyecto'; break;
        case 'This is your personal page.': $tmp = 'Ésta es su página personal'; break;
        case 'Type': $tmp = 'Tipo'; break;
        case 'Unknown': $tmp = 'Desconocido'; break;
        case 'User Administration': $tmp = 'Administraci&oacute;n de usuarios'; break;
        case 'Username': $tmp = 'Nombre de usuario'; break;
        case 'Users': $tmp = 'Usuarios'; break;
        case 'Volume': $tmp = 'Volumen'; break;
        case 'Welcome to SourceAgency': $tmp = 'Bienvenido a SourceAgency'; break;
        case 'What is this?': $tmp = '¿Qué es esto?'; break;
        case 'You are logged in as': $tmp = 'Se ha autentificado como'; break;
        case 'You have been logged in as': $tmp = 'Estaba autentificado como'; break;
        case "You'll find here all what has been posted to the project (news, comments, content suggestions, etc.) in chronological order": $tmp = 'Aquí encontrará todo lo que está relacionado con el proyecto (noticias, comentarios, propuestas de contenidos, etc.)'; break;
        case 'Your authentication is valid until': $tmp = 'Su autentificación es válida hasta'; break;
        case 'Your authentication was valid until': $tmp = 'Su autentificación era válida hasta'; break;
        case 'accepted sponsoring collaborations': $tmp = 'patrocinadores aceptados'; break;
        case 'by': $tmp = 'por'; break;
        case 'comments': $tmp = 'comentarios'; break;
        case 'comments on it': $tmp = 'comentarios'; break;
        case 'configure': $tmp = 'configurar'; break;
        case 'consultants': $tmp = 'Consultor'; break;
        case 'content suggestions': $tmp = 'Propuestas de contenido'; break;
        case 'feedback': $tmp = 'Realimentación'; break;
        case 'general comments': $tmp = 'Comentarios generales'; break;
        case 'history': $tmp = 'Historia'; break;
        case 'in': $tmp = 'en'; break;
        case 'milestones': $tmp = 'hitos'; break;
        case 'news for this project': $tmp = 'noticias en este proyecto'; break;
        case 'news': $tmp = 'noticias'; break;
        case 'news_edit': $tmp = 'Edición de noticias'; break;
        case 'no comments posted': $tmp = 'No se han puesto comentarios'; break;
        case 'no news posted': $tmp = 'No se han puesto noticias'; break;
        case 'non-accepted sponsoring proposals for this project': $tmp = 'No hay propuestas no aceptadas de patrocinio para este proyecto'; break;
        case 'on project': $tmp = 'en el proyecto'; break;
        case 'other comments on this project': $tmp = 'Otros comentarios a este proyecto'; break;
        case 'permission': $tmp = 'permiso'; break;
        case 'posted to': $tmp = 'enviado a'; break;
        case 'project': $tmp = 'proyecto'; break;
        case 'project_name': $tmp = 'nombre de proyecto'; break;
        case 'rated': $tmp = 'puntuado'; break;
        case 'sponsoring': $tmp = 'patrocinio'; break;
        case 'status': $tmp = 'estado'; break;
        case 'step': $tmp = "paso"; break;
        case 'step1_edit': $tmp = 'Proposici&oaacute;n de consultores'; break;
        case 'step3_edit': $tmp = 'Definici&oacute;n de hitos'; break;
        case 'summary': $tmp = 'Resumen'; break;
        case 'times': $tmp = 'veces'; break;
        case 'to this': $tmp = 'a este'; break;
        case 'views': $tmp = 'Acceso a informaci&oacute;n del proyecto'; break;
        case 'with': $tmp = 'con'; break;

            /** explanations for the various steps a project has **/
        case "step_1_explanation": {
            $tmp = 'Ayude a que los patrocinadores puedan tener un proceso correcto.';
            break;
        }
        case "step_2_explanation": {
            $tmp = ( "Este proyecto puede tener diferentes especificaciones. "
                     ."Los desarrolladores pueden hacer sus porpuestas de desarrollo "
                     ."a esas especificaciones." );
            break;
        }
        case "step_3_explanation": {
            $tmp = "Hitos del proyecto propuestas por los desarrolladores.";
            break;
        }
        case "step_4_explanation": {
            $tmp = ( "Los desarrolladores pueden proponerse como &aacute;rbitros. Los "
                     ."patrocinadores y los desarrolladores del proyecto elegir&aacute;n "
                     ."a uno de ellos.");
            break;
        }
        case "step_5_explanation": {
            $tmp = ( "Entrega de hitos y otras tareas relacionadas con el"
                     ."seguimiento del proyecto." );
            break;
        }
        case "step_6_explanation": {
            $tmp = "Puntuaci&oacute;n final entre desarrolladores y patrocinadores.";
            break;
        }
        
        default: {
            $tmp = "$phrase"; 
            $l = new Logger;
            $l->log( "translating (Spanish unknown) '" . $phrase . "'" );
            break;
        }
    }
    return($tmp);
}
?>
