<?php
// TestNewslib.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: TestNewslib.php,v 1.4 2001/10/31 12:23:16 riessen Exp $

include_once( "../constants.php" );

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
