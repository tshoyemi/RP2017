<?php
/**
 * @version		$Id: $
 * @author		Nguyen Dinh Luan
 * @package		Joomla!
 * @subpackage	Paypal Payment Plugin
 * @copyright	Copyright (C) 2008 - 2011 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * JoomlaUX Payment - Paypal Payment Plugin.
 * @package		JoomlaUX Payment
 * @subpackage	Payment Plugin
 */
class plgJSPaymentPaypal extends JPlugin {
	/** @var plugin parameter */
	var $params				= null;
	/** @var string Code name of payment method */
	var $_name				= 'paypal';
	/** @var array Payment method information */
	var $_info				= null;
	/** @var string live site */
	var $live_site			= null;
	/** @var array Paypal payment data */
	var $data				= array();
	/** @var string Paypal notify URL */
	var $_url				= null;
	/** @var integer IPN logging */
	var $ipn_log			= 0;
	/** @var string IPN responding */
	var $ipn_response		= null;
	/** @var string	IPN logging file */
	var $ipn_log_file		= null;
	/** @var integer IPN debugger */
	var $ipn_debug			= 0;
	/** @var string IPN debug file */
	var $ipn_debug_file		= null;
	/** @var array IPN data */
	var $ipn_data			= array();
	/** @var array Paypal notify */
	var $_ppnotify			= array(
								"valid_ip"			=> true,
								"order_stt"			=> "",
								"email_sbj"			=> "",
								"email_body"		=> ""
								);


	/**
	 * Constructor
	 */
	function __construct(& $subject, $params) {
		parent::__construct($subject, $params);
		
		// init variables
		$this->_name			= 'paypal';
		$this->_url				= ($this->params->get('test_mode')) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
		$this->live_site		= JUri::base();
		
		$this->ipn_log			= $this->params->get('ipn_log');
		$this->ipn_debug		= $this->params->get('ipn_debug');
		
		$this->ipn_log_file		= JPATH_ROOT.'/'.'plugins'.'/'.'jspayment'.'/'.'paypal'.'/'.'paypal'.'/'.'ipn_log.txt';
		$this->ipn_debug_file	= JPATH_ROOT.'/'.'plugins'.'/'.'jspayment'.'/'.'paypal'.'/'.'paypal'.'/'.'ipn_debug.txt';
		
		// init some default values
		$this->addField('rm',					'2');		// Return method = POST
		$this->addField('cmd',					'_ext-enter');
		$this->addField('redirect_cmd',			'_xclick');
		$this->addField('business',				$this->params->get('paypal_id'));
		$this->addField('receiver_email',		$this->params->get('paypal_id'));
		$this->addField('cpp_header_image',		$this->params->get('merchant_image'));
		if ($this->params->get('no_shipping')) {
			$this->addField('no_shipping',		1);
		}
	}
	
	/**
	 * Add form field data
	 */
	function addField($field, $value) {
		$this->data["$field"]	= $value;
	}

	/**
	 * Get payment info method.
	 */
	function onPaymentInfo() {
		if (empty($this->_info)) {
			$this->_info = array(
				'code'		=> 'paypal',							// Code to separate payment plugin
				'name'		=> JText::_('Paypal'),					// Name to display of payment method
				'image'		=> $this->params->get('payment_image'),	// Image to display of payment method
				'use_cc'	=> 0,									// Use credit card or not?
			);
		}

		return $this->_info;
	}

	/**
	 * Process payment method.
	 */
	function onProcessPayment($order) {
		if ($order->payment_method != $this->_name) {
			return 0;
		}
		// @todo: remove this
		// Phần kiểm tra ở trên luôn trả về JOOMSELLER_PAYMENT_PROCESS_NO_CC = 0
		// trong hàm này có thể xuất ra submit form
		// hoặc là kiểm tra credit card với payment sử dụng credit card.
		// nếu không sử dung credit card thì luôn trả về JOOMSELLER_PAYMENT_PROCESS_NO_CC = 0
		// nếu sử dụng thì trả về một trong 2 giá trị sau:
		// JOOMSELLER_PAYMENT_PROCESS_CC_SUCCESS
		// JOOMSELLER_PAYMENT_PROCESS_CC_FAIL
		//$products = htmlentities($products);

		$this->addField('return',			$order->return_url);
		$this->addField('cancel_return',	$order->cancel_url);
		$this->addField('notify_url',		$order->notify_url);
		$this->addField('item_name',		$order->title);
		$this->addField('item_number',		$order->description);
		$this->addField('amount',			round($order->total_price, 2));
		$this->addField('custom',			$order->id);
		$this->addField('currency_code',	$order->currency_code);
		$this->addField('invoice',			$order->invoice);
		$this->addField('order_id',			$order->id);
		
		$this->redirectingPaypal();

		return 0;
	}
	
