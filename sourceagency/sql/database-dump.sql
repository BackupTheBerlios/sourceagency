# MySQL dump 8.16
#
# Host: db.berlios.de    Database: sourceagencybeta
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

INSERT INTO active_sessions VALUES ('4252de1a02044c9b2e104a4e1efd45e6','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20030305053052');
INSERT INTO active_sessions VALUES ('274ec3578ba3ee54cd9b0d0bf7157783','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20030304224736');
INSERT INTO active_sessions VALUES ('4a91959bbe360fdffafa586a605eaf13','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20030304145723');
INSERT INTO active_sessions VALUES ('390943dff6398d76f11a997599ae648b','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20030306002004');
INSERT INTO active_sessions VALUES ('968cf7ddf7bb199af12e812c1f53b98e','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20030305093247');
INSERT INTO active_sessions VALUES ('32d0cfb02d1fbdabec2c7d3e5262d1d7','SourceAgency_Session','U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==','20030305080113');

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

INSERT INTO auth_user VALUES ('c8a174e0bdda2011ff798b20f219adc5','admin','admin%sa','admin','grex@scouts-es.org',20020906123927,20010419103000,'editor,admin');
INSERT INTO auth_user VALUES ('42b3cdc7658ed6b3e07b9441c7679b28','devel','devel','devel','devel@scouts-es.org',20010426182520,20010426182520,'devel');
INSERT INTO auth_user VALUES ('740156f449ebc5950546517021fda49d','sponsor','sponsor','sponsor','sponsor@scouts-es.org',20010426182533,20010426182533,'sponsor');
INSERT INTO auth_user VALUES ('f4c756dfc8e55a131d2a7d4be9e3e11b','riessen','fu23bar','Gerrit Riessen','gerrit.riessen@web.de',20020326143032,20011004150815,'devel');
INSERT INTO auth_user VALUES ('35b3db48944dba7a4e272926fd0d2839','helix','helix%sa','Lutz Henckel','lutz.henckel@fokus.gmd.de',20011005122316,20011005111603,'devel');
INSERT INTO auth_user VALUES ('48db7ac02e974648e3454c02021b632a','nilix','nilix%sa','Lutz Henckel','lutz.henckel@fokus.gmd.de',20011005180845,20011005180845,'sponsor');
INSERT INTO auth_user VALUES ('25ed018734decceec7943589910e6d84','sponsort3','sponsor','Mr Sponsor 3','riessen@fokus.gmd.de',20020328155148,20020328155124,'sponsor');
INSERT INTO auth_user VALUES ('45c56e3ed2c3c4dc023e70a86f8ffddc','sponsor2','sponsor','Sponsor, Mr.','riessen@fokus.fhg.de',20020325142248,20020325142157,'sponsor');
INSERT INTO auth_user VALUES ('f185339982b21005cadfdf7b4bfdcec7','jovice','tuxedo42','Jovice King','jovice.king@msa.hinet.net',20020609151206,20020609151206,'devel_pending');
INSERT INTO auth_user VALUES ('8c953d0167764d54664ebac5262aef50','erik','leocentric','Erik Moeller','e.moeller@fokus.gmd.de',20021007152254,20021007152155,'devel');
INSERT INTO auth_user VALUES ('9dc71f404f6cdfd909f817a916cce0c3','ry4an','hiabba','Ry4an Brase','ry4an-sourceagency@ry4an.org',20021024175005,20021024174905,'devel');
INSERT INTO auth_user VALUES ('59d5292eedec08eba0159fb09d4ae730','ipul','sembahyang','ipul keren','rakyatjelata@gawab.com',20021117025744,20021117025744,'devel_pending');
INSERT INTO auth_user VALUES ('f6164ea184cb9caa0225d07a19fd7866','ghuebner','l1ndw0rm','Gerhard Hübner','info@huebner-technologie.de',20021117213320,20021117213200,'devel');
INSERT INTO auth_user VALUES ('aed300845364a899d397b0545438351e','srs','leocentric','Scientific Review Service','e.moeller@fokus.gmd.de',20021209111359,20021209103439,'sponsor');
INSERT INTO auth_user VALUES ('a849ad4dd77977398606613a27b9cdb8','alberisch','mariquita','alberich','alberich@gmx.at',20030102140717,20030102140645,'devel');
INSERT INTO auth_user VALUES ('d9e9b5e208e30cf2eb4345e3e9e4b566','alberich','mariquita','alberich','alberich@gmx.at',20030102141925,20030102141925,'devel_pending');
INSERT INTO auth_user VALUES ('7094c21cafefc230381bae7146e34ee6','308624857','308624857','Michael Pavlovsky','spavlov@t2.technion.ac.il',20030113150713,20030113150652,'devel');
INSERT INTO auth_user VALUES ('e756f0cd2615f604b8aa8ddbb0a341f1','GREEKMAILER','gaster1','GREEKMAILER','greekmailer@lycos.de',20030128212903,20030128212903,'devel_pending');
INSERT INTO auth_user VALUES ('0035888d0daa3eb4e3bb9a30dc3ff64f','platformer','pfl123','Jirka','contact@platformer.de',20030205174248,20030205174237,'devel');
INSERT INTO auth_user VALUES ('f21fea2357bf334e8749f2edb718f350','detrax','c6h12o6','Martin T. Heintze','mtheintze@gmx.net',20030212202845,20030212202835,'devel');
INSERT INTO auth_user VALUES ('2e15df500015a7e8e19d9b1bae53af70','Tatanka','alfalfa','Hannes','Tatanka-Yotanka@gmx.de',20030214020152,20030214020129,'devel');
INSERT INTO auth_user VALUES ('c79c8de5518212e79a7f31fe17a761d7','alexey0321','alexa45','Alexey','alexey0321@yandex.ru',20030217101525,20030217101449,'devel');
INSERT INTO auth_user VALUES ('a09109e3a7c3b482696589c60c316402','Micro','rudi66','Mirko Glotz','microg@gmx.net',20030218215857,20030218214607,'devel');

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
INSERT INTO comments VALUES (7,'riessen','General','0',1,0,'sasd','sadasd',20020424115101);
INSERT INTO comments VALUES (7,'riessen','News','20020424115123',1,0,'Re:ddddaa','zxZX',20020424115129);
INSERT INTO comments VALUES (5,'nilix','News','20020906144945',1,0,'Re:About the project status','fine',20020906145406);
INSERT INTO comments VALUES (5,'nilix','News','20020906144945',2,0,'Re:About the project status','fb rzkbtj',20020906145638);
INSERT INTO comments VALUES (5,'helix','News','20020906144945',3,1,'Re:Re:About the project status','gf ruiu w',20020910162400);
INSERT INTO comments VALUES (5,'helix','General','0',1,0,'fsu rz 35uesy w57zjs','fv twmets 46rsehfb rzdxuj w',20020910162537);
INSERT INTO comments VALUES (5,'helix','General','0',2,1,'Re:fsu rz 35uesy w57zjs','',20020910162555);

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
INSERT INTO configure VALUES (3,65,'No','No','No','nilix','helix');
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
INSERT INTO configure VALUES (27,NULL,'No','Yes','',NULL,'admin');
INSERT INTO configure VALUES (28,NULL,'No','Yes','Yes',NULL,'helix');
INSERT INTO configure VALUES (30,NULL,'No','Yes','Yes',NULL,'helix');
INSERT INTO configure VALUES (31,NULL,'No','Yes','Yes','nilix','erik');
INSERT INTO configure VALUES (32,NULL,'No','Yes','Yes',NULL,'308624857');
INSERT INTO configure VALUES (33,NULL,'No','No','No',NULL,'Tatanka');

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
INSERT INTO counter VALUES (27,0,0);
INSERT INTO counter VALUES (28,0,0);
INSERT INTO counter VALUES (29,0,0);
INSERT INTO counter VALUES (30,0,0);
INSERT INTO counter VALUES (31,0,0);
INSERT INTO counter VALUES (32,0,0);
INSERT INTO counter VALUES (33,0,0);

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
INSERT INTO decisions VALUES (31,2,'nilix','10');

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

