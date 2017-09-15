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

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * JUX_Real_Estate Image Libraries
 * @package		JUX_Real_Estate
 * @subpackage	Libraries
 * @since		3.0
 */
class JUX_Real_EstateClassImage {

    /**
     * Get version of GD library
     * 
     * @access	public
     * @since	3.0
     */
    function getGDVersion($user_ver = 0) {
	if (!extension_loaded('gd')) {
	    return;
	}

	static $gd_ver = 0;

	// just accept the specified setting if it's 1.
	if ($user_ver == 1) {
	    $gd_ver = 1;
	    return 1;
	}

	// use static variable if function was cancelled previously.
	if ($user_ver != 2 && $gd_ver > 0) {
	    return $gd_ver;
	}

	// use the gd_info() function if posible.
	if (function_exists('gd_info')) {
	    $ver_info = gd_info();
	    $match = null;
	    preg_match('/\d/', $ver_info['GD Version'], $match);
	    $gd_ver = $match[0];

	    return $match[0];
	}

	// if phpinfo() is disabled use a specified / fail-safe choice...
	if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
	    if ($user_ver == 2) {
		$gd_ver = 2;
		return 2;
	    } else {
		$gd_ver = 1;
		return 1;
	    }
	}
	// ...otherwise use phpinfo().
	ob_start();
	phpinfo(8);
	$info = ob_get_contents();
	ob_end_clean();
	$info = stristr($info, 'gd version');
	$match = null;
	preg_match('/\d/', $info, $match);
	$gd_ver = $match[0];

