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

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<?php
//echo $this->loadtemplate('message');
?>
<div class="jux-row agentprofile">
    <div class="agentprofile-left jux-col-sm-4 jux-col-md-4 ">
        <div class="agentprofile-left-wrapper">
            <div class="agentprofile-left-menu dashboard-sidebar">
                <div class="agentprofile-avatar content-thumb">
                    <?php
                    if ($this->agent->avatar) {
                        echo '<img src="' . JUri::root() . $this->agent->avatar . '" alt="' . $this->agent->username . '">';
                    } else {
                        echo'<img  src="' . JUri::root() . '/components/com_jux_real_estate/assets/images/no-image.jpg' . '" />';
                    }
                    ?>

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
    <div class="jux-col-sm-8 jux-col-md-8 agentprofile-right">
        <h1 class="agentprofile-title"><?php echo JText::_('COM_JUX_REAL_ESTATE_MAP_MY_MESSAGE');?></h1>
        <div class="agentprofile-content">
            <table class="table table-hover" style="text-align: center; width: 100%;">
                <thead style="background-color:#EEF6F9; border-bottom: 10px #62C6E4 thick; height:30px;" >
                <th><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_FROM_USER'); ?></strong></th>
                <th><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_EMAIL'); ?></strong></th>
                <th><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_MESSAGE'); ?></strong></th>
                <th><strong><?php echo JText::_('COM_JUX_REAL_ESTATE_SENT_DATE'); ?></strong></th>
                </thead>
                <?php if (count($this->messages) < $this->pagination->total) : ?>
                    <tfoot>
                        <tr>
                            <td colspan="5" align="center">
                                <ul class="pagination">
                                    <?php echo $this->pagination->getPagesLinks(); ?>
                                </ul>
                            </td>
                        </tr>
                    </tfoot>
                <?php endif; ?>
                <tbody>
                    <?php
                    if ($this->messages) {
                        foreach ($this->messages as $message) {
                            $status = $message->status;
                            $fromuser = '<a class="" href="' . JUX_Real_EstateRoute::_('index.php?option=com_jux_real_estate&view=message&layout=message&id=' . $message->id)
                                    . '" title="">' . $message->name . '</a>';

                            $realty = $message->content;


                            echo '<tr>';
                            echo ($status) ? '<td>' . $fromuser . '</td>' : '<td><strong>' . $fromuser . '</strong></td>';
                            echo ($status) ? '<td>' . $message->email . '</td>' : '<td><strong>' . $message->email . '</strong></td>';
                            echo '<td>' . $realty . '</td>';
                            echo '<td>' . JHTML::_('date', $message->date_created, $this->config->get('date_format')) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5"><h3>' . JText::_('COM_JUX_REAL_ESTATE_YOU_DONT_HAVE_ANY_MESSAGE_YET') . '</h3></td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>