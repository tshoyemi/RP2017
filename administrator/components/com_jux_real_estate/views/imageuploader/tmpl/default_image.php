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
JHtml::_('bootstrap.tooltip');
?>
<div class="item">
    <div align="center" class="imageBorder">
        <a onclick="window.parent.jp_SwitchImage('<?php echo $this->_tmp_icon->name; ?>');">
            <div class="image">
                <img src="<?php echo JURI::root(true); ?>/images/jux_real_estate/<?php echo $this->folder; ?>/<?php echo $this->_tmp_icon->name; ?>"  width="<?php echo $this->_tmp_icon->width_60; ?>" height="<?php echo $this->_tmp_icon->height_60; ?>" alt="<?php echo $this->_tmp_icon->name; ?> - <?php echo $this->_tmp_icon->size; ?>" />
            </div>
        </a>
    </div>
    <div class="imagecontrols">
        <?php echo $this->_tmp_icon->size; ?>
        <a class="delete-item" href="<?php echo JRoute::_('index.php?option=com_jux_real_estate&task=imageuploader.delete&tmpl=component&folder='.$this->folder.'&rm[]='.$this->_tmp_icon->name); ?>">
            <?php echo JHtml::_('image', 'media/remove.png', JText::_('JACTION_DELETE'), array('width' => 16, 'height' => 16), true); ?>
        </a>
    </div>
    <div class="imageinfo">
        <span class="hasTip" title="<?php echo $this->_tmp_icon->name; ?>"><?php echo $this->escape( substr( $this->_tmp_icon->name, 0, 10 ) . ( strlen( $this->_tmp_icon->name ) > 10 ? '...' : '')); ?></span>
    </div>
</div>