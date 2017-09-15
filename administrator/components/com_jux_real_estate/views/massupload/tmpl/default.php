<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla.Administrator
 * @subpackage	com_jux_fileseller
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHTML::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'components/com_jux_real_estate/assets/css/jquery.fileupload-ui.css');
//$document->addStyleSheet(JURI::base(). 'components/com_jux_real_estate/assets/css/jquery.fileupload-ui-noscript.css');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.ui.widget.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/tmpl.min.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.iframe-transport.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.fileupload.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.fileupload-process.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.fileupload-validate.js');
$document->addScript(JURI::base() . 'components/com_jux_real_estate/assets/js/jquery.fileupload-ui.js');

$params = JComponentHelper::getParams('com_jux_real_estate');

$allowed_exts = explode(',', str_replace(' ', '', $params->get('file_allowed_ext', 'jpg, png, jpeg')));
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task == 'massupload.cancelupload' || document.formvalidator.isValid(document.id('fileupload'))) {
            Joomla.submitform(task, document.getElementById('fileupload'));
        }
    }
</script>
<form enctype="multipart/form-data" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=massupload.upload'); ?>"
      method="post" name="fileupload" id="fileupload" class="form-validate">
    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="container-fluid fileupload-buttonbar">
        <?php
        $row = JUX_Real_EstateHelperQuery::getRealtiesList();
        echo $row;
        //var_dump($row);
        ?>
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

            <button type="button" href="#" onclick="Joomla.submitbutton('massupload.cleannclose')" class="btn pull-right">
                <i class="icon-cancel icon-white"></i>
                <span><?php echo JText::_('JTOOLBAR_CLOSE'); ?></span>
            </button>
            <button type="button" href="#" onclick="Joomla.submitbutton('massupload.clean')" class="btn btn-info pull-right">
                <i class="icon-refresh icon-white"></i>
                <span><?php echo JText::_('COM_JUX_REAL_ESTATE_FILES_MASS_UPLOAD_CLEAN'); ?></span>
            </button>
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
        <th><?php echo JText::_('COM_JUX_REAL_ESTATE_FILE_NAME'); ?></th>
        <th><?php echo JText::_('COM_JUX_REAL_ESTATE_SIZE'); ?></th>
        <th></th>
        </thead>
        <tbody class="files">

        </tbody>
    </table>

    <input type="hidden" name="task" value="massupload.upload" />
    <?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>
</form>
<br>
<div class="well">
    <h3>Demo Notes</h3>
    <ul>
        <li>The maximum file size for uploads in this demo is <strong>5 MB</strong> (default file size is unlimited).</li>
        <li>Only image files (<strong>JPG, GIF, PNG</strong>) are allowed in this demo (by default there is no file type restriction).</li>
        <li>Uploaded files will be deleted automatically after <strong>5 minutes</strong> (demo setting).</li>
        <li>You can <strong>drag &amp; drop</strong> files from your desktop on this webpage with Google Chrome, Mozilla Firefox and Apple Safari.</li>
        <li>Please refer to the <a href="https://github.com/blueimp/jQuery-File-Upload">project website</a> and <a href="https://github.com/blueimp/jQuery-File-Upload/wiki">documentation</a> for more information.</li>
        <li>Built with Twitter's <a href="http://twitter.github.com/bootstrap/">Bootstrap</a> toolkit and Icons from <a href="http://glyphicons.com/">Glyphicons</a>.</li>
    </ul>
</div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
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
    jQuery(function() {
        'use strict';
        // Initialize the jQuery File Upload widget:
        jQuery('#fileupload').fileupload({
            url: '<?php echo JURI::base() . 'index.php?option=com_jux_real_estate&task=massupload.upload'; ?>',
//		url: 'http://localhost/html/jQuery-File-Upload-8.3.2/server/php/',
            autoUpload: false,
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
//		disableImageResize: /Android(?!.*Chrome)|Opera/
//			.test(window.navigator && navigator.userAgent),
            acceptFileTypes: /(\.|\/)(<?php echo implode('|', $allowed_exts); ?>)$/i
        });

        // Load existing files:
        jQuery('#fileupload').addClass('fileupload-processing');
        jQuery.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: '<?php echo JURI::base() . 'index.php?option=com_jux_real_estate&task=massupload.getFiles'; ?>',
            dataType: 'json',
            context: jQuery('#fileupload')[0]
        }).always(function() {
            jQuery(this).removeClass('fileupload-processing');
        }).done(function(result) {
            jQuery(this).fileupload('option', 'done')
                    .call(this, null, {result: result});
        });
    });
</script>
<!--<script src="<?php echo JURI::base() . 'components/com_jux_real_estate/assets/js/'; ?>tmpl.min.js" type="text/javascript"></script>
<script src="<?php echo JURI::base() . 'components/com_jux_real_estate/assets/js/'; ?>jquery.iframe-transport.js" type="text/javascript"></script>
<script src="<?php echo JURI::base() . 'components/com_jux_real_estate/assets/js/'; ?>jquery.fileupload.js" type="text/javascript"></script>
<script src="<?php echo JURI::base() . 'components/com_jux_real_estate/assets/js/'; ?>jquery.fileupload-process.js" type="text/javascript"></script>
<script src="<?php echo JURI::base() . 'components/com_jux_real_estate/assets/js/'; ?>jquery.fileupload-validate.js" type="text/javascript"></script>
<script src="<?php echo JURI::base() . 'components/com_jux_real_estate/assets/js/'; ?>jquery.fileupload-ui.js" type="text/javascript"></script>-->
<!--<script src="<?php echo JURI::base() . 'components/com_jux_real_estate/assets/js/'; ?>main.js" type="text/javascript"></script>-->
<?php
echo JUX_Real_EstateFactory::getFooter();