<?php
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Utilities for grabbing output that is printed out using 'echo' or 'print'
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: capture.php,v 1.4 2002/07/23 14:09:39 riessen Exp $
#
######################################################################

// global (g_) variable for storing the data that would normally
// have been outtputed through echo's or print's. Avoiding using
// this directly, instead use the functions provided below.
$g_cap_text = array();

// this function is passed to the ob_start function to capture text
function capture_text( $str ) {
    global $g_cap_text;
    $g_cap_text[] = $str;
    return '';
}

// replaces the ob_get_length function
function capture_text_length() {
    return ( strlen( join( '', $GLOBALS['g_cap_text'] ) ) );
}

// replaces the ob_get_content function
function &capture_text_get() {
    return ( join( '', $GLOBALS['g_cap_text'] ) );
}

// this should be called to begin output capturing
function capture_start() {
    ob_start("capture_text");
}

// this must be called to stop output capturing
function capture_stop() {
    ob_end_flush();
}

// resets the contents of the capture buffer to zero
function capture_reset_text() {
    $GLOBALS['g_cap_text'] = array();
}

// stop the capturing and return the captured text
function &capture_stop_and_get() {
    capture_stop();
    return join( '', $GLOBALS['g_cap_text'] );
}

// short cut: one call instead of two
function capture_reset_and_start() {
    capture_reset_text();
    capture_start();
}

?>
