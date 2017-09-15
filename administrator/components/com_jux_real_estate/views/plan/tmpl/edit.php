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
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$db = JFactory::getDBO();
$nullDate = $db->getNullDate();
?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if (task == 'plan.cancel' || document.formvalidator.isValid(document.id('plan-form'))) {
	    Joomla.submitform(task, document.getElementById('plan-form'));
	} else {
	    alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
	}
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="plan-form" enctype="multipart/form-data" class="form-validate" id="adminForm">
    <!-- code 3.0 -->
    <div class="row-fluid">
        <!-- begin plan -->
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_PLAN_DETAILS'); ?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <fieldset>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('name'); ?></div>
                        </div>

                        <div class="control-group">
                            <div class="control-label" width="15%">
				<?php echo $this->form->getLabel('price'); ?>
                            </div>
                            <div class="controls">
				<?php echo $this->form->getInput('price'); ?>
				<?php echo $this->form->getInput('currency_id'); ?>
                                &nbsp;&nbsp;(<strong><?php echo JText::_('COM_JUX_REAL_ESTATE_0_FOR_FREE'); ?></strong>)
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label" width="15%">
				<?php echo $this->form->getLabel('days'); ?>
                            </div>
                            <div class="controls">
				<?php echo $this->form->getInput('days'); ?>
				<?php echo $this->form->getInput('days_type'); ?>
                                &nbsp;&nbsp;(<strong><?php echo JText::_('COM_JUX_REAL_ESTATE_0_FOR_UNLIMITED'); ?></strong>)
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
				<?php echo $this->form->getLabel('count_limit'); ?>
                            </div>
                            <div class="controls">
				<?php echo $this->form->getInput('count_limit'); ?>
                                &nbsp;&nbsp;(<strong><?php echo JText::_('COM_JUX_REAL_ESTATE_0_FOR_UNLIMITED'); ?></strong>)

                            </div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
				<?php echo $this->form->getLabel('date_created'); ?>
                            </div>
                            <div class="controls">
				<?php echo $this->form->getInput('date_created'); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
				<?php echo $this->form->getLabel('publish_up'); ?>
                            </div>
                            <div class="controls">
				<?php echo $this->form->getInput('publish_up'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
				<?php echo $this->form->getLabel('publish_down'); ?>
                            </div>
                            <div class="controls">
				<?php echo $this->form->getInput('publish_down'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('image'); ?></div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
				<?php echo $this->form->getLabel('description'); ?>
                            </div>
                            <div class="controls">
				<?php echo $this->form->getInput('description'); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <!-- End plan -->
	
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
                    <div class="control-label"><?php echo $this->form->getLabel('featured'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('featured'); ?></div>
                </div>

            </fieldset>
        </div>
        <!-- End Sidebar -->
	
        <!-- code 2.5 -->

        <div class="clr"></div>
	<?php echo $this->form->getInput('old_image', null, $this->item->image); ?>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="controller" value="plan"/>
	<?php echo JHTML::_('form.token'); ?>
</form>