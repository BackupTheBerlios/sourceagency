<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
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
# The PHP3 and PHPLib HTML library
# (you will find some examples at the end of this document)
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: html.inc,v 1.9 2002/04/18 10:58:11 grex Exp $
#
######################################################################

/**
 * This library allows to separate the html from the php coding
 * it is a 1:1 relationship between HTML tags and a PHP abstraction function
 */

/**
 * HTML link
 *
 * @param   string    $address       URL (local form and with extension)
 * @param   array     $query         PHPLib session object query
 * @param   string    $text          Text displayed as link
 *                                   (should already be translated!)
 *
 * @global  object    $sess          PHPLib session object
 *
 * @access  public
 */
function html_link($address, $query, $text)
{
    global $sess;
    
    if (!isset($query) || empty($query)) {
        $output = "<a href=\"".$sess->url($address)."\">".$text."</a>\n";
    } else {
        $output = "<a href=\"".$sess->url($address)
             .$sess->add_query($query)."\">".$text."</a>\n";
    }
    
    return $output;
}
function htmlp_link($address, $query, $text)
{
    print html_link( $address, $query, $text );
}

/**
 * HTML anchor tag
 *
 * @param   string    $name          Anchor name
 *
 * @access  public
 */ 
function html_anchor($name)
{
    return ( "<a name=\"".$name."\"></a>\n" );
}
function htmlp_anchor($name)
{
    print html_anchor( $name );
}

/**
 * HTML image
 *
 * @param   string    $file       URL (locally and with extension) of the image 
 * @param   int       $border     size of the image border
 * @param   int       $width      image width
 * @param   int       $height     image height
 * @param   string    $alternate  alternate text for the image
 *
 * @access  public
 */ 
function html_image($file, $border, $width, $height, $alternate)
{
    return ( "<img src=\"images/".$file."\" border=\""
             .$border."\" width=\"".$width."\" height=\""
             .$height."\" alt=\"".$alternate."\">" );
}
function htmlp_image($file, $border, $width, $height, $alternate)
{
    print html_image( $file, $border, $width, $height, $alternate );
}

/**
 * HTML Form action tag
 *
 * @param   string    $file       URL (locally and with extension) of the image 
 * @param   array     $query      PHPLib session query
 * @param   string    $type       POST or GET
 *
 * @global  object    $sess       PHPLib session object
 *
 * @access  public
 */ 
function html_form_action($file, $query='', $type='POST')
{
    global $sess;
    
    if ($file == "PHP_SELF") {
        $output = ( "<form action=\"".$sess->self_url()
                    .$sess->add_query($query)."\" method=\"".$type."\">");

    } else {
        $output = ( "<form action=\"".$sess->url($file)
                    .$sess->add_query($query)."\" method=\"".$type."\">" );
    }
    return($output);
}
function htmlp_form_action($file, $query, $type)
{
    print html_form_action( $file, $query, $type );
}

/**
 * HTML Form hidden field
 *
 * WISH: might be necessary to escape the value (and name) so that
 * WISH: they do not contain any quotes
 *
 * @param   string    $name        Name of the variable that will be passed
 * @param   string    $value       Value of the variable that will be passed
 *
 * @access  public
 */ 	
function html_form_hidden($name, $value)
{
    return ( "<input type=\"hidden\" name=\"".$name
             ."\" value=\"".$value."\">");
}
function htmlp_form_hidden($name, $value)
{
    print html_form_hidden( $name, $value );
}

/**
 * HTML Form select field
 *
 * @param   string    $name        Name of the variable that will be passed
 *
 * @access  public
 */ 	
function html_select($name)
{
    return "<select name=\"".$name."\">\n";
}
function htmlp_select($name)
{
    print html_select( $name );
}

/**
 * HTML Form Option field in a select form
 *
 * @param   string    $value      Value that will be given to the variable if selected
 * @param   string    $selected   If this option is the default one (selected attribute) 
 * @param   string    $text       Text displayed to the user (sometimes equal to value)
 *
 * @access  public
 */
function html_select_option($value,$selected,$text)
{
    $output = "<option ";
    $output .= ( $selected ? "selected " : "" );
    $output .= "value=\"".$value."\">".$text."\n";
    return($output);
}
function htmlp_select_option($value,$selected,$text)
{
    print html_select_option( $value, $selected, $text );
}

