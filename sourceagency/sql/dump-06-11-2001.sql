# MySQL dump 8.12
#
# Host: localhost    Database: sourceagency
#--------------------------------------------------------
# Server version	3.23.33-log

#
# Table structure for table 'active_sessions'
#

CREATE TABLE active_sessions (
  sid varchar(32) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  val text,
  changed varchar(14) NOT NULL default '',
  PRIMARY KEY (name,sid),
  KEY changed(changed)
) TYPE=MyISAM;

#
# Dumping data for table 'active_sessions'
#

INSERT INTO active_sessions VALUES ('81f6261802868b9aa957300b1bb55362','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnZm9ybSc7ICRHTE9CQUxTWydhdXRoJ10tPmF1dGhbJ3Blcm0nXSA9ICcnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydleHAnXSA9ICcyMTQ3NDgzNjQ3JzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsncmVmcmVzaCddID0gJzIxNDc0ODM2NDcnOyA=','20011016213221');
INSERT INTO active_sessions VALUES ('75864319deb8156505c6aee3eaaac018','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnZm9ybSc7ICRHTE9CQUxTWydhdXRoJ10tPmF1dGhbJ3Blcm0nXSA9ICcnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydleHAnXSA9ICcyMTQ3NDgzNjQ3JzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsncmVmcmVzaCddID0gJzIxNDc0ODM2NDcnOyA=','20011017110539');
INSERT INTO active_sessions VALUES ('30a03677cd5f3b44d46588f844db1c55','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnMzViM2RiNDg5NDRkYmE3YTRlMjcyOTI2ZmQwZDI4MzknOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydwZXJtJ10gPSAnZGV2ZWwnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydleHAnXSA9ICcxMDAzMzE4OTUzJzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsncmVmcmVzaCddID0gJzEwMDMzMDk5MzgnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWyd1bmFtZSddID0gJ2hlbGl4Jzsg','20011017111233');

#
# Table structure for table 'auth_user'
#

CREATE TABLE auth_user (
  user_id varchar(32) NOT NULL default '',
  username varchar(32) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  realname varchar(64) NOT NULL default '',
  email_usr varchar(128) NOT NULL default '',
  modification_usr timestamp(14) NOT NULL,
  creation_usr timestamp(14) NOT NULL,
  perms varchar(255) default NULL,
  PRIMARY KEY (user_id),
  UNIQUE KEY k_username(username)
) TYPE=MyISAM;

#
# Dumping data for table 'auth_user'
#

INSERT INTO auth_user VALUES ('c8a174e0bdda2011ff798b20f219adc5','admin','admin','admin','grex@scouts-es.org',20010419103000,20010419103000,'editor,admin');
INSERT INTO auth_user VALUES ('42b3cdc7658ed6b3e07b9441c7679b28','devel','devel','devel','devel@scouts-es.org',20010426182520,20010426182520,'devel');
INSERT INTO auth_user VALUES ('740156f449ebc5950546517021fda49d','sponsor','sponsor','sponsor','sponsor@scouts-es.org',20010426182533,20010426182533,'sponsor');
INSERT INTO auth_user VALUES ('f4c756dfc8e55a131d2a7d4be9e3e11b','riessen','fu23bar','Gerrit Riessen','gerrit.riessen@web.de',20011004151153,20011004150815,'devel');
INSERT INTO auth_user VALUES ('35b3db48944dba7a4e272926fd0d2839','helix','helix%sa','Lutz Henckel','lutz.henckel@fokus.gmd.de',20011005122316,20011005111603,'devel');
INSERT INTO auth_user VALUES ('48db7ac02e974648e3454c02021b632a','nilix','nilix%sa','Lutz Henckel','lutz.henckel@fokus.gmd.de',20011005180845,20011005180845,'sponsor');

#
# Table structure for table 'categories'
#

CREATE TABLE categories (
  section varchar(64) NOT NULL default '',
  category varchar(64) NOT NULL default ''
) TYPE=MyISAM;

