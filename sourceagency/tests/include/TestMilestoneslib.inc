<?php
// TestMilestoneslib.inc
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestMilestoneslib.inc,v 1.1 2001/10/16 14:04:37 ger Exp $

require_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    // LIB_ROOT is the base of my libraries and is defined in constants.php
    ini_set('include_path', ini_get('include_path') . ':../../include' );

    // required for the $sess global variable
    require_once( "session.inc" );
    $sess = new Session;
}

require_once( 'milestoneslib.inc' );

class UnitTestMilestoneslib
extends TestCase
{
    function UnitTestMilestoneslib( $name ) {
        $this->TestCase( $name );
    }

    
}

define_test_suite( __FILE__ );
?>
