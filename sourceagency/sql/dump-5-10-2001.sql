# phpMyAdmin MySQL-Dump
# http://phpwizard.net/phpMyAdmin/
#
# Host: localhost Database : sourceagency

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

#
# Dumping data for table 'active_sessions'
#

INSERT INTO active_sessions VALUES ( 'e43307d0f7efffa6c5122eb547afdbc1', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnZm9ybSc7ICRHTE9CQUxTWydhdXRoJ10tPmF1dGhbJ3Blcm0nXSA9ICcnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydleHAnXSA9ICcyMTQ3NDgzNjQ3JzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsncmVmcmVzaCddID0gJzIxNDc0ODM2NDcnOyA=', '20011005210759');
INSERT INTO active_sessions VALUES ( '19336211b2aed029fcb75a63b9ada2c3', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnZjRjNzU2ZGZjOGU1NWExMzFkMmE3ZDRiZTllM2UxMWInOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydwZXJtJ10gPSAnZGV2ZWwnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydleHAnXSA9ICcxMDAyMzAyODEzJzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsncmVmcmVzaCddID0gJzEwMDIyODczMzcnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWyd1bmFtZSddID0gJ3JpZXNzZW4nOyA=', '20011005165654');
INSERT INTO active_sessions VALUES ( 'd0eaae31dcd4f1d89b4a2d270ac892b5', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnYzhhMTc0ZTBiZGRhMjAxMWZmNzk4YjIwZjIxOWFkYzUnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydwZXJtJ10gPSAnZWRpdG9yLGFkbWluJzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsnZXhwJ10gPSAnMTAwMjMxMjgyOSc7ICRHTE9CQUxTWydhdXRoJ10tPmF1dGhbJ3JlZnJlc2gnXSA9ICcxMDAyMzAzODA5JzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndW5hbWUnXSA9ICdhZG1pbic7IA==', '20011005194349');
INSERT INTO active_sessions VALUES ( 'ff3600452763778c683c51340e2ebb43', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnMzViM2RiNDg5NDRkYmE3YTRlMjcyOTI2ZmQwZDI4MzknOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydwZXJtJ10gPSAnZGV2ZWwnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydleHAnXSA9ICcxMDAyMjk1ODczJzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsncmVmcmVzaCddID0gJzEwMDIyODY4NzEnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWyd1bmFtZSddID0gJ2hlbGl4Jzsg', '20011005150113');
INSERT INTO active_sessions VALUES ( 'be005a4b2dc89cbca8130781bcefedef', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==', '20011005111232');
INSERT INTO active_sessions VALUES ( '7e8ec532ba69296863bb8bffa9c069a0', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==', '20011005111323');
INSERT INTO active_sessions VALUES ( '2bddd01485f4ddf3f01effe54cac9a10', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==', '20011005190506');
INSERT INTO active_sessions VALUES ( 'd1e2cf5fe3a56573ad8f43324c84ffb6', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==', '20011005190713');
INSERT INTO active_sessions VALUES ( '9f0a1492f1a862a577fb691e333685a4', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==', '20011005191111');
INSERT INTO active_sessions VALUES ( 'f315c984ee0022f772b47318e2b30695', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnMzViM2RiNDg5NDRkYmE3YTRlMjcyOTI2ZmQwZDI4MzknOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydwZXJtJ10gPSAnZGV2ZWwnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydleHAnXSA9ICcxMDAyMjgyNTYyJzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsncmVmcmVzaCddID0gJzEwMDIyNzM1NTAnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWyd1bmFtZSddID0gJ2hlbGl4Jzsg', '20011005111922');
INSERT INTO active_sessions VALUES ( 'c7d48b3ae8521434ff91d27442292a03', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnNDhkYjdhYzAyZTk3NDY0OGUzNDU0YzAyMDIxYjYzMmEnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydwZXJtJ10gPSAnc3BvbnNvcl9wZW5kaW5nJzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsnZXhwJ10gPSAnMTAwMjMwNzMzMic7ICRHTE9CQUxTWydhdXRoJ10tPmF1dGhbJ3JlZnJlc2gnXSA9ICcxMDAyMjk4MjM3JzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndW5hbWUnXSA9ICduaWxpeCc7IA==', '20011005181212');
INSERT INTO active_sessions VALUES ( '008ad7bd6521121424df691ce5825047', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJEdMT0JBTFNbJ2xhJ10gPSAnRW5nbGlzaCc7IA==', '20011005191440');
INSERT INTO active_sessions VALUES ( 'e5c8f9b2a3f996806fd3ebd791a758d7', 'SourceAgency_Session', 'U291cmNlQWdlbmN5X1Nlc3Npb246JHRoaXMtPmluID0gJyc7ICR0aGlzLT5wdCA9IGFycmF5KCk7ICR0aGlzLT5wdFsnbGEnXSA9ICcxJzsgJHRoaXMtPnB0WydhdXRoJ10gPSAnMSc7ICRHTE9CQUxTWydsYSddID0gJ0VuZ2xpc2gnOyAkR0xPQkFMU1snYXV0aCddID0gbmV3IFNvdXJjZUFnZW5jeV9BdXRoOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoID0gYXJyYXkoKTsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndWlkJ10gPSAnNDhkYjdhYzAyZTk3NDY0OGUzNDU0YzAyMDIxYjYzMmEnOyAkR0xPQkFMU1snYXV0aCddLT5hdXRoWydwZXJtJ10gPSAnc3BvbnNvcl9wZW5kaW5nJzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsnZXhwJ10gPSAnMTAwMjMxNTM2OCc7ICRHTE9CQUxTWydhdXRoJ10tPmF1dGhbJ3JlZnJlc2gnXSA9ICcxMDAyMzA2MzY1JzsgJEdMT0JBTFNbJ2F1dGgnXS0+YXV0aFsndW5hbWUnXSA9ICduaWxpeCc7IA==', '20011005202608');

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
INSERT INTO auth_user VALUES ( '42b3cdc7658ed6b3e07b9441c7679b28', 'devel', 'devel', 'devel', 'grex@scouts-es.org', '20010426182520', '20010426182520', 'devel');
INSERT INTO auth_user VALUES ( '740156f449ebc5950546517021fda49d', 'sponsor', 'sponsor', 'sponsor', 'sponsor@scouts-es.org', '20010426182533', '20010426182533', 'sponsor');
INSERT INTO auth_user VALUES ( 'e355cab52005064b27e2913281c90e6c', 'sponsor2', 'sponsor2', 'sponsor2', 'grex@scouts-es.org', '20010810202354', '20010810194213', 'sponsor');
INSERT INTO auth_user VALUES ( 'cc4a0d0cf62626c30e2582bea5a9b8e1', 'sponsor3', 'sponsor3', 'sponsor3', 'grex@scouts-es.org', '20010810202357', '20010810194223', 'sponsor');
INSERT INTO auth_user VALUES ( '0bc9c97ef50bedb734da4f5d4d71efbd', 'devel2', 'devel2', 'devel2', 'grex@scouts-es.org', '20010810202345', '20010810194235', 'devel');
INSERT INTO auth_user VALUES ( '2108146220b239c76d0cd1861ab831b9', 'devel3', 'devel3', 'devel3', 'grex@scouts-es.org', '20010810202349', '20010810194242', 'devel');
INSERT INTO auth_user VALUES ( 'f4c756dfc8e55a131d2a7d4be9e3e11b', 'riessen', 'fu23bar', 'Gerrit Riessen', 'gerrit.riessen@web.de', '20011004151153', '20011004150815', 'devel');
INSERT INTO auth_user VALUES ( '35b3db48944dba7a4e272926fd0d2839', 'helix', 'helix%sa', 'Lutz Henckel', 'lutz.henckel@fokus.gmd.de', '20011005122316', '20011005111603', 'devel');
INSERT INTO auth_user VALUES ( '48db7ac02e974648e3454c02021b632a', 'nilix', 'nilix%sa', 'Lutz Henckel', 'lutz.henckel@fokus.gmd.de', '20011005180845', '20011005180845', 'sponsor');

# --------------------------------------------------------
#
# Table structure for table 'categories'
#

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
   section varchar(64) NOT NULL,
   category varchar(64) NOT NULL
);

#
# Dumping data for table 'categories'
#

INSERT INTO categories VALUES ( 'GNOME', 'Miscellaneous');
INSERT INTO categories VALUES ( 'KDE', 'Miscellaneous');
INSERT INTO categories VALUES ( 'X11', 'Multimedia');

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

INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '1', '0', 'Pos sip.', 'Pos parece que funciona pesfectamente, no?', '20010810183034');
INSERT INTO comments VALUES ( '1', 'devel', 'News', '20010417133952', '1', '0', 'This is a comment to the news', 'And this is its text', '20010810183625');
INSERT INTO comments VALUES ( '1', 'admin', 'News', '20010417133952', '2', '1', 'This is another comment', 'lets see if it works', '20010810190912');
INSERT INTO comments VALUES ( '1', 'admin', 'General', '0', '2', '1', 'Yes, but you did it', 'I mean... if you have done this app, then I surely can imagine you use it ;-)', '20010809180051');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '3', '0', 'This is the subject', 'And this is the body', '20010809191612');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '4', '1', 'This is a comment that references the first one', 'Yes, it works', '20010809193750');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '5', '1', 'This is another comment to the first one', 'C\\\'mon. This time it should work.', '20010809194327');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '6', '1', 'Another comment to the first one', 'This is the last one.', '20010809195950');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '7', '6', 'And this is a comment to the sixth', 'Wow! If this one does work, then you\\\'re finished with comments!', '20010809200028');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '8', '3', 'This is a comment on the third comment', 'And this is the body of such a comment.', '20010809212619');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '9', '7', 'Re: And this is a comment to the sixth', 'Lets see if this works alone...', '20010809213140');
INSERT INTO comments VALUES ( '1', 'sponsor2', 'News', '20010417133952', '3', '2', 'Re:This is another comment', 'It is.', '20010811213348');
INSERT INTO comments VALUES ( '1', 'sponsor2', 'General', '0', '10', '0', 'Another general comment', 'Yes, this works!', '20010811213432');
INSERT INTO comments VALUES ( '1', 'sponsor2', 'Specifications', '1', '1', '0', 'this is a comment to a content', 'Let\\\'s see if everything works ok.', '20010811213812');
INSERT INTO comments VALUES ( '1', 'sponsor2', 'Specifications', '1', '2', '1', 'Re:this is a comment to a content', 'Oh... yeah! This is great! A good design is half of the programming work :-)', '20010811213841');
INSERT INTO comments VALUES ( '1', 'devel2', 'Specifications', '2', '1', '0', 'Comment on Specification #2', 'Comment on the second specification', '20010815215623');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '11', '4', 'Re:This is a comment that references the first one', 'Lets see if this works!', '20010815224842');
INSERT INTO comments VALUES ( '1', 'devel', 'Sponsoring', '1', '1', '0', 'Comment on Sponsor Involvement #1', 'This is a comment to an involvement.', '20010816012436');
INSERT INTO comments VALUES ( '3', 'devel2', 'General', '0', '1', '0', 'I\'m interested in this project', 'Hi, I\'m very interested in this project.', '20010830155052');
INSERT INTO comments VALUES ( '3', 'sponsor', 'General', '0', '2', '1', 'Re:I\'m interested in this project', 'So am I.', '20010830160536');
INSERT INTO comments VALUES ( '3', 'sponsor2', 'News', '20010830165114', '1', '0', 'Re:We are in phase 2!', 'Ok, lets see if this works right!', '20010901115301');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '12', '8', 'Re:This is a comment on the third comment', 'Let\'s see if monitoring works!', '20010908133837');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '13', '8', 'Re:This is a comment on the third comment', 'another time', '20010908133920');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '14', '8', 'Re:This is a comment on the third comment', 'another time', '20010908133946');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '15', '8', 'Re:This is a comment on the third comment', 'another time!!!!
(and this is the third one)', '20010908134010');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '16', '8', 'Re:This is a comment on the third comment', 'another time!!!!
(and this is the third one)', '20010908134235');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '17', '16', 'Re:Re:This is a comment on the third comment', 'Yes... wouw! This looks good.', '20010908134524');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '18', '16', 'Re:Re:This is a comment on the third comment', 'Yes... wouw! This looks good.', '20010908134605');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '19', '16', 'Re:Re:This is a comment on the third comment', 'Yes... wouw! This looks good.', '20010908134626');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '20', '16', 'Re:Re:This is a comment on the third comment', 'Yes... wouw! This looks good.', '20010908134838');
INSERT INTO comments VALUES ( '1', 'devel', 'General', '0', '21', '20', 'Re:Re:Re:This is a comment on the third comment', 'This should only send one mail.', '20010908141100');
INSERT INTO comments VALUES ( '1', 'devel', 'News', '20010417133952', '4', '0', 'Re:This is the headline', 'This is to test another time the mail function!', '20010908142252');
INSERT INTO comments VALUES ( '1', 'devel', 'News', '20010417133952', '5', '4', 'Re:Re:This is the headline', 'Only one!', '20010908171611');
INSERT INTO comments VALUES ( '1', 'devel', 'News', '20010815223812', '1', '0', 'Re:This is the second news', 'Hi! I want to receive only one email!', '20010908173456');
INSERT INTO comments VALUES ( '1', 'devel', 'Cooperation', '20010908205714', '1', '0', 'This a commento to a cooperation', 'Yeah!', '20010908212245');

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

