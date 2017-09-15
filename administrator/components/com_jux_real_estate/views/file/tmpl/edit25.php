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
defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_jux_fileseller&view=filehandler&tmpl=component&function='.$this->function.'&param='.$this->param.'&'.JSession::getFormToken().'=1');?>" method="post" name="adminForm" enctype="multipart/form-data" id="adminForm">
	<?php echo $this->loadTemplate('upload'); ?>
	<?php if ($this->show_choose_file) {?>
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JUXARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JUX_FILESELLER_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JUXARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JUXARCH_FILTER_CLEAR'); ?></button>
		</div>
	</fieldset>
	<div class="clr"> </div>
<!--	<table class="adminform">
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_('COM_JUX_FILESELLER_FILTER') . ':'; ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_('COM_JUX_FILESELLER_GO'); ?></button>
				<button onclick="this.form.getElementById('search').value=''; this.form.getElementById('filter_state').value = '0'; this.form.getElementById('product_id').value = '0'; this.form.submit();"><?php echo JText::_('COM_JUX_FILESELLER_RESET'); ?></button>
			</td>
		</tr>
	</table>-->
	<div id="editcell">
		<table class="adminlist" cellpadding="4">
			<thead>
				<tr>
					<th width="5">
						<?php echo JText::_('COM_JUX_FILESELLER_NUM'); ?>
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort', JText::_('COM_JUX_FILESELLER_NAME'), 'name',$listDirn, $listOrder); ?>
						<?php //echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th width="30">
						<?php echo JText::_('COM_JUX_FILESELLER_DELETE'); ?>
					</th>
					<th width="25%">
						<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_DATE'), 'filedate', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
					<th width="10%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_EXTENSION'), 'ext', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
					<th width="10%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', JText::_('COM_JUX_FILESELLER_SIZE'), 'filesize', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="6">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->files); $i < $n; $i++) {
				$row =& $this->files[$i];
				
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td nowrap="nowrap">
						<a title="<?php echo $row->name; ?>" href="javascript: void(0);" onclick="window.parent.jux_selectFile(<?php echo $this->param; ?>, '<?php echo $row->name; ?>');">
							<?php echo $row->name; ?>
						</a>
					</td>
					<td align="center">
						<a class="delete-item" title="Remove" href="index.php?option=com_jux_fileseller&amp;task=filehandler.delete&amp;tmpl=component&amp;rm[]=<?php echo $row->name; ?>&amp;function=<?php echo $this->function; ?>&amp;param=<?php echo $this->param; ?>">
<!--						<a class="delete-item" title="Remove" href="index.php?option=com_jux_fileseller&amp;task=delete&amp;controller=filehandler&amp;tmpl=component&amp;rm[]=<?php echo $row->name; ?>">-->
							<?php echo JHTML::_('jux_fileseller.icon', 0, JText::_('COM_JUX_FILESELLER_DELETE'),null,null,'publish_r.png'); ?>
						</a>
					</td>
					<td align="center" nowrap="nowrap">
						<?php echo $row->date; ?>
					</td>
					<td align="center" nowrap="nowrap">
						<?php echo $row->ext; ?>
					</td>
					<td align="center" nowrap="nowrap">
						<?php echo $row->size; ?>
					</td>
				</tr>
			<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
	</div>
	
	
	<?php
	}
	?>
	<input type="hidden" name="option" value="com_jux_fileseller" />
	<input type="hidden" name="controller" value="filehandler" />
	<input type="hidden" name="view" value="filehandler" />
	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<input type="hidden" name="file_id" value="<?php //echo $this->file_id; ?>" />
	
	<?php echo JHTML::_('form.token'); ?>
</form>

<?php
echo JUX_FileSellerFactory::getFooter();