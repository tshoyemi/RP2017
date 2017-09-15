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
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate.company');
$saveOrder = $listOrder == 'c.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=companies'); ?>" method="post" name="adminForm">
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

            <select name="filter_access" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access')); ?>
            </select>

        </div>
    </fieldset>
    <div class="clr"></div>

    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%">
                    <?php echo JText::_('#'); ?>
                </th>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                           onclick="Joomla.checkAll(this)"/>
                </th>
                <th width="3%">
                    <?php echo JText::_('COM_JUX_REAL_ESTATE_IMAGE'); ?>
                </th>
                <th width="20%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_NAME'), 'c.name', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_EMAIL'), 'c.email', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_PHONE'), 'c.phone', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_WEBSITE'), 'c.website', $listDirn, $listOrder); ?>
                </th>

                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_AGENTS'), 'agent_count', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_REALTIES'), 'realty_count', $listDirn, $listOrder); ?>
                </th>

                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'c.published', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_FEATURED'), 'featured', $listDirn, $listOrder); ?>
                </th>
                <th width="13%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_ORDER', 'c.ordering', $listDirn, $listOrder); ?>
                    <?php if ($listOrder) echo JHTML::_('grid.order', $this->items, 'filesave.png', 'companies.saveorder'); ?>
                </th>

                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'c.access_level', $listDirn, $listOrder); ?>
                </th>
                <th width="1%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', JText::_('JGRID_HEADING_ID'), 'c.id', $listDirn, $listOrder); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="14">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
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
                    <tr class="row<?php echo $i % 2; ?>">
                        <td>
                            <?php echo $this->pagination->getRowOffset($i); ?>
                        </td>
                        <td>
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>

                        <td>
                            <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=company.edit&id=' . (int) $item->id); ?>">

                                <?php if ($item->image) : ?>
                                    <img src="<?php echo JUri::root() . $item->image; ?>" width="100" height="100" />
                                <?php else : ?>
                                    <img src="<?php echo JUri::base() . 'components/com_jux_real_estate/assets/img/no-image.jpg' ?>" />

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
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'companies.', $canChange, 'cb'); ?>
                        </td>
                        <td align="center">
                            <?php echo JHtml::_('jux_real_estate.featured', $item->featured, $i, $canChange, 'companies'); ?>
                        </td>

                        <td class="order">
                            <?php
                            if ($canChange) {
                                if ($saveOrder) {
                                    if ($listDirn == 'asc') {
                                        echo '<span>' . $this->pagination->orderUpIcon($i, true, 'companies.orderup', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                        echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'companies.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                                    } else if ($listDirn == 'desc') {
                                        echo '<span>' . $this->pagination->orderUpIcon($i, true, 'companies.orderdown', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                        echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'companies.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                                    }
                                }
                                $disabled = $saveOrder ? '' : 'disabled="disabled"';
                                echo '<input type="text" name="order[]" size="5" value="' . $item->ordering . '" ' . $disabled . ' class="text-area-order" />';
                            } else {
                                echo $item->ordering;
                            }
                            ?>
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
        </tbody>
    </table>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
<?php echo JHTML::_('form.token'); ?>
</form>
<?php
echo JUX_Real_EstateFactory::getFooter();