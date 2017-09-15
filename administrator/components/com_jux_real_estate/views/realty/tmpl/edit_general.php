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
?>
<fieldset>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
    </div>
    <div class="control-group hidden">
        <div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('type_id'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('type_id'); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('cat_id'); ?> </div>
        <div class="controls">
	    <?php echo $this->form->getInput('cat_id'); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('sale'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('sale'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('preview_image'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('preview_image'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('call_for_price'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('call_for_price'); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('agent_id'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('agent_id'); ?></div>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('price2'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('price2'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('price'); ?></div>
        <div class="controls">
	    <?php echo $this->form->getInput('price'); ?>  <?php echo $this->form->getInput('currency_id'); ?>
	    <?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_PER'); ?>
	    <?php echo $this->form->getInput('price_freq'); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('sub_desc'); ?> </div>
        <div class="controls">
	    <?php echo $this->form->getInput('sub_desc'); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('description'); ?></div>
    </div>

</fieldset>