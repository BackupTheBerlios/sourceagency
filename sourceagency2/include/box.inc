<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001-2003 by
#		 Lutz Henckel (lutz.henckel@fokus.fraunhofer.de) and
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# The HTML table library
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: box.inc,v 1.1 2003/11/21 12:56:01 helix Exp $
#
######################################################################

/**
 * Class Table: a class for HTML Tables
 *
 * This library contains an object oriented vision of the HTML tables.
 * Its use allows to abstract the PHP from the HTML
 *
 * With this class, you should be able of doing something like this:
 *
 * +------------------------------------------------+
 * | Title                                          |
 * +------------------------------------------------+
 * | Body                                           |
 * |                                                |
 * |                                                |
 * +------------------------------------------------+
 *
 * or even like this:
 *
 * +------------------------------------------------+
 * | Title                                          |
 * +------------------------------------------------+
 * | Body                                           |
 * |                                                |
 * | +-------------++--------------++-------------+ |
 * | |  Column     ||  Column      ||  Column     | |
 * | +-------------++--------------++-------------+ |
 * | |  Column     ||  Column      ||  Column     | |
 * | +-------------++--------------++-------------+ |
 * | |  Spanned column             ||  Column     | |
 * | +-----------------------------++-------------+ |
 * +------------------------------------------------+
 *
 * Title, Body, Column and Spanned Column are contents
 * (in string format) you may want to put in there
 *
 * This class handles primarily visual aspects of the table,
 * while it provides an interface to introduce its contents
 * Visual configuration is done through config.inc
 *
 * @author Lutz Henckel <lutz.henckel@fokus.fhg.de>
 * @author Gregorio Robles <grex@scouts-es.org>
 * @access public
 * @see html
 */

class box {
    /**
     * Width of the table (IE has problems with 100% widths!) 
     * @var  string
     */
    var $box_width;
    var $box_frame_color;
    var $box_frame_width;
    /**
     * Background color of the Title
     * @var  string
     */
    var $box_title_bgcolor;
    /**
     * Font Color of the Title
     * @var  string
     */
    var $box_title_font_color;
    /**
     * Align of the Title
     * @var  string
     */
    var $box_title_align;
    /**
     * Background color of the Body
     * @var  string
     */
    var $box_body_bgcolor;
    /**
     * Font color of the body
     * @var  string
     */
    var $box_body_font_color;
    /**
     * Align of the Body
     * @var  string
     */
    var $box_body_align;

    /**
     * Vertical align for the body
     * @var string
     */
    var $box_body_valign;

    /**
     * Constructor
     *
     * @param   string  $
     * @param   string  $
     * @param   string  $
     * @param   string  $
     * @param   string  $
     * @param   string  $
     * @param   string  $
     * @param   string  $
     * @param   string  $
     * @param   string  $
     * @param   string  $
     * @param   string  $
     *
     * @access  public
     */
    function box($box_width='', $frame_color='#000000', $frame_width='1', 
                 $title_bgcolor='#CCCCCC', $title_font_color='#000000', 
                 $title_align='', $body_bgcolor='#FFFFFF', 
                 $body_font_color='#000000', $body_align='', $body_valign='')
    {
        $this->box_width = $box_width;
	$this->box_frame_color = $frame_color;
	$this->box_frame_width = $frame_width;
	$this->box_title_bgcolor = $title_bgcolor;
	$this->box_title_font_color = $title_font_color;
	$this->box_title_align = $title_align;
	$this->box_body_bgcolor = $body_bgcolor;
	$this->box_body_font_color = $body_font_color;
	$this->box_body_align = $body_align;
        $this->box_body_valign = $body_valign;

	/* HTML4 specification requires valign to be always set */
        if (!$this->box_body_valign) {
  	    $this->box_body_valign = 'top';
	}
    }
   
    /**
     * Prints the beginning tags for a table
     *
     * @access public
     */
    function box_begin()
    {
	echo "\n<!-- box begin -->\n";
    	echo '<table border="0" cellspacing="0" cellpadding="0" bgcolor=';
        echo "\"$this->box_frame_color\" width=\"$this->box_width\" ";
        echo "align=\"center\">\n<tr><td>\n";
	echo '<table border="0" cellspacing=';
        echo "\"$this->box_frame_width\" ";
        echo 'cellpadding="3" align="center" width="100%">';
        echo "\n";
    }

    /**
     * Prints the end tags for a table
     *
     * @access public
     */
    function box_end()
    {
	echo "</table>\n";
	echo "</td></tr></table><br>\n";
	echo "<!-- box end -->\n";
    }

    /**
     * Prints the beginning tags for the table title
     *
     * @access public
     */
    function box_title_begin()
    {
	echo "   <!-- box title begin -->\n";
	echo "   <tr bgcolor=\"$this->box_title_bgcolor\">";
        echo "      <td align=\"$this->box_title_align\">\n";
    }

    /**
     * Prints the end tags for the table title
     *
     * @access public
     */
    function box_title_end()
    {
	echo "      </td>\n";
        echo "   </tr>\n";
	echo "   <!-- box title end -->\n";
    }

    /**
     * Prints the title
     * It is the same as calling (but in one function):
     *       box_title_begin()
     *       echo $text;
     *       box_title_end()
     *
     * @param    string      $title     Text that will be the title
     * @access public
     */
    function box_title($title)
    {
	$this->box_title_begin();
	echo ( "      <font color=\"$this->box_title_font_color\">"
               ."<b>$title</b></font>\n" );
	$this->box_title_end();
    }

