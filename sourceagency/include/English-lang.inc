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
# $Id: English-lang.inc,v 1.5 2001/11/09 13:56:23 riessen Exp $
#
######################################################################

switch($phrase) {
    
    default: 
        $tmp = "$phrase"; 
        // php4 defines require_once
        if ( floor( phpversion() >= 4 ) ) {
          require_once( "logger.inc" );
        }
        // ASSUME: that the class logger is defined
        $l = new Logger;
        $l->log( "translating '" . $phrase . "'" );
        break;
}
?>
