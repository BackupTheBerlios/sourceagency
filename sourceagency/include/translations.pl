#!/usr/bin/perl

while ($file = glob "*.inc") {
    open FILE, $file or die "Can't open the $file file: $!";
    $i = 0;
    while (<FILE>) {
	$i++;
        if (/$t->translate\(\s?"(.*?)"\s?\)/) {
	    print $1."\n";
	} elsif (/$t->translate\(\s?'(.*?)'\s?\)/) {
	    print $1."\n";
	} elsif (/$t->translate\(\$/) {
	    # print "Tranlation is a variable: line $i\n";
	} elsif (/$t->translate/) {
	    print "----> Uncompleted translation in file $file at line $i <----\n";
	}
    }
    close FILE;
}

chdir "../";

while ($file = glob "*.php3") {
    open FILE, $file or die "Can't open the $file file: $!";
    $i = 0;
    while (<FILE>) {
	$i++;
        if (/$t->translate\(\s?"(.*?)"\s?\)/) {
	    print $1."\n";
	} elsif (/$t->translate\(\s?'(.*?)'\s?\)/) {
	    print $1."\n";
	} elsif (/$t->translate\(\$/) {
	    # print "Tranlation is a variable: line $i\n";
	} elsif (/$t->translate/) {
	    print "----> Uncompleted translation in file $file at line $i <----\n";
	}
    }
    close FILE;
}
