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
# Library with the functions for comments and faq
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: followmentlib.inc,v 1.5 2002/05/28 08:54:51 riessen Exp $
#
######################################################################

function show_followment($proid) {
  global $t, $bx, $db;

  $query = "SELECT * FROM milestones WHERE proid='$proid' ORDER BY number";
  $db->query($query);

  if ($db->num_rows() != 0) {
	$bx->box_begin();
	$bx->box_title($t->translate("Milestones"));
	$bx->box_body_begin();

        echo"<table border=1><tr>\n";
	echo "<td align=center><b>".$t->translate("Number")."</b></td>";
	echo "<td align=center><b>".$t->translate("Goals")."</b></td>";
	echo "<td align=center><b>".$t->translate("Release Date")."</b></td>";
	echo "<td align=center><b>".$t->translate("Product")."</b></td>";
	echo "<td align=center><b>".$t->translate("Payment")."</b></td>";
	echo "<td align=center><b>".$t->translate("Status")."</b></td>";
	echo "<td align=center><b>".$t->translate("Creation")."</b></td>";
	echo "</tr>\n";

        $colors = array( 0 => "gold", 1 => "#FFFFFF" );
	while($db->next_record()) {

            $bgcolor = $colors[ $db->f("number") % 2 ];

            echo "<tr bgcolor=$bgcolor>\n";
            echo "<td><b>".$db->f("number")."</b></td>";
            echo "<td><b>".$db->f("goals")."</b></td>";
            echo "<td><b>".timestr_middle(mktimestamp($db->f("release")))."</b></td>";
            echo "<td><b>".$db->f("product")."</b></td>";
            echo "<td><b>".$db->f("payment")."</b></td>";
            echo "<td><b>".show_status($db->f("status"))."</b></td>";
            echo "<td><b>".timestr_short(mktimestamp($db->f("creation")))."</b></td>";
            echo "</tr>\n";
  	}

        echo"</table>\n";
	$bx->box_body_end();
	$bx->box_end();
  }

  if ($db->num_rows() == 0) {
	print $t->translate("There have not been posted any milestones "
                            ."by the project main developer").".\n";
  }
}

?>

