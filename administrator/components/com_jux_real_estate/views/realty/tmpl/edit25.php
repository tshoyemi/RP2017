<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
JHtml::_('behavior.keepalive');
$configs = JUX_Real_EstateFactory::getConfigs();
$db = JFactory::getDBO();
$nullDate = $db->getNullDate();
$JUXFields = new JUX_Real_EstateFields();
$document = JFactory::getDocument();
?>
<!--<script language="javascript" type="text/javascript">
    
    Joomla.submitbutton = function (task) {
                var form = document.adminForm;
        
        if (task == 'realty.cancel' || document.formvalidator.isValid(document.id('realty-form'))) {
            Joomla.submitform(task, document.getElementById('realty-form'));
        } else {
                        alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
                
<?php echo $JUXFields->renderJSValidation(); ?>
        }
    }

//    //modified at runtime
//    window.addEvent("domready", function () {
//
//        $("realty-form").getElements("td.title_cell").each(function (el) {
//            el.addClass("key").removeClass("title_cell");
//        });
//
//    });

</script>-->

<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task) {
        var form = document.adminForm;
        if (task == 'realty.cancel' || document.formvalidator.isValid(document.id('realty-form'))) {
            Joomla.submitform(task, document.getElementById('realty-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="realty-form" enctype="multipart/form-data" class="form-validate">
    <div class="width-60 fltlft">
        <?php
        echo JHtml::_('tabs.start', 'detail_pane', array('useCookie' => false));
        echo JHtml::_('tabs.panel', JText::_('COM_JUX_REAL_ESTATE_DESCRIPTION'), 'description_panel');
        ?>

        <div class="spacer"></div>
        <div class="width-100">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILS'); ?></legend>
                <ul class="adminformlist">

                    <li><?php echo $this->form->getLabel('title'); ?>
                        <?php echo $this->form->getInput('title'); ?></li>
                    <li><?php echo $this->form->getLabel('alias'); ?>
                        <?php echo $this->form->getInput('alias'); ?></li>
                    <li><?php echo $this->form->getLabel('ordering'); ?>
                        <?php echo $this->form->getInput('ordering'); ?></li>
                    <li><?php echo $this->form->getLabel('company_id'); ?>
                        <?php echo $this->form->getInput('company_id'); ?></li>

                    <li><?php echo $this->form->getLabel('type_id'); ?>
                        <?php echo $this->form->getInput('type_id'); ?></li>
                    <li><?php echo $this->form->getLabel('price'); ?>
                        <fieldset class="radio inputbox">
                            <?php echo $this->form->getInput('price'); ?>  <?php echo $this->form->getInput('currency_id'); ?>
                            <label>&nbsp;<?php echo JText::_('COM_JUX_REAL_ESTATE_PER'); ?>&nbsp;</label>
                            <?php echo $this->form->getInput('price_freq'); ?>

                        </fieldset>
                    </li>
                    <li><?php echo $this->form->getLabel('price2'); ?>
                        <?php echo $this->form->getInput('price2'); ?></li>
                    <li><?php echo $this->form->getLabel('call_for_price'); ?>
                        <?php echo $this->form->getInput('call_for_price'); ?></li>

                    <li>
                        <?php echo $this->form->getLabel('catid'); ?>
                        <?php echo $this->form->getInput('catid'); ?>
                    </li>
                    <li>
                        <?php if (JFactory::getUser()->authorise('core.admin')) : //only show agents if admin user ?>
                            <?php echo $this->form->getLabel('agent_id'); ?>                       
                            <?php echo $this->form->getInput('agent_id'); ?>
                        <?php else: ?>
                            <input type="hidden" name="jform[agent_id][]" value=""/>
                        <?php endif; ?>
                    </li>
                    <li>
                        <?php echo $this->form->getLabel('sub_desc'); ?>
                        <?php echo $this->form->getInput('sub_desc'); ?>
                    </li>
                    <li>
                        <?php echo $this->form->getLabel('description_header'); ?>
                        <div class="clr"></div>
                        <?php echo $this->form->getInput('description'); ?>
                    </li>
                </ul>
                <div class="clr" style="height: 10px;"></div>
            </fieldset>
        </div>

        <?php echo JHtml::_('tabs.panel', JText::_('COM_JUX_REAL_ESTATE_LOCATION'), 'location_panel'); ?>

        <div class="jp_spacer"></div>
        <div class="width-100">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_LOCATION'); ?></legend>
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('hide_address'); ?>
                        <?php echo $this->form->getInput('hide_address'); ?></li>
                    <li><?php echo $this->form->getLabel('street_num'); ?>
                        <?php echo $this->form->getInput('street_num'); ?></li>
                    <li><?php echo $this->form->getLabel('address'); ?>
                        <?php echo $this->form->getInput('address'); ?></li>
                    <li><?php echo $this->form->getLabel('locstate'); ?>
                        <?php echo $this->form->getInput('locstate'); ?></li>
                    <li><?php echo $this->form->getLabel('province'); ?>
                        <?php echo $this->form->getInput('province'); ?></li>
                    <li><?php echo $this->form->getLabel('country_id'); ?>
                        <?php echo $this->form->getInput('country_id'); ?></li>
                    <li><?php echo $this->form->getLabel('region'); ?>
                        <?php echo $this->form->getInput('region'); ?></li>
                    <li><?php echo $this->form->getLabel('city'); ?>
                        <?php echo $this->form->getInput('city'); ?></li>
                </ul>
            </fieldset>
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_GOOGLE_MAPS'); ?></legend>
                <?php echo $this->form->getLabel('geocode_header'); ?>
                <div class="width-40 fltlft">
                    <ul class="adminformlist">
                        <li><?php echo $this->form->getLabel('show_map'); ?>
                            <?php echo $this->form->getInput('show_map'); ?></li>
                        <li><?php echo $this->form->getLabel('latitude'); ?>
                            <?php echo $this->form->getInput('latitude'); ?></li>
                        <li><?php echo $this->form->getLabel('longitude'); ?>
                            <?php echo $this->form->getInput('longitude'); ?></li>
                    </ul>
                </div>
                <div class="width-60 fltrt">
                    <?php echo $this->form->getInput('google_map'); ?>
                </div>
            </fieldset>
        </div>

        <?php echo JHtml::_('tabs.panel', JText::_('COM_JUX_REAL_ESTATE_DETAILS'), 'details_panel'); ?>

        <div class="spacer"></div>
        <div class="width-100">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILS'); ?></legend>
                <div class="width-50 fltlft">
                    <ul class="adminformlist">
                        <li><?php echo $this->form->getLabel('beds'); ?>
                            <?php echo $this->form->getInput('beds'); ?></li>
                        <li><?php echo $this->form->getLabel('baths'); ?>
                            <?php echo $this->form->getInput('baths'); ?></li>
                        <li><?php echo $this->form->getLabel('sqft'); ?>
                            <?php echo $this->form->getInput('sqft'); ?></li>
                        <li><?php echo $this->form->getLabel('lotsize'); ?>
                            <?php echo $this->form->getInput('lotsize'); ?></li>

                    </ul>
                </div>               
            </fieldset>
        </div>

        <?php echo JHtml::_('tabs.panel', JText::_('COM_JUX_REAL_ESTATE_ADDITION_INFORMATION'), 'additions_panel'); ?>

        <div class="spacer"></div>
        <div class="width-100">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_ADDITION_INFORMATION'); ?></legend>
                <table>
                    <?php echo $this->form->getInput('additions'); ?>
                </table>
            </fieldset>
        </div>

        <?php echo JHtml::_('tabs.panel', JText::_('COM_JUX_REAL_ESTATE_AMENITIES'), 'amenities_panel'); ?>

        <div class="spacer"></div>
        <div class="width-100">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILS'); ?></legend>
                <div class="width-30 fltlft" style="margin-right: 20px;">
                    <?php echo $this->form->getLabel('general_amenity_header'); ?>
                    <?php echo $this->form->getInput('general_amenies'); ?>
                </div>
                <div class="width-30 fltlft" style="margin-right: 20px;">
                    <?php echo $this->form->getLabel('interior_amenity_header'); ?>
                    <?php echo $this->form->getInput('interior_amenies'); ?>
                </div>
                <div class="width-30 fltlft">
                    <?php echo $this->form->getLabel('exterior_amenity_header'); ?>
                    <?php echo $this->form->getInput('exterior_amenies'); ?>
                </div>
            </fieldset>
        </div>

        <?php echo JHtml::_('tabs.panel', JText::_('COM_JUX_REAL_ESTATE_IMAGES'), 'images_panel'); ?>

        <div class="spacer"></div>
        <div class="clear"></div>
        <div class="width-100">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_IMAGES'); ?></legend>
                <ul class="adminformlist">
                    <li> <?php echo $this->form->getInput('images'); ?></li>
                </ul>
            </fieldset>
        </div>
        <?php echo JHtml::_('tabs.panel', JText::_('COM_JUX_REAL_ESTATE_VIDEO'), 'video_panel'); ?>

        <div class="jp_spacer"></div>
        <div class="width-100">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_VIDEO'); ?></legend>
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('video'); ?>
                        <?php echo $this->form->getInput('video'); ?></li> 

                </ul>
            </fieldset>
            <li>
                Please insert video link youtube follow define.
                <br> Copy youtube link from URL browse :http://www.youtube.com/watch?v=WPVdiox-Dgo
                <br> Then get link http://www.youtube.com/embed/WPVdiox-Dgo paste to text area
            </li>
        </div>
        <?php
        echo JHtml::_('tabs.end');
        ?>
    </div>
    <!-- right bar -->
    <div class="width-40 fltrt paneldown">
        <?php if (JFactory::getUser()->authorise('core.admin')): ?>
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_DETAILS'); ?></legend>
                <ul class="adminformlist">
                    <li><?php echo $this->form->getLabel('count'); ?>
                        <?php echo $this->form->getInput('count'); ?></li>
                    <li><?php echo $this->form->getLabel('user_id'); ?>
                        <?php echo $this->form->getInput('user_id'); ?></li>
                    <li><?php echo $this->form->getLabel('date_created'); ?>
                        <?php echo $this->form->getInput('date_created'); ?></li>
                    <?php if ($this->item->modified && $this->item->modified != '0000-00-00 00:00:00'): ?>
                        <li><?php echo $this->form->getLabel('modified'); ?>
                            <?php echo $this->form->getInput('modified'); ?></li>
                        <li><?php echo $this->form->getLabel('modified_by'); ?>
                            <?php echo $this->form->getInput('modified_by'); ?></li>
                    <?php endif; ?>
                    <li><?php echo $this->form->getLabel('featured'); ?>
                        <?php echo $this->form->getInput('featured'); ?></li>
                    <li><?php echo $this->form->getLabel('approved'); ?>
                        <?php echo $this->form->getInput('approved'); ?></li>
                    <li><?php echo $this->form->getLabel('sold'); ?>
                        <?php echo $this->form->getInput('sold'); ?></li>
                </ul>
            </fieldset>
        <?php endif; ?>

        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_PUBLISHING'); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('language'); ?>
                    <?php echo $this->form->getInput('language'); ?></li>
                <li><?php echo $this->form->getLabel('access'); ?>
                    <?php echo $this->form->getInput('access'); ?></li>
                <li><?php echo $this->form->getLabel('publish_up'); ?>
                    <?php echo $this->form->getInput('publish_up'); ?></li>
                <li><?php echo $this->form->getLabel('publish_down'); ?>
                    <?php echo $this->form->getInput('publish_down'); ?></li>
                <li><?php echo $this->form->getLabel('published'); ?>
                    <?php echo $this->form->getInput('published'); ?></li>
            </ul>
        </fieldset>
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_META_INFO'); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('keywords'); ?>
                    <?php echo $this->form->getInput('keywords'); ?></li>
                <li><?php echo $this->form->getLabel('meta_keywords'); ?>
                    <?php echo $this->form->getInput('meta_keywords'); ?></li>
                <li><?php echo $this->form->getLabel('meta_desc'); ?>
                    <?php echo $this->form->getInput('meta_desc'); ?></li>
            </ul>
        </fieldset>
    </div>
    <?php echo $this->form->getInput('id'); ?>
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>