INSERT INTO description VALUES (1,'Developing project','Documentation','Developing project','devel','< 2 Man Months',2,20011008202340);
INSERT INTO description VALUES (2,'Sponsored project','Development','Sponsored project','sponsor','< 0.25 Man Month',3,20011008211238);
INSERT INTO description VALUES (3,'OS Machine','Development','A Machine to develop Open Source','helix','< 6 Man Months',2,20011009131944);
INSERT INTO description VALUES (4,'Aaaaaaaaaaaaaah','Adaption','Aaaaaaaaaaaaaah','devel','< 0.25 Man Month',3,20011009193816);
INSERT INTO description VALUES (5,'Thinking Machine','Expansion','Enhance the existing thinking machine.','nilix','> 6 Man Months',4,20011009142343);
INSERT INTO description VALUES (6,'Lets hack on step 5','Expansion','This is a project for hacking specifically on step 5.','sponsor','< 0.25 Man Month',5,20011010182504);
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
INSERT INTO description VALUES (26,'asdasd','Adaption','sadasd','riessen','< 0.25 Man Month',-1,20020424124758);
INSERT INTO description VALUES (27,'DevCounter','Expansion','DevCounter befragt Open-Source-Entwickler nach ihren Kentnissen und Erfahrungen sowie der Mitarbeit an Open-Source-Projekten. Außerdem erlaubt DevCounter die zielgerichtete Suche nach Entwicklern mit definierten Know-How. Man kann dort weitere Entwickler für die eigenen Projekte suchen oder einfach jemand, der bei bestimmten Aufgaben kurz aushelfen kann, wie z.B. bei der Netzwerkprogrammierung, dem Schreiben von Dokumentation oder dem Erstellen von Übersetzungen.\r\n\r\nEntwickler, die Projekte suchen und helfen wollen, können sich bei DevCounters unter Angaben ihrer Kenntnisse eintragen.','admin','< 1 Man Month',0,20020906130357);
INSERT INTO description VALUES (28,'A New Project','Development','A very new program','helix','> 6 Man Months',0,20020907170611);
INSERT INTO description VALUES (29,'pppp','Development','ggb aezztzbznjhesh','helix','< 6 Man Months',0,20020908000112);
INSERT INTO description VALUES (30,'qqqq','Adaption','rusr tejn 73k zu','helix','< 0.25 Man Month',0,20020908000413);
INSERT INTO description VALUES (31,'Lokalisierung des Scoop CMS','Adaption','Das Content Management System (CMS) Scoop muss für die Übersetzung in verschiedene Sprachen angepasst werden. Hauptproblem sind existierende Text-Blöcke im Quellcode, die in die Datenbank oder evtl., falls es sich um kürzere Textketten handelt, in eine Textdatei verschoben werden müssen.\r\n\r\nWeiterhin muss es einem Benutzer möglich sein, eine Sprachpräferenz auszuwählen. Anonyme Nutzer sollten das System in der in ihrem Browser eingestellten Sprache sehen. \r\n\r\nEine deutsche Übersetzung von Scoop ist Teil dieses Projekts. Fehlermeldungen bzw. Warnhinweise, die im Apache-Error-Log gespeichert werden, müssen nicht übersetzt werden.\r\n\r\nEs wäre u.U. wünschenswert, auch für von Nutzern beigesteuerte Kommentare und Stories ein Sprachattribut zu verwalten und Nutzern und Administratoren zu gestatten, Sprachfilter zu definieren. Dies ist jedoch kein unbedingt/sofort notwendiges Feature.','erik','< 2 Man Months',2,20021106151536);
INSERT INTO description VALUES (32,'Reflective memory','Expansion','There are many problems facing the design of a good distributed file system. Transporting many files over the net can easily create sluggish performance and latency, network bottlenecks and server overload can result. The security of data is another important issue: how can we be sure that a client is really authorized to have access to information and how can we prevent data being sniffed off the network? Two further problems facing the design are related to failures. Often client computers are more reliable than the network connecting them and network failures can render a client useless. Similarly a server failure can be very unpleasant, since it can disable all clients from accessing crucial information. This project has paid attention to many of these issues and implemented them as a research prototype.','308624857','< 6 Man Months',0,20030113151547);
INSERT INTO description VALUES (33,'Collaboration Systems','Documentation','There is a need to deploy a collaborating system. First to do to evaluate the existing solutions. Non public use included.','Tatanka','< 0.25 Man Month',0,20030214020544);

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
INSERT INTO developing VALUES (6,31,10,'erik',500,'The GNU General Public License (GPL)','P','No',20030101120000,20021110120000,4,20021120113108);

#
# Table structure for table 'doco'
#

CREATE TABLE doco (
  docoid int(8) unsigned NOT NULL auto_increment,
  language varchar(24) NOT NULL default '',
  page varchar(64) NOT NULL default '',
  header varchar(255) NOT NULL default '',
  doco blob NOT NULL,
  PRIMARY KEY  (docoid)
) TYPE=MyISAM;

#
# Dumping data for table 'doco'
#

