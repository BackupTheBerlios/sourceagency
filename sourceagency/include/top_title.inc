<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org) and
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
# $Id: top_title.inc,v 1.12 2002/05/07 19:54:27 grex Exp $
#
######################################################################

?>

<!-- top title -->

<table border="0" cellspacing="0" cellpadding="0" width="100%">

<tr valign="top" bgcolor="<?php echo $th_nav_bgcolor;?>">
<td bgcolor="#FFFFFF">
<a href="<?php echo $sys_url_title;?>">
<img src="<?php echo $sys_logo_image;?>" border="0" 
height="<?php echo $sys_logo_height;?>" width="<?php echo $sys_logo_width;?>"
vspace="5" hspace="5" alt="<?php echo $sys_logo_alt;?>"></a>
</td>

<td width="10">
<img src="images/blank.gif" border="0" height="1" width="10" alt="">
</td>

<td align="left" valign="middle" > <!-- removed att: width="99%" -->
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
<td valign="middle">
<a href="<?php echo $org_url;?>" target="_blank">
<img src="<?php echo $org_logo_image;?>" alt="<?php echo $org_logo_alt;?>" 
hspace="10" border="0" height="<?php echo $org_logo_height;?>" 
width="<?php echo $org_logo_width;?>" align="RIGHT"></a>
</td>
</tr>
<!-- end logo at right -->

<tr>
<td colspan="5" bgcolor="<?php echo $th_navstrip_bgcolor;?>">
<img src="images/blank.gif" height="1" width="1" alt="">
</td>
</tr>

</table>

<!-- end top title -->
