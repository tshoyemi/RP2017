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
    <div id="email">
        <fieldset>
            <div class="span12">
                <div class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_EMAIL'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('store_email'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('store_email'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('store_email_name'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('store_email_name'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('notify_email'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('notify_email'); ?></div>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="span6">
                <div class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_EMAIL_PROPERTY_CONFIGURATION'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('new_property_inform'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('new_property_inform'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('new_property_confirmation'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('new_property_confirmation'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('agent_approved'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agent_approved'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('agent_rejected'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agent_rejected'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('agent_send'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agent_send'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('user_send'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('user_send'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_NEW_PROPERTY_INFORM'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_new_property_inform_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_new_property_inform_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_new_property_inform_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_new_property_inform_body'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_NEW_PROPERTY_CONFIRMATION'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_new_property_confirmation_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_new_property_confirmation_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_new_property_confirmation_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_new_property_confirmation_body'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_APPROVED'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_approved_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_approved_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_approved_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_approved_body'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_REJECTED'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_rejected_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_rejected_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_rejected_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_rejected_body'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_SEND_MAIL'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_agent_to_user_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_agent_to_user_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_agent_to_user_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_agent_to_user_body'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_USER_SEND_MAIL'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_user_to_agent_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_user_to_agent_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_user_to_agent_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_user_to_agent_body'); ?></div>
                    </div>
                </div>
            </div>

            <div class="span6">
                <div class="adminform">

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_EMAIL_AGENT_CONFIGURATION'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('notify_new_agent'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('notify_new_agent'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('notify_agent_actived'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('notify_agent_actived'); ?></div>
                    </div>
             
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_THANK_YOU_EMAIL_MESSAGE'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_thanks_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_thanks_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_thanks_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_thanks_body'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_REQUEST_ACTIVE_AGENT_EMAIL_MESSAGE'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_request_active_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_request_active_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_request_active_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_request_active_body'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_PAY_LATER_THANK_YOU_EMAIL_MESSAGE'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_paylater_thanks_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_paylater_thanks_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_paylater_thanks_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_paylater_thanks_body'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_ACTIVED_NOTIFY_EMAIL_MESSAGE'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_agent_actived_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_agent_actived_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_agent_actived_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_agent_actived_body'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_NEW_AGENT_PLACED_EMAIL_MESSAGE'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_admin_agent_created_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_admin_agent_created_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_admin_agent_created_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_admin_agent_created_body'); ?></div>
                    </div>

                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_ACTIVED_EMAIL_MESSAGE'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_admin_agent_actived_subject'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_admin_agent_actived_subject'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('email_admin_agent_actived_body'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('email_admin_agent_actived_body'); ?></div>
                    </div>
                </div>
            </div>

        </fieldset>
    </div>
</div>

