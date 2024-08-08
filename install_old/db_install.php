<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1980 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.1.1                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2023 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Change for 3.0.3
use JAKWEB\JAKsql;

if (!file_exists('../include/db.php')) die('[install.php] include/db.php not exist');
require_once '../include/db.php';

/* NO CHANGES FROM HERE */
if (!file_exists('../class/class.jaklic.php')) die('It looks like the boat has been reported as missing.');

// Get the ls DB class
require_once '../class/class.db.php';

// Fresh installation
$fresh_install = false;

if (is_numeric($_POST['step']) && $_POST['step'] == 3) {

  $dsn = JAKDB_DBTYPE.':dbname='.JAKDB_NAME.';host='.JAKDB_HOST;

  try {
    $dbh = new PDO($dsn, JAKDB_USER, JAKDB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
      $check_db = false;
      $db_error_msg = $e->getMessage();
  }

  try {
    $dbh->query("SELECT title FROM ".JAKDB_PREFIX."departments WHERE id = 1 LIMIT 1");
  } catch (Exception $e) {
    // We got an exception == table not found
    $fresh_install = true;
  }
    
if ($fresh_install) {

// Database connection
$jakdb = new JAKsql([
    // required
    'database_type' => JAKDB_DBTYPE,
    'database_name' => JAKDB_NAME,
    'server' => JAKDB_HOST,
    'username' => JAKDB_USER,
    'password' => JAKDB_PASS,
    'charset' => 'utf8',
    'port' => JAKDB_PORT,
    'prefix' => JAKDB_PREFIX,
 
    // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [PDO::ATTR_CASE => PDO::CASE_NATURAL]
    ]);

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."answers (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `department` int(10) unsigned NOT NULL DEFAULT '0',
  `lang` varchar(3) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text,
  `fireup` smallint(5) unsigned NOT NULL DEFAULT '60',
  `msgtype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=standard,2=welcome,3=closed,4=expired,5=firstmsg',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `depid` (`opid`,`department`,`lang`,`fireup`,`msgtype`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."autoproactive (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(200) NULL DEFAULT NULL,
  `title` varchar(255) NULL DEFAULT NULL,
  `imgpath` varchar(255) NULL DEFAULT NULL,
  `message` varchar(255) NULL DEFAULT NULL,
  `btn_confirm` VARCHAR(50) NULL DEFAULT NULL,
  `btn_cancel` VARCHAR(50) NULL DEFAULT NULL,
  `showalert` smallint(1) unsigned NOT NULL DEFAULT '1',
  `soundalert` VARCHAR(100) NULL DEFAULT NULL,
  `timeonsite` smallint(3) unsigned NOT NULL DEFAULT '2',
  `visitedsites` smallint(2) unsigned NOT NULL DEFAULT '1',
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `path` (`opid`,`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."urlblacklist (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(200) NULL DEFAULT NULL,
  `title` varchar(255) NULL DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `path` (`opid`,`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."buttonstats (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `depid` int(10) unsigned NOT NULL DEFAULT '0',
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `singleopid` int(10) unsigned NOT NULL DEFAULT '0',
  `referrer` varchar(255) DEFAULT NULL,
  `firstreferrer` varchar(255) DEFAULT NULL,
  `crossurl` varchar(255) DEFAULT NULL,
  `agent` varchar(255) DEFAULT NULL,
  `hits` int(10) NOT NULL DEFAULT '0',
  `ip` char(45) NOT NULL DEFAULT '0',
  `country` varchar(64) DEFAULT NULL,
  `countrycode` CHAR(2) NOT NULL DEFAULT 'xx',
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `readtime` smallint(1) NOT NULL DEFAULT '0',
  `session` varchar(64) DEFAULT NULL,
  `lasttime` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`,`depid`,`session`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."bot_question (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `widgetids` varchar(100) DEFAULT '0',
  `depid` int(10) unsigned NOT NULL DEFAULT '0',
  `lang` varchar(2) DEFAULT NULL,
  `question` text,
  `answer` text,
  `updated` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `widgetids` (`widgetids`, `opid`, `depid`, `lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."chatwidget (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `depid` varchar(50) NOT NULL DEFAULT '0',
  `singleopid` int(10) unsigned NOT NULL DEFAULT '0',
  `lang` char(2) DEFAULT NULL,
  `dsgvo` text,
  `redirect_url` varchar(200) DEFAULT NULL,
  `redirect_active` tinyint(3) unsigned DEFAULT '0',
  `redirect_after` tinyint(3) unsigned DEFAULT '8',
  `feedback` tinyint(3) unsigned DEFAULT '0',
  `hidewhenoff` tinyint(3) unsigned DEFAULT '0',
  `onlymembers` tinyint(3) unsigned DEFAULT '0',
  `template` varchar(20) DEFAULT 'business',
  `avatarset` varchar(20) DEFAULT 'business',
  `btn_tpl` varchar(100) DEFAULT 'icon_bottom_right.php',
  `start_tpl` varchar(100) DEFAULT 'small_big_bottom_right.php',
  `chat_tpl` varchar(100) DEFAULT 'small_big_bottom_right.php',
  `contact_tpl` varchar(100) DEFAULT 'small_big_bottom_right.php',
  `profile_tpl` varchar(100) DEFAULT 'small_big_bottom_right.php',
  `feedback_tpl` varchar(100) DEFAULT 'small_big_bottom_right.php',
  `updated` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `depid` (`depid`, `opid`, `singleopid`, `lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."chatsettings (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `widgetid` int(10) unsigned NOT NULL DEFAULT '0',
  `template` varchar(20) DEFAULT 'business',
  `formtype` varchar(20) DEFAULT NULL,
  `lang` varchar(2) DEFAULT NULL,
  `settname` varchar(100) DEFAULT NULL,
  `settvalue` text,
  `updated` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `widgetid` (`widgetid`,`opid`,`template`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."chatcustomfields (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `convid` int(10) unsigned NOT NULL DEFAULT '0',
  `contactid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) NULL DEFAULT NULL,
  `settname` varchar(100) DEFAULT NULL,
  `settvalue` text,
  `updated` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `convid` (`convid`,`opid`,`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."clientcontact (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `sessionid` int(10) unsigned NOT NULL DEFAULT '0',
  `operatorid` int(10) unsigned NOT NULL DEFAULT '0',
  `operatorname` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `sent` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."contacts (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `depid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `message` text,
  `ip` char(45) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `countrycode` varchar(2) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `reply` smallint(1) unsigned NOT NULL DEFAULT '0',
  `answered` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `sent` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `depid` (`depid`, `opid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."contactsreply (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `contactid` int(10) unsigned NOT NULL DEFAULT '0',
  `operatorid` int(10) unsigned NOT NULL DEFAULT '0',
  `operatorname` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `sent` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `contactid` (`contactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."departments (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `description` mediumtext,
  `email` varchar(255) DEFAULT NULL,
  `faq_url` text,
  `active` smallint(1) unsigned NOT NULL DEFAULT '1',
  `dorder` smallint(2) unsigned NOT NULL DEFAULT '1',
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."files (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `path` text,
  `orig_name` varchar(100) NULL DEFAULT NULL,
  `name` varchar(200) NULL DEFAULT NULL,
  `description` text,
  `mime_type` VARCHAR(20) NOT NULL,
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."groupchat (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(20) NULL DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text NULL DEFAULT NULL,
  `opids` varchar(10) DEFAULT '0',
  `maxclients` tinyint(3) unsigned NOT NULL DEFAULT '20',
  `lang` char(2) DEFAULT NULL,
  `buttonimg` varchar(100) NOT NULL,
  `floatpopup` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `floatcss` varchar(100) DEFAULT NULL,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `opids` (`opids`, `opid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."groupchatmsg (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `groupchatid` int(10) NOT NULL DEFAULT '0',
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `chathistory` mediumtext,
  `operatorid` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `groupchatid` (`groupchatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."groupchatuser (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `groupchatid` int(10) NOT NULL DEFAULT '0',
  `name` varchar(100) NULL DEFAULT NULL,
  `usr_avatar` varchar(255) NULL DEFAULT NULL,
  `statusc` int(10) unsigned NOT NULL DEFAULT '0',
  `lastmsg` int(10) unsigned NOT NULL DEFAULT '0',
  `banned` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip` char(45) NOT NULL DEFAULT '0',
  `isop` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `session` varchar(64) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `groupchatid` (`groupchatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

// We add the new whatslog table
$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."whatslog (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `guestid` varchar(200) NULL DEFAULT NULL,
  `operatorid` int(10) unsigned NOT NULL DEFAULT '0',
  `clientid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `whatsid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `itemid` int(10) unsigned NOT NULL DEFAULT '0',
  `fromwhere` varchar(255) DEFAULT NULL,
  `ip` char(45) NOT NULL DEFAULT '0',
  `country` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `countrycode` varchar(2) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `usragent` varchar(255) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."operatorchat (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `fromid` int(10) NOT NULL DEFAULT '0',
  `toid` int(10) NOT NULL DEFAULT '0',
  `message` text NULL DEFAULT NULL,
  `sent` int(10) NOT NULL DEFAULT '0',
  `received` smallint(1) unsigned NOT NULL DEFAULT '0',
  `msgpublic` smallint(1) unsigned NOT NULL DEFAULT '0',
  `system_message` varchar(3) DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."responses (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `department` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NULL DEFAULT NULL,
  `short_code` varchar(200) NULL DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`, `short_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."sessions (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `widgetid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniqueid` varchar(20) NULL DEFAULT NULL,
  `userid` varchar(200) NULL DEFAULT NULL,
  `department` int(10) unsigned NOT NULL DEFAULT '0',
  `operatorid` int(10) unsigned NOT NULL DEFAULT '0',
  `operatorname` varchar(255) NULL DEFAULT NULL,
  `template` varchar(20) NULL DEFAULT 'business',
  `avatarset` varchar(20) NULL DEFAULT 'business',
  `usr_avatar` varchar(255) NULL DEFAULT NULL,
  `name` varchar(100) NULL DEFAULT NULL,
  `email` varchar(100) NULL DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `countrycode` varchar(2) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `lang` char(2) DEFAULT 'en',
  `notes` text,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `fcontact` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `initiated` int(10) unsigned NOT NULL DEFAULT '0',
  `ended` int(10) unsigned NOT NULL DEFAULT '0',
  `deniedoid` int(10) unsigned NOT NULL DEFAULT '0',
  `session` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`,`userid`,`operatorid`,`session`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."settings (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `varname` varchar(100) DEFAULT NULL,
  `used_value` text,
  `default_value` text,
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$jakdb->query("INSERT INTO ".JAKDB_PREFIX."settings (`opid`, `varname`, `used_value`, `default_value`) VALUES
(0, 'allowedo_files', '.zip,.rar,.jpg,.jpeg,.png,.gif', '.zip,.rar,.jpg,.jpeg,.png,.gif'),
(0, 'allowed_files', '.zip,.rar,.jpg,.jpeg,.png,.gif', '.zip,.rar,.jpg,.jpeg,.png,.gif'),
(0, 'client_expired', '600', '600'),
(0, 'client_left', '300', '300'),
(0, 'title', 'Cloud Chat 3', 'Cloud Chat 3'),
(0, 'email', '', 'jakcc3'),
(0, 'lang', 'en', 'en'),
(0, 'o_number', 'O-test', 'cc3jak'),
(0, 'smtp_sender', '', ''),
(0, 'smtphost', '', ''),
(0, 'smtppassword', '', ''),
(0, 'smtpport', '25', '25'),
(0, 'smtpusername', '', ''),
(0, 'smtp_alive', '0', '0'),
(0, 'smtp_auth', '0', '0'),
(0, 'smtp_mail', '0', '0'),
(0, 'smtp_prefix', '', ''),
(0, 'updated', ".time().", '326430900'),
(0, 'useravatheight', '250', '250'),
(0, 'useravatwidth', '250', '250'),
(0, 'version', '3.1.1', '3.1.1'),
(0, 'push_reminder', '120', '120'),
(0, 'native_app_token', '', 'jakweb_app'),
(0, 'native_app_key', '', 'jakweb_app'),
(0, 'live_online_status', '0', '0'),
(0, 'recap_client', '', ''),
(0, 'recap_server', '', ''),
(0, 'proactive_time', '3', '3'),
(0, 'openop', '1', '1')");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."transcript (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NULL DEFAULT NULL,
  `message` varchar(2000) NULL DEFAULT NULL,
  `user` varchar(100) NULL DEFAULT NULL,
  `operatorid` int(10) unsigned NOT NULL DEFAULT '0',
  `convid` int(10) unsigned NOT NULL DEFAULT '0',
  `quoted` int(10) unsigned NOT NULL DEFAULT '0',
  `replied` int(10) unsigned NOT NULL DEFAULT '0',
  `starred` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `editoid` int(10) unsigned NOT NULL DEFAULT '0',
  `edited` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `sentstatus` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `class` varchar(20) NULL DEFAULT NULL,
  `plevel` smallint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `convid` (`convid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."user (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `departments` varchar(100) DEFAULT '0',
  `available` smallint(1) unsigned NOT NULL DEFAULT '0',
  `busy` smallint(1) unsigned NOT NULL DEFAULT '0',
  `hours_array` TEXT NULL,
  `phonenumber` varchar(255) DEFAULT NULL,
  `whatsappnumber` varchar(255) DEFAULT NULL,
  `pusho_tok` VARCHAR(50) DEFAULT NULL,
  `pusho_key` VARCHAR(50) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` char(64) NULL DEFAULT NULL,
  `idhash` varchar(32) DEFAULT NULL,
  `session` varchar(64) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `picture` varchar(100) NOT NULL DEFAULT '/standard.jpg',
  `aboutme` TEXT NULL,
  `language` varchar(10) DEFAULT NULL,
  `invitationmsg` varchar(255) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `lastactivity` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `responses` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `files` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `useronlinelist` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `operatorchat` tinyint(1) NOT NULL DEFAULT '0',
  `operatorchatpublic` tinyint(1) NOT NULL DEFAULT '0',
  `operatorlist` tinyint(1) NOT NULL DEFAULT '0',
  `transferc` tinyint(1) NOT NULL DEFAULT '1',
  `chat_latency` smallint(4) UNSIGNED NOT NULL DEFAULT '3000',
  `push_notifications` tinyint(1) NOT NULL DEFAULT '1',
  `sound` tinyint(1) NOT NULL DEFAULT '1',
  `ringing` tinyint(2) NOT NULL DEFAULT '3',
  `alwaysnot` tinyint(1) NOT NULL DEFAULT '0',
  `alwaysonline` tinyint(1) NOT NULL DEFAULT '0',
  `emailnot` tinyint(1) NOT NULL DEFAULT '0',
  `navsidebar` tinyint(1) NOT NULL DEFAULT '1',
  `themecolour` varchar(10) NOT NULL DEFAULT 'yellow',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `autodelete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `autoupdate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `extraop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `validtill` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `permissions` varchar(512) DEFAULT NULL,
  `forgot` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."user_stats (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `vote` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comment` text,
  `support_time` int(10) unsigned NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."checkstatus (
  `convid` int(10) unsigned NOT NULL,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `depid` int(10) unsigned NOT NULL DEFAULT '0',
  `department` varchar(100) DEFAULT NULL,
  `operatorid` int(10) unsigned NOT NULL DEFAULT '0',
  `operator` varchar(100) DEFAULT NULL,
  `pusho` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `newc` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `newo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `files` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `knockknock` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `msgdel` int(10) unsigned NOT NULL DEFAULT '0',
  `msgedit` int(10) unsigned NOT NULL DEFAULT '0',
  `typec` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `typeo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `transferoid` int(10) unsigned NOT NULL DEFAULT '0',
  `transferid` int(10) unsigned NOT NULL DEFAULT '0',
  `denied` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hide` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `datac` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `alert` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `statusc` int(10) unsigned NOT NULL DEFAULT '0',
  `statuso` int(10) unsigned NOT NULL DEFAULT '0',
  `initiated` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `convid` (`convid`),
  KEY `denied` (`denied`,`hide`,`statusc`,`statuso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."transfer (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `convid` int(10) unsigned NOT NULL DEFAULT '0',
  `fromoid` int(10) unsigned NOT NULL DEFAULT '0',
  `fromname` varchar(100) DEFAULT NULL,
  `tooid` int(10) unsigned NOT NULL DEFAULT '0',
  `toname` varchar(100) DEFAULT NULL,
  `message` text,
  `used` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `convid` (`convid`,`tooid`,`used`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."push_notification_devices (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `ostype` enum('ios','android') NOT NULL DEFAULT 'ios',
  `token` varchar(255) DEFAULT NULL,
  `appname` enum('lc3','hd3') DEFAULT NULL,
  `appversion` varchar(10) DEFAULT NULL,
  `lastedit` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`ostype`,`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

// Cloud Chat 3 extra tables
$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."user_confirm (
  `opid` int(10) unsigned NOT NULL,
  `confirmcode` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  UNIQUE KEY `opid` (`opid`),
  KEY `confirmcode` (`confirmcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."subscriptions (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `packageid` int(10) unsigned NOT NULL DEFAULT '0',
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `chatwidgets` SMALLINT UNSIGNED NOT NULL DEFAULT '1',
  `groupchats` SMALLINT UNSIGNED NOT NULL DEFAULT '1',
  `operatorchat` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
  `operators` SMALLINT UNSIGNED NOT NULL DEFAULT '1',
  `extraoperators` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `departments` TINYINT UNSIGNED NOT NULL DEFAULT '1',
  `files` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `copyfree` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `activechats` SMALLINT UNSIGNED NOT NULL DEFAULT '5',
  `chathistory` SMALLINT UNSIGNED NOT NULL DEFAULT '30',
  `islc3` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `ishd3` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `validfor` smallint(3) unsigned NOT NULL DEFAULT '30',
  `amount` float NOT NULL DEFAULT '0',
  `currency` varchar(3) DEFAULT NULL,
  `paidhow` varchar(30) DEFAULT NULL,
  `subscribed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `paygateid` int(10) unsigned NOT NULL DEFAULT '0',
  `subscribeid` VARCHAR(255) NULL DEFAULT NULL,
  `planid` VARCHAR(255) NULL DEFAULT NULL,
  `paidwhen` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `paidtill` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `registered` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `trial` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `packageid` (`packageid`,`opid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."files_archive (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(100) DEFAULT NULL,
  `path` varchar(100) NULL DEFAULT NULL,
  `orig_name` varchar(100) NULL DEFAULT NULL,
  `name` varchar(100) NULL DEFAULT NULL,
  `ip` varbinary(16) NOT NULL DEFAULT '0',
  `mime_type` VARCHAR(20) NOT NULL,
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb->query("CREATE TABLE ".JAKDB_PREFIX."payment_security (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `opid` int(10) NOT NULL DEFAULT '0',
  `userid` int(10) DEFAULT '0',
  `payidnow` varchar(255) NOT NULL,
  `subscribe_id` varchar(255) NOT NULL,
  `success` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `paidwhen` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

// Now let us delete all cache files
$cacheallfiles = '../'.JAK_CACHE_DIRECTORY.'/';
$msfi = glob($cacheallfiles."*.php");
if ($msfi) foreach ($msfi as $filen) {
    if (file_exists($filen)) unlink($filen);
}

// Now let's update the admin panel.

// Database connection to the main site
$jakdb1 = new JAKsql([
    // required
    'database_type' => JAKDB_MAIN_DBTYPE,
    'database_name' => JAKDB_MAIN_NAME,
    'server' => JAKDB_MAIN_HOST,
    'username' => JAKDB_MAIN_USER,
    'password' => JAKDB_MAIN_PASS,
    'charset' => 'utf8',
    'port' => JAKDB_MAIN_PORT,
    'prefix' => JAKDB_MAIN_PREFIX,
 
    // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [PDO::ATTR_CASE => PDO::CASE_NATURAL]
    ]);

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."admins (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` char(64) DEFAULT NULL,
  `idhash` varchar(32) DEFAULT NULL,
  `session` varchar(64) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `picture` varchar(255) NOT NULL DEFAULT '/standard.png',
  `language` varchar(10) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `lastactivity` int(10) unsigned NOT NULL DEFAULT '0',
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `permissions` varchar(512) DEFAULT NULL,
  `forgot` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."advaccess (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locationid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `lc3hd3` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `url` text,
  `lastedit` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `paidtill` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `paythanks` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`paidtill`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."locations (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `lc3hd3` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `url` varchar(150) DEFAULT NULL,
  `db_host` text,
  `db_type` varchar(10) DEFAULT 'mysql',
  `db_port` smallint(5) unsigned NOT NULL DEFAULT '3306',
  `db_user` varchar(100) DEFAULT NULL,
  `db_pass` varchar(100) DEFAULT NULL,
  `db_name` varchar(100) DEFAULT NULL,
  `db_prefix` varchar(10) DEFAULT NULL,
  `lastedit` datetime DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb1->query("INSERT INTO ".JAKDB_MAIN_PREFIX."locations (`id`, `title`, `url`, `db_host`, `db_type`, `db_port`, `db_user`, `db_pass`, `db_name`, `db_prefix`, `lastedit`) VALUES
(1, 'Cloud Chat 3 Operator', 'https://s1.domain.com/operator', '', 'mysql',  3306, '', '', '', '', NOW())");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."loginlog (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `fromwhere` varchar(255) DEFAULT NULL,
  `ip` char(45) NOT NULL DEFAULT '0',
  `usragent` varchar(255) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `access` smallint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."payment_ipn (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `status` varchar(200) DEFAULT NULL,
  `amount` varchar(200) DEFAULT NULL,
  `currency` varchar(200) DEFAULT NULL,
  `txn_id` varchar(200) DEFAULT NULL,
  `receiver_email` varchar(200) DEFAULT NULL,
  `payer_email` varchar(200) DEFAULT NULL,
  `paid_with` varchar(200) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."settings (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(100) DEFAULT NULL,
  `used_value` text,
  `default_value` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$jakdb1->exec("INSERT INTO ".JAKDB_MAIN_PREFIX."settings (`id`, `varname`, `used_value`, `default_value`) VALUES
(NULL, 'title',  'Cloud Chat 3 Server', 'Cloud Chat 3 Server'),
(NULL, 'currency', 'USD',  'USD'),
(NULL, 'trialdays',  '10', '10'),
(NULL, 'smtp', '0',  '0'),
(NULL, 'smtphost', '', ''),
(NULL, 'smtpauth', 'true', 'true'),
(NULL, 'smtpprefix', 'tls',  'tls'),
(NULL, 'smtpalive',  '0',  '0'),
(NULL, 'smtpport', '25', '25'),
(NULL, 'smtpusername', '', ''),
(NULL, 'smtppass', '', ''),
(NULL, 'emailtitle', 'Cloud Chat 3',  'Cloud Chat 3'),
(NULL, 'emailaddress', '', ''),
(NULL, 'webaddress', '', ''),
(NULL, 'webhello', 'Hi %s',  'Hi %s'),
(NULL, 'dateformat', 'd.m.Y', 'd.m.Y'),
(NULL, 'timeformat', 'g:i a', 'g:i a'),
(NULL, 'exchangekey', '', ''),
(NULL, 'stripe_publish_key', '', ''),
(NULL, 'stripe_secret_key', '', ''),
(NULL, 'paypal_client', '', ''),
(NULL, 'paypal_secret', '', ''),
(NULL, 'yookassa_id', '', ''),
(NULL, 'yookassa_secret', '', ''),
(NULL, 'paystack_secret', '', ''),
(NULL, 'twoco', '', ''),
(NULL, 'twoco_secret', '', ''),
(NULL, 'authorize_id', '', ''),
(NULL, 'authorize_key', '', ''),
(NULL, 'bank_info', '', ''),
(NULL, 'sandbox_mode', '1', '1'),
(NULL,  'emailsignup',  '<p>Thank you for signup on Cloud Chat 3. Your %d days trial will start the first time you login into the live chat. Please login with the link provided below.</p>',  'email_signup'),
(NULL,  'welcomemsg', 'Thank you for your signup. An email has been sent to the provided email address, please click the link inside the email to confirm your account. Emails can end up in your spam folder, make sure to check it as well. Should you not receive an email within 5 minutes, feel free to contact us anytime.',  'web_thank_you'),
(NULL,  'emailwelcome', '<p>Your email has been confirmed and your account is now active. You can always login via the URL below.</p>', 'welcome_email'),
(NULL,  'lc3confirm', '<p>Thank you for your purchase once again. Your private and personal Live Chat 3 access has been setup, you can now login with your current username/email and password here: %s.</p>',  'live_chat_3_confirm'),
(NULL,  'emailexpire',  '<p>Your live chat user account will expire soon. Please login and extend your membership access as soon as possible to prevent any interruptions. When you have chosen monthly payments your access will be renewed automatically and no action is necessary. Thank you very much.</p>', 'expire_email'),
(NULL,  'emailpaid',  '<p>Thank you very much for your payment over %s. Your membership access has been updated it is now valid till %s. When you have chosen monthly payments your access will be renewed automatically.</p>',  'email_paid_single'),
(NULL,  'emailpaidlc3', '<p>Thank you very much for your payment over %s. Your personal Live Chat 3 access will be in installed within 24 hours, we will send you another confirmation email as soon your private access is ready. Your personal Live Chat 3 access is valid till %s. When you have chosen monthly payments your access will be renewed automatically.</p>',  'email_paid_lc3'),
(NULL,  'emailmoved', '<p>We have moved your account to one of our other location. You will need to confirm your account once again with a click on the link below. You will also need to replace the Chat Widget on your website from the new location when your account has been setup.</p>', 'email_moved'),
(NULL, 'emailpass',  '<p>Your password: %s</p>', 'email_password'),
(NULL, 'newtickettitle', 'New Ticket', 'New Ticket'),
(NULL, 'newticketmsg', '<p>Hi %s, you have received a new ticket. Please login to our dashboard to read it with the link provided below.</p>', 'new_ticket_msg'),
(NULL,  'hd3confirm', '<p>Thank you for your purchase once again. Your private and personal HelpDesk 3 access has been setup, you can now login with your current username/email and password here: %s.</p>',  'helpdesk_3_confirm'),
(NULL,  'emailpaidhd3', '<p>Thank you very much for your payment over %s. Your personal HelpDesk 3 access will be in installed within 24 hours, we will send you another confirmation email as soon your private access is ready. Your personal HelpDesk 3 access is valid till %s. When you have chosen monthly payments your access will be renewed automatically.</p>',  'email_paid_hd3'),
(NULL, 'addops', '3', '3'),
(NULL, 'welcomedash', '<h3>Welcome to Cloud Chat 3</h3><p>Thank you for beeing a part of it. Our dashboard will give you full control over your payments, additional operators and subscription. In case of any questions please create a support ticket. Enjoy Cloud Chat 3.</p>', 'welcome_dashboard'),
(NULL, 'stripewebhook', '', 'whsec_stripe'),
(NULL, 'invoicetitle', 'Subscription Reminder', 'subreminder'),
(NULL, 'invoicecontent', '<p>Hi %s, we want to inform you that your subscription with us will be updated soon. There is no action involved except you want to cancel your subscription. In case you want to cancel your subscription please login into our <a href=\"%s\">live chat server</a> and hit cancel on the subscription package. Thank you very much and we hope to keep you as a customer. Enjoy Cloud Chat 3.</p>', 'remindertext'),
(NULL, 'subsctitle', 'Subscription Extended', 'subsctitle'),
(NULL, 'subsctext', '<p>Hi %s, your subscription with us has been extended. Thank you very much! Please login into our <a href=\"%s\">live chat server</a> and enjoy Cloud Chat 3.</p>', 'subsctext'),
(NULL, 'failedtitle', 'Subscription Failed!', 'subscfailed'),
(NULL, 'failedtext', '<p>Hi %s, we want to inform you that your subscription has ended. It was not possible to automatically charge your account In case you want to resubscribe please login into our <a href=\"%s\">live chat server</a> and select a subscription package. Thank you very much and we hope to welcome you as a customer soon again. We hope you enjoyed Cloud Chat 3.</p>', 'subsctext'),
(NULL, 'version', '3.1.1', '3.1.1'),
(NULL, 'onumber', '', 'O-t'),
(NULL, 'updated', ".time().", '06051980'),
(NULL, 'appboxes', '<div class=\"row\">\r\n<div class=\"col-md-3\">\r\n<div class=\"card card-stats\">\r\n<div class=\"card-body\">\r\n<div class=\"statistics statistics-horizontal\">\r\n<div class=\"info info-horizontal\">\r\n<div class=\"row\">\r\n<div class=\"col-5\">\r\n<div class=\"icon icon-primary icon-circle\">\r\n<i class=\"fab fa-android\"></i>\r\n</div>\r\n</div>\r\n<div class=\"col-7 text-right\">\r\n<h3 class=\"info-title\">Android</h3>\r\n<a href=\"https://play.google.com/store/apps/details?id=ch.jakweb.livechat\"><h6 class=\"stats-title\">Get Android App</h6></a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<hr>\r\n<div class=\"card-footer\">\r\n<div class=\"stats\">\r\n<a href=\"https://play.google.com/store/apps/details?id=ch.jakweb.livechat\"><i class=\"fab fa-android\"></i> Get Android App</a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-md-3\">\r\n<div class=\"card card-stats\">\r\n<div class=\"card-body\">\r\n<div class=\"statistics statistics-horizontal\">\r\n<div class=\"info info-horizontal\">\r\n<div class=\"row\">\r\n<div class=\"col-3\">\r\n<div class=\"icon icon-info icon-circle\">\r\n<i class=\"fab fa-apple\"></i>\r\n</div>\r\n</div>\r\n<div class=\"col-9 text-right\">\r\n<h3 class=\"info-title\">iOS</h3>\r\n<a href=\"https://itunes.apple.com/us/app/live-chat-3-lcps/id1229573974\"><h6 class=\"stats-title\">Get iOS App</h6></a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<hr>\r\n<div class=\"card-footer\">\r\n<div class=\"stats\">\r\n<a href=\"https://itunes.apple.com/us/app/live-chat-3-lcps/id1229573974\"><i class=\"fab fa-apple\"></i> Get iOS App</a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-md-3\">\r\n<div class=\"card card-stats\">\r\n<div class=\"card-body\">\r\n<div class=\"statistics statistics-horizontal\">\r\n<div class=\"info info-horizontal\">\r\n<div class=\"row\">\r\n<div class=\"col-5\">\r\n<div class=\"icon icon-success icon-circle\">\r\n<i class=\"fas fa-puzzle-piece\"></i>\r\n</div>\r\n</div>\r\n<div class=\"col-7 text-right\">\r\n<h3 class=\"info-title\">Plugins</h3>\r\n<a href=\"https://jakweb.ch/downloads\"><h6 class=\"stats-title\">Live Chat Plugins</h6></a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<hr>\r\n<div class=\"card-footer\">\r\n<div class=\"stats\">\r\n<a href=\"https://jakweb.ch/downloads\"><i class=\"fa fa-puzzle-piece\"></i> Live Chat Plugins</a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-md-3\">\r\n<div class=\"card card-stats\">\r\n<div class=\"card-body\">\r\n<div class=\"statistics statistics-horizontal\">\r\n<div class=\"info info-horizontal\">\r\n<div class=\"row\">\r\n<div class=\"col-3\">\r\n<div class=\"icon icon-warning icon-circle\">\r\n<i class=\"fa fa-question\"></i>\r\n</div>\r\n</div>\r\n<div class=\"col-9 text-right\">\r\n<h3 class=\"info-title\">FAQ</h3>\r\n<a href=\"https://jakweb.ch/faq\"><h6 class=\"stats-title\">Get Answers</h6></a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<hr>\r\n<div class=\"card-footer\">\r\n<div class=\"stats\">\r\n<a href=\"https://jakweb.ch/faq\"><i class=\"fa fa-question\"></i> Get Answers</a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>', '<div class=\"row\">\r\n<div class=\"col-md-3\">\r\n<div class=\"card card-stats\">\r\n<div class=\"card-body\">\r\n<div class=\"statistics statistics-horizontal\">\r\n<div class=\"info info-horizontal\">\r\n<div class=\"row\">\r\n<div class=\"col-5\">\r\n<div class=\"icon icon-primary icon-circle\">\r\n<i class=\"fab fa-android\"></i>\r\n</div>\r\n</div>\r\n<div class=\"col-7 text-right\">\r\n<h3 class=\"info-title\">Android</h3>\r\n<a href=\"https://play.google.com/store/apps/details?id=ch.jakweb.livechat\"><h6 class=\"stats-title\">Get Android App</h6></a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<hr>\r\n<div class=\"card-footer\">\r\n<div class=\"stats\">\r\n<a href=\"https://play.google.com/store/apps/details?id=ch.jakweb.livechat\"><i class=\"fab fa-android\"></i> Get Android App</a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-md-3\">\r\n<div class=\"card card-stats\">\r\n<div class=\"card-body\">\r\n<div class=\"statistics statistics-horizontal\">\r\n<div class=\"info info-horizontal\">\r\n<div class=\"row\">\r\n<div class=\"col-3\">\r\n<div class=\"icon icon-info icon-circle\">\r\n<i class=\"fab fa-apple\"></i>\r\n</div>\r\n</div>\r\n<div class=\"col-9 text-right\">\r\n<h3 class=\"info-title\">iOS</h3>\r\n<a href=\"https://itunes.apple.com/us/app/live-chat-3-lcps/id1229573974\"><h6 class=\"stats-title\">Get iOS App</h6></a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<hr>\r\n<div class=\"card-footer\">\r\n<div class=\"stats\">\r\n<a href=\"https://itunes.apple.com/us/app/live-chat-3-lcps/id1229573974\"><i class=\"fab fa-apple\"></i> Get iOS App</a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-md-3\">\r\n<div class=\"card card-stats\">\r\n<div class=\"card-body\">\r\n<div class=\"statistics statistics-horizontal\">\r\n<div class=\"info info-horizontal\">\r\n<div class=\"row\">\r\n<div class=\"col-5\">\r\n<div class=\"icon icon-success icon-circle\">\r\n<i class=\"fas fa-puzzle-piece\"></i>\r\n</div>\r\n</div>\r\n<div class=\"col-7 text-right\">\r\n<h3 class=\"info-title\">Plugins</h3>\r\n<a href=\"https://jakweb.ch/downloads\"><h6 class=\"stats-title\">Live Chat Plugins</h6></a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<hr>\r\n<div class=\"card-footer\">\r\n<div class=\"stats\">\r\n<a href=\"https://jakweb.ch/downloads\"><i class=\"fa fa-puzzle-piece\"></i> Live Chat Plugins</a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-md-3\">\r\n<div class=\"card card-stats\">\r\n<div class=\"card-body\">\r\n<div class=\"statistics statistics-horizontal\">\r\n<div class=\"info info-horizontal\">\r\n<div class=\"row\">\r\n<div class=\"col-3\">\r\n<div class=\"icon icon-warning icon-circle\">\r\n<i class=\"fa fa-question\"></i>\r\n</div>\r\n</div>\r\n<div class=\"col-9 text-right\">\r\n<h3 class=\"info-title\">FAQ</h3>\r\n<a href=\"https://jakweb.ch/faq\"><h6 class=\"stats-title\">Get Answers</h6></a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<hr>\r\n<div class=\"card-footer\">\r\n<div class=\"stats\">\r\n<a href=\"https://jakweb.ch/faq\"><i class=\"fa fa-question\"></i> Get Answers</a>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>'),
(NULL, 'expiredmsgdash', 'Your membership has expired, the chat widget will not appear on your website. Please renew your membership with one of the options below.', 'Your membership has expired, the chat widget will not appear on your website. Please renew your membership with one of the options below.'),
(NULL, 'trialdate', 'Trial valid until: {{trialdate}}', 'Trial valid until: {{trialdate}}'),
(NULL, 'addopsmsg', '<h5>Add more Operators</h5>\r\n<p>You can add more operators anytime to your account. After the purchase you can manage your operators from the user section. We will also display how long the accounts are valid for here on the dashboard.</p>', '<h5>Add more Operators</h5>\r\n<p>You can add more operators anytime to your account. After the purchase you can manage your operators from the user section. We will also display how long the accounts are valid for here on the dashboard.</p>'),
(NULL, 'moreopmsg', 'You have not purchased additional operator accounts, yet.', 'You have not purchased additional operator accounts, yet.'),
(NULL, 'opwarnmsg', '<p><strong>Please note</strong> you will still need your main account active otherwise the chat widget won\'t show on your website. Expired accounts will be automatically removed after 1 Month.</p>', '<p><strong>Please note</strong> you will still need your main account active otherwise the chat widget won\'t show on your website. Expired accounts will be automatically removed after 1 Month.</p>'),
(NULL, 'businesshours', 'We suggest you set up your personal business hours in <a href=\"{{clientprofile}}\">your profile</a> together with email or push notifications. That way you do not need to be physically online, the system will inform you when a customer needs your attention.', 'We suggest you set up your personal business hours in <a href=\"{{clientprofile}}\">your profile</a> together with email or push notifications. That way you do not need to be physically online, the system will inform you when a customer needs your attention.'),
(NULL, 'heldashpmsg', '<div class=\"row\">\r\n<div class=\"col-md-12\">\r\n<div class=\"card bg-success\">\r\n<div class=\"card-body\">\r\n<h5 id=\"membermsg\">Your membership is valid until: <span id=\"memberdate\">{{paidtill}}</span></h5>\r\n<div class=\"row\">\r\n<div class=\"col-md-6\">\r\n<ul>\r\n<li><a href=\"{{clientprofile}}\">Edit your Profile</a></li>\r\n<li><a href=\"{{clientsettings}}\">Change your Settings</a></li>\r\n<li><a href=\"{{clientresponse}}\">Setup your personal standard responses</a></li>\r\n</ul>\r\n</div>\r\n<div class=\"col-md-6\">\r\n<ul>\r\n<li><a href=\"{{clientproactive}}\">Setup automatic engage entries.</a></li>\r\n<li><a href=\"{{clientbot}}\">Let the bot answer some questions.</a></li>\r\n<li><a href=\"{{clientwidget}}\">Copy/paste your personal widget code</a></li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>', '<div class=\"row\">\r\n<div class=\"col-md-12\">\r\n<div class=\"card bg-success\">\r\n<div class=\"card-body\">\r\n<h5 id=\"membermsg\">Your membership is valid until: <span id=\"memberdate\">{{paidtill}}</span></h5>\r\n<div class=\"row\">\r\n<div class=\"col-md-6\">\r\n<ul>\r\n<li><a href=\"{{clientprofile}}\">Edit your Profile</a></li>\r\n<li><a href=\"{{clientsettings}}\">Change your Settings</a></li>\r\n<li><a href=\"{{clientresponse}}\">Setup your personal standard responses</a></li>\r\n</ul>\r\n</div>\r\n<div class=\"col-md-6\">\r\n<ul>\r\n<li><a href=\"{{clientproactive}}\">Setup automatic engage entries.</a></li>\r\n<li><a href=\"{{clientbot}}\">Let the bot answer some questions.</a></li>\r\n<li><a href=\"{{clientwidget}}\">Copy/paste your personal widget code</a></li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>'),
(NULL, 'purchasedtitle', 'Purchased subscriptions', 'Purchased subscriptions'),
(NULL, 'packageseltitle', 'Extend your access with one of our package', 'Extend your access with one of our package'),
(NULL, 'addoptitle', 'Additional Operator Accounts', 'Additional Operator Accounts'),
(NULL,  'lc3update', '<p>Your LiveChat 3 installation has been updated. Please login into your operator panel: %s go to maintenance and delete the cache. Thank you very much.</p>',  'livechat_3_update'),
(NULL,  'hd3update', '<p>Your HelpDesk 3 installation has been updated. Please login into your operator panel: %s go to maintenance and delete the cache. Thank you very much.</p>',  'helpdesk_3_update'),
(NULL,  'newclient', '<p>We have created your account on: %s</p><p>Please login with following user credentials:<br>Username: %s<br>Password: %s</p>',  'newclient')");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."subscriptions (
`id` int(11) NOT NULL AUTO_INCREMENT,
`locationid` int(10) unsigned NOT NULL DEFAULT '0',
`packageid` int(10) unsigned NOT NULL DEFAULT '0',
`userid` int(10) unsigned NOT NULL DEFAULT '0',
`amount` float NOT NULL DEFAULT '0',
`currency` varchar(3) DEFAULT NULL,
`paidfor` varchar(255) DEFAULT NULL,
`paidhow` varchar(255) DEFAULT NULL,
`subscribed` tinyint(3) unsigned NOT NULL DEFAULT '0',
`paygateid` int(10) unsigned NOT NULL DEFAULT '0',
`subscribeid` int(10) unsigned NOT NULL DEFAULT '0',
`subscribetoken` varchar(255) DEFAULT NULL,
`paidwhen` datetime NOT NULL DEFAULT NOW(),
`paidtill` datetime NOT NULL DEFAULT NOW(),
`freeplan` tinyint(3) unsigned NOT NULL DEFAULT '0',
`active` tinyint(3) unsigned NOT NULL DEFAULT '0',
`success` tinyint(3) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
KEY `packageid` (`packageid`,`locationid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."support_tickets (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(100) DEFAULT NULL,
  `ticketid` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` text,
  `content` mediumtext,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `isnews` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `readtime` int(10) unsigned NOT NULL DEFAULT '0',
  `sent` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `opid` (`opid`,`ticketid`,`isnews`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."users (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locationid` int(10) unsigned NOT NULL DEFAULT '0',
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` char(64) DEFAULT NULL,
  `signup` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `trial` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `paidtill` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `lastedit` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
  `newticket` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `welcomemsg` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `payreminder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `paythanks` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `confirm` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."packages ( 
  `id` INT NOT NULL AUTO_INCREMENT,
  `locationid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  `previmg` varchar(100) DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `currency` varchar(3) DEFAULT NULL,
  `operators` SMALLINT UNSIGNED NOT NULL DEFAULT '1',
  `departments` TINYINT UNSIGNED NOT NULL DEFAULT '1',
  `files` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `chatwidgets` TINYINT UNSIGNED NOT NULL DEFAULT '1',
  `groupchats` TINYINT UNSIGNED NOT NULL DEFAULT '1',
  `operatorchat` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `copyfree` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `activechats` SMALLINT UNSIGNED NOT NULL DEFAULT '5',
  `chathistory` SMALLINT UNSIGNED NOT NULL DEFAULT '30',
  `islc3` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `ishd3` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `validfor` smallint(3) unsigned NOT NULL DEFAULT '30',
  `multipleuse` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `isfree` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `supackage` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lastedit` DATETIME NOT NULL DEFAULT '1980-05-06 00:00:00',
  `created` DATETIME NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `locationid` (`locationid`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8");

$jakdb1->query("INSERT INTO ".JAKDB_MAIN_PREFIX."packages (`id`, `locationid`, `title`, `description`, `previmg`, `amount`, `currency`, `operators`, `departments`, `files`, `copyfree`, `activechats`, `islc3`, `ishd3`, `active`, `lastedit`, `created`) VALUES
(1, 1, 'First Package', 'Description about the first package.', '', 10, 'USD', 1, 1, 0, 0, 3, 0, 0, 1, NOW(), NOW())");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."coupons (
  `id` INT NOT NULL AUTO_INCREMENT,
  `locationid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `code` varchar(20) DEFAULT NULL,
  `discount` tinyint(3) unsigned NOT NULL DEFAULT '10',
  `freepackageid` int(10) unsigned NOT NULL DEFAULT 0,
  `datestart` int(10) unsigned NOT NULL DEFAULT 0,
  `dateend` int(10) unsigned NOT NULL DEFAULT 0,
  `total` int(11) unsigned NOT NULL DEFAULT 0,
  `used` int(11) unsigned NOT NULL DEFAULT 0,
  `products` varchar(100) DEFAULT NULL,
  `active` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `lastedit` DATETIME NOT NULL DEFAULT '1980-05-06 00:00:00',
  `created` DATETIME NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

// Add all phrases to the administration panel
$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."answers (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `locid` int(10) unsigned NOT NULL DEFAULT '0',
      `lang` varchar(5) DEFAULT NULL,
      `title` varchar(255) DEFAULT NULL,
      `message` text,
      `fireup` smallint(5) unsigned NOT NULL DEFAULT '60',
      `msgtype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=standard,2=welcome,3=closed,4=expired,5=firstmsg',
      `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
      PRIMARY KEY (`id`),
      KEY `locid` (`locid`,`lang`,`fireup`,`msgtype`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."departments (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `locid` int(10) unsigned NOT NULL DEFAULT '0',
      `title` varchar(255) DEFAULT NULL,
      `description` mediumtext,
      `email` varchar(255) DEFAULT NULL,
      `guesta` smallint(1) unsigned NOT NULL DEFAULT '1',
      `active` smallint(1) unsigned NOT NULL DEFAULT '1',
      `dorder` smallint(2) unsigned NOT NULL DEFAULT '1',
      `time` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
      PRIMARY KEY (`id`),
      KEY `locid` (`locid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."opsettings (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `locid` int(10) unsigned NOT NULL DEFAULT '0',
      `varname` varchar(100) DEFAULT NULL,
      `used_value` text,
      `default_value` text,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."groupchat (
        `id` int(10) NOT NULL AUTO_INCREMENT,
        `locid` int(10) unsigned NOT NULL DEFAULT '0',
        `password` varchar(20) NULL DEFAULT NULL,
        `title` varchar(100) DEFAULT NULL,
        `description` text NULL DEFAULT NULL,
        `maxclients` tinyint(3) unsigned NOT NULL DEFAULT '20',
        `lang` varchar(5) DEFAULT NULL,
        `buttonimg` varchar(100) NOT NULL,
        `floatpopup` tinyint(3) unsigned NOT NULL DEFAULT '0',
        `floatcss` varchar(100) DEFAULT NULL,
        `active` tinyint(3) unsigned NOT NULL DEFAULT '0',
        `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
        PRIMARY KEY (`id`),
        KEY `locid` (`locid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."chatwidget (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `locid` int(10) unsigned NOT NULL DEFAULT '0',
      `title` varchar(100) DEFAULT NULL,
      `lang` varchar(5) DEFAULT NULL,
      `template` varchar(20) DEFAULT 'business',
      `created` datetime NOT NULL DEFAULT '1980-05-06 00:00:00',
      PRIMARY KEY (`id`),
      KEY `locid` (`locid`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."payment_gateways (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `locid` int(10) unsigned NOT NULL DEFAULT '0',
  `paygateid` varchar(25) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `secretkey_one` varchar(255) DEFAULT NULL,
  `secretkey_two` varchar(255) DEFAULT NULL,
  `emailkey` varchar(100) DEFAULT NULL,
  `bank_info` text,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sandbox` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `lastedit` DATETIME NOT NULL DEFAULT '1980-05-06 00:00:00',
  `created` DATETIME NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `locid` (`locid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."package_gateways (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `locid` int(10) unsigned NOT NULL DEFAULT '0',
  `packageid` int(10) unsigned NOT NULL DEFAULT '0',
  `paygateid` int(10) unsigned NOT NULL DEFAULT '0',
  `created` DATETIME NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `packageid` (`packageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."payment_plans (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `amount` float NOT NULL DEFAULT '0',
  `currency` varchar(3) NOT NULL,
  `interval` varchar(10) NOT NULL,
  `interval_count` smallint(3) unsigned NOT NULL DEFAULT '30',
  `paygateid` int(10) unsigned NOT NULL DEFAULT '0',
  `opid` int(10) unsigned NOT NULL DEFAULT '0',
  `planid` varchar(255) DEFAULT NULL,
  `created` DATETIME NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `paygateid` (`paygateid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

$jakdb1->query("CREATE TABLE ".JAKDB_MAIN_PREFIX."currency_conversations (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `packageid` int(10) unsigned NOT NULL DEFAULT '0',
  `fromcurrency` varchar(3) NOT NULL,
  `tocurrency` varchar(3) NOT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `updated` DATETIME NOT NULL DEFAULT '1980-05-06 00:00:00',
  `created` DATETIME NOT NULL DEFAULT '1980-05-06 00:00:00',
  PRIMARY KEY (`id`),
  KEY `packageid` (`packageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

// Set all the standard values
$jakdb1->query("INSERT INTO ".JAKDB_MAIN_PREFIX."opsettings (`locid`, `varname`, `used_value`, `default_value`) VALUES
  (1, 'dateformat', 'd.m.Y', 'd.m.Y'),
  (1, 'timeformat', 'g:i a', 'g:i a'),
  (1, 'timezoneserver', 'Europe/Zurich', 'Europe/Zurich'),
  (1, 'lang', 'en', 'en'),
  (1, 'title', 'Cloud Chat 3', 'Cloud Chat 3'),
  (1, 'tw_msg', 'A customer is requesting attention.', 'A customer is requesting attention.'),
  (1, 'useravatheight', '250', '250'),
  (1, 'useravatwidth', '250', '250')");

// Chat Widget
$jakdb1->insert("chatwidget", ["locid" => 1, "title" => "Live Support Chat", "lang" => "en", "template" => "business", "created" => $jakdb1->raw("NOW()")]);

// Group Chat
$jakdb1->insert("groupchat", ["locid" => 1, "title" => "Weekly Support", "maxclients" => 10, "lang" => "en", "buttonimg" => "colour_on.png", "floatpopup" => 0, "floatcss" => "bottom:20px;left:20px", "active" => 0, "created" => $jakdb1->raw("NOW()")]);

// Insert the chat department
$jakdb1->insert("departments", ["locid" => 1, "title" => "Chat", "description" => "About the Chat Department", "active" => 1, "dorder" => 1, "time" => $jakdb1->raw("NOW()")]);
  
// Insert the answers
$jakdb1->insert("answers", [["locid" => 1, "lang" => "en", "title" => "Enters Chat", "message" => "%operator% enters the chat.", "fireup" => 15, "msgtype" => 2, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Expired", "message" => "This session has expired!", "fireup" => 15, "msgtype" => 4, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Ended", "message" => "%client% has ended the conversation", "fireup" => 15, "msgtype" => 3, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Welcome", "message" => "Welcome %client%, a representative will be with you shortly.", "fireup" => 15, "msgtype" => 5, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Leave", "message" => "has left the conversation.", "fireup" => 15, "msgtype" => 6, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Start Page", "message" => "Please insert your name to begin, a representative will be with you shortly.", "fireup" => 15, "msgtype" => 7, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Contact Page", "message" => "None of our representatives are available right now, although you are welcome to leave a message!", "fireup" => 15, "msgtype" => 8, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Feedback Page", "message" => "We would appreciate your feedback to improve our service.", "fireup" => 15, "msgtype" => 9, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Quickstart Page", "message" => "Please type a message and hit enter to start the conversation.", "fireup" => 15, "msgtype" => 10, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Group Chat Welcome Message", "message" => "Welcome to our weekly support session, sharing experience and feedback.", "fireup" => 15, "msgtype" => 11, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Group Chat Offline Message", "message" => "The public chat is offline at this moment, please try again later.", "fireup" => 15, "msgtype" => 12, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Group Chat Full Message", "message" => "The public chat is full, please try again later.", "fireup" => 15, "msgtype" => 13, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Select Operator", "message" => "Please select an operator of your choice and add your name and message to start a conversation.", "fireup" => 15, "msgtype" => 14, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Expired Soft", "message" => "The chat has been ended due the inactivity, please type a message to restart again.", "fireup" => 15, "msgtype" => 15, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "Transfer Message", "message" => "We have transferred your conversation to %operator%, please hold.", "fireup" => 15, "msgtype" => 16, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "WhatsApp Online", "message" => "Please click on a operator below to connect via WhatsApp and get help immediately.", "fireup" => 15, "msgtype" => 26, "created" => $jakdb1->raw("NOW()")],
  ["locid" => 1, "lang" => "en", "title" => "WhatsApp Offline", "message" => "We are currently offline however please check below for available operators in WhatsApp, we try to help you as soon as possible.", "fireup" => 15, "msgtype" => 27, "created" => $jakdb1->raw("NOW()")]]);

// Finish installation of the admin panel
  
  die(json_encode(array("status" => 1)));

} else {
  die(json_encode(array("status" => 2)));
}

} else {
  die(json_encode(array("status" => 0)));
}
?>