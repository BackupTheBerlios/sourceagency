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
# Unit test class for the functions contained in the 
# include/milestoneslib.inc
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: TestMilestoneslib.inc,v 1.6 2001/11/08 16:17:42 riessen Exp $
#
######################################################################

include_once( "../constants.php" );

include_once( 'milestoneslib.inc' );

class UnitTestMilestoneslib
extends TestCase
{
    function UnitTestMilestoneslib( $name ) {
        $this->TestCase( $name );
    }
}

define_test_suite( __FILE__ );
?>
