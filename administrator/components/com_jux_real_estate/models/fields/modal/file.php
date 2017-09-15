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

defined('JPATH_BASE') or die;

/**
 * Supports a modal article picker.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_content
 * @since       1.6
 */
class JFormFieldModal_File extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since   1.6
     */
    protected $type = 'Modal_File';

    /**
     * Method to get the field input markup.
     *
     * @return  string	The field input markup.
     * @since   1.6
     */
    protected function getInput() {
	// Load the modal behavior script.
	JHtml::_('behavior.modal', 'a.modal');

	// Build the script.
	$script = array();
	$script[] = '	function jSelectFile_' . $this->id . '(id, title) {';
	$script[] = '		document.id("' . $this->id . '_id").value = id;';
	$script[] = '		document.id("' . $this->id . '_name").value = title;';
	$script[] = '		SqueezeBox.close();';
	$script[] = '	}';

	// Add the script to the document head.
	JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

	// Setup variables for display.
	$html = array();
	$link = 'index.php?option=com_jux_fileseller&amp;view=files&amp;layout=modal&amp;tmpl=component&amp;function=jSelectFile_' . $this->id;

	$db = JFactory::getDbo();
	$db->setQuery(
		'SELECT title' .
		' FROM #__jux_fileseller_files' .
		' WHERE id = ' . (int) $this->value
	);

	try {
	    $title = $db->loadResult();
	} catch (RuntimeException $e) {
	    JError::raiseWarning(500, $e->getMessage());
	}

	if (empty($title)) {
	    $title = JText::_('COM_JUX_FILESELLER_SELECT_A_FILE');
	}
	$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

	// The current user display field.
	$html[] = '<span class="input-append">';
	$html[] = '<input type="text" class="input-medium" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" /><a class="modal btn" title="' . JText::_('COM_JUX_FILESELLER_CHANGE_FILE') . '"  href="' . $link . '&amp;' . JSession::getFormToken() . '=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> ' . JText::_('JUXLECT') . '</a>';
	$html[] = '</span>';

	// The active article id field.
	if (0 == (int) $this->value) {
	    $value = '';
	} else {
	    $value = (int) $this->value;
	}

	// class='required' for client side validation
	$class = '';
	if ($this->required) {
	    $class = ' class="required modal-value"';
	}

	$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

	return implode("\n", $html);
    }

}
