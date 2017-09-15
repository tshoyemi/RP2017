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

    Joomla.submitbutton = function (task) {
        var form = document.adminForm;
        if (task == 'agent.cancel' || document.formvalidator.isValid(document.id('agent-form'))) {
            Joomla.submitform(task, document.getElementById('agent-form'));
        }
    }

    window.addEvent('domready', function () {
        checkAgentUser = function () {
            $('#message').set('tween', {duration: 4500});
            var checkurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_jux_real_estate&task=agent.checkUserAgent';
            var attachedUser = jQuery('#jform_user_id_id').val();

            req = new Request({
                method: 'post',
                url: checkurl,
                data: {'user_id': attachedUser,
                    'agent_id': <?php echo (int) $this->item->id; ?>,
                    '<?php echo JSession::getFormToken(); ?>': '1'
                },
                onRequest: function () {
                    $('#message').set('html', '');
                },
                onSuccess: function (response) {

                    if (isNumber(response)) {
                        $('#message').highlight('#ff0000');
                        $('#jform_user_id_id').value = '';
                        $('#jform_user_id_name').value = '';
                        $('#jform_email').value = '';
                        $('#message').set('html', '<div class="warning"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_USER_ALREADY_EXISTS'); ?></div>');
                    } else {
                        $('#jform_email').value = response;
                    }
                }
            }).send();
        }
    });

    function isNumber(num) {
        return (typeof num == 'string' || typeof num == 'number') && !isNaN(num - 0) && num !== '';
    }
    ;
</script>
<form	action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="agent-form" class="form-validate">

    <div class="row-fluid">
        <!-- Begin detail -->
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_DETAILS'); ?></a></li>
                <li><a href="#avatar" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_AVATAR'); ?></a></li>
                <li><a href="#administrator" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_ADMINISTRATOR'); ?></a></li>
                <li><a href="#payment" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PAYMENT_INFORMATION'); ?></a></li>
                <li><a href="#web" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_WEB'); ?></a></li>
                <li><a href="#publishing" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PUBLISHING'); ?></a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <fieldset>
                        <div class="span6">
                            <div class="adminformlist">
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('username'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('username'); ?></div>
                                </div>
                                <div class="control-group hidden">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('alias'); ?></div>
                                    <div class="controls">
                                        <?php echo $this->form->getInput('alias'); ?></div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('email'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('email'); ?></div>
                                </div>


                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('fax'); ?></div>
                                    <div class="controls">
                                        <?php echo $this->form->getInput('fax'); ?></div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('organization'); ?></div>
                                    <div class="controls">
                                        <?php echo $this->form->getInput('organization'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="adminformlist">
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('phone'); ?></div>
                                    <div class="controls">
                                        <?php echo $this->form->getInput('phone'); ?></div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('address'); ?></div>
                                    <div class="controls">
                                        <?php echo $this->form->getInput('address'); ?></div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('country_id'); ?></div>
                                    <div class="controls">
                                        <?php echo $this->form->getInput('country_id'); ?></div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('locstate'); ?></div>
                                    <div class="controls">
                                        <?php echo $this->form->getInput('locstate'); ?></div>
                                </div>

                            </div>
                        </div>
                        <div class="span12">                          
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('description'); ?>

                                </div>
                            </div>
                        </div>
                    </fieldset>

                </div>

                <div class="tab-pane" id="avatar">
                    <fieldset class="adminform">
                        <div class="adminformlist">

                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('avatar'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('avatar'); ?></div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="tab-pane" id="administrator">
                    <?php if (JFactory::getUser()->authorise('core.admin')): ?>
                        <fieldset class="adminform admin">
                            <div id="message"></div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('user_id'); ?></div>
                                <div class="controls">	<?php echo $this->form->getInput('user_id'); ?></div>
                            </div>
                        </fieldset>
                    <?php endif; ?>
                </div>
                <div class="tab-pane" id="payment">

                    <fieldset class="adminform">
                        <div class="adminformlist">
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('plan_id'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('plan_id'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('payment_method'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('payment_method'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('transaction_id'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('transaction_id'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('date_created'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('date_created'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('date_paid'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('date_paid'); ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="tab-pane" id="web">

                    <fieldset class="adminform">
                        <div class="adminformlist">
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('website'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('website'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('msn'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('msn'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('skype'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('skype'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('googleplus'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('googleplus'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('linkedin'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('linkedin'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('facebook'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('facebook'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('twitter'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('twitter'); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('social1'); ?>
                                </div>
                                <div class="controls"><?php echo $this->form->getInput('social1'); ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="tab-pane" id="publishing">
                    <fieldset class="adminform">
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('published'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('published'); ?></div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('featured'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('featured'); ?></div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('approved'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('approved'); ?></div>
                        </div>
                    </fieldset>
                </div>
            </div>

        </div>


        <!-- Begin Sidebar -->
        <div class="span2">
            <h4><?php echo JText::_('JDETAILS'); ?></h4>
            <hr />
            <fieldset class="form-vertical">

                <div class="control-group">
                    <div class="controls"><?php echo $this->form->getValue('title'); ?></div>
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
                    <?php echo $this->form->getLabel('language'); ?>
                    <div class="controls"><?php echo $this->form->getInput('language'); ?></div>
                </div>

            </fieldset>
        </div>
        <!-- End Sidebar -->
        <div class="clr"></div>
        <input type="hidden" name="controller" value="type"/>
        <input type="hidden" name="task" value=""/>
        <?php echo JHTML::_('form.token'); ?>
    </div>
</form>
<?php
echo JUX_Real_EstateFactory::getFooter();
?>
<style>
    #jform_sub_desc.textarea{
        width: 98%;
    }
</style>