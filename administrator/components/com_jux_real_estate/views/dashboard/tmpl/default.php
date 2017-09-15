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

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
?>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=dashboard'); ?>" method="post" name="adminForm" id="adminForm">

    <?php if (!empty($this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
	    <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
	<?php else : ?>
	    <div id="j-main-container">
	<?php endif; ?>
	    <div class="span6">
		<div class="well well-small">
		    <div class="module-title nav-header"><?php echo JText::_('QUICK LINKS') ?></div>
		    <div class="row-striped">
			<div class="row-fluid">
			    <div class="span12">
				<?php $this->quickiconButton('index.php?option=com_categories&amp;extension=com_jux_real_estate', 'icon-48-categories.png', JText::_('COM_JUX_REAL_ESTATE_CONTRACTS')); ?>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_categories&amp;view=category&amp;extension=com_jux_real_estate&amp;layout=edit', 'icon-48-addcategory.png', JText::_('COM_JUX_REAL_ESTATE_ADD_CONTRACT'));
				?>
			    </div>
			</div>

			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=types', 'icon-48-types.png', JText::_('COM_JUX_REAL_ESTATE_TYPES'));
				?>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=type&amp;layout=edit', 'icon-48-addtype.png', JText::_('COM_JUX_REAL_ESTATE_ADD_TYPE'));
				?>
			    </div>
			</div>

			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=realties', 'icon-48-realty.png', JText::_('COM_JUX_REAL_ESTATE_REALTY_MANAGEMENT'));
				?>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=agents', 'icon-48-agents.png', JText::_('COM_JUX_REAL_ESTATE_AGENT_MANAGEMENT'));
				?>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=agent&amp;layout=edit', 'icon-48-addagent.png', JText::_('COM_JUX_REAL_ESTATE_ADD_AGENT'));
				?>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=plans', 'icon-48-plans.png', JText::_('COM_JUX_REAL_ESTATE_PLANS_MANAGEMENT'));
				?>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=amenities', 'icon-48-amenities.png', JText::_('COM_JUX_REAL_ESTATE_AMENITIES'));
				?>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=currencies', 'icon-48-currencies.png', JText::_('COM_JUX_REAL_ESTATE_CURRENCY_MANAGEMENT'));
				?>
			    </div>
			</div>

			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=fields', 'icon-48-fields.png', JText::_('COM_JUX_REAL_ESTATE_FIELDS_MANAGEMENT'));
				?>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=field&amp;layout=edit', 'icon-48-addfield.png', JText::_('COM_JUX_REAL_ESTATE_ADD_FIELD'));
				?>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=messages', 'icon-48-messages.gif', JText::_('COM_JUX_REAL_ESTATE_MESSAGES'));
				?>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<?php
				    $this->quickiconButton('index.php?option=com_jux_real_estate&amp;view=configs', 'icon-48-config.png', JText::_('COM_JUX_REAL_ESTATE_CONFIGURATION'));
				?>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	    <div class="span5">
		<?php
		    echo JHtml::_('sliders.start', 'statistics_pane');

		    echo JHtml::_('sliders.panel', JText::_('COM_JUX_REAL_ESTATE_ABOUT_REALTY'), 'about_panel');
		    echo $this->loadTemplate('about');
		    echo JHtml::_('sliders.end');
		?>
	    </div>
	</div>
    </div>
</form>
<?php echo JUX_Real_EstateFactory::getFooter();