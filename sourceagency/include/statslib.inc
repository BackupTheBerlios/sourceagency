<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Lutz Henckel (lutz.henckel@fokus.gmd.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# Statistics Library file.
# You'll find in SourceAgency's documentation a good explanation of the
# functions that are coded in this file.
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: statslib.inc,v 1.2 2001/11/08 16:17:41 riessen Exp $
#
######################################################################

## function statslib_top($query,$var,$message,$position)
##
## Displays a top
##
## $query has the database query to be listed
## $var gives what should be listed
## $message has the title of the box
## $position gives the starting position for the top

function statslib_top($query,$var,$message,$position) {
  global $sess, $db, $bx, $t;
  $max = 1; 		// max is a number which gives the top 1!!
  $length = 280; 	// length is the maximal lenght of the image

  $bx->box_begin();
  $bx->box_title($t->translate($message));
	
  $bx->box_body_begin();
  $db->query($query);
  echo "<table width=100%>";
  while($db->next_record()) {
   if ( ($db->f("$var") > $max) ) {
      $max = $db->f("$var");
    } 
    $position +=1;
    $width = $db->f("$var") * $length / $max;
    echo "<tr><td width=5% align=right>&nbsp;".$position.".</td>\n";
    echo "<td width=35%>&nbsp;".$db->f("name");

		// We show (S) for the stable branch
		// and (D) for the development branch
    $db_local = new DB_SourceAgency;
    $db_name = $db->f("name");
    $db_local->query("SELECT appid,type FROM software WHERE name='$db_name'");
    while ($db_local->next_record()) {
      $db_local_appid = $db_local->f("appid");
      $db_local_type = $db_local->f("type");
      if ($db_local_type == "S") {
        echo " (<a href=\"".$sess->url("appbyid.php3").$sess->add_query(array("id" => $db_local->f("appid")))."\">S</A>)";
      }
      if ($db_local_type == "D") {
        echo " (<a href=\"".$sess->url("appbyid.php3").$sess->add_query(array("id" => $db_local->f("appid")))."\">D</A>)";
      }
    }
    echo "</td>\n";

    echo "<td width=1%>&nbsp;</td>\n";
    echo "<td width=59%><img src=\"images/leftbar.gif\" height=14 width=7 Alt=\"".$db->f("name")."\"><img src=\"images/mainbar.gif\" Alt=\"".$db->f("name")."\" height=14 width=\"$width\"><img src=\"images/rightbar.gif\" height=14 width=7 Alt=\"".$db->f("name")."\">&nbsp; ".$db->f("$var")."</td></tr>\n";
    echo "</td></tr>\n";
  }
  echo "</table>\n";
  $bx->box_body_end();
  $bx->box_end();
}


## stats_title($message):
##
## Displays the header of the table with the title given by $message

function stats_title($message) {
  global $t;
  echo "<center>\n";
  echo "<table width=600 border=0 cellspacing=0 cellpadding=0 bgcolor=\"".$GLOBALS["th_box_frame_color"]."\" align=center>\n";
  echo "<tr><td>\n";
  echo "<table width=100% border=0 cellspacing=1 cellpadding=3>\n";
  echo "<tr bgcolor=\"".$GLOBALS["th_box_title_bgcolor"]."\">\n";
  echo "<td><b>";
  echo $t->translate($message);
  echo "</b></td>\n";
  echo "</tr><tr bgcolor=\"".$GLOBALS["th_box_body_bgcolor"]."\"><td>\n";
  echo "<table border=0 width=100% cellspacing=0>\n";
}

## stats_subtitle($message):
##
## Displays a subtitle

function stats_subtitle($message) {
  echo "<tr><td bgcolor=\"".$GLOBALS["th_box_title_bgcolor"]."\"><B>".$message."</B></td>\n";
  echo "<td bgcolor=\"".$GLOBALS["th_box_title_bgcolor"]."\">&nbsp;</td>";
  echo "<td bgcolor=\"".$GLOBALS["th_box_title_bgcolor"]."\">&nbsp;</td>";
  echo "<td bgcolor=\"".$GLOBALS["th_box_title_bgcolor"]."\">&nbsp;</td>";
  echo "<td bgcolor=\"".$GLOBALS["th_box_title_bgcolor"]."\">&nbsp;</td></tr>";
}


## stats_display($what,$count,$url):
##
## Displays the statistic graphically in one column
## $what is the string which gives the name
## $count gives the number of times it appears
## $url gives the corresponding url
## if $url = 0 then no url is displayed

function stats_display($what,$count,$url) {
  $max= 400;		// max is a number to ensure the maximum lenght of the graph
  $length = 575; 	// length is the maximal lenght of the image

  echo "<tr><td width=47%>&nbsp;<a href=\"".$url."\">".$what."</A>:</td>\n";
  echo "<td width=1%>&nbsp;</td>\n";
  echo "<td width=52%><img src=\"images/leftbar.gif\" height=14 width=7 Alt=\"${$type}[$i]\"><img src=\"images/mainbar.gif\" Alt=\"${$type}[$i]\" height=14 width=", $count * $length / $max, "><img src=\"images/rightbar.gif\" height=14 width=7 Alt=\"${$type}[$i]\">&nbsp; $count</td></tr>\n";
  echo "</FONT></td></tr>";
}

## stats_display_alt($what,$count,$url,$total):
##
## Displays the statistic graphically in one column
## $what is the string which gives the name
## $count gives the number of times it appears
## $url gives the corresponding url
## $total gives the total sum of all the studied items

function stats_display_alt($what,$count,$url,$total) {
  $epsilon = 0.0001;    // To avoid having division by zero
  if (empty($total) || !isset($total) || $total == 0) {$total=1; $count=0;}
  if (empty($count) || !isset($count)) {$count=0;}

			// To avoid that by 100% the graph is too big
  if (($count/($total + $epsilon)) >= 0.95) {
    $total_normalize = 1.1 * ($total+ $epsilon);
  } else {
    $total_normalize = $total + $epsilon;
  }

  echo "<tr><td width=246>&nbsp;";
    if (strcmp($url,"0")) echo "<a href=\"".$url."\">";
       echo $what;
	 if (strcmp($url,"0")) echo "</A>:</td>\n";
  echo "<td width=6>&nbsp;</td>\n";
  echo "</td width=42><td>&nbsp; $count</td>\n";

  echo "<td><table width=264><TR><td><img src=\"images/leftbar.gif\" height=14 width=7 Alt=\"$what\"><img src=\"images/mainbar.gif\" Alt=\"$what\" height=14 width=", $count * 240 / $total_normalize, "><img src=\"images/rightbar.gif\" height=14 width=7 Alt=\"$what\"></td></tr></table>\n";

  echo "</td><td width=42>&nbsp;".(Ceil(($count*100 /($total+$epsilon))*10)/10)."%</td></tr>\n";
}

## stats_end():
##
## Finishes the table

function stats_end(){
  echo "</table>\n";
  echo "</TD></TR></TABLE>\n";
  echo "</TD></TR></TABLE></CENTER>\n";
  echo "<BR>\n";
}


######################################################################
# Domaines
#
# Please, do not touch this!
# It is used in the statistics
#
######################################################################

$domain_country[1][0] = "com";	$domain_country[1][1] = "Commercial (.com)";
$domain_country[2][0] = "org";	$domain_country[2][1] = "Organization (.org)";
$domain_country[3][0] = "net";	$domain_country[3][1] = "Network (.net)";
$domain_country[4][0] = "de";	$domain_country[4][1] = "Germany (.de)";
$domain_country[5][0] = "fr";	$domain_country[5][1] = "France (.fr)";
$domain_country[6][0] = "edu";	$domain_country[6][1] = "Educational (.edu)";
$domain_country[7][0] = "uk";	$domain_country[7][1] = "United Kingdom (.uk)";
$domain_country[8][0] = "it";	$domain_country[8][1] = "Italy (.it)";
$domain_country[9][0] = "se";	$domain_country[9][1] = "Sweden (.se)";
$domain_country[10][0] = "nl";	$domain_country[10][1] = "The Netherlands (.nl)";
$domain_country[11][0] = "au";	$domain_country[11][1] = "Australia (.au)";
$domain_country[12][0] = "es";	$domain_country[12][1] = "Spain (.es)";
$domain_country[13][0] = "be";	$domain_country[13][1] = "Belgium (.be)";
$domain_country[14][0] = "nu";	$domain_country[14][1] = "Niue (.nu)";
$domain_country[15][0] = "ca";	$domain_country[15][1] = "Canada (.ca)";
$domain_country[16][0] = "jp";	$domain_country[16][1] = "Japan (.jp)";
$domain_country[17][0] = "cz";	$domain_country[17][1] = "Czech Republik (.cz)";
$domain_country[18][0] = "ru";	$domain_country[18][1] = "Russian Federation (.ru)";
$domain_country[19][0] = "no";	$domain_country[19][1] = "Norway (.no)";
$domain_country[20][0] = "at";	$domain_country[20][1] = "Austria (.at)";
$domain_country[21][0] = "xx";	$domain_country[21][1] = "";
$domain_country[22][0] = "xx";	$domain_country[22][1] = "";
$domain_country[23][0] = "xx";	$domain_country[23][1] = "";
$domain_country[24][0] = "xx";	$domain_country[24][1] = "";

// In alfabetical order
$domain_country[25][0] = "ac";	$domain_country[25][1] = "Ascension Island (.ac)";
$domain_country[26][0] = "ad";	$domain_country[26][1] = "Andorra (.ad)";
$domain_country[27][0] = "ae";	$domain_country[27][1] = "United Arab Emirates (.ae)";
$domain_country[28][0] = "af";	$domain_country[28][1] = "Afghanistan (.af)";
$domain_country[29][0] = "ag";	$domain_country[29][1] = "Antigua and Barbuda (.ag)";
$domain_country[30][0] = "ai";	$domain_country[30][1] = "Anguilla (.ai)";
$domain_country[31][0] = "al";	$domain_country[31][1] = "Albania (.al)";
$domain_country[32][0] = "am";	$domain_country[32][1] = "Armenia (.am)";
$domain_country[33][0] = "an";	$domain_country[33][1] = "Netherlands Antilles (.an)";
$domain_country[34][0] = "ao";	$domain_country[34][1] = "Angola (.ao)";
$domain_country[35][0] = "aq";	$domain_country[35][1] = "Antartica (.aq)";
$domain_country[36][0] = "ar";	$domain_country[36][1] = "Argentina (.ar)";
$domain_country[37][0] = "as";	$domain_country[37][1] = "American Samoa (.as)";
$domain_country[39][0] = "aw";	$domain_country[39][1] = "Aruba (.aw)";
$domain_country[40][0] = "az";	$domain_country[40][1] = "Azerbaijan (.az)";
$domain_country[41][0] = "ba";	$domain_country[41][1] = "Bosnia and Herzegovina (.ba)";
$domain_country[42][0] = "bb";	$domain_country[42][1] = "Barbados (.bb)";
$domain_country[43][0] = "bd";	$domain_country[43][1] = "Bangladesh (.bd)";
$domain_country[44][0] = "bf";	$domain_country[44][1] = "Burkina Faso";
$domain_country[45][0] = "bg";	$domain_country[45][1] = "Bulgaria";
$domain_country[46][0] = "bh";	$domain_country[46][1] = "Bahrain";
$domain_country[47][0] = "bi";	$domain_country[47][1] = "Burundi";
$domain_country[48][0] = "bj";	$domain_country[48][1] = "Benin";
$domain_country[49][0] = "bm";	$domain_country[49][1] = "Bermuda";
$domain_country[50][0] = "bn";	$domain_country[50][1] = "Brunei Darussalam";
$domain_country[51][0] = "bo";	$domain_country[51][1] = "Bolivia";
$domain_country[52][0] = "br";	$domain_country[52][1] = "Brazil (.br)";
$domain_country[53][0] = "bs";	$domain_country[53][1] = "Bahamas";
$domain_country[54][0] = "bt";	$domain_country[54][1] = "Bhutan";
$domain_country[55][0] = "bv";	$domain_country[55][1] = "Bouvet Island";
$domain_country[56][0] = "bw";	$domain_country[56][1] = "Botswana";
$domain_country[57][0] = "by";	$domain_country[57][1] = "Belarus";
$domain_country[58][0] = "bz";	$domain_country[58][1] = "Belize";
$domain_country[59][0] = "cc";	$domain_country[59][1] = "Cocos (Keeling) Islands";
$domain_country[60][0] = "cd";	$domain_country[60][1] = "Congo, Democratic People's Republic";
$domain_country[61][0] = "cf";	$domain_country[61][1] = "Central African Republic";
$domain_country[62][0] = "cg";	$domain_country[62][1] = "Congo, Republic of";
$domain_country[63][0] = "ch";	$domain_country[63][1] = "Switzerland";
$domain_country[64][0] = "ci";	$domain_country[64][1] = "Cote d''Ivoire";
$domain_country[65][0] = "ck";	$domain_country[65][1] = "Cook Islands";
$domain_country[66][0] = "cl";	$domain_country[66][1] = "Chile";
$domain_country[67][0] = "cm";	$domain_country[67][1] = "Cameroon";
$domain_country[68][0] = "cn";	$domain_country[68][1] = "China";
$domain_country[69][0] = "co";	$domain_country[69][1] = "Colombia";
$domain_country[70][0] = "cr";	$domain_country[70][1] = "Costa Rica";
$domain_country[71][0] = "cu";	$domain_country[71][1] = "Cuba";
$domain_country[72][0] = "cv";	$domain_country[72][1] = "Cap Verde";
$domain_country[73][0] = "cx";	$domain_country[73][1] = "Christmas Island";
$domain_country[74][0] = "cy";	$domain_country[74][1] = "Cyprus";
$domain_country[75][0] = "dj";	$domain_country[75][1] = "Djibouti";
$domain_country[76][0] = "dk";	$domain_country[76][1] = "Denmark";
$domain_country[77][0] = "dm";	$domain_country[77][1] = "Dominica";
$domain_country[78][0] = "do";	$domain_country[78][1] = "Dominican Republic";
$domain_country[79][0] = "dz";	$domain_country[79][1] = "Algeria";
$domain_country[80][0] = "ec";	$domain_country[80][1] = "Ecuador";
$domain_country[81][0] = "ee";	$domain_country[81][1] = "Estonia";
$domain_country[82][0] = "eg";	$domain_country[82][1] = "Egypt";
$domain_country[83][0] = "eh";	$domain_country[83][1] = "Western Sahara";
$domain_country[84][0] = "er";	$domain_country[84][1] = "Eritrea";
$domain_country[85][0] = "et";	$domain_country[85][1] = "Ethiopia";
$domain_country[86][0] = "fi";	$domain_country[86][1] = "Finland";
$domain_country[87][0] = "fj";	$domain_country[87][1] = "Fiji";
$domain_country[88][0] = "fk";	$domain_country[88][1] = "Falkland Islands (Malvina)";
$domain_country[89][0] = "fm";	$domain_country[89][1] = "Micronesia, Federal State of";
$domain_country[90][0] = "fo";	$domain_country[90][1] = "Faroe Islands";
$domain_country[91][0] = "ga";	$domain_country[91][1] = "Gabon";
$domain_country[92][0] = "gd";	$domain_country[92][1] = "Grenada";
$domain_country[93][0] = "ge";	$domain_country[93][1] = "Georgia";
$domain_country[94][0] = "gf";	$domain_country[94][1] = "French Guiana";
$domain_country[95][0] = "gg";	$domain_country[95][1] = "Guernse";
$domain_country[96][0] = "gh";	$domain_country[96][1] = "Ghana";
$domain_country[97][0] = "gi";	$domain_country[97][1] = "Gibraltar";
$domain_country[98][0] = "gl";	$domain_country[98][1] = "Greenland";
$domain_country[99][0] = "gm";	$domain_country[99][1] = "Gambia";
$domain_country[100][0] = "gn";	$domain_country[100][1] = "Guinea";
$domain_country[101][0] = "gp";	$domain_country[101][1] = "Guadeloupe";
$domain_country[102][0] = "gq";	$domain_country[102][1] = "Equatorial Guinea";
$domain_country[103][0] = "gr";	$domain_country[103][1] = "Greece (.gr)";
$domain_country[104][0] = "gs";	$domain_country[104][1] = "South Georgia and the South Sandwich Islands";
$domain_country[105][0] = "gt";	$domain_country[105][1] = "Guatemala";
$domain_country[106][0] = "gu";	$domain_country[106][1] = "Guam";
$domain_country[107][0] = "gw";	$domain_country[107][1] = "Guinea-Bissau";
$domain_country[108][0] = "gy";	$domain_country[108][1] = "Guyana";
$domain_country[109][0] = "hk";	$domain_country[109][1] = "Hong Kong";
$domain_country[110][0] = "hm";	$domain_country[110][1] = "Heard and McDonald Island";
$domain_country[111][0] = "hn";	$domain_country[111][1] = "Honduras";
$domain_country[112][0] = "hr";	$domain_country[112][1] = "Croatia/Hrvatska";
$domain_country[113][0] = "ht";	$domain_country[113][1] = "Haiti";
$domain_country[114][0] = "hu";	$domain_country[114][1] = "Hungary";
$domain_country[115][0] = "id";	$domain_country[115][1] = "Indonesia";
$domain_country[116][0] = "ie";	$domain_country[116][1] = "Ireland";
$domain_country[117][0] = "il";	$domain_country[117][1] = "Israel (.il)";
$domain_country[118][0] = "im";	$domain_country[118][1] = "Isle of Man";
$domain_country[119][0] = "in";	$domain_country[119][1] = "India";
$domain_country[120][0] = "io";	$domain_country[120][1] = "British Indian Ocean Territory";
$domain_country[121][0] = "iq";	$domain_country[121][1] = "Iraq";
$domain_country[122][0] = "ir";	$domain_country[122][1] = "Iran (Islamic Republic of)";
$domain_country[123][0] = "is";	$domain_country[123][1] = "Iceland";
$domain_country[124][0] = "je";	$domain_country[124][1] = "Jersey";
$domain_country[125][0] = "jm";	$domain_country[125][1] = "Jamaica";
$domain_country[126][0] = "jo";	$domain_country[126][1] = "Jordan";
$domain_country[127][0] = "ke";	$domain_country[127][1] = "Kenya";
$domain_country[128][0] = "kg";	$domain_country[128][1] = "Kyrgyzstan";
$domain_country[129][0] = "kh";	$domain_country[129][1] = "Cambodia";
$domain_country[130][0] = "ki";	$domain_country[130][1] = "Kiribati";
$domain_country[131][0] = "km";	$domain_country[131][1] = "Comoros";
$domain_country[132][0] = "kn";	$domain_country[132][1] = "Saint Kitts and Nevis";
$domain_country[133][0] = "kp";	$domain_country[133][1] = "Korea, Democratic People''s Republic";
$domain_country[134][0] = "kr";	$domain_country[134][1] = "Korea, Republic of";
$domain_country[135][0] = "kw";	$domain_country[135][1] = "Kuwait";
$domain_country[136][0] = "ky";	$domain_country[136][1] = "Cayman Islands";
$domain_country[137][0] = "kz";	$domain_country[137][1] = "Kazakhstan";
$domain_country[138][0] = "la";	$domain_country[138][1] = "Lao, People''s Democratic Republic";
$domain_country[139][0] = "lb";	$domain_country[139][1] = "Lebanon";
$domain_country[140][0] = "lc";	$domain_country[140][1] = "Saint Lucia";
$domain_country[141][0] = "li";	$domain_country[141][1] = "Liechtenstein";
$domain_country[142][0] = "lk";	$domain_country[142][1] = "Sri Lanka";
$domain_country[143][0] = "lr";	$domain_country[143][1] = "Liberia";
$domain_country[144][0] = "ls";	$domain_country[144][1] = "Lesotho";
$domain_country[145][0] = "lt";	$domain_country[145][1] = "Lithuania";
$domain_country[146][0] = "lu";	$domain_country[146][1] = "Luxembourg";
$domain_country[147][0] = "lv";	$domain_country[147][1] = "Latvia";
$domain_country[148][0] = "ly";	$domain_country[148][1] = "Libyan Arab Jamahiriya";
$domain_country[149][0] = "ma";	$domain_country[149][1] = "Morocco";
$domain_country[150][0] = "mc";	$domain_country[150][1] = "Monaco";
$domain_country[151][0] = "md";	$domain_country[151][1] = "Moldova, Republic of";
$domain_country[152][0] = "mg";	$domain_country[152][1] = "Madagascar";
$domain_country[153][0] = "mh";	$domain_country[153][1] = "Marshall Islands";
$domain_country[154][0] = "mk";	$domain_country[154][1] = "Macedonia, Former Yugoslav Republic";
$domain_country[155][0] = "ml";	$domain_country[155][1] = "Mali";
$domain_country[156][0] = "mm";	$domain_country[156][1] = "Myanmar";
$domain_country[157][0] = "mn";	$domain_country[157][1] = "Mongolia";
$domain_country[158][0] = "mo";	$domain_country[158][1] = "Macau";
$domain_country[159][0] = "mp";	$domain_country[159][1] = "Northern Mariana Islands";
$domain_country[160][0] = "mq";	$domain_country[160][1] = "Martinique";
$domain_country[161][0] = "mr";	$domain_country[161][1] = "Mauritania";
$domain_country[162][0] = "ms";	$domain_country[162][1] = "Montserrat";
$domain_country[163][0] = "mt";	$domain_country[163][1] = "Malta";
$domain_country[164][0] = "mu";	$domain_country[164][1] = "Mauritius";
$domain_country[165][0] = "mv";	$domain_country[165][1] = "Maldives";
$domain_country[166][0] = "mw";	$domain_country[166][1] = "Malawi";
$domain_country[167][0] = "mx";	$domain_country[167][1] = "Mexico";
$domain_country[168][0] = "my";	$domain_country[168][1] = "Malaysia";
$domain_country[169][0] = "mz";	$domain_country[169][1] = "Mozambique";
$domain_country[170][0] = "na";	$domain_country[170][1] = "Namibia";
$domain_country[171][0] = "nc";	$domain_country[171][1] = "New Caledonia";
$domain_country[172][0] = "ne";	$domain_country[172][1] = "Niger";
$domain_country[173][0] = "nf";	$domain_country[173][1] = "Norfolk Island";
$domain_country[174][0] = "ng";	$domain_country[174][1] = "Nigeria";
$domain_country[175][0] = "ni";	$domain_country[175][1] = "Nicaragua";
$domain_country[176][0] = "np";	$domain_country[176][1] = "Nepal";
$domain_country[177][0] = "nr";	$domain_country[177][1] = "Nauru";
$domain_country[178][0] = "nz";	$domain_country[178][1] = "New Zealand";
$domain_country[179][0] = "om";	$domain_country[179][1] = "Oman";
$domain_country[180][0] = "pa";	$domain_country[180][1] = "Panama";
$domain_country[181][0] = "pe";	$domain_country[181][1] = "Peru";
$domain_country[182][0] = "pf";	$domain_country[182][1] = "French Polynesia";
$domain_country[183][0] = "pg";	$domain_country[183][1] = "Papua New Guinea";
$domain_country[184][0] = "ph";	$domain_country[184][1] = "Philippines";
$domain_country[185][0] = "pk";	$domain_country[185][1] = "Pakistan";
$domain_country[186][0] = "pl";	$domain_country[186][1] = "Poland";
$domain_country[187][0] = "pm";	$domain_country[187][1] = "St. Pierre and Miquelon";
$domain_country[188][0] = "pn";	$domain_country[188][1] = "Pitcairn Island";
$domain_country[189][0] = "pr";	$domain_country[189][1] = "Puerto Rico";
$domain_country[190][0] = "pt";	$domain_country[190][1] = "Portugal";
$domain_country[191][0] = "pw";	$domain_country[191][1] = "Palau";
$domain_country[192][0] = "py";	$domain_country[192][1] = "Paraguay";
$domain_country[193][0] = "qa";	$domain_country[193][1] = "Qatar";
$domain_country[194][0] = "re";	$domain_country[194][1] = "Reunion Island";
$domain_country[195][0] = "ro";	$domain_country[195][1] = "Romania";
$domain_country[196][0] = "rw";	$domain_country[196][1] = "Rwanda";
$domain_country[197][0] = "sa";	$domain_country[197][1] = "Saudi Arabia";
$domain_country[198][0] = "sb";	$domain_country[198][1] = "Solomon Islands";
$domain_country[199][0] = "sc";	$domain_country[199][1] = "Seychelles";
$domain_country[200][0] = "sd";	$domain_country[200][1] = "Sudan";
$domain_country[201][0] = "sg";	$domain_country[201][1] = "Singapore";
$domain_country[202][0] = "sh";	$domain_country[202][1] = "St. Helena";
$domain_country[203][0] = "si";	$domain_country[203][1] = "Slovenia";
$domain_country[204][0] = "sj";	$domain_country[204][1] = "Svalbard and Jan Mayen Islands";
$domain_country[205][0] = "sk";	$domain_country[205][1] = "Slovak Republic";
$domain_country[206][0] = "sl";	$domain_country[206][1] = "Sierra Leone";
$domain_country[207][0] = "sm";	$domain_country[207][1] = "San Marino";
$domain_country[208][0] = "sn";	$domain_country[208][1] = "Senegal";
$domain_country[209][0] = "so";	$domain_country[209][1] = "Somalia";
$domain_country[210][0] = "sr";	$domain_country[210][1] = "Suriname";
$domain_country[211][0] = "st";	$domain_country[211][1] = "Sao Tome and Principe";
$domain_country[212][0] = "sv";	$domain_country[212][1] = "El Salvador";
$domain_country[213][0] = "sy";	$domain_country[213][1] = "Syrian Arab Republic";
$domain_country[214][0] = "sz";	$domain_country[214][1] = "Swaziland";
$domain_country[215][0] = "tc";	$domain_country[215][1] = "Turks and Ciacos Islands";
$domain_country[216][0] = "td";	$domain_country[216][1] = "Chad";
$domain_country[217][0] = "tf";	$domain_country[217][1] = "French Southern Territories";
$domain_country[218][0] = "tg";	$domain_country[218][1] = "Togo";
$domain_country[219][0] = "th";	$domain_country[219][1] = "Thailand";
$domain_country[220][0] = "tj";	$domain_country[220][1] = "Tajikistan";
$domain_country[221][0] = "tk";	$domain_country[221][1] = "Tokelau";
$domain_country[222][0] = "tm";	$domain_country[222][1] = "Turkmenistan";
$domain_country[223][0] = "tn";	$domain_country[223][1] = "Tunisia";
$domain_country[224][0] = "to";	$domain_country[224][1] = "Tonga";
$domain_country[225][0] = "tp";	$domain_country[225][1] = "East Timor";
$domain_country[226][0] = "tr";	$domain_country[226][1] = "Turkey";
$domain_country[227][0] = "tt";	$domain_country[227][1] = "Trinidad and Tobago";
$domain_country[228][0] = "tv";	$domain_country[228][1] = "Tuvalu";
$domain_country[229][0] = "tw";	$domain_country[229][1] = "Taiwan";
$domain_country[230][0] = "tz";	$domain_country[230][1] = "Tanzania";
$domain_country[231][0] = "ua";	$domain_country[231][1] = "Ukraine";
$domain_country[232][0] = "ug";	$domain_country[232][1] = "Uganda";
$domain_country[233][0] = "gb";	$domain_country[233][1] = "United Kingdom";
$domain_country[234][0] = "um";	$domain_country[234][1] = "US Minor Outlying Islands";
$domain_country[235][0] = "us";	$domain_country[235][1] = "United States";
$domain_country[236][0] = "uy";	$domain_country[236][1] = "Uruguay";
$domain_country[237][0] = "uz";	$domain_country[237][1] = "Uzbekistan";
$domain_country[238][0] = "va";	$domain_country[238][1] = "Holy See (City Vatican State)";
$domain_country[239][0] = "vc";	$domain_country[239][1] = "Saint Vincent and the Grenadines";
$domain_country[240][0] = "ve";	$domain_country[240][1] = "Venezuela";
$domain_country[241][0] = "vg";	$domain_country[241][1] = "Virgin Islands (British)";
$domain_country[242][0] = "vi";	$domain_country[242][1] = "Virgin Islands (USA)";
$domain_country[243][0] = "vn";	$domain_country[243][1] = "Vietnam";
$domain_country[244][0] = "vu";	$domain_country[244][1] = "Vanuatu";
$domain_country[245][0] = "wf";	$domain_country[245][1] = "Wallis and Futuna Islands";
$domain_country[246][0] = "ws";	$domain_country[246][1] = "Western Samoa";
$domain_country[247][0] = "ye";	$domain_country[247][1] = "Yemen";
$domain_country[248][0] = "yt";	$domain_country[248][1] = "Mayotte";
$domain_country[249][0] = "yu";	$domain_country[249][1] = "Yugoslavia";
$domain_country[250][0] = "za";	$domain_country[250][1] = "South Africa";
$domain_country[251][0] = "zm";	$domain_country[251][1] = "Zambia";
$domain_country[252][0] = "zr";	$domain_country[252][1] = "Zaire";
$domain_country[253][0] = "zw";	$domain_country[253][1] = "Zimbabwe";
$domain_country[254][0] = "int";	$domain_country[254][1] = "International";
$domain_country[255][0] = "gov";	$domain_country[255][1] = "U.S. Governement";
$domain_country[256][0] = "mil";	$domain_country[256][1] = "U.S. Miltary";
$domain_country[257][0] = "su";	$domain_country[257][1] = "Ex U.S.R.R.";
$domain_country[258][0] = "arpa";	$domain_country[258][1] = "ArpaNet";
?>