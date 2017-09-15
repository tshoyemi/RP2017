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
<fieldset class="adminform">
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('count'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('count'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('user_id'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('user_id'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('date_created'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('date_created'); ?></div>
    </div>
    <?php if ($this->item->modified && $this->item->modified != '0000-00-00 00:00:00'): ?>
        <div class="control-group">
    	<div class="control-label"><?php echo $this->form->getLabel('modified'); ?></div>
    	<div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
        </div>
        <div class="control-group">
    	<div class="control-label"><?php echo $this->form->getLabel('modified_by'); ?></div>
    	<div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
        </div>
    <?php endif; ?>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('publish_up'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('publish_up'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('publish_down'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('publish_down'); ?></div>
    </div> 
</fieldset>