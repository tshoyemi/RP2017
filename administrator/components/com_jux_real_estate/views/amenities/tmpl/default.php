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

defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate.amenity');

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$colspan = 6;
require_once JPATH_COMPONENT . '/models/fields/juxamenitycat.php';


$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_jux_real_estate&task=amenities.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
$assoc = isset($app->item_associations) ? $app->item_associations : 0;
?>

<script type="text/javascript">
    Joomla.orderTable = function()
    {
	table = document.getElementById("sortTable");
	direction = document.getElementById("directionTable");
	order = table.options[table.selectedIndex].value;
	if (order != '<?php echo $listOrder; ?>')
	{
	    dirn = 'asc';
	}
	else
	{
	    dirn = direction.options[direction.selectedIndex].value;
	}
	Joomla.tableOrdering(order, dirn, '');
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=amenities'); ?>" 
      method="post" name="adminForm" id="adminForm">
    <!-- code 3.0 -->
    <?php if (!empty($this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
	    <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
    <?php else : ?>
    	<div id="j-main-container">
    <?php endif; ?>
            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_JUX_REAL_ESTATE_AMENITY_FILTER_SEARCH_DESC'); ?></label>
                    <input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_AMENITY_FILTER_SEARCH_DESC'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JUX_REAL_ESTATE_FILTER_SEARCH_DESC'); ?>" />
                </div>
                <div class="btn-group pull-left hidden-phone">
                    <button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JUXARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                    <button class="btn tip hasTooltip" type="button" onclick="document.id('filter_search').value = ''; this.form.submit();" title="<?php echo JText::_('JUXARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
		    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
                    <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                        <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
                    </select>
                </div>
                <div class="btn-group pull-right">
                    <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
			<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                    </select>
                </div>
            </div>

            <div class="clearfix"> </div>
            <table class="table table-striped" id="articleList">
                <thead>
                    <tr>
                        <th width="1%" class="hidden-phone">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th width="25%"
                            ><?php echo JHtml::_('grid.sort', 'COM_JUX_REAL_ESTATE_TITLE', 'title', $listDirn, $listOrder); ?>
                        </th>
                        <th width="24%">
			    <?php echo JHtml::_('grid.sort', 'COM_JUX_REAL_ESTATE_CATEGORY', 'cat', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%">&nbsp;</th>
                        <th width="25%">
			    <?php echo JHtml::_('grid.sort', 'COM_JUX_REAL_ESTATE_TITLE', 'title', $listDirn, $listOrder); ?>
                        </th>
                        <th width="24%">
			    <?php echo JHtml::_('grid.sort', 'COM_JUX_REAL_ESTATE_CATEGORY', 'cat', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
		
                <tbody>
		    <?php
			$amenity_cats = array(0 => JText::_('COM_JUX_REAL_ESTATE_GENERAL_AMENITIES'), 1 => JText::_('COM_JUX_REAL_ESTATE_INTERIOR_AMENITIES'), 2 => JText::_('COM_JUX_REAL_ESTATE_EXTERIOR_AMENITIES'));
			if (count($this->items) > 0):
			    echo    '<tr>
					<td colspan="3" style="border-right: solid 1px #d6d6d6;" width="50%" valign="top">
					    <table width="100%">';
			    $x = 0;
			    foreach ($this->items as $i => $item) :
		    ?>
				    <tr class="row<?php echo $i % 2; ?>">
					<td width="1%"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
					<td width="25%" align="left">
					    <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=amenity.edit&id=' . (int) $item->id); ?>"><?php echo $item->title; ?></a>
					</td>
					<td width="24%" class="center">
					    <select name="amen_cat_<?php echo $item->id; ?>" class="inputbox"
						    onchange="document.getElementById('cb<?php echo $i; ?>').checked = true;">
							<?php echo JHtml::_('select.options', JFormFieldJUXAmenityCat::getOptions(), 'value', 'text', $item->cat); ?>
					    </select>
					</td>
				    </tr>
				    <?php
					$x++;
					if ($x == 10 && $x != count($this->items)) {
					    echo '</table>
					    </td>
					<td colspan="3" width="50%" valign="top">
					    <table width="100%">';
					}
					endforeach;
					echo '</table>
					</td>
				    </tr>';
			    else:
				    ?>
    		    <tr>
    			<td colspan="<?php echo $colspan; ?>" class="center">
				<?php echo JText::_('COM_JUX_REAL_ESTATE_NO_RESULTS'); ?>
    			</td>
    		    </tr>
		    <?php endif; ?>
                </tbody>
		
                <tfoot>
                    <tr>
                        <td colspan="<?php echo $colspan; ?>" class="center">
			    <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <!-- code 2.5 -->
        </div>
    </div>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>
<?php echo JUX_Real_EstateFactory::getFooter(); ?>