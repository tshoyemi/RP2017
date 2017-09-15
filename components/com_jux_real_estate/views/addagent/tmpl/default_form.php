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

?>

<div class="jux-row addagent">
    <div class="addagent-left jux-col-sm-5 jux-col-md-4">
        <h3 class="dashboard-sidebar-title"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PLAN');?></h3>
        <div class="addagent-left-wrapper">
            <div class="addagent-left-menu dashboard-sidebar">
                <div class="sidebar-content">
                    <div class="plan-package plan-table ascending threecol">
                        <div class="plan-column">
                            <div class="plan-content">
                                <div class="plan-header">
                                    <h2 class="plan-title"><?php echo $this->plan->name; ?></h2>
                                    <h3 class="plan-value">
                                        <span class="noo-price">
                                            <?php
                                            if ($this->plan->price == '0.00')
                                                echo JText::_('COM_JUX_REAL_ESTATE_FREE');
                                            else {
                                                echo JUX_Real_EstateUtils::formatPrice($this->plan->price, $this->plan->currency_id, $this->config->get('thousand_separator'));
                                            }
                                            ?>
                                        </span> /<?php echo JText::_('COM_JUX_REALTY_ESTATE_MONTH');?></h3>
                                </div>
                                <div class="plan-info">
                                    <p class="text-center">
                                        <?php echo JText::_('COM_JUX_REALTY_ESTATE_PROPERTY_SUBMIT_LIMIT'); ?> :
                                        <?php if (!empty($this->plan->count_limit)) { ?>
                                            <?php echo $this->plan->count_limit; ?>
                                        <?php } else { ?>
                                            <?php echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED'); ?>
                                        <?php } ?>
                                    </p>
                                    <p class="text-center"><?php echo JText::_('COM_JUX_REAL_ESTATE_OPTION_PROPERTIES_FEATURED_LBL');?>: <?php echo $this->plan->featured; ?></p>
                                    <p class="text-center"><?php echo JText::_('COM_JUX_REAL_ESTATE_DAYS');?>: 
                                        <?php
                                        if (!empty($this->plan->days)) {
                                            if ($this->plan->days_type == 'day') {
                                                echo $this->plan->days . ' ' . JText::_('COM_JUX_REAL_ESTATE_DAYS');
                                            } else {
                                                echo $this->plan->days . ' ' . JText::_('COM_JUX_REAL_ESTATE_MONTHS');
                                            }
                                        } else {
                                            echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED');
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="plan-footer">
                                    <form class="subscription_post">
                                        <div class="form-message"></div>
                                        <a class="btn" type="submit" href="<?php //echo $link;   ?>" ><?php echo JText::_('COM_JUX_REAL_ESTATE_SIGN_UP'); ?></a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
        <h3 class="dashboard-sidebar-title"><?php echo JText::_('COM_JUX_REAL_ESTATE_PAYMENT_METHOD');?></h3>
        <div class="addagent-left-wrapper">
            <div class="addagent-left-menu dashboard-sidebar">
                <div class="sidebar-content">
                    <div id="agent-payment">
                        <fieldset class="payment-information">
                            <table cellspacing="5" cellpadding="5" border="0" width="100%">
                                <tr>
                                    <td>
                                        <?php echo $this->form->getInput('payment_method'); ?>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
    if(!$this->user->get('id')):?>
            <div id="jse_user-login" class=" jux-col-md-8 addagent-login">
                <div class="jux-row">
                     <div class="jux-col-md-6" >
                        <form action="" method="post" id="adminForm" name="adminForm">
                            <div class="login-heading">
                                <h3><?php echo JText::_('COM_JUX_REAL_ESTATE_PLEASE_LOGIN_TO_BUY_OUR_PRODUCTS');?></h3>
                            </div>
                           
                            <div class="username">
                                <label for="lg_username"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_USERNAME');?> <span>*</span></label>
                                <input id="lg_username" type="text" name="lg_username" value=""/>
                            </div>
                            <div class="password">
                                <label for="lg_password"><?php echo JText::_('COM_JUX_REAL_ESTATE_PASSWORD');?> <span>*</span></label>
                                <input id="lg_password" type="password" name="lg_password" value=""/>
                            </div>
                            <input type="hidden" name="option" value="<?php if(isset($_GET['option'])){echo $_GET['option'];} ?>" />
                            <input type="hidden" name="view" value="<?php if(isset($_GET['view'])){echo $_GET['view'];} ?>" />
                            <input type="hidden" name="plan_id" value="<?php if(isset($_GET['plan_id'])){echo $_GET['plan_id'];} ?>" />
                            <input type="hidden" name="Itemid" value="<?php if(isset($_GET['Itemid'])){echo $_GET['Itemid'];} ?>" />
                            <?php echo JHtml::_('form.token'); ?>
                            <input type="submit" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_LOGIN');?>" />
                            <input type="hidden" name="task" value="agent.lg_addagent" />
                        </form>
                    </div>     
                    <div class="jux-col-md-6">
                        <div class="yet-account">
                            <div class="register-heading">
                                <h3><?php echo JText::_('COM_JUX_REAL_ESTATE_DONT_HAVE_AN_ACCOUNT');?></h3>
                            </div>
                          <a id="toggle-billing-form" href="javascript:void(0);" class="jse-btn-m toggle-billing-form"> <?php echo JText::_('COM_JUX_REAL_ESTATE_PURCHASE_NOW');?></a> <?php echo JText::_('COM_JUX_REAL_ESTATE_OR');?> 
                            <span> <a href="<?php echo JUX_Real_EstateRoute::_('index.php?option=com_users&view=registration'); ?>"><?php echo JText::_('COM_JUX_REAL_ESTATE_GO_TO_REGISTER_FORM');?></a></span>
                        </div>
                    </div>     
                </div>
            </div>    
    <?php endif;?>
    <div id="jse_purchase-form" class="jux-col-sm-7 jux-col-md-8 agentprofile-right <?php if(!$this->user->get('id')){echo 'purchase-hide';}?>">
        <fieldset class="agent-information">
            <div>
                <h1 class="agentprofile-title a-infomation"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_INFORMATION');?></h1>
                <?php if(!$this->user->get('id')):?>
                    <div class="close-btn ">
			<a class="delete" href="javascript:void(0);" title=""></a>
		</div>
                <?php endif;?>
            </div>
            <div class="clearfix"></div>
            <div class="agentprofile-content">
                <div class="agentprofile-group>">
                    <div class="group-container jux-row">
                        <?php if($this->user->id == 0): ?>
                            <div class="jux-col-xs-6 jux-col-sm-6 jux-col-md-6">
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('username'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('username', null, isset($this->post['username']) ? $this->post['username'] : ''); ?></div>
                                </div>
                            </div>
                        <?php endif;?>
                        <div class="jux-col-xs-6 jux-col-sm-6 jux-col-md-6">
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('phone'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('phone', null, isset($this->post['phone']) ? $this->post['phone'] : ''); ?></div>
                            </div>
                        </div>
                        <?php if($this->user->id == 0): ?>
                            <div class="jux-col-xs-6 jux-col-sm-6 jux-col-md-6">
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('password'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('password', null, isset($this->post['password']) ? $this->post['password'] : ''); ?></div>
                                </div>
                            </div>
                            <div class="jux-col-xs-6 jux-col-sm-6 jux-col-md-6">
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('confirm_password'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('confirm_password', null, isset($this->post['confirm_password']) ? $this->post['confirm_password'] : ''); ?></div>
                                </div>
                            </div>
                        <?php endif;?>
                        <div class="jux-col-xs-6 jux-col-sm-6 jux-col-md-6">
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('email', null, isset($this->post['email']) ? $this->post['email'] : $this->user->get('email')); ?></div>
                            </div>
                        </div>
                        <div class="jux-col-xs-6 jux-col-sm-6 jux-col-md-6">
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('email_confirm'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('email_confirm', null, isset($this->post['email_confirm']) ? $this->post['email_confirm'] : $this->user->get('email')); ?></div>
                            </div>
                        </div>
                        <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12" id="sh_moredetail">
                            <h3 class="show_hide" href="javascript:void(0);" title="" attr-text="1"><?php echo JText::_('COM_JUX_REAL_ESTATE_ADD_MORE');?></h3>
                        </div>
                        <div class="jux-col-xs-6 jux-col-sm-6 jux-col-md-6 more-info">
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('organization'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('organization', null, isset($this->post['organization']) ? $this->post['organization'] : ''); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('address', null, isset($this->post['address']) ? $this->post['address'] : ''); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('country_id'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('country_id'); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('locstate'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('locstate'); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('mobile'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('mobile', null, isset($this->post['mobile']) ? $this->post['mobile'] : ''); ?></div>
                            </div>    
                        </div>  
                        <div class="jux-col-xs-6 jux-col-sm-6 jux-col-md-6 more-info">
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('sub_desc'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('sub_desc'); ?></div>
                            </div>
                            <div class="control-group">
                                <div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
                                <div class="controls"><?php echo $this->form->getInput('description'); ?>
                                </div>
                               
                            </div>   
                        </div>
                    </div>
                    <div  id="socialnetwork" class="agentprofile-group more-info">
                        <h3 class="agentprofile-title" href="javascript:void(0);"> <?php echo JText::_('COM_JUX_REAL_ESTATE_SOCIAL_NETWORK');?></h3>
                      
                        <div   class="group-container jux-row">
                          
                            <div id="social" class=" jux-col-xs-6 jux-col-sm-6 jux-col-md-6">
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('website'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('website', null, isset($post['website']) ? $post['website'] : ''); ?></div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('msn'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('msn', null, isset($post['msn']) ? $post['msn'] : ''); ?></div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('skype'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('skype', null, isset($post['skype']) ? $post['skype'] : ''); ?></div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('twitter'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('twitter', null, isset($this->post['twitter']) ? $this->post['twitter'] : ''); ?></div>
                                </div>
                            </div>
                            <div class="jux-col-xs-6 jux-col-sm-6 jux-col-md-6">
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('googleplus'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('googleplus', null, isset($post['gtalk']) ? $post['gtalk'] : ''); ?></div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('linkedin'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('linkedin', null, isset($post['linkedin']) ? $post['linkedin'] : ''); ?></div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label"><?php echo $this->form->getLabel('facebook'); ?></div>
                                    <div class="controls"><?php echo $this->form->getInput('facebook', null, isset($post['facebook']) ? $post['facebook'] : ''); ?></div>
                                </div>
                            </div>                 
                        </div>
                       
                    </div> 
                    <div class="agentprofile-group">
                        <div class="group-container jux-row">
                            <div class="jux-col-md-12">
                                    <p>
                                        <input class="btn validate" type="submit" value =" <?php echo JText::_('COM_JUX_REAL_ESTATE_REGISTER_NOW'); ?> "/>
                                    </p>
                                    <input type="hidden" name="plan" value="<?php echo $this->plan->id ?>"/>
                                    <input type="hidden" name="option" value="com_jux_real_estate"/>
                                    <input type="hidden" name="task" value="agent.confirm_agent"/>
                                    <?php echo $this->form->getInput('id'); ?>
                                    <input type="hidden" name="plan_price" id="plan_price" value="" />
                                    <?php echo JHTML::_('form.token'); ?>
                                </div>
                        </div>
                    </div>       
                </div>
        </fieldset>
    </div>

</div>
<script>
        jQuery(document).ready(function(){
            document.formvalidator.setHandler('passverify', function (value) {
                return (jQuery('#password').val() == value); 
            });
            document.formvalidator.setHandler('emailverify', function (value) {
                return (jQuery('#email').val() == value); 
            });
        });
        
        var register_unsuccess = "<?php if(isset($_SESSION['register_unsuccess'])){echo $_SESSION['register_unsuccess']; }?>";
        jQuery(document).ready(function(){
                if(register_unsuccess){
                        jQuery("#jse_user-login").slideUp();
                        jQuery("#jse_purchase-form").slideDown(500);
                        <?php unset($_SESSION['register_unsuccess']);?>
                }
        });
	if(token === undefined)
		var token = '<?php echo JSession::getFormToken(); ?>';

	if(typeof(hasSubmitted) === 'undefined')
		var hasSubmitted = false;

	hasSubmitted = <?php echo isset($post['hassubmitted']) && !empty($post['hassubmitted']) ? 'true' : 'false'; ?>;
        
	(function($)
	{
		$(document).ready(function() {
			if(hasSubmitted) {
				$("#jse_purchase-form").removeClass('none');
				$("#jse_user-login").addClass('none');
			}

			$("#toggle-billing-form").click(function () {
				$("#jse_user-login").slideUp();
				$("#jse_purchase-form").slideDown(500);
			});

			$("#jse_purchase-form .close-btn a").click(function () {
				$("#jse_purchase-form").slideUp();
				$("#jse_user-login").slideDown(500);
			});
                        
                        $("#sh_moredetail .show_hide").click(function () {
				$(".more-info").toggle(700);
                                if($(".show_hide").attr('attr-text') == 1){
                                    $(".show_hide").attr('attr-text',2);
                                    $(".show_hide").html('<?php echo JText::_('COM_JUX_REAL_ESTATE_HIDE');?>');
                                } else {
                                    $(".show_hide").attr('attr-text',1);
                                    $(".show_hide").html('<?php echo JText::_('COM_JUX_REAL_ESTATE_ADD_MORE');?>');
                                }
			});
		});
	})(jQuery);
</script>
