<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the configuration file for the Project Page
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: config.inc,v 1.7 2002/05/02 09:45:07 riessen Exp $
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
######################################################################

$sys_name = "SourceAgency Project Page";
$sys_title = "Open Source Project Mediation & Management";
$sys_url_title = "http://www.berlios.de/";
$sys_url = "http://sourceagency.berlios.de/beta/";
$sys_logo_image = "../images/berlios_logo.png";
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
$org_logo_image = "../images/logo_fokus.png";
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

?>
