# MySQL dump 8.16
#
# Host: db.berlios.de    Database: sourceagency
#--------------------------------------------------------
# Server version	3.23.37-log

#
# Table structure for table 'active_sessions'
#

CREATE TABLE active_sessions (
  sid varchar(32) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  val text,
  changed varchar(14) NOT NULL default '',
  PRIMARY KEY  (name,sid),
  KEY changed (changed)
) TYPE=MyISAM;

#
# Dumping data for table 'active_sessions'
#

INSERT INTO active_sessions VALUES ('5a44a4b8555c7e4ed727159f35bb5aae','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20020403150348');
INSERT INTO active_sessions VALUES ('edfddc324325d97ce551e2d035b6ab18','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20020403210946');
INSERT INTO active_sessions VALUES ('9c1122079fc91a22f035486ec6e2d9e9','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20020405191922');
INSERT INTO active_sessions VALUES ('80e9ddbe28c03e7c2d8ecd2981c94812','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20020408125544');
INSERT INTO active_sessions VALUES ('839c8017ccc9c72e53b75bb679c1d676','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20020408140556');
INSERT INTO active_sessions VALUES ('887bdd5c57c57bb07100e8ae00e97584','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnYzhhMTc0ZTBiZGRhMjAxMWZmNzk4YjIwZjIxOWFkYzUnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydwZXJtJ10gPSAnZWRpdG9yLGFkbWluJzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsnZXhwJ10gPSAnMTAxNzc2Mjc2Nic7ICRHTE9CQUxTWydhdXRoJ10tPmF1dGhbJ3JlZnJlc2gnXSA9ICcxMDE3NzQ0NzM2JzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndW5hbWUnXSA9ICdhZG1pbic7IA==','20020402152246');
INSERT INTO active_sessions VALUES ('c3f8a400479880cc6ab43b0002c467d0','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnZm9ybSc7ICRHTE9CQUxTWydhdXRoJ10tPmF1dGhbJ3Blcm0nXSA9ICcnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydleHAnXSA9ICcyMTQ3NDgzNjQ3JzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsncmVmcmVzaCddID0gJzIxNDc0ODM2NDcnOyA=','20020402161702');
INSERT INTO active_sessions VALUES ('fc4c812c74dc00bc890d6bf8a3d7c55e','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ1Vua25vd24nOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnYzhhMTc0ZTBiZGRhMjAxMWZmNzk4YjIwZjIxOWFkYzUnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydwZXJtJ10gPSAnZWRpdG9yLGFkbWluJzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsnZXhwJ10gPSAnMTAxNzgzOTAyNCc7ICRHTE9CQUxTWydhdXRoJ10tPmF1dGhbJ3JlZnJlc2gnXSA9ICcxMDE3ODI4NTIwJzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndW5hbWUnXSA9ICdhZG1pbic7IA==','20020403123344');

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
  PRIMARY KEY  (user_id),
  UNIQUE KEY k_username (username)
) TYPE=MyISAM;

#
# Dumping data for table 'auth_user'
#

