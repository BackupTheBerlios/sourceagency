<?php

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
# This is the index file, i.e. the front page of the SourceAgency portal.
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

$bx = new box("100%",$th_box_frame_color,$th_box_frame_width,
              $th_box_title_bgcolor,$th_box_title_font_color,
              $th_box_title_align,$th_box_body_bgcolor,
              $th_box_body_font_color,$th_box_body_align);

start_content();

$db_summary = new DB_SourceAgency;

$bx->box_begin();
$bx->box_body_begin();
$bx->box_columns_begin(2);

$bx->box_column_start("left","65%","");

// I18N
echo ( "<br>\n" 
       . "You have entered SourceAgency, the Open Source projects "
       . "exchange at <a href=\"http://www.berlios.de\">BerliOS</a>.\n"
       . "<p>In SourceAgency you can sponsor projects if you commit as a "
       . "sponsor or let your projects be sponsored if you are an Open "
       . "Source developer.\n");
echo html_link( "doco.php3", $t->translate( "More" ) . "..."

$bx->box_begin();
$bx->box_title($t->translate("News"));
$bx->box_body_begin();

$db_summary->query("SELECT * FROM news,description WHERE "
                   ."news.proid=description.proid ORDER BY "
                   ."creation_news DESC LIMIT 3");

while ($db_summary->next_record()) {
  echo "<p><b>".html_link("news.php3",
                          array("proid" => $db_summary->f("proid")),
                          $db_summary->f("subject_news"))
    ."</b> in project <b>"
    .html_link("summary.php3",array("proid" => $db_summary->f("proid")),
               $db_summary->f("project_title"))."\n<br>";
  lib_pnick($db_summary->f("user_news"));
  echo ( " - ".timestr(mktimestamp($db_summary->f("creation_news")))
         ."</b>\n" . "<p>".$db_summary->f("text_news") . "<hr>");
}
$bx->box_body_end();
$bx->box_end();

$bx->box_begin();
$bx->box_title($t->translate("New Projects"));
$bx->box_body_begin();
$db_summary->query("SELECT * FROM description WHERE status > '0' ORDER "
                   ."BY description_creation DESC LIMIT 3");
while ($db_summary->next_record()) {
  echo "<p><b>".html_link("summary.php3",
                          array("proid" => $db_summary->f("proid")),
                          $db_summary->f("project_title"))."</b><br>";
  lib_pnick($db_summary->f("description_user"));
  echo (" - "
        .timestr(mktimestamp($db_summary->f("description_creation")))
        ."</b>\n");
  echo "<p>".$db_summary->f("description");
  echo "<p>type <b>".$db_summary->f("type")."</b> volume <b>"
    .$db_summary->f("volume")."</b>\n<br>";
  echo "<hr>";
}
$bx->box_body_end();
$bx->box_end();

$bx->box_column_finish();

$bx->box_column_start("right","35%","");

$bx->box_begin();
$bx->box_title($t->translate("Developers Wanted"));
$bx->box_body_begin();

$db_summary->query("SELECT * FROM description,configure WHERE status = '2'"
                   ." AND description.proid=configure.proid AND "
                   ."other_developing_proposals='Yes' ORDER BY "
                   ."description_creation DESC LIMIT 5");
echo "<ul>\n";
while ($db_summary->next_record()) {
  echo "<li><b>".html_link("step2.php3",
                           array("proid" => $db_summary->f("proid")),
                           $db_summary->f("project_title"))."</b>";
}
echo "</ul>\n";
$bx->box_body_end();
$bx->box_end();

$bx->box_begin();
$bx->box_title($t->translate("Sponsors Wanted"));
$bx->box_body_begin();

echo "<ul>\n";
$db_summary->query("SELECT * FROM description WHERE status >'0' ORDER BY "
                   ."description_creation DESC LIMIT 5");
while ($db_summary->next_record()) {
  echo "<li><b>".html_link("summary.php3",
                           array("proid" => $db_summary->f("proid")),
                           $db_summary->f("project_title"))."</b>";
}
echo "</ul>\n";
$bx->box_body_end();
$bx->box_end();

$bx->box_begin();
$bx->box_title($t->translate("Consultants Wanted"));
$bx->box_body_begin();
$db_summary->query("SELECT * FROM description,configure WHERE status = '1' "
                   ."AND configure.proid=description.proid AND "
                   ."consultants='Yes' ORDER BY description_creation "
                   ."DESC LIMIT 5");
echo "<ul>\n";
while ($db_summary->next_record()) {
  echo "<li><b>".html_link("step1.php3",
                           array("proid" => $db_summary->f("proid")),
                           $db_summary->f("project_title"))."</b>";
}
echo "</ul>\n";
$bx->box_body_end();
$bx->box_end();

$bx->box_begin();
$bx->box_title($t->translate("Referees Wanted"));
$bx->box_body_begin();
$db_summary->query("SELECT * FROM description WHERE status = '4' ORDER "
                   ."BY description_creation DESC LIMIT 5");
echo "<ul>\n";
while ($db_summary->next_record()) {
  echo "<li><b>".html_link("step4.php3",
                           array("proid" => $db_summary->f("proid")),
                           $db_summary->f("project_title"))."</b>";
}
echo "</ul>\n";
$bx->box_body_end();
$bx->box_end();

$bx->box_begin();
$bx->box_title($t->translate("Developing cooperants Wanted"));
$bx->box_body_begin();
$db_summary->query("SELECT * FROM description,developing WHERE "
                   ."cooperation!='No' AND "
                   ."description.proid=developing.proid ORDER BY "
                   ."description_creation DESC LIMIT 5");
echo "<ul>\n";
while ($db_summary->next_record()) {
  echo "<li><b>".html_link("step2.php3",
                           array("proid" => $db_summary->f("proid")),
                           $db_summary->f("project_title"))."</b>";
}
echo "</ul>\n";
$bx->box_body_end();
$bx->box_end();

$bx->box_begin();
$bx->box_title($t->translate("Finished projects"));
$bx->box_body_begin();
$db_summary->query("SELECT * FROM description WHERE status = '6' "
                   ."ORDER BY description_creation DESC LIMIT 5");
echo "<ul>\n";
while ($db_summary->next_record()) {
  echo "<li><b>".html_link("summary.php3",
                           array("proid" => $db_summary->f("proid")),
                           $db_summary->f("project_title"))."</b>";
}
echo "</ul>\n";
$bx->box_body_end();
$bx->box_end();

$bx->box_column_finish();
$bx->box_columns_end();
$bx->box_body_end();
$bx->box_end();

$db->query("SELECT * FROM description");

while ($db->next_record()) {

  htmlp_link("summary.php3",array("proid" => $db->f("proid")),
             "Project ".$db->f("proid"));
  echo " (".$db->f("project_title").")<br>\n";
}

end_content();
require("footer.inc");
@page_close();
?>