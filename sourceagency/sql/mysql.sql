# Database sourceagency
# phpMyAdmin MySQL-Dump
# http://phpwizard.net/phpMyAdmin/
#
# SourceAgency Version 0.x.0
#	     Gregorio Robles <grex@scouts-es.org>
#
# For more information about the database structure
# have a look at the SourceAgency documentation
#
# Database: sourceagency

USE sourceagency;

# --------------------------------------------------------
#
# Table structure for table 'active_sessions'
#

DROP TABLE IF EXISTS active_sessions;
CREATE TABLE active_sessions (
   sid varchar(32) NOT NULL,
   name varchar(32) NOT NULL,
   val text,
   changed varchar(14) NOT NULL,
   PRIMARY KEY (name, sid),
   KEY changed (changed)
);

# --------------------------------------------------------
#
# Table structure for table 'auth_user'
#

DROP TABLE IF EXISTS auth_user;
     CREATE TABLE auth_user (
   user_id varchar(32) NOT NULL,
   username varchar(32) NOT NULL,
   password varchar(32) NOT NULL,
   realname varchar(64) NOT NULL,
   email_usr varchar(128) NOT NULL,
   modification_usr timestamp(14),
   creation_usr timestamp(14),
   perms varchar(255),
   PRIMARY KEY (user_id),
   UNIQUE k_username (username)
);

#
# Dumping data for table 'auth_user'
#

INSERT INTO auth_user VALUES ( 'c8a174e0bdda2011ff798b20f219adc5', 'admin', 'admin', 'admin', 'grex@scouts-es.org', '20010419103000', '20010419103000', 'editor,admin');
INSERT INTO auth_user VALUES ( '42b3cdc7658ed6b3e07b9441c7679b28', 'devel', 'devel', 'devel', 'devel@scouts-es.org', '20010426182520', '20010426182520', 'devel');
INSERT INTO auth_user VALUES ( '740156f449ebc5950546517021fda49d', 'sponsor', 'sponsor', 'sponsor', 'sponsor@scouts-es.org', '20010426182533', '20010426182533', 'sponsor');
INSERT INTO auth_user VALUES ( 'f4c756dfc8e55a131d2a7d4be9e3e11b', 'riessen', 'fu23bar', 'Gerrit Riessen', 'gerrit.riessen@web.de', '20011004151153', '20011004150815', 'devel');
INSERT INTO auth_user VALUES ( '35b3db48944dba7a4e272926fd0d2839', 'helix', 'helix%sa', 'Lutz Henckel', 'lutz.henckel@fokus.gmd.de', '20011005122316', '20011005111603', 'devel');
INSERT INTO auth_user VALUES ( '48db7ac02e974648e3454c02021b632a', 'nilix', 'nilix%sa', 'Lutz Henckel', 'lutz.henckel@fokus.gmd.de', '20011005180845', '20011005180845', 'sponsor');

# --------------------------------------------------------
#
# Table structure for table 'cooperation'
#

DROP TABLE IF EXISTS cooperation;
CREATE TABLE cooperation (
   devid bigint(20) unsigned DEFAULT '0' NOT NULL,
   developer varchar(16) NOT NULL,
   cost int(8) NOT NULL,
   status char(1) NOT NULL,
   creation timestamp(14)
);

#
# Dumping data for table 'cooperation'
#


# --------------------------------------------------------
#
# Table structure for table 'comments'
#

DROP TABLE IF EXISTS comments;
CREATE TABLE comments (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   user_cmt varchar(16) NOT NULL,
   type varchar(16) DEFAULT 'General' NOT NULL,
   number varchar(16) DEFAULT '0' NOT NULL,
   id int(8) DEFAULT '1' NOT NULL,
   ref int(8) DEFAULT '0' NOT NULL,
   subject_cmt varchar(128) NOT NULL,
   text_cmt blob NOT NULL,
   creation_cmt timestamp(14)
);

#
# Dumping data for table 'comments'
#

