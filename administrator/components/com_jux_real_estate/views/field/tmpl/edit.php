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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if (task == 'field.cancel' || document.formvalidator.isValid(document.id('field-form'))) {
	    Joomla.submitform(task, document.getElementById('field-form'));
	}
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="field-form" id="adminForm">

    <!-- code 3.0 -->
    <div class="row-fluid">
	<!-- begin plan -->
	<div class="span10 form-horizontal">
	    <ul class="nav nav-tabs">
		<li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_FIELD_DETAILS'); ?></a></li>
	    </ul>
	    <div class="tab-content">
		<div class="tab-pane active" id="general">
		</div>
		<div class="span6">
		    <fieldset>
			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('name'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('name'); ?>
			    </div>
			</div>

			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('title'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('title'); ?>
			    </div>
			</div>
			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('field_type'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('field_type'); ?>
			    </div>
			</div>

			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('description'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('description'); ?>
			    </div>
			</div>

			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('values'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('values'); ?>
			    </div>
			</div>
			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('default_values'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('default_values'); ?>
			    </div>
			</div>

		    </fieldset>
		</div>
		<div class="span6">
		    <fieldset>
			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('required'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('required'); ?>
			    </div>
			</div>
			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('rows'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('rows'); ?>
			    </div>
			</div>
			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('cols'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('cols'); ?>
			    </div>
			</div>
			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('size'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('size'); ?>
			    </div>
			</div>
			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('css_class'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('css_class'); ?>
			    </div>
			</div>
			<div class="control-group">
			    <div class="control-label">
				<?php echo $this->form->getLabel('search_field'); ?>
			    </div>
			    <div class="controls">
				<?php echo $this->form->getInput('search_field'); ?>
			    </div>
			</div>
		    </fieldset>
		</div>
	    </div>
	</div>
	<!-- End field -->
	
	<!-- Begin Sidebar -->
	<div class="span2">
	    <h4><?php echo JText::_('JDETAILS'); ?></h4>
	    <hr />
	    <fieldset class="form-vertical">

		<div class="control-group">
		    <div class="controls"><?php echo $this->form->getValue('title'); ?></div>
		</div>

		<div class="control-group">
		    <div class="control-label"><?php echo $this->form->getLabel('published'); ?></div>
		    <div class="controls"><?php echo $this->form->getInput('published'); ?></div>
		</div>

		<div class="control-group">
		    <div class="control-label"><?php echo $this->form->getLabel('ordering'); ?></div>
		    <div class="controls"><?php echo $this->form->getInput('ordering'); ?></div>
		</div>

		<div class="control-group">
		    <div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
		    <div class="controls"><?php echo $this->form->getInput('access'); ?></div>
		</div>
		<div class="control-group">
		    <div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
		    <div class="controls"><?php echo $this->form->getInput('language'); ?></div>
		</div>

	    </fieldset>
	</div>
	<!-- End Sidebar -->
    </div>
    <!-- code 2.5 -->
</div>
<div class="clr"></div>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="controller" value="field"/>
<?php echo JHTML::_('form.token'); ?>
<script type="text/javascript" language="javascript">
    function checkFieldName() {
	var form = document.adminForm;
	var name = form.jform_name.value;
	var oldValue = name;
	name = name.replace('jp_', '');
	name.replace(/[^a-zA-Z0-9]+/g, '');
	form.jform_name.value = 'jp_' + name;
	if (oldValue != form.jform_name.value) {
	    alert('<?php echo JText::_('COM_JUX_REAL_ESTATE_FIELD_NAME_AUTOMATICALLY_CHANGED_TO_FOLLOW_NAMING_STANDARD'); ?>');
	}
    }
</script>

</form>