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


define('NUMBER_OPTION_PER_LINE', 1);
define('FIELD_TYPE_TEXTBOX', 0);
define('FIELD_TYPE_TEXTAREA', 1);
define('FIELD_TYPE_DROPDOWN', 2);
define('FIELD_TYPE_CHECKBOXLIST', 3);
define('FIELD_TYPE_RADIOLIST', 4);
define('FIELD_TYPE_DATETIME', 5);
define('FIELD_TYPE_HEADING', 6);
define('FIELD_TYPE_MESSAGE', 7);
define('FIELD_TYPE_MULTISELECT', 8);

/**
 * JUX_Real_Estate Component - Fields Helper
 * @package		JUX_Real_Estate
 * @subpackage	Helper
 * @since		1.5
 */
class JUX_Real_EstateFields {

    /**
     * List of custom fields used in the system
     *
     * @var array
     */
    var $_fields = null;

    /**
     * List of custom search fields used in the system
     *
     * @var array
     */
    var $_searchfields = null;

    /**
     * Constructor
     *
     */
    function __construct() {
	//Load all custom field
	$db = JFactory::getDBO();
	$sql = 'SELECT * FROM #__re_fields WHERE published=1 ORDER BY ordering';
	$db->setQuery($sql);
	$this->_fields = $db->loadObjectList();

	//Load all custom search field
	$db = JFactory::getDBO();
	$sql = 'SELECT * FROM #__re_fields WHERE published=1 AND search_field = 1 ORDER BY ordering';
	$db->setQuery($sql);
	$this->_searchfields = $db->loadObjectList();
    }

    /**
     * Get total custom fields
     *
     * @return int
     */
    function getTotal() {
	return count($this->_fields);
    }

    /**
     * Get total custom search fields
     *
     * @return int
     */
    function getSearchTotal() {
	return count($this->_searchfields);
    }

    /**
     * Render a textbox
     *
     * @param object $row
     */
    function _renderTextBox($row) {
	$postedValue = JRequest::getVar($row->name, $row->default_values);
	?>
	<div class="control-group">
	    <div class="control-label">
		<label for="<?php echo $row->name; ?>">
		    <strong><?php echo JText::_($row->title); ?></strong>:
		    <?php
		    if ($row->required)
			echo '<span class="jp_required">*</span>';
		    ?>
		</label>
	    </div>
	    <div class="controls">
		<input type="text" id="<?php echo $row->name; ?>" name="<?php echo $row->name; ?>" class="inputbox <?php echo $row->css_class; ?> <?php echo ($row->required) ? 'required' : ''; ?>" size="<?php echo $row->size; ?>" value="<?php echo JText::_($postedValue); ?>" />
	    </div>
	</div>
	<?php
    }

    /**
     * Render a search textbox
     *
     * @param object $row
     */
    function _renderSearchTextBox($row) {
	?>
	<div class="control-label">
	    <strong class="title_cell"><?php echo JText::_($row->title); ?></strong>
	</div>
	<div class="controls">
	    <input type="text" size="18" name="<?php echo $row->name; ?>" class="inputbox" value="" />
	</div>
	<?php
    }

    /**
     * Render textbox when edit a realty
     *
     * @param object $row
     * @param string $value
     */
    function _renderTextBoxEdit($row, $value) {
	?>
	<div class="control-group">
	    <div class="control-label">
		<?php echo JText::_($row->title); ?>
	    </div>
	    <div class="controls">
		<input type="text" name="<?php echo $row->name; ?>" class="<?php echo $row->css_class; ?>" size="<?php echo $row->size; ?>" value="<?php echo JText::_($value); ?>" />
	    </div>
	</div>
	<?php
    }

    /**
     * Gender validation for textbox 
     *
     * @param object $row
     */
    function _renderTextBoxValidation($row) {
	?>
	if (form.<?php echo $row->name; ?>.value == "") {
	//alert("<?php echo JText::_($row->title); ?> <?php echo JText::_('COM_JUX_REAL_ESTATE_IS_REQUIRED'); ?>");
	error_msg.removeClass('jux-hide');
	form.<?php echo $row->name; ?>.focus();
	return ;
	}
	<?php
    }

    /**

     * Output values which users entered in the textbox field

     *

     * @param object $row

     */
    function _renderTextboxConfirmation($row) {
	?>
	<div class="control-group">
	    <div class="control-label">
		<?php echo JText::_($row->title); ?>
	    </div>
	    <div class="controls">
		<?php
		$name = $row->name;
		$postedValue = JRequest::getVar($name, '', 'post');
		echo $postedValue;
		?>
	    </div>
	</div>
	<?php
    }

    /**
     * Render hidden field for textbox 
     *
     * @param object $row
     */
    function _renderTextboxHidden($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	?>
	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $postedValue; ?>" />
	<?php
    }

