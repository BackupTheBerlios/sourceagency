<?php
// mock_perm.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2002 Gerrit Riessen
// This code is licensed under the GNU Public License.
// 
// $Id: mock_perm.php,v 1.1 2003/11/21 12:56:02 helix Exp $

// mock replacement for the perm permission object.

class Permission 
{
  var $perm;

  function Permission() {
      $this->perm = array();
  }

  function add_perm( $p ) {
      $this->perm[$p] = $p;
  }

  function remove_perm( $p = '' ) {
      if ( !$p ) {
          $this->perm = array();
      } else {
          $k = array_search( $p, $this->perm );
          if ( $k ) {
              unset( $this->perm[ $k ] );
          }
      }
  }
  
  function have_perm( $p ) {
      return ( in_array( $p, $this->perm ) );
  }
}

function perm_unset() {
    unset( $GLOBALS['perm'] );
}

function perm_set() {
    $GLOBALS['perm'] = new Permission;
}

perm_set();

?>
