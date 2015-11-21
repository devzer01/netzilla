
var _ = {
    json: null,
    page: null,
    reloadTime: null,
    init: function() {
        $("#updateStatus").click(function(e) {
            e.preventDefault();
            $("#showstatus")
                .html("Please wait. now updating ....")
                .load('ajax.php?section=fetchStatus&returnTo='+_.page);
        });
        
        _.reloadTime = setInterval(function(){
        	$("#londonTime").html(_.calcTime('London','+1'));
        	$("#berlinTime").html(_.calcTime('Berlin','+2'));
        },1000);
        
        $("#site").on('change',function (e) {
            $.get("json.php?action=vcardh&id=" + $('#site').find(":selected").val(), function (data) {
                $("#vcard").val(data);
            });
            $.get("json.php?action=vcardh2&id=" + $('#site').find(":selected").val(), function (data) {
                $("#vcard2").val(data);
            });     
            $("#vcard_site_id").val($('#site').find(":selected").val());
        });
    },
    loading: {
        show: function () {
            $("#message").html("Please wait ... Loading Preset");
        },
        hide: function () {
            $("#message").html("");
        }
    },
    getPreset: function (c, d) {
        _.loading.show();
        
        // Reset Option
        $("#repeat_profile").prop('checked', false);
        $("#logout_after_sent").prop("checked",false);
        $("#vcard, #vcard2").val("vCard Loading ....");
        
        $.post('?action=load_preset', {
            id: c,
            index: d
        }, function (a) {
            _.json = jQuery.parseJSON(a);
            $("select[name='site']").val(_.json['site']);
            var b = parseInt(_.json['site']);
			var site1 = [5,10,35,39,46,50,51,54,55,56,57,58,59,60,63,66,67,68,69,71,73,78];
			var site2 = [5,10,35,39,46,50,51,54,55,56,57,58,59,60,63,66,67,68,69,71,73,78];
			if(($.inArray(b, site1) != -1) || b > 84){
                $("#messages_area").show();
				if($.inArray(b, site2) != -1 || b > 84){
                    if (login_by == 1) {
                        $("#repeat_area").show();
                    } else {
                        $("#repeat_area").hide();
                    }
                } else {
                    $("#repeat_area").hide();
                }
            } else {
                $("#messages_area").hide();
                $("#repeat_area").hide();
            }
            _.getSearchOption();
        });
    },
    getSearchOption: function () {
        var b = '_search_option/' + $("select[name=site] option:selected").text() + '.txt';
        var c = '_search_option/default.txt';
        $.ajax({
            type: 'HEAD',
            url: b,
            success: function () {
                $.get(b, function (a) {
                    $('#resultsearchoption').html(a);
                    _.setBotOptions();
                });
            },
            error: function () {
                $.get(c, function (a) {
                    $('#resultsearchoption').html(a);
                    _.setBotOptions();
                });
            }
        });
    },
    setBotOptions: function () {
        for (var i in _.json) {
            if (i != 'site') {
                if (typeof (_.json[i]) === "object") {
                    for (var j in _.json[i]) {
                        if ($("*[name='" + i + "[" + j + "]']").is(':checkbox')) {
                            $("*[name='" + i + "[" + j + "]']").prop('checked', 'checked').trigger('change');
                        } else if ($("*[name='" + i + "[]']").is(':checkbox')) {
                            $("*[name='" + i + "[]']").each(function () {
                                $("*[name^=" + i + "][value=" + _.json[i][j] + "]").attr("checked", true);
                                $(this).trigger('change');
                            });
                        } else {
                            $("*[name='" + i + "[" + j + "]']").val(_.json[i][j]).trigger('change');
                        }
                    }
                } else {
                    if ($("*[name=" + i + "]").is(':checkbox')) {
                        $("*[name=" + i + "]").prop('checked', 'checked').trigger('change');
                    } else if ($("*[name=" + i + "]").is(':radio')) {
                        $("*[name=" + i + "][value=" + _.json[i] + "]").prop('checked', 'checked').trigger('change');
                    } else {
                        $("*[name=" + i + "]").val(_.json[i]).trigger('change');
                    }
                }
            }
        }
        
        // Load v-card
        $.get("json.php?action=vcardh&id=" + $('#site').find(":selected").val(), function (data) {
            $("#vcard").val(data);
        });
        $.get("json.php?action=vcardh2&id=" + $('#site').find(":selected").val(), function (data) {
            $("#vcard2").val(data);
        });     
        $("#vcard_site_id").val($('#site').find(":selected").val());

		_.sleep(2000, _.setMaskURL);

        _.loading.hide();
    },
	setMaskURL: function () {
        for (var i in _.json) {
			if(i == 'mask_url')
			{
				$("*[name=" + i + "]").val(_.json[i]).trigger('change');
			}
		}
	},
	sleep: function (millis, callback) {
		setTimeout(function()
				{ callback(); }
		, millis);
	},
	calcTime: function(city, offset) {
	    // create Date object for current location
	    d = new Date();
	    
	    // convert to msec
	    // add local time zone offset 
	    // get UTC time in msec
	    utc = d.getTime() + (d.getTimezoneOffset() * 60000);
	    
	    // create new Date object for different city
	    // using supplied offset
	    nd = new Date(utc + (3600000*offset));
	    
	    // return time as a string
	    // return "The local time in " + city + " is " + nd.toLocaleString();
	    return "The local time is " + nd.toLocaleString();
	
	}
};
