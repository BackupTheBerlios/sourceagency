<?php
// mock_database.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: mock_database.php,v 1.6 2001/10/24 17:09:31 riessen Exp $

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

// *****************
// the following data structures are all arrays of arrays. The first
// array is indexed by the instance number,
// *****************
// array of valid queries that can be placed to this database. This
// should be reset 
$g_mkdb_queries = array(); 
// array of values that should returned for successive calls to the
// num_rows method of the mock_database class
$g_mkdb_num_rows = array();
// an array of arrays for storing the values for a row of values
// The second arrays should be indexed with column names.
$g_mkdb_next_record_data = array();
// 
// the next 3 are indexes into the arrays above
$g_mkdb_cur_num_row_call = array();
$g_mkdb_cur_query_call = array();
$g_mkdb_cur_record = array();
// ************************
// Here ends variables that are arrays of arrays ....
// ************************

// this is set to true if the database failed for some reason or other
$g_mkdb_failed = false;
$g_mkdb_failure_text = "";

// handle multiple instances of the DB_Sourceagency class
$g_mkdb_instance_counter = 0;
$g_mkdb_nr_instance_expected = 1;

// every time a mock database is required, an new object of this
// class should be created and used to configure the mock database
// that will be used.
class mock_db_configure
{
    function mock_db_configure() {
        global $g_mkdb_failed, $g_mkdb_failure_text, $g_mkdb_instance_counter;

        $g_mkdb_failed = false;
        $g_mkdb_failure_text = ">>>>Database failed<<<<\n";

        $g_mkdb_instance_counter = 0;
        $this->set_nr_instance_expected( 1 );
    }

    // this should be called fairly early in the configuration phase,
    // it sets the number of instance expected of the database class, and
    // also initialises all data structures.
    function set_nr_instance_expected( $instance_count ) {
        global $g_mkdb_nr_instance_expected, $g_mkdb_cur_num_row_call,
            $g_mkdb_cur_query_call, $g_mkdb_cur_record, $g_mkdb_queries,
            $g_mkdb_num_rows, $g_mkdb_next_record_data;

        $g_mkdb_nr_instance_expected = $instance_count;

        $g_mkdb_cur_num_row_call = array();
        $g_mkdb_cur_query_call = array();
        $g_mkdb_cur_record = array();
        
        $g_mkdb_queries = array(); 
        $g_mkdb_num_rows = array();
        $g_mkdb_next_record_data = array();

        for ( $idx = 0; $idx < $g_mkdb_nr_instance_expected; $idx++ ) {
            $g_mkdb_cur_num_row_call[$idx] = 0;
            $g_mkdb_cur_query_call[$idx] = 0;
            $g_mkdb_cur_record[$idx] = 0;

            $g_mkdb_queries[$idx] = array(); 
            $g_mkdb_num_rows[$idx] = array();
            $g_mkdb_next_record_data[$idx] = array();
        }
    }

    // add a number of rows value. The row_size argument must be a integer.
    function add_num_row( $row_size, $inst_nr = 0, $index = -1 ) {
        global $g_mkdb_num_rows;
        $this->_add_data_point( $g_mkdb_num_rows[$inst_nr],$row_size,$index );
    }

    // add a query string. $query_string must be a string
    function add_query( $query_string, $inst_nr = 0, $index = -1 ) {
        global $g_mkdb_queries;
        $this->_add_data_point( $g_mkdb_queries[$inst_nr], 
                                $query_string, $index );
    }

    // add a record for a call to the next_record(...) method. The $record
    // argument must be an array or false. False indicates that next_record(..)
    // should return false, this allows storing all row information for
    // all databases created during the test.
    function add_record( $record, $inst_nr = 0, $index = -1 ) {
        global $g_mkdb_next_record_data;
        $this->_add_data_point( $g_mkdb_next_record_data[$inst_nr], 
                                $record, $index );
    }

