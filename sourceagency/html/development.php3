<?php

######################################################################
# SourceAgency:
# ================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
#                Gerrit Riessen (gerrit.riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# The SourceAgency Project Page
#
# It also shows the number of apps in each one
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
###################################################################### 

require("header.inc");

?>

<!-- content -->

<P><H2>For developers</H2>

<P>SourceAgency can be thought of as a mediation portal between project 
sponsors and project developers. It is part of the larger 
<a href="http://www.berlios.de">BerliOS</a> Platform which provides a complete
service for sponsored Open Source development.

<P>SourceAgency is Free Software / Open Source, 
so you're invited to contribute to it. To contribute, obtain either a 
<A HREF="http://developer.berlios.de/project/filelist.php?group_id=89"
>tarball</a> of the SourceAgency portal or check out the latest version
from the SourceAgency 
<A HREF="http://developer.berlios.de/cvs/?group_id=89"
>CVS repository</a>. There are also SourceAgency 
<a href="http://wiki.berlios.de/index.php?SourceAgency">Wiki</a> pages which 
provide help information to the development of SourceAgency.

<p>SourceAgency is exclusively developed in PHP (versions 3 and 4), so 
<A HREF="http://www.php.net">PHP</a> knowledge is essential. To understand
the underlying database structure, SQL experience is also preferable.
For more complex changes, a knowledge of <A HREF="http://phplib.netuse.de/"
>PHPLib</A> is also desirable. At the moment, PHPLib is used for session
and authentication.

<P>Please report to the <A HREF="authors.php3">authors</A> any adaptions 
and/or modifications. This
could help us knowing what SourceAgency should implement in the future
and making other SourceAgency users benefit from your work.


<P>&nbsp;

<!-- end content -->

<?php
require("footer.inc");
?>
