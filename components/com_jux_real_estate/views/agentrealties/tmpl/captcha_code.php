<?php
/**
 * @version     $Id: $
 * @author      Joomseller!
 * @package     Joomla.Site
 * @subpackage  com_jse_real_estate
 * @copyright   Copyright (C) 2008 - 2012 by Joomseller. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
if (!isset($_SESSION)) {
    session_start();
}

class CapCha {

    public function getImgCapcha() {
        ob_start();
        $img = null;
        $ranStr = md5(microtime());
        $ranStr = substr($ranStr, 0, 4);
        $_SESSION['captcha_code'] = $ranStr;
       
        $newImage = imagecreatefromjpeg("components/com_jux_real_estate/views/agentrealties/tmpl/bg_captcha.jpg");
        $txtColor = imagecolorallocate($newImage, 0, 0, 0);
        imagestring($newImage, 5, 5, 5, $ranStr, $txtColor);
        imagejpeg($newImage);
        $rawImageBytes = ob_get_clean();
        echo "<img src='data:image/jpeg;base64," . base64_encode($rawImageBytes) . "' alt='captcha'  />";
    }

}
