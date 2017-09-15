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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate.plan');
$saveOrder = $listOrder == 'a.ordering';

$db = JFactory::getDBO();
$nullDate = $db->getNullDate();
?>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=plans'); ?>" method="post" name="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JUXARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search"
                   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                   title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>"/>

            <button type="submit" class="btn"><?php echo JText::_('JUXARCH_FILTER_SUBMIT'); ?></button>
            <button type="button"
                    onclick="document.id('filter_search').value = '';
                            this.form.submit();"><?php echo JText::_('JUXARCH_FILTER_CLEAR'); ?></button>
        </div>
        <div class="filter-select fltrt">

            <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false, 'trash' => false)), 'value', 'text', $this->state->get('filter.published'), true); ?>
            </select>
        </div>
    </fieldset>
    <div class="clr"></div>

    <table class="adminlist">
        <thead>
            <tr>
                <th width="5">
                    <?php echo JText::_('#'); ?>
                </th>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value=""
                           title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                           onclick="Joomla.checkAll(this)"/>
                </th>
                <th style="text-align: left;">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_NAME', 'a.name', $listDirn, $listOrder); ?>
                </th>

                <th style="text-align: left;">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_DESCRIPTION', 'a.description', $listDirn, $listOrder); ?>
                </th>
                <th style="text-align: left;">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_IMAGE', 'a.image', $listDirn, $listOrder); ?>
                </th>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_PRICE', 'a.price', $listDirn, $listOrder); ?>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_CURRENCY', 'currency', $listDirn, $listOrder); ?>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_DAYS', 'a.days', $listDirn, $listOrder); ?>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_COUNT_LIMIT', 'a.count_limit', $listDirn, $listOrder); ?>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_START_DATE', 'a.publish_up', $listDirn, $listOrder); ?>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_END_DATE', 'a.publish_down', $listDirn, $listOrder); ?>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_CREATED_DATE', 'a.date_created', $listDirn, $listOrder); ?>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_FEATURED'), 'a.featured', $listDirn, $listOrder); ?>
                </th>
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
                </th>
                <th width="13%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_ORDER', 'a.ordering', $listDirn, $listOrder); ?>
                    <?php if ($listOrder) echo JHTML::_('grid.order', $this->items, 'filesave.png', 'agents.saveorder'); ?>
                </th>
                <th width="1%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', JText::_('JGRID_HEADING_ID'), 'a.id', $listDirn, $listOrder); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="16">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            $link_img = JUri::base() . 'image/';
            $ordering = ($listOrder == 'a.ordering');
            foreach ($this->items AS $i => $item) :
                $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
                $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.plan.' . $item->id);
                $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate.plan.' . $item->id) && $canCheckin;
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td>

                        <?php if ($item->checked_out) : ?>
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'plans.', $canCheckin); ?>
                        <?php endif; ?>

                        <?php if ($canEdit) : ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=plan.edit&id=' . (int) $item->id); ?>">
                                <?php echo $this->escape($item->name); ?></a>
                            <?php else : ?>
                            <?php echo $this->escape($item->name); ?>
                        <?php endif; ?>

                    </td>
                    <td>
                        <?php echo strip_tags($item->description); ?>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=plan.edit&id=' . (int) $item->id); ?>">

                            <?php if ($item->image) : ?>
                                <img src="<?php echo JUri::root() . $item->image; ?>" width="100" height="100" />
                            <?php else : ?>
                                <img src="<?php echo $this->path_no_image . 'no-avatar.png'; ?>" width="100" height="100"
                                     alt="<?php echo $item->name; ?>"/>
                                 <?php endif; ?>
                        </a>
                    </td>
                    <td align="center">
                        <?php
                        if ($item->price > 0)
                            echo number_format($item->price, 0, '.', $this->configs->get('thousand_separator'));
                        else
                            echo JText::_('COM_JUX_REAL_ESTATE_FREE');
                        ?>
                    </td>
                    <td align="center">
                        <?php echo $item->currency; ?>
                    </td>
                    <td align="center">
                        <?php
                        if (!empty($item->days)) {
                            if ($item->days_type == "day")
                                echo $item->days . ' ' . JText::_('COM_JUX_REAL_ESTATE_DAYS');
                            else
                                echo $item->days . ' ' . JText::_('COM_JUX_REAL_ESTATE_MONTHS');
                        } else {
                            echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED');
                        }
                        ?>
                    </td>
                    <td align="center">
                        <?php if (!empty($item->count_limit)) { ?>
                            <?php echo $item->count_limit; ?>
                        <?php } else { ?>
                            <?php echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED'); ?>
                        <?php } ?>
                    </td>
                    <td align="center">
                        <?php echo JHTML::_('date', $item->publish_up, 'd-m-Y'); ?>
                    </td>
                    <td align="center">
                        <?php
                        if ($item->publish_down == $nullDate)
                            echo JText::_('COM_JUX_REAL_ESTATE_NEVER');
                        else
                            echo JHTML::_('date', $item->publish_down, 'd-m-Y');
                        ?>
                    </td>
                    <td align="center">
                        <?php echo JHTML::_('date', $item->date_created, 'd-m-Y'); ?>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jux_real_estate.featured', $item->featured, $i, $canChange, 'plans'); ?>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jgrid.published', $item->published, $i, 'plans.', $canChange, 'cb'); ?>
                    </td>
                    <td class="order">
                        <?php
                        if ($canChange) {
                            if ($saveOrder) {
                                if ($listDirn == 'asc') {
                                    echo '<span>' . $this->pagination->orderUpIcon($i, true, 'plans.orderup', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'plans.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                                } else if ($listDirn == 'desc') {
                                    echo '<span>' . $this->pagination->orderUpIcon($i, true, 'plans.orderdown', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'plans.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                                }
                            }
                            $disabled = $saveOrder ? '' : 'disabled="disabled"';
                            echo '<input type="text" name="order[]" size="5" value="' . $item->ordering . '" ' . $disabled . ' class="text-area-order" />';
                        } else {
                            echo $item->ordering;
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
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="controller" value="plans"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
<?php echo JHTML::_('form.token'); ?>
</form>