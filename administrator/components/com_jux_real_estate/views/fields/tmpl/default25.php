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
defined('_JEXEC') or die( 'Restricted access' );
// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate.field');
$saveOrder = $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=fields'); ?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JUXARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JUXARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JUXARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">

			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false, 'trash' => false)), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

            <select name="filter_language" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
                <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
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
				<th style="text-align: left;">
					<?php echo JHTML::_('grid.sort',  'COM_JUX_REAL_ESTATE_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th style="text-align: left;">
					<?php echo JHTML::_('grid.sort',  'COM_JUX_REAL_ESTATE_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th style="text-align: left;">
					<?php echo JHTML::_('grid.sort',  'COM_JUX_REAL_ESTATE_FIELD_TYPE', 'a.field_type', $listDirn, $listOrder ); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',  'COM_JUX_REAL_ESTATE_REQUIRE', 'a.required', $listDirn, $listOrder ); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',  'COM_JUX_REAL_ESTATE_PUBLISHED', 'a.published', $listDirn, $listOrder ); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort',  'COM_JUX_REAL_ESTATE_ACCESS', 'a.access', $listDirn, $listOrder ); ?>
				</th>
				
                <th width="13%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_ORDER', 'a.ordering', $listDirn, $listOrder); ?>
                    <?php if ($listOrder) echo JHTML::_('grid.order', $this->items, 'filesave.png', 'fields.saveorder'); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
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

		$ordering = ($listOrder == 'a.ordering');
        foreach ($this->items as $i => $item)  :

            $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
            $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.field.' . $item->id);
            $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
            $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate.field.' . $item->id) && $canCheckin;

			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset( $i ); ?>
				</td>
				<td>
                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>

                <td>
                    <?php if ($item->checked_out) : ?>
                        <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'fields.', $canCheckin); ?>
                    <?php endif; ?>
                    <?php if ($canEdit || $canEditOwn) : ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=field.edit&id=' . (int)$item->id); ?>">
                        <?php echo $this->escape($item->name); ?></a>
                    <?php else : ?>
                        <?php echo $this->escape($item->name); ?>
                    <?php endif; ?>

                </td>

				<td>

                    <?php if ($canEdit || $canEditOwn) : ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=field.edit&id='.(int) $item->id); ?>">
                        <?php echo $this->escape($item->title); ?></a>
                    <?php else : ?>
                    <?php echo $this->escape($item->title); ?>
                    <?php endif; ?>

				</td>
				<td>
					<?php
						$fieldTypes = array(
									0 => Jtext::_('COM_JUX_REAL_ESTATE_TEXTBOX'),
									1 => Jtext::_('COM_JUX_REAL_ESTATE_TEXTAREA'),
									2 => Jtext::_('COM_JUX_REAL_ESTATE_DROPDOWN'),
									3 => Jtext::_('COM_JUX_REAL_ESTATE_CHECKBOX_LIST') ,
									4 => Jtext::_('COM_JUX_REAL_ESTATE_RADIO_LIST') ,
									5 => Jtext::_('COM_JUX_REAL_ESTATE_DATE_TIME')
									) ;
						echo $fieldTypes[$item->field_type] ;
					?>
				</td>
				<td align="center">
                    <?php echo JHtml::_('jux_real_estate.requiredfield', $item->required, $i, $canChange); ?>
				</td>
				<td align="center">
                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'fields.', $canChange, 'cb'); ?>
				</td>
				<td align="center">
                    <?php echo JHtml::_('jux_real_estate.accessfield', $item->access, $i, $canChange); ?>
				</td>
                <td class="order">
                    <?php if ($canChange) {
                    if ($saveOrder) {
                        if ($listDirn == 'asc') {
                            echo '<span>' . $this->pagination->orderUpIcon($i, true, 'fields.orderup', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                            echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'fields.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                        } else if ($listDirn == 'desc') {
                            echo '<span>' . $this->pagination->orderUpIcon($i, true, 'fields.orderdown', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                            echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'fields.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                        }
                    }
                    $disabled = $saveOrder ? '' : 'disabled="disabled"';
                    echo '<input type="text" name="order[]" size="5" value="' . $item->ordering . '" ' . $disabled . ' class="text-area-order" />';
                } else {
                    echo $item->ordering;
                } ?>
                </td>
                <td class="center">
                    <?php if ($item->language == '*') {
                    echo JText::alt('JALL', 'language');
                } else {
                    echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED');
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

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>