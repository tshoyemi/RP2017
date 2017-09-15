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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
?>

<script type="text/javascript">
    Joomla.submitbutton = function(task) {
	if (task == 'configs.cancel' || document.formvalidator.isValid(document.id('config-form'))) {
	    Joomla.submitform(task, document.getElementById('config-form'));
	} else {
	    alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
	}
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=configs'); ?>" method="post" name="adminForm" id="config-form" class="form-validate">
    <!-- code 3.0 -->
    <div class="row-fluid">
        <!-- begin plan -->
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_CONFIG_GENERAL_DETAILS'); ?></a></li>
                <li><a href="#agents" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_CONFIG_AGENT_DETAILS'); ?></a></li>
                <li><a href="#email" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_CONFIG_EMAIL_DETAILS'); ?></a></li>
                <li><a href="#message" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_CONFIG_MESSAGE_DETAILS'); ?></a></li>
                <li><a href="#permissions" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_CONFIG_PERMISSTION_DETAILS'); ?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <fieldset>
			<?php echo $this->loadTemplate('general'); ?>
                    </fieldset>
                </div>				
                <div class="tab-pane" id="agents">
                    <fieldset>
			<?php echo $this->loadTemplate('agents'); ?>
                    </fieldset>
                </div>               
                <div class="tab-pane" id="email">
                    <fieldset>
			<?php echo $this->loadTemplate('email'); ?>
                    </fieldset>
                </div>
                <div class="tab-pane" id="message">
                    <fieldset>
			<?php echo $this->loadTemplate('message'); ?>
                    </fieldset>
                </div>
                <div class="tab-pane" id="permissions">
                    <fieldset>
			<?php echo $this->loadTemplate('permissions'); ?>
                    </fieldset>
                </div>


            </div>
        </div>
    </div>
    <input type="hidden" name="option" value="com_jux_real_estate" />
    <input type="hidden" name="controller" value="configs" />
    <input type="hidden" name="task" value="" />
    <?php echo JHTML::_('form.token'); ?>
</form>

<?php
echo JUX_Real_EstateFactory::getFooter();