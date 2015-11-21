<?php
require_once('bot.php');

/*******************************/
/************ TO DO ************/
/*******************************/
/*
- change all URL variables in flirtfever()
- change login post array in addLoginData()
- change search post array in work()
- ...
*/
/*******************************/
/************ / TO DO ************/
/*******************************/

class Jappy extends bot
{
	public function jappy($post)
	{
		if(is_array($post) && count($post))
		{
			ignore_user_abort(true);
			$commandID = $post['id'];
			$runCount = $post['run_count'];
			$botID = $post['server'];
			$siteID = $post['site'];
			$this->command = $this->mb_unserialize($post['command']);
			$post['command'] = $this->command;
			file_put_contents("logs/".$commandID."_post.log",print_r($post,true));
			file_put_contents("logs/".$commandID."_run_count.log",$runCount);
		}
		else
		{
			$this->command = array(
									"profiles" => array(
															array(
																	"username" => "blondinehanckel@yahoo.de",
																	"password" => "erstaunlich"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => " freuemich punkt net "
																)
														),
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"ageStart" => 11,
									"ageEnd" => 35,
									"gender" => "both",
									"msg_type" => "gb",
									"send_test" => 0,
									"distance" => 0,
									"start_city" => "Cardiff",
									"start_page" => 1,
									"version" => 2,
									"range_start" => 31,
									"range_end" => 392910,
									//"full_msg" => 1,
									//"online" => 3600,				//now
									//"online" => 86400,			//last day
									//"online" => 604800,			//last week
									//"online" => 2678400,		//last month
									"isOnline" => 0,		//last year
									//"online" => 315360000,		//last decade
									"withImage" => 0,
									"locationId	" => 7063,
									"locationName" => "Berlin, Prenzlauer Berg",
									"name" => "",
									"recentRegistration" => 0,
									"useLocation" => 0,
									"entryVisibility" => 1, //1: Public, 2: Private
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 54;
		}

		if(isset($this->command['inboxLimit']) && is_numeric($this->command['inboxLimit']))
			$this->inboxLimit = $this->command['inboxLimit'];
		else
			$this->inboxLimit = 10;

		$this->databaseName = "loomin";
		$this->userhash = "";

		//Login
			$this->usernameField = "login";
			$this->indexURL = "http://www.loomin.de/home/";
			$this->indexURLLoggedInKeyword = "/logout/";
			$this->loginURL = "http://www.loomin.de/login";
			$this->loginRefererURL = "http://www.loomin.de";
			$this->loginRetry = 3;
			$this->logoutURL = "http://www.jappy.de/password?logout=1&h=35243";
		
		//Search
			$this->searchPageURL = "http://www.loomin.de/search/advanced/";
			$this->searchURL = "http://www.jappy.de/search/standard/settings/alter";
			$this->searchRefererURL = "http://www.jappy.de/search/standard/settings";
			$this->searchQueryURL = "http://www.jappy.de/backend/search/standard.php";
			$this->locationQueryURL = "https://www.jappy.de/backend/getLocation.php?location=";
			$this->searchResultsPerPage = 25;		

		$this->profileURL = "http://www.loomin.de";
		
		//Message
			$this->sendMessagePageURL = "http://www.jappy.de/mailbox";
			$this->sendMessageURL = "http://www.jappy.de/backend/messageStream/send.php";
			$this->sendQuestionURL = "http://feed.meetme.com/askMe/json/submit";
			$this->sendIMURL = "http://www.okcupid.com/instantevents";
			$this->sendGuestbookURL = "http://single.de/Rest/postfach-message";
			$this->urlReferer = "https://www.jappy.de/mailbox/compose";
			
			$this->inboxURL = "http://www.jappy.de/mailbox";
			$this->deleteInboxURL = "http://www.jappy.de/mailbox";
			$this->conversation = "http://www.jappy.de/mailbox/conversation";
			$this->deleteInboxRefererURL = "http://www.okcupid.com/messages";
			$this->outboxURL = "http://www.jappy.de/mailbox";
			$this->deleteOutboxURL = "http://www.okcupid.com/mailbox";
			$this->deleteOutboxRefererURL = "http://www.okcupid.com/messages?folder=2";
			$this->deleteUrl = "http://www.jappy.de";
		
		//Guestbook
			$this->sendGuestbookPageURL = "";

		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (Windows NT 6.1; rv:20.0) Gecko/20100101 Firefox/20.0";
		$this->commandID = $commandID;
		$this->workCount = 1;
		$this->siteID = $siteID;
		$this->botID = $botID;
		$this->runCount = $runCount;
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->addLoginData($this->command['profiles']);
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		$this->message="";
		$this->newMessage=true;
		

		$this->cities = array(
			303 => "Berlin, Pankow",310 => "Berlin, Spandau",544 => "Berlin, Buckow",971 => "Berlin",1325 => "Berlin, Reinickendorf",1415 => "Berlin, Friedenau",24624 => "Berlin, Berlin-Treptow",57195 => "Berlin, Berlin-Schmöckwitz",89263 => "Berlin, Berlin-Buchholz",312 => "München, Ramersdorf",456 => "München, Mittersendling",679 => "München, Prinz-Ludwigs-Höhe",2223 => "München, Sendling",3855 => "München",3275 => "München, Fürstenried",3413 => "München, Maxvorstadt",3488 => "München, Au-Haidhausen",184 => "Köln, Porz",277 => "Köln, Hardt",360 => "Köln, Esch/Auweiler",995 => "Köln, Gremberg",4075 => "Köln",2343 => "Köln, Ehrenfeld",2774 => "Köln, Bergheimerhöfe",2828 => "Köln, Mitte",3881 => "Köln, Furth",359239 => "Frankfurt am Main, Innenstadt",359231 => "Frankfurt am Main",363777 => "Frankfurt, Frankfurt am Main Ost",373599 => "Frankfurt am Main, Frankfurt am Main Ost",373600 => "Frankfurt am Main, Frankfurt am Main Nord-Ost",8269 => "Stuttgart, Bad Cannstatt",5910 => "Stuttgart, Ost",6654 => "Stuttgart, West",8112 => "Stuttgart, Heumaden",9264 => "Stuttgart, Bergheim",9491 => "Stuttgart, Mönchfeld",9788 => "Stuttgart, Neckarhafen",11466 => "Stuttgart, Buchrain",665 => "Düsseldorf, Unterbilk",805 => "Düsseldorf, Friedrichstadt",806 => "Düsseldorf, Stadtmitte",2386 => "Düsseldorf, Mörsenbroich",3928 => "Düsseldorf, Lohausen",4438 => "Düsseldorf, Kaiserswerth",5800 => "Düsseldorf, Gerresheim",5974 => "Düsseldorf, Oberbilk",6754 => "Düsseldorf, Himmelgeist",7409 => "Düsseldorf, Flehe",773 => "Dortmund, Lindenhorst",917 => "Dortmund, Brünninghausen",1157 => "Dortmund, Westrich",1967 => "Dortmund, Hörde",2076 => "Dortmund, Kirchlinde",2334 => "Dortmund, Somborn",2637 => "Dortmund, Eving",2676 => "Dortmund, Wichlinghofen",2808 => "Dortmund, Asseln",3482 => "Dortmund, Rahm",125 => "Essen, Burgaltendorf",144 => "Essen",1563 => "Essen, Altendorf",1951 => "Essen, Bochold",2559 => "Essen, Dellwig",2830 => "Essen, Frillendorf",3182 => "Essen, Fischlaken",3982 => "Essen, Werden",4600 => "Essen, Nordviertel",6210 => "Essen, Süd",10193 => "Bremen",2973 => "Bremen, Oslebshausen",4165 => "Bremen, Kirchhuchting",7079 => "Bremen, Klöckner-Hütte",7199 => "Bremen, Tenever",9256 => "Bremen, Werderland",9681 => "Bremen, Fesenfeld",96891 => "Bremen, Radio Bremen",99 => "Leipzig, Lindenthal",175 => "Leipzig, Göbschelwitz",1174 => "Leipzig, Mölkau",1672 => "Leipzig, Lützschena",2457 => "Leipzig",2594 => "Leipzig, Dösen",3592 => "Leipzig, Knauthain",4707 => "Leipzig, Althen",5905 => "Leipzig, Althen Bahnhaus",170 => "Dresden, Kleinpestitz",390 => "Dresden, Innere Altstadt",551 => "Dresden, Johannstadt-Süd",558 => "Dresden, Südvorstadt-Ost",1198 => "Dresden, Seidnitz-Nord",1221 => "Dresden, Bühlau/Weißer Hirsch",1677 => "Dresden, Strehlen",109577 => "Dresden, Dresdener Heide",3487 => "Hannover",1490 => "Hannover, Klein Buchholz",1516 => "Hannover, Ricklingen",3353 => "Hannover, Vahrenwald",3951 => "Hannover, Zoo",4566 => "Hannover, Limmer-Nord",4584 => "Hannover, Linden-Süd",6366 => "Hannover, Bemerode",6507 => "Hannover, Anderten",234 => "Nürnberg, Flughafen",1909 => "Nürnberg, Kleinweidenmühle",2412 => "Nürnberg",2548 => "Nürnberg, Veilhof",2986 => "Nürnberg, Doos",3210 => "Nürnberg, Altenfurt",4249 => "Nürnberg, Marienberg",4985 => "Nürnberg, Oberbürg",74907 => "Nürnberg, Gewerbepark Nürnberg-Feucht",2881 => "Duisburg",2406 => "Duisburg, Hochheide",2893 => "Duisburg, Ruhrort",2923 => "Duisburg, Huckingen",3280 => "Duisburg, Hochemmerich",4909 => "Duisburg, Alt-Homberg",5139 => "Duisburg, Marxloh",359257 => "Duisburg, Duisburg Mitte",257 => "Bochum, Stiepel",2074 => "Bochum, Brenschede",3331 => "Bochum, Höhwege",5509 => "Bochum",4152 => "Bochum, Altenbochum",4615 => "Bochum, Kaltehardt",7051 => "Bochum, Höntrop",359117 => "Bochum, Bochum Mitte",496 => "Wuppertal, Lichtenplatz",568 => "Wuppertal, Stadt Remscheid",3781 => "Wuppertal",4205 => "Wuppertal, Windfoche",7911 => "Wuppertal, West",8294 => "Wuppertal, Laaken",11955 => "Wuppertal, Dornap",14030 => "Wuppertal, Barmen",13991 => "Wuppertal, Katernberg",1955 => "Bonn, Mitte",1962 => "Bonn, Poppelsdorf",2252 => "Bonn, Vilich",2372 => "Bonn, Gronau",3067 => "Bonn, Alt-Godesberg",3922 => "Bonn, Nord",4109 => "Bonn, Duisdorf",4383 => "Bonn, Röttgen",5962 => "Bonn, Annabergerhof",6749 => "Bonn, Auerberg",62 => "Bielefeld",408 => "Bielefeld, Wilhelmsdorf",468 => "Bielefeld, In der Heide",756 => "Bielefeld, Wächtershofsiedlung",961 => "Bielefeld, Am Reiherbach",1432 => "Bielefeld, Schildesche",1934 => "Bielefeld, Theeser Heide",2314 => "Bielefeld, Niederdornberg-Deppendorf",6715 => "Bielefeld, Senne I",276 => "Mannheim, Feudenheim",1148 => "Mannheim",1382 => "Mannheim, Friedrichsfeld",4589 => "Mannheim, Casterfeld",4937 => "Mannheim, Straßenheim",8079 => "Mannheim, Innenstadt",14352 => "Mannheim, Sandtorf",16222 => "Mannheim, Vogelstang",17042 => "Mannheim, Waldhof",2444 => "Karlsruhe, Grötzingen",4041 => "Karlsruhe, Dammerstock",5195 => "Karlsruhe, Beiertheim-Bulach",5593 => "Karlsruhe, Bulach",5706 => "Karlsruhe, Rintheim",6034 => "Karlsruhe, Innenstadt",7563 => "Karlsruhe, Kirchfeldsiedl",8218 => "Karlsruhe, Weiherfeld-Dammerstock",12344 => "Karlsruhe, Lamprechtshof",13355 => "Karlsruhe, Grünwinkel",13901 => "Münster, Zentrum-Nord",1850 => "Münster, Uppenberg",4208 => "Münster, Renfert",4497 => "Münster, Roxel",6647 => "Münster, Werse",8298 => "Münster, Amelsbüren",8399 => "Münster, Altenroxel",11304 => "Münster, Stadt Ascheberg",13102 => "Münster, Altstadt",1206 => "Wiesbaden, Nordenstadt",1581 => "Wiesbaden, Dotzheim",20199 => "Wiesbaden",12958 => "Wiesbaden, Riedhof",20740 => "Wiesbaden, Kohlheck",22706 => "Wiesbaden, Biebrich",23474 => "Wiesbaden, Mainz-Amöneburg",25312 => "Wiesbaden, Freudenberg",11795 => "Augsburg",6936 => "Augsburg, Lechhausen",11414 => "Augsburg, Innenstadt",13694 => "Augsburg, Hochfeld",14190 => "Augsburg, Radegundis",17912 => "Augsburg, Haunstetten",1286 => "Aachen, Krauthausen",14581 => "Aachen",7890 => "Aachen, Bildchen Gzg",8129 => "Aachen, Richterich",13168 => "Aachen, Haaren",14521 => "Aachen, Seffent",17032 => "Aachen, Locht",17134 => "Aachen, Lichtenbusch",1716 => "Mönchengladbach",2001 => "Mönchengladbach, Hoven",3127 => "Mönchengladbach, Westend",5610 => "Mönchengladbach, Rheydt-West",5830 => "Mönchengladbach, Kamphausener Höhe",6947 => "Mönchengladbach, Hehn",8022 => "Mönchengladbach, Geneicken",8171 => "Mönchengladbach, Stadtmitte",9292 => "Mönchengladbach, Odenkirchen",9426 => "Mönchengladbach, Woof",2320 => "Gelsenkirchen, Erle",9049 => "Gelsenkirchen, Hassel",28163 => "Gelsenkirchen, Heßler",11187 => "Gelsenkirchen, Scholven",13896 => "Gelsenkirchen, Eckeresse",27951 => "Gelsenkirchen",17341 => "Gelsenkirchen, Horst",1446 => "Braunschweig, Steinhof",2959 => "Braunschweig",3185 => "Braunschweig, Klein Schöppenstedt",3468 => "Braunschweig, Völkenrode",4480 => "Braunschweig, Wenden",5329 => "Braunschweig, Riddagshausen",6257 => "Braunschweig, Vorwerksiedlung",7123 => "Braunschweig, Siedlung am Schwarzen Berge",8709 => "Braunschweig, Thune",11972 => "Braunschweig, Friedrichshöhe",2361 => "Chemnitz, Altchemnitz",7244 => "Chemnitz",6162 => "Chemnitz, Hilbersdorf",3988 => "Chemnitz, Berbisdorf",4148 => "Chemnitz, Draisdorf",5301 => "Chemnitz, Altendorf",5624 => "Chemnitz, Kaßberg",129 => "Krefeld, Backeshof",414 => "Krefeld, Benrad-Nord",459 => "Krefeld, Orbroich",2805 => "Krefeld, Stock",2906 => "Krefeld, Dießem/Lehmheide",5984 => "Krefeld, Hülser Berg",3682 => "Krefeld, Steinrath",4499 => "Krefeld, Kempener Feld/Baakeshof",5935 => "Krefeld, Inrath",2711 => "Halle (Saale)",3009 => "Halle (Saale), Kanena-Bruckdorf",109803 => "Halle (Saale), Saaleaue",1575 => "Magdeburg, Leipziger Str",2404 => "Magdeburg, Industriehafen",8242 => "Magdeburg",4751 => "Magdeburg, Ottersleben",5543 => "Magdeburg, Puppendorf",8168 => "Magdeburg, Stadtfeld West",9356 => "Magdeburg, Beyendorfer Grund",12902 => "Magdeburg, Fermersleben",361251 => "Freiburg im Breisgau",361911 => "Freiburg im Breisgau, Süd",374407 => "Freiburg im Breisgau, Hochdorf",380778 => "Freiburg im Breisgau, Herdern",380981 => "Freiburg im Breisgau, Wiehre",381416 => "Freiburg im Breisgau, Nord",392900 => "Freiburg im Breisgau, Stühlinger",392910 => "Freiburg im Breisgau, Zähringen",1715 => "Oberhausen, Altstadt-Mitte",1840 => "Oberhausen, Osterfeld-West",8308 => "Oberhausen",4352 => "Oberhausen, Alstaden-West",5699 => "Oberhausen, Eisenheim",6468 => "Oberhausen, Waldhuck",7750 => "Oberhausen, Alstaden-Ost",11294 => "Oberhausen, Schmachtendorf",271 => "Lübeck, Dritte Fischerbuden",1652 => "Lübeck, Eichholz",2639 => "Lübeck, Steinraderhof",7274 => "Lübeck",3664 => "Lübeck, Steinrade",4473 => "Lübeck, Brandenmühle",4657 => "Lübeck, Reecke",4739 => "Lübeck, Roter Löwe",6622 => "Lübeck, Moorgarten",31 => "Erfurt, Brühlervorstadt",3980 => "Erfurt, Roter Berg",13930 => "Erfurt",7434 => "Erfurt, Büßleben",10433 => "Erfurt, Daberstedt",13970 => "Erfurt, Ilversgehofen",51198 => "Erfurt, Erfurt-Altstadt",1622 => "Rostock, Markgrafenheide",2658 => "Rostock",2822 => "Rostock, Groß Klein",4828 => "Rostock, Bramow",7159 => "Rostock, Evershagen Dorf",7401 => "Rostock, Stadtmitte",7576 => "Rostock, Toitenwinkel",8523 => "Rostock, Lütten Klein",9575 => "Rostock, Evershagen",11173 => "Rostock, Hinrichsdorf",1642 => "Mainz, Drais",4609 => "Mainz, Bretzenheim",8029 => "Mainz, Hartenberg",9939 => "Mainz",18751 => "Mainz, Layenhof",20184 => "Mainz, Hartenberg/Münchfeld",23338 => "Mainz, Weisenau",23576 => "Mainz, Zahlbach",8361 => "Kassel, Oberzwehren",2171 => "Kassel, Süd",4151 => "Kassel, Niederzwehren",4850 => "Kassel, Fasanenhof",5902 => "Kassel",5933 => "Kassel, Philippinenhof/Warteberg",6635 => "Kassel, Gemeinde Lohfelden",9850 => "Kassel, Mitte",565 => "Hagen, Westerbauer",853 => "Hagen, Mittelstadt",1440 => "Hagen",1617 => "Hagen (Teutoburger Wald), Natrup-Hagen",2797 => "Hagen, Vorhalle",2942 => "Hagenow, Hagenower Heide",4778 => "Hagen, Kuhlerkamp",5206 => "Hagen, Eilpe",5646 => "Hagen, Oege",6961 => "Hagen, Quambusch",1570 => "Hamm, Hamm-Osten",2045 => "Hamm, Mitte",104851 => "Hamm, Hamm-Westen",108798 => "Hamm, Hamm-Mitte",77021 => "Hamm, Hamm-Norden",9171 => "Saarbrücken",3631 => "Saarbrücken, Güdingen Gzg",5072 => "Saarbrücken, Scheidterberg",6374 => "Saarbrücken, Malstatt",7027 => "Saarbrücken, Burbach",46132 => "Saarbrücken, Alt-Saarbrücken",605 => "Bad Füssing, Angering",673 => "Annweiler (Trifels), Bindersbach",760 => "Breitenbrunn (Erzgebirge), Antonshöhe",1474 => "Ansbach, Claffheim",1586 => "Garmisch-Partenkirchen, Anzlesau",1822 => "Galmsbüll, Anna-Sophienhof",2040 => "Oberstdorf, Anatswald",2115 => "Krummhörn, Angernheim",2176 => "Wettringen, Andorf",2363 => "Anzing, Ried",368305 => "Ludwigshafen am Rhein",633 => "Osnabrück, Fledder",6412 => "Osnabrück, Gartlage",7389 => "Osnabrück, Martinitor",11252 => "Osnabrück, Hickingen",13412 => "Osnabrück, Kalkhügel",13512 => "Osnabrück",21518 => "Osnabrück, Schinkel",22433 => "Osnabrück, Burg Gretesch",22596 => "Osnabrück, Innenstadt",23766 => "Osnabrück, Wüste",2933 => "Herne, Röhlinghausen",6095 => "Herne",99499 => "Herne, Herne-Mitte",8353 => "Herne, Crange",97093 => "Herne, Herne-Süd",4579 => "Oldenburg",1285 => "Oldenburg, Nord-Moslesfehn",1348 => "Hude (Oldenburg), Oberhausen",2414 => "Hude (Oldenburg), Wüsting",4197 => "Oldenburg, Bloherfelde",4767 => "Oldenburg, Kortendorf",5153 => "Hude (Oldenburg), Altmoorhausen",6064 => "Hude (Oldenburg), Vielstedt",6888 => "Oldenburg, Donnerschwee",8176 => "Leverkusen, Voigtslach",9326 => "Leverkusen",22484 => "Leverkusen, Opladen",29584 => "Leverkusen, Innenstadt",34172 => "Leverkusen, Pattscheid",40377 => "Leverkusen, Uppersberg",45914 => "Leverkusen, Steinbüchel",46694 => "Leverkusen, Quettingen",1364 => "Solingen, Strohnerhön",1587 => "Solingen",2582 => "Solingen, Gönrath",83300 => "Solingen, Solingen-Mitte",7559 => "Solingen, Schaberg",549 => "Potsdam, Nedlitz",827 => "Potsdam, Drewitz",4316 => "Potsdam",4936 => "Potsdam, Eiche",6049 => "Potsdam, Jäger Vorstadt",8369 => "Potsdam, Zentrum Ost",9191 => "Potsdam, Schlaatz",10993 => "Potsdam, Nauener Vorstadt",56168 => "Potsdam, Potsdam West",8225 => "Neuss",9131 => "Neuss, Bettikum",10451 => "Neuss, Minkel",14600 => "Neuss, Gnadental",16521 => "Neuss, Erfttal",17336 => "Neuss, Reuschenberg",18265 => "Neuss, Neuenbaum",19821 => "Neuss, Dreikönigenviertel",20106 => "Neuss, Furth-Mitte",24306 => "Neuss, Elvekum",5912 => "Heidelberg, Auf dem Heiligenberg",7300 => "Heidelberg, Grenzhof",14275 => "Heidelberg, Emmertsgrund",21485 => "Heidelberg, Paffengrund",23953 => "Heidelberg, Wieblingen",26564 => "Heidelberg, Bierhelderhof",28083 => "Heidelberg, Patrick-Henry-Siedlung",32049 => "Heidelberg",33652 => "Heidelberg, Kohlhof",360620 => "Heidelberg - GP, Heidelberg",614 => "Darmstadt, Heimstätten Siedlung",690 => "Darmstadt, Innenstadt",3550 => "Darmstadt, Jefferson Siedlung",3658 => "Darmstadt, Eberstadt",7332 => "Darmstadt, Waldkolonie",11832 => "Darmstadt, Stadt Pfungstadt",13794 => "Darmstadt",22248 => "Darmstadt, St Barbara-Siedlung",22744 => "Darmstadt, Lincoln Siedlung",24453 => "Darmstadt, Wixhausen",1722 => "Paderborn, Buchholz",2078 => "Paderborn",3744 => "Paderborn, Heng",7488 => "Paderborn, Kernstadt",26007 => "Paderborn, Sennelager",12532 => "Paderborn, Wewer",20098 => "Paderborn, Senne",28461 => "Paderborn, Ringelsbruch",168 => "Regensburg, Ostenviertel",3282 => "Regensburg, Leoprechting",6126 => "Regensburg, Graß-Oberisling",8891 => "Regensburg, Pfaffenstein",10778 => "Regensburg, Kumpfmühl",11209 => "Regensburg, Kumpfmühl-Ziegetsdorf",11426 => "Regensburg",3399 => "Würzburg, Rottenbauer",3479 => "Würzburg, Lindleinsmühle",10289 => "Hausen (Würzburg), Jobsthalerhof",12041 => "Würzburg, Altstadt",12357 => "Hausen (Würzburg), Erbshausen",17592 => "Würzburg, Oberdürrbach",19235 => "Würzburg, Sanderau",3001 => "Ingolstadt",3489 => "Ingolstadt, Pettenhofen",9075 => "Ingolstadt, Herrenschwaige",17417 => "Ingolstadt, Mailing",18494 => "Ingolstadt, Einbogen",18709 => "Ingolstadt, Samholz",19670 => "Ingolstadt, Unsernherrn",20132 => "Ingolstadt, Seehof",20152 => "Ingolstadt, Winden",21044 => "Ingolstadt, Oberbrunnenreuth",11603 => "Heilbronn, Sontheim",11826 => "Heilbronn, Klingenberg",20895 => "Heilbronn, Biberach",26778 => "Heilbronn, Neckargartach",38077 => "Heilbronn",34378 => "Heilbronn, Kirchhausen",37953 => "Heilbronn, Frankenbach",1199 => "Ulm, Oberer Eselsberg",3614 => "Neu Ulm",5648 => "Ulm, Oberhaslach",7541 => "Neu Ulm, Offenhausen",8271 => "Neu Ulm, Lindenhof",8354 => "Ulm, Weststadt",9185 => "Neu Ulm, Stadtmitte",9322 => "Ulm, Fischbach",10450 => "Ulm, Lehr",11957 => "Neu Ulm, Schwaighofen",84556 => "Offenbach (Main), Offenbach am Main",363334 => "Mühlheim am Main",371883 => "Offenbach am Main",2736 => "Wolfsburg, Allerpark",6943 => "Wolfsburg, Barnstorf",7136 => "Wolfsburg, Hehlingen",9260 => "Wolfsburg, Kästorf",13245 => "Wolfsburg, Steimkerberg",14964 => "Wolfsburg, Schwinkermühle",16445 => "Wolfsburg, Klieversberg",28059 => "Wolfsburg, Wolfsburg Innenstadt",77986 => "Wolfsburg, Alt Wolfsburg",32279 => "Göttingen, Grone",1547 => "Göttingen, Elliehausen",5269 => "Göttingen, Esebeck",6912 => "Göttingen, Mittelberg",11529 => "Göttingen, Reinshof",12208 => "Göttingen, Geismar",15131 => "Göttingen, Hagenberg",17047 => "Göttingen, Herberhausen",17541 => "Göttingen, Weende",503 => "Pforzheim, Sonnenberg",3653 => "Pforzheim, Nordweststadt",10312 => "Pforzheim, Eutingen",7938 => "Pforzheim, Südweststadt",8248 => "Pforzheim, Innenstadt",8565 => "Pforzheim, Gemeinde Neulingen",9090 => "Pforzheim, Weststadt",10454 => "Pforzheim, Wilferdinger Höhe",11330 => "Pforzheim",46633 => "Recklinghausen, Hillerheide",37064 => "Recklinghausen, Stadtmitte",13902 => "Recklinghausen, Süd",14325 => "Recklinghausen, Speckhorn",15678 => "Recklinghausen, Hochlarmark",24065 => "Recklinghausen, Berghausen",34075 => "Recklinghausen",43406 => "Recklinghausen, Börste",543 => "Bottrop, Grafenwald",1147 => "Bottrop, Fuhlenbrock",1469 => "Bottrop, Boy",4541 => "Bottrop",5966 => "Bottrop, Süd",7848 => "Bottrop, Im Loh",14458 => "Bottrop, Welheim",14656 => "Bottrop, Welheimer Mark",14823 => "Bottrop, Süd-West-Innenstadt",16166 => "Bottrop, Innenstadt",1263 => "Fürth, Unterfarrnbach",5612 => "Fürth, Herboldshof",7385 => "Fürth, Bislohe",12044 => "Fürth",9233 => "Fürth, Weststadt",9750 => "Fürth, Flexdorf",14205 => "Fürth, Ronhof",24291 => "Fürth, Innenstadt",25597 => "Fürth, Stadeln",22651 => "Bremerhaven",4961 => "Bremerhaven, Mitte",19382 => "Bremerhaven, Geestemünde",7725 => "Bremerhaven, Weddewarden",28829 => "Bremerhaven, Lehe",35672 => "Bremerhaven, Schiffdorfer Damm",11713 => "Reutlingen, Weststadt",2629 => "Reutlingen, Kaibach",2840 => "Reutlingen, Betzenried",4560 => "Reutlingen, Römerschanze",5441 => "Reutlingen, Betzingen",7348 => "Reutlingen, Ohmenhausen",7423 => "Reutlingen, Südstadt",10438 => "Reutlingen, Nordstadt",12395 => "Reutlingen",28534 => "Remscheid",1067 => "Remscheid, Lennep",5996 => "Remscheid, Halbach",9154 => "Remscheid, Nord",14969 => "Remscheid, Großhülsberg",15500 => "Remscheid, Westen",17095 => "Remscheid, Oelingrath",1448 => "Koblenz, Horchheim",1873 => "Koblenz, rechtsrheinisch",5015 => "Koblenz, Pfaffendorf",6145 => "Koblenz, Rauental",6470 => "Koblenz, Asterstein",8127 => "Koblenz, Mühlenbacherhof",8381 => "Koblenz, Mailust",11969 => "Koblenz, Königsbach",13159 => "Koblenz, Niederberg",13597 => "Koblenz, Vorstadt",2896 => "Erlangen, In der Reuth",3510 => "Erlangen, Innenstadt",5047 => "Erlangen",61230 => "Erlangen, Erlangen-Süd",9178 => "Erlangen, Sieglitzhof",12552 => "Erlangen, Dechsendorf",40807 => "Erlangen, Erlangen-Ost",3248 => "Bergisch Gladbach, Katterbach",5214 => "Bergisch Gladbach, Hand",6418 => "Bergisch Gladbach, Strassen",7360 => "Bergisch Gladbach, Kaule",8158 => "Bergisch Gladbach, Herrenstrunden",8159 => "Bergisch Gladbach, Gronauerwald",57896 => "Bergisch Gladbach, Gladbach Innenstadt",99698 => "Bergisch Gladbach, Gladbach",6501 => "Trier, Trier-Ost",75455 => "Trier, Trier-Nord",25269 => "Trier, Trier-West",38006 => "Trier, Trier-Süd",79359 => "Trier, Trier-West-Pallien",866 => "Jena, Burgau",3041 => "Jena, Neue Schenke",3092 => "Jena, Vierzehnheiligen",3761 => "Jena, Lützeroda",4690 => "Jena, Lobeda",6762 => "Jena, Kunitz",7072 => "Jena, Lichtenhain",9274 => "Jena, Stadt",10584 => "Jena, Nelkenweg",63635 => "Jena, Jenaprießnitz",2164 => "Moers, Asberg",2761 => "Moers, Innenstadt",2878 => "Moers, Boschmannshof",3526 => "Moers, Schwafheim",3724 => "Moers, Eick",3767 => "Moers, Genend",4753 => "Moers, Niephauserfeld",8052 => "Moers",9439 => "Frankenthal (Pfalz), Moersch",106590 => "Moers, Moers-Mitte",8812 => "Siegen, Volnsberg",12341 => "Siegen, Bürbach",14982 => "Siegen, Eiserfeld",16572 => "Siegen, Trupbach",19495 => "Siegen, Gosenbach",37180 => "Siegen, Weidenau",38420 => "Siegen, Obersetzen",39105 => "Siegen, Breitenbach",46322 => "Siegen, Niedersetzen",2225 => "Hildesheim",4449 => "Hildesheim, Uppen",6368 => "Hildesheim, Bavenstedt",13433 => "Hildesheim, Süd",14942 => "Hildesheim, Moritzberg",32784 => "Hildesheim, Hildesheimer Wald",13160 => "Cottbus",3451 => "Cottbus, Skadow",8034 => "Cottbus, Stadtmitte",11985 => "Cottbus, Lakoma",22200 => "Cottbus, Sachsendorf",14546 => "Cottbus, Sandow",12701 => "Salzgitter, Süd",14156 => "Salzgitter, Reppner",14391 => "Salzgitter, Thiede",15015 => "Salzgitter, Gitter",17328 => "Salzgitter, Lebenstedt",18075 => "Salzgitter, Salder",18105 => "Salzgitter, Westerkamp",21121 => "Salzgitter, Osterlinde",25927 => "Salzgitter, Hallendorf",796 => "Dessau-Roßlau, Kleinkühnau",1687 => "Dessau-Roßlau, Naundorf",2272 => "Dessau-Roßlau, Törten",2958 => "Dessau-Roßlau, Schaltwarte",4439 => "Dessau-Roßlau, Waldersee",9349 => "Dessau-Roßlau, Siedlung",9705 => "Dessau-Roßlau",10199 => "Dessau-Roßlau, Zoberberg",18848 => "Dessau-Roßlau, Buchholzmühle Genesungsheim",19064 => "Dessau-Roßlau, West",717 => "Gera, Dorna",1329 => "Gera, Pforten",2938 => "Gera, Cretzschwitz",5676 => "Gera, Alt Taubenpreskeln",5701 => "Gera, Lietzsch",6442 => "Gera, Gorlitzsch",8206 => "Gera, Neu-Taubenpreskeln",8644 => "Gera, Seligenstädt",9355 => "Gera, Südhang/Zschippern",9492 => "Gera, Untermhaus",2752 => "Görlitz, Deutsch Ossig",5307 => "Görlitz, Kunnerwitz",8944 => "Görlitz, Hagenwerder",10243 => "Görlitz, Ludwigsdorf",27891 => "Görlitz, Klein Neundorf",22772 => "Görlitz, Zentrum",26969 => "Görlitz, Rauschwalde",26703 => "Görlitz, Ober-Neundorf",7687 => "Kaiserslautern, Vogelweh",9568 => "Kaiserslautern, Espensteig",10548 => "Kaiserslautern, Wiesenthalerhof",12142 => "Kaiserslautern, Innenstadt",14194 => "Kaiserslautern, Gelterswoog",15952 => "Kaiserslautern, Morlautern",16307 => "Kaiserslautern, Ruhetal",17627 => "Kaiserslautern, Stiftswalder Forsthaus",27594 => "Kaiserslautern, Stockborn",1528 => "Plauen, Alt-Chrieschwitz",1974 => "Plauen, Oberlosa",2939 => "Plauen",8222 => "Plauen, Waldesruh",11909 => "Plauen, Süd-Ost",13539 => "Plauen, Südvorstadt",15776 => "Plauen, Chrieschwitz",16005 => "Plauen, Straßberg",16734 => "Plauen, Hammervorstadt",16835 => "Plauen, Tannenhof",136 => "Schwerin, Lankow",13306 => "Schwerin, Weststadt",12900 => "Schwerin",8777 => "Schwerin, Großer Dreesch",10343 => "Schwerin, Ostorf",10784 => "Schwerin, Wickendorf",14453 => "Schwerin, Paulsstadt",3939 => "Wittenberg (Lutherstadt), Schmilkendorf",5570 => "Wittenberg (Lutherstadt), Apollensdorf",9371 => "Wittenberg (Lutherstadt), Elstervorstadt",9933 => "Wittenberg (Lutherstadt), Lerchenbergsiedlung",10927 => "Wittenberg (Lutherstadt), Wittenberg",21810 => "Wittenberg (Lutherstadt), Birkenbusch",26947 => "Wittenberg (Lutherstadt), Kienberge",30179 => "Wittenberg (Lutherstadt), Kleinwittenberg",36530 => "Wittenberg (Lutherstadt), Probstei",78287 => "Wittenberg (Lutherstadt), Wittenberg-West",8435 => "Zwickau",4605 => "Zwickau, Schneppendorf",7433 => "Zwickau, Crossen",7857 => "Zwickau, Schedewitz",10915 => "Zwickau, Bockwa",11046 => "Zwickau, Cainsdorf",14659 => "Zwickau, Oberplanitz",17351 => "Zwickau, Marienthal"
		);

		if($this->command['version']==1)
		{
			$this->preMessage = array(	"Hallo du bist süsss, gefällst mir, bin aber leider nicht mehr hier aber bei",
										"na du Nettes profil, wenn du magst, is nicht mein login, komm doch zu",
										"hallo, find dich süss, bin aber jetzt offline und nur noch bei",
										"hallo bist du auch single? Ich auch, lust auf ein cafe?  Is der login meiner siss, komm doch zu",
										"hey habe dich gefunden, Find dich nett, komm dich in",
										"hallo nettes profil, habe lust dich kennen zu lernen, aber nicht hier, bin bei",
										"bist du ein netter? Hast du lust zu chatten?, dies is aber nicht mein account, du findest mich bei",
										"ups na was gibt’s? auch einsam? Ok ich auch, was machen wir denn schönes? Bin leider nichtmehr hier, komm nach",
										"nicht mehr online warum? Ok wenn du magst können wir ja chatten und uns beschnuppern vielleicht n cafe am we? Komm zu",
										"kommst aus meiner nähe was?  (-; würde dich gerne kennen lernen, der account is aber von meiner siss, such doch nach",
										"was bist du denn für einer? Find dich nett, auch solo? Mal chatten? Ok bin nun aber offline komm doch zu",
										"oh gerade noch ne mail von dir bevor ich auslogge, komme hier nicht mehr zu viele anschreiben, hätte aber lust dich kennen lzu lernen, bin bei",
										"mir geht’s gut und dir? Was gibbet denn? Bist süss, lust auf n cafe? Am we? Bin nun aber weg hier zuviele anschreiben, bin aber auf",
										"nett nett, stimmt oder? Lust zu chatten? Aber nicht hier ok? Viele spinner, komm zu mir wenn du magst auf",
										"wohnst um die ecke, gerne möchte ich mit dir chatten, aber nicht hier, zu viel los, habe bilder von mir auf",
										"hey stehst du auch auf blind dates? Ich schon wenn du magst habe bilder von mir, auch erotische in",
										"stehe auf aubenteuer du auch? Kommst ja aus meiner nähe, ah is nicht mein logion dieser aber bin bei",
										"bin ein wilde, abenteuer mag ich du auch? Blind dates? Am we vielleicht, bin ich zu direkt? Ok schau meine bilder an und entscheide selber ok? Hier bei",
										"dachte ich schreib dir mal, bin schon lange solo, bin grade in deine ecke gezogen vielleicht zeigst du mir hier wo man was machen kann? Bin hier aber weg, zuviele zuschrifeten komm doch zu",
										"ja wer bist du? So ein zufall kommst wohnst ja gleich um die ecke, magst du mir zeigen wo man hier was machen kann? Am we? Bin hier aber jetzt nicht mehr, komm zu",
										"was suchst du denn? Ich ers mal chatten abenteuer mal schauen, aber nicht hier, zuviele spinner, du bist süss, komm doch zu mir zu",
										"blind date? Abenteuer? Wenn du magst ich stehe auf sowas, habe bilder online, is hier leider verboten aber schau in mein album bei",
										"stehst du auch auf bilder tausch? Leider darf man das hier nicht, wenn du welche sehen willst von mir geh zu",
										"nettes profil, hast du noch mehr bilder? Hier kann ich meine nicht hochladen, verbotem, deswegen komme ich auch nicht wieder, komm zu",
										"schüchtern bin ich nicht eher in abenteuer laune, wohnst ja nicht weit weg, wenn du lust hast…. Ok schau dir ers mal meine bilder an und entscheide ok? Sind bei",
										"hey single oder schon vergeben?was machst du denn so? ach vergessen, meine schwester lies mich kurz online, wenn du chatten magst bin bei",
										"hi wmag dein profil, lust zu ….sorry bin nicht mehr hier, zuviel los, aber finde dich nett, komm zu",
										"hui soviele zuschriften, ich muss gehen, is doof hier, aber dich finde ich nett, vielleicht klapts ja und is ja nicht weit weg zu dir ;-) bin bei",
										"warum soviele nachrichten das ist zu viel, schreib nur dir zurück, bist nett, aber wie gesagt bin hier weg, würde mich aber freuen wenn du zu mir kommst auf",
										"danke bist ja ein netter ;-) aber für freuen nicht das richtige hier, bin wieder weg, aber dich find ich gut, würde mich freuen wenn du mir schreibst auf",
										"olla la über 40 nachrichten, das ist ja belästigung, aber du gefällst mir, lust auf ein beschnuppern? Bin hier ers mal weg, aber komm zu",
										"hallo bin neu hier aber auch wieder weg, viele idioten, aber dich will ich kennen lernen, komm zu",
										"man oh man 43 nachrichten in 1h das ist zuviel, bin weg hier, aber du bist anders oder? Also ich hätte schon lust du bist ja auch fast nachbar ;-) würde mich freuen wenn du zu mir kommst zu",
										"hallo wo kommst du her? Ach um die ecke, ok lust zu chatten mal n cafe? Aber nicht hier ok zu viele leute und nicht angenhem für faruen. Bin auf",
										"hi, wie geht’s dir? Schon was gefunden? Bin gerade 1h hier und muss leider kündigen, echt krass, aber dich mag ich irgendwie, wenn du magst bin auf",
										"na endlich mal jemand nettes, ist echt viel hier vier mich, ich gehe hier auch wieder, wenn du magst bin auf",
										"netter typ gefällst mir aber bitte nicht hier ok? Komm auf",
										"du bist ja ein süsser, wenn du willst ass chatten bei",
										"lust auf geile bilder? Schau in mein albun in",
										"hey kommzu",
										"hi du luist auf  kennen lernen ja ers mal beschnuppern ok aber im ruhigen kreise ok? Und nur bei",
										"menn bist du süss ;-) las mal chatten aber bei",
										"hi, wasmachst du denn so? lange schon alleine? Ich bin gerade hier her gezogen kenne noch niemanden, hier is aber zu heavy bin bei",
										"hey du, und wie geht es dir? Bin gerde neu hier in der stadt magst mir was zeigen? Hier bitte nicht, zu viele spinnde, nur bei",
										"ja wunderbar, nettes profil, was suchst du genau? Wenn du magst cafe trinken gehen? Ok findest mich immer bei",
										"wohnst ja hier, du sag mal  las mal cafe trinken am we ja? Wenn du mich suchst bin immer bei",
										"nicht immer so stürmich aber egal was machst du am we? Zeigst mir die stadt? Bin neu hergezogen, du erreichst mich bei",
										"hi, bin neu hier in der stadt, zeigst mir was? Is leider das prifil meiner schwester, bin aber hier",
										"ok komm zu",
										"hast du lust auf ein qoicki am we in der stadt treffen beschnuppern und mal schauen? Habe bilder sind hier nicht erlaubt aber auf",
										"tutut der zug ist abgefahren für alle spinner hier aber nicht für dich bist nett, was machst am wochenende? Bilder von mir bei",
										"ok ok gerne kennen lernen chatten und sehen was passiert ok? Hier werde ich aber immer belästigt, bin nur noch bei",
										"halli hallo ja ich war auf deinem profil, kommst aus der umgebung, single? Dan lass treffen ja? Melde mich hier jetzt aber ab, geht gar nicht, freumich wenn du kommst zu",
										"moin moin was geht ab? Ja würde dich gerne näher kennen lernen aber nicht bei kwick, find ich doof, bin das letzte mal hier, bin bei",
										"na bischen direkt oder? Aber das mag ich, bist n netter glaube ich, bin bei",
										"hallo hallo, na warum schreibst du mir? Was willst duß das selbe wie ich oder? Ok bilder hier",
										"oh warum schreibst du mir? Is nicht mein prfil is von siss, wenn du mich kennen lernen willst bin bei",
										"guck guck, ja las mal chatten und vieleciht mal treffen am we ja? Melde mich bei kwick aber nun ab, weiss warum nicht lustig, würde mich aber freuen wenn du mich besuchst bei",
										"einen schönen guten tag, lust auf ein abenteuer? Wenn du mir gegen ein kleines taschengeld einen schönen abend bereitest bin ich für alles bereit, meine bilder sind hier nicht erlaubt, aber schau sie dir an bei"
										);
			$this->postMessage = array(	"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg",
										"suche nach PattyR ok?",
										"schau nach PattyR ja?",
										"gucke nach PattyR ok?",
										"guck nach PattyR bis denne",
										"gehe in die suche und schau nach PattyR ok?",
										"du findest mich in der suche PattyR ok?",
										"suche nach meinem namen PattyR lg",
										"freumich auf dich und such nach PattyR lg"
										);
		}
		else
		{
			$this->preMessage = array(
									"Danke für deine Nachricht süss, gefällst mir, bin aber leider nicht mehr hier aber bei",
									"was gibt es denn? Nettes profil, wenn du magst, is nicht mein login, komm doch zu",
									"hallo, find dich süss, bin aber jetzt offline und nur noch bei",
									"danke, nett bist du auch single? Ich auch, lust auf ein cafe?  Is der login meiner siss, komm doch zu",
									"hui wie kommst das du mir schreibst? Find dich nett, komm dich in",
									"klasse endlich schreibt mir jemand, habe lust dich kennen zu lernen, aber nicht hier, bin bei",
									"du bist ja ein netter, ja habe auch lust zu chatten, is aber nicht mein account, du findest mich bei",
									"ups na was gibt’s? auch einsam? Ok ich auch, was machen wir denn schönes? Bin leider nichtmehr hier, komm nach",
									"nicht mehr online warum? Ok wenn du magst können wir ja chatten und uns beschnuppern vielleicht n cafe am we? Komm zu",
									"holla kaum auf deim profil schon schreibst du mir (-; würde dich gerne kennen lernen, der account is aber von meiner siss, such doch nach",
									"was bist du denn für einer? Find dich nett, auch solo? Mal chatten? Ok bin nun aber offline komm doch zu",
									"oh gerade noch ne mail von dir bevor ich auslogge, komme hier nicht mehr zu viele anschreiben, hätte aber lust dich kennen lzu lernen, bin bei",
									"mir geht’s gut und dir? Was gibbet denn? Bist süss, lust auf n cafe? Am we? Bin nun aber weg hier zuviele anschreiben, bin aber auf",
									"danke, wie kommts? Lust zu chatten? Aber nicht hier ok? Viele spinner, komm zu mir wenn du magst auf",
									"gerne möchte ich mit dir chatten, aber nicht hier, zu viel los, habe bilder von mir auf",
									"hey stehst du auch auf blind dates? Ich schon wenn du magst habe bilder von mir, auch erotische in",
									"supi, stehe auf aubenteuer du auch? Kommst ja aus meiner nähe, ah is nicht mein logion dieser aber bin bei",
									"abenteuer mag ich du auch? Blind dates? Am we vielleicht, bin ich zu direkt? Ok schau meine bilder an und entscheide selber ok? Hier bei",
									"das ist lieb, danke, bin schon lange solo, bin grade in deine ecke gezogen vielleicht zeigst du mir hier wo man was machen kann? Bin hier aber weg, zuviele zuschrifeten komm doch zu",
									"ja wer bist du? So ein zufall kommst wohnst ja gleich um die ecke, magst du mir zeigen wo man hier was machen kann? Am we? Bin hier aber jetzt nicht mehr, komm zu",
									"lieb von dir, was suchst du denn? Ich ers mal chatten abenteuer mal schauen, aber nicht hier, zuviele spinner, du bist süss, komm doch zu mir zu",
									"blind date? Abenteuer? Wenn du magst ich stehe auf sowas, habe bilder online, is hier leider verboten aber schau in mein album bei",
									"stehst du auch auf bilder tausch? Leider darf man das hier nicht, wenn du welche sehen willst von mir geh zu",
									"klar mag ich dichkennen lernen, hast du noch mehr bilder? Hier kann ich meine nicht hochladen, verbotem, deswegen komme ich auch nicht wieder, komm zu",
									"schüchtern bin ich nicht eher in abenteuer laune, wohnst ja nicht weit weg, wenn du lust hast…. Ok schau dir ers mal meine bilder an und entscheide ok? Sind bei",
									"na du, is ja lieb das du mir schreibst was machst du denn so? ach vergessen, meine schwester lies mich kurz online, wenn du chatten magst bin bei",
									"sorry bin nicht mehr hier, zuviel los, aber finde dich nett, komm zu",
									"hui soviele zuschriften, ich muss gehen, is doof hier, aber dich finde ich nett, vielleicht klapts ja und is ja nicht weit weg zu dir ;-) bin bei",
									"warum soviele nachrichten das ist zu viel, schreib nur dir zurück, bist nett, aber wie gesagt bin hier weg, würde mich aber freuen wenn du zu mir kommst auf",
									"danke bist ja ein netter ;-) aber für freuen nicht das richtige hier, bin wieder weg, aber dich find ich gut, würde mich freuen wenn du mir schreibst auf",
									"olla la über 40 nachrichten, das ist ja belästigung, aber du gefällst mir, lust auf ein beschnuppern? Bin hier ers mal weg, aber komm zu",
									"danke, ich bin auch auf der suche, bin neu hier aber auch wieder weg, viele idioten, aber dich will ich kennen lernen, komm zu",
									"man oh man 43 nachrichten in 1h das ist zuviel, bin weg hier, aber du bist anders oder? Also ich hätte schon lust du bist ja auch fast nachbar ;-) würde mich freuen wenn du zu mir kommst zu",
									"auch hallo wo kommst du her? Ach um die ecke, ok lust zu chatten mal n cafe? Aber nicht hier ok zu viele leute und nicht angenhem für faruen. Bin auf",
									"auch hi, wie geht’s dir? Schon was gefunden? Bin gerade 1h hier und muss leider kündigen, echt krass, aber dich mag ich irgendwie, wenn du magst bin auf",
									"na endlich mal jemand nettes, ist echt viel hier vier mich, ich gehe hier auch wieder, wenn du magst bin auf",
									"netter typ gefällst mir aber bitte nicht hier ok? Komm auf",
									"du bist ja ein süsser, wenn du willst ass chatten bei",
									"lust auf geile bilder? Schau in mein albun in",
									"hey kommzu",
									"ok kennen lernen ja ers mal beschnuppern ok aber im ruhigen kreise ok? Und nur bei",
									"menn bist du süss ;-) las mal chatten aber bei",
									"hallo ja danke, und wie geht es dir? Bin gerde neu hier in der stadt magst mir was zeigen? Hier bitte nicht, zu viele spinnde, nur bei",
									"ja wunderbar, nettes profil, was suchst du genau? Wenn du magst cafe trinken gehen? Ok findest mich immer bei",
									"ok las mal cafe trinken am we ja? Wenn du mich suchst bin immer bei",
									"nicht immer so stürmich aber egal was machst du am we? Zeigst mir die stadt? Bin neu hergezogen, du erreichst mich bei",
									"hi, bin neu hier in der stadt, zeigst mir was? Is leider das prifil meiner schwester, bin aber hier",
									"ok komm zu",
									"hast du lust auf ein qoicki am we in der stadt treffen beschnuppern und mal schauen? Habe bilder sind hier nicht erlaubt aber auf",
									"tutut der zug ist abgefahren für alle spinner hier aber nicht für dich bist nett, was machst am wochenende? Bilder von mir bei",
									"ok ok gerne kennen lernen chatten und sehen was passiert ok? Hier werde ich aber immer belästigt, bin nur noch bei",
									"halli hallo ja ich war auf deinem profil, kommst aus der umgebung, single? Dan lass treffen ja? Melde mich hier jetzt aber ab, geht gar nicht, freumich wenn du kommst zu",
									"moin moin was geht ab? Ja würde dich gerne näher kennen lernen aber nicht bei kwick, find ich doof, bin das letzte mal hier, bin bei",
									"na bischen direkt oder? Aber das mag ich, bist n netter glaube ich, bin bei",
									"hallo hallo, na warum schreibst du mir? Was willst duß das selbe wie ich oder? Ok bilder hier",
									"oh warum schreibst du mir? Is nicht mein prfil is von siss, wenn du mich kennen lernen willst bin bei",
									"guck guck, ja las mal chatten und vieleciht mal treffen am we ja? Melde mich bei kwick aber nun ab, weiss warum nicht lustig, würde mich aber freuen wenn du mich besuchst bei",
									"einen schönen guten tag, lust auf ein abenteuer? Wenn du mir gegen ein kleines taschengeld einen schönen abend bereitest bin ich für alles bereit, meine bilder sind hier nicht erlaubt, aber schau sie dir an bei"
									);
			$this->postMessage = array(
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg",
									"suche nach PattyR ok?",
									"schau nach PattyR ja?",
									"gucke nach PattyR ok?",
									"guck nach PattyR bis denne",
									"gehe in die suche und schau nach PattyR ok?",
									"du findest mich in der suche PattyR ok?",
									"suche nach meinem namen PattyR lg",
									"freumich auf dich und such nach PattyR lg"
									);
		}


		$this->postMessageTemp = array();
		$this->preMessageTemp = array();
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$this->savelog("Adding Login Data");
			$cookiePath = $this->getCookiePath($user['username']);
			
			$login_arr = array(
				"login"		=> $user['username'],
				"password"		=> $user['password']		
			);
				
			array_push($this->loginArr, $login_arr);
			
		}
	}

