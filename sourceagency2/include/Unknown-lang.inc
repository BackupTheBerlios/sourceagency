<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Lutz Henckel (lutz.henckel@fokus.fraunhofer.de) and
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
# $Id: Unknown-lang.inc,v 1.1 2003/11/21 12:56:01 helix Exp $
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
