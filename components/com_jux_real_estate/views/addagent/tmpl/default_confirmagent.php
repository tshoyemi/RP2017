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
defined('_JEXEC') or die('Restricted access');
$row = $this->row;

$config = $this->config;
$post = $this->post;
$user = JFactory::getUser();

?>
<table cellspacing="5" cellpadding="5" border="0" width="100%">
    <tr>
        <td colspan="2">
            <h3 class="agent-form-title">
		<?php echo JText::_('COM_JUX_REAL_ESTATE_PLAN_INFORMATION'); ?>
            </h3>
        </td>
    </tr>
    <tr>
        <td class="title_cell" width="18%">
            <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_NAME'); ?>:</strong>
        </td>
        <td><?php echo $row->name; ?></td>
    </tr>
    <tr>
        <td class="title_cell">
            <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_PLAN_START_DATE'); ?>:</strong>
        </td>
        <td>
	    <?php echo JHTML::_('date', $row->publish_up, JText::_('DATE_FORMAT_LC')); ?>
        </td>
    </tr>
    <tr>
        <td class="title_cell">
            <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_PLAN_END_DATE'); ?>:</strong>
        </td>
        <td>
	    <?php echo JHTML::_('date', $row->publish_down, JText::_('DATE_FORMAT_LC')); ?>
        </td>
    </tr>
    <tr>
        <td class="title_cell">
            <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_PLAN_PRICE'); ?>:</strong>
        </td>
        <td><?php
	    echo ($row->price != 0.00) ? JUX_Real_EstateUtils::formatPrice($row->price, $row->currency_id, $config->get('thousand_separator')) :
		    JText::_('COM_JUX_REAL_ESTATE_FREE');
	    ?>
	</td>
    </tr>
