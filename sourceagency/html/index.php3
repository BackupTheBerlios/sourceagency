<?php

######################################################################
# SourceAgency: Software Announcement & Retrieval System
# ================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
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

<P>SourceAgency is an .... writen in <a href="http://www.php.net">PHP3</a> and database independent. It includes user authentication and autherization system (sponsor/developer/editor/admin), sessions with and without cookies, high configurability, multilangual support, ease of administration, RDF-type document backend, advanced statistics, announcing mailing lists, application indexing by sections, installation support  and many other useful features.

<P>SourceWell depends on the PHPLib library (version 7.2d or later). Only if you want to have diary and weekly mailing lists with the announcements, you should also have GNU Mailman installed in your box.

<P>You can see a fully working example of the SourceAgency system at BerliOS
SourceAgency by visiting <A HREF="http://sourceagency.berlios.de">http://sourceagency.berlios.de</A>. A close look at it will show you what
you can do with SourceAgency.

<P>BerliOS SourceAgency is part of the BerliOS project at GMD FOKUS. Please, have
a look at <A HREF="http://www.berlios.de">http://www.berlios.de</A> for further information.

<P>SourceAgency can be easily translated into different
languages. If you see that SourceAgency does not have support in your
language, you're gladly invited to <A HREF="translating.php3">help us with the
internationalization</A> of SourceAgency by sending us your translation.

<P>You can download the latest version of SourceAgency (sources and documentation) at:
<A HREF="http://developer.berlios.de/projects/sourceagency">http://developer.berlios.de/projects/sourceagency</A>

<P>SourceAgency Features:
<UL>
<LI>Different type of users (nonauthorized users, sponsors, developers, editors and
administrators) with different functions
<LI>Advanced configurability from a single file
<LI>Simple, intuitive use of the system
<LI>Documentation for further development and/or adjustment
<LI>Session management with and without cookies
<LI>Through-the-web reviewing and project administration for editors
<LI>Through-the-web administration of applications, comments and licenses
<LI>system FAQ and through-the-web administration of it
<LI>Dynamic order of applications by date (default), importance, urgency or
by alphabetical order
<LI>"true" project counter
<LI>Multilingual support
<LI>Dynamic permission configuration
<LI>Stable and development branches for applications
<LI>XML Backend (RDF-document format)
<LI>Daily and Weekly automatic Newsletters
<LI>EMail advices
<LI>Graphical statistics
<LI>Web browser independence
<LI>Cache avoidance
</UL>

<P>&nbsp;

<!-- end content -->

<?php
require("footer.inc");
?>