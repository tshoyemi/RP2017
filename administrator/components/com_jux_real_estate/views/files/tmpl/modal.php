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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
$app = JFactory::getApplication();
$function	= $app->input->getCmd('function', 'jSelectFile');
$param		= $app->input->getCmd('param', '0');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo JRoute::_('index.php?option=com_jux_fileseller&view=files&layout=modal&tmpl=component&function='.$function.'&'.JSession::getFormToken().'=1');?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
	<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">
			<label class="element-invisible" for="filter_search"><?php echo JText::_('JUXARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JUX_FILESELLER_FILTER_SEARCH_DESC'); ?>" />
		</div>
		<div class="btn-group pull-left hidden-phone">
			<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JUXARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
			<button class="btn tip hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JUXARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
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
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('JGLOBAL_TITLE'), 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_EXTENSION'), 'a.ext', $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_SIZE'), 'a.size', $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_UPLOAD'), 'a.created', $listDirn, $listOrder); ?>
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
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $item->title; ?>',<?php echo $param; ?>);">
						<?php echo $this->escape($item->title); ?>
					</a>
				</td>
				<td>
					<?php echo $this->escape($item->ext);?>
				</td>
				<td>
					<?php echo $this->escape($item->size);?>
				</td>
				<td>
					<?php echo JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
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
	</div>
</form>
