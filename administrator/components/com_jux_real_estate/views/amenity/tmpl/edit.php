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

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task)
    {
	if (task == 'amenity.cancel' || document.formvalidator.isValid(document.id('amenity-form'))) {
	    Joomla.submitform(task, document.getElementById('amenity-form'));
	} else {
	    alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
	}
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="amenity-form" class="form-validate">
    <div class="row-fluid">
	<!-- Begin detail -->
	<div class="span10 form-horizontal">
	    <ul class="nav nav-tabs">
		<li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_DETAILS'); ?></a></li>
	    </ul>
	    <div class="tab-content">
		<div class="tab-pane active" id="general">
		    <fieldset>
			<div class="adminformlist">
			    <div class="control-group">
				<div class="control-label">
				    <?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			    </div>
			</div>

			<div class="adminformlist">
			    <div class="control-group">
				<div class="control-label">
				    <?php echo $this->form->getLabel('title'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
			    </div>
			</div>
			<div class="adminformlist">
			    <div class="control-group">
				<div class="control-label">
				    <?php echo $this->form->getLabel('cat'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('cat'); ?></div>
			    </div>
			</div>

		    </fieldset>
		</div>
	    </div>

	</div>
	<div class="clr"></div>
	<input type="hidden" name="controller" value="type"/>
	<input type="hidden" name="task" value=""/>
	<?php echo JHTML::_('form.token'); ?>
</form>
<div class="clr"></div>
<p class="copyright"><?php echo JUX_Real_EstateFactory::getFooter(); ?></p>