INSERT INTO doco VALUES (1,'English','index','SourceAgency: Front Page','SourceAgency is an open source project planning and exchange portal. It is the place where Open Source Software (OSS) obtains financial support and OSS developers have the chance to get paid for their work. The short version:\r\n<P>\r\n<UL><LI>Project sponsors describe their needs. Software developers describe the open source projects they are working on. New sponsors and developers can join existing projects.\r\n<LI>If desired, project consultants are invited. Technical specifications and implementation proposals are submitted by developers or sponsors.\r\n<LI>Milestones are proposed by developers to measure the development process. \r\n<LI>Developers whose milestone suggestions have been accepted take part in the implementation: a contract is made. A referee is appointed to decide whether milestones have been completed.\r\n<LI>After all milestones have been completed, the parties rate the transaction and each other.\r\n</ul>\r\n<p>\r\nThe long version:\r\n<P>\r\nThe SourceAgency project exchange allows sponsors to describe their software needs. Alternatively, developers can present their projects and request funding. The nature of a project depends on whether a developer or a sponsor registered the project. \r\n<p>\r\nAfter projects have been registered, interested parties can offer financial sponsorship, thus sponsors combine into a sponsor consortium that shares the overall cost for development of the software. On the other hand, interested developers can add technical information about possible solutions. \r\n<p>\r\nOnce sponsors are happy with a possible solution, the developers and sponsors contractually agree to carry out the project. When this happens, the project moves into the planning phase. In this phase, consultants (if required) are chosen to help the project with technical details. A <I>referee</i> must also be chosen to make decisions when problems between developers and sponsors arise, therefore a referee is normally a third party accepted by both developers and sponsors.\r\n<p>\r\n<B>Specifications and Milestones</b>\r\n<P>\r\nTechnical specifications are entered by either sponsors or developers or both and provide a detailed description of what is required and what is not. It is configurable who may enter technical specifications, however, sponsors decide which technical specifications are accepted.\r\n<p>\r\nBased on the technical specifications, milestones are proposed by interested developers. This is how sponsors choose the developers they would like to  complete the project. Only developers who get all their milestones accepted may work on the project. \r\n<p>\r\nOnce the planning phase is complete, the project moves into the development phase. This phase is not supported by SourceAgency, but BerliOS provides the <a href=\\\"http://developer.berlios.de\\\">Developer</a> portal to handle\r\nit. Releases may be advertised using the <a href=\\\"http://sourcewell.berlios.de\\\">SourceWell</a> portal and\r\ndocumentation may be presented using <a href=\\\"http://docswell.berlios.de\\\">DocsWell</a>.<p>\r\n<P>\r\nReferees decide which milestones are completed and after all the milestones have been fulfilled, the project moves into the rating phase. This allows all parties to rate the performance of the project and the developers involved.');
INSERT INTO doco VALUES (2,'English','faq','Frequently Asked Questions','The FAQ page provides answers to some common questions.<p> \r\n\r\nIf your questions is not there, then send the SourceAgency  developers <a href=\"mailto:sourceagency-support@lists.berlios.de?subject=FAQ Question\">feedback</a>!\r\n');
INSERT INTO doco VALUES (3,'English','login','Login Page','Here registered users may login. If you are not a registered user, then you can register <a href=\"register.php3\">here</a>');
INSERT INTO doco VALUES (4,'English','doco','Documentation Page','Page specific documentation is generated using this page. Each pages documentation can be accessed using the \"What is this?\" link on the left hand side menubar.');
INSERT INTO doco VALUES (5,'English','users','User Listing','Listing of the different types of registered users. This  allows developers to directly contact sponsors and sponsors  to directly contact developers.<p> Three listings available:<ul> <li><a href=\"users.php3\">All Users</a> <li><a href=\"users.php3?type=devel\">All Developers</a> <li><a href=\"users.php3?type=sponsor\">All Sponsors</a> </ul>');
INSERT INTO doco VALUES (6,'English','browse','Project Browsing','All currently registered and accepted projects can be browsed based on several different categories based on the projects configuration.<p> Projects matching a category are listed <b>below</b> the category listing.');
INSERT INTO doco VALUES (7,'English','licenses','SourceAgency License Listing','Provides a listing of all accepted Open Source licenses. Each project must choice a license for each Technical Content made to a project. <p>As SourceAgency only provides support for the development of Open Source Software, therefore all licenses are Open Source licenses approved by the <a href=\"http://opensource.org\">Open Source Initiative (OSI)</a>.');
INSERT INTO doco VALUES (8,'English','insform','Project Register Form','Here registered user may enter new project descriptions.<p>The nature of the project is defined by the type of user: developer enter descriptions of developing projects, while sponsors enter descriptions of sponsoring projects.');
INSERT INTO doco VALUES (9,'English','remind','Password Reminder','Registered user may have their forgotten password sent to their email address.');
INSERT INTO doco VALUES (10,'English','chguser','Change User Information','Registered users may change their personal information using this page.');
INSERT INTO doco VALUES (11,'English','register','User Registration','New users can register to become SourceAgency users.<p> SourceAgency has two <b>types</b> of users:<ul><li>Developers who can <a href=\"insform.php3\">register</a> developing projects and who are interested in getting involved projects as a developer <li>Sponsors can <a href=\"insform.php3\">register</a> sponsoring projects and represent businesses interested in getting specific software modules to fill the business needs.</ul>');
INSERT INTO doco VALUES (12,'English','summary','Project Summary','\r\nAlong with configuration and status information about a project, the\r\nsummary page provides links to other project related services. This\r\nallows users to comment on projects, provide sponsorship or technical\r\nassistance, and examine project progress. The focus of the page is to\r\nallow project members to easily manage the planning of a project.<p>\r\n\r\nEach project is broken up into 6 sequential steps. These ensure that a\r\nproject is clearly specified and provide a frame in which project\r\ndevelopment can take place. Software development is done on a project\r\nhosting service such as Berlios\' <a\r\nhref=\\\"http://developer.berlios.de\\\">Developer</a> or <a\r\nhref=\\\"http://sourceforge.net\\\">SourceForge</a> or <a\r\nhref=\\\"http://savannah.gnu.org/\\\">Savannah</a>.<p>\r\n\r\n<ul>\r\n<li>Step 1: Consultancy \r\n<ul><li>First step is to define technical consultants who help to\r\nclear up technical issues and questions. Thses are users who aid the\r\ndevelopers to define technical specifications or define themselve. A\r\nproject may choose to skip this step. \r\n</ul>\r\n\r\n<li>Step 2: Technical Specifications\r\n<ul><li>Step two is the definition of the technical content of the\r\nproject. These specify the project requirements and allow developers\r\nto define milestones in step 3.\r\n</ul>\r\n\r\n<li>Step 3: Milestones\r\n<ul><li>Defined by developers and accepted by sponsors, milestones\r\nserve to define a timeline for the development of the software. Those\r\ndevelopers who have all their milestones accepted, become members of\r\nthe development team. After this step, sponsors and developers are\r\nfixed and together they choose a referee.\r\n</ul>\r\n\r\n<li>Step 4: Referees\r\n<ul><li>Users can suggest themselves to be a referee for the project,\r\nhowever, only one referee is chosen. The decision is a joint decision\r\nof the developers and sponsors, although sponsors have the last\r\nword. The role a referee is to judge, as a neutral third party, the\r\ncompletion of individual milestones and thereby the entire project\r\ndevelopment. They also have the final word is disputes between\r\nsponsors and developers.\r\n</ul>\r\n\r\n<li>Step 5: Project Follow-up\r\n<ul><li>Tracks the exact progress of each milestone.\r\n</ul>\r\n\r\n<li>Step 6: Rating\r\n<ul><li>The final step is the rating of each developer by the sponsors.\r\n</ul>\r\n</ul>\r\n');
INSERT INTO doco VALUES (13,'English','personal','Personal Page','The peronsal page keeps users informed about the project they are\r\ninvolved in. For all users, the page displays their rating, projects\r\nthat they have suggested (<b>My Projects</b>). To the right of the my\r\nprojects box is the projects that the user is monitoring (<b>Monitored\r\nProjects</b>). Below this are projects where the user is involved and which have\r\neither been accepted, proposed or rejected. <p>\r\n\r\nAt the bottom of the page is a list of the last 10 comments made by\r\nthe user and the last five news items posted by the user.<p>\r\n\r\nFor users who are developers, there are additional boxes displaying\r\nprojects where they have been accepted, proposed or rejected as\r\nconsultants or referees.<p>\r\n');
INSERT INTO doco VALUES (14,'English','news','Projects News Page','The news page shows latest news posted by the project initiator. This\r\nkeeps project involved users informed about latest developments. <p>\r\n\r\nRegistered users may post comments on individual news items but may\r\nnot post news items. Comments are linked on the news page and shown in\r\nmore detail on the \r\n<a href=\"http://sourceagency.berlios.de/beta/comments.php3\">projects comments</a> \r\npage.<p>\r\n\r\n\r\n');
INSERT INTO doco VALUES (15,'English','comments','Projects Comment Page','Comments page shows all comments posted about a project. Each comment\r\nmay be commented on, i.e. replied to, and any registered user may post\r\ncomments. <p>\r\n');
INSERT INTO doco VALUES (16,'English','sponsoring','Project Sponsorship','The financial support for a project is listed on the sponsoring\r\npage. The page shows the amount each sponsor is providing, which in\r\nturn defines their voting rights when it comes to project\r\ndecisions.<p>\r\n\r\nAfter a project has began, sponsors may not decrease their sponsorship\r\nbut ofcourse an increase is always possible! \r\n');
INSERT INTO doco VALUES (17,'English','history','Projects History Page','Show what has happened in the life time of the project. The history\r\npages documents changes made to the project, describing the time and\r\ndate, user and what the change was. <p>\r\n');
INSERT INTO doco VALUES (18,'English','step1','Consultants to a Project','Lists all consultants aiding a project in its technical\r\nspecification. Projects may be configured to have no consultants.<p>\r\n\r\nA developer may propose themselves to be constultants to a project,\r\nand their proposal is subject to acceptance from sponsors. There is no\r\nlimit to the number of consultants a project may have, but all are\r\nsubject to approval of the sponsors. <p>\r\n\r\n');
INSERT INTO doco VALUES (19,'English','step2','Technical Specification of Project','Lists the technical specifications for a project. Each specification\r\ncan be made by a developer or the project initiator. Sponsors may not\r\nmake specifications, the intention being that sponsors only decide\r\nwhich proposals are acceptable. <p>\r\n\r\nDevelopers can make proposals for solutions for the technical\r\nspecifications and sponosors decide which proposals are accepted for\r\nwhich technical specification. In this way, sponsors define the\r\nrequirements of the project. <p>\r\n\r\nOnce all technical specifications and their corresponding have been\r\naccepted by sponsors, the project moves into Step 3. The developers\r\nwho had their proposals accepted, become the development team who\r\nimplement the project. In this way, step 2 acts as a selection process\r\nfor the development team.<p>\r\n');
INSERT INTO doco VALUES (20,'English','step3','Project Milestones','Milestones page allows developers to define milestones for the\r\nwork. Once they have been accepted by the sponsors, development can \r\nalmost begin -- Step 4, the choice of a referee is the final step\r\nbefore development can begin. <p>\r\n\r\n\r\n');
INSERT INTO doco VALUES (21,'English','step4','Choosing a Project Referee','This is the final step before development can start, it is the choice\r\nof a referee to act as a neutral decision maker in case of\r\nindecision.<p>\r\n\r\nAs the referee acts as final decider between developers and sponsors,\r\nthis is a person who is accepted by both parties. In this step, the\r\nreferee is chosen by all developers and sponsors. <p>\r\n\r\nAfter the referee has been chosen, development may begin. Software\r\ndevelopment is done on a project hosting service such as Berlios\' <a\r\nhref=\"http://developer.berlios.de\">Developer</a> or <a\r\nhref=\"http://sourceforge.net\">SourceForge</a> or <a\r\nhref=\"http://savannah.gnu.org\">Savannah</a>.<p>\r\n');
INSERT INTO doco VALUES (22,'English','step5','Project Monitoring and Follow Up','Step 5 is the project monitoring and follow up. This is basically the\r\ntracking of project progress and ensures that milestones are met by\r\ndevelopers.<p>\r\n');
INSERT INTO doco VALUES (23,'English','step6','Developer and Sponsor Rating','The final step involves rating all parties who participated in the\r\nproject. This is done after the project is completed.<p>\r\n\r\n');
INSERT INTO doco VALUES (24,'English','decisions','Decisions Page','Here sponsors decide specific open questions. Whenever a step requires\r\na decision, then the decisions page is used to obtain a decision. <p>\r\n\r\nEach sponsors has a vote and that vote is based on the amount of\r\nsponsorship: the more sponsorship, the more weight their vote\r\ncarries. <p>\r\n\r\nEach project has a configurable quorum, a minimum value of votes for a\r\ndecision in order to have the decision accepted. Ofcourse if a single\r\nsponsor has 90% of the sponsorship and the quorum is set at 70%, then\r\nthat sponsor can decide everything alone! The quorum should be set to\r\nsomething where every sponsors has, to a degree, a say in decisions made.<p>\r\n');
INSERT INTO doco VALUES (25,'English','configure','Project Configuration Page','The configuration page allows a project initiator to configure their\r\nproject. This page can also be viewed by project members but can not\r\nbe edited. <p>\r\n\r\nItems that are displayed:\r\n<ul>\r\n  <li><b>Quorum</b>(Not editable): Percentage of sponsor votes that are\r\n  required for a decision to be accpted\r\n  <li><b>Consultants</b>(Not editable): Whether the project requires\r\n  consultants to be chosen in the first step\r\n  <li><b>Other Technical Contents</b>(Editable) <b>??????</b>\r\n  <li><b>Other Developing Proposals</b>(Editable) <b>??????</b>\r\n  <li><b>First Sponsor</b>(Not editable): Name of the user who was the\r\n  first sponsor or initiator of the project if this is a sponsoring project\r\n  <li><b>Developer</b>(Not editable): Name of the first developer or\r\n  project initiator if this is a developing project\r\n</ul>\r\n');
INSERT INTO doco VALUES (26,'English','configure_edit','Page for editing a Projects Configuration','<b>To Be Documented</b>');
INSERT INTO doco VALUES (27,'English','views','Information views on the Project','Displays the who is allowed to view specific information related to the projecct.\r\n\r\n<b>To Be Completed</b>');
INSERT INTO doco VALUES (28,'English','views_edit','Edit the Information Viewing of the Project','<b>To Be Documented</b>');
INSERT INTO doco VALUES (29,'English','monitor','Monitoring of Project','<b>To Be Documented</b>');
INSERT INTO doco VALUES (30,'English','monitor_edit','Edit who is Monitoring the Project','<b>To Be Documented</b>');
INSERT INTO doco VALUES (31,'English','sponsoring_edit','Edit Sponsorship of a Project','<b>To Be Documented</b>');
INSERT INTO doco VALUES (32,'English','sponsoring_accepted','Accptance of a Sponsorship','<b>To Be Documented</b>');
INSERT INTO doco VALUES (33,'German','index','SourceAgency: Hauptseite','SourceAgency ist ein Portal für die Planung und Finanzierung von Open-Source-Projekten. Hier findet Open-Source-Software (OSS) Unterstützung, und OSS-Entwickler erhalten die Chance, für ihre Arbeit bezahlt zu werden. Das Wichtigste in Kürze:\r\n<P>\r\n<UL><LI>Projekt-Sponsoren erklären ihre Bedürfnisse. Software-Entwickler beschreiben die Open-Source-Projekte, an denen sie arbeiten. Neue Sponsoren und Entwickler können existierenden Projekten beitreten.\r\n<LI>Sofern gewünscht werden Projekt-Berater eingeladen. Technische Spezifikationen und Implementierungsvorschläge werden durch die Entwickler oder die Sponsoren eingereicht.\r\n<LI>Meilensteine werden durch die Entwickler vorgeschlagen, um den Implementierungsprozess aufzuteilen.\r\n<LI>Entwickler, deren Meilensteine akzeptiert wurden, nehmen an der Implementierung teil. Ein Vertrag wird abgeschlossen. Ein Sachverständiger wird bestimmt, um über den Erfolg der Implementierung zu urteilen.\r\n<LI>Nachdem alle Meilensteine erreicht wurden, bewerten die Parteien sich gegenseitig und das Projekt.\r\n</UL>\r\n<p>\r\nDie lange Version:\r\n<P>\r\nDer SourceAgency Projekt-Austausch erlaubt es Sponsoren, ihre Software-Bedürfnisse zu formulieren.  Entwickler können dagegen ihre Open-Source-Projekte präsentieren und Geldmittel anfordern. Die Art eines Projekts hängt davon ab, ob ein Entwickler oder ein Sponsor es registriert hat. \r\n<p>\r\nNach der Registrierung können interessierte Parteien finanzielle Unterstützung anbieten, so dass Sponsoren ein Konsortium bilden, das die Kosten des Projekts gemeinsam trägt. Interessierte Software-Entwickler können dagegen technische Informationen über mögliche Lösungen hinzufügen.\r\n<p>\r\nSobald Sponsoren mit einer vorgeschlagenen Lösung zufrieden sind, einigen sie sich vertraglich über die Durchführung des Projekts. Wenn dies passiert, erreicht das Projekt die Planungsphase. In dieser Phase werden ggf. Berater kontaktiert, um dem Projekt mit technischen Details zu helfen. Ein Schiedsrichter muss ebenfalls bestimmt werden, um bei Konflikten zu vermitteln.\r\n<p>\r\n<B>Spezifikationen und Meilensteine</B>\r\n<P>\r\nTechnische Spezifikationen werden von Sponsoren, von Entwicklern oder von beiden eingetragen und liefern eine detaillierte Beschreibung der Muss- und Kann-Kriterien, der Abgrenzungsmerkmale gegenüber anderen Projekten usw. Es ist konfigurierbar, wer technische Spezifikationen eintragen darf; Sponsoren entscheiden darüber, welche Spezifikationen akzeptiert werden.\r\n<p>\r\nBasierend auf den Spezifikationen werden von interessierten Entwicklern Meilensteine vorgeschlagen. Hier entscheiden die Sponsoren darüber, mit welchen Entwicklern sie das Projekt zum Abschluss bringen möchten. Nur die Entwickler, deren Meilensteine akzeptiert werden, dürfen an dem Projekt mitarbeiten.\r\n<p>\r\nSobald die Planungsphase abgeschlossen ist, bewegt sich das Projekt in die Entwicklungsphase. Diese Phase wird nicht direkt durch SourceAgency unterstützt, aber BerliOS stellt für diesen Zweck das <a href=\\\"http://developer.berlios.de\\\">Developer</a>-Portal bereit. Software-Releases können über BerliOS <a href=\\\"http://sourcewell.berlios.de\\\">SourceWell</a> angekündigt werden, und Dokumentation kann mit BerliOS <a href=\\\"http://docswell.berlios.de\\\">DocsWell</a> präsentiert werden.\r\n<P>\r\nSachverständige entscheiden, welche Meilensteine abgeschlossen wurden, und nachdem alle Meilensteine erfüllt wurden, folgt als letzter Schritt die Bewertungsphase. In dieser Phase bewerten alle Parteien die Leistung der Projektbeteiligten und das Ergebnis.');
INSERT INTO doco VALUES (34,'German','faq','FAQ','Die FAQ (Frequently Asked Questions, auf Deutsch: häufig gestellte Fragen) sollte bei typischen Fragestellungen weiterhelfen. Wenn Ihre Frage nicht in der FAQ zu finden ist, schicken sie uns doch einfach eine <A HREF=\"mailto:sourceagency-support@lists.berlios.de?subject=FAQ Question\">E-Mail</A>!');
INSERT INTO doco VALUES (35,'German','login','Login-Seite','Hier können Sie sich anmelden, wenn Sie sich zuvor als Benutzer registriert haben. Bitte beachten Sie, dass Sie vor der ersten Anmeldung den Aktivierungslink in der Mail anklicken müssen, die Ihnen nach der Registrierung zugeschickt wurde.\r\n<P>\r\nWenn Sie noch kein registrierter Benutzer sind, können Sie das  <A HREF=\"register.php3\">hier</a> nachholen.');
INSERT INTO doco VALUES (36,'German','doco','Dokumentationsseite','Seitenbezogene Dokumentation kann hier verwaltet werden. Diese Dokumente werden angezeigt, wenn man dem \"Was ist das?\" Link auf einer Seite folgt. Außerdem ist hier auch der Text der Hauptseite zu finden.');
INSERT INTO doco VALUES (37,'German','users','Benutzerliste','Liste der verschiedenen Arten registrierter Benutzer. Dies erlaubt es Entwicklern, direkt mit Sponsoren in Kontakt zu treten und umgekehrt. Es sind also drei Listen verfügbar:\r\n<P>\r\n<UL><LI><A HREF=\"users.php3\">Alle Benutzer</a>\r\n<LI><A HREF=\"users.php3?type=sponsor\">Alle Sponsoren</a>\r\n<LI><A HREF=\"users.php3?type=devel\">Alle Entwickler</a>\r\n</ul>');
INSERT INTO doco VALUES (38,'German','browse','Projekte durchblättern','Alle derzeit registrierten und akzeptierten Projekte können nach verschiedenen Kategorien durchblättert werden. Projekte, die einer ausgewählten Kategorie entsprechen, werden unter der Kategorieliste aufgeführt.');
INSERT INTO doco VALUES (39,'German','licenses','SourceAgency-Lizenzliste','Eine Liste aller akzeptierten Open-Source-Lizenzen. Für alle dem Projekt hinzugefügten technischen Inhalte muss jeweils eine Lizenz vorgegeben werden.\r\n\r\nSourceAgency unterstützt nur die Entwicklung von Open-Source-Software. In dieser Liste finden sich deshalb nur Lizenzen, die von der <A HREF=\"http://opensource.org\">Open-Source-Initative (OSI)</A> zertifiziert wurden.');
INSERT INTO doco VALUES (40,'German','insform','Projektregistrierung','Hier können registrierte Benutzer neue Projekte anmelden. Die Art des Projekts ist davon abhängig, ob ein Entwickler oder ein Sponsor das Projekt registriert.');
INSERT INTO doco VALUES (41,'German','remind','Passworterinnerung','Registrierte Benutzer können sich ihr vergessenes Passwort per E-Mail zuschicken lassen.');
INSERT INTO doco VALUES (42,'German','chguser','Benutzerinformationen ändern','Registrierte Benutzer können auf dieser Seite ihre persönlichen Informationen ändern.');
INSERT INTO doco VALUES (43,'German','register','Benutzerregistrierung','Neue Benutzer können sich hier bei SourceAgency registrieren.\r\nSourceAgency kennt zwei Arten von Benutzern:\r\n<P>\r\n<UL><LI>Entwickler, die Entwicklerprojekte registrieren können und sich in vorhandenen Projekten als Entwickler bewerben können.\r\n<LI>Sponsoren, die Sponsorenprojekte registrieren können und Organisationen oder Unternehmen repräsentieren, die ein Interesse an der Entwicklung spezifischer Open-Source-Lösungen haben.\r\n</UL>');
INSERT INTO doco VALUES (44,'German','summary','Projektzusammenfassung','Neben der Konfiguration und Statusinformationen über ein Projekt finden sich auf der Zusammenfassungs-Seite Links auf andere projektbezogene Dienste. Dies erlaubt es Benutzern, Projekte zu kommentieren, Sponsorenschaft oder technische Hilfe anzubieten, und den Projektfortschritt zu beurteilen. Der Schwerpunkt der Seite ist es, Projektmitgliedern die einfache Verwaltung und Planung eines Projekts zu ermöglichen.\r\n\r\nJedes Projekt ist in sechs sequentielle Schritte eingeteilt. Diese Schritte stellen sicher, dass ein Projekt klar spezifiziert ist und liefern den Rahmen, innerhalb dessen die Projektentwicklung stattfinden kann. Die eigentliche Software-Entwicklung kann über <A HREF=\"http://developer.berlios.de/\">BerliOS Developer</a> koordiniert werden.\r\n\r\n<UL><LI>Schritt 1: Beratung\r\n<UL>\r\n<LI>Der erste Schritt besteht darin, technische Berater zu bestimmen, die dabei helfen, technische Probleme und Fragen zu klären. Dies sind Benutzer, die den Entwicklern dabei helfen, technische Spezifikationen zu formulieren oder diese sogar selbst formulieren. Ein Projekt kann diesen Schritt überspringen.\r\n</ul>\r\n<LI>Schritt 2: Technische Spezifikationen\r\n<UL>\r\n<LI>Im zweiten Schritt werden die technischen Inhalte des Projekts definiert. Diese spezifizieren die Projektanforderungen und erlauben es Entwicklern, in Schritt 3 Meilensteine festzulegen.\r\n</ul>\r\n<LI>Schritt 3: Meilensteine\r\n<UL>\r\n<LI>Von Entwicklern definiert und von Sponsoren akzeptiert, dienen Meilensteine der Definition eines Zeitplans für die Software-Entwicklung. Nur die Entwickler, deren Meilenstein-Vorschläge akzeptiert wurden, werden tatsächlich auch Mitglieder des Entwicklerteams. Nach diesem Schritt stehen Sponsoren und Entwickler fest und bestimmen einen Sachverständigen.\r\n</ul>\r\n<LI>Schritt 4: Sachverständige\r\n<UL>\r\n<LI>Benutzer können sich selbst als Sachverständige für das Projekt vorschlagen, es wird jedoch nur ein Sachverständiger ausgewählt. Die Entscheidung wird gemeinsam von Entwicklern und Sponsoren gefällt, wobei Sponsoren jedoch das letzte Wort behalten. Die Rolle des Sachverständigen ist es als unabhängiger Dritter den Abschluss individueller Meilensteine und damit den Erfolg des Gesamtprojekts zu beurteilen. In allen Konflikten zwischen Sponsoren und Entwickler hat der Sachverständige das letzte Wort.\r\n</ul>\r\n<LI>Schritt 5: Projektdurchführung\r\n<UL>\r\n<LI>In diesem Schritt, der nicht direkt über SourceAgency  organisiert wird, kann der Fortschritt jedes einzelnen Meilensteins verfolgt werden.\r\n</ul>\r\n<LI>Schritt 6: Bewertung\r\n<UL>\r\n<LI>Der letzte Schritt ist die Bewertung jedes Entwicklers durch die Sponsoren.\r\n</ul>\r\n</ul>');
INSERT INTO doco VALUES (45,'German','personal','Persönliche Seite','Die persönliche Seite informiert Benutzer über die Projekte, an denen sie beteiligt sind. Sie zeigt die Bewertung des Benutzers durch Dritte, Projekte, die der Benutzer vorgeschlagen hat (<B>Meine Projekte</B>), und Statusinformationen über Projekte, die er beobachten möchte (<B>Beobachtete Projekte</B>). Darunter findet sich eine Liste der Projekte, an denen der Benutzer beteiligt ist und die entweder akzeptiert, vorgeschlagen oder abgelehent wurden.\r\n<P>\r\nAm Ende der Seite findet sich eine Liste der 10 letzten Kommentare des Nutzers und der letzten fünf Nachrichten, die er eingespielt hat.  Für Entwickler gibt es zusätzliche Informationen über Projekte, bei denen sie als Berater oder Sachverständige akzeptiert, vorgeschlagen oder abgelehnt worden sind.');
INSERT INTO doco VALUES (46,'German','news','Nachrichtenseite','Die Nachrichtenseite zeigt die letzten durch den Projekt-Initiator eingespeisten Projektnachrichten. Damit werden Projektbeteiligte über neueste Entwicklungen auf dem Laufenden gehalten.\r\n\r\nRegistrierte Benutzer können auf einzelne Nachrichten bezogene Kommentare schreiben, können aber keine Nachrichten für nicht von ihnen initiierte Projekte einspielen. Kommentare sind auf der Nachrichtenseite als Links und auf der Kommentarseite im Volltext sichtbar.');
INSERT INTO doco VALUES (47,'German','comments','Kommentarseite','Die Kommentarseite zeigt alle zu einem Projekt beigesteuerten Kommentare. Auf jeden Kommentar kann geantwortet werden, alle registrierten Benutzer können Beiträge schreiben.');
INSERT INTO doco VALUES (48,'German','sponsoring','Finanzielle Unterstützung','Die finanzielle Unterstützung für ein Projekt ist auf der Sponsorenseite aufgelistet. Diese Seite zeigt den Betrag, den jeder Sponsor bereitstellt, woraus sich wiederum die Stimmrechte im Fall von Projektentscheidungen ergeben.\r\n\r\nNachdem ein Projekt begonnen worden ist, können Sponsoren ihre Sponsorenschaft nicht reduzieren, eine Erhöhung ist jedoch immer möglich.');
INSERT INTO doco VALUES (49,'German','history','Projekt history','Diese Seite zeichnet auf, was in der Lebensgeschichte eines Projekts passiert ist. Sie dokumentiert alle Änderungen an dem Projekt mit Zeit, Datum, Benutzer und Art der Änderung.');
INSERT INTO doco VALUES (50,'German','step1','Berater eines Projekts','Hier findet sich eine Liste aller Berater, die dem Projekt bei technischen Spezifikationen helfen. Projekte können so konfiguriert werden, dass keine Berater verwendet werden.\r\n<P>\r\nEin Entwickler kann sich selbst als Berater vorschlagen. Ein solcher Vorschlag muss von den Sponsoren akzeptiert werden. Es gibt keine Beschränkung der Zahl der Berater, die ein Projekt haben kann, aber alle Berater müssen von den Sponsoren genehmigt werden.');
INSERT INTO doco VALUES (51,'German','step2','Technische Spezifikationen eines Projekts','Hier werden alle dem Projekt zugeordneten technischen Spezifikationen aufgeführt. Spezifikationen können von Entwicklern oder dem Projektinitiator vorgeschlagen werden. Sponsoren können keine Spezifikation beisteueren, sondern nur über die vorgeschlagenen Spezifikationen abstimmen.\r\n<P>\r\nEntwickler können Implementierungsvorschläge für die Spezifikationen machen, und auch hier entscheiden die Sponsoren darüber, welche Vorschläge akzeptiert werden. So definieren die Sponsoren die Anforderungen des Projekts.\r\n<P>\r\nSobald alle technischen Spezifikationen und ihre korrespondierenden Implementierungsvorschläge durch die Sponsoren akzeptiert wurden, erreicht das Projekt Schritt 3. Die Entwickler, deren Implementierungsvorschläge akzeptiert wurden, konstitutieren das Entwicklerteam, welches das Projekt implementiert. So fungiert Schritt 2 als Selektionsprozess für das Entwickler-Team.');
INSERT INTO doco VALUES (52,'German','step3','Schritt 3: Meilensteine','Die Meilenstein-Seite erlaubt es Entwicklern, für ihre bevorstehende Arbeit Meilensteine zu definieren. Sobald diese Meilensteine durch die Sponsoren akzeptiert wurden, kann die Entwicklung beinahe beginnen -- zuvor muss in Schritt 4 noch ein Sachverständiger bestimmt werden.');
INSERT INTO doco VALUES (53,'German','step4','Bestimmung eines Sachverständigen','Die Bestimmung eines Sachverständigen ist der letzte Schritt, bevor die Entwicklung des Projekts beginnen kann. Der Sachverständige agiert als neutraler Entscheidungsträger, wenn es zwischen Entwicklern und Sponsoren zu Konflikten kommt.\r\n<P>\r\nDa die Entscheidungen des Sachverständigen endgültig sind, müssen sich alle Sponsoren und Entwickler über den Sachverständigen einig sein. Nachdem ein Sachverständiger bestimmt worden ist, kann die Entwicklung beginnen, z.B. mit Hilfe des <A HREF=\"http://developer.berlios.de/\">BerliOS-Entwicklerportals</A>.');
INSERT INTO doco VALUES (54,'German','step5','Projektdurchführung','Schritt 5 ist die Projektkontrolle und Durchführung. Hier wird der aktuelle Fortschritt des Projekts wiedergegeben und sichergestellt, dass Entwickler ihre Meilensteine erreichen.');
INSERT INTO doco VALUES (55,'German','step6','Bewertung','Der letzte Schritt beinhaltet die Bewertung aller Parteien, die an dem Projekt teilnahmen. Dieser Schritt wird nach dem Abschluss des Projekts durchgeführt.');
INSERT INTO doco VALUES (56,'German','decisions','Entscheidungen','Hier entscheiden Sponsoren spezifische offene Fragen. Wann immer ein Schritt eine Entscheidung erfordert, wird diese über die Entscheidungs-Seite ermittelt.\r\n<P>\r\nJeder Sponsor hat eine Stimme, und das Stimmgewicht ist proportional zum Umfang der Sponsorenschaft (Zensuswahlrecht).\r\n<P>\r\nJedes Projekt hat ein konfigurierbares Quorum, ein Schwellenwert, ab dem Entscheidungen akzeptiert werden. Wenn das Quorum z.B. 70% beträgt und ein Sponsor 90% des Projekts unterstützt, kann dieser alle Entscheidungen allein treffen. Somit sollte das Quorum so konfiguriert werden, dass jeder Sponsor zu einem gewissen Grad an den Entscheidungen teilnehmen kann.');
INSERT INTO doco VALUES (57,'German','configure','Projektkonfiguration','Diese Seite erlaubt es dem Projektinitiator, das Projekt zu konfigurieren. Sie kann auch von Projektmitgliedern angesehen, aber nicht bearbeitet werden.\r\n<P>\r\nDie folgenden Informationen werden angezeigt:\r\n<P>\r\n<UL><LI>Quorum (nicht editierbar): Prozentsatz der Sponsorenstimmen, der erforderlich ist, damit eine Entscheidung akzeptiert wird\r\n<LI>Berater (nicht editierbar): Ob das Projekt Berater benötigt, die im ersten Schritt bestimmt werden.\r\n<LI>Andere technische Inhalte\r\n<LI>Andere Entwickler-Vorschläge\r\n<LI>Erster Sponsor: identisch mit dem Projektinitiator, wenn es sich um ein Sponsorenprojekt handelt\r\n<LI>Erster Entwickler: identisch mit dem Projektinitiator, wenn es sich um ein Entwicklerprojekt handelt\r\n</UL>');

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
INSERT INTO faq VALUES (3,'Unknown','This is an unknown question','with an unknown answer!');
INSERT INTO faq VALUES (4,'German','Warum gibt es SourceAgency nicht in meiner Muttersprache?','SourceAgency kann einfach in verschiedene Sprachen übersetzt werden.\r\nSollten Sie feststellen, dass wir Ihre Sprache nicht unterstützen,\r\nwürden wir uns über Hilfe bei der Übersetzung sehr freuen.\r\nWeitere Infos gibt es unter <A HREF=\"http://sourceagency.berlios.de/html/translating.php3\">http://sourceagency.berlios.de/html/translating.php3.</a>');
INSERT INTO faq VALUES (5,'German','Wie kann ich mein Passwort oder meine E-Mail-Adresse ändern?','Klicken Sie den Link <A HREF=\"chguser.php3\">Benutzer-Info ändern</A> aus der Seitenleiste aus.');

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
INSERT INTO history VALUES (7,'riessen','sponsoring','Sponsor sponsor2 accepted as sponsor',20020411153957);
INSERT INTO history VALUES (7,'riessen','sponsoring','Sponsor sponsor accepted as sponsor',20020411154004);
INSERT INTO history VALUES (7,'riessen','sponsoring','Sponsor admin accepted as sponsor',20020412134138);
INSERT INTO history VALUES (27,'admin','Configure','Project configuration',20020906140140);
INSERT INTO history VALUES (27,'admin','Configure','Project configuration modified',20020906140213);
INSERT INTO history VALUES (27,'admin','Configure','Project configuration modified',20020906141051);
INSERT INTO history VALUES (27,'admin','Configure','Project configuration modified',20020906141101);
INSERT INTO history VALUES (27,'admin','Configure','Project configuration modified',20020906141107);
INSERT INTO history VALUES (27,'admin','Configure','Project configuration modified',20020906141151);
INSERT INTO history VALUES (27,'admin','Configure','Project configuration modified',20020906141302);
INSERT INTO history VALUES (3,'nilix','Configure','Project configuration modified',20020906141436);
INSERT INTO history VALUES (5,'nilix','Configure','Project configuration modified',20020906142519);
INSERT INTO history VALUES (5,'nilix','Configure','Project configuration modified',20020906142531);
INSERT INTO history VALUES (5,'nilix','Configure','Project configuration modified',20020906142601);
INSERT INTO history VALUES (5,'helix','Configure','Project configuration modified',20020906161441);
INSERT INTO history VALUES (28,'helix','Configure','Project configuration',20020907172230);
INSERT INTO history VALUES (30,'helix','Configure','Project configuration',20020908000427);
INSERT INTO history VALUES (30,'helix','Configure','Project configuration modified',20020908000628);
INSERT INTO history VALUES (3,'helix','Configure','Project configuration modified',20020911155112);
INSERT INTO history VALUES (1,'nilix','sponsoring','Sponsor sponsor accepted as sponsor',20020911164915);
INSERT INTO history VALUES (31,'erik','Configure','Project configuration',20021025172851);
INSERT INTO history VALUES (31,'BerliOS editor','Review','Project reviewed by a SourceAgency Editor',20021106151536);
INSERT INTO history VALUES (32,'308624857','Configure','Project configuration',20030113151634);
INSERT INTO history VALUES (32,'308624857','Configure','Project configuration modified',20030113151753);
INSERT INTO history VALUES (33,'Tatanka','Configure','Project configuration',20030214020615);

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

