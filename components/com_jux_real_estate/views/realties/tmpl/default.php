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
$doc->addStyleSheet(JURI::base(true) . '/components/com_jux_real_estate/assets/css/jux_realties.css');
$doc->addScript(JURI::base(true) . '/components/com_jux_real_estate/assets/js/jquery.matchHeight.js');
$doc->addScript(JURI::base(true) . '/components/com_jux_real_estate/assets/js/jquery.fancybox.js');
require_once 'imageresize.php';
$configs = JUX_Real_EstateFactory::getConfiguration();
$date = new DateTime();
$current_time = $date->format('Y-m-d H:i:s');

?>
<?php
if ($this->params->get('show_page_title', 1)) {
    ?>
    <h3 class="componentheading<?php echo $this->params->get('pageclass_sfx'); ?>">
	<?php echo $this->params->get('page_title'); ?>
    </h3>
    <?php
}
?>
<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="get"
      name="adminForm" id="adminForm" class="form-horizontal">
    <input type="hidden" name="option" value="<?php if (isset($_REQUEST['option'])) {
    echo $_REQUEST['option'];
} ?>" />
    <input type="hidden" name="view" value="<?php if (isset($_REQUEST['view'])) {
    echo $_REQUEST['view'];
} ?>" />
    <input type="hidden" name="list_style" value="<?php if (isset($_REQUEST['list_style'])) {
	echo $_REQUEST['list_style'];
    } ?>" />
    <input type="hidden" name="Itemid" value="<?php if (isset($_REQUEST['Itemid'])) {
	echo $_REQUEST['Itemid'];
    } ?>" />
<?php echo $this->lists['type_id'] ?>
    <?php echo $this->lists['cat_id'] ?>
    <?php echo $this->lists['filter_order'] ?>
    <?php echo $this->lists['filter_order_Dir'] ?>
</form>
    <?php
    if (count($this->items)) {
	$total = count($this->items);
	?>
    <div id="products" class="jux-row jux-realties">
	<?php
	for ($i = 0; $i < $total; $i++) {
	    $this->item = $this->items[$i];
	    $this->current_time = $current_time;
	    $this->configs = $configs;
	    if ($this->state->get('list_style') == 'grid') {
		echo $this->loadtemplate('grid');
	    } else {
		echo $this->loadtemplate('list');
	    }
	}
	?>
    </div>
    <div style="text-align: center">
        <div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    <?php if ($this->params->get('show_pagination')): ?>
        <p class="counter" style="text-align: left">
	<?php echo $this->pagination->getPagesCounter(); ?>
	    </p>
    <?php endif;
    ?>
    </div>
    <?php
} else {
    echo '<h3>' . JText::_('COM_JUX_REAL_ESTATE_SORRY_WE_DONT_HAVE_ANY_REALTY_FOR_THIS_LIST_YET') . '</h3>';
}
?>
<script type="text/javascript">
    jQuery(window).load(function() {
	jQuery('.realty-item').matchHeight();
    });
</script>
<script type="text/javascript">
        jQuery(document).ready(function($) {
         
         $(".fancybox-effects-a").fancybox({
                padding: 0,
                openEffect : 'elastic',
                openSpeed  : 150,
                closeEffect : 'elastic',
                closeSpeed  : 150,
                closeClick : true,
                helpers: {
                  title : {
                        type : 'over'
                    },
                    overlay : {
                        speedOut : 0
                    }
                }

            });
        
        });
    </script>