	private function getPreMessage()
	{
		if(count($this->preMessageTemp)==0)
		{
			shuffle($this->preMessage);
		}
		elseif(count($this->preMessage)==0)
		{
			$this->preMessage = $this->preMessageTemp;
			shuffle($this->preMessage);
			$this->preMessageTemp = array();
		}

		$msg = array_pop($this->preMessage);
		array_push($this->preMessageTemp, $msg);
		return $msg;
	}

	private function getPostMessage()
	{
		if(count($this->postMessageTemp)==0)
		{
			shuffle($this->postMessage);
		}
		elseif(count($this->postMessage)==0)
		{
			$this->postMessage = $this->postMessageTemp;
			shuffle($this->postMessage);
			$this->postMessageTemp = array();
		}

		$msg = array_pop($this->postMessage);
		array_push($this->postMessageTemp, $msg);
		return $msg;
	}

	private function getLocationID($location, $cookiePath)
	{
		/*
		$content = $this->getHTTPContent($this->locationQueryURL.$location, $this->searchPageURL, $cookiePath);
		$content = json_decode($content);
		if(is_object($content))
			return $content->locid;
		else
			return false;
		*/
		return 7063;
	}

	private function getUserID($content)
	{
		$userid = substr($content, strpos($content, "var user_info =")+16);
		$userid = substr($userid, 0, strpos($userid, "}")+1);
		$userid = json_decode($userid);

		if(is_object($userid))
			return $userid->userid;
		else
			return false;
	}