INSERT INTO auth_user VALUES ('c8a174e0bdda2011ff798b20f219adc5','admin','admin','admin','grex@scouts-es.org',20010419103000,20010419103000,'editor,admin');
INSERT INTO auth_user VALUES ('42b3cdc7658ed6b3e07b9441c7679b28','devel','devel','devel','devel@scouts-es.org',20010426182520,20010426182520,'devel');
INSERT INTO auth_user VALUES ('740156f449ebc5950546517021fda49d','sponsor','sponsor','sponsor','sponsor@scouts-es.org',20010426182533,20010426182533,'sponsor');
INSERT INTO auth_user VALUES ('f4c756dfc8e55a131d2a7d4be9e3e11b','riessen','fu23bar','Gerrit Riessen','gerrit.riessen@web.de',20020326143032,20011004150815,'devel');
INSERT INTO auth_user VALUES ('35b3db48944dba7a4e272926fd0d2839','helix','helix%sa','Lutz Henckel','lutz.henckel@fokus.gmd.de',20011005122316,20011005111603,'devel');
INSERT INTO auth_user VALUES ('48db7ac02e974648e3454c02021b632a','nilix','nilix%sa','Lutz Henckel','lutz.henckel@fokus.gmd.de',20011005180845,20011005180845,'sponsor');
INSERT INTO auth_user VALUES ('25ed018734decceec7943589910e6d84','sponsort3','sponsor','Mr Sponsor 3','riessen@fokus.gmd.de',20020328155148,20020328155124,'sponsor');
INSERT INTO auth_user VALUES ('45c56e3ed2c3c4dc023e70a86f8ffddc','sponsor2','sponsor','Sponsor, Mr.','riessen@fokus.fhg.de',20020325142248,20020325142157,'sponsor');

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
INSERT INTO comments VALUES (8,'admin','General','0',1,0,'ist das auch erstrebenswert ?','anspannung kann durchaus produktiv sein.',20011120171835);
INSERT INTO comments VALUES (8,'admin','General','0',2,0,'klingt sehr ineressant','',20011120171952);
INSERT INTO comments VALUES (8,'admin','News','20011120171701',1,0,'Re:erste Erfolge erziehlt','ich bin der Meinung das heisst erzielt.',20011120172226);

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
INSERT INTO configure VALUES (7,80,'No','Yes','Yes','sponsor','riessen');
INSERT INTO configure VALUES (9,55,'Yes','Yes','Yes','sponsor','');
INSERT INTO configure VALUES (8,NULL,'No','Yes','',NULL,'admin');
INSERT INTO configure VALUES (10,NULL,'No','No','',NULL,'admin');
INSERT INTO configure VALUES (12,NULL,'No','Yes','Yes',NULL,'riessen');
INSERT INTO configure VALUES (13,NULL,'No','Yes','Yes',NULL,'riessen');
INSERT INTO configure VALUES (14,NULL,'No','Yes','Yes',NULL,'riessen');
INSERT INTO configure VALUES (15,NULL,'No','Yes','Yes',NULL,'riessen');
INSERT INTO configure VALUES (16,NULL,'No','Yes','Yes',NULL,'riessen');
INSERT INTO configure VALUES (19,NULL,'No','Yes','Yes',NULL,'admin');
INSERT INTO configure VALUES (20,NULL,'No','Yes','Yes',NULL,'admin');
INSERT INTO configure VALUES (21,NULL,'No','Yes','Yes',NULL,'riessen');
INSERT INTO configure VALUES (23,NULL,'No','Yes','Yes',NULL,'riessen');
INSERT INTO configure VALUES (24,55,'No','Yes','Yes','sponsor','admin');
INSERT INTO configure VALUES (25,55,'Yes','Yes','Yes','sponsor','');
INSERT INTO configure VALUES (26,NULL,'No','Yes','Yes',NULL,'riessen');

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
  PRIMARY KEY  (proid)
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
INSERT INTO counter VALUES (7,0,0);
INSERT INTO counter VALUES (8,0,0);
INSERT INTO counter VALUES (9,0,0);
INSERT INTO counter VALUES (10,0,0);
INSERT INTO counter VALUES (11,0,0);
INSERT INTO counter VALUES (12,0,0);
INSERT INTO counter VALUES (13,0,0);
INSERT INTO counter VALUES (14,0,0);
INSERT INTO counter VALUES (15,0,0);
INSERT INTO counter VALUES (16,0,0);
INSERT INTO counter VALUES (17,0,0);
INSERT INTO counter VALUES (18,0,0);
INSERT INTO counter VALUES (19,0,0);
INSERT INTO counter VALUES (20,0,0);
INSERT INTO counter VALUES (21,0,0);
INSERT INTO counter VALUES (22,0,0);
INSERT INTO counter VALUES (23,0,0);
INSERT INTO counter VALUES (24,0,0);
INSERT INTO counter VALUES (25,0,0);
INSERT INTO counter VALUES (26,0,0);

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
INSERT INTO decisions VALUES (7,2,'riessen','5');
INSERT INTO decisions VALUES (7,2,'sponsor','5');
INSERT INTO decisions VALUES (2,2,'sponsor','6');
INSERT INTO decisions VALUES (24,2,'sponsor','7');
INSERT INTO decisions VALUES (1,2,'sponsor','8');

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
  PRIMARY KEY  (proid)
) TYPE=MyISAM;

