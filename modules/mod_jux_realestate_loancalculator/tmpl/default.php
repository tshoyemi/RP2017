<?php

/**
 * @version		$Id: $
 * @author		JoomlaUX
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_jux_realestate_loancalculator/assets/css/loancalculator.css');
?>
<script type="text/javascript">
    function calculateit() {
        f = document.repayment;
        amt = f.amt.value;
        annual_int = f.interest.value / 100;
        term = f.term.value;
        monthly = annual_int / 12;
        monthly_pay = Math.floor((amt * monthly) / (1 - Math.pow((1 + monthly), (-1 * term * 12))) * 100) / 100;
        if(currency(monthly_pay) != 'NaN.aN' && currency(monthly_pay) != 'Infinity.ty'){
            document.getElementById("monthly").innerHTML = '$' + currency(monthly_pay);
        } else {
            document.getElementById("monthly").innerHTML = '0';
        }
        yearly_pay  = monthly_pay*12;
        if(currency(yearly_pay) != 'NaN.aN' && currency(yearly_pay) != 'Infinity.ty') {
            document.getElementById("yearly").innerHTML = '$' + currency(yearly_pay);
        } else {
            document.getElementById("yearly").innerHTML = '0';
        }
        document.getElementById("calculated").style.display = 'block';
        
    }

    function currency(num) {
        var dollars = Math.floor(num);
        for (var i = 0; i < num.length; i++) {
            if (num.charAt(i) == ".")
                break;
        }
        var cents = "" + Math.round(num * 100);
        cents = cents.substring(cents.length - 2, cents.length);
        return (dollars + "." + cents)
    }
</script>
<div class="jux_realestate-loancalculator">
    <div class="mortgage-calculator">
        <h3>Mortgage Calculator</h3>
    </div>
    <form name="repayment" method="post" class="zj_load<?php echo $moduleclass_sfx; ?>">
        <div>
            <label class="lb">
                <?php echo JText::_('COM_JUX_REAL_ESTATE_LOAN_AMOUNT'); ?>:
            </label>
            <input type="text" value="" name="amt" id="amt" maxlength="15" class="input-medium" style="width: 100%" />
        </div>
        <div>
            <label class="lb"><?php echo JText::_('COM_JUX_REAL_ESTATE_INTEREST_RATE'); ?>: </label>
            <input type="text" value="" name="interest" id="interest" maxlength="15" class="input-medium" style="width: 100%" />
        </div>
        <div>
            <label class="lb"><?php echo JText::_('COM_JUX_REAL_ESTATE_TERM_OF_LOAD'); ?>: </label>
            <input type="text" value="" name="term" id="term" maxlength="15" class="input-medium"  style="width: 100%"/>
        </div>
        <div class="calculated" id="calculated">
            <div>
                <label><strong class="st"><?php echo JText::_('COM_JUX_REAL_ESTATE_MONTHLY_REPAYMENT'); ?>:<br></strong></label>
                <p id="monthly"></p>
            </div>
            <div>
                <label><strong class="st"><?php echo JText::_('COM_JUX_REAL_ESTATE_YEARLY_REPAYMENT'); ?>:<br></strong></label>
                <p id="yearly"></p>
            </div>
        </div>
        <div class="calculator-bt">
            <input type="button" class="btn" name="<?php echo JText::_('COM_JUX_REAL_ESTATE_CACULALATE'); ?>" onclick="calculateit();" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_CACULALATE'); ?>" />
        </div>
    </form>
</div>
