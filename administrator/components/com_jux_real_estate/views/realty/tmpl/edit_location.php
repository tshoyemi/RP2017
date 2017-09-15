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
<fieldset>
    <div class="span12">
        <div class="adminform">
            <div class="span6">
		<legend><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_ADRESS'); ?></legend>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('address'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('country_id'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('country_id'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('locstate'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('locstate'); ?></div>
                </div>
            </div>
            <div class="span6">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_GOOGLE_MAPS'); ?></legend>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('latitude'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('latitude'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('longitude'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('longitude'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('show_map'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('show_map'); ?></div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<fieldset>
    <?php echo $this->form->getLabel('geocode_header'); ?>
    <div class="span12">

        <div class="adminform">
            <div class="control-group">
		<?php echo $this->form->getInput('google_map'); ?>
            </div>
        </div>
    </div>
</fieldset>