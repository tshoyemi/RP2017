<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate');
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_jux_real_estate&task=files.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'filesList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
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

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=files'); ?>" method="post" name="adminForm" id="adminForm">
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
                    <label class="element-invisible" for="filter_search"><?php echo JText::_('JUXARCH_FILTER_LABEL'); ?></label>
                    <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JUX_FILESELLER_FILTER_SEARCH_DESC'); ?>" />
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
            </div>
            <div class="clearfix"> </div>
            <table class="adminlist table table-striped" id="filesList">
                <thead>
                    <tr>
                        <th width="1%" class="nowrap center hidden-phone">
                            <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                        </th>
                        <th width="20">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th width="5%" nowrap="nowrap">
                            <?php echo JHTML::_('grid.sort', JText::_('JSTATUS'), 'a.published', $listDirn, $listOrder); ?>
                        </th>
                        <th>
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_IMAGE'); ?>
                        </th>
                        <th class="title">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_REALTY_NAME'), 'a.realty_id', $listDirn, $listOrder); ?>
                        </th>
                        <th class="title">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_FILE_NAME'), 'a.file_name', $listDirn, $listOrder); ?>
                        </th>
                        <th class="title">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_SIZE'), 'a.size', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" nowrap="nowrap">
                            <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_ID'), 'a.id', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->items as $i => $item) {
                        $ordering = ($listOrder == 'a.ordering');
                        $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
                        $canEdit = $user->authorise('core.edit', 'com_jux_real_estate');
                        $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
                        $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate') && $canCheckin;
                        $canEditOwn = $user->authorise('core.edit.own', 'com_jux_real_estate');

                        $link = JRoute::_('index.php?option=com_jux_real_estate&task=file.edit&id=' . $item->id);
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">
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
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td align="center">
                                <?php echo JHtml::_('jgrid.published', $item->published, $i, 'files.', $canChange, 'cb'); ?>
                            </td>
                            <td>
                                <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=file.edit&id=' . (int) $item->id); ?>">

                                    <?php if ($item->file_name) : ?>
                                        <img src="<?php echo JUri::root() . '/media/com_jux_real_estate/realty_image/' . $item->realty_id . '/' . $item->file_name; ?>" width="100" height="100" />
                                    <?php else : ?>
                                        <img src="<?php echo $this->path_no_image . 'no-avatar.png'; ?>" width="100" height="100"
                                             alt="<?php echo $item->file_name; ?>"/>
                                         <?php endif; ?>
                                </a>
                            </td>

                            <td>
                                <?php
                                if ($item->realty_id != null) {
                                    echo '<a href="' . JRoute::_('index.php?option=com_jux_real_estate&task=realty.edit&id=' . (int) $item->realty_id) . '">';
                                    echo JUX_Real_EstateHelperQuery::getRealtyName($item->realty_id);
                                    echo '</a>';
                                } else {
                                    echo '<span class="invalid">' . JText::_('---') . '</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo $link; ?>">	
                                    <?php echo $this->escape($item->file_name); ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $this->escape($item->size); ?>
                            </td>
                            <td align="center">
                                <?php echo $item->id; ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
<!--		<tfoot>
                        <tr>
                                <td colspan="12">
                <?php // echo $this->pagination->getListFooter();  ?>
                                </td>
                        </tr>
                </tfoot>-->
            </table>
            <?php echo $this->pagination->getListFooter(); ?>
            <?php //Load the batch processing form.   ?>
            <?php //echo $this->loadTemplate('batch'); ?>
            <div>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
                <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </div>
</form>

<?php
echo JUX_Real_EstateFactory::getFooter();