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

<div id="page-general">
    <table class="noshow">
        <tr>
            <td width="45%" align="left" valign="top">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_GENERAL'); ?></legend>
                    <div class="fltlft">
                        <ul class="adminformlist">
                            <li><?php echo $this->form->getLabel('background_color'); ?>
                                <?php echo $this->form->getInput('background_color'); ?></li>
                            <li><?php echo $this->form->getLabel('template'); ?>
                                <?php echo $this->form->getInput('template'); ?></li>
                            <li><?php echo $this->form->getLabel('auto_approve'); ?>
                                <?php echo $this->form->getInput('auto_approve'); ?></li>
                            <li><?php echo $this->form->getLabel('social_share'); ?>
                                <?php echo $this->form->getInput('social_share'); ?></li>
                            <li><?php echo $this->form->getLabel('accept_term'); ?>
                                <?php echo $this->form->getInput('accept_term'); ?></li>
                            <li><?php echo $this->form->getLabel('article_id'); ?>
                                <?php echo $this->form->getInput('article_id'); ?></li>
                            <li><?php echo $this->form->getLabel('date_format'); ?>
                                <?php echo $this->form->getInput('date_format'); ?></li>
                            <li><?php echo $this->form->getLabel('item_per_row'); ?>
                                <?php echo $this->form->getInput('item_per_row'); ?></li>
                        </ul>
                    </div>
                </fieldset>
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_GLOBAL_REALTY_CONFIGURATION'); ?></legend>
                    <div class="fltlft">
                        <ul class="adminformlist">
                            <li><?php echo $this->form->getLabel('showtitle'); ?>
                                <?php echo $this->form->getInput('showtitle'); ?></li>
                            <li><?php echo $this->form->getLabel('street_num_pos'); ?>
                                <?php echo $this->form->getInput('street_num_pos'); ?></li>
                            <li><?php echo $this->form->getLabel('measurement_units'); ?>
                                <?php echo $this->form->getInput('measurement_units'); ?></li>
                        </ul>
                    </div>
                </fieldset>

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

            <td width="45%" valign="top">

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_CURRENCY_CONFIGURATION'); ?></legend>
                    <table class="admintable" cellspacing="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('thousand_separator'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('thousand_separator'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('currency_digits'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('currency_digits'); ?>

                            </td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_IMAGES'); ?></legend>
                    <table class="admintable" cellpadding="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('upload_images'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('upload_images'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('no_images'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('no_images'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('imgs_per_row'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('imgs_per_row'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('images_zise'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('images_zise'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('image_exts'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('image_exts'); ?>
                            </td>
                        </tr>

                    </table>
                </fieldset>

                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_GOOGLE_MAP_CONFIGURATION'); ?></legend>
                    <table class="admintable" cellspacing="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('enable_map'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('enable_map'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('gmapapikey'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('gmapapikey'); ?>

                            </td>
                        </tr>
                        <td colspan="2">
                            <?php
                            $link = '<a target="_blank" href="https://code.google.com/apis/console">' . JText::_('COM_JUX_REAL_ESTATE_GOOGLE_MAP_HERE') . '</a>';
                            echo JText::sprintf('COM_JUX_REAL_ESTATE_GOOGLE_MAP_LINK', $link);
                            ?>
                        </td>
                    </table>
                </fieldset>
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_FACEBOOK_CONFIGURATION'); ?></legend>
                    <table class="admintable" cellspacing="1">
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('enable_facebookCMT'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('enable_facebookCMT'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('enable_facebookCMT_realty'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('enable_facebookCMT_realty'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('enable_facebookCMT_agent'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('enable_facebookCMT_agent'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('enable_facebookCMT_company'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('enable_facebookCMT_company'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                <?php echo $this->form->getLabel('facebookAPI'); ?>
                            </td>
                            <td>
                                <?php echo $this->form->getInput('facebookAPI'); ?>
                            </td>
                        </tr>
                        <td colspan="2">
                            <?php
                            $link = '<a target="_blank" href="https://developers.facebook.com/apps">' . JText::_('COM_JUX_REAL_ESTATE_FACEBOOK_CMT_HERE') . '</a>';
                            echo JText::sprintf('COM_JUX_REAL_ESTATE_FACEBOOK_CMT_LINK', $link);
                            ?>
                        </td>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
</div>