<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 2.2                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// No errors let's proceed with the sign up.
	if (!file_exists('../include/db.php')) die('process/[db.php] config.php not exist');
	require_once '../include/db.php';

$error = $success = '';

if (!file_exists('../class/class.db.php')) die('process/[class.db.php] config.php not exist');
require_once '../class/class.db.php';

include_once '../class/PHPMailerAutoload.php';

// Change for 1.0.3
use JAKWEB\JAKsql;

// Database connection main manage
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

// Ok, user is logged in let's get the settings table
$sett = array();
$settings = $jakdb->select("settings", ["varname", "used_value"]);
foreach ($settings as $v) {
  $sett[$v["varname"]] = $v["used_value"]; 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uid']) && is_numeric($_POST['uid'])) {

	// Check if we have a database connection
	if ($jakdb) {
		$userid = $jakdb->get("advaccess", ["id", "userid"], ["AND" => ["opid" => $_POST['uid']], "paidtill[>]" => $jakdb->raw("NOW()")]);
		if ($userid) {

			$userdet = $jakdb->get("users", ["username", "email"], ["id" => $userid["userid"]]);

			$mail = new PHPMailer(); // defaults to using php "mail()" or optional SMTP
									
			if ($sett["smtp"] == 1) {

				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->Host = $sett["smtphost"];
        $mail->SMTPAuth = ($sett["smtpauth"] ? true : false); // enable SMTP authentication
        $mail->SMTPSecure = $sett["smtpprefix"]; // sets the prefix to the server
        $mail->SMTPKeepAlive = ($sett["smtpalive"] ? true : false); // SMTP connection will not close after each email sent
        $mail->Port = $sett["smtpport"]; // set the SMTP port for the GMAIL server
        $mail->Username = $sett["smtpusername"]; // SMTP account username
        $mail->Password = $sett["smtppass"];        // SMTP account password
        $mail->SetFrom($sett["emailaddress"]);
										
			} else {
									
				$mail->SetFrom($sett["emailaddress"]);
									
			}

			// Send email to customer
			$mail->AddAddress($userdet["email"]);

			// Say Hello
			$webtext = '<h1>'.sprintf($sett["webhello"], $userdet["username"]).'</h1>';

			// Send the operator url
			$webtext .= sprintf($sett["lc3confirm"], '<a href="'.$_POST['url'].'">'.$_POST['url'].'</a>');

			// Get the email template
			$nlhtml = file_get_contents('../email/index.html');
		
			// Change fake vars into real ones.
			$cssAtt = array('{emailcontent}', '{weburl}');
			$cssUrl   = array($webtext, $sett["webaddress"]);
			$nlcontent = str_replace($cssAtt, $cssUrl, $nlhtml);
		
			$body = str_ireplace("[\]", "", $nlcontent);
									
			$mail->Subject = $sett["emailtitle"];
			$mail->MsgHTML($body);
			$mail->Send();

      // finally update the url for the user
      $jakdb->update("advaccess", ["url" => $_POST['url'], "lastedit" => $jakdb->raw("NOW()")], ["id" => $userid["id"]]);

			$success = "Email has been sent succesfully.";

		} else {

			$error = "Something went wrong!";

		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
    <title>Send Operator Confirmation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
body {
  margin: 0;
  mso-line-height-rule: exactly;
  padding: 0;
  min-width: 100%;
}
table {
  border-collapse: collapse;
  border-spacing: 0;
}
td {
  padding: 0;
  vertical-align: top;
}
.spacer,
.border {
  font-size: 1px;
  line-height: 1px;
}
.spacer {
  width: 100%;
}
img {
  border: 0;
  -ms-interpolation-mode: bicubic;
}
.image {
  font-size: 0;
  margin-bottom: 24px;
}
.image img {
  display: block;
}
.logo {
  mso-line-height-rule: at-least;
}
.logo img {
  display: block;
}
strong {
  font-weight: bold;
}
h1,
h2,
h3,
p,
ol,
ul,
li {
  margin-top: 0;
}
ol,
ul,
li {
  padding-left: 0;
}
blockquote {
  margin-top: 0;
  margin-right: 0;
  margin-bottom: 0;
  padding-right: 0;
}
.column-top {
  font-size: 32px;
  line-height: 32px;
}
.column-bottom {
  font-size: 8px;
  line-height: 8px;
}
.column {
  text-align: left;
}
.contents {
  width: 100%;
}
.padded {
  padding-left: 32px;
  padding-right: 32px;
}
.wrapper {
  display: table;
  table-layout: fixed;
  width: 100%;
  min-width: 620px;
  -webkit-text-size-adjust: 100%;
  -ms-text-size-adjust: 100%;
}
table.wrapper {
  table-layout: fixed;
}
.one-col,
.two-col,
.three-col {
  margin-left: auto;
  margin-right: auto;
  width: 600px;
}
.centered {
  margin-left: auto;
  margin-right: auto;
}
.two-col .image {
  margin-bottom: 23px;
}
.two-col .column-bottom {
  font-size: 9px;
  line-height: 9px;
}
.two-col .column {
  width: 300px;
}
.three-col .image {
  margin-bottom: 21px;
}
.three-col .column-bottom {
  font-size: 11px;
  line-height: 11px;
}
.three-col .column {
  width: 200px;
}
.three-col .first .padded {
  padding-left: 32px;
  padding-right: 16px;
}
.three-col .second .padded {
  padding-left: 24px;
  padding-right: 24px;
}
.three-col .third .padded {
  padding-left: 16px;
  padding-right: 32px;
}
@media only screen and (min-width: 0) {
  .wrapper {
    text-rendering: optimizeLegibility;
  }
}
@media only screen and (max-width: 620px) {
  [class=wrapper] {
    min-width: 318px !important;
    width: 100% !important;
  }
  [class=wrapper] .one-col,
  [class=wrapper] .two-col,
  [class=wrapper] .three-col {
    width: 318px !important;
  }
  [class=wrapper] .column,
  [class=wrapper] .gutter {
    display: block;
    float: left;
    width: 318px !important;
  }
  [class=wrapper] .padded {
    padding-left: 32px !important;
    padding-right: 32px !important;
  }
  [class=wrapper] .block {
    display: block !important;
  }
  [class=wrapper] .hide {
    display: none !important;
  }
  [class=wrapper] .image {
    margin-bottom: 24px !important;
  }
  [class=wrapper] .image img {
    height: auto !important;
    width: 100% !important;
  }
}
.wrapper h1 {
  font-weight: 700;
}
.wrapper h2 {
  font-style: italic;
  font-weight: normal;
}
.wrapper h3 {
  font-weight: normal;
}
.one-col blockquote,
.two-col blockquote,
.three-col blockquote {
  font-style: italic;
}
.one-col-feature h1 {
  font-weight: normal;
}
.one-col-feature h2 {
  font-style: normal;
  font-weight: bold;
}
.one-col-feature h3 {
  font-style: italic;
}
td.border {
  width: 1px;
}
tr.border {
  background-color: #e9e9e9;
  height: 1px;
}
tr.border td {
  line-height: 1px;
}
.one-col,
.two-col,
.three-col,
.one-col-feature {
  background-color: #ffffff;
  font-size: 14px;
}
.one-col,
.two-col,
.three-col,
.one-col-feature,
.preheader,
.header,
.footer {
  margin-left: auto;
  margin-right: auto;
}
.preheader table {
  width: 602px;
}
.preheader .title,
.preheader .webversion {
  padding-top: 10px;
  padding-bottom: 12px;
  font-size: 12px;
  line-height: 21px;
}
.preheader .title {
  text-align: left;
}
.preheader .webversion {
  text-align: right;
  width: 300px;
}
.header {
  width: 602px;
}
.header .logo {
  font-size: 26px;
  font-weight: 700;
  letter-spacing: -0.02em;
  line-height: 32px;
  padding: 32px 0;
}
.header .logo a {
  text-decoration: none;
}
.header .logo .logo-center {
  text-align: center;
}
.header .logo .logo-center img {
  margin-left: auto;
  margin-right: auto;
}
.gmail {
  width: 650px;
  min-width: 650px;
}
.gmail td {
  font-size: 1px;
  line-height: 1px;
}
.wrapper a {
  text-decoration: underline;
  transition: all .2s;
}
.wrapper h1 {
  font-size: 36px;
  margin-bottom: 18px;
}
.wrapper h2 {
  font-size: 26px;
  line-height: 32px;
  margin-bottom: 20px;
}
.wrapper h3 {
  font-size: 18px;
  line-height: 22px;
  margin-bottom: 16px;
}
.wrapper h1 a,
.wrapper h2 a,
.wrapper h3 a {
  text-decoration: none;
}
.one-col blockquote,
.two-col blockquote,
.three-col blockquote {
  font-size: 14px;
  border-left: 2px solid #e9e9e9;
  margin-left: 0;
  padding-left: 16px;
}
table.divider {
  width: 100%;
}
.divider .inner {
  padding-bottom: 24px;
}
.divider table {
  background-color: #e9e9e9;
  font-size: 2px;
  line-height: 2px;
  width: 60px;
}
.wrapper .gray {
  background-color: #f7f7f7;
}
.wrapper .gray blockquote {
  border-left-color: #dddddd;
}
.wrapper .gray .divider table {
  background-color: #dddddd;
}
.image-frame {
  padding: 8px;
}
.image-background {
  display: inline-block;
}
.one-col .column table:nth-last-child(2) td h1:last-child,
.one-col .column table:nth-last-child(2) td h2:last-child,
.one-col .column table:nth-last-child(2) td h3:last-child,
.one-col .column table:nth-last-child(2) td p:last-child,
.one-col .column table:nth-last-child(2) td ol:last-child,
.one-col .column table:nth-last-child(2) td ul:last-child {
  margin-bottom: 24px;
}
.one-col p,
.one-col ol,
.one-col ul {
  font-size: 16px;
  line-height: 24px;
}
.one-col ol,
.one-col ul {
  margin-left: 18px;
}
.two-col .column table:nth-last-child(2) td h1:last-child,
.two-col .column table:nth-last-child(2) td h2:last-child,
.two-col .column table:nth-last-child(2) td h3:last-child,
.two-col .column table:nth-last-child(2) td p:last-child,
.two-col .column table:nth-last-child(2) td ol:last-child,
.two-col .column table:nth-last-child(2) td ul:last-child {
  margin-bottom: 23px;
}
.two-col .image-frame {
  padding: 6px;
}
.two-col h1 {
  font-size: 26px;
  line-height: 32px;
  margin-bottom: 16px;
}
.two-col h2 {
  font-size: 20px;
  line-height: 26px;
  margin-bottom: 18px;
}
.two-col h3 {
  font-size: 16px;
  line-height: 20px;
  margin-bottom: 14px;
}
.two-col p,
.two-col ol,
.two-col ul {
  font-size: 14px;
  line-height: 23px;
}
.two-col ol,
.two-col ul {
  margin-left: 16px;
}
.two-col li {
  padding-left: 5px;
}
.two-col .divider .inner {
  padding-bottom: 23px;
}
.two-col blockquote {
  padding-left: 16px;
}
.three-col .column table:nth-last-child(2) td h1:last-child,
.three-col .column table:nth-last-child(2) td h2:last-child,
.three-col .column table:nth-last-child(2) td h3:last-child,
.three-col .column table:nth-last-child(2) td p:last-child,
.three-col .column table:nth-last-child(2) td ol:last-child,
.three-col .column table:nth-last-child(2) td ul:last-child {
  margin-bottom: 21px;
}
.three-col .image-frame {
  padding: 4px;
}
.three-col h1 {
  font-size: 20px;
  line-height: 26px;
  margin-bottom: 12px;
}
.three-col h2 {
  font-size: 16px;
  line-height: 22px;
  margin-bottom: 14px;
}
.three-col h3 {
  font-size: 14px;
  line-height: 18px;
  margin-bottom: 10px;
}
.three-col p,
.three-col ol,
.three-col ul {
  font-size: 12px;
  line-height: 21px;
}
.three-col ol,
.three-col ul {
  margin-left: 14px;
}
.three-col li {
  padding-left: 6px;
}
.three-col .divider .inner {
  padding-bottom: 21px;
}
.three-col blockquote {
  padding-left: 16px;
}
.one-col-feature .column-top {
  font-size: 36px;
  line-height: 36px;
}
.one-col-feature .column-bottom {
  font-size: 4px;
  line-height: 4px;
}
.one-col-feature .column {
  text-align: center;
  width: 600px;
}
.one-col-feature .image {
  margin-bottom: 32px;
}
.one-col-feature .column table:nth-last-child(2) td h1:last-child,
.one-col-feature .column table:nth-last-child(2) td h2:last-child,
.one-col-feature .column table:nth-last-child(2) td h3:last-child,
.one-col-feature .column table:nth-last-child(2) td p:last-child,
.one-col-feature .column table:nth-last-child(2) td ol:last-child,
.one-col-feature .column table:nth-last-child(2) td ul:last-child {
  margin-bottom: 32px;
}
.one-col-feature h1,
.one-col-feature h2,
.one-col-feature h3 {
  text-align: center;
}
.one-col-feature h1 {
  font-size: 52px;
  margin-bottom: 22px;
}
.one-col-feature h2 {
  font-size: 42px;
  margin-bottom: 20px;
}
.one-col-feature h3 {
  font-size: 32px;
  line-height: 42px;
  margin-bottom: 20px;
}
.one-col-feature p,
.one-col-feature ol,
.one-col-feature ul {
  font-size: 21px;
  line-height: 32px;
  margin-bottom: 32px;
}
.one-col-feature p a,
.one-col-feature ol a,
.one-col-feature ul a {
  text-decoration: none;
}
.one-col-feature p {
  text-align: center;
}
.one-col-feature ol,
.one-col-feature ul {
  margin-left: 40px;
  text-align: left;
}
.one-col-feature li {
  padding-left: 3px;
}
.one-col-feature .divider .inner {
  padding-bottom: 32px;
}
.one-col-feature blockquote {
  border-bottom: 2px solid #e9e9e9;
  border-left-color: #ffffff;
  border-left-width: 0;
  border-left-style: none;
  border-top: 2px solid #e9e9e9;
  margin-bottom: 32px;
  margin-left: 0;
  padding-bottom: 42px;
  padding-left: 0;
  padding-top: 42px;
  position: relative;
}
.one-col-feature blockquote:before,
.one-col-feature blockquote:after {
  background: -moz-linear-gradient(left, #ffffff 25%, #e9e9e9 25%, #e9e9e9 75%, #ffffff 75%);
  background: -webkit-gradient(linear, left top, right top, color-stop(25%, #ffffff), color-stop(25%, #e9e9e9), color-stop(75%, #e9e9e9), color-stop(75%, #ffffff));
  background: -webkit-linear-gradient(left, #ffffff 25%, #e9e9e9 25%, #e9e9e9 75%, #ffffff 75%);
  background: -o-linear-gradient(left, #ffffff 25%, #e9e9e9 25%, #e9e9e9 75%, #ffffff 75%);
  background: -ms-linear-gradient(left, #ffffff 25%, #e9e9e9 25%, #e9e9e9 75%, #ffffff 75%);
  background: linear-gradient(to right, #ffffff 25%, #e9e9e9 25%, #e9e9e9 75%, #ffffff 75%);
  content: '';
  display: block;
  height: 2px;
  left: 0;
  outline: 1px solid #ffffff;
  position: absolute;
  right: 0;
}
.one-col-feature blockquote:before {
  top: -2px;
}
.one-col-feature blockquote:after {
  bottom: -2px;
}
.one-col-feature blockquote p,
.one-col-feature blockquote ol,
.one-col-feature blockquote ul {
  font-size: 42px;
  line-height: 48px;
  margin-bottom: 48px;
}
.one-col-feature blockquote p:last-child,
.one-col-feature blockquote ol:last-child,
.one-col-feature blockquote ul:last-child {
  margin-bottom: 0 !important;
}
.footer {
  width: 602px;
}
.footer .padded {
  font-size: 12px;
  line-height: 20px;
}
.social {
  padding-top: 32px;
  padding-bottom: 22px;
}
.social img {
  display: block;
}
.social .divider {
  font-family: sans-serif;
  font-size: 10px;
  line-height: 21px;
  text-align: center;
  padding-left: 14px;
  padding-right: 14px;
}
.social .social-text {
  height: 21px;
  vertical-align: middle !important;
  font-size: 10px;
  font-weight: bold;
  text-decoration: none;
  text-transform: uppercase;
}
.social .social-text a {
  text-decoration: none;
}
.address {
  width: 250px;
}
.address .padded {
  text-align: left;
  padding-left: 0;
  padding-right: 10px;
}
.subscription {
  width: 350px;
}
.subscription .padded {
  text-align: right;
  padding-right: 0;
  padding-left: 10px;
}
.address,
.subscription {
  padding-top: 32px;
  padding-bottom: 64px;
}
.address a,
.subscription a {
  font-weight: bold;
  text-decoration: none;
}
.address table,
.subscription table {
  width: 100%;
}
@media only screen and (max-width: 651px) {
  .gmail {
    display: none !important;
  }
}
@media only screen and (max-width: 620px) {
  [class=wrapper] .one-col .column:last-child table:nth-last-child(2) td h1:last-child,
  [class=wrapper] .two-col .column:last-child table:nth-last-child(2) td h1:last-child,
  [class=wrapper] .three-col .column:last-child table:nth-last-child(2) td h1:last-child,
  [class=wrapper] .one-col-feature .column:last-child table:nth-last-child(2) td h1:last-child,
  [class=wrapper] .one-col .column:last-child table:nth-last-child(2) td h2:last-child,
  [class=wrapper] .two-col .column:last-child table:nth-last-child(2) td h2:last-child,
  [class=wrapper] .three-col .column:last-child table:nth-last-child(2) td h2:last-child,
  [class=wrapper] .one-col-feature .column:last-child table:nth-last-child(2) td h2:last-child,
  [class=wrapper] .one-col .column:last-child table:nth-last-child(2) td h3:last-child,
  [class=wrapper] .two-col .column:last-child table:nth-last-child(2) td h3:last-child,
  [class=wrapper] .three-col .column:last-child table:nth-last-child(2) td h3:last-child,
  [class=wrapper] .one-col-feature .column:last-child table:nth-last-child(2) td h3:last-child,
  [class=wrapper] .one-col .column:last-child table:nth-last-child(2) td p:last-child,
  [class=wrapper] .two-col .column:last-child table:nth-last-child(2) td p:last-child,
  [class=wrapper] .three-col .column:last-child table:nth-last-child(2) td p:last-child,
  [class=wrapper] .one-col-feature .column:last-child table:nth-last-child(2) td p:last-child,
  [class=wrapper] .one-col .column:last-child table:nth-last-child(2) td ol:last-child,
  [class=wrapper] .two-col .column:last-child table:nth-last-child(2) td ol:last-child,
  [class=wrapper] .three-col .column:last-child table:nth-last-child(2) td ol:last-child,
  [class=wrapper] .one-col-feature .column:last-child table:nth-last-child(2) td ol:last-child,
  [class=wrapper] .one-col .column:last-child table:nth-last-child(2) td ul:last-child,
  [class=wrapper] .two-col .column:last-child table:nth-last-child(2) td ul:last-child,
  [class=wrapper] .three-col .column:last-child table:nth-last-child(2) td ul:last-child,
  [class=wrapper] .one-col-feature .column:last-child table:nth-last-child(2) td ul:last-child {
    margin-bottom: 24px !important;
  }
  [class=wrapper] .address,
  [class=wrapper] .subscription {
    display: block;
    float: left;
    width: 318px !important;
    text-align: center !important;
  }
  [class=wrapper] .address {
    padding-bottom: 0 !important;
  }
  [class=wrapper] .subscription {
    padding-top: 0 !important;
  }
  [class=wrapper] h1 {
    font-size: 36px !important;
    line-height: 42px !important;
    margin-bottom: 18px !important;
  }
  [class=wrapper] h2 {
    font-size: 26px !important;
    line-height: 32px !important;
    margin-bottom: 20px !important;
  }
  [class=wrapper] h3 {
    font-size: 18px !important;
    line-height: 22px !important;
    margin-bottom: 16px !important;
  }
  [class=wrapper] p,
  [class=wrapper] ol,
  [class=wrapper] ul {
    font-size: 16px !important;
    line-height: 24px !important;
    margin-bottom: 24px !important;
  }
  [class=wrapper] ol,
  [class=wrapper] ul {
    margin-left: 18px !important;
  }
  [class=wrapper] li {
    padding-left: 2px !important;
  }
  [class=wrapper] blockquote {
    padding-left: 16px !important;
  }
  [class=wrapper] .two-col .column:nth-child(n + 3) {
    border-top: 1px solid #e9e9e9;
  }
  [class=wrapper] .column-bottom {
    font-size: 8px !important;
    line-height: 8px !important;
  }
  [class=wrapper] .first .column-bottom,
  [class=wrapper] .three-col .second .column-bottom {
    display: none;
  }
  [class=wrapper] .second .column-top,
  [class=wrapper] .third .column-top {
    display: none;
  }
  [class=wrapper] .image-frame {
    padding: 4px !important;
  }
  [class=wrapper] .header .logo {
    font-size: 26px !important;
    line-height: 32px !important;
    padding-left: 10px !important;
    padding-right: 10px !important;
  }
  [class=wrapper] .header .logo img {
    display: inline-block !important;
    max-width: 280px !important;
    height: auto !important;
  }
  [class=wrapper] table.border,
  [class=wrapper] .header,
  [class=wrapper] .webversion,
  [class=wrapper] .footer {
    width: 320px !important;
  }
  [class=wrapper] .preheader .webversion,
  [class=wrapper] .header .logo a {
    text-align: center !important;
  }
  [class=wrapper] .preheader table,
  [class=wrapper] .border td {
    width: 318px !important;
  }
  [class=wrapper] .border td.border {
    width: 1px !important;
  }
  [class=wrapper] .image .border td {
    width: auto !important;
  }
  [class=wrapper] .title {
    display: none;
  }
  [class=wrapper] .footer .padded {
    text-align: center !important;
  }
  [class=wrapper] .footer .subscription .padded {
    padding-top: 20px !important;
  }
  [class=wrapper] .footer .social-link {
    display: block !important;
  }
  [class=wrapper] .footer .social-link table {
    margin: 0 auto 10px !important;
  }
  [class=wrapper] .footer .divider {
    display: none !important;
  }
  [class=wrapper] .one-col-feature .image {
    margin-bottom: 28px !important;
  }
  [class=wrapper] .one-col-feature .divider .inner {
    padding-bottom: 28px !important;
  }
  [class=wrapper] .one-col-feature h1 {
    font-size: 42px !important;
    line-height: 48px !important;
    margin-bottom: 20px !important;
  }
  [class=wrapper] .one-col-feature h2 {
    font-size: 32px !important;
    line-height: 36px !important;
    margin-bottom: 18px !important;
  }
  [class=wrapper] .one-col-feature h3 {
    font-size: 26px !important;
    line-height: 32px !important;
    margin-bottom: 20px !important;
  }
  [class=wrapper] .one-col-feature p,
  [class=wrapper] .one-col-feature ol,
  [class=wrapper] .one-col-feature ul {
    font-size: 20px !important;
    line-height: 28px !important;
    margin-bottom: 28px !important;
  }
  [class=wrapper] .one-col-feature blockquote {
    font-size: 18px !important;
    line-height: 26px !important;
    margin-bottom: 28px !important;
    padding-bottom: 26px !important;
    padding-left: 0 !important;
    padding-top: 26px !important;
  }
  [class=wrapper] .one-col-feature blockquote p,
  [class=wrapper] .one-col-feature blockquote ol,
  [class=wrapper] .one-col-feature blockquote ul {
    font-size: 26px !important;
    line-height: 32px !important;
  }
  [class=wrapper] .one-col-feature blockquote p:last-child,
  [class=wrapper] .one-col-feature blockquote ol:last-child,
  [class=wrapper] .one-col-feature blockquote ul:last-child {
    margin-bottom: 0 !important;
  }
  [class=wrapper] .one-col-feature .column table:last-of-type h1:last-child,
  [class=wrapper] .one-col-feature .column table:last-of-type h2:last-child,
  [class=wrapper] .one-col-feature .column table:last-of-type h3:last-child {
    margin-bottom: 28px !important;
  }
}
@media only screen and (max-width: 320px) {
  [class=wrapper] td.border {
    display: none;
  }
  [class=wrapper] table.border,
  [class=wrapper] .header,
  [class=wrapper] .webversion,
  [class=wrapper] .footer {
    width: 318px !important;
  }
}
</style>
  <meta name="robots" content="noindex,nofollow" />
<meta property="og:title" content="Live Chat PHP" />
</head>
  <body style="margin: 0;mso-line-height-rule: exactly;padding: 0;min-width: 100%;background-color: #fbfbfb"><style type="text/css">
body,.wrapper,.emb-editor-canvas{background-color:#fbfbfb}.border{background-color:#e9e9e9}h1{color:#565656}.wrapper h1{}.wrapper h1{font-family:sans-serif}@media only screen and (min-width: 0){.wrapper h1{font-family:Avenir,sans-serif !important}}h1{}.one-col h1{line-height:42px}.two-col h1{line-height:32px}.three-col h1{line-height:26px}.wrapper .one-col-feature h1{line-height:58px}@media only screen and (max-width: 620px){h1{line-height:42px !important}}h2{color:#555}.wrapper h2{}.wrapper h2{font-family:Georgia,serif}h2{}.one-col h2{line-height:32px}.two-col h2{line-height:26px}.three-col h2{line-height:22px}.wrapper .one-col-feature h2{line-height:52px}@media only screen and (max-width: 620px){h2{line-height:32px !important}}h3{color:#555}.wrapper h3{}.wrapper h3{font-family:Georgia,serif}h3{}.one-col h3{line-height:26px}.two-col h3{line-height:22px}.three-col 
h3{line-height:20px}.wrapper .one-col-feature h3{line-height:42px}@media only screen and (max-width: 620px){h3{line-height:26px !important}}p,ol,ul{color:#565656}.wrapper p,.wrapper ol,.wrapper ul{}.wrapper p,.wrapper ol,.wrapper ul{font-family:Georgia,serif}p,ol,ul{}.one-col p,.one-col ol,.one-col ul{line-height:25px;margin-bottom:25px}.two-col p,.two-col ol,.two-col ul{line-height:23px;margin-bottom:23px}.three-col p,.three-col ol,.three-col ul{line-height:21px;margin-bottom:21px}.wrapper .one-col-feature p,.wrapper .one-col-feature ol,.wrapper .one-col-feature ul{line-height:32px}.one-col-feature blockquote p,.one-col-feature blockquote ol,.one-col-feature blockquote ul{line-height:50px}@media only screen and (max-width: 620px){p,ol,ul{line-height:25px !important;margin-bottom:25px !important}}.wrapper a{color:#41637e}.wrapper a:hover{color:#30495c !important}.wrapper 
.logo{color:#41637e}.wrapper .logo{font-family:sans-serif}@media only screen and (min-width: 0){.wrapper .logo{font-family:Avenir,sans-serif !important}}.wrapper .logo a{color:#41637e}.wrapper .logo a:hover{color:#41637e !important}.wrapper .one-col-feature p a,.wrapper .one-col-feature ol a,.wrapper .one-col-feature ul a{border-bottom:1px solid #41637e}.wrapper .one-col-feature p a:hover,.wrapper .one-col-feature ol a:hover,.wrapper .one-col-feature ul a:hover{color:#30495c !important;border-bottom:1px solid #30495c !important}.preheader .title,.preheader .webversion,.footer 
.padded{color:#999}.preheader .title,.preheader .webversion,.footer .padded{font-family:Georgia,serif}.preheader .title a,.preheader .webversion a,.footer .padded a{color:#999}.preheader .title a:hover,.preheader .webversion a:hover,.footer .padded a:hover{color:#737373 !important}.footer .social .divider{color:#e9e9e9}.footer .social .social-text,.footer .social a{color:#999}.wrapper .footer .social .social-text,.wrapper .footer .social a{}.wrapper .footer .social .social-text,.wrapper .footer .social a{font-family:Georgia,serif}.footer .social .social-text,.footer .social a{}.footer .social .social-text,.footer .social a{letter-spacing:0.05em}.footer .social .social-text:hover,.footer .social a:hover{color:#737373 !important}.image .border{background-color:#c8c8c8}.image-frame{background-color:#dadada}.image-background{background-color:#f7f7f7}
</style>
    <center class="wrapper" style="display: table;table-layout: fixed;width: 100%;min-width: 620px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;background-color: #fbfbfb">
      <table class="header centered" style="border-collapse: collapse;border-spacing: 0;margin-left: auto;margin-right: auto;width: 602px">
        <tbody><tr><td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #e9e9e9;width: 1px">&nbsp;</td></tr>
        <tr><td class="logo" style="padding: 32px 0;vertical-align: top;mso-line-height-rule: at-least;font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif"><div class="logo-center" style="text-align: center" align="center" id="emb-email-header"><a style="text-decoration: none;transition: all .2s;color: #41637e" href="<?php echo $sett["webaddress"];?>"><img style="border: 0;-ms-interpolation-mode: bicubic;display: block;margin-left: auto;margin-right: auto;max-width: 187px" src="<?php echo $sett["webaddress"];?>/img/logo.png" alt="logo"></a></div></td></tr>
      </tbody></table>
      
          <table class="border" style="border-collapse: collapse;border-spacing: 0;font-size: 1px;line-height: 1px;background-color: #e9e9e9;margin-left: auto;margin-right: auto" width="602">
            <tbody><tr><td style="padding: 0;vertical-align: top">&#8203;</td></tr>
          </tbody></table>
        
          <table class="centered" style="border-collapse: collapse;border-spacing: 0;margin-left: auto;margin-right: auto">
            <tbody><tr>
              <td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #e9e9e9;width: 1px">&#8203;</td>
              <td style="padding: 0;vertical-align: top">
                <table class="one-col-feature" style="border-collapse: collapse;border-spacing: 0;background-color: #ffffff;font-size: 14px;margin-left: auto;margin-right: auto">
                  <tbody><tr>
                    <td class="column" style="padding: 0;vertical-align: top;text-align: center;width: 600px">
                      
                        <table class="contents" style="border-collapse: collapse;border-spacing: 0;width: 100%">
                          <tbody><tr>
                            <td class="padded" style="padding: 0;vertical-align: top;padding-left: 32px;padding-right: 32px">
                              
            					<p style="margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 21px;line-height: 32px;margin-bottom: 32px;text-align: center">

            						<?php if (!empty($error)) { ?>
            							<div class="alert alert-danger"><?php echo $error;?></div>
            						<?php } if (!empty($success)) { ?>
            							<div class="alert alert-success"><?php echo $success;?></div>
            						<?php } if (!isset($_GET["uid"]) || !is_numeric($_GET["uid"])) { ?>
            							<div class="alert alert-danger">UserID is missing, form won't be sent.</div>
            						<?php } ?>
            						
            						<form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>">
            							<div class="form-group">
										    <label class="control-label" for="url">URL to custom Operator panel</label>
											<input type="text" name="url" id="url" class="form-control" value="" placeholder="URL">
										</div>

										<button type="submit" class="btn btn-primary btn-block">Send Email</button>
										<input type="hidden" name="uid" value="<?php echo $_GET["uid"];?>">
            						</form>

            					</p>
          
                            </td>
                          </tr>
                        </tbody></table>
                      
                      <div class="column-bottom" style="font-size: 4px;line-height: 4px">&nbsp;</div>
                    </td>
                  </tr>
                </tbody></table>
              </td>
              <td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #e9e9e9;width: 1px">&#8203;</td>
            </tr>
          </tbody></table>
        
          <table class="border" style="border-collapse: collapse;border-spacing: 0;font-size: 1px;line-height: 1px;background-color: #e9e9e9;margin-left: auto;margin-right: auto" width="602">
            <tbody><tr><td style="padding: 0;vertical-align: top">&#8203;</td></tr>
          </tbody></table>

      <table class="footer centered" style="border-collapse: collapse;border-spacing: 0;margin-left: auto;margin-right: auto;width: 602px">
        <tbody><tr>
          <td style="padding: 0;vertical-align: top">
            <table style="border-collapse: collapse;border-spacing: 0">
              <tbody><tr>
                <td class="address" style="padding: 0;vertical-align: top;width: 250px;padding-top: 32px;padding-bottom: 64px">
                  <table class="contents" style="border-collapse: collapse;border-spacing: 0;width: 100%">
                    <tbody><tr>
                      <td class="padded" style="padding: 0;vertical-align: top;padding-left: 0;padding-right: 10px;text-align: left;font-size: 12px;line-height: 20px;color: #999;font-family: Georgia,serif">
                        <div>Made in Switzerland</div>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
                <td class="subscription" style="padding: 0;vertical-align: top;width: 350px;padding-top: 32px;padding-bottom: 64px">
                  <table class="contents" style="border-collapse: collapse;border-spacing: 0;width: 100%">
                    <tbody><tr>
                      <td class="padded" style="padding: 0;vertical-align: top;padding-left: 10px;padding-right: 0;font-size: 12px;line-height: 20px;color: #999;font-family: Georgia,serif;text-align: right">
                        <div>
                          <span class="block"><a href="<?php echo $sett["webaddress"];?>" style="font-weight:bold;text-decoration:none;">Cloud Chat 3</a></span>
                        </div>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
    </center>
  
</body></html>