<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ================================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org) and
#		 Lutz Henckel (lutz.henckel@fokus.gmd.de)
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the configuration file
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: config.inc,v 1.21 2002/04/08 21:32:47 grex Exp $
#
######################################################################

######################################################################
# System Config
#
# sys_name:		Name of the System
# sys_title:		Your site's slogan
# sys_url_title		URL of the system's portal
# sys_url		System URL
# sys_logo_image	Image of your Site
# sys_logo_alt		Alternative text for your site's image
# sys_logo_width	Width of the image of your site
# sys_logo_height	Heigth of the image of your site
#
# (don't forget to put slashes at the end of the URLs)
######################################################################

$sys_name = "SourceAgency";
$sys_title = "Open Source Project Mediation & Management";
$sys_url_title = "http://www.berlios.de/";
$sys_url = "http://sourceagency.berlios.de/beta/";
$sys_logo_image = "images/berliOS_logo.png";
$sys_logo_alt = "BerliOS";
$sys_logo_width = "238";
$sys_logo_height = "61";

######################################################################
# Organisation Config
#
# org_name	   Name of your Organisation
# org_url	   URL of your Organisation
# org_logo_image   Image of your Organisation
# org_logo_alt	   Alternative text for the image of your Organisation
# org_logo_width   Width of the image of your Organisation
# org_logo_height  Height of the image of your Organisation
######################################################################

$org_name = "FOKUS";
$org_url = "http://www.fokus.fhg.de/";
$org_logo_image = "images/logo_fokus.png";
$org_logo_alt = $org_name;
$org_logo_width = "60";
$org_logo_height = "60";

######################################################################
# Top Strip
#
# You can change (add, delete, modify) the top strip
# as you wish as long as you mantain this syntax:
#
#         "Title"	=> $sys_url_title."URLofYourTitle",
#
# (don't forget that the last one hasn't got a comma!)
#
######################################################################

$ts_array = array (
	"Home"		=> $sys_url_title."index.php",
	"About us"	=> $sys_url_title."about/index.php",
	"Partners"	=> $sys_url_title."partners/index.php",
	"Contact"	=> $sys_url_title."contact/index.php"
);

######################################################################
# Languages
#
# List of languages supported by your website
# You can add/delete/modify as long as you mantain the syntax
# New languages are always wellcome. Contact with the authors!
#
######################################################################

// WISH: this should obtain the list of languages from the *-lang.inc
// WISH: files in the include directory. But this might be too expensive
// WISH: since this file is read in for each page .... further: it might
// WISH: be possible to do this and store the results in an environmental
// WISH: variable, ofcourse updating the variable once set would be a problem
$la_array[] = "English";
$la_array[] = "German";
# $la_array[] = "Spanish";

######################################################################
# Step names and count
#
# you give each step a name here, but the descriptions are (at this
# stage) hardcoded in security.inc::allowed_actions
# Also changing the number of steps doesn't change anything: 6 steps
# is also pretty much hardcoded.
#
# The texts are sent through the translator before being used on the page.
#
######################################################################

$g_step_text[-1] = "Deleted";
$g_step_text[0] = "Configuration";
$g_step_text[1] = "Consultancy";
$g_step_text[2] = "Technical Specifications";
$g_step_text[3] = "Milestones";
$g_step_text[4] = "Referees";
$g_step_text[5] = "Project Follow-up";
$g_step_text[6] = "Rating";

$g_step_explanation[-1] = "step_-1_explanation";   
$g_step_explanation[0] = "step_0_explanation";
$g_step_explanation[1] = "step_1_explanation";
$g_step_explanation[2] = "step_2_explanation";
$g_step_explanation[3] = "step_3_explanation";
$g_step_explanation[4] = "step_4_explanation";
$g_step_explanation[5] = "step_5_explanation";
$g_step_explanation[6] = "step_6_explanation";

$g_step_count = 6;

######################################################################
# Project Types
#
# List of project types
# You can add/delete/modify as long as you mantain the syntax
#
######################################################################

$project_types[] = "Adaption";
$project_types[] = "Expansion";
$project_types[] = "Development";
$project_types[] = "Documentation";
$project_types[] = "Other";

######################################################################
# Project Volumes
#
# List of project volumes
# You can add/delete/modify as long as you mantain the syntax
#
######################################################################

$project_volume[] = "< 0.25 Man Month (5 Man Days)";
$project_volume[] = "< 1 Man Month";
$project_volume[] = "< 2 Man Months";
$project_volume[] = "< 6 Man Months";
$project_volume[] = "> 6 Man Months";

######################################################################
# Platforms
#
# List of platforms
# this is assumed to be the operating system on a hardware configuration,
# if this becomes too long, think about split hardware and OS.
#
# You can add/delete/modify as long as you mantain the syntax
#
######################################################################

$platform_array[] = "Linux";
$platform_array[] = "Solaris";
$platform_array[] = "SunOS";
$platform_array[] = "FreeBSD";
$platform_array[] = "Windows XP";
$platform_array[] = "Windows 2000";
$platform_array[] = "Windows NT";
$platform_array[] = "Windows 3.X";
$platform_array[] = "HPUX";
$platform_array[] = "MSDOS";
$platform_array[] = "Platform independent";
######################################################################
# Architectures
#
# List of architectures
# You can add/delete/modify as long as you mantain the syntax
#
######################################################################