</table>
<form method="post" action="index.php" id="agentForm" name="agentForm" class="form-validate"
      enctype="multipart/form-data">
    <table cellspacing="5" cellpadding="5" border="0" width="100%">
	<tr>
	    <td colspan="2">
		<h3 class="agent-form-title">
		    <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_INFORMATION'); ?>
		</h3>
	    </td>
	</tr>
	<tr>
	    <td class="title_cell" width="18%">
		<label for="name">
		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_USER_NAME'); ?></strong>:
		</label>
	    </td>
	    <td>
                <?php 
                    if(isset($post['username'])){ 
                        echo $post['username'];
                    } elseif(isset ($user->username)) {
                        echo $user->username;
                    }
                ?>
		<input type="hidden" name="username" 
                       value="<?php 
                                    if(isset($post['username'])){ 
                                        echo $post['username'];
                                    } elseif(isset ($user->username)) {
                                        echo $user->username;
                                    }
                              ?>"
                />
	    </td>
	</tr>
        <tr>
	    <td>
		<input type="hidden" name="password" 
                       value="<?php 
                                    if(isset($post['password'])){ 
                                        echo $post['password'];
                                    } elseif(isset ($user->password)) {
                                        echo $user->password;
                                    }
                              ?>"
                />
	    </td>
	</tr>
	<tr>
	    <td class="title_cell">
		<label for="email">
		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_EMAIL'); ?></strong>:
		</label>
	    </td>
	    <td>
		<?php echo $post['email']; ?>
		<input type="hidden" name="email" value="<?php echo $post['email']; ?>"/>
	    </td>
	</tr>
	<?php if (isset($post['address']) && $post['address'] != '') { ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="address">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_ADDRESS'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
		    <?php echo $post['address']; ?>
    		<input type="hidden" name="address" value="<?php echo $post['address']; ?>"/>
    	    </td>
    	</tr>
	    <?php }if (isset($post['city']) && $post['city'] != '') {
	    ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="city">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_CITY'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
		    <?php echo $post['city']; ?>
    		<input type="hidden" name="city" value="<?php echo $post['city']; ?>"/>
    	    </td>
    	</tr>
	    <?php }if (isset($post['locstate']) && $post['locstate'] != '') {
	    ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="locstate">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_STATE'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
		    <?php echo JUX_Real_EstateHelperQuery::getStateName($post['locstate']); ?>
    		<input type="hidden" name="locstate" value="<?php echo $post['locstate']; ?>"/>
    	    </td>
    	</tr>
	    <?php }if (isset($post['zip']) && $post['zip'] != '') {
	    ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="zip">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_ZIP_CODE'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
		    <?php echo $post['zip']; ?>
    		<input type="hidden" name="zip" value="<?php echo $post['zip']; ?>"/>
    	    </td>
    	</tr>
	    <?php }if (isset($post['country_id']) && $post['country_id']) {
	    ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="country_id">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_COUNTRY'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
		    <?php echo JUX_Real_EstateHelperQuery::getCountryName($post['country_id']); ?>
    		<input type="hidden" name="country_id" value="<?php echo $post['country_id']; ?>"/>
    	    </td>
    	</tr>
	    <?php }if (isset($post['phone']) && $post['phone'] != '') {
	    ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="phone">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_PHONE_NUMBER'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
		    <?php echo $post['phone']; ?>
    		<input type="hidden" name="phone" value="<?php echo $post['phone']; ?>"/>
    	    </td>
    	</tr>
	    <?php }if (isset($post['mobile']) && $post['mobile'] != '') {
	    ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="mobile">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_MOBILE'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
		    <?php echo $post['mobile']; ?>
    		<input type="hidden" name="mobile" value="<?php echo $post['mobile']; ?>"/>
    	    </td>
    	</tr>
	    <?php }
	?>
	<?php if (isset($post['organization']) && $post['organization'] != '') { ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="organization">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_ORGANIZATION'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
    <?php echo $post['organization']; ?>
    		<input type="hidden" name="organization" value="<?php echo $post['organization']; ?>"/>
    	    </td>
    	</tr>
    <?php }
    ?>
	<?php if (isset($post['website']) && $post['website'] != '') { ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="website">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_WEBSITE'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
    <?php echo $post['website']; ?>
    		<input type="hidden" name="website" value="<?php echo $post['website']; ?>"/>
    	    </td>
    	</tr>
    <?php }
    ?>
	<?php if (isset($post['msn']) && $post['msn'] != '') { ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="msn">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_MSN'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
    <?php echo $post['msn']; ?>
    		<input type="hidden" name="msn" value="<?php echo $post['msn']; ?>"/>
    	    </td>
    	</tr>
		    <?php }
		?>
    <?php if (isset($post['skype']) && $post['skype'] != '') { ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="skype">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_SKYPE'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
    <?php echo $post['skype']; ?>
    		<input type="hidden" name="skype" value="<?php echo $post['skype']; ?>"/>
    	    </td>
    	</tr>
		    <?php }
		?>
    <?php if (isset($post['gtalk']) && $post['gtalk'] != '') { ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="gtalk">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_GTALK'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
    <?php echo $post['gtalk']; ?>
    		<input type="hidden" name="gtalk" value="<?php echo $post['gtalk']; ?>"/>
    	    </td>
    	</tr>
    <?php }
    ?>
		<?php if (isset($post['linkedin']) && $post['linkedin'] != '') { ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="linkedin">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_LINKEDIN'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
    <?php echo $post['linkedin']; ?>
    		<input type="hidden" name="linkedin" value="<?php echo $post['linkedin']; ?>"/>
    	    </td>
    	</tr>
    <?php }
    ?>
		<?php if (isset($post['facebook']) && $post['facebook'] != '') { ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="facebook">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_FACEBOOK'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
	    <?php echo $post['facebook']; ?>
    		<input type="hidden" name="facebook" value="<?php echo $post['facebook']; ?>"/>
    	    </td>
    	</tr>
    <?php }
    ?>
    <?php if (isset($post['twitter']) && $post['twitter'] != '') { ?>
    	<tr>
    	    <td class="title_cell">
    		<label for="twitter">
    		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_TWITTER'); ?></strong>:
    		</label>
    	    </td>
    	    <td>
	    <?php echo $post['twitter']; ?>
    		<input type="hidden" name="twitter" value="<?php echo $post['twitter']; ?>"/>
    	    </td>
    	</tr>
    <?php }
    ?>
	<tr>
	    <td class="title_cell">
		<label>
		    <strong><?php echo JText::_('COM_JUX_REAL_ESTATE_AVATAR'); ?></strong>:
		</label>
	    </td>
	    <td>
		<input type="file" name="imagefile" class="inputbox"/>
	    </td>
	</tr>
	<input type="hidden" name="sub_desc" value="<?php echo $post['sub_desc']; ?>"/>
	<input type="hidden" name="description" value="<?php echo $post['description']; ?>"/>
    </table>
    <?php if ($row->price != 0.00) : ?>
        <table cellspacing="5" cellpadding="5" border="0" width="100%">
    	<tr>
    	    <td colspan="2">
    		<h3 class="agent-form-title">
    <?php echo JText::_('COM_JUX_REAL_ESTATE_PAYMENT_INFORMATION'); ?>
    		</h3>
    	    </td>
    	</tr>
    	<tr>
    	    <td class="title_cell" width="18%">
    		<strong><?php echo JText::_('COM_JUX_REAL_ESTATE_TOTTAL_PRICE'); ?>:</strong>
    	    </td>
    	    <td>
			<?php echo JUX_Real_EstateUtils::formatPrice($row->price, $row->currency_id) ?>
    		<input type="hidden" name="total_price" value="<?php echo $row->price; ?>"/>
    	    </td>
    	</tr>
    	<tr>
    	    <td class="title_cell">
    		<strong><?php echo JText::_('COM_JUX_REAL_ESTATE_PAYMENT_METHOD'); ?>:</strong>
    	    </td>
    	    <td>
		    <?php echo $post['payment_method']; ?>
    		<input type="hidden" name="payment_method" value="<?php echo $post['payment_method']; ?>"/>
    	    </td>
    	</tr>
        </table>
    <?php endif; ?>
    <p>
	<button class="btn validate" type="submit"><?php echo JText::_('COM_JUX_REAL_ESTATE_CONFIRM'); ?></button>
    </p>
    <input type="hidden" name="option" value="com_jux_real_estate"/>
    <input type="hidden" name="task" value="agent.process_agent"/>
    <input type="hidden" name="id" value="<?php echo $row->id; ?>"/>
    <input type="hidden" name="plan" value="<?php echo $post['plan']; ?>"/>
<?php echo JHTML::_('form.token'); ?>
</form>