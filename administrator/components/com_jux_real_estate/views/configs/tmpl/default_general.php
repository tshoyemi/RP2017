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
?>
<!-- code 3.0 -->
<div class="tab-content">
    <div id="general">
        <fieldset>
            <div class="span6">
                <div class="adminform">
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_GENERAL'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('auto_approve'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('auto_approve'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('social_share'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('social_share'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('accept_term'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('accept_term'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('article_id'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('article_id'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('date_format'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('date_format'); ?>
                        </div>
                    </div>
		    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('item_per_page'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('item_per_page'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('new_days'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('new_days'); ?>
                        </div>
                    </div>
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_GLOBAL_REALTY_CONFIGURATION'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('measurement_units'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('measurement_units'); ?>
                        </div>
                    </div> 
                            
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_CURRENCY_CONFIGURATION'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('thousand_separator'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('thousand_separator'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('currency_digits'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('currency_digits'); ?>
                        </div>
                    </div>
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_CROP_RESIZE_IMAGE_CONFIGURATION'); ?></legend>
                     <div class="control-group">
                        <div class="control-label">
                <?php echo $this->form->getLabel('thumbnail_mode'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('thumbnail_mode'); ?>
                        </div>
                    </div>
                     <div class="control-group">
                        <div class="control-label">
                <?php echo $this->form->getLabel('use_ratio'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('use_ratio'); ?>
                        </div>
                    </div>
                     <div class="control-group">
                        <div class="control-label">
                <?php echo $this->form->getLabel('image_width'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('image_width'); ?>
                        </div>
                    </div>
                     <div class="control-group">
                        <div class="control-label">
                <?php echo $this->form->getLabel('image_height'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('image_height'); ?>
                        </div>
                    </div>
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_ENABLE_CAPCHA'); ?></legend>
                      <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('enable_capcha'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('enable_capcha'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="span6">
                <div class="adminform">              
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_IMAGES'); ?></legend>                
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('imgs_per_row'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('imgs_per_row'); ?>
                        </div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('images_zise'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('images_zise'); ?>MB</div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('image_exts'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('image_exts'); ?></div>
                    </div>
		    
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_GOOGLE_MAP_CONFIGURATION'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('enable_map'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('enable_map'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label">
			    <?php echo $this->form->getLabel('gmapapikey'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('gmapapikey'); ?></div>
                    </div>
		    
                    <div class="control-group">
			<?php
			    $link = '<a target="_blank" href="https://code.google.com/apis/console">' . JText::_('COM_JUX_REAL_ESTATE_GOOGLE_MAP_HERE') . '</a>';
			    echo JText::sprintf('COM_JUX_REAL_ESTATE_GOOGLE_MAP_LINK', $link);
			?>
                    </div>
		    
                    <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_REALTIES_CONFIGURATION'); ?></legend>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('realties_layout'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('realties_layout'); ?></div>
                    </div>
		    
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('max_desc'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('max_desc'); ?></div>
                    </div>
                     <legend><?php echo JText::_('COM_JUX_REAL_ESTATE_COMMENT_CONFIGURATION'); ?></legend>
                     <div class="control-group">
                        <div class="control-label">
                <?php echo $this->form->getLabel('comment_components'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('comment_components'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                <?php echo $this->form->getLabel('disqus_api'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('disqus_api'); ?>
                        </div>
                    </div> 
                    <div class="control-group">
                        <div class="control-label">
                <?php echo $this->form->getLabel('disqus_shortname'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('disqus_shortname'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                <?php echo $this->form->getLabel('disqus_language'); ?>
                        </div>
                        <div class="controls"><?php echo $this->form->getInput('disqus_language'); ?>
                        </div>
                    </div>       
                </div>
            </div>
        </fieldset>
    </div>
</div>