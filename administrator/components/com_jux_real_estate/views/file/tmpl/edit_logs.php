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

$document = JFactory::getDocument();
$document->addScript(JURI::base() . 'components/com_jux_fileseller/assets/js/tmpl.js');
//JHtml::_('sortablelist.sortable', 'change_logs_tbl', 'file-form', 'ASC', '');
?>
<script type="text/javascript">
    var change_logs_count = 0;
    function add_changelog()
    {
        blank_log = {
            "version": [""],
            "date": [""],
            "desc": [""],
            "published": [1]
        };
        log_row = tmpl("tmpl-change-logs", blank_log);
        jQuery('#change_logs_tbl_body').append(log_row);
    }
    jQuery(document).ready(function()
    {
        var change_logs = <?php echo $this->item->changelogs; ?>;
        jQuery('#change_logs_tbl_body').append(tmpl("tmpl-change-logs", change_logs));
    });

</script>
<table class="table table-striped" id="change_logs_tbl">
    <thead>
        <tr class="title">
<!--			<th width="1%" class="nowrap center hidden-phone">
                        <i class="icon-menu-2"></i>
                </th>-->
            <th>
                <?php echo JText::_('COM_JUX_FILESELLER_VERSION'); ?>
            </th>
            <th>
                <?php echo JText::_('COM_JUX_FILESELLER_DATE'); ?>
            </th>
            <th>
                <?php echo JText::_('COM_JUX_FILESELLER_DESCRIPTION'); ?>
            </th>
            <th>
                <?php echo JText::_('COM_JUX_FILESELLER_PUBLISHED'); ?>
            </th>
            <th>
                <?php echo JText::_('COM_JUX_FILESELLER_REMOVE'); ?>
            </th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: left;">
                <a href="javascript:void(0);" onclick="add_changelog()" class="btn btn-info">
                    <i class="icon-plus"></i>
                    <?php echo JText::_('COM_JUX_FILESELLER_ADD_CHANGE_LOGS'); ?>
                </a>
            </td>
        </tr>
    </tfoot>
    <tbody id="change_logs_tbl_body">
    </tbody>
</table>
<!-- The template to display change logs -->
<script id="tmpl-change-logs" type="text/x-tmpl">
    {% for (var i=0; i < o.version.length; i++) {
    change_logs_count++;
    %}
    <tr class="template-row">
    <!--		<td class="order nowrap center hidden-phone">
    <span class="sortable-handler hasTooltip">
    <i class="icon-menu"></i>
    </span>
    </td>-->
    <td>
    <input type="text" class="input-small" name="changelogs[version][]" value="{%=o.version[i]%}">
    </td>
    <td>
    <input type="text" class="input-small" name="changelogs[date][]" value="{%=o.date[i]%}">
    </td>
    <td>
    <textarea class="input-large" name="changelogs[desc][]" >{%=o.desc[i]%}</textarea>
    </td>
    <td>
    <input type="checkbox" onclick="tmpl_toggle_published(this)" {% if (o.published[i] && o.published[i] == 1) { %} checked="checked" {% } %}>
    <input type="hidden" name="changelogs[published][]" value="{% if (o.published[i] && o.published[i] == 1) { %}1{% }else{ %}0{% } %}">
    </td>
    <td>
    <a href="javascript:void(0);" onclick="tmpl_remove_row(this)" class="btn btn-danger btn-small">
    <i class="icon-remove"></i>
    </a>
    </td>
    </tr>
    {% } %}
</script>