<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function (task) {
        var form = document.adminForm;
        if (task == 'field.cancel' || document.formvalidator.isValid(document.id('field-form'))) {
            Joomla.submitform(task, document.getElementById('field-form'));
        }
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="field-form">
    <div class="col width-55" style="float:left">
        <table class="admintable">
            <tr>
                <td nowrap="nowrap" class="key" width="15%">
                    <?php echo $this->form->getLabel('name'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('name'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('title'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('title'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('ordering'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('ordering'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('field_type'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('field_type'); ?>
                </td>

            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('description'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('description'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('required'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('required'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('language'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('language'); ?>
                </td>
            </tr>

            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('values'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('values'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('default_values'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('default_values'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('rows'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('rows'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('cols'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('cols'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('size'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('size'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('css_class'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('css_class'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('published'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('published'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('access'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('access'); ?>
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap" class="key">
                    <?php echo $this->form->getLabel('search_field'); ?>
                </td>
                <td>
                    <?php echo $this->form->getInput('search_field'); ?>
                </td>
            </tr>

        </table>
    </div>
    <div class="clr"></div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="controller" value="field"/>
    <?php echo JHTML::_('form.token'); ?>
    <script type="text/javascript" language="javascript">
        function checkFieldName() {
            var form = document.adminForm;
            var name = form.jform_name.value;
            var oldValue = name;
            name = name.replace('jp_', '');
            name.replace(/[^a-zA-Z0-9]+/g, '');
            form.jform_name.value = 'jp_' + name;
            if (oldValue != form.jform_name.value) {
                alert('<?php echo JText::_('COM_JUX_REAL_ESTATE_FIELD_NAME_AUTOMATICALLY_CHANGED_TO_FOLLOW_NAMING_STANDARD'); ?>');
            }
        }
    </script>

</form>