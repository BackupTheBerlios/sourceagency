<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org)
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# TODO: description missing
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: menubar.inc,v 1.3 2001/11/08 16:17:41 riessen Exp $
#
######################################################################

?>

<!-- BerliOS menu -->
<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%">
<tr BGCOLOR="<?php echo $th_navstrip_bgcolor;?>">
<td ALIGN=CENTER>
<span class="titlebar"><font color="<?php echo $th_navstrip_font_color;?>"><?php echo $sys_name?></font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="<?php echo $th_nav_bgcolor;?>">
<td><a href="<?php $sess->purl("index.php3") ?>" class="menus"><?php echo $t->translate("Index"); ?></a>
<br><a href="<?php $sess->purl("faq.php3") ?>" class="menus"><?php echo $t->translate("Q&amp;A"); ?></a>

<br><br>

<?php
if (isset($auth) && !empty($auth->auth["perm"]) && !($logout) ) {
  if ($perm->have_perm("devel_pending") || $perm->have_perm("sponsor_pending") || $perm->have_perm("devel") || $perm->have_perm("sponsor") || $perm->have_perm("editor") || $perm->have_perm("admin")) {
?>
<a href="<?php $sess->purl("logout.php3") ?>" class="menus"><?php echo $t->translate("Logout"); ?></a>
<?php
echo "<br><a href=\"".$sess->url("personal.php3").$sess->add_query(array("username" => $auth->auth["uname"]))."\" class=\"menus\">".$t->translate("Personal Page")."</a>\n";
  }
} else {
?>
<a href="<?php $sess->purl("login.php3") ?>" class="menus"><?php echo $t->translate("Login"); ?></a>
<?php
}
?>
<br><a href="<?php $sess->purl("register.php3") ?>" class="menus"><?php echo $t->translate("New User"); ?></a>
<br><a href="<?php $sess->purl("remind.php3") ?>" class="menus"><?php echo $t->translate("Forgot Password"); ?></a>
<?php
if (isset($auth) && !empty($auth->auth["perm"]) && !($logout)) {
	if ($perm->have_perm("devel") || $perm->have_perm("sponsor") || $perm->have_perm("devel_pending") || $perm->have_perm("sponsor_pending") || $perm->have_perm("editor") || $perm->have_perm("admin")) {
?>
<br><a href="<?php $sess->purl("chguser.php3") ?>" class="menus"><?php echo $t->translate("Change User"); ?></a>
<?php
	}
}

if (($config_perm_users == "all") || (isset($perm) && $perm->have_perm($config_perm_users))  && !($logout)) {
?>
<br><a href="<?php $sess->purl("users.php3") ?>" class="menus"><?php echo $t->translate("Users"); ?></a>
<br><a href="<?php $sess->purl("users.php3?type=devel") ?>" class="menus"><?php echo $t->translate("Developers"); ?></a>
<br><a href="<?php $sess->purl("users.php3?type=sponsor") ?>" class="menus"><?php echo $t->translate("Sponsors"); ?></a>
<?php
}
?>
<br>&nbsp;

<br>

<a href="<?php $sess->purl("browse.php3") ?>" class="menus"><?php echo $t->translate("Browse"); ?></a>

<?php

echo "<br><a href=\"".$sess->url("insform.php3")."\" class=\"menus\">".$t->translate("New Project")."</a>\n";

/*
if (($config_perm_apppend == "all") || (isset($perm) && $perm->have_perm($config_perm_apppend))  && !($logout)) {
  $db->query("SELECT COUNT(*) FROM developing,sponsoring WHERE developing.status='P' OR sponsoring.status='P'");
  $db->next_record();
  echo "<br><a href=\"".$sess->url("apppend.php3")."\" class=\"menus\">".$t->translate("Pending Projects")." [".$db->f("COUNT(*)")."]</a>\n";
}

if (($config_perm_appdom == "all") || (isset($perm) && $perm->have_perm($config_perm_appdom))  && !($logout)) {
  $db->query("SELECT COUNT(*) FROM developing,sponsoring WHERE developing.status='D' OR sponsoring.status='D'");
  $db->next_record();
  echo "<br><a href=\"".$sess->url("appdom.php3?action=delete")."\" class=\"menus\">".$t->translate("Deleted Projects")." [".$db->f("COUNT(*)")."]</a>\n";
  $db->query("SELECT COUNT(*) FROM developing,sponsoring WHERE developing.status='M' OR sponsoring.status='M'");
  $db->next_record();
  echo "<br><a href=\"".$sess->url("appdom.php3?action=modify")."\" class=\"menus\">".$t->translate("Modified Projects")." [".$db->f("COUNT(*)")."]</a>\n";
}
*/
?>
<br><a href="<?php $sess->purl("licenses.php3"); ?>" class="menus"><?php echo $t->translate("Licenses"); ?></a>
<br>
<!--
<br><a href="<?php $sess->purl("newsletter.php3"); ?>" class="menus"><?php echo $t->translate("Newsletter"); ?></a>
<br><a href="<?php $sess->purl("backend.php3"); ?>" class="menus"><?php echo $t->translate("Backend"); ?></a>
-->
<?php
/*
// The credits have been moved to the footer
<br><a href="/html" class="menus"><?php echo $t->translate("About"); ?></a>
*/
?>
</td>
</tr>
</table>
<!-- end BerliOS menu -->
<!-- Admin menu -->
<?php

