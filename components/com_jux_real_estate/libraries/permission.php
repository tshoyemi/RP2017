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
jimport('joomla.base.object');

/**
 * JUX_Real_Estate Component - Permission Library.
 * @package        JUX_Real_Estate
 * @subpackage    Library
 */
class JUX_Real_EstatePermission extends JObject {

    private $db = null;
    private $config = null;
    private $app = null;
    private $user = false;
    private $uagent = false;
    private $uagent_id = false; // user's agent ID from agent table
    private $uagent_cid = false; // user's company ID
    private $admin = false; // admin
    private $guest = true;
    private $company_id = false; // company id
    private $realty_id = false; // realty id of the realty table
    private $realty_cid = false; // realty's company ID
    private $realty_aid = array(); // realty's listing agent
    private $agent_id = false; //
    private $agent_cid = false; // agent's company ID
    private $agent_uid = false; // agent's User joomla ID
    private $plan_aid = false; // plan's agent ID

    function __construct($params = array('msg' => false)) {

	parent::__construct();
	$this->config = JUX_Real_EstateFactory::getConfiguration();
	$this->db = JFactory::getDbo();
	$this->app = JFactory::getApplication();
	$this->user = JFactory::getUser();
	$this->guest = (boolean) $this->user->guest;
	$this->getUAgentInfo(); // create user agent info
	$this->getAgentInfo($this->user->id); // get Agent infor from User_id
	$this->isAdmin(); // check if user is an admin
	$this->displayMessages = ($params['msg']) ? true : false;
    }

    public function setUagentId($id) {
	$this->uagent_id = $id;
    }

    public function getUagentId() {
	return $this->uagent_id;
    }

    public function setUagentCid($id) {
	$this->uagent_cid = $id;
    }

    public function getUagentCid() {
	return $this->uagent_cid;
    }

    public function setAgentId($id) {
	$this->agent_id = $id;
    }

    public function getAgentId() {
	return $this->agent_id;
    }

    public function setAgentCid($id) {
	$this->agent_cid = $id;
    }

    public function getAgentCid() {
	return $this->agent_cid;
    }

    public function setAgentUid($id) {

	$this->agent_uid = $id;
    }

    public function getAgentUid() {
	return $this->agent_uid;
    }


    public function setRealtyId($id) {
	$this->realty_id = $id;
    }

    public function getRealtyId() {
	return $this->realty_id;
    }

    public function setRealtyCid($id) {
	$this->realty_cid = $id;
    }

    public function getRealtyCid() {
	return $this->realty_cid;
    }

    public function setRealtyAid($id) {
	$this->realty_aid = $id;
    }

    public function getRealtyAid() {
	return $this->realty_aid;
    }

    public function setPlanAid($id) {
	$this->plan_aid = $id;
    }

    public function getPlanAid() {
	return $this->plan_aid;
    }

    public function setAdmin($bool) {
	$this->admin = $bool;
    }

    public function getAdmin() {
	return $this->admin;
    }

    /**
     * check if user is agent
     */
    function userIsAgent($user_id = null) {
	$user = JFactory::getUser($user_id);
	if ($user->get('id') == 0) {
	    return JUX_REAL_ESTATE_PERM_GUEST;
	}
	if ($user->get('id')) {
	    $db = JFactory::getDbo();
	    $isAgentPost = 0;
	    $sql = "SELECT id FROM #__re_agents WHERE user_id =" . $user->id . " AND published = 1 LIMIT 1";
	    $db->setQuery($sql);
	    $isAgentPost = $db->loadResult();
	    if ($isAgentPost) {
		return JUX_REAL_ESTATE_PERM_IS_AGENT;
	    }
	}
	return JUX_REAL_ESTATE_PERM_FAIL;
    }

    /**
     * Check if user can perform checkout.
     */
    public static function userCanAddagent($user_id = null) {
	$user = JFactory::getUser($user_id);
	if ($user->get('id') == 0) {
	    return JUX_REAL_ESTATE_PERM_GUEST;
	}
	if ($user->id) {
	    $db = JFactory::getDBO();
	    $isAgentPost = 0;
	    $sql = "SELECT id, count, count_limit FROM #__re_agents WHERE user_id = " . $user->id . " AND published = 1 AND approved = 1 LIMIT 1";
	    $db->setQuery($sql);
	    $isAgentPost = $db->loadObject();
	    if($isAgentPost && $isAgentPost->count >= $isAgentPost->count_limit) {
                return JUX_REAL_ESTATE_PERM_AGENT_ADDED_EXTRA;
            } elseif($isAgentPost) {
                return JUX_REAL_ESTATE_PERM_AGENT_ADDED;
            }
            else {
		return JUX_REAL_ESTATE_PERM_NOT_AGENT;
	    }
	}

	return JUX_REAL_ESTATE_PERM_SUCCESS;
    }

