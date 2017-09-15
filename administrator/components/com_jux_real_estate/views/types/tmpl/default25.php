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
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_jux_real_estate');
$saveOrder = $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=types'); ?>" method="post" name="adminForm"
      id="adminForm">
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
                <th width="5">
                    <?php echo JText::_('#'); ?>
                </th>
                <th width="20">
                    <input type="checkbox" name="checkall-toggle" value=""
                           title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
                </th>
                <th style="text-align: left;">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_TITLE', 'a.title', $listDirn, $listOrder); ?>
                </th>
                <th style="text-align: left;">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_ICON', 'a.icon', $listDirn, $listOrder); ?>
                </th>
                <th style="text-align: left;">
                    <?php echo JText::_('COM_JUX_REAL_ESTATE_DESCRIPTION'); ?>
                </th> 
<!--                <th style="text-align: left;">
                <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_BANNER_IMAGE', 'a.banner_image', $listDirn, $listOrder); ?>
                </th>
                <th style="text-align: left;">
                <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_BANNER_COLOR', 'a.banner_color', $listDirn, $listOrder); ?>
                </th>
                <th class="title">
                <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_SHOW_BANNER', 'a.show_banner', $listDirn, $listOrder); ?>
                </th>-->
                <th class="title">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
                </th>
                <th width="8%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_JUX_REAL_ESTATE_ORDER', 'a.ordering', $listDirn, $listOrder); ?>
                    <?php if ($listOrder) echo JHTML::_('grid.order', $this->items, 'filesave.png', 'types.saveorder'); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access_level', $listDirn, $listOrder); ?>
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
                <td colspan="10">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            $ordering = ($listOrder == 'a.ordering');
            $link_img = JUri::base() . 'components/com_jux_real_estate/assets/icon/';
            foreach ($this->items as $i => $item) {
                $canCreate = $user->authorise('core.create', 'com_jux_real_estate');
                $canEdit = $user->authorise('core.edit', 'com_jux_real_estate.type.' . $item->id);
                $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                $canChange = $user->authorise('core.edit.state', 'com_jux_real_estate.type.' . $item->id) && $canCheckin;
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
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'types.', $canCheckin); ?>
                        <?php endif; ?>
                        <?php if ($canEdit) : ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=type.edit&id=' . (int) $item->id); ?>">
                                <?php echo $this->escape($item->title); ?></a>
                        <?php else : ?>
                            <?php echo $this->escape($item->title); ?>
                        <?php endif; ?>
                        <p class="smallsub">
                            <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?></p>
                    </td>
                    <td>
                        <span class="editlinktip hasTip"
                              title="<?php echo JText::_('COM_JUX_REAL_ESTATE_EDIT_ICON'); ?>::<?php echo $this->escape($item->title); ?>">
                            <a href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=type.edit&id=' . (int) $item->id); ?>">
                                <img src="<?php echo $link_img . $item->icon; ?>"/>
                            </a>
                        </span>
                    </td>
                    <td>
                        <?php echo JText::_($item->description); ?>
                    </td>
    <!--                    <td>
                    <?php //echo Jtext::_($item->banner_image); ?>
                </td>-->
    <!--                    <td>
                    <?php //echo Jtext::_($item->banner_color); ?>
                 </td>-->
    <!--                    <td align="center">
                    <?php //echo JHtml::_('jux_real_estate.showbanner', $item->show_banner, $i, $canChange); ?>
                </td>-->
                    <td align="center">
                        <?php echo JHtml::_('jgrid.published', $item->published, $i, 'types.', $canChange, 'cb'); ?>
                    </td>
                    <td class="order">
                        <?php
                        if ($canChange) {
                            if ($saveOrder) {
                                if ($listDirn == 'asc') {
                                    echo '<span>' . $this->pagination->orderUpIcon($i, true, 'types.orderup', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'types.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
                                } else if ($listDirn == 'desc') {
                                    echo '<span>' . $this->pagination->orderUpIcon($i, true, 'types.orderdown', 'JLIB_HTML_MOVE_UP', $ordering) . '</span>';
                                    echo '<span>' . $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'types.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering) . '</span>';
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
                <?php
            }
            ?>
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