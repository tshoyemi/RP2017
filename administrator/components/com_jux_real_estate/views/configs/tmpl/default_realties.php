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
    <div id="realties">
        <fieldset>
            <div class="span12">
                <div class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTIES_CONFIGURATION'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('show_social_share'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('show_social_share'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('show_agent'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('show_agent'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('num_intro_realties'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('num_intro_realties'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('max_desc'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('max_desc'); ?></div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
