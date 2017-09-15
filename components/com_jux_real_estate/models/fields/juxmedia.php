<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');

class JFormFieldJUXMedia extends JFormField {

    protected function getInput() {
        // Get name of field in file .xml
        $field_name = (string)$this->element['name'] ? (string)$this->element['name'] : 'juxmedia';
        $link_image = $this->form->getValue($field_name);
        $options = array();
        
        JHtml::_('behavior.tooltip', '.juxhasTipPreview', $options);
        $html = array();
        $html[] = '<div class="input-prepend input-append" style="position: relative;">';
        $html[] = '<div class="media-preview add-on">';
        $previewImgEmpty = '<div id="' . $this->id . '_preview_empty"' . ($link_image ? ' style="display:none"' : '') . '>' . JText::_('JLIB_FORM_MEDIA_PREVIEW_EMPTY') . '</div>';
        $imgattr = array(
            'id' => $this->id . '_preview',
            'class' => 'media-preview',
        );
        $img = JHtml::image($link_image, JText::_('JLIB_FORM_MEDIA_PREVIEW_ALT'), $imgattr);
        $previewImg = '<div id="' . $this->id . '_preview_img"' . ($link_image ? '' : ' style="display:none"') . '>' . $img . '</div>';
        $tooltip = $previewImgEmpty . $previewImg;
        $options = array(
            'title' => JText::_('JLIB_FORM_MEDIA_PREVIEW_SELECTED_IMAGE'),
            'text' => '<i class="icon-eye"></i>',
            'class' => 'hasTipPreview juxhasTipPreview'
        );
        $html[] = JHtml::tooltip($tooltip, $options);
        $html[] = '</div>';
        $html[] = '<input type="text" name="jform['.$field_name.']" class="input-small hasTipImgpath juxhasTipImgpath" id="jform_'.$field_name.'" value="' . $link_image . '" readonly="readonly" title="' . $link_image . '" style="height: 100%;">';
        $html[] = '<a class="btn" title="">Select</a>';
        $html[] = '<input type="file" name="'.$field_name.'" style="position: absolute;width: 1px;height: 1px;  top: 0px;  left: 117px;padding: 26px 47px 0px 13px;" title="' . $link_image . '"/>';
        $html[] = '<a onclick="jInsertFieldValue(\'\',\'jform_'.$field_name.'\');return false;" href="#" class="btn hasTooltip" data-original-title="Clear" title=""><i class="icon-remove"></i></a>';
        $html[] = '</div>';
        
        $document = JFactory::getDocument();
        $src  = 'jQuery(document).ready(function(){';
        $src .=     'jQuery(\'[name="'.$field_name.'"]\').change(function(){';
        $src .=         'if(jQuery(\'[name="'.$field_name.'"]\').val() !== \'\'){';
        $src .=             'jQuery(this).attr(\'title\',\'\');';
        $src .=             'jQuery(\'#jform_'.$field_name.'\').attr(\'title\',jQuery(\'[name="'.$field_name.'"]\').val());';
        $src .=             'jQuery(\'#jform_'.$field_name.'\').val(jQuery(\'[name="'.$field_name.'"]\').val());';
        $src .=             'jQuery(\'.juxhasTipPreview\').parent().append(\'<i class="icon-eye"></i>\');';
        $src .=             'jQuery(\'.juxhasTipPreview\').remove();';
        $src .=         '}';
        $src .=     '});';
        $src .= '});';
        $document->addScriptDeclaration($src);
        
        return implode($html);
    }

}

?>