<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ================================================
#
# Copyright (c) 2002-2003 by
#                Gregorio Robles (grex@scouts-es.org)
#                Gerrit Riessen (gerrit.riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# The SourceAgency Project Page
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
###################################################################### 

require("header.inc");

?>

<!-- content -->

<P><H2>SourceAgency</H2>

<?php
#
# this is maybe a little over the top!!! 
#
?>
<P>SourceAgency is an mediation portal between project sponsors and developers,
written in <a href="http://www.php.net">PHP</a> (versions 3 and 4) and is
database independent. It includes user authentication and authorization 
systems (sponsor/developer/editor/admin), sessions with and without cookies, 
high configurability, multilingual support, ease of administration, RDF-type 
document backend, advanced statistics, announcement mailing lists, 
application indexing by sections, installation support  and many other 
useful features.

<P>SourceAgency depends on the <A HREF="http://phplib.netuse.de/">PHPLib</a>
library (version 7.2d or later). 
<?php
#Only if you want to have diary and weekly mailing lists 
#with the announcements, you should also have GNU Mailman 
#installed in your box.
?>

<P>You can see a fully working example of the SourceAgency system at BerliOS
SourceAgency by visiting <A HREF="http://sourceagency.berlios.de"
>http://sourceagency.berlios.de</A>.

<P>BerliOS SourceAgency is part of the <A HREF="http://www.berlios.de"
>BerliOS</a> project at GMD (Now part of 
the <a href="http://www.fraunhofer.de">FhG</a>) FOKUS. 
For further information, have a look at the BerliOS main site 
<A HREF="http://www.berlios.de">http://www.berlios.de</A>.

<p>SourceAgency can be translated into different languages, if you would
like to help us to translate SourceAgency into your language, then please
check out the <A HREF="translating.php">translating</a> page.

<P>You can download the latest version of SourceAgency (sources and 
documentation) at:
<A HREF="http://developer.berlios.de/projects/sourceagency"
>http://developer.berlios.de/projects/sourceagency</A>

<P>SourceAgency Features:
<UL>
<LI>Different type of users (nonauthorized users, sponsors, developers, 
editors and
administrators) with different functions
<LI>Advanced configurability from a single file
<LI>Simple, intuitive use of the system
<LI>Documentation for further development and/or adjustment
<LI>Session management with and without cookies
<LI>Through-the-web reviewing and project administration for editors
<LI>Through-the-web administration of applications, comments and licenses
<LI>System FAQ and through-the-web administration of it
<LI>Dynamic order of applications by date (default), importance, 
urgency or
by alphabetical order
<?php 
#<LI>"true" project counter
?>
<LI>Multilingual support
<LI>Dynamic permission configuration
<?php 
#<LI>Stable and development branches for applications
#<LI>XML Backend (RDF-document format)
?>
<LI>Daily and Weekly Newsletters
<LI>Email notification
<?php 
//<LI>Graphical statistics
//<LI>Web browser independence
?>
<LI>Cache avoidance
</UL>

<P>&nbsp;

<!-- end content -->

<?php
require("footer.inc");
?>
