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

// Load the tooltip behavior.
//JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task) {
        var form = document.adminForm;
        if (task == 'type.cancel' || document.formvalidator.isValid(document.id('type-form'))) {
            Joomla.submitform(task, document.getElementById('type-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
    function changeImg(img) {
        var link_img = '<?php echo JURI::base(); ?>' + 'components/com_jux_real_estate/assets/icon/';
        document.images["currenticon"].src = link_img + img;
        document.getElementById("jform_icon").value = img;
    }
</script>
<form
    action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="type-form" class="form-validate">
    <table class="admintable">
        <tr>
            <td nowrap="nowrap" class="key" width="15%">
                <?php echo $this->form->getLabel('title'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('title'); ?>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('alias'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('alias'); ?>
            </td>
        </tr>

        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo JText::_('COM_JUX_REAL_ESTATE_CURRENT_ICON'); ?>
            </td>
            <td>
                <div class="jp_icon">
                    <?php if ($this->item->icon) { ?>
                        <img id="currenticon"
                             src="<?php echo JUri::base() . 'components/com_jux_real_estate/assets/icon/' . $this->item->icon; ?>"
                             width="50" height="50"/>
                         <?php } else { ?> 
                        <img id="currenticon"
                             src="<?php echo JUri::base() . 'components/com_jux_real_estate/assets/icon/apartment-3.png'; ?>"
                             width="50" height="50"/>
                         <?php } ?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="key">

            </td>
            <td style="border: 1px solid #f0f0ee;">
                <ul id="listimgaes">
                    <?php
                    if (count($this->images)) {

                        foreach ($this->images as $image) {
                            echo '<li><a href="#" onclick="changeImg(\'' . $image . '\')"><img src="' . JUri::base() . 'components/com_jux_real_estate/assets/icon/' . $image . '" width="32" height="32"/></a></li>';
                        }
                    }
                    ?>
                </ul>
                <br><br>

                <?php if ($this->item->icon != null) : ?>
                    <?php echo $this->form->getInput('icon', null, $this->item->icon); ?>
                <?php else: ?>
                    <?php echo $this->form->getInput('icon', null, $this->images[0]); ?>
                <?php endif; ?>

            </td>
        </tr>
        <tr>
            <td class="key"></td>
            <td valign="top" style="margin:0;padding-top:0px;"><p><?php echo JText::_('COM_JUX_REAL_ESTATE_MORE_ICON'); ?>: <a
                        href="http://mapicons.nicolasmollet.com/category/markers/offices/real-estate/" target="_blank">http://mapicons.nicolasmollet.com/category/markers/offices/real-estate/</a>
                </p></td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('ordering'); ?>
            </td>
            <td colspan="3">
                <?php echo $this->form->getInput('ordering'); ?>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('description'); ?>
            </td>
            <td colspan="3">
                <?php echo $this->form->getInput('description'); ?>
            </td>
        </tr>

        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('published'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('published'); ?>
            </td>
        </tr>
      
     
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('access'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('access'); ?>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('language'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('language'); ?>
            </td>
        </tr>
        <tr>
            <td nowrap="nowrap" class="key">
                <?php echo $this->form->getLabel('id'); ?>
            </td>
            <td>
                <?php echo $this->form->getInput('id'); ?>
            </td>
        </tr>
    </table>
    <div class="clr"></div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="controller" value="type"/>
    <?php echo JHTML::_('form.token'); ?>

</form>
<?php
echo JUX_Real_EstateFactory::getFooter();