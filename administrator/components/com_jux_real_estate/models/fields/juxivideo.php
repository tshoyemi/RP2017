<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
JHtml::_('behavior.modal');

class JFormFieldJUXImage extends JFormField
{
	protected $type = 'JUXVideo';

	protected function getInput()
    {
        $user       = JFactory::getUser();
        $document   = JFactory::getDocument();
        $folder     = $this->element['folder'];

        // Build image select js and load the view
        $img_path   = JURI::root(true).'/images/jux_real_estate/'.$folder.'/';
        $img_upload = $folder.'img';
        $img_select = 'select'.$folder.'img';
        // Add js function to switch icon image
		$js = "
            function jp_SwitchImage(image) {
                $('live_image').value = image;
                $('image_name').value = image;
                $('imagepreview').src = '".$img_path."' + image;
                window.parent.SqueezeBox.close();
            }";
        $document->addScriptDeclaration($js);

		$upload_link = 'index.php?option=com_jux_real_estate&amp;view=imageuploader&amp;layout=uploadimage&amp;task='.$img_upload.'&amp;tmpl=component';
		$select_link = 'index.php?option=com_jux_real_estate&amp;view=imageuploader&amp;task='.$img_select.'&amp;tmpl=component';
        ?>
        <div style="padding: 4px;">
            <!-- physical input to show user which file has been selected -->
            <input type="text" class="inputbox" id="image_name" value="<?php echo $this->value; ?>" disabled="disabled" />&nbsp;

            <!-- Buttons to upload, select, or reset image -->
            <div class="button2-left"><div class="blank"><a class="modal" title="<?php echo JText::_('COM_JUX_REAL_ESTATE_UPLOAD'); ?>" href="<?php echo $upload_link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 270}}"><?php echo JText::_('COM_JUX_REAL_ESTATE_UPLOAD'); ?></a></div></div>
            <?php if($user->authorise('core.admin')): ?>
                <div class="button2-left"><div class="blank"><a class="modal" title="<?php echo JText::_('COM_JUX_REAL_ESTATE_SELECTIMAGE'); ?>" href="<?php echo $select_link; ?>" rel="{handler: 'iframe', size: {x: 650, y: 375}}"><?php echo JText::_('COM_JUX_REAL_ESTATE_SELECTIMAGE'); ?></a></div></div>
            <?php endif; ?>
            <div class="button2-left"><div class="blank"><a href="javascript:void(0);" onclick="jp_SwitchImage('noimage.png');" title="<?php echo JText::_('COM_JUX_REAL_ESTATE_RESET'); ?>"><?php echo JText::_('COM_JUX_REAL_ESTATE_RESET'); ?></a></div></div>

            <!-- hidden field to store the actual value of the image name -->
            <input type="hidden" id="live_image" name="<?php echo $this->name; ?>" value="<?php echo $this->value; ?>" />

            <!-- image preview display and script to swap image with live image -->
            <div style="clear: both; margin-top: 20px; border-top: solid 1px #ccc; padding: 5px;">
                <img src="<?php echo JURI::root(true); ?>/images/jux_real_estate/noimage.png" id="imagepreview" style="padding: 2px; border: solid 1px #ccc;" width="100" height="100" alt="Preview" />
                <script language="javascript" type="text/javascript">
                    //<!CDATA[
                    if ($('image_name').value != ''){
                        var imname = $('image_name').value;
                    }else{
                        var imname = 'noimage.png';
                        $('live_image').value = imname;
                        $('image_name').value = imname;
                    }
                    jsimg = '<?php echo JURI::root(true); ?>/images/jux_real_estate/<?php echo $folder; ?>/' + imname;
                    $('imagepreview').src = jsimg;
                    //]]>
                </script>
            </div>
        </div>
        <?php
	}
}