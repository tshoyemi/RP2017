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

$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate.company');

$saveOrder = $listOrder == 'c.ordering';
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_jux_real_estate&task=companies.saveOrderAjax&tmpl=component';
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
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=companies'); ?>" method="post" name="adminForm" id="adminForm">

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
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_JUX_REAL_ESTATE_COMPANY_FILTER_SEARCH_DESC'); ?></label>
                    <input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_COMPANY_FILTER_SEARCH_DESC'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JUX_REAL_ESTATE_COMPANY_FILTER_SEARCH_DESC'); ?>" />
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
                        <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                    </select>
                </div>
            </div>
            <div class="clearfix"> </div>
            <table class="table table-striped" id="articleList">
                <thead>
                    <tr>
                        <th width="1%" class="nowrap center hidden-phone">
                            <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'c.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                        </th>
                        <th width="1%" class="hidden-phone">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th width="1%" style="min-width:55px" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'JSTATUS', 'c.published', $listDirn, $listOrder); ?>
                        </th>

                        <th width="10%">
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_IMAGE'); ?>
                        </th>
                        <th width="1%">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_NAME'), 'c.name', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_EMAIL'), 'c.email', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_PHONE'), 'c.phone', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_WEBSITE'), 'c.website', $listDirn, $listOrder); ?>
                        </th>

                        <th width="1%">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_AGENTS'), 'agent_count', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_REALTIES'), 'realty_count', $listDirn, $listOrder); ?>
                        </th>

                        <th width="1%">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_FEATURED'), 'featured', $listDirn, $listOrder); ?>
                        </th>

                        <th width="1%">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'c.access_level', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" nowrap="nowrap">
                            <?php echo JHTML::_('grid.sort', JText::_('JGRID_HEADING_ID'), 'c.id', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
                <?php
                $ordering = ($listOrder == 'c.ordering');
                if (count($this->items) > 0) :
                    foreach ($this->items as $i => $item) :

                        $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
                        $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.company.' . $item->id);
                        $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                        $canEditOwn = $user->authorise('core.edit.own', 'com_jux_real_estate.company.' . $item->id);
                        $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate.company.' . $item->id) && $canCheckin;
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
                                <?php else : ?>
                                    <span class="sortable-handler inactive" >
                                        <i class="icon-menu"></i>
                                    </span>
                                <?php
                                endif;
                                //echo $item->ordering;
                                ?>
                            </td>
                            <td class="center hidden-phone">
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td class="center">
                                <?php echo JHtml::_('jgrid.published', $item->published, $i, '.companies.', $canChange, 'cb'); ?>
                            </td>

                            <td>
                                <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=company.edit&id=' . (int) $item->id); ?>">

                                    <?php if ($item->image) : ?>
                                        <img src="<?php echo JUri::root() . $item->image; ?>"  style="width:100px; height:100px" />
                                    <?php else : ?>
                                        <img src="<?php echo JUri::base() . 'components/com_jux_real_estate/assets/img/no-image.jpg' ?>"  style="width:100px; height:100px"/>

                                    <?php endif; ?>
                                </a>
                            </td>
                            <td>
                                <?php if ($item->checked_out) : ?>
                                    <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'companies.', $canCheckin); ?>
                                <?php endif; ?>
                                <?php if ($canEdit || $canEditOwn) : ?>
                                    <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=company.edit&id=' . (int) $item->id); ?>">
                                        <?php echo $this->escape($item->name); ?></a>
                                    <?php else : ?>
                                    <?php echo $this->escape($item->name); ?>
                                <?php endif; ?>
                                <p class="smallsub">
                                    <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?></p>
                            </td>

                            <td>
                                <?php echo ($item->email) ? $item->email : '--'; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo ($item->phone) ? $item->phone : '--'; ?>&nbsp;
                            </td>

                            <td>
                                <?php echo ($item->website) ? $item->website : '--'; ?>&nbsp;
                            </td>


                            <td align="center">
                                <?php echo $item->agent_count; ?>
                            </td>
                            <td align="center">
                                <?php echo $item->realty_count; ?>
                            </td>
                            <td align="center">
                                <?php echo JHtml::_('jux_real_estate.featured', $item->featured, $i, $canChange, 'companies'); ?>
                            </td>



                            <td class="center">
                                <?php echo $this->escape($item->access_level); ?>
                            </td>
                            <td align="center">
                                <?php echo $item->id; ?>
                            </td>

                        </tr>

                        <?php
                    endforeach;
                else :
                    ?>
                    <tr>
                        <td colspan="14" class="center">
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_NO_RESULTS'); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>

            <!--code 2.5-->

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