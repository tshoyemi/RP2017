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
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('meta_keywords'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('meta_keywords'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('meta_desc'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('meta_desc'); ?></div>
    </div>
</fieldset>