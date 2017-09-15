<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
//JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
//JHtml::_('dropdown.init');
//JHtml::_('formbehavior.chosen', 'select');

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
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=dashboard'); ?>" method="post" name="adminForm" id="adminForm">
		<?php if (!empty($this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
		<?php else : ?>
			<div id="j-main-container">
			<?php endif; ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td valign="top">
            <table>
                <tr>
                    <td>
                        <div id="cpanel">
                            <?php
                            $this->quickiconButton('index.php?option=com_categories&amp;extension=com_jux_real_estate', 'icon-48-categories.png', JText::_('COM_JUX_REAL_ESTATE_CATEGORIES'));
                            $this->quickiconButton('index.php?option=com_categories&amp;view=category&amp;extension=com_jux_real_estate&amp;layout=edit', 'icon-48-addcategory.png', JText::_('COM_JUX_REAL_ESTATE_ADD_CATEGORY'));

                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=types', 'icon-48-types.png', JText::_('COM_JUX_REAL_ESTATE_TYPES'));
                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=type&amp;layout=edit', 'icon-48-addtype.png', JText::_('COM_JUX_REAL_ESTATE_ADD_TYPE'));

                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=realties', 'icon-48-realty.png', JText::_('COM_JUX_REAL_ESTATE_REALTY_MANAGEMENT'));

                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=agents', 'icon-48-agents.png', JText::_('COM_JUX_REAL_ESTATE_AGENT_MANAGEMENT'));
                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=agent&amp;layout=edit', 'icon-48-addagent.png', JText::_('COM_JUX_REAL_ESTATE_ADD_AGENT'));

                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=plans', 'icon-48-plans.png', JText::_('COM_JUX_REAL_ESTATE_PLANS_MANAGEMENT'));

                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=companies', 'icon-48-company.png', JText::_('COM_JUX_REAL_ESTATE_COMPANIES'));
                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=company&amp;layout=edit', 'icon-48-company.png', JText::_('COM_JUX_REAL_ESTATE_ADD_COMPANY'));

                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=amenities', 'icon-48-amenities.png', JText::_('COM_JUX_REAL_ESTATE_AMENITIES'));
                            //$this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=amenity&amp;layout=add', 'icon-48-amenities.png', JText::_('COM_JUX_REAL_ESTATE_ADD_AMENITY'));

                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=currencies', 'icon-48-currencies.png', JText::_('COM_JUX_REAL_ESTATE_CURRENCY_MANAGEMENT'));

                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=fields', 'icon-48-fields.png', JText::_('COM_JUX_REAL_ESTATE_FIELDS_MANAGEMENT'));
                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=field&amp;layout=edit', 'icon-48-addfield.png', JText::_('COM_JUX_REAL_ESTATE_ADD_FIELD'));

                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=messages', 'icon-48-messages.gif', JText::_('COM_JUX_REAL_ESTATE_MESSAGES'));
                            $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=configs', 'icon-48-config.png', JText::_('COM_JUX_REAL_ESTATE_CONFIGURATION'));

                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
        <td valign="top" width="40%" style="padding: 0 0 0 5px">
            <?php
            echo JHtml::_('sliders.start', 'statistics_pane');
            echo JHtml::_('sliders.panel', JText::_('COM_JUX_REAL_ESTATE_ABOUT_REALTY'), 'about_panel');
            echo $this->loadTemplate('about');
            echo JHtml::_('sliders.end');
            ?>
        </td>
    </tr>
</table>

<?php
echo JUX_Real_EstateFactory::getFooter();