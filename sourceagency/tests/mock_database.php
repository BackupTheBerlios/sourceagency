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
# Imitate the DB_SourceAgency class.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: mock_database.php,v 1.23 2002/07/09 11:15:33 riessen Exp $
#
######################################################################

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

// these are the flags that can be used in the ignore error method of the
// configuration class
define( "MKDB_NO_ERRORS", 0x00 );

define( "MKDB_QUERY_COUNT", 0x01 ); // whether all queries were used
define( "MKDB_RECORD_COUNT", 0x02 ); // whether all data records were used
define( "MKDB_QUERY_EXISTS", 0x04 ); // is query available for query(..) call
define( "MKDB_QUERY_COMPARE", 0x08 ); // compare queries (expected/actual)
define( "MKDB_FIELD_SET", 0x10 ); // field in record is set
define( "MKDB_NUM_ROWS", 0x20 ); // number of rows array size check (num_rows)
define( "MKDB_NEXT_RECORD", 0x40 ); // enough data records avail. (next_record)

// if adding more errors, then don't forget to update MKDB_ALL_ERRORS
define( "MKDB_ALL_ERRORS", MKDB_QUERY_COUNT | MKDB_RECORD_COUNT 
        | MKDB_QUERY_EXISTS | MKDB_QUERY_COMPARE | MKDB_FIELD_SET 
        | MKDB_NUM_ROWS | MKDB_NEXT_RECORD );

// *****************
// the following data structures are all arrays of arrays. The first
// array is indexed by the instance number,
// *****************
// array of valid queries that can be placed to this database. This
// should be reset 
$g_mkdb_queries = array(); 
// array of values that should returned for successive calls to the
// num_rows method of the mock_database class (successive calls because
// next_record can reduce the value that num_rows should return).
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

// this is an array indexed by instance number and containing 
// flags to tell which errors should be ignored for the instance
$g_mkdb_ignore_error = array();

// this is set to true if the database failed for some reason or other
$g_mkdb_failed = false;
$g_mkdb_failure_text = "";

// handle multiple instances of the DB_Sourceagency class
$g_mkdb_instance_counter = 0;
$g_mkdb_nr_instance_expected = 1;

$g_mkdb_config_instance_count = 0;
$g_mkdb_config_did_db_fail_called = array();

// every time a mock database is required, an new object of this
// class should be created and used to configure the mock database
// that will be used. Normally each unit test that requires a mock 
// database would create one instance of the mock_db_configure class
// and use it to configure all the DB_SourceAgency instances created
// by the corresponding function.
class mock_db_configure
{
    var $instance_num = -1;

    // instance_count is the number of instance we are going to need
    function mock_db_configure( $instance_count = 1 ) {
        global $g_mkdb_failed, $g_mkdb_failure_text, $g_mkdb_instance_counter;

        // this is used to ensure that each instance of the database 
        // configuration class has the did_db_fail method called, if not,
        // then this prints a warning....
        $this->_increment_instance_counter();

        $g_mkdb_failed = false;
        $g_mkdb_failure_text = ">>>>Database failed<<<<<br>\n";

        $g_mkdb_instance_counter = 0;
        $this->set_nr_instance_expected( $instance_count );
    }

    function _did_db_fail_called() {
        global $g_mkdb_config_did_db_fail_called;
        $g_mkdb_config_did_db_fail_called[$this->instance_num] = true;
    }

    function _increment_instance_counter() {
        global $g_mkdb_config_instance_count,$g_mkdb_config_did_db_fail_called;

        $g_mkdb_config_did_db_fail_called[$g_mkdb_config_instance_count]=false;

        $this->instance_num = $g_mkdb_config_instance_count;
        $g_mkdb_config_instance_count++;
    }

    // this should be called fairly early in the configuration phase,
    // it sets the number of instance expected of the database class, and
    // also initialises all data structures.
    function set_nr_instance_expected( $instance_count ) {
        global $g_mkdb_nr_instance_expected, $g_mkdb_cur_num_row_call,
            $g_mkdb_cur_query_call, $g_mkdb_cur_record, $g_mkdb_queries,
            $g_mkdb_num_rows, $g_mkdb_next_record_data, $g_mkdb_ignore_error,
            $g_mkdb_instance_counter;

        $g_mkdb_instance_counter = 0;
        $g_mkdb_nr_instance_expected = $instance_count;

        $g_mkdb_cur_num_row_call = array();
        $g_mkdb_cur_query_call = array();
        $g_mkdb_cur_record = array();
        
        $g_mkdb_queries = array(); 
        $g_mkdb_num_rows = array();
        $g_mkdb_next_record_data = array();

        $g_mkdb_ignore_errors = array();

        for ( $idx = 0; $idx < $g_mkdb_nr_instance_expected; $idx++ ) {
            $g_mkdb_cur_num_row_call[$idx] = 0;
            $g_mkdb_cur_query_call[$idx] = 0;
            $g_mkdb_cur_record[$idx] = 0;

            $g_mkdb_ignore_error[$idx] = MKDB_NO_ERRORS;

            $g_mkdb_queries[$idx] = array(); 
            $g_mkdb_num_rows[$idx] = array();
            $g_mkdb_next_record_data[$idx] = array();
        }
    }