    /**
     * Render textarea object
     *
     * @param object $row
     */
    function _renderTextarea($row) {
	$postedValue = JRequest::getVar($row->name, $row->default_values);
	?>
	<div class="control-group">
	    <div class="control-label">
		<label for="<?php echo $row->name; ?>">
		    <strong><?php echo JText::_($row->title); ?></strong>:
		    <?php
		    if ($row->required)
			echo '<span class="jp_required">*</span>';
		    ?>
		</label>
	    </div>
	    <div class="controls">
		<textarea id="<?php echo $row->name; ?>" name="<?php echo $row->name; ?>" rows="<?php echo $row->rows; ?>" cols="<?php echo $row->cols; ?>" class="inputbox <?php echo $row->css_class; ?> <?php echo ($row->required) ? 'required' : ''; ?>"><?php echo $postedValue; ?></textarea>
	    </div>
	</div>
	<?php
    }

    /**
     * Render search textarea object
     *
     * @param object $row
     */
    function _renderSearchTextarea($row) {
	?>

	<div class="control-label">
	    <strong class="title_cell"><?php echo JText::_($row->title); ?></strong>
	</div>
	<div class="controls">
	    <textarea name="<?php echo $row->name; ?>" rows="<?php echo $row->rows; ?>" cols="<?php echo $row->cols; ?>" class="inputbox"></textarea>
	</div>
	<?php
    }

    /**
     * Render textarea in edit mode
     *
     * @param object $row
     * @param string $value
     */
    function _renderTextareaEdit($row, $value) {
	?>
	<div class="control-group">
	    <div class="control-label">
		<?php echo JText::_($row->title); ?>
	    </div>
	    <div class="controls">
		<textarea name="<?php echo $row->name; ?>" rows="<?php echo $row->rows; ?>" cols="<?php echo $row->cols; ?>" class="<?php echo $row->css_class; ?>"><?php echo JText::_($value); ?></textarea>
	    </div>
	</div>
	<?php
    }

    /**
     * Gender validation for textarea 
     *
     * @param object $row
     */
    function _renderTextAreaValidation($row) {
	?>
	if (form.<?php echo $row->name; ?>.value == "") {
	//alert("<?php echo JText::_($row->title); ?> <?php echo JText::_('COM_JUX_REAL_ESTATE_IS_REQUIRED'); ?>");
	error_msg.removeClass('jux-hide');
	form.<?php echo $row->name; ?>.focus();
	return ;
	}
	<?php
    }

    /**
     * Output values which users entered in the textarea field
     *
     * @param object $row
     */
    function _renderTextAreaConfirmation($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	?>
	<div class="control-group">
	    <div class="control-label">
		<?php echo JText::_($row->title); ?>
	    </div>
	    <div class="controls">
		<?php echo $postedValue; ?>
	    </div>
	</div>
	<?php
    }

    /**
     * Render hidden field for textarea
     *
     * @param object $row
     */
    function _renderTextAreaHidden($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	?>
	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $postedValue; ?>" />
	<?php
    }

