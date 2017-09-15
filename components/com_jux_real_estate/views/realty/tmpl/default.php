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
$munits = $this->configs->get('measurement_units') ? JText::_('COM_JUX_REAL_ESTATE_SQM') : JText::_('COM_JUX_REAL_ESTATE_SQFT');

$document = JFactory::getDocument();
$document->addScript(JUri::base() . 'components/com_jux_real_estate/assets/js/jssor.js');
$document->addScript(JUri::base() . 'components/com_jux_real_estate/assets/js/jssor.slider.js');
$document->addScript(JUri::base() . 'components/com_jux_real_estate/assets/js/jquery-1.9.1.min.js');
$document->addStyleSheet(JUri::base() . 'components/com_jux_real_estate/assets/css/jux-realty.css', 'text/css');
$document->addStyleSheet(JUri::base() . 'components/com_jux_real_estate/assets/css/imageslider.css', 'text/css');

$document->addStyleSheet(JUri::base() . 'components/com_jux_real_estate/assets/css/owl.carousel.css', 'text/css');
$document->addStyleSheet(JUri::base() . 'components/com_jux_real_estate/assets/css/owl.theme.css', 'text/css');
$configs = $this->configs;
$amenities = $this->amenities;
$app = JFactory::getApplication();
$post = JRequest::get('post', JREQUEST_ALLOWHTML);

?>
<script>
    var uri_icon = "<?php echo JUri::base().'administrator/components/com_jux_real_estate/assets/icon/'?>";
    var icon = "<?php
foreach ($this->type as $types) {
    echo $types->icon;
}
?>";
</script>
<?php
if ($this->configs->get('enable_map', 1)) {
    $document->addScript("http://maps.googleapis.com/maps/api/js?libraries=places&amp;key=" . $this->configs->get('gmapapikey') . "&amp;sensor=false");
    $document->addScript(JUri::base() . 'components/com_jux_real_estate/assets/js/realtydetailmap.js');
}
$document->addScript(JUri::base() . 'components/com_jux_real_estate/assets/js/owl.carousel.js');

$date = new DateTime();
$current_time = $date->format('Y-m-d H:i:s');

$agent = JUX_Real_EstateHelperQuery::getAgentInfo($this->item->agent_id);
$agent->link_gent = JUX_Real_EstateHelperRoute::getAgentRealtyRoute($this->item->agent_id);
$files_image = JUX_Real_EstateHelperQuery::getFileImageList($this->item->id);
$uri = JFactory::getURI();
$link_share = $uri->toString();
?>

<script src="<?php echo JUri::root() . 'components/com_jux_real_estate/assets/js/responsiveslides.min.js'; ?>"></script>
<script>
    var sentmail = "<?php echo isset($_SESSION['sentmail']) && $_SESSION['sentmail'] ? $_SESSION['sentmail'] : false; ?>";
    var namemail = "<?php echo JUX_Real_EstateHelperQuery::getAgentName($agent->id); ?>";
    jQuery(document).ready(function ($) {
        $("#realty-images").responsiveSlides({
            auto: true, pager: false, nav: true, speed: 1500, namespace: "callbacks"
        });
        if (sentmail == 'yes') {
            alert('You sent a message to ' + namemail);
        }
        else if (sentmail == 'no') {
            alert('Error sent a message to ' + namemail);
        }
    });
