<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla.Site
 * @subpackage          mod_jux_metro_contents
 * @copyright           Copyright (C) 2015 JoomlaUX. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access'); 

class JFormFieldJUXgroup extends JFormField {
	
	protected $type= "juxgroup";
	
	protected $elementArr = array('text','textarea','radio','select','checkbox');
	
	protected function getInput(){
		$html = array();
		
		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="form-inline ' . (string) $this->element['class'] . '"' : ' class="form-inline"';

		// Start the radio field output.
		$html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

		// Get the element.
		$elements = $this->getElements();
		// Build the radio field output.
		foreach ($elements as $element)
		{
			$html[] = $element;
		}
		$html[] = '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="'
			. htmlspecialchars(trim($this->value), ENT_COMPAT, 'UTF-8') . '" />';
		// End the radio field output.
		$html[] = '</fieldset>';
		
		return implode($html);
	}
	
	protected function getElements()
	{
		$document = JFactory::getDocument();
		
		$inputHtml = array();
		$i=0;
		$valueArr = json_decode($this->value);

		$jsArr = array();
		
		foreach ($this->element->children() as $element)
		{
			if (!in_array($element->getName(),$this->elementArr))
			{
				continue;
			}
			$method = 'render'.(string)$element->getName();
			$name = (string) $element['name'];
			if (method_exists($this,$method)){
				$inputHtml[$name] = $this->$method($element,$valueArr);
			}
			$i++;
			$jsArr[] = "'".$name."'";
		}
		$document->addScriptDeclaration($this->addJsObject($jsArr));
		
		reset($inputHtml);

		return $inputHtml;
	}
	
	protected function renderText($element,$valueArr){
		// Initialize some field attributes.
		$is_color = ($element['type'] &&  (string) $element['type'] == 'color') ? true : false ; 
		$opacity = '';
		$name = (string) $element['name'];
		$opacity = (string) $element['opacity'] ? $element['opacity'] :'';
		if (isset($valueArr->$name)){
			if (is_string($valueArr->$name)){
				$value = $valueArr->$name;
			}elseif (isset($valueArr->$name->color) && isset($valueArr->$name->opacity)){
				$value = $valueArr->$name->color;
				$opacity = $valueArr->$name->opacity;
			}
		}
		
		if ($is_color){
			$control = (string) $element['control'] ?' data-control="' . (string) $element['control']. '"' : '';
			$element['class'] = (string) $element['class'] ? trim('jux-colorpicker '.$element['class']) : 'jux-colorpicker';
			$opacity =  $opacity ?' data-opacity="' . $opacity. '"' : '';
		}
		$size = $element['size'] ? ' size="' . (int) $element['size'] . '"' : '';
		$maxLength = $element['maxlength'] ? ' maxlength="' . (int) $element['maxlength'] . '"' : '';
		$class = $element['class'] ? ' class="input-small ' . (string)$element['class'] . '"' : 'class="input-small"';
		$readonly = ((string) $element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$required = $element['required'] ? ' required="required" aria-required="true"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $element['onchange'] ? ' onchange="' . (string) $element['onchange'] . '"' : '';
		
		$html = '<input data-toggle="tooltip" data-placement="bottom" data-original-title="'.(string) $element.'" placeholder="'.(string) $element.'" type="text" name="' . $name . '" id="' .$name. '" value="'
		. htmlspecialchars((isset($value) ? $value :  ($element['value'] ? (string) $element['value'] : '' ) ), ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $required .$opacity. '/> ';
		if ($is_color){
			$this->addcolorPicker();
		}
		
		return $html;
	}
	
	protected function renderRadio($element,$valueArr){
		$html = array();
		$class = $element['class'] ? ' class="radio ' . (string) $element['class'] . '"' : '';
		$html[] = '<div '.$class.' >';
		$i=0;
		foreach ($element->children() as $option){
			if ($option->getName() != 'option')
				continue;
				
			$name = (string) $element['name'];
			$value = $element['value'] ? $element['value'] : '' ;
			if (isset($valueArr->$name)){
				if (is_string($valueArr->$name)){
					$value = $valueArr->$name;
				}
			}
				
			// Initialize some option attributes.
			
			$checked = ((string) $option['value'] == $value) ? ' checked="checked"' : '';
			$class = $option['class'] ? ' class="' . $option['class'] . '"' : '';
			$disabled = $option['disabled'] ? ' disabled="disabled"' : '';
			$required =  $option['required'] ? ' required="required" aria-required="true"' : '';

			// Initialize some JavaScript option attributes.
			$onclick = $option['onclick'] ? ' onclick="' . $option['onclick'] . '"' : '';

			$html[] = '<input  type="radio" id="' . $name . $i . '" name="' . $name . '" value="'
				. htmlspecialchars($option['value'] ? (string) $option['value'] : '' , ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . $required . '/>';

			$html[] = '<label  for="' . $name . $i . '" '.$class.'>'
				. JText::alt((string)$option, preg_replace('/[^a-zA-Z0-9_\-]/', '_',(string)$option)) . '</label>';
			$i++;
		}
		$html[] = '</div>';
		return implode($html);
	}
	
	protected function renderCheckbox(){
		return '';
	}
	
	protected function renderSelect($element,$valueArr){
		$html = array();
		$html[] = '<div style="display:inline-table;vertical-align: sub;">';
		
		$attr = '';
		$attr .= $element['class'] ? ' class="' . (string)$element['class'] . '"' : '';
		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $element['readonly'] == 'true' || (string)$element['disabled'] == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		$attr .= $element['size'] ? ' size="' . (int) $element['size'] . '"' : '';
		$attr .= $element['multiple'] ? ' multiple="multiple"' : '';
		$attr .= $element['required'] ? ' required="required" aria-required="true"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $element['onchange'] ? ' onchange="' . (string) $element['onchange'] . '"' : '';
		$attr .= $element['style'] ? ' style="'.htmlspecialchars($element['style']).'"' : '';
		
		$name = (string) $element['name'];
		$value = $element['value'] ? $element['value'] : '' ;
		if (isset($valueArr->$name)){
			if (is_string($valueArr->$name)){
				$value = $valueArr->$name;
			}
		}
			
		
		$options = array();
		foreach ($element->children() as $option){
			if ($option->getName() != 'option')
			continue;
			
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
				'select.option', (string) $option['value'],
				JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $name)), 'value', 'text',
				((string) $option['disabled'] == 'true')
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];
			$options[] = $tmp;
		}
		
		$html[] = JHtml::_('select.genericlist', $options,$name, trim($attr), 'value', 'text',$value,$name);
		$html[] = '</div> ';
		return implode($html);
	}
	