	/**
	 * Redirecting to paypal.com
	 */
	function redirectingPaypal() {
		$document = JFactory::getDocument();
		$js = '
			function directToPaypal() {
				document.formPaypal.submit();
			}

			setTimeout("directToPaypal()", 5000);
			';
		$document->addScriptDeclaration($js);
		?>
		<div class="componentheading">
			<?php echo JText::_('Redirecting to paypal...'); ?>
		</div>
		<div>
			<?php echo JText::_('Please wait while redirecting to paypal...'); ?>
		</div>
		<form method="post" name="formPaypal" action="<?php echo $this->_url; ?>">
			<?php
			foreach ($this->data as $name => $value) {
				echo "<input type=\"hidden\" name=\"$name\" value=\"". htmlspecialchars($value). "\" />";
			}
			?>
			<input type="button" class="button" name="submitpaypal" onclick="directToPaypal()" value="Click here" /> if you are not redirected to PayPal after 5seconds
		</form>
		<?php
	}

	/**
	 * Get order id from notification.
	 */
	function onPaymentNotify($payment_method) {		
		if ($payment_method != $this->_name) {
			return array();
		}

		$post	= JRequest::get('post');
		$data	= array(			
			'order_id'			=> $post['custom'],
			'transaction_id'	=> $post['txn_id']
		);
		return $data;
	}

	/**
	 * Verify payment notification.
	 */
	function onVerifyPayment($order) {
		if ($order->payment_method != $this->_name) {
			return false;
		}
		if($this->validate_ipn($order)) {
			foreach ($this->_ppnotify as $key => $value) {
				$this->debug_file("$key=$value\n");
			}
			//$this->debug_file(implode(',', $this->_ppnotify));
			if ($this->_ppnotify['valid_ip']) {
				// @todo paypal ip address has been validated
				//return array('status'	=>	$this->_ppnotify['order_stt']);
				$data	= array(			
					'status'		=> $this->_ppnotify['order_stt']
				);
				return $data;
			} else {
				// @todo hacking paypal from invalid ip address
				return false;
			}
		}

		return true;
	}
	
