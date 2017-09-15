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

<div class="width-100 fltlft">
    <form method="post" action="<?php echo JRoute::_('index.php?option=com_jux_real_estate'); ?>" enctype="multipart/form-data" name="adminForm" id="adminForm">
        <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_JUX_REAL_ESTATE_SELECT_IMAGE_UPLOAD' ); ?></legend>
            <ul class="adminformlist">
                <li><input class="inputbox" name="userfile" id="userfile" type="file" /></li>
                <li><input class="button" type="submit" value="<?php echo JText::_( 'COM_JUX_REAL_ESTATE_UPLOAD' ) ?>" name="adminForm" /></li>
            </ul>
        </fieldset>

        <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_JUX_REAL_ESTATE_DETAILS' ); ?></legend>
            <ul class="adminformlist">
                <li><label><?php echo JText::_( 'COM_JUX_REAL_ESTATE_TARGET_DIRECTORY' ).':'; ?></label>
                    <fieldset class="radio inputbox">
                        <span class="blue">
                        <?php
                        switch($this->task){
                            case 'companiesimg':
                                echo "/images/jux_real_estate/companies/";
                                $this->task = 'companiesimgup';
                            break;

                            case 'agentsimg':
                                echo "/images/jux_real_estate/agents/";
                                $this->task = 'agentsimgup';
                            break;

                            case 'categoriesimg':
                                echo "/images/jux_real_estate/categories/";
                                $this->task = 'categoriesimgup';
                            break;
                        }
                        ?>
                        </span>
                    </fieldset>
                </li>
                <li><label><?php echo JText::_( 'COM_JUX_REAL_ESTATE_IMAGE_FILESIZE' ).':'; ?></label>
                    <fieldset class="radio inputbox"><b><span class="blue"><?php echo $this->configs->get('images_zise'); ?> kb</span></b></fieldset>
                </li>
            </ul>
        </fieldset>
        <?php echo JHTML::_( 'form.token' ); ?>
        <input type="hidden" name="task" value="imageuploader.<?php echo $this->task; ?>" />
    </form>
</div>    
<div class="clr"></div>
<p class="copyright"><?php echo JUX_Real_EstateFactory::getFooter(); ?></p>