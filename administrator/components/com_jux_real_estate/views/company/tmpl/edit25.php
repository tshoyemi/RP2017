<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$user = JFactory::getUser();
?>

<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'company.cancel' || document.formvalidator.isValid(document.id('company-form'))) {
            Joomla.submitform(task, document.getElementById('company-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="company-form" class="form-validate">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILS'); ?></legend>
            <div class="width-50 fltlft">
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('name'); ?>
                        <?php echo $this->form->getInput('name'); ?></li>
                    <li><?php echo $this->form->getLabel('alias'); ?>
                        <?php echo $this->form->getInput('alias'); ?></li>
                    <li><?php echo $this->form->getLabel('email'); ?>
                        <?php echo $this->form->getInput('email'); ?></li>
                    <li><?php echo $this->form->getLabel('phone'); ?>
                        <?php echo $this->form->getInput('phone'); ?></li>
                    <li><?php echo $this->form->getLabel('fax'); ?>
                        <?php echo $this->form->getInput('fax'); ?></li>
                    <li><?php echo $this->form->getLabel('website'); ?>
                        <?php echo $this->form->getInput('website'); ?></li>
                </ul>
            </div>
            <div class="width-50 fltrt">
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('street'); ?>
                        <?php echo $this->form->getInput('street'); ?></li>
                    <li><?php echo $this->form->getLabel('city'); ?>
                        <?php echo $this->form->getInput('city'); ?></li>
                    <li><?php echo $this->form->getLabel('country_id'); ?>
                        <?php echo $this->form->getInput('country_id'); ?></li>

                    <li><?php echo $this->form->getLabel('locstate'); ?>
                        <?php echo $this->form->getInput('locstate'); ?></li>
                    <li><?php echo $this->form->getLabel('province'); ?>
                        <?php echo $this->form->getInput('province'); ?></li>
                    <li><?php echo $this->form->getLabel('postcode'); ?>
                        <?php echo $this->form->getInput('postcode'); ?></li>

                </ul>
            </div>
             <li><?php echo $this->form->getLabel('sub_desc'); ?>
                        <?php echo $this->form->getInput('sub_desc'); ?></li>
              <li><?php echo $this->form->getLabel('description'); ?>
                        <?php echo $this->form->getInput('description'); ?></li>
        </fieldset>
    </div>
    <div class="width-40 fltrt">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_IMAGE'); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getInput('image'); ?></li>
            </ul>
        </fieldset>
        <?php if ($user->authorise('core.admin')): ?>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_PUBLISHING'); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('state'); ?>
                    <?php echo $this->form->getInput('state'); ?></li>
                <li><?php echo $this->form->getLabel('featured'); ?>
                    <?php echo $this->form->getInput('featured'); ?></li>
                <li><?php echo $this->form->getLabel('access'); ?>
                    <?php echo $this->form->getInput('access'); ?></li>
            </ul>
        </fieldset>

        <?php endif; ?>
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>
<p class="copyright"><?php echo JUX_Real_EstateFactory::getFooter(); ?></p>