$architecture_array[] = "x86";
$architecture_array[] = "Sparc";
$architecture_array[] = "HP";
$architecture_array[] = "Apple";
$architecture_array[] = "IBM";
$architecture_array[] = "CHAIR";
$architecture_array[] = "Architecture Independent";

######################################################################
# Environments
#
# List of environments
# You can add/delete/modify as long as you mantain the syntax
#
######################################################################

$environment_array[] = "Web";
$environment_array[] = "Server";
$environment_array[] = "Client";
$environment_array[] = "Distributed";
#$environment_array[] = "";
#$environment_array[] = "";
#$environment_array[] = "";
#$environment_array[] = "";

######################################################################
# Monitoring Importance
#
# List of types with certain monitoring importance
# You can add/delete/modify as long as you mantain the syntax
#
######################################################################

$config_monitor_importance_comments = "middle";
$config_monitor_importance_news = "high";
$config_monitor_importance_next_step = "high";
$config_monitor_importance_decision = "low";
$config_monitor_importance_decision_milestone = "low";
$config_monitor_importance_decision_step_5 = "low";
$config_monitor_importance_sponsoring = "high";
$config_monitor_importance_developing = "high";
$config_monitor_importance_referee = "high";
$config_monitor_importance_consultant = "high";
$config_monitor_importance_milestone_delivery = "high";
$config_monitor_importance_monitor = "low";
$config_monitor_importance_configure = "middle";

/*
$config_monitor_importance_ = "";
$config_monitor_importance_ = "";
$config_monitor_importance_ = "";
*/

######################################################################
# Milestone Product
#
# List of milestone products
# You can add/delete/modify as long as you mantain the syntax
#
######################################################################

$milestone_product_array[] = "Prototype";
$milestone_product_array[] = "Developing Version";
$milestone_product_array[] = "Alpha";
$milestone_product_array[] = "Beta";
$milestone_product_array[] = "Release Candidate";
$milestone_product_array[] = "Final";

######################################################################
# Developer rating skills by Sponsor
#
# List of skills a developer is rated by a sponsor
# You can add/delete/modify as long as you mantain the syntax
#
######################################################################

$sponsor_rates_developer[] = "one";
$sponsor_rates_developer[] = "two";
$sponsor_rates_developer[] = "three";

$developer_rates_sponsor[] = "uno";
$developer_rates_sponsor[] = "due";
$developer_rates_sponsor[] = "tres";

/*

######################################################################
# Comments type
#
# List of types comments can be
# This should not be modifiable
# You can add/delete/modify as long as you mantain the syntax
#
######################################################################

$comments_type_array[] = "general";
$comments_type_array[] = "news";
$comments_type_array[] = "comments";
$comments_type_array[] = "sponsoring";
$comments_type_array[] = "history";
$comments_type_array[] = "consultants";
$comments_type_array[] = "specifications";
$comments_type_array[] = "proposals";
$comments_type_array[] = "milestones";
$comments_type_array[] = "referees";

*/

######################################################################
# Views
#
# List of views that can be
# This should not be modifiable
# You can add/delete/modify as long as you mantain the syntax
#
######################################################################

$views_array[] = "Everybody";
$views_array[] = "Registered";
$views_array[] = "Developers";
$views_array[] = "Sponsors";
$views_array[] = "Project Participants";
$views_array[] = "Project Developers";
$views_array[] = "Project Sponsors";
$views_array[] = "Project Initiator";

######################################################################
# Theme
#
# Configuration of background colors, font families, etc.
#
######################################################################

$th_body_bgcolor = "#FFFFFF";
$th_font_family = "verdana,arial,helvetica,sans-serif";
$th_tt_font_family = "courier,sans-serif";
$th_font_color = "#333333";
$th_hover_font_color = "#FF0000";

$th_nav_bgcolor = "#FFCC33";
$th_navstrip_bgcolor = "#7B7B7B";
$th_nav_font_color = "#333333";
$th_navstrip_font_color = "#FFFFFF";

$th_box_frame_color = "#CCCCCC";
$th_box_frame_width = "1";
$th_box_title_bgcolor = "#CCCCCC";
$th_box_body_bgcolor = "#FFFFFF";
$th_box_title_align = "left";
$th_box_body_align = "left";
$th_box_title_font_color = "#000000";
$th_box_body_font_color = "#333333";
$th_box_error_font_color = "#FF2020";

$th_strip_frame_color = "#CCCCCC";
$th_strip_frame_width = "1";
$th_strip_title_bgcolor = "#CCCCCC";
$th_strip_body_bgcolor = "";
$th_strip_title_align = "center";
$th_strip_body_align = "";
$th_strip_title_font_color = "#000000";
$th_strip_body_font_color = "";

######################################################################
# Page Layout
#
# config_show_appsperpage	maximum number of applications shown each time
# config_show_numberofdays	Number of days shown at the right column
#
######################################################################