#
# Dumping data for table 'description'
#

INSERT INTO description VALUES (1,'Developing project','Documentation','Developing project','devel','Regular',2,20011008202340);
INSERT INTO description VALUES (2,'Sponsored project','Development','Sponsored project','sponsor','Very Small',3,20011008211238);
INSERT INTO description VALUES (3,'OS Machine','Development','A Machine to develop Open Source','helix','Regular',2,20011009131944);
INSERT INTO description VALUES (4,'Aaaaaaaaaaaaaah','Adaption','Aaaaaaaaaaaaaah','devel','Very Small',3,20011009193816);
INSERT INTO description VALUES (5,'Thinking Machine','Expansion','Enhance the existing thinking machine.','nilix','Big',4,20011009142343);
INSERT INTO description VALUES (6,'Lets hack on step 5','Expansion','This is a project for hacking specifically on step 5.','sponsor','Very Small',5,20011010182504);
INSERT INTO description VALUES (7,'Open Source Moon Lander','Expansion','We, at NASA, require an Open Source Moon lander.','riessen','< 1 Man Month',3,20011114152650);
INSERT INTO description VALUES (8,'immer locker bleiben','Adaption','hmmm','admin','> 6 Man Months',-1,20011126121204);
INSERT INTO description VALUES (9,'New Project','Adaption','No Description','sponsor','< 0.25 Man Month',1,20011126120650);
INSERT INTO description VALUES (10,'Yet Another Project','Adaption','no description','admin','< 2 Man Months',-1,20011126121202);
INSERT INTO description VALUES (11,'sadasd','Adaption','asdasd','admin','< 0.25 Man Month',-1,20011126124047);
INSERT INTO description VALUES (12,'sdasd','Adaption','asdasd','riessen','< 0.25 Man Month',-1,20011126124046);
INSERT INTO description VALUES (13,'sdasda','Adaption','sadasd','riessen','< 0.25 Man Month',-1,20011126124222);
INSERT INTO description VALUES (14,'saasf','Adaption','sdasd','riessen','< 0.25 Man Month',-1,20011126124733);
INSERT INTO description VALUES (15,'dasdadas','Adaption','asdsad','riessen','< 0.25 Man Month',-1,20011126130034);
INSERT INTO description VALUES (16,'assadasd','Adaption','asdasd','riessen','< 0.25 Man Month',-1,20011126130324);
INSERT INTO description VALUES (17,'asdsad','Adaption','ssdasdasd','riessen','< 0.25 Man Month',-1,20011213171559);
INSERT INTO description VALUES (18,'Test view table','Adaption','This is a test for the view table: can a projecct_initiator edit the configuration for their project when the configuration of a project can be viewed by all sponsors?','riessen','< 0.25 Man Month',-1,20020326145015);
INSERT INTO description VALUES (19,'View table test','Adaption','sddfsz','riessen','< 0.25 Man Month',-1,20020326171326);
INSERT INTO description VALUES (20,'Yet another projecct ...','Expansion','sdaddasd','riessen','< 0.25 Man Month',-1,20020326171325);
INSERT INTO description VALUES (21,'Yet Another project ....','Adaption','aasdasd','riessen','< 0.25 Man Month',-1,20020326171324);
INSERT INTO description VALUES (22,'dsfsadf','Adaption','adsfsdaf','riessen','< 0.25 Man Month',-1,20020326171323);
INSERT INTO description VALUES (23,'asdasdas','Adaption','asdasdasd','riessen','< 0.25 Man Month',-1,20020326171321);
INSERT INTO description VALUES (24,'Test of  the view attribute ...','Adaption','adsasd','sponsor','< 0.25 Man Month',3,20020327110123);
INSERT INTO description VALUES (25,'and again a test','Adaption','adsad','sponsor','< 0.25 Man Month',-1,20020327110121);
INSERT INTO description VALUES (26,'asdasd','Adaption','sadasd','riessen','< 0.25 Man Month',0,20020327133631);

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
  PRIMARY KEY  (devid)
) TYPE=MyISAM;

