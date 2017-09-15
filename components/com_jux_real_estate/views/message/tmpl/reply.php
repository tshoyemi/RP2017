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

JHTML::_('behavior.tooltip');
?>
<style type="text/css">
    table.sendmessagetable {margin:10px; background-color:#cccccc; width:90%; }
    table.sendmessagetable td {padding:5px 10px;}
</style>
<form action="index.php" method="post"
      name="msform" id="msform" class="form-validate" enctype="multipart/form-data">
    <div colspan="4"><strong
            style="color:#58C3E3; font-size:16px"><?php echo JText::_('COM_JUX_REAL_ESTATE_CONTACT_AGENTS'); ?></strong></th>
    </div>
    <div class="row">
        <div col-xs-12">
             <div>
                <span style="font-size: 90%"><?php echo JText::_('COM_JUX_REAL_ESTATE_CONTACT_AGENTS_YOUR_NAME');?></span> 
                <span style="font-size: 80%">(*<?php echo JText::_('COM_JUX_REAL_ESTATE_MY_MESSAGE_REQUIRED');?>)</span>
                <input required class="span12" name="name" id="name" maxlength="50" type="text"   value="<?php echo $this->user->name; ?>" />
            </div>

            <div>
                <span style="font-size: 90%"><?php echo JText::_('COM_JUX_REAL_ESTATE_CONTACT_AGENTS_YOUR_EMAIL');?></span> 
                <span style="font-size: 80%">(*<?php echo JText::_('COM_JUX_REAL_ESTATE_MY_MESSAGE_REQUIRED');?>)</span>
                <input required class="span12" name="email" id="email" maxlength="50" type="email" value="<?php echo $this->user->email; ?>" />
            </div>

            <div>
		<span style="font-size: 90%"><?php echo JText::_('COM_JUX_REAL_ESTATE_MESSAGE');?></span> 
		<span style="font-size: 80%">(*<?php echo JText::_('COM_JUX_REAL_ESTATE_MY_MESSAGE_REQUIRED');?>)</span><br>
                <textarea required class="span12" rows="4" name="content" id="content" cols="20"></textarea>
            </div>
	     
            <input class="btn btn-info btn-small" type="submit" name="send" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_SEND'); ?>" id="send"/>
            <input class="btn btn-info btn-small" name="Reset" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_MESSAGE_RESET');?>" type="reset">
        </div>

    </div> 
    <input type="hidden" name="option" id="option" value="com_jux_real_estate"/>
    <input type="hidden" name="task" id="task" value="messages.sendmessage" />
    <input type="hidden" name="controller" id="controller" value="messages" />
    <input type="hidden" name="realty_id" id="realty_id" value="<?php echo JRequest::getInt('realty_id', 0); ?>" />
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $this->user->id; ?>" />
    <input type="hidden" name="receive_id" id="receive_id" value="<?php echo JRequest::getVar('receive_id', 0); ?>" />
    <input type="hidden" name="toemail" id="toemail" value="<?php echo JRequest::getString('toemail', ''); ?>" />
    <input type="hidden" name="toname" id="toname" value="<?php echo JRequest::getString('toname', ''); ?>" />
</form>