INSERT INTO monitor VALUES (5,'helix','low',20020906171411);
INSERT INTO monitor VALUES (3,'helix','high',20020906204433);

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
INSERT INTO news VALUES (26,'riessen','sadasd','asdasd',20020411124706);
INSERT INTO news VALUES (7,'riessen','ddddaa','sads',20020424115123);
INSERT INTO news VALUES (5,'nilix','About the project status','success',20020906144945);
INSERT INTO news VALUES (5,'nilix','About the project status','success',20020906145227);
INSERT INTO news VALUES (31,'erik','Projektstart','Das Projekt startet offiziell.',20021106151838);

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

INSERT INTO sponsoring VALUES (1,1,'sponsor',12345,'A','',20010101120000,20010101120000,20010101120000,20020411151553);
INSERT INTO sponsoring VALUES (2,1,'nilix',234,'A','',20010101120000,20010101120000,20010101120000,20011008202604);
INSERT INTO sponsoring VALUES (3,2,'sponsor',2000,'A','',20040604120000,20030102120000,20040704120000,20011008211226);
INSERT INTO sponsoring VALUES (4,3,'nilix',2000,'A','Let\' do it!',20011201120000,20011201120000,20020101120000,20011009134940);
INSERT INTO sponsoring VALUES (5,5,'nilix',5000,'A','Hope, it will start at the beginning of 2002.',20011001120000,20011201120000,20030101120000,20011009142139);
INSERT INTO sponsoring VALUES (6,4,'sponsor',123,'A','',20010101120000,20010101120000,20010101120000,20011009194827);
INSERT INTO sponsoring VALUES (7,6,'sponsor',10,'A','',20041231120000,20011010120000,20011012120000,20011010182452);
INSERT INTO sponsoring VALUES (8,7,'sponsor',232,'A','testing whether the date is being set',20021201120000,20031102120000,20041003120000,20020411152306);
INSERT INTO sponsoring VALUES (9,9,'sponsor',12,'A','',20010101120000,20010101120000,20010101120000,20011126120603);
INSERT INTO sponsoring VALUES (10,7,'riessen',23,'A','',20010101120000,20010101120000,20010101120000,20020319093314);
INSERT INTO sponsoring VALUES (13,9,'sponsor2',2323,'P','asdasd',20010101120000,20010101120000,20010101120000,20020325142650);
INSERT INTO sponsoring VALUES (18,7,'admin',100000,'A','testing whether proposed sponsorship\r\nbecomes part of the total project\r\nbudget ',20010101120000,20010101120000,20010101120000,20020412133854);
INSERT INTO sponsoring VALUES (15,25,'sponsor',1213,'A','asasd',20010101120000,20010101120000,20010101120000,20020327110037);
INSERT INTO sponsoring VALUES (16,24,'sponsor',123445,'A','sada',20010101120000,20010101120000,20010101120000,20020327110101);
INSERT INTO sponsoring VALUES (17,7,'sponsor2',11223,'A','asdasd',20010101120000,20010101120000,20010101120000,20020328152926);
INSERT INTO sponsoring VALUES (19,30,'helix',10000,'P','Ich möchte helfen.',20021001120000,20021101120000,20031031120000,20020910160626);
INSERT INTO sponsoring VALUES (20,31,'nilix',500,'P','Wird im Rahmen eines Praktikums finanziert.',20021205120000,20021205120000,20021212120000,20021205120657);

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
INSERT INTO tech_content VALUES (9,3,'PHP','Linux','x86','Web','','rzinei 4788 478 ktzr','helix','P',20020911154108);
INSERT INTO tech_content VALUES (10,31,'Perl-Kenntnisse, MySQL-Kenntnisse','Linux','x86','Web','','Entfernung aller Textkomponenten und großer HTML-Blöcke aus dem Perl-Code.','erik','P',20021120110020);

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
INSERT INTO views VALUES (27,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020906130357);
INSERT INTO views VALUES (28,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020907170611);
INSERT INTO views VALUES (29,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020908000112);
INSERT INTO views VALUES (30,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20020908000413);
INSERT INTO views VALUES (31,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20021025172802);
INSERT INTO views VALUES (32,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20030113151547);
INSERT INTO views VALUES (33,'Project Participants','Project Participants','Project Initiator','Registered','Everybody','Everybody','Everybody','Everybody','Everybody',20030214020544);

