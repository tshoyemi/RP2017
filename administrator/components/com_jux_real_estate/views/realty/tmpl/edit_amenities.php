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
<fieldset class="adminform">
    <div class="span1"></div>
    <div class="span3">
        <?php echo $this->form->getLabel('general_amenity_header'); ?>
        <?php echo $this->form->getInput('general_amenies'); ?>
    </div>
    <div class="span3">
        <?php echo $this->form->getLabel('interior_amenity_header'); ?>
        <?php echo $this->form->getInput('interior_amenies'); ?>
    </div>
    <div class="span3">
        <?php echo $this->form->getLabel('exterior_amenity_header'); ?>
        <?php echo $this->form->getInput('exterior_amenies'); ?>
    </div>
</fieldset>