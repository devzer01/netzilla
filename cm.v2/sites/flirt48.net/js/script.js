var country = new Array();
var state = new Array();
var city = new Array();
var country_select;
var state_select;
var city_select;
var q_country_select;
var q_state_select;
var q_city_select;
var register_step_2_ok = false;
var register_step_3_ok = false;
var notificationSoundElement = "";

function ageRange(idage_min, idage_max)
{
	var agemin = $(idage_min);
	var agemax = $(idage_max);
	var agemaxselected = agemax.options[agemax.selectedIndex].value;
	var n = 0;
	var begin = 0;
	for(var i=0;i<agemin.options.length;i++)
	{
		if(agemin.options[i].selected == true)
		{
			var begin = 1;
			if(parseInt(agemaxselected)<parseInt(agemin.options[i].value))
			{
				agemaxselected = parseInt(agemin.options[i].value)+2;
			}
		}
		if(begin)
		{
			var opt = new Option();
			opt.value = opt.text = agemin.options[i].value;
			if(opt.value == agemaxselected)
				opt.selected = true;
			agemax.options[n] = opt;
			n++;
		}
	}
	agemax.options.length = n;
}

function rememberMe()
{
	if($('remember').checked == true)
	{
		return '&remember=1';
	}
	else
	{
		return '&remember=0';
	}
}

function ajaxRequest(action, parameters, loading, complete, error)
{
	var url = 'ajaxRequest.php';
	var pars = 'action=' + action;
	pars = pars+'&'+parameters;

	var myAjax = new Ajax.Request(
		url,
		{
			method: 'post',
			parameters: pars,
			onComplete: eval(complete),
			onLoading: eval(loading),
			onFailure: eval(error)
		});
}

function checkSendMail()
{
	var subject = $('subject').value;
	var message = $('message').value;

	var data = Array(Array('subject', subject, '==', '', subject_alert),
					 Array('message', message, '==', '', message_alert)
					 );

	return checkActionFocus(data);
}

function checkecardto(username)
{
	if(checkNull($(username).value))
	{
		ajaxRequest('isUsername', 'username='+$(username).value, '', 'toUsername', 'reportError');

	}
}

function checkUsername(username)
{
	var username = document.getElementById('username');

	if(checkNull(username.value))
	{
		if((username.value.length>=6) && (username.value.length<=30))
			ajaxRequest('isUsername', 'username='+username.value, '', 'isUsername', 'reportError');
		else
			alert(usernameLength_alert);
	}
}

function checkUsername2(username)
{
	var username = document.getElementById(username);

	if(checkNull(username.value))
	{
		if((username.value.length>=6) && (username.value.length<=30))
			ajaxRequest('isUsername', 'username='+username.value, '', 'isUsername2', 'reportError');
		else
			alert(usernameLength_alert);
	}
}

//function paste by D.Krause
function checkStrToNum(phone_number)
{
	for(var n=0;n<phone_number.length;n++)
	{
		var chr = phone_number.charAt(n);

		if(isNaN(chr))
		{
    		return false;
  		}
	}
	return true;
}

//function paste by D.Krause
function checkMobilePhone(phone_code, phone_number)
{
	if(checkNull($(phone_number).value));
	{
		if(checkStrToNum($(phone_number).value) == true)
		{
			if(($(phone_number).value.length >= 7) && ($(phone_number).value.length <=10)){
				var complete_number = $(phone_code).value + $(phone_number).value;
				//alert(complete_number);
				ajaxRequest('isPhoneNumber', 'phone_number='+complete_number, '', 'isPhoneNumber', 'reportError');
			}else
				alert(mobile_alert);
		}else
			alert(mobile_format);
	}
}

//function paste by D.Krause
function checkMobilePhone2(phone_code, phone_number)
{
	var complete_number = $(phone_code).value + $(phone_number).value;
	//alert(complete_number);
	ajaxRequest('isPhoneNumber', 'phone_number='+complete_number, '', 'isPhoneNumber2', 'reportError');
}

/*
function checkMobilePhone(phone_code, phone_number)
{
	if(checkNull($(phone_number).value))
	{
		if(($(phone_number).value.length >= 7) && ($(phone_number).value.length <=10)){
			var complete_number = $(phone_code).value + $(phone_number).value;
			//alert(complete_number);
			ajaxRequest('isPhoneNumber', 'phone_number='+complete_number, '', 'isPhoneNumber', 'reportError');
		}else
			alert(mobile_alert);
	}
}
*/

function checkWriteLonely()
{
	var headline = $('headline').value;
	var text = $('text').value;

	var data = Array(Array('headline', headline, '==', '', headline_alert),
					 Array('text', text, '==', '', text_alert)
					 );

	return checkActionFocus(data);
}

function checkWriteMessage()
{
	var to = $('to').value;
	var subject = $('subject').value;
	var message = $('message').value;
	var sms = $('sms').value;
	var checksms = $('send_via_sms').checked;

	if(checksms)
	{
		var data = Array(Array('to', to, '==', '', to_alert),
					 Array('sms', sms, '==', '', message_alert)
					 );
	}
	else
	{
		var data = Array(Array('to', to, '==', '', to_alert),
					 Array('subject', subject, '==', '', subject_alert),
					 Array('message', message, '==', '', message_alert)
					 );
	}

	return checkActionFocus(data);
}

function checkQuestionWriteMessage()
{
	var subject = $('subject').value;
	var message = $('message').value;

	var data = Array(Array('subject', subject, '==', '', subject_alert),
					 Array('message', message, '==', '', message_alert)
					 );
	return checkActionFocus(data);
}

function checkWriteSuggestion()
{
	var subject = $('subject').value;
	var message = $('message').value;

	var data = Array(Array('subject', subject, '==', '', subject_alert),
					 Array('message', message, '==', '', message_alert)
					 );

	return checkActionFocus(data);
}

function deleteLonelyHeart(form_name)
{
	$(form_name).action = '?action=lonely_heart_ads&do=delete';
	$(form_name).submit();
}

function deleteMessage(form, idMsg)
{
	var list = '';
	var checkbox = eval("document."+form+"."+idMsg);
	if(checkbox.length>1)
	{
		for(var n=0;n<checkbox.length;n++)
		{
			if(checkbox[n].checked == true)
				list += 'messageid%5B%5D='+checkbox[n].value+'&';
		}
	}
	else
	{
		if(checkbox.checked == true)
				list += 'messageid%5B%5D='+checkbox.value+'&';
	}
	if(list == '')
		alert(select_alert);
	else
		ajaxRequest('deleteMessage', list, '', deleteMessage_Complete, '');
}

function deleteMessage_Complete(originalRequest)
{
	if(originalRequest.status == 200)
	{
		if(originalRequest.responseText == false)
			alert(cannot_del_alert);
		else
			$('message').innerHTML = originalRequest.responseText;
	}
}

function deleteSuggestion(form_name)
{
	$(form_name).action = '?action=admin_suggestionbox&do=delete';
	$(form_name).submit();
}

function enterLogin(e)
{
	var key = e.which || event.keyCode;
	if(key == 13)
	{
		ajaxRequest('login', 'username='+$('l_username').value+'&password='+$('l_password').value, '', loginSite, '')
	}
}

