<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#                Lutz Henckel (lutz.henckel@fokus.fraunhofer.de),
#                Gregorio Robles (grex@scouts-es.org) and
#                Gerrit Riessen (riessen@open-source-consultants.de)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Translation class. Enables multilingual user interface
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: translation.inc,v 1.1 2003/11/21 12:56:02 helix Exp $
#
######################################################################

/**
 * Class translation
 *
 * This class enables to have a multilingual interface
 * The user interface is programmed in English and these strings
 * are translated into other languages for have convenient support
 *
 * @author Lutz Henckel <lutz.henckel@fokus.fhg.de>
 * @author Gregorio Robles <grex@scouts-es.org>
 * @access public
 */

class translation {
    /**
     * Language that is going to be displayed, by default English
     * @var  string
     */
    var $tolang;

    /**
     * Constructor
     *
     * @param   string  $tolang     Language the interface will be displayed in
     *
     * @access  public
     */
    function translation($lang = 'English') {
        $this->tolang = $lang;
    }

    /**
     * Given an English string, returns a translated string
     * (or returns itself if the selected language is English)
     *
     * In order to avoid including the file that contains the translated
     * strings every time the translate function is invoked, i've put
     * a translate_real functino in each *-lang.inc file (where * is the name
     * of the language).
     *
     * @param  string     $phrase      String (in English)
     * @return string                  Translated string
     * @access public
     * @see translate_real in *-lang.inc
     */   
    function translate($phrase) {
	$tmp = translate_real($phrase);
	return($tmp);
    }
}
?>