#
# Dumping data for table 'developing'
#

INSERT INTO developing VALUES (1,5,1,'helix',8000,'The GNU General Public License (GPL)','A','No',20011201120000,20020101120000,61,20011009171917);
INSERT INTO developing VALUES (2,4,3,'devel',1234,'Public Domain','A','No',20010101120000,20010101120000,1,20011122172051);
INSERT INTO developing VALUES (3,6,4,'devel',12,'The GNU General Public License (GPL)','A','No',20041231120000,20011010120000,1,20011010183231);
INSERT INTO developing VALUES (4,24,7,'admin',123,'The Common Public License','P','No',20010101120000,20010101120000,1,20020327121345);
INSERT INTO developing VALUES (5,1,8,'devel',2,'The Common Public License','P','No',20010101120000,20010101120000,1,20020327130234);

#
# Table structure for table 'faq'
#

CREATE TABLE faq (
  faqid int(8) unsigned NOT NULL auto_increment,
  language varchar(24) NOT NULL default '',
  question blob NOT NULL,
  answer blob NOT NULL,
  PRIMARY KEY  (faqid)
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
  count int(3) NOT NULL default '1'
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
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration',20011114141649);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011114141822);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011114142703);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011114143007);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011114144136);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011114144514);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011114144655);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011114144902);
INSERT INTO history VALUES (7,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011114152650);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011120154904);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011120160541);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011120160831);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011120161256);
INSERT INTO history VALUES (7,'riessen','Configure','Project configuration modified',20011120170356);
INSERT INTO history VALUES (7,'sponsor','Configure','Project configuration modified',20011120170937);
INSERT INTO history VALUES (9,'sponsor','Configure','Project configuration',20011126120547);
INSERT INTO history VALUES (9,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011126120650);
INSERT INTO history VALUES (8,'admin','Configure','Project configuration',20011126120752);
INSERT INTO history VALUES (10,'admin','Configure','Project configuration',20011126121015);
INSERT INTO history VALUES (10,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011126121202);
INSERT INTO history VALUES (8,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011126121204);
INSERT INTO history VALUES (12,'riessen','Configure','Project configuration',20011126124039);
INSERT INTO history VALUES (12,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011126124046);
INSERT INTO history VALUES (11,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011126124047);
INSERT INTO history VALUES (13,'riessen','Configure','Project configuration',20011126124057);
INSERT INTO history VALUES (13,'riessen','Configure','Project configuration modified',20011126124121);
INSERT INTO history VALUES (13,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011126124222);
INSERT INTO history VALUES (14,'riessen','Configure','Project configuration',20011126124726);
INSERT INTO history VALUES (14,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011126124733);
INSERT INTO history VALUES (15,'riessen','Configure','Project configuration',20011126124847);
INSERT INTO history VALUES (15,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20011126130034);
INSERT INTO history VALUES (16,'riessen','Configure','Project configuration',20011126130312);
INSERT INTO history VALUES (7,'sponsor','sponsoring','Sponsor riessen accepted as sponsor',20020319093444);
INSERT INTO history VALUES (7,'Project Owners','decision','Project is now in phase 3',20020319093513);
INSERT INTO history VALUES (2,'Project Owners','decision','Project is now in phase 2',20020320115809);
INSERT INTO history VALUES (2,'Project Owners','decision','Project is now in phase 3',20020320115855);
INSERT INTO history VALUES (7,'riessen','sponsoring','Sponsor riessen accepted as sponsor',20020325140953);
INSERT INTO history VALUES (7,'sponsor','sponsoring','Sponsor riessen accepted as sponsor',20020325141125);
INSERT INTO history VALUES (7,'riessen','sponsoring','Sponsor riessen accepted as sponsor',20020326141803);
INSERT INTO history VALUES (19,'riessen','Configure','Project configuration',20020326145117);
INSERT INTO history VALUES (20,'riessen','Configure','Project configuration',20020326145329);
INSERT INTO history VALUES (20,'admin','Configure','Project configuration modified',20020326161144);
INSERT INTO history VALUES (19,'admin','Configure','Project configuration modified',20020326161439);
INSERT INTO history VALUES (19,'admin','Configure','Project configuration modified',20020326161512);
INSERT INTO history VALUES (21,'riessen','Configure','Project configuration',20020326161634);
INSERT INTO history VALUES (23,'riessen','Configure','Project configuration',20020326171304);
INSERT INTO history VALUES (24,'sponsor','Configure','Project configuration',20020327105826);
INSERT INTO history VALUES (24,'sponsor','Configure','Project configuration modified',20020327105930);
INSERT INTO history VALUES (24,'admin','Configure','Project configuration modified',20020327105946);
INSERT INTO history VALUES (25,'sponsor','Configure','Project configuration',20020327110023);
INSERT INTO history VALUES (24,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20020327110123);
INSERT INTO history VALUES (24,'sponsor','Configure','Project configuration modified',20020327121203);
INSERT INTO history VALUES (24,'Project Owners','decision','Project is now in phase 2',20020327121213);
INSERT INTO history VALUES (24,'Project Owners','decision','Project is now in phase 3',20020327121627);
INSERT INTO history VALUES (26,'riessen','Configure','Project configuration',20020327133637);

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
INSERT INTO licenses VALUES ('The MIT license','licnotavailable.php3');
INSERT INTO licenses VALUES ('The Artistic license','http://www.perl.com/language/misc/Artistic.html');
INSERT INTO licenses VALUES ('The Mozilla Public License v. 1.0 (MPL)','http://www.mozilla.org/MPL/');
INSERT INTO licenses VALUES ('The Qt Public License (QPL)','licnotavailable.php3');
INSERT INTO licenses VALUES ('The IBM Public License','licnotavailable.php3');
INSERT INTO licenses VALUES ('The MITRE Collaborative Virtual Workspace License (CVW License)','licnotavailable.php3');
INSERT INTO licenses VALUES ('The Python License','licnotavailable.php3');
INSERT INTO licenses VALUES ('The Apache Software License','http://www.apache.org/docs-2.0/LICENSE');
INSERT INTO licenses VALUES ('The Vovida Software License v. 1.0','licnotavailable.php3');
INSERT INTO licenses VALUES ('The Sun Internet Standards Source License (SISSL)','licnotavailable.php3');
INSERT INTO licenses VALUES ('The Intel Open Source License','licnotavailable.php3');
INSERT INTO licenses VALUES ('The Mozilla Public License v. 1.1 (MPL 1.1)','http://www.mozilla.org/MPL/');
INSERT INTO licenses VALUES ('The Jabber Open Source License','http://docs.jabber.org/general/html/josl.html');
INSERT INTO licenses VALUES ('The Nokia Open Source License','licnotavailable.php3');
INSERT INTO licenses VALUES ('The Sleepy Cat License','licnotavailable.php3');
INSERT INTO licenses VALUES ('The Nethack General Public License','licnotavailable.php3');
INSERT INTO licenses VALUES ('The Common Public License','licnotavailable.php3');
INSERT INTO licenses VALUES ('The Apple Public Source License','licnotavailable.php3');
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
INSERT INTO milestones VALUES (7,0,1,'fubar and snafu',20020501120000,'Prototype',20,'P',20020319094443);
INSERT INTO milestones VALUES (7,0,2,'CeBIT besiuch',20030316120000,'Prototype',38,'P',20020319094547);
INSERT INTO milestones VALUES (7,0,3,'dasdaasdasd',20010601120000,'Prototype',42,'P',20020319094611);
INSERT INTO milestones VALUES (7,0,4,'sddasdasd',20010101120000,'Prototype',0,'P',20020319094624);
INSERT INTO milestones VALUES (7,0,5,'sadasdasd\r\nasdasd\r\n\r\nasd\r\nasd',20010101120000,'Beta',0,'P',20020319095233);
INSERT INTO milestones VALUES (7,0,5,'sadasdasd\r\nasdasd\r\n\r\nasd\r\nasd',20010101120000,'Beta',0,'P',20020319095630);
INSERT INTO milestones VALUES (7,0,5,'sadasdasd\r\nasdasd\r\n\r\nasd\r\nasd',20010101120000,'Beta',0,'P',20020319095639);
INSERT INTO milestones VALUES (7,0,5,'sadasdasd\r\nasdasd\r\n\r\nasd\r\nasd',20010101120000,'Beta',0,'P',20020319095709);
INSERT INTO milestones VALUES (7,0,6,'sdasdasdasasdasd',20010101120000,'Prototype',0,'P',20020319095952);
INSERT INTO milestones VALUES (7,0,7,'sadasdas',20010101120000,'Prototype',0,'P',20020319102049);
INSERT INTO milestones VALUES (7,0,8,'',20010101120000,'Prototype',0,'P',20020319102344);
INSERT INTO milestones VALUES (7,0,9,'sddasdasdas',20010101120000,'Prototype',0,'P',20020319110103);
INSERT INTO milestones VALUES (7,0,10,'',20010101120000,'Prototype',0,'P',20020319110539);
INSERT INTO milestones VALUES (7,0,11,'asdasdasd',20010101120000,'Prototype',0,'P',20020319111117);
INSERT INTO milestones VALUES (7,0,12,'asdasda',20010101120000,'Prototype',0,'P',20020319180925);
INSERT INTO milestones VALUES (7,0,13,'asdasdas',20010101120000,'Prototype',0,'P',20020320115530);
INSERT INTO milestones VALUES (4,2,1,'sdadasdsdasdasd',20010101120000,'Prototype',2,'P',20020320121105);
INSERT INTO milestones VALUES (7,0,14,'dfasdfsd',20010101120000,'Prototype',0,'P',20020320121307);
INSERT INTO milestones VALUES (7,0,15,'asdasd',20010101120000,'Prototype',0,'P',20020320121316);
INSERT INTO milestones VALUES (4,0,5,'sdfsdf',20010101120000,'Prototype',2,'P',20020320122329);
INSERT INTO milestones VALUES (4,0,6,'mjbbkjbjnm',20010101120000,'Prototype',2,'P',20020320125628);
INSERT INTO milestones VALUES (4,0,8,'kjjlkjnlk',20010101120000,'Prototype',2,'P',20020320125637);
INSERT INTO milestones VALUES (4,0,2,'adasd',20010101120000,'Prototype',90,'P',20020320125806);

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
INSERT INTO news VALUES (8,'admin','erste Erfolge erziehlt','war heute zwischenzeitlich locker',20011120171701);

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
  PRIMARY KEY  (spoid)
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
INSERT INTO sponsoring VALUES (8,7,'sponsor',12122123,'P','asdasd',20010101120000,20010101120000,20010101120000,20020328154755);
INSERT INTO sponsoring VALUES (9,9,'sponsor',12,'A','',20010101120000,20010101120000,20010101120000,20011126120603);
INSERT INTO sponsoring VALUES (10,7,'riessen',23,'A','',20010101120000,20010101120000,20010101120000,20020319093314);
INSERT INTO sponsoring VALUES (11,7,'riessen',0,'A','asdasd',20010101120000,20010101120000,20010101120000,20020319093314);
INSERT INTO sponsoring VALUES (12,7,'riessen',3234,'A','asdasd',20010101120000,20010101120000,20010101120000,20020319093314);
INSERT INTO sponsoring VALUES (13,9,'sponsor2',2323,'P','asdasd',20010101120000,20010101120000,20010101120000,20020325142650);
INSERT INTO sponsoring VALUES (14,7,'riessen',123,'A','asdasd',20010101120000,20010101120000,20010101120000,20020319093314);
INSERT INTO sponsoring VALUES (15,25,'sponsor',1213,'A','asasd',20010101120000,20010101120000,20010101120000,20020327110037);
INSERT INTO sponsoring VALUES (16,24,'sponsor',123445,'A','sada',20010101120000,20010101120000,20010101120000,20020327110101);
INSERT INTO sponsoring VALUES (17,7,'sponsor2',11223,'P','asdasd',20010101120000,20010101120000,20010101120000,20020328152926);

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
  PRIMARY KEY  (content_id)
) TYPE=MyISAM;

