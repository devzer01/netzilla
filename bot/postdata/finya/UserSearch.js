var UserSearch = {
	init: function (a) {
		this.type = a || "standard";
		this.start = 0;
		this.filters = Jpy.id("usFilter");
		this.results = Jpy.id("usResults");
		this.getResults();
		this.more = Jpy.id("usMore");
		this.appendFooter();
		this.watchScrolling()
	},
	appendFooter: function () {
		var d = this;
		var b = DOM.span({
			className: "stats"
		});
		this.numberOfResultsSpan = b;
		var a = DOM.div({
			className: "actionSelector alignLeft cf",
			child: [DOM.div({
				className: "button blue",
				text: "Mehr anzeigen",
				click: function () {
					d.start = d.start + d.numberOfResults;
					d.getResults()
				}
			})]
		});
		var c = DOM.div({
			className: "pd15",
			child: b
		});
		this.more.innerHTML = "";
		this.more.appendChild(a);
		this.more.appendChild(c)
	},
	suggestedFilters: {},
	lastUpdate: Math.floor(new Date().getTime()),
	numberOfResults: 25,
	start: 0,
	getResults: function () {
		var c = this;
		var b = {
			start: this.start,
			numberOfResults: this.numberOfResults,
			h: User.hash
		};
		var a = "/backend/search/standard.php";
		if (this.type == "interest") {
			b.interestId = UserSearch.interestId;
			a = "/backend/search/interest.php"
		} else {
			if (this.type == "flirt") {
				a = "/backend/search/flirt.php"
			}
		}
		var d = {
			json: true,
			success: function (e) {
				UserSearch.insertResults(e);
				if (e.numberOfUsers == 0) {
					c.showInfo("Keine Nutzer gefunden", "Überprüfe deine Filter. Zu viele Einschränkungen führen zu weniger Ergebnissen.")
				} else {
					if (e.numberOfUsers < c.numberOfResults) {
						c.showInfo("Wenige Nutzer gefunden", "Überprüfe deine Filter")
					}
				}
				c.scrollingLoadingLock = false;
				c.lastUpdate = Math.floor(new Date().getTime());
				if (e.numberOfUsers < (c.start + c.numberOfResults)) {
					Jpy.hide(c.more)
				} else {
					if (e.numberOfUsers == 1000) {
						c.numberOfResultsSpan.innerHTML = "Mehr als 1000 Ergebnisse"
					} else {
						c.numberOfResultsSpan.innerHTML = parseInt(e.numberOfUsers) - (c.start + c.numberOfResults) + " weitere Ergebnisse"
					}
				}
			}
		};
		new AjaxHandler().request("POST", a, d, b)
	},
	scrollingLoadingLock: false,
	showInfo: function (a, c) {
		Jpy.remove("usInfoUser");
		var b = DOM.div({
			id: "usInfoUser",
			className: "msInfo fl",
			style: "width:100%",
			child: [DOM.div({
				className: "headline",
				text: a
			}), DOM.div({
				className: "text",
				text: c ? c: ""
			})]
		});
		UserSearch.results.appendChild(b)
	},
	watchScrolling: function () {
		var b = this;
		var a = function (f) {
			f = f || window.event;
			var c = window.innerHeight + Jpy.getScrolling().y;
			if (b.scrollingLoadingLock == false) {
				if (document.body.offsetHeight - c < 300 && /^\/search/.test(location.pathname)) {
					var d = Math.floor(new Date().getTime()) - b.lastUpdate;
					if (d > 800) {
						b.start = b.start + b.numberOfResults;
						b.scrollingLoadingLock = true;
						b.getResults()
					}
				}
			}
		};
		Jpy.removeEvent(document, "scroll", a);
		Jpy.addEvent(document, "scroll", a)
	},
	insertResults: function (d) {
		var c = d.result;
		if (!c) {
			return
		}
		if (this.start == 0) {
			this.results.innerHTML = ""
		}
		for (var b = 0, a = c.length; b < a; b++) {
			if (c[b].stamp) {
				if (c[b].stamp.gender) {
					var e = new this.result(c[b]);
					this.results.appendChild(e.frame)
				}
			}
		}
	},
	result: function (a) {
		var c = DOM.div({
			className: "activity",
			text: Timestamp.getActivity(a.stamp)
		});
		var b = DOM.fragment();
		if (a.stamp.town) {
			b = DOM.div({
				className: "zipcode",
				child: [DOM.span({
					className: "code",
					text: Jpy.truncate(30, a.stamp.town)
				})]
			})
		}
		this.frame = DOM.link({
			href: "",
			className: "entry",
			child: [c, Jpy.getUserImage(a.stamp.profileImage, 120, a.stamp.hashM, undefined, "m"), b, DOM.div({
				className: "stamp",
				text: Userstamp.get(a.stamp, "noLink")
			})],
			mouseover: function () {
				Jpy.css.hoverClass(this, "highlight");
				Jpy.show(c);
				this.onmouseout = function () {
					this.className = "entry";
					Jpy.hide(c)
				}
			}
		});
		if (User.profilePopup == "y") {
			this.frame.onclick = function () {
				Jpy.popupProfile(a.stamp.nickname);
				return false
			}
		} else {
			this.frame.href = "/user/" + a.stamp.nickname
		}
	}
};