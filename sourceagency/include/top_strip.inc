<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This file shows a strip at the top of the page
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: top_strip.inc,v 1.2 2001/11/08 16:17:41 riessen Exp $
#
######################################################################

?>

<!-- top strip -->
<table BORDER=0 CELLSPACING=0 CELLPADDING=2 WIDTH="100%" BGCOLOR="<?php echo $th_navstrip_bgcolor;?>">
<tr>
<td width=45%><span class=maintitlebar>
<?php
$i = 0;
while (list($title, $url) = each($ts_array)) {
	if ($i > 0)
		echo "\n | ";
	echo "<b><a href=\"$url\" class=\"maintitlebar\">".$t->translate("$title")."</a></b>";
	$i++;
};
echo "\n</span></td><td width=55% align=right>\n";
if (isset($auth) && !empty($auth->auth["perm"])  && !($logout)) {
	echo "<FONT COLOR=\"".$th_nav_bgcolor."\">".$t->translate("Logged in")." <b>".$auth->auth["uname"]."</b> (<b>".$auth->auth["perm"]."</b>) / ".$t->translate("Expires")." <b>".timestr_short($auth->auth["exp"])."</b></FONT>";
} else {
	echo "<FONT COLOR=\"".$th_nav_bgcolor."\"><B>".$t->translate("Not Logged in")."</B></FONT>";
}
?>

</td>
</tr>

</table>
<!-- end top strip -->
