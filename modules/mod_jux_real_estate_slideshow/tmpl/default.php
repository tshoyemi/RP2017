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
require_once( JPATH_BASE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'route.php');
$autoplay = $params->get("autoplay");
$style = $params->get('custom_css');
$document = JFactory::getDocument();
$document->addStyleDeclaration($style);
?>

    <div id="jux-slider-1" class="jux-slider jux-property-slide-wrap">
        <ul class="sliders clearfix">
            <?php foreach ($realties as $realty): 
                $realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($realty->id.'-'.$realty->alias));
                ?>
                <li class="slide-item jux-property-slide">
                    <img src="<?php echo  $realty->preview_image; ?>" alt="" />
                    <div class="slide-caption">
                        <div class="slide-caption-info">
                            <h3>
                              <?php if($params->get("show_title")==1): ?>
                                <div class="title">
                                    <?php if($params->get('linked_title') == 1):?>
                                        <a href="<?php echo $realtylink; ?>"><?php echo $realty->title; ?></a>
                                    <?php else:?>
                                        <?php echo $realty->title; ?>
                                    <?php endif;?>
                                </div>
                              <?php endif;?>
                              <?php if($params->get("show_address")==1){ ?><small><?php echo $realty->address?></small><?php }?>
                            </h3>
                            <div class="info-summary">
                              <?php if($params->get('show_area')==1){  ?>
                                <div class="size">
                                    <span><?php echo $realty->sqft; ?> sqft</span>
                                </div>
                              <?php } if($params->get("show_baths")==1){ ?>
                                <div class="bathrooms">
                                    <span><?php echo $realty->baths; ?></span>
                                </div>
                              <?php } if($params->get('show_beds')==1){ ?>
                                <div class="bedrooms">
                                    <span><?php echo $realty->beds; ?></span>
                                 </div>
                              <?php } if($params->get("show_price")==1) { ?>
                                <div class="property-price">
                                    <span>
                                      <span class="amount"><?php echo $realty->formattedprice; ?></span>
                                    </span>
                                </div>
                              <?php }?>
                            </div>
                        </div>
                          <div class="slide-caption-action">
                            <?php if($params->get("show_readmore")==1) {?>
                              <a href="<?php echo $realtylink; ?>">More Details</a>
                            <?php }?>
                          </div>
                    </div>
                </li>
            <?php endforeach;?>   
             
        </ul>
        <div class="clearfix"></div>
        <div id="jux-slider-1-pagination" class="slider-indicators indicators-center-bottom"></div>
        <a id="jux-slider-1-prev" class="slider-control prev-btn" role="button" href="#">
          <span class="slider-icon-prev"></span>
        </a>
        <a id="jux-slider-1-next" class="slider-control next-btn" role="button" href="#">
          <span class="slider-icon-next"></span>
        </a>
    </div>

<script type="text/javascript">
    ! function(jQuery) {
  jQuery(document).ready(function() {

    (function() {
      if(jQuery('#jux-slider-1 .sliders').length){
        jQuery('#jux-slider-1 .sliders').carouFredSel({
          infinite: true,
          circular: true,
          responsive: true,
          debug: false,
          scroll: {
            items: 1,
            pauseOnHover: "resume",
            fx: "scroll"
          },

          auto: {
            timeoutDuration: <?php echo $params->get("animation_speed");?>,
            progress: {
              bar: "#jux-slider-1-timer"
            },
            play:<?php if($params->get('autoplay')==1) echo "true"; else echo "false";?>
          },

          pagination: {
            container: "#jux-slider-1-pagination"
          },

          prev: {
            button: "#jux-slider-1-prev"
          },

          next: {
            button: "#jux-slider-1-next"
          },

          swipe: {
            onTouch: true,
            onMouse: true
          }
        });
      }
    })();
    

    });
    }(jQuery);


</script>

