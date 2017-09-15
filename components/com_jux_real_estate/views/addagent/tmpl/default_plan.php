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
<div id="agent-plans" >
    <fieldset class="plan-infomation">
        <legend><h3><?php echo JText::_('COM_JUX_REAL_ESTATE_SELECT_A_PLAN'); ?></h3></legend>
        <table width="100%" class="contentplan">
            <thead style="background: #F0F0F0;color: #666;">
            <th> <?php echo JText::_('COM_JUX_REAL_ESTATE_NAME'); ?></th>
            <th> <?php echo JText::_('COM_JUX_REAL_ESTATE_DAYS'); ?> </th>
            <th> <?php echo JText::_('COM_JUX_REAL_ESTATE_COUNT_LIMIT'); ?> </th>
            <th> <?php echo JText::_('COM_JUX_REAL_ESTATE_PRICE'); ?> </th>
            <th> <?php echo JText::_('COM_JUX_REAL_ESTATE_FEATURED'); ?> </th>
            </thead>
            <tbody>

		<tr>
		    <td align="center">
			<?php echo $this->plan->name; ?>
		    </td>
		    <td align="center">
			<?php
			if (!empty($this->plan->days)) {
			    if ($this->plan->days_type == 'day') {
				echo $this->plan->days . ' ' . JText::_('COM_JUX_REAL_ESTATE_DAYS');
			    } else {
				echo $this->plan->days . ' ' . JText::_('COM_JUX_REAL_ESTATE_MONTHS');
			    }
			} else {
			    echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED');
			}
			?>
		    </td>
		    <td align="center">
			<?php if (!empty($this->plan->count_limit)) { ?>
			    <?php echo $this->plan->count_limit; ?>
			<?php } else { ?>
			    <?php echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED'); ?>
			<?php } ?>
		    </td>
		    <td align="center">

			<?php
			if ($this->plan->price == '0.00')
			    echo JText::_('COM_JUX_REAL_ESTATE_FREE');
			else {
			    echo JUX_Real_EstateUtils::formatPrice($this->plan->price, $this->plan->currency_id, $this->config->get('thousand_separator'));
			}
			?>
		    </td>
		</tr>

            </tbody>
        </table>
    </fieldset>

</div>
