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
# Imitate the PHPLib Auth class.
# 
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: mock_auth.php,v 1.3 2001/11/08 16:17:42 riessen Exp $
#
######################################################################

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