    /**
     * Check permission agent can Approve
     * */
    function agentCanApprove($user_id = null) {

	$db = JFactory::getDBO();
	$date_current = JHTML::date(time(), 'Y-m-d');
	$sql = "SELECT a.count AS `count`, a.count_limit AS `agent_countlimit`"
		. ", p.days AS `days`,p.days_type AS `days_type`, p.count_limit AS `count_limit`"
		. ", (CASE WHEN days_type='day'"
		. " THEN DATEDIFF(DATE_FORMAT(DATE_ADD(a.date_created, INTERVAL days DAY),'%Y-%m-%d'), '" . $date_current . "')"
		. " WHEN days_type='month'"
		. " THEN DATEDIFF(DATE_FORMAT(DATE_ADD(a.date_created, INTERVAL days MONTH),'%Y-%m-%d'), '" . $date_current . "')"
		. " END) AS `sub_days`"
		. " FROM #__re_agents AS a"
		. " INNER JOIN #__re_plans AS p ON p.id = a.plan_id"
		. " WHERE a.user_id = " . $user_id
		. " AND a.published = 1 AND p.published = 1";
	$db->setQuery($sql);
	$objAgent = $db->loadObject();
	if (is_object($objAgent)) {
	    if ($objAgent->days) {
		if (!($objAgent->sub_days > 0)) {
		    return JUX_REAL_ESTATE_PERM_AGENT_EXPIRED_APPROVE;
		} else if ($objAgent->count_limit) {
		    if (!$objAgent->agent_countlimit) {
			return JUX_REAL_ESTATE_PERM_AGENT_EXPIRED_COUNT;
		    }
		}
	    }
	}

	return true;
    }

    /**
     * Check permission agent can submit realty
     *
     * */
    function agentCanSubmit($user_id = null) {

	$db = JFactory::getDBO();
	$date_current = JHTML::date(time(), 'Y-m-d');

	$sql = "SELECT a.count AS `count`, a.count_limit AS `agent_countlimit`"
		. ", p.days AS `days`,p.days_type AS `days_type`, p.count_limit AS `count_limit`"
		. ", (CASE WHEN days_type='day'"
		. " THEN DATEDIFF(DATE_FORMAT(DATE_ADD(a.date_created, INTERVAL days DAY),'%Y-%m-%d'), '" . $date_current . "')"
		. " WHEN days_type='month'"
		. " THEN DATEDIFF(DATE_FORMAT(DATE_ADD(a.date_created, INTERVAL days MONTH),'%Y-%m-%d'), '" . $date_current . "')"
		. " END) AS `sub_days`"
		. " FROM #__re_agents AS a"
		. " INNER JOIN #__re_plans AS p ON p.id = a.plan_id"
		. " WHERE a.user_id = " . $user_id
		. " AND a.published = 1 AND p.published = 1";
	$db->setQuery($sql);
	$objAgent = $db->loadObject();
	if (is_object($objAgent)) {
	    if ($objAgent->days) {
		if (!($objAgent->sub_days > 0)) {
		    echo "agent expried";
		    return JUX_REAL_ESTATE_PERM_AGENT_EXPIRED;
		} else {
		    if ($objAgent->count_limit) {
			if (!$objAgent->agent_countlimit) {
			    echo "agent expried count";
			    return JUX_REAL_ESTATE_PERM_AGENT_EXPIRED_COUNT;
			}
		    }
		}
	    }
	}


	return JUX_REAL_ESTATE_PERM_DONE;
    }

// Method to check if User is an Admin

    private function isAdmin() {
	if ($this->user && ($this->user->authorise('core.admin')) || ($this->user->authorise('core.manage')))
	    $this->setAdmin(true);
    }

