<?php
// logger.inc
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: logger.inc,v 1.1 2001/11/06 17:35:09 riessen Exp $

// very simple logger class ...

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
