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
# $Id: translations.pl,v 1.6 2002/06/12 11:16:11 grex Exp $
#
######################################################################

parse("inc");
chdir "../";
parse("php3");
unique(@complete);


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

sub nextLine {
    $_ = shift(@_);

    /\s?\.("|')(.*?)("|')\s?/;
    return $2;
}

sub ends {
    $_ = shift(@_);

    if (/"\)/ || /'\)/) {
        return 0;
    } else {
        return 1;
    }
}
