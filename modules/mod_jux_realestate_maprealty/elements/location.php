<?php
/**
 * @version		$Id$
 * @author		JoomlaUX Admin
 * @package		Joomla!
 * @subpackage	JUX Real Estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldLocation extends JFormField {

    /**
     * Element name
     *
     * @access	protected
     * @var		string
     */
    var $type = 'Location';

    function getInput() {
        global $mainframe;
        $doc = JFactory::getDocument();
        $js = "
        function jSelectLocation(lat, long, object) {
           document.getElementById(object + '_lat').value = lat;
           document.getElementById(object + '_long').value = long;
           document.getElementById(object + '_lat_name').value = lat;
           document.getElementById(object + '_long_name').value = long;
           SqueezeBox.close();
        }";
       $doc->addScriptDeclaration($js);
       if (is_array($this->value)) {
            $lat = $this->value[0];
            $long = $this->value[1];
        } else {
            $lat = '';
            $long = '';
        }

        $link = 'index.php?option=com_jux_real_estate&amp;view=maprealty&amp;tmpl=component&amp;&amp;lat=' . $lat . '&amp;long=' . $long . '&amp;object=' . $this->name;
        JHTML::_('behavior.modal', 'a.modal');
        $html = "\n" . '<div style="float: left;"><input style="background: #ffffff;height:20px;" type="text" id="' . $this->name . '_lat_name" value="' . $lat . '" disabled="disabled" />
        <input style="background: #ffffff;height:20px;" type="text" id="' . $this->name . '_long_name" value="' . $long . '" disabled="disabled" />
        </div>';
        $html .= '<div class="button2-left"><div class="blank"><a class="modal" title="' . JText::_('MOD_JUX_REAL_ESTATE_SELECT_LOCATION') . '"  href="' . $link . '" rel="{handler: \'iframe\', size: {x: 850, y: 375}}">' . JText::_('MOD_JUX_REAL_ESTATE_SELECT_LOCATION') . '</a></div></div>' . "\n";
        $html .= "\n" . '<input type="hidden" id="' . $this->name . '_lat" name="' . $this->name . '[]" value="' . $lat . '" />';
        $html .= "\n" . '<input type="hidden" id="' . $this->name . '_long" name="' . $this->name . '[]" value="' . $long . '" />';
        return $html;
    }

}
