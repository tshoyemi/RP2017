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
$munits = $this->configs->get('measurement_units') ? JText::_('COM_JUX_REAL_ESTATE_SQM') : JText::_('COM_JUX_REAL_ESTATE_SQFT');
$datediff = date_diff(date_create($this->item->publish_up), date_create($this->current_time));

?>
<div class=" jux-col-xs-12 jux-col-sm-6 jux-col-md-<?php echo (12 / $this->params->get('grid_column', 4)); ?>">
    <div class="grid-style realty-item">
        <div class="realty-image">
            <div class="thumb-holder">
                <div class="bannerleft">
                    <?php if ($this->item->featured): ?>
                        <?php echo JHTML::_('image', JUri::base() . '/components/com_jux_real_estate/assets/images/banners/featured.png', '', '', JText::_('COM_JUX_REAL_ESTATE_FEATURED')); ?>
                    <?php endif;
                    ?>
                </div>
                <?php if ($this->item->sale == 0) : ?>
                    <div class="bannerright">
                        <?php echo JHTML::_('image', JUri::base() . '/components/com_jux_real_estate/assets/images/banners/sold.png', '', '', JText::_('COM_JUX_REAL_ESTATE_SOLD')); ?>
                    </div>
                <?php endif; ?>
                <div class="status">
                    <div class="type"><?php echo JUX_Real_EstateHTML::getTypeTitle($this->item->type_id); ?> - <?php echo JUX_Real_EstateHTML::getCategoryTitle($this->item->cat_id); ?>
                    </div>
                    <?php
                    if ($datediff->format('%a') < $this->configs->new_days):
                        echo '<div class="new">'.JText::_('COM_JUX_REAL_ESTATE_NEW').'</div>';
                    endif;
                    ?>
                </div>
                <div class="view">
                    <?php
                    $link = $this->item->realtylink;
                    $image_url = JURI::base() . $this->item->preview_image;
                    $image = JUXRealEstateImageHelper::renderImage($this->item->title, $link, $image_url, $this->configs, $this->configs->get('image_width', 100), $this->configs->get('image_height', 100));
//                                var_dump($image);die;
                    if ($this->item->preview_image) {
                        echo $image;
                    } else {
                        echo '<a class="agent_link" href=""><img class="img-responsive" src="' . JUri::root() . 'components/com_jux_real_estate/assets/images/no-image.jpg' . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_NO_IMAGE') . '" style="height:200px;" /></a>';
                    }
                    ?>    
                    <div class="mask">
                        <?php echo '<a href="' . JUri::root() . $this->item->preview_image . '" class="info fancybox-effects-a jux-fa jux-fa-search"></a>'; ?>
                    </div>
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
                if ($this->params->get('max_desc_realty') == 0) {
                    $description = substr(strip_tags($this->item->description), 0, $this->configs->get('max_desc')) . ' ...';
                    echo $description;
                } else {
                    $description = substr(strip_tags($this->item->description), 0, $this->params->get('max_desc_realty')) . ' ...';
                    echo $description;
                }
                ?>
            </div>
            <div class="realty-summary">
                <div class="realty-detail">
                    <div class="size"><span><?php echo $this->item->sqft . $munits; ?></span>
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