    // add a number of rows value. The row_size argument must be a integer.
    function add_num_row( $row_size, $inst_nr = 0, $index = -1 ) {
        global $g_mkdb_num_rows;
        $this->_add_data_point( $g_mkdb_num_rows,$inst_nr,$row_size,$index );
    }

    // add a query string. $query_string must be a string
    function add_query( $query_string, $inst_nr = 0, $index = -1 ) {
        global $g_mkdb_queries;
        $this->_add_data_point( $g_mkdb_queries,$inst_nr, 
                                $query_string, $index );
    }

    // add a record for a call to the next_record(...) method. The $record
    // argument must be an array or false. False indicates that next_record(..)
    // should return false, this allows storing all row information for
    // all databases created during the test.
    function add_record( $record, $inst_nr = 0, $index = -1 ) {
        global $g_mkdb_next_record_data;
        $this->_add_data_point( $g_mkdb_next_record_data,$inst_nr, 
                                $record, $index );
    }
    // this is the same as add_record except record_array is an array of
    // arrays
    function add_record_array( $record_array, $inst_nr = 0 ) {
        foreach ( $record_array as $record ) {
            $this->add_record( $record, $inst_nr );
        }
    }

    // method can be used to turn off specific error checking for specific
    // instances of the database.
    function ignore_errors( $error = MKDB_NO_ERRORS, $inst_nr = 0 ) {
        global $g_mkdb_ignore_error;
        $g_mkdb_ignore_error[ $inst_nr ] = $error;
    }
    function ignore_all_errors( $inst_nr = 0 ) {
        $this->ignore_errors( MKDB_ALL_ERRORS, $inst_nr );
    }

    // returns true if the database failed. This also checks whether 
    // the number of expected instance equals the number of instances
    // actually created .....
    function did_db_fail() {
        global $g_mkdb_failed, $g_mkdb_failure_text, $g_mkdb_instance_counter,
            $g_mkdb_nr_instance_expected, $g_mkdb_cur_query_call,
            $g_mkdb_queries, $g_mkdb_next_record_data, $g_mkdb_cur_record,
            $g_mkdb_ignore_error, $g_mkdb_num_rows, $g_mkdb_cur_num_row_call;

        $this->_did_db_fail_called();

        // check instance count
        if ( $g_mkdb_instance_counter != $g_mkdb_nr_instance_expected ) {
            $g_mkdb_failure_text 
                 .= ("<br>\n<br>\n*** Instance creation count mismatch "
                     ."***<br>\nExpected: " . $g_mkdb_nr_instance_expected 
                     . " but created: " . $g_mkdb_instance_counter . "<br>\n");
            $g_mkdb_failed = true;
        } 

        // check whether every instance used all of it's num_row values
        for ( $idx = 0; $idx < $g_mkdb_instance_counter; $idx++ ) {
            $exp_num_rows = count( $g_mkdb_num_rows[$idx] );
            $act_num_rows = $g_mkdb_cur_num_row_call[$idx];
            if ( $exp_num_rows != $act_num_rows 
                       && (($g_mkdb_ignore_error[$idx] & MKDB_NUM_ROWS) 
                                            != MKDB_NUM_ROWS)) {
                $g_mkdb_failure_text
                     .= ("<br>\nInstance " . $idx . " mismatch in num rows: "
                         ."too ".($exp_num_rows > $act_num_rows ? "many" 
                                                                    : "few")
                         ." defined, expected = " . $exp_num_rows 
                         . ", actual = " . $act_num_rows . "<br>\n" );
                $g_mkdb_failed = true;
            }
        }

        // check whether every instance also used all of it's queries,
        // this detects whether more queries were defined than used.
        // this check can be turned off using MKDB_QUERY_COUNT
        for ( $idx = 0; $idx < $g_mkdb_instance_counter; $idx++ ) {
            $query_count_actual = $g_mkdb_cur_query_call[$idx];
            $query_count_expected = count( $g_mkdb_queries[$idx] );
            
            if ( $query_count_expected != $query_count_actual 
                       && (($g_mkdb_ignore_error[$idx] & MKDB_QUERY_COUNT) 
                           != MKDB_QUERY_COUNT)) {
                
                $g_mkdb_failure_text
                     .= ("<br>\nInstance " . $idx . " query count mismatch: "
                         ."too ".($query_count_expected > $query_count_actual 
                                                            ? "many" : "few")
                         ." defined queries: expected = "
                         . $query_count_expected . ", actual = "
                         . $query_count_actual . "<br>\n" );
                $g_mkdb_failed = true;
            }
        }
        
        // check whether all rows for the next_record method were used up
        // this check can be turned off using the MKDB_RECORD_COUNT flag
        for ( $idx = 0; $idx < $g_mkdb_instance_counter; $idx++ ) {
            $next_record_data = $g_mkdb_next_record_data[$idx];
            $cur_record = $g_mkdb_cur_record[$idx];
            $exp_record_count = count( $next_record_data );

            if ( $cur_record < $exp_record_count
                          && (($g_mkdb_ignore_error[$idx] & MKDB_RECORD_COUNT) 
                              != MKDB_RECORD_COUNT)) {
                $g_mkdb_failure_text
                     .= ("<br>\nInstance " . $idx . " records mismatch: too "
                         .($exp_record_count > $cur_record ? "many" : "few" )
                         . " defined records: available = " . $exp_record_count
                         . ", used = " . $cur_record . "<br>\n" );
                $g_mkdb_failed = true;
            }
        }
        
        return $g_mkdb_failed;
    }

