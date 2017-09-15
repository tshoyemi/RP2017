<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla
 * @subpackage		com_jux_real_estate
 * 
 * @copyright		Copyright (C) 2012 - 2015 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL, See LICENSE.txt
 */

defined('_JEXEC') or die;
require_once ('recaptchalib.php');
// get a response from reCaptcha
$resp = null;
$msg = null;
$private_key = $_REQUEST['private_key'];
$recaptcha_response_field = $_REQUEST['recaptcha_response_field'];
$recaptcha_challenge_field = $_REQUEST['recaptcha_challenge_field'];
if ($recaptcha_response_field != '') {

    $resp = recaptcha_check_answer($private_key, $_SERVER["REMOTE_ADDR"], $recaptcha_challenge_field, $recaptcha_response_field);
    if ($resp->is_valid) {
	echo 'Successfull';
    } else {
	echo '<b style="color: red">You entered is incorrect. Please, re-enter!</b>';
    }
} else {
    echo '<b style="color: red">You must enter the verification code !</b>';
}
?>
