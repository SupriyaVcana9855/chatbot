<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.1                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Check if the user has access to this file
if (!JAK_ADMINACCESS) jak_redirect(BASE_URL);

// Get the class
use JAKWEB\JAKsql;

// Get the license file
require_once '../class/class.jaklic.php';
$jaklic = new JAKLicenseAPI();

// All the tables we need for this plugin
$errors = array();
$jaktable = 'user';
$jaktable1 = 'user_stats';
$jaktable2 = 'departments';

$insert = '';
$updatepass = $addmuser = false;
$totalavops = (!empty($jakosub) ? $jakosub['operators'] + $jakosub['extraoperators'] : 0);

// Now start with the plugin use a switch to access all pages
switch ($page1) {

	// Create new user
	case 'new':
		
		// No special access, so what you doing here?
		if (!jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_MAIN_OP)) jak_redirect(BASE_URL);

		// Let's check if we can add more users
		if (jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_SUPERADMIN)) {
			$JAK_USER_ALL = jak_get_user_all($jaktable, $opcacheid, JAK_USERID, JAK_SUPERADMIN);		
		} else {
			$JAK_USER_ALL = jak_get_user_all($jaktable, $opcacheid, JAK_USERID, '');
		}
		$totalops = count($JAK_USER_ALL);
		if ($totalavops > $totalops) $addmuser = true;
			
		// No special access, so what you doing here?
		if (!$addmuser) jak_redirect(JAK_rewrite::jakParseurl('users'));
		
		// Get all departments
		$JAK_DEPARTMENTS = $jakdb->select($jaktable2, ["id", "title"], ["opid" => $opcacheid, "ORDER" => ["dorder" => "ASC"]]);
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $jkp = $_POST;
		
		    if (empty($jkp['jak_name'])) {
		        $errors['e1'] = $jkl['e7'];
		    }
		    
		    if ($jkp['jak_email'] == '' || !filter_var($jkp['jak_email'], FILTER_VALIDATE_EMAIL)) {
		        $errors['e2'] = $jkl['e3'];
		    }

		    if (jak_field_not_exist(strtolower($jkp['jak_email']), $jaktable, "email")) {
		        $errors['e2'] = $jkl['e18'];
		    }
		    
		    if (!preg_match('/^([a-zA-Z0-9\-_])+$/', $jkp['jak_username'])) {
		    	$errors['e3'] = $jkl['e8'];
		    }
		    
		    if (jak_field_not_exist(strtolower($jkp['jak_username']), $jaktable, "username")) {
		        $errors['e4'] = $jkl['e9'];
		    }
		     
		    if ($jkp['jak_password'] != $jkp['jak_confirm_password']) {
		    	$errors['e5'] = $jkl['e10'];
		    } elseif (strlen($jkp['jak_password']) <= '7') {
		    	$errors['e6'] = $jkl['e11'];
		    } else {
		    	$updatepass = true;
		    }
		    
		    if (count($errors) == 0) {
		    
		    if (!isset($jkp['jak_depid']) OR in_array("0", $jkp['jak_depid'])) {
		    	$depa = 0;
		    } else {
		    	$depa = join(',', $jkp['jak_depid']);
		    }

		    // Reset
		    $opid = $extraop = 0;
		    $validtill = '1980-05-06 00:00:00';
		    // what we have to add for the operator
		    if ($addmuser) {
				$opid = $opcacheid;
				$totaleops = $jakdb->count($jaktable, ["AND" => ["opid" => $opcacheid, "extraop" => 1]]);
				
				if ($totaleops < $jakosub['extraoperators']) {
					$date = new DateTime();
					// Modify the date
					$date->modify('+1 month');
					$validtill = $date->format('Y-m-d H:i:s');
					$extraop = 1;
				}
			}
		    
		    $tw_roles = '';
		    if (($addmuser) && $jkp['jak_roles'] && !empty($jkp['jak_roles'])) $tw_roles = join(',', $jkp['jak_roles']);

			$jakdb->insert($jaktable, [
				"opid" => $opcacheid,
				"departments" => $depa,
				"password" => hash_hmac('sha256', $jkp['jak_password'], DB_PASS_HASH),
				"username" => trim($jkp['jak_username']),
				"name" => trim($jkp['jak_name']),
				"email" => filter_var($jkp['jak_email'], FILTER_SANITIZE_EMAIL),
				"responses" => $jkp['jak_responses'],
				"files" => $jkp['jak_files'],
				"operatorchat" => $jkp['jak_chat'],
				"operatorchatpublic" => $jkp['jak_chatpublic'],
				"operatorlist" => $jkp['jak_chatlist'],
				"transferc" => $jkp['jak_transfer'],
				"chat_latency" => $jkp['jak_latency'],
				"useronlinelist" => $jkp['jak_uolist'],
				"sound" => $jkp['jak_sound'],
				"ringing" => $jkp['jak_ringing'],
				"language" => $jkp['jak_lang'],
				"invitationmsg" => $jkp['jak_inv'],
				"permissions" => $tw_roles,
				"access" => $jkp['jak_access'],
				"validtill" => $validtill,
				"extraop" => $extraop,
				"time" => $jakdb->raw("NOW()")]);

			$lastid = $jakdb->id();
		
			if (!$lastid) {
		    	$_SESSION["errormsg"] = $jkl['i4'];
		    	jak_redirect($_SESSION['LCRedirect']);
			} else {
				$newuserpath = APP_PATH.JAK_FILES_DIRECTORY.'/'.$lastid;
				
				if (!is_dir($newuserpath)) {
					mkdir($newuserpath, 0755);
				    copy(APP_PATH.JAK_FILES_DIRECTORY."/index.html", $newuserpath."/index.html");
				}

				$_SESSION["successmsg"] = $jkl['g14'];
		    	jak_redirect(JAK_rewrite::jakParseurl('users', 'edit', $lastid));
		 	}
		 
		 } else {
		    
		   	$errors['e'] = $jkl['e'];
		    $errors = $errors;
		 }
		}
		
		// Call the settings function
		$lang_files = jak_get_lang_files();
		
		// Title and Description
		$SECTION_TITLE = $jkl["m7"];
		$SECTION_DESC = "";
		
		// Include the javascript file for results
		$js_file_footer = 'js_edituser.php';
		
		// Call the template
		$template = 'newuser.php';
		
	break;
	case 'stats':
	
		// Let's go on with the script
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email_feedback'])) {
			    $jkp = $_POST;
			    
			    // Errors in Array
			    $errors = array();
			    
			    if ($jkp['email'] == '' || !filter_var($jkp['email'], FILTER_VALIDATE_EMAIL)) {
			        $errors['email'] = $jkl['e3'];
			    }
			    
			    if (count($errors) > 0) {
			    
			    /* Outputtng the error messages */
			    	if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			    	
			    		header('Cache-Control: no-cache');
			    		die('{"status":0, "errors":'.json_encode($errors).'}');
			    		
			    	} else {
			    	
			    		$errors = $errors;
			    	}
			    	
			    } else {

			    	$result = $jakdb->select($jaktable1, "*", ["userid" => $page2, "ORDER" => ["id" => "ASC"]]);

			    	$total_vote = $jakdb->sum($jaktable1, "vote", ["userid" => $page2]);
			    	$total_support = $jakdb->sum($jaktable1, "support_time", ["userid" => $page2]);
			    	
			    	$subject = $jkl["g81"].' '.$page3;
			    	
			    	$mailchat = '<div style="margin:10px 0px 0px 0px;padding:10px;border:1px solid #A8B9CB;font-family: Verdana, sans-serif;font-size: 13px;
			    	font-weight: 500;letter-spacing: normal;line-height: 1.5em;"><h2>'.$subject.'</h2><ul style="list-style:none;">';
			    	
			    	// Reset var
			    	$count = 0;
			    	if (isset($result) && !empty($result) && is_array($result)) foreach ($result as $row) {
			    		// collect each record into $_data
			    	    $mailchat .= '<li style="border-bottom:1px solid #333"><span style="font-size:11px">'.$row['time'].' - '.$jkl['g86'].':</span><br /><span style="color:#c92e2e">'.$jkl['g85'].': </span>'.$row['vote'].'/5<br />'.$jkl['g54'].': '.$row['name'].'<br />'.$jkl['stat_s12'].': '.$row['comment'].'<br />'.$jkl['l5'].': '.$row['email'].'<br />'.$jkl['g87'].': '.gmdate('H:i:s', $row['support_time']).'</li>';
			    	        	
			    	   $count++;
			    	}
			    	    
			    	$mailchat .= '</ul>';
			    	
			    	$mailchat .= '<h2>'.$jkl["g89"].'</h2>
			    	<p><strong>'.$jkl["g90"].':</strong> '.gmdate('H:i:s', $total_support).'<br /><strong>'.$jkl["g91"].':</strong> '.round(($total_vote / $count), 2).'/5</p></div>';

			    	// Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
					if (jak_send_email($jkp["email"], "", "", $subject, $mailchat, "")) {
			    	
				    	// Ajax Request
				    	if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
				    	
				    		header('Cache-Control: no-cache');
				    		die(json_encode(array('status' => 1, 'html' => $jkl["g14"])));
				    		
				    	} else {
				    	
				            jak_redirect($_SERVER['HTTP_REFERER']);
				        
				        }
				    } 
			    
				}
			}

		// Extra Check for CC3
		if (!jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_MAIN_OP) && !jak_cc3_access($jaktable, $opcacheid, $page2)) {
			$_SESSION["errormsg"] = $jkl['i3'];
		   	jak_redirect(JAK_rewrite::jakParseurl('users'));
		}
	
		// Check if the user exists
		if (is_numeric($page2) && jak_row_exist($page2, $opcacheid, $jaktable, JAK_MAIN_OP)) {
		
			$USER_FEEDBACK = array();
			$USER_VOTES = $USER_SUPPORT = 0;

			$USER_FEEDBACK = $jakdb->select($jaktable1, "*", ["userid" => $page2, "ORDER" => ["id" => "ASC"]]);

			$USER_VOTES = $jakdb->sum($jaktable1, "vote", ["userid" => $page2]);
			$USER_SUPPORT = $jakdb->sum($jaktable1, "support_time", ["userid" => $page2]);

			// Get the user details
			$USER_NAME = $jakdb->get($jaktable, "username", ["AND" => ["id" => $page2, "opid" => $opcacheid]]);
		}
	
		// Call the template
		$template = 'userstats.php';
		 		
	break;
	case 'lock':
	
		if (jak_user_exist_deletable($page2, $opcacheid)) {

			// Check what we have to do
			$datausrac = $jakdb->get($jaktable, "access", ["AND" => ["id" => $page2, "opid" => $opcacheid]]);
			// update the table
			if ($datausrac) {
				$result = $jakdb->update($jaktable, ["access" => 0], ["AND" => ["id" => $page2, "opid" => $opcacheid]]);
			} else {
				$result = $jakdb->update($jaktable, ["access" => 1], ["AND" => ["id" => $page2, "opid" => $opcacheid]]);
			}

			if (!$result) {
		    	$_SESSION["infomsg"] = $jkl['i'];
		    	jak_redirect($_SESSION['LCRedirect']);
			} else {
			    $_SESSION["successmsg"] = $jkl['g14'];
			    jak_redirect($_SESSION['LCRedirect']);
			}
		
		} else {
		   	$_SESSION["infomsg"] = $jkl['i1'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
		 		
	break;
	case 'delete':
		 
		// Check if user exists and can be deleted
		if (jak_user_exist_deletable($page2, $opcacheid)) {
		        
			// Now check how many languages are installed and do the dirty work
			$result = $jakdb->delete($jaktable, ["AND" => ["id" => $page2, "opid" => $opcacheid]]);
		
			if (!$result) {
		    	$_SESSION["infomsg"] = $jkl['i'];
		    	jak_redirect($_SESSION['LCRedirect']);
			} else {
				
				// Delete Avatar and folder
				$targetPath = APP_PATH.JAK_FILES_DIRECTORY.'/'.$page2.'/';
				$removedouble =  str_replace("//","/",$targetPath);
				foreach(glob($removedouble.'*.*') as $jak_unlink) {
				
					@unlink($jak_unlink);
				
					@unlink($targetPath);
				
				}

				$_SESSION["successmsg"] = $jkl['g14'];
			    jak_redirect($_SESSION['LCRedirect']);
		    }
		    
		} else {
			$_SESSION["infomsg"] = $jkl['i1'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
		
	break;
	case 'edit':

		// No special access and not your userid, what you up to?
		if (!jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_MAIN_OP) && ($page2 != JAK_USERID || $page3 != $opcacheid)) {
			$_SESSION["errormsg"] = $jkl['i3'];
		    jak_redirect(JAK_rewrite::jakParseurl('users'));
		}
		
		// Protect the super operators from user manager
		if (!jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_MAIN_OP) && in_array($page2, explode(',', JAK_SUPERADMIN))) jak_redirect(BASE_URL);
	
		// Check if the user exists
		if (is_numeric($page2) && is_numeric($page3) && ($page2 == $page3 && jak_row_exist($page2, $opcacheid, $jaktable, JAK_MAIN_OP) || ($page2 != $page3 && jak_row_exist($page2, $page3, $jaktable, false)))) {
		
			// Get all departments
			$JAK_DEPARTMENTS = $jakdb->select($jaktable2, ["id", "title"], ["opid" => $opcacheid, "ORDER" => ["dorder" => "ASC"]]);
		
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $jkp = $_POST;
		
		    if (empty($jkp['jak_name'])) {
		        $errors['e1'] = $jkl['e7'];
		    }
		    
		    if ($jkp['jak_email'] == '' || !filter_var($jkp['jak_email'], FILTER_VALIDATE_EMAIL)) {
		        $errors['e2'] = $jkl['e3'];
		    }

		    if (jak_field_not_exist_id($jkp['jak_email'], $page2, $jaktable, "email")) {
		        $errors['e2'] = $jkl['e18'];
		    }
		    
		    if (!preg_match('/^([a-zA-Z0-9\-_])+$/', $jkp['jak_username'])) {
		    	$errors['e3'] = $jkl['e8'];
		    }
		    
		    if (jak_field_not_exist_id($jkp['jak_username'], $page2, $jaktable, "username")) {
		        $errors['e4'] = $jkl['e9'];
		    }
		    
		    if (!empty($jkp['jak_password']) || !empty($jkp['jak_confirm_password'])) {    
		    if ($jkp['jak_password'] != $jkp['jak_confirm_password']) {
		    	$errors['e5'] = $jkl['e10'];
		    } elseif (strlen($jkp['jak_password']) <= '7') {
		    	$errors['e6'] = $jkl['e11'];
		    } else {
		    	$updatepass = true;
		    }
		    }
		    
		    // Delete Avatar if yes
		    if (!empty($jkp['jak_delete_avatar'])) {
			    $avatarpi = APP_PATH.JAK_FILES_DIRECTORY.'/index.html';
			    $avatarpid =  str_replace("//","/",$avatarpi);
			    $targetPath = APP_PATH.JAK_FILES_DIRECTORY.'/'.$page2.'/';
			    $removedouble =  str_replace("//","/",$targetPath);
			    foreach(glob($removedouble.'*.*') as $jak_unlink){
			        unlink($jak_unlink);
			        copy($avatarpid, $targetPath . "/index.html");
			    }
			    
			    $jakdb->update($jaktable, ["picture" => "/standard.jpg"], ["id" => $page2]);
		    
		    }
		    
		    if (!empty($_FILES['uploadpp']['name'])) {
		    
		    	if ($_FILES['uploadpp']['name'] != '') {
		    	
		    	$filename = $_FILES['uploadpp']['name']; // original filename
		    	// Fix explode when upload in 3.7
		    	$ls_xtension = pathinfo($filename);
		    	
		    	if ($ls_xtension['extension'] == "jpg" || $ls_xtension['extension'] == "jpeg" || $ls_xtension['extension'] == "png" || $ls_xtension['extension'] == "gif") {

		    	// Get the maximum upload or set to 2
				$postmax = (ini_get('post_max_size') ? filter_var(ini_get('post_max_size'), FILTER_SANITIZE_NUMBER_INT) : "2");
		    	
		    	if ($_FILES['uploadpp']['size'] <= ($postmax * 1000000)) {
		    	
		    	list($width, $height, $type, $attr) = getimagesize($_FILES['uploadpp']['tmp_name']);
		    	$mime = image_type_to_mime_type($type);
		    	
		    	if (($mime == "image/jpeg") || ($mime == "image/pjpeg") || ($mime == "image/png") || ($mime == "image/gif")) {
		    	
		    	// first get the target path
		    	$targetPathd = APP_PATH.JAK_FILES_DIRECTORY.'/'.$page2.'/';
		    	$targetPath =  str_replace("//","/",$targetPathd);

		    	// Create the target path
		    	if (!is_dir($targetPath)) {
		    		mkdir($targetPath, 0755);
		    	    copy(APP_PATH.JAK_FILES_DIRECTORY."/index.html", $targetPath . "/index.html");
		    	
		    	}

		    	// if old avatars exist delete it
		    	foreach(glob($targetPath.'*.*') as $jak_unlink){
		    	    unlink($jak_unlink);
		    	    copy(APP_PATH.JAK_FILES_DIRECTORY."/index.html", $targetPath . "/index.html");
		    	}
		    	
		    	$tempFile = $_FILES['uploadpp']['tmp_name'];
		    	$origName = substr($_FILES['uploadpp']['name'], 0, -4);
		    	$name_space = strtolower($_FILES['uploadpp']['name']);
		    	$middle_name = str_replace(" ", "_", $name_space);
		    	$middle_name = str_replace(".jpeg", ".jpg", $name_space);
		    	$glnrrand = rand(10, 99);
		    	$bigPhoto = str_replace(".", "_" . $glnrrand . ".", $middle_name);
		    	$smallPhoto = str_replace(".", "_t.", $bigPhoto);
		    	    
		    	$targetFile =  str_replace('//','/',$targetPath) . $bigPhoto;
		    	$origPath = '/'.$page2.'/';
		    	$dbSmall = $origPath.$smallPhoto;
		    	
		    	require_once '../include/functions_thumb.php';
		    	// Move file and create thumb     
		    	move_uploaded_file($tempFile,$targetFile);
		    	     
		    	create_thumbnail($targetPath, $targetFile, $smallPhoto, JAK_USERAVATWIDTH, JAK_USERAVATHEIGHT, 80);
		    	     	
		    	// SQL update
		    	$jakdb->update($jaktable, ["picture" => $dbSmall], ["id" => $page2]);
		    	     		
		    	} else {
		    		$errors['e'] = $jkl['e24'].'<br />';
		    		$errors = $errors;
		    	}
		    	
		    	} else {
		    		$errors['e'] = $jkl['e46'].'<br />';
		    		$errors = $errors;
		    	}
		    	
		    	} else {
		    		$errors['e'] = $jkl['e24'].'<br />';
		    		$errors = $errors;
		    	}
		    	
		    	} else {
		    		$errors['e'] = $jkl['e24'].'<br />';
		    		$errors = $errors;
		    	}
		    
		    }
		    
		    if (count($errors) == 0) {
		    
		    if (!isset($jkp['jak_access'])) $jkp['jak_access'] = '1';
		    
		    // We cant deny access for superadmin
		    $useridarray = explode(',', JAK_SUPERADMIN);
		    
		    if (!in_array($page2, $useridarray)) {

		    	$result = $jakdb->update($jaktable, ["access" => $jkp['jak_access']], ["AND" => ["id" => $page2, "opid" => $opcacheid]]);
		    }
		    
		    if (!isset($jkp['jak_depid']) OR in_array("0", $jkp['jak_depid'])) {
		    	$depa = 0;
		    } else {
		    	$depa = join(',', $jkp['jak_depid']);
		    }
		    
		    $bhours = '';
		    $bhours = trim($_REQUEST["bhours"]);
		    
		    // Reset the hours if they not set.
		    if ($bhours == '[{"isActive":false,"timeFrom":null,"timeTill":null,"timeFroma":null,"timeTilla":null},{"isActive":false,"timeFrom":null,"timeTill":null,"timeFroma":null,"timeTilla":null},{"isActive":false,"timeFrom":null,"timeTill":null,"timeFroma":null,"timeTilla":null},{"isActive":false,"timeFrom":null,"timeTill":null,"timeFroma":null,"timeTilla":null},{"isActive":false,"timeFrom":null,"timeTill":null,"timeFroma":null,"timeTilla":null},{"isActive":false,"timeFrom":null,"timeTill":null,"timeFroma":null,"timeTilla":null},{"isActive":false,"timeFrom":null,"timeTill":null,"timeFroma":null,"timeTilla":null}]') $bhours = '';

		    $smsphone = '';
			if (isset($jkp['jak_phone'])) $smsphone = $jkp['jak_phone'];

			$whatsphone = '';
			if (isset($jkp['jak_whatsphone'])) $whatsphone = $jkp['jak_whatsphone'];

			// No sibling update
		    $siblingupd = false;
		    
		    if (jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_MAIN_OP)) {
				
				$result = $jakdb->update($jaktable, ["departments" => $depa,
				"username" => trim($jkp['jak_username']),
				"name" => trim($jkp['jak_name']),
				"aboutme" => trim($jkp['jak_aboutme']),
				"phonenumber" => $smsphone,
				"whatsappnumber" => $whatsphone,
				"pusho_tok" => $jkp['jak_pushot'],
				"pusho_key" => $jkp['jak_pushok'],
				"responses" => $jkp['jak_responses'],
				"files" => $jkp['jak_files'],
				"operatorchat" => $jkp['jak_chat'],
				"operatorchatpublic" => $jkp['jak_chatpublic'],
				"operatorlist" => $jkp['jak_chatlist'],
				"transferc" => $jkp['jak_transfer'],
				"chat_latency" => $jkp['jak_latency'],
				"useronlinelist" => $jkp['jak_uolist'],
				"sound" => $jkp['jak_sound'],
				"ringing" => $jkp['jak_ringing'],
				"alwaysnot" => $jkp['jak_alwaysnot'],
				"alwaysonline" => $jkp['jak_alwaysonline'],
				"emailnot" => $jkp['jak_emailnot'],
				"navsidebar" => $jkp['jak_navsidebar'],
				"themecolour" => $jkp['jak_themecolour'],
				"language" => $jkp['jak_lang'],
				"invitationmsg" => $jkp['jak_inv'],
				"hours_array" => $bhours,
				"email" => filter_var($jkp['jak_email'], FILTER_SANITIZE_EMAIL)], ["id" => $page2]);
			
			} else {

				$result = $jakdb->update($jaktable, ["username" => trim($jkp['jak_username']),
				"name" => trim($jkp['jak_name']),
				"aboutme" => trim($jkp['jak_aboutme']),
				"phonenumber" => $smsphone,
				"whatsappnumber" => $whatsphone,
				"pusho_tok" => $jkp['jak_pushot'],
				"pusho_key" => $jkp['jak_pushok'],
				"chat_latency" => $jkp['jak_latency'],
				"useronlinelist" => $jkp['jak_uolist'],
				"sound" => $jkp['jak_sound'],
				"ringing" => $jkp['jak_ringing'],
				"alwaysnot" => $jkp['jak_alwaysnot'],
				"alwaysonline" => $jkp['jak_alwaysonline'],
				"emailnot" => $jkp['jak_emailnot'],
				"navsidebar" => $jkp['jak_navsidebar'],
				"themecolour" => $jkp['jak_themecolour'],
				"language" => $jkp['jak_lang'],
				"invitationmsg" => $jkp['jak_inv'],
				"hours_array" => $bhours,
				"email" => filter_var($jkp['jak_email'], FILTER_SANITIZE_EMAIL)], ["id" => $page2]);
			
			}

			// Table to update usually the one belongs to the operator // Sibling update
			if (isset($page3) && !empty($page3) && is_numeric($page3) && $jakdb->has($jaktable, ["AND" => ["id" => $page2, "opid" => $opcacheid]])) $siblingupd = true;

			// Finally we update the password
			if ($updatepass) $result = $jakdb->update($jaktable, ["password" => hash_hmac('sha256', $jkp['jak_password'], DB_PASS_HASH)], ["id" => $page2]);

			// Finally update the user permission
			if (jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_MAIN_OP)) {
		    
		        if (!isset($jkp['jak_roles'])) {
		        	$tw_roles = '';
		        } else {
		        	$tw_roles = join(',', $jkp['jak_roles']);
		        }

		        $result = $jakdb->update($jaktable, ["permissions" => $tw_roles], ["id" => $page2]);
		        
		    }
		
		if (!$result) {
		    $_SESSION["infomsg"] = $jkl['i'];
		    jak_redirect($_SESSION['LCRedirect']);
		} else {
			// We have a username change reset the sessions or we get logged out
			if ($jkp['jak_username'] != $jkp['jak_username_old']) {

				// Set the session
				$_SESSION['jak_username'] = $jkp['jak_username'];

				// Check if cookies are set previous (wrongly) and delete
				if (isset($_COOKIE['jak_lcp_cookname'])) {
					JAK_base::jakCookie('jak_lcp_cookname', $jkp['jak_username'], JAK_COOKIE_TIME, JAK_COOKIE_PATH);
				}
			}

			if (!$siblingupd) {

				// Now if we have multi site please update the main database.
				if (!empty(JAKDB_MAIN_NAME) && JAK_MAIN_LOC) {

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

					$jakdb1->update("users", [ 
						"email" => filter_var($jkp['jak_email'], FILTER_SANITIZE_EMAIL),
						"username" => trim($jkp['jak_username']),
						"lastedit" => $jakdb->raw("NOW()")], ["AND" => ["locationid" => JAK_MAIN_LOC, "opid" => $opcacheid]]);

				    if ($updatepass) $jakdb1->update("users", ["password" => hash_hmac('sha256', $jkp['jak_password'], DB_PASS_HASH)], ["AND" => ["locationid" => JAK_MAIN_LOC, "opid" => $opcacheid]]);
				}
			} // end siblings update

		    $_SESSION["successmsg"] = $jkl['g14'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
		
		// Output the errors
		} else {
		    
		   	$errors['e'] = $jkl['e'];
		    $errors = $errors;
		}
		}
		
			// Call the settings function
			$lang_files = jak_get_lang_files();
		
			if (is_numeric($page2) && is_numeric($page3) && $page2 == $page3) {
				$JAK_FORM_DATA = $jakdb->get($jaktable, "*", ["id" => JAK_USERID]);
			} else {
				$JAK_FORM_DATA = $jakdb->get($jaktable, "*", ["AND" => ["id" => $page2, "opid" => $opcacheid]]);
			}
			
			// Title and Description
			$SECTION_TITLE = $jkl["m11"];
			$SECTION_DESC = "";
			
			// Include the javascript file for results
			$js_file_footer = 'js_edituser.php';
			
			$template = 'edituser.php';
		
		} else {
			$_SESSION["errormsg"] = $jkl['i3'];
		    jak_redirect(JAK_rewrite::jakParseurl('users'));
		}
		
		break;
		case 'resethours':

			// No special access and not your userid, what you up to?
			if (!jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_MAIN_OP) && $page2 != JAK_USERID) jak_redirect(BASE_URL);
		
			// Protect the super operators from user manager
			if (!jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_MAIN_OP) && in_array($page2, explode(',', JAK_SUPERADMIN))) jak_redirect(BASE_URL);
	
			// Check if the user exists
			if (is_numeric($page2) && jak_row_exist($page2, $opcacheid, $jaktable, JAK_MAIN_OP)) {

				$result = $jakdb->update($jaktable, ["hours_array" => ""], ["id" => $page2]);

				$_SESSION["successmsg"] = $jkl['g14'];
		    	jak_redirect(JAK_rewrite::jakParseurl('users', 'edit', $page2));

			} else {
				$_SESSION["errormsg"] = $jkl['i2'];
		    	jak_redirect(JAK_rewrite::jakParseurl('users'));
			}
		break;
		default:
		
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				if (isset($_POST['jak_delock_all'])) {
			    $jkp = $_POST;
			    
			    if (isset($jkp['action']) && $jkp['action'] == "lock") {
				    
					    $lockuser = $jkp['jak_delock_all'];
					    $useridarray = explode(',', JAK_SUPERADMIN);
					
					    for ($i = 0; $i < count($lockuser); $i++) {
					        $locked = $lockuser[$i];
					        // Get the userid / access token
					        $uidacc = explode(":#:", $locked);
					        if (!in_array($uidacc[0], $useridarray)) {
					        	if ($uidacc[1] == 1) {
					        		$query = $jakdb->update($jaktable, ["access" => 0], ["AND" => ["id" => $uidacc[0], "opid" => $opcacheid]]);
					        	} else {
					        		$query = $jakdb->update($jaktable, ["access" => 1], ["AND" => ["id" => $uidacc[0], "opid" => $opcacheid]]);
					        	}
					    	}
					    }
					    
					    if ($query) {
					    	$_SESSION["successmsg"] = $jkl['g14'];
			    			jak_redirect($_SESSION['LCRedirect']);
			    		}
				  
				 	$_SESSION["infomsg"] = $jkl['i1'];
		    		jak_redirect($_SESSION['LCRedirect']);
			    
			    }
			    
			    if (isset($jkp['delete']) && $jkp['action'] == "delete") {
			    
					    $lockuser = $jkp['jak_delock_all'];
					    $useridarray = explode(',', JAK_SUPERADMIN);
					
					    for ($i = 0; $i < count($lockuser); $i++) {
					        $locked = $lockuser[$i];
					        // Get the userid / access token
					        $uidacc = explode(":#:", $locked);
					        if (!in_array($uidacc[0], $useridarray)) {
					        	$query = $jakdb->delete($jaktable, ["AND" => ["id" => $uidacc[0], "opid" => $opcacheid]]);
					    	}
					    }
					        
					    if ($query) {
						    $_SESSION["successmsg"] = $jkl['g14'];
				    		jak_redirect($_SESSION['LCRedirect']);
				    	}
			  
			 		$_SESSION["infomsg"] = $jkl['i1'];
		    		jak_redirect($_SESSION['LCRedirect']);
			    
			    }

			}

			    $_SESSION["infomsg"] = $jkl['i'];
		    	jak_redirect($_SESSION['LCRedirect']);
			    
			}
			 
			if (jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_SUPERADMIN)) {
				$JAK_USER_ALL = jak_get_user_all($jaktable, $opcacheid, JAK_USERID, JAK_SUPERADMIN);		
			} else {
				$JAK_USER_ALL = jak_get_user_all($jaktable, $opcacheid, JAK_USERID, '');
			}

			// Let's check if we can add more users
			$totalAll = count($JAK_USER_ALL);
			if ($totalavops > $totalAll) $addmuser = true;

			// Total Logs
			$totalAll = $totalSeven = $total_voted = $total_logins = 0;
			$total_vote = '-';

		    // Get the totals users
		    $totalAll = count($JAK_USER_ALL);

		    // Get all active users the last 7 days
			$totalSeven = $jakdb->count($jaktable, ["AND" => ["OR" => ["id" => JAK_USERID, "opid" => $opcacheid], "lastactivity[>=]" => strtotime("-1 week")]]);

			// Get total logins
			$total_logins = $jakdb->sum($jaktable, "logins", ["OR" => ["id" => JAK_USERID, "opid" => $opcacheid]]);

			// Get user ratings through chat
			$total_voted = $jakdb->count("user_stats", ["OR" => ["id" => JAK_USERID, "userid" => $opcacheid]]);
			$total_vote = $jakdb->sum("user_stats", "vote", ["OR" => ["id" => JAK_USERID, "userid" => $opcacheid]]);

			// Check and validate
		    $verify_response = $jaklic->verify_license(true);
		    if ($verify_response['status'] != true) {
		        if (JAK_SUPERADMINACCESS) {
		            jak_redirect(JAK_rewrite::jakParseurl('maintenance'));
		        } else {
		            $_SESSION["errormsg"] = $jkl['e27'];
		            jak_redirect(BASE_URL);
		        }
		    }
			
			// Title and Description
			$SECTION_TITLE = $jkl["m4"];
			$SECTION_DESC = "";
			
			// Include the javascript file for results
			$js_file_footer = 'js_user.php';
			
			// Call the template
			$template = 'user.php';
}
?>