/**
 * HTML Form ending select box
 *
 * @access  public
 */
function html_select_end()
{
    return "</select>\n";
}
function htmlp_select_end()
{
    print html_select_end();
}

/**
 * HTML Form Text Field
 *
 * @param   string    $name        Name of the variable
 * @param   int       $size        Size of the field (=< $maxlength)
 * @param   int       $maxlength   Maximal lentgh of the field (should be =< 255)
 * @param   string    $value       Default value
 *
 * @access  public
 */
function html_input_text($name, $size, $maxlength, $value= '')
{
    return ( "<input type=\"text\" name=\"".$name."\" size=\""
             .$size."\" maxlength=\"".$maxlength."\" value=\""
             .$value."\">\n" );
}
function htmlp_input_text($name, $size, $maxlength, $value)
{
    print html_input_text( $name, $size, $maxlength, $value );
}

/**
 * HTML Form Password Field
 *
 * @param   string    $name        Name of the variable
 * @param   int       $size        Size of the field (=< $maxlength)
 * @param   int       $maxlength   Maximal lentgh of the field (should be =< 255)
 * @param   string    $value       Default value
 *
 * @access  public
 */
function html_input_password($name, $size, $maxlength, $value= '')
{
    return ( "<input type=\"password\" name=\"".$name."\" size=\""
             .$size."\" maxlength=\"".$maxlength."\" value=\""
             .$value."\">\n" );
}
function htmlp_input_password($name, $size, $maxlength, $value)
{
    print html_input_password( $name, $size, $maxlength, $value );
}

/**
 * HTML Form Buttons
 *
 * @param   string    $value       Value of the variable
 * @param   string    $name        Name of the variable
 *
 * @access  public
 */
function html_form_submit($value, $name)
{
    return "<input type=\"submit\" value=\"".$value."\" name=\"".$name."\">\n";
}
function htmlp_form_submit($value, $name)
{
    print html_form_submit($value, $name);
}

/**
 * HTML Form check button
 *
 * @param   string    $name       Name of the variable
 * @param   string    $value      Value that will be given to the variable if selected
 * @param   string    $checked    If this option is selected
 *
 * @access  public
 */
function html_checkbox($name, $value, $checked)
{
    $output = ( "<input type=\"checkbox\" name=\"".$name."\" value=\""
                .$value."\"" );
    $output .= ( $checked ? " checked >" : ">" );
    return($output);
}
function htmlp_checkbox($name,$value,$checked)
{
    print html_checkbox( $name, $value, $checked );
}

/**
 * HTML Form radio button
 *
 * @param   string    $name       Name of the variable
 * @param   string    $value      Value that will be given to the variable if selected
 * @param   string    $checked    If this option is selected
 *
 * @access  public
 */
function html_radio($name, $value, $checked)
{
    $output = "<input type=\"radio\" name=\"".$name."\" value=\"".$value."\"";
    $output .= ( $checked ? " checked >" : ">" );
    return($output);
}
function htmlp_radio($name,$value,$checked)
{
    print html_radio( $name, $value, $checked );
}

/**
 * HTML Form TextArea Field
 *
 * @param   string    $name        Name of the variable
 * @param   string    $columns     Number of columns
 * @param   string    $rows        Number of rows
 * @param   string    $wrap        TODO
 * @param   int       $maxlength   Maximal lentgh of the field
 * @param   string    $value       Default value
 *
 * @access  public
 */
function html_textarea($name, $columns, $rows, $wrap='', $maxlength='', $value='')
{
    return ("<textarea cols=\"".$columns."\" rows=\"".$rows."\" name=\""
            .$name."\" wrap=\"".$wrap."\" maxlength=\"".$maxlength."\">"
            .$value."</textarea>\n" );
}
function htmlp_textarea($name,$columns,$rows,$wrap,$maxlength,$value)
{
    print html_textarea( $name, $columns, $rows, $wrap, $maxlength, $value );
}

/**
 * HTML Form end
 *
 * @access  public
 */
function html_form_end()
{
    return "</form>\n";
}
function htmlp_form_end()
{
    print html_form_end();
}
?>