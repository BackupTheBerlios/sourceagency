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
# $Id: English-lang.inc,v 1.8 2001/11/14 12:27:05 riessen Exp $
#
######################################################################

// php4 defines require_once
if ( floor( phpversion() >= 4 ) ) {
  require_once( "logger.inc" );
}
// php3: ASSUME: that the class logger is defined
$l = new Logger;
$l->log( "translating '" . $phrase . "'" );

switch($phrase) {
    
    /** these are various page titles **/
    case "comments_edit": $tmp = "Edit Comments"; break;
    case "comments_mod": $tmp = "Modify Comments"; break;
    case "consultants": $tmp = "Consultants"; break;
    case "summary": $tmp = "Summary"; break;

    default:   $tmp = "$phrase";  break;
}
?>
