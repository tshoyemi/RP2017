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
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base(true) . '/components/com_jux_real_estate/assets/css/myproperty.css');

JUX_Real_EstateTemplate::addStyleSheet('simpletabs', 'text/css', 'screen');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
$tab = JRequest::getVar('tab', 'available');
$delete_confirm = JText::_('COM_JUX_REAL_ESTATE_ARE_YOU_READY_WANT_TO_DELETE_THIS_REALTY');
$munits = (!$this->configs->get('measurement_units')) ? JText::_('COM_JUX_REAL_ESTATE_SQFT') : JText::_('COM_JUX_REAL_ESTATE_SQM');
?>

<div class="row">
    <div class="myproperty-left jux-col-sm-4 jux-col-md-4 ">
        <div class="myproperty-left-wrapper">
            <div class="myproperty-left-menu dashboard-sidebar jux-row">
                <div class="myproperty-avatar content-thumb">
		    <?php
		    if ($this->agent->avatar) {
			echo '<img src="' . JUri::root() . $this->agent->avatar . '" alt="' . $this->agent->username . '">';
		    } else {
			echo'<img  src="' . JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg' . '" />';
		    }
		    ?>

                </div>

		<div class="myproperty-link">
		    <?php echo '<a href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&amp;view=agentprofile') . '" class="user-link active">
                        <i class="jux-fa jux-fa-user"></i>'.JText::_('COM_JUX_REAL_ESTATE_AGENT_MY_PROFILE').' </a>'; ?>
		    <?php echo '<a href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&amp;view=myrealty') . '" class="user-link">
                        <i class="jux-fa jux-fa-home"></i>'.Jtext::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_MY_PROPERTIES').' </a>'; ?>
		</div>
		<div class="myproperty-link myproperty-logout">
		    <?php echo' <a href="' . JUX_Real_EstateRoute::_('index.php?option=com_users&amp;view=login') . '"  title="Logout">
                     <i class="jux-fa jux-fa-sign-out"></i>'.JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_LOG_OUT').' </a>'; ?>
		</div>
		<div class="myproperty-submit">
		    <?php echo '<a href="index.php?option=com_jux_real_estate&amp;view=form" class="btn btn-secondary ">
                   '.JText::_('COM_JUX_REAL_ESTATE_SUBMIT_PROPERTY').' </a>' ?>
		</div>
	    </div>
	</div>
        <h3 class="dashboard-sidebar-title"><?php echo JText::_('COM_JUX_REAL_ESTATE_YOUR_CURRENT_PACKAGE');?></h3>
        <div class="myproperty-left-wrapper">
            <div class="myproperty-left-menu dashboard-sidebar">
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

    <div class="jux-col-sm-8 jux-col-md-8">
	<div class="properties list my-properties">
	    <div class="navbar">
		<div class="navbar-inner">
		    <ul class="nav">
			<li <?php if ($tab == '' || $tab == "available") echo 'class="active"'; ?>><a href="<?php echo JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=myrealty&tab=available'); ?>" ><?php echo JText::_('COM_JUX_REAL_ESTATE_AVAILABLE'); ?></a></li>
			<li  <?php if ($tab && $tab == "unavailable") echo 'class="active"'; ?>><a href="<?php echo JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=myrealty&tab=unavailable'); ?>"><?php echo JText::_('COM_JUX_REAL_ESTATE_UNAVAILABLE'); ?></a></li>
			<li <?php if ($tab && $tab == "pending") echo 'class="active"'; ?>><a href="<?php echo JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=myrealty&tab=pending'); ?>" ><?php echo JText::_('COM_JUX_REAL_ESTATE_PENDING'); ?></a></li>
			<li <?php if ($tab && $tab == "unpublished") echo 'class="active"'; ?>><a href="<?php echo JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=myrealty&tab=unpublished'); ?>" ><?php echo JText::_('COM_JUX_REAL_ESTATE_UNPUBLISHED'); ?></a></li>
		    </ul>
		</div>
	    </div> 
	    <div class="properties-header">
		<h1 class="page-title"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_MY_PROPERTIES');?></h1>
	    </div>

	    <?php foreach ($this->items as $item) : ?>
    	    <div class="property-content">
    		<article class="hentry">
    		    <div class="property-featured">
			    <?php if ($item->featured): ?>
				<span class="featured"><i class="jux-fa jux-fa-star"></i></span>
			    <?php endif; ?>
    			<a class="content-thumb" href="">
			    <?php
			    if ($item->preview_image) {
				echo '<img src="' . JUri::root() . $item->preview_image . '" alt=" " />';
			    } else {
				echo '<img src="' . JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg' . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_NO_IMAGE') . '" style="height:200px;" />';
			    }
			    ?>
    			</a>
    			<span class="property-label sold"><?php if (isset($item->text_sale)) {
				echo $item->text_sale;
			    } ?></span>
    			<span class="property-category"><a href="#"><?php echo $cat_string = JUX_Real_EstateHTML::getCategoryName($item->cat_id); ?></a>
    			</span>
    		    </div>
    		    <div class="property-wrap">
    			<h2 class="property-title">
    			    <a href="<?php echo $item->realtylink; ?>" title="The Helux"><?php echo $item->title; ?></a>
    			</h2>
    			<div class="property-labels"></div>

    			<div class="property-excerpt">
    			    <p> <?php echo $description = substr(strip_tags($item->description), 0, 100) . '...'; ?></p>

    			</div>
    			<div class="property-summary">
    			    <div class="property-detail">
    				<div class="size">
    				    <span><?php echo $item->sqft.' '.$munits; ?></span>
    				</div>
    				<div class="bathrooms">
    				    <span><?php echo $item->baths; ?></span>
    				</div>
    				<div class="bedrooms">
    				    <span><?php echo $item->beds; ?></span>
    				</div>
    			    </div>

    			    <div class="property-info">
    				<div class="property-price">
    				    <span><span class="amount"><?php echo $item->price; ?></span> /<?php echo JText::_('COM_JUX_REALTY_ESTATE_MONTH');?></span>
    				</div>
    				<div class="property-action">
    				    <div class="agent-action four-buttons">
					    <?php echo '<a class="edit-property hasTooltip" data-toggle="tooltip" data-original-title="Edit" data-placement="top" href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=form&id=' . $item->id) . '" >
                                <i class="jux-fa jux-fa-pencil"></i></a>'; ?>
    					<a id="status-property<?php echo $item->id; ?>"
					    <?php if ($item->published == 1): ?>
						   onclick="ajaxUpdate('unpublish',<?php echo $item->id; ?>, '#status-property')" 
					       <?php else: ?>
						   onclick="ajaxUpdate('publish',<?php echo $item->id; ?>, '#status-property')" 
					       <?php endif; ?>
    					   class="status-property active hasTooltip" href="#" data-toggle="tooltip" data-placement="top" title="" 
					       <?php if (isset($item->published) && $item->published == 1): ?>
						   data-original-title="Unpublished"
					       <?php else: ?>
						   data-original-title="Published"
					       <?php endif; ?>
    					   >
						   <?php if (isset($item->published) && $item->published == 1): ?>
						    <i class="jux-fa jux-fa-check"></i>
						<?php else: ?>
						    <i class="fa fa-minus"></i>
						<?php endif; ?>
    					</a>
    					<a id="featured-property<?php echo $item->id; ?>"
					    <?php if ($item->sale == 0): ?>
						   onclick="ajaxUpdate('sold',<?php echo $item->id; ?>, '#featured-property')" 
					       <?php else: ?>
						   onclick="ajaxUpdate('unsold',<?php echo $item->id; ?>, '#status-property')" 
					       <?php endif; ?>

    					   class="featured-property active hasTooltip" data-toggle="tooltip" 
    					   data-placement="top" title="" href="#" 
					       <?php if (isset($item->sale) && $item->sale == 0): ?>
						   data-original-title="Sold"
					       <?php else: ?>
						   data-original-title="Sale"
					       <?php endif; ?>
    					   >
    					    <i class="fa fa-shopping-cart"></i>
    					</a>
					    <?php echo '<a class="delete-property hasTooltip" data-toggle="tooltip" data-original-title="Delete" data-placement="top" href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&task=realty.delete&id=' . $item->id . '&tab=' . $tab) . '" onclick="return confirm(\'' . $delete_confirm . '\')">
                                <i class="jux-fa jux-fa-times"></i></a>'; ?>
    				    </div>
    				</div>
    			    </div>
    			</div>
    		    </div>
    		</article>
    	    </div>
	    <?php endforeach ?>
	    <?php if (count($this->items) < $this->pagination->total || 1): ?>
    			<div class="pagination">
				<?php echo $this->pagination->getListFooter(); ?>

    			</div>
	    <?php endif; ?>
	</div>
    </div>
