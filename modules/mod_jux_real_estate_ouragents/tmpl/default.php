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
require_once( JPATH_BASE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'juximage.php');
require_once( JPATH_BASE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'libraries' . '/' . 'factory.php');
require_once( JPATH_BASE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'route.php');
$autoplay = $params->get("autoplay");
$configs = JUX_Real_EstateFactory::getConfiguration();
?>
<div id="jux_real_estate_ouragents<?php echo $module->id; ?>">
    <div id="owl-demo<?php echo $module->id; ?>">
        <?php foreach ($agents as $agent): ?>
            <div class="ouragents-body clearfix">
                <?php if ($params->get('show_image') == 1) { ?>
                    <?php if ($params->get('linked_image') == 1) { ?><a href="#"> <?php } ?>
                        <div class="ouragents-image">
                            <?php if ($agent->avatar): ?>
                                <?php
                                $agentlink = JRoute::_(JUX_Real_EstateHelperRoute::getAgentRealtyRoute($agent->id . '-' . $agent->alias));
                                $image_url = JURI::base() . $agent->avatar;
                                $image = JUXRealEstateImageHelper::renderImage($agent->alias, $agentlink, $image_url, $params, $params->get('image_width', 100), $params->get('image_height', 100));
                                echo $image;
                                ?> 
                            <?php else: ?>
                                <img class="img-responsive" src="<?php echo JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg'; ?>" border="0" />;
                        <?php endif; ?>
                        </div>
                    <?php if ($params->get('linked_image') == 1) { ?></a> <?php } ?>
                    <?php } ?>
                <div class="ouragents-des clearfix">
                        <?php if ($params->get('show_title') == 1) { ?>
                        <h2 class="ouragents-title">
                            <?php if ($params->get('linked_title') == 1) : ?>
                                <a href="<?php echo JRoute::_(JUX_Real_EstateHelperRoute::getAgentRealtyRoute($agent->id . '-' . $agent->alias)); ?>" title="<?php echo $agent->username ?>"><?php echo $agent->username; ?></a>
                            <?php else: ?>
                                <?php echo $agent->username; ?>
                        <?php endif; ?>
                        </h2>
    <?php } if ($params->get("show_des") == 1) { ?>

                        <div class="ouragent-escription">
                            <?php
                            $description = substr(strip_tags($agent->description), 0, $params->get('max_desc', $configs->get('max_desc_agents'))) . ' ...';
                            echo JHTML::_('content.prepare', $description);
                            ?>
                        </div>
    <?php } if ($params->get("show_icon") == 1) { ?>
                        <div class="ouragents-ion">
                            <ul>
                                <li>
                                    <div class="facebook">
                                        <a href="<?php echo $agent->facebook; ?>" target="_blank">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </div>
                                </li>                                   
                                <li>
                                    <div class="twitter">
                                        <a target="_blank" href="<?php echo $agent->twitter; ?>"><i class="fa fa-twitter"></i></a>
                                    </div>
                                </li>                                   
                                <li>
                                    <div class="google">
                                        <a href="<?php echo $agent->googleplus; ?>" target="_blank"><i class="fa fa-google-plus"></i></a>
                                    </div>
                                </li> 
                            </ul> 
                        </div>
    <?php } ?>
                </div>
            </div>

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
        jQuery("#jux_real_estate_ouragents<?php echo $module->id ?> #owl-demo<?php echo $module->id; ?>").owlCarousel({
            autoPlay:<?php
if ($params->get('autoplay') == 0)
    echo 'false';
else {
    echo $params->get('animation_speed');
}
?>,
            items:<?php echo $params->get("count", 3); ?>,
            stopOnHover: <?php if ($params->get('stop0nHover', 'false') == 0)
    echo 'false';
else
    echo 'true';
?>,
            itemsDesktop: [1199, 3],
            itemsDestopSmall: [979, 3],
            navigationText: ["<i class=\"fa fa-angle-left \"></i>", "<i class=\"fa fa-angle-right \"></i>"],
        });
    });
</script>