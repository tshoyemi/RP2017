<?php
/**
 * @version		$Id$
 * @author		JoomlaUX Admin
 * @package		Joomla!
 * @subpackage	JUX Real Estate
 * @copyright	Copyright (C) 2008 - 2012 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once( JPATH_BASE . '/' . 'components' . '/' . 'com_jux_real_estate' . '/' . 'helpers' . '/' . 'route.php');

if (count($rows)) { ?>
<div id="mod-jux-re-categories">
    <ul class="menu re-categories-listing">
        <?php foreach ($rows as $row) {
            $cat_link =  JRoute::_(JUX_Real_EstateHelperRoute::getCategoryRoute($row->id.'-'.$row->alias));
            ?>
            <li class="item">
                <a href="<?php echo $cat_link; ?>">
                    <?php echo $row->title; ?>
                    <?php if ($count_realties) {
                        echo " ($row->count)";
                    } ?>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>