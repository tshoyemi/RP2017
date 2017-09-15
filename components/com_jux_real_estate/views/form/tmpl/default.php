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

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base(true) . '/components/com_jux_real_estate/assets/css/submitproperty.css');
jimport('joomla.html.html');
jimport('joomla.form.formfield');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');

if (JVERSION >= '3.0.0') {
    JHtml::_('bootstrap.framework');
    JHtml::_('bootstrap.loadCss');
    JHtml::_('bootstrap.tooltip');
}

$id = (int) JRequest::getVar('id', 0);

$db = JFactory::getDBO();
$nullDate = $db->getNullDate();
$document = JFactory::getDocument();

$this->params = JComponentHelper::getParams('com_jux_real_estate');

if ($this->config->get('enable_recaptcha')) {
    JUX_Real_EstateTemplate::addScript('ajaxrecaptcha');
}
$user = JFactory::getUser();
$isroot = $user->authorise('core.admin');
?>
<?php
if ($this->params->get('show_page_title', 1)) {
    ?>
    <h3 class="componentheading<?php echo $this->params->get('pageclass_sfx'); ?>">
	<?php echo $this->params->get('page_title'); ?>
    </h3>
    <?php
}
?>
<script type="text/javascript">
     var id = "<?php if(isset($id)) echo $id; ?>";
     var sm_limit = "<?php if(isset($this->agent->plan_countlimit)) echo $this->agent->plan_countlimit; ?>";
     var sm_count = "<?php if(isset($this->agent->count)) echo $this->agent->count; ?>";
    jQuery(document).ready(function(){
       
    Joomla.submitbutton = function(task) {
        if(id==0){
        if(sm_limit !== 0 && sm_count >= sm_limit ){
            r = window.confirm("You cant submit property , please buy new package");
            if(r==true){
                window.location="index.php?option=com_jux_real_estate&view=agentplan";
            }
        }      
    }
    else{
         Joomla.submitform(task);
    }
   
	if (document.formvalidator.isValid(document.id('adminForm'))) {
<?php echo $this->form->getField('description')->save(); ?>
	    var form = document.adminForm;
<?php if ($this->config->get('enable_recaptcha')) : ?>

    	    if (xmlHttp) {
    		try {
    		    var ch = form.recaptcha_challenge_field.value;
    		    var re = form.recaptcha_response_field.value;
    		    var private_key = '<?php echo $this->config->get('private_key'); ?>';
    		    var url = "<?php echo JUri::base(); ?>components/com_jux_real_estate/libraries/recaptchavalid.php";
    		    xmlHttp.open("POST", url, true);
    		    xmlHttp.onreadystatechange = stateChanged;
    		    xmlHttp.setRequestHeader("content-type", "application/x-www-form-urlencoded");
    		    xmlHttp.send('recaptcha_challenge_field=' + ch + '&recaptcha_response_field=' + re + '&private_key=' + private_key);
    		}
    		catch (e) {
    		    alert("Can't connect to server:\n" + e.toString());
    		}
    	    }
<?php else : ?>
    	    Joomla.submitform(task);
<?php endif; ?>

	}
        }
    });
</script>
<script type="text/javascript">
    var accept_term = "<?php echo $this->config->accept_term ? $this->config->accept_term : ''; ?>";
    jQuery(document).ready(function() {
	jQuery('.formelm-buttons .btn').click(function() {
	    if (accept_term == 1) {
		var check = jQuery('[name="accept_term"]').is(':checked');
		if (check == false) {
		    alert('You have to agree to Term and Condition');
		    return false;
		}
	    }
	});
    });