INSERT INTO configure VALUES ( '1', '65', 'Yes', 'No', NULL, 'sponsor', NULL);

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

INSERT INTO consultants VALUES ( '1', 'devel', 'P', '20010714143555');
INSERT INTO consultants VALUES ( '1', 'admin', 'P', '20010725224813');
INSERT INTO consultants VALUES ( '3', 'devel', 'P', '20010830164222');
INSERT INTO consultants VALUES ( '3', 'devel3', 'P', '20010830164305');
INSERT INTO consultants VALUES ( '2', 'devel2', 'P', '20010831210953');

# --------------------------------------------------------
#
# Table structure for table 'cooperation'
#

DROP TABLE IF EXISTS cooperation;
CREATE TABLE cooperation (
   devid bigint(20) unsigned DEFAULT '0' NOT NULL,
   developer varchar(16) NOT NULL,
   cost int(8) DEFAULT '0' NOT NULL,
   status char(1) NOT NULL,
   creation timestamp(14)
);

#
# Dumping data for table 'cooperation'
#

INSERT INTO cooperation VALUES ( '8', 'devel', '3', 'P', '20010908205714');
INSERT INTO cooperation VALUES ( '1', 'riessen', '564', 'P', '20011005152542');

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

INSERT INTO counter VALUES ( '1', '0', '0');
INSERT INTO counter VALUES ( '3', '0', '0');
INSERT INTO counter VALUES ( '4', '0', '0');
INSERT INTO counter VALUES ( '5', '0', '0');
INSERT INTO counter VALUES ( '6', '0', '0');

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
# Table structure for table 'decisions'
#