# --------------------------------------------------------
#
# Table structure for table 'configure'
#

DROP TABLE IF EXISTS configure;
CREATE TABLE configure (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   quorum int(2) unsigned,
   consultants char(3),
   other_tech_contents char(3),
   other_developing_proposals char(3),
   sponsor varchar(16),
   developer varchar(16)
);

#
# Dumping data for table 'configure'
#


# --------------------------------------------------------
#
# Table structure for table 'decisions'
#

DROP TABLE IF EXISTS decisions;
CREATE TABLE decisions (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   step int(8) unsigned NOT NULL,
   decision_user  varchar(16) NOT NULL,
   decision varchar(16) NOT NULL
);

#
# Dumping data for table 'decisions'
#

# --------------------------------------------------------
#
# Table structure for table 'decisions_step5'
#

DROP TABLE IF EXISTS decisions_step5;
CREATE TABLE decisions_step5 (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   number int(8) unsigned NOT NULL,    # milestone_number
   count int(3) unsigned NOT NULL,     # Number of times this milestone has been
                                       # released
   decision_user  varchar(16) NOT NULL,
   decision varchar(16) NOT NULL
);

#
# Dumping data for table 'decisions_step5'
#


# --------------------------------------------------------
#
# Table structure for table 'decisions_milestones'
#

DROP TABLE IF EXISTS decisions_milestones;
CREATE TABLE decisions_milestones (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   devid bigint(20) unsigned DEFAULT '0' NOT NULL,
   decision_user  varchar(16) NOT NULL,
   number int(8) NOT NULL,
   decision varchar(16) NOT NULL
);

#
# Dumping data for table 'decisions_milestones'
#


# --------------------------------------------------------
#
# Table structure for table 'counter'	
#

DROP TABLE IF EXISTS counter;
CREATE TABLE counter (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   pro_cnt int(11) DEFAULT '0' NOT NULL,
   docs_cnt int(11) DEFAULT '0' NOT NULL,
   UNIQUE prosid (proid)
);

#
# Dumping data for table 'counter'
#


# --------------------------------------------------------
#
# Table structure for table 'counter_check'
#

DROP TABLE IF EXISTS counter_check;
CREATE TABLE counter_check (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   cnt_type varchar(20) NOT NULL,
   ipaddr varchar(15) DEFAULT '127.000.000.001' NOT NULL,
   creation_cnt timestamp(14)
);

#
# Dumping data for table 'counter_check'
#

# --------------------------------------------------------
#
# Table structure for table 'doco'
#
DROP TABLE IF EXISTS doco;
CREATE TABLE doco (
   docoid int(8) unsigned DEFAULT '0' NOT NULL auto_increment,
   language varchar(24) NOT NULL,
   page varchar(64) NOT NULL,
   header varchar(255) NOT NULL,
   doco blob NOT NULL,
   UNIQUE docid (docoid)
);

#
# Dumping data for table 'doco'
#

