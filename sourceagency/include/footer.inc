<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org),
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Footer of all the SourceAgency pages
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: footer.inc,v 1.7 2002/05/07 20:17:55 grex Exp $
#
######################################################################

?>
<br><p>
</td>
</tr>
</table>

<!-- footer -->
<table border="0" cellspacing="0" cellpadding="2" width="100%" 
bgcolor="<?php echo $th_navstrip_bgcolor;?>">
<tr>
<td width="15%">&nbsp;</td>
<td align="center" width="70%"><span class="titlebar">
<font color="<?php echo $th_navstrip_font_color;?>">Copyright
&copy; 2001,2002 <a href="<?php echo $org_url;?>" class="maintitlebar" 
target="_blank"><?php echo $org_name;?></a>.</font></span></td>
<td width=15% align=right><span class="titlebar">
<a href="<?php echo $sys_url;?>html" target="_blank"><font 
color="<?php echo $th_nav_bgcolor;?>"
><?php echo $t->translate('System Credits');?></font></<a>&nbsp;</span></td>
</tr>
</table>
<!-- end footer -->

</body>
<?php
#
# the last we do is shut down the logger
#
$l = new Logger;
$l->close();
?>
</html>
