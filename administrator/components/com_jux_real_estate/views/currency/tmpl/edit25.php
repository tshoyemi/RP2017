<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$db = JFactory::getDBO();
$nullDate = $db->getNullDate();
// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>

<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        var form = document.adminForm;
        if (task == 'currency.cancel' || document.formvalidator.isValid(document.id('currency-form'))) {
            Joomla.submitform(task, document.getElementById('currency-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="currency-form" class="form-validate">
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td valign="top">
                <table class="admintable">
                    <tr>

                        <td nowrap="nowrap" class="key" width="15%">
                            <?php echo $this->form->getLabel('title'); ?>
                        </td>
                        <td>
                            <?php echo $this->form->getInput('title'); ?>
                        </td>

                        <td nowrap="nowrap" class="key">
                            <?php echo $this->form->getLabel('published'); ?>
                        </td>
                        <td>
                            <?php echo $this->form->getInput('published'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" class="key">
                            <?php echo $this->form->getLabel('code'); ?>
                        </td>
                        <td>
                            <?php echo $this->form->getInput('code'); ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php echo $this->form->getLabel('sign'); ?>
                            &nbsp;&nbsp;
                            <?php echo $this->form->getInput('sign'); ?>
                        </td>
                        <td nowrap="nowrap" class="key">
                            <?php echo $this->form->getLabel('position'); ?>
                        </td>
                        <td>
                            <?php echo $this->form->getInput('position'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" class="key">
                            <?php echo $this->form->getLabel('ordering'); ?>
                        </td>
                        <td colspan="3">
                            <?php echo $this->form->getInput('ordering'); ?>
                        </td>
                    </tr>
                </table>

                <table class="adminform">
                    <tr>
                        <td>
                            <?php if ($this->configs->get('use_editor')) {
                            echo $this->form->getInput('description');
                        } else {
                            echo '<textarea class="inputbox" name="jform[description]" id="jform_description" items="10" cols="60">' . $this->item->description . '</textarea>';
                        }
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td valign="top" width="320px" style="padding: 7px 0 0 5px">
                <table width="100%" style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px;">
                    <?php if ($this->item->id) { ?>
                    <tr>
                        <td>
                            <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_CURRENCY_ID'); ?>:</strong>
                        </td>
                        <td>
                            <?php echo $this->item->id; ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_STATE'); ?>:</strong>
                        </td>
                        <td>
                            <?php echo $this->item->published > 0 ? JText::_('COM_JUX_REAL_ESTATE_PUBLISHED') : JText::_('COM_JUX_REAL_ESTATE_UNPUBLISHED'); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <input type="hidden" name="controller" value="currency"/>
    <input type="hidden" name="task" value=""/>
    <?php echo JHTML::_('form.token'); ?>
</form>

<?php
echo JUX_Real_EstateFactory::getFooter();