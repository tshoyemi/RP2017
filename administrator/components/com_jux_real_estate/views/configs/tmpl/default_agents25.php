<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div id="page-agents">
    <table class="noshow">
        <tr>
            <td width="100%" align="left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENTS_CONFIGURATION'); ?></legend>
                    <div class="fltlft">
                        <ul class="adminformlist">

                            <li><?php echo $this->form->getLabel('max_desc_agents'); ?>
                                <?php echo $this->form->getInput('max_desc_agents'); ?></li>
                            <li><?php echo $this->form->getLabel('agent_show_image'); ?>
                                <?php echo $this->form->getInput('agent_show_image'); ?></li>
                            <li><?php echo $this->form->getLabel('agent_show_address'); ?>
                                <?php echo $this->form->getInput('agent_show_address'); ?></li>
                            <li><?php echo $this->form->getLabel('agent_show_address'); ?>
                                <?php echo $this->form->getInput('agent_show_address'); ?></li>
                            <li><?php echo $this->form->getLabel('agent_show_email'); ?>
                                <?php echo $this->form->getInput('agent_show_email'); ?></li>
                            <li><?php echo $this->form->getLabel('agent_show_phone'); ?>
                                <?php echo $this->form->getInput('agent_show_phone'); ?></li>
                            <li><?php echo $this->form->getLabel('agent_show_fax'); ?>
                                <?php echo $this->form->getInput('agent_show_fax'); ?></li>
                            <li><?php echo $this->form->getLabel('agent_show_website'); ?>
                                <?php echo $this->form->getInput('agent_show_website'); ?></li>
                            <li><?php echo $this->form->getLabel('num_intro_agents'); ?>
                                <?php echo $this->form->getInput('num_intro_agents'); ?></li>
                            <li><?php echo $this->form->getLabel('agent_show_featured'); ?>
                                <?php echo $this->form->getInput('agent_show_featured'); ?></li>
                            <li><?php echo $this->form->getLabel('agent_feat_num'); ?>
                                <?php echo $this->form->getInput('agent_feat_num'); ?></li>
                            <li><?php echo $this->form->getLabel('agent_feat_position'); ?>
                                <?php echo $this->form->getInput('agent_feat_position'); ?></li>
                        </ul>
                    </div>
                </fieldset>
            </td>
        </tr>
    </table>
</div>