    /**
     * Render dropdown field type
     *
     * @param object $row
     */
    function _renderDropdown($row) {
	$postedValue = JRequest::getVar($row->name, $row->default_values, 'post');
	$options = array();
	$options[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_A_VALUE'));
	$values = explode("\r\n", $row->values);
	for ($i = 0, $n = count($values); $i < $n; $i++) {
	    $options[] = JHTML::_('select.option', $values[$i], JText::_($values[$i]));
	}
	?>
	<div class="control-group">
	    <div class="control-label">
		<label for="<?php echo $row->name; ?>">
		    <strong><?php echo JText::_($row->title); ?></strong>:
		    <?php
		    if ($row->required)
			echo '<span class="jp_required">*</span>';
		    ?>
		</label>
	    </div>
	    <div class="controls">
		<?php
		$required = ($row->required) ? ' required' : '';
		$class = 'id="' . $row->name . '" class="inputbox ' . $row->css_class . $required . '"';
		echo JHTML::_('select.genericlist', $options, $row->name, $class, 'value', 'text', $postedValue);
		?>
	    </div>
	</div>
	<?php
    }

    /**
     * Render search dropdown field type
     *
     * @param object $row
     */
    function _renderSearchDropdown($row) {
	$options = array();
	$options[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_A_VALUE'));
	$values = explode("\r\n", $row->values);
	for ($i = 0, $n = count($values); $i < $n; $i++) {
	    $options[] = JHTML::_('select.option', $values[$i], JText::_($values[$i]));
	}
	?>
	<div class="control-label">
	    <strong class="title_cell"><?php echo JText::_($row->title); ?></strong>
	</div>
	<div class="controls">
	    <?php
	    echo JHTML::_('select.genericlist', $options, $row->name, '', 'value', 'text');
	    ?>
	</div>
	<?php
    }

    /**
     * Render the dropdown in edit mode
     *
     * @param object $row
     * @param string $value
     */
    function _renderDropdownEdit($row, $value) {
	$options = array();
	$options[] = JHTML::_('select.option', '', JText::_('COM_JUX_REAL_ESTATE_SELECT_A_VALUE'));
	$values = explode("\r\n", $row->values);
	for ($i = 0, $n = count($values); $i < $n; $i++) {
	    $options[] = JHTML::_('select.option', $values[$i], JText::_($values[$i]));
	}
	?>
	<div class="control-group">
	    <div class="control-label">
		<?php echo JText::_($row->title); ?>
	    </div>
	    <div class="controls">
		<?php
		echo JHTML::_('select.genericlist', $options, $row->name, '', 'value', 'text', $value);
		?>
	    </div>
	</div>
	<?php
    }

    /**
     * Gender validation for dropdown 
     *
     * @param object $row
     */
    function _renderDropdownValidation($row) {
	?>
	if (form.<?php echo $row->name; ?>.selectedIndex == 0) {
	//alert("<?php echo JText::_($row->title); ?> <?php echo JText::_('COM_JUX_REAL_ESTATE_IS_REQUIRED'); ?>");
	error_msg.removeClass('jux-hide');
	form.<?php echo $row->name; ?>.focus();
	return ;
	}
	<?php
    }

    /**
     * Output values which users choosed in the dropdown
     *
     * @param object $row
     */
    function _renderDropDownConfirmation($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	?>
	<div class="control-group">
	    <div class="control-label">
		<?php echo JText::_($row->title); ?>
	    </div>
	    <div class="controls">
		<?php echo $postedValue; ?>
	    </div>

	</div>

	<?php
    }

    /**
     * Render hidden field for textbox 
     *
     * @param object $row
     */
    function _renderDropdownHidden($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	?>
	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $postedValue; ?>" />
	<?php
    }

    /**
     * Render dropdown field type
     *
     * @param object $row
     */
    function _renderMultiSelect($row) {
	if (isset($_POST[$row->name])) {
	    $selectedValues = $_POST[$row->name];
	} else {
	    $selectedValues = explode("\r\n", $row->default_values);
	}
	$options = array();
	$values = explode("\r\n", $row->values);
	for ($i = 0, $n = count($values); $i < $n; $i++) {
	    $options[] = JHTML::_('select.option', $values[$i], JText::_($values[$i]));
	}
	$selectedOptions = array();
	for ($i = 0, $n = count($selectedValues); $i < $n; $i++) {
	    $selectedOptions[] = JHTML::_('select.option', $selectedValues[$i], $selectedValues[$i]);
	}
	?>
	<div class="control-group">
	    <div class="control-label">
		<label for="<?php echo $row->name; ?>">
		    <strong><?php echo JText::_($row->title); ?></strong>:
		    <?php
		    if ($row->required)
			echo '<span class="jp_required">*</span>';
		    ?>
		</label>
	    </div>
	    <div class="controls">
		<?php
		$required = ($row->required) ? ' required' : '';
		$class = 'id="' . $row->name . '" class="inputbox ' . $row->css_class . $required . '"';
		echo JHTML::_('select.genericlist', $options, $row->name . '[]', ' multiple="multiple" size="4" ' . $class, 'value', 'text', $selectedValues);
		?>
	    </div>>
	</div>
	<?php
    }

    /**
     * Render search dropdown field type
     *
     * @param object $row
     */
    function _renderSearchMultiSelect($row) {
	$options = array();
	$values = explode("\r\n", $row->values);
	for ($i = 0, $n = count($values); $i < $n; $i++) {
	    $options[] = JHTML::_('select.option', $values[$i], JText::_($values[$i]));
	}
	?>
	<div class="control-label">
	    <strong class="title_cell"><?php echo JText::_($row->title); ?></strong>
	</div>
	<div class="controls">
	    <?php
	    echo JHTML::_('select.genericlist', $options, $row->name . '[]', ' multiple="multiple" size="4" ', 'value', 'text');
	    ?>
	</div>
	<?php
    }

    /**
     * Render the dropdown in edit mode
     *
     * @param object $row
     * @param string $value 
     */
    function _renderMultiSelectEdit($row, $value) {
	$options = array();
	$values = explode("\r\n", $row->values);
	for ($i = 0, $n = count($values); $i < $n; $i++) {
	    $options[] = JHTML::_('select.option', $values[$i], JText::_($values[$i]));
	}
	$selectedValues = explode('|*|', $value);
	$selectedOptions = array();
	for ($i = 0, $n = count($selectedValues); $i < $n; $i++) {
	    $selectedOptions[] = JHTML::_('select.option', $selectedValues[$i], $selectedValues[$i]);
	}
	?>
	<div class="control-group">
	    <div class="control-label">
		<?php echo JText::_($row->title); ?>
	    </div>
	    <div class="controls">
		<?php
		echo JHTML::_('select.genericlist', $options, $row->name . '[]', ' multiple="multiple" size="4" ', 'value', 'text', $selectedOptions);
		?>
	    </div>
	</div>
	<?php
    }

    /**
     * Gender validation for dropdown 
     *
     * @param object $row
     */
    function _renderMultiSelectValidation($row) {
	?>
	var selected = false ;
	for (var i = 0 ; i < form["<?php echo $row->name; ?>[]"].length ; i++) {
	if (form["<?php echo $row->name; ?>[]"][i].selected) {
	selected = true ;
	break ;
	}
	}
	if (!selected) {
	//alert("<?php echo JText::_($row->title); ?> <?php echo JText::_('COM_JUX_REAL_ESTATE_IS_REQUIRED'); ?>");
	error_msg.removeClass('jux-hide');
	form.<?php echo $row->name; ?>.focus();
	return ;
	}
	<?php
    }

    /**
     * Output values which users choosed in the dropdown
     *
     * @param object $row
     */
    function _renderMultiSelectConfirmation($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	$postedValue = implode('|*|', $postedValue);
	?>
	<div class="control-group">
	    <div class="control-label">
		<?php echo JText::_($row->title); ?>
	    </div>
	    <div class="control">
		<?php echo $postedValue; ?>
	    </div>
	</div>
	<?php
    }

    /**
     * Render hidden field for textbox 
     *
     * @param object $row
     */
    function _renderMultiSelectHidden($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	for ($i = 0, $n = count($postedValue); $i < $n; $i++) {
	    ?>
	    <input type="hidden" name="<?php echo $name; ?>[]" value="<?php echo $postedValue[$i]; ?>" />
	    <?php
	}
    }

    /**
     * Render checkbox list
     *
     * @param object $row
     */
    function _renderCheckboxList($row) {
	$values = explode("\r\n", $row->values);
	if (isset($_POST[$row->name])) {
	    $defaultValues = $_POST[$row->name];
	} else {
	    $defaultValues = explode("\r\n", $row->default_values);
	}
	?>
	<div class="control-group">
	    <div class="control-label">
		<label for="<?php echo $row->name; ?>">
		    <strong><?php echo JText::_($row->title); ?></strong>:
		    <?php
		    if ($row->required)
			echo '<span class="jp_required">*</span>';
		    ?>
		</label>
	    </div>
	    <div class="controls">
		<?php
		$required = ($row->required) ? ' required' : '';
		$class = 'id="' . $row->name . '" class="inputbox ' . $row->css_class . $required . '"';
		?>
		<div cellspacing="3" cellpadding="3" width="100%">
		    <?php
		    for ($i = 0, $n = count($values); $i < $n; $i++) {
			$value = $values[$i];
			if ($i % NUMBER_OPTION_PER_LINE == 0) {
			    ?>
			    <div>
				<?php
			    }
			    ?>
	    		<div>
	    		    <input class="inputbox" value="<?php echo $value; ?>" type="checkbox" name="<?php echo $row->name; ?>[]" <?php if (in_array($value, $defaultValues)) echo ' checked="checked" '; ?>><?php echo JText::_($value); ?>
	    		</div>
			    <?php
			    if (($i + 1) % NUMBER_OPTION_PER_LINE == 0) {

				echo '</div>';
			    }
			}
			if ($i % NUMBER_OPTION_PER_LINE != 0) {
			    $colspan = NUMBER_OPTION_PER_LINE - $i % NUMBER_OPTION_PER_LINE;
			    ?>
	    		<div colspan="<?php echo $colspan; ?>">&nbsp;</div>
	    	    </div>
			<?php
		    }
		    ?>
		</div>
	    </div>
	</div>
	<?php
    }

    /**
     * Render search checkbox list
     *
     * @param object $row
     */
    function _renderSearchCheckboxList($row) {
	$values = explode("\r\n", $row->values);
	$html = '<tr><td class="title_cell"><strong>' . JText::_($row->title) . '</strong></td><td colspan="5">';
	$html .= '<table cellspacing="3" cellpadding="3" width="100%">';
	for ($i = 0, $n = count($values); $i < $n; $i++) {
	    $value = $values[$i];
	    if ($i % NUMBER_OPTION_PER_LINE == 0) {
		$html .= '<tr>';
	    }

	    $html .= '<td><input class="checkbox" value="' . $value . '" type="checkbox" name="' . $row->name . '[]" ><lable for="' . $row->name . '[]">' . JText::_($value) . '</lable>';
	    $html .= '</td>';
	    if (($i + 1) % NUMBER_OPTION_PER_LINE == 0) {
		$html .= '</tr>';
	    }
	}
	if ($i % NUMBER_OPTION_PER_LINE != 0) {
	    $colspan = NUMBER_OPTION_PER_LINE - $i % NUMBER_OPTION_PER_LINE;
	    $html .= '<td colspan="' . $colspan . '">&nbsp;</td></tr>';
	}

	$html .= '</table></td></tr>';

	return $html;
    }

    /**
     * Render checkboxlist in edit mode
     *
     * @param object $row
     */
    function _renderCheckboxListEdit($row, $savedValues) {
	$values = explode("\r\n", $row->values);
	$defaultValues = explode('|*|', $savedValues);
	?>
	<div class="control-group">
	    <div class="control-label">
		<?php echo JText::_($row->title); ?>
	    </div>
	    <div class="controls">
		<div cellspacing="3" cellpadding="3" width="100%">
		    <?php
		    for ($i = 0, $n = count($values); $i < $n; $i++) {
			$value = $values[$i];
			if ($i % NUMBER_OPTION_PER_LINE == 0) {
			    ?>
			    <div>
				<?php
			    }
			    ?>
	    		<div>
	    		    <input class="inputbox" value="<?php echo $value; ?>" type="checkbox" name="<?php echo $row->name; ?>[]" <?php if (in_array($value, $defaultValues)) echo ' checked="checked" '; ?>><?php echo JText::_($value); ?>
	    		</div>
			    <?php
			    if (($i + 1) % NUMBER_OPTION_PER_LINE == 0) {
				echo '</div>';
			    }
			}
			if ($i % NUMBER_OPTION_PER_LINE != 0) {
			    $colspan = NUMBER_OPTION_PER_LINE - $i % NUMBER_OPTION_PER_LINE;
			    ?>
	    		<div colspan="<?php echo $colspan; ?>">&nbsp;</div>
	    	    </div>
			<?php
		    }
		    ?>
		</div>
	    </div>
	</div>

	<?php
    }

    /**
     * Gender validation for textbox 
     *
     * @param object $row
     */
    function _renderCheckBoxListValidation($row) {
	?>
	var checked = false ;
	if (form["<?php echo $row->name; ?>[]"].length) {
	for (var i=0; i < form["<?php echo $row->name; ?>[]"].length; i++) {
	if (form["<?php echo $row->name; ?>[]"][i].checked == true) {
	checked = true ;
	break ;
	}
	}
	} else {
	if (form["<?php echo $row->name; ?>[]"].checked) {
	checked = true ;
	}
	}
	if (!checked) {
	//alert("<?php echo JText::_($row->title); ?> <?php echo JText::_('COM_JUX_REAL_ESTATE_IS_REQUIRED'); ?>");
	error_msg.removeClass('jux-hide');
	form.<?php echo $row->name; ?>.focus();
	return ;
	}
	<?php
    }

    /**
     * Output values which users selectd in the checkboxlist
     *
     * @param object $row
     */
    function _renderCheckBoxListConfirmation($row) {
	?>
	<tr>
	    <td class="title_cell">
		<?php echo JText::_($row->title); ?>
	    </td>
	    <td>
		<?php
		$name = $row->name;
		$postedValue = JRequest::getVar($name, array(), 'post');
		echo implode('|*|', $postedValue);
		?>
	    </td>
	</tr>
	<?php
    }

    /**
     * Render hidden field for textbox 
     *
     * @param object $row
     */
    function _renderCheckBoxListHidden($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, array(), 'post');
	for ($i = 0, $n = count($postedValue); $i < $n; $i++) {
	    $value = $postedValue[$i];
	    ?>
	    <input type="hidden" name="<?php echo $name; ?>[]" value="<?php echo $value; ?>" />
	    <?php
	}
    }

    /**
     * Reder radio list
     *
     * @param object $row
     */
    function _renderRadioList(&$row) {
	$postedValue = JRequest::getVar($row->name, $row->default_values, 'post');
	$values = explode("\r\n", $row->values);
	$options = array();
	for ($i = 0, $n = count($values); $i < $n; $i++) {
	    $options[] = JHTML::_('select.option', $values[$i], JText::_($values[$i]));
	}
	?>
	<tr>
	    <td class="title_cell">
		<label for="<?php echo $row->name; ?>">
		    <strong><?php echo JText::_($row->title); ?></strong>:
		    <?php
		    if ($row->required)
			echo '<span class="jp_required">*</span>';
		    ?>
		</label>
	    </td>
	    <td>
		<?php
		$class = 'id="' . $row->name . '" class="inputbox ' . $row->css_class . '"';
		echo JHTML::_('select.radiolist', $options, $row->name, $class, 'value', 'text', $postedValue);
		?>
	    </td>
	</tr>
	<?php
    }

    /**
     * Reder search radio list
     *
     * @param object $row
     */
    function _renderSearchRadioList(&$row) {
	$values = explode("\r\n", $row->values);
	$options = array();
	for ($i = 0, $n = count($values); $i < $n; $i++) {
	    $options[] = JHTML::_('select.option', $values[$i], JText::_($values[$i]));
	}
	?>
	<td width="10%">
	    <strong class="title_cell"><?php echo JText::_($row->title); ?></strong>
	</td>
	<td width="20%">
	    <?php echo JHTML::_('select.radiolist', $options, $row->name, '', 'value', 'text'); ?>
	</td>
	<?php
    }

    /**
     * Reder radio list in edit mode
     *
     * @param object $row
     * @param string $value
     */
    function _renderRadioListEdit(&$row, $value) {
	?>
	<tr>
	    <td class="key"><?php echo JText::_($row->title); ?></td>
	    <td>
		<?php
		$values = explode("\r\n", $row->values);
		$options = array();
		for ($i = 0, $n = count($values); $i < $n; $i++) {
		    $options[] = JHTML::_('select.option', $values[$i], JText::_($values[$i]));
		}
		echo JHTML::_('select.radiolist', $options, $row->name, '', 'value', 'text', $value);
		?>
	    </td>
	</tr>
	<?php
    }

    /**
     * Gender validation for RadioList 
     *
     * @param object $row
     */
    function _renderRadioListValidation($row) {
	?>
	var checked = false ;
	if (form.<?php echo $row->name; ?>.length) {
	for (var i=0 ; i < form.<?php echo $row->name; ?>.length ; i++) {
	if (form.<?php echo $row->name; ?>[i].checked == true) {
	checked = true ;
	break ;
	}
	}
	} else {
	if (form.<?php echo $row->name; ?>.checked == true)
	checked = true ;
	}
	if (!checked) {
	//alert("<?php echo JText::_($row->title) . ' ' . JText::_('COM_JUX_REAL_ESTATE_IS_REQUIRED'); ?>");
	error_msg.removeClass('jux-hide');
	return ;
	}
	<?php
    }

    /**
     * Output values which users entered in the textarea field
     *
     * @param object $row
     */
    function _renderRadioListConfirmation($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	?>
	<tr>
	    <td class="title_cell">
		<?php echo JText::_($row->title); ?>
	    </td>
	    <td class="field_cell">
		<?php echo $postedValue; ?>
	    </td>
	</tr>
	<?php
    }

    /**
     * Render hidden tag for radio list
     *
     * @param object $row
     */
    function _renderRadioListHidden($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	?>
	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $postedValue; ?>" />
	<?php
    }

    /**
     * 
     *
     * @param string $row
     */
    function _renderDateTime(&$row) {
	$db = JFactory::getDBO();
	$sql = 'SELECT `value` FROM #__re_configs WHERE `key`="date_format"';
	$db->setQuery($sql);
	$dateFormat = $db->loadResult();
	if (empty($dateFormat))
	    $dateFormat = '%d-%m-%Y';
	?>
	<tr>
	    <td class="title_cell">
		<label for="<?php echo $row->name; ?>">
		    <strong><?php echo JText::_($row->title); ?></strong>:
		    <?php
		    if ($row->required)
			echo '<span class="jp_required">*</span>';
		    ?>
		</label>
	    </td>
	    <td class="field_cell">
		<?php
		$required = ($row->required) ? ' required' : '';
		$class = 'id="' . $row->name . '" class=" ' . $row->css_class . $required . '"';
		echo JHTML::_('calendar', $row->default_values, $row->name, $row->name, $dateFormat, $class);
		?>
	    </td>
	</tr>
	<?php
    }

    /**
     * 
     *
     * @param string $row
     */
    function _renderSearchDateTime(&$row) {
	$db = JFactory::getDBO();
	$sql = 'SELECT `value` FROM #__re_configs WHERE `key`="date_format"';
	$db->setQuery($sql);
	$dateFormat = $db->loadResult();
	if (empty($dateFormat))
	    $dateFormat = '%d-%m-%Y';
	?>
	<td width="10%">
	    <strong class="field_cell"><?php echo JText::_($row->title); ?></strong>
	</td>
	<td width="20%">
	    <?php echo JHTML::_('calendar', '', $row->name, $row->name, $dateFormat); ?>
	</td>
	<?php
    }

    /**
     * Render datetime inputbox in edit mode
     *
     * @param object $row
     * @param string $value
     */
    function _renderDateTimeEdit(&$row, $value) {
	$db = JFactory::getDBO();
	$sql = 'SELECT `value` FROM #__re_configs WHERE `key`="date_format"';
	$db->setQuery($sql);
	$dateFormat = $db->loadResult();
	if (empty($dateFormat))
	    $dateFormat = '%d-%m-%Y';
	?>
	<tr>
	    <td class="key">
		<?php echo JText::_($row->title); ?>
	    </td>
	    <td>
		<?php echo JHTML::_('calendar', $value, $row->name, $row->name, $dateFormat); ?>
	    </td>
	</tr>
	<?php
    }

    /**
     * Gender validation for RadioList 
     *
     * @param object $row
     */
    function _renderDateTimeValidation($row) {
	?>
	if (form.<?php echo $row->name; ?>.value == "") {
	//alert("<?php echo JText::_($row->title) . ' ' . JText::_('COM_JUX_REAL_ESTATE_IS_REQUIRED'); ?>");
	error_msg.removeClass('jux-hide');
	form.<?php echo $row->name; ?>.focus();
	return ;
	}
	<?php
    }

    /**
     * Output values which users entered in the textarea field
     *
     * @param object $row
     */
    function _renderDateTimeConfirmation($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	?>
	<tr>
	    <td class="title_cell">
		<?php echo JText::_($row->title); ?>
	    </td>
	    <td class="field_cell">
		<?php echo $postedValue; ?>
	    </td>
	</tr>
	<?php
    }

    /**
     * Render hidden tag for radio list
     *
     * @param object $row
     */
    function _renderDateTimeHidden($row) {
	$name = $row->name;
	$postedValue = JRequest::getVar($name, '', 'post');
	?>
	<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $postedValue; ?>" />
	<?php
    }

    /**
     * Render output in the confirmation page
     *
     */
    function renderConfirmation() {
	ob_start();
	for ($i = 0, $n = count($this->_fields); $i < $n; $i++) {
	    $row = $this->_fields[$i];
	    switch ($row->field_type) {
		case FIELD_TYPE_HEADING :
		    ?>
		    <?php echo JText::_($row->title); ?>
		    <?php
		    break;
		case FIELD_TYPE_TEXTBOX :
		    $this->_renderTextboxConfirmation($row);
		    break;
		case FIELD_TYPE_TEXTAREA :
		    $this->_renderTextAreaConfirmation($row);
		    break;
		case FIELD_TYPE_DROPDOWN :
		    $this->_renderDropDownConfirmation($row);
		    break;
		case FIELD_TYPE_CHECKBOXLIST :
		    $this->_renderCheckBoxListConfirmation($row);
		    break;
		case FIELD_TYPE_RADIOLIST :
		    $this->_renderRadioListConfirmation($row);
		    break;
		case FIELD_TYPE_DATETIME :
		    $this->_renderDateTimeConfirmation($row);
		    break;
		case FIELD_TYPE_MULTISELECT :
		    $this->_renderMultiSelectConfirmation($row);
		    break;
	    }
	}
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
    }

    /**
     * Render published custom fields
     *
     */
    function renderCustomFields() {
	ob_start();
	for ($i = 0, $n = count($this->_fields); $i < $n; $i++) {
	    $row = $this->_fields[$i];
	    switch ($row->field_type) {
		case FIELD_TYPE_HEADING :
		    ?>
		    <?php echo JText::_($row->title); ?>

		    <?php
		    break;
		case FIELD_TYPE_MESSAGE :
		    ?>	
		    <?php echo $row->description; ?>

		    <?php
		    break;
		case FIELD_TYPE_TEXTBOX :
		    $this->_renderTextBox($row);
		    break;
		case FIELD_TYPE_TEXTAREA :
		    $this->_renderTextarea($row);
		    break;
		case FIELD_TYPE_DROPDOWN :
		    $this->_renderDropdown($row);
		    break;
		case FIELD_TYPE_CHECKBOXLIST :
		    $this->_renderCheckboxList($row);
		    break;
		case FIELD_TYPE_RADIOLIST :
		    $this->_renderRadioList($row);
		    break;
		case FIELD_TYPE_DATETIME :
		    $this->_renderDateTime($row);
		    break;
		case FIELD_TYPE_MULTISELECT :
		    $this->_renderMultiSelect($row);
		    break;
	    }
	    ?>				
	    <?php
	}
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
    }

    /**
     * Render published custom search fields
     *
     */
    function renderCustomSearchFields() {
        die('ds');
	ob_start();
	for ($i = 0, $n = count($this->_searchfields); $i < $n; $i++) {
	    $row = $this->_searchfields[$i];
	    if ($i % NUMBER_OPTION_PER_LINE == 0) {
		echo '<tr>';
	    }
	    switch ($row->field_type) {
		case FIELD_TYPE_HEADING :
		    ?>
		    <?php echo JText::_($row->title); ?>
		    <?php
		    break;
		case FIELD_TYPE_MESSAGE :
		    ?>	
		    <?php echo JText::_($row->description); ?>
		    <?php
		    break;
		case FIELD_TYPE_TEXTBOX :
		    $this->_renderSearchTextBox($row);
		    break;
		case FIELD_TYPE_TEXTAREA :
		    $this->_renderSearchTextarea($row);
		    break;
		case FIELD_TYPE_DROPDOWN :
		    $this->_renderSearchDropdown($row);
		    break;
		case FIELD_TYPE_CHECKBOXLIST :
		    $extraHtml = $this->_renderSearchCheckboxList($row);
		    break;
		case FIELD_TYPE_RADIOLIST :
		    $this->_renderSearchRadioList($row);
		    break;
		case FIELD_TYPE_DATETIME :
		    $this->_renderSearchDateTime($row);
		    break;
		case FIELD_TYPE_MULTISELECT :
		    $this->_renderSearchMultiSelect($row);
		    break;
	    }
	    if (($i + 1) % NUMBER_OPTION_PER_LINE == 0) {
		echo '</tr>';
	    }
	}
	if ($i % NUMBER_OPTION_PER_LINE != 0) {
	    $colspan = NUMBER_OPTION_PER_LINE - $i % NUMBER_OPTION_PER_LINE;
	    ?>
	    <td colspan="<?php echo $colspan; ?>">&nbsp;</td>

	    <?php
	    echo '</tr>';
	}

	$output = ob_get_contents() . $extraHtml;
	ob_end_clean();
	return $output;
    }

    /**
     * Render js validation code
     *
     */
    function renderJSValidation() {
	ob_start();
	for ($i = 0, $n = count($this->_fields); $i < $n; $i++) {
	    $row = $this->_fields[$i];
	    if ($row->required) {
		switch ($row->field_type) {
		    case FIELD_TYPE_TEXTBOX :
			$this->_renderTextBoxValidation($row);
			break;
		    case FIELD_TYPE_TEXTAREA :
			$this->_renderTextAreaValidation($row);
			break;
		    case FIELD_TYPE_DROPDOWN :
			$this->_renderDropdownValidation($row);
			break;
		    case FIELD_TYPE_CHECKBOXLIST :
			$this->_renderCheckBoxListValidation($row);
			break;
		    case FIELD_TYPE_RADIOLIST :
			$this->_renderRadioListValidation($row);
			break;
		    case FIELD_TYPE_DATETIME :
			$this->_renderDateTimeValidation($row);
			break;
		    case FIELD_TYPE_MULTISELECT :
			$this->_renderMultiSelect($row);
			break;
		}
	    }
	}
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
    }

    /**
     * Render hidden fields to pass to the next form
     *
     */
    function renderHiddenFields() {
	ob_start();
	for ($i = 0, $n = count($this->_fields); $i < $n; $i++) {
	    $row = $this->_fields[$i];
	    switch ($row->field_type) {
		case FIELD_TYPE_TEXTBOX :
		    $this->_renderTextboxHidden($row);
		    break;
		case FIELD_TYPE_TEXTAREA :
		    $this->_renderTextAreaHidden($row);
		    break;
		case FIELD_TYPE_DROPDOWN :
		    $this->_renderDropdownHidden($row);
		    break;
		case FIELD_TYPE_CHECKBOXLIST :
		    $this->_renderCheckBoxListHidden($row);
		    break;
		case FIELD_TYPE_RADIOLIST :
		    $this->_renderRadioListHidden($row);
		    break;
		case FIELD_TYPE_DATETIME :
		    $this->_renderDateTimeHidden($row);
		    break;
		case FIELD_TYPE_MULTISELECT :
		    $this->_renderMultiSelectHidden($row);
		    break;
	    }
	}
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
    }

    /**
     * Save Field Value 
     *
     * @param int $id
     * @return boolean
     */
    function saveFieldValues($realtyId) {
	$db = JFactory::getDBO();

	//Delete all old database
	$sql = "DELETE FROM #__re_field_value WHERE realty_id = $realtyId";
	$db->setQuery($sql);
	$db->query();

	for ($i = 0, $n = count($this->_fields); $i < $n; $i++) {
	    $row = $this->_fields[$i];
	    if ($row->field_type == FIELD_TYPE_HEADING || $row->field_type == FIELD_TYPE_MESSAGE)
		continue;
	    $name = $row->name;
	    $postedValue = JRequest::getVar($name, '', 'post');
	    if (is_array($postedValue))
		$postedValue = implode('|*|', $postedValue);
	    $postedValue = $db->Quote($postedValue);

	    //Store new fields data
	    $sql = 'INSERT INTO #__re_field_value(field_id, realty_id, field_value)'
		    . " VALUES($row->id, $realtyId, $postedValue) "
	    ;
	    $db->setQuery($sql);
	    $db->query();
	}
	return true;
    }

    /**
     * Render custom fields in edit mode
     *
     * @param object $donorId
     */
    function renderCustomFieldsEdit($realtyId) {
	$db = JFactory::getDBO();
	$sql = 'SELECT field_id, field_value FROM #__re_field_value WHERE realty_id=' . $realtyId;
	$db->setQuery($sql);
	$rowFields = $db->loadObjectList();
	$values = array();
	for ($i = 0, $n = count($rowFields); $i < $n; $i++) {
	    $rowField = $rowFields[$i];
	    $values[$rowField->field_id] = $rowField->field_value;
	}
	ob_start();
	for ($i = 0, $n = count($this->_fields); $i < $n; $i++) {
	    $row = $this->_fields[$i];
	    if (isset($values[$row->id]))
		$value = $values[$row->id];
	    else
		$value = '';
	    switch ($row->field_type) {
		case FIELD_TYPE_HEADING :
		    ?>
		    <?php echo JText::_($row->title); ?>
		    <?php
		    break;
		case FIELD_TYPE_TEXTBOX :
		    $this->_renderTextBoxEdit($row, $value);
		    break;
		case FIELD_TYPE_TEXTAREA :
		    $this->_renderTextareaEdit($row, $value);
		    break;
		case FIELD_TYPE_DROPDOWN :
		    $this->_renderDropdownEdit($row, $value);
		    break;
		case FIELD_TYPE_CHECKBOXLIST :
		    $this->_renderCheckboxListEdit($row, $value);
		    break;
		case FIELD_TYPE_RADIOLIST :
		    $this->_renderRadioListEdit($row, $value);
		    break;
		case FIELD_TYPE_DATETIME :
		    $this->_renderDateTimeEdit($row, $value);
		    break;
		case FIELD_TYPE_MULTISELECT :
		    $this->_renderMultiSelectEdit($row, $value);
		    break;
	    }
	}
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
    }

    /**
     * get custom fields in show realty
     *
     * @param object $donorId
     */
    function getCustomFields($realtyId, $isOwner) {
	$db = JFactory::getDBO();
	$leftJoin = ' LEFT JOIN #__re_fields AS f ON f.id = m.field_id';
	if (!$isOwner) {
	    $where = '	WHERE f.access = 1 AND f.published = 1';
	} else {
	    $where = ' WHERE 1';
	}
	$where .= ' AND m.realty_id=' . $realtyId;
	$orderby = ' ORDER BY f.ordering';

	$sql = 'SELECT m.field_id, m.field_value, f.title FROM #__re_field_value AS m'
		. $leftJoin
		. $where
		. $orderby
	; // WHERE realty_id='.$realtyId;
	$db->setQuery($sql);
	$rowFields = $db->loadObjectList();

	$n = count($rowFields);
	for ($i = 0; $i < $n; $i++) {
	    $values = explode('|*|', $rowFields[$i]->field_value);
	    $rowFields[$i]->field_value = $values;
	}
	return $rowFields;
    }

}
?>