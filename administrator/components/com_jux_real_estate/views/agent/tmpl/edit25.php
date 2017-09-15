<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>

<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task) {
        var form = document.adminForm;
        if (task == 'agent.cancel' || document.formvalidator.isValid(document.id('agent-form'))) {
            Joomla.submitform(task, document.getElementById('agent-form'));
        }
    }

    window.addEvent('domready', function() {
        checkAgentUser = function() {
            $('message').set('tween', {duration: 4500});
            var checkurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_jux_real_estate&task=agent.checkUserAgent';
            var attachedUser = $('jform_user_id_id').value;

            req = new Request({
                method: 'post',
                url: checkurl,
                data: {'user_id': attachedUser,
                    'agent_id': <?php echo (int) $this->item->id; ?>,
                    '<?php echo JSession::getFormToken(); ?>': '1'
                },
                onRequest: function() {
                    $('message').set('html', '');
                },
                onSuccess: function(response) {
                    if (isNumber(response)) {
                        $('message').highlight('#ff0000');
                        $('jform_user_id_id').value = '';
                        $('jform_user_id_name').value = '';
                        $('jform_email').value = '';
                        $('message').set('html', '<div class="warning"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_USER_ALREADY_EXISTS'); ?></div>');
                    } else {
                        $('jform_email').value = response;
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
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . $this->item->id); ?>" method="post"
      name="adminForm" id="agent-form" enctype="multipart/form-data" class="form-validate">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILS'); ?></legend>
            <div class="width-50 fltlft">
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('first_name'); ?>
                        <?php echo $this->form->getInput('first_name'); ?></li>
                    <li><?php echo $this->form->getLabel('last_name'); ?>
                        <?php echo $this->form->getInput('last_name'); ?></li>
                    <li><?php echo $this->form->getLabel('alias'); ?>
                        <?php echo $this->form->getInput('alias'); ?></li>
                    <li><?php echo $this->form->getLabel('company_id'); ?>
                        <?php echo $this->form->getInput('company_id'); ?></li>
                    <li><?php echo $this->form->getLabel('email'); ?>
                        <?php echo $this->form->getInput('email'); ?></li>
                    <li><?php echo $this->form->getLabel('phone'); ?>
                        <?php echo $this->form->getInput('phone'); ?></li>
                    <li><?php echo $this->form->getLabel('mobile'); ?>
                        <?php echo $this->form->getInput('mobile'); ?></li>
                    <li><?php echo $this->form->getLabel('fax'); ?>
                        <?php echo $this->form->getInput('fax'); ?></li>
                    <li><?php echo $this->form->getLabel('organization'); ?>
                        <?php echo $this->form->getInput('organization'); ?></li>
                    <li><?php echo $this->form->getLabel('ordering'); ?>
                        <?php echo $this->form->getInput('ordering'); ?></li>
                </ul>
            </div>
            <div class="width-50 fltrt">
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('address'); ?>
                        <?php echo $this->form->getInput('address'); ?></li>
                    <li><?php echo $this->form->getLabel('street'); ?>
                        <?php echo $this->form->getInput('street'); ?></li>
                    <li><?php echo $this->form->getLabel('city'); ?>
                        <?php echo $this->form->getInput('city'); ?></li>
                    <li><?php echo $this->form->getLabel('locstate'); ?>
                        <?php echo $this->form->getInput('locstate'); ?></li>
                    <li><?php echo $this->form->getLabel('province'); ?>
                        <?php echo $this->form->getInput('province'); ?></li>
                    <li><?php echo $this->form->getLabel('postcode'); ?>
                        <?php echo $this->form->getInput('postcode'); ?></li>
                    <li><?php echo $this->form->getLabel('country_id'); ?>
                        <?php echo $this->form->getInput('country_id'); ?></li>
                </ul>
            </div>
            <div class="clr" style="height: 10px;"></div>
            <div class="clr">

            </div>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('sub_desc'); ?>
                    <?php echo $this->form->getInput('sub_desc'); ?></li>
            </ul>
            <?php
            if ($this->configs->get('use_editor')) {
                echo $this->form->getInput('description');
            } else {
                echo '<textarea class="inputbox" name="jform[description]" id="jform_description" rows="10" cols="60">' . $this->item->description . '</textarea>';
            }
            ?>
        </fieldset>
    </div>
    <div class="width-40 fltrt">

        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_AVATAR'); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getInput('avatar'); ?></li>
            </ul>
        </fieldset>
        <!-- only admin can set agent to super agent level -->
        <?php if (JFactory::getUser()->authorise('core.admin')): ?>
            <fieldset class="adminform admin">
                <legend><?php echo JText::_('JADMINISTRATION'); ?></legend>
                <div id="message"></div>
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('user_id'); ?>
                        <?php echo $this->form->getInput('user_id'); ?></li>
                </ul>
            </fieldset>
        <?php endif; ?>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_PAYMENT_INFOMATION'); ?></legend>
            <ul class="adminformlist">

                <li><?php echo $this->form->getLabel('plan_id'); ?>
                    <?php echo $this->form->getInput('plan_id'); ?></li>
                <li><?php echo $this->form->getLabel('payment_method'); ?>
                    <?php echo $this->form->getInput('payment_method'); ?></li>
                <li><?php echo $this->form->getLabel('transaction_id'); ?>
                    <?php echo $this->form->getInput('transaction_id'); ?></li>
                <li><?php echo $this->form->getLabel('date_created'); ?>
                    <?php echo $this->form->getInput('date_created'); ?></li>
                <li><?php echo $this->form->getLabel('date_paid'); ?>
                    <?php echo $this->form->getInput('date_paid'); ?></li>

            </ul>
        </fieldset>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_WEB'); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('website'); ?>
                    <?php echo $this->form->getInput('website'); ?></li>
                <li><?php echo $this->form->getLabel('msn'); ?>
                    <?php echo $this->form->getInput('msn'); ?></li>
                <li><?php echo $this->form->getLabel('skype'); ?>
                    <?php echo $this->form->getInput('skype'); ?></li>
                <li><?php echo $this->form->getLabel('gtalk'); ?>
                    <?php echo $this->form->getInput('gtalk'); ?></li>
                <li><?php echo $this->form->getLabel('linkedin'); ?>
                    <?php echo $this->form->getInput('linkedin'); ?></li>
                <li><?php echo $this->form->getLabel('facebook'); ?>
                    <?php echo $this->form->getInput('facebook'); ?></li>
                <li><?php echo $this->form->getLabel('twitter'); ?>
                    <?php echo $this->form->getInput('twitter'); ?></li>
                <li><?php echo $this->form->getLabel('social1'); ?>
                    <?php echo $this->form->getInput('social1'); ?></li>
            </ul>
        </fieldset>
        <!-- admin can edit publishing -->
        <?php if (JFactory::getUser()->authorise('core.admin')): ?>
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_PUBLISHING'); ?></legend>
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('published'); ?>
                        <?php echo $this->form->getInput('published'); ?></li>
                    <li><?php echo $this->form->getLabel('featured'); ?>
                        <?php echo $this->form->getInput('featured'); ?></li>
                    <li><?php echo $this->form->getLabel('approved'); ?>
                        <?php echo $this->form->getInput('approved'); ?></li>
                </ul>
            </fieldset>

        <?php endif; ?>

    </div>
    <div class="clr"></div>
    <input type="hidden" name="task" value=""/>
    <?php echo JHTML::_('form.token'); ?>

</form>