<?php

######################################################################
# SourceAgency:
# ================================================
#
# Copyright (c) 2001 by
#             Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the index file which shows the recent apps
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
######################################################################  

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require("header.inc");
require("personallib.inc");

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
?>

<!-- content -->

<?php

$page = "personal";

  $bx->box_begin();
  $bx->box_body_begin();
  $bx->box_columns_begin(2);
  $bx->box_column("left","60%","","Hi, <b>".$auth->auth["uname"]."</b>. This is your personal page.<br>
				   In this page you'll find all the information related to you in SourceAgency.");
  $bx->box_column_start("right","40%","");
  personal_ratings_short($auth->auth["uname"]);
  $bx->box_column_finish();
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();

  $bx->box_begin();
  $bx->box_body_begin();
  $bx->box_columns_begin(3);
  $bx->box_column_start("center","34%","");
  personal_my_projects($auth->auth["uname"]);
  $bx->box_column_finish();
  $bx->box_column_start("center","33%","");
  personal_monitored_projects($auth->auth["uname"]);
  $bx->box_column_finish();
  $bx->box_column_start("center","33%","");
#  print "(FIXME: Possible actions)\n";
  $bx->box_column_finish();
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();


//  personal_ratings_long($auth->auth["uname"]);


  if (($auth->auth["perm"]!="sponsor_pending") && ($auth->auth["perm"]!="devel_pending") && ($auth->auth["perm"]!="editor") && ($auth->auth["perm"]!="admin") && ($auth->auth["perm"]!="editor,admin")) {
  	$bx->box_begin();
  	$bx->box_body_begin();
  	$bx->box_columns_begin(3);
  	$bx->box_column_start("right","34%","");
  	personal_related_projects($auth->auth["uname"],"A");
  	$bx->box_column_finish();
  	$bx->box_column_start("right","33%","");
  	personal_related_projects($auth->auth["uname"],"P");
  	$bx->box_column_finish();
  	$bx->box_column_start("right","33%","");
  	personal_related_projects($auth->auth["uname"],"R");
  	$bx->box_column_finish();
  	$bx->box_columns_end();
  	$bx->box_body_end();
  	$bx->box_end();
  }

  if (is_developer($auth->auth["uname"]) && ($auth->auth["perm"]!="devel_pending") && ($auth->auth["perm"]!="editor") && ($auth->auth["perm"]!="admin") && ($auth->auth["perm"]!="editor,admin")) {
	$bx->box_begin();
	$bx->box_body_begin();
	$bx->box_columns_begin(3);
	$bx->box_column_start("right","34%","");
	personal_consultants($auth->auth["uname"],"A");
  	$bx->box_column_finish();
  	$bx->box_column_start("right","33%","");
	personal_consultants($auth->auth["uname"],"P");
  	$bx->box_column_finish();
  	$bx->box_column_start("right","33%","");
	personal_consultants($auth->auth["uname"],"R");
  	$bx->box_column_finish();
  	$bx->box_columns_end();
  	$bx->box_body_end();
  	$bx->box_end();

	$bx->box_begin();
	$bx->box_body_begin();
	$bx->box_columns_begin(3);
	$bx->box_column_start("right","34%","");
	personal_referees($auth->auth["uname"],"A");
  	$bx->box_column_finish();
  	$bx->box_column_start("right","33%","");
	personal_referees($auth->auth["uname"],"P");
  	$bx->box_column_finish();
  	$bx->box_column_start("right","33%","");
	personal_referees($auth->auth["uname"],"R");
  	$bx->box_column_finish();
  	$bx->box_columns_end();
  	$bx->box_body_end();
	$bx->box_end();

// TODO
/*
	$bx->box_begin();
	$bx->box_body_begin();
	$bx->box_columns_begin(3);
	$bx->box_column_start("right","34%","");
	personal_cooperation($auth->auth["uname"],"A");
  	$bx->box_column_finish();
  	$bx->box_column_start("right","33%","");
	personal_cooperation($auth->auth["uname"],"P");
  	$bx->box_column_finish();
  	$bx->box_column_start("right","33%","");
	personal_cooperation($auth->auth["uname"],"R");
  	$bx->box_column_finish();
  	$bx->box_columns_end();
  	$bx->box_body_end();
	$bx->box_end();
*/

  }

  personal_comments_short($auth->auth["uname"]);
  personal_news_short($auth->auth["uname"]);

?>

<!-- end content -->

<?php
require("footer.inc");
page_close();
?>