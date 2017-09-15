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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Include the component HTML helpers.
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
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate');

$archived = $this->state->get('filter.published') == 2 ? true : false;
$trashed = $this->state->get('filter.published') == -2 ? true : false;
$saveOrder = $listOrder == 'a.ordering';
$sortFields = $this->getSortFields();
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

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=states'); ?>" method="post" name="adminForm"
      id="adminForm">
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
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_JUX_REAL_ESTATE_STATE_FILTER_SEARCH_DESC'); ?></label>
                    <input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_STATE_FILTER_SEARCH_DESC'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JUX_REAL_ESTATE_FILTER_SEARCH_DESC'); ?>" />
                </div>
                <div class="btn-group pull-left hidden-phone">
                    <button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
		    <button class="btn tip hasTooltip" type="button" onclick="document.getElementById('filter_search').value = ''; this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
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
                        <th width="1%">
			    <?php echo JText::_('#'); ?>
                        </th>
                        <th width="1%" class="hidden-phone">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th style="text-align: left;">
			    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_NAME', 'a.state_name', $listDirn, $listOrder); ?>
                        </th>
                        <th style="text-align: left;">
			    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_CODE', 'a.state_code', $listDirn, $listOrder); ?>
                        </th>
                        <th style="text-align: left;">
			    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_COUNTRY_NAME', 'country_name', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" nowrap="nowrap">
			    <?php echo JHTML::_('grid.sort', JText::_('JGRID_HEADING_ID'), 'a.id', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="10" class="center">
			    <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
		    <?php
		    if (count($this->items) > 0) :
			foreach ($this->items as $i => $item) {
			    $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
			    $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.state.' . $item->id);
			    $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate.state.' . $item->id);
			    ?>
			    <tr class="row<?php echo $i % 2; ?>">
				<td>
				    <?php echo $this->pagination->getRowOffset($i); ?>
				</td>
				<td>
				    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
				    <?php if ($canEdit) : ?>
	    			    <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=state.edit&id=' . (int) $item->id); ?>">
					    <?php echo $this->escape($item->state_name); ?></a>
				    <?php else : ?>
					<?php echo $this->escape($item->state_name); ?>
				    <?php endif; ?>
				</td>
				<td>
				    <?php echo $item->state_code; ?>
				</td>
				<td>
				    <?php echo $item->country_name; ?>
				</td>

				<td>
				    <?php echo $item->id; ?>
				</td>
			    </tr>
			    <?php
			}
		    else :
			?>
    		    <tr>
    			<td colspan="6" class="center">
				<?php echo JText::_('COM_JUX_REAL_ESTATE_NO_RESULTS'); ?>
    			</td>
    		    </tr>
		    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHTML::_('form.token'); ?>
</form>

<?php echo JUX_Real_EstateFactory::getFooter();