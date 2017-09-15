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

<div id="page-recaptcha">
    <table class="noshow">
        <tr>
            <td width="100%" align="left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_RECAPTCHA'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('enable_recaptcha'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('enable_recaptcha'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('public_key'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('public_key'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('private_key'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('private_key'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('theme_recaptcha'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('theme_recaptcha'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php
                                $link = '<a target="_blank" href="https://www.google.com/recaptcha/admin/create">' . JText::_('COM_JUX_REAL_ESTATE_RECAPTCHA_HERE') . '</a>';
                                echo JText::sprintf('COM_JUX_REAL_ESTATE_RECAPTCHA_LINK', $link);
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
</div>