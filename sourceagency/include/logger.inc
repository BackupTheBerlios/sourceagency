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
# BerliOS SourceWell: http://sourcewell.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# A very very simple logger ....
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: logger.inc,v 1.2 2001/11/07 18:48:53 riessen Exp $
#
######################################################################

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
        fwrite( $g_logger_file_pointer, "[" . date("D M j G:i:s T Y")
                                        . "]: " . $msg . "\n" );
    }

    // called in the footer
    function close() {
        global $g_logger_file_pointer;
        if ( $g_logger_file_pointer ) {
            fclose( $g_logger_file_pointer );
        }
    }
}

?>
