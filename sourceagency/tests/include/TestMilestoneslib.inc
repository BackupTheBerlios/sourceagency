<?php
// TestMilestoneslib.inc
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestMilestoneslib.inc,v 1.3 2001/10/18 18:20:02 ger Exp $

require_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
  ini_set('include_path', ini_get('include_path') . ':../../include' );
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
