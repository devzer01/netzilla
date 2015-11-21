-- phpMyAdmin SQL Dump
-- version 2.8.1-Debian-1~dapper1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 07, 2007 at 06:19 PM
-- Server version: 5.0.22
-- PHP Version: 5.1.2
-- 
-- Database: `flirtpub`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `card`
-- 

CREATE TABLE `card` (
  `id` smallint(4) unsigned NOT NULL auto_increment,
  `cardpath` varchar(255) collate utf8_unicode_ci NOT NULL,
  `cardtmp` varchar(255) collate utf8_unicode_ci NOT NULL,
  `cardshow` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `card`
-- 

INSERT INTO `card` (`id`, `cardpath`, `cardtmp`, `cardshow`) VALUES (1, 'images/card/img-1.jpeg', 'images/card/thumb/img-1.jpeg', 1),
(7, 'images/card/img-7.jpeg', 'images/card/thumb/img-7.jpeg', 1),
(4, 'images/card/img-4.jpeg', 'images/card/thumb/img-4.jpeg', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `card_log`
-- 

CREATE TABLE `card_log` (
  `id` mediumint(7) unsigned NOT NULL auto_increment,
  `card_id` smallint(4) unsigned NOT NULL,
  `parent_id` mediumint(7) unsigned NOT NULL,
  `child_id` mediumint(7) unsigned NOT NULL,
  `datetime` datetime NOT NULL,
  `message` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `card_log`
-- 

INSERT INTO `card_log` (`id`, `card_id`, `parent_id`, `child_id`, `datetime`, `message`) VALUES (1, 4, 15, 18, '2007-05-07 18:05:43', '123123123132'),
(2, 4, 15, 18, '2007-05-07 18:05:29', '455555');

-- --------------------------------------------------------

-- 
-- Table structure for table `config`
-- 

CREATE TABLE `config` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `value` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=116 ;

-- 
-- Dumping data for table `config`
-- 

INSERT INTO `config` (`id`, `name`, `value`) VALUES (1, 'TABLE_MEMBER', 'member'),
(2, 'TABLE_MEMBER_ID', 'id'),
(3, 'TABLE_MEMBER_USERNAME', 'username'),
(4, 'TABLE_MEMBER_PASSWORD', 'password'),
(5, 'TABLE_MEMBER_EMAIL', 'email'),
(6, 'TABLE_MEMBER_PICTURE', 'picturepath'),
(7, 'TABLE_MEMBER_SESSION', 'member_session'),
(8, 'TABLE_MEMBER_SESSION_MEMBER_ID', 'member_id'),
(9, 'TABLE_MEMBER_SESSION_SESSION_ID', 'session_id'),
(10, 'TABLE_MEMBER_SESSION_SESSION_DATETIME', 'session_datetime'),
(11, 'TABLE_MESSAGE_INBOX', 'message_inbox'),
(12, 'TABLE_MESSAGE_INBOX_TO', 'to_id'),
(13, 'URL_WEB', 'http://server/Fpub2/'),
(14, 'UPLOAD_DIR', 'thumbs/'),
(15, 'TABLE_MESSAGE_INBOX_FROM', 'from_id'),
(16, 'TABLE_MESSAGE_INBOX_SUBJECT', 'subject'),
(17, 'TABLE_MESSAGE_INBOX_MESSAGE', 'message'),
(18, 'TABLE_MESSAGE_INBOX_DATETIME', 'datetime'),
(19, 'TABLE_MESSAGE_INBOX_ARCHIVE', 'archive'),
(20, 'MESSAGE_PAGE_LIMIT', '9'),
(21, 'MESSAGE_RECORD_LIMIT', '15'),
(22, 'TABLE_MESSAGE_INBOX_ID', 'id'),
(23, 'TABLE_MESSAGE_OUTBOX', 'message_outbox'),
(24, 'TABLE_MESSAGE_OUTBOX_ID', 'id'),
(25, 'TABLE_MESSAGE_OUTBOX_TO', 'to_id'),
(26, 'TABLE_MESSAGE_OUTBOX_FROM', 'from_id'),
(27, 'TABLE_MESSAGE_OUTBOX_SUBJECT', 'subject'),
(28, 'TABLE_MESSAGE_OUTBOX_MESSAGE', 'message'),
(29, 'TABLE_MESSAGE_OUTBOX_DATETIME', 'datetime'),
(30, 'TABLE_LONELYHEART', 'lonely_heart_ads'),
(31, 'TABLE_LONELYHEART_ID', 'id'),
(32, 'TABLE_LONELYHEART_USERID', 'userid'),
(33, 'TABLE_LONELYHEART_TARGET', 'target'),
(34, 'TABLE_LONELYHEART_CATEGORY', 'category'),
(35, 'TABLE_LONELYHEART_HEADLINE', 'headline'),
(36, 'TABLE_LONELYHEART_TEXT', 'text'),
(37, 'TABLE_LONELYHEART_DATETIME', 'datetime'),
(38, 'WEB_DIR', 'C:/AppServ/www/flirtpub/'),
(39, 'TABLE_MEMBER_SIGNUP_DATETIME', 'signup_datetime'),
(40, 'TABLE_MEMBER_BIRTHDAY', 'birthday'),
(41, 'TABLE_MEMBER_GENDER', 'gender'),
(42, 'TABLE_MEMBER_COUNTRY', 'country'),
(43, 'TABLE_MEMBER_CITY', 'city'),
(44, 'TABLE_MEMBER_APPEARANCE', 'appearance'),
(45, 'TABLE_MEMBER_EYE', 'eyescolor'),
(46, 'TABLE_MEMBER_HAIRCOLOR', 'haircolor'),
(47, 'TABLE_MEMBER_HAIRLENGTH', 'hairlength'),
(48, 'TABLE_MEMBER_BEARD', 'beard'),
(49, 'TABLE_MEMBER_ZODIAC', 'zodiac'),
(50, 'TABLE_MEMBER_CIVIL', 'civilstatus'),
(51, 'TABLE_MEMBER_SEXUALITY', 'sexuality'),
(52, 'TABLE_MEMBER_TATTOS', 'tattos'),
(53, 'TABLE_MEMBER_SMOKING', 'smoking'),
(54, 'TABLE_MEMBER_GLASSES', 'glasses'),
(55, 'TABLE_MEMBER_HANDICAPPED', 'handicapped'),
(56, 'TABLE_MEMBER_PIERCINGS', 'piercings'),
(57, 'TABLE_MEMBER_LOOKMEN', 'lookmen'),
(58, 'TABLE_MEMBER_LOOKWOMEN', 'lookwomen'),
(59, 'TABLE_MEMBER_LOOKPAIRS', 'lookpairs'),
(60, 'TABLE_MEMBER_RELATIONSHIP', 'relationship'),
(61, 'TABLE_MEMBER_ONENIGHTSTAND', 'onenightstand'),
(62, 'TABLE_MEMBER_AFFAIR', 'affair'),
(63, 'TABLE_MEMBER_FRIENDSHIP', 'friendship'),
(64, 'TABLE_MEMBER_CYBERSEX', 'cybersex'),
(65, 'TABLE_MEMBER_PICTURE_SWAP', 'picture_swapping'),
(66, 'TABLE_MEMBER_LIVE_DATING', 'live_dating'),
(67, 'TABLE_MEMBER_ROLE_PLAYING', 'role_playing'),
(68, 'TABLE_MEMBER_S_M', 's_m'),
(69, 'TABLE_MEMBER_PARTNER_EX', 'partner_exchange'),
(70, 'TABLE_MEMBER_VOYEURISM', 'voyeurism'),
(72, 'TABLE_FAVORITE', 'favorite'),
(73, 'TABLE_FAVORITE_ID', 'id'),
(74, 'TABLE_FAVORITE_PARENT', 'parent_id'),
(75, 'TABLE_FAVORITE_CHILD', 'child_id'),
(76, 'TABLE_FAVORITE_DATETIME', 'datetime'),
(77, 'TABLE_MEMBER_AREA', 'area'),
(78, 'TABLE_MEMBER_HEIGHT', 'height'),
(79, 'TABLE_MEMBER_WEIGHT', 'weight'),
(80, 'TABLE_CARD', 'card'),
(81, 'TABLE_CARD_ID', 'id'),
(82, 'TABLE_CARD_CARDPATH', 'cardpath'),
(83, 'TABLE_CARDLOG', 'card_log'),
(84, 'TABLE_CARDLOG_ID', 'id'),
(85, 'TABLE_CARDLOG_CARD', 'card_id'),
(86, 'TABLE_CARDLOG_PARENT', 'parent_id'),
(87, 'TABLE_CARDLOG_CHILD', 'child_id'),
(88, 'TABLE_CARDLOG_DATETIME', 'datetime'),
(89, 'UPLOAD_DIR_CARD', 'images/card/'),
(90, 'TABLE_CARD_CARDTMP', 'cardtmp'),
(91, 'TABLE_CARD_CARDSHOW', 'cardshow'),
(92, 'TABLE_MEMBER_STATUS', 'status'),
(93, 'SMS_MIN_CHAR', '0'),
(94, 'SMS_MAX_CHAR', '150'),
(95, 'SMS_KEYWORD', 'www.westkit.com'),
(96, 'SMS_FREE', '5'),
(97, 'SMS_FRANCHKEY', '456565'),
(98, 'SMS_PID', '453'),
(99, 'SMS_SMSNR', '44'),
(100, 'SMS_SERVERIP', '192.168.1.253'),
(101, 'SMS_SOCKETIP', '192.168.1.250'),
(102, 'SMS_TRY_COUNT', '3'),
(103, 'TABLE_PAIR', 'pair_id'),
(104, 'TABLE_PAIR_ID', 'id'),
(105, 'TABLE_PAIR_PARENT', 'parent_id'),
(106, 'TABLE_PAIR_CHILD', 'child_id'),
(107, 'TABLE_PAIR_DATETIME', 'datetime'),
(108, 'MAIL_FROM', 'sl@westkit.com'),
(109, 'TABLE_MEMBER_VALIDATION', 'validation_code'),
(110, 'TABLE_MEMBER_ISACTIVE', 'isactive'),
(111, 'TABLE_MEMBER_SIGNIN_DATETIME', 'signin_datetime'),
(112, 'TABLE_MESSAGE_ALERT', 'message_alert'),
(113, 'TABLE_MESSAGE_ALERT_MASSAGE_ID', 'message_id'),
(114, 'TABLE_MESSAGE_ALERT_DATETIME', 'alert_datetime'),
(115, 'TABLE_CARDLOG_MESSAGE', 'message');

-- --------------------------------------------------------

-- 
-- Table structure for table `favorite`
-- 

CREATE TABLE `favorite` (
  `id` mediumint(7) unsigned NOT NULL auto_increment,
  `parent_id` mediumint(7) unsigned NOT NULL,
  `child_id` mediumint(7) unsigned NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;

-- 
-- Dumping data for table `favorite`
-- 

INSERT INTO `favorite` (`id`, `parent_id`, `child_id`, `datetime`) VALUES (6, 14, 8, '2007-04-26 18:04:27'),
(5, 14, 14, '2007-04-26 18:04:54'),
(19, 15, 14, '2007-04-30 11:04:53'),
(20, 15, 8, '2007-04-30 11:04:09'),
(27, 8, 18, '2007-05-04 16:05:04'),
(22, 15, 38, '2007-05-04 15:05:17'),
(29, 8, 40, '2007-05-04 16:05:07'),
(26, 8, 38, '2007-05-04 16:05:02'),
(28, 8, 14, '2007-05-04 16:05:58'),
(30, 8, 39, '2007-05-04 16:05:13'),
(31, 15, 39, '2007-05-04 18:05:20');

-- --------------------------------------------------------

-- 
-- Table structure for table `handystatus`
-- 

CREATE TABLE `handystatus` (
  `handynr` varchar(20) collate utf8_unicode_ci NOT NULL default '0',
  `code` varchar(12) collate utf8_unicode_ci NOT NULL,
  `try` smallint(6) NOT NULL default '0',
  `status` enum('b','c','o') collate utf8_unicode_ci NOT NULL default 'c',
  `bannedUntil` int(15) NOT NULL default '0',
  `kdseit` bigint(20) default '0',
  `firstsms` datetime default NULL,
  `werbung` enum('y','n') collate utf8_unicode_ci NOT NULL default 'y',
  `sms` tinyint(10) NOT NULL default '0',
  `email` varchar(255) collate utf8_unicode_ci NOT NULL,
  `sent` int(20) NOT NULL default '0',
  `keyword` varchar(32) collate utf8_unicode_ci NOT NULL default 'www.westkit.com',
  PRIMARY KEY  (`handynr`),
  UNIQUE KEY `handynr` (`handynr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `handystatus`
-- 

INSERT INTO `handystatus` (`handynr`, `code`, `try`, `status`, `bannedUntil`, `kdseit`, `firstsms`, `werbung`, `sms`, `email`, `sent`, `keyword`) VALUES ('0150111111', '8459', 2, 'o', 0, 1178128459, '2007-05-03 00:54:19', 'n', 1, '', 1178128510, 'www.westkit.com'),
('015012345', '8066', 1, 'o', 0, 1178128066, '2007-05-03 00:47:46', 'n', 1, '', 1178128093, 'www.westkit.com'),
('01502222', '0', 0, 'b', 1924623498, 1178127493, '2007-05-03 00:38:13', 'n', 0, '', 1178127493, 'www.westkit.com'),
('0150666', '0', 0, 'b', 1924663392, 1178167381, '2007-05-03 11:43:01', 'n', 0, '', 1178167381, 'www.westkit.com'),
('0150123456', '5917', 0, 'c', 0, 1178175917, '2007-05-03 14:05:17', 'y', 0, '', 1178175917, 'www.westkit.com'),
('0150123212', '5990', 0, 'c', 0, 1178175990, '2007-05-03 14:06:30', 'y', 0, '', 1178175990, 'www.westkit.com'),
('01503333', '0', 0, 'b', 1924672441, 1178176159, '2007-05-03 14:09:19', 'n', 4, '', 1178176433, 'www.westkit.com'),
('01504234242', '6451', 1, 'c', 0, 1178176451, '2007-05-03 14:14:11', 'n', 0, '', 1178176451, 'www.westkit.com');

-- --------------------------------------------------------

-- 
-- Table structure for table `lonely_heart_ads`
-- 

CREATE TABLE `lonely_heart_ads` (
  `id` mediumint(7) unsigned NOT NULL auto_increment,
  `userid` mediumint(7) unsigned NOT NULL,
  `target` tinyint(1) NOT NULL,
  `category` tinyint(1) NOT NULL,
  `headline` varchar(100) collate utf8_unicode_ci NOT NULL,
  `text` varchar(800) collate utf8_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `lonely_heart_ads`
-- 

INSERT INTO `lonely_heart_ads` (`id`, `userid`, `target`, `category`, `headline`, `text`, `datetime`) VALUES (3, 8, 4, 2, 'hbkjjnbjnkn', '555g', '2007-04-23 10:55:27'),
(4, 0, 1, 1, '', '', '0000-00-00 00:00:00'),
(8, 14, 2, 2, 'qwe', 'qwe', '0000-00-00 00:00:00'),
(7, 15, 1, 1, 'tesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesetteset', 'tesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesettesetteset', '0000-00-00 00:00:00');

-- --------------------------------------------------------

-- 
-- Table structure for table `member`
-- 

CREATE TABLE `member` (
  `id` mediumint(7) unsigned NOT NULL auto_increment,
  `validation_code` varchar(6) collate utf8_unicode_ci NOT NULL,
  `username` varchar(20) collate utf8_unicode_ci NOT NULL,
  `password` varchar(20) collate utf8_unicode_ci NOT NULL,
  `email` varchar(255) collate utf8_unicode_ci NOT NULL,
  `gender` tinyint(1) unsigned NOT NULL,
  `birthday` date NOT NULL,
  `country` tinyint(3) unsigned NOT NULL,
  `city` mediumint(5) unsigned NOT NULL,
  `area` varchar(5) collate utf8_unicode_ci NOT NULL,
  `height` tinyint(3) unsigned NOT NULL,
  `weight` tinyint(3) unsigned NOT NULL,
  `appearance` tinyint(1) NOT NULL,
  `eyescolor` tinyint(1) NOT NULL,
  `haircolor` tinyint(1) NOT NULL,
  `hairlength` tinyint(1) NOT NULL,
  `beard` tinyint(1) NOT NULL,
  `zodiac` tinyint(2) NOT NULL,
  `civilstatus` tinyint(1) NOT NULL,
  `sexuality` tinyint(1) NOT NULL,
  `tattos` tinyint(1) NOT NULL,
  `smoking` tinyint(1) NOT NULL,
  `glasses` tinyint(1) NOT NULL,
  `handicapped` tinyint(1) NOT NULL,
  `piercings` tinyint(1) NOT NULL,
  `lookmen` tinyint(1) NOT NULL,
  `lookwomen` tinyint(1) NOT NULL,
  `lookpairs` tinyint(1) NOT NULL,
  `minage` tinyint(2) NOT NULL,
  `maxage` tinyint(2) NOT NULL,
  `relationship` tinyint(1) NOT NULL,
  `onenightstand` tinyint(1) NOT NULL,
  `affair` tinyint(1) NOT NULL,
  `friendship` tinyint(1) NOT NULL,
  `cybersex` tinyint(1) NOT NULL,
  `picture_swapping` tinyint(1) NOT NULL,
  `live_dating` tinyint(1) NOT NULL,
  `role_playing` tinyint(1) NOT NULL,
  `s_m` tinyint(1) NOT NULL,
  `partner_exchange` tinyint(1) NOT NULL,
  `voyeurism` tinyint(1) NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  `picturepath` varchar(255) collate utf8_unicode_ci NOT NULL,
  `signup_datetime` datetime NOT NULL,
  `signin_datetime` datetime NOT NULL,
  `isactive` tinyint(1) NOT NULL default '1',
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=41 ;

-- 
-- Dumping data for table `member`
-- 

INSERT INTO `member` (`id`, `validation_code`, `username`, `password`, `email`, `gender`, `birthday`, `country`, `city`, `area`, `height`, `weight`, `appearance`, `eyescolor`, `haircolor`, `hairlength`, `beard`, `zodiac`, `civilstatus`, `sexuality`, `tattos`, `smoking`, `glasses`, `handicapped`, `piercings`, `lookmen`, `lookwomen`, `lookpairs`, `minage`, `maxage`, `relationship`, `onenightstand`, `affair`, `friendship`, `cybersex`, `picture_swapping`, `live_dating`, `role_playing`, `s_m`, `partner_exchange`, `voyeurism`, `description`, `picturepath`, `signup_datetime`, `signin_datetime`, `isactive`, `status`) VALUES (8, '', 'test123', '123123', 'test@test.com', 1, '1983-05-04', 1, 27, '180', 180, 75, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 18, 25, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 'sss', '8/winnie_the_pooh_2.jpg', '2007-04-10 00:00:00', '2007-05-07 15:05:37', 1, 2),
(14, '', 'test1234', '123123', 'sk143281@yahoo.com', 1, '1983-05-04', 1, 27, '80160', 185, 75, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 18, 18, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'xxx', '14/284.jpg', '1983-04-30 00:00:00', '2007-05-07 10:05:57', 1, 0),
(15, '', 'superadmin', '123123', 'sk14328@yahoo.com', 1, '0000-00-00', 1, 27, '11', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 18, 18, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '111', '15/amazon_01.jpg', '0000-00-00 00:00:00', '2007-05-07 18:05:32', 1, 1),
(18, '', 'test12345', 'FqCl6L', 'taikgnudap@hotmail.com', 1, '1983-05-07', 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 18, 36, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, '', '', '2007-04-30 15:04:48', '0000-00-00 00:00:00', 1, 0),
(38, '4Xo6ed', 'sk14328', '8YQzUm', 'sl@westkit.com', 1, '1917-01-01', 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 18, 18, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, '', '', '2007-05-04 14:05:04', '0000-00-00 00:00:00', 0, 3),
(39, 'nDNt63', 'susany', 'utAf8K', 'sss@sss.com', 2, '1917-01-01', 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 18, 65, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, '', '', '2007-05-04 16:05:51', '0000-00-00 00:00:00', 1, 3),
(40, 'emAL9P', 'LuisXii', 'z0rbd7', 'ex_hong@hotmail.com', 1, '1917-01-01', 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 18, 18, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, '', '', '2007-05-04 16:05:33', '0000-00-00 00:00:00', 1, 3);

-- --------------------------------------------------------

-- 
-- Table structure for table `member_session`
-- 

CREATE TABLE `member_session` (
  `member_id` mediumint(7) unsigned NOT NULL,
  `session_id` varchar(25) collate utf8_unicode_ci NOT NULL,
  `session_datetime` datetime NOT NULL,
  PRIMARY KEY  (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Dumping data for table `member_session`
-- 

INSERT INTO `member_session` (`member_id`, `session_id`, `session_datetime`) VALUES (8, '7fb0e12a69742ef95ef0452c8', '2007-04-03 10:52:14'),
(14, '209719f5a3575c5d4f2d15895', '2007-04-23 14:44:16'),
(15, '039a1cc87f2ef09a633101b1c', '2007-04-27 15:04:32'),
(37, 'b8eef45443b3b48201fd93173', '2007-05-04 13:05:04');

-- --------------------------------------------------------

-- 
-- Table structure for table `message_alert`
-- 

CREATE TABLE `message_alert` (
  `message_id` bigint(20) NOT NULL,
  `alert_datetime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `message_alert`
-- 

INSERT INTO `message_alert` (`message_id`, `alert_datetime`) VALUES (49, '2007-05-07 15:05:30'),
(39, '2007-05-07 15:05:25'),
(38, '2007-05-07 15:05:19'),
(34, '2007-05-07 15:05:14'),
(36, '2007-05-07 15:05:09'),
(37, '2007-05-07 15:05:03');

-- --------------------------------------------------------

-- 
-- Table structure for table `message_inbox`
-- 

CREATE TABLE `message_inbox` (
  `id` mediumint(7) unsigned NOT NULL auto_increment,
  `to_id` mediumint(7) unsigned NOT NULL,
  `from_id` mediumint(7) unsigned NOT NULL,
  `subject` varchar(255) collate utf8_unicode_ci NOT NULL,
  `message` text collate utf8_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `archive` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

-- 
-- Dumping data for table `message_inbox`
-- 

INSERT INTO `message_inbox` (`id`, `to_id`, `from_id`, `subject`, `message`, `datetime`, `archive`) VALUES (22, 8, 8, '123', '123', '2007-05-04 09:05:00', 0),
(23, 8, 15, 'test', 'teset', '2007-05-04 16:05:56', 0),
(37, 40, 15, '456456', '489789789', '2007-05-07 13:05:37', 0),
(36, 40, 15, '456456', '489789789', '2007-05-07 12:05:14', 0),
(32, 15, 15, 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'xxxx', '2007-05-04 17:05:42', 1),
(34, 18, 15, '555', '6666', '2007-05-07 11:05:32', 0),
(35, 14, 15, '666', '7777', '2007-05-07 12:05:18', 0),
(38, 40, 15, '456456', '489789789', '2007-05-07 13:05:20', 0),
(39, 40, 15, '456456', '489789789', '2007-05-07 13:05:14', 0),
(51, 15, 8, 'test123', '123', '2007-05-07 15:05:36', 0),
(50, 15, 8, 'test', 'test', '2007-05-07 15:05:46', 0),
(49, 38, 15, 'test', '5555', '2007-05-07 14:05:02', 0),
(48, 15, 15, 'test', 'test', '2007-05-07 13:05:43', 0),
(47, 15, 15, 'test', 'test', '2007-05-07 13:05:41', 0),
(46, 15, 15, 'test', 'test', '2007-05-07 13:05:39', 0),
(53, 18, 15, 'Happy Birth Day', '#HPB#1', '2007-05-07 18:05:43', 0),
(54, 18, 15, 'Happy Birth Day', '#HPB#2', '2007-05-07 18:05:29', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `message_outbox`
-- 

CREATE TABLE `message_outbox` (
  `id` mediumint(7) unsigned NOT NULL auto_increment,
  `to_id` mediumint(7) unsigned NOT NULL,
  `from_id` mediumint(7) unsigned NOT NULL,
  `subject` varchar(255) collate utf8_unicode_ci NOT NULL,
  `message` text collate utf8_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=46 ;

-- 
-- Dumping data for table `message_outbox`
-- 

INSERT INTO `message_outbox` (`id`, `to_id`, `from_id`, `subject`, `message`, `datetime`) VALUES (12, 8, 8, 'teset', 'test', '2007-05-03 16:05:45'),
(13, 8, 8, '123', '123', '2007-05-04 09:05:00'),
(45, 18, 15, 'Happy Birth Day', '#HPB#2', '2007-05-07 18:05:29'),
(44, 18, 15, 'Happy Birth Day', '#HPB#1', '2007-05-07 18:05:43'),
(42, 15, 8, 'test123', '123', '2007-05-07 15:05:36'),
(39, 15, 15, 'test', 'test', '2007-05-07 13:05:43'),
(40, 38, 15, 'test', '5555', '2007-05-07 14:05:02'),
(41, 15, 8, 'test', 'test', '2007-05-07 15:05:46'),
(38, 15, 15, 'test', 'test', '2007-05-07 13:05:41'),
(37, 15, 15, 'test', 'test', '2007-05-07 13:05:39');

-- --------------------------------------------------------

-- 
-- Table structure for table `pair`
-- 

CREATE TABLE `pair` (
  `id` mediumint(7) unsigned NOT NULL auto_increment,
  `parent_id` mediumint(7) unsigned NOT NULL,
  `child_id` mediumint(7) unsigned NOT NULL,
  `isactive` tinyint(1) NOT NULL default '0',
  `datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `pair`
-- 

