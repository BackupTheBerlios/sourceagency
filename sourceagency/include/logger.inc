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
# A very very simple logger ....
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: logger.inc,v 1.6 2001/12/13 16:49:39 riessen Exp $
#
######################################################################

// this is global because we don't want new instances of the logger
// using a new file_pointer, hence this isn't an instance variable 
// on the logger class
$g_logger_file_pointer = -1;

class Logger {
    function Logger() {
    }

    // called in the header 
    function open() {
        global $g_logger_file_pointer;
        $g_logger_file_pointer = fopen( "/tmp/sourceagency.log", "a+" );
    }

    function log( $msg ) {
        global $g_logger_file_pointer;
        if ( $g_logger_file_pointer > -1 ) {
            fwrite( $g_logger_file_pointer, "[" . date("D M j G:i:s T Y")
                                                       . "]: " . $msg . "\n" );
        }
    }

    // called in the footer
    function close() {
        global $g_logger_file_pointer;
        if ( $g_logger_file_pointer > -1 ) {
            fclose( $g_logger_file_pointer );
        }
    }
}

?>
