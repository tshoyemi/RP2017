<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate');
$saveOrder = $listOrder == 'a.ordering';

$archived = $this->state->get('filter.published') == 2 ? true : false;
$trashed = $this->state->get('filter.published') == -2 ? true : false;
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_jux_real_estate&task=types.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

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

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=openhouses'); ?>" method="post" name="adminForm" id="adminForm">
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
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_JUX_REAL_ESTATE_OPEN_HOUSE_FILTER_SEARCH_DESC'); ?></label>
                    <input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_OPEN_HOUSE_FILTER_SEARCH_DESC'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JUX_REAL_ESTATE_OPEN_HOUSE_FILTER_SEARCH_DESC'); ?>" />
                </div>
                <div class="btn-group pull-left hidden-phone">
                    <button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JUXARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                    <button class="btn tip hasTooltip" type="button" onclick="document.id('filter_search').value = '';
        this.form.submit();" title="<?php echo JText::_('JUXARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
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
                        <th width="1%" style="min-width:55px" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
                        </th>
                        <th class="title">
                            <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_TITLE', 'a.name', $listDirn, $listOrder); ?>							
                            / <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_STREEET'), 'r.title', $listDirn, $listOrder); ?>
                        </th>
                        <th class="title">
                            <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_START_DATE', 'a.publish_up', $listDirn, $listOrder); ?>
                        </th>
                        <th class="title">
                            <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_END_DATE', 'a.publish_down', $listDirn, $listOrder); ?>
                        </th>
                        <th class="title">
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_AGENT'); ?>
                        </th>
                        <th class="title">
                            <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_COMPANY', 'company_id', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" nowrap="nowrap">
                            <?php echo JHTML::_('grid.sort', JText::_('JGRID_HEADING_ID'), 'a.id', $listDirn, $listOrder); ?>
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
                <tbody>

                    <?php
                    if (count($this->items) > 0) {
                        foreach ($this->items as $i => $item) {
                            $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
                            $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.openhouse.' . $item->id);
                            $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                            $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate.openhouse.' . $item->id) && $canCheckin;
                            ?>

                            <tr class="row<?php echo $i % 2; ?>" >
                                <td class="center hidden-phone">
                                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td class="center">
                                    <?php echo JHtml::_('jgrid.published', $item->published, $i, '.openhouses.', $canChange, 'cb'); ?>
                                </td>
                                <td>
                                    <?php if ($item->checked_out) : ?>
                                        <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'openhouses.', $canCheckin); ?>
                                    <?php endif; ?>
                                    <?php if ($item->name) echo '<em>' . $this->escape($item->name) . '</em><br />'; ?>
                                    <?php if ($canEdit) : ?>
                                        <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=openhouse.edit&id=' . (int) $item->id); ?>">
                                            <?php echo $this->escape($item->street_address); ?></a>
                                    <?php else : ?>
                                        <?php echo $this->escape($item->street_address); ?>
                                    <?php endif; ?>
                                    <?php if ($item->title) echo '<br />' . $this->escape($item->title); ?>
                                </td>

                                <td align="center"><?php echo ($item->publish_up) ? $item->publish_up : '--'; ?></td>
                                <td align="center"><?php echo ($item->publish_down) ? $item->publish_down : '--'; ?></td>
                                <td>
                                    <?php
                                    $agents = JUX_Real_EstateHelperQuery::getAgents($item->realty_id);
                                    $x = 0;
                                    if ($agents) {
                                        foreach ($agents AS $agent) {
                                            echo JUX_Real_EstateHelperQuery::getAgentName($agent->id);
                                            $x++;
                                            if ($x < count($agents))
                                                echo '<br />';
                                        }
                                    } else {
                                        echo '--';
                                    }
                                    ?>
                                </td>
                                <td><?php echo JUX_Real_EstateHelperQuery::getCompanyName($item->company_id); ?></td>

                                <td align="center">
                                    <?php echo $item->id; ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="14" class="center">
                                <?php echo JText::_('COM_JUX_REAL_ESTATE_NO_RESULTS'); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

            <!-- code 2.5 -->


            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
            <?php echo JHTML::_('form.token'); ?>
        </div>
    </div>

</form>
<?php
echo JUX_Real_EstateFactory::getFooter();