    // returns true if the database failed. This also checks whether 
    // the number of expected instance equals the number of instances
    // actually created .....
    function did_db_fail() {
        global $g_mkdb_failed, $g_mkdb_failure_text, $g_mkdb_instance_counter,
            $g_mkdb_nr_instance_expected;

        if ( $g_mkdb_instance_counter != $g_mkdb_nr_instance_expected ) {
            $g_mkdb_failure_text 
                 .= ("\n\n****** Instance creation count mismatch ******\n"
                     . "Expected: " . $g_mkdb_nr_instance_expected 
                     . " but created: " . $g_mkdb_instance_counter . "\n");
            $g_mkdb_failed = true;
        }
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
    var $instance_number = -1;

    function mock_database() {
        global $g_mkdb_instance_counter, $g_mkdb_nr_instance_expected;
        // TODO: should be semaphored when accessing instance counter
        $this->instance_number = $g_mkdb_instance_counter++;
        if ( $this->instance_number > $g_mkdb_nr_instance_expected ) {
            // if there are more instances than expected, throw exception
            $this->assertEquals( $this->instance_number,
                                 $g_mkdb_nr_instance_expected,
                                 "too many mock_database instance created" );
        }
    }

    function query( $query_string ) {
        global $g_mkdb_queries, $g_mkdb_cur_query_call;
        
        $cur_query_call = $g_mkdb_cur_query_call[$this->instance_number];
        $queries = $g_mkdb_queries[$this->instance_number];

        $this->assertEquals( false, 
                             $cur_query_call >= count($queries),
                             "mock_database(query): no query for call = " 
                             . $cur_query_call );

        $this->assertEquals( $queries[$cur_query_call],
                             $query_string, "mock_database(query): query "
                             . "mismatch, call = " . $cur_query_call );

        //$g_mkdb_cur_query_call++;
        $g_mkdb_cur_query_call[$this->instance_number]++;
    }
    
    function f( $column_name ) {
        global $g_mkdb_cur_record;

        $cur_record = $g_mkdb_cur_record[$this->instance_number];

        $this->assertEquals( true, isset( $this->cur_fetch_row[$column_name] ),
                             "mock_database(fetch): '$column_name' was not "
                             . "set, current row: " . $cur_record );
        return $this->cur_fetch_row[ $column_name ];
    }
    
    function num_rows() {
        global $g_mkdb_num_rows, $g_mkdb_cur_num_row_call;

        $num_rows = $g_mkdb_num_rows[$this->instance_number];
        $cur_num_row_call = $g_mkdb_cur_num_row_call[$this->instance_number];

        $this->assertEquals(false, $cur_num_row_call 
                            >= count($num_rows),
                            "mock_database(num_rows): no more rows available,"
                            . " call = " . $cur_num_row_call);

        $g_mkdb_cur_num_row_call[$this->instance_number]++;
        return $num_rows[ $cur_num_row_call ];
    }
    
    function next_record() {
        global $g_mkdb_next_record_data, $g_mkdb_cur_record;
        // can't perform an assert here because it is expected that
        // next_record runs out of data: it returns true if data exists
        // else false.
        $next_record_data = $g_mkdb_next_record_data[$this->instance_number];
        $cur_record = $g_mkdb_cur_record[$this->instance_number];

        $this->assertEquals(false, $cur_record >= count($next_record_data),
                            "mock_database(next_record): no more data,"
                            . " record = " . $cur_record);
        
        $g_mkdb_cur_record[$this->instance_number]++;
        $this->cur_fetch_row = $next_record_data[$cur_record];
        return ( $this->cur_fetch_row );
    }
    
    // this is the function callback from the Assert class when a
    // test fails
    function fail( $message ) {
        global $g_mkdb_failed, $g_mkdb_failure_text;
        $g_mkdb_failure_text .= sprintf( "******** mock_database, instance: "
                                         . $this->instance_number
                                         ." FAILURE: ****\n%s\n**********\n",
                                         ($message ? $message
                                         : "No Message Give"));
        $g_mkdb_failed = true;
    }
}
?>
