<?php
// TestPersonallib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestPersonallib.php,v 1.1 2001/10/17 14:18:23 ger Exp $

require_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
}

require_once( 'personallib.inc' );

class UnitTestPersonallib
extends TestCase
{
    function UnitTestPersonallib( $name ) {
        $this->TestCase( $name );
    }
}

define_test_suite( __FILE__ );

?>
