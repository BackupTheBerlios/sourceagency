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
# Contains the English(sic) translation of the SourceAgency portal.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: English-lang.inc,v 1.9 2001/11/15 18:07:44 riessen Exp $
#
######################################################################

switch($phrase) {
    
    /** these are various page titles **/
    case "comments_edit": $tmp = "Edit Comments"; break;
    case "comments_mod": $tmp = "Modify Comments"; break;
    case "consultants": $tmp = "Consultants"; break;
    case "summary": $tmp = "Summary"; break;
    case "configure_edit": $tmp = "Edit Configuration"; break;
    case "content suggestions": $tmp = "Content Suggestions"; break;

    default:   $tmp = "$phrase";  break;
}
?>
