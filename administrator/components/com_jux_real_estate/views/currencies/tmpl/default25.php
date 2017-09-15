<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate.currency');
$saveOrder = $listOrder == 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=currencies'); ?>" method="post" name="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JUXARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_SEARCH_DESC'); ?>" />
            <button type="submit" class="btn"><?php echo JText::_('JUXARCH_FILTER_SUBMIT');?> </button>
            <button type="button" onclick="document.id('filter_search').value =''; this.form.submit();"><?php echo JText::_('JUXARCH_FILTER_CLEAR');?></button>
        </div>
        <div class="filter-select fltrt">
            <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false, 'trash' => false)), 'value', 'text', $this->state->get('filter.published'), true); ?>
            </select> 
        </div>
    </fieldset>
    <div class="clr"> </div>
    <table class="adminlist">
            <thead>
                    <tr>
                            <th width="5">
                                <?php echo JText::_( '#' ); ?>
                            </th>
                            <th width="1%">
                                <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                                       onclick="Joomla.checkAll(this)"/>
                            </th>
                            <th class="title">
                                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_TITLE'), 'a.title', $listDirn, $listOrder); ?>
                            </th>
                            <th width="10%" nowrap="nowrap">
                                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_CODE'), 'a.code', $listDirn, $listOrder); ?>
                            </th>
                            <th width="10%" nowrap="nowrap">
                                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_SIGN'), 'a.sign', $listDirn, $listOrder); ?>
                            </th>
                            <th width="10%" nowrap="nowrap">
                                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_POSITION'), 'a.position', $listDirn, $listOrder); ?>
                            </th>
                            <th width="5%" nowrap="nowrap">
                                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_PUBLISHED'), 'a.published', $listDirn, $listOrder); ?>
                            </th>
                            <th width="8%" nowrap="nowrap">
                                <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_ORDER', 'a.ordering', $listDirn, $listOrder); ?>
                                <?php if ($listOrder) echo JHTML::_('grid.order', $this->items, 'filesave.png', 'currencies.saveorder'); ?>
                            </th>
                            <th width="1%" nowrap="nowrap">
                                <?php echo JHTML::_('grid.sort', JText::_('JGRID_HEADING_ID'), 'a.id', $listDirn, $listOrder); ?>
                            </th>
                    </tr>
            </thead>
            <tfoot>
                    <tr>
                            <td colspan="9">
                                    <?php echo $this->pagination->getListFooter(); ?>
                            </td>
                    </tr>
            </tfoot>
            <tbody>
            <?php

            $ordering = ($listOrder == 'a.ordering');
            foreach ($this->items as $i => $item)  :
                $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
                $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.currency.' . $item->id);
                $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate.currency.' . $item->id) && $canCheckin;

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
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'currencies.', $canCheckin); ?>
                            <?php endif; ?>
                            <?php if ($canEdit || $canEditOwn) : ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=currency.edit&id=' . (int)$item->id); ?>">
                                <?php echo $this->escape($item->title); ?></a>
                            <?php else : ?>
                            <?php echo $this->escape($item->title); ?>
                            <?php endif; ?>

                        </td>

                        <td align="center">
                                <?php echo $item->code; ?>
                        </td>
                        <td align="center">
                                <?php echo $item->sign; ?>
                        </td>
                        <td align="center">
                                <?php echo $item->position ? JText::_('COM_JUX_REAL_ESTATE_RIGHT') : JText::_('COM_JUX_REAL_ESTATE_LEFT'); ?>
                        </td>
                        <td align="center">
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'currencies.', $canChange, 'cb'); ?>
                        </td>
                        <td class="order">
                            <?php if ($canChange) {
                            if ($saveOrder) {
                                if ($listDirn == 'asc') {
                                    echo '<span>' . $this->pagination->orderUpIcon($i, true, 'currencies.orderup', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'currencies.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                                } else if ($listDirn == 'desc') {
                                    echo '<span>' . $this->pagination->orderUpIcon($i, true, 'currencies.orderdown', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'currencies.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                                }
                            }
                            $disabled = $saveOrder ? '' : 'disabled="disabled"';
                            echo '<input type="text" name="order[]" size="5" value="' . $item->ordering . '" ' . $disabled . ' class="text-area-order" />';
                        } else {
                            echo $item->ordering;
                        } ?>
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
    <?php echo JHTML::_('form.token'); ?>
</form>

<?php
echo JUX_Real_EstateFactory::getFooter();