DROP TABLE IF EXISTS decisions;
CREATE TABLE decisions (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   step int(8) unsigned DEFAULT '0' NOT NULL,
   decision_user varchar(16) NOT NULL,
   decision varchar(16) NOT NULL
);

#
# Dumping data for table 'decisions'
#

INSERT INTO decisions VALUES ( '1', '1', 'devel', 'devel');
INSERT INTO decisions VALUES ( '1', '1', 'sponsor', 'devel');
INSERT INTO decisions VALUES ( '1', '1', 'admin', 'admin');
INSERT INTO decisions VALUES ( '1', '1', 'sponsor2', 'devel');
INSERT INTO decisions VALUES ( '1', '4', 'sponsor2', 'admin');
INSERT INTO decisions VALUES ( '1', '2', 'sponsor2', '1');
INSERT INTO decisions VALUES ( '1', '3', 'sponsor2', '1');
INSERT INTO decisions VALUES ( '1', '2', 'sponsor', '1');
INSERT INTO decisions VALUES ( '3', '1', 'sponsor2', 'devel3');
INSERT INTO decisions VALUES ( '3', '2', 'sponsor3', '4');
INSERT INTO decisions VALUES ( '3', '2', 'sponsor2', '4');
INSERT INTO decisions VALUES ( '3', '3', 'sponsor2', '6');
INSERT INTO decisions VALUES ( '3', '3', 'sponsor3', '6');

