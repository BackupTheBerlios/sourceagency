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
# This file shows a bar with a title at the top of the pages
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: top_title.inc,v 1.10 2002/03/06 12:58:17 riessen Exp $
#
######################################################################

?>

<!-- top title -->

<table BORDER="0" CELLSPACING="0" CELLPADDING="0" WIDTH="100%" 
valign="center" >

<tr VALIGN="TOP" BGCOLOR="<?php echo $th_nav_bgcolor;?>">
<td bgcolor="#FFFFFF">
<a href="<?php echo $sys_url_title;?>">
<img SRC="<?php echo $sys_logo_image;?>" BORDER="0" 
height="<?php echo $sys_logo_height;?>" width="<?php echo $sys_logo_width;?>"
vspace="5" hspace="5" ALT="<?php echo $sys_logo_alt;?>"></a>
</td>

<td WIDTH="10">
<img SRC="images/blank.gif" BORDER="0" height="1" width="10">
</td>

<td align="left" VALIGN="middle" > <!-- removed att: WIDTH="99%" -->
<p align="left">
<font color="<?php echo $th_nav_font_color;?>">
<b><font size="+1"><?php echo $sys_name;?></font></b>
<br><?php echo $t->translate($sys_title);?></font></p>
</td>

<!-- beta string -->
<td align="center" valign="middle">
<center>
<font font="times" size="+2" color="red">BETA BETA BETA</font><br>
<?php echo $t->translate("This site is being beta tested"); ?>, 
<?php echo $t->translate("please send any and all"); ?>
 <a href="mailto:sourceagency-support@lists.berlios.de?subject=SourceAgency Beta Feedback&cc=grex@scouts-es.org&cc=lutz.henckel@fokus.fhg.de&cc=riessen@berlios.de"
><?php echo $t->translate("feedback"); ?></a>!
</center>
</td>

<!-- logo at right -->
<td VALIGN="middle">
<a href="<?php echo $org_url;?>" TARGET="_blank">
<img SRC="<?php echo $org_logo_image;?>" ALT="<?php echo $org_logo_alt;?>" 
HSPACE="10" BORDER="0" height="<?php echo $org_logo_height;?>" 
width="<?php echo $org_logo_width;?>" align="RIGHT"></a>
</td>
</tr>
<!-- end logo at right -->

<tr>
<td COLSPAN="5" BGCOLOR="<?php echo $th_navstrip_bgcolor;?>">
<img SRC="images/blank.gif" height="1" width="1">
</td>
</tr>

</table>

<!-- end top title -->
