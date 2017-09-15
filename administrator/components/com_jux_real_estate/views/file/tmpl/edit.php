<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHTML::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

//$pane		= &JPane::getInstance('sliders', array('allowAllClose' => 1));
$canDo = JUX_Real_EstateHelper::getActions();
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task == 'file.cancel' || document.formvalidator.isValid(document.id('file-form'))) {
            Joomla.submitform(task, document.getElementById('file-form'));
        }
    }
</script>
<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=file&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="file-form" id="file-form" class="form-validate" enctype="multipart/form-data">
    <div class="row-fluid">
        <!-- Begin Content -->
        <div class="span10 form-horizontal">
            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_JUX_REAL_ESTATE_FILE_GENERAL', true)); ?>
            <p>
                <span style="color:#AAAAAA;"><?php echo JText::_('COM_JUX_REAL_ESTATE_VALID_FILE_TYPE') . ': '; ?><i><?php echo $this->params->get('file_allowed_ext'); ?></i></span>
            </p>
            <?php
            $fieldset = $this->form->getFieldset('general');
            foreach ($fieldset as $field) :
                ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $field->__get('label'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $field->__get('input'); ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_JUX_REAL_ESTATE_FILE_PUBLISHING', true)); ?>
            <?php
            $fieldset = $this->form->getFieldset('publishing');
            foreach ($fieldset as $field) :
                ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $field->__get('label'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $field->__get('input'); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        </div>
        <!-- End Content -->
        <!-- Begin Sidebar -->
        <div class="span2">
            <h4><?php echo JText::_('JDETAILS'); ?></h4>
            <hr />
            <fieldset class="form-vertical">
                <?php
                $fieldset = $this->form->getFieldset('file_info');
                foreach ($fieldset as $field) :
                    ?>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $field->__get('label'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $field->__get('input'); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </fieldset>
        </div>
        <!-- End Sidebar -->
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>
</form>
<?php
echo JUX_Real_EstateFactory::getFooter();