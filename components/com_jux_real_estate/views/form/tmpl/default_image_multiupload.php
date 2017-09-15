<?php
/**
 * @version     $Id$
 * @author      Joomseller
 * @package     Joomla.Administrator
 * @subpackage  com_jse_digital_store
 * @copyright   Copyright (C) 2008 - 2013 by Joomseller Solutions. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl.html
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
        img_row = Elements.from(tmpl("tmpl-images", blank_img));
        img_row.inject($('images_tbl_body'));
        newSqueezeBox('a.modal-addimg-gallery-' + images_count);
        newTip('.hasTipPreview-image' + images_count);
    }
    function jMediaRefreshPreview(id) {
        var value = document.id(id).value;
        var img = document.id(id + "_preview");
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
</script>
   <?php if ($this->config->get('imgs_per_row')) {?>
<fieldset class="adminform">
    <div class="jux-col-sm-8">
        
        <table class="table table-striped" id="images_tbl">
            <thead>
                <tr>
                    <th width="50%">
                        <?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_IMAGE_TITLE'); ?>
                    </th>
                    <th width="5%">
                        <?php echo JText::_('COM_JUX_REAL_ESTATE_REALTY_IMAGE_PUBLISHED'); ?>
                    </th>
                    <th width="5%">
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
        <!--        <td class="order nowrap center hidden-phone">
        <span class="sortable-handler hasTooltip">
        <i class="icon-menu"></i>
        </span>
        </td>-->
        <td>
        <div class="input-prepend input-append">
        <input type="text" name="gallery[{%=images_count%}][title]" value="{%=image.title%}" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_TITLE'); ?>" class="input-medium">
        <div class="media-preview add-on">
        <span class="hasTipPreview-image hasTipPreview-image{%=images_count%}" title="Selected image::&lt;div id=&quot;jform_image{%=images_count%}_preview_empty&quot; style=&quot;display:none&quot;&gt;No image selected.&lt;/div&gt;&lt;div id=&quot;jform_image{%=images_count%}_preview_img&quot;&gt;&lt;img src=&quot;<?php echo JURI::root(); ?>{%=image.path_image%}&quot; alt=&quot;Selected image&quot; id=&quot;jform_image{%=images_count%}_preview&quot; class=&quot;media-preview&quot; style=&quot;max-width:300px; max-height:200px;&quot; /&gt;&lt;/div&gt;">
        <i class="icon-eye"></i>
        </span>
        </div>
        <input type="text" class="input-small" readonly="readonly" value="{%=image.path_image%}" id="jform_image{%=images_count%}" name="gallery[{%=images_count%}][path_image]" aria-invalid="false">
        <a class="modal btn modal-addimg modal-addimg-gallery-{%=images_count%}" title="" href="<?php echo JURI::base(); ?>index.php?option=com_media&view=images&tmpl=component&asset=com_jse_digital_store&fieldid=jform_image{%=images_count%}&folder=jux_real_estate/realties" rel="{handler: 'iframe', size: {x: 800, y: 600}}">
        Select
        </a>
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