</script>
<?php
if (isset($_SESSION['sentmail'])) {
    unset($_SESSION['sentmail']);
}
?>
<div class="jux-row realty-item-details">
    <div class="realty-title jux-col-xs-12 jux-col-sm-8 jux-col-md-8">
        <h2 class="title">
            <?php echo $this->item->title; ?>
        </h2>
        <h4 class="address">
            <?php echo JText::_($this->item->address); ?>
        </h4>
    </div>
    <div class="jux-col-xs-12 jux-col-sm-4 jux-col-md-4">
        <?php if ($this->configs->get('social_share')) : ?>
            <div class="realty-social clearfix">
                <a href="#share" data-toggle="tooltip" data-placement="bottom" data-trigger="hover" class="jux-social-facebook" title="Share on Facebook" onclick="window.open('http://www.facebook.com/sharer.php?u=<?php echo $link_share; ?>', 'popupFacebook', 'width=650,height=270,resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0');
                        return false;">
                    <i class="jux-fa jux-fa-facebook"></i>
                </a>
                <a href="#share" class="jux-social-twitter" title="Share on Twitter" onclick="window.open('https://twitter.com/intent/tweet?url=<?php echo $link_share; ?>&amp;text=<?php echo $this->item->title; ?>', 'popupTwitter', 'width=500,height=370,resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0');
                        return false;">
                    <i class="jux-fa jux-fa-twitter"></i>
                </a>
                <a href="#share" class="jux-social-googleplus" title="Share on Google+" onclick="window.open('https://plus.google.com/share?url=<?php echo $link_share; ?>', 'popupGooglePlus', 'width=650,height=226,resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0');
                        return false;">
                    <i class="jux-fa jux-fa-google-plus"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <div class=" jux-col-xs-12 jux-col-sm-12 jux-col-md-12 realty-images-slider ">
        <?php echo $this->loadtemplate('imageslider'); ?>
    </div>

    <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12">
        <div class="realty-summary jux-row">
            <div class="property-detail jux-col-xs-12 jux-col-md-4 jux-col-sm-4">
                <h4 class="property-detail-title"><?php echo JText::_('COM_JUX_REALTY_ESTATE_PROPERTY_DETAIL');?></h4>
                <div class="property-detail-content">
                    <div class="detail-field jux-row">
                        <span class="jux-col-md-5 jux-col-sm-5 jux-col-xs-5 detail-field-label"> 
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILREALTY_PRICE'); ?> :
                        </span>
                        <span class="jux-col-md-7 jux-col-sm-7 jux-col-xs-7 detail-field-value"> 
                            <?php echo $this->item->formattedprice; ?> 
                        </span>
                        <span class="jux-col-md-5 jux-col-sm-5 jux-col-xs-5 detail-field-label">
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILREALTY_SALE_TYPE'); ?> :
                        </span>
                        <span class="jux-col-md-7 jux-col-sm-7 jux-col-xs-7 detail-field-value">
                            <?php
                            echo JUX_Real_EstateHTML::getTypeTitle($this->item->type_id);
                            ?>
                        </span>
                        <span class="jux-col-md-5 jux-col-sm-5 jux-col-xs-5 detail-field-label location-label"> 
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILREALTY_CONTRACT'); ?> :
                        </span>
                        <span class="jux-col-md-7 jux-col-sm-7 jux-col-xs-7 detail-field-value location-value">
                            <?php
                            echo JUX_Real_EstateHTML::getCategoryTitle($this->item->cat_id);
                            ?>
                        </span>
                        <span class="jux-col-md-5 jux-col-sm-5 jux-col-xs-5 detail-field-label"> 
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILREALTY_AREA'); ?> :
                        </span>
                        <span class="jux-col-md-7 jux-col-sm-7 jux-col-xs-7 detail-field-value price-value">
                            <?php echo $this->item->sqft.$munits; ?>
                        </span>
                        <span class="jux-col-md-5 jux-col-sm-5 jux-col-xs-5 detail-field-label area-label">
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_BEDS'); ?> : 

                        </span>
                        <span class="jux-col-md-7 jux-col-sm-7 jux-col-xs-7 detail-field-value area-value">
                            <?php echo $this->item->beds; ?> 
                        </span>
                        <span class="jux-col-md-5 jux-col-sm-5 jux-col-xs-5 detail-field-label bedrooms-label">
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_BATHS'); ?> : 
                        </span>
                        <span class="jux-col-md-7 jux-col-sm-7 jux-col-xs-7 detail-field-value bedrooms-value">
                            <?php echo $this->item->baths; ?> 
                        </span>
                        <?php if (isset($this->item->extra_field_new)): ?>
                            <?php foreach ($this->item->extra_field_new as $key => $val): ?>
                                <?php if ($val->field_value): ?>
                                    <span class="jux-col-md-5 jux-col-sm-5 jux-col-xs-5 detail-field-label bathrooms-label">
                                        <?php echo $val->title; ?>:
                                    </span>
                                    <span class="jux-col-md-7 jux-col-sm-7 jux-col-xs-7 detail-field-value lot_area">
                                        <?php echo $val->field_value; ?>
                                    </span> 

                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if ($this->item->description): ?>
                <div class="property-desc jux-col-md-7 jux-col-sm-7 jux-col-xs-12">
                    <h4 class="property-detail-title"><?php echo JText::_('COM_JUX_REALTY_ESTATE_PROPERTY_DESCRIPTION');?></h4>
                </div>
                <?php if (isset($this->item->description)) : ?>
                    <div class="property-content">
                        <p><span> <?php echo $this->item->description; ?> </span></p>
                    <?php endif; ?> 
                </div>
            <?php endif; ?>
        </div>  
    </div>

    <?php if ($amenities) : ?>
        <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12">
            <div class="realty-featured clearfix">
                <h2><span><?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILREALTY_FEATURED'); ?></span></h2>
                <div class="jux-row">
                    <?php
                    $amenities_arr = array(
                        'general' => array(),
                        'interior' => array(),
                        'exterior' => array()
                    );
                    foreach ($amenities as $amen) {
                        switch ($amen->cat) {
                            case 0:
                                $amenities_arr['general'][] = $amen;
                                break;
                            case 1:
                                $amenities_arr['interior'][] = $amen;
                                break;
                            case 2:
                                $amenities_arr['exterior'][] = $amen;
                                break;
                            default:
                                $amenities_arr['general'][] = $amen;
                                break;
                        }
                    }
                    foreach ($amenities_arr as $k => $a) {
                        $amen_n = (count($a));
                        if ($amen_n > 0) {
                            switch ($k) {
                                case 'general':
                                    $amen_label = JText::_('COM_JUX_REAL_ESTATE_GENERAL_AMENITIES');
                                    break;
                                case 'interior':
                                    $amen_label = JText::_('COM_JUX_REAL_ESTATE_INTERIOR_AMENITIES');
                                    break;
                                case 'exterior':
                                    $amen_label = JText::_('COM_JUX_REAL_ESTATE_EXTERIOR_AMENITIES');
                                    break;
                            }
                            $amen_item = '';

                            for ($i = 0; $i < $amen_n; $i++):
                                $amen_item = $amen_item . '<div class="amen-item"><i class="jux-fa jux-fa-check"></i>' . $a[$i]->title . '</div>';
                            endfor;
                            echo '
                <div class="jux-col-xs-6 jux-col-sm-4 jux-col-md-4">
                    <div class="amen-category">
                        <h4 class="title">' . $amen_label . '</h4>
                        <div class="amen-featured-list">
                            ' . $amen_item . '
                        </div>
                    </div>
                </div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

    <?php endif; ?>
    <?php if ($this->item->video): ?>
        <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12">
            <div class="realty-video">
                <h2><span><?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILREALTY_VIDEO'); ?></span></h2>
                <?php echo $this->item->video; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php
    $show_map = $this->configs->get('enable_map', 0);
    if ($show_map):
        ?>
        <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12">
            <div class="realty-map">
                <h2>
                    <?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILREALTY_GOOGLEMAP'); ?>
                </h2>
                <div class="map-direction">
                    <select id="mode" class="" onchange="updateMode()">
                        <option value="driving"><?php echo JText::_('COM_JUX_REALTY_ESTATE_MAP_DRIVING');?></option>
                        <option value="bicycling"><?php echo JText::_('COM_JUX_REALTY_ESTATE_MAP_BICYCLING');?></option>
                        <option value="walking"><?php echo JText::_('COM_JUX_REALTY_ESTATE_MAP_WALKING');?></option>
                    </select>
                    <input class="btn btn-default " type="button" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_MESSAGE_RESET');?>" onclick="reset()" />
                    <input class="btn btn-default " type="button" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_GET_DIRECTION');?>" onclick="calcRoute()" />
                </div>
                <div class="map-gg">
                    <div id="map_canvas"></div>
                    <div id="directionsPanel"></div>
                    <input type="hidden" value="<?php echo $this->item->latitude; ?>" name="jform_latitude" id="jform_latitude"/>
                    <input type="hidden" value="<?php echo $this->item->longitude; ?>" name="jform_longitude" id="jform_longitude"/>
                    <input type="hidden" value="<?php echo $this->item->address; ?>" name="jform_address" id="jform_address"/>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="jux-row  agent-property">
    <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12 agent-profile">
        <div class="agent-property-title">
            <h2><?php echo JText::_('COM_JUX_REAL_ESTATE_CONTACT_AGENT');?></h2> 
        </div>
        <div class="agents grid jux-row clearfix">
            <div class="left-content jux-col-xs-12 jux-col-sm-7 jux-col-md-6">
                <div class="agent-info clearfix">
                    <div class=" jux-col-xs-12 jux-col-sm-12 jux-col-md-5 leftcontent">
                        <div class="agent-image">
                            <?php
                            if ($agent->avatar) {
                                echo '<a class="agent_link" href=""><img class="img-responsive" src="' . JUri::root() . $agent->avatar . '" alt=" " border="0" /></a>';
                            } else {
                                echo '<a class="agent_link" href=""><img class="img-responsive" src="' . JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg' . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_NO_IMAGE') . '" border="0" /></a>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-7 rightcontent">
                        <div class="agent-wrap">
                            <div class="agent-summary">
                                <div class="agent-info">
                                    <div><i class="jux-fa jux-fa-phone"></i><?php echo $agent->phone; ?></div>
                                    <div><i class="jux-fa jux-fa-print"></i><?php echo $agent->fax; ?></div>
                                    <div><i class="jux-fa jux-fa-envelope-square"></i><?php echo $agent->email; ?></div>
                                    <div><i class="jux-fa jux-fa-skype"></i><?php echo $agent->skype; ?></div>
                                </div>
                                <div class="agent-desc">
                                    <ul class="social-list agent-social clearfix">
                                        <li><a href="<?php echo $agent->facebook; ?>" title="Facebook" target="_blank"><i class="jux-fa jux-fa-facebook"></i></a></li>
                                        <li><a href="<?php echo $agent->twitter; ?>" title="Twitter" target="_blank"><i class="jux-fa jux-fa-twitter"></i></a></li>
                                        <li><a href="<?php echo $agent->googleplus; ?>" title="Google +" target="_blank"><i class="jux-fa jux-fa-google-plus"></i></a></li>
                                        <li><a href="<?php echo $agent->linkedin; ?>" title="Linkedin" target="_blank"><i class="jux-fa jux-fa-linkedin"></i></a></li>
                                        <li><a href="<?php echo $agent->msn; ?>" title="Pinterest" target="_blank"><i class="jux-fa jux-fa-pinterest"></i></a></li>
                                    </ul>
                                    <div class="agent-action">
                                        <a href="<?php echo $agent->link_gent; ?>"><?php echo JUX_Real_EstateHelperQuery::getAgentName($agent->id); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if ($this->configs->get('enable_recaptcha')) {
                JUX_Real_EstateTemplate::addScript('ajaxrecaptcha');
            }
            ?>

            <?php
            $realty_id = $this->item->id;
            $actionlink = JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=realty&id=' . $realty_id);
            ?>  
            
            <div class="right-content jux-col-xs-12 jux-col-sm-5 jux-col-md-6">
                <div class="contact-agent">
                    <form role="form" id="conactagentform" method="post">
                        <div class="form-group">
                            <input required  class="form-control" name="name" id="name"  type="text" value="<?php echo $this->user->name; ?>" placeholder="<?php echo  JText::_('COM_JUX_REAL_ESTATE_CONTACT_AGENTS_YOUR_NAME');?>">
                        </div>
                        <div class="form-group">
                            <input required class="form-control" name="email" id="email"  type="email" value="<?php echo $this->user->email; ?>" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_CONTACT_AGENTS_YOUR_EMAIL');?>">
                        </div>
                        <div class="form-group">
                            <textarea required  name="content" class="form-control" id="content" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_YOUR_MESSAGES');?>"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="form-action">
                                <?php if ($this->configs->get('enable_capcha') == 1) { ?>
                                    <input type="text" name="jform[captcha]" id="jform_captcha" value="" required="" aria-required="true">
                                    <?php
                                    require_once 'captcha_code.php';
                                    $clCapcha = new CapCha();
                                    $clCapcha->getImgCapcha();
                                   ?>        
                                <?php } ?>
                                <button type="submit"  class="btn btn-info btn-small" name="Send a Message" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_SEND'); ?>" onClick="sendContact();"><?php echo JText::_('COM_JUX_REALTY_ESTATE_SEND_A_MESSAGE');?></button>
                            </div>    
                        </div>
                        <?php $user = JFactory::getUser(); ?>
                        <input type="hidden" name="option" id="option" value="com_jux_real_estate"/>
                        <input type="hidden" name="task" id="task" value="realty.sendmessage"/>
                        <input type="hidden" name="id_page" id="realty_id" value="<?php echo $this->item->id; ?>"/>
                        <input type="hidden" name="view_page" id="realty" value="<?php
                        if (isset($_REQUEST['view'])) {
                            echo $_REQUEST['view'];
                        }
                        ?>"/>
                        <input type="hidden" name="Itemid_page" id="Itemid_page" value="<?php
                        if (isset($_REQUEST['Itemid'])) {
                            echo $_REQUEST['Itemid'];
                        }
                        ?>"/>
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $this->user->id; ?>"/>
                        <input type="hidden" name="receive_id" id="receive_id" value="<?php echo $this->item->user_id; ?>"/>
                        <input type="hidden" name="toemail" id="toemail" value="<?php echo $agent->email; ?>"/>
                        <input type="hidden" name="toname" id="toname" value="<?php echo JUX_Real_EstateHelperQuery::getAgentName($agent->id); ?>"/>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12 agent-more-properties">
        <?php
        if (count($this->agentRealty)) {
            ?>
            <div class="more-properties-title">
                <h3><?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_MY_PROPERTIES');?></h3> 
            </div>
            <div class="more-properties-items">
                <?php
                foreach ($this->agentRealty as $item):
                    $this->agentItem = $item;
                    $this->configs = $configs;
                    $this->current_time = $current_time;
                    echo $this->loadtemplate('agent_properties');
                endforeach;
                ?>
            </div>
            <?php
        } else {
            echo '<h3>' . JText::_('COM_JUX_REAL_ESTATE_SORRY_WE_DONT_HAVE_ANY_REALTY_FOR_THIS_LIST_YET') . '</h3>';
        }
        ?>
    </div>


</div>
<div class="jux-row">
    <div class="jux-col-xs-12 jux-col-sm-12 jux-col-md-12">
        <?php if ($this->configs->get('show_comment', 1) && $this->configs->get('comment_components', '') == 'disqus') : ?>
            <div class="jux_cause-comment">
                <div id="disqus_thread"></div>
                <script type="text/javascript">
    <?php $disqusSubDomain = str_replace(array('http://', '.disqus.com/', '.disqus.com'), array('', '', ''), $this->configs->get('disqus_shortname')); ?>
                    var disqus_shortname = '<?php echo $disqusSubDomain ?>';
                    var disqus_identifier = '<?php echo md5("_id" . $this->item->id) ?>_<?php echo $this->item->id; ?>';
                        //var disqus_developer = 1; // this would set it to developer mode
                        var disqus_config = function () {
                            this.language = '<?php echo $this->configs->get('disqus_language', 'en') ?>';
                        };
                        (function () {
                            var dsq = document.createElement('script');
                            dsq.type = 'text/javascript';
                            dsq.async = true;
                            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                        })();
                </script>
            </div>
        <?php endif; ?>
    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".more-properties-items").owlCarousel({
            autoPlay: false, //Set AutoPlay to 3 seconds
            items: 3,
            itemsDesktop: [1199, 3],
            itemsDestopSmall: [979, 2],
            itemsTablet: [768, 2],
            itemsMobile: [600, 1],
            navigationText: ["<i class=\"jux-fa jux-fa-angle-left \"></i>", "<i class=\"jux-fa jux-fa-angle-right \"></i>"],
        });
    });

</script>