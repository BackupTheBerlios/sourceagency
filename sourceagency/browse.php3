<?php

######################################################################
# SourceAgency: Open Source Project Mediation & Management System
# ===============================================================
#
# Copyright (c) 2001 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# TODO: Description missing
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
#
# $Id: browse.php3,v 1.2 2001/11/09 20:34:08 riessen Exp $
#
######################################################################  

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require("header.inc");
require("browselib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

$page = "browse";

  $bx->box_begin();
  $bx->box_body_begin();  
  $bx->box_columns_begin(2);

  $bx->box_column_start("","50%","");
  $bx->box_begin();
  $bx->box_title($t->translate("Categories"));
  $bx->box_body_begin();
  htmlp_link("$PHP_SELF",array("through" => "license"),"License");
  echo "<br>".html_link("$PHP_SELF",array("through" => "type"),"Type");
  echo "<br>".html_link("$PHP_SELF",array("through" => "steps"),"Steps");
  echo "<br>".html_link("$PHP_SELF",array("through" => "volume"),"Volume");
#  echo "<br>".html_link("$PHP_SELF",array("through" => "date"),"[Date]");
  echo "<br>".html_link("$PHP_SELF",array("through" => "platform"),"Platform");
  echo "<br>".html_link("$PHP_SELF",array("through" => "architecture"),"Architecture");
  echo "<br>".html_link("$PHP_SELF",array("through" => "environment"),"Environment");
#  echo "<br>".html_link("$PHP_SELF",array("through" => "audience"),"[Intended Audience]");
#  echo "<br>".html_link("$PHP_SELF",array("through" => "os"),"[Operating System]");
#  echo "<br>".html_link("$PHP_SELF",array("through" => "programming"),"[Programming Language]");
  $bx->box_body_end();
  $bx->box_end();
  $bx->box_column_finish();

  if (isset($through)) {
	browse_through($through);
  } else {
	$bx->box_column_start("","50%","");
	echo "&nbsp;\n";
	$bx->box_column_finish();
  }

  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();


  if (isset($$through)) {
	browse_list($through,$$through);
  }


?>

<!-- end content -->

<?php
require("footer.inc");
@page_close();
?>