<?php

######################################################################
# SourceAgency
# ================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourcewell.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
# This is the index file which shows the recent apps
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
######################################################################  

// TODO: do we need this page ???
// TODO: can this page be removed?

page_open(array("sess" => "SourceAgency_Session"));
if (isset($auth) && !empty($auth->auth["perm"])) {
  page_close();
  page_open(array("sess" => "SourceAgency_Session",
                  "auth" => "SourceAgency_Auth",
                  "perm" => "SourceAgency_Perm"));
}

require("header.inc");
$index = 0; // Zeigt ein Box mit den BerliOS News
$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);
$be = new box("80%",$th_box_frame_color,$th_box_frame_width,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_error_font_color,$th_box_body_align);
$bs = new box("100%",$th_strip_frame_color,$th_strip_frame_width,$th_strip_title_bgcolor,$th_strip_title_font_color,$th_strip_title_align,$th_strip_body_bgcolor,$th_strip_body_font_color,$th_strip_body_align);
?>

<!-- content -->
<table BORDER=0 CELLSPACING=10 CELLPADDING=0 WIDTH="100%" >
<tr width=80% valign=top><td>
<?php

$bx->box_begin();
$bx->box_title("Probe");
$bx->box_body_begin();
$bx->box_columns_begin(3);
$bx->box_column("left","33%","yellow","Eins");

$bx->box_column_start("center","33%","");
$bx->box_begin();
$bx->box_title("Zwei");
$bx->box_body_begin();
$bx->box_columns_begin(3);
$bx->box_column("left","33%","yellow","Eins");
$bx->box_column("center","33%","red","Zwei");
$bx->box_column("right","33%","green","und Drei");
$bx->box_columns_end();
$bx->box_body_end();
$bx->box_end();
$bx->box_column_finish();

$bx->box_column("right","33%","green","und Drei");
$bx->box_columns_end();
$bx->box_body_end();
$bx->box_end();

// Eine andere Probe
echo"<p>";
$page = "news";
$proid = 1;

$btop = new box("100%",$th_box_frame_color,1,$th_box_title_bgcolor,$th_box_title_font_color,$th_box_title_align,$th_box_body_bgcolor,$th_box_body_font_color,$th_box_body_align);

  $db_top = new DB_SourceAgency;
  $db_top->query("SELECT * FROM description WHERE proid='$proid'");
  $db_top->next_record();

$btop->box_begin();
$btop->box_body_begin();
$btop->box_columns_begin(2);
		// 1st column (left one)
$btop->box_column("left","","","<FONT size=\"+2\"><B> ".$db_top->f("project_title")." - $page</B></FONT>");
		// 2nd column (right one)
$btop->box_column_start("right","","");

					// Actions listed and linked
					// The current page has border = 1

  htmlp_link("summary.php3",array("proid" => $proid),html_image("ic/a.png",abs(!strcmp("summary",$page)),24,24,"Summary"));
  htmlp_link("news.php3",array("proid" => $proid),html_image("ic/b.png",abs(!strcmp("news",$page)),24,24,"News"));
  htmlp_link("comments.php3",array("proid" => $proid),html_image("ic/c.png",abs(!strcmp("comments",$page)),24,24,"General Comments"));
  htmlp_link("involvement.php3",array("proid" => $proid),html_image("ic/d.png",abs(!strcmp("involvement",$page)),24,24,"Sponsor Collaboration"));
  htmlp_link("story.php",array("proid" => $proid),html_image("ic/e.png",abs(!strcmp("story",$page)),24,24,"Project Story"));

					// Blank space that
					// limits actions from states

  htmlp_image("blank.gif",0,24,24,"");


					// Project steps
					// In grey future steps

for($i=1;$i<7;$i++) {
	if ($i <= $db_top->f("status")) {
		htmlp_link("step$i.php3",array("proid" => $proid),html_image("ic/$i.png",abs(!strcmp($i,$page)),24,24,"Step $i"));
	} else {
		htmlp_image("ic/".$i."grey.png",abs(!strcmp($i,$page)),24,24,"Step $i");
	}
}

$btop->box_next_row_of_columns();
$btop->box_column ("center","","",html_image("blank.gif",0,2,2,""));
$btop->box_column ("right","","",html_image("blank.gif",0,2,2,""));

$btop->box_columns_end();
$btop->box_body_end();
$btop->box_end();




$bx->box_begin();
$bx->box_title("Probe");
$bx->box_body_begin();
$bx->box_columns_begin(3);

$bx->box_column("left","33%","yellow","Eins");
$bx->box_column("center","33%","red","Zwei");
$bx->box_column("right","33%","green","und Drei");

$bx->box_next_row_of_columns();

$bx->box_colspan(2,"left%","orange","Eins");
$bx->box_column("right","33%","blue","und Drei");

$bx->box_next_row_of_columns();

$bx->box_column("left","33%","aqua","und Drei");
$bx->box_colspan("2","right","brown","Eins");

$bx->box_columns_end();
$bx->box_body_end();
$bx->box_end();






?>
</td></tr>
</table>
<!-- end content -->

<?php
require("footer.inc");
@page_close();
?>