if (
    (($config_perm_admuser == "all") ||
       ($config_perm_admlicens == "all") ||
       ($config_perm_admcomment == "all") ||
       ($config_perm_admsec == "all") ||
       ($config_perm_admcat == "all") ||
       ($config_perm_nladm == "all") ||
       ($config_perm_admfaq == "all") ||
       ($config_perm_admprojects == "all"))
  || (isset($perm) &&
       (($perm->have_perm($config_perm_admuser)) ||
        ($perm->have_perm($config_perm_admlicens)) ||
        ($perm->have_perm($config_perm_admcomment)) ||
        ($perm->have_perm($config_perm_admsec)) ||
        ($perm->have_perm($config_perm_admcat)) ||
        ($perm->have_perm($config_perm_nladm)) ||
        ($perm->have_perm($config_perm_admfaq)) ||
        ($perm->have_perm($config_perm_admprojects)) ))
  && !($logout)) {

?>
<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%">
<tr BGCOLOR="<?php echo $th_navstrip_bgcolor;?>">
<td ALIGN=CENTER><img SRC="http://www.berlios.de/images/blank.gif" height="1" width="135" border=0><br>
<span class="titlebar"><font color="<?php echo $th_navstrip_font_color;?>"><?php echo $t->translate("Administration"); ?></font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="<?php echo $th_nav_bgcolor;?>">
<td>
<?php

  if (($config_perm_admuser == "all") || (isset($perm) && $perm->have_perm($config_perm_admuser))  && !($logout)) {
    $db->query("SELECT COUNT(*) FROM auth_user");
    $db->next_record();
    echo "<a href=\"".$sess->url("admuser.php3")."\" class=\"menus\">".$t->translate("Users")." [".$db->f("COUNT(*)")."]</a>\n";
  }

/*
  if (($config_perm_admsec == "all") || (isset($perm) && $perm->have_perm($config_perm_admsec))  && !($logout)) {
    $db->query("SELECT DISTINCT section,COUNT(*) FROM categories GROUP BY section");
    $db->next_record();
    echo "<br><a href=\"".$sess->url("admsec.php3")."\" class=\"menus\">".$t->translate("Sections")." [".$db->f("COUNT(*)")."]</a>\n";
  }

  if (($config_perm_admcat == "all") || (isset($perm) && $perm->have_perm($config_perm_admcat))  && !($logout)) {
    $db->query("SELECT COUNT(*) FROM categories");
    $db->next_record();
    echo "<br><a href=\"".$sess->url("admcat.php3")."\" class=\"menus\">".$t->translate("Categories")." [".$db->f("COUNT(*)")."]</a>\n";
  }
*/

  if (($config_perm_admlicens == "all") || (isset($perm) && $perm->have_perm($config_perm_admlicens))  && !($logout)) {
    $db->query("SELECT COUNT(*) FROM licenses");
    $db->next_record();
    echo "<br><a href=\"".$sess->url("admlicens.php3")."\" class=\"menus\">".$t->translate("Licenses")." [".$db->f("COUNT(*)")."]</a>\n";
  }

  if (($config_perm_admprojects == "all") || (isset($perm) && $perm->have_perm($config_perm_admprojects))  && !($logout)) {
    $db->query("SELECT COUNT(*) FROM description WHERE status='0'");
    $db->next_record();
    echo "<br><a href=\"".$sess->url("admprojects.php3")."\" class=\"menus\">".$t->translate("Pending Proj.")." [".$db->f("COUNT(*)")."]</a>\n";
  }

/*
  if (($config_perm_admcomment == "all") || (isset($perm) && $perm->have_perm($config_perm_admcomment))  && !($logout)) {
    $db->query("SELECT COUNT(*) FROM comments");
    $db->next_record();
    echo "<br><a href=\"".$sess->url("admcomment.php3")."\" class=\"menus\">".$t->translate("Comments")." [".$db->f("COUNT(*)")."]</a>\n";
  }
*/

  if (($config_perm_admfaq == "all") || (isset($perm) && $perm->have_perm($config_perm_admfaq))  && !($logout)) {
    $db->query("SELECT COUNT(*) FROM faq");
    $db->next_record();
    echo "<br><a href=\"".$sess->url("admfaq.php3")."\" class=\"menus\">".$t->translate("Q&A")." [".$db->f("COUNT(*)")."]</a>\n";
  }

/*
  if (($config_perm_admdate == "all") || (isset($perm) && $perm->have_perm($config_perm_admdate))  && !($logout)) {
    $db->query("SELECT DISTINCT software.name,COUNT(*) FROM history,software WHERE software.modification!=history.creation_his AND history.version_his=software.version AND history.appid=software.appid GROUP by software.appid");
    $db->next_record();
    $db_count = $db->f("COUNT(*)");
    if ($db_count = NULL) $db_count = 0;
    echo "<br><a href=\"".$sess->url("admdate.php3")."\" class=\"menus\">".$t->translate("Check Date")." [".$db_count."]</a>\n";
  }


  if (($config_perm_nladm == "all") || (isset($perm) && $perm->have_perm($config_perm_nladm))  && !($logout)) {
				// Daily Newsletter
    $where = "DATE_FORMAT(projects.creation,'%Y-%m-%d')=\"".date("Y-m-d")."\"";

    $db->query("SELECT COUNT(*) FROM projects,developing,sponsoring WHERE $where AND (projects.proid=developing.proid OR projects.proid=sponsoring.proid) AND (developing.status='A' OR sponsoring.status='A')"); 
    $db->next_record();

    echo "<br><a href=\"".$sess->url("nladm.php3?period=daily")."\" class=\"menus\">".$t->translate("Daily Newsletter")." [".$db->f("COUNT(*)")."]</a>\n";
				// Weekly Newsletter
    $lastday = time() - 7 * 24 * 60 * 60;
    $where = "DATE_FORMAT(projects.creation,'%Y-%m-%d')<=\"".date("Y-m-d")."\" AND DATE_FORMAT(projects.creation,'%Y-%m-%d')>\"".date("Y-m-d",$lastday)."\"";

    $db->query("SELECT COUNT(*) FROM projects,developing,sponsoring WHERE $where AND (projects.proid=developing.proid OR projects.proid=sponsoring.proid) AND (developing.status='A' OR sponsoring.status='A')"); 
    $db->next_record();

    echo "<br><a href=\"".$sess->url("nladm.php3?period=weekly")."\" class=\"menus\">".$t->translate("Weekly Newsletter")." [".$db->f("COUNT(*)")."]</a>\n";
  }
*/
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
<td ALIGN=CENTER><img SRC="http://www.berlios.de/images/blank.gif" height="1" width="135" border=0><br>
<span class="titlebar"><font color="<?php echo $th_navstrip_font_color;?>"><?php echo $t->translate("Search"); ?></font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="<?php echo $th_nav_bgcolor;?>">
<td align=center>
<p>
<form action="<?php $sess->purl("search.php3"); ?>">
<input TYPE="text" SIZE="10" NAME="search" VALUE="">
<?php
echo "<p><input TYPE=\"submit\" NAME=\"Search\" VALUE=\"".$t->translate("Search")."\">";
?>
</form>
</td>
</tr>
</table>
<!-- end Search menu -->
<!-- Language menu -->
<table BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH="100%">
<tr BGCOLOR="<?php echo $th_navstrip_bgcolor;?>">
<td ALIGN=CENTER><img SRC="http://www.berlios.de/images/blank.gif" height="1" width="135" border=0><br>
<span class="titlebar"><font color="<?php echo $th_navstrip_font_color;?>"><?php echo $t->translate("Language");?></font></span></td>
</tr>

<tr ALIGN=RIGHT BGCOLOR="<?php echo $th_nav_bgcolor;?>">
<td align=center>
<p>
<form action="<?php $sess->pself_url()?>">
<?php
while (is_array($HTTP_GET_VARS) 
		&& list($key, $val) = each($HTTP_GET_VARS)) {
	if ($key != "lang" && $key != "go") {
		echo "<input TYPE=\"hidden\" NAME=\"$key\" VALUE=\"$val\">\n";
	}
}
?>
<select name="lang" size=1>
<?php
while (list(, $ln) = each($la_array)) {
	if ($la == $ln) {
		$sel = "selected";
	} else {
		$sel ="";
	}
	echo "<option value=\"$ln\" $sel>".$t->translate($ln)."\n";
}
?>
</select>
<?php
echo "<p><input TYPE=\"submit\" NAME=\"go\" VALUE=\"".$t->translate("Go")."\">";
?>
</form>
</td>
</tr>
</table>
<!-- end Language menu -->