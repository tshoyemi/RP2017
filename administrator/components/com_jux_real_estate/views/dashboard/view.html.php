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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * View class for Dashboard.
 *
 * @package        Joomla.Administrator
 * @subpackage    com_jux_real_estate
 * @since        1.6
 */
class JUX_Real_EstateViewDashboard extends JViewLegacy {

    protected $xml;

    /**
     * Display the view
     */
    public function display($tpl = null) {

	JUX_Real_EstateHelper::addSubmenu('dashboard');
	$this->xml = $this->get('XmlParser');

	// Check for errors.
	if (count($errors = $this->get('Errors'))) {
	    JError::raiseError(500, implode("\n", $errors));
	    return false;
	}
	$this->addToolBar();
	if (JVERSION >= '3.0.0') {
	    $this->sidebar = JHtmlSidebar::render();
	}
	if (JVERSION < '3.0.0') {
	    $this->setLayout($this->getLayout() . '25');
	}
	parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since    1.6
     */
    protected function addToolBar() {
	// set page title

	$document = JFactory::getDocument();
	$canDo = JUX_Real_EstateHelper::getActions();
	$document->setTitle(JText::_('COM_JUX_REAL_ESTATE_REALTY_MANAGEMENT'));
	
	//create the toolbar
	JToolBarHelper::title(JText::_('COM_JUX_REAL_ESTATE_REALTY_MANAGEMENT'), 'fileseller.png');
    }

    /**
     * Dipslay icon button.
     */
    function quickiconButton($link, $image, $text, $modal = 0) {
	//initialise variables
	$lang = JFactory::getLanguage();
	?>

	<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
	    <div class="icon">
		<?php
		if ($modal == 1) {
		    JHTML::_('behavior.modal');
		    ?>
	    	<a href="<?php echo $link . '&amp;tmpl=component'; ?>" style="cursor:pointer" class="modal" rel="{handler: 'iframe', size: {x: 650, y: 400}}">
			<?php
		    } else {
			?>
	    	    <a href="<?php echo $link; ?>">
			    <?php
			}
			echo JHTML::_('image', 'administrator/components/com_jux_real_estate/assets/img/' . $image, $text);
			?>
			<span><?php echo $text; ?></span>
		    </a>
	    </div>
	</div>
	<?php
    }

}

