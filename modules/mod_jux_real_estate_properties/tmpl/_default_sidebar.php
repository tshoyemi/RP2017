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
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_jux_real_estate_properties/assets/css/sidebar.css');

require_once( JPATH_BASE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'route.php');
?>
<div id="jux_real_estate_properties<?php echo $module->id; ?>" class="jux-real-estate-properties">

    <div class="sidebar-layout">
        <?php foreach ($realties as $realty): ?>
            <?php
            $description = substr(strip_tags($realty->sub_desc), 0, $params->get('description_max_chars', 200)) . '...';
            $realtylink = JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($realty->id . '-' . $realty->alias));
            $catelink = JRoute::_(JUX_Real_EstateHelperRoute::getCategoryRoute($realty->cat_id));
            ?>
            <div class="mod-properties realty-item clearfix">                
                <div class="realty-image">
                    <div class="image">
                        <?php
                        if ($realty->preview_image) {
                            echo '<img class="img-responsive" src="' . JUri::root() . $realty->preview_image . '" alt=" ' . JText::_('COM_JUX_REAL_ESTATE_IMG') . '" border="0" />';
                        } else {
                            echo '<img class="img-responsive" src="' . JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg' . '" alt="' . JText::_('COM_JUX_REAL_ESTATE_NO_IMAGE') . '" border="0" />';
                        }
                        ?>      
                    </div>
                </div>

                <div class="realty-wrap">
                    <div class="realty-type">
                        <div class="status">
                            <?php echo $realty->cat_title; ?>
                        </div>
                    </div> 
                    <div class="realty-title">
                        <a href="<?php echo $realtylink; ?>"><?php echo $realty->title; ?></a>
                    </div>

                </div>
            </div>

        <?php endforeach; ?>   
    </div>

</div>
