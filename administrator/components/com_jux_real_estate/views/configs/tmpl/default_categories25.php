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

<div id="page-categories">
    <table class="noshow">
        <tr>
            <td width="100%" align="left">
                <fieldset class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_CATEGORIES_CONFIGURATION'); ?></legend>
                    <div class="fltlft">
                        <ul class="adminformlist">
                            <li><?php echo $this->form->getLabel('cat_image_width'); ?>
                                <?php echo $this->form->getInput('cat_image_width'); ?></li>
                            <li><?php echo $this->form->getLabel('show_scats'); ?>
                                <?php echo $this->form->getInput('show_scats'); ?></li>
                            <li><?php echo $this->form->getLabel('cat_recursive'); ?>
                                <?php echo $this->form->getInput('cat_recursive'); ?></li>
                            <li><?php echo $this->form->getLabel('cat_entries'); ?>
                                <?php echo $this->form->getInput('cat_entries'); ?></li>
                            <li><?php echo $this->form->getLabel('cat_featured'); ?>
                                <?php echo $this->form->getInput('cat_featured'); ?></li>
                            <li><?php echo $this->form->getLabel('cat_featured_position'); ?>
                                <?php echo $this->form->getInput('cat_featured_position'); ?></li>
                        </ul>
                    </div>
                </fieldset>
            </td>
        </tr>
    </table>
</div>