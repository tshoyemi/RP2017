/**
 * @version		$Id: $
 * @author		joomlaux!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by joomlaux. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

//creates an XMLHttpRequest instance
var xmlHttp = createXMLHttpRequestObject();
function createXMLHttpRequestObject()
{
	// store the reference to the XMLHttpRequest Object
	var xmlHttp;
	// this should work for  all browsers except IE6 and Older
	try	{
		xmlHttp = new XMLHttpRequest();
	} catch(e) {
		// if is IE6 or Older
		var XmlHttpVersions = new array('MSXML2.XMLHTTP.6.0',
										'MSXML2.XMLHTTP.5.0',
										'MSXML2.XMLHTTP.4.0',
										'MSXML2.XMLHTTP',
										'Microsoft.XMLHttp');
		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++) {
			try {
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			} catch(e) {

			}
		}
	}
	if (!xmlHttp) {
		alert("Error creating the XMLHttpRequest object");
	} else {
		return xmlHttp;
	}
}

function stateChanged() {
    if ( xmlHttp.readyState==4 || xmlHttp.readyState=="complete") {
        if (xmlHttp.responseText=='Successfull') {
            Joomla.submitform('realty.save', document.getElementById('adminForm'));
        } else {
          
            Recaptcha.reload();
            $('recaptcha_msg').innerHTML = xmlHttp.responseText;
            return false;
        }

    }
}