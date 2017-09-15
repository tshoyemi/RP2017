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

$db = JFactory::getDBO();
$nullDate = $db->getNullDate();
// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript">
    Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if (task == 'currency.cancel' || document.formvalidator.isValid(document.id('currency-form'))) {
	    Joomla.submitform(task, document.getElementById('currency-form'));
	} else {
	    alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
	}
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="currency-form" class="form-validate">
    <div class="row-fluid">
        <!-- Begin Content -->
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_CURRENCY_DETAILS'); ?></a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <fieldset>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('code'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('code'); ?></div>
                        </div>

                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('sign'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('sign'); ?></div>
                        </div>

                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('position'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('position'); ?></div>
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
    </div>
    <input type="hidden" name="controller" value="currency"/>
    <input type="hidden" name="task" value=""/>
    <?php echo JHTML::_('form.token'); ?>
</form>
<?php echo JUX_Real_EstateFactory::getFooter();