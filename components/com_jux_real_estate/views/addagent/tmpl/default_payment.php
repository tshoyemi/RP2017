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
<div id="agent-payment">
    <fieldset class="payment-information">
        <legend><h3><?php echo JText::_('COM_JUX_REAL_ESTATE_PAYMENT_INFORMATION'); ?></h3></legend>
        <table cellspacing="5" cellpadding="5" border="0" width="100%">
	    <tr>
		<td class="title_cell"  width="18%">
		    <?php echo $this->form->getLabel('payment_method'); ?>
		</td>
		<td>
		    <?php echo $this->form->getInput('payment_method'); ?>
		</td>
	    </tr>
        </table>
    </fieldset>
</div>
<style>
    fieldset#payment_method {
	height: auto;
    }
    input.radio {
	margin: 0 20px 0 0;
	padding: 0;
	height: auto;
	position: relative;
	top: 5px;
	display: inline-block;
    }
</style>
