<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX
 * @package		Joomla!
 * @subpackage	2Co Payment Plugin
 * @copyright	Copyright (C) 2011 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * JoomlaUX Payment - Paypal Payment Plugin.
 * @package		JoomlaUX Payment
 * @subpackage	Payment Plugin
 */
class plgJSPayment2co extends JPlugin {
	/** @var string Code name of payment method */
	var $_name				= null;
	/** @var string Paypal notify URL */
	var $_url				= null;
	/** @var array global data */
	var $_data				= array();
	/** @var array */
	var $_info				= array();
	/** @var plugin parameters */
	var $params				= null;

	/**
	 * Constructor.
	 */
	function __construct(& $subject, $params) {
		parent::__construct($subject, $params);

		$this->_name		= '2co';
		$this->_url			= $this->params->get('routine') ? 'https://www.2checkout.com/checkout/purchase' : 'https://www.2checkout.com/checkout/spurchase';
	}

	/**
	 * add form field data
	 */
	function addField($field, $value) {
		$this->_data["$field"]	= $value;
	}

	/**
	 * Get payment info method.
	 */
	function onPaymentInfo() {
		if (empty($this->_info)) {
			$this->_info = array(
				'code'		=> '2co',								// Code to separate payment plugin
				'name'		=> JText::_('2Checkout'),				// Name to display of payment method
				'image'		=> $this->params->get('payment_image'),	// Image to display of payment method
				'use_cc'	=> 0									// Use credit card or not?
			);
		}

		return $this->_info;
	}
	
	function onProcessPayment($order) {
		if ($order->payment_method != $this->_name) {
			return 0;
		}

		if (!$this->params->get('mode')) {
			$this->addField('demo',	'Y');
		}

		if($this->params->get('email_merchant')) {
			$this->addField('x_email_merchant',		'true');
		} else {
			$this->addField('x_email_merchant',		'false');
		}

		$this->addField('sid',					$this->params->get('id'));
		$this->addField('cart_order_id',		$order->title);
		$this->addField('total',				$this->_convertNumber($order->total_price));
		$this->addField('x_receipt_link_url',	$order->notify_url);
		$this->addField('custom',				$order->id);
		$this->addField('return_url',			$order->return_url);		
		$this->addField('x_user_name',			$order->username);
		
		$this->addField('x_email',				$order->email);
		$this->addField('x_address',			$order->address);
		$this->addField('x_city',				$order->city);
		$this->addField('x_state',				$order->locstate);
		$this->addField('x_zip',				$order->zip);
		$this->addField('x_amount',				$this->_convertNumber($order->total_price));
		$this->addField('x_login',				'TWOCO_LOGIN');

		$this->redirecting2co();

		return 0;
	}

	function redirecting2co() {
		$document = JFactory::getDocument();

		$js = '
			function directTo2co() {
				document.twocoForm.submit();
			}

			setTimeout("directTo2co()", 5000);
			';
		$document->addScriptDeclaration($js);

		?>
		<div class="componentheading">
			<?php echo JText::_('Redirecting to 2Checkout...'); ?>
		</div>
		<div>
			<?php echo JText::_('Please wait while redirecting to 2Checkout...'); ?>
		</div>
		<form method="post" action="<?php echo $this->_url;?>" name="twocoForm">
			<?php
			foreach ($this->_data as $name => $value) {
				echo "<input type=\"hidden\" name=\"$name\" value=\"". htmlspecialchars($value). "\" />";
			}
			?>
			<input type="button" class="button" name="submitpaypal" onclick="directTo2co()" value="Click here" /> if you are not redirected to 2CheckOut after 5seconds
		</form>
		<?php
	}

	function onPaymentNotify($payment_method) {
		if ($payment_method != $this->_name) {
			return array();
		}

		$custom		= JRequest::getInt('custom');
		$trans_id	= JRequest::getint('invoice_id');

		$data	= array(
			'order_id'			=> $custom,
			'transaction_id'	=> $trans_id
		);
		return $data;
	}

	function onVerifyPayment($order) {
		global $mainframe;

		if (!count($order)) return;

		$total = JRequest::getVar('total');
		if($order->total_price != $total) return;

		$secret_word = $this->params->get('secret_word');
		$sid = $this->params->get('id');
		$ordernumber = $this->params->get('mode') ? JRequest::getInt('order_number') : 1;

		// calculate the hash
		$hash = strtoupper(md5($secret_word.$sid.$ordernumber.$total));
		$key = JRequest::getVar('key');

		if ($hash != $key) return;

		// process payment
		$processed = JRequest::getVar('credit_card_processed');
		if ($processed == 'Y') {
			$data	= array(
					'status'		=> 'COMPLETED'
				);
				return $data;
		} else {
			return false;
		}
		return true;
	}

	function _convertNumber(&$number) {
		return number_format($number, 2, '.', '');
	}
}
?>