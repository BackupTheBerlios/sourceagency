#!/usr/bin/perl

while ($file = glob "*.inc") {
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
	    push @uncomplete, "File $file at line $i";
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
	    push @complete, $1;
	} elsif (/$t->translate\(\s?'(.*?)'\s?\)/) {
	    push @complete, $1;
	} elsif (/$t->translate\(\$/) {
	    # print "Tranlation is a variable: line $i\n";
	} elsif (/$t->translate/) {
	    push @uncompleted, "File $file at line $i";
	}
    }
    close FILE;
}

@complete = sort @complete;
$last = "";
foreach $line (@complete) {
    if ($line eq $last) {
	# print "Repeated string ".$line."\n";
    } else {
        print $line."\n";
    }
    $last = $line;
}

print "\n\nUncomplete translations (have to be added manually so far):\n\n";
foreach $line (@uncomplete) {
    print $line."\n";
}
