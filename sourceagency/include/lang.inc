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
# BerliOS SourceWell: http://sourcewell.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# TODO: description missing
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: lang.inc,v 1.9 2001/12/13 15:40:02 riessen Exp $
#
######################################################################

global $l;
$l->log( "Language: " . (isset( $lang ) ? $lang : "No Language set"));
if ($sess->is_registered("la")) {
   if (isset($lang)) {
      $la = $lang;
   }
} else {
   if (isset($lang)) {
      $la = $lang;
   } else {
      $la = "English";
   }
   $sess->register("la");
}
switch($la) {
   case "English": $locale = "en_EN"; break;
   case "German": $locale = "de_DE"; break;
   case "Spanish": $locale = "es_ES"; break;
   default: $locale = "en_EN"; break;
}
setlocale (LC_TIME, $locale);
?>