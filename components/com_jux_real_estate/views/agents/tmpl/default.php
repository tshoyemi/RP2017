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
$doc->addStyleSheet(JURI::base(true) . '/components/com_jux_real_estate/assets/css/agents.css');
$doc->addScript(JURI::base(true) . '/components/com_jux_real_estate/assets/js/jquery.matchHeight.js');
require_once'imageresize.php';
?>
<div class="jux-agents-listing">
<?php if ($this->params->get('show_page_title', 1)) { ?>
        <h3 class="componentheading<?php echo $this->params->get('pageclass_sfx'); ?>">
	<?php echo $this->params->get('page_title'); ?>
        </h3>
	<?php } ?>
    <form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm"  class="form-horizontal" >
        <div class="agent-filter">
            <input placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_SEARCH_KEYWORD'); ?>" type="text" name="filter-search" id="filter-search"
		   value="<?php echo $this->state->get('filter.search'); ?>" class="input-medium" onchange="document.adminForm.submit();"
		   title="<?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT_SEARCH_DESC'); ?>"/>
<?php echo $this->lists['filter_order'] ?>
<?php echo $this->lists['filter_order_Dir'] ?>
        </div>
    </form>
		   <?php
		   if (count($this->items)) {
		       $total = count($this->items);
		       ?>
        <div class="jux-row agents-listing">
	<?php
	for ($i = 0; $i < $total; $i++) {
	    $this->item = $this->items[$i];
	    if ($this->state->get('list_style') == 'grid') {
		echo $this->loadtemplate('grid');
	    } else {
		echo $this->loadtemplate('list');
	    }
	}
	?>
        </div>
        <div style="text-align: center" >
    	<ul class="pagination">
		<?php echo $this->pagination->getPagesLinks(); ?>
    	</ul>
	    <?php if ($this->params->get('show_pagination_results')): ?>
		<p class="counter" >
		    <?php echo $this->pagination->getPagesCounter(); ?>
		</p>
	    </div>
	<?php endif; ?>
    <?php } else {
	echo '<div colspan="' . $this->params->get('num_columns') . '"><h3>' . JText::_('COM_JUX_REAL_ESTATE_THERE_NO_ANY_AGENTS_ON_THE_LIST') . '</h3></div>';
    }
    ?>
</div>
