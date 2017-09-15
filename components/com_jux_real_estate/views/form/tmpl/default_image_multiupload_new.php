<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
    function add_image(){
        var add_img = '<tr class="template-row">'+
                    '<td>'+
                        '<div class="input-prepend input-append">'+
                            '<input type="text" name="gallery[][title]" value="" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_TITLE'); ?>" class="input-medium">'+
                            '<div class="media-preview add-on">'+
                                '<span class="hasTipPreview-image hasTipPreview-image1" title="">'+
                                    '<i class="icon-eye"></i>'+
                                '</span>'+
                            '</div>'+
                            '<input type="text" class="input-small" readonly="readonly" value="" id="jform_image[]" name="gallery[][path_image]" aria-invalid="false">'+
                            '<a class="modal btn modal-addimg modal-addimg-gallery-1" title="" href="">'+
                                'Select'+
                            '</a>'+
                            '<a onclick="jInsertFieldValue(\'\', \'jform_image\'); return false;" href="#" class="btn hasTooltip" data-original-title="Clear">&nbsp;<i class="icon-remove"></i>&nbsp;'+
                            '</a>'+
                        '</div>'+
                    '</td>'+
                    '<td>'+
                        '<input type="checkbox" onclick="tmpl_toggle_published(this)" >'+
                               '<input type="hidden" name="gallery[][published]" value="">'+
                    '</td>'+
                    '<td>'+
                        '<a href="javascript:void(0);" onclick="tmpl_remove_row(this)" class="btn btn-danger btn-small">&nbsp;<i class="icon-remove"></i>&nbsp;'+
                        '</a>'+
                   ' </td>'+
                '</tr>';
        jQuery('#images_tbl_body').append(add_img);
    }
</script>
<fieldset class="adminform">
    <div class="span8">
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
                <tr class="template-row">
                    <td>
                        <div class="input-prepend input-append">
                            <input type="text" name="gallery[][title]" value="" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_TITLE'); ?>" class="input-medium">
                            <div class="media-preview add-on">
                                <span class="hasTipPreview-image hasTipPreview-image1" title="">
                                    <i class="icon-eye"></i>
                                </span>
                            </div>
                            <input type="text" class="input-small" readonly="readonly" value="" id="jform_image[]" name="gallery[][path_image]" aria-invalid="false">
                            <a class="modal btn modal-addimg modal-addimg-gallery-1" title="" href="">
                                Select
                            </a>
                            <a onclick="jInsertFieldValue('', 'jform_image'); return false;" href="#" class="btn hasTooltip" data-original-title="Clear">
                                &nbsp;<i class="icon-remove"></i>&nbsp;
                            </a>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" onclick="tmpl_toggle_published(this)" >
                               <input type="hidden" name="gallery[][published]" value="">
                    </td>
                    <td>
                        <a href="javascript:void(0);" onclick="tmpl_remove_row(this)" class="btn btn-danger btn-small">
                            &nbsp;<i class="icon-remove"></i>&nbsp;
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</fieldset>