#
# Dumping data for table 'categories'
#

INSERT INTO categories VALUES ('GNOME','Miscellaneous');
INSERT INTO categories VALUES ('KDE','Miscellaneous');
INSERT INTO categories VALUES ('X11','Multimedia');

#
# Table structure for table 'comments'
#

CREATE TABLE comments (
  proid bigint(20) unsigned NOT NULL default '0',
  user_cmt varchar(16) NOT NULL default '',
  type varchar(16) NOT NULL default 'General',
  number varchar(16) NOT NULL default '0',
  id int(8) NOT NULL default '1',
  ref int(8) NOT NULL default '0',
  subject_cmt varchar(128) NOT NULL default '',
  text_cmt blob NOT NULL,
  creation_cmt timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'comments'
#

INSERT INTO comments VALUES (3,'helix','News','20011009134141',1,0,'Re:OS Machine is started','Go ahead!',20011009134219);
INSERT INTO comments VALUES (3,'helix','General','0',1,0,'General Thinking','It\'s nice!',20011009134338);
INSERT INTO comments VALUES (5,'nilix','Specifications','1',1,0,'Comment on Specification #1','text',20011009170012);

#
# Table structure for table 'configure'
#

CREATE TABLE configure (
  proid bigint(20) unsigned NOT NULL default '0',
  quorum int(2) unsigned default NULL,
  consultants char(3) default NULL,
  other_tech_contents char(3) default NULL,
  other_developing_proposals char(3) default NULL,
  sponsor varchar(16) default NULL,
  developer varchar(16) default NULL
) TYPE=MyISAM;

#
# Dumping data for table 'configure'
#

INSERT INTO configure VALUES (1,90,'No','No','No','sponsor','devel');
INSERT INTO configure VALUES (2,75,'No','Yes','Yes','sponsor','');
INSERT INTO configure VALUES (4,70,'No','No','No','sponsor','devel');
INSERT INTO configure VALUES (3,60,'No','No','No','nilix','helix');
INSERT INTO configure VALUES (5,60,'Yes','Yes','Yes','nilix','helix');
INSERT INTO configure VALUES (6,80,'No','No','Yes','sponsor','devel');

#
# Table structure for table 'consultants'
#

CREATE TABLE consultants (
  proid bigint(20) unsigned NOT NULL default '0',
  consultant varchar(16) NOT NULL default '',
  status char(1) NOT NULL default '',
  creation timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'consultants'
#

INSERT INTO consultants VALUES (5,'helix','A',20011009151935);

#
# Table structure for table 'cooperation'
#

CREATE TABLE cooperation (
  devid bigint(20) unsigned NOT NULL default '0',
  developer varchar(16) NOT NULL default '',
  cost int(8) NOT NULL default '0',
  status char(1) NOT NULL default '',
  creation timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'cooperation'
#


#
# Table structure for table 'counter'
#

CREATE TABLE counter (
  proid bigint(20) unsigned NOT NULL default '0',
  pro_cnt int(11) NOT NULL default '0',
  docs_cnt int(11) NOT NULL default '0',
  PRIMARY KEY (proid)
) TYPE=MyISAM;

#
# Dumping data for table 'counter'
#

INSERT INTO counter VALUES (1,0,0);
INSERT INTO counter VALUES (2,0,0);
INSERT INTO counter VALUES (3,0,0);
INSERT INTO counter VALUES (4,0,0);
INSERT INTO counter VALUES (5,0,0);
INSERT INTO counter VALUES (6,0,0);

#
# Table structure for table 'counter_check'
#

CREATE TABLE counter_check (
  proid bigint(20) unsigned NOT NULL default '0',
  cnt_type varchar(20) NOT NULL default '',
  ipaddr varchar(15) NOT NULL default '127.000.000.001',
  creation_cnt timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'counter_check'
#


#
# Table structure for table 'decisions'
#

CREATE TABLE decisions (
  proid bigint(20) unsigned NOT NULL default '0',
  step int(8) unsigned NOT NULL default '0',
  decision_user varchar(16) NOT NULL default '',
  decision varchar(16) NOT NULL default ''
) TYPE=MyISAM;

#
# Dumping data for table 'decisions'
#

INSERT INTO decisions VALUES (5,1,'nilix','helix');
INSERT INTO decisions VALUES (5,2,'nilix','1');
INSERT INTO decisions VALUES (5,3,'nilix','1');
INSERT INTO decisions VALUES (4,2,'sponsor','3');
INSERT INTO decisions VALUES (6,2,'sponsor','4');
INSERT INTO decisions VALUES (6,3,'sponsor','3');
INSERT INTO decisions VALUES (6,4,'sponsor','helix');
INSERT INTO decisions VALUES (6,4,'devel','helix');

#
# Table structure for table 'decisions_milestones'
#

CREATE TABLE decisions_milestones (
  proid bigint(20) unsigned NOT NULL default '0',
  devid bigint(20) unsigned NOT NULL default '0',
  decision_user varchar(16) NOT NULL default '',
  number int(8) NOT NULL default '0',
  decision varchar(16) NOT NULL default ''
) TYPE=MyISAM;

#
# Dumping data for table 'decisions_milestones'
#

INSERT INTO decisions_milestones VALUES (5,1,'nilix',1,'Yes');
INSERT INTO decisions_milestones VALUES (5,1,'nilix',2,'Yes');
INSERT INTO decisions_milestones VALUES (5,1,'nilix',3,'Yes');
INSERT INTO decisions_milestones VALUES (5,1,'nilix',4,'Yes');
INSERT INTO decisions_milestones VALUES (6,3,'sponsor',1,'Yes');
INSERT INTO decisions_milestones VALUES (6,3,'sponsor',2,'Yes');
INSERT INTO decisions_milestones VALUES (6,3,'sponsor',3,'Yes');
INSERT INTO decisions_milestones VALUES (6,3,'sponsor',4,'Yes');

#
# Table structure for table 'decisions_step5'
#

CREATE TABLE decisions_step5 (
  proid bigint(20) unsigned NOT NULL default '0',
  number int(8) unsigned NOT NULL default '0',
  time int(3) unsigned NOT NULL default '0',
  decision_user varchar(16) NOT NULL default '',
  decision varchar(16) NOT NULL default ''
) TYPE=MyISAM;

#
# Dumping data for table 'decisions_step5'
#

INSERT INTO decisions_step5 VALUES (6,1,1,'sponsor','accept');
INSERT INTO decisions_step5 VALUES (6,2,1,'sponsor','light');
INSERT INTO decisions_step5 VALUES (6,2,2,'sponsor','light');
INSERT INTO decisions_step5 VALUES (6,2,4,'sponsor','severe');
INSERT INTO decisions_step5 VALUES (6,2,5,'sponsor','severe');
INSERT INTO decisions_step5 VALUES (6,3,1,'sponsor','severe');

#
# Table structure for table 'description'
#

CREATE TABLE description (
  proid bigint(20) unsigned NOT NULL auto_increment,
  project_title varchar(128) NOT NULL default '',
  type varchar(16) NOT NULL default '',
  description blob NOT NULL,
  description_user varchar(16) NOT NULL default '',
  volume varchar(16) NOT NULL default '',
  status int(1) NOT NULL default '0',
  description_creation timestamp(14) NOT NULL,
  PRIMARY KEY (proid)
) TYPE=MyISAM;

#
# Dumping data for table 'description'
#

INSERT INTO description VALUES (1,'Developing project','Documentation','Developing project','devel','Regular',2,20011008202340);
INSERT INTO description VALUES (2,'Sponsored project','Development','Sponsored project','sponsor','Very Small',1,20011008211238);
INSERT INTO description VALUES (3,'OS Machine','Development','A Machine to develop Open Source','helix','Regular',2,20011009131944);
INSERT INTO description VALUES (4,'Aaaaaaaaaaaaaah','Adaption','Aaaaaaaaaaaaaah','devel','Very Small',3,20011009193816);
INSERT INTO description VALUES (5,'Thinking Machine','Expansion','Enhance the existing thinking machine.','nilix','Big',4,20011009142343);
INSERT INTO description VALUES (6,'Lets hack on step 5','Expansion','This is a project for hacking specifically on step 5.','sponsor','Very Small',5,20011010182504);

#
# Table structure for table 'developing'
#

CREATE TABLE developing (
  devid bigint(20) unsigned NOT NULL auto_increment,
  proid bigint(20) unsigned NOT NULL default '0',
  content_id bigint(20) unsigned NOT NULL default '0',
  developer varchar(16) NOT NULL default '',
  cost int(8) NOT NULL default '0',
  license varchar(64) NOT NULL default '',
  status char(1) NOT NULL default '',
  cooperation varchar(64) NOT NULL default '',
  valid bigint(14) NOT NULL default '0',
  start bigint(14) NOT NULL default '0',
  duration int(4) NOT NULL default '0',
  creation timestamp(14) NOT NULL,
  PRIMARY KEY (devid)
) TYPE=MyISAM;

#
# Dumping data for table 'developing'
#

INSERT INTO developing VALUES (1,5,1,'helix',8000,'The GNU General Public License (GPL)','A','No',20011201120000,20020101120000,61,20011009171917);
INSERT INTO developing VALUES (2,4,3,'devel',1234,'Public Domain','R','No',20010101120000,20010101120000,1,20011009195016);
INSERT INTO developing VALUES (3,6,4,'devel',12,'The GNU General Public License (GPL)','A','No',20041231120000,20011010120000,1,20011010183231);

#
# Table structure for table 'faq'
#

CREATE TABLE faq (
  faqid int(8) unsigned NOT NULL auto_increment,
  language varchar(24) NOT NULL default '',
  question blob NOT NULL,
  answer blob NOT NULL,
  PRIMARY KEY (faqid)
) TYPE=MyISAM;

#
# Dumping data for table 'faq'
#

INSERT INTO faq VALUES (1,'English','How to change my Password or E-mail address I am registered with?','Select \"<a href=\"chguser.php3\">Change User</a>\" and enter your new parameters.');
INSERT INTO faq VALUES (2,'English','Why is the system not in my language?','This system can be easily translated into different languages. If you see that we do not have support in your language, you\'re gladly invited to help us with the internationalization. Visit <A HREF=\"http://sourceagency.berlios.de/html/translating.php3\">http://sourceagency.berlios.de/html/translating.php3</A>.');

#
# Table structure for table 'follow_up'
#

CREATE TABLE follow_up (
  proid bigint(20) unsigned NOT NULL default '0',
  milestone_number int(8) unsigned NOT NULL default '0',
  iteration int(8) NOT NULL default '1',
  location varchar(255) NOT NULL default '',
  time int(3) NOT NULL default '1'
) TYPE=MyISAM;

#
# Dumping data for table 'follow_up'
#

INSERT INTO follow_up VALUES (6,1,5,'http://www.localhost.com',2);
INSERT INTO follow_up VALUES (6,2,5,'http://www.hiholetsgo.com',5);
INSERT INTO follow_up VALUES (6,3,3,'http://localhost/',1);

#
# Table structure for table 'history'
#

CREATE TABLE history (
  proid bigint(20) unsigned NOT NULL default '0',
  history_user varchar(16) NOT NULL default '',
  type varchar(16) NOT NULL default '',
  action varchar(255) NOT NULL default '',
  creation timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'history'
#

INSERT INTO history VALUES (1,'devel','Configure','Project configuration',20011008202327);
INSERT INTO history VALUES (1,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011008202340);
INSERT INTO history VALUES (1,'sponsor','Configure','Project configuration modified',20011008202537);
INSERT INTO history VALUES (1,'sponsor','sponsoring','Sponsor nilix accepted as sponsor',20011008202618);
INSERT INTO history VALUES (2,'sponsor','Configure','Project configuration',20011008211205);
INSERT INTO history VALUES (2,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011008211238);
INSERT INTO history VALUES (4,'devel','Configure','Project configuration',20011009131612);
INSERT INTO history VALUES (3,'helix','Configure','Project configuration',20011009131823);
INSERT INTO history VALUES (3,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011009131944);
INSERT INTO history VALUES (3,'nilix','Configure','Project configuration modified',20011009135216);
INSERT INTO history VALUES (5,'nilix','Configure','Project configuration',20011009141843);
INSERT INTO history VALUES (5,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011009142343);
INSERT INTO history VALUES (5,'Project Owners','decision','Project is now in phase 2',20011009152131);
INSERT INTO history VALUES (5,'Project Owners','decision','Project is now in phase 3',20011009172417);
INSERT INTO history VALUES (5,'Project Owners','decision','Project is now in phase 4',20011009174405);
INSERT INTO history VALUES (5,'Project Owners','decision','Project is now in phase 5',20011009174422);
INSERT INTO history VALUES (4,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011009193816);
INSERT INTO history VALUES (4,'sponsor','Configure','Project configuration modified',20011009194833);
INSERT INTO history VALUES (4,'Project Owners','decision','Project is now in phase 3',20011009195311);
INSERT INTO history VALUES (4,'Project Owners','decision','Project is now in phase 3',20011009200822);
INSERT INTO history VALUES (4,'Project Owners','decision','Project is now in phase 4',20011009200826);
INSERT INTO history VALUES (4,'Project Owners','decision','Project is now in phase 3',20011009201006);
INSERT INTO history VALUES (4,'Project Owners','decision','Project is now in phase 4',20011009201013);
INSERT INTO history VALUES (4,'Project Owners','decision','Project is now in phase 3',20011009201458);
INSERT INTO history VALUES (4,'Project Owners','decision','Project is now in phase 3',20011009201902);
INSERT INTO history VALUES (6,'sponsor','Configure','Project configuration',20011010182409);
INSERT INTO history VALUES (6,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011010182504);
INSERT INTO history VALUES (6,'Project Owners','decision','Project is now in phase 2',20011010182806);
INSERT INTO history VALUES (6,'Project Owners','decision','Project is now in phase 3',20011010183253);
INSERT INTO history VALUES (6,'Project Owners','decision','Project is now in phase 4',20011010184537);
INSERT INTO history VALUES (6,'Project Owners','decision','Project is now in phase 5',20011010184634);

#
# Table structure for table 'involved'
#

CREATE TABLE involved (
  proid bigint(20) unsigned NOT NULL default '0',
  involved_sponsor varchar(16) NOT NULL default '',
  involved_text blob NOT NULL,
  max_sum_of_money int(8) default NULL,
  interest int(1) default NULL,
  status char(1) NOT NULL default '',
  creation_involved timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'involved'
#


#
# Table structure for table 'licenses'
#

CREATE TABLE licenses (
  license varchar(64) NOT NULL default '',
  url varchar(255) NOT NULL default ''
) TYPE=MyISAM;

#
# Dumping data for table 'licenses'
#

INSERT INTO licenses VALUES ('The GNU General Public License (GPL)','http://www.gnu.org/copyleft/gpl.html');
INSERT INTO licenses VALUES ('The GNU Library or Lesser Public License (LGPL)','http://www.gnu.org/copyleft/lesser.html');
INSERT INTO licenses VALUES ('The BSD license','http://www.freebsd.org/copyright/license.html');
INSERT INTO licenses VALUES ('The MIT license','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Artistic license','http://www.perl.com/language/misc/Artistic.html');
INSERT INTO licenses VALUES ('The Mozilla Public License v. 1.0 (MPL)','http://www.mozilla.org/MPL/');
INSERT INTO licenses VALUES ('The Qt Public License (QPL)','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The IBM Public License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The MITRE Collaborative Virtual Workspace License (CVW License)','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Python License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Apache Software License','http://www.apache.org/docs-2.0/LICENSE');
INSERT INTO licenses VALUES ('The Vovida Software License v. 1.0','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Sun Internet Standards Source License (SISSL)','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Intel Open Source License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Mozilla Public License v. 1.1 (MPL 1.1)','http://www.mozilla.org/MPL/');
INSERT INTO licenses VALUES ('The Jabber Open Source License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Nokia Open Source License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Sleepy Cat License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Nethack General Public License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Common Public License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('The Apple Public Source License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ('Public Domain','http://www.eiffel-forum.org/license/index.htm#pd');

#
# Table structure for table 'milestones'
#

CREATE TABLE milestones (
  proid bigint(20) unsigned NOT NULL default '0',
  devid bigint(20) unsigned NOT NULL default '0',
  number int(8) NOT NULL default '0',
  goals blob NOT NULL,
  release timestamp(14) NOT NULL,
  product varchar(128) default NULL,
  payment bigint(20) unsigned NOT NULL default '0',
  status char(1) NOT NULL default '',
  creation timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'milestones'
#

INSERT INTO milestones VALUES (5,1,1,'First Module',20020201120000,'Alpha',20,'A',20011009173328);
INSERT INTO milestones VALUES (5,1,2,'Module 2',20020301120000,'Alpha',10,'A',20011009173518);
INSERT INTO milestones VALUES (5,1,3,'Module 3',20020401120000,'Beta',20,'A',20011009173551);
INSERT INTO milestones VALUES (5,1,4,'Module 4',20020801120000,'Release Candidate 1',50,'A',20011009173641);
INSERT INTO milestones VALUES (6,3,1,'Design',20011010120000,'Developing Version',22,'A',20011010183527);
INSERT INTO milestones VALUES (6,3,2,'First implementation',20011011120000,'Alpha',30,'A',20011010183849);
INSERT INTO milestones VALUES (6,3,3,'Implementation of the decisions made by the referees',20011011120000,'Beta',30,'A',20011010184251);
INSERT INTO milestones VALUES (6,3,4,'Beta-testing and watching that everything is ok',20011012120000,'Release Candidate',18,'A',20011010184322);

#
# Table structure for table 'monitor'
#

CREATE TABLE monitor (
  proid bigint(20) unsigned NOT NULL default '0',
  username varchar(16) NOT NULL default '',
  importance varchar(16) NOT NULL default '',
  creation timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'monitor'
#


#
# Table structure for table 'news'
#

CREATE TABLE news (
  proid bigint(20) unsigned NOT NULL default '0',
  user_news varchar(16) NOT NULL default '',
  subject_news varchar(128) NOT NULL default '',
  text_news blob NOT NULL,
  creation_news timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'news'
#

INSERT INTO news VALUES (3,'helix','OS Machine is started','The project is started today.',20011009134141);

#
# Table structure for table 'ratings'
#

CREATE TABLE ratings (
  proid bigint(20) unsigned NOT NULL default '0',
  to_whom varchar(16) NOT NULL default '',
  by_whom varchar(16) NOT NULL default '',
  rating int(1) unsigned NOT NULL default '0',
  on_what varchar(24) NOT NULL default '',
  project_importance varchar(16) NOT NULL default '',
  creation timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'ratings'
#


#
# Table structure for table 'referees'
#

CREATE TABLE referees (
  proid bigint(20) unsigned NOT NULL default '0',
  referee varchar(16) NOT NULL default '',
  status char(1) NOT NULL default '',
  creation timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'referees'
#

INSERT INTO referees VALUES (6,'helix','A',20011010184559);

#
# Table structure for table 'sponsoring'
#

CREATE TABLE sponsoring (
  spoid bigint(20) unsigned NOT NULL auto_increment,
  proid bigint(20) unsigned NOT NULL default '0',
  sponsor varchar(16) NOT NULL default '',
  budget int(8) NOT NULL default '0',
  status char(1) NOT NULL default '',
  sponsoring_text blob,
  valid bigint(14) default NULL,
  begin bigint(14) default NULL,
  finish bigint(14) default NULL,
  creation timestamp(14) NOT NULL,
  PRIMARY KEY (spoid)
) TYPE=MyISAM;

#
# Dumping data for table 'sponsoring'
#

INSERT INTO sponsoring VALUES (1,1,'sponsor',234,'A','',20010101120000,20010101120000,20010101120000,20011008202532);
INSERT INTO sponsoring VALUES (2,1,'nilix',234,'A','',20010101120000,20010101120000,20010101120000,20011008202604);
INSERT INTO sponsoring VALUES (3,2,'sponsor',2000,'A','',20040604120000,20030102120000,20040704120000,20011008211226);
INSERT INTO sponsoring VALUES (4,3,'nilix',2000,'A','Let\' do it!',20011201120000,20011201120000,20020101120000,20011009134940);
INSERT INTO sponsoring VALUES (5,5,'nilix',5000,'A','Hope, it will start at the beginning of 2002.',20011001120000,20011201120000,20030101120000,20011009142139);
INSERT INTO sponsoring VALUES (6,4,'sponsor',123,'A','',20010101120000,20010101120000,20010101120000,20011009194827);
INSERT INTO sponsoring VALUES (7,6,'sponsor',10,'A','',20041231120000,20011010120000,20011012120000,20011010182452);

#
# Table structure for table 'tech_content'
#

CREATE TABLE tech_content (
  content_id bigint(20) unsigned NOT NULL auto_increment,
  proid bigint(20) unsigned NOT NULL default '0',
  skills varchar(64) default NULL,
  platform varchar(64) default NULL,
  architecture varchar(64) default NULL,
  environment varchar(64) default NULL,
  docs varchar(255) default NULL,
  specification blob,
  content_user varchar(16) NOT NULL default '',
  status char(1) NOT NULL default '',
  creation timestamp(14) NOT NULL,
  PRIMARY KEY (content_id)
) TYPE=MyISAM;

#
# Dumping data for table 'tech_content'
#

INSERT INTO tech_content VALUES (1,5,'C++, Perl,HTML','Platform 2','Architecture 2','Environment 2','http://www','Specification 1 of Thinking Machine','nilix','A',20011009165414);
INSERT INTO tech_content VALUES (2,5,'C, Python, HTML','Platform 1','Architecture 1','Environment 1','http://www','Spec2','nilix','R',20011009165933);
INSERT INTO tech_content VALUES (3,4,'c','Platform 1','Architecture 1','Environment 1','','Blah, blah, blah','devel','A',20011009194954);
INSERT INTO tech_content VALUES (4,6,'PHP, PHPLib, MySQL, BerliOS platform','Platform 4','Architecture 4','Environment 4','','Step 5 is the step where the controlling takes place. Therefore SourceAgency will offer a way that enables developers, sponsors and referees interact with each other.','sponsor','A',20011010183031);

#
# Table structure for table 'views'
#

CREATE TABLE views (
  proid bigint(20) unsigned NOT NULL default '0',
  configure varchar(24) NOT NULL default 'Project Participants',
  views varchar(24) NOT NULL default 'Project Participants',
  news varchar(24) NOT NULL default 'Project Initiator',
  comments varchar(24) NOT NULL default 'Registered',
  history varchar(24) NOT NULL default 'Everybody',
  step3 varchar(24) NOT NULL default 'Everybody',
  step4 varchar(24) NOT NULL default 'Everybody',
  step5 varchar(24) NOT NULL default 'Everybody',
  cooperation varchar(24) NOT NULL default 'Everybody',
  creation timestamp(14) NOT NULL
) TYPE=MyISAM;

#
# Dumping data for table 'views'
#

INSERT INTO views VALUES (1,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011008202321);
INSERT INTO views VALUES (2,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011008211158);
INSERT INTO views VALUES (3,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011009130159);
INSERT INTO views VALUES (4,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011009131534);
INSERT INTO views VALUES (5,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011009141820);
INSERT INTO views VALUES (6,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011010182400);

