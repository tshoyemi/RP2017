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
?>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=messages'); ?>" method="post" name="adminForm">
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

            <select name="filter_status" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                <?php echo JHtml::_('select.options', JUX_Real_EstateHelper::getStatusOptions(), 'value', 'text', $this->state->get('filter.status'), true);?>
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
                <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_FROM_USER'), 'a.name', $listDirn, $listOrder); ?>
            </th>
            <th style="text-align: left;">
                <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_EMAIL'), 'a.email', $listDirn, $listOrder); ?>
            </th>
            <th style="text-align: left;">
                <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_REALTY'), 'r.title', $listDirn, $listOrder); ?>
            </th>
            <th style="text-align: left;">
                <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_SUBJECT'), 'a.subject', $listDirn, $listOrder); ?>
            </th>
            <th style="text-align: left;">
                <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_CONTENT'), 'a.content', $listDirn, $listOrder); ?>
            </th>
            <th style="text-align: left;">
                <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_DATE'), 'a.date_created', $listDirn, $listOrder); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_AGENT'), 'm.name', $listDirn, $listOrder); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_STATUS'), 'a.status', $listDirn, $listOrder); ?>
            </th>
            <th width="1%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', JText::_('JGRID_HEADING_ID'), 'a.id', $listDirn, $listOrder); ?>
            </th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="12">
                <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>
        <tbody>
        <?php
        $img_src = JURI::root() . 'components/com_jux_real_estate/templates/default/images/';
        foreach ($this->items AS $i => $item) :
            $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.realty.' . $item->id);
            $stt_img = $item->status ? 'tick.png' : 'publish_x.png';
            $stt_alt = $item->status ? JText::_('COM_JUX_REAL_ESTATE_READ') : JText::_('COM_JUX_REAL_ESTATE_NOT_READ');
            $stt_href = JHtml::_('image', 'admin/' . $stt_img, $stt_alt, NULL, true);

            ?>
        <tr class="row<?php echo $i % 2; ?>">
            <td>
                <?php echo $this->pagination->getRowOffset($i); ?>
            </td>
            <td>
                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
            </td>
            <td>
                <?php echo $item->name; ?>
            </td>
            <td>
                <?php echo $item->email; ?>
            </td>
            <td>
                <?php if ($canEdit) : ?>
                <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=realty.edit&id=' . (int)$item->id); ?>">
                    <?php echo $this->escape($item->realty); ?></a>
                <?php else : ?>
                <?php echo $this->escape($item->realty); ?>
                <?php endif; ?>
            </td>
            <td>
                <?php echo $item->subject; ?>
            </td>
            <td>
                <?php echo $item->content; ?>
            </td>
            <td>
                <?php echo date('d-m-Y h:i:s A', strtotime($item->date_created)); ?>
            </td>
            <td>
                <?php echo $item->agent; ?>
            </td>
            <td align="center">
                <?php echo $stt_href; ?>
            </td>
            <td align="center">
                <?php echo $item->id; ?>
            </td>
        </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <input type="hidden" name="controller" value="messages"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHTML::_('form.token'); ?>
</form>