<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Lutz Henckel (lutz.henckel@fokus.fraunhofer.de),
#                Gregorio Robles (grex@scouts-es.org) and
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Right menubar (navigation bar)
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: menubar.inc,v 1.1 2003/11/21 12:56:01 helix Exp $
#
######################################################################

global $PHP_SELF;
?>

<!-- BerliOS menu -->
<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%">
<tr BGCOLOR="<?php echo $th_navstrip_bgcolor;?>">
<td ALIGN=CENTER>
<span class="titlebar"><font color="<?php echo $th_navstrip_font_color;?>"><?php echo $sys_name?></font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="<?php echo $th_nav_bgcolor;?>">
<td><a href="<?php $sess->purl('index.php') ?>" class="menus"><?php echo $t->translate("Index"); ?></a>
<br><a href="<?php $sess->purl('faq.php') ?>" class="menus"><?php echo $t->translate("Q&amp;A"); ?></a>
<br><a target="_blank" href="<?php print $sess->url('doco.php') . $sess->add_query(array('page' => $PHP_SELF)) ?>" class="menus"><?php echo $t->translate('What is this?'); ?></a>

<br><br>

<?php
if (isset($auth) && !empty($auth->auth['perm']) && !($logout) ) {
  if ($perm->have_perm('devel_pending') 
     || $perm->have_perm('sponsor_pending') 
     || $perm->have_perm('devel') || $perm->have_perm('sponsor') 
     || $perm->have_perm('editor') || $perm->have_perm('admin')) {
?>
<a href="<?php $sess->purl('logout.php') ?>" class="menus"
><?php echo $t->translate('Logout'); ?></a>
<?php
    echo "<br><a href=\"".$sess->url('personal.php')
         .$sess->add_query(array('username' => $auth->auth['uname']))
         ."\" class=\"menus\">".$t->translate('Personal Page')."</a>\n";
  }
} else {
?>
<a href="<?php $sess->purl('login.php') ?>" class="menus">
   <?php echo $t->translate('Login'); ?></a>
<?php
}
?>
<br><a href="<?php $sess->purl('register.php') ?>" class="menus"><?php echo $t->translate('New User'); ?></a>
<br><a href="<?php $sess->purl('remind.php') ?>" class="menus"><?php echo $t->translate('Forgot Password'); ?></a>
<?php
if ( isset($auth) && !empty($auth->auth['perm']) && !($logout)) {
  if ($perm->have_perm('devel') || $perm->have_perm('sponsor') 
      || $perm->have_perm('devel_pending') 
      || $perm->have_perm('sponsor_pending') || $perm->have_perm('editor') 
      || $perm->have_perm('admin')) {
?>
<br><a href="<?php $sess->purl('chguser.php') ?>" class="menus"
><?php echo $t->translate('Change User Info'); ?></a>

<?php
	}
}

if (($config_perm_users == 'all') || (isset($perm) && $perm->have_perm($config_perm_users))  && !($logout)) {
?>
<br><a href="<?php $sess->purl('users.php') ?>" class="menus"><?php echo $t->translate("Users"); ?></a>
<br><a href="<?php $sess->purl('users.php?type=devel') ?>" class="menus"><?php echo $t->translate('Developers'); ?></a>
<br><a href="<?php $sess->purl('users.php?type=sponsor') ?>" class="menus"><?php echo $t->translate('Sponsors'); ?></a>
<?php
}
?>
<br>&nbsp;

<br>

<a href="<?php $sess->purl('browse.php') ?>" class="menus"><?php echo $t->translate('Browse'); ?></a>

<?php

echo "<br><a href=\"".$sess->url('insform.php')."\" class=\"menus\">".$t->translate('New Project')."</a>\n";

?>
<br><a href="<?php $sess->purl('licenses.php'); ?>" class="menus"><?php echo $t->translate('Licenses'); ?></a>
<br>
<!--
<br><a href="<?php $sess->purl('newsletter.php'); ?>" class="menus"><?php echo $t->translate('Newsletter'); ?></a>
<br><a href="<?php $sess->purl('backend.php'); ?>" class="menus"><?php echo $t->translate('Backend'); ?></a>
-->
</td>
</tr>
</table>
<!-- end BerliOS menu -->
<!-- Admin menu -->
<?php

