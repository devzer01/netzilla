$(function () {
	$(".btn-del-fav").click(function (e) {
		e.preventDefault();
		var that = $(this);
		$.get(app_path + '/ajax/removefavorite/' + $(this).data('username'), function (json) {
			if (json.status == 0) {
				that.parent().remove();
			}
		});
	});
	
	$(".btn-del-foto").click(function (e) {
		e.preventDefault();
		var that = $(this);
		$.get(app_path + '/ajax/removefoto/' + $(this).data('foto-id'), function (json) {
			if (json.status == 0) {
				that.parent().remove();
			}
		});
	});
	
	/**
	 * unfavorite 
	 */
	$(document).on('click', ".unfav", function (e) {
		e.preventDefault();
		var that = $(this);
		$.get(app_path + '/ajax/removefavorite/' + $(this).data('username'), function (json) {
			that.toggleClass('unfav fav');
		});
	});
	
	/**
	 * favorite
	 */
	$(document).on('click', ".fav", function (e) {
		if ($(this).attr('id') != undefined) return true;
		e.preventDefault();
		var that = $(this);
		$.get(app_path + '/ajax/addfavorite/' + $(this).data('username'), function (json) {
			that.toggleClass('fav unfav');
		});
	});
	
	$(".closechat").click(function (e) {
		e.preventDefault();
		if (confirm("Chatpartner entfernen!")) {
			var that = $(this);
			$.get(app_path + '/ajax/closechat/' + $(this).data('username'), function (json) {
				that.parent().parent().parent().parent().remove();
			});
		}
	});
	
	$(".btn-back-chat").click(function (e) {
		console.log('test');
		e.preventDefault();
		window.location.href = app_path + '/chat';
	});
	
	//special case fav and unfav on chat screen
	/**
	 * unfavorite 
	 */
	$(document).on('click', ".cunfav", function (e) {
		e.preventDefault();
		var that = $(this);
		$.get(app_path + '/ajax/removefavorite/' + $(this).data('username'), function (json) {
			that.toggleClass('cunfav cfav');
			that.html('<img src="images/s-icon-02.png"/>');
		});
	});
	
	/**
	 * favorite
	 */
	$(document).on('click', ".cfav", function (e) {
		if ($(this).attr('id') != undefined) return true;
		e.preventDefault();
		var that = $(this);
		$.get(app_path + '/ajax/addfavorite/' + $(this).data('username'), function (json) {
			that.toggleClass('cfav cunfav');
			that.html('<img src="images/s-icon-03.png"/>');
		});
	});
	
	$("#coins").load(app_path + '/ajax/coins');
	$("#msgcount").load(app_path + '/ajax/msgcnt');
});

window.setInterval(function () {
	$("#coins").load(app_path + '/ajax/coins');
	$("#msgcount").load(app_path + '/ajax/msgcnt');
}, 30000);