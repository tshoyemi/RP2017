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
    <div id="agents">
        <fieldset>
            <div class="span6">
                <div class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENTS_CONFIGURATION'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agents_layout'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agents_layout'); ?></div>
                    </div> 
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('max_desc_agents'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('max_desc_agents'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agents_show_email'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agents_show_email'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agents_show_phone'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agents_show_phone'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agents_show_fax'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agents_show_fax'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agents_show_skype'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agents_show_skype'); ?></div>
                    </div> 
		    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agents_show_social'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agents_show_social'); ?></div>
                    </div>                                    

                </div>
            </div>
            <div class="span6">
                <div class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_CONFIGURATION'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agentdetail_realties_layout'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agentdetail_realties_layout'); ?></div>
                    </div> 
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agentdetail_contact_form'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agentdetail_contact_form'); ?></div>
                    </div>
		    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agentdetail_show_address'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agentdetail_show_address'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agentdetail_show_email'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agentdetail_show_email'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agentdetail_show_phone'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agentdetail_show_phone'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agentdetail_show_fax'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agentdetail_show_fax'); ?></div>
                    </div>

                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agentdetail_show_website'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agentdetail_show_website'); ?></div>
                    </div>  
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agentdetail_show_skype'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agentdetail_show_skype'); ?></div>
                    </div> 
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('agentdetail_show_organization'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agentdetail_show_organization'); ?></div>
                    </div>                                    
                </div>
            </div>
        </fieldset>
    </div>
</div>
