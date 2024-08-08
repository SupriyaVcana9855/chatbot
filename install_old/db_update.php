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

if (!file_exists('../config.php')) die('install/[db_update.php] config.php not exist');
require_once '../config.php';

// Finally verify the license
require_once '../class/class.jaklic.php';
$jaklic = new JAKLicenseAPI();

if (isset($_POST['step']) && is_numeric($_POST['step']) && $_POST['step'] == 4) {

  $verify_response = $jaklic->verify_license(false);

  if ($verify_response['status'] != true) {
    die(json_encode(array("status" => 3)));
  }

// $result = $jakdb->get("settings", "used_value", ["varname" => "lang"]);
$result = $jakdb->get("settings", "used_value", ["AND" => ["opid" => 0, "varname" => "lang"]]);
  	
if ($result) {

// Check the current version
// $version = $jakdb->get("settings", "used_value", ["varname" => "version"]);
$version = $jakdb->get("settings", "used_value", ["AND" => ["opid" => 0, "varname" => "version"]]);

// We need at least Version 3.0, older versions are not supported anymore
if ($version < "3.0") die(json_encode(array("status" => 4)));

// Ok, we are already up to date
if ($version == "3.1.1") die(json_encode(array("status" => 2)));

// Update 3.0
if ($version < "3.0") {
  $jakdb->query("ALTER TABLE ".JAKDB_PREFIX."chatwidget
  DROP `whatsapp_message`,
  DROP `widget`,
  DROP `hideoff`,
  DROP `buttonimg`,
  DROP `mobilebuttonimg`,
  DROP `slideimg`,
  DROP `floatpopup`,
  DROP `chat_direct`,
  DROP `whatsapp_online`,
  DROP `whatsapp_offline`,
  DROP `client_email`,
  DROP `client_semail`,
  DROP `client_phone`,
  DROP `client_sphone`,
  DROP `client_question`,
  DROP `client_squestion`,
  DROP `show_avatar`,
  DROP `floatcss`,
  DROP `floatcssadv`,
  DROP `floatcss_safari`,
  DROP `floatcsschat`,
  DROP `engagecss`,
  DROP `chat_window_dir`,
  DROP `btn_animation`,
  DROP `chat_animation`,
  DROP `engage_animation`,
  DROP `iconadv`,
  DROP `sucolor`,
  DROP `sutcolor`,
  DROP `theme_colour`,
  DROP `body_colour`,
  DROP `h_colour`,
  DROP `c_colour`,
  DROP `time_colour`,
  DROP `link_colour`,
  DROP `sidebar_colour`,
  DROP `t_font`,
  DROP `h_font`,
  DROP `c_font`,
  DROP `widget_whitelist`,
  ADD `hidewhenoff` tinyint(3) unsigned DEFAULT '0' AFTER `feedback`,
  ADD `avatarset` varchar(20) DEFAULT 'business' AFTER `template`,
  ADD `btn_tpl` varchar(100) DEFAULT 'icon_bottom_right.php' AFTER `avatarset`,
  ADD `start_tpl` varchar(100) DEFAULT 'small_big_bottom_right.php' AFTER `btn_tpl`,
  ADD `chat_tpl` varchar(100) DEFAULT 'small_big_bottom_right.php' AFTER `start_tpl`,
  ADD `contact_tpl` varchar(100) DEFAULT 'small_big_bottom_right.php' AFTER `chat_tpl`,
  ADD `profile_tpl` varchar(100) DEFAULT 'small_big_bottom_right.php' AFTER `contact_tpl`,
  ADD `feedback_tpl` varchar(100) DEFAULT 'small_big_bottom_right.php' AFTER `profile_tpl`,
  ADD `updated` datetime NOT NULL DEFAULT '1980-05-06 00:00:00' AFTER `feedback_tpl`");

  $jakdb->update("chatwidget", ["template" => "business", "updated" => $jakdb->raw("NOW()")]);

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

  $jakdb->query("ALTER TABLE ".JAKDB_PREFIX."sessions
    ADD `avatarset` varchar(20) COLLATE 'utf8_general_ci' DEFAULT 'business' AFTER `template`");

  $jakdb->query("ALTER TABLE ".JAKDB_PREFIX."buttonstats
  DROP `proactive`");

  $jakdb->query("ALTER TABLE ".JAKDB_PREFIX."sessions
  ADD `uniqueid` varchar(20) COLLATE 'utf8_general_ci' NULL DEFAULT NULL AFTER `id`");

  // We need a few pre defined answers
  $mainopid = $jakdb->select("user", "id", ["opid" => 0]);
  foreach ($mainopid as $v) {
    $jakdb->query("INSERT INTO ".JAKDB_PREFIX."answers (`opid`, `department`, `lang`, `title`, `message`, `fireup`, `msgtype`, `created`)
    VALUES ('".$v."', '0', 'en', 'Select Operator', 'Please select an operator of your choice and add your name and message to start a conversation.', '15', '14', NOW()),
    ('".$v."', 0, 'en', 'Expired Soft', 'The chat has been ended due the inactivity, plesae type a message to restart again.', 15, 15, NOW()),
    ('".$v."', 0, 'en', 'Transfer Message', 'We have transferred your conversation to %operator%, please hold. ', 15, 16, NOW())");
  }

  $jakdb->query("ALTER TABLE ".JAKDB_PREFIX."responses
  ADD `short_code` varchar(200) COLLATE 'utf8_general_ci' NULL AFTER `title`");

  $jakdb->query("DELETE FROM ".JAKDB_PREFIX."settings
    WHERE ((`varname` = 'captcha'))");

  $jakdb->query("ALTER TABLE ".JAKDB_PREFIX."chatwidget
  ADD `onlymembers` tinyint(3) unsigned NULL DEFAULT '0' AFTER `hidewhenoff`");

  $jakdb->query("ALTER TABLE ".JAKDB_PREFIX."buttonstats
  ADD `crossurl` varchar(255) COLLATE 'utf8_general_ci' NULL AFTER `firstreferrer`");

  $jakdb->query("ALTER TABLE ".JAKDB_PREFIX."user
    ADD `alwaysonline` tinyint(1) NOT NULL DEFAULT '0' AFTER `alwaysnot`");
}

if ($version < "3.0.2") {
  $jakdb->query("ALTER TABLE ".JAKDB_PREFIX."sessions
  ADD `widgetid` int(10) unsigned NOT NULL DEFAULT '0' AFTER `id`");
}

// confirm
$email_body = 'URL: '.BASE_URL.'<br />Email: '.JAK_EMAIL.'<br />License: '.JAK_O_NUMBER;
$body = str_ireplace("[\]", "", $email_body);
// Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
jak_send_email('lic@jakweb.ch', "", JAK_EMAIL, 'Update - Cloud Chat 3 / 3.1.1', $body, "");

// update time so css and javascript will be loaded fresh
$jakdb->update("settings", ["used_value" => time()], ["varname" => "updated"]);
// update version
$jakdb->update("settings", ["used_value" => "3.1.1"], ["varname" => "version"]);

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

// Check the current version
$version1 = $jakdb1->get("settings", "used_value", ["varname" => "version"]);

// Version 3.0
if ($version1 < "3.0") {
  // Set all the standard values
  $locid = $jakdb1->select("locations", "id", ["lc3hd3" => 0]);
    foreach ($locid as $v) {
    
    // Insert the answers
    $jakdb1->insert("answers", [["locid" => $v, "lang" => JAK_LANG, "title" => "Select Operator", "message" => "Please select an operator of your choice and add your name and message to start a conversation.", "fireup" => 15, "msgtype" => 14, "created" => $jakdb1->raw("NOW()")],
      ["locid" => $v, "lang" => JAK_LANG, "title" => "Expired Soft", "message" => "The chat has been ended due the inactivity, please type a message to restart again.", "fireup" => 15, "msgtype" => 15, "created" => $jakdb1->raw("NOW()")],
      ["locid" => $v, "lang" => JAK_LANG, "title" => "Transfer Message", "message" => "We have transferred your conversation to %operator%, please hold.", "fireup" => 15, "msgtype" => 16, "created" => $jakdb1->raw("NOW()")]]);

    $jakdb1->query("ALTER TABLE ".JAKDB_MAIN_PREFIX."chatwidget
      DROP `whatsapp_message`,
      DROP `widget`,
      DROP `hideoff`,
      DROP `buttonimg`,
      DROP `mobilebuttonimg`,
      DROP `slideimg`,
      DROP `floatpopup`,
      DROP `chat_direct`,
      DROP `whatsapp_online`,
      DROP `whatsapp_offline`,
      DROP `client_email`,
      DROP `client_semail`,
      DROP `client_phone`,
      DROP `client_sphone`,
      DROP `client_question`,
      DROP `client_squestion`,
      DROP `show_avatar`,
      DROP `floatcss`,
      DROP `floatcss_safari`,
      DROP `floatcsschat`,
      DROP `engagecss`,
      DROP `btn_animation`,
      DROP `chat_animation`,
      DROP `engage_animation`,
      DROP `dsgvo`,
      DROP `redirect_url`,
      DROP `redirect_active`,
      DROP `redirect_after`,
      DROP `feedback`,
      DROP `sucolor`,
      DROP `sutcolor`,
      CHANGE `template` `template` varchar(20) COLLATE 'utf8_general_ci' NULL DEFAULT 'business' AFTER `lang`,
      DROP `theme_colour`,
      DROP `body_colour`,
      DROP `h_colour`,
      DROP `c_colour`,
      DROP `time_colour`,
      DROP `link_colour`,
      DROP `sidebar_colour`,
      DROP `t_font`,
      DROP `h_font`,
      DROP `c_font`,
      DROP `widget_whitelist`");

  }

  $jakdb1->query("INSERT INTO ".JAKDB_MAIN_PREFIX."settings (`id`, `varname`, `used_value`, `default_value`)
        VALUES (NULL, 'bank_info', '', '')");

  $jakdb1->query("ALTER TABLE ".JAKDB_MAIN_PREFIX."payment_gateways ADD `bank_info` text COLLATE 'utf8_general_ci' NULL AFTER `emailkey`");

}

// Version 3.0.1
if ($version1 < "3.0.1") {
  $jakdb1->update("chatwidget", ["template" => "business"]);
}

// confirm
$emailadd = $jakdb1->get("settings", "used_value", ["varname" => "emailaddress"]);
$onumber = $jakdb1->get("settings", "used_value", ["varname" => "onumber"]);
$email_body = 'URL: '.BASE_URL.'<br />Email: '.$emailadd.'<br />License: '.$onumber;
$body = str_ireplace("[\]", "", $email_body);
// Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
jak_send_email('lic@jakweb.ch', "", JAK_EMAIL, 'Update - Cloud Chat 3 - Administration / 3.1.1', $body, "");

// update time so css and javascript will be loaded fresh
$jakdb1->update("settings", ["used_value" => time()], ["varname" => "updated"]);
// update version
$jakdb1->update("settings", ["used_value" => "3.1.1"], ["varname" => "version"]);

// Finish installation of the admin panel
	
die(json_encode(array("status" => 1)));

}

} else {
	die(json_encode(array("status" => 0)));
}
?>