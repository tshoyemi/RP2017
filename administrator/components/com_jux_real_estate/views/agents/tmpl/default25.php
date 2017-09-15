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
JHtml::_('behavior.modal');
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate.agent');
$saveOrder = $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=agents'); ?>" method="post" name="adminForm">
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
                <th width="2%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_FIRST_NAME', 'a.first_name', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_LAST_NAME', 'a.last_name', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_AVATAR', 'a.avatar', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_EMAIL', 'a.email', $listDirn, $listOrder); ?>
                </th>
                <th width="5%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_DAYS_LEFT', 'days', $listDirn, $listOrder); ?>
                </th>

                <th width="1%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_COUNT', 'a.count', $listDirn, $listOrder); ?>
                </th>
                <th style="text-align: left;">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_REMAINING', 'a.count_limit', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_PAYMENT_METHOD', 'a.payment_method', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_CREATED_DATE', 'a.date_created', $listDirn, $listOrder); ?>
                </th>
                <th>
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_PAYMENT_DATE', 'a.date_paid', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_FEATURED', 'a.featured', $listDirn, $listOrder); ?>
                </th>
                <th width="5%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_APPROVED', 'a.approved', $listDirn, $listOrder); ?>
                </th>
                <th width="5%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
                </th>
                <th width="5%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_PLAN', 'plan', $listDirn, $listOrder); ?>
                </th>
                <th width="8%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_ORDER', 'a.ordering', $listDirn, $listOrder); ?>
                    <?php if ($listOrder) echo JHTML::_('grid.order', $this->items, 'filesave.png', 'agents.saveorder'); ?>
                </th>
                <th width="1%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="17">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            $k = 0;
            $ordering = ($listOrder == 'a.ordering');
            foreach ($this->items AS $i => $item) :

                $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
                $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.agent.' . $item->id);
                $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate.agent.' . $item->id) && $canCheckin;
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
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'agents.', $canCheckin); ?>
                        <?php endif; ?>

                        <?php if ($canEdit) : ?>
                            <span class="editlinktip hasTip"
                                  title="<?php echo JText::_('COM_JUX_REAL_ESTATE_EDIT_AGENT'); ?>::<?php echo $this->escape($item->name); ?>">
                                <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=agent.edit&id=' . (int) $item->id); ?>">
                                    <?php echo $this->escape($item->first_name); ?></a>
                            </span>
                        <?php else : ?>
                            <?php echo $this->escape($item->first_name); ?>
                        <?php endif; ?>

                    </td>
                    <td>
                        <?php echo $item->last_name; ?>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=agent.edit&id=' . (int) $item->id); ?>">

                            <?php if ($item->avatar) : ?>
                                <img src="<?php echo JUri::root() . $item->avatar; ?>" width="100" height="100" />
                            <?php else : ?>
                                <img src="<?php echo JUri::base() . 'components/com_jux_real_estate/assets/img/no-avatar.png'; ?>" width="100" height="100"
                                     alt="<?php echo $item->avatar; ?>"/>
                                 <?php endif; ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $item->email; ?>
                    </td>
                    <td align="center">
                        <?php
                        if ($item->days) {
                            echo $item->sub_days;
                        } else {
                            echo JText::_('COM_JUX_REAL_ESTATE_NEVER');
                        }
                        ?>
                    </td>

                    <td align="center">
                        <?php echo $item->count; ?>
                    </td>
                    <td align="center">
                        <?php
                        if ($item->plan_countlimit)
                            echo $item->count_limit;
                        else
                            echo JText::_('COM_JUX_REAL_ESTATE_UNLIMITED');
                        ?>
                    </td>
                    <td align="center">
                        <?php echo $item->payment_method; ?>
                    </td>
                    <td align="center">
                        <?php echo JHTML::_('date', $item->date_created, JText::_('DATE_FORMAT_LC4')); ?>
                    </td>
                    <td align="center">
                        <?php echo JHTML::_('date', $item->date_paid, JText::_('DATE_FORMAT_LC4')); ?>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jux_real_estate.featured', $item->featured, $i, $canChange, 'agents'); ?>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jux_real_estate.approve', $item->approved, $i, $canChange); ?>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jgrid.published', $item->published, $i, 'agents.', $canChange, 'cb'); ?>
                    </td>
                    <td align="center">
                        <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=plan.edit&id=' . $item->plan_id); ?>">
                            <?php echo $item->plan; ?>
                        </a>
                    </td>
                    <td class="order">
                        <?php
                        if ($canChange) {
                            if ($saveOrder) {
                                if ($listDirn == 'asc') {
                                    echo '<span>' . $this->pagination->orderUpIcon($i, true, 'agents.orderup', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'agents.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                                } else if ($listDirn == 'desc') {
                                    echo '<span>' . $this->pagination->orderUpIcon($i, true, 'agents.orderdown', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'agents.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
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
    <input type="hidden" name="controller" value="agents"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHTML::_('form.token'); ?>
</form>