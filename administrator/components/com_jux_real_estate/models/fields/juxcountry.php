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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldJUXCountry extends JFormField {

    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'JUXCountry';

    /**
     * Method to get the field input markup.
     *
     * @return  string   The field input markup.
     *
     * @since   11.1
     */
    protected function getInput() {
	// Initialize variables.
	$html = array();
	$attr = '';

	// Initialize some field attributes.
	$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
//	$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '1';

	// Initialize JavaScript field attributes.
	$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

	// front end field attributes
	$frontend = (isset($this->element['frontend']) && $this->element['frontend']) ? true : false;

	$controller = ($frontend) ? 'ajax' : 'states';
	// Build the script.
	if (JVERSION >= '3.0.0') {
	    $script = "jQuery(function() {
			jQuery('#jform_country_id').change(function() {
                            $('ajax-container').empty().addClass('ajax-loading');

                            //Ajax Request start here
                            var myElement = document.getElementById('ajax-container');
                            var cid = document.getElementById('jform_country_id').value;
                            var myRequest = new Request({
                                url: 'index.php?option=com_jux_real_estate&task=$controller.getstates',
                                method: 'get',
                                evalResponse: 'true',
                                data: {
                                'country_id' : cid,
                                },
                                onRequest: function(){
                                myElement.set('text', 'Loading. Please wait...');
                                myElement.addClass('loading');
                                },
                                onSuccess: function(responseText){
                                myElement.set('html', responseText);
                                myElement.addClass('success');
                                },
                                onFailure: function(){
                                myElement.set('text', 'Sorry, your request failed :(');
                                myElement.addClass('error');
                                }
                            }).send();

                        });
                    });
            ";
	}

	// Add the script to the document head.
	JFactory::getDocument()->addScriptDeclaration($script);

	$data = $this->getData();

	return JHTML::_('select.genericlist', $data, $this->name, $attr, 'value', 'text', $this->value, $this->id, true);

	return implode($html);
    }

    /**
     * Method to get list of countries.
     *
     * @return	array
     */
    protected function getData() {
	// Get a database object.
	$db = JFactory::getDBO();

	$query = $db->getQuery(true);

	$query->select('`id` AS value, `name` AS text');
	$query->from('#__re_countries');
	$query->where('published = 1');
	$query->order('id');

	$db->setQuery($query);
	$sec = array();
	$sec[] = JHTML::_('select.option', '', '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_COUNTRY') . ' -', 'value', 'text');
	try {
	    $countries = $db->loadObjectList();
	    if (count($countries)) {
		foreach ($countries as $country) {
		    $sec[] = JHTML::_('select.option', $country->value, $country->text, 'value', 'text');
		}
	    }
	} catch (JDatabaseException $e) {
	    $je = new JException($e->getMessage());
	    $this->setError($je);
	    return array();
	}

	return $sec;
    }

}