function getNumDate(iddate, month, year)
{
	var daysofmonth   = new Array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var daysofmonthLY = new Array( 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	if (LeapYear(year))
	  daysofmonth = daysofmonthLY;
	list = $(iddate);
	var numday = daysofmonth[month-1];
	var selected = list.options[list.selectedIndex].value;
	for(var i=1;i<=numday;i++)
	{
		opt = new Option();
		opt.text = i;
		opt.value = i;
		if(selected == i)
			opt.selected = true;
		list.options[i-1] = opt;
	}
	list.length = i-1;
}

function toUsername(originalRequest)
{
	if(originalRequest.status == 200)
	{
		if(originalRequest.responseText == 0)
		alert(to_username_alert);
	}
}

function isUsername(originalRequest)
{
	if(originalRequest.status == 200)
	{
		if(originalRequest.responseText > 0)
			var msg = already_txt;
		else
			var msg = ok_txt;
		alert(msg);
	}
}

function isUsername2(originalRequest)
{
	if(originalRequest.status == 200)
	{
		if(originalRequest.responseText > 0) {
			alert(already_txt);
		}
	}
}

function isPhoneNumber(originalRequest)
{
	if(originalRequest.status == 200)
	{
		if(originalRequest.responseText > 0)
		{
			//$("phone_number_info").innerHTML = mobile_existing;
			$("phone_number_info").innerHTML = '';
			register_step_2_ok = false;
		}
		else
		{
			$("phone_number_info").innerHTML = '';
			register_step_2_ok = true;
		}
	}
}

function isPhoneNumber2(originalRequest)
{
	if(originalRequest.status == 200)
	{
		if(originalRequest.responseText > 0)  {
			var msg = already_txt;
			alert(msg);
		}
	}
}

function LeapYear(year)
{
    if ((year/4)   != Math.floor(year/4))   return false;
    if ((year/100) != Math.floor(year/100)) return true;
    if ((year/400) != Math.floor(year/400)) return false;
    return true;
}

function loginSite(originalRequest)
{
	if(originalRequest.status == 200)
	{
		if(originalRequest.responseText != false)
		{
			parent.location = './';
		}
		else
		{
			alert(login_alert);
			$('l_password').select();
		}
	}
}

function isEmailValid()
{
	var email = $('email').value;
	if (!checkFormEmail(email)) {
		$("email_info").innerHTML = "<div class='error_info left'>"+emailForm_alert+"</div>";
		return false;
	}
	return true;
}

function isPasswordMatch()
{
	var password = $('password').value;
	var confirmpassword = $('confirm_password').value;
	
	if (password == confirmpassword) {
		$('password_info').innerHTML = "";
	} else {
		$('password_info').innerHTML = "<div class='error_info left'>" + confirmpasswordMatch_alert + "</div>";
	}
}

function checkActionFocus(data)
{
	for(var n=0;n<data.length;n++)
	{
		if(eval("data[n][1]"+data[n][2]+"data[n][3]"))
		{
			popup(data[n][4]);
			if(data[n][0] != '')
				$(data[n][0]).select();
			return false;
		}
	}
	return true;
}

function checkFormUserName(usrname){
	var ok = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNMÄäÖöÜüß";
	for(i=0; i < usrname.length ;i++){
		if(ok.indexOf(usrname.charAt(i))<0)
			return false;
	}

	var re = /[a-zA-Z0-9ÄäÖöÜüß]$/;
	if (usrname.match(re))
		return true;
}

function checkFormEmail(email)
{
	var ok = "1234567890qwertyuiop[]asdfghjklzxcvbnm.@-_QWERTYUIOPASDFGHJKLZXCVBNM";
	for(i=0; i < email.length ;i++)
	{
		if(ok.indexOf(email.charAt(i))<0)
			return false;
	}
	var re = /(@.*@)|(\.\.)|(^\.)|(^@)|(@$)|(\.$)|(@\.)/;
	var re_two = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	if (!email.match(re) && email.match(re_two))
		return true;
}

function checkNull(value)
{
	if(value)
		return true;
	else
		return false;
}

function checkNullAddUser() {
	var username = $('username').value;
	var data = Array(Array('username', username, '==', '', username_alert),
					 Array('username', username.length, '<', 6, usernameLength_alert),
					 Array('username', username.length, '>', 30, usernameLength_alert)
					);
	return checkActionFocus(data);
}

function checkNullMemberTest()
{
	var username = $('username').value;
	var email = $('email').value;
	var email_check = checkFormEmail($('email').value);
	var gender = checkNullRadio('register_test_form', 'gender');
	var lookmen = checkNullRadio('register_test_form', 'lookmen');
	var lookwomen = checkNullRadio('register_test_form', 'lookwomen');
	var lookpairs = checkNullRadio('register_test_form', 'lookpairs');
	var relationship = checkNullRadio('register_test_form', 'relationship');
	var onenightstand = checkNullRadio('register_test_form', 'onenightstand');
	var affair = checkNullRadio('register_test_form', 'affair');
	var friendship = checkNullRadio('register_test_form', 'friendship');

	var data = Array(Array('username', username, '==', '', username_alert),
  				     Array('username', username.length, '<', 6, usernameLength_alert),
					 Array('username', username.length, '>', 30, usernameLength_alert),
					 Array('email', email, '==', '', email_alert),
					 Array('email', email_check, '!=', true, emailForm_alert),
					 Array('gender', gender, '!=', true, gender_alert),
					 Array('lookmen', lookmen, '!=', true, lookmen_alert),
					 Array('lookwomen', lookwomen, '!=', true, lookwomen_alert),
					 Array('lookpairs', lookpairs, '!=', true, lookpairs_alert),
					 Array('relationship', relationship, '!=', true, relationship_alert),
					 Array('onenightstand', onenightstand, '!=', true, onenightstand_alert),
					 Array('affair', affair, '!=', true, affair_alert),
					 Array('friendship', friendship, '!=', true, friendship_alert)
					 );

	return checkActionFocus(data);
}

function callNullEditprofile()
{
	var gender = checkNullRadio('editProfile', 'gender');
	var country = $('country').value;// document.getElementById('country').options[document.getElementById('country').selectedIndex].value;
	var state = $('state').value;// document.getElementById('state').options[document.getElementById('state').selectedIndex].value;
	var city = $('city').value;// document.getElementById('city').options[document.getElementById('city').selectedIndex].value;
	//var area = document.getElementById('area').value;
	var height = $('height').value; //document.getElementById('height').value;
	var weight = $('weight').value; //document.getElementById('weight').value;
	var tattos = checkNullRadio('editProfile', 'tattos');
	var smoking = checkNullRadio('editProfile', 'smoking');
	var glasses = checkNullRadio('editProfile', 'glasses');
	//var handicapped = checkNullRadio('editProfile', 'handicapped');
	var piercings = checkNullRadio('editProfile', 'piercings');
	var lookmen = checkNullRadio('editProfile', 'lookmen');
	var lookwomen = checkNullRadio('editProfile', 'lookwomen');
	var lookpairs = checkNullRadio('editProfile', 'lookpairs');
	var relationship = checkNullRadio('editProfile', 'relationship');
	var onenightstand = checkNullRadio('editProfile', 'onenightstand');
	var affair = checkNullRadio('editProfile', 'affair');
	var friendship = checkNullRadio('editProfile', 'friendship');
	var cybersex = checkNullRadio('editProfile', 'cybersex');
	var picture_swapping = checkNullRadio('editProfile', 'picture_swapping');
	var live_dating = checkNullRadio('editProfile', 'live_dating');
	var role_playing = checkNullRadio('editProfile', 'role_playing');
	var s_m = checkNullRadio('editProfile', 's_m');
	var partner_exchange = checkNullRadio('editProfile', 'partner_exchange');
	var voyeurism = checkNullRadio('editProfile', 'voyeurism');
	var description = $('description').value;

	var data = Array(Array('gender', gender, '!=', true, gender_alert, 'gender_info'),
					 Array('country', country, '==', 0, country_alert, 'country_info'),
					 Array('state', state, '==', 0, state_alert, 'state_info'),
					 Array('city', city, '==', 0, city_alert, 'city_info'),
					 //Array('area', area, '==', '', area_alert),
					 Array('height', height, '==', 0, height_alert, 'height_info'),
					 Array('weight', weight, '==', 0, weight_alert, 'weight_info'),
					 Array('tattos', tattos, '!=', true, tattos_alert, 'tattos_info'),
					 Array('smoking', smoking, '!=', true, smoking_alert, 'smoking_info'),
					 Array('glasses', glasses, '!=', true, glasses_alert, 'glasses_info'),
					 //Array('handicapped', handicapped, '!=', true, handicapped_alert),
					 Array('piercings', piercings, '==', '', piercings_alert, 'piercings_info'),
					 Array('lookmen', lookmen, '!=', true, lookmen_alert, 'lookmen_info'),
					 Array('lookwomen', lookwomen, '!=', true, lookwomen_alert, 'lookwomen_info'),
					 Array('lookpairs', lookpairs, '!=', true, lookpairs_alert, 'lookpairs_info'),
					 Array('relationship', relationship, '!=', true, relationship_alert, 'relationship_info'),
					 Array('onenightstand', onenightstand, '!=', true, onenightstand_alert, 'onenightstand_info'),
					 Array('affair', affair, '!=', true, affair_alert, 'affair_info'),
					 Array('friendship', friendship, '!=', true, friendship_alert, 'friendship_info'),
					 Array('cybersex', cybersex, '!=', true, cybersex_alert, 'cybersex_info'),
					 Array('picture_swapping', picture_swapping, '!=', true, picture_swapping_alert, 'picture_swapping_info'),
					 Array('live_dating', live_dating, '!=', true, live_dating_alert, 'live_dating_info'),
					 Array('role_playing', role_playing, '!=', true, role_playing_alert, 'role_playing_info'),
					 Array('s_m', s_m, '!=', true, s_m_alert, 's_m_info'),
					 Array('partner_exchange', partner_exchange, '!=', true, partner_exchange_alert, 'partner_exchange_info'),
					 Array('voyeurism', voyeurism, '!=', true, voyeurism_alert, 'voyeurism_info'),
					 Array('description', description, '==', '', description_alert, 'description_info')
					 );


	return checkActionFocus2(data);

	//return checkActionFocus(data);
}

function checkNullRadio(form, name)
{
	var check = false;
	var radio = eval("document."+form+"."+name);
	var num = radio.length;

	for(var n=0;n<num;n++)
	{
		if(radio[n].checked)
			check = true;
	}
	return check;
}

function isValidEmail()
{
	var email = jQuery("#email").val();
	var username = jQuery("#username").val();
	
	if (email == '') {
		$("email_info").innerHTML = "<div class='error_info left'>"+email_alert+"</div>";
		return false;
	}
	
	if (checkFormEmail(email)) {
		checkEmailSilent(email);
	} else {
		$("email_info").innerHTML = "<div class='error_info left'>"+emailForm_alert+"</div>";
	}
	
	checkUsernameSilent(username);
}

function isValidEmailJQuery()
{
	var email = $("#email").val();
	
	if (checkFormEmail(email)) {
		checkEmailSilentJQuery(email);
	} else {
		$('#email_info').validationEngine('showPrompt', emailForm_alert, 'error', 'topRight', true);
	}
}

function checkNullSignup1()
{
	//var forname = $('forname').value;
	//var surname = $('surname').value;
	var username = $('username').value;
	//var username_check = checkUsernameSilent($('username').value);
	var email = $('email').value;
	var email_check = checkFormEmail($('email').value);
	var password = $('password').value;
	var confirmpassword = $('confirm_password').value;
	//var country = $('country').options[$('country').selectedIndex].value;
	var gender = checkNullRadio('register_form', 'gender');
	var accept = $('accept').checked;
	//var area = $('area').value;

	var data = Array(
					//Array('forname', forname.length, '<', 2, forname_alert, ''),
				 	//Array('surname', surname.length, '<', 2, surname_alert, ''),
					Array('username', username, '==', '', username_alert, 'username_info'),
					Array('username', username.length, '<', 6, usernameLength_alert, 'username_info'),
					Array('username', username.length, '>', 30, usernameLength_alert, 'username_info'),
					//Array('username', username_check, '!=', true, 'Duplicate nickname.', ''),
					Array('email', email, '==', '', email_alert, 'email_info'),
					Array('email', email_check, '!=', true, emailForm_alert, 'email_info'),
					Array('gender', gender, '!=', true, gender_alert, 'gender_info'),
					Array('password', password, '==', '', password_alert, 'password_info'),
					Array('password', password.length, '<', 6, passwordLength_alert, 'password_info'),
					Array('password', password.length, '>', 30, passwordLength_alert, 'password_info'),
					Array('confirm_password', confirmpassword, '==', '', confirmpassword_alert, 'confirm_password_info'),
					Array('confirm_password', password, '!=', confirmpassword, confirmpasswordMatch_alert, 'confirm_password_info'),
					//Array('country', country, '==', 0, country_alert, 'country_info'),
					Array('accept', accept, '==', false, accept_alert,'accept_info')
					);

	return checkActionFocus2(data);
}


function checkNullSignupJQuery()
{
	var username = $('#username').val();
	var email = $('#email').val();
	var email_check = checkFormEmail($('#email').val());
	var password = $('#password').val();
	var gender = $(".gender").is(':checked');
	var accept = $('#accept').is(':checked');

	var data = Array(
					Array('username', username, '==', '', username_alert, 'username_info'),
					Array('username', username.length, '<', 6, usernameLength_alert, 'username_info'),
					Array('username', username.length, '>', 30, usernameLength_alert, 'username_info'),
					Array('email', email, '==', '', email_alert, 'email_info'),
					Array('email', email_check, '!=', true, emailForm_alert, 'email_info'),
					Array('gender', gender, '!=', true, gender_alert, 'gender_info'),
					Array('password', password, '==', '', password_alert, 'password_info'),
					Array('password', password.length, '<', 6, passwordLength_alert, 'password_info'),
					Array('password', password.length, '>', 30, passwordLength_alert, 'password_info'),
					Array('accept', accept, '==', false, accept_alert,'accept_info')
					);

	return checkActionFocusJQuery(data);
}

function checkActionFocusJQuery(data)
{
	var status = true;
	for(var n=0;n<data.length;n++)
	{
		if(eval("data[n][1]"+data[n][2]+"data[n][3]"))
		{
			//alert(data[n][4]);//alert(data[n][5]);
			if((data[n][0] != '') && (data[n][5] != ''))
			{
				$('#' + data[n][5]).validationEngine('showPrompt', data[n][4], 'error', 'topRight', true);
			}
			status = false;
		}
	}
	return status;
}



function checkActionFocus2(data)
{
	var status = true;
	for(var n=0;n<data.length;n++)
	{
		if(eval("data[n][1]"+data[n][2]+"data[n][3]"))
		{
			//alert(data[n][4]);//alert(data[n][5]);
			if((data[n][0] != '') && (data[n][5] != ''))
			{
				if(data[n][5] != "accept_info") {
					$(data[n][5]).innerHTML = "<div class='error_info left'>"+data[n][4]+"</div>";
				} else {
					$(data[n][5]).innerHTML = "<div class='error_info position-top'>"+data[n][4]+"</div>";
				}
			}
			status = false;
		}
	}
	return status;
}

function checkNullSignup2()
{
	/*var country = $('country').options[$('country').selectedIndex].value;
	var state = $('state').options[$('state').selectedIndex].value;
	var city = $('city').options[$('city').selectedIndex].value;
	var gender = checkNullRadio('register_form', 'gender');
	var looking_for = checkNullRadio('register_form', 'looking_for');*/
	var phone_number = $('phone_code2').value + $('phone_number').value;

	var data = Array(
					//Array('phone_number',register_step_2_ok, '==', false,mobile_existing,'phone_number_info'),
					Array('phone_number',register_step_2_ok, '==', false,"",'phone_number_info'),
					//Array('country', country, '==', 0, country_alert, 'country_info'),
					//Array('state', state, '==', 0, state_alert, 'state_info'),
					//Array('city', city, '==', 0, city_alert, 'city_info'),
					//Array('gender', gender, '!=', true, gender_alert, 'gender_info'),
					//Array('looking_for', looking_for, '!=', true, lookingfor_alert, 'looking_for_info'),
					Array('phone_number', phone_number.length, '<', 9, mobile_alert, 'phone_number_info'),
					Array('phone_number', phone_number.length, '>', 12, mobile_alert, 'phone_number_info')
					);

	return checkActionFocus2(data);
	/*
	var height = $('height').value;
	var weight = $('weight').value;
	var tattos = checkNullRadio('register_form', 'tattos');
	var smoking = checkNullRadio('register_form', 'smoking');
	var glasses = checkNullRadio('register_form', 'glasses');
	//var handicapped = checkNullRadio('register_form', 'handicapped');
	var piercings = checkNullRadio('register_form', 'piercings');

	var data = Array(Array('height', height, '==', '', height_alert),
					 Array('weight', weight, '==', '', weight_alert),
					 Array('tattos', tattos, '!=', true, tattos_alert),
					 Array('smoking', smoking, '!=', true, smoking_alert),
					 Array('glasses', glasses, '!=', true, glasses_alert),
					 //Array('handicapped', handicapped, '!=', true, handicapped_alert),
					 Array('piercings', piercings, '==', '', piercings_alert)
					 );

	return checkActionFocus(data);*/
}



function checkNullSignup3()
{
	/*var lookmen = checkNullRadio('register_form', 'lookmen');
	var lookwomen = checkNullRadio('register_form', 'lookwomen');
	var lookpairs = checkNullRadio('register_form', 'lookpairs');
	var relationship = checkNullRadio('register_form', 'relationship');
	var onenightstand = checkNullRadio('register_form', 'onenightstand');
	var affair = checkNullRadio('register_form', 'affair');
	var friendship = checkNullRadio('register_form', 'friendship');
	var cybersex = checkNullRadio('register_form', 'cybersex');
	var picture_swapping = checkNullRadio('register_form', 'picture_swapping');
	var live_dating = checkNullRadio('register_form', 'live_dating');
	var role_playing = checkNullRadio('register_form', 'role_playing');
	var s_m = checkNullRadio('register_form', 's_m');
	var partner_exchange = checkNullRadio('register_form', 'partner_exchange');
	var voyeurism = checkNullRadio('register_form', 'voyeurism');
	var description = $('description').value;

	var data = Array(Array('lookmen', lookmen, '!=', true, lookmen_alert),
					 Array('lookwomen', lookwomen, '!=', true, lookwomen_alert),
					 Array('lookpairs', lookpairs, '!=', true, lookpairs_alert),
					 Array('relationship', relationship, '!=', true, relationship_alert),
					 Array('onenightstand', onenightstand, '!=', true, onenightstand_alert),
					 Array('affair', affair, '!=', true, affair_alert),
					 Array('friendship', friendship, '!=', true, friendship_alert),
					 Array('cybersex', cybersex, '!=', true, cybersex_alert),
					 Array('picture_swapping', picture_swapping, '!=', true, picture_swapping_alert),
					 Array('live_dating', live_dating, '!=', true, live_dating_alert),
					 Array('role_playing', role_playing, '!=', true, role_playing_alert),
					 Array('s_m', s_m, '!=', true, s_m_alert),
					 Array('partner_exchange', partner_exchange, '!=', true, partner_exchange_alert),
					 Array('voyeurism', voyeurism, '!=', true, voyeurism_alert),
					 Array('description', description, '==', '', description_alert)
					 );

	return checkActionFocus(data);*/

	var mobile_ver_code = $('mobile_ver_code').value;

	var data = Array(
					Array('mobile_ver_code',register_step_3_ok, '==', false,"",'mobile_ver_code_info'),
					Array('mobile_ver_code', mobile_ver_code.length, '==', "", mobile_ver_code_alert, 'mobile_ver_code_info')
					);

	return checkActionFocus2(data);
}

function checkMobileWithCountryCode()
{
	var country_code = $('phone_code1').value
	var phone_number = $('phone_code2').value + $('phone_number').value;

	var data = Array(
					Array('phone_number', register_step_2_ok, '==', false,"",'phone_number_info'),
					Array('phone_number', phone_number.length, '<', 9, mobile_alert, 'phone_number_info'),
					Array('phone_number', phone_number.length, '>', 12, mobile_alert, 'phone_number_info')
					);

	return checkActionFocus2(data);
}

/*NOI*/
function checkNullSelectOption(elm)
{
	var objName		= elm.name;
	var objVal		= $(objName).options[$(objName).selectedIndex].value;
	var objAlert	= eval(objName + '_alert');
	var objMsgErr	= '';

	if(objAlert != '')
		objMsgErr = objAlert;
	else
		objMsgErr = 'Please Select ' + objName.capitalize();

	var objMsgElm = objName + '_info';

	if(objVal == 0)
	{
		if((objName != '') && (objMsgElm != ''))
			$(objMsgElm).innerHTML = objMsgErr;
		return false;
	}
	else
	{
		$(objMsgElm).innerHTML = '';
		return true;
	}
}

function getCountryCode(country)
{
	if(country == 0)
	{
		$("country_code").innerHTML = '+';
	}
	else
	{
		var mbox = new Ajax.Request("ajaxRequest.php", {method: "post", parameters: "action=getCountryCode&country_id="+country, onComplete: setCountryCode});
	}
}

function setCountryCode(originalRequest)
{
	if(originalRequest.status == 200)
	{
		if(originalRequest.responseText != '')
		{
			$("country_code").innerHTML = '+' + originalRequest.responseText;
		}
		else
		{
			$("country_code").innerHTML = '+';
		}
	}
}

function checkNullRadioOption(frm, elm, msgErr)
{
	var objName		= elm.name;
	var objVal		= checkNullRadio(frm, objName);
	var objMsgErr	= msgErr;

	var objMsgElm = objName + '_info';

	if(objVal != true)
	{
		if((objName != '') && (objMsgElm != ''))
			$(objMsgElm).innerHTML = objMsgErr;

		return false;
	}
	else
	{
		$(objMsgElm).innerHTML = '';
		return true;
	}
}

function checkNullInputText(elm, msgErr)
{
	var objName		= elm.name;
	var objVal		= elm.value;

	var objMsgElm = objName + '_info';
	var objMsgErr	= msgErr;

	if((objVal.length > 1) && (objVal>30))
	{
		$(objMsgElm).innerHTML = '';
	}
	else
	{
		$(objMsgElm).innerHTML = objMsgErr;
	}
}

function checkNullTextArea(elm, msgErr)
{
	var objName		= elm.name;
	var objVal		= elm.value;

	var objMsgElm = objName + '_info';
	var objMsgErr	= msgErr;

	if(objVal.length > 10)
	{
		$(objMsgElm).innerHTML = '';
	}
	else
	{
		$(objMsgElm).innerHTML = objMsgErr;
	}
}

function checkNullPhone(code, code2, phone)
{
	//if((code.length>=1) && (code.length<=4) && ((code2.length + phone.length)>=9) && ((code2.length + phone.length)<=14))
	if(((code2.length + phone.length)>=9) && ((code2.length + phone.length)<=14))
	{
		if(code2.search("0")==0)
		{
			code2 = code2.substring(1);
		}

		var fullnumber = code + code2 + phone;
		var mbox = new Ajax.Request("ajaxRequest.php", {method: "post", parameters: "action=isPhoneNumber&phone_number="+fullnumber, onComplete: isPhoneNumber});
		//register_step_2_ok = true;
	}
	else
	{
		$("phone_number_info").innerHTML = mobile_alert;
	}
}

function checkNullPhoneAutoCode(country, code2, phone)
{
	var mbox = new Ajax.Request("ajaxRequest.php",
									{
										method: "post",
										parameters: "action=getCountryCode&country_id="+country,
										onComplete: function(originalRequest){
											if(originalRequest.status == 200)
											{
												if(originalRequest.responseText != '')
												{
													checkNullPhone(originalRequest.responseText, code2, phone);
													register_step_2_ok = true;
												}
												else
												{
													$("phone_number_info").innerHTML = country_alert;
													register_step_2_ok = false;
												}
											}
										}
									});
}

function checkNullVerifyCode(mobileVerCode)
{
	if(mobileVerCode.length!="")
	{
		register_step_3_ok = true;
	}
	else
	{
		$("mobile_ver_code_info").innerHTML = mobile_ver_code_alert;
		register_step_3_ok = false;
	}
}

function checkNullSignup_adv()
{
  //var area = $('area').value;
	var forname = $('forname').value;
	var surname = $('surname').value;
	var city = $('city').value;
	var street = $('street').value;
	var phone_number = $('phone_number').value;
	var accept = $('accept').checked;

	var data = Array(//Array('forname', forname.length, '<', 2, forname_alert),
				 	 //Array('surname', surname.length, '<', 2, surname_alert),
					 //Array('city', city, '==', 0, city_alert),
					 //Array('street', street, '==', 0, street_alert),
					 //Array('phone_number', phone_number.length, '<', 7, mobile_alert),
					 //Array('phone_number', phone_number.length, '>', 10, mobile_alert),
					 Array('accept', accept, '==', false, accept_alert)
					 );

	return checkActionFocus(data);
}

function callNullUpgrade()
{
	//var area = $('area').value;
	var height = $('height').value;
	var weight = $('weight').value;
	var tattos = checkNullRadio('upgrade_form', 'tattos');
	var smoking = checkNullRadio('upgrade_form', 'smoking');
	var glasses = checkNullRadio('upgrade_form', 'glasses');
	//var handicapped = checkNullRadio('upgrade_form', 'handicapped');
	var piercings = checkNullRadio('upgrade_form', 'piercings');
	var cybersex = checkNullRadio('upgrade_form', 'cybersex');
	var picture_swapping = checkNullRadio('upgrade_form', 'picture_swapping');
	var live_dating = checkNullRadio('upgrade_form', 'live_dating');
	var role_playing = checkNullRadio('upgrade_form', 'role_playing');
	var s_m = checkNullRadio('upgrade_form', 's_m');
	var partner_exchange = checkNullRadio('upgrade_form', 'partner_exchange');
	var voyeurism = checkNullRadio('upgrade_form', 'voyeurism');
	var description = $('description').value;

	var data = Array(
					//Array('area', area, '==', '', area_alert),
					 Array('height', height, '==', '', height_alert),
					 Array('weight', weight, '==', '', weight_alert),
					 Array('tattos', tattos, '!=', true, tattos_alert),
					 Array('smoking', smoking, '!=', true, smoking_alert),
					 Array('glasses', glasses, '!=', true, glasses_alert),
					 //Array('handicapped', handicapped, '!=', true, handicapped_alert),
					 Array('piercings', piercings, '==', '', piercings_alert),
					 Array('cybersex', cybersex, '!=', true, cybersex_alert),
					 Array('picture_swapping', picture_swapping, '!=', true, picture_swapping_alert),
					 Array('live_dating', live_dating, '!=', true, live_dating_alert),
					 Array('role_playing', role_playing, '!=', true, role_playing_alert),
					 Array('s_m', s_m, '!=', true, s_m_alert),
					 Array('partner_exchange', partner_exchange, '!=', true, partner_exchange_alert),
					 Array('voyeurism', voyeurism, '!=', true, voyeurism_alert),
					 Array('description', description, '==', '', description_alert)
					 );

	return checkActionFocus(data);
}

function goUrl(url)
{
	parent.location = url;
}

function adminReplyMessage(form_name)
{
	$(form_name).action = '?action=admin_message&type=reply';
	$(form_name).submit();
}

function loadOptionCountry(originalRequest)
{
	if(originalRequest.status == 200)
	{
		var data_country = originalRequest.responseXML.getElementsByTagName('category')[0].getElementsByTagName('country');

		for(var n=0; n<data_country.length; n++)
		{
			country[n] = new Object();
			country[n].id = data_country[n].getElementsByTagName('id')[0].firstChild.nodeValue;
			country[n].value = data_country[n].getElementsByTagName('name')[0].firstChild.nodeValue;

			var data_state = data_country[n].getElementsByTagName('state');
			state[country[n].id] = new Array();

			for(var i=0; i<data_state.length; i++)
			{
				state[country[n].id][i] = new Object();
				state[country[n].id][i].id = data_state[i].getElementsByTagName('id')[0].firstChild.nodeValue;
				state[country[n].id][i].value = data_state[i].getElementsByTagName('name')[0].firstChild.nodeValue;

				var data_city = data_state[i].getElementsByTagName('city');
				city[state[country[n].id][i].id] = new Array();

				for(var x=0; x<data_city.length; x++)
				{
					city[state[country[n].id][i].id][x] = new Object();
					city[state[country[n].id][i].id][x].id = data_city[x].getElementsByTagName('id')[0].firstChild.nodeValue;
					city[state[country[n].id][i].id][x].value = data_city[x].getElementsByTagName('name')[0].firstChild.nodeValue;
				}
			}
		}

		$('country').options[0] = new Option(select_country, 0);
		$('state').options[0] = new Option(select_state, 0);
		$('city').options[0] = new Option(select_city, 0);

		for(n=0; n<country.length; n++)
		{
			$('country').options[n+1] = new Option(country[n].value, country[n].id);
			if(country_select == country[n].id)
			{
				$('country').options[n+1].selected = true;
				loadOptionState('state', country_select, state_select);
				if(!city_select)
					loadOptionCity('city', 0, '');
				else
					loadOptionCity('city', state_select, city_select);
			}
		}
		$('country').options.length = country.length+1;
	}
}

function loadOptionState(state_id, select_country, select_state)
{
	if((select_country == 0) || (select_country == ''))
	{
		$(state_id).options[0].selected = true;
		$(state_id).options.length = 1;
	}
	else
	{
		for(n=0; n<state[select_country].length; n++)
		{
			$(state_id).options[n+1] = new Option(state[select_country][n].value, state[select_country][n].id);
			if(select_state == state[select_country][n].id)
				$(state_id).options[n+1].selected = true;
		}
		$(state_id).options.length = state[select_country].length+1;
		if($(state_id).options.length==2)
		{
			$(state_id).selectedIndex=1;
			state_select = $(state_id).options[1].value;
		}
	}
}

function loadOptionCity(city_id, select_state, select_city)
{
	if((select_state == 0) || (select_state == ''))
	{
		$(city_id).options[0].selected = true;
		$(city_id).options.length = 1;
	}
	else
	{
		for(m=0; m<city[select_state].length; m++)
		{
			$(city_id).options[m+1] = new Option(city[select_state][m].value, city[select_state][m].id);
			if(select_city == city[select_state][m].id)
				$(city_id).options[m+1].selected = true;
		}
		$(city_id).options.length = city[select_state].length+1;
		if($(city_id).options.length==2)
		{
			$(city_id).selectedIndex=1;
			city_select = $(city_id).options[1].value;
		}
	}
}

function loadOptionCountry1(originalRequest)
{
	if(originalRequest.status == 200)
	{
		var data_country = originalRequest.responseXML.getElementsByTagName('category')[0].getElementsByTagName('country');

		for(var n=0; n<data_country.length; n++)
		{
			country[n] = new Object();
			country[n].id = data_country[n].getElementsByTagName('id')[0].firstChild.nodeValue;
			country[n].value = data_country[n].getElementsByTagName('name')[0].firstChild.nodeValue;

			var data_state = data_country[n].getElementsByTagName('state');
			state[country[n].id] = new Array();

			for(var i=0; i<data_state.length; i++)
			{
				state[country[n].id][i] = new Object();
				state[country[n].id][i].id = data_state[i].getElementsByTagName('id')[0].firstChild.nodeValue;
				state[country[n].id][i].value = data_state[i].getElementsByTagName('name')[0].firstChild.nodeValue;

				var data_city = data_state[i].getElementsByTagName('city');
				city[state[country[n].id][i].id] = new Array();

				for(var x=0; x<data_city.length; x++)
				{
					city[state[country[n].id][i].id][x] = new Object();
					city[state[country[n].id][i].id][x].id = data_city[x].getElementsByTagName('id')[0].firstChild.nodeValue;
					city[state[country[n].id][i].id][x].value = data_city[x].getElementsByTagName('name')[0].firstChild.nodeValue;
				}
			}
		}

		$('country').options[0] = new Option(select_country, 0);
		$('state').options[0] = new Option(select_state, 0);
		$('city').options[0] = new Option(select_city, 0);

		for(n=0; n<country.length; n++)
		{
			$('country').options[n+1] = new Option(country[n].value, country[n].id);
		}
		$('country').options.length = country.length+1;

		if((country_select == 0) || (country_select == ''))
			$('country').options[0].selected = true;
		else
		{
			for(var n=1; n<$('country').options.length; n++)
			{
				if($('country').options[n].value == country_select)
					$('country').options[n].selected = true;
			}
		}

		loadOptionState('state', country_select, state_select);
		loadOptionCity('city', state_select, city_select);
	}
}

function q_loadOptionCountry(originalRequest)
{
	if(originalRequest.status == 200)
	{
		var data_country = originalRequest.responseXML.getElementsByTagName('category')[0].getElementsByTagName('country');

		for(var n=0; n<data_country.length; n++)
		{
			country[n] = new Object();
			country[n].id = data_country[n].getElementsByTagName('id')[0].firstChild.nodeValue;
			country[n].value = data_country[n].getElementsByTagName('name')[0].firstChild.nodeValue;

			var data_state = data_country[n].getElementsByTagName('state');
			state[country[n].id] = new Array();

			for(var i=0; i<data_state.length; i++)
			{
				state[country[n].id][i] = new Object();
				state[country[n].id][i].id = data_state[i].getElementsByTagName('id')[0].firstChild.nodeValue;
				state[country[n].id][i].value = data_state[i].getElementsByTagName('name')[0].firstChild.nodeValue;

				var data_city = data_state[i].getElementsByTagName('city');
				city[state[country[n].id][i].id] = new Array();

				for(var x=0; x<data_city.length; x++)
				{
					city[state[country[n].id][i].id][x] = new Object();
					city[state[country[n].id][i].id][x].id = data_city[x].getElementsByTagName('id')[0].firstChild.nodeValue;
					city[state[country[n].id][i].id][x].value = data_city[x].getElementsByTagName('name')[0].firstChild.nodeValue;
				}
			}
		}

		$('q_country').options[0] = new Option(select_country, 0);
		$('q_state').options[0] = new Option(select_state, 0);
		$('q_city').options[0] = new Option(select_city, 0);

		for(n=0; n<country.length; n++)
		{
			$('q_country').options[n+1] = new Option(country[n].value, country[n].id);
			if(q_country_select == country[n].id)
				$('q_country').options[n+1].selected = true;
		}
		$('q_country').options.length = country.length+1;

		loadOptionState('q_state', q_country_select, q_state_select);
		loadOptionCity('q_city', q_state_select, q_city_select);
	}
}

function replyMessage(form_name)
{
	$(form_name).action = '?action=mymessage&type=reply';
	$(form_name).submit();
}

function reportError()
{
	alert(error_txt);
}

function selectAll(form, id)
{
	var checkbox = eval("document."+form+"."+id);
	var check = false;
	if(checkbox.length>1)
	{
		for(var n=0;n<checkbox.length;n++)
		{
			if(checkbox[n].checked == false)
				check = true;
		}
	}
	else
	{
		if(checkbox.checked == false)
			check = true;
	}
	if(checkbox.length>1)
	{
		for(var n=0;n<checkbox.length;n++)
			checkbox[n].checked = check;
	}
	else
		checkbox.checked = check;
}

function keyLength(limit, val)
{
	if(val.length <= limit)
		return true;
	else
		return false;
}

function sendSMS_checkText()
{
	var sendmessage = $('sendmessage_form');
	if(sendmessage.message.value.length > sendmessage.MAX_SMS.value)
		alert(txt_greater_than+sendmessage.MAX_SMS.value);
}

function stepWizard(show, hide)
{
	Element.show(show);
	//hide.each(Element.hide);
	for(i=0;i<hide.length;i++){
		Element.hide(hide[i]);
		//alert(hide[i]);
	}
}

function submitForm(form)
{
	document.forms[form].submit();
}

function updateMessage(originalRequest)
{
	if(originalRequest.status == 200)
		$('message').innerHTML = originalRequest.responseText;
}

function valid_sendSms()
{
	var sendmessage = $('sendmessage_form');
	if(sendmessage.message.value.length > sendmessage.MAX_SMS.value)
	{
		alert(txt_greater_than+sendmessage.MAX_SMS.value);
		sendmessage.message.focus();
		return false;
	}
	if(sendmessage.message.value.length < sendmessage.MIN_SMS.value)
	{
		alert(txt_greater_than+sendmessage.MIN_SMS.value);
		sendmessage.message.focus();
		return false;
	}

	if(isNaN(sendmessage.phone_number.value))
	{
		alert(mobile_must_be_digit);
		return false;
	}
	phoneNumber_length = sendmessage.phone_number.value.replace(/(^\s+|\s+$)/g,'').length;
	if(phoneNumber_length == 0)
	{
		alert(plz_in_mobile);
		sendmessage.phone_number.value = "";
		sendmessage.phone_number.focus();
		return false;
	}
	if(phoneNumber_length > 8)
	{
		alert(mobile_mus_less_than_8_digit);
		sendmessage.phone_number.value = "";
		sendmessage.phone_number.focus();
		return false;
	}
	return true;
}


function popup(url, height, width)
{
	window.open(url, 'popup', "location=0,menubar=1,resizable=1,scrollbars=1,width="+width+",height="+height);
}

function showAll_ads(username)
{
	$('q_username').value = username;
	$('q_nickname').value = '';
	$('qsearch_form').submit();
}

function setVisibility(el_id, idcheckbox, vis_action){

	var el = document.getElementById(el_id);

	if(vis_action=="none"){
		el.style.display = "none";
	}else{
		el.style.display = "";
	}

	if(idcheckbox=="lha_q_search"){
		document.getElementById('pf_q_search').checked = false;
	}else if(idcheckbox=="pf_q_search"){
		document.getElementById('lha_q_search').checked = false;
	}
}

function isValidCharacterPattern(e,value,type){
	/*
	1 = A-Z,a-z,0-9,Ä,ä,Ö,ö,Ü,ü,ß (for username only)
	2 = email
	3 = 0-9 only

	129 = ü
	132 = ä
	142 = Ä
	148 = ö
	153 = Ö
	154 = Ü
	225 = ß
	*/
	isIE = document.all ?1:0;

	if(isNaN(type)){
		type = 1;
	}
	var keyEntry = !isIE?e.which : event.keyCode;
	switch(type){
		case 1:
			if((keyEntry >= 48 && keyEntry <= 57) || (keyEntry >= 65 && keyEntry <= 90) || (keyEntry >= 97 && keyEntry <= 122) || keyEntry == 129 || keyEntry == 132 || keyEntry == 142 || keyEntry == 148 || keyEntry == 153 || keyEntry == 154 || keyEntry == 225 || keyEntry <= 31) {
				return true;
			} else{
				return false;
			}
		break;
		case 2:
			if(keyEntry == 45 || keyEntry == 46 || (keyEntry >= 48 && keyEntry <= 57) || (keyEntry >= 64 && keyEntry <= 90) || keyEntry == 95 || (keyEntry >= 97 && keyEntry <= 122) || keyEntry <= 31) {
				return true;
			} else{
				return false;
			}
		break;
		case 3:
			if ((keyEntry >= 48 && keyEntry <= 57) || keyEntry <= 31) {
				return true;
			} else{
				return false;
			}
		break;
	}
}

function checkUsernameSilentJQuery(username){
	$("#username_info").validationEngine('hide');
	if(checkNull(username)){
		if(username==old_username){
			$("#username_info").validationEngine('hide');
			username_ok = true;
		} else{
			if((username.length>=6) && (username.length<=30)){
				if(checkFormUserName(username)) {
					$.ajax({
							url: 'ajaxRequest.php',
							type: 'post',
							data: {action: 'isUsername', username: username},
							success: function(json, code, response) {
								if(response.responseText==0){
									$("#username_info").validationEngine('hide');
									username_ok = true;
								} else{
									$('#username_info').validationEngine('showPrompt', duplicate_nickname, 'error', 'topRight', true);
									username_ok = false;
								}
							}
					});
				} else{
					$('#username_info').validationEngine('showPrompt', username_invalid_alert, 'error', 'topRight', true);
					username_ok = false;
				}
			} else{
				$('#username_info').validationEngine('showPrompt', usernameLength_alert, 'error', 'topRight', true);
				username_ok = false;
			}
		}
	}
	else{
		$('#username_info').validationEngine('showPrompt', can_not_empty, 'error', 'topRight', true);
		username_ok = false;
	}
}


function checkUsernameSilent(username){
	if(checkNull(username)){
		if(username==old_username){
			//$("username_indicator").innerHTML="OK";
			$("username_info").innerHTML = "";
			username_ok = true;
		}
		else{
			if((username.length>=6) && (username.length<=30)){
				if(checkFormUserName(username)){
					var mbox = new Ajax.Request("ajaxRequest.php", {method: "post", parameters: "action=isUsername&username="+username, onComplete: checkUsernameSilentResult});
				}
				else{
					$("username_info").innerHTML = "<div class='error_info left'>"+username_invalid_alert+"</div>";
				}
			}
			else{
				//$("username_indicator").innerHTML="X";
				$("username_info").innerHTML = "<div class='error_info left'>"+usernameLength_alert+"</div>";
				username_ok = false;
			}
		}
	}
	else{
		//$("username_indicator").innerHTML="X";
		$("username_info").innerHTML = "<div class='error_info left'>"+can_not_empty+"</div>";
		username_ok = false;
	}
}

function checkUsernameSilentResult(originalRequest){
	if(originalRequest.responseText==0){
		$("username_info").innerHTML = "";
		username_ok = true;
	}
	else{
		$("username_info").innerHTML = "<div class='error_info left'>"+duplicate_nickname+"</div>";
		username_ok = false;
	}
	hideWaitingBox();
}

function checkEmailSilent(email)
{
	if(checkNull(email))
	{
		if(checkFormEmail(email))
		{
			var mbox = new Ajax.Request("ajaxRequest.php", {method: "post", parameters: "action=isEmail&email="+email, onComplete: checkEmailSilentResult});
		}
		else
		{
			$("email_info").innerHTML = "<div class='error_info left'>"+emailForm_alert+"</div>";
			return false;
		}
	}
	else
	{
		$("email_info").innerHTML = "<div class='error_info left'>"+email_alert+"</div>";
		return false;
	}
}

function checkEmailSilentJQuery(email)
{
	$('#email_info').validationEngine('hide');
	if(checkNull(email))
	{
		if(checkFormEmail(email))
		{
			$.ajax({
				url: "ajaxRequest.php",
				type: "post",
				data: {action: 'isEmail', email: email},
				success: function (data, ret, xhr) {
					if (xhr.responseText != 0) {
						$('#email_info').validationEngine('showPrompt', duplicate_email, 'error', 'topRight', true);
					} else {
						if (!email_validated) {
							$.ajax(
									{
										url: 'ajaxRequest.php?action=verify&email=' + email,
										type: 'get',
										dataType: 'json',
										success: function (json) {
											if (json.status==0) {
												$('#email_info').validationEngine('showPrompt', 'Ihre Email Adresse ist nicht korrekt!', 'error', 'topRight', true);
											} else {
												email_validated = true;
											}
										}
									}
							);
						}
					}
				}
			});
		}
		else
		{
			$('#email_info').validationEngine('showPrompt', emailForm_alert, 'error', 'topRight', true);
			//$("email_info").innerHTML = "<div class='error_info left'>"+emailForm_alert+"</div>";
			return false;
		}
	}
	else
	{
		$('#email_info').validationEngine('showPrompt', email_alert, 'error', 'topRight', true);
		//$("email_info").innerHTML = "<div class='error_info left'>"+email_alert+"</div>";
		return false;
	}
}


function checkEmailSilentResult(originalRequest)
{
	var email = $("email").value;
	if(originalRequest.responseText==0) {
		$("email_info").innerHTML = "";
		if (!email_validated) {
			jQuery.ajax(
					{
						url: 'ajaxRequest.php?action=verify&email=' + email,
						type: 'get',
						dataType: 'json',
						success: function (json) {
							if (json.status==0) $("email_info").innerHTML = "<div class='error_info left'>Ihre Email Adresse ist nicht korrekt!</div>";
							else email_validated = true;
						}
					}
			);
		}
	} else {
		$("email_info").innerHTML = "<div class='error_info left'>"+duplicate_email+"</div>";
	}
}

function checkNullPassword(password)
{
	$("password_info").innerHTML = "";
	if((password.length>=6) && (password.length<=30))
		$("password_info").innerHTML = "";
	else
		$("password_info").innerHTML = "<div class='error_info left'>"+passwordLength_alert+"</div>";
}

function checkMatching(fstVal,sndVal)
{
	if(fstVal == sndVal)
		$("confirm_password_info").innerHTML = "";
	else
		$("confirm_password_info").innerHTML = "<div class='error_info left'>"+confirmpasswordMatch_alert+"</div>";
}

function checkAcept(acept)
{
	if(acept.checked !== false)
		$("accept_info").innerHTML = "";
	else
		$("accept_info").innerHTML = "<div class='error_info position-top' style='width: auto; margin-top: 5x; line-height:15px'>"+accept_alert+"</div>";
}

function getZodiac(bDate, bMonth)
{
	var birthDay = bMonth + '-' + bDate;
	if(checkNull(birthDay))
	{
		var mbox = new Ajax.Request("ajaxRequest.php", {method: "post", parameters: "action=getZodiac&bdate="+birthDay, onComplete: setZodiac});
	}
}

function setZodiac(originalRequest)
{
	var ZodiacArray = new Array();
	ZodiacArray[1] = 'Aquarius';		// ( Jan 20 - Feb 18 )
	ZodiacArray[2] = 'Pisces';			// ( Feb 19 - Mar 20 )
	ZodiacArray[3] = 'Aries';			// ( Mar 21 - Apr 19 )
	ZodiacArray[4] = 'Taurus';			// ( Apr 20 - May 20 )
	ZodiacArray[5] = 'Gemini';			// ( May 21 - Jun 20 )
	ZodiacArray[6] = 'Cancer';			// ( Jun 21 - Jul 22 )
	ZodiacArray[7] = 'Leo';				// ( Jul 23 - Aug 23 )
	ZodiacArray[8] = 'Virgo';			// ( Aug 24 - Sep 22 )
	ZodiacArray[9] = 'Libra';			// ( Sep 23 - Oct 22 )
	ZodiacArray[10] = 'Scorpio';		// ( Oct 23 - Nov 21 )
	ZodiacArray[11] = 'Sagittarius';	// ( Nov 22 - Dec 21 )
	ZodiacArray[12] = 'Capricorn';		// ( Dec 22 - Jan 19 )

	$("zodiac_text").innerHTML = ZodiacArray[originalRequest.responseText];
	$("zodiac_val").innerHTML = ZodiacArray[originalRequest.responseText];
}

function switch_submit(sendingType)
{
	if(sendingType=='sms')
	{

		var mbox = new Ajax.Request("ajaxRequest.php",
									{
										method: "post",
										parameters: "action=getCurrentUserMobileNo",
										onComplete: function(originalRequest) {
											if((originalRequest.responseText==="Step2") || (originalRequest.responseText==="Step3"))
											{
												switch (originalRequest.responseText)
												{
													case 'Step2':
														var popup_url = root_path + '?action=incompleteinfo';
														break;
													case 'Step3':
														var popup_url = root_path + '?action=mobileverify';

												}


												Lightview.show({
																href: popup_url,
																rel: 'ajax',
																options: {
																	autosize: true,
																	topclose: true
																}
															});
												return false;
											}
											else if(originalRequest.responseText==="Verified")
											{
												if(checkSendMessageNull())
												{
													$('message_write_form').action = root_path + "?action=mymessage&type=writemessage&send_via_sms=1";
													$('message_write_form').submit();
													return true;
												}
											}
										}
									});

	}
	else
	{
		if(checkSendEmailNull())
		{
			$('message_write_form').action = "?action=mymessage&type=writemessage";
			$('message_write_form').submit();
			return true;
		}
	}
}

function submitAjaxFormIncompleteInfo()
{
	var mobileNo = $('phone_code2').value + $('phone_number').value;
	var mbox = new Ajax.Request("ajaxRequest.php",
								{
									method: "post",
									parameters: "action=ajaxFormIncompleteInfo&mobileNo="+mobileNo,
									onComplete: function(originalRequest) {
										if(originalRequest.responseText==="1")
										{
											loadPagePopup('?action=mobileverify', '100%');
										}
									}
								});
}

function submitAjaxFormMobileVerify()
{
	$('resend_code_info').innerHTML = "";
	if(checkNullSignup3())
	{
		var verCode = $('mobile_ver_code').value;
		var mbox = new Ajax.Request("ajaxRequest.php",
									{
										method: "post",
										parameters: "action=ajaxFormMobileVerify&verCode="+verCode,
										onComplete: function(originalRequest) {
											if(originalRequest.responseText==="1")
											{
												loadPagePopup('?action=mobileverify_successful', '100%');
											}
											else
											{
												alert(originalRequest.responseText);
											}
										}
									});
	}
}

function submitAjaxFormWrongnumber()
{
	var mbox = new Ajax.Request("ajaxRequest.php",
									{
										method: "post",
										parameters: "action=setNulCurrentUserMobileNo",
										onComplete: function(originalRequest) {
											if(originalRequest.responseText==="1")
											{
												loadPagePopup('?action=incompleteinfo', '100%');
											}
										}
									});
}

function submitAjaxFormResendVerify()
{
	var mbox = new Ajax.Request("ajaxRequest.php",
								{
									method: "post",
									parameters: "action=ajaxFormResendVerify",
									onComplete: function(originalRequest) {
										if(originalRequest.responseText!="")
										{
											alert(originalRequest.responseText);
										}
									}
								});
								return false;
}

function checkSendEmailNull()
{
	var to = $('to').value;
	var sms = $('sms').value;
	var subject = $('subject').value;

	var data = Array(
					Array('to', to, '==', '', send_msg_to_alert, 'to_info'),
					Array('subject', subject, '==', '', send_msg_subject_alert, 'subject_info'),
					Array('sms', sms, '==', '', send_msg_sms_alert, 'sms_info')
					);

	return checkActionFocus2(data);
}

function checkSendMessageNull()
{
	var to = $('to').value;
	var sms = $('sms').value;
	$('subject_info').innerHTML = '';

	var data = Array(
					Array('to', to, '==', '', send_msg_to_alert, 'to_info'),
					Array('sms', sms, '==', '', send_msg_sms_alert, 'sms_info')
					);

	return checkActionFocus2(data);
}

function limitText(limitField, limitCount, limitNum)
{
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}

function addFavorite(username, targetArea)
{
	jQuery.ajax({
					url: "ajaxRequest.php",
					type: 'post',
					dataType: "json",
					data: {"action" : "addFavorite", "username":username},
					success: function(data)
					{
						if(data.result == "FINISHED")
						{
							if(targetArea && jQuery('#'+targetArea))
							{
								loadFavorite(targetArea);
							}
							jQuery.smallBox({
								title:favorite_added,
								content: "",
								timeout: 5000,
								color:"#ec008c",
								img: "thumbnails.php?file="+data.picturepath+"&w=100&h=100"
							});
						}
					}
	});
	return false;
}

function loadFavorite(targetArea, style)
{
	jQuery.get("ajaxRequest.php?action=loadFavorite&style="+style, function(data){if(data){jQuery('#'+targetArea).parent().show(); jQuery('#'+targetArea).html(data);}else{jQuery('#'+targetArea).parent().hide();}});
}

function removeFavorite(username, targetArea, style)
{
	if(confirm('Bist du sicher, dass du dieses Mitglied aus deiner Liste entfernen willst?'))
	{
		jQuery.post("ajaxRequest.php",{"action" : "removeFavorite", "username":username}, function(){if(targetArea){loadFavorite(targetArea, style);}});
		return true;
	}
	else
		return false;
}

jQuery(function() {
	jQuery(window).resize(function () {
		//Get the screen height and width
		var maskHeight = jQuery(document).height();
		var maskWidth = jQuery(window).width();

		//Set height and width to mask to fill up the whole screen
		jQuery('#mask').css({'width':maskWidth,'height':maskHeight});

		var box = jQuery('#boxes .window').each(function(){
			//Get the window height and width
			var winH = jQuery(window).height();
			var winW = jQuery(window).width();

			//Set the popup window to center
			jQuery(this).css('top',  winH/2 - jQuery(this).height()/2);
			jQuery(this).css('left', winW/2 - jQuery(this).width()/2);
		});

	});

	jQuery('#mask').click(function () {
		closePopup();
	});

	notificationSoundElement = loadSound("sounds/what.mp3");
});

function loadSound(src)
{
	var sound = document.createElement("audio");
	if ("src" in sound)
	{
		if(!!(sound.canPlayType && sound.canPlayType('audio/mpeg;').replace(/no/, '')))
		{
			src = src;
		}
		else
		{
			src = src.replace("mp3", "ogg");
		}
		sound.autoPlay = false;
	}
	else
	{
		sound = document.createElement("bgsound");
		document.getElementsByTagName('body')[0].appendChild(sound);
		sound.volume = -10000;
		sound.pause = function ()
		{
			this.volume = -10000;
		}
		sound.play = function ()
		{
			this.src = src;
			this.volume = 0;
		}
	}
	sound.src = src;
	document.body.appendChild(sound);
	return sound;
}

function closePopup()
{
	jQuery('#mask').hide();
	jQuery('.window').hide();
}

function loadPagePopup(url, width)
{
	divTag = document.getElementById("profilePopup");
	if(!divTag)
	{
		divTag = document.createElement("div");
		divTag.className = 'window';
		divTag.id = 'profilePopup';
	}
	if(width)
		divTag.style.width = width;
	else
		divTag.style.width = '';
	if(!document.getElementById('boxes'))
	{
		divTag2 = document.createElement("div");
		divTag2.id = 'boxes';
		document.body.appendChild(divTag2);
	}
	document.getElementById('boxes').appendChild(divTag);
	jQuery(divTag).load(url, function() {
		//Get the screen height and width
		var maskHeight = jQuery(document).height();
		var maskWidth = jQuery(window).width();

		//Set heigth and width to mask to fill up the whole screen
		jQuery('#mask').css({'width':maskWidth,'height':maskHeight});

		//transition effect
		//$('#mask').fadeIn(1000);
		jQuery('#mask').fadeTo("fast",0.8);

		//Get the window height and width
		var winH = jQuery(window).height();
		var winW = jQuery(window).width();
		var docH = jQuery(divTag).height();
		var docW = jQuery(divTag).width();

		//Set the popup window to center
		jQuery(divTag).css('top',  winH/2-docH/2);
		jQuery(divTag).css('left', winW/2-docW/2);

		//transition effect
		jQuery(divTag).fadeIn(1500);
	});
}

function showVerifyMobileDialog()
{
	var mbox = new Ajax.Request("ajaxRequest.php",
	{
		method: "post",
		parameters: "action=getCurrentUserMobileNo",
		onComplete: function(originalRequest) {
			if((originalRequest.responseText==="Step2") || (originalRequest.responseText==="Step3"))
			{
				switch (originalRequest.responseText)
				{
					case 'Step2':
						var popup_url = '?action=incompleteinfo';
						break;
					case 'Step3':
						var popup_url = '?action=mobileverify';

				}
				loadPagePopup(popup_url, '100%');
			}
		}
	});
}