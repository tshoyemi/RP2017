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
jimport('joomla.html.html');
jimport('joomla.form.formfield');
require_once JPATH_COMPONENT . '/models/fields/juxfields.php';

JHTML::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$id = (int) JRequest::getVar('id', 0);
//$files_image = JUX_Real_EstateHelperQuery::getFileImageList($id);
$configs = JUX_Real_EstateFactory::getConfigs();
$db = JFactory::getDBO();
$nullDate = $db->getNullDate();
$JUXFields = new JUX_Real_EstateFields();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'components/com_jux_real_estate/assets/css/bootstrap-fileupload.css');
//$document->addStyleSheet(JURI::base() . 'components/com_jux_real_estate/assets/css/bootstrap-fileupload.min.css');
//$document->addStyleSheet(JURI::base(). 'components/com_jux_fileseller/assets/css/jquery.fileupload-ui-noscript.css');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/bootstrap-fileupload.js');
//$document->addScript(JURI::base(). 'components/com_jux_real_estate/assets/js/bootstrap-fileupload.min.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/tmpl.js');


//$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.fileupload-image.js');
$document->addStyleSheet(JURI::base() . 'components/com_jux_real_estate/assets/css/jquery.fileupload-ui.css');
//$document->addStyleSheet(JURI::base(). 'components/com_jux_real_estate/assets/css/jquery.fileupload-ui-noscript.css');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.ui.widget.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/tmpl.min.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.iframe-transport.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.fileupload.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.fileupload-process.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.fileupload-validate.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.fileupload-ui.js');

$max_file_size = (int) $configs->images_zise;
$allowed_exts = explode(',', str_replace(' ', '', ($configs->image_exts)));
?>

<?php
if ($id != NULL) {
    ?>  
    <div id="fileupload">
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="container-fluid fileupload-buttonbar">
            <input type="hidden" name="realty_id" value="<?php echo $this->item->id ?>">
            <div>
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span><?php echo JText::_('COM_JUX_REAL_ESTATE_FILES_MASS_UPLOAD_ADD_FILE'); ?></span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="icon-upload icon-white"></i>
                    <span><?php echo JText::_('COM_JUX_REAL_ESTATE_FILES_MASS_UPLOAD_UPLOAD_ALL'); ?></span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="icon-remove icon-white"></i>
                    <span><?php echo JText::_('COM_JUX_REAL_ESTATE_FILES_MASS_UPLOAD_CANCEL_ALL'); ?></span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span><?php echo JText::_('JTOOLBAR_DELETE'); ?></span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The loading indicator is shown during file processing -->
                <span class="fileupload-loading"></span>

            </div>
            <!-- The global progress information -->
            <div class="span5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped">
            <thead>

            <th style="width: 10%"><?php echo JText::_('COM_JUX_REAL_ESTATE_IMAGE'); ?></th>
            <th><?php echo JText::_('COM_JUX_REAL_ESTATE_FILE_NAME'); ?></th>
            <th><?php echo JText::_('COM_JUX_REAL_ESTATE_SIZE'); ?></th>
            <th></th>
            </thead>
            <tbody class="files">

            </tbody>
        </table>
        <?php echo JHtml::_('form.token'); ?>
        <div class="clr"></div>
        <br>
        <div class="well">
            <h3>Notes for upload image</h3>
            <ul>
                <li>The maximum file size for uploads is <strong> <?php echo $max_file_size ?>MB</strong></li>
                <li>Only image files (<strong><?php echo implode('|', $allowed_exts); ?></strong>) are allowed (by default there is no file type restriction).</li>
            </ul>
        </div>
    </div>

    <!-- The template to display files available for upload -->
    <script id="template-upload" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-upload fade">
        <td>
        <span class="preview"></span>
        </td>
        <td>
        <p class="name">{%=file.name%}</p>
        {% if (file.error) { %}
        <div><span class="label label-important">Error</span> {%=file.error%}</div>
        {% } %}
        </td>
        <td>
        <p class="size">{%=o.formatFileSize(file.size)%}</p>
        {% if (!o.files.error) { %}
        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
        {% } %}
        </td>
        <td>
        {% if (!o.files.error && !i && !o.options.autoUpload) { %}
        <button class="btn btn-primary start">
        <i class="icon-upload icon-white"></i>
        <span><?php echo JText::_('JTOOLBAR_UPLOAD'); ?></span>
        </button>
        {% } %}
        {% if (!i) { %}
        <button class="btn btn-warning cancel">
        <i class="icon-remove icon-white"></i>
        <span><?php echo JText::_('JTOOLBAR_CANCEL'); ?></span>
        </button>
        {% } %}
        </td>
        </tr>
        {% } %}
    </script>
    <!-- The template to display files available for download -->
    <script id="template-download" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-download fade">  
        <td>
        <span class="preview">
        <img style="width: 100px; heigth:100px" src="<?php echo JUri::root() . 'media/com_jux_real_estate/realty_image/' . $id . '/' ?>{%= file.name%}">
        </span>
        </td>
        <td>
        <p class="name">
        {%=file.name%}
        </p>
        {% if (file.error) { %}
        <div><span class="label label-important">Error</span> {%=file.error%}</div>
        {% } %}
        </td>
        <td>
        <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>

        <td>
        <button class="btn btn-danger delete" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
        <i class="icon-trash icon-white"></i>
        <span><?php echo JText::_('JTOOLBAR_DELETE'); ?></span>
        </button>
        <input type="checkbox" name="delete" value="1" class="toggle">
        </td>
        </tr>
        {% } %}
    </script>
    <script type="text/javascript">
        jQuery.noConflict();
        jQuery(function() {
            'use strict';
            // Initialize the jQuery File Upload widget:
            jQuery('#fileupload').fileupload({
                url: '<?php echo JURI::base() . 'index.php?option=com_jux_real_estate&task=realty.upload'; ?>',
                autoUpload: false,
                // Enable image resizing, except for Android and Opera,
                // which actually support image resizing, but fail to
                // send Blob objects via XHR requests:
                //		disableImageResize: /Android(?!.*Chrome)|Opera/
                //			.test(window.navigator && navigator.userAgent),
                maxFileSize: <?php echo $max_file_size * 1048576; ?>,
                acceptFileTypes: /(\.|\/)(<?php echo implode('|', $allowed_exts); ?>)$/i
            });

            // Load existing files:
            jQuery('#fileupload').addClass('fileupload-processing');
            jQuery.ajax({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url: '<?php echo JRoute::_(JURI::base() . 'index.php?option=com_jux_real_estate&task=realty.getFiles&realty_id=' . $id); ?>',
                dataType: 'json',
                contentType: "text/xml",
                context: jQuery('#fileupload')[0]
            }).always(function() {
                jQuery(this).removeClass('fileupload-processing');
            }).done(function(result) {
                jQuery(this).fileupload('option', 'done').call(this, null, {result: result});
            });

        });
    </script>

    <?php
}
?>