$config_show_appsperpage = 10;
$config_show_numberofdays = 7;

######################################################################
# Email Notification
#
# Notify admin by email that a new user has registered
# and editors that apps were inserted, reviewed, updated or changed.
#
# ml_notify		To be notified, ml_notify apps have to be inserted
# ml_fromaddr		Email address in the From field
# ml_replyaddr		Return Email address
#
######################################################################

$ml_notify = 1;
$ml_fromaddr = "noreply@mail.berlios.de";
$ml_replyaddr = "noreply@mail.berlios.de";

######################################################################
# Newsletter Configuration
#
# ml_listurl		
# ml_weeklylisturl	
# ml_newstoaddr 
# ml_newsreqaddr 
# ml_weeklynewstoaddr 
# ml_weeklynewsreqaddr =
# ml_newsadmaddr 
# ml_newsfromaddr 
# ml_newsreplyaddr 
#
######################################################################

$ml_listurl = "http://lists.berlios.de/mailman/listinfo/sourceagency-news";
$ml_weeklylisturl = "http://lists.berlios.de/mailman/listinfo/sourceagency-weeklynews";
$ml_newstoaddr = "sourceagency-news@lists.berlios.de";
$ml_newsreqaddr = "sourceagency-news-request@lists.berlios.de";
$ml_weeklynewstoaddr = "sourceagency-weeklynews@lists.berlios.de";
$ml_weeklynewsreqaddr = "sourceagency-weeklynews-request@lists.berlios.de";
$ml_newsadmaddr = "sourceagency-admin@lists.berlios.de";
$ml_newsfromaddr = $ml_newsadmaddr;
$ml_newsreplyaddr = $ml_newsadmaddr;

######################################################################
# Statistic configuration
#
# MinimumAppsByEmail	minimum percentage of an Email domain for
#			being displayed  >= 0.01
# MinimumSecByEmail	the same but for Sections >= 0.01
# MinimumLicByEmail	the same but for Licenses >= 0.01
# Minimum_apps_in_license   minimum amount of apps a license has to
#			    have to be displayed in the statistic
#			    "Apps by Licenses and Email Domains"
#			    >= 1
#
######################################################################

$MinimumAppsByEmail = 0.4;
$MinimumSecByEmail = 5;
$MinimumLicByEmail = 1;
$Minimum_apps_in_license = 1;

######################################################################
# Permission for accessing web pages
#
# "devel"    only allows access to registered users with devel permission
# "sponsor"    only allows access to registered users with sponsor permission
# "editor"  only allows access to registered users with editor permission
# "admin"   only allows access to registered users with admin permission
# "all"     allows access to everybody (also unregistered users)
#
# Please, be sure of what you make!
# An error could make your system fragile.
#
# config_perm_apppend	Access permission to enter apppend.php3
# config_perm_appdom	Access permission to enter appdom.php3
# config_perm_developer	Access permission to enter developer.php3
# config_perm_users	Access permission to enter users.php3
# config_perm_admdate	Access permission to enter admdate.php3
# config_perm_admuser	Access permission to enter admuser.php3
# config_perm_admlicens	Access permission to administrate licenses
# config_perm_admcomment	Access permission to administrate comments
# config_perm_admsec	Access permission to administrate sections
# config_perm_admcat	Access permission to administrate categories
# config_perm_nladm	Access permission to administrate newsletters
# config_perm_admfaq 	Access permission for administrating the faq
# config_perm_admprojects 	Access permission for administrating for project reviewing
# 
######################################################################

$config_perm_apppend = "editor";
$config_perm_appdom = "editor";
$config_perm_developer = "all";
$config_perm_users = "all";
$config_perm_admdate = "admin";
$config_perm_admuser = "admin";
$config_perm_admlicens = "admin";
$config_perm_admcomment = "admin";
$config_perm_admsec = "admin";
$config_perm_admcat = "admin";
$config_perm_nladm = "admin";
$config_perm_admfaq = "admin";
$config_perm_admprojects = "admin";


######################################################################
# Parameter configuration
#
# $config_first_year	contains the first year in forms that can be selected by the users (f.e. "valid until", "start" and "finish")
# $config_last_year		contains the last year in forms that can be selected by the users
######################################################################

$config_first_year = 2001;
$config_last_year = 2005;

######################################################################
# PHPLIB user authorization
#
# Put a random string in it 
######################################################################

$hash_secret = "Jabberwocky...";

######################################################################
# 
# Ok... that's it. You've finished configuring the SourceAgency system
#
# The rest of parameters that are listed beyond this comment
# are internal for the SourceAgency system or needed for advanced purposes
#
######################################################################

######################################################################
# SourceAgency Version
#
# Please, do not touch this in any case!
# It just gives the version of SourceAgency you're are using
# You can always download the latest version of SourceAgency at
# http://sourceagency.berlios.de
#
# You can read in the documentation the version number policy followed
# for the SourceAgency system development to know when the developers
# recommend you to update your SourceAgency system.
#
######################################################################

$SourceAgency_Version = "0.8";

?>
