#!/usr/bin/perl
# vim: set expandtab tabstop=4 shiftwidth=4: 
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This script extracts strings to be translated
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: translations.pl,v 1.1 2003/11/21 12:56:02 helix Exp $
#
######################################################################

parse("inc");
chdir "../";
parse("php3");
unique(@complete);

# Subroutines

# Subroutine parse
# Looks in the files with the given extension
# for lines that contain the translation function ($t->translate())

sub parse {
    $extension = shift(@_);

    while ($file = glob "*.".$extension) {
        open FILE, $file or die "Can't open the $file file: $!";
        $i = 0;
        while (<FILE>) {
	    $i++;
            if (/$t->translate\(\s?"(.*?)"\s?\)/) {
	        push @complete, $1;
	    } elsif (/$t->translate\(\s?'(.*?)'\s?\)/) {
	        push @complete, $1;
	    } elsif (/$t->translate\(\$/) {
	        # print "Tranlation is a variable: line $i\n";
	    } elsif (/$t->translate/) {

 # Get the first line
	        /$t->translate\(\s?("|')(.*?)("|')\s?/;
	        $string = $2;

		while (ends($_)) {
		    $_ = <FILE>;
                    $string .= nextLine($_);
		}
		push @complete, $string;
	    }
        }
        close FILE;
    }
    return @complete;
}

# Subroutine unique
# Gets an array and returns the sorted array
# with elements appearing only once

sub unique {
    @array = sort @_;

    $last = "";
    foreach $element (@array) {
        if ($element eq $last) {
	    # print "Repeated string ".$element."\n";
        } else {
            print $element."\n";
        }
        $last = $element;
    }
}

# Subroutine nextLine
# Looks for the string to be translated in the current line
# passed as argument

sub nextLine {
    $_ = shift(@_);

    /\s?\.("|')(.*?)("|')\s?/;
    return $2;
}

# Subrouting ends
# Gets a line and sees if the translation functions
# finishes in this line (returning 0)
# else it returns 1, which means, another line
# has to be retrieved and parsed

sub ends {
    $_ = shift(@_);

    if (/"\)/ || /'\)/) {
        return 0;
    } else {
        return 1;
    }
}
