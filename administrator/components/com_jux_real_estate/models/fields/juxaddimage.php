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

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldJUXAddimage extends JFormField {

    protected $type = 'JUXAddimage';

    protected function getInput() {
	$document = JFactory::getDocument();
	$frontend = (isset($this->element['frontend']) && $this->element['frontend']) ? true : false;
	if ($frontend) {
	    $configs = JUX_Real_EstateFactory::getConfiguration();
	} else {
	    $configs = JUX_Real_EstateFactory::getConfigs();
	}
	
	//Get all item images
	$images = array();
	if ($this->form->getValue('images')) {
	    $images = explode(',', $this->form->getValue('images'));
	}
	?>
	<div style="display: inline-block;">
	<?php
	if (count($images)) {
	    echo '<div class="block-images">';
	    $count = 0;
	    foreach ($images as $key => $realty_image) {
		$count++;
		$thumb = JUX_REAL_ESTATE_IMG . "?width={$configs->get('thumb_img_width')}&amp;height={$configs->get('thumb_img_height')}&amp;image=/$realty_image";
		$large = JUX_REAL_ESTATE_IMG . "?width={$configs->get('main_img_width')}&amp;height={$configs->get('main_img_height')}&amp;image=/$realty_image";
		echo '<div id="box-' . $key . '" class="box">';
		echo '   <a class="jux_real_estate_image" rel="lightbox-atomium" title="' . $realty_image . '"
                           href="' . $large . '">
                            <img class="images" border="0" src="' . $thumb . '" alt="image"/>
                        </a><br>                       
                        <a id="delete-' . $key . '"
                           onclick="del(\'' . $key . '\',\'' . $realty_image . '\'); return false;"
                           class="del" title="' . JText::_('COM_JUX_REAL_ESTATE_DELETE') . '" href="#">x</a>';
		echo '</div>';
	    }
	    echo '</div>';
	}
	echo '<br>
        <div id="UploadImages" name="UploadImages">
            <div id="ImageNo0" class="ImageNo0">
                <input class="inputbox" type="file" name="imagefile[]" size="50"/>
            </div>
        </div>

        <input type="button" name="addmorebox" id="addmorebox"
               value="' . JText::_('COM_JUX_REAL_ESTATE_ADD_MORE') . '"/>
        <input type="button" name="removebox" id="removebox" onclick="removeBElm(); return false;"
               value="' . JText::_('COM_JUX_REAL_ESTATE_REMOVE') . '"/>
        </div>';
	echo $this->form->getInput('old_images', null, $this->form->getValue('images'));
	echo $this->form->getInput('del_images');

	$script = array();
	$script[] = ' <script type="text/javascript">';
	$script[] = ' var num = ""; ';
	if (isset($count) && $count) {
	    $script[] = ' num =  "' . $count . '";';
	}
	$script[] = ' var maximg = "' . $configs->get('no_images') . '";';
	$script[] = '  window.addEvent("domready", function () {';
	$script[] = '     $("addmorebox").addEvent("click", function () { ';
	$script[] = '     num++;';
	$script[] = '                if (num < maximg) {  ';
	$script[] = '                 var container = new Element("div", { "id":"ImageNo" + num });';
	$script[] = '                        innerHtmlImage = "<input class=\'inputbox\' type=\'file\' name=\'imagefile[]\' class=\'ImageNo"+num+"\' size=\'50\' />";';
	$script[] = '                        container.set("html", innerHtmlImage);';
	$script[] = '                        container.injectInside($("UploadImages"));';

	$script[] = '               } else {';
	$script[] = '                 num--; ';
	$script[] = '                 alert("' . JText::sprintf('COM_JUX_REAL_ESTATE_YOU_ARE_LIMITED_TO_S_IMAGES', $configs->get('no_images')) . '");';
	$script[] = '               } ';
	$script[] = '       });';

	$script[] = '    });';
	$script[] = '    var items = new Array();
                        function del(id, image) {

                            if ($("delete-" + id)) {
                                $("box-" + id).dispose();
                            }
                            num--;
                            var strimage = $("jform_old_images").value;
                            arrimage = strimage.split(",");
                            var idx = arrimage.indexOf(image);
                            arrimage.splice(idx, 1);
                            $("jform_old_images").value = arrimage.toString();
                            // push image to stack
                            add_Item(image);
                        }
                        function add_Item(item) {
                            items.push(item);
                            $("jform_del_images").value = items;
                        }

                        function removeBElm() {
                            var inputform = "UploadImages";
                            var removeform = "ImageNo" + num;
                            var para = document.getElementById(inputform);
                            var boldElm = document.getElementById(removeform);
                            para.removeChild(boldElm);
                            num--;
                        }

         ';
	$script[] = '</script>';
	echo implode("\n", $script);
    }

}

