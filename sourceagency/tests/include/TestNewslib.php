<?php
// TestNewslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestNewslib.php,v 1.3 2001/10/18 18:51:01 riessen Exp $

include_once( "../constants.php" );

if ( !defined("BEING_INCLUDED" ) ) {
    ini_set('include_path', ini_get('include_path') . ':../../include' );
}

include_once( 'newslib.inc' );

class UnitTestNewslib
extends TestCase
{
    function UnitTestNewslib( $name ) {
        $this->TestCase( $name );
    }
}

define_test_suite( __FILE__ );

?>