</script>
<?php if (JVERSION >= '3.0.0') { ?>
    <div class="row">
        <div class="submitproperty-left jux-col-sm-4 jux-col-md-4 ">
            <div class="submitproperty-left-wrapper">
                <div class="submitproperty-left-menu dashboard-sidebar jux-row">
                    <div class="submitproperty-avatar content-thumb">
                            <?php
                            if ($this->agent->avatar) {
                                echo '<img src="' . JUri::root() . $this->agent->avatar . '" alt="' . $this->agent->username . '">';
                            } else {
                                echo'<img  src="' . JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg' . '" />';
                            }
                            ?>
                    </div>

                    <div class="submitproperty-link">
                            <?php echo '<a href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=agentprofile') . '" class="user-link active">
                         <i class="jux-fa jux-fa-user"></i>'.JText::_('COM_JUX_REAL_ESTATE_AGENT_MY_PROFILE').' </a>'; ?>
                            <?php echo '<a href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=myrealty') . '" class="user-link">
                         <i class="jux-fa jux-fa-home"></i>'.JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_MY_PROPERTIES').' </a>'; ?>
                    </div>
                    <div class="submitproperty-link submitproperty-logout">
                            <?php echo' <a href="' . JUX_Real_EstateRoute::_('index.php?option=com_users&view=login') . '"  title="Logout">
                        <i class="jux-fa jux-fa-sign-out"></i>'.JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_LOG_OUT').' </a>'; ?>
                    </div>
                    <div class="submitproperty-submit">
                        <a href="" class="btn btn-secondary ">
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_SUBMIT_PROPERTY');?> </a>
                    </div>
                </div>
            </div>
            <h3 class="dashboard-sidebar-title"><?php echo JText::_('COM_JUX_REAL_ESTATE_YOUR_CURRENT_PACKAGE');?></h3>
            <div class="submitproperty-left-wrapper">
                <div class="submitproperty-left-menu dashboard-sidebar">
                    <div class="sidebar-content">
                       <p> <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_PLAN');?>
                        <?php
                        echo $this->agent->plan;
                        ?>
                    </p>
                    <p> <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_DAYS_LEFT');?>
                        <?php
                       if ($this->agent->days) {
				echo $this->agent->sub_days;
			    } else {
				echo JText::_('COM_JUX_REAL_ESTATE_NEVER');
			    }
                        ?>
                    </p>
                    <p> <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_LIMIT');?>
                        <?php if ($this->agent->plan_countlimit)
				echo $this->agent->plan_countlimit;
			    else
				echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED'); ?>
                    </p>
                    <p> <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_COUNT');?>
                        <?php echo $this->agent->count; ?>
                    </p>
                    <p> <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_REMAIN');?>
                        <?php
                        if ($this->agent->plan_countlimit && ($this->agent->plan_countlimit > $this->agent->count))
                        echo $this->agent->plan_countlimit - $this->agent->count;
                         elseif($this->agent->plan_countlimit==0)
				echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED');
                         else 
                             echo JText::_('COM_JUX_REAL_ESTATE_NEVERSUBMIT')
                        ?>
                    </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="jux-col-sm-8 jux-col-md-8 submitproperty">
    	<h1 class="page-title"> <?php echo JText::_('COM_JUX_REAL_ESTATE_SUBMIT_PROPERTY'); ?></h1>
    	<form method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    	    <div class="submitproperty-content">
    		<div class="submitproperty-group">
    		    <div class="group-title">
			    <?php echo JText::_('COM_JUX_REAL_ESTATE_SUBMITFORM_PROPERTY_DESCRIPTION_PRICE'); ?>
    		    </div>
    		    <div class="group-container jux-row">
    			<div class="jux-col-sm-6 jux-col-md-6">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('title'); ?> </div>
    				<div class="controls"><?php echo$this->form->getInput('title'); ?></div>
    			    </div>
    			</div>
    			<div class="jux-col-sm-6 jux-col-md-6">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('agent_id'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('agent_id'); ?></div>
    			    </div>
    			</div>
    			<div class="jux-col-sm-6 jux-col-md-6">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('price2'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('price2'); ?></div>
    			    </div>
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('call_for_price'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('call_for_price'); ?></div>
    			    </div>
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('price'); ?></div>
    				<div class="controls">
					<?php echo $this->form->getInput('price'); ?>
					<?php echo $this->form->getInput('currency_id'); ?>
					<?php echo JText::_('COM_JUX_REAL_ESTATE_PER'); ?>
					<?php echo $this->form->getInput('price_freq'); ?>
    				</div>
    			    </div>
    			</div>

    			<div class="jux-col-sm-6 jux-col-md-6">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('cat_id'); ?> </div>
    				<div class="controls"> <?php echo $this->form->getInput('cat_id'); ?></div>
    			    </div>
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('type_id'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('type_id'); ?></div>
    			    </div>

    			</div>
    			<div class="jux-col-sm-3 jux-col-md-3">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('sale'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('sale'); ?></div>
    			    </div>

				<?php if ($isroot) { ?>
				    <div class="control-group">
					<div class="control-label">
					    <?php echo $this->form->getLabel('featured'); ?></div>
					<div class="controls">  
					    <?php echo $this->form->getInput('featured'); ?>
					</div>
				    </div>
				<?php } ?>
    			</div>
			    <?php if ($isroot) { ?>
				<div class="jux-col-sm-3 jux-col-md-3">
				    <div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('published'); ?></div>
					<div class="controls">  <?php echo $this->form->getInput('published'); ?></div>
				    </div>
				    <?php if ($this->permissions->getAdmin() || ($this->item->id && (($this->item->agent_id == $isAgentPost) && !($this->item->user_id == $this->user->get('id'))))) : ?>
	    			    <div class="control-group">
	    				<div class="control-label"><?php echo $this->form->getLabel('approved'); ?></div>
	    				<div class="controls"> 
						<?php
						if ($this->permissions->canApproveRealty($this->item->id)) {
						    echo $this->form->getInput('approved');
						}
						?>
	    				</div>
	    			    </div> 
				    <?php endif; ?>   
				</div>
			    <?php } ?>
    			<div class="jux-col-sm-12 jux-col-md-12">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('sub_desc'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('sub_desc'); ?></div>
    			    </div>
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
    			    </div>
    			</div>
    		    </div>
    		</div>
    		<div class="submitproperty-group">
    		    <div class="group-title">
			    <?php echo JText::_('COM_JUX_REAL_ESTATE_SUBMIT_PROPERTY_IMAGES'); ?> </div>
    		    <div class="group-container jux-row">
    			<div class="jux-col-sm-6 jux-col-md-6">
    			    <h3>  <?php echo JText::_('COM_JUX_REAL_ESTATE_SUBMIT_PROPERTY_PREVIEW_IMAGES'); ?>  </h3>
    			    <div class="control-group">
    				<div class="controls">
					<?php echo $this->form->getInput('preview_image'); ?>
    				</div>
    			    </div>
    			</div>
    			<div class="jux-col-sm-12 jux-col-md-12">
    			    <h3>  <?php echo JText::_('COM_JUX_REAL_ESTATE_SUBMIT_PROPERTY_SLIDER_IMAGES'); ?>  </h3>

    			    <div class="control-group">
				    <?php
				    echo $this->loadtemplate('image_multiupload_new2');
				    ?>
    			    </div>

    			</div>
    		    </div>
    		</div>
    		<div class="submitproperty-group">
    		    <div class="group-title">
			    <?php echo JText::_('COM_JUX_REAL_ESTATE_SUBMIT_PROPERTY_ADDITIONAL_INFO'); ?>
    		    </div>
    		    <div class="group-container jux-row">
    			<div class="jux-col-sm-6 jux-col-md-6">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('beds'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('beds'); ?></div>
    			    </div>
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('baths'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('baths'); ?></div>
    			    </div>
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('sqft'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('sqft'); ?></div>
    			    </div>
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('lotsize'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('lotsize'); ?></div>
    			    </div>      
    			</div>
    			<div class="jux-col-sm-6 jux-col-md-6">
    			    <div class="control-group">
    				<div class="controls">
					<?php echo $this->form->getInput('additions'); ?>
    				</div>
    			    </div>
    			</div>

    		    </div>
    		</div>
    		<div class="submitproperty-group">
    		    <div class="group-title">
			    <?php echo JText::_('COM_JUX_REAL_ESTATE_SUBMIT_PROPERTY_LISTING_LOCATION'); ?>
    		    </div>
    		    <div class="group-container jux-row">
    			<div class="jux-col-sm-4 jux-col-md-4">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('hide_address'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('hide_address'); ?></div>
    			    </div>
    			</div>
    			<div class="jux-col-sm-4 jux-col-md-4">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('country_id'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('country_id'); ?></div>
    			    </div> 
    			</div>
    			<div class="jux-col-sm-4 jux-col-md-4">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('locstate'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('locstate'); ?></div>
    			    </div>
    			</div>
    			<div class="jux-col-md-12">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('address'); ?></div>
    			    </div> 
    			</div>
    			
			    <?php if ($this->config->get('enable_map')) { ?>
				<fieldset>

				    <div>
					<div class="jux-col-sm-4 jux-col-md-4">
					    <div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('latitude'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('latitude'); ?></div>
					    </div>
					</div>
					<div class="jux-col-sm-4 jux-col-md-4">
					    <div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('longitude'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('longitude'); ?></div>
					    </div>
					</div>
					<div class="jux-col-sm-4 jux-col-md-4">
					    <div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('show_map'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('show_map'); ?></div>
					    </div>
					</div>
				    </div>

				    <div class="jux-col-xs-12">
					<div class="control-group">
					    <?php echo $this->form->getInput('google_map'); ?>
					</div>
				    </div>
				</fieldset>
			    <?php } ?>
    		    </div>       
    		</div>
    		<div class="submitproperty-group">
    		    <div class="group-title">
			    <?php echo JText::_('COM_JUX_REAL_ESTATE_SUBMITFORM_VIDEO_AMENITIES'); ?>
    		    </div>
    		    <div class="group-container jux-row">
    			<div class="jux-col-xs-12">
    			    <div class="control-group">
    				<div class="control-label"><?php echo $this->form->getLabel('video'); ?></div>
    				<div class="controls"><?php echo $this->form->getInput('video'); ?></div>
    			    </div>
    			</div>
    			<div class="jux-col-xs-12"> 
    			    <?php echo JText::_('COM_JUX_REAL_ESTATE_FORM_PLEASE_INSERT_IFRAME_FOR_VIDEO');?>                                
    			</div>
    			<fieldset class="adminform">
    			    <div class="jux-col-sm-4 jux-col-md-4">
				    <?php echo $this->form->getLabel('general_amenity_header'); ?>
				    <?php echo $this->form->getInput('general_amenies'); ?>  
    			    </div>
    			    <div class="jux-col-sm-4 jux-col-md-4">
				    <?php echo $this->form->getLabel('exterior_amenity_header'); ?>
				    <?php echo $this->form->getInput('exterior_amenies'); ?>
    			    </div>
    			    <div class="jux-col-sm-4 jux-col-md-4">
				    <?php echo $this->form->getLabel('interior_amenity_header'); ?>
				    <?php echo $this->form->getInput('interior_amenies'); ?>
    			    </div>
    			</fieldset>   
    		    </div>
    		</div>

    		<div class="submitproperty-group">
			<?php
			if ($this->permissions->getAdmin()) {
			    ?>
			    <div class="group-title">
				<?php echo JText::_('COM_JUX_REAL_ESTATE_SUBMIT_PROPERTY_PUBLISHING_METADATA'); ?> 
			    </div>
			    <div class="group-container jux-row">
				<fieldset class="adminform">  
				    <div class="jux-col-sm-6 jux-col-md-6">
					<div class="control-group">
					    <div class="control-label"><?php echo $this->form->getLabel('publish_up'); ?></div>
					    <div class="controls"><?php echo $this->form->getInput('publish_up'); ?></div>
					</div>
				    </div>
				    <div class="jux-col-sm-6 jux-col-md-6">
					<div class="control-group">
					    <div class="control-label"><?php echo $this->form->getLabel('publish_down'); ?></div>
					    <div class="controls"><?php echo $this->form->getInput('publish_down'); ?></div>
					</div>
				    </div>
				</fieldset>
				<fieldset class="adminform">
				    <div class="jux-col-sm-6 jux-col-md-6"> 
					<div class="control-group">
					    <div class="control-label"><?php echo $this->form->getLabel('keywords'); ?></div>
					    <div class="controls"><?php echo $this->form->getInput('keywords'); ?></div>
					</div>
					<div class="control-group">
					    <div class="control-label"><?php echo $this->form->getLabel('meta_keywords'); ?></div>
					    <div class="controls"><?php echo $this->form->getInput('meta_keywords'); ?></div>
					</div>
				    </div>
				    <div class="jux-col-sm-6 jux-col-md-6"> 
					<div class="control-group">
					    <div class="control-label"><?php echo $this->form->getLabel('meta_desc'); ?></div>
					    <div class="controls"><?php echo $this->form->getInput('meta_desc'); ?></div>
					</div>
				    </div>
				</fieldset>
			    </div>
			<?php } ?>
    		</div>
    		<div class="submitproperty-group">
    		    <div class="group-title">
    			<?php echo JText::_('COM_JUX_REAL_ESTATE_TERMS_AND_CONDITION');?>
    		    </div>
    		    <div class="group-container jux-row">
    			<div class="jux-col-md-12">
				<?php if ($this->config->get('accept_term')) : ?>

				    <fieldset class="acceptTerms">
					<input type="checkbox" class="checkbox required" name="accept_term">
					<?php echo JText::_('COM_JUX_REAL_ESTATE_I_AGREE_TO'); ?>&nbsp;
					<a href="index.php?option=com_content&amp;view=article&amp;id=<?php echo $this->config->get('article_id') ?>&amp;tmpl=component"
					   class="modal">
					       <?php echo JText::_('COM_JUX_REAL_ESTATE_TERMS_AND_CONDITION'); ?>
					</a>
				    </fieldset>
				    <br/>
				</div>
			    <?php endif; ?>
    			<div class="jux-col-md-12">
    			    <fieldset class="submitbutton">
    				<div class="formelm-buttons">
    				    <button class="btn" type="button" onclick="Joomla.submitbutton('realty.apply')">
					    <?php echo JText::_('Apply') ?>
    				    </button> 
    				    <button class="btn" type="button" onclick="Joomla.submitbutton('realty.save')">

					    <?php echo JText::_('Save & Close') ?>
    				    </button>
    				    <button class="btn" type="button" onclick="goBack();">
					    <?php echo JText::_('JCANCEL') ?>
    				    </button>
    				</div>
    			    </fieldset>

    			</div>
			    <?php if ($this->config->get('auto_approve')) { ?>
				<input type="hidden" name="jform[approved]" id="jform_approved" value="1"/>
			    <?php } elseif ($this->item->id && $this->item->approved == -1 && !$this->isAgentPost) { ?>
				<input type="hidden" name="jform[approved]" id="jform_approved" value="1"/>
				<?php
				echo $this->form->getInput('agent_id_old', null, $this->item->agent_id);
				?>
			    <?php } ?>
			    <?php
			    if ($this->isAgentPost) {
				echo $this->form->getInput('isagent', null, $this->isAgentPost);
			    }
			    ?>
			    <?php echo $this->form->getInput('id', null, $this->item->id); ?>
    			<input type="hidden" name="task" value="realty.save"/>

    			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
			    <?php echo JHTML::_('form.token'); ?>

    		    </div>
    		</div>
    	    </div>
    	</form>
        </div>
    </div>

<?php } ?>