# --------------------------------------------------------
#
# Table structure for table 'decisions_milestones'
#

DROP TABLE IF EXISTS decisions_milestones;
CREATE TABLE decisions_milestones (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   devid bigint(20) unsigned DEFAULT '0' NOT NULL,
   decision_user varchar(16) NOT NULL,
   number int(8) DEFAULT '0' NOT NULL,
   decision varchar(16) NOT NULL
);

#
# Dumping data for table 'decisions_milestones'
#

INSERT INTO decisions_milestones VALUES ( '1', '1', 'sponsor', '1', 'Yes');
INSERT INTO decisions_milestones VALUES ( '1', '1', 'sponsor', '2', 'Yes');
INSERT INTO decisions_milestones VALUES ( '1', '1', 'sponsor', '3', 'Yes');
INSERT INTO decisions_milestones VALUES ( '1', '1', 'sponsor', '4', 'No');
INSERT INTO decisions_milestones VALUES ( '1', '1', 'sponsor', '5', 'Yes');
INSERT INTO decisions_milestones VALUES ( '1', '1', 'sponsor', '6', 'Yes');
INSERT INTO decisions_milestones VALUES ( '1', '1', 'sponsor', '7', 'No');
INSERT INTO decisions_milestones VALUES ( '1', '3', 'sponsor', '1', 'Yes');

