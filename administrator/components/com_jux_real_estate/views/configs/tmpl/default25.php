<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('behavior.switcher');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>

<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task == 'configs.cancel' || document.formvalidator.isValid(document.id('config-form'))) {
            Joomla.submitform(task, document.getElementById('config-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=configs'); ?>" method="post" name="adminForm" id="config-form" class="form-validate">
    <div id="config-document">
        <div id="page-general" class="tab">
            <div class="noshow">
                <div class="width-100">
                    <?php echo $this->loadTemplate('general25');?>
                </div>
            </div>
        </div>
        <div id="page-realties" class="tab">
            <div class="noshow">
                <div class="width-100">
                    <?php echo $this->loadTemplate('realties25');?>
                </div>
            </div>
        </div>
        <div id="page-agents" class="tab">
            <div class="noshow">
                <div class="width-100">
                    <?php echo $this->loadTemplate('agents25');?>
                </div>
            </div>
        </div>
        <div id="page-companies" class="tab">
            <div class="noshow">
                <div class="width-100">
                    <?php echo $this->loadTemplate('companies25');?>
                </div>
            </div>
        </div>
        <div id="page-email" class="tab">
            <div class="noshow">
                <div class="width-100">
                    <?php echo $this->loadTemplate('email25');?>
                </div>
            </div>
        </div>
        <div id="page-message" class="tab">
            <div class="noshow">
                <div class="width-100">
                    <?php echo $this->loadTemplate('message25');?>
                </div>
            </div>
        </div>
        <div id="page-permissions" class="tab">
            <div class="noshow">
                <div class="width-100">
                    <?php echo $this->loadTemplate('permissions25');?>
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