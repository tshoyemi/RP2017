<?php
/**
 * @version   $Id$
 * @author    JoomlaUX Admin
 * @package   Joomla!
 * @subpackage  JUX Real Estate
 * @copyright Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
require_once(JPATH_BASE  . '/'. 'components' . '/' . 'com_jux_real_estate' . '/' .'views'. '/'.'realties'.'/'.'tmpl'.'/'. 'imageresize.php');
require_once( JPATH_BASE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'route.php');
require_once( JPATH_BASE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'libraries' . '/' . 'factory.php');
$autoplay = $params->get("autoplay");
$configs = JUX_Real_EstateFactory::getConfiguration();
$max_height_image = $configs->get('max_height_image');
$max_width_image = $configs->get('max_width_image');
$date = new DateTime();
$current_time = $date->format('Y-m-d H:i:s');
$munits = $configs->measurement_units ? JText::_('MOD_JUX_REAL_ESTATE_SQM') : JText::_('MOD_JUX_REAL_ESTATE_SQFT');

?>
<div id="jux_real_estate_properties<?php echo $module->id; ?>">

    <div id="owl-demo">
        <?php foreach ($realties as $realty): ?>
            <?php
            $datediff = date_diff(date_create($realty->publish_up), date_create($current_time));
            $description = substr(strip_tags($realty->sub_desc), 0, $params->get('description_max_chars', 300)) . '...';
            $realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($realty->id));
            $catelink = JRoute::_(JUX_Real_EstateHelperRoute::getCategoryRoute($realty->cat_id));

            ?>
            <?php if ($params->get('text_image') == 'imagein') { ?>    
                <div class="real_slider realty-item clearfix imagein cot<?php echo $params->get('count') ?>">
                    <figure class="tag status">
                        <div class="type">
                            <?php echo JUX_Real_EstateHelperQuery::getType($realty->type_id); ?> - <?php echo $realty->cat_title; ?>                    
                        </div>
                        <?php
                        if ($datediff->format('%a') < $configs->new_days):
                            echo '<div class="new">NEW</div>';
                        endif;
                        ?>
                    </figure>               
                    <div class="banners">
                        <div class="bannerleft">
                            <?php if ($realty->featured): ?>
                                <?php echo JHTML::_('image', JUri::base() . '/components/com_jux_real_estate/assets/images/banners/featured.png', '', '', JText::_('COM_JUX_REAL_ESTATE_FEATURED')); ?>
                            <?php endif;
                            ?>
                        </div>
                        <div class="bannerright">
                            <?php
                            if ($realty->sale == 0):
                                echo JHTML::_('image', JUri::base() . '/components/com_jux_real_estate/assets/images/banners/sold.png', '', '', JText::_('COM_JUX_REAL_ESTATE_SOLD'));
                            endif;
                            ?>

                        </div>
                    </div>
                    <div class="real_image">
                        <?php if ($realty->preview_image): ?>
                            <?php
                            
                            $img = $realty->preview_image;
                            $thumb_path = basename($img);
                            if ($configs->get('crop_resize') == 1) {
								$imageThumb = new Imageresize($img);
                                $imageThumb->createThumb($thumb_path, $max_width_image, $max_height_image, "fit");
                                echo '<img class="img-responsive" src="' . JUri::root() . 'images/jux_real_estate/previews_img_crop/' . $thumb_path . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_IMG') . ' " border="0" />';
                            } else {
                                echo '<img class="img-responsive" src="' . JUri::root() . $realty->preview_image . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_IMG') . '" border="0" /></a>';
                            }
                            ?>
                        <?php else: ?>
                            <img class="img-responsive" src="<?php echo JUri::root(); ?>/components/com_jux_real_estate/assets/images/no-image.jpg" alt="<?php JText::_('COM_JUX_REAL_ESTATE_NO_IMAGE'); ?>" border="0" />
                        <?php endif; ?>
                    </div>

                    <div class="overlay imagein">
                        <div class="real_des"> 
                            <?php if ($params->get('show_price') == 1) { ?> <span  class="real_price">  <?php echo $realty->formattedprice; ?> </span><?php } ?>
                            <?php if ($params->get('show_title') == 1) { ?>  <h3><a href="<?php echo $realtylink; ?>"><?php echo $realty->title; ?></a></h3><?php } ?>
                            <?php if ($params->get('show_address') == 1) { ?>  <div class="real_address">  <?php echo $realty->address; ?>   </div> <?php } ?>
                        </div>
                        <div class="property-detail">
                            <div class="size">
                                <?php if ($params->get('show_area') == 1) { ?> <span >  <?php echo $realty->sqft . $munits; ?> </span><?php } ?>
                            </div>
                            <div class="bathrooms">
                                <?php if ($params->get('show_baths') == 1) { ?> <span >  <?php echo $realty->baths; ?> </span><?php } ?>
                            </div>
                            <div class="bedrooms">
                                <?php if ($params->get('show_beds') == 1) { ?> <span >  <?php echo $realty->baths; ?> </span><?php } ?>
                            </div>
                        </div>                   
                    </div>

                </div>

            <?php } else { ?>
                <div class="mod-properties realty-item clearfix imageon cot<?php echo $params->get('count') ?>">                
                    <div class="realty-image">
                        <div class="thumb-holder">
                            <div class="bannerleft">
                                <?php if ($realty->featured): ?>
                                    <?php echo JHTML::_('image', JUri::base() . '/components/com_jux_real_estate/assets/images/banners/featured.png', '', '', JText::_('COM_JUX_REAL_ESTATE_FEATURED')); ?>
                                <?php endif;
                                ?>
                            </div>
                            <div class="bannerright">
                                <?php
                                if ($realty->sale == 0):
                                    echo JHTML::_('image', JUri::base() . '/components/com_jux_real_estate/assets/images/banners/sold.png', '', '', JText::_('COM_JUX_REAL_ESTATE_SOLD'));
                                endif;
                                ?>

                            </div>
                            <div class="status">
                                <div class="type"><?php echo JUX_Real_EstateHelperQuery::getType($realty->type_id); ?> - <?php echo $realty->cat_title; ?>
                                </div>
                                <?php
                                if ($datediff->format('%a') < $configs->new_days):
                                    echo '<div class="new">NEW</div>';
                                endif;
                                ?>
                            </div>
                            <div class="image">
                                <?php if ($realty->preview_image): ?>
                                    <?php
                                    $img = $realty->preview_image;
                                    $thumb_path = basename($img);
                                    if ($configs->get('crop_resize') == 1) {
										$imageThumb = new Imageresize($img);
                                        $imageThumb->createThumb($thumb_path, $max_width_image, $max_height_image, "fit");
                                        echo '<img class="img-responsive" src="' . JUri::root() . 'images/jux_real_estate/previews_img_crop/' . $thumb_path . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_IMG') . ' " border="0" />';
                                    } else {
                                        echo '<img class="img-responsive" src="' . JUri::root() . $realty->preview_image . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_IMG') . '" border="0" /></a>';
                                    }
                                    ?>
                                <?php else: ?>
                                    <img class="img-responsive" src="<?php echo JUri::root(); ?>/components/com_jux_real_estate/assets/images/no-image.jpg" alt="<?php JText::_('COM_JUX_REAL_ESTATE_NO_IMAGE'); ?>" border="0" />
                                <?php endif; ?> 
                            </div>
                        </div>
                    </div>

                    <div class="realty-wrap">
                        <div class="realty-title">
                            <?php if ($params->get('show_title') == 1) { ?>  <a href="<?php echo $realtylink; ?>"><?php echo $realty->title; ?></a><?php } ?>
                        </div>
                        <div class="realty-address">
                            <?php if ($params->get('show_address') == 1) { ?>   <i class="jux-fa jux-fa-map-marker"></i><?php echo $realty->address; ?><?php } ?>
                        </div>
                        <div class="realty-desc">
                            <?php
                            $description = substr(strip_tags($realty->description), 0, $params->get('max_desc', $params->get('max_desc_agents'))) . ' ...';
                            echo JHTML::_('content.prepare', $description);
                            ?>
                        </div>
                        <div class="realty-summary">
                            <div class="realty-detail">
                                <div class="size"> <?php if ($params->get('show_area') == 1) { ?> <span >  <?php echo $realty->sqft . $munits; ?> </span><?php } ?>
                                </div>
                                <div class="bedrooms"><?php if ($params->get('show_beds') == 1) { ?> <span >  <?php echo $realty->beds; ?> </span><?php } ?>
                                </div>
                                <div class="bathrooms"><?php if ($params->get('show_baths') == 1) { ?> <span >  <?php echo $realty->baths; ?> </span><?php } ?>
                                </div>
                            </div>
                            <div class="realty-info">
                                <div class="realty-price"><?php if ($params->get('show_price') == 1) { ?> <?php echo $realty->formattedprice; ?><?php } ?>

                                </div>
                                <div class="realty-action">
                                    <?php if ($params->get('show_readmore') == 1) { ?> <a href="<?php echo $realtylink; ?>">More Details</a><?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

        <?php endforeach; ?>   
    </div>

    <div class="owl-controls clickable">
        <div class="owl-pagination">
            <div class="owl-page" style="color:red;">
                <span class=""></span>
            </div>
            <div class="owl-page active">
                <span class=""></span>
            </div>
        </div>
    </div>   
</div>


<script type="text/javascript">
    jQuery(window).load(function () {
        jQuery("#jux_real_estate_properties<?php echo $module->id ?> #owl-demo").owlCarousel({
            autoPlay:<?php
        if ($params->get('autoplay') == 0)
            echo 'false';
        else {
            echo $params->get('animation_speed');
        }
        ?>,
            items:<?php echo $params->get("count"); ?>,
            stopOnHover: <?php
        if ($params->get('stop0nHover', 'false') == 0)
            echo 'false';
        else
            echo 'true';
        ?>,
            itemsDesktop: [1199, 3],
            itemsDestopSmall: [979, 2],
            itemsTablet: [768, 2],
            itemsMobile: [600, 1],
            navigationText: ["<i class=\"jux-fa jux-fa-angle-left \"></i>", "<i class=\"jux-fa jux-fa-angle-right \"></i>"],
        });
    });

</script>
<!-- JS image height -->
<script type="text/javascript">
    jQuery(window).load(function () {
        // jQuery('.realty-item').matchHeight();
    });
</script>