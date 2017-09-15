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
<script type="text/javascript">

    var images_count = 0;
    var blank_img = {
	"gallery": [
	    {
		"title": [""],
		"path_image": [""],
		"published": [1]
	    }
	]
    };
    function add_image()
    {
	var count_ii = "<?php echo JUX_Real_EstateFactory::getConfiguration()->get('imgs_per_row'); ?>"
	var images_count = jQuery('#images_tbl_body tr').length;
	if (images_count >= count_ii) {
	    alert('Maximum images are ' + count_ii);
	    return fasle;
	}
	img_row = Elements.from(tmpl("tmpl-images", blank_img));
	img_row.inject($('images_tbl_body'));
	newSqueezeBox('a.modal-addimg-gallery-' + images_count);
	newTip('.hasTipPreview-image' + images_count);
    }
    function jMediaRefreshPreview(id) {
	var value = document.id(id).value;
	var img = document.id(id + "_preview");
	console.log(img);
//        console.log(document.getElementById('jform_preview_image_preview'));
	if (img) {
	    if (value) {
		img.src = "<?php echo JUri::base(); ?>" + value;
		document.id(id + "_preview_empty").setStyle("display", "none");
		document.id(id + "_preview_img").setStyle("display", "");
	    } else {
		img.src = ""
		document.id(id + "_preview_empty").setStyle("display", "");
		document.id(id + "_preview_img").setStyle("display", "none");
	    }
	}
    }
    window.addEvent('domready', function() {
	var images = <?php echo is_null($this->gallery) || empty($this->gallery) ? 'blank_img' : json_encode(array("gallery" => $this->gallery)); ?>;
	img_row = Elements.from(tmpl("tmpl-images", images));
	img_row.inject($('images_tbl_body'));

	newSqueezeBox('a.modal-addimg');
	newTip('.hasTipPreview-image');
    });

    function insertPath(obj) {
	if (jQuery(obj).val() != '') {
	    var nameObj = jQuery(obj).attr('name');
	    jQuery(obj).attr('title', '');
	    jQuery('#jform_image' + nameObj).val(jQuery(obj).val());
	    jQuery('.hasTipPreview-image' + nameObj).parent().append('<i class="icon-eye"></i>');
	    jQuery('.hasTipPreview-image' + nameObj).remove();
	}
    }
</script>
<?php if ($this->config->get('imgs_per_row')) { ?>
    <fieldset class="adminform">
        <div class="span8">

            <table class="table table-striped" id="images_tbl">
                <thead>
                    <tr>
                        <th>
			    <?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_IMAGE_TITLE'); ?>
                        </th>
                        <th>
			    <?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_IMAGE_PUBLISHED'); ?>
                        </th>
                        <th>
			    <?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_IMAGE_REMOVE'); ?>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: left;">
                            <a href="javascript:void(0);" onclick="add_image()" class="btn btn-info">
                                <i class="icon-plus"></i>
				<?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_IMAGE_ADD_MORE_IMAGES'); ?>
                            </a>
                        </td>
                    </tr>
                </tfoot>
                <tbody id="images_tbl_body">
                </tbody>
            </table>
        </div>
        <!-- The template to display images -->
        <script id="tmpl-images" type="text/x-tmpl">
            {% for (var i=0, image; image = o.gallery[i]; i++) {
		images_count++;
            %}

            <tr class="template-row">
		<td>
		    <div class="input-prepend input-append" style="position: relative;">
			<input type="text" name="gallery[{%=images_count%}][title]" value="{%=image.title%}" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_TITLE'); ?>" class="input-medium">
			<div class="media-preview add-on">
			    <span class="hasTipPreview-image hasTipPreview-image{%=images_count%}" title="Selected image::&lt;div id=&quot;jform_image{%=images_count%}_preview_empty&quot; style=&quot;display:none&quot;&gt;No image selected.&lt;/div&gt;&lt;div id=&quot;jform_image{%=images_count%}_preview_img&quot;&gt;&lt;img src=&quot;<?php echo JURI::root(); ?>{%=image.path_image%}&quot; alt=&quot;Selected image&quot; id=&quot;jform_image{%=images_count%}_preview&quot; class=&quot;media-preview&quot; style=&quot;max-width:300px; max-height:200px;&quot; /&gt;&lt;/div&gt;">
				<i class="icon-eye"></i>
			    </span>
			</div>
			<input type="text" class="input-small" readonly="readonly" value="{%=image.path_image%}" id="jform_image{%=images_count%}" name="gallery[{%=images_count%}][path_image]" aria-invalid="false">
			<div class="select-file">	    
			    <a class=" btn" title="" href="javascript:void(0)">Select</a>
			    <input onchange="insertPath(this);" type="file" value="" title="{%=image.path_image%}" name="{%=images_count%}" style="position: absolute;width: 1px;height: 1px;  top: 0px;  left: 270px;  padding: 26px 46px 0px 13px;"/>
			</div>	    
			<a onclick="jInsertFieldValue('','jform_image{%=images_count%}');return false;" href="#" class="btn hasTooltip" data-original-title="Clear">
			&nbsp;<i class="icon-remove"></i>&nbsp;
			</a>
		    </div>
		</td>
		<td>
		    <input type="checkbox" onclick="tmpl_toggle_published(this)" {% if (image.published && image.published == 1) { %} checked="checked" {% } %}>
		    <input type="hidden" name="gallery[{%=images_count%}][published]" value="{% if (image.published && image.published == 1) { %}1{% }else{ %}0{% } %}">
		</td>
		<td>
		    <a href="javascript:void(0);" onclick="tmpl_remove_row(this)" class="btn btn-danger btn-small">
		    &nbsp;<i class="icon-remove"></i>&nbsp;
		    </a>
		</td>
            </tr>
            {% } %}
        </script>
    </fieldset>
<?php } ?>