INSERT INTO doco VALUES ('1', 'English', 'index', 'SourceAgency: Front Page', 'SourceAgency is a Open Source project exchange. It is the place where developers meet sponsors, and sponsors can describe the software requirements.<p> Two nature-types of projects are available: developing and sponsoring.<ul><li>Developing are those projects that are being developed by developers and are looking for finanical sponsorship.<li>Sponsoring projects are ones where sponsor(s) are looking for a developer group to develop a needed solution for them.</ul><p> All projects developed using the SourceAgency/Berlios platform are Open Source projects and use Open Source Licenses.');
INSERT INTO doco VALUES ('2', 'English', 'faq', 'Frequently Asked Questions', 'The FAQ page provides answers to some common questions.<p> Questions can be asked by sending the SourceAgency  developers <a href=\"mailto:sourceagency-support@lists.berlios.de?subject=FAQ Question\">feedback</a>!');
INSERT INTO doco VALUES ('3', 'English', 'login', 'Login Page', 'Here registered users may login. If you are not a registered user, then you can register <a href=\"register.php3\">here</a>');
INSERT INTO doco VALUES ('4', 'English', 'doco', 'Documentation Page', 'Page specific documentation is generated using this page. Each pages documentation can be accessed using the \"What this\" link on the left hand side menubar.');
INSERT INTO doco VALUES ('5', 'English', 'users', 'User Listing', 'Listing of the different types of registered users. This  allows developers to directly contact sponsors and sponsors  to directly contact developers.<p> Three listings available:<ul> <li><a href=\"users.php3\">All Users</a> <li><a href=\"users.php3?type=devel\">All Developers</a> <li><a href=\"users.php3?type=sponsor\">All Sponsors</a> </ul>');
INSERT INTO doco VALUES ('6', 'English', 'browse', 'Project Browsing', 'All currently registered and accepted projects can be browsed based on several different categories based on the projects configuration.<p> Projects matching a category are listed below the category listing.');
INSERT INTO doco VALUES ('7', 'English', 'licenses', 'Open Source License Listing', 'Provides a listing of all accepted Open Source licenses. Each project must choice a license for each Technical Content made to a project. <p>As SourceAgency only provides support for the development of Open Source Software, therefore all licenses are Open Source licenses approved by the <a href=\"http://opensource.org\">Open Source Initiative (OSI)</a>.');
INSERT INTO doco VALUES ('8', 'English', 'insform', 'Project Register Form', 'Here registered user may enter new project descriptions.<p>The nature of the project is defined by the type of user: developer enter descriptions of developing projects, while sponsors enter descriptions of sponsoring projects.');
INSERT INTO doco VALUES ('9', 'English', 'remind', 'Password Reminder', 'Registered user may have their forgotten password sent to their email address.');
INSERT INTO doco VALUES ('10', 'English', 'chguser', 'Change User Information', 'Registered users may change their personal information using this page.');
#INSERT INTO doco VALUES ('', 'English', '', '', '');
#INSERT INTO doco VALUES ('', 'English', '', '', '');

# --------------------------------------------------------
#
# Table structure for table 'faq'
#

DROP TABLE IF EXISTS faq;
CREATE TABLE faq (
   faqid int(8) unsigned DEFAULT '0' NOT NULL auto_increment,
   language varchar(24) NOT NULL,
   question blob NOT NULL,
   answer blob NOT NULL,
   UNIQUE idx_2 (faqid)
);

#
# Dumping data for table 'faq'
#

INSERT INTO faq VALUES ('1', 'English', 'How to change my Password or E-mail address I am registered with?', 'Select \"<a href="chguser.php3">Change User</a>\" and enter your new parameters.');
INSERT INTO faq VALUES ('2', 'English', 'Why is the system not in my language?', 'This system can be easily translated into different languages. If you see that we do not have support in your language, you\'re gladly invited to help us with the internationalization. Visit <A HREF=\"http://sourceagency.berlios.de/html/translating.php3\">http://sourceagency.berlios.de/html/translating.php3</A>.');

# --------------------------------------------------------
#
# Table structure for table 'history'
#

DROP TABLE IF EXISTS history;
CREATE TABLE history (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   history_user varchar(16) NOT NULL,
   type varchar(16) NOT NULL,
   action varchar(255) NOT NULL,
#   link varchar(255) NOT NULL,
   creation timestamp(14)
);

#
# Dumping data for table 'history'
#


# --------------------------------------------------------
#
# Table structure for table 'licenses'
#

DROP TABLE IF EXISTS licenses;
CREATE TABLE licenses (
   license varchar(64) NOT NULL,
   url varchar(255) NOT NULL
);

#
# Dumping data for table 'licenses'
#