    private function getUAgentInfo() {
	if ($this->user->id) {
	    $query = $this->db->getQuery(true);
	    $query->select('a.*');
	    $query->from('#__re_agents AS a');

	    $query->where('a.published = 1');
	    $query->where('user_id=' . $this->user->id);
	    $this->db->setQuery($query);
	    if ($item = $this->db->loadObject()) {
		$this->setUagentId($item->id);
		$this->setPlanAid($item->plan_id);
	    }
	}
    }

    private function getAgentInfo($user_id) {
	if ($this->user->id) {
	    $query = $this->db->getQuery(true);
	    $query->select('id, user_id, published');
	    $query->from('#__re_agents');
	    $query->where('user_id =' . $user_id);
	    $query->where('published = 1');
	    $this->db->setQuery($query);
	    if (($item = $this->db->loadObject()) != FALSE) {
		$this->setAgentId($item->id);
		$this->setAgentUid($item->user_id);
	    }
	}
    }

    private function getPlanInfo() {
	if ($this->user->id) {
	    $query = $this->db->getQuery(true);
	    $query->select('id, days, days_type, count_limit, featured');
	    $query->from('#__re_plans');
	    $query->where('id =' . $this->getPlanAid());
	    $this->db->setQuery($query);
	    if (($item = $this->db->loadObject()) != FALSE) {
		return $item;
	    }
	}
    }

    private function getACL($type) {
	if (!$this->getUagentCid() || !$this->getUagentId()) {
	    if ($this->displayMessages) {
		$this->app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_NO_AGENT_ID_SET'), 'error');
	    }
	    return false;
	}

	switch ($type) {
	    case 'realty':
		if ($this->displayMessages) {
		    $this->app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_AGENT_COMPANY_MISMATCH'), 'error');
		}
		if (in_array($this->getAgentId(), $this->getRealtyAid())) {

		    return true;
		}
		return false;
		break;

	    case 'agent':
		if ($this->getAgentCid() != $this->getUagentCid()) {
		    if ($this->displayMessages) {
			$this->app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_COMPANY_MISMATCH'), 'error');
		    }
		    return false;
		}

		if ($this->getAgentId() == $this->getUagentId())
		    return true;
		return false;
		break;
	  
	}
    }

    private function checkRealtyValid($id) {
	if ($this->user->id > 0) {
	    $query = $this->db->getQuery(true);
	    $query->select('id , r.agent_id as `agent`')
		    ->from('`#__re_realties` AS r')
		    ->where('r.id = ' . (int) $id);
	    $this->db->setQuery($query);
	    if (($result = $this->db->loadObjectList()) !== FALSE) {
		return true;
	    }
	}
	return false;
    }

    private function getRealtyInfo($id) {
	if ($this->user->id > 0) {

	    $this->realty_aid = array();

	    $query = $this->db->getQuery(true);
	    $query->select('r.agent_id as `agent`')
		    ->from('`#__re_realties` AS r')
		    ->where('r.id = ' . (int) $id);
	    $this->db->setQuery($query);
	    if (($result = $this->db->loadObjectList()) !== FALSE) {
		$this->setRealtyId($id);
		foreach ($result as $row) {
		    // lay ra company_id gan cho RealtyCid
		    // lay ra agent_id gan cho realty_aid
		    $this->realty_aid[] = $row->agent;
		}
	    }
	}
    }

    // return true if number of featured realties for an agent is < limit
    private function checkAgentFeatRealtyLimit() {
	if ($this->getAgentId()) {
	    // get the plan
	    $plan = $this->getPlanInfo();
	    if ($plan->featured) {
		$count_limit = $plan->count_limit;
		if (!$count_limit || $count_limit == 0)
		    return true;

		$query = $this->db->getQuery(true);
		$query->select('count(r.realty_id)')
			->from('#__re_realtyamid as ra')
			->leftJoin('#__re_realties as r ON r.id = r.realty_id')
			->where('r.featured = 1')
			->where('r.published = 1')
			->where('ra.agent_id = ' . (int) $this->getAgentId());
		$this->db->setQuery($query);
		if (($result = $this->db->loadResult() !== FALSE)) {
		    if ($result < $count_limit) {
			return true;
		    } else {
			if ($this->displayMessages) {
			    $this->app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_YOU_HAVE_EXPIRED_NUMBER_TIMES_FEATURED'), 'error');
			}
			return false;
		    }
		}
	    }
	}
	return false;
    }

