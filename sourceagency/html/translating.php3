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
# It also shows the number of apps in each one
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
###################################################################### 

require("header.inc");

?>

<!-- content -->

<A NAME="international">
<P><H2>International support</H2>

<P>SourceAgency can be translated into different
languages. If you notice that SourceAgency does not support your
language, you're gladly invited to help us with the
internationalization of SourceAgency by providing us your translation.

<P>You don't need to have any programming experience to do provide
translation information. 

<A NAME="normal_outputs">
<P><H3>1. Main outputs</H3>

<P>Download the <a href="http://cvs.berlios.de/cgi-bin/cvsweb.cgi/sourceagency/include/English-lang.inc?rev=1.1&content-type=text/x-cvsweb-markup&cvsroot=sourceagency"
>English-lang.inc</A> file (<i>Note:</i> it does not exactly match the
following description!!) (it also comes in SourceAgency's
tarball). If you look at it, you'll find lines
like this:

<PRE>
     case "Home": $tmp = ""; break;
</PRE>

<P>We will explain it briefly: after the <I>case</I> statement you will 
see the English text to translate written in
quotes (in our example, the English text is "Home"). Then you'll find a
sort of equation. The content of your translation from English into your 
language should
be placed in between these second quotes. For example, in the case you were 
making a translation into German, this would be the result for this line:
<?php
// TODO: explain how umlauts, i.e. international, characters can be added
?>
<PRE>
     case "Home": $tmp = "Heim"; break;
</PRE>

<P>Ok, now that you're an expert, you'll notice that "Home" is translated
into German as "Heim" ;-). The procedure just explained should be repeated 
with all the lines in this file. 

<P>Once you're finished, save it as <I>YourLanguage-lang.inc</I> and please 
send it to the <a href="authors.php3">authors</a>. We will include
it in the future releases so that everybody can benefit of your work.

<A NAME="contributors">
<P><H3>2. Contributors</H3>

<P>Here's a list of all the people that have contributed to the
translation of SourceAgency.

<CENTER>
<TABLE width=95%>
<TR><TH align="left">Language</TH><TH align="left">Translator</TH></TR>
<TR><TD>German</TD><TD>Lutz Henckel &lt;<A
HREF="mailto:lutz.henckel@fokus.gmd.de">lutz.henckel@fokus.gmd.de</A>&gt;
</TD></TR>
<TR><TD>Spanish</TD><TD>Gregorio Robles &lt;<A
HREF="mailto:grex@scouts-es.org">grex@scouts-es.org</A>&gt;</TD></TR>
<TR><TD>English (non-translation)</TD><TD>Gerrit Riessen &lt;<A
HREF="mailto:riessen@open-source-consultants.de"
>riessen@open-source-consultants.de</A>&gt;</TD></TR>

</TABLE></CENTER>


<P>&nbsp;

<!-- end content -->

<?php
require("footer.inc");
?>