#
# Dumping data for table 'tech_content'
#

INSERT INTO tech_content VALUES (1,5,'C++, Perl,HTML','Platform 2','Architecture 2','Environment 2','http://www','Specification 1 of Thinking Machine','nilix','A',20011009165414);
INSERT INTO tech_content VALUES (2,5,'C, Python, HTML','Platform 1','Architecture 1','Environment 1','http://www','Spec2','nilix','R',20011009165933);
INSERT INTO tech_content VALUES (3,4,'c','Platform 1','Architecture 1','Environment 1','','Blah, blah, blah','devel','A',20011009194954);
INSERT INTO tech_content VALUES (4,6,'PHP, PHPLib, MySQL, BerliOS platform','Platform 4','Architecture 4','Environment 4','','Step 5 is the step where the controlling takes place. Therefore SourceAgency will offer a way that enables developers, sponsors and referees interact with each other.','sponsor','A',20011010183031);
INSERT INTO tech_content VALUES (5,7,'Can fly','Linux','x86','Environment 1','','Astro-bot','riessen','A',20011120170458);
INSERT INTO tech_content VALUES (6,2,'','Linux','x86','Web','','sdffsdf','sponsor','A',20020320115836);
INSERT INTO tech_content VALUES (7,24,'asdasd','Linux','x86','Web','adsad','sadas','sponsor','A',20020327121234);
INSERT INTO tech_content VALUES (8,1,'adssd','Linux','x86','Web','asdasd','sadasd','devel','P',20020327130218);

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
INSERT INTO views VALUES (7,'Sponsors','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020326144245);
INSERT INTO views VALUES (8,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011120171434);
INSERT INTO views VALUES (9,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011126120537);
INSERT INTO views VALUES (10,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Registered','Project Participants','Everybody','Everybody',20011126120950);
INSERT INTO views VALUES (11,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011126122834);
INSERT INTO views VALUES (12,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011126124035);
INSERT INTO views VALUES (13,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011126124054);
INSERT INTO views VALUES (14,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011126124707);
INSERT INTO views VALUES (15,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011126124844);
INSERT INTO views VALUES (16,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011126130309);
INSERT INTO views VALUES (17,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20011126130742);
INSERT INTO views VALUES (18,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020326144708);
INSERT INTO views VALUES (19,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020326145111);
INSERT INTO views VALUES (20,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020326145324);
INSERT INTO views VALUES (21,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020326161631);
INSERT INTO views VALUES (22,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020326161804);
INSERT INTO views VALUES (23,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020326165701);
INSERT INTO views VALUES (24,'Sponsors','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020327114156);
INSERT INTO views VALUES (25,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020327110020);
INSERT INTO views VALUES (26,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020327133631);

