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
$image_url = JURI::base() . $this->item->avatar;
$agentlink = JRoute::_(JUX_Real_EstateHelperRoute::getAgentRealtyRoute($this->item->id . ':' . $this->item->alias));
$image = JUXRealEstateImageHelper::renderImage($this->item->alias, $agentlink, $image_url, $this->params, $this->params->get('image_width', 100), $this->params->get('image_height', 100));
?>
<?php $number = JUX_Real_EstateFactory::countNumberRealty($this->item->id); ?>
<div class="grid-layout jux-col-sm-6  jux-col-md-<?php echo (12 / $this->params->get('grid_column', 4)); ?>">
    <div class="agent-item">
        <div class="jux-col-sm-5 jux-col-md-5 leftcontent">
            <?php if ($this->configs->get('agent_show_image')) { ?>
                <div class="agent-image">
                    <?php
                    if ($this->item->avatar) {
                       echo $image;
                    } else {
                        '<img class="img-responsive" src="' . JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg' . '" />';
                    }
                    ?>

                </div>

            <?php } ?>
        </div>
        <div class="jux-col-xs-12 jux-col-sm-7 jux-col-md-7 rightcontent">
            <div class="agent-wrap">
                <div class="agent-summary">
                    <div class="agent-info">
                        <div><i class="jux-fa jux-fa-phone"></i><?php echo $this->item->phone; ?></div>
                        <div><i class="jux-fa jux-fa-print"></i><?php echo $this->item->fax; ?></div>
                        <div><i class="jux-fa jux-fa-envelope-square"></i><?php echo $this->item->email; ?></div>
                        <div><i class="jux-fa jux-fa-skype"></i><?php echo $this->item->skype; ?></div>
                    </div>
                    <div class="agent-desc">
                        <ul class="social-list agent-social clearfix">
                            <li><a href="<?php echo $this->item->facebook; ?>" title="Facebook" target="_blank"><i class="jux-fa jux-fa-facebook"></i></a></li>
                            <li><a href="<?php echo $this->item->twitter; ?>" title="Twitter" target="_blank"><i class="jux-fa jux-fa-twitter"></i></a></li>
                            <li><a href="<?php echo $this->item->googleplus; ?>" title="Google +" target="_blank"><i class="jux-fa jux-fa-google-plus"></i></a></li>
                            <li><a href="<?php echo $this->item->linkedin; ?>" title="Linkedin" target="_blank"><i class="jux-fa jux-fa-linkedin"></i></a></li>
                            <li><a href="<?php echo $this->item->msn; ?>" title="Pinterest" target="_blank"><i class="jux-fa jux-fa-pinterest"></i></a></li>
                        </ul>
                        <div class="agent-action">
                            <a href="<?php echo $agentlink; ?>"><?php echo JUX_Real_EstateHelperQuery::getAgentName($this->item->id); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>