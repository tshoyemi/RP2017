/**
 * @version		$Id: $
 * @author		joomlaux!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by joomlaux. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

function goBack(){
	window.history.go(-1);
}

/**
 * @version		$Id$
 * @author		Joomseller
 * @package		Joomla.Administrator
 * @subpackage	com_jse_digital_store
 * @copyright	Copyright (C) 2008 - 2013 by Joomseller Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */
 function jInsertFieldValue(value, id) {
 	var old_value = document.id(id).value;
 	if (old_value != value) {
 		var elem = document.id(id);
 		elem.value = value;
 		elem.fireEvent("change");
 		if (typeof(elem.onchange) === "function") {
 			elem.onchange();
 		}
 		jMediaRefreshPreview(id);
 	}
 }

 function jMediaRefreshPreviewTip(tip)
 {
 	var img = tip.getElement("img.media-preview");
 	tip.getElement("div.tip").setStyle("max-width", "none");
 	var id = img.getProperty("id");
 	id = id.substring(0, id.length - "_preview".length);
 	jMediaRefreshPreview(id);
 	tip.setStyle("display", "block");
 }

 function tmpl_remove_row(btn)
 {
 	var tmpl_row = jQuery(btn).closest('.template-row');
 	tmpl_row.remove();
 }
 function tmpl_toggle_published(checkbox) {
 	jcheckbox = jQuery(checkbox);
 	val = jcheckbox.prop('checked') ? 1 : 0;
 	jcheckbox.parent().find('input:hidden').first().val(val);
 }

 function newTip(className)
 {
 	$$(className).each(function(el) {
 		var title = el.get('title');
 		if (title) {
 			var parts = title.split('::', 2);
 			el.store('tip:title', parts[0]);
 			el.store('tip:text', parts[1]);
 		}
 	});
 	new Tips($$(className), {'maxTitleChars': 50,'fixed': false,'onShow': jMediaRefreshPreviewTip});
 	parse: 'rel'
 }

 function newSqueezeBox(className)
 {
 	SqueezeBox.initialize({});
 	SqueezeBox.assign($$(className), {
 		parse: 'rel'
 	});
 }

// select image from modal-btn-l
function jse_selectImage(name, param) {
	SqueezeBox.close();

	$('jse_image_name-' + param).value	= name;
	$('jse_image-' + param).value		= name;
	$('jse_image_select-' + param).setProperty('title', '<img src="' + jse_live_site + 'media/jse_digital_store/images/' + name + '" width="200" alt="' + name + '" />');

	jse_reloadJs();
}

function jSelectProduct(id, title, param) {
	document.getElementById('related_product-' + param).value = id;
	document.getElementById('related_product_name-' +param).value = title;
	SqueezeBox.close();
}

function approve( approve_value, order_id) {
	//var log = jQuery('#approve');
	var log = $('approve');
	var url = jse_admin_live_site + 'index.php?option=com_jse_digital_store&view=order&task=order.approve&tmpl=component&approve='+approve_value+'&id='+order_id;
	var req = new Request.HTML({
		method: 'get',
		url : url,
		onRequest:function(){
			log.empty().addClass('ajax-loading');
		},
		update: log,
		onComplete: function(response) {
			log.removeClass('ajax-loading');
		}
	}).send();
}
jQuery(document).ready(function(){
    jQuery('.purchase').click(function(){
        jQuery('.agentprofile-right').show();
        jQuery('.yet-account').hide();
        jQuery('.addagent-login').hide();
    });
    jQuery('.close-agent').click(function(){
        jQuery('.agentprofile-right').hide();
        jQuery('.yet-account').show();
        jQuery('.addagent-login').show();
    });
});