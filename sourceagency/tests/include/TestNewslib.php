<?php
// TestNewslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestNewslib.php,v 1.1 2001/10/16 14:17:10 ger Exp $

require_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
}

require_once( 'newslib.inc' );

class UnitTestNewslib
extends TestCase
{
    function UnitTestNewslib( $name ) {
        $this->TestCase( $name );
    }
}

define_test_suite( __FILE__ );

?>
