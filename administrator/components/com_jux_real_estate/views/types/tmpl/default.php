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

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$archived = $this->state->get('filter.published') == 2 ? true : false;
$trashed = $this->state->get('filter.published') == - 2 ? true : false;
$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_jux_real_estate&task=types.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
$assoc = isset($app->item_associations) ? $app->item_associations : 0;
?>

<script type="text/javascript">
    Joomla.orderTable = function() {
	table = document.getElementById("sortTable");
	direction = document.getElementById("directionTable");
	order = table.options[table.selectedIndex].value;
	if (order != '<?php echo $listOrder; ?>') {
	    dirn = 'asc';
	} else {
	    dirn = direction.options[direction.selectedIndex].value;
	}
	Joomla.tableOrdering(order, dirn, '');
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=types'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)): ?>
        <div id="j-sidebar-container" class="span2">
	    <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
	<?php else: ?>
    	<div id="j-main-container">
	    <?php endif; ?>
            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_JUX_REAL_ESTATE_TYPE_FILTER_SEARCH_DESC'); ?>
                    </label>
                    <input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_TYPE_FILTER_SEARCH_DESC'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JUX_REAL_ESTATE_FILTER_SEARCH_DESC'); ?>" />
                </div>
                <div class="btn-group pull-left hidden-phone">
                    <button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                    <button class="btn tip hasTooltip" type="button" onclick="document.getElementById('filter_search').value = ''; this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?>
                    </label>
		    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?>
                    </label>
                    <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                        <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
                    </select>
                </div>
                <div class="btn-group pull-right">
                    <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
			<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                    </select>
                </div>
            </div>
            <div class="clearfix"> </div>
            <table class="table table-striped" id="articleList">
                <thead>
                    <tr>
                        <th width="1%" class="nowrap center hidden-phone">
			    <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                        </th>
                        <th width="1%" class="hidden-phone">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th width="1%" style="min-width:55px" class="nowrap center">
			    <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
                        </th>
                        <th>
			    <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
                        </th>
                        <th width="20%" style="text-align: left;">
			    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_ICON', 'a.icon', $listDirn, $listOrder); ?>
                        </th>
                        <th width="5%" class="nowrap hidden-phone">
			    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
                        </th>

                        <th width="5%" class="nowrap hidden-phone">
			    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" class="nowrap hidden-phone">
			    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="10">
			    <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>                
		<?php
		$link_img = JUri::base() . 'components/com_jux_real_estate/assets/icon/';
		foreach ($this->items as $i => $item):
		    $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
		    $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.type.' . $item->id);
		    $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
		    $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate.type.' . $item->id) && $canCheckin;
		    ?>

                    <tr class="row<?php echo $i % 2; ?>" >
                        <td class="order nowrap center hidden-phone">
			    <?php
			    if ($canChange) :
				$disableClassName = '';
				$disabledLabel = '';
				if (!$saveOrder) :
				    $disabledLabel = JText::_('JORDERINGDISABLED');
				    $disableClassName = 'inactive tip-top';
				endif;
				?>
				<span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
				    <i class="icon-menu"></i>
				</span>
				<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
			    <?php else: ?>
				<span class="sortable-handler inactive" >
				    <i class="icon-menu"></i>
				</span>
			    <?php endif; ?>
			</td>
			<td class="center hidden-phone">
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>
			<td class="center">
				<?php echo JHtml::_('jgrid.published', $item->published, $i, '.types.', $canChange, 'cb'); ?>
			</td>
			<td>
				<?php if ($item->checked_out): ?>
				    <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'types.', $canCheckin); ?>
				    <?php endif; ?>
				    <?php if ($canEdit): ?>
				    <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=type.edit&id=' . (int) $item->id); ?>">
				    <?php echo $this->escape($item->title); ?></a>
				<?php else: ?>
					<?php echo $this->escape($item->title); ?>
				    <?php endif; ?>
				<div class="smallsub">
				<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?></div>
			</td>
			<td>
			    <span class="editlinktip hasTip"
    			      title="<?php echo JText::_('COM_JUX_REAL_ESTATE_EDIT_ICON'); ?>::<?php echo $this->escape($item->title); ?>">
                                <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=type.edit&id=' . (int) $item->id); ?>">
                                    <img src="<?php echo $link_img . $item->icon; ?>"/>
                                </a>
                            </span>
                        </td>
                        <td class="center">
			    <?php echo $this->escape($item->access_level); ?>
                        </td>
                        <td class="center">
			    <?php
			    if ($item->language == '*') {
				echo JText::alt('JALL', 'language');
			    } else {
				echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED');
			    }
			    ?>
                        </td>
                        <td align="center">
			    <?php echo $item->id; ?>
                        </td>
                    </tr>
		<?php endforeach; ?>
		</tbody>
	    </table>
	    <input type="hidden" name="task" value="" />
	    <input type="hidden" name="boxchecked" value="0" />
	    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	    <?php echo JHtml::_('form.token'); ?>
	</div>
    </div>
</form>

<?php echo JUX_Real_EstateFactory::getFooter();
