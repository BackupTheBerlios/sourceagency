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
# Used to find out what is actually being sent to the translation 
# routines.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: Unknown-lang.inc,v 1.3 2002/04/02 15:04:22 riessen Exp $
#
######################################################################

// php4 defines require_once
if ( floor( phpversion() >= 4 ) ) {
    require_once( "logger.inc" );
}

if ( isset( $l ) ) {
    $l->log( "translating '" . $phrase . "'" );
}

if ( function_exists( "translate_real" ) ) {
    return;
}

function translate_real( $phrase ) {
    return ( "<b>[</b>". $phrase . "<b>]</b>" );
}

?>
