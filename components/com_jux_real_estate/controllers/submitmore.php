<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

/**
 * JUX_Real_Estate Component - Agent Controller
 * @package        JUX_Real_Estate
 * @subpackage    Controller
 */
class JUX_Real_EstateControllerSubmitmore extends JControllerForm {

    var $_format_email = null;
    var $_thanks_message = null;

    function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Check submitmore action permission.
     */
    function _checkPermission() {
        $permission = JUX_Real_EstatePermission::userIsAgent();
        if ($permission != JUX_REAL_ESTATE_PERM_FAIL) {
            if ($permission == JUX_REAL_ESTATE_PERM_GUEST) {
                $url = JUX_Real_EstateRoute::getURI();
                $this->setRedirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url), JText::_('COM_JUX_REAL_ESTATE_PLEASE_LOGIN_TO_PERFORM_THIS_TASK'));
                return false;
            } else if ($permission == JUX_REAL_ESTATE_PERM_IS_AGENT) {
                return true;
            } else {
                $this->setRedirect(JRoute::_('index.php?option=com_jux_real_estate'), JText::_('COM_JUX_REAL_ESTATE_UNKNOWN_ERROR'));
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Validate agent form data
     */
    function _formValidate($post) {
        $app = JFactory::getApplication();
        $success = true;
        // check for first name
        if (!isset($post['first_name']) || trim($post['first_name'] == '')) {
            $app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_PLEASE_ENTER_YOUR_FIRST_NAME'), 'error');
            $success = false;
        }

        // check for last name
        if (!isset($post['last_name']) || trim($post['last_name'] == '')) {
            $app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_PLEASE_ENTER_YOUR_LAST_NAME'), 'error');
            $success = false;
        }
        // check for email
        jimport('joomla.mail.helper');
        if (!isset($post['email']) || !JMailHelper::isEmailAddress($post['email'])) {
            $app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_PLEASE_ENTER_YOUR_VALID_EMAIL_ADDRESS'), 'error');
            $success = false;
        } else {
            if (isset($post['task']) && $post['task'] == 'confirm_agent' && (!isset($post['email_confirm']) || $post['email'] != $post['email_confirm'])) {
                $app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_PLEASE_CHECK_YOUR_EMAIL_THE_CONFIRMATION_ENTRY_DOES_NOT_MATCH'), 'error');
                $success = false;
            }
        }
        // check for selecting plan
        if (!isset($post['plan_id'])) {
            $app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_PLEASE_CHOOSE_A_PLAN'), 'error');
            $success = false;
        }
        // check payment method
        if (isset($post['total_price']) && !isset($post['payment_method'])) {
            $app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_PLEASE_CHOOSE_A_PAYMENT_METHOD'), 'error');
            $success = false;
        }

        return $success;
    }

    /**
     * Get search and replace string for email sending.
     */
    function _getFormatEmail($agent_id) {
        if (empty($this->_format_email[$agent_id])) {
            //get post
            $post = &JRequest::get('post');

            // get the agent info
            $agent_model = &JUX_Real_EstateFactory::getModel('submitmore');
            unset($agent_model->_data);
            $agent = $agent_model->getItem($agent_id);

            $plan = $agent_model->getPlans($agent->plan_id);
            $name = $agent->first_name . ' ' . $agent->last_name;
            $email = $agent->email;

            $extra_info = '';

            // organization
            if (isset($post['company']) && $post['company'] != '') {
                $extra_info .= JText::_('COM_JUX_REAL_ESTATE_COMPANY') . ': ' . $agent->company . '<br />';
            }
            // address
            if (isset($post['address']) && $post['address'] != '') {
                $extra_info .= JText::_('COM_JUX_REAL_ESTATE_ADDRESS') . ': ' . $agent->address . '<br />';
            }
            // city
            if (isset($post['city']) && $post['city'] != '') {
                $extra_info .= JText::_('COM_JUX_REAL_ESTATE_CITY') . ': ' . $agent->city . '<br />';
            }
            // state
            if (isset($post['state']) && $post['state'] != '') {
                $extra_info .= JText::_('COM_JUX_REAL_ESTATE_STATE') . ': ' . $agent->state . '<br />';
            }
            // zip code
            if (isset($post['zip']) && $post['zip'] != '') {
                $extra_info .= JText::_('COM_JUX_REAL_ESTATE_ZIP_CODE') . ': ' . $agent->zip . '<br />';
            }
            // country
            if (isset($post['country']) && $post['country'] != '') {
                $extra_info .= JText::_('COM_JUX_REAL_ESTATE_COUNTRY') . ': ' . $agent->country . '<br />';
            }
            // phone
            if (isset($post['phone']) && $post['phone'] != '') {
                $extra_info .= JText::_('COM_JUX_REAL_ESTATE_PHONE') . ': ' . $agent->phone . '<br />';
            }
            // mobile
            if (isset($post['mobile']) && $post['mobile'] != '') {
                $extra_info .= JText::_('COM_JUX_REAL_ESTATE_MOBILE') . ': ' . $agent->mobile . '<br />';
            }

            $full_info = JText::_('COM_JUX_REAL_ESTATE_NAME') . ': ' . $name . '<br />'
                    . JText::_('COM_JUX_REAL_ESTATE_EMAIL') . ': ' . $email . '<br />'
                    . $extra_info;

            $agent_id = $agent->id;
            $agent_date = JHTML::_('date', $agent->date_created);
            $active_date = JHTML::_('date', $agent->date_paid);
            $transaction_id = $agent->transaction_id;
            $plan_title = $plan->name;
            $plan_desc = strip_tags($plan->description);
            $total_price = $agent->total_price;

            $active_link = JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&task=agent.active&id=' . $agent->id . '&token=' . $agent->token, true, 2);
            $agent_link = JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=agentrealties&id=' . $agent->id . '&token=' . $agent->token, true, 2);

            $ret['search'] = array('{name}', '{email}', '{full_info}', '{agent_id}', '{total_price}', '{agent_date}', '{active_date}', '{active_link}', '{agent_link}', '{plan_title}', '{plan_desc}', '{transaction_id}');
            $ret['replace'] = array($name, $email, $full_info, $agent_id, $total_price, $agent_date, $active_date, $active_link, $agent_link, $plan_title, $plan_desc, $transaction_id);

            $this->_format_email[$agent_id] = $ret;
        }

        return $this->_format_email[$agent_id];
    }

    /**
     * Display agent form.
     */
    function agent() {
        // check permission
        if (!$this->_checkPermission()) {
            return false;
        }

        JRequest::setVar('task', 'agent');
        JRequest::setVar('view', 'submitmore');

        parent::display();
    }

    /**
     * Confirm agent.
     */
    function confirm_agent() {
        // check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        // check permission
        if (!$this->_checkPermission()) {
            return false;
        }

        // check form validation
        $post = &JRequest::get('post');

        if ($this->_formValidate($post)) {
            JRequest::setVar('view', 'submitmore');
            JRequest::setVar('task', 'confirm_submitmore');
            parent::display();
        } else {
            $this->execute('agent');
        }
    }

    /**
     * Process agent.
     */
    function process_agent() {
        jimport('joomla.filesystem.file');
        $app = JFactory::getApplication();
        // check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');
        // check permission
        if (!$this->_checkPermission()) {
            return false;
        }

        $config = &JUX_Real_EstateFactory::getConfiguration();

        // check form validation
        $post = &JRequest::get('post');
        if ($this->_formValidate($post)) {

            $msg = '';
            $link = JUX_Real_EstateRoute::getURI();
            if ($msg != '') {
                $this->setRedirect(JRoute::_($link), $msg);
                return false;
            }
            
            $model = &JUX_Real_EstateFactory::getModel('submitmore');
            $obplan = $model->getPlans($post['id']);
            $post['count_limit'] = $obplan->count_limit;
            $agent = $model->store($post);


            //Send confirmation email
            $user = JFactory::getUser();

            if ($config->get('notify_new_agent')) {
                // send notify email to admin
                $format = $this->_getFormatEmail($agent->id);
                $from = $config->get('store_email', $app->getCfg('mailfrom'));
                $fromname = $config->get('store_email_name', $app->getCfg('fromname'));
                $recipient = $config->get('notify_email', $from);
                $subject = str_replace($format['search'], $format['replace'], $config->get('email_admin_agent_created_subject'));
                $body = str_replace($format['search'], $format['replace'], $config->get('email_admin_agent_created_body'));
                $mail = new JMail;
                $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);
            }

            if (!$agent->id) {
                $this->setRedirect(JRoute::_('index.php?option=com_jux_real_estate'), JText::_('COM_JUX_REAL_ESTATE_ERROR_WHILE_SAVING_AGENT'));
                return false;
            } else {
                if ($agent->total_price != 0) {
                    //Process agent at here
                    $dispatcher = &JDispatcher::getInstance();
                    $config = JUX_Real_EstateFactory::getConfiguration();
                    $model = &JUX_Real_EstateFactory::getModel('submitmore');
                    $plan = $model->getPlans($agent->plan_id);
                    $currency = JUX_Real_EstateUtils::getCurrency($plan->currency_id);
                    $agent->payment_method = $post['payment_method'];
                    $agent->return_url = JUX_Real_EstateRoute::_(JURI::base() . 'index.php?option=com_jux_real_estate&task=agent.payment_return&id=' . $agent->id . '&token=' . $agent->token);
                    $agent->cancel_url = JUX_Real_EstateRoute::_(JURI::base() . 'index.php?option=com_jux_real_estate&task=agent.payment_cancel&id=' . $agent->id . '&token=' . $agent->token);
                    $agent->notify_url = JUX_Real_EstateRoute::_(JURI::base() . 'index.php?option=com_jux_real_estate&task=agent.payment_notify&payment_method=' . $post['payment_method']);
                    $agent->total_price = $agent->total_price;
                    // 2co
                    $agent->products = 1;

                    $agent->subtotal = $agent->total_price;
                    $agent->title = $plan->name;
                    $agent->firstname = $agent->first_name;
                    $agent->lastname = $agent->last_name;
                    //end 2co
                    $agent->description = strip_tags($plan->description);
                    $agent->invoice = $agent->token;
                    $agent->currency_code = $currency->code;

                    JPluginHelper::importPlugin('jspayment', $post['payment_method']);

                    $dispatcher->trigger('onProcessPayment', array($agent));
                } else if ($user->get('id') != 0) {
                    // active the agent
                    $agent_model = &JUX_Real_EstateFactory::getModel('submitmore');
                    $date = &JFactory::getDate();
                    $trans_id = JApplication::getHash($date->toSql());
                    $data['order_id'] = $agent->id;
                    $data['transaction_id'] = $trans_id;
                    $agent = $agent_model->approve($data);

                    // send notify email to user
                    $format = $this->_getFormatEmail($agent->id);
                    $from = $config->get('store_email', $app->getCfg('mailfrom'));
                    $fromname = $config->get('store_email_name', $app->getCfg('fromname'));
                    $recipient = $agent->email;
                    $subject = str_replace($format['search'], $format['replace'], $config->get('email_agent_actived_subject'));
                    $body = str_replace($format['search'], $format['replace'], $config->get('email_agent_actived_body'));

                    $mail = new JMail;
                    $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);
                    if ($config->get('notify_agent_actived')) {
                        // send notify email to admin
                        $format = $this->_getFormatEmail($agent->id);
                        $from = $config->get('store_email', $app->getCfg('mailfrom'));
                        $fromname = $config->get('store_email_name', $app->getCfg('fromname'));
                        $recipient = $config->get('notify_email', $from);
                        $subject = str_replace($format['search'], $format['replace'], $config->get('email_admin_agent_actived_subject'));
                        $body = str_replace($format['search'], $format['replace'], $config->get('email_admin_agent_actived_body'));

                        $mail = new JMail;
                        $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);
                    }

                    // redirect to agent
                    $this->setRedirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=agent&id=' . $agent->id));
                } else {
                    // send email request active agent to user
                    $format = $this->_getFormatEmail($agent->id);
                    $from = $config->get('store_email', $app->getCfg('mailfrom'));
                    $fromname = $config->get('store_email_name', $app->getCfg('fromname'));
                    $recipient = $agent->email;
                    $subject = str_replace($format['search'], $format['replace'], $config->get('email_request_active_subject'));
                    $body = str_replace($format['search'], $format['replace'], $config->get('email_request_active_body'));

                    $mail = new JMail;
                    $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);

                    // display thank you page
                    if ($config->get('page_thanks_type')) {
                        $this->setRedirect(JRoute::_($config->get('page_thanks_url')));
                    } else {
                        // redirect to thank you page
                        $this->setRedirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=message&task=display&type=thankyou'));
                    }
                }
            }
        }
    }

    /**
     * Active anonymous agent.
     */
    function active() {
        echo "active";
        $app = JFactory::getApplication();
        // check if agent is valid
        $id = JRequest::getInt('id');
        $token = JRequest::getString('token');

        $agent_model = &JUX_Real_EstateFactory::getModel('submitmore');
        $agent = &$agent_model->getItem($id);

        if ($agent->token != $token || $agent->approved != 0 || $agent->user_id != 0 || floatval($agent->total_price) != 0) {
            $this->setRedirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate'), JText::_('COM_JUX_REAL_ESTATE_INVALID_AGENT'));
            return false;
        }

        $config = &JUX_Real_EstateFactory::getConfiguration();

        $date = &JFactory::getDate();
        $trans_id = JApplication::getHash($date->toSQL());

        $data['agent_id'] = $id;
        $data['transaction_id'] = $trans_id;
        $agent_model->approve($data);

        // send notify email to user
        $format = $this->_getFormatEmail($id);
        $from = $config->get('store_email', $app->getCfg('mailfrom'));
        $fromname = $config->get('store_email_name', $app->getCfg('fromname'));
        $recipient = $agent->email;
        $subject = str_replace($format['search'], $format['replace'], $config->get('email_agent_actived_subject'));
        $body = str_replace($format['search'], $format['replace'], $config->get('email_agent_actived_body'));
        $mail = new JMail;
        $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);

        if ($config->get('notify_agent_actived')) {
            // send notify email to admin
            $format = $this->_getFormatEmail($id);
            $from = $config->get('store_email', $app->getCfg('mailfrom'));
            $fromname = $config->get('store_email_name', $app->getCfg('fromname'));
            $recipient = $config->get('notify_email', $from);
            $subject = str_replace($format['search'], $format['replace'], $config->get('email_admin_agent_actived_subject'));
            $body = str_replace($format['search'], $format['replace'], $config->get('email_admin_agent_actived_body'));

            $mail = new JMail;
            $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);
        }

        // redirect to agent
        $this->setRedirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=agentrealties&id=' . $id . '&token=' . $token));
    }

    /**
     * User cancel payment.
     */
    function payment_cancel() {
        // check if agent is valid
        $id = JRequest::getInt('id');
        $token = JRequest::getString('token');

        $agent_model = &JUX_Real_EstateFactory::getModel('submitmore');
        $agent = &$agent_model->getItem($id);

        if ($agent->token != $token || $agent->approved != 0) {
            $this->setRedirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate'), JText::_('COM_JUX_REAL_ESTATE_INVALID_REALTY'));
            return false;
        }
        // delete agent
        $agent_model->delete($id);

        // display cancel page
        $config = &JUX_Real_EstateFactory::getConfiguration();

        if ($config->get('page_cancel_type')) {
            $this->setRedirect(JRoute::_($config->get('page_cancel_url')));
        } else {
            // redirect to cancel page
            $this->setRedirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=message&task=display&type=cancel'));
        }
    }

    /**
     * Payment complete.
     */
    function payment_return($id = null, $token = null) {
        $app = JFactory::getApplication();
        // check if agent is valid
        $id = ($id) ? $id : JRequest::getInt('id');
        $token = ($token) ? $token : JRequest::getString('token');

        $agent_model = &JUX_Real_EstateFactory::getModel('submitmore');
        $agent = &$agent_model->getItem($id);
        if ($agent->token != $token) {
            $this->setRedirect(JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate'), JText::_('COM_JUX_REAL_ESTATE_INVALID_AGENT'));
            return false;
        }

        $config = &JUX_Real_EstateFactory::getConfiguration();
        // send thank you email to user
        $format = $this->_getFormatEmail($id);

        $from = $app->getCfg('mailfrom');
        $fromname = $app->getCfg('fromname');
        $recipient = $agent->email;
        $subject = str_replace($format['search'], $format['replace'], $config->get('email_thanks_subject'));
        $body = str_replace($format['search'], $format['replace'], $config->get('email_thanks_body'));

        $mail = new JMail;
        $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);
        if ($config->get('page_thanks_type')) {
            $this->setRedirect(JRoute::_($config->get('page_thanks_url')));
        } else {
            JRequest::setVar('view', 'message');
            JRequest::setVar('task', 'display');
            JRequest::setVar('type', 'thankyou');
            parent::display();
        }
    }

    /**
     * Payment complete notify.
     */
    function payment_notify() {
        $app = JFactory::getApplication();
        $dispatcher = &JDispatcher::getInstance();
        $payment_method = JRequest::getString('payment_method');

        JPluginHelper::importPlugin('jspayment', $payment_method);
        $post = JRequest::get('post');


        if (count($post)) {

            $data = $dispatcher->trigger('onPaymentNotify', array($payment_method));
            if (count($data)) {
                $data = $data[0];
            } else {
                return;
            }
            $agent_model = &JUX_Real_EstateFactory::getModel('submitmore');
            $agent = &$agent_model->getItem($data['order_id']);
            $plan = &$agent_model->getPlans($agent->plan_id);
            $currency = JUX_Real_EstateUtils::getCurrency($plan->currency_id);

            if ($agent->transaction_id != '') {
                //ensure new transaction ID
                return;
            }

            $agent->currency_code = $currency->code;
            $agent->payment_method = $payment_method;
            $result = $dispatcher->trigger('onVerifyPayment', array($agent));

            if (count($result)) {
                $result = $result[0];
            } else {
                return;
            }

            if (trim($result['status']) == 'COMPLETED') {
                $agent = $agent_model->approve($data);

                if (!$agent->id) {
                    // delete if exist
                    $agent_model->delete($agent->id);
                    return false;
                }
                //Send notification email
                $format = $this->_getFormatEmail($agent->id);
                $config = &JUX_Real_EstateFactory::getConfiguration();
                // send agent active email
                $from = $config->get('store_email', $app->getCfg('mailfrom'));
                $fromname = $config->get('store_email_name', $app->getCfg('fromname'));
                $recipient = $agent->email;
                $subject = str_replace($format['search'], $format['replace'], $config->get('email_agent_actived_subject'));
                $body = str_replace($format['search'], $format['replace'], $config->get('email_agent_actived_body'));

                $mail = new JMail;
                $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);
                if ($config->get('notify_agent_actived')) {
                    // send notify email to admin
                    $from = $config->get('store_email', $app->getCfg('mailfrom'));
                    $fromname = $config->get('store_email_name', $app->getCfg('fromname'));
                    $recipient = $config->get('notify_email', $from);
                    $subject = str_replace($format['search'], $format['replace'], $config->get('email_admin_agent_actived_subject'));
                    $body = str_replace($format['search'], $format['replace'], $config->get('email_admin_agent_actived_body'));

                    $mail = new JMail;
                    $mail->sendMail($from, $fromname, $recipient, $subject, $body, 1);
                }
            }
        }

        $this->payment_return($agent->id, $agent->token);
    }

}