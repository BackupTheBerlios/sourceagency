<?php

######################################################################
# SourceAgency:
# ================================================
#
# Copyright (c) 2001 by
#                Gregorio Robles (grex@scouts-es.org)
#
# BerliOS SourceAgency: http://sourceagency.berlios.de
# BerliOS - The OpenSource Mediator: http://www.berlios.de
#
#
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 or later of the GPL.
######################################################################

function ratings_form($proid,$dev_or_spo,$number) {
  global $bx, $auth, $db, $t;

  $bx->box_begin();
  $bx->box_title($t->translate("Rating"));
  $bx->box_body_begin();
  $bx->box_columns_begin(3);

  $bx->box_column_start("center","33%","white");
  $db->query("SELECT * FROM developing WHERE proid='$proid' AND status='A' AND developer!='".$auth->auth["uname"]."' ORDER BY devid");
  while($db->next_record()) {
	if (!strcmp($dev_or_spo,"developer") && $db->f("devid")==$number) ratings_form_full($proid,$db->f("developer"),"developer",$db->f("devid"));
	elseif (!ratings_rated_yet($proid,$db->f("developer"),$auth->auth["uname"])) ratings_form_empty($proid,$db->f("developer"),"developer",$db->f("devid"));
	else ratings_form_rated($proid,$db->f("developer"),"developer",$db->f("devid"));
  }
  $bx->box_column_finish($proid);

  $bx->box_column_start("center","33%","white");
  $db->query("SELECT * FROM sponsoring WHERE proid='$proid' AND status='A' AND sponsor!='".$auth->auth["uname"]."' ORDER BY spoid");
  while($db->next_record()) {
	if (!strcmp($dev_or_spo,"sponsor") && $db->f("spoid")==$number) ratings_form_full($proid,$db->f("sponsor"),"sponsor",$db->f("spoid"));
	elseif (!ratings_rated_yet($proid,$db->f("sponsor"),$auth->auth["uname"])) ratings_form_empty($proid,$db->f("sponsor"),"sponsor",$db->f("spoid"));
	else ratings_form_rated($proid,$db->f("sponsor"),"sponsor",$db->f("spoid"));
  }
  $bx->box_column_finish();

  $bx->box_column("center","33%","white","Explanation");
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();

  ratings_form_finish($proid);

}

function ratings_form_full($proid,$username,$dev_or_spo,$id) {
  global $db, $bx, $t, $sess, $auth;

  include("config.inc");
  $array = $sponsor_rates_developer;

  $bx->box_begin();
  $bx->box_title("Rate ".$username);
  $bx->box_body_begin();

  $bx->box_columns_begin(6);
  $bx->box_column ("right","30%","","");
  for($j=1;$j<=5;$j++) {
	$bx->box_column ("center","14%","","<b>$j</b>");
  }
  $bx->box_next_row_of_columns();		

  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");

  for($i=0;$i < sizeof($array);$i++) {
	if ($i%2==0) $background="gold";
	else $background="#FFFFFF";

	$bx->box_column ("right","",$background,"<b>".$t->translate($array[$i])."</b>: ");
	if (!ratings_rated_yet($proid,$username,$auth->auth["uname"])) {
		for($j=1;$j<=5;$j++) {
			$bx->box_column ("center","",$background,html_radio($array[$i],$j,$checked));
		}
	} else {
		$db_local = new DB_SourceAgency;
		$db_local->query("SELECT rating FROM ratings WHERE proid='$proid' AND to_whom='$username' AND by_whom='".$auth->auth["uname"]."' AND on_what='".$array[$i]."'");
		$db_local->next_record();
		for($j=1;$j<=5;$j++) {
			$checked = "";
			if ($j==$db_local->f("rating")) $checked = "checked";
			$bx->box_column ("center","",$background,html_radio($array[$i],$j,$checked));
		}
	}
	$bx->box_next_row_of_columns();
  }

  htmlp_form_hidden("dev_or_spo",$dev_or_spo);
  htmlp_form_hidden("id_number",$id);

  $bx->box_column ("right","30%","","&nbsp;");
  $bx->box_colspan("5","right","",html_form_submit("Submit","submit"));
  htmlp_form_end();
  $bx->box_columns_end();

  $bx->box_body_end();
  $bx->box_end();
}

