<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div class="imagehead">
        <?php echo JText::_( 'COM_JUX_REAL_ESTATE_SEARCH' ).' '; ?>
        <input type="text" name="search" id="search" value="<?php echo $this->search; ?>" class="inputbox" />
        <button onclick="document.adminForm.submit();"><?php echo JText::_( 'COM_JUX_REAL_ESTATE_GO' ); ?></button>
        <button onclick="document.adminForm.search.value='';document.adminForm.submit();"><?php echo JText::_( 'COM_JUX_REAL_ESTATE_RESET' ); ?></button>
        <div class="imagefoldername"><?php echo "/images/jux_real_estate/". $this->folder; ?></div>
    </div>
    <div class="imglist">
        <?php
        for ($i = 0, $n = count($this->images); $i < $n; $i++) :
            $this->setImage($i);
            echo $this->loadTemplate('image');
        endfor;
        ?>
    </div>
	<input type="hidden" name="option" value="com_jux_real_estate" />
	<input type="hidden" name="view" value="imageuploader" />
	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="task" value="<?php echo $this->task; ?>" />
    <div class="clear"></div>
    <div class="imagenav"><?php echo $this->pagination->getListFooter(); ?></div>
</form>
