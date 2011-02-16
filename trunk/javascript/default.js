function appendTextToElement(elementName, text){
	document.getElementById(elementName).innerHTML += text;
	document.getElementById(elementName).style.display = "";
}

function checkEmail(str) {
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if(reg.test(str) == false) {
		return false;
	}
	return true;
	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	if (str.indexOf(at)==-1){
	   return false
	}

	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
	   return false
	}

	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
	    return false
	}

	if (str.indexOf(at,(lat+1))!=-1){
		return false
	}

	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		return false
	}

	if (str.indexOf(dot,(lat+2))==-1){
		return false
	}
	
	if (str.indexOf(" ")!=-1){
		return false
	}
	return true					
}

function confirmSubmit(text) {
	var agree=confirm(text);
	if(agree) return true;
	else return false ;
}

function expand(thistag, img, hideText, showText, newSrcUrl) {
	styleObj=document.getElementById(thistag).style;
	if (styleObj.display=='none')
	{
		styleObj.display='';
		//img.innerHTML = hideText;
		img.src = newSrcUrl+"/collapse.gif";
	}
	else {
		styleObj.display='none';
		//img.innerHTML = showText;
		img.src = newSrcUrl+"/expand.gif";
	}
}

function imagePreview(picture,value) {
	if(value!="") picture.src = "file://localhost/" + value;
}

function imageResizeWidth(img,maxWidth) {
	if(img.width>maxWidth) img.width=maxWidth; 
	img.height=maxWidth*(img.height/img.width);
}

function imageResizeHeight(img,maxHeight) {
	if(img.height>maxHeight) img.height=maxHeight; 
	img.width=maxHeight*(img.width/img.height);
}

function popup(mypage,myname,w,h,features) {
	if(screen.width){
		var winl = (screen.width-w)/2;
		var wint = (screen.height-h)/2;
	}
	else {
		winl = 0;
		wint =0;
	}

	if(winl<0) winl = 0;
	if(wint<0) wint = 0;
	var settings = 'height=' + h + ',';
	settings += 'width=' + w + ',';
	settings += 'top=' + wint + ',';
	settings += 'left=' + winl + ',';
	settings += features;
	win = window.open(mypage,myname,settings);
	win.window.focus();
	if(!win.opener) win.opener = self;
}

function protectMail(name, domain) {
	window.location = 'mailto:' + name + '@' + domain;	
}

function replaceText(thistag, img, hideText, showText, newSrcUrl) {
	object=document.getElementById(thistag);
	object.innerHTML = hideText;
}

function selectAll(form, checkAllBox, select) {
	for(var i=0;i<form.length;i++) {
		var e = form.elements[i];
		if(e.type=='checkbox') {
			if (select) e.checked = true;
			else e.checked=false;
		}
	}
}

function validateDate(day,month,year) {
	var date=new Date(year.value,month.value-1,day.value)
	day.value=date.getDate()
	month.value=date.getMonth()+1
	year.value=date.getFullYear()
}

function validateField(field, normalClass, errorClass, warningIcon) {
	if (field.name == 'u_email') {
		if (checkEmail(field.value)) {
			field.className = normalClass; 
			warningIcon.style.display='none'; 
		}
		else {
			field.className = normalClass+' '+errorClass; 
			warningIcon.style.display=''; 			
		}
		return;		
	}
	if (field.value!='') { 
		field.className = normalClass; 
		warningIcon.style.display='none'; 
	} 
	else { 
		field.className = normalClass+' '+errorClass; 
		warningIcon.style.display=''; 
	}
}
