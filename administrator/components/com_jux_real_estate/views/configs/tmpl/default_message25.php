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

<div id="page-message">
    <table class="noshow">
        <tr>
            <td width="100%">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_THANK_YOU_MESSAGE_PAGE'); ?></legend>
                    <table class="admintable" cellspacing="1" width="100%">
                        <tr>
                            <td class="key" valign="top">
                                <?php echo $this->form->getLabel('page_thanks_type'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('page_thanks_type'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key" valign="top">
                                <?php echo $this->form->getLabel('page_thanks_url'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('page_thanks_url'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key" valign="top">
                                <?php echo $this->form->getLabel('page_thanks_title'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('page_thanks_title'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key" valign="top">
                                <?php echo $this->form->getLabel('page_thanks_msg'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('page_thanks_msg'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_CANCEL_MESSAGE_PAGE'); ?></legend>
                    <table class="admintable" cellspacing="1" width="100%">
                        <tr>
                            <td class="key" valign="top">
                                <?php echo $this->form->getLabel('page_cancel_type'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('page_cancel_type'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key" valign="top">
                                <?php echo $this->form->getLabel('page_cancel_url'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('page_cancel_url'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key" valign="top">
                                <?php echo $this->form->getLabel('page_cancel_title'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('page_cancel_title'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key" valign="top">
                                <?php echo $this->form->getLabel('page_cancel_msg'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('page_cancel_msg'); ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
</div>