    // return true if number of realties for an agent is < limit
    private function checkAgentApproveRealtyLimit() {
	if ($this->getAgentId()) {
	// get the plan
	    $plan = $this->getPlanInfo();
	    $count_limit = $plan->count_limit;
	    if (!$count_limit || $count_limit == 0)
		return true;

	    $query = $this->db->getQuery(true);
	    $query->select('count(r.realty_id)')
		    ->from('#__re_realtyamid as ra')
		    ->leftJoin('#__re_realties as r ON r.id = r.realty_id')
		    ->where('r.featured = 0')
		    ->where('r.published = 1')
		    ->where('ra.agent_id = ' . (int) $this->getAgentId());
	    $this->db->setQuery($query);
	    if (($result = $this->db->loadResult() !== FALSE)) {
		if ($result < $count_limit) {
		    return true;
		} else {
		    if ($this->displayMessages) {
			$this->app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_YOU_HAVE_EXPIRED_NUMBER_TIMES_APPROVED'), 'error');
		    }
		    return false;
		}
	    }
	}
	return false;
    }

    public function canEditAgent($id) {
	if ($this->getAdmin())
	    return true;
	$this->getAgentInfo($id);
	if ($this->getACL('agent'))
	    return true;
	return false;
    }

    public function canAddRealty($user_id) {
	$realty_id = JRequest::getInt('id');
	if ($realty_id != null) {
	    if ($this->checkRealtyValid($realty_id) != false)
		return false;
	}
	if ($this->getAdmin())
	    return true;
	$this->getAgentInfo($user_id);

	if ($this->getAgentId()) {
	    $bool = $this->agentCanSubmit($this->getAgentUid());
	    if ($bool == JUX_REAL_ESTATE_PERM_DONE) { // return true neu user la agent, va agent van con kha nang submit(count_limit, agent_limt, days)
		if ($bool == JUX_REAL_ESTATE_PERM_AGENT_EXPIRED) {
		    if ($this->displayMessages) {
			$this->app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_YOU_HAVE_EXPIRED_OF_DAYS_FOR_SUBMITTING_REALTY'), 'error');
		    }
		    return false;
		} else if ($bool == JUX_REAL_ESTATE_PERM_AGENT_EXPIRED_COUNT) {
		    if ($this->displayMessages) {

			$this->app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_YOU_HAVE_EXPIRED_THE_TIMES_FOR_SUBMITTING_REALTY'), 'error');
		    }
		    return false;
		} else {
		    return true;
		}
	    } else {
		return false;
	    }
	} else {
	    return false;
	}
    }

    public function canApproveRealty($id) {
	if ($this->getAdmin())
	    return true;
	$this->getRealtyInfo($id);
	if ($this->getAgentId()) {
	    if (($bool = $this->agentCanApprove($this->getAgentId())) !== FALSE) {
		if ($bool == JUX_REAL_ESTATE_PERM_AGENT_EXPIRED_APPROVE) {
		    if ($this->displayMessages) {
			$this->app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_YOU_HAVE_EXPIRED_OF_DAYS_FOR_SUBMITTING_REALTY'), 'error');
		    }
		    return false;
		} else if ($bool == JUX_REAL_ESTATE_PERM_AGENT_EXPIRED_COUNT) {
		    if ($this->displayMessages) {
			$this->app->enqueueMessage(JText::_('COM_JUX_REAL_ESTATE_YOU_HAVE_EXPIRED_THE_TIMES_FOR_SUBMITTING_REALTY'), 'error');
		    }
		    return false;
		} else {
		    return true;
		}
	    }
	}

	return false;
    }

    public function canEditRealty($id) {
	if ($this->getAdmin())
	    return true;
	$this->getRealtyInfo($id); // lay thong in company_id va agent_id cua realty

	if ($this->getACL('realty') && $this->checkRealtyValid($id)) { // check agent_id va company_id lay ra so voi thong tin user.
	    return true;
	}
	return false;
    }

    

    public function canFeatureRealty($id) {
	if ($this->getAdmin())
	    return true;
	$this->getRealtyInfo($id);
	if ($this->getACL('realty')) {
	    if ($this->checkAgentFeatRealtyLimit())
		return true;
	}
	return false;
    }

}