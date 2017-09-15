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
$formattedprice = JUX_Real_EstateHTML::getFormattedPriceModule($this->agentItem->price, $this->agentItem->price_freq, $this->agentItem->currency_id, false, $this->agentItem->call_for_price, $this->agentItem->price2);

$datediff = date_diff(date_create($this->agentItem->publish_up), date_create($this->current_time));
$munits = $this->configs->get('measurement_units') ? JText::_('COM_JUX_REAL_ESTATE_SQM') : JText::_('COM_JUX_REAL_ESTATE_SQFT');
?>
<div class="real_slider realty-item clearfix imagein">
    <figure class="tag status">
        <div class="type">
	    <?php echo JUX_Real_EstateHTML::getTypeTitle($this->agentItem->type_id); ?> - <?php echo JUX_Real_EstateHTML::getCategoryTitle($this->item->cat_id) ?>                 
        </div>
	<?php
	if ($datediff->format('%a') < $this->configs->new_days):
	    echo '<div class="new">'.JText::_("COM_JUX_REAL_ESTATE_NEW").'</div>';
	endif;
	?>
    </figure>   
    <div class="banners">
        <div class="bannerleft">
	    <?php if ($this->agentItem->featured): ?>
		<?php echo JHTML::_('image', JUri::base() . '/components/com_jux_real_estate/assets/images/banners/featured.png', '', '', JText::_('COM_JUX_REAL_ESTATE_FEATURED')); ?>
		<?php endif;
	    ?>
        </div>
        <div class="bannerright">
	    <?php
	    if ($this->agentItem->sale == 1):
		echo JHTML::_('image', JUri::base() . '/components/com_jux_real_estate/assets/images/banners/sold.png', '', '', JText::_('COM_JUX_REAL_ESTATE_SOLD'));
	    endif;
	    ?>

        </div>
    </div>
    <div class="real_image">
	 <?php
        $link = $this->agentItem->link;
        $image_url = JURI::base() . $this->agentItem->preview_image;
        $image = JUXRealEstateImageHelper::renderImage($this->agentItem->title, $link, $image_url, $this->configs, $this->configs->get('image_width', 100), $this->configs->get('image_height', 100));

        if ($this->agentItem->preview_image) {
            echo $image;
        } else {
            echo '<a class="agent_link" href=""><img class="img-responsive" src="' . JUri::root() . 'components/com_jux_real_estate/assets/images/no-image.jpg' . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_NO_IMAGE') . '" style="height:200px;" /></a>';
        }
        ?>

    </div>

    <div class="overlay imagein">
        <div class="real_des"> 
            <span class="real_price"><?php echo $formattedprice; ?> </span>
            <h3><a href="<?php echo $this->agentItem->link; ?>"><?php echo $this->agentItem->title; ?></a></h3> 
            <div class="real_address">  <?php echo $this->agentItem->address; ?>   </div>
        </div>
        <div class="property-detail">
            <div class="size">
                <span><?php echo $this->agentItem->sqft.$munits; ?></span>
            </div>
            <div class="bathrooms">
                <span><?php echo $this->agentItem->baths; ?></span>
            </div>
            <div class="bedrooms">
                <span><?php echo $this->agentItem->beds; ?></span>
            </div>
        </div>                   
    </div>

</div>