<?php
// mock_database.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: mock_database.php,v 1.2 2001/10/18 18:51:01 riessen Exp $

// define a mock db_sourceagency class and use it to fake out the 
// library code

// this called mock_database, because we want specific to define
// the db_sourceagency class so that they can configure this class
// to return specific values.
class mock_database 
{
  function mock_database() {
  }

  function query( $query_string ) {
    printf( "Mock_Database: query %s\n", $query_string );
  }
  
  // retrieves a specific column value but also something like
  // count(*)
  function f( $function_string ) {
    printf( "Mock_Database: f %s\n", $function_string );
  }

  function num_rows() {
    printf( "Mock_Database: num_rows\n" );
  }

  function next_record() {
  }
}
?>
