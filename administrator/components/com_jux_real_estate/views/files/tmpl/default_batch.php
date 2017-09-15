<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JFactory::getDocument()->addScript(JURI::base(). 'components/com_jux_fileseller/models/fields/assets/js/juxoptions.js');
?>
<div class="modal hide fade" id="collapseModal">
	<div class="modal-header">
		<button type="button" role="presentation" class="close" data-dismiss="modal">x</button>
		<h3><?php echo JText::_('COM_JUX_FILESELLER_FILE_BATCH_OPTIONS'); ?></h3>
	</div>
	<div class="modal-body">
		<p><?php echo JText::_('COM_JUX_FILESELLER_FILE_BATCH_TIP'); ?></p>
		<div class="control-group">
			<div class="control-label">
				<label id="batch_limit-lbl" for="batch_limit" class="hasTip" title="">Limit</label>
			</div>
			<div class="controls">
				<script type="text/javascript">
					var js_subfield_limit = "batch_limit_downloads,batch_limit_days";
					window.addEvent('load', function() {
						js_HideOptions(js_subfield_limit);
						js_ShowOptions('');
					});
				</script>
				<fieldset id="batch_limit" class="radio btn-group">
					<input type="radio" id="batch_limit0" name="batch[limit]" value="0" checked="checked" onclick=" js_HideOptions(js_subfield_limit);js_ShowOptions('');">
					<label for="batch_limit0" class="btn">No Limit</label>
					<input type="radio" id="batch_limit1" name="batch[limit]" value="1" onclick=" js_HideOptions(js_subfield_limit);js_ShowOptions('batch_limit_downloads');">
					<label for="batch_limit1" class="btn">Number Downloads</label>
					<input type="radio" id="batch_limit2" name="batch[limit]" value="2" onclick=" js_HideOptions(js_subfield_limit);js_ShowOptions('batch_limit_days');">
					<label for="batch_limit2" class="btn">Number Days</label>
				</fieldset>
			</div>
		</div>
		<div class="control-group ">
			<div class="control-label">
				<label id="batch_limit_downloads-lbl" for="batch_limit_downloads" class="hasTip" title="">Number Downloads</label>
			</div>
			<div class="controls">
				<input type="text" name="batch[limit_downloads]" id="batch_limit_downloads" value="0" size="40" maxlength="255">
			</div>
		</div>
		<div class="control-group hide">
			<div class="control-label">
				<label id="batch_limit_days-lbl" for="batch_limit_days" class="hasTip" title="">Number Days</label>
			</div>
			<div class="controls">
				<input type="text" name="batch[limit_days]" id="batch_limit_days" value="0" size="40" maxlength="255">
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="document.id('batch_limit').value='';document.id('batch_limit_downloads').value='';document.id('batch_limit_days').value='';" data-dismiss="modal">
			<?php echo JText::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('file.batch');">
			<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>
