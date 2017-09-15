<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla.Administrator
 * @subpackage	com_jux_fileseller
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

// No direct access.
defined('_JEXEC') or die;

$logs	= json_decode($this->item->changelogs);
?>
<script type="text/javascript">
	var jux_logs= <?php echo (!is_null($logs)?(int)count($logs):0); ?>;
</script>

<table class="adminlist jux_table">
	<thead>
		<tr class="title">
			<th width="1%">
			</th>
			<th>
				<?php echo JText::_('COM_JUX_FILESELLER_VERSION'); ?>
			</th>
			<th>
				<?php echo JText::_('COM_JUX_FILESELLER_DATE'); ?>
			</th>
			<th>
				<?php echo JText::_('COM_JUX_FILESELLER_DESCRIPTION'); ?>
			</th>
			<th width="5%">
				<?php echo JText::_('COM_JUX_FILESELLER_PUBLISHED'); ?>
			</th>
			<th width="5%">
				<?php echo JText::_('COM_JUX_FILESELLER_REMOVE'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="6" style="text-align: left;">
				<a href="javascript:void(0);" onclick="jux_addChangeLogs()">
					<div class="jux_addbutton">
						<?php echo JText::_('COM_JUX_FILESELLER_ADD_CHANGE_LOGS'); ?>
					</div>
				</a>
			</td>
		</tr>
	</tfoot>
	<tbody id="jux_changelogs_sortable">
	<?php
	if (!empty($logs)) {
		for ($i = 0, $n = count($logs->version); $i < $n; $i++) {
		?>
			<tr id="jux_changelogs_row-<?php echo $i;?>">
				<td align="center" valign="top">
					<div class="jux_changelogs_sortablehandler"></div>
				</td>
				<td align="center" valign="top">
					<input type="text" class="inputbox" name="changelogs[version][]" size="40" value="<?php echo $logs->version[$i];?>">
				</td>
				<td nowrap="nowrap" align="center" valign="top">
					<input type="text" class="inputbox" name="changelogs[date][]" id="jux_changelogs_date-<?php echo $i;?>" size="10" value="<?php echo $logs->date[$i];?>">
				</td>
				<td nowrap="nowrap" align="center" valign="top">
					<textarea rows="5" cols="40" name="changelogs[desc][]" id="jux_changelogs_desc-<?php echo $i;?>" size="10"><?php echo @$logs->desc[$i];?></textarea>
					<!-- input type="text" class="inputbox" name="changelogs[desc][]" id="jux_changelogs_desc-<?php echo $i;?>" size="10" value="<?php echo @$logs->desc[$i];?>" -->
				</td>
				<td align="center" valign="top">
					<input type="checkbox" class="inputbox" value="1" onclick="jux_setChangeLogsPublish(<?php echo $i;?>, this.checked ? 1 : 0);" <?php echo $logs->published[$i] != 0 ? 'checked="checked"' : ''; ?>>
					<input type="hidden" name="changelogs[published][]" id="jux_changelogs_published-<?php echo $i;?>" value="<?php echo $logs->published[$i];?>">
				</td>
				<td align="center" valign="top">
					<a href="javascript:void(0)" onclick="jux_removeChangeLogs(<?php echo $i;?>)">
						<img src="<?php echo JURI::base(true); ?>/components/com_jux_fileseller/assets/img/delete.png" alt="remove">
					</a>
				</td>
			</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>