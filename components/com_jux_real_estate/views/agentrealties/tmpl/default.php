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
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::base() . 'components/com_jux_real_estate/assets/css/agent-details.css', 'text/css');
$document->addScript(JUri::base() . 'components/com_jux_real_estate/assets/js/jquery-1.9.1.min.js');
$configs = JUX_Real_EstateFactory::getConfiguration();
$date = new DateTime();
$current_time = $date->format('Y-m-d H:i:s');
?>
    <div class="jux-row agent-details">
        <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12">
            <h1 class="content-title agent-name componentheading<?php echo $this->params->get('pageclass_sfx'); ?>">
		<?php echo $this->params->get('page_title'); ?>
		<?php if ($this->configs->get('agentdetail_show_organization')) : ?>
                    <small class="agent-organization"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_REAL_ESTATE_BROKER');?></small>
		<?php endif; ?>
            </h1>
            <div class="agent-social clearfix">
                <a href="<?php echo $this->agent->facebook; ?>">
                    <i class="jux-fa jux-fa-facebook"></i>
                </a> 
                <a href="<?php echo $this->agent->twitter; ?>">
                    <i class="jux-fa jux-fa-twitter"></i>
                </a>
                <a href="<?php echo $this->agent->linkedin; ?>">
                    <i class="jux-fa jux-fa-linkedin"></i>
                </a>    
                <a href="<?php echo $this->agent->msn; ?>">
                    <i class="jux-fa jux-fa-pinterest"></i>
                </a>
            </div>
        </div>
        <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12">
            <div class="jux-row agent-info">
		<?php echo $this->loadtemplate('agent'); ?>
            </div>
        </div>
        <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12">
            <div class="realty-filter">
                <form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post"
		      name="adminForm" id="adminForm">
			  <?php echo $this->lists['type_id'] ?>
			  <?php echo $this->lists['cat_id'] ?>
			  <?php echo $this->lists['filter_order'] ?>
			  <?php echo $this->lists['filter_order_Dir'] ?>

		</form>
	    </div>
	    <div class="realties-listing">
		<div class="jux-row">
		    <?php
		    if (count($this->items)) {
			$total = count($this->items);
			for ($i = 0; $i < $total; $i++) {
			    $this->item = $this->items[$i];
			    $this->current_time = $current_time;
			    $this->configs = $configs;

			    if ($this->state->get('list_style') == 'grid') {
				echo $this->loadtemplate('realtygrid');
			    } else {
				echo $this->loadtemplate('realtylist');
			    }
			}
			?>
                        <div style="text-align: center">
                            <ul class="pagination">
				<?php echo $this->pagination->getPagesLinks(); ?>
                            </ul>
			    <?php if ($this->params->get('show_pagination_results')): ?>
				<p class="counter" style="text-align: left" >
				    <?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			    <?php endif; ?>
                        </div>
			<?php
		    } else {
			echo '<h3>' . JText::_('COM_JUX_REAL_ESTATE_SORRY_WE_DONT_HAVE_ANY_REALTY_FOR_THIS_LIST_YET') . '</h3>';
		    }
		    ?>
		</div>
	    </div>
	</div>
    </div>
