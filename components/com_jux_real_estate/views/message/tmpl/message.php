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
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base(true) . '/components/com_jux_real_estate/assets/css/mymessage.css');
JHTML::_('behavior.modal');
?>

<div class="jux-row agentprofile">
    <div class="agentprofile-left jux-col-md-4 ">
	<div class="agentprofile-left-wrapper">
	    <div class="agentprofile-left-menu dashboard-sidebar">
		<div class="agentprofile-avatar content-thumb">
		    <img src="<?php echo JUri::root() . $this->agent->avatar; ?>" alt="Stacy Barron">
		</div>
		<div class="agentprofile-link">
		    <?php echo '<a href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=agentprofile') . '" class="user-link active">
                            <i class="jux-fa jux-fa-user"></i>'.JText::_('COM_JUX_REAL_ESTATE_AGENT_MY_PROFILE').' </a>'; ?>
		    <?php echo '<a href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=myrealty') . '" class="user-link">
                            <i class="jux-fa jux-fa-home"></i>'.JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_MY_PROPERTIES').' </a>'; ?>
		</div>
		<div class="agentprofile-link agentprofile-logout">
		    <?php echo' <a href="' . JUX_Real_EstateRoute::_('index.php?option=com_users&amp;view=login&amp;return=L1ZpZXRicmFpbi9KVVhFeHRlbnNpb25zL2p1eF9yZWFsX2VzdGF0ZS9Tb3VyY2VVcGRhdGUvaW5kZXgucGhwP29wdGlvbj1jb21fanV4X3JlYWxfZXN0YXRlJnZpZXc9bXlyZWFsdHkmSXRlbWlkPTE0Mw==') . '"  title="Logout">
                            <i class="jux-fa jux-fa-sign-out"></i>'.JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_LOG_OUT').' </a>'; ?>
		</div>
		<div class="agentprofile-submit">
		    <?php echo '<a href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=form') . '" class="btn btn-secondary ">
                                '.JText::_('COM_JUX_REAL_ESTATE_SUBMIT_PROPERTY').' </a>' ?>
		</div>
	    </div>
	</div>
	<h3 class="dashboard-sidebar-title"><?php echo JText::_('COM_JUX_REAL_ESTATE_YOUR_CURRENT_PACKAGE');?></h3>
	<div class="agentprofile-left-wrapper">
	    <div class="agentprofile-left-menu dashboard-sidebar">
		<div class="sidebar-content">
		   <p> <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_PLAN');?>
                        <?php
                        echo $this->agent->plan;
                        ?>
                    </p>
                    <p> <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_DAYS_LEFT');?>
                        <?php
                       if ($this->agent->days) {
				echo $this->agent->sub_days;
			    } else {
				echo JText::_('COM_JUX_REAL_ESTATE_NEVER');
			    }
                        ?>
                    </p>
                    <p> <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_LIMIT');?>
                        <?php if ($this->agent->plan_countlimit)
				echo $this->agent->plan_countlimit;
			    else
				echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED'); ?>
                    </p>
                    <p> <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_COUNT');?>
                        <?php echo $this->agent->count; ?>
                    </p>
                    <p> <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_PROFILE_REMAIN');?>
                        <?php
                        if ($this->agent->plan_countlimit && ($this->agent->plan_countlimit > $this->agent->count))
                        echo $this->agent->plan_countlimit - $this->agent->count;
                         elseif($this->agent->plan_countlimit==0)
				echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED');
                         else 
                             echo JText::_('COM_JUX_REAL_ESTATE_NEVERSUBMIT')
                        ?>
                    </p>
		</div>
	    </div>
	</div>   
    </div>
    <div class="jux-col-md-8 agentprofile-right">

	<div class="agentprofile-content">
	    <?php if (count($this->messages)) : $message = $this->messages[0]; ?>
		<?php
		$realty = '<a class="hasTip" href="' . JRoute::_(JUX_Real_EstateHelperRoute::getRealtyRoute($message->realty_id . ':' . $message->realty_alias)) . '" title="' . $message->realty . '::' . substr(strip_tags($message->description), 0, 300) . '...">' . $message->realty . '</a>';
		?>

    	    <h3 class="contentheading"><?php echo JText::_('COM_JUX_REAL_ESTATE_YOUR_MESSAGES'); ?></h3>

    	    <table class="table table">
    		<tr>
    		    <td style="width: 20%"><b><?php echo JText::_('COM_JUX_REAL_ESTATE_FROM_USER'); ?></b></td>
    		    <td style="width: 80%"><?php echo $message->name; ?></td>
    		</tr>
    		<tr>
    		    <td><b><?php echo JText::_('COM_JUX_REAL_ESTATE_EMAIL'); ?></b></td>
    		    <td>
			    <?php echo $message->email; ?>
    			<a href="mailto:<?php echo $message->email; ?>?Subject=Re: <?php echo $message->realty; ?>" class="mailto" target="_top" title="Send mail"><i class="fa fa-paper-plane-o"></i></a>
    		    </td>
    		</tr>
    		<tr>
    		    <td><b>    <?php echo JText::_('COM_JUX_REAL_ESTATE_MESSAGE'); ?></b></td>
    		    <td>   <?php echo $message->content; ?></td>
    		</tr>
    	    </table>


    	    <form action = "index.php" method="post" id="msform" name="msform">
    		<input type="button" class="btn" name="goback" onclick="return goBack();" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_GO_BACK'); ?>" />
    		<a class="modal" rel="{handler: 'iframe', size: {x: 400, y: 130}}" href="<?php echo JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=message&layout=delete&tmpl=component&id=' . $message->id); ?>">
    		    <input class="btn" type="button" value="<?php echo JText::_('COM_JUX_REAL_ESTATE_DELETE'); ?>" />
    		</a>
    	    </form>
    	    <script type="text/javascript">
    		    function goBack() {
    			window.history.go(-1);
    		    }
    	    </script>
	    <?php endif; ?>
	</div>
    </div>
</div>
<style>
    .sbox-content-iframe#sbox-content iframe{
        height:100px;
    }
</style>