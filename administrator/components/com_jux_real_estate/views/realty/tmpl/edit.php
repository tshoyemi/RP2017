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
jimport('joomla.html.html');
jimport('joomla.form.formfield');
require_once JPATH_COMPONENT . '/models/fields/juxfields.php';
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHTML::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$id = (int) JRequest::getVar('id', 0);
$configs = JUX_Real_EstateFactory::getConfigs();
$db = JFactory::getDBO();
$nullDate = $db->getNullDate();
$JUXFields = new JUX_Real_EstateFields();
$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/tmpl.js');
$params = JComponentHelper::getParams('com_jux_real_estate');
require_once JPATH_COMPONENT . '/helpers/route.php';
?>

<script language="javascript" type="text/javascript">
    var form = document.adminForm;
    Joomla.submitbutton = function(task) {
	if (task == 'realty.cancel' || document.formvalidator.isValid(document.id('realty-form'))) {
	    Joomla.submitform(task, document.getElementById('realty-form'));
	} else {
	    <?php                                                                                                             ?>
	}
    }
    //modified at runtime
    window.addEvent("domready", function() {
	$("realty-form").getElements("td.title_cell").each(function(el) {
	    el.addClass("key").removeClass("title_cell");
	});
    });
</script>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="realty-form" enctype="multipart/form-data" class="form-validate">
    <!-- code 3.0 -->
    <div class="row-fluid">
        <!-- Begin Content -->
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_GENERAL'); ?></a></li>
                <li><a href="#location" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_LOCATION'); ?></a></li>
                <li><a href="#details" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_DETAILS'); ?></a></li>
                <li><a href="#amenities" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_AMENITIES'); ?></a></li>
                <li><a href="#images" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_GALLERY'); ?></a></li>
                <li><a href="#video" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_VIDEO'); ?></a></li>
                <li><a href="#publishing" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_PUBLISHING_OPTION'); ?></a></li>
                <li><a href="#meta" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_META_DATA'); ?></a></li>
            </ul>
            <!-- Menu tab  end -->
            <div class="tab-content">
                <div class="tab-pane active" id="general">
		    <?php echo $this->loadTemplate('general'); ?>
                </div>
                <div class="tab-pane" id="location">
		    <?php echo $this->loadTemplate('location'); ?>
                </div>
                <div class="tab-pane" id="details">
		    <?php echo $this->loadTemplate('details'); ?>
                </div>
                <div class="tab-pane" id="amenities">
		    <?php echo $this->loadTemplate('amenities'); ?>
                </div>
                <div class="tab-pane" id="images">
		    <?php echo $this->loadTemplate('gallery'); ?>
                </div>
                <div class="tab-pane" id="video">
		    <?php echo $this->loadTemplate('video'); ?>
                </div>
                <div class="tab-pane" id="publishing">
		    <?php echo $this->loadTemplate('publishing'); ?>
                </div>
                <div class="tab-pane" id="meta">
		    <?php echo $this->loadTemplate('meta'); ?>
                </div>
            </div>
        </div>
        <!-- End Content -->
	
        <!-- Begin Sidebar -->
        <div class="span2">
            <h4><?php echo JText::_('JDETAILS'); ?></h4>
            <hr />
            <fieldset class="form-vertical">
                <div class="control-group">
                    <div class="controls"><?php echo $this->form->getValue('title'); ?></div>
                </div>
		
                <div class="control-group">
		    <?php echo $this->form->getLabel('published'); ?>
                    <div class="controls"><?php echo $this->form->getInput('published'); ?></div>
                </div>

                <div class="control-group">
		    <?php echo $this->form->getLabel('ordering'); ?>
		    <?php echo $this->form->getInput('ordering'); ?>
                </div>
		
                <div class="control-group">
		    <?php echo $this->form->getLabel('access'); ?>
                    <div class="controls"><?php echo $this->form->getInput('access'); ?></div>
                </div>
		
                <div class="control-group">
		    <?php echo $this->form->getLabel('approved'); ?>
                    <div class="controls"><?php echo $this->form->getInput('approved'); ?></div>
                </div>
		
                <div class="control-group">
		    <?php echo $this->form->getLabel('language'); ?>
                    <div class="controls"><?php echo $this->form->getInput('language'); ?></div>
                </div>
            </fieldset>
        </div>
        <!-- End Sidebar -->
    </div>
    <?php echo $this->form->getInput('id'); ?>
    <input type="hidden" name="task" value="realty.upload"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

<?php echo JUX_Real_EstateFactory::getFooter(); ?>

<style>
    #jform_sub_desc.textarea{
        width: 98%;
    }
</style>