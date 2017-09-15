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
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate');
?>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=states'); ?>" method="post" name="adminForm"
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
            <select name="filter_country_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_COUNTRY') . ' -';?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jux_real_estate.country','','','','','', true), 'value', 'text', $this->state->get('filter.country_id'));?>
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
            <td colspan="10">
                <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>
        <tbody>
        <?php
        if (count($this->items) > 0) :
            foreach ($this->items as $i => $item)
            {
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
                    <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=state.edit&id=' . (int)$item->id); ?>">
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
        else : ?>
         <tr>
             <td colspan="6" class="center">
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