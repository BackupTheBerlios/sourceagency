<?php
// mock_auth.php
// 
// Author: Gerrit Riessen, gerrit.riessen@open-source-consultants.de
// Copyright (C) 2001 Gerrit Riessen
// 
// $Id: mock_auth.php,v 1.2 2001/11/06 11:56:47 riessen Exp $

// mock the Auth class of the PhpLib (??) library.
class Auth 
{
    var $auth;
    function Auth() {
        $this->auth = array();
    }

    function set_uname( $name ) {
        $this->_set_attribute( 'uname', $name );
    }
    function set_perm( $perm ) {
        $this->_set_attribute( 'perm', $perm );
    }

    function _set_attribute( $att_name, $att_value ) {
        $this->auth[$att_name] = $att_value;
    }
}

$auth = new Auth;
global $auth;

?>