if (
    (($config_perm_admuser == 'all') ||
       ($config_perm_admlicens == 'all') ||
       ($config_perm_admcomment == 'all') ||
       ($config_perm_admsec == 'all') ||
       ($config_perm_admcat == 'all') ||
       ($config_perm_nladm == 'all') ||
       ($config_perm_admfaq == 'all') ||
       ($config_perm_admdoco == 'all') ||
       ($config_perm_admprojects == 'all'))
  || (isset($perm) &&
       (($perm->have_perm($config_perm_admuser)) ||
        ($perm->have_perm($config_perm_admlicens)) ||
        ($perm->have_perm($config_perm_admcomment)) ||
        ($perm->have_perm($config_perm_admsec)) ||
        ($perm->have_perm($config_perm_admcat)) ||
        ($perm->have_perm($config_perm_nladm)) ||
        ($perm->have_perm($config_perm_admfaq)) ||
        ($perm->have_perm($config_perm_admdoco)) ||
        ($perm->have_perm($config_perm_admprojects)) ))
  && !($logout)) {

?>
<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%">
<tr BGCOLOR="<?php echo $th_navstrip_bgcolor;?>">
<td ALIGN=CENTER><img SRC="images/blank.gif" height="1" width="135" border=0><br>
<span class="titlebar"><font color="<?php echo $th_navstrip_font_color;?>"><?php echo $t->translate('Administration'); ?></font></span></td>
</tr>

<tr ALIGN="RIGHT" BGCOLOR="<?php echo $th_nav_bgcolor;?>">
<td>
<?php

  if (($config_perm_admuser == 'all') 
      || (isset($perm) && $perm->have_perm($config_perm_admuser))  
      && !($logout)) {
    $db->query('SELECT COUNT(*) FROM auth_user');
    $db->next_record();
    echo "<a href=\"".$sess->url('admuser.php')."\" class=\"menus\">"
    .$t->translate('Users')." [".$db->f("COUNT(*)")."]</a>\n";
  }


  if (($config_perm_admlicens == 'all') 
      || (isset($perm) && $perm->have_perm($config_perm_admlicens))  
      && !($logout)) {
    $db->query("SELECT COUNT(*) FROM licenses");
    $db->next_record();
    echo "<br><a href=\"".$sess->url('admlicens.php')."\" class=\"menus\">"
      .$t->translate('Licenses')." [".$db->f("COUNT(*)")."]</a>\n";
  }

  if (($config_perm_admprojects == 'all') 
      || (isset($perm) && $perm->have_perm($config_perm_admprojects))  
      && !($logout)) {
    $db->query("SELECT COUNT(*) FROM description WHERE status='0'");
    $db->next_record();
    echo "<br><a href=\"".$sess->url('admprojects.php')
      ."\" class=\"menus\">".$t->translate('Pending Proj.')
      ." [".$db->f("COUNT(*)")."]</a>\n";
  }


  if (($config_perm_admfaq == 'all') 
      || (isset($perm) && $perm->have_perm($config_perm_admfaq))  
      && !($logout)) {
    $db->query("SELECT COUNT(*) FROM faq");
    $db->next_record();
    echo "<br><a href=\"".$sess->url('admfaq.php')."\" class=\"menus\">"
      .$t->translate('Q&A')." [".$db->f("COUNT(*)")."]</a>\n";
  }

  if (($config_perm_admdoco == 'all') 
      || (isset($perm) && $perm->have_perm($config_perm_admdoco))  
      && !($logout)) {
    $db->query("SELECT COUNT(*) FROM doco");
    $db->next_record();
    echo "<br><a href=\"".$sess->url('admdoco.php')."\" class=\"menus\">"
      .$t->translate('AdmDoco')." [".$db->f("COUNT(*)")."]</a>\n";
  }

?>
</td>
</tr>
</table>
<?php
}
?>
<!-- end Admin menu -->
<!-- Search menu -->
<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%">
<tr BGCOLOR="<?php echo $th_navstrip_bgcolor;?>">
<td ALIGN=CENTER><img SRC="images/blank.gif" height="1" width="135" border=0><br>
<span class="titlebar"><font color="<?php echo $th_navstrip_font_color;?>"><?php echo $t->translate("Search"); ?></font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="<?php echo $th_nav_bgcolor;?>">
<td align=center>
<p>
<form action="<?php $sess->purl("search.php"); ?>">
<input TYPE="text" SIZE="10" NAME="search" VALUE="">
<?php
echo "<p><input TYPE=\"submit\" NAME=\"Search\" VALUE=\""
    .$t->translate("Search")."\">";
?>
</form>
</td>
</tr>
</table>
<!-- end Search menu -->
<!-- Language menu -->
<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%">
<tr BGCOLOR="<?php echo $th_navstrip_bgcolor;?>">
<td ALIGN=CENTER><img SRC="images/blank.gif" height="1" width="135" border=0><br>
<span class="titlebar"><font color="<?php echo $th_navstrip_font_color;?>"><?php echo $t->translate("Language");?></font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="<?php echo $th_nav_bgcolor;?>">
<td align=center>
<p>
<form action="<?php $sess->pself_url()?>">
<?php
while ( is_array($HTTP_GET_VARS) 
        && list($key, $val) = each($HTTP_GET_VARS)) {
          if ($key != "lang" && $key != "go") {
           echo "<input TYPE=\"hidden\" NAME=\"$key\" VALUE=\"$val\">\n";
         }
       }
?>
<select name="lang" size="1">
<?php
while (list(, $ln) = each($la_array)) {
  $sel = ( $la == $ln ? "selected" : "" );
  echo "<option value=\"$ln\" $sel>".$t->translate($ln)."</option>\n";
}
if ( is_administrator() ) {
  echo "<option value=\"Unknown\"" . ($la == "Unknown" ? "selected" : "")
    .">".$t->translate("Unknown")."</option>\n";
}
?>
</select>
<?php
echo "<p><input TYPE=\"submit\" NAME=\"go\" VALUE=\""
       .$t->translate("Go")."\">";
?>
</form>
</td>
</tr>
</table>
<p align="center"><a href="http://developer.berlios.de"><img src="http://developer.berlios.de/bslogo.php?group_id=89&type=1" width="124" height="32" border="0" hspace="3" vspace="3" alt="BerliOS Logo"><br>Developer</a>
<!-- end Language menu -->