	return $match[0];
    }

    /**
     * Get real image width and height to resize
     * 
     * @access	public
     * @since	1.0
     */
    function getSize($image, $width, $height) {
	$info = @getimagesize($image); // width = info[0], height = info[1]

	if ($info[0] < $width && $info[1] < $height) {
	    return array($info[0], $info[1]);
	}

	if ($info[0] / $width > $info[1] / $height) {
	    $percentage = $width / $info[0];
	} else {
	    $percentage = $height / $info[1];
	}

	return array(round($info[0] * $percentage), round($info[1] * $percentage));
    }

    /**
     * Get real size
     * 
     * @access	public
     * @since	1.0
     */
    function imageResize($width, $height, $max_width, $max_height) {
	if ($width < $max_width && $height < $max_height) {
	    return array($width, $height);
	}

	if ($width / $max_width > $height / $max_height) {
	    $percentage = $max_width / $width;
	} else {
	    $percentage = $max_height / $height;
	}

	return array(round($width * $percentage), round($height * $percentage));
    }

    /**
     * Get image filename to upload
     * 
     * @access	public
     * @since	1.0
     */
    function sanitize($base_dir, $filename) {
	jimport('joomla.filesystem.file');

	//check for any leading/trailing dots and remove them (trailing shouldn't be possible cause of the getEXT check)
	$filename = preg_replace("/^[.]*/", '', $filename);
	$filename = preg_replace("/[.]*$/", '', $filename); //shouldn't be necessary, see above
	//we need to save the last dot position cause preg_replace will also replace dots
	$lastdotpos = strrpos($filename, '.');

	//replace invalid characters
	$chars = '[^0-9a-zA-Z()_-]';
	$filename = strtolower(preg_replace("/$chars/", '_', $filename));

	//get the parts before and after the dot (assuming we have an extension...check was done before)
	$beforedot = substr($filename, 0, $lastdotpos);
	$afterdot = substr($filename, $lastdotpos + 1);

	//make a unique filename for the image and check it is not already taken
	//if it is already taken keep trying till success
	$now = time();

	while (JFile::exists($base_dir . $beforedot . '_' . $now . '.' . $afterdot)) {
	    $now++;
	}

	//create out of the seperated parts the new filename
	$filename = $beforedot . '_' . $now . '.' . $afterdot;

	return $filename;
    }

    /**
     * Add image subfix
     * 
     * @access	public
     * @since	1.0
     */
    function addSubfix($filename, $subfix) {
	//check for any leading/trailing dots and remove them (trailing shouldn't be possible cause of the getEXT check)
	$filename = preg_replace("/^[.]*/", '', $filename);
	$filename = preg_replace("/[.]*$/", '', $filename); //shouldn't be necessary, see above
	//we need to save the last dot position cause preg_replace will also replace dots
	$lastdotpos = strrpos($filename, '.');

	//replace invalid characters
	$chars = '[^0-9a-zA-Z()_-]';
	$filename = strtolower(preg_replace("/$chars/", '_', $filename));

	//get the parts before and after the dot (assuming we have an extension...check was done before)
	$beforedot = substr($filename, 0, $lastdotpos);
	$afterdot = substr($filename, $lastdotpos + 1);

	$filename = $beforedot . '_' . $subfix . '.' . $afterdot;

	return $filename;
    }

    /**
     * Check image for uploading
     * 
     * @access	public
     * @since	1.0
     */
    function check($file, $maxsize = 1000) {
	jimport('joomla.filesystem.file');

	$sizelimit = $maxsize * 1048576; //size limit in kb
	$imagesize = $file['size'];

	// check if the upload is an image...getimagesize will return false if not
	if (!getimagesize($file['tmp_name'])) {
	    JError::raiseWarning(100, JText::_('COM_JUX_REAL_ESTATE_UPLOAD_FAILED_THE_UPLOADED_FILE_IS_NOT_AN_IMAGE') . ': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));
	    return false;
	}
	// check if the imagefiletype is valid
	$fileext = JFile::getExt($file['name']);

	$allowable = array('gif', 'jpg', 'png', 'jpeg');
	if (!in_array(strtolower($fileext), $allowable)) {
	    JError::raiseWarning(100, JText::_('COM_JUX_REAL_ESTATE_THE_FILE_MUST_BE_GIF_PNG_JPG') . ': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));
	    return false;
	}
	// check filesize
	if ($imagesize > $sizelimit) {
	    JError::raiseWarning(100, JText::_('COM_JUX_REAL_ESTATE_IMAGE_SIZE_TOO_BIG') . ': ' . htmlspecialchars($file['name'], ENT_COMPAT, 'UTF-8'));
	    return false;
	}
	// XSS check
	$xss_check = JFile::read($file['tmp_name'], false, 256);
	$html_tags = array('abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink', 'blockquote', 'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del', 'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext', 'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object', 'ol', 'optgroup', 'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar', 'small', 'spacer', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title', 'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE', '!--');
	foreach ($html_tags as $tag) {
	    // A tag is '<tagname ', so we need to add < and a space or '<tagname>'
	    if (stristr($xss_check, '<' . $tag . ' ') || stristr($xss_check, '<' . $tag . '>')) {
		JError::raiseWarning(100, JText::_('COM_JUX_REAL_ESTATE_IE_XSS_WARNING'));
		return false;
	    }
	}

	return true;
    }

    /**
     * Resize image with width and height limit
     * 
     * @access	public
     * @since	1.0
     */
    function resize($file, $save, $width, $height) {
	// GD-Lib > 2.0 only!
	@unlink($save);

	// get sizes else stop
	if (!$infos = @getimagesize($file)) {
	    return false;
	}

	// keep proportions
	$iWidth = $infos[0];
	$iHeight = $infos[1];
	$iRatioW = $width / $iWidth;
	$iRatioH = $height / $iHeight;

	if ($iRatioW < $iRatioH) {
	    $iNewW = $iWidth * $iRatioW;
	    $iNewH = $iHeight * $iRatioW;
	} else {
	    $iNewW = $iWidth * $iRatioH;
	    $iNewH = $iHeight * $iRatioH;
	}

	// don't resize images which are smaller than thumbs
	if ($infos[0] < $width && $infos[1] < $height) {
	    $iNewW = $infos[0];
	    $iNewH = $infos[1];
	}

	if ($infos[2] == 1) {
	    // image is type gif
	    $imgA = imagecreatefromgif($file);
	    $imgB = imagecreate($iNewW, $iNewH);

	    // keep gif transparent color if possible
	    if (function_exists('imagecolorsforindex') && function_exists('imagecolortransparent')) {
		$transcolorindex = imagecolortransparent($imgA);
		//transparent color exists
		if ($transcolorindex >= 0) {
		    $transcolor = imagecolorsforindex($imgA, $transcolorindex);
		    $transcolorindex = imagecolorallocate($imgB, $transcolor['red'], $transcolor['green'], $transcolor['blue']);
		    imagefill($imgB, 0, 0, $transcolorindex);
		    imagecolortransparent($imgB, $transcolorindex);
		    // fill white
		} else {
		    $whitecolorindex = @imagecolorallocate($imgB, 255, 255, 255);
		    imagefill($imgB, 0, 0, $whitecolorindex);
		}
		// fill white
	    } else {
		$whitecolorindex = imagecolorallocate($imgB, 255, 255, 255);
		imagefill($imgB, 0, 0, $whitecolorindex);
	    }
	    imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
	    imagegif($imgB, $save);
	} elseif ($infos[2] == 2) {
	    // image is type jpg
	    $imgA = imagecreatefromjpeg($file);
	    $imgB = imagecreatetruecolor($iNewW, $iNewH);
	    imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
	    imagejpeg($imgB, $save);
	} elseif ($infos[2] == 3) {
	    // image is type png
	    $imgA = imagecreatefrompng($file);
	    $imgB = imagecreatetruecolor($iNewW, $iNewH);
	    imagealphablending($imgB, false);
	    imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
	    imagesavealpha($imgB, true);
	    imagepng($imgB, $save);
	} else {
	    return false;
	}

	return true;
    }

}

?>