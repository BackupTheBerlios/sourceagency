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
# This file shows a strip at the top of the page
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: top_strip.inc,v 1.1 2003/11/21 12:56:02 helix Exp $
#
######################################################################

?>

<!-- top strip -->
<table border="0" cellspacing="0" cellpadding="2" width="100%" 
       bgcolor="<?php echo $th_navstrip_bgcolor;?>">
<tr>
<td width="45%"><span class=maintitlebar>
<?php
$i = 0;
while (list($title, $url) = each($ts_array)) {
	if ($i > 0)
		echo "\n | ";
	echo "<b><a href=\"$url\" class=\"maintitlebar\">".$t->translate("$title")."</a></b>";
	$i++;
};
echo "\n</span></td><td width=\"55%\" align=\"right\">\n";
if (isset($auth) && !empty($auth->auth["perm"])  && !($logout)) {
	echo "<font color=\"".$th_nav_bgcolor."\">".$t->translate("Logged in")." <b>".$auth->auth["uname"]."</b> (<b>".$t->translate($auth->auth["perm"])."</b>) / ".$t->translate("Expires")." <b>".timestr_short($auth->auth["exp"])."</b></font>";
} else {
	echo "<font color=\"".$th_nav_bgcolor."\"><b>".$t->translate("Not Logged in")."</b></font>";
}
?>

</td>
</tr>

</table>
<!-- end top strip -->