</div>  
<script type="text/javascript">
	jQuery(function() {
	    jQuery('[data-toggle="tooltip"]').tooltip();
	});
	function ajaxUpdate(task, id, name) {
	    var log = jQuery(name + id);
	    var url = '<?php echo JURI::base(); ?>' + 'index.php?option=com_jux_real_estate&amp;task=realty.' + task + '&amp;id=' + id;
	    new Request.HTML({
		method: 'get',
		url: url,
		onRequest: function() {
		    log.empty().addClass('ajax-loading');
		},
		onComplete: function(response) {
		    log.removeClass('ajax-loading');
		    var tsk = response[0].wholeText;
		    var string = null;
		    switch (tsk) {
			case 'reject':
			    log.title = '<?php echo JText::_('COM_JUX_REAL_ESTATE_REJECT'); ?>';
			    string = '<img src="<?php echo'components/com_jux_real_estate/assets/images/tick.png'; ?>" alt="<?php echo JText::_('COM_JUX_REAL_ESTATE_APPROVED'); ?>" />';
			    break;
			case 'approve':
			    log.title = '<?php echo JText::_('COM_JUX_REAL_ESTATE_APPROVE'); ?>';
			    string = '<img src="<?php echo 'components/com_jux_real_estate/assets/images/rejected.png'; ?>" alt="<?php echo JText::_('COM_JUX_REAL_ESTATE_REJECTED'); ?>" />';
			    break;
			case 'publish':
			    log.title = '<?php echo JText::_('COM_JUX_REAL_ESTATE_PUBLISH'); ?>';
			    string = '<img src="<?php echo 'components/com_jux_real_estate/assets/images/publish_x.png'; ?>" alt="<?php echo JText::_('COM_JUX_REAL_ESTATE_UNPUBLISH'); ?>" />';
			    break;
			case 'unpublish':
			    log.title = '<?php echo JText::_('COM_JUX_REAL_ESTATE_UNPUBLISH'); ?>';
			    string = '<img src="<?php echo 'components/com_jux_real_estate/assets/images/tick.png'; ?>" alt="<?php echo JText::_('COM_JUX_REAL_ESTATE_PUBLISHED'); ?>" />';
			    break;
			    // sold
			case 'sold':
			    log.title = '<?php echo JText::_('COM_JUX_REAL_ESTATE_SOLD'); ?>';
			    string = '<img src="<?php echo 'components/com_jux_real_estate/assets/images/available.png'; ?>" alt="<?php echo JText::_('COM_JUX_REAL_ESTATE_UNSOLD'); ?>" />';
			    break;
			case 'unsold':
			    log.title = '<?php echo JText::_('COM_JUX_REAL_ESTATE_UNSOLD'); ?>';
			    string = '<img src="<?php echo 'components/com_jux_real_estate/assets/images/sold2.png'; ?>" alt="<?php echo JText::_('COM_JUX_REAL_ESTATE_SOLD'); ?>" />';
			    break;
		    }
		    log.innerHTML = string;

		    location.reload();
		}
	    }).send();
	}
</script>