	private function getAuthCode($content)
	{
		$content=substr($content,strpos($content,"authid=")+7);
		$content=substr($content,0,strpos($content,"&"));
		return $content;
	}
	
	public function getKeyPositionInArray($haystack, $keyNeedle)
	{
		$i = 0;
		foreach($haystack as $key => $value)
		{
			if($value == $keyNeedle)
			{
				return $i;
			}
			$i++;
		}
	}

	public function work()
	{
		$this->savelog("Job started, bot version ".$this->command['version']);
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		list($subject, $message)=$this->getMessage($this->newMessage);
		$this->newMessage = false;

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchPageURL, $this->loginRefererURL, $cookiePath);
		
		//http://www.loomin.de/search/advanced/?action=searchAction&mySexId=2&seekingSexId=1&seekingAgeFrom=18&seekingAgeTo=90&usePostalCode=false
		$search_arr = "?action=searchAction&mySexId=2&seekingSexId=1&seekingAgeFrom=18&seekingAgeTo=90&onlineOnly=true&usePostalCode=false";
		$content = $this->getHTTPContent($this->searchPageURL.$search_arr, $this->searchPageURL, $cookiePath);
		
		$content = substr($content,strpos($content,'withGallery=false" class="page"> &hellip;</a>&nbsp;<a href="?pageNum'));
		$content = substr($content,0,strpos($content,'</a> <a href="?pageNum=')+4);
		
