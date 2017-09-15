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

//  var_dump($this->item);
defined('_JEXEC') or die('Restricted access');
$realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($this->item->id . ':' . $this->item->alias));
$munits = (!$this->configs->get('measurement_units')) ? JText::_('COM_JUX_REAL_ESTATE_SQFT') : JText::_('COM_JUX_REAL_ESTATE_SQM');
$datediff = date_diff(date_create($this->item->publish_up), date_create($this->current_time));
?>
<div class=" jux-col-xs-12 jux-col-sm-6 jux-col-md-6">
    <div class="grid-style realty-item">
        <div class="realty-image">
            <div class="thumb-holder">
                <div class="bannerleft">
		    <?php if ($this->item->featured): ?>
			<?php echo JHTML::_('image', JUri::base() . '/components/com_jux_real_estate/assets/images/banners/featured.png', '', '', JText::_('COM_JUX_REAL_ESTATE_FEATURED')); ?>
		    <?php endif;
		    ?>
                </div>
                <div class="bannerright">
		    <?php if ($this->item->sale == 0): ?>
			<?php echo JHTML::_('image', JUri::base() . '/components/com_jux_real_estate/assets/images/banners/sold.png', '', '', JText::_('COM_JUX_REAL_ESTATE_SOLD')); ?>
		    <?php endif; ?>
                </div>
                <div class="status">
                    <div class="type"><?php echo JUX_Real_EstateHTML::getTypeTitle($this->item->type_id); ?> - <?php echo JUX_Real_EstateHTML::getCategoryTitle($this->item->cat_id); ?>
                    </div>
		    <?php
		    if ($datediff->format('%a') < $this->configs->new_days):
			echo '<div class="new">'.JText::_('COM_JUX_REAL_ESTATE_NEW').'</div>';
		    endif;
		    ?>
                </div>

                <div class="image">
		    <?php
		    if ($this->item->preview_image) {
			echo '<a class="agent_link" href="' . $realtylink . '"><img class="img-responsive" src="' . JUri::root() . $this->item->preview_image . '" alt="' . $this->item->alias . ' " /></a>';
		    } else {
			echo '<a class="agent_link" href=""><img class="img-responsive" src="' . JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg' . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_NO_IMAGE') . '" style="height:200px;" /></a>';
		    }
		    ?>

                </div>
            </div>
        </div>
        <div class="realty-wrap">
            <div class="realty-title">
                <a href="<?php echo $this->item->realtylink; ?>"><?php echo $this->item->title; ?></a>
            </div>
            <div class="realty-address">
                <i class="jux-fa jux-fa-map-marker"></i><?php echo $this->item->address; ?>
            </div>
            <div class="realty-desc">
		<?php
		$description = JHtml::_('string.truncate', ($this->item->sub_desc));
		echo JHTML::_('content.prepare', $description);
		?>
            </div>
            <div class="realty-summary">
                <div class="realty-detail">
                    <div class="size"><span><?php echo $this->item->sqft.' '.$munits; ?></span>
                    </div>
                    <div class="bedrooms"><span><?php echo $this->item->beds; ?></span>
                    </div>
                    <div class="bathrooms"><span><?php echo $this->item->baths; ?></span>
                    </div>
                </div>
                <div class="realty-info">
                    <div class="realty-price"><?php echo $this->item->formattedprice; ?>
                    </div>
                    <div class="realty-action"><a href="<?php echo $this->item->realtylink; ?>"><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_MORE_DETAILS');?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($total) && $total == 3) {
    echo '<div style="clear:both;"></div> ';
}