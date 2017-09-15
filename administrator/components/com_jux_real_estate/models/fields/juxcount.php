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

class JFormFieldJUXCount extends JFormField {

    protected $type = 'JUXCount';

    protected function getInput() {
	$item = JRequest::getInt('id');
	if (!$item)
	    return;

	// define variables
	$db = JFactory::getDbo();
	$document = JFactory::getDocument();

	// See if hits exist
	$query = $db->getQuery(true);
	$query->select('count');
	$query->from('#__re_realties');
	$query->where('id = ' . $item);
	$db->setQuery($query);

	$js = "
        resetCount = function(){
            var url = '" . JURI::base('true') . "/index.php?option=com_jux_real_estate&task=realty.resetCount';
            var Id = " . $item . ";

            req = new Request({
                method: 'post',
                url: url,
                data: { 'realty_id': Id,
                        '" . JSession::getFormToken() . "':'1','tmpl': 'component'
                       },
                onRequest: function() {
                    document.id('count_msg').set('html', '');
                    document.id('count_msg').set('class', 'ajax-loading');
                },
                onSuccess: function(response) {
                    if(response){
                        document.id('count_msg').set('class', 'message');
                        document.id('jform_count').value = '0';
                        document.id('count_msg').set('html', response);                    
                    }else{
                        document.id('count_msg').set('class', 'warning');
                        document.id('count_msg').set('html', '" . JText::_('COM_JUX_REAL_ESTATE_COUNTER_NOT_RESET') . "');
                    }
                }
            }).send();
        }";

	$document->addScriptDeclaration($js);

	if ($result = $db->loadResult()) {
	    ?>

	    <div>
	        <div id="count_msg"></div>
	    </div>
	    <div class="fltlft">
	        <input type="text" id="jform_count" value="<?php echo $result; ?>" disabled="disabled"/>
	    </div>
	    &nbsp;
	    <div class="button2-left">
	        <div class="blank">
	    	<a title="<?php echo JText::_('COM_JUX_REAL_ESTATE_RESET'); ?>"
	    	   onclick="resetCount();"><?php echo JText::_('COM_JUX_REAL_ESTATE_RESET'); ?></a>
	        </div>
	    </div>

	    <?php
	} else {
	    echo '0';
	}
    }

}