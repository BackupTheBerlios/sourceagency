<?php
// mock_database.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: mock_database.php,v 1.4 2001/10/23 09:45:01 riessen Exp $

//
// For an explanation of this class, see:
//    http://wiki.berlios.de/index.php?SourceAgencyUnitTests
//
// require the Assert class
include_once( 'phpunit.php' );

// define a mock db_sourceagency class and use it to fake out the 
// library code. Tests that require a database should extend this
// class and define any specific methods/results.

// globals required by our database class. These should not be set
// directly, only referenced through the configuration class.

// array of valid queries that can be placed to this database. This
// should be reset 
$g_mkdb_queries = array(); 
// array of values that should returned for successive calls to the
// num_rows method of the mock_database class
$g_mkdb_num_rows = array();
// an array of arrays for storing the values for a row of values
// The second arrays should be indexed with column names.
$g_mkdb_next_record_data = array();

// the next 3 are indexes into the arrays above
$g_mkdb_cur_num_row_call = 0;
$g_mkdb_cur_query_call = 0;
$g_mkdb_cur_record = 0;

// this is set to true if the database failed for some reason or other
$g_mkdb_failed = false;
$g_mkdb_failure_text = "";

// every time a mock database is required, an new object of this
// class should be created and used to configure the mock database
// that will be used.
class mock_db_configure
{
    function mock_db_configure() {
        global $g_mkdb_failed, $g_mkdb_failure_text, $g_mkdb_cur_num_row_call,
               $g_mkdb_cur_query_call, $g_mkdb_cur_record, $g_mkdb_queries,
               $g_mkdb_num_rows, $g_mkdb_next_record_data;

        $g_mkdb_failed = false;
        $g_mkdb_failure_text = ">>>>Database failed<<<<\n";

        $g_mkdb_cur_num_row_call = 0;
        $g_mkdb_cur_query_call = 0;
        $g_mkdb_cur_record = 0;

        $g_mkdb_queries = array(); 
        $g_mkdb_num_rows = array();
        $g_mkdb_next_record_data = array();
    }

    // add a number of rows value. The row_size argument must be a integer.
    function add_num_row( $row_size, $index = -1 ) {
        global $g_mkdb_num_rows;
        $this->_add_data_point( $g_mkdb_num_rows, $row_size, $index );
    }

    // add a query string. $query_string must be a string
    function add_query( $query_string, $index = -1 ) {
        global $g_mkdb_queries;
        $this->_add_data_point( $g_mkdb_queries, $query_string, $index );
    }

    // add a record for a call to the next_record(...) method. The $record
    // argument must be an array or false. False indicates that next_record(..)
    // should return false, this allows storing all row information for
    // all databases created during the test.
    function add_record( $record, $index = -1 ) {
        global $g_mkdb_next_record_data;
        $this->_add_data_point( $g_mkdb_next_record_data, $record, $index );
    }

    // returns true if the database failed.
    function did_db_fail() {
        global $g_mkdb_failed;
        return $g_mkdb_failed;
    }

    // returns any error message produced because of a failure
    function error_message() {
        global $g_mkdb_failure_text;
        return $g_mkdb_failure_text;
    }

    function _add_data_point( &$array, &$point, $index ) {
        if ( $index > -1 ) {
            $array[ $index ] = $point;
        } else {
            $array[] = $point;
        }
    }
}

// this called mock_database, because we want specific to define
// the db_sourceagency class so that they can configure this class
// to return specific values.
class mock_database 
extends Assert
{
    var $cur_fetch_row = array();

    function mock_database() {
        /* do nothing constructor */
    }

    function query( $query_string ) {
        global $g_mkdb_queries, $g_mkdb_cur_query_call;
        
        $this->assertEquals( false, 
                             $g_mkdb_cur_query_call >= count($g_mkdb_queries),
                             "mock_database(query): no query for call = " 
                             . $g_mkdb_cur_query_call );

        $this->assertEquals( $g_mkdb_queries[$g_mkdb_cur_query_call],
                             $query_string, "mock_database(query): query "
                             . "mismatch, call = " . $g_mkdb_cur_query_call );

        $g_mkdb_cur_query_call++;
    }
    
    function f( $column_name ) {
        global $g_mkdb_cur_record;
        $this->assertEquals( true, isset( $this->cur_fetch_row[$column_name] ),
                             "mock_database(fetch): '$column_name' was not "
                             . "set, current row: " . $g_mkdb_cur_record );
        return $this->cur_fetch_row[ $column_name ];
    }
    
    function num_rows() {
        global $g_mkdb_num_rows, $g_mkdb_cur_num_row_call;

        $this->assertEquals(false, $g_mkdb_cur_num_row_call 
                            >= count($g_mkdb_num_rows),
                            "mock_database(num_rows): no more rows available,"
                            . " call = " . $g_mkdb_cur_num_row_call);

        return $g_mkdb_num_rows[ $g_mkdb_cur_num_row_call++ ];
    }
    
    function next_record() {
        global $g_mkdb_next_record_data, $g_mkdb_cur_record;
        // can't perform an assert here because it is expected that
        // next_record runs out of data: it returns true if data exists
        // else false.

        $this->assertEquals(false, $g_mkdb_cur_record 
                            >= count($g_mkdb_next_record_data),
                            "mock_database(next_record): no more data,"
                            . " record = " . $g_mkdb_cur_record);
        $this->cur_fetch_row = $g_mkdb_next_record_data[$g_mkdb_cur_record++];
        return ( $this->cur_fetch_row );
    }
    
    // this is the function callback from the Assert class when a
    // test fails
    function fail( $message ) {
        global $g_mkdb_failed, $g_mkdb_failure_text;
        $g_mkdb_failure_text .= sprintf( "******** mock_database: "
                                         ."FAILURE: ****\n%s\n**********\n",
                                         ($message ? $message 
                                         : "No Message Give"));
        $g_mkdb_failed = true;
    }
}
?>
