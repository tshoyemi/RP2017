<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'state.cancel' || document.formvalidator.isValid(document.id('state-form'))) {
            Joomla.submitform(task, document.getElementById('state-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="state-form" class="form-validate">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILS'); ?></legend>
            <div class="fltlft">
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('state_name'); ?>
                        <?php echo $this->form->getInput('state_name'); ?></li>
                    <li><?php echo $this->form->getLabel('state_code'); ?>
                        <?php echo $this->form->getInput('state_code'); ?></li>
                    <li><?php echo $this->form->getLabel('country_id'); ?>
                        <?php echo $this->form->getInput('country_id'); ?></li>
                </ul>
            </div>
        </fieldset>
    </div>

    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>
<p class="copyright"><?php echo JUX_Real_EstateFactory::getFooter();?></p>