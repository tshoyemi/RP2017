<?php
/**
 * @version		$Id: $
 * @author		JoomlaUX!
 * @package		Joomla.Administrator
 * @subpackage          jux_real_estate_update
 * @copyright           Copyright (C) 2008 - 2012 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');

?>
<?php
$lists = $this->lists;
$trans = $this->trans;
$flag = $this->flag;
?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function(task) {
        var form = document.adminForm;
        var form1 = document.adminForm1;
        if (task === 'language.translation_save') {
            form.site.value = form1.site.value;
            form.lang.value = form1.lang.value;
            var field_name_list = form1.field_name_list.value;
            var fieldNameArr = new Array();
            fieldNameArr = field_name_list.split("|");
            var temp, temp1, temp2, temp3;
            temp2 = "";
            for (i = 0; i < fieldNameArr.length; i++) {
                temp = fieldNameArr[i];
                temp1 = document.getElementById(temp);
                if (temp1 != null) {
                    temp3 = temp1.value;
                    temp3 = temp3.replace("\"", "'");
                    temp2 += temp + "|" + temp3 + "@@@";
                }
            }
            temp2 = temp2.substr(0, temp2.length - 3);
            document.getElementById('lang_page').value = temp2;
            Joomla.submitform(task);
        }
        if (task === 'language.cancel') {
            Joomla.submitform(task);
        }

    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=language'); ?>" method="POST" name="adminForm" id="adminForm">
    <div style="display:none;">
        <textarea name="lang_page" id="lang_page"></textarea>
    </div>
    <input type="hidden" name="task" value="translation_save"/>
    <input type="hidden" name="extension_name" id="extension_name" value="<?php if(isset($_REQUEST['extension_name']) && $_REQUEST['extension_name']){echo $_REQUEST['extension_name'];} else {echo 'com_jux_real_estate';} ?>">
    <input type="hidden" name="lang" id="lang" value="">
    <input type="hidden" name="site" id="site" value="">
    <input type="hidden" name="option" value="com_jux_real_estate">
    <input type="hidden" name="boxchecked" value="0">
    <input type="hidden" name="item" value="<?php if(isset($_REQUEST['extension_name']) && $_REQUEST['extension_name']){echo $_REQUEST['extension_name'];} else {echo 'com_jux_real_estate';} ?>" />
</form>
<form action="<?php echo JRoute::_('index.php?option=com_jux_real_estate&view=language'); ?>" method="POST" name="adminForm1" id="adminForm1">
    <!-- code 3.0 -->
    <?php if (!empty($this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
        <?php else : ?>
            <div id="j-main-container">
            <?php endif; ?>
            <div id="filter-bar" class="btn-toolbar">
                <div class="btn-group pull-left">
                    <?php echo $this->lists['extensions']; ?>
                    <?php echo $lists['langs']; ?>
                    <?php echo $lists['site']; ?>
                </div>
            </div>
            <div class="clearfix"> </div>
            <table class="admintable" style="width:100%" id="lang_table">
                <tr>
                    <td class="key" style="width:5%; text-align: center;"><?php echo JText::_('#'); ?></td>
                    <td class="key" style="width:20%; text-align: left;"><?php echo JText::_('JUX_KEY'); ?></td>
                    <td class="key" style="width:35%; text-align: left;"><?php echo JText::_('JUX_ORIGINAL'); ?></td>
                    <td class="key" style="width:40%; text-align: left;"><?php echo JText::_('JUX_TRANSLATION'); ?></td>
                </tr>
                <?php
                $item = $lists['item'];
                $lang = $lists['lang'];
                
                $original = $trans[$this->original][$item];
                $tran = $trans[$lang][$item];
                $j = 0;
                $str = array();
                foreach ($original as $key => $value) {
                    $j++;
                    $str[] = $key;
                    $show = true;
                    if (isset($tran[$key])) {
                        $translatedValue = $tran[$key];
                        $missing = false;
                    } else {
                        $translatedValue = '';
                        $missing = true;
                    }
                ?>
                <tr>
                    <td class="key" style="text-align:center;">
                        <?php echo $j ?>.
                    </td>
                    <td class="key" style="text-align: left;" width="20%" title="<?php echo $key; ?>"><?php echo (strlen($key) > 50) ? substr($key, 0, 50) . '...' : $key; ?></td>
                    <td style="text-align: left;"><?php echo $value; ?></td>
                    <td>                        
                        <input type="hidden" name="keys[]" value="<?php echo $key; ?>" />
                        <input type="text" id="<?php echo $key; ?>" name="<?php echo $key; ?>" class="inputbox" size="100"  
                               value="<?php echo $flag ? $translatedValue : ''; ?>" />
                        <?php if ($missing) { ?>
                                <span style="color:red;">*</span>
                        <?php } ?>
                    </td>                   
                </tr>   
                <?php
                }
                $str = implode("|", $str);
                ?> 
            </table>
            
            <div style="display:none;">
                <textarea name="field_name_list" id="field_name_list"><?php echo $str ?></textarea>
            </div>
        </div>
    </div>
    <input type="hidden" name="option" value="com_jux_real_estate">
    <input type="hidden" name="boxchecked" value="0">
    <input type="hidden" name="item" value="<?php echo $this->extension_name; ?>" />
    <?php echo JHTML::_('form.token'); ?>
</form>
<?php
echo JUX_LanguageFactory::getFooter();