# --------------------------------------------------------
#
# Table structure for table 'decisions_step5'
#

DROP TABLE IF EXISTS decisions_step5;
CREATE TABLE decisions_step5 (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   milestone_number int(8) unsigned DEFAULT '0' NOT NULL,
   decision_user varchar(16) NOT NULL,
   decision varchar(16) NOT NULL
);

#
# Dumping data for table 'decisions_step5'
#


# --------------------------------------------------------
#
# Table structure for table 'description'
#

DROP TABLE IF EXISTS description;
CREATE TABLE description (
   proid bigint(20) unsigned NOT NULL auto_increment,
   project_title varchar(128) NOT NULL,
   type varchar(16) NOT NULL,
   description blob NOT NULL,
   description_user varchar(16) NOT NULL,
   volume varchar(16) NOT NULL,
   status int(1) DEFAULT '0' NOT NULL,
   description_creation timestamp(14),
   UNIQUE proid (proid)
);

#
# Dumping data for table 'description'
#

INSERT INTO description VALUES ( '1', 'My First Project (Development)', 'Adaption', 'This is an example development project. It should allow us to make some testing on our system and allow us knowing how our process is working.', 'devel', 'big', '5', '20010511182803');
INSERT INTO description VALUES ( '2', 'My Second Project (Sponsored)', 'Adaption', 'This is an example sponsored project. It should allow us to make some testing on our system and allow us knowing how our process is working.', 'sponsor', 'very big', '1', '20010426163452');
INSERT INTO description VALUES ( '3', 'This is the Sponsor Usability test', 'Development', 'I\'m going to try to follow a project from its beginning to its conclusion and see what problems I see.', 'sponsor2', 'Big', '4', '20010830153820');
INSERT INTO description VALUES ( '4', 'Fubar and Snafu need a new home', 'Development', 'Geez! Fubar and Snafu need a place to
stay ...', 'riessen', 'Very Small', '0', '20011004153230');
INSERT INTO description VALUES ( '5', 'yet another project', 'Development', 'this is yet another projecct ....', 'riessen', 'Regular', '0', '20011004153754');
INSERT INTO description VALUES ( '6', 'Meine Gelddruckmaschine', 'Development', 'Diese Maschine soll Spielgeld drucken können.', 'helix', 'Very Big', '1', '20011005145441');

# --------------------------------------------------------
#
# Table structure for table 'developing'
#

DROP TABLE IF EXISTS developing;
CREATE TABLE developing (
   devid bigint(20) unsigned NOT NULL auto_increment,
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   content_id bigint(20) unsigned DEFAULT '0' NOT NULL,
   developer varchar(16) NOT NULL,
   cost int(8) DEFAULT '0' NOT NULL,
   license varchar(64) NOT NULL,
   status char(1) NOT NULL,
   cooperation varchar(64) NOT NULL,
   valid bigint(14) DEFAULT '0' NOT NULL,
   start bigint(14) DEFAULT '0' NOT NULL,
   duration int(4) DEFAULT '0' NOT NULL,
   creation timestamp(14),
   UNIQUE devid (devid)
);

#
# Dumping data for table 'developing'
#

INSERT INTO developing VALUES ( '1', '1', '1', 'devel', '800', 'GPL', 'N', '15%', '20010725160000', '20010501150000', '3', '20010501234321');
INSERT INTO developing VALUES ( '2', '1', '2', 'devel', '1300', 'BSD type', 'A', 'No', '20010727160000', '20010501850000', '3', '20010502012547');
INSERT INTO developing VALUES ( '3', '1', '1', 'devel', '1900', 'MIT', 'R', 'No', '20010920140000', '20010601950000', '3', '20010502012638');
INSERT INTO developing VALUES ( '4', '3', '4', 'devel', '2300', 'LGPL', 'R', 'No', '20010829001010', '20010829001010', '4', '20010829120000');
INSERT INTO developing VALUES ( '8', '1', '3', 'devel', '350', 'GPL', 'P', '40%', '20020202120000', '20030303120000', '4', '20010908200431');
INSERT INTO developing VALUES ( '6', '3', '4', 'devel3', '200', 'GPL', 'A', 'Yes', '20011231120000', '20020202120000', '2', '20010829012345');

# --------------------------------------------------------
#
# Table structure for table 'faq'
#

DROP TABLE IF EXISTS faq;
CREATE TABLE faq (
   faqid int(8) unsigned NOT NULL auto_increment,
   language varchar(24) NOT NULL,
   question blob NOT NULL,
   answer blob NOT NULL,
   UNIQUE idx_2 (faqid)
);

#
# Dumping data for table 'faq'
#

INSERT INTO faq VALUES ( '1', 'English', 'How to change my Password or E-mail address I am registered with?', 'Select \"<a href=\"chguser.php3\">Change User</a>\" and enter your new parameters.');
INSERT INTO faq VALUES ( '2', 'English', 'Why is the system not in my language?', 'This system can be easily translated into different languages. If you see that we do not have support in your language, you\'re gladly invited to help us with the internationalization. Visit <A HREF=\"http://sourceagency.berlios.de/html/translating.php3\">http://sourceagency.berlios.de/html/translating.php3</A>.');

# --------------------------------------------------------
#
# Table structure for table 'follow_up'
#

DROP TABLE IF EXISTS follow_up;
CREATE TABLE follow_up (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   milestone_number int(8) unsigned DEFAULT '0' NOT NULL,
   iteration int(8) DEFAULT '1' NOT NULL,
   location varchar(255) NOT NULL,
   time int(3) DEFAULT '1' NOT NULL
);

#
# Dumping data for table 'follow_up'
#

INSERT INTO follow_up VALUES ( '1', '1', '2', 'http://www.es.gnome.org', '1');

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
   creation timestamp(14)
);