    /**
     * Prints the beginning tags for the table body
     *
     * @access public
     */
    function box_body_begin()
    {
       	echo ( "   <!-- box body begin -->\n"
               . "   <tr bgcolor=\"$this->box_body_bgcolor\">\n"
               . "         <td align=\"$this->box_body_align\" valign="
               . "\"$this->box_body_valign\"><font color=\""
               . "$this->box_body_font_color\">\n" );
    }

    /**
     * Prints the end tags for the table body
     *
     * @access public
     */
    function box_body_end()
    {
	echo ( "      </font>\n"
               . "      </td>\n"
               . "   </tr>\n"
               . "   <!-- box body end -->\n" );
    }

    /**
     * Prints the body
     * It is the same as calling (but in one function):
     *       box_body_begin()
     *       echo $text;
     *       box_body_end()
     *
     * @param    string      $text     Text that will be in the body
     * @access public
     */
    function box_body($text)
    {
	$this->box_body_begin();
	echo ( "            <font color=\"".$this->box_body_font_color."\">\n"
               . '            '.$text
               . "            </font>\n" );
	$this->box_body_end();
    }

    /**
     * Prints a full box (title + body)
     * It is the same as calling (but in one function):
     *       box_title($title)
     *       box_body($text)
     *
     * @param    string      $title    Text that will be the title of the table
     * @param    string      $text     Text that will be in the body
     * @access public
     */
    function box_full($title, $text)
    {
       	$this->box_begin();
	$this->box_title($title);
	$this->box_body($text);
	$this->box_end();
    }

    /**
     * Prints a box strip
     * A strip is a table that only contains a title (no body)
     *
     * @param    string      $title    Text that will be the title of the strip
     * @access public
     */
    function box_strip($title)
    {
	$this->box_begin();
	$this->box_title($title);
	$this->box_end();
    }

    /**
     * Prints the beginning tags for starting row(s) of columns
     *
     * @param    int         $number_of_columns     Number of columns
     * Strictly speaking, the number of columns is not required
     * It is used for making a better codification possible (it is easier 
     * to be read)
     * @access public
     */
    function box_columns_begin($number_of_columns, $valign = 'top')
    {
	echo ( "\n   <!-- table with ".$number_of_columns." columns -->\n"
               . "   <table border=\"0\" cellspacing=\"0\" cellpadding=\"3"
               . "\" align=\"center\" width=\"100%\">\n"
               . "      <tr valign=\"$valign\">\n");
    }

    /**
     * Prints the beginning tags for starting a single columns
     * It is the same as calling (but in one function):
     *
     * @param    string      $align     Text align
     * @param    string      $width     Column width (best in %)
     * @param    string      $bgcolor   Background color
     * @access public
     */
    function box_column_start($align, $width, $bg_color='#FFFFFF')
    {
	if (!isset($bg_color) || empty($bg_color)) {
            $bg_color='#FFFFFF';
	}
	echo ( "         <!-- New Column starts -->\n"
               . "         <td align=\"".$align."\" width=\"".$width
               ."\" bgcolor=\"".$bg_color."\">" );
    }

    /**
     * Prints the ending tags for a single columns
     *
     * @access public
     */
    function box_column_finish()
    {
	echo "</td>\n         <!-- Column finishes -->\n";
    }

    /**
     * Prints the ending tags for ending row(s) of columns
     *
     * @acchess public
     */
    function box_columns_end()
    {
       	echo "      </tr>\n   </table>\n   <!-- end table with columns -->\n";
    }		

    /**
     * Prints a single columns
     * It is the same as calling (but in one function):
     *       box_column_start($width, $bgcolor, $align)
     *       echo $text;
     *       box_column_finish()
     *
     * Requires to work fine that you put it between:
     *       box_columns_begin()
     *       box_columns_end()
     *
     * @param    string      $align     Text align
     * @param    string      $width     Column width (best in %)
     * @param    string      $bgcolor   Background color
     * @param    string      $text      Text in the column
     * @access public
     */
    function box_column($align, $width, $bgcolor, $text)
    {
       	$this->box_column_start($align, $width, $bgcolor);
       	echo $text;
       	$this->box_column_finish();
    }

    /**
     * New row of columns
     *
     * It should be used as followed:
     * StartColumns
     * Column1  Column2  nextRowWithColumns
     * Column4  Column5  nextRowWithColumns
     * Column7  Column8
     * EndColumns
     *
     * @access public
     */
    function box_next_row_of_columns()
    {
       	echo ( "      </tr>\n      <!-- next row with several columns -->\n"
               . "      <tr>\n" );
    }

    /**
     * Joins several columns in one
     *
     * Requires to work fine that you put it between:
     *       box_columns_begin()
     *       box_columns_end()
     *
     * @param    string      $text                 Text in the column
     * @param    int         $numberOfColumns      number of columns being 
     *                                             joined
     * @param    string      $width                Column width (best in %)
     * @param    string      $bgcolor              Background color
     * @param    string      $align                Text align
     * @access public
     */
    function box_colspan($number_of_columns, $align, $bgcolor, $text='')
    {
       	echo ( "         <!-- New Column spanned over $number_of_columns "
               . "columns starts -->\n"
               . "         <td colspan=\"".$number_of_columns."\" align=\""
               . $align."\" bgcolor=\"".$bgcolor."\">" . $text
               . "</td>\n"
               . "         <!-- Column spanned over $number_of_columns "
               ."columns finished -->\n" );
    }

    function box_set_body_valign( $valign = 'top' )
    {
        $this->box_body_valign = $valign;
    }
}
?>
