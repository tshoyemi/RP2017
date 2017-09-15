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

jimport('joomla.application.component.model');
jimport('joomla.installer.installer');
jimport('joomla.filesystem.folder');

/**
 * JUX_Real_Estate Component - Dashboard Model.
 * @package		JUX_Real_Estate
 * @subpackage	Model
 */
class JUX_Real_EstateModelDashboard extends JModelLegacy {

    /**
     * Method to parse xml file to get component information.
     *
     * @return component information.
     */
    public function getXmlParser() {
	$xmlfiles = JFolder::files(JPATH_COMPONENT_ADMINISTRATOR, '.xml$', 1, true);
	$installer = JInstaller::getInstance();


	$xml = new stdClass();
	if (!empty($xmlfiles)) {

	    if (JVERSION < '3.0.0') {

		foreach ($xmlfiles as $file) {
		    $manifest = $installer->isManifest($file);
		    if ($manifest) {
			$xmlParse = JFactory::getXMLParser('simple');
			$xmlParse->loadFile($file);

			$xml->version = $xmlParse->document->getElementByPath('version')->data();
			$xml->copyright = $xmlParse->document->getElementByPath('copyright')->data();
			//$xml->license = $xmlParse->document->getElementByPath('license')->data();
			$xml->jed_link = $xmlParse->document->getElementByPath('jedlink')->data();
			foreach ($xmlParse->document->children() as $child) {
			    if (is_a($child, 'JSimpleXMLElement') && $child->name() == 'members') {
				if (count($child->children())) {
				    $i = 0;
				    foreach ($child->children() as $c) {
					$xml->juxmember[$i]['name'] = trim($c->_attributes['type']);
					$xml->juxmember[$i]['data'] = trim($c->data());
					$i++;
				    }
				}
			    }
			}
		    }
		}
	    }

	    if (JVERSION >= '3.0.0') {
		foreach ($xmlfiles as $file) {
		    $manifest = $installer->isManifest($file);
		    if ($manifest) {
			$xmlParse = JFactory::getXML($file, true);

			$xml->version = (string) $xmlParse->version;
			$xml->copyright = (string) $xmlParse->copyright;
			$xml->license = (string) $xmlParse->license;
			$xml->jed_link = (string) $xmlParse->jedlink;

			foreach ($xmlParse->children() as $child) {
			    if ($child->name() == 'members') {
				if (count($child->children())) {
				    $i = 0;
				    foreach ($child->children() as $c) {
					$xml->juxmember[$i]['name'] = trim((string) $c->attributes()->type);
					$xml->juxmember[$i]['data'] = trim($c);
					$i++;
				    }
				}
			    }
			}
		    }
		}
	    }
	}

	return $xml;
    }

}