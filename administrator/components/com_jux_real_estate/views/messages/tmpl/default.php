<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla
 * @subpackage		com_jux_real_estate
 * 
 * @copyright		Copyright (C) 2012 - 2015 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL, See LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$trashed = $this->state->get('filter.published') == -2 ? true : false;
$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_jux_real_estate&task=types.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
    Joomla.orderTable = function()
    {
	table = document.getElementById("sortTable");
	direction = document.getElementById("directionTable");
	order = table.options[table.selectedIndex].value;
	if (order != '<?php echo $listOrder; ?>')
	{
	    dirn = 'asc';
	}
	else
	{
	    dirn = direction.options[direction.selectedIndex].value;
	}
	Joomla.tableOrdering(order, dirn, '');
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=messages'); ?>" method="post" name="adminForm" id="adminForm">
    <!-- code 3.0 -->
    <?php if (!empty($this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
	    <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
	<?php else : ?>
    	<div id="j-main-container">
	    <?php endif; ?>
            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_JUX_REAL_ESTATE_MESSAGE_FILTER_SEARCH_DESC'); ?></label>
                    <input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_JUX_REAL_ESTATE_MESSAGE_FILTER_SEARCH_DESC'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JUX_REAL_ESTATE_FILTER_SEARCH_DESC'); ?>" />
                </div>
		<div class="btn-group pull-left hidden-phone">
                    <button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
		    <button class="btn tip hasTooltip" type="button" onclick="document.getElementById('filter_search').value = ''; this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
		    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
                    <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                        <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
                    </select>
                </div>
                <div class="btn-group pull-right">
                    <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
			<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                    </select>
                </div>
            </div>

            <div class="clearfix"> </div>
            <table class="table table-striped" id="articleList">
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
				    <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=realty.edit&id=' . (int) $item->id); ?>">
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
        </div>
    </div>
    <input type="hidden" name="controller" value="messages"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHTML::_('form.token'); ?>
</form>
<?php
echo JUX_Real_EstateFactory::getFooter();