function ratings_form_empty($proid,$username,$dev_or_spo,$number) {
  global $bx, $t, $sess;

  include("config.inc");
  $array = $sponsor_rates_developer;
  $bx->box_begin();
  $bx->box_title($username);
  $bx->box_body_begin();
  $bx->box_columns_begin(1);
  $bx->box_column ("","","white","&nbsp;");
  $bx->box_next_row_of_columns();
  for($i=0;$i<sizeof($array);$i++) {
	if ($i%2==0) $background="gold";
	else $background="#FFFFFF";

	$bx->box_column ("","",$background,"&nbsp;");
	$bx->box_next_row_of_columns();
  }
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");
  htmlp_form_hidden("dev_or_spo",$dev_or_spo);
  htmlp_form_hidden("id_number",$number);

  $bx->box_column ("right","","white",html_form_submit("Rate Me","rateme"));
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}


function ratings_form_rated($proid,$username,$dev_or_spo,$number) {
  global $bx, $t, $sess;

  include("config.inc");
  $array = $sponsor_rates_developer;
  $bx->box_begin();
  $bx->box_title($username);
  $bx->box_body_begin();
  $bx->box_columns_begin(1);
  $bx->box_column ("","","white","&nbsp;");
  $bx->box_next_row_of_columns();
  for($i=0;$i<sizeof($array);$i++) {
	if ($i%2==0) $background="gold";
	else $background="#FFFFFF";

	if ($i==floor(sizeof($array)/2))  $bx->box_column ("","",$background,"<center><b>Already rated</b></center>");
	else $bx->box_column ("","",$background,"&nbsp;");
	$bx->box_next_row_of_columns();
  }
  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");
  htmlp_form_hidden("dev_or_spo",$dev_or_spo);
  htmlp_form_hidden("id_number",$number);

  $bx->box_column ("right","","white",html_form_submit("Modify","rateme"));
  $bx->box_columns_end();
  $bx->box_body_end();
  $bx->box_end();
}


function ratings_insert($proid,$dev_or_spo,$number,$by_whom) {
  global $db, $auth;

  if(!strcmp($dev_or_spo,"developer")) {
	$table = "developing";
	$type_of_id = "devid";
  } else {
	$table = "sponsoring";
	$type_of_id = "spoid";
  }

  $db->query("SELECT $dev_or_spo FROM $table WHERE proid='$proid' AND $type_of_id='$number'");
  $db->next_record();
  $to_whom = $db->f($dev_or_spo);

  include("config.inc");

  $string = $auth->auth["perm"]."_rates_".$dev_or_spo;

  switch ($string) {
	case "sponsor_rates_developer":	$array = $sponsor_rates_developer; break;
	case "sponsor_rates_sponsor": $array = $sponsor_rates_developer; break;
	case "developer_rates_sponsor": $array = $sponsor_rates_developer; break;
	case "developer_rates_developer": $array = $sponsor_rates_developer; break;
  }

  if (ratings_rated_yet($proid,$to_whom,$by_whom)) {
	for($i=0;$i<sizeof($array);$i++) {
		$db->query("UPDATE ratings SET rating='".$GLOBALS["$array[$i]"]."'  WHERE proid='$proid' AND to_whom='$to_whom' AND by_whom='$by_whom' AND on_what='$array[$i]'");
  	}
  } else {

	$db->query("SELECT volume FROM description WHERE proid='$proid'");
	$db->next_record();
	$volume = $db->f("volume");

	for($i=0;$i<sizeof($array);$i++) {
		$db->query("INSERT ratings SET proid='$proid', to_whom='$to_whom', by_whom='$by_whom', rating='".$GLOBALS["$array[$i]"]."', on_what='$array[$i]', project_importance='$volume'");
  	}
  }
}

