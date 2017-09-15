<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla.Administrator
 * @subpackage	com_jux_fileseller
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_jux_fileseller');
$saveOrder	= $listOrder == 'a.ordering';

$db			= JFactory::getDBO();
$nulldate	= $db->getNullDate();
?>

<form action="<?php echo JRoute::_('index.php?option=com_jux_fileseller&view=files'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JUXARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JUX_FILESELLER_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JUXARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JUXARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">

			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('JGLOBAL_TITLE'), 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_FILE_NAME'), 'a.file_name', $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_EXTENSION'), 'a.ext', $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_SIZE'), 'a.size', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('JSTATUS'), 'a.published', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder) :?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'files.saveorder'); ?>
					<?php endif; ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_UPLOAD'), 'a.created', $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_DOWNLOADS'), 'downloads', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_ID'), 'a.id', $listDirn, $listOrder); ?>
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
			foreach ($this->items as $i => $item) {
				$ordering	= ($listOrder == 'a.ordering');
				$canCreate	= $user->authorise('core.create', 'com_jux_fileseller');
				$canEdit	= $user->authorise('core.edit', 'com_jux_fileseller');
				$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
				$canChange	= $user->authorise('core.edit.state',	'com_jux_fileseller') && $canCheckin;
				$canEditOwn	= $user->authorise('core.edit.own',		'com_jux_fileseller');

				$link		= JRoute::_('index.php?option=com_jux_fileseller&task=file.edit&id=' . $item->id);
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'files.', $canCheckin); ?>
					<?php endif; ?>
					<?php if ($canEdit || $canEditOwn) : ?>
						<a href="<?php echo $link;?>">
							<?php echo $this->escape($item->title); ?>
						</a>
					<?php else : ?>
						<?php echo $this->escape($item->title); ?>
					<?php endif; ?>
				</td>
				<td>
					<?php echo $this->escape($item->file_name);?>
				</td>
				<td>
					<?php echo $this->escape($item->ext);?>
				</td>
				<td>
					<?php echo $this->escape($item->size);?>
				</td>
				<td align="center">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'files.', $canChange, 'cb'); ?>
				</td>
				<td class="order" align="center">
					<?php if ($canChange)
					{
						if ($saveOrder) {
							if ($listDirn == 'asc') {
								echo '<span>'. $this->pagination->orderUpIcon($i, true, 'files.orderup', 'JLIB_HTML_MOVE_UP', $ordering).'</span>';
								echo '<span>'.$this->pagination->orderDownIcon($i, $this->pagination->total, true, 'files.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering).'</span>';
							} else if ($listDirn == 'desc') {
								echo '<span>'. $this->pagination->orderUpIcon($i, true, 'files.orderdown', 'JLIB_HTML_MOVE_UP', $ordering).'</span>';
								echo '<span>'.$this->pagination->orderDownIcon($i, $this->pagination->total, true, 'files.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering).'</span>';
							}
						}
						$disabled = $saveOrder ?  '' : 'disabled="disabled"';
						echo '<input type="text" name="order[]" size="5" value="'.$item->ordering.'" '.$disabled.' class="text-area-order" />';
					} else {
						echo $item->ordering;
					} ?>
				</td>
				<td>
					<?php echo JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
				</td>
				<td>
					<?php echo $item->downloads; ?>
				</td>
				<td align="center">
					<?php echo $item->id; ?>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<?php
echo JUX_FilesellerFactory::getFooter();