<?php
// TestLogger.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: TestLogger.php,v 1.1 2003/11/21 12:56:03 helix Exp $

include_once( '../constants.php' );

include_once( 'logger.inc' );

if ( !defined("BEING_INCLUDED" ) ) {
}

class UnitTestLogger
extends UnitTest
{
    function UnitTestLogger( $name ) {
        $this->UnitTest( $name );
    }
    
    function setup() {
    }
    function tearDown() {
    }

    function testClose() {
        $l = new Logger;
        $file = '/tmp/'.rand().'.log';
        if ( file_exists( $file ) ) {
            unlink( $file );
        }

        $l->setLogFile( $file );
        $l->open();
        $l->log( 'this si a test' );
        $l->close();

        if ( !file_exists( $file ) ) {
            $this->assert( false, "File wasn't created" );
        } else {
            $fp = fopen( $file, "r" );
            $this->set_text( fgets( $fp, 4096 ) );
            fclose( $fp );
            unlink( $file );
            $this->_testFor_pattern( 'this si a test' );
        }
    }

    function testGetLogFile() {
        $l = new Logger;
        $file = '/tmp/'.rand().'.log';
        if ( file_exists( $file ) ) {
            unlink( $file );
        }

        $l->setLogFile( $file );
        $l->log( 'test' );
        $l->close();
        $this->assertEquals( $file, $l->getLogFile() );

        $this->assert( !file_exists( $file ), "File was created" );
        if ( file_exists( $file ) ) {
            unlink( $file );
        }
    }

    function testLog() {
        $l = new Logger;
        $file = '/tmp/'.rand().'.log';
        if ( file_exists( $file ) ) {
            unlink( $file );
        }

        $l->setLogFile( $file );
        $l->open();
        $l->log( 'this si a test' );
        $l->close();

        if ( !file_exists( $file ) ) {
            $this->assert( false, "File wasn't created" );
        } else {
            $fp = fopen( $file, "r" );
            $this->set_text( fgets( $fp, 4096 ) );
            fclose( $fp );
            unlink( $file );
            $this->_testFor_pattern( 'this si a test' );
        }
    }

    function testOpen() {
        $l = new Logger;
        $file = '/tmp/'.rand().'.log';
        if ( file_exists( $file ) ) {
            unlink( $file );
        }
        $l->setLogFile( $file );
        $l->open();
        $l->log( 'test' );
        $l->close();

        $this->assert( file_exists( $file ), "File wasn't created" );
        if ( file_exists( $file ) ) {
            unlink( $file );
        }
    }

    function testSetLogFile() {
        $l = new Logger;
        $file = '/tmp/'.rand().'.log';
        if ( file_exists( $file ) ) {
            unlink( $file );
        }

        $l->setLogFile( $file );
        $l->log( 'test' );
        $l->close();
        $this->assertEquals( $file, $l->getLogFile() );

        $this->assert( !file_exists( $file ), "File was created" );
        if ( file_exists( $file ) ) {
            unlink( $file );
        }
    }
}

define_test_suite( __FILE__ );
?>
