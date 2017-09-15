/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla
 * @subpackage		com_jux_real_estate
 * 
 * @copyright		Copyright (C) 2012 - 2015 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL, See LICENSE.txt
 */

var arrInput        = new Array(0);
var arrInputValue   = new Array(0);
var arrCatValue     = new Array(0);

function addInput() {
    arrInput.push(arrInput.length);
    arrInputValue.push("");
    arrCatValue.push("");
    display();
}

function display() {
    document.getElementById('parah').innerHTML = "";
    for (intI = 0; intI < arrInput.length; intI++) {
        document.getElementById('parah').innerHTML+= createInput(arrInput[intI], arrInputValue[intI], arrCatValue[intI]);
    }
}

function saveValue(intId, strValue) {
    //alert(strValue);
    arrInputValue[intId] = strValue;
}

function saveCatValue(intId, strValue){
    //alert(strValue);
    arrCatValue[intId] = strValue;
}

function createInput(id, value, catValue) {
    var GenAmen = (catValue == 0) ? ' selected="selected"' : '';
    var IntAmen = (catValue == 1) ? ' selected="selected"' : '';
    var ExtAmen = (catValue == 2) ? ' selected="selected"' : '';

    var inputs = '<div style="width: 200px; float: left;">\n\
                    <input type="text" name="title[]" class="inputbox" id="amenity '+id+'" onChange="javascript:saveValue('+id+',this.value)" value="'+value+'" />\n\
                </div>\n\
                <div style="width: 200px; float: left;">\n\
                    <select name="cat[]" class="inputbox" id="catamenity '+id+'" onChange="javascript:saveCatValue('+id+',this.selectedIndex)">\n\
                        <option value="0"'+GenAmen+'>'+General+'</option>\n\
                        <option value="1"'+IntAmen+'>'+Interior+'</option>\n\
                        <option value="2"'+ExtAmen+'>'+Exterior+'</option>\n\
                    </select>\n\
                </div>';
    return inputs;
}

function deleteInput() {
    if (arrInput.length > 0) {
        arrInput.pop();
        arrInputValue.pop();
        arrCatValue.pop();
    }
    display();
}