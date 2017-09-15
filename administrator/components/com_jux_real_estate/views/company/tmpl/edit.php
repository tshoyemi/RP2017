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
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
?>

<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'company.cancel' || document.formvalidator.isValid(document.id('company-form'))) {
            Joomla.submitform(task, document.getElementById('company-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="company-form" class="form-validate">

    <!-- code 3.0 -->
    <div class="row-fluid">
        <!-- begin plan -->
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_COMPANY_DETAILS'); ?></a></li>
                <li><a href="#image" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_COMPANY_IMAGE_DETAILS'); ?></a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="general">
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
                                    <?php echo $this->form->getLabel('alias'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('alias'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('email'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('email'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('phone'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('phone'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('fax'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('fax'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('website'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('website'); ?>
                                </div>
                            </div>

                        </fieldset>
                    </div>

                    <div class="span6">
                        <fieldset>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('street'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('street'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('city'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('city'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('country_id'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('country_id'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('locstate'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('locstate'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('province'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('province'); ?>
                                </div>
                            </div>
                        </fieldset>

                    </div>
                    <div class="span10">
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('sub_desc'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('sub_desc'); ?>
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
                    </div>
                </div>
                <div class="tab-pane" id="image">
                    <fieldset>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('image'); ?></div>
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
                    <?php echo $this->form->getLabel('state'); ?>
                    <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
                </div>

                <div class="control-group">
                    <?php echo $this->form->getLabel('ordering'); ?>
                    <div class="controls"><?php echo $this->form->getInput('ordering'); ?></div>
                </div>

                <div class="control-group">
                    <?php echo $this->form->getLabel('access'); ?>
                    <div class="controls"><?php echo $this->form->getInput('access'); ?></div>
                </div>
                <div class="control-group">
                    <?php echo $this->form->getLabel('featured'); ?>
                    <div class="controls"><?php echo $this->form->getInput('featured'); ?></div>
                </div>

            </fieldset>
        </div>
        <!-- End Sidebar -->
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<?php echo JUX_Real_EstateFactory::getFooter(); ?></p>
<style>
    #jform_sub_desc.textarea{
        width: 98%;
    }
</style>