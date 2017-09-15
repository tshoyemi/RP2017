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

<div id="page-email">
    <table class="noshow">
        <tr>
            <td align="left" colspan="2">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_EMAIL'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('store_email'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('store_email'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('store_email_name'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('store_email_name'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('notify_email'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('notify_email'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td width="55%" align="left" valign="top">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_EMAIL_PROPERTY_CONFIGURATION'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('new_property_inform'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('new_property_inform'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('new_property_confirmation'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('new_property_confirmation'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('agent_approved'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('agent_approved'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('agent_rejected'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('agent_rejected'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('agent_send'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('agent_send'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('user_send'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('user_send'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_NEW_PROPERTY_INFORM'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_new_property_inform_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_new_property_inform_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_new_property_inform_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_new_property_inform_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_NEW_PROPERTY_CONFIRMATION'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_new_property_confirmation_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_new_property_confirmation_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_new_property_confirmation_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_new_property_confirmation_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_APPROVED'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_approved_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_approved_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_approved_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_approved_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_REJECTED'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_rejected_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_rejected_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_rejected_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_rejected_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_SEND_MAIL'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_agent_to_user_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_agent_to_user_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_agent_to_user_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_agent_to_user_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_USER_SEND_MAIL'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_user_to_agent_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_user_to_agent_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_user_to_agent_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_user_to_agent_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
            <td valign="top">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_EMAIL_AGENT_CONFIGURATION'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('notify_new_agent'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('notify_new_agent'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                    <?php echo $this->form->getLabel('notify_agent_actived'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('notify_agent_actived'); ?>
                            </td>
                        </tr>

                    </table>
                </fieldset>
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_THANK_YOU_EMAIL_MESSAGE'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_thanks_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_thanks_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_thanks_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_thanks_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_REQUEST_ACTIVE_AGENT_EMAIL_MESSAGE'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_request_active_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_request_active_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_request_active_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_request_active_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_PAY_LATER_THANK_YOU_EMAIL_MESSAGE'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_paylater_thanks_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_paylater_thanks_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_paylater_thanks_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_paylater_thanks_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_ACTIVED_NOTIFY_EMAIL_MESSAGE'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_agent_actived_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_agent_actived_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_agent_actived_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_agent_actived_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_NEW_AGENT_PLACED_EMAIL_MESSAGE'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_admin_agent_created_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_admin_agent_created_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_admin_agent_created_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_admin_agent_created_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_ACTIVED_EMAIL_MESSAGE'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_admin_agent_actived_subject'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_admin_agent_actived_subject'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('email_admin_agent_actived_body'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('email_admin_agent_actived_body'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
</div>