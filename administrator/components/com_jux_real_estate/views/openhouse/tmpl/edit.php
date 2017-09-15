<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
$db = JFactory::getDBO();
$nullDate = $db->getNullDate();
?>

<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task == 'openhouse.cancel' || document.formvalidator.isValid(document.id('openhouse-form'))) {
            Joomla.submitform(task, document.getElementById('openhouse-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="openhouse-form" class="form-validate">
    <!-- code 3.0 -->
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_OPEN_HOUSE_DETAILS'); ?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <fieldset>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('name'); ?></div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('realty_id'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('realty_id'); ?></div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('publish_up'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('publish_up'); ?></div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('publish_down'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('publish_down'); ?></div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('comments'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('comments'); ?></div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <!-- end detail open house -->
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
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="controller" value="plan"/>
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<?php echo JUX_Real_EstateFactory::getFooter(); ?>