INSERT INTO licenses VALUES ( 'The GNU General Public License (GPL)', 'http://www.gnu.org/copyleft/gpl.html');
INSERT INTO licenses VALUES ( 'The GNU Library or Lesser Public License (LGPL)', 'http://www.gnu.org/copyleft/lesser.html');
INSERT INTO licenses VALUES ( 'The BSD license', 'http://www.freebsd.org/copyright/license.html');
INSERT INTO licenses VALUES ( 'The MIT license', 'http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Artistic license', 'http://www.perl.com/language/misc/Artistic.html');
INSERT INTO licenses VALUES ( 'The Mozilla Public License v. 1.0 (MPL)', 'http://www.mozilla.org/MPL/');
INSERT INTO licenses VALUES ( 'The Qt Public License (QPL)','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The IBM Public License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The MITRE Collaborative Virtual Workspace License (CVW License)','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Python License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Apache Software License', 'http://www.apache.org/docs-2.0/LICENSE');

INSERT INTO licenses VALUES ( 'The Vovida Software License v. 1.0','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Sun Internet Standards Source License (SISSL)','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Intel Open Source License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Mozilla Public License v. 1.1 (MPL 1.1)', 'http://www.mozilla.org/MPL/');
INSERT INTO licenses VALUES ( 'The Jabber Open Source License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Nokia Open Source License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Sleepy Cat License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Nethack General Public License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Common Public License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'The Apple Public Source License','http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'Public Domain', 'http://www.eiffel-forum.org/license/index.htm#pd');

#INSERT INTO licenses VALUES ( 'PHP License', 'http://www.php.net/license.html');
#INSERT INTO licenses VALUES ( 'X11 License', 'http://www.x.org/terms.htm');
#INSERT INTO licenses VALUES ( 'Zope Public License', 'http://www.zope.com/Resources/ZPL');


# --------------------------------------------------------
#
# Table structure for table 'milestones'
#

DROP TABLE IF EXISTS milestones;
CREATE TABLE milestones (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   devid bigint(20) unsigned DEFAULT '0' NOT NULL,
   number int(8) NOT NULL,
   goals blob NOT NULL,
   release timestamp(14) NOT NULL,
   product varchar(128),
   payment bigint(20) unsigned DEFAULT '0' NOT NULL,
   status char(1) NOT NULL,
   creation timestamp(14)
);

#
# Dumping data for table 'milestones'
#


# --------------------------------------------------------
#
# Table structure for table 'monitor'
#

DROP TABLE IF EXISTS monitor;
CREATE TABLE monitor (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   username varchar(16) NOT NULL,
   importance varchar(16) NOT NULL,
   creation timestamp(14)
);

#
# Dumping data for table 'monitor'
#

# --------------------------------------------------------
#
# Table structure for table 'news'
#

DROP TABLE IF EXISTS news;
CREATE TABLE news (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   user_news varchar(16) NOT NULL,
   subject_news varchar(128) NOT NULL,
   text_news blob NOT NULL,
   creation_news timestamp(14)
);

#
# Dumping data for table 'news'
#

# --------------------------------------------------------
#
# Table structure for table 'ratings'
#

DROP TABLE IF EXISTS ratings;
CREATE TABLE ratings (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   to_whom varchar(16) NOT NULL,
   by_whom varchar(16) NOT NULL,
   rating int(1) unsigned DEFAULT '0' NOT NULL,
   on_what varchar(24) NOT NULL,
   project_importance varchar(16) NOT NULL,
   creation timestamp(14)
);



# --------------------------------------------------------
#
# Table structure for table 'referees'
#

DROP TABLE IF EXISTS referees;
CREATE TABLE referees (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   referee varchar(16) NOT NULL,
   status char(1) NOT NULL,
   creation timestamp(14)
);

#
# Dumping data for table 'referees'
#

# --------------------------------------------------------
#
# Table structure for table 'consultants'
#

DROP TABLE IF EXISTS consultants;
CREATE TABLE consultants (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   consultant varchar(16) NOT NULL,
   status char(1) NOT NULL,
   creation timestamp(14)
);

#
# Dumping data for table 'consultants'
#

# --------------------------------------------------------
#
# Table structure for table 'involved'
#

DROP TABLE IF EXISTS involved;
CREATE TABLE involved (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   involved_sponsor varchar(16) NOT NULL,
   involved_text blob NOT NULL,
   max_sum_of_money int(8),
   interest int(1),
   status char(1) NOT NULL,
   creation_involved timestamp(14)
);

