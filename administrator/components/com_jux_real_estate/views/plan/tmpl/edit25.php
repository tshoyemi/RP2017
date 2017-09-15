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
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$db = JFactory::getDBO();
$nullDate = $db->getNullDate();
?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task) {
        var form = document.adminForm;
        if (task == 'plan.cancel' || document.formvalidator.isValid(document.id('plan-form'))) {
            Joomla.submitform(task, document.getElementById('plan-form'));
        }
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="plan-form" enctype="multipart/form-data" class="form-validate">

    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td valign="top">
                <?php
//General pane
                echo $this->loadTemplate('general');
                echo JHtml::_('tabs.start', 'plan-sliders-' . $this->item->id, array('useCookie' => 1));
                echo JHtml::_('tabs.end');
                ?>
            </td>
        </tr>
    </table>

    <?php
                var_dump($this->form->getInput('old_image', null, $this->item->image)) ;
    
    echo $this->form->getInput('old_image', null, $this->item->image); ?>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="controller" value="plan"/>
    <?php echo JHTML::_('form.token'); ?>
</form>