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
require_once JPATH_COMPONENT . '/models/fields/juxlocstate.php';
require_once JPATH_COMPONENT . '/models/fields/juxcompany.php';
require_once JPATH_COMPONENT . '/models/fields/juxagent.php';
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate.realty');
$saveOrder = $listOrder == 'r.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=realties'); ?>" method="post" name="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JUXARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search"
                   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                   title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>"/>

            <button type="submit" class="btn"><?php echo JText::_('JUXARCH_FILTER_SUBMIT'); ?></button>
            <button type="button"
                    onclick="document.id('filter_search').value = '';
                            this.form.submit();"><?php echo JText::_('JUXARCH_FILTER_CLEAR'); ?></button>
        </div>
        <div class="filter-select fltrt">

            <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false, 'trash' => false)), 'value', 'text', $this->state->get('filter.published'), true); ?>
            </select>

            <select name="filter_featured" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('COM_JUX_REAL_ESTATE_SELECT_FEATURED'); ?></option>
                <?php echo JHtml::_('select.options', JUX_Real_EstateHelper::getFeaturedOptions(), 'value', 'text', $this->state->get('filter.featured'), true); ?>
            </select>

            <select name="filter_approved" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_APPROVED') . ' -'; ?></option>
                <?php echo JHtml::_('select.options', JUX_Real_EstateHelper::getApprovedOptions(), 'value', 'text', $this->state->get('filter.approved')); ?>
            </select>

            <select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_A_CATEGORY') . ' -'; ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jux_real_estate.category'), 'value', 'text', $this->state->get('filter.category_id')); ?>
            </select>

            <select name="filter_type_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_A_TYPE') . ' -'; ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jux_real_estate.type'), 'value', 'text', $this->state->get('filter.type_id')); ?>
            </select>
            <select name="filter_agent_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_AN_AGENT') . ' -'; ?></option>
                <?php echo JHtml::_('select.options', JFormFieldJUXAgent::getOptions(), 'value', 'text', $this->state->get('filter.agent_id')); ?>
            </select>

            <select name="filter_company_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_COMPANY') . ' -'; ?></option>
                <?php echo JHtml::_('select.options', JFormFieldJUXCompany::getOptions(), 'value', 'text', $this->state->get('filter.company_id')); ?>
            </select>

            <select name="filter_locstate" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_LOCSTATE') . ' -'; ?></option>
                <?php echo JHtml::_('select.options', JFormFieldJUXLocstate::getOptions(), 'value', 'text', $this->state->get('filter.locstate')); ?>
            </select>

            <select name="filter_city" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_CITY') . ' -'; ?></option>
                <?php echo JHtml::_('select.options', JUX_Real_EstateHelperQuery::getCityList('', '', '', false, true), 'value', 'text', $this->state->get('filter.city')); ?>
            </select>
            <select name="filter_couuntry_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_COUNTRY') . ' -'; ?></option>
                <?php echo JHtml::_('select.options', JUX_Real_EstateHelperQuery::getCountryList('', '', '', false, true), 'value', 'text', $this->state->get('filter.country_id')); ?>
            </select>

            <select name="filter_beds" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_BEDS') . ' -'; ?></option>
                <?php echo JHtml::_('select.options', JUX_Real_EstateHelper::getBedsOptions(), 'value', 'text', $this->state->get('filter.beds')); ?>
            </select>

            <select name="filter_baths" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo '- ' . JText::_('COM_JUX_REAL_ESTATE_SELECT_BATHS') . ' -'; ?></option>
                <?php echo JHtml::_('select.options', JUX_Real_EstateHelper::getBathsOptions(), 'value', 'text', $this->state->get('filter.baths')); ?>
            </select>

            <select name="filter_access" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access')); ?>
            </select>

            <select name="filter_language" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language')); ?>
            </select>
        </div>
    </fieldset>
    <div class="clr"></div>

    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%">
                    <?php echo JText::_('#'); ?>
                </th>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                           onclick="Joomla.checkAll(this)"/>
                </th>
                <th width="20%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_TITLE'), 'r.title', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_TYPE'), 't.title', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_CATEGORY'), 'c.title', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_AGENT'), 'agentname', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_USER'), 'username', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_CREATED_DATE'), 'date_created', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_PRICE'), 'r.price', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_CURRENCY'), 'd.title', $listDirn, $listOrder); ?>
                </th>

                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_FEATURED'), 'r.featured', $listDirn, $listOrder); ?>
                </th>

                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_SOLD'), 'r.sold', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_APPROVED'), 'r.approved', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_PUBLISHED'), 'r.published', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_JUX_REAL_ESTATE_COUNT'), 'r.count', $listDirn, $listOrder); ?>
                </th>
                <th width="13%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_ORDER', 'r.ordering', $listDirn, $listOrder); ?>
                    <?php if ($listOrder) echo JHTML::_('grid.order', $this->items, 'filesave.png', 'realties.saveorder'); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
                </th>
                <th width="1%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', JText::_('JGRID_HEADING_ID'), 'r.id', $listDirn, $listOrder); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="19">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            $ordering = ($listOrder == 'r.ordering');

            foreach ($this->items as $i => $item) :

                $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
                $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.realty.' . $item->id);
                $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                $canEditOwn = $user->authorise('core.edit.own', 'com_jux_real_estate.realty.' . $item->id) && $item->user_id == $userId;
                $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate.realty.' . $item->id) && $canCheckin;
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td>
                        <?php if ($item->checked_out) : ?>
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'realties.', $canCheckin); ?>
                        <?php endif; ?>
                        <?php if ($canEdit || $canEditOwn) : ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=realty.edit&id=' . (int) $item->id); ?>">
                                <?php echo $this->escape($item->title); ?></a>
                        <?php else : ?>
                            <?php echo $this->escape($item->title); ?>
                        <?php endif; ?>
                        <p class="smallsub">
                            <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?></p>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=type.edit&id=' . (int) $item->type_id); ?>">
                            <?php echo $this->escape($item->type); ?></a>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_categories&extension=com_jux_real_estate&view=category&layout=edit&id=' . (int) $item->catid); ?>">
                            <?php echo $this->escape($item->category); ?></a>
                    </td>

                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=agent.edit&id=' . (int) $item->agent_id); ?>">

                        </a>

                        <?php
                        $agents = JUX_Real_EstateHelperQuery::getAvailableAgents($item->id);
                        $x = 0;
                        if ($agents) {
                            foreach ($agents AS $a) {
                                echo '<a href="' . JRoute::_('index.php?option=com_jux_real_estate&task=agent.edit&id=' . (int) $a->id) . '">';
                                echo JUX_Real_EstateHelperQuery::getAgentName($a->id);
                                echo '</a>';
                                $x++;
                                if ($x < count($agents))
                                    echo ', <br />';
                            }
                        } else {
                            echo '<span class="invalid">' . JText::_('---') . '</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->user_id); ?>">
                            <?php echo $this->escape($item->username); ?></a>
                    </td>
                    <td align="center">
                        <?php echo JHTML::_('date', $item->date_created, $this->configs->get('date_format')); ?>
                    </td>
                    <td align="center">
                        <?php echo number_format($item->price, 0, '.', $this->configs->get('thousand_separator')); ?>
                    </td>
                    <td align="center">
                        <?php echo $item->currency; ?>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jux_real_estate.featured', $item->featured, $i, $canChange, 'realties'); ?>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jux_real_estate.sold', $item->sold, $i, $canChange); ?>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jux_real_estate.approved', $item->approved, $i, $canChange); ?>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jgrid.published', $item->published, $i, 'realties.', $canChange, 'cb'); ?>
                    </td>
                    <td align="center">
                        <?php echo $item->count; ?>
                    </td>

                    <td class="order">
                        <?php
                        if ($canChange) {
                            if ($saveOrder) {
                                if ($listDirn == 'asc') {
                                    echo '<span>' . $this->pagination->orderUpIcon($i, true, 'realties.orderup', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'realties.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                                } else if ($listDirn == 'desc') {
                                    echo '<span>' . $this->pagination->orderUpIcon($i, true, 'realties.orderdown', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'realties.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                                }
                            }
                            $disabled = $saveOrder ? '' : 'disabled="disabled"';
                            echo '<input type="text" name="order[]" size="5" value="' . $item->ordering . '" ' . $disabled . ' class="text-area-order" />';
                        } else {
                            echo $item->ordering;
                        }
                        ?>
                    </td>

                    <td class="center">
                        <?php echo $this->escape($item->access_level); ?>
                    </td>
                    <td class="center">
                        <?php
                        if ($item->language == '*') {
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
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHTML::_('form.token'); ?>
</form>
<?php
echo JUX_Real_EstateFactory::getFooter();