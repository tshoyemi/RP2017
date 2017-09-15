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

defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
?>

<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task)
    {
	if (task == 'state.cancel' || document.formvalidator.isValid(document.id('state-form'))) {
	    Joomla.submitform(task, document.getElementById('state-form'));
	} else {
	    alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
	}
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="state-form" class="form-validate">
    <div class="row-fluid">
	<div class="span10 form-horizontal">
	    <ul class="nav nav-tabs">
		<li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_STATE_DETAILS'); ?></a></li>
	    </ul>
	    <div class="tab-content">
		<div class="tab-pane active" id="general">
		    <fieldset>
			<div class="control-group">
			    <div class="control-label"><?php echo $this->form->getLabel('state_name'); ?></div>
			    <div class="controls"><?php echo $this->form->getInput('state_name'); ?></div>
			</div>
			<div class="control-group">
			    <div class="control-label"><?php echo $this->form->getLabel('state_code'); ?></div>
			    <div class="controls"><?php echo $this->form->getInput('state_code'); ?></div>
			</div>
			<div class="control-group">
			    <div class="control-label"><?php echo $this->form->getLabel('country_id'); ?></div>
			    <div class="controls"><?php echo $this->form->getInput('country_id'); ?></div>
			</div>
		    </fieldset>
		</div>
	    </div>
	</div>
    </div>
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>

<?php echo JUX_Real_EstateFactory::getFooter(); ?>