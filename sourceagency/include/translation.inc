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
# TODO: description missing
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: translation.inc,v 1.3 2001/11/08 16:17:41 riessen Exp $
#
######################################################################

 class translation {
     var $tolang;

     function translation($lang = "English") {
          $this->tolang = $lang;
     }
   
     function translate($phrase) {
	  include($this->tolang."-lang.inc");
	  return($tmp);
     }
 }
?>