function ratings_look_for_first_one($proid) {
  global $db, $auth, $dev_or_spo;

  if (!isset($dev_or_spo) || empty($dev_or_spo)) $dev_or_spo = "developer";

  $dev_or_spo = "developer";
  $db->query("SELECT developer,devid FROM developing WHERE proid='$proid' AND status='A' AND developer!='".$auth->auth["uname"]."' ORDER BY devid");
  while ($db->next_record()) {
	if (!ratings_rated_yet($proid,$db->f("developer"),$auth->auth["uname"])) return $db->f("devid");
  }

  $dev_or_spo = "sponsor";
  $db->query("SELECT sponsor,spoid FROM sponsoring WHERE proid='$proid' AND status='A' AND sponsor!='".$auth->auth["uname"]."' ORDER BY spoid");
  while ($db->next_record()) {
	if (!ratings_rated_yet($proid,$db->f("sponsor"),$auth->auth["uname"])) return $db->f("spoid");
  }
}

function ratings_look_for_next_one($proid,$number) {
  global $db, $auth, $dev_or_spo;

  switch($dev_or_spo) {
	case "developer":  	$id = "devid"; 	$table = "developing"; 	break;
	case "sponsor":		$id = "spoid"; 	$table = "sponsoring";	break;
  }

  $db->query("SELECT $dev_or_spo,$id FROM $table WHERE proid='$proid' AND status='A' AND $dev_or_spo!='".$auth->auth["uname"]."' AND $id>'$number' ORDER BY $id");
  while ($db->next_record()) {
	if (!ratings_rated_yet($proid,$db->f($dev_or_spo),$auth->auth["uname"])) { $number = $db->f($id); return;}
  }

  if(!strcmp($dev_or_spo,"developer")) {
	$dev_or_spo = "sponsor";
	ratings_look_for_next_one($proid,1);
  } else {
	$dev_or_spo = "";
	$number = "";
  }
}


function ratings_rated_yet($proid,$to_whom,$by_whom) {
 
	$db_local = new DB_SourceAgency;
	$db_local->query("SELECT * FROM ratings WHERE proid='$proid' AND to_whom='$to_whom' AND by_whom='$by_whom'");
	if ($db_local->num_rows() == 0) return 0;
	else return 1;

}


function show_participants_rating($proid,$participant_type) {
  global $t, $db, $bx;

  $bx->box_begin();
  $bx->box_title($t->translate($participant_type));
  $bx->box_body_begin();

  switch($participant_type) {
	case "developer":	$table = "developing"; break;
	case "sponsor":		$table = "sponsoring"; break;
	case "referee":		$table = "referees"; break;
  }

  $db->query("SELECT $participant_type FROM $table WHERE proid='$proid' AND status='A'");
  while($db->next_record()) {
	show_personal_rating($proid,$db->f($participant_type));
  }

  echo "<br>&nbsp;\n";
  $bx->box_body_end();
  $bx->box_end();
}


function show_personal_rating($proid,$username) {
  global $t, $bx;

  $db_local = new DB_SourceAgency;
  $db_local->query("SELECT rating FROM ratings WHERE proid='$proid' AND to_whom='$username'");
  if ($db_local->num_rows() == 0) echo "<p><b>$username</b>: Not rated yet\n";
  else {
	$i=0; $rating=0;
  	while ($db_local->next_record()) {
		$i+=1;
		$rating += $db_local->f("rating");
	}
  	echo "<p><b>$username</b>: ".(round($rating*100/$i)/100)." (rated $i times)\n";
  }
}

function ratings_form_finish($proid) {

  htmlp_form_action("PHP_SELF",array("proid" => $proid),"POST");
  htmlp_form_hidden("dev_or_spo","");
  htmlp_form_hidden("id_number","");
  htmlp_form_submit("Rating finished","finished");
  htmlp_form_end();

}

function ratings_in_history($proid,$history_user) {
  global $db, $t, $bx;

	$db->query("INSERT history SET proid='$proid', history_user='$history_user', type='Rating', action='Rating by $history_user completed'");
	if ($db->affected_rows() > 0) {
		$bx->box_full("Rating saved","Your rating has been successfully saved");
	}
}

?>