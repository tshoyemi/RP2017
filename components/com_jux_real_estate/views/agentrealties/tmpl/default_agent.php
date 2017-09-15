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
defined('_JEXEC') or die('Restricted access');
$agentlink = JRoute::_(JUX_Real_EstateHelperRoute::getAgentRealtyRoute($this->agent->id . ':' . $this->agent->alias));
$agentcontactlink = '#';
?>
<script>
    var sentmail = "<?php echo isset($_SESSION['sentmail']) && $_SESSION['sentmail'] ? $_SESSION['sentmail'] : false; ?>";
    var namemail = "<?php echo $this->agent->username; ?>";
    jQuery(document).ready(function ($) {
        if (sentmail == 'yes') {
            alert('You sent a message to ' + namemail);
        }
        else if (sentmail == 'no') {
            alert('Error sent a message to ' + namemail);
        }
    });
</script>

<?php
if (isset($_SESSION['sentmail'])) {
    unset($_SESSION['sentmail']);
}
?>

<div class="jux-col-xs-12 jux-col-sm-4 jux-col-md-4 left-content">
    <div class="agent-avatar">
        <div class="avatar-thumb">
            <?php
            if ($this->configs->get('agent_show_image', 1)) {
                if ($this->agent->avatar):
                    echo '<a class="agent_link" href="' . $agentlink . '"><img class="img-responsive" src="' . JUri::root() . $this->agent->avatar . '" alt="' . $this->agent->username . '" /></a>';
                else:
                    echo '<a class="agent_link" href="' . $agentlink . '"><img class="img-responsive" src="' . JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg' . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_NO_IMAGE') . '" /></a>';
                endif;
            }
            ?>
        </div>
    </div>
</div>
<div class="right-content">
    <div class="agent-detail">
        <h4 class="agent-detail-title"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_CONTACT_INFO');?></h4>
        <div class="agent-detail-info">
            <?php if ($this->configs->get('agentdetail_show_phone')) : ?>
                <div class="agent-phone"><i class="jux-fa jux-fa-phone"></i>&nbsp;<span><?php echo JText::_('COM_JUX_REAL_ESTATE_PHONE');?>:</span><?php echo $this->agent->phone; ?></div>
            <?php endif; ?>
            <?php if ($this->configs->get('agendetail_show_fax')) : ?>
                <div class="agent-mobile"><i class="jux-fa jux-fa-tablet"></i>&nbsp;<span><?php echo JText::_('COM_JUX_REAL_ESTATE_FAX');?>:</span><?php echo $this->agent->fax; ?></div>
            <?php endif; ?>
            <?php if ($this->configs->get('agentdetail_show_email')) : ?>
                <div class="agent-email"><i class="jux-fa jux-fa-envelope-square"></i>&nbsp;<span><?php echo JText::_('COM_JUX_REAL_ESTATE_EMAIL');?>:</span><?php echo $this->agent->email; ?></div>
            <?php endif; ?>
            <?php if ($this->configs->get('agentdetail_show_website')) : ?>
                <div class="agent-website"><i class="jux-fa jux-fa-book"></i>&nbsp;<span><?php echo JText::_('COM_JUX_REAL_ESTATE_WEBSITE');?>:</span><?php echo $this->agent->website; ?></div>
            <?php endif; ?>
            <?php if ($this->configs->get('agentdetail_show_skype')) : ?>
                <div class="agent-skype"><i class="jux-fa jux-fa-skype"></i>&nbsp;<span><?php echo JText::_('COM_JUX_REAL_ESTATE_SKYPE');?>:</span><?php echo $this->agent->skype; ?></div>
            <?php endif; ?>
            <?php if ($this->configs->get('agentdetail_show_address')) : ?>
                <div class="agent-address"><i class="jux-fa jux-fa-map-marker"></i>&nbsp;<span><?php echo JText::_('COM_JUX_REAL_ESTATE_ADDRESS');?>:</span><?php echo $this->agent->address; ?></div>
            <?php endif; ?>
        </div>

        <div class="agent-desc">
            <h4 class="agent-detail-title"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_ABOUT_ME');?></h4>
            <?php
            $description = JHtml::_('string.truncate', ($this->agent->description));
            echo JHTML::_('content.prepare', $description);
            ?>
        </div>

    </div>
</div>
<div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12">
    <?php if ($this->configs->get('agentdetail_contact_form')) : ?>
        <div class = "contact-agent">
            <h2 class="content-title"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_CONTACT_ME');?></h2>
            <form  method="post">
                <div class="jux-row">
                    <div class="jux-col-xs-5 jux-col-sm-5">   
                        <input name="name" id="agent_contact_name" type="text" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_CONTACT_AGENTS_YOUR_NAME');?>" aria-required="true" class="form-control">
                        <input type="text" name="email" class="form-control" id="agent_user_email" aria-required="true" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_CONTACT_AGENTS_YOUR_EMAIL');?>">
                        <input type="text" name="user_phone" class="form-control" id="agent_phone" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_YOUR_PHONE');?>">
                    </div>
                    <div class="jux-col-xs-7 jux-col-sm-7">
                        <textarea id="agent_comment" name="content" class="form-control" cols="45" rows="8" aria-required="true" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_YOUR_MESSAGES');?>"></textarea>
                    </div>
                </div>
                <div class="jux-row">
                    <div class="jux-col-sm-5">
                        <?php if ($this->configs->get('enable_capcha') == 1) { ?>
                            <input type="text" name="jform[captcha]" id="jform_captcha" value="" required="" aria-required="true">
                            <?php
                            require_once 'captcha_code.php';
                            $clCapcha = new CapCha();
                            $clCapcha->getImgCapcha();
                            ?>       
                        <?php } ?>
                    </div>
                    <div class="jux-col-sm-7">
                        <input type="submit" class="wpb_button  wpb_btn-info wpb_btn-large" id="agent_submit" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_SEND_MESSAGE');?>">
                    </div>
                </div>

                <input name="prop_id" type="hidden" id="agent_property_id" value="">
                <input name="agent_email" type="hidden" id="agent_email" value="michael@wpresidence.net">
                <input type="hidden" name="contact_ajax_nonce" id="agent_property_ajax_nonce" value="d9e30d931d">
                <input type="hidden" name="toemail" id="toemail" value="<?php echo $this->agent->email; ?>"/>
                <input type="hidden" name="toname" id="toname" value="<?php echo $this->agent->username; ?>"/>
                <input type="hidden" name="view_page" id="view_page" value="<?php
                    if (isset($_REQUEST['view'])) {
                        echo $_REQUEST['view'];
                    }
                        ?>"/>
                <input type="hidden" name="id_page" id="id_page" value="<?php
                       if (isset($_REQUEST['id'])) {
                           echo $_REQUEST['id'];
                       }
                       ?>"/>
                <input type="hidden" name="user_id" id="user_id" value="<?php echo JFactory::getUser()->id; ?>"/>
                <input type="hidden" name="receive_id" id="receive_id" value="<?php echo $this->agent->user_id; ?>"/>

                <input type="hidden" name="task" id="task" value="messages.sendmessage"/>
            </form>
        </div>
<?php endif; ?>
</div>
