<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gerrit Riessen (riessen@open-source-consultants.de) and
#                Gregorio Robles (grex@scouts-es.org)
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
# $Id: logger.inc,v 1.8 2002/05/08 11:49:17 riessen Exp $
#
######################################################################

/**
 * This is a simple logging script
 *
 * There is a default value for the log file that may be changed using
 * the methods of this class
 *
 * @author Gerrit Riessen <riessen@open-source-consultants.de>
 * @author Gregorio Robles <grex@scouts-es.org>
 * @access public
 */

// this is global because we don't want new instances of the logger
// using a new file_pointer, hence this isn't an instance variable 
// on the logger class
$g_logger_file_pointer = -1;

class Logger {
    /**
     * Path to the logging file
     * @var string
     */
    var $logFile;

    /**
     * Constructor
     *
     * @access public
     */
    function Logger() {
        $this->logFile = '/tmp/sourceagency.log';
    }

    /**
     * Sets the Log File
     *
     * @param  string   $logFile      Path with the log file
     * @access public
     */
    function setLogFile($logFile) {
        $this->logFile = $logFile;
        // don't want to reset the logfile and log to the old log file!
        $this->close();
    }

    /**
     * Gets the Log File
     *
     * @return string                Path with the log file
     * @access public
     */
    function getLogFile() {
        return $this->logFile;
    }

    /**
     * Opens the log file - Should be called in the header
     *
     * @global  file_pointer   $g_logger_file_pointer   file descriptor to the logging file
     * 
     * @access public
     */
    function open() {
        global $g_logger_file_pointer;
        $g_logger_file_pointer = fopen($this->logFile, 'a+');
    }

    /**
     * Message logging function
     * Prints a log message into the log file
     *
     * @param   string   $msg                     Message to be logged
     * @global  file_pointer   $g_logger_file_pointer   file descriptor to the logging file
     * 
     * @access public
     */
    function log($msg) {
        global $g_logger_file_pointer;
        if ($g_logger_file_pointer > -1) {
            fwrite($g_logger_file_pointer, '[' . date('D M j G:i:s T Y')
                                                       . ']: ' . $msg . "\n");
        }
    }

    /**
     * Closes the LogFile - Should be called in the footer
     *
     * @global  file_pointer   $g_logger_file_pointer   file descriptor to the logging file
     * @access public
     */
    function close() {
        global $g_logger_file_pointer;
        if ($g_logger_file_pointer > -1) {
            fclose($g_logger_file_pointer);
        }
        $g_logger_file_pointer = -1;
    }
}
?>
