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
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate');
$saveOrder = $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=openhouses'); ?>" method="post" name="adminForm"
      id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JUXARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search"
                   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                   title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>"/>

            <button type="submit" class="btn"><?php echo JText::_('JUXARCH_FILTER_SUBMIT'); ?></button>
            <button type="button"
                    onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JUXARCH_FILTER_CLEAR'); ?></button>
        </div>
        <div class="filter-select fltrt">

            <select name="filter_published" id="filter_published" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false, 'trash' => false, 'all' => false)), 'value', 'text', $this->state->get('filter.published'), true);?>
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
            <th width="20">
                <input type="checkbox" name="checkall-toggle" value=""
                       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_TITLE', 'a.name', $listDirn, $listOrder); ?>
                / <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_STREET'), 'r.street', $listDirn, $listOrder); ?>
                / <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_TITLE'), 'r.title', $listDirn, $listOrder); ?>
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
            <th>
                <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
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
            <tr class="row<?php echo $i % 2; ?>">
                <td>
                    <?php echo $this->pagination->getRowOffset($i); ?>
                </td>
                <td>
                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                    <?php if ($item->checked_out) : ?>
                    <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'openhouses.', $canCheckin); ?>
                    <?php endif; ?>
                    <?php if ($item->name) echo '<em>' . $this->escape($item->name) . '</em><br />'; ?>
                    <?php if ($canEdit) : ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=openhouse.edit&id=' . (int)$item->id); ?>">
                        <?php echo $this->escape($item->street_address); ?></a>
                    <?php else : ?>
                    <?php echo $this->escape($item->street_address); ?>
                    <?php endif; ?>
                    <?php if ($item->title) echo '<br />' . $this->escape($item->title);?>
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
                            if ($x < count($agents)) echo '<br />';
                        }
                    } else {
                        echo '--';
                    }
                    ?>
                </td>
                <td><?php echo JUX_Real_EstateHelperQuery::getCompanyName($item->company_id); ?></td>
                <td align="center">
                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'openhouses.', $canChange, 'cb'); ?>
                </td>

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

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHTML::_('form.token'); ?>
</form>
<?php
echo JUX_Real_EstateFactory::getFooter();