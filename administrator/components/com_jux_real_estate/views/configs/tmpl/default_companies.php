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
    <div id="companies">
        <fieldset>
            <div class="span12">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_COMPANIES_CONFIGURATION'); ?></legend>
                <div class="adminform">
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('com_show_image'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('com_show_image'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('com_show_address'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('com_show_address'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('com_show_email'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('com_show_email'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('com_show_phone'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('com_show_phone'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('com_show_fax'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('com_show_fax'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('com_show_website'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('com_show_website'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('num_intro_companies'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('num_intro_companies'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('com_show_featured'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('com_show_featured'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('com_feat_position'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('com_feat_position'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('com_feat_num'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('com_feat_num'); ?></div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>