<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org),
#                Gerrit Riesen (riesen@open-source-consultants.de) and
#                Lutz Henckel (lutz.henckel@fokus.gmd.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This file contains the common header
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: header.inc,v 1.10 2002/05/07 19:35:17 grex Exp $
#
######################################################################

// Disabling cache
header('Cache-Control: no-cache, must-revalidate');     // HTTP/1.1
header('Pragma: no-cache'); 				// HTTP/1.0

require('logger.inc');
$l = new Logger;
$l->open();

require('config.inc');
require('lib.inc');
require('security.inc');
require('translation.inc');
require('lang.inc');
require('box.inc');
require('html.inc');
$t = new translation($la);

// As the database connection is used on almost any page,
// the constructor is called here

$db = new DB_SourceAgency;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta http-equiv="expires" content="0">
   <meta name="Author" content="<?php echo $config_meta_author;?>">
   <meta name="Description" content="<?php echo $config_meta_description;?>">
   <meta name="KeyWords" content="<?php echo $config_meta_keywords;?>">
   <title><?php echo $sys_name;?> - <?php echo $t->translate($sys_title);?></title>
   <link rel="stylesheet" type="text/css" href="berlios.css">
   <link rel="shortcut icon" href="berlios.ico">
</head>
<body bgcolor="<?php echo $th_body_bgcolor;?>" topmargin="0" bottommargin="0" 
leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0">

<?php
require('top_strip.inc');
require('top_title.inc');
?>

<table border=0 cellspacing=0 cellpadding=0 width="100%" >
<tr valign="top" bgcolor="<?php echo $th_body_bgcolor;?>">
<td bgcolor="<?php echo $th_nav_bgcolor;?>">
<?php
require('menubar.inc');
?>
</td>

<td width="10"><img src="images/blank.gif" border=0 height=1 width=10></td>

<td width="99%">
<p>&nbsp;
