<?php

/**
 * @version      $Id$
 * @author       JoomlaUX Admin
 * @package      Joomla!
 * @subpackage   JUX Real Estate
 * @copyright    Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Resdivicted access');
require_once (JPATH_SITE . '/'.'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'route.php');

$doc = JFactory::getDocument();
JHtml::_('jquery.framework');
$doc->addScript(JUri::base() . 'modules/mod_jux_realestate_realtysearch/assets/js/jquery.nouislider.all.js');
$doc->addStyleSheet(JUri::base() . 'modules/mod_jux_realestate_realtysearch/assets/css/jquery.nouislider.css');
$doc->addStyleSheet(JUri::base() . 'modules/mod_jux_realestate_realtysearch/assets/css/jux-font-awesome.css');
$doc->addStyleSheet(JUri::base() . 'modules/mod_jux_realestate_realtysearch/assets/css/jux-responsivestyle.css');

?>
<script type="text/javascript">
    function showAdvancedSearch() {
        var element = document.getElementById('advanced_search');
        element.style.display = 'block';
        document.getElementById('advanced_btn').style.display = 'none';
        document.getElementById('standard_btn').style.display = 'block';
    }
    function showStandardSearch() {
        var element = document.getElementById('advanced_search');
        element.style.display = 'none';
        document.getElementById('advanced_btn').style.display = 'block';
        document.getElementById('standard_btn').style.display = 'none';
    }
</script>
<?php
$post = JRequest::get('post');
$link_search = JUX_Real_EstateHelperRoute::getRealtySearchRoute();
$itemIdPage = JUX_Real_EstateHelperRoute::getRealtyItemid();
?>
<script>
    var agent_idagent_id = '';
    var country_id = '';
    var maxprice = '';
    var type_id = '';
    var cat_id = '';
    var beds = '';
    var baths = '';
    var new_min_price = parseInt('<?php echo $new_min_price; ?>');
    var new_max_price = parseInt('<?php echo $new_max_price; ?>');
    var start_price = parseInt('<?php echo $start_price; ?>');
    var end_price = parseInt('<?php echo $end_price; ?>');
    var new_currencies = '<?php echo $new_currencies ?>';

    var new_min_area = parseInt('<?php echo $new_min_area; ?>');
    var new_max_area = parseInt('<?php echo $new_max_area; ?>');
    var start_area = parseInt('<?php echo $start_area; ?>');
    var end_area = parseInt('<?php echo $end_area; ?>');
    agent_id    = '<?php if (isset($post['agent_id'])) echo $post['agent_id']; ?>';
    minprice    = '<?php if (isset($post['minprice'])) echo $post['minprice']; ?>';
    maxprice    = '<?php if (isset($post['maxprice'])) echo $post['maxprice']; ?>';
    type_id     = '<?php if (isset($post['type_id'])) echo $post['type_id']; ?>';
    country_id  = '<?php if (isset($post['country_id'])) echo $post['country_id']; ?>';
    locstate    = '<?php if (isset($post['locstate'])) echo $post['locstate']; ?>';
    cat_id      = '<?php if (isset($post['cat_id'])) echo $post['cat_id']; ?>';
    beds        = '<?php if (isset($post['beds'])) echo $post['beds']; else echo 0;?>';
    baths       = '<?php if (isset($post['baths'])) echo $post['baths']; else echo 0;?>';
    jQuery(document).ready(function($) {
        if (agent_id !== null)
            $("#agent_id").val(agent_id);
        if (minprice !== null)
        if (maxprice !== null)
        if (country_id !== null)
            $("#country_id").val(country_id);
        if (locstate !== null)
            $("#locstate").val(locstate);
        if (beds !== null)
            $("#beds").val(beds);
        if (baths !== null)
            $("#baths").val(baths);

        //price: noUI slider
        jQuery("#price_slider").noUiSlider({
            start: [start_price, end_price],
            connect: true,
            range: {
                'min': new_min_price,
                'max': new_max_price
            }
        });
        jQuery("#price_slider").Link('lower').to(function(value) {
            value = parseInt(value);
            jQuery('.price_slider_lower').html(new_currencies + '' + value);
            jQuery('#price_slider_lower').val(value);
        });
        jQuery("#price_slider").Link('upper').to(function(value) {
            value = parseInt(value);
            jQuery('.price_slider_upper').html(new_currencies + '' + value);
            jQuery('#price_slider_upper').val(value);
        });
        //end price

        //area: noUI slider
        jQuery("#area_slider").noUiSlider({
            start: [start_area, end_area],
            connect: true,
            range: {
                'min': new_min_area,
                'max': new_max_area
            }
        });
        jQuery("#area_slider").Link('lower').to(function(value) {
            value = parseInt(value);
            jQuery('.area_slider_lower').html(value + 'sqft');
            jQuery('#area_slider_lower').val(value);
        });
        jQuery("#area_slider").Link('upper').to(function(value) {
            value = parseInt(value);
            jQuery('.area_slider_upper').html(value + 'sqft');
            jQuery('#area_slider_upper').val(value);
        });
        //end area

    })
</script>
<form id="ZJSearchForm" action="<?php echo JRoute::_($link_search); ?>" method="get" name="ZJSearchForm">
    <input type="hidden" name="option" value="com_jux_real_estate" />
    <input type="hidden" name="view" value="realties" />
    <input type="hidden" name="Itemid" value="<?php echo $itemIdPage;?>" />
    <div class="jux-search-form">
        <div class="keyword-field">
            <input class="keyword" placeholder="Search Keyword" type="text" name="title" id="title" value="<?php if (isset($post['title'])) echo $post['title']; ?>" />
        </div>
        <div class="price-field">
            <div class="title">
                <div class="left-content"><?php echo JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_PRICE'); ?>:</div>
                <div class="right-content"><span class="price_slider_lower"></span> - <span class="price_slider_upper"></span></div>
            </div>
            <div id="price_slider"></div>
            <input type="hidden" name="price_slider_lower" value="" id="price_slider_lower"/>
            <input type="hidden" name="price_slider_upper" value="" id="price_slider_upper"/>
        </div>
        <div class="area-field">
            <div class="title">
                <div class="left-content"><?php echo JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_AREA'); ?>:</div>
                <div class="right-content"><span class="area_slider_lower"></span> - <span class="area_slider_upper"></span></div>
            </div>
            <div id="area_slider"></div>
            <input type="hidden" name="area_slider_lower" value="" id="area_slider_lower"/>
            <input type="hidden" name="area_slider_upper" value="" id="area_slider_upper"/>
        </div>
        <?php if(isset($types_advance) && $types_advance == 0 ):?>
            <div class="type-field">
                <?php echo $type_id; ?>
            </div>
        <?php endif;?>
        
        <?php if(isset($cat_advance) && $cat_advance == 0 ):?>
            <div class="contract-field">
                <?php echo $category; ?>
            </div>
        <?php endif;?>
        
        <?php if(isset($country_advance) && $country_advance == 0 ):?>
            <div class="country-field">
                <?php echo $country_id; ?>
            </div>
        <?php endif;?>
        
        <?php if(isset($states_advance) && $states_advance == 0 ):?>
            <div class="state-field">
                <?php echo $state_id; ?>
            </div>
        <?php endif;?>
        
        <?php if(isset($agent_advance) && $agent_advance == 0 ):?>
            <div class="agent-field">
                <?php echo $mods; ?>
            </div>
        <?php endif;?>
        
        <?php  if(isset($beds_advance) && $beds_advance == 0 ):?>
            <div class="beds-field">
                <select name="beds" id="beds" class="input">
                    <option value="0"><?php echo JText::_('MOD_JUX_REAL_ESTATE_BEDROOMS'); ?></option>
                    <option value="1">1+</option>
                    <option value="2">2+</option>
                    <option value="3">3+</option>
                    <option value="4">4+</option>
                    <option value="5">5+</option>
                </select>
            </div>
        <?php endif;?>
        
        <?php if(isset($baths_advance) && $baths_advance == 0 ):?>
            <div class="baths-field">
                <select name="baths" id="baths" class="input">
                    <option value="0"><?php echo JText::_('MOD_JUX_REAL_ESTATE_BATHROOMS'); ?></option>
                    <option value="1">1+</option>
                    <option value="2">2+</option>
                    <option value="3">3+</option>
                    <option value="4">4+</option>
                    <option value="5">5+</option>
                </select>
            </div>
        <?php endif;?>
        
        <?php if ($advance) { ?>
                <div class="advanced">
                    <?php if ($toggle) { ?>
                        <span id="advanced_btn" onclick="showAdvancedSearch();"><?php echo JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_ADVANCE_SEARCH'); ?></span>
                    <?php } ?>
                </div>
                <div class="advanced_search" id="advanced_search" style="<?php if (!$toggle) { ?>display:block;<?php } else { ?>display:none;<?php } ?>">
                    <?php if(isset($types_advance) && $types_advance == 1 ):?>
                        <div class="type-field">
                            <?php echo $type_id; ?>
                        </div>
                    <?php endif;?>
                    
                    <?php if(isset($cat_advance) && $cat_advance == 1 ):?>
                        <div class="contract-field">
                            <?php echo $category; ?>
                        </div>
                    <?php endif;?>
                    
                    <?php if(isset($country_advance) && $country_advance == 1 ):?>
                        <div class="country-field">
                            <?php echo $country_id; ?>
                        </div>
                    <?php endif;?>
                    
                    <?php if(isset($states_advance) && $states_advance == 1 ):?>
                        <div class="state-field">
                            <?php echo $state_id; ?>
                        </div>
                    <?php endif;?>
                    
                    <?php if(isset($agent_advance) && $agent_advance == 1 ):?>
                        <div class="agent-field">
                            <?php echo $mods; ?>
                        </div>
                    <?php endif;?>
                    
                    <?php if(isset($beds_advance) && $beds_advance == 1 ):?>
                        <div class="beds-field">
                            <select name="beds" id="beds" class="input">
                                <option value="0">No.of Bedrooms</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                                <option value="5">5+</option>
                            </select>
                        </div>
                    <?php endif;?>
                    
                    <?php if(isset($baths_advance) && $baths_advance == 1 ):?>
                        <div class="baths-field">
                            <select name="baths" id="baths" class="input">
                                <option value="0"><?php echo JText::_('MOD_JUX_REAL_ESTATE_BATHROOMS'); ?></option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                                <option value="5">5+</option>
                            </select>
                        </div>
                    <?php endif;?>
                    
                    <?php if(isset($extrafield) && $extrafield != 0):?>
                                <?php echo $field_search;?>
                    <?php endif;?>
                </div>
		<div class="clearfix"></div>
                <?php if ($toggle) { ?>
                    <span id="standard_btn" onclick="showStandardSearch();"><?php echo JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_STANDARD'); ?></span>
                <?php } ?>
        <?php } ?>
        <div>
            <div>
                <input type="submit" class="btn" name="button" id="submitter" value="<?php echo JText::_('MOD_JUX_REALESTATE_REALTYSEARCH_SEARCH'); ?>" />
            </div>
        </div>
    </div>

</form>