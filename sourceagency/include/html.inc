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
# $Id: html.inc,v 1.21 2002/05/22 11:49:32 riessen Exp $
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
 * @param   string    $cssClass      CSS Class this link obeys to
 *
 * @global  object    $sess          PHPLib session object
 *
 * @access  public
 */
function html_link($address, $query, $text, $cssClass='')
{
    global $sess;

    if ($address == 'PHP_SELF') {
        if (is_array($query) && isset($query) && !empty($query)) {
            $output = '<a href="'.$sess->self_url().$sess->add_query($query).'" class="'.$cssClass.'">'.$text.'</a>';
        } else {
            $output = '<a href="'.$sess->self_url().'" class="'.$cssClass.'">'.$text.'</a>';
        }
    } else {
        if (is_array($query) && isset($query) && !empty($query)) {
            $output = '<a href="'.$sess->url($address).$sess->add_query($query).'" class="'.$cssClass.'">'.$text.'</a>';
        } else {
            $output = '<a href="'.$sess->url($address).'" class="'.$cssClass.'">'.$text.'</a>';
        }
    }
    return($output);
}
function htmlp_link($address, $query, $text, $cssClass='')
{
    print html_link( $address, $query, $text, $cssClass);
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
    return ('<a name="'.$name.'"></a>');
}
function htmlp_anchor($name)
{
    print html_anchor($name);
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
    return ( '<img src="images/'.$file.'" border="'.$border.'"'
             .' width="'.$width.'" height="'
             .$height.'" alt="'.$alternate.'">' );
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
function html_form_action($file = 'PHP_SELF', $query='', $type='POST')
{
    global $sess;
    
    if ($file == 'PHP_SELF') {
        $output = ( "\n".'<form action="'.$sess->self_url()
                    .$sess->add_query($query).'" method="'.$type.'">');

    } else {
        $output = ( "\n".'<form action="'.$sess->url($file)
                    .$sess->add_query($query).'" method="'.$type.'">' );
    }
    return($output);
}
function htmlp_form_action($file = 'PHP_SELF', $query = '', $type = 'POST')
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
    return ( "\n".'    <input type="hidden" name="'.$name.'" value="'.$value.'">');
}
function htmlp_form_hidden($name, $value)
{
    print html_form_hidden( $name, $value );
}

/**
 * HTML Form select field
 *
 * @param   string    $name        Name of the variable that will be passed
 * @param   boolen    $multiple    If the select box is multiple or not
 * @param   int       $size        If multiple, size of the select box
 * @return  string/void            depending whether it is the htmlp or html function
 * @access  public
 */ 	
function html_select($name, $multiple = 0, $size = 0)
{
    $output = "\n".'   <select name="'.$name.'" size="'.$size.'"';
    if ($multiple) {
        $output .= ' multiple';
    }
    $output .= ">\n";
    return($output);
}
function htmlp_select($name, $multiple = 0, $size = 0 )
{
    print html_select( $name, $multiple, $size );
}

/**
 * HTML Form Option field in a select form
 *
 * @param   string    $value      Value that will be given to the variable if selected
 * @param   boolean   $selected   If this option is the default one (selected attribute) 
 * @param   string    $text       Text displayed to the user (sometimes equal to value)
 * @return string/void         depending whether it is the htmlp or html function
 * @access  public
 */
function html_select_option($value, $selected, $text)
{
    $output = "\n".'      <option ';
    if ($selected) {
        $output .= 'selected ';
    }
    $output .= 'value="'.$value.'">'.$text."\n";
    return($output);
}
function htmlp_select_option($value,$selected,$text)
{
    print html_select_option( $value, $selected, $text );
}

/**
 * HTML Form ending select box
 *
 * @return string/void         depending whether it is the htmlp or html function
 * @access  public
 */
function html_select_end()
{
    $output = "\n   </select>\n";
    return($output);
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
function html_input_text($name, $size, $maxlength, $value = '')
{
    $output = "\n".'   <input type="text" name="'.$name.'" size="'
       .$size.'" maxlength="'.$maxlength.'" value="'.$value.'">';
    return($output);
}
function htmlp_input_text($name, $size, $maxlength, $value = '')
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
function html_input_password($name, $size, $maxlength, $value = '')
{
    return ( "\n".'   <input type="password" name="'.$name
             .'" size="'.$size.'" maxlength="'.$maxlength
             .'" value="'.$value.'">' );
}
function htmlp_input_password($name, $size, $maxlength, $value = '')
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
function html_form_submit($value, $name='')
{
    return ( "\n".'   <input type="submit" value="'.$value
             . ($name ? '" name="'.$name : '') . '">' );
}
function htmlp_form_submit($value, $name='')
{
    print html_form_submit($value, $name);
}

/**
 * HTML Form: image as submit button
 *
 * @param   string  $file         path to the image
 * @param   string  $description  alternate text for non-graphical browsers
 * @return string/void            depending whether it is the htmlp or html function
 * @access public
 */

function html_form_image($file, $description)
{
    return ( "\n".'   <input type="image" src="'.$file
             .'" alt="'.$description.'">' );
}
function htmlp_form_image($file, $description)
{
    print html_form_image($file, $description);
}

/**
 * HTML Form: reset button
 *
 * @param   string  $value        label for the button
 * @return  string/void            depending whether it is the htmlp or html function
 * @access  public
 */

function html_form_reset($value = 'Reset')
{
    return ( "\n".'   <input type="reset" value="'.$value.'">' );
}
function htmlp_form_reset($value = 'Reset')
{
    print html_form_reset($value);
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
    return ( "\n".'   <input type="checkbox" name="'.$name.'" value="'
             .$value.'"'. ( $checked ? ' checked >' : '>' ) );
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
    return ( "\n".'   <input type="radio" name="'.$name.'" value="'
             .$value.'"' . ( $checked ? ' checked >' : '>' ) );
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
    return ( "\n".'   <textarea name="'.$name.'" cols="'.$columns
             .'" rows="'.$rows.'" wrap="'.$wrap.'" maxlength="'
             .$maxlength.'">'.$value.'</textarea>' );
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
    return "\n</form>";
}
function htmlp_form_end()
{
    print html_form_end();
}
?>