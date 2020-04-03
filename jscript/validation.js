
function span_switch(field){
	el = document.getElementById(field+'_sel');
    el2 = document.getElementById(field+'_txt');
	if (el.style.display=='block')	{
		el.style.display='none';
		el2.style.display='block';
	}
	else {
		el2.style.display='none';
		el.style.display='block';
	}
}

function span_showhide(field){
    el = document.getElementById(field);
	if (el.style.display=='block')	{
		el.style.display='none';
	}
	else {
		el.style.display='block';
	}
}
function div_close(field){
	el = document.getElementById(field);
	if (el.style.display=='block'){
		el.style.display='none';
	}
}

function div_open(field){
	el = document.getElementById(field);
	if (el.style.display=='none'){
		el.style.display='block';
	}
}

function show_detail(id){
	el = document.getElementById('detail_'+id);
	elvdesc = document.getElementById('vdesc');
	if (el && elvdesc){
		elvdesc.innerHTML = el.innerHTML;
	}
}

function input_disabled(field){
    el = document.getElementById(field);
    el.disabled = !el.disabled;
}


function set_input_value(field, value){
    el = document.getElementById(field);
    el.value = value;
}

function isLengthMin(elem, min){
	var uInput = elem.value;
	if(uInput.length < min ){
		elem.focus();
		return false;
	}
	return true;
}

function asSubString(elem, searchTxt){
	var uInput = elem.value;
	if(uInput.length < searchTxt.length)
	{
		elem.focus();
		return false;
	}
	uInput = uInput.toLowerCase()
	searchTxt = searchTxt.toLowerCase()
 	if(uInput.search(searchTxt) == -1 )
	{
		elem.focus();
		return false;
	}
	return true;
}

function isEmpty(elem){
	if(elem.value.length == 0){
		elem.focus(); // set the focus to this input
		return true;
	}
	return false;
}

function isNumeric(elem){
	var numericExpression = /^[0-9]+$/;
	if(elem.value.match(numericExpression)){
		return true;
	}else{
		elem.focus();
		return false;
	}
}

function isAlphabet(elem){
	var alphaExp = /^[a-zA-Z]+$/;
	if(elem.value.match(alphaExp)){
		return true;
	}else{
		elem.focus();
		return false;
	}
}

function isAlphanumeric(elem){
	var alphaExp = /^[0-9a-zA-Z]+$/;
	if(elem.value.match(alphaExp)){
		return true;
	}else{
		elem.focus();
		return false;
	}
}

function lengthRestriction(elem, min, max){
	var uInput = elem.value;
	if(uInput.length >= min && uInput.length <= max){
		return true;
	}else{
		elem.focus();
		return false;
	}
}

function madeSelection(elem){
	if(elem.value == "Choix"){
		elem.focus();
		return false;
	}else{
		return true;
	}
}

function emailValidator(elem){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
	if(elem.value.match(emailExp)){
		return true;
	}else{
		elem.focus();
		return false;
	}
}

function setbdidinput(bdid){
	var listinput = document.getElementById("bdid");
	listinput.value = bdid;
}

//submenu hide / show
function div_showmenu(activemenu, nextmenu){
	el = document.getElementById(nextmenu);
	if (el.style.display=='none'){
		el.style.display='block';
	}
	el = document.getElementById(activemenu);
	if (el.style.display=='block'){
		el.style.display='none';
	}
	return nextmenu;
}

//checkbox table item
function checkall(listname, ischecked) {
	var frm= document.getElementById(listname);
	for (var i =0; i < frm.elements.length; i++) {
		if ( frm.elements[i].type.toLowerCase() == "checkbox" ) {
			frm.elements[i].checked = ischecked;
		}
	}
}

function listchecked(formid,listid){
	var list="";
	var frm = document.getElementById(formid);
	for (var i =0; i < frm.elements.length; i++) {
		if ( frm.elements[i].type.toLowerCase() == "checkbox" ) {
			if ( frm.elements[i].checked == true ) {
				list += frm.elements[i].value + ",";
			}
		}
	}
	var listinput = document.getElementById(listid);
	listinput.value = list;
}

//Copy / Paste 
function copyform(field) {
	if (document.body.createControlRange) {
		var x = window.document.getElementById(field);
		x.contentEditable = 'true';
		var controlRange;
		controlRange = document.body.createControlRange();
		controlRange.addElement(x);
		controlRange.execCommand("Copy");
		x.contentEditable = 'false';
	}
}