    // returns any error message produced because of a failure
    function error_message() {
        global $g_mkdb_failure_text;
        return $g_mkdb_failure_text;
    }

    function _add_data_point( &$array, $inst_nr, &$point, $index ) {
        if ( !isset( $array[$inst_nr] ) || !is_array($array[$inst_nr])) {
            $array[$inst_nr] = array();
        }

        $ary = &$array[$inst_nr];

        if ( $index > -1 ) {
            $ary[ $index ] = $point;
        } else {
            $ary[] = $point;
        }
    }
}

// used to query what actually happens with the database when it's used.
// this does not do any error checking. db_sourceagency uses this as
// it's base class.
class mock_database_query_only
extends Assert
{
    function mock_database_query_only() {
    }

    function query( $query_string ) {
    }
    
}

// mock_database is the database configured by mock_db_configure and
// should be used as the base class for the db_sourceagency class (see 
// below for this extension of mock_database).
class mock_database 
extends Assert
{
    var $cur_fetch_row = array();
    var $instance_number = -1;
    // flag which says which errors should be ignored
    var $ignore_error_flag = MKDB_NO_ERRORS;

    function mock_database() {
        global $g_mkdb_instance_counter, $g_mkdb_nr_instance_expected,
            $g_mkdb_ignore_error;
        // TODO: should be semaphored when accessing instance counter
        $this->instance_number = $g_mkdb_instance_counter++;

        // assert that we're creating too many instances
        $this->assert( $this->instance_number <= $g_mkdb_nr_instance_expected,
                       "too many instance created, expected: "
                       . $g_mkdb_nr_instance_expected . ", have: "
                       . $this->instance_number);

        $this->ignore_error_flag = 
             $g_mkdb_ignore_error[ $this->instance_number ];
    }

    // private function for query the $ignore_error_flag value, returns
    // true if we should be checking for a particular error 
    function _check_for( $error ) {
        return ( ( $this->ignore_error_flag & $error) != $error );
    }

    function query( $query_string ) {
        global $g_mkdb_queries, $g_mkdb_cur_query_call;
        
        if ( $this->_check_for( MKDB_QUERY_EXISTS )) {
            $this->assert(count($g_mkdb_cur_query_call)>$this->instance_number,
                          "`Current query call` array to small for instance: "
                          . $this->instance_number 
                          . " query [" . $query_string . "]");
            $this->assert(count($g_mkdb_queries)>$this->instance_number,
                          "`Query list` array to small for instance: "
                          .$this->instance_number
                          . " query [" . $query_string . "]");
        }

        $cur_query_call = $g_mkdb_cur_query_call[$this->instance_number];
        $queries = $g_mkdb_queries[$this->instance_number];

        // check whether we have enough queries to satisy the query call
        if ( $this->_check_for(MKDB_QUERY_EXISTS)) {
            $this->assert( $cur_query_call < count($queries),
                           "mock_database(query): no query for call = " 
                           . $cur_query_call . ", query: [" 
                           . $query_string . "]");
        }

        // check that the query string passed as argument and the one
        // expected match up.
        if ( $this->_check_for( MKDB_QUERY_COMPARE ) ) {
            $this->assertEquals( $queries[$cur_query_call],
                                 $query_string, "mock_database(query): query "
                                 . "mismatch, call = " . $cur_query_call );
        }

        $g_mkdb_cur_query_call[$this->instance_number]++;
    }
    
    function f( $column_name ) {
        global $g_mkdb_cur_record;

        $cur_record = $g_mkdb_cur_record[$this->instance_number];

        // assert that the field is set, i.e. defined, in the record
        if ( $this->_check_for( MKDB_FIELD_SET ) ) {
            $this->assert( isset( $this->cur_fetch_row[$column_name] ),
                           "mock_database(fetch): '$column_name' was not "
                           . "set, current row: " . $cur_record );
        }

        return $this->cur_fetch_row[ $column_name ];
    }

    function p( $col_name ) {
        print $this->f( $col_name );
    }

    function affected_rows() {
        // TODO: implement method 'affected_rows' which returns the number
        // TODO: of rows that were changed by an insert statement
        // This has one value for each query, i.e. this value is strongly
        // correlated to the query, unlike the num_rows which is affected
        // by the next_record call. Inparticular, this should only be set
        // if the query is an update or insert query.
        $this->assert( false, "affected_rows: Method not implemented" );
    }

    function nf() {
        // TODO: 'number of fields' for current query?
        $this->assert( false, "nf: Method not implemented" );
    }

    function num_rows() {
        global $g_mkdb_num_rows, $g_mkdb_cur_num_row_call;

        // g_mkdb_num_rows contains arrays because each call to num_rows
        // may have been affected by a next_record call, i.e. while(num_rows())
        // { next_record(); } ... although normally while(next_record()) {...}
        // is used, the first variation can not be excluded
        $num_rows = $g_mkdb_num_rows[$this->instance_number];
        $cur_num_row_call = $g_mkdb_cur_num_row_call[$this->instance_number];

        if ( $this->_check_for( MKDB_NUM_ROWS ) ) {
            // check that we have enough data
            $this->assert($cur_num_row_call < count($num_rows),
                          "mock_database(num_rows): no more rows available,"
                          . " call = " . $cur_num_row_call);

            $g_mkdb_cur_num_row_call[$this->instance_number]++;
            return $num_rows[ $cur_num_row_call ];
        } else {
            // this avoids a warning about index numbers if the data
            // isn't defined.
            return -1;
        }
    }
    
    function next_record() {
        global $g_mkdb_next_record_data, $g_mkdb_cur_record;
        // can't perform an assert here because it is expected that
        // next_record runs out of data: it returns true if data exists
        // else false.
        $next_record_data = $g_mkdb_next_record_data[$this->instance_number];
        $cur_record = $g_mkdb_cur_record[$this->instance_number];

        // cause an error if next_record is called _again_ after it already
        // has returned false, hence the +1 on the count($next_record_data)
        if ( $this->_check_for( MKDB_NEXT_RECORD ) ) {
            $this->assert($cur_record < count($next_record_data) + 1,
                          "mock_database(next_record): no more data,"
                          . " record = " . $cur_record);
        }
        
        $g_mkdb_cur_record[$this->instance_number]++;
        if ( $cur_record == count( $next_record_data ) ) {
            // this is the last available record, avoid getting a warning
            // about undefined indexes into the next_record_data array
            $this->cur_fetch_row = false;
        } else {
            $this->cur_fetch_row = $next_record_data[$cur_record];
        }
        return ( $this->cur_fetch_row );
    }
    
    // this is the function callback from the Assert class when a
    // test fails
    function fail( $message ) {
        global $g_mkdb_failed, $g_mkdb_failure_text;
        $g_mkdb_failure_text .= sprintf( "FAILURE: mock_database, instance: "
                                         . $this->instance_number
                                         ." ****<br>\n%s<br>\n"
                                         ."**********<br>\n",
                                         ($message ? $message
                                         : "No Message Give"));
        $g_mkdb_failed = true;
    }
}

// used to check that all instances of the mock_db_configure class have
// called the did_db_fail method to check that the database instance
// created did not fail in some way.
function mkdb_check_did_db_fail_calls() {
    global $g_mkdb_config_did_db_fail_called;
    for ( $idx = 0; $idx<count($g_mkdb_config_did_db_fail_called);$idx++){
        if ( !$g_mkdb_config_did_db_fail_called[$idx] ) {
            print "<b>WARNING</b>: Instance " . $idx . " of mock db_configure "
                . "did not call did_db_fail method<br>\n";
        }
    }
    print "mock_database instances created: " 
      . count($g_mkdb_config_did_db_fail_called) . "<br>\n";
}

// this replaces the normal db_sourceagency classes and ensures that
// last mock_db_configure instance defines how the database should behave
class db_sourceagency 
extends mock_database 
{
    function db_sourceagency() {
        // call the constructor of our parent
        $this->mock_database();
    }
}
?>