#
# Dumping data for table 'involved'
#

# --------------------------------------------------------
#
# Table structure for table 'developing'
#

DROP TABLE IF EXISTS developing;
CREATE TABLE developing (
   devid bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   content_id bigint(20) unsigned DEFAULT '0' NOT NULL,
   developer varchar(16) NOT NULL,
   cost int(8) NOT NULL,
   license varchar(64) NOT NULL,
   status char(1) NOT NULL,
   cooperation varchar(64) NOT NULL,
   valid bigint(14) NOT NULL,
   start bigint(14) NOT NULL,
   duration int(4) NOT NULL,
   creation timestamp(14),
   UNIQUE devid (devid)
);

#
# Dumping data for table 'developing'
#

# --------------------------------------------------------
#
# Table structure for table 'sponsoring'
#

DROP TABLE IF EXISTS sponsoring;
CREATE TABLE sponsoring (
   spoid bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   sponsor varchar(16) NOT NULL,
   budget int(8) NOT NULL,
   status char(1) NOT NULL,
   sponsoring_text blob,
   valid bigint(14),
   begin bigint(14),
   finish bigint(14),
   creation timestamp(14),
   UNIQUE spoid (spoid)
);

#
# Dumping data for table 'sponsoring'
#

# --------------------------------------------------------
#
# Table structure for table 'description'
#

DROP TABLE IF EXISTS description;
CREATE TABLE description (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,
   project_title varchar(128) NOT NULL,
   type varchar(16) NOT NULL,
   description blob NOT NULL,
   description_user varchar(16) NOT NULL,
   volume varchar(16) NOT NULL,
   status int(1) NOT NULL,
   description_creation timestamp(14),
   UNIQUE proid (proid)
);

#
# Dumping data for table 'description'
#

# --------------------------------------------------------
#
# Table structure for table 'tech_content'
#

DROP TABLE IF EXISTS tech_content;
CREATE TABLE tech_content (
   content_id bigint(20) unsigned DEFAULT '0' NOT NULL auto_increment,
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
#   license varchar(64),
   skills varchar(64),
   platform varchar(64),
   architecture varchar(64),
   environment varchar(64),
   docs varchar(255),
   specification blob,
#   cost int(8),
   content_user varchar(16) NOT NULL,
   status char(1) NOT NULL,
   creation timestamp(14),
   UNIQUE content_id (content_id)
);

#
# Dumping data for table 'tech_content'
#

# --------------------------------------------------------
#
# Table structure for table 'views'
#

DROP TABLE IF EXISTS views;
CREATE TABLE views (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   configure varchar(24) DEFAULT 'Project Participants' NOT NULL,
   views varchar(24) DEFAULT 'Project Participants' NOT NULL,
   news varchar(24) DEFAULT 'Project Initiator' NOT NULL,
   comments varchar(24) DEFAULT 'Registered' NOT NULL,
   history varchar(24) DEFAULT 'Everybody' NOT NULL,
   step3 varchar(24) DEFAULT 'Everybody' NOT NULL,
   step4 varchar(24) DEFAULT 'Everybody' NOT NULL,
   step5 varchar(24) DEFAULT 'Everybody' NOT NULL,
   cooperation varchar(24) DEFAULT 'Everybody' NOT NULL,
   creation timestamp(14)
);

#
# Dumping data for table 'views'
#

# --------------------------------------------------------
#
# Table structure for table 'follow_up'
#

DROP TABLE IF EXISTS follow_up;
CREATE TABLE follow_up (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   milestone_number int(8) unsigned NOT NULL,
   iteration int(8) DEFAULT '1' NOT NULL,     # Iteration process number
                                              # from 1-5
   location varchar(255) NOT NULL,        # URL where the release can be found
   count int(3) DEFAULT '1' NOT NULL      # Number of times this milestone has been
                                          # released
);