		//?pageNum=1&action=searchAction&mySexId=2&onlineOnly=false&usePostalCode=false&postalCode=&seekingAgeFrom=18&seekingAgeTo=90&seekingSexId=1&srchN=679073704&withGallery=false
		
		if($html = str_get_html($content)){
			
			foreach($html->find('a') as $e)
			{
				$pages = trim($e->innertext);
				$flink = str_replace("?","",$e->href);
				$link = @explode("&",$flink);

				if($pages > 0){
					for($i=1;$i<=$pages;$i++){
						
						$this->savelog("Go to pages #".$i);
						
						$search_arr = "";
						if(count($link) > 0){
							for($ii=0;$ii<count($link);$ii++){
								if($search_arr!=""){$search_arr .= "&";}
								list($klink,$vlink) = @explode("=",$link[$ii]);

								if($klink == "pageNum"){
									$search_arr .= "pageNum=".$i;
								}else{
									$search_arr .= $klink."=".$vlink;
								}
							}
						}
						
						$content = $this->getHTTPContent($this->searchPageURL."?".$search_arr, $this->searchPageURL, $cookiePath);

						$content = substr($content,strpos($content,'<div class="MembersList ">'));
						$content = substr($content,0,strpos($content,'<div class="pager">'));
						
						$item = $this->getMembersFromSearchResult($username, $content);
						
						if(count($item) > 0){
							
							//Step 1
							for($j=0;$j<count($item);$j++){
								
								if($this->command['version']==1){									
									$this->savelog("Sending Message to: ".$item[$j]['username']);								
								}
								elseif($this->command['version']==2)
								{
									if($i == 1){
										$this->work_visitProfile($username, $item[$j]['username'], $item[$j]["links"], $cookiePath);
									}								
								}
								else
								{
									$this->savelog("Wrong version selected.");								
								}

							}

							//Step 2

						}
					}
				}
			}
		}		

		$this->savelog("Job completed.");
		return true;
	}
	
	private function getMembersFromSearchResult($username, $content){
		$list = array();
		$cookiePath = $this->getCookiePath($username);

		if($html = str_get_html($content)){
			
			foreach($html->find('a.photolink') as $e)
			{
				$content = $this->getHTTPContent($this->profileURL.$e->href."?", $this->searchPageURL, $cookiePath);

				$content = substr($content,strpos($content,'<h1>Profil von <strong>'));
				$content = substr($content,0,strpos($content,'</strong></h1> <div class="tabs">'));

				$name = str_replace("<h1>Profil von <strong>","",$content);
				
				$profiles = array(
					"links" => $e->href,
					"username" => $name
				);

				array_push($list,$profiles);
								
			}

		}

		return $list;
	}

	private function work_visitProfile($username, $name, $links, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$name);
		$content = $this->getHTTPContent($this->profileURL.$links."?", $this->searchPageURL, $cookiePath);
		$this->sleep(5);
		return $content;
	}

	public function getAction(){
		return $this->command['action'];
	}

	public function getSiteID(){
		return $this->siteID;
	}

	public function checkLogin($username, $password){
		$this->loginArr = array();
		$this->addLoginData(array(array("username"=>$username, "password"=>$password)));
		$this->currentUser=0;
		$cookiePath = $this->getCookiePath($username);

		if(!($this->isLoggedIn($username)))
		{
			$this->savelog("This profile: ".$username." does not log in.");
			// count try to login
			for($count_login=1; $count_login<=$this->loginRetry; $count_login++)
			{
				if($this->tor_new_identity($this->proxy_ip,$this->proxy_control_port,'bot'))
					$this->savelog("New Tor Identity request completed.");
				else
					$this->savelog("New Tor Identity request failed.");

				$this->savelog("Logging in.");
				$content = $this->getHTTPContent($this->loginURL, $this->loginRefererURL, $cookiePath, $this->loginArr[$this->currentUser]);	
				//file_put_contents("login/".$username."-".date("YmdHis").".html",$content);

				if(!($this->isLoggedIn($username)))
				{
					$this->savelog("Log in failed with profile: ".$username);
					$this->savelog("Log in failed $count_login times.");

					if($count_login>($this->loginRetry-1))
					{
						return false;
					}
					else
					{
						$sleep_time = 120; // 2 mins

						$this->savelog("Sleep after log in failed for ". $this->secondToTextTime($sleep_time));
						$this->sleep($sleep_time);
					}
				}
				else
				{
					$this->savelog("Logged in with profile: ".$username);
					return true;
				}
			}
		}
		else
		{
			$this->savelog("This profile: ".$username." has been logged in.");
			return true;
		}
	}

	private function utime(){
		$utime = preg_match("/^(.*?) (.*?)$/", microtime(), $match);
		$utime = $match[2] + $match[1];
		$utime *=  1000;
		return ceil($utime);
	}

	private function getMessage($new=true)
	{
		if($new)
		{
			//RANDOM SUBJECT AND MESSAGE
			$this->savelog("Random new subject and message");
			$this->currentSubject = rand(0,count($this->command['messages'])-1);
			$this->currentMessage = rand(0,count($this->command['messages'])-1);

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
			$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);

			$this->message=$this->getPreMessage()." ".$message." ".$this->getPostMessage();
			$this->subject=$subject;
		}
		return array($this->subject, $this->message);
	}

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = true;
		// If not already sent
		if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
		{
			if(isset($this->command['full_msg']) && ($this->command['full_msg']==1))
			{
				//RANDOM SUBJECT AND MESSAGE
				$this->savelog("Random new subject and message");
				$this->currentSubject = rand(0,count($this->command['messages'])-1);
				$this->currentMessage = rand(0,count($this->command['messages'])-1);

				//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
				$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
				$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);
			}
			else
			{
				list($subject, $message)=$this->getMessage($this->newMessage);
			}
			$this->savelog("Message is : ".$message);

			// Go to profile page
			$content = $this->work_visitProfile($username, $item, $cookiePath);

			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username']))
			{				
				if($this->command['msg_type']=="pm")
				{
					////////////////////////////////////////
					/////// Go to send message page ////////
					////////////////////////////////////////
					$this->savelog("Go to send message page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendMessagePageURL, $this->profileURL.$item['username'], $cookiePath);
					$this->sleep(5);					
					
					$message_arr = array(
						"action" => "send",
						"imageIds" => "[]",
						"linkIds" => "[]",
						"mailId" => "",
						"message" => $message,
						"read" => false,
						"recipientId" => $item['recipientId'],
						"userId" => ""
						);

					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$url = $this->sendMessageURL."?h=".$item['h'];
						$url_referer = $this->profileURL.$item['username'];
						$content = $this->getHTTPContent($url, $url_referer, $cookiePath, $message_arr);
						$url_log = "URL => ".$url."\nREFERER => ".$url_referer."\n";
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$url_log.$content);

						if(strpos($content, '"s":1')!==false)
						{
							$this->newMessage=true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();

							if(isset($item['message']))
								$this->deleteInboxMessage($username, $item['message'], $cookiePath);
							$return = true;
						}
						else
						{
							$this->newMessage=false;
							$this->savelog("Sending message failed.");
							$json = json_decode($content);
							if(isset($json->fail))
								$this->savelog("Fail: ".$json->fail);
							$this->lastSentTime = time();
							$return = true;
						}
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['username']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						$return = true;
					}
				}
				elseif($this->command['msg_type']=="gb")
				{
					//////////////////////////////////////////
					/////// Go to sign guestbook page ////////
					//////////////////////////////////////////
					$this->savelog("Go to sign guestbook page: ".$item['username']);
					$content = $this->getHTTPContent($this->profileURL.$item['username']."/guestbook",  $this->profileURL.$item['username'], $cookiePath);
					$this->sleep(5);
					$message_arr = array(
							"content" => $message,
							"entryVisibility" => $this->command['entryVisibility'], //1: Public, 2: Private
							"submit" => "Eintrag schreiben"
						);

					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Signing guestbook to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->profileURL.$item['username']."/guestbook/entry/add", $this->profileURL.$item['username']."/guestbook", $cookiePath, $message_arr);
						
						file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);
						
						$html = str_get_html($content);
						
						$entries_number = 0;
						$msg_sendout = 0;
						$pages = 1;
						foreach($html->find('div.numberOfEntries b') as $eEntries) {
							$entries_number = trim($eEntries->innertext);
						}
						
						if($entries_number > 5){
							$pages = ceil($entries_number/5);
						}
						
						$index = 0; $oldlinks = "";
						foreach($html->find('div.listOfActions') as $e)
						{
							$oldlinks = $e->find('a',0)->href;
							if(isset($links)){								
								$index++;
							}

							if($index > 0) break;
						}

						if($entries_number > 0){
							for($i=1;$i<=$pages;$i++){
								
								if($i > 1){
									$content = $this->getHTTPContent($this->profileURL.$item['username']."/guestbook?&start=".$i, $this->profileURL.$item['username']."/guestbook", $cookiePath);
									$html = str_get_html($content);
								}

								foreach($html->find('div#gbList') as $e) {
									foreach($e->find('div.listOfActions') as $eText) {	
										$links = $eText->find('a',0);

										if(trim($oldlinks) == trim($links->href)){
											$msg_sendout++;
											break 3;
										}
									}
								}
							}
						}
					
						if($msg_sendout > 0)
						{
							$this->newMessage=true;
							$this->savelog("Signing guestbook completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							
							if(isset($item['message'])){
								$this->deleteInboxMessage($username, $item['message'], $cookiePath);
							}

							$return = true;
						}
						else
						{
							$this->newMessage=false;
							$this->savelog("Signing guestbook failed.");
							$json = json_decode($content);
							if(isset($json->fail))
								$this->savelog("Fail: ".$json->fail);
							$this->lastSentTime = time();
							$return = true;
						}
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['username']);
						$this->savelog("Signing guestbook failed. This profile has been sent message to.");
						$return = true;
					}
				}
				$this->cancelReservedUser($item['username']);
				$this->sleep(2);
			}
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item['username']);
			$return = true;
		}
		return $return;
	}

	private function sendTestMessage($username, $cookiePath){
		$this->savelog("Sending test message.");
		$profiles = DBConnect::assoc_query_1D("SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".$this->siteID);
		if($this->command['gender']=="maennlich")
		{
			$receiverId			= $profiles['male_id'];
			$receiverUsername	= $profiles['male_user'];
		}
		else
		{
			$receiverId			= $profiles['female_id'];
			$receiverUsername	= $profiles['female_user'];
		}

		if(($receiverId!='') && ($receiverUsername!=''))
		{
			$item = array("username" => $receiverUsername,
							"userid" => $receiverId
							);

			$this->work_sendMessage($username, $item, $cookiePath, true);
		}
		else
		{
			$this->savelog("Get test profile failed.");
		}
	}

	private function isAlreadySent($username)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->databaseName."_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ".$this->databaseName."_reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO ".$this->databaseName."_reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
			DBConnect::execute_q($sql);			
			return true;
		}
		elseif($server==$this->botID)
		{
			$this->savelog("Already Reserved: ".$username);
			return true;
		}
		else
		{
			$this->savelog("Already Reserved by other bot: ".$username);
			return false;
		}
	}

	private function cancelReservedUser($username)
	{
		DBConnect::execute_q("DELETE FROM ".$this->databaseName."_reservation WHERE username='".$username."' AND server=".$this->botID);
	}

	private function deleteAllOutboxMessages($username, $cookiePath)
	{
		$list = array();
		$content = $this->getHTTPContent($this->inboxURL, $this->inboxURL, $cookiePath);
		
		if($html = str_get_html($content)){
			$pages = 1;
			
			foreach($html->find('div.numberOfEntries b') as $entries){
				$numberOfEntries = $entries->innertext;
				
				if($numberOfEntries > 15){
					$pages = ceil($numberOfEntries / 15);
				}
			}			
	
			$param = "";

			for($i=1; $i <= $pages;$i++){				
				
				$param = "?&start=".$i;
				$content2 = $this->getHTTPContent($this->inboxURL.$param, $this->inboxURL, $cookiePath);
				$html2 = str_get_html($content2);

				foreach($html2->find('div.listOfConversations') as $e)
				{	
					$message_count = 0;

					foreach($e->find('a') as $item)
					{			
						$profile = str_replace("/mailbox/conversation/","",$item->href);	
						$cConversation = $this->getHTTPContent($this->conversation."/".$profile, $this->inboxURL, $cookiePath);
						
						if($html3 = str_get_html($cConversation)){						
							
							foreach($html3->find('.entry') as $e2){								

								foreach($e2->find('.referenceEntry a') as $e3){
									$profile2 = str_replace("/user/","",$e3->href);
									if($username == $profile2){										
										$links = $e2->find('.listOfActions a',2);
										
										$url_delete_msg = $this->deleteUrl.$links->href;

										$deleteConversation = $this->getHTTPContent($url_delete_msg, $this->conversation."/".$profile, $cookiePath);
										$message = str_replace("/mailbox/delete/".$profile."/","",$links->href);
										list($msid,$lms) = @explode("?",$message);
										$this->savelog("Deleting message id: ".$msid." completed.");
									}
								}								
								
							}	
							
						}						
					}
					
				}				
				
			}			
		}

	}

	private function getOutboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving outbox messages.");
		$content = $this->getHTTPContent($this->outboxURL, $this->indexURL, $cookiePath);
		
		$content = substr($content, strpos($content, '<ul id="messages"'));
		$content = substr($content, 0, strpos($content, '</ul>')+5);
		$parser = $this->convertToXML($username, "outbox", $content);

		if(isset($parser->document->ul[0]) && isset($parser->document->ul[0]->li))
		{
			foreach($parser->document->ul[0]->li as $item)
			{
				$message = array(
					"message" => str_replace("checkbox_","",$item->input[0]->tagAttrs['id'])
				);
				array_push($list,$message);
			}
		}
		return $list;
	}

	private function deleteOutboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
							"ajax" => 1,
							"deletethread" => 1,
							"deletemsg" => 0,
							"threadid" => $message['message'],
							"msgid" => 0
			);

		$content = $this->getHTTPContent($this->deleteOutboxURL."?".http_build_query($delete_arr), $this->deleteOutboxRefererURL, $cookiePath);

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}

	private function getInboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving inbox messages.");
		$content = $this->getHTTPContent($this->inboxURL, $this->inboxURL, $cookiePath);
		
		if($html = str_get_html($content)){
			$pages = 1;
			
			foreach($html->find('div.numberOfEntries b') as $entries){
				$numberOfEntries = $entries->innertext;
				
				if($numberOfEntries > 15){
					$pages = ceil($numberOfEntries / 15);
				}
			}			
	
			$param = "";

			for($i=1; $i <= $pages;$i++){				
				
				$param = "?&start=".$i;
				$content2 = $this->getHTTPContent($this->inboxURL.$param, $this->inboxURL, $cookiePath);
				$html2 = str_get_html($content2);

				foreach($html2->find('div.listOfConversations') as $e)
				{	
					foreach($e->find('a') as $item)
					{			
						$profile = trim(str_replace("/mailbox/conversation/","",$item->href));
						$cConversation = $this->getHTTPContent($this->conversation."/".$profile, $this->inboxURL, $cookiePath);						
						
						if($html3 = str_get_html($cConversation)){						
							
							foreach($html3->find('.entry') as $e2){
								
								if($links = $e2->find('.listOfActions a',0)){
									
									$key_value = str_replace("/mailbox/conversation/","",$links->href);
									
									if($key_value != ""){
										$ms_array = @explode("/",$key_value);
										
										if(is_numeric($ms_array[1])){
											$list[$ms_array[1]]['nickname'] = $ms_array[0];
											$list[$ms_array[1]]['userid'] = $ms_array[1];
											$list[$ms_array[1]]['msid'] = $ms_array[2];											
										}
									}
								}
							}	
							
						}
						
					}
					
				}
			}
		}

		return $list;
	}

	private function deleteInboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
							"ajax" => 1,
							"deletethread" => 1,
							"deletemsg" => 0,
							"threadid" => $message['message'],
							"msgid" => 0
			);

		$content = $this->getHTTPContent($this->deleteInboxURL."?".http_build_query($delete_arr), $this->deleteInboxRefererURL, $cookiePath);

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}
}
?>