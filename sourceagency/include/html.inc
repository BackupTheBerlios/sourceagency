<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# The PHP3 and PHPLib HTML library
# (you will find some examples at the end of this document)
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: html.inc,v 1.2 2001/11/07 18:48:52 riessen Exp $
#
######################################################################

// falta mirar si es local o global !!!
// funciona si no tiene addquery?

function html_link($address, $query, $text) {
	global $sess;

	if (!isset($query) || empty($query)) $output = "<a href=\"".$sess->url($address)."\">".$text."</a>";
	else $output = "<a href=\"".$sess->url($address).$sess->add_query($query)."\">".$text."</a>";

	return($output);
}

function html_anchor($name) {

	$output = "<a name=\"".$name."\"></a>\n";

	return($output);
}


function html_image($file, $border, $width, $height, $alternate) {

	$output = "<img src=\"images/".$file."\" border=\"".$border."\" width=\"".$width."\" height=\"".$height."\" alt=\"".$alternate."\">";

	return($output);
}

function html_form_action($file, $query, $type) {
	global $sess;

        if ($file == "PHP_SELF") $output = "<form action=\"".$sess->self_url().$sess->add_query($query)."\" method=\"".$type."\">";

	else $output = "<form action=\"".$sess->url($file).$sess->add_query($query)."\" method=\"".$type."\">";

	return($output);
}
	
function html_form_hidden($name, $value) {

	$output = "<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\">";

	return($output);
}

function html_select($name) {

	$output = "<select name=\"".$name."\">\n";

	return($output);
}

function html_select_option($value,$selected,$text) {

	$output = "<option ";
	if ($selected) $output .= "selected ";
	$output .= "value=\"".$value."\">".$text."\n";

	return($output);
}

function html_select_end() {

	$output = "</select>\n";

	return($output);
}

function html_input_text($name, $size, $maxlength, $value) {

	$output = "<input type=\"text\" name=\"".$name."\" size=\"".$size."\" maxlength=\"".$maxlength."\" value=\"".$value."\">";

	return($output);
}

function html_form_submit($value,$name) {

	$output = "<input type=\"submit\" value=\"".$value."\" name=\"".$name."\">";

	return($output);
}

function html_checkbox($name,$value,$checked) {

	$output = "<input type=\"checkbox\" name=\"".$name."\" value=\"".$value."\"";
	if ($checked) $output .= " checked ";
	$output .= ">";

	return($output);

}

function html_radio($name,$value,$checked) {

	$output = "<input type=\"radio\" name=\"".$name."\" value=\"".$value."\"";
	if ($checked) $output .= " checked ";
	$output .= ">";

	return($output);
}

function html_textarea($name,$columns,$rows,$wrap,$maxlength,$value) {

	$output = "<textarea cols=\"".$columns."\" rows=\"".$rows."\" name=\"".$name."\" wrap=\"".$wrap."\" maxlength=\"".$maxlength."\">".$value."</textarea>";

	return($output);
}

function html_form_end() {

	$output = "</form>";

	return($output);
}

function htmlp_link($address, $query, $text) {
	global $sess;

	if (!isset($query) || empty($query)) print "<a href=\"".$sess->url($address)."\">".$text."</a>\n";
	else print "<a href=\"".$sess->url($address).$sess->add_query($query)."\">".$text."</a>\n";

}

function htmlp_anchor($name) {

	print "<a name=\"".$name."\"></a>\n";

}

function htmlp_image($file, $border, $width, $height, $alternate) {

	print "<img src=\"images/".$file."\" border=\"".$border."\" width=\"".$width."\" height=\"".$height."\" alt=\"".$alternate."\">\n";

}

function htmlp_form_action($file, $query, $type) {
	global $sess;

        if ($file == "PHP_SELF") print "<form action=\"".$sess->self_url().$sess->add_query($query)."\" method=\"".$type."\">";

	else print "<form action=\"".$sess->url($file).$sess->add_query($query)."\" method=\"".$type."\">";

}
	
function htmlp_form_hidden($name, $value) {

	print "<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\">\n";

}

function htmlp_select($name) {

	print "<select name=\"".$name."\">\n";
}

function htmlp_select_option($value,$selected,$text) {

	print "<option ";
	if ($selected) print "selected ";
	print "value=\"".$value."\">".$text."\n";
}

function htmlp_select_end() {

	print "</select>\n";
}

function htmlp_input_text($name, $size, $maxlength, $value) {

	print "<input type=\"text\" name=\"".$name."\" size=\"".$size."\" maxlength=\"".$maxlength."\" value=\"".$value."\">\n";
}


function htmlp_textarea($name,$columns,$rows,$wrap,$maxlength,$value) {

	print "<textarea cols=\"".$columns."\" rows=\"".$rows."\" name=\"".$name."\" wrap=\"".$wrap."\" maxlength=\"".$maxlength."\">".$value."</textarea>\n";

}

function htmlp_form_submit($value,$name) {

	print "<input type=\"submit\" value=\"".$value."\" name=\"".$name."\">\n";
}

function htmlp_checkbox($name,$value,$checked) {

	print "<input type=\"checkbox\" name=\"".$name."\" value=\"".$value."\"";
	if ($checked) print " checked ";
	print ">";
}

function htmlp_radio($name,$value,$checked) {

	print "<input type=\"radio\" name=\"".$name."\" value=\"".$value."\"";
	if ($checked) print " checked ";
	print ">";
}

function htmlp_form_end() {

	print "</form>\n";
}

?>