	/**
	 * Get paypal IPN validation.
	 * @return boolean
	 */
	function validate_ipn($order) {
		$post		= JRequest::get('post');
		$db			= JFactory::getDBO();

		if ($post) {
			header("HTTP/1.0 200 OK");
			/**
			 * @todo process to validate ipn at here
			 */

			//1. Finished Initialization of the notification script
			$post_msg 	= '';
			$workstring	= '';
			foreach ($post as $ipnkey => $ipnval) {
				// Initialization ipn data
				$this->ipn_data["$ipnkey"]	= $ipnval;

				$post_str .= "$ipnkey=$ipnval&amp;";

				// Fix issue with magic quotes
				//if (get_magic_quotes_gpc()) $ipnval = stripslashes ($ipnval);
				$workstring	.=	$ipnkey.'='.urlencode($ipnval).'&';
			}

			//2. Received this POST: $post_msg
			// Reset post message
			$post_msg = "";
			/**
			 * Read post from PayPal system and create reply
			 * starting with: 'cmd=_notify-validate'...
			 * then repeating all values sent: that's our VALIDATION.
			 **/
			$workstring .= 'cmd=_notify-validate'; // Notify validate


			$paypal_receiver_email	= $this->params->get('paypal_id');
			$business				= trim(stripslashes($post['business']));
			$item_name				= trim(stripslashes($post['item_name']));
			$item_number			= trim(stripslashes(@$post['item_number']));
			$payment_status 		= trim(stripslashes($post['payment_status']));

			// The order total amount including taxes, shipping and discounts
			$mc_gross		= trim(stripslashes($post['mc_gross']));

			// Can be USD, GBP, EUR, CAD, JPY
			$currency_code 	=  trim(stripslashes($post['mc_currency']));

			$txn_id 		= trim(stripslashes($post['txn_id']));
			$receiver_email = trim(stripslashes($post['receiver_email']));
			$payer_email	= trim(stripslashes($post['payer_email']));
			$payment_date 	= trim(stripslashes($post['payment_date']));

			// The Order Number (not order_id !)
			$invoice 		= trim(stripslashes($post['invoice']));
			$amount 		= trim(stripslashes(@$post['amount']));

			$quantity 		= trim(stripslashes($post['quantity']));
			$pending_reason = trim(stripslashes(@$post['pending_reason']));
			$payment_method = trim(stripslashes(@$post['payment_method'])); // deprecated
			$payment_type 	= trim(stripslashes(@$post['payment_type']));

			// Billto
			$first_name 	= trim(stripslashes($post['first_name']));
			$last_name 		= trim(stripslashes($post['last_name']));
			$address_street = trim(stripslashes(@$post['address_street']));
			$address_city 	= trim(stripslashes(@$post['address_city']));
			$address_state 	= trim(stripslashes(@$post['address_state']));
			$address_zipcode 	= trim(stripslashes(@$post['address_zip']));
			$address_country 	= trim(stripslashes(@$post['address_country']));
			$residence_country 	= trim(stripslashes(@$post['residence_country']));

			$address_status 	= trim(stripslashes(@$post['address_status']));

			$payer_status 		= trim(stripslashes($post['payer_status']));
			$notify_version 	= trim(stripslashes($post['notify_version']));
			$verify_sign 		= trim(stripslashes($post['verify_sign']));
			$custom 			= trim(stripslashes(@$post['custom']));
			$order_id 			= trim(stripslashes(@$post['custom']));
			$txn_type 			= trim(stripslashes($post['txn_type']));


			// Get the list of IP addresses for www.paypal.com and notify.paypal.com
			$paypal_iplist = gethostbynamel('www.paypal.com');
			$paypal_iplist2 = gethostbynamel('notify.paypal.com');
			$paypal_iplist = array_merge( $paypal_iplist, $paypal_iplist2 );

			$paypal_sandbox_hostname = 'ipn.sandbox.paypal.com';
			$remote_addr	= JRequest::getString('REMOTE_ADDR', '', 'server');

			$remote_hostname = gethostbyaddr($remote_addr);

			$valid_ip = false;
			$this->_ppnotify['valid_ip']	= true;

			if( $paypal_sandbox_hostname == $remote_hostname ) {
				//if( $this->test ) {
				$valid_ip = true;
				//$hostname = 'www.sandbox.paypal.com';
			}
			else {
				if (in_array($remote_addr, $paypal_iplist)) {
					$valid_ip = true;
				}
				//$hostname = 'www.paypal.com';
			}

			$valid_ip = true;
			if( !$valid_ip ) {
				//Error code 506. Possible fraud. Error with REMOTE IP ADDRESS = ".$_SERVER['REMOTE_ADDR'].".
				//The remote address of the script posting to this notify script does not match a valid PayPal ip address

				$mailsubject = "PayPal IPN Transaction on your site: Possible fraud";
				$mailbody = "Error code 506. Possible fraud. Error with REMOTE IP ADDRESS = ".$_SERVER['REMOTE_ADDR'].".
	                        The remote address of the script posting to this notify script does not match a valid PayPal ip address\n
	            These are the valid IP Addresses: ". implode(', ', $paypal_iplist). "\n"
					." The Order ID received was: $invoice";

				/* @todo email function at here */
				$this->_ppnotify['valid_ip']	= false;
				$this->_ppnotify['order_stt']	= "UNDEFINE";
				$this->_ppnotify['email_sbj']	= $mailsubject;
				$this->_ppnotify['email_body']	= $mailbody;
				return true;
			}

			/**--------------------------------------------
		    * Create message to post back to PayPal...
		    * Open a socket to the PayPal server...
		    *--------------------------------------------*/

			$url_parsed	= parse_url($this->_url);
			$hostname	= $url_parsed[host];
			$errno		= '';
			$errstr		= '';

			$header = "POST $url_parsed[path] HTTP/1.0\r\n";
			$header.= "User-Agent: PHP/".phpversion()."\r\n";
			$header.= "Referer: ".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].@$_SERVER['QUERY_STRING']."\r\n";
			$header.= "Server: ".$_SERVER['SERVER_SOFTWARE']."\r\n";
			$header.= "Host: ".$hostname.":80\r\n";
			$header.= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header.= "Content-Length: ".strlen($workstring)."\r\n";
			$header.= "Accept: */*\r\n\r\n";

			$fp = fsockopen($hostname, 80, $errno, $errstr, 30);

			//3. Connecting to: {$hostname}{$url_parsed[path]} Using these http Headers:

			//----------------------------------------------------------------------
			// Check HTTP connection made to PayPal OK, If not, print an error msg
			//----------------------------------------------------------------------
			if (!$fp) {
				$error_description = "$errstr ($errno)
		        Status: FAILED";

				//4. Connection failed: $error_description

				$res = "FAILED";

				$mailsubject = "PayPal IPN Fatal Error on your Site";
				$mailbody = "Hello,
		        A fatal error occured while processing a paypal transaction.
		        ----------------------------------
		        Hostname: $hostname
		        URI: $url_parsed[path]
					$error_description";
				/* @todo email function at here */
				$this->_ppnotify['order_stt']	= "UNDEFINE";
				$this->_ppnotify['email_sbj']	= $mailsubject;
				$this->_ppnotify['email_body']	= $mailbody;
				$this->log_ipn_results(false);
				return true;
			}

			//--------------------------------------------------------
			// If connected OK, write the posted values back, then...
			//--------------------------------------------------------
			else {
				//4. Connection successful. Now posting to {$url_parsed[path]}

				fwrite($fp, $header . $workstring . "\r\n\r\n");
				$res = '';
				while (!feof($fp)) {
					$res .= fgets ($fp, 1024);
				}
				$this->ipn_response	= $res;
				fclose ($fp);
				// get IPN log file
				//if ($this->ipn_log) $this->log_ipn_results(false);

				$error_description = "Response from {$hostname}:{$res}\n";

				//5. $error_description


				// remove post headers if present.
				//$res = preg_replace("'Content-type: text/plain'si","",$res);

				//-------------------------------------------
				// ...read the results of the verification...
				// If VERIFIED = continue to process the TX...
				//-------------------------------------------
				if (eregi ("VERIFIED", $res)) {
					// IPN Verified
					$this->log_ipn_results(true);
					//----------------------------------------------------------------------
					// If the payment_status is Completed... Get the password for the product
					// from the DB and email it to the customer.
					//----------------------------------------------------------------------
					if (eregi ("Completed", $payment_status) || eregi ("Pending", $payment_status)) {

						// UPDATE THE ORDER STATUS to 'Completed'
						if(eregi("Completed", $payment_status)) {
							$this->_ppnotify['order_stt']	= "COMPLETED";
						}
						// UPDATE THE ORDER STATUS to 'Pending'
						elseif(eregi("Pending", $payment_status)) {
							$this->_ppnotify['order_stt']	= "PENDING";
						}

						if (empty($order_id)) {
							$mailsubject = "PayPal IPN Transaction on your site: Order ID not found";
							$mailbody = "The right order_id wasn't found during a PayPal transaction on your website.
		                    The Order ID received was: $invoice";
							// @todo email function at here
							$this->_ppnotify['order_stt']	= "EMPTYORDER";
							$this->_ppnotify['email_sbj']	= $mailsubject;
							$this->_ppnotify['email_body']	= $mailbody;
							return true;
						}

						// AMOUNT and CURRENCY CODE CHECK
						$amount_check = round($order->total_price, 2 );
						//$a = "amount_check:".$amount_check ;
						if( $mc_gross != $amount_check || $currency_code != $order->currency_code) {
							$mailsubject = "PayPal IPN Error: Order Total/Currency Check failed";
							$mailbody = "During a paypal transaction on your site the received amount didn't match the order total.
		                    Order ID: " .$order_id . ".
		                    Order Number: $invoice.
		                    The amount received was: $mc_gross $currency_code.
		                    It should be: $amount_check ".$order->currency_code.".";

							// @todo function email at here
							$this->_ppnotify['order_stt']	= "HACKPRICE";
							$this->_ppnotify['email_sbj']	= $mailsubject;
							$this->_ppnotify['email_body']	= $mailbody;
							return true;
						}

						// RECEIVER EMAIL CHECK
						if($paypal_receiver_email != $receiver_email) {
							$mailsubject = "PayPal IPN Error: receiver email does not match";
							$mailbody = "During a paypal transaction on your site the receiver email didn't match with the configuration.
		                    Order ID: ".$order_id.".
		                    Order Number: $invoice.
		                    The amount received: $mc_gross $currency_code.
		                    The paypal email received configuration: $paypal_receiver_email
		                    The paypal receive email respond: $receiver_email";

							// @todo function email at here
							$this->_ppnotify['order_stt']	= "HACKEMAIL";
							$this->_ppnotify['email_sbj']	= $mailsubject;
							$this->_ppnotify['email_body']	= $mailbody;
							return true;
						}

						$mailsubject = "PayPal IPN txn on your site";
						$mailbody = "Hello,\n\n";
						$mailbody .= "a PayPal transaction for you has been made on your website!\n";
						$mailbody .= "-----------------------------------------------------------\n";
						$mailbody .= "Transaction ID: $txn_id\n";
						$mailbody .= "Payer Email: $payer_email\n";
						$mailbody .= "Order ID: $order_id\n";
						$mailbody .= "Payment Status returned by PayPal: $payment_status\n";
						$mailbody .= "Order Status Code: ".$this->_ppnotify['order_stt'];

						// @todo function email at here
						$this->_ppnotify['email_sbj']	= $mailsubject;
						$this->_ppnotify['email_body']	= $mailbody;

						return true;
					}
					else {
						//----------------------------------------------------------------------
						// If the payment_status is not Completed... do nothing but mail
						//----------------------------------------------------------------------
						// UPDATE THE ORDER STATUS to 'FALSE'
						$this->_ppnotify['order_stt']	= "FALSE";

						$mailsubject = "PayPal IPN Transaction on your site";
						$mailbody = "Hello,
		                a Failed PayPal Transaction on " . $this->_live_site . " requires your attention.
		                -----------------------------------------------------------
		                Order ID: " . $order_id . "
		                User ID: " . $order->user_id . "
		                Payment Status returned by PayPal: $payment_status

							$error_description";

						// @todo function email at here
						$this->_ppnotify['email_sbj']	= $mailsubject;
						$this->_ppnotify['email_body']	= $mailbody;

						return true;

					}
				}
				//----------------------------------------------------------------
				// ..If UNVerified - It's 'Suspicious' and needs investigating!
				// Send an email to yourself so you investigate it.
				//----------------------------------------------------------------
				elseif (eregi ("INVALID", $res)) {
					// IPN Invalid
					$this->log_ipn_results(false);

					$mailsubject = "Invalid PayPal IPN Transaction on your site";
					$mailbody = "Hello,\n\n";
					$mailbody .= "An Invalid PayPal Transaction requires your attention.\n";
					$mailbody .= "-----------------------------------------------------------\n";
					$mailbody .= "REMOTE IP ADDRESS: ".$_SERVER['REMOTE_ADDR']."\n";
					$mailbody .= "REMOTE HOST NAME: $remote_hostname\n";
					$mailbody .= "Order ID: " . $order_id . "\n";
					$mailbody .= "User ID: " . $order->user_id . "\n";
					$mailbody .= $error_description;

					// @todo function email at here
					$this->_ppnotify['order_stt']	= "INVALID";
					$this->_ppnotify['email_sbj']	= $mailsubject;
					$this->_ppnotify['email_body']	= $mailbody;
					return true;

				}
				else {
					$mailsubject = "PayPal IPN Transaction on your Site";
					$mailbody = "Hello,
		                An error occured while processing a paypal transaction.
		                ----------------------------------
						$error_description";
					// IPN Invalid
					$this->log_ipn_results(false);
					// @todo function email at here
					$this->_ppnotify['order_stt']	= "UNDEFINE";
					$this->_ppnotify['email_sbj']	= $mailsubject;
					$this->_ppnotify['email_body']	= $mailbody;
					return true;
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * Write ipn log file.
	 */
	function log_ipn_results($stt) {
		if ($this->ipn_log) {
			$text	= '[' . date('m/d/Y g:i A') . '] PAYPAL RESPONSE - ';
			if ($stt)
				$text .= "SUCCESS!\n";
			else $text .= "FAIL!\n";
			$text	.= "IPN POST Vars from Paypal:\n";
			foreach ($this->ipn_data as $key=>$value) {
				$text .= "$key=$value, ";
			}
			$text 	.= "\nIPN Response from Paypal Server:\n " . $this->ipn_response;
			$fp		= fopen($this->ipn_log_file, 'a');
			fwrite($fp, $text . "\n\n");
			fclose($fp);  // close file
		} else {
			// @todo does not log ipn file
		}
	}

	/**
	 * Save debug to file
	 *
	 * @param string $msg
	 */
	function debug_file($msg) {
		if ($this->ipn_debug){
			$file	= $this->ipn_debug_file;
			$fp		= fopen($file, 'a');
			fwrite($fp, $msg . "\n");
			fclose($fp);  // close file
		}
	}	
}