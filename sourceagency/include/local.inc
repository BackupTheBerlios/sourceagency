<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: local.inc,v 1.3 2001/11/06 11:56:47 riessen Exp $
 *
 */ 

######################################################################
# SourceAgency Database Configuration
#
# For using SourceAgency, you only have to fill in the appropriate
# parameters that fit your database
#
# The default (and recommended) configuration is the one with
# "sourceagency" as the database name. Do better not change it ;-)
#
######################################################################

class DB_SourceAgency extends DB_Sql {
  var $Host     = "localhost";
  var $Database = "sourceagency";
  var $User     = "operator";
  var $Password = "password";


}

/*********************************************************************/
/* If you've finished configuring the Database, you can login as an  */
/* administrator. To do so, just launch your web browser pointing to */
/* http://yourdomain.com/login.php3 and login as the default admin   */
/* with user,editor,admin permissions.                               */
/*                                                                   */
/* At the prompt use the following ID to login (case sensitive):     */
/*                                                                   */
/*         Username: admin                                           */
/*         Password: admin                                           */
/*                                                                   */
/* Vert important:                                                   */
/* Be sure of inmediately changing the login & password by clicking  */
/* on User Admin. You'll notice this is quite easy to do ;-)         */
/*								     */
/*               Thanks for having chosen SourceAgency               */
/*********************************************************************/


######################################################################
# SourceAgency Advanced Database Configuration
#
# If you've chosen "sourceagency" as your database name, you don't
# need to worry about the rest of the file ;-)
#
######################################################################

class SourceAgency_CT_Sql extends CT_Sql {
  var $database_class = "DB_SourceAgency";   ## Which database to connect...
  var $database_table = "active_sessions"; ## and find our session data in this table.
}

class SourceAgency_Session extends Session {
  var $classname = "SourceAgency_Session";

  var $cookiename     = "";                ## defaults to classname
  var $magic          = "Hocuspocus";      ## ID seed
  var $mode           = "cookie";          ## We propagate session IDs with cookies
#  var $mode = "get";
  var $fallback_mode  = "get";
  var $lifetime       = 0;                 ## 0 = do session cookies, else minutes
  var $that_class     = "SourceAgency_CT_Sql"; ## name of data storage container
  var $gc_probability = 5;  
}

class SourceAgency_User extends User {
  var $classname = "SourceAgency_User";

  var $magic          = "Abracadabra";     ## ID seed
  var $that_class     = "SourceAgency_CT_Sql"; ## data storage container
}

class SourceAgency_Auth extends Auth {
  var $classname      = "SourceAgency_Auth";

  var $lifetime       =  150;

  var $database_class = "DB_SourceAgency";
  var $database_table = "auth_user";
  
  function auth_loginform() {
    global $sess;
    global $_PHPLIB;

    include("loginform.ihtml");
  }
  
  function auth_validatelogin() {
    global $username, $password;

    if(isset($username)) {
      $this->auth["uname"]=$username;        ## This provides access for "loginform.ihtml"
    }
    
    
    $uid = false;
    
    $this->db->query(sprintf("select user_id, perms ".
                             "        from %s ".
                             "       where username = '%s' ".
                             "         and password = '%s'",
                          $this->database_table,
                          addslashes($username),
                          addslashes($password)));

    while($this->db->next_record()) {
      $uid = $this->db->f("user_id");
      $this->auth["perm"] = $this->db->f("perms");
    }
    return $uid;
  }
}

class SourceAgency_Default_Auth extends SourceAgency_Auth {
  var $classname = "SourceAgency_Default_Auth";
  
  var $nobody    = true;
}

class SourceAgency_Challenge_Auth extends Auth {
  var $classname      = "SourceAgency_Challenge_Auth";

  var $lifetime       =  1;

  var $magic          = "Simsalabim";  ## Challenge seed
  var $database_class = "DB_SourceAgency";
  var $database_table = "auth_user";

  function auth_loginform() {
    global $sess;
    global $challenge;
    global $_PHPLIB;
    
    $challenge = md5(uniqid($this->magic));
    $sess->register("challenge");
    
    include($_PHPLIB["libdir"] . "crloginform.ihtml");
  }
  
  function auth_validatelogin() {
    global $username, $password, $challenge, $response;

    if(isset($username)) {
      $this->auth["uname"]=$username;        ## This provides access for "loginform.ihtml"
    }
    $this->db->query(sprintf("select user_id,perms,password ".
                "from %s where username = '%s'",
                          $this->database_table,
                          addslashes($username)));

    while($this->db->next_record()) {
      $uid   = $this->db->f("user_id");
      $perm  = $this->db->f("perms");
      $pass  = $this->db->f("password");
    }
    $exspected_response = md5("$username:$pass:$challenge");

    ## True when JS is disabled
    if ($response == "") {
      if ($password != $pass) {
        return false;
      } else {
        $this->auth["perm"] = $perm;
        return $uid;
      }
    }
    
    ## Response is set, JS is enabled
    if ($exspected_response != $response) {
      return false;
    } else {
      $this->auth["perm"] = $perm;
      return $uid;
    }
  }
}

##
## SourceAgency_Challenge_Crypt_Auth: Keep passwords in md5 hashes rather 
##                           than cleartext in database
## Author: Jim Zajkowski <jim@jimz.com>

class SourceAgency_Challenge_Crypt_Auth extends Auth {
  var $classname      = "SourceAgency_Challenge_Crypt_Auth";

  var $lifetime       =  1;

  var $magic          = "Frobozzica";  ## Challenge seed
  var $database_class = "DB_SourceAgency";
  var $database_table = "auth_user_md5";

  function auth_loginform() {
    global $sess;
    global $challenge;
    
    $challenge = md5(uniqid($this->magic));
    $sess->register("challenge");
    
    include("crcloginform.ihtml");
  }
  
  function auth_validatelogin() {
    global $username, $password, $challenge, $response;

    $this->auth["uname"]=$username;        ## This provides access for "loginform.ihtml"
    
    $this->db->query(sprintf("select user_id,perms,password ".
                "from %s where username = '%s'",
                          $this->database_table,
                          addslashes($username)));

    while($this->db->next_record()) {
      $uid   = $this->db->f("user_id");
      $perm  = $this->db->f("perms");
      $pass  = $this->db->f("password");   ## Password is stored as a md5 hash
    }
    $exspected_response = md5("$username:$pass:$challenge");

    ## True when JS is disabled
    if ($response == "") {
      if (md5($password) != $pass) {       ## md5 hash for non-JavaScript browsers
        return false;
      } else {
        $this->auth["perm"] = $perm;
        return $uid;
      }
    }
    
    ## Response is set, JS is enabled
    if ($exspected_response != $response) {
      return false;
    } else {
      $this->auth["perm"] = $perm;
      return $uid;
    }
  }
}

class SourceAgency_Perm extends Perm {
  var $classname = "SourceAgency_Perm";
  
  var $permissions = array(
                            "sponsor_pending" => 1,
                            "devel_pending" => 1,
                            "devel"    => 2,
                            "sponsor"      => 4,
                            "editor"      => 8,
                            "admin"      => 16
                          );

  function perm_invalid($does_have, $must_have) {
    global $perm, $auth, $sess;
    global $_PHPLIB;
    
    include($_PHPLIB["libdir"] . "perminvalid.ihtml");
  }
}

?>