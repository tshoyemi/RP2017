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
?>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=add&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="width-60 fltlft">
	<fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AMENITIES'); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getInput('amenities'); ?></li>
            </ul>
        </fieldset>
    </div>
    <div class="clr"></div>
    <input type="hidden" name="controller" value="type"/>
    <input type="hidden" name="task" value=""/>
    <?php echo JHTML::_('form.token'); ?>
</form>
<div class="clr"></div>
<p class="copyright"><?php echo JUX_Real_EstateFactory::getFooter(); ?></p>