#
# Dumping data for table 'history'
#

INSERT INTO history VALUES ( '3', 'Project Owners', 'decision', 'Project is now in phase 4', '20010831213747');
INSERT INTO history VALUES ( '1', 'sponsor3', 'Rating', 'Rating by sponsor3 completed', '20010902192420');

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

INSERT INTO licenses VALUES ( 'Apache style', 'http://www.apache.org/docs-2.0/LICENSE');
INSERT INTO licenses VALUES ( 'BSD type', 'http://www.freebsd.org/copyright/license.html');
INSERT INTO licenses VALUES ( 'GPL', 'http://www.gnu.org/copyleft/gpl.html');
INSERT INTO licenses VALUES ( 'LGPL', 'http://www.gnu.org/copyleft/lesser.html');
INSERT INTO licenses VALUES ( 'MIT', 'http://sourceagency.berlios.de/licnotavailable.php3');
INSERT INTO licenses VALUES ( 'MPL', 'http://www.mozilla.org/MPL/');
INSERT INTO licenses VALUES ( 'Open Source', 'http://www.opensource.org/osd.html');
INSERT INTO licenses VALUES ( 'Public Domain', 'http://www.eiffel-forum.org/license/index.htm#pd');
INSERT INTO licenses VALUES ( 'FreeBSD', 'http://www.freebsd.org/copyright/freebsd-license.html');
INSERT INTO licenses VALUES ( 'OpenBSD', 'http://www.openbsd.org/policy.html');
INSERT INTO licenses VALUES ( 'Artistic License', 'http://www.perl.com/language/misc/Artistic.html');
INSERT INTO licenses VALUES ( 'PHP License', 'http://www.php.net/license.html');
INSERT INTO licenses VALUES ( 'X11 License', 'http://www.x.org/terms.htm');
INSERT INTO licenses VALUES ( 'Zope Public License', 'http://www.zope.com/Resources/ZPL');

# --------------------------------------------------------
#
# Table structure for table 'milestones'
#

DROP TABLE IF EXISTS milestones;
CREATE TABLE milestones (
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   devid bigint(20) unsigned DEFAULT '0' NOT NULL,
   number int(8) DEFAULT '0' NOT NULL,
   goals blob NOT NULL,
   release timestamp(14),
   product varchar(128),
   payment bigint(20) unsigned DEFAULT '0' NOT NULL,
   status char(1) NOT NULL,
   creation timestamp(14)
);

#
# Dumping data for table 'milestones'
#

INSERT INTO milestones VALUES ( '1', '1', '1', 'The main developer can propose the milestone planning.', '20030505120000', 'Release Candidate 3', '14', 'M', '20010803145224');
INSERT INTO milestones VALUES ( '1', '1', '2', 'This is the second milestone. If everything has gone well, the sponsor will pay, the developer will be payed, the referee will also get his money for doing also nothing and BerliOS can say this project was successful', '20010615000000', 'Beta', '8', 'N', '20010803145249');
INSERT INTO milestones VALUES ( '1', '1', '3', 'The third milestone. The product is almost ready for being shipped away', '20010619000000', 'PreRelease', '6', 'P', '20010803145357');
INSERT INTO milestones VALUES ( '1', '1', '4', 'The last milestone. It should be considered as the forth one.', '20010627000000', 'Stable', '12', 'P', '20010803145845');
INSERT INTO milestones VALUES ( '1', '3', '1', 'The main developer can propose the milestone planning.', '20030505120000', 'Release Candidate 3', '14', 'M', '20010910210012');
INSERT INTO milestones VALUES ( '1', '1', '5', 'adf', '20020203120000', 'Prototype', '20', 'M', '20020202120000');
INSERT INTO milestones VALUES ( '1', '1', '6', 'The main developer can propose the milestone planning. ', '20020202120000', 'Beta', '20', 'P', '20020202120000');
INSERT INTO milestones VALUES ( '1', '1', '7', 'The main developer can propose the milestone planning. ', '20030606120000', 'Release Candidate 3', '14', 'M', '20030606120000');

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

INSERT INTO monitor VALUES ( '1', 'devel', 'low', '20010906180929');

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

INSERT INTO news VALUES ( '1', 'devel', 'This is the headline', 'Hi, everybody out there. I want you to know, that I am looking for sponsors that make this interesting project very, very big!', '20010417133952');
INSERT INTO news VALUES ( '1', 'devel', 'This is the second news', 'And this its body.', '20010815223812');
INSERT INTO news VALUES ( '3', 'devel', 'I am the project initiator', 'so I can post news on my project.', '20010830162505');
INSERT INTO news VALUES ( '3', 'devel', 'Now in phase 2', 'Hi, this project has reached phase 2! We are looking for project ideas. Go for it!', '20010830164911');
INSERT INTO news VALUES ( '3', 'sponsor2', 'We are in phase 2!', 'Now this news should appear with my name!', '20010830165114');

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

#
# Dumping data for table 'ratings'
#

INSERT INTO ratings VALUES ( '1', 'devel', 'sponsor', '1', 'one', 'big', '20010902133512');
INSERT INTO ratings VALUES ( '1', 'devel', 'sponsor', '2', 'two', 'big', '20010902135033');
INSERT INTO ratings VALUES ( '1', 'devel', 'sponsor', '3', 'three', 'big', '20010902135033');
INSERT INTO ratings VALUES ( '1', 'sponsor3', 'sponsor', '5', 'three', 'big', '20010902133527');
INSERT INTO ratings VALUES ( '1', 'sponsor2', 'sponsor', '3', 'one', 'big', '20010902133507');
INSERT INTO ratings VALUES ( '1', 'sponsor2', 'sponsor', '3', 'two', 'big', '20010902133507');
INSERT INTO ratings VALUES ( '1', 'sponsor2', 'sponsor', '3', 'three', 'big', '20010902133507');
INSERT INTO ratings VALUES ( '1', 'sponsor3', 'sponsor', '5', 'one', 'big', '20010902133527');
INSERT INTO ratings VALUES ( '1', 'sponsor3', 'sponsor', '5', 'two', 'big', '20010902133527');
INSERT INTO ratings VALUES ( '1', 'devel', 'sponsor3', '2', 'one', 'big', '20010902175132');
INSERT INTO ratings VALUES ( '1', 'devel', 'sponsor3', '3', 'two', 'big', '20010902175132');
INSERT INTO ratings VALUES ( '1', 'devel', 'sponsor3', '4', 'three', 'big', '20010902175132');
INSERT INTO ratings VALUES ( '1', 'sponsor', 'sponsor3', '2', 'one', 'big', '20010902175146');
INSERT INTO ratings VALUES ( '1', 'sponsor', 'sponsor3', '3', 'two', 'big', '20010902175146');
INSERT INTO ratings VALUES ( '1', 'sponsor', 'sponsor3', '4', 'three', 'big', '20010902175146');
INSERT INTO ratings VALUES ( '1', 'sponsor2', 'sponsor3', '2', 'one', 'big', '20010902175150');
INSERT INTO ratings VALUES ( '1', 'sponsor2', 'sponsor3', '3', 'two', 'big', '20010902175150');
INSERT INTO ratings VALUES ( '1', 'sponsor2', 'sponsor3', '4', 'three', 'big', '20010902175150');

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

INSERT INTO referees VALUES ( '1', 'devel3', 'A', '20010823120000');

# --------------------------------------------------------
#
# Table structure for table 'sponsoring'
#

DROP TABLE IF EXISTS sponsoring;
CREATE TABLE sponsoring (
   spoid bigint(20) unsigned NOT NULL auto_increment,
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   sponsor varchar(16) NOT NULL,
   budget int(8) DEFAULT '0' NOT NULL,
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

INSERT INTO sponsoring VALUES ( '1', '1', 'sponsor', '200', 'A', 'The permissions needed to access certain pages can be configurated dinamically from the include/config.inc file. This allows to set the permission to admin, editor, user (developer and sponsor) or even all (with the all option, everybody is able to access that page) at your wish.', '20010525160000', '20010502150000', '20010520120000', '20010430000000');
INSERT INTO sponsoring VALUES ( '2', '1', 'sponsor3', '600', 'A', 'We should not allow the same sponsor to make more than one offer to the same project!', '20010525163450', '20010502132400', '20010520120550', '20010822140000');
INSERT INTO sponsoring VALUES ( '3', '1', 'sponsor2', '400', 'A', 'Yes, I want to take part in it.', '20011225140012', '20010812121212', '20020301121222', '20010810202652');
INSERT INTO sponsoring VALUES ( '4', '3', 'sponsor2', '2500', 'A', '', '20010829120000', '20010829120000', '20010829120000', '20010830161250');
INSERT INTO sponsoring VALUES ( '6', '3', 'sponsor3', '2500', 'A', 'I want to sponsor this project.', '20020202120000', '20030303120000', '20040404120000', '20010831121020');
INSERT INTO sponsoring VALUES ( '7', '3', 'sponsor', '1200', 'P', '', '20020202120000', '20040303120000', '20010404120000', '20010901142256');

# --------------------------------------------------------
#
# Table structure for table 'tech_content'
#

DROP TABLE IF EXISTS tech_content;
CREATE TABLE tech_content (
   content_id bigint(20) unsigned NOT NULL auto_increment,
   proid bigint(20) unsigned DEFAULT '0' NOT NULL,
   skills varchar(64),
   platform varchar(64),
   architecture varchar(64),
   environment varchar(64),
   docs varchar(255),
   specification blob,
   content_user varchar(16) NOT NULL,
   status char(1) NOT NULL,
   creation timestamp(14),
   UNIQUE content_id (content_id)
);

#
# Dumping data for table 'tech_content'
#

INSERT INTO tech_content VALUES ( '1', '1', 'GTK, Perl, C', 'Platform 7', 'Architecture 6', 'Environment 5', 'http://sourceagency.berlios.de/html/', 'Take GTK, use Perl and then bind it with C. That was now the technical specification of the project', 'sponsor', 'A', '20010426163452');
INSERT INTO tech_content VALUES ( '2', '1', 'PHP, PHPLib and MySQL', 'Platform 1', 'Architecture 2', 'Environment 3', 'http://www.whatsup.de/', 'Webbased enviroment with lots and lots of different innovative features', 'devel', 'R', '20010602150432');
INSERT INTO tech_content VALUES ( '3', '1', 'C, Java, Javascript and MySQL', 'Platform 8', 'Architecture 5', 'Environment 3', 'http://www.berlios.de', 'Blablablabla', 'devel', 'P', '20010816005806');
INSERT INTO tech_content VALUES ( '4', '3', 'C', 'Platform 8', 'Architecture 8', 'Environment 8', 'http://www.hello.com', 'This is a specification made by the project owner.', 'devel', 'P', '20010830184346');

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

INSERT INTO views VALUES ( '1', 'Project Participants', 'Project Participants', 'Project Initiator', 'Registered', 'Everybody', 'Everybody', 'Everybody', 'Everybody', 'Everybody', '20010921205357');
INSERT INTO views VALUES ( '4', 'Project Participants', 'Project Participants', 'Project Initiator', 'Registered', 'Everybody', 'Everybody', 'Everybody', 'Everybody', 'Everybody', '20011004153230');
INSERT INTO views VALUES ( '5', 'Project Participants', 'Project Participants', 'Project Initiator', 'Registered', 'Everybody', 'Everybody', 'Everybody', 'Everybody', 'Everybody', '20011004153754');
INSERT INTO views VALUES ( '6', 'Project Participants', 'Project Participants', 'Project Initiator', 'Registered', 'Everybody', 'Everybody', 'Everybody', 'Everybody', 'Everybody', '20011005145441');
INSERT INTO views VALUES ( '2', 'Project Participants', 'Project Participants', 'Project Initiator', 'Registered', 'Everybody', 'Everybody', 'Everybody', 'Everybody', 'Everybody', '20010921205357');
INSERT INTO views VALUES ( '3', 'Project Participants', 'Project Participants', 'Project Initiator', 'Registered', 'Everybody', 'Everybody', 'Everybody', 'Everybody', 'Everybody', '20010921205357');