	protected function renderTextarea(){
		
	}
	
	protected function loadjQuery(){
		if (!defined('_JUX_JQUERY')){
			define('_JUX_JQUERY', 1);
			$jdoc = JFactory::getDocument();
			$jdoc->addScript(JURI::root(true).'/modules/mod_jux_content_slider/assets/js/jquery.min.js');
			$jdoc->addScript(JURI::root(true).'/modules/mod_jux_content_slider/assets/js/jquery.noconflict.js');
			$jdoc->addScript(JURI::root(true).'/modules/mod_jux_content_slider/assets/bootstrap/js/bootstrap.js');
		}
	}
	
	protected function addcolorPicker(){
		if (!defined("_JUX_COLORPIKER")){
			define("_JUX_COLORPIKER",1);
			// Include jQuery
			$jdoc = JFactory::getDocument();
			$jversion  = new JVersion;
			if(!$jversion->isCompatible('3.0')){
				$this->loadjQuery();
			}
			$jdoc->addScript(JURI::root(true).'/modules/mod_jux_content_slider/assets/minicolors/js/jquery.minicolors.min.js');
			$jdoc->addStyleSheet(JURI::root(true).'/modules/mod_jux_content_slider/assets/minicolors/css/jquery.minicolors.css');
			$jdoc->addScriptDeclaration("
				jQuery(document).ready(function (){
					jQuery('.jux-colorpicker').each(function() {
						jQuery(this).minicolors({
							control: jQuery(this).attr('data-control') || 'hue',
							position: jQuery(this).attr('data-position') || 'right',
							opacity: jQuery(this).attr('data-opacity'),
							theme: 'bootstrap'
						});
					});
					 // tooltip 
					jQuery('[data-toggle=tooltip]').tooltip({}); 
				});
			"
			);
			$jdoc->addStyleDeclaration("
				.minicolors-opacity-slider {
				    background-position: -40px 0;
				    display: none;
				    left: 178px !important;
				}
			");
		}
	}
	
	protected function addJsObject($jsArr){
		$jsObject = "
			jQuery(document).ready(function(){
				var form =  document.adminForm;
				if(!form){
					return false;
				}
				var onsubmit = form.onsubmit;
				form.onsubmit = function(e){
					".$this->fieldname."SubmitInit();
					if(jQuery.isFunction(onsubmit)){
						onsubmit();
					}
				};
			});
			function ".$this->fieldname."SubmitInit(){
				var ".$this->fieldname."Arr = [".implode(',',$jsArr)."];
				var ".$this->fieldname."Vaule = {};
				jQuery.each(".$this->fieldname."Arr,function(key,val){
					var that = jQuery('input[name='+val+'],select[name='+val+']'),
						col = {};
					if(jQuery.trim(that.val()) != ''){
						var key = that.attr('name');
						if(that.data('opacity')){
							".$this->fieldname."Vaule[key] = {};
							".$this->fieldname."Vaule[key]['color']= that.val();
							".$this->fieldname."Vaule[key]['opacity']= that.attr('data-opacity');
						}else{
							if(that.attr('type') == 'radio'){
								that.each(function(){
									if(jQuery(this).is(':checked')){
										".$this->fieldname."Vaule[key] = jQuery(this).val();
									}
								});
							}else{
								".$this->fieldname."Vaule[key] = that.val();
							}
						}
					}
				});
				jQuery('input#" . $this->id . "').prop('value',JSON.stringify(".$this->fieldname."Vaule));
			}
		";
		return $jsObject;
	}
}