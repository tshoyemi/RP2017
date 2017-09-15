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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

// Create shortcut to parameters.
?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if (task == 'type.cancel' || document.formvalidator.isValid(document.id('type-form'))) {
	    Joomla.submitform(task, document.getElementById('type-form'));
	} else {
	    alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
	}
    }
    function changeImg(img) {
	var link_img = '<?php echo JURI::base(); ?>' + 'components/com_jux_real_estate/assets/icon/';
	document.images["currenticon"].src = link_img + img;
	document.getElementById("jform_icon").value = img;
    }
</script>
<form
    action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="type-form" class="form-validate">
    <div class="row-fluid">
        <!-- Begin Content -->
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_JUX_REAL_ESTATE_TYPE_DETAILS'); ?></a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <fieldset>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
                        </div>

                        <div class="control-group">
                            <div nowrap="nowrap" class="control-label">
				<?php echo JText::_('COM_JUX_REAL_ESTATE_CURRENT_ICON'); ?>
                            </div>

                            <div class="controls">
				<?php if ($this->item->icon) { ?>
    				<img id="currenticon"
    				     src="<?php echo JUri::base() . 'components/com_jux_real_estate/assets/icon/' . $this->item->icon; ?>"
    				     width="50" height="50"/>
				     <?php } else { ?> 
    				<img id="currenticon"
    				     src="<?php echo JUri::base() . 'components/com_jux_real_estate/assets/icon/apartment-3.png'; ?>"
    				     width="50" height="50"/>
				     <?php } ?>
                            </div>

                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <div style="border: 1px solid #f0f0ee">
                                    <div id="listimgaes">
					<?php
					if (count($this->images)) {

					    foreach ($this->images as $image) {
						echo '<li><a href="#" onclick="changeImg(\'' . $image . '\')"><img src="' . JUri::base() . 'components/com_jux_real_estate/assets/icon/' . $image . '" width="32" height="32"/></a></li>';
					    }
					}
					?>
                                    </div>
                                    <br><br/>

				    <?php if ($this->item->icon != null) : ?>
					<?php echo $this->form->getInput('icon', null, $this->item->icon); ?>
				    <?php else: ?>
					<?php echo $this->form->getInput('icon', null, $this->images[0]); ?>
				    <?php endif; ?>
                                    <br/>
                                </div>
                                <div valign="top" style="margin:0;padding-top:0px;" >
				    <?php echo JText::_('COM_JUX_REAL_ESTATE_MORE_ICON'); ?>: <a
                                        href="http://mapicons.nicolasmollet.com/category/markers/offices/real-estate/" target="_blank">http://mapicons.nicolasmollet.com/category/markers/offices/real-estate/</a>
                                </div>
                            </div>

                        </div>
                        <div class="control-group">
                            <div nowrap="nowrap" class="control-label">
				<?php echo $this->form->getLabel('id'); ?>
                            </div>
                            <div class="controls">
				<?php echo $this->form->getInput('id'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
                            <div class="controls"><?php echo $this->form->getInput('description'); ?>
                            </div>
                        </div>

                    </fieldset>

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
                    <div class="controls"><?php echo $this->form->getInput('ordering'); ?></div>
                </div>

                <div class="control-group">
		    <?php echo $this->form->getLabel('access'); ?>
                    <div class="controls"><?php echo $this->form->getInput('access'); ?></div>
                </div>
                <div class="control-group">
		    <?php echo $this->form->getLabel('language'); ?>
                    <div class="controls"><?php echo $this->form->getInput('language'); ?></div>
                </div>
            </fieldset>
        </div>
        <!-- End Sidebar -->
    </div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="controller" value="type"/>
    <?php echo JHTML::_('form.token'); ?>
</form>

<?php echo JUX_Real_EstateFactory::getFooter();