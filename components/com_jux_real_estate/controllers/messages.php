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
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * JUX_Real_Estate controller
 * @package        JUX_Real_Estate
 * @subpackage    Controller
 * @since        3.0
 *
 */
class JUX_Real_EstateControllerMessages extends JControllerForm {

    /**
     * @var    string  The prefix to use with controller realty.
     * @since  1.6
     */
    protected $text_prefix = 'COM_JUX_REAL_ESTATE_MESSAGES';

    //Function to send a message via message board and email
    function sendmessage() {
	$app = JFactory::getApplication();
       $id = JRequest::getVar('id');
	$menu = $app->getMenu()->getActive()->id;
	$db = JFactory::getDBO();
        $msg = JText::_('You have entered wrong captcha');
	$user = JFactory::getUser();
       
	$post = JRequest::get('post', JREQUEST_ALLOWHTML);
       
	$post['menu_itemid'] = $menu;
     
         if (isset($post['jform']['captcha'])) {
           
            if (isset($_SESSION["captcha_code"])) {
                
                if (trim($post['jform']['captcha']) != trim($_SESSION["captcha_code"])) {
                    
                    $url1 = JRoute::_('index.php?option=com_jux_real_estate&view=agentrealties&id=' . (int)$id);
                    $app->redirect($url1,$msg);
                } else {
                    
                }
            }
        }
	$model = $this->getModel('message');
	$ret = $model->store($post);
 
	$model->sendEmails($post, $ret);
	$document = JFactory::getDocument();
	$script = "window.addEvent('domready',
			function() {
					parent.SqueezeBox.close();
					parent.window.location=parent.window.location;
			});";
	$document->addScriptDeclaration($script);
       
    }

    //Function to delete a message
    function deletemessage() {
	$db = JFactory::getDBO();
	$user = JFactory::getUser();

	$post = JRequest::get('post', JREQUEST_ALLOWHTML);

	$sql = "SELECT id FROM #__re_messages WHERE id = " . $post['id'] . ' AND receive_id = ' . $user->id;
	$db->setQuery($sql);
	$isOwner = (int) $db->loadResult();


	$model = $this->getModel('message');
	$ret = $model->delete($post['id']);
	if ($ret) {
	    $msg = JText::_('COM_JUX_REAL_ESTATE_YOUR_MESSAGE_HAS_BEEN_DELETED');
	} else {
	    $msg = JText::_('COM_JUX_REAL_ESTATE_ERROR_WHILE_DELETING_MESSAGE');
	}

	$view = & $this->getView('message', 'html');
	$view->deletemessage($msg);
    }

    function sendmail() {
	$document = &JFactory::getDocument();
	$site_name = JURI::base();
	$post = JRequest::get('post', JREQUEST_ALLOWHTML);
	$post['subject'] = $post['name'] . ' ' . JText::sprintf('COM_JUX_REAL_ESTATE_HAS_JUST_SHARED_S_TO_YOUR_EMAIL', $post['title']);
	$body = '<table cellspacing="5" cellpadding="5" ><tr>';
	$body .= '<td>' . JText::_('COM_JUX_REAL_ESTATE_HELLO') . '<br /> ' . JText::sprintf('COM_JUX_REAL_ESTATE_YOU_HAVE_GOT_A_MESSAGE_FROM_S_ABOUT_THIS_REALTY', $site_name) . '<br /><br/>';
	$body .= '<b>' . $post['title'] . '</b><br />';
	$body .= '<a href="' . $post['link'] . '">' . JText::_('COM_JUX_REAL_ESTATE_CLICK_HERE') . '</a> ' . JText::_('COM_JUX_REAL_ESTATE_TO_MORE_INFOMATION') . '<br />';
	$body .= JText::_('COM_JUX_REAL_ESTATE_THE_DETAILS_MESSAGE_IS_BELLOW');
	$body .= '<br/>' . nl2br($post['content']) . '<br/>';
	$body .= '</td></tr></table>';

	$toemmails = explode(',', $post['toemail']);

	$email = new JMail();
	$email->sendMail($post['fromemail'], $post['fromname'], $toemmails, $post['subject'], $body, 1);

	$script = "window.addEvent('domready',
			function() {
					parent.SqueezeBox.close();
					parent.window.location=parent.window.location;
			});";
	$document->addScriptDeclaration($script);
    }

}
