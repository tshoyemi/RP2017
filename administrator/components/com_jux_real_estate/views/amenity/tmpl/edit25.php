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
?>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="amenity-form" class="form-validate">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILS'); ?></legend>
            <div class="fltlft">
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('id'); ?>
                        <?php echo $this->form->getInput('id'); ?></li>
                    <li><?php echo $this->form->getLabel('title'); ?>
                        <?php echo $this->form->getInput('title'); ?></li>
                    <li><?php echo $this->form->getLabel('cat'); ?>
                        <?php echo $this->form->getInput('cat'); ?></li>
                </ul>
            </div>
        </fieldset>
    </div>
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>
<p class="copyright"><?php echo JUX_Real_EstateFactory::getFooter();?></p>