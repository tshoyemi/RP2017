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
<div class="tab-content">
    <div id="message">
	<fieldset>
	    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_THANK_YOU_MESSAGE_PAGE'); ?></legend>
	    <div class="span12">
		<div class="adminform">
		    <div class="control-group">
			<div class="control-label">
			    <?php echo $this->form->getLabel('page_thanks_type'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('page_thanks_type'); ?></div>
		    </div>
		    <div class="control-group">
			<div class="control-label">
			    <?php echo $this->form->getLabel('page_thanks_url'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('page_thanks_url'); ?></div>
		    </div>
		    <div class="control-group">
			<div class="control-label">
			    <?php echo $this->form->getLabel('page_thanks_title'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('page_thanks_title'); ?></div>
		    </div>
		    <div class="control-group">
			<div class="control-label">
			    <?php echo $this->form->getLabel('page_thanks_msg'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('page_thanks_msg'); ?></div>
		    </div>
		</div>
	    </div>
	</fieldset>

	<fieldset>
	    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_CANCEL_MESSAGE_PAGE'); ?></legend>
	    <div class="span12">
		<div class="adminform">
		    <div class="control-group">
			<div class="control-label">
			    <?php echo $this->form->getLabel('page_cancel_type'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('page_cancel_type'); ?></div>
		    </div>
		    <div class="control-group">
			<div class="control-label">
			    <?php echo $this->form->getLabel('page_cancel_url'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('page_cancel_url'); ?></div>
		    </div>
		    <div class="control-group">
			<div class="control-label">
			    <?php echo $this->form->getLabel('page_cancel_title'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('page_cancel_title'); ?></div>
		    </div>
		    <div class="control-group">
			<div class="control-label">
			    <?php echo $this->form->getLabel('page_cancel_msg'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('page_cancel_msg'); ?></div>
		    </div>
		</div>
	    </div>
	</fieldset
    </div>
</div>

