<!-- {$smarty.template} -->
<script language="javascript" type="text/javascript">
	var old_username="";
	var username_ok = false;
	var email_ok = false;
	var password_ok = false;

	{literal}
	jQuery(
	function()
	{
		jQuery('#agb_description').bind('scroll',
			function()
			{
				if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight)
				{
					jQuery('#agb_description_info').fadeOut('slow');
				}
			})
		}
	);
	{/literal}
</script>

<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
<div class="register-box-tr">
<label class="text">Nickname :</label>
<div class="line-box-register">
<input name="username" type="text" id="username" value="{$save.username}" maxlength="30" class="formfield_01" onkeyup="checkUsernameSilent(this.value)" autocomplete="off" />
<div id="username_info" class="left"></div>
</div>
</div>
<div class="register-box-tr">
<label class="text">Achtung :</label>
<div style="display:block; width:480px; margin-bottom:3px; float:left; font-weight:normal; font-size:10px; margin-top:5px; margin-left:10px; line-height:1.3em;">Derzeit können wir nicht sicher gehen, dass Du bei Gmail auch die Registrierungsmail erhältst. Bitte nutze nach Möglichkeit einen anderen Email Provider.</div>
</div>

<div class="register-box-tr">
<label class="text">{#Email#}:</label>
<div class="line-box-register">
<input id="email" name="email" type="text" value="{$save.email}" class="formfield_01" onblur="checkEmailSilent(this.value);" autocomplete="off"/>
<div id="email_info" class="left"></div>
</div>
</div>

<div class="register-box-tr">
<label class="text">{#PASSWORD#}:</label>
<div class="line-box-register">
<input id="password" name="password" type="password" maxlength="30" class="formfield_01" onblur="checkNullPassword(this.value);" autocomplete="off"/>
<div id="password_info" class="left"></div>
</div>
</div>

<div class="register-box-tr">
<label class="text">{#Birthday#}:</label>
<div class="line-box-register">
{html_options id="date" name="date" options=$date selected=$save.date class="date formfield_01"}
{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month class="month formfield_01"}
{html_options id="year" name="year" options=$year_range|default:1994 onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year class="year formfield_01"}
</div>
</div>

<div class="register-box-tr">
<label class="text">{#Gender#}:</label>
<div class="line-box-register">
	<div style="float:left; line-height:30px;">
	{html_radios id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('register_form',this,'')"}
    </div>
<div id="gender_info" class="left"></div>
</div>
</div>

<div class="register-box-tr">
<label class="text">{#Country#}:</label>
<div class="line-box-register">
<select id="country" name="country" class="formfield_01"  autocomplete='off' style="width:310px !important">
{foreach from=$country item=foo}
<option value="{$foo.id}">{$foo.name}</option>
{/foreach}
</select>
<div id="country_info" class="left"></div>
</div>
</div>
<br class="clear" />
<div id="agb_description_info" style="width:auto; height:auto; background-color: rgba(255, 0, 6, 0.7); margin-top:10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; text-align:center; padding:10px; font-size:14px; font-weight:bold !important; color:#fff;">
Bitte scrollen sie bis zum Ende der AGB`s, eine erfolgreiche <br />Registrierung ist ohne diese Aktion nicht möglich!
</div>
<br class="clear" />
<div id="agb_description" style="background-color: rgba(255, 255, 255, 0.2); width:auto; height:300px; margin-top:0; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; padding:10px; overflow:scroll; overflow-x:hidden; z-index:10;">
<p id="agb-register">
Willkommen bei www.flirt48.net. Jeder Zugang zu den Diensten & Leistungen dieser Internetpräsenz wird von diesen Allgemeinen Geschäftsbedingungen umfasst, die im Folgenden im Einzelnen aufgeführt sind.
<br /><br />
Der Zugang zu allen Diensten dieser Webseite ist nur möglich, wenn der Nutzer die Allgemeinen Geschäftsbedingungen akzeptiert. Gleiches gilt für die Datenschutzerklärung, Des Weiteren verwendet diese Seite so genannte "Cookies" und der Nutzer stimmt der Verwendung dieser ausdrücklich zu. Wenn Sie weder der Verwendung von Cookies noch den Allgemeinen Geschäftsbedingungen sowie unseren Datenschutzbestimmungen zustimmen möchten, dann brechen Sie bitte jeglichen Anmeldevorgang ab und verlassen Sie diese Internetpräsenz.
<br /><br />
Diese Internetpräsenz ist ein Unterhaltungsdienst. Das Angebot umfasst sowohl die Kommunikation über das Onlineportal selbst als auch über einen angebundenen SMS-Dienst. Diese Dienste sind registrierten Usern eingeschränkt zugänglich. Nutzer, die über die angeschlossenen Zahlmethoden individuelle Gegenwerte für Ihre Einzahlungen (so genannte "Coins") erhalten haben, können diese Coins widerum nutzen um erweiterte Leistungen & Funktionen in Anspruch zu nehmen. Diese Leistungen sind im Einzelnen auf den jeweiligen Seite in unmittelbarer Nähe zum jeweiligen Angebot benannt, ebenso wie der zu entrichtende Gegenwert in Coins.
<br /><br />
Diese Allgemeinen Geschäftsbedingungen betreffend somit insbesondere die Erstellung von eigenen Accounts durch den sich registrierenden Nutzer sowie den Zugang, die Nutzungsmöglichkeiten & angebotenen Dienste von www.flirt48.net für Besucher, registrierte Nutzer und jenen Nutzern, die für die einzelnen Dienstleistungen Zahlungen erbringen.
<br /><br />
Der Gerichtstand ist das Vereinigte Königreich Grossbritannien
<br /><br />
<strong>1 Definitionen</strong>
<br /><br />
1.1 "Account" umfasst den Zugang zum geschlossenen Nutzerbereich nach erfolgreich abgeschlossener Registrierung durch den Nutzer.
<br /><br />
1.2 "Kontakt, Flirt- und Chat-Dienstleistungen umfasst den Kontakt zu anderen Personen über die Messenger-Funktionen des Portals im geschlossenen Nutzerbereich sowie den Kontakt über SMS zu anderen Mitgliedern des Portals und virtuellen Personen. Dieser Service setzt zusätzlich zu den real existierenden Personen mit eigens hinterlegten Profilen so genannte "Agenten" ein, welche dem Nutzer als Gesprächspartner für den virtuellen Gedankenaustausch zur Verfügung stehen. Mit diesen Personen sind lediglich Rollenspiele möglich und es wird über einen reinen virtuellen Kontakt hinaus zu keinen "Realkontakten" kommen, Treffen mit diesen Agenten sind also ausgeschlossen. Diese Agenten sind gewerblich agierende Mitarbeiter oder Freiberufler eines oder auch diverser Unternehmen und werden für den virtuellen Gedankenaustausch von ihren Arbeitgebern/Geschäftspartnern für die erbrachten Chatleistungen (Rollenspiele) bezahlt. Um dem Endnutzer für diese Rollenspiele ein möglichst ansprechendes Umfeld zu bieten und eine möglichst angenehme virtuelle Erfahrung zu bieten, verwenden diese Agenten Nutzerprofile innerhalb der Internetpräsenz, die keine reale Person zum Vorbild haben, folglich somit auch nicht real sind.
<br /><br />
1.3 "Nutzer-Kontakt, Flirt- und Chat-Dienstleistungen umfasst nur den Kontakt zu anderen Personen über die Messenger-Funktionen des Portals im geschlossenen Nutzerbereich sowie den Kontakt über SMS zu anderen Mitgliedern des Portals.
<br /><br />
1.4 "Cookies" ist der verwendete und weithin geläufige Begriff für eigene Elemente dieser Internetpräsenz. Genaueres entnehmen Sie bitte unserer Datenschutzerklärung.
<br /><br />
1.5 "Firma", "www.flirt48.net", "wir" oder "unser" sowie "Seitenbetreiber" steht innerhalb dieser Allgemeinen Geschäftsbedingungen grundsätzlich für den verantwortlichen Seitenbetreiber. Dier hier verantwortliche Seitenbetreiber ist dem Impressum zu entnehmen.
<br /><br />
1.6 "Zustimmen" oder "Zustimmung" bedeutet, der Nutzer muss der Verwendung von Cookies auf dieser Internetpräsenz zustimmen, indem an der entsprechenden Stelle während der Registrierung der erforderliche Haken im angezeigten Feld (Checkbox) gesetzt wird. Diese Zustimmung ist Voraussetzung für die Nutzung der Dienste von www.flirt48.net und ist somit eine Pflichtangabe. Der Nutzer willigt an dieser Stelle ebenfalls ein, dass er die Datenschutzerklärung sowie diese Allgemeinen Geschäftsbedingungen in vollem Umfang anerkennt und ihm diese bekannt sind, er sie also auch vollständig zur Kenntnis genommen hat.
<br /><br />
1.7 "Inhalte" oder "Content" als Samelbegriff umnfasst alle Texte, Scripte, Informationen, Dokumente und Dokumentationen sowie Grafiken, digitalen Dateien, Fotografien, mobilen Inhalte, Töne, Musik, sonstige Audiodateien, Audio-visuelle Dateien, Videos und interaktive Elemente, welche auf der gesamten Internetpräsenz enthalten sind.
<br /><br />
1.8 "Rechte am geistigen Eigentum" oder "Urheberrechte" umfasst alle Copyrightbestimmungen und Rechte von gleicher oder ähnlicher Gestalt, unregistrierte und registrierte Markenschutzrechte, unregistrierte Designs wie registrierte Designs, Bildrechte, Rechte an der Unterhaltung und deren Inhalten und den Rechten an selbst erstelltem Tonmaterial. Dies schließt das Recht auf eine spätere Registrierung der einzelnen Positionen ausdrücklich mit ein.
<br /><br />
1.9 "Medien" umfasst als Begriff alle multimedialen Kanäle, Videos, Fotografien, Animationen, Töne und Musikdateien, welche auf dieser Internetpräsenz enthalten sind.
<br /><br />
1.10 "Member" umfasst alle Endnutzer, welche den Registrierungsprozess auf www.flirt48.net erfolgreich abgeschlossen haben und somit kostenfreien Zugang zum geschlossenen Nutzerbereich erhalten haben.
<br /><br />
1.11 "Beteiligte" bedeutet die Nutzer/Member und/oder VIP-Member
<br /><br />
1.12 "Payment" oder "Zahlung" bedeutet die Zahlung durch das Mitglied bzw. den Endnutzer in Übereinstimmung mit Ziffer 6 um ein VIP Mitglied zu werden und Coins zu erwerben um individuelle Leistungen sowie Dienste in Anspruch nehmen zu können.
<br /><br />
1.13 "Datenschutzerklärung" bedeutet & beinhaltet eben jene Datenschutzerklärung, die jedem Enduser jederzeit unter der folgenden URL zugänglich ist: [insert URL]
<br /><br />
1.14 "Profil" bedeutet alle Videos, fotografien, e-Books, Texte, Scripte, Informationen, mobile Inhalte, Töne, Musik, sonstige Audio-Dateien sowie miltimediale Inhalte und interaktive Funktionen, welche von den Mitglieder / Nutzern dieser Internetseite erstelllt oder bereitgestellt werden /worden sind.
<br /><br />
1.15 "Registrierung" oder "Registrieren" bedeutet die Registrierung des Nutzers als ein Mitglied mit Zugang zum geschlossenen Nutzerbereich dieser Webseite um individuelle Inhalte zu erstellen wie in Ziffer 4 beschrieben.
<br /><br />
1.16 "Dienste" bedeutet die Erleichterung des Kontaktes, der virtuellen Flirts, der Chatdienste während der Nutzung dieser Webseite oder der damit in Verbindung stehenden SMS-Dienste für die Anzahl an Coins in Zusammenhang mit den Coinkosten für die Nutzung der einzelnen Dienste als registrierter Nutzer dieser Internetseite wie unter Ziffer 7 beschrieben.
<br /><br />
1.17 "Coins" ist die auf dieser Seite erwerbbare Online-Währung zur Nutzung der einzelnen kostenpflichtigen Leistungen & Dienste.
<br /><br />
1.18 "Nutzer" umfasst als Begriff jede Person, die diese Seite durch Nutzung des Mediums Internet aufrufen bzw. betreten kann.
<br /><br />
1.19 "Member" oder "Mitglied" als Begriffsdefinition umfasst alle Personen, die Zugriff auf diese Internetseite oder die damit in Verbindung stehenden SMS-Dienste haben, gleich welches Gerät dafür genutzt wird.Der Zugang des Nutzers zu Leistungen & Diensten dieser Internetseite wird limitiert und somit stehen dem Nutzer auch nur bestimmte Inhalte zur Verfügung. Um vollen Zugriff auf den geschlossenen Nutzerbereich dieser Internetpräsenz zu haben ist es erforderlich, dass der Nutzer den Registrierungsprozess abschließt und somit zu einem Mitglied wird. Der Nutzer mag ein Vertreter einer Firma, Gesellschaft, eines Vereines oder einer Behörde sein und ist daher vollumfänglich selbst verantwortlich fpür die Inhalte und die Form der gewählten Selbstdarstellung.
<br /><br />
1.20 "VIP", "VIP-Member" oder "VIP Mitglied" als Begriffe beschreiben jeden einzelnen Nutzer dieser Internetseite, welcher den Registrierungsprozess abgeschlossen und Coins gekauft hat um den vollen Umfang der hier angebotenen Leistungen & Dienste zu nutzen / nutzen zu können.
<br /><br />
1.21 VIP Mitgliedschaft" umfasst den Status aller Mitglieder dieser Internetseite, welche sowohl den Registrierungsprozess abgeschlossen als auch Coins erworben haben um den vollen Umfang der hier angebotenen Leistungen & Dienste zu nutzen / nutzen zu können.
<br /><br />
1.22 "Virtuelle Person" als Begriff beschreibt alle künstlich erschaffenen Personen & Personenprofile, welche stellvertretend durch in irgend einer Geschäftsbeziehung zu dem/den Anbieter(n) dieser Dienste & Leistungen stehenden Personen (seien es Angestellte, freie Mitarbeiter oder sonstige Geschäftspartner) gegen Entgeld mit den Endnutzern dieser Internetseite im Rahmen einer rein virtueller Rollenspiel-Kommunikation in Kontakt treten.
<br /><br />
<strong>2 Zustimmungen</strong>
<br /><br />
2.1 Diese AGB`s sind die rechtsverbindlichen Bedingungen für den Benutzer, das Mitglied und/oder das VIP-Mitglied, um die Website zu besuchen, auf die Webseite zuzugreifen, die Dienste & Leistungen auf dieser Website zu nutzen und/oder um ein eigenes Nutzerprofil auf dieser Internetseite zu erstellen. Der Nutzer, das Mitglied und/oder das VIP-Mitglied welches im Rahmen dieses Angebotes auf dieser Webseite browsen, zugreifen, nutzen oder Daten hochladen bzw. bereitstellen möchte, wird an das Folgende gebunden sein:
<br /><br />
2.1.1 Diese Allgemeinen Geschäftsbedingungen, welche jederzeit einsehbar sind unter: [insert URL of ToS]
<br /><br />
2.1.2 Die Nutzung von Cookies durch den diese Webseite vertretenden Seitenbetreiber sowie
<br /><br />
2.1.3 Die Datenschutzerklärung, welche jederzeit unter [Privacy Policy URL] eingesehen werden kann.
<br /><br />
2.2 Das Mitglied und/oder VIP-Mitglied wird diese Allgemeinen Geschäftsbedingungen bestätigen müssen um Zugang zum geschlossenen Nutzerbereich und allen damit in Verbindung stehenden Diensten & Leistungen zu haben.
<br /><br />
2.3 Der Seitenbetreiber behält sich das Recht vor, diese Allgemeinen Geschäftsbedingungen und/oder die Datenschutzerklärung jederzeit nach eigenem Ermessen abzuändern oder an neu eingetretene Umstände anzupassen. Alle Änderungen an diesen genannten Dokumenten treten in Kraft, sobald sie auf der Webeiste jederman zugänglich gemacht, respektive veröffentlicht wurden. In beiden Dokumenten wird am Ende der einzelnen Bestimmungen das Datum der letzten Aktualisierung/Änderung dargestellt. Der Zugriff und/oder die Nutzung der Dienste & Leistungen auf dieser Webseite nachdem Datenschutzerklärung und/oder Allgemeine Geschäftsbedingungen aktualisiert wurden erneuert das Einverständnis der Nutzer/Mitglieder/VIP-Mitglieder zu eben diesen und den damit ggf. in Verbindung stehenden Änderungen und/oder Aktualisierungen.
<br /><br />
2.4 Der Nutzer versichert, über 18 Jahre alt zu sein (oder das gesetzliche Mindestalter erreicht zu haben, um diese AGB`s in der Gerichtsbarkeit, in der sich der Nutzer befindet, anzunehmen) ist. Die Nutzung der Angebote, Dienste & Leistungen auf dieser Webseite ist für Benutzer über 18 Jahre beschränkt. Entsprechend ist es Nutzern unter 18 Jahren nicht gestattet (oder unterhalb des gesetzlichen Mindestalters innerhalb der Gerichtsbarkeit, in der sich der Nutzer befindet, um diese AGB`s anzunehmen), sich auf dieser Internetseite zu registrieren. Jeder Nutzer verpflichtet sich, bei der Registrierung ehrliche Angaben bezüglich seines Alters zu hinterlegen. Sofern ein Nutzer zum Zeitpunkt der Registrierung nachweislich falsche Angaben über sein tatsächliches Alter (Geburtsdatum) gemacht hat, liegt es allein im Ermessen des Seitenbetreibers, ob und wie dieser Nutzer die Angebote, Dienste & Leistungen dieser Internetseite weiterhin nutzen kann. Der Seitenbetreiber ist zudem berechtigt, auch eine lebenslange Sperre dieses Nutzers für die Angebote, Dienste & Leistungen dieser Internetpräsenz zu veranlassen.
<br /><br />
2.5 Der Zugriff auf die Angebote, Dienste & Leistungen dieser Internetseite ist nur Nutzern, die im Rahmen der Gesetze Ihres Landes derartige Leistungen auch in Anspruch nehmen dürfen und diese AGB`s akzeptieren dürfen.
<br /><br />
2.6 Um Zweifel zu vermeiden wird an dieser Stelle noch einmal ausdrücklich darauf hingewiesen, dass diese Webseite den Zugang zu Angeboten, Inhalten, Diensten & Leistungen ermöglicht bzw. erleichtert. Jugendlichen ist der Zugang zu diesen Angeboten, Inhalten, Diensten & Leistungen ausdrücklich und unter allen Umständen untersagt/zu untersagen.
<br /><br />
2.7 Diese Webseite stellt Angeboten, Inhalten, Diensten & Leistungen für Erwachsene bereit. Dies beinhaltet vor Allem Kontakte, Flirt- und Chatdienste.
<br /><br />
<strong>3 Zustimmung zur Nutzung von Cookies</strong>
<br /><br />
3.1 Der Nutzer/Member/VIP-Member stimmt der Nutzung von Cookies auf dieser und durch diese Internetseite ausdrücklich zu, wie in den Datenschutzbestimmungen näher beschrieben. Einsehbar sind diese jederzeit unter: http://www.flirt48.net/?action=policy
<br /><br />
3.2 Der Seitenbetreiber verwendet so genannte "Session Cookies" auf dieser Internetseite.
<br /><br />
3.3 Der Seitenbetreiber benutzt Google Analytics auf dieser Webseite. Wenn der Benutzer und/oder das Mitglied und/oder die VIP Member mittels eines so genannten Internetbrowsers oder unter Nutzung einer sonstigen Anwendung auf diese Internetseite zugreifen, werden automatisch bestimmte Informationen an Server von Google übermittelt.
<br /><br />
<strong>4 Registrierung</strong>
<br /><br />
4.1 Der Nutzer kann diese Internetseite auch ohne jegliche Vorab-Registrierung besuchen, erhält jedoch nur eingeschränkten Zugang zu den hier angebotenen Diensten & Leistungen bzw. Inhalten. Für den vollen Zugang zu all diesen Optionen ist eine Registrierung des Nutzers als Member unumgänglich. Bestimmte Funktionen, angebote & Dienste stehen dem registrierten Nutzer zudem nur nach erfolgreichem Kauf der auf dieser Webseite verwendeten Online-Währung, den so genannten Coins, zur Verfügung.
<br /><br />
4.2 Im Rahmen der Registrierung werden von dem sich Registrierenden diverse Informationen als Pflichtangaben abgefragt werden. Zum Umgang des Seitenbetreibers mit diesen Angaben finden sich Detailinformationen in der Datenschutzerkärung. Der Nutzer wird nach erfolgreichem Abschluß seiner Registrierung mittels eines Passwortes und eines Benutzernamens Zugriff auf den geschlossenen Nutzerbereich haben.
<br /><br />
4.3 Der Nutzer wird vor Abschluß der Registrierung den Allgemeinen Geschäftsbedingungen, der Datenschutzerklärung sowie der Nutzung von Cookies zustimmen müssen, indem er in den jeweiligen Feldern einen Haken setzt. Durch die Betätigung der Schaltfläche "Registrierung abschließen" oder "Senden" erklärt der Nutzer ausdrücklich Einverständnis zu diesen diesen Bestimmungen.
<br /><br />
4.4 Im Rahmen der Registrierung wird der Nutzer eine Email an seine angegebene Email-Adresse erhalten, in welcher ein Bestätigungslink enthalten ist. Dieser Link muss vom User betätigt (angeklickt) werden um den Registrierungsprozess fortzusetzen. Dieser Link wird den Nutzer wieder zurück auf die internetseite bringen, wo er den Registrierungsprozess dann fortsetzen kann. Auch wird der Nutzer im Rahmen seiner Registrierung eine SMS an seine angebene Mobilfunkrufnummer mit einem dort enthaltenen Code erhalten, welchen er auf der Internetseite in einem speziell gekenneichneten Feld eingeben und durch Betätigung einer schaltfläche bestätigen muss. Sobald dieser Vorgang abgeschlossen ist, wird der Nutzer automatisch in den geschlossenen Nutzerbereich weiter geleitet und ist als Member registriert. Ab diesem Zeitpunkt ist der Nutzer (das Member) in der Lage, sich mit seinem Nutzernamen und seinem Passwort jederzeit in dem geschlossenen Nutzerbereich anzumelden. Um Zweifel zu vermeiden sei erwähnt, dass jedes Member in der Lage sein wird, durch den Verbrauch zuvor erworbener Coins weitere Leistungen & Dienste der Internetseite zu nutzen.
<br /><br />
4.5 Nachdem der Nutzer seine Registrierung erfolgreich abgeschlossen hat, wird er in der Lage sein, innerhalb des geschlossenen Nutzerbereiches jegliche kostenfreien Inhalte einzusehen und seine eigenen Profildaten zu erweitern/korrigieren/anzupassen.
<br /><br />
4.6 Nur Nutzer im Alter von 18 Jahren oder älter dürfen die Registrierung abschließen.
<br /><br />
4.7 Der Nutzer ist verpflichtet, während des Registrierungsprozesses wahre & vollständige Angaben zu machen.
<br /><br />
4.8 Es ist dringend erforderlich, dass jedes Mitglied seine Zugangsdaten, insebsondere das Passwort, sicher und geschützt vor dem Zugriff durch Dritte aufbewahrt.
<br /><br />
4.9 Der Seitenbetreiber wird die Verwendung von vulgären, unangemessenen und/oder beleidigenden Nutzernamen (Nicknames) einschränken und ggf. unterbinden.
<br /><br />
<strong>5 Verbindlichkeiten & Pflichten der Member & VIP-Member (Pflichten des Nutzers)</strong>
<br /><br />
5.1 Die Member & VIP-Member (Mitglieder) verpflichten sich, die auf dieser Internetseite angebotenen Dienste & Leistungen nur in Übereinstimmung mit diesen AGB`s & der Datenschutzerklärung zu nutzen bzw. in Anspruch zu nehmen.
<br /><br />
5.2 Jedes Member und/oder VIP-Member garantiert dem Seitenbetreiber, dass die während der Registrierung angegebenen Informationen, in Übereinstimmung mit Ziffer 4 weiter oben, wahr sind und nach bestem Wissen und Gewissen getätigt wurden.
<br /><br />
5.3 Jedes Member & VIP-Member garantiert dem Seitenbetreiber, dass die angegebenen Informationen nach erfolgter Registrierung, insbesondere die angebenenen Profilinformationen, wahr sind und nach bestem Wissen und Gewissen getätigt wurden.
<br /><br />
5.4 Durch die Nutzung dieser Internetseite erklären die Member & VIP-Member sich, übereinstimmend mit dem Data Protection Act 1998 ("the Act") damit einverstanden, dass Angestellte des Seitenbetreibers genauso Zugriff auf die ihre persönlichen Informationen haben wie vertraglich mit dem Seitenbetreiber verbundene Unternehmen & Einzelpersonen sowie deren Angestellte & Mitarbeiter. Dies geschieht in Übereinstimmung mit der Datenschutzerklärung.
<br /><br />
5.5 Die Member & VIP-Member sind damit einverstanden, verpflichten sich und garantieren dem Seitenbetreiber, dass:
<br /><br />
5.5.1 Das eigene Profil des Members/VIP-Members auch von ihm selbst erstellt wurde und
<br /><br />
5.5.2 Das Member oder VIP-Member nicht Informationen über sich selbst im Profil hinterlegen wird, die die Urheberrechte Dritter verletzen.
<br /><br />
5.6 Die Nutzer, die Member & VIP-Member sind damit einverstanden, verpflichten sich und garantieren dem Seitenbetreiber, dass:
<br /><br />
5.6.1 Jegliche Urheberrechtsverletzung in jedem Profil des Nutzers, des Members und/oder des VIP-Members unverzüglich dem Seitenbetreiber zu melden und den Seitenbetreiber bei der Beseitigung derartiger Inhalte zu unterstützen durch eine Informations-Email an: [Insert Email-Address];
<br /><br />
5.6.2 Regelmässig die Internetseite zu besuchen und sich auf dem Laufenden zu halten bezüglich Änderungen und/oder Aktualisierungen, welche durch den Seitenbetreiber an den Allgemeinen Geschäftsbedingungen vorgenommen werden.
<br /><br />
5.6.3 Keinesfalls im Rahmen der Registrierung oder danach falsche, irreführende oder ungenaue Informationen auf dieser Internetseite zu verwenden.
<br /><br />
<strong>6 Zahlmethoden</strong>
<br /><br />
6.1 Um auf dieser Webseite Zahlungen leisten zu können, muss jeder Nutzer einen Prozess durchlaufen:
<br /><br />
6.1.1 Das Member muss den Allgemeinen Geschäftsbedingungen zustimmen, bevor Zahlungen geleistet werden können.
<br /><br />
6.1.2 Alle Preise werden in EUR dargestellt.
<br /><br />
6.1.3 Zahlungen mit der Kreditkarte unterliegen u.U. einer externen Gebühr der jeweiligen Kreditkartengesellschaft
<br /><br />
6.1.4 Zahlungen werden akzeptiert durch Kreditkarte, Ukash und/oder Paysafe
<br /><br />
6.1.5 Das Member muss über 18 Jahre alt sein und dieses auch versichern (oder in dem legalen Alter im Rahmen der Gesetze seines Landes, um diese Allgemeinen Geschäftsbedingungen zu bestätigen.
<br /><br />
6.1.6 Um jeden Zweifel auszuräumen: jede Nachricht, die von Membern empfangen wird – gleich ob per Email oder SMS – ist frei von jeglichen Kosten. Lediglich das Schreiben von Nachrichten unterliegt den ausgewiesenen Kosten.
<br /><br />
6.2 Um ein Member im Rahmen der kostenfreien Nutzung des geschlossenen Nutzerbereiches zu werden, muss der Nutzer diesen Allgemeinen Geschäftsbedingungen zustimmen/zugestimmt haben. Diese kostenfreie Registrierung, verbunden mit der kostenfreien Nutzung einzelner und gesondert ausgewiesener Angebote, beinhaltet eine zeitlich ungebundene Mitgliedschaft.
<br /><br />
<strong>7 Member Zugänge</strong>
<br /><br />
7.1 Um ein Profil auf dieser Webseite erstellen zu können, muss der jeweilige Nutzer sich zunächst auf dieser Internetseite registrieren, also den Status eines Members erreichen.
<br /><br />
7.2 Für den Fall, dass das Member und/oder VIP-Member Kenntnis davon erlangt, dass sich Unbefugte auf den eigenen Zugang Zugriff verschaffen, ist der Seitenbetreiber unverzüglich zu benachrichtigen durch eine Email an: [Insert Email-Address]
<br /><br />
7.3 Das Member/VIP-Member ist für den Seitenbetreiber haftbar für alle Aktivitäten, welche in Zusammenhang mit dem jeweiligen persönlichen Zugang stehen.
<br /><br />
7.4 Das Member/VIP-Member ist nicht berechtigt, den Zugang eines anderen Members/VIP-Members zu nutzen, sei es für eigene oder fremde Zwecke.
<br /><br />
7.5 Für den Fall, dass ein Member und/oder VIP-Member gegen eine Bestimmung innerhalb dieser Allgemeinen Geschäftsbedingungen und/oder der Datenschutzbestimmungen verstößt, stehen dem Seitenbetreiber alle Möglichkeiten des "Hausrechtes" sowie alle juristischen Möglichkeiten offen. In jedem Fall aber ist der Seitenbetreiber unverzüglich und ohne Vorankündigung berechtigt, den betreffenden Nutzerzugang auf unbestimmte Zeit zu sperren.
<br /><br />
7.6 Der Seitenbetreiber ist nicht verantwortlich für jegliche entstandenen Schäden oder Verluste, die Member und/oder VIP-Member durch unauthorisierte Nutzung/Handlungen innerhalb der jeweiligen Nutzerzugänge erleiden. Im Gegenzug ist jedoch das Member/VIP-Member dem Seitenbetreiber gegenüber für entstandene & zukünftig entstehende Schäden verantwortlich, die durch derartige unauthorisierte Nutzungshandlungen entstanden sind/entstehen oder entstehen können.
<br /><br />
7.7 Im Falle, dass das Member einen kostenfreien Zugang besitzt hat das Member Zugang zu folgenden Diensten & Leistungen:
<br /><br />
7.7.1 Im Falle einer zeitlich unbegrenzten kostenfreien Mitgliegschaft ist das Member berechtigt, die folgenden Handlungen durchzuführen:
<br /><br />
7.7.1.1.Die einmalige Versendung von [Number] Online-Nachrichten zu den angegebenen Coinkosten an andere Member/VIP-Member/virtuelle Personen;
<br /><br />
7.7.1.2.Der in der Menge unlimitierte Empfang von Online-Nachrichten von anderen Member/VIP-Member/virtuellen Personen;
<br /><br />
7.7.1.3.Die einmalige Versendung von [Number] SMS Nachrichten zu den angegebenen Coinkosten an andere Member/VIP-Member/virtuelle Personen;
<br /><br />
7.7.1.4.Der in der Menge unlimitierte Empfang von SMS-Nachrichten von anderen Member/VIP-Member/virtuellen Personen.
<br /><br />
7.8 Im Falle, dass ein Member die Online-Währung Coins auf dieser Webseite erwirbt, erhält das Member automatisch den so genannten VIP-Status, welcher Zugang zu folgenden Leistungen gewährt:
<br /><br />
7.8.1 Im Falle einer zeitlich unbegrenzten Mitgliedschaft ist das VIP-Member berechtigt, die folgenden Handlungen durchzuführen:
<br /><br />
7.8.1.1 Der in der Menge unlimitierte Versand von Online-Nachrichten an andere Member/VIP-Member/virtuelle Personen, abhängig vom noch verfügbaren Coin-Guthaben;
<br /><br />
7.8.1.2 Der in der Menge unlimitierte Empfang von Online-Nachrichten von anderen Member/VIP-Member/virtuellen Personen, abhängig vom noch verfügbaren Coin-Guthaben;
<br /><br />
7.8.1.3 Der in der Menge unlimitierte Versand von SMS Nachrichten an andere Member/VIP-Member/virtuelle Personen, abhängig vom noch verfügbaren Coin-Guthaben;
<br /><br />
7.8.1.4 Der in der Menge unlimitierte Empfang von SMS-Nachrichten von anderen Member/VIP-Member/virtuellen Personen, abhängig vom noch verfügbaren Coin-Guthaben.
<br /><br />
<strong>8 Widerrufsfrist</strong>
<br /><br />
8.1 Da es sich bei diesen Produkten um so bezeichnete "Verbrauchsgüter" handelt, ist ein Widerruf bzw. eine Erstattung der bereits erhaltenen Leistungen und eine Rückzahlung der bereits erfolgten Ausgaben auf seiten des Nutzers nicht möglich.
<br /><br />
<strong>9 Die Pflichten des Seitenbetreibers</strong>
<br /><br />
9.1 Der Seitenbetreiber ist verpflichtet, die von ihm bereit gestellten Leistungen auf dieser Webseite für seine Member und VIP-Member mit der gebotenen Umsicht und Professionalität auszuführen.
<br /><br />
9.2 Der Seitenbetreiber wird im Rahmen seines Verantwortungsbereiches Sorge dafür tragen, dass die Webseite ohne unnötige Verzögerungen jederzeit erreichbar ist. Für extern verursachte Ausfälle ist der Seitenbetreiber hingegen nicht verantwortlich und/oder verantwortlich zu machen.
<br /><br />
9.3 Der Seitenbetreiber wird sicherstellen, dass entsprechende, marktübliche Sicherheitsvorkehrungen gegen die Zerstörung, den Verlust oder den unauthorisierte Zugriff auf diese Webseite getroffen werden. Das schließt Sicherungen des vorhandenen Datenmaterials ausdrücklich mit ein.
<br /><br />
9.4 Um jeden Zweifel auszuräumen: Der Seitenbetreiber gibt keine Garantien betreffend die Qualität, die Erreichbarkeit oder das persönliche Nutzer-Wohlbefinden, die nicht in seiner Hand und/oder seinem Verantwortungsbereich liegen. Ausdrücklich ist der Seitenbetreiber auch nicht dafür verantwortlich und/oder haftbar zu machen, wenn der Nutzer/das Member/das VIP-Member nach Nutzung der auf dieser Webseite anngebotenen Dienstleistungen eine andersartige Dienstleistung vorgestellt hat und/oder diese Leistungen für ein kostenfreies Angebot gehalten hat. Auch ist der Seitenbetreiber in keisnter Weise verantwortlich für einen wie auch immer gearteten Erwartungshorizont des Nutzers/Members/VIP-Members, insofern dafür auch nicht haftbar zu machen.
<br /><br />
9.5 Der Seitenbetreiber ist ausdrücklich nicht verantwortlich für die Nicht-Erreichbarkeit der Dienste & Leistungen sowie der Webseite selbst, sofern es nicht seinem Verantwortungsbereich unterliegt. Dies schließt technische Probleme ausserhalb der Sphäre des Seitenbetreibers sowie außerplanmässige Wartungsarbeiten mit ein.
<br /><br />
9.6 Weder der Seitenbetreiber noch irgend ein Dritter hat Kontrolle über das Internet, welches ein dezentralen, globales Netzwerk darstellt, bestehend aus einer unüberschaubaren Zahl unterschiedlichster Computersysteme. Probleme bezüglich der Erreichbarkeit dieser Webseite mögen weit ausserhalb der vom Seitenbetreiber verantwortbaren und/oder leistbaren und/oder kontrollierbaren Grenzen auftreten, wie beispielsweise Systemfehlfunktionen oder Fehler von Seiten Dritter. Unter diesen Umständen wird der Seitenbetreiber Alles tun, was in seiner Macht steht und/oder in seinem Verantwortungsbereich liegt um die Dienste & Leistungen dieser Webseite so schnell wie möglich erneut zur Verfügung zu stellen.
<br /><br />
9.7 Der Seitenbetreiber wird ernsthafte Anstrengungen unternehmen, um jeglichen denkbaren Fehler so schnell als irgend möglich und innerhalb realistischer Zeit- sowie Kostenrahmen wieder zu beheben. Es ist mittlerweile Bestandteil der Allgemeinbildung, dass die Nutzung des Internets generell Risiken in sich birgt, die weit über die reine Stabilität der Kommunikation hinaus gehen. Daher ist es ausdrücklich nicht möglich, jeglichen Umstand innerhalb oder ausserhalb des Verantwortungsbereiches des Seitenbetreibers für jeglichen denkbaren in der Zukunft liegenden Zeitpunkt zu berücksichtigen und somit eine in die Zukunft schauende Garantie abzugeben, was die Dienste & Leistungen angeht.
<br /><br />
9.8 Der Seitenbetreiber hat das Recht jegliches Nutzerprofil von seiner Webseite zu entfernen, welches Urheberrechte Dritter verletzt, bis der Ursprung der tatsächlichen Urheberschaft nachgewiesen ist.
<br /><br />
9.9 Der Seitenbetreiber hat das Recht, jegliches Nutzerprofil von seiner Webseite zu entfernen, welches erwiesenermaßen Urheberrechte Dritter verletzt.
<br /><br />
<strong>10 Disclaimers</strong>
<br /><br />
10.1 Der Seitenbetreiber macht keine Zusicherungen, dass:
<br /><br />
10.1.1 Profile, welche von den Membern/VIP-Membern auf dieser Webseite hochgeladen werden/worden sind, ganz oder in Teilen frei sind von Material(ien), welche einen Verstoß gegen jegliches Schutzrecht darstellen;
<br /><br />
10.1.2	Profile, welche von den Membern/VIP-Membern auf dieser Webseite hochgeladen werden/worden sind, ganz oder in Teilen frei sind von lizenzpflichtigen Inhalten, die der Zustimmung durch einen anderen Rechteinhaber bedürfen;
<br /><br />
10.1.3 das Gegenüber, mit welchem das Member/VIP-Member in Kontakt tritt um zu flirten und zu chatten ein gleichartiges Ziel oder eine gleichartige Interessenlage verfolgt wie das Member/VIP-Member selbst. Um jeden Zweifel auszuräumen: der Seitenbetreiber hat keinerlei Einfluss auf die Inhalte, welche zwischen den einzelnen Membern/VIP-Membern ausgetauscht werden, benso wenig hat der Seitenbetreiber Einfluss auf die im Einzelnen innerhalb dieser Kommunikation verfolgten Ziele & Vorstellungen;
<br /><br />
10.1.4 das Member und/oder VIP-Member jemals ein reales Treffen mit seinem Gegenüber wird vereinbaren können oder dass es jemals dazu kommen wird. Ob und wie es zu Treffen bzw. Verabredungen derselben kommt liegt allein im Ermessen der beiden Kommunikationsparteien und unterliegt ausdrücklich nicht dem Verantwortungsbereich des Seitenbetreibers.
<br /><br />
10.2 Der Seitenbetreiber wird im Rahmen seiner Möglichkeiten den Kontakt, die Kommunikation, den Flirt zwischen den den Mitgliedern/VIP-Mitgliedern/virtuellen Personen erleichtern. Um jeden Zweifel auszuräumen: es ist ein Teil der Dienste & Leistungen auf dieser Webseite, dass die Member/VIP-Member mit virtuellen Personen flirten bzw. plaudern können!
<br /><br />
<strong>11. Garantien</strong>
<br /><br />
Das Member und/oder VIP-Member sowie der Seitenbetreiber gewährleisten gegenseitig, dass jede Vertragspartei über ausreichende Recht am Profil verfügt um ihre vertraglichen Pflichten zu erfüllen, sobald die für ein Profil erforderlichen Inhalte einmal auf die Webseite hochgeladen wurden.
<br /><br />
11.1 Obwohl der Seitenbetreiber bestrebt ist sicher zu stellen, dass diese Webseite mit all ihren Angeboten, Leistungen & Diensten jederzeit erreichbar ist, können dafür keine Garantien übernommen werden. Der Seitenbetreiber behält sich das Recht vor, ohne die Nennung von Gründen jederzeit den Zugang zu der Webseite und/oder einzelnen Leistungen & Diensten auf dieser Webseite einzuschränken und/oder ganz zu verhindern.
<br /><br />
11.2 Obwohl der Seitenbetreiber bestrebt ist sicher zu stellen, dass die auf dieser Webseite dargestellten Inhalte vollständig angezeigt werden, kann und wird er dafür keine Garantien übernehmen. Eine Gewährleistung beispielweise aber nicht ausschließlich bezüglich der Rechtmäßigkeit, Vollständigkeit, Eignung für einen bestimmten Zweck, Funktionalität Zuverlässigkeit, Verfügbarkeit, Geschwindigkeit des Zugriffes oder Aktualität durch den Seitenbetreiber erfolgt ausdrücklich nicht.
<br /><br />
<strong>12 Verbotene Inhalte</strong>
<br /><br />
12.1 Es ist den Membern und/oder VIP-Membern nicht gestattet, an irgend einer Stelle dieser Webseite Inhalte zu veröffentlichen, die verletzend, beleidigend, diffamierend, belästigend, abfällig oder anderweitig anstößig sind oder die Rechte Dritter verletzen. Die Member/VIP-Member werden auf dieser Webseite keine Software- oder Datenbankinhalte lagern und auch sonst keine Inhalte hier ablegen, die mit den Gesetzen des jeweiligen Landes nicht in Einklang stehen.
<br /><br />
<strong>13 Lizenz, Zusicherungen und Gewährleistungen</strong>
<br /><br />
13.1 Durch die Veröffentlichung ihrer Daten sichern die Member/VIP-Member dem Seitennbetreiber eine unwiderrufliche, unbefristete, nicht-exklusive, unentgeltliche, weiltweite, übertragbare Sub-Lizenz und übertragbare Lizenz zu, diese Inhalte zu veröffentlichen, zu verbreiten und derivative Kopien davon zu erstellen. Dies gilt auch für eventuelle zukünftige Rechts- oder Geschäftsnachfolger des gegenwärtigen Seitenbetreibers, einschließlich und ohne Einschränkungen in Bezug auf die Umverteilung von Teilen oder den Inhalten in Ihrer Gesamtheit (und Derivaten davon) in einem beliebigen Media-Format und über Medien jeder Art ohne Erfordernis einer vorherigen Genehmigung durch das Member/VIP-Member.
<br /><br />
<strong>14 Recht auf Entfernung einzelner Inhalte</strong>
<br /><br />
14.1 Alle Profile, welche durch Member/VIP-Member vorgelegt werden unterliegen dem Vorbehalt der Genehmigung durch den Seitenbetreiber. Profile werden durch den Seitenbetreiber nach dem Zufallsprinzip in Übereinstimmung mit Ziffer 26 kontrolliert. Die Member/VIP-Member erklären sich ausdrücklich damit einverstanden, dass der Seitenbetreiber berechtigt ist ihre Profile ganz oder in Teilen zu entfernen oder zu deaktivieren respektive den Zugriff auf ganze Profile oder einzelne Teile dieser einzuschränken (einschließlich, aber nicht beschränkt auf jene Profile, die die Member/VIP-Member entsprechend ihrer jeweiligen Mitgliedsstaaten auf diese Webseite hochgeladen haben). Der Seitenbetreiber nimmt die Angaben der jeweiligen Member/VIP-Member in gutem Glauben als wahr an und es liegt im Ermessen des Seitenbetreibers, wann er einen Verstoß gegen die hier vorliegenden Allgemeinen Geschäftsbedingungen annimmt. Verbotene Profile und/oder Profilbestandteil. Verbotene Bestandteile innerhalb eines Profiles sind beispielsweise:
<br /><br />
14.1.1 Offenkundig den Rassismus, Fanatismus, Hass oder die körperliche Gewalt jeglicher Art gegen Einzelpersonen oder Personengruppen fördernde oder dazu aufrufende Aussagen & Begrifflichkeiten;
<br /><br />
14.1.2 Aussagen & Begriffe die geeignet sind, andere Personen oder Personengruppen zu belästigen oder dazu aufzurufen;
<br /><br />
14.1.3 Aussagen & Begriffe die geeignet sind, andere Personen oder Personengruppen in sexueller oder gewaltsamer Weise zu verletzen;
<br /><br />
14.1.4 Aussagen & Begriffe die geeignet sind, ein Übermaß an Gewalt oder andersartige anstößige Inhalte auszudrücken;
<br /><br />
14.1.5 Vetrauliche oder persönliche Informationen von Personen unter 18 Jahren;
<br /><br />
14.1.6 Veröffentlichte Beiträge oder Informationen, die ein Datenschutz- oder Sicherheitsrisiko für einzelne Personen darstellen;
<br /><br />
14.1.7 Dargestellte Informationen oder das Fördern derselben, sofern diese offenkundig falsch oder irreführend, illegale Handlungen oder Verhaltensweisen darstellend, beleidigend, bedrohend, obszön, diffamierend oder verleumderisch sind;
<br /><br />
14.1.8 Sich auf die Übertragung von "Junk Mail", "Kettenbriefen" oder unaufgeforderten Massenmails, Sofortnachrichten, "Fishing" oder "Spamming beziehende oder Derartiges fördernde Inhalte;
<br /><br />
14.1.9 Enthaltene Pseudonyme, die geeignet sind die Rechte Dritter zu verletzen wie beispielsweise Verstöße gegen das Urheberrecht, Patent- & Markenrechte, Eigentumsrechte sowie andere persönliche Informationen ohne die vorherige Zustimmung durch den betroffenen Dritten.
<br /><br />
14.1.10 Enthaltene Bilder oder (versteckte) Seiten, die nur eingeschränkt oder durch Nutzung eines Passwortes zugänglich sind;
<br /><br />
14.1.11 Aussagen & Begriffe die geeignet sind, kriminelle Aktivitäten zu fördern oder derartige Vorhaben unterstützen bzw. als Anweisungen verstanden werden können;
<br /><br />
14.1.12 Passworte oder personenbezogene Daten von anderen Nutzern für kommerzielle oder ungesetzliche Zwecke;
<br /><br />
14.1.13 Sich auf kommerzielle Aktivitäten und/oder Verkäufe beziehende Inhalte ohne vorherige schriftliche Genehmigung durch den Seitenbetreiber.
<br /><br />
14.2 Der Seitenbetreiber ist nicht verpflichtet, jedem User, jedem Member und/oder VIP-Member den Zugang zu den Diensten & Leistungen dieser Webseite zu ermöglichen und kann dieses nach eigenem Ermessen auch ablehnen.
<br /><br />
<strong>15 Ausschluß der Haftung für Profilinhalte</strong>
<br /><br />
15.1 Der Seitenbetreiber hat nicht die Inhalte für jedes Profil, nicht jede Meinung, Empfehlung oder jeden Ratschlag der auf dieser Webseite dargestellt wird erstellt. Ausdrücklich ist der Seitenbetreiber nicht haftbar zu machen für Inhalte, die durch seine Nutzer/Member/VIP-Member hochgeladen bzw. durch diese auf dieser Webseite bereit gestellt wurden.
<br /><br />
<strong>16 Limitierung der Haftung</strong>
<br /><br />
16.1 Es ist ein Grundsatz des Seitenbetreibers, keinen Akt der Verletzung gewerblicher Schutzrechte oder Verletzungen der Gesetze der Dominikanischen Republik oder anderen anwendbaren Gesetzen zu tolerieren. Der Seitenbetreiber wird alle angemessenen Anstrengungen unternehmen Profile zu entfernen, deaktivieren oder nut noch beschränkten Zugriff auf diese zu ermöglichen bis hin zu vorübergehender Nicht-Verfügbarkeit, sofern diese aus der subjektiven Sicht des Seitenbetreibers gegen diesen Grundsatz verstoßen. Die Bestimmungen des Abs. 2 sollen diese Allgemeinen Geschäftsbedingungen ergänzen, sind aber nicht dazu gedacht, den Seitenbetreiber an anderer Stelle zu limitieren oder haftbar zu machen.
<br /><br />
16.2 Der Seitenbetreiber ist für unauthorisiert und/oder unbefugt hochgeladene Profile nicht haftbar zu machen.
<br /><br />
16.3 Nichts in diesen Allgemeinen Geschäftsbedingungen soll in irgend einer Weise die Verantwortung und/oder Haftung irgend einer Partei limitieren in Fällen von Betrug, Mord oder Körperverletzung, sofern direkt durch die Fahrlässigkeit der betroffenen Parteien verursacht . Dies gilt für jegliche Form der Haftung, sofern diese nicht ausgeschlossen werden kann oder als eine Angelegenheit von begrenzten Rechts oder verursacht wurde durch Bedienste, Mitarbeiter oder Vertreter. Dies gilt unter diesen Vorbehalten:
<br /><br />
16.3.1 Die maximale Haftung der Gesellschaft bezüglich unerlaubter Handlung (ohne Diffamierung), Fahrlässigkeit, vorvertragliche oder andere Zusicherungen oder anderweitig aus oder im Zusammenhang mit diesen Allgemeinen Geschäftsbedingungen oder die Leistung oder die Einhaltung seiner Verpflichtungen aus diesen Allgemeinen Geschäftsbedingungen, und jeder anwendbaren Teil davon wird in ihrer Gesamtheit auf den Wert der Zahlung für den im Individualfall in Rede stehenden Gegenwert der betroffenen Coinsumme im Rahmen dieser Allgemeinen Geschäftsbedingungen beschränkt.
<br /><br />
16.4 Der Seitenbetreiber ist nicht haftbar zu machen für die Erwartungen seiner Member/VIP-Member/Nutzer, seien diese geschäftlicher oder auch rein privater Natur, seien sie ein tatsächlicher oder erwarteter Verlust, sei dieser Verlust materiell oder immateriell. Auch haftet der Seitenbetreiber nicht für daraus möglicherweise entstehende Folgeschäden irgendwelcher Art. Der Seitenbetreiber haftet ausdrücklich nur für Dinge, die er innerhalb dieser Allgemeinen Geschäftsbedingungen aufgrund der geltenden Gesetzeslage nicht ausschließen kann.
<br /><br />
16.5 Unter keinen Umständen wird das Unternehmen seine leitenden Angestellten, Direktoren, Mitarbeiter oder Vertreter haftbar machen für irgendwelche Fehler, Trojanische Pferde, Viren, oder dergleichen, sofern diese von Dritten auf diese Webseite übertragen wurden oder werden.
<br /><br />
16.6 Die Basis, auf der der Seitenbetreiber diese Webseite den Membern/Nutzern/VIP-Membern zugänglich macht lautet "so wie sie ist" und "wenn verfügbar" ohne jeglichen weiteren Garantien bezüglich der Natur oder Richtigkeit irgend welcher Inhalte auf dieser Webseite. Um Zweifel zu vermeiden sei erwähnt, dass der Seitenbetreiber (im vollen Umfang und gesetzlich zulässig) Garantien hinsichtlich der Qualität und/oder Eignung der Profile auf dieser Webseite und darüber, ob diese sich für einen bestimmten Zweck eignen, ausschließt. Der Seitenbetreiber ist unter keinen Umständen und in keinster Weise haftbar für Handlungen seiner Member/VIP-Member, seien diese privater oder gewerblicher Natur, sofern diese für eine eigene vorteilhaft erscheinende Darstellung innerhalb der jeweiligen Profile gegen diese Allgemeinen Geschäftsbedingungen verstoßen.
<br /><br />
<strong>17 Politik</strong>
<br /><br />
17.1 Unter keinen Umständen haftet der Seitenbetreiber, seine leitenden Angestellten, Direktoren, Mitarbeiter oder Vertreter gegenüber dem Member/VIP-Member in dem Falle, dass irgend ein Profil auf der Webseite bereitgestellt wurd/wurde, um die Rechte Dritter zu verletzen.
<br /><br />
17.2 Weder der Seitenbetreiber, noch seine leitenden Angestellten, Direktoren, Mitarbeiter oder Agenten geben eine Zusicherung oder Gewährleistung in Bezug auf die Qualität, Eignung oder Echtheit der Profile ab, die von jedem einzelnen Member und/oder VIP-Member auf diese Website hochgeladen wurden.
<br /><br />
17.3 Die Member/VIP-Member erklären sich bereit, jeglichen dem Seitenbetreiber entstehenden Schaden zu erstatten, der durch ihre Handlungen im Umgang mit dem Profil entsteht, insbesondere betrifft dass die Verletzung von Rechten Dritter.
<br /><br />
17.4 Die Member/VIP-Member erklären sich damit einverstanden und dazu bereit, sofern durch ihre Handlungen dem Seitenbetreiber Schäden entstehen, die entstandenen Schäden und Auslagen zu erstatten.
<br /><br />
<strong>18. Datenschutz und Vertraulichkeit</strong>
<br /><br />
18.1 Der Seitenbetreiber hat den Information Commissioner in der Dominikanischen Republik im Sinne des Data Protection Act von 1998 benachrichtigt und wird die ihm auferlegten Bestimmungen erfüllen.
<br /><br />
18.2 Alle personenbezogenen Daten, die (ggf. von Zeit zu Zeit) durch den Seitenbetreiber über die Nutzer/Member/VIP-Member gesammelt werden, werden streng vertraulich behandelt und nicht an Dritte weitergegeben, außer dies ist gesetzlich vorgeschrieben. Der Seitenbetreiber wird nur personenbezogene Daten erheben, sofern diese im Einklang mit dem Data Protection Act von 1998 und den Datenschutzbestimmungen stehen.
<br /><br />
18.3 Die Daten der Nutzer/Member/VIP-Member werden in einer Datenbank im Einklang mit den Bestimmungen des Data Protection Act von 1998 gespeichert werden.
<br /><br />
18.4 Der Seitenbetreiber kann die Nutzer/Member/VIP-Member über Angebote und/oder Neuigkeiten bzw. Änderungen auf dieser Webseite informieren. Für den Fall, dass die Nutzer/Member/VIP-Member dieses nicht wünschen, können diese im Einklang mit den Bestimmungen des Datenschutzes davon Abstand nehmen durch Zusendung einer Email an: [insert email]
<br /><br />
<strong>19. Vertraulichkeit</strong>
<br /><br />
Während der Laufzeit dieser Allgeimenen Geschätsbedingungen und für einen Zeitraum von 2 Jahren danach stimmen die Member/VIP-Member zu, alle Informationen, die die Member/VIP-Member in dieser Zeit über den Seitenbetreiber, sein Geschäft, seine Finanzen sowie innere Abläufe, mit Arbeiten betraute Personen & Technologien erhalten haben ("Vertrauliche Informationen" streng vertraulich zu behandeln.
<br /><br />
19.1 Die Bedingungen von Ziffer 19 gelten nicht bei:
<br /><br />
19.1.1 Informationen, die auf anderem Wege und unabhängig von dieser Klausel bereits Verbreitung in der Öffentlichkeit gefunden haben und/oder
<br /><br />
19.1.2 Informationen, die durch einen Dritten ohne Verletzung dieser Klausel oder einer anderen Vertrauenspflicht mitgeteilt wurden. und/oder
<br /><br />
19.1.3 Informationen, die im Rahmen von Berateraufträgen offen gelegt wurden unter der Voraussetzung, dass die Verpflichtung zur Geheimhaltung aus solchen Beratungsverhältnissen nicht weniger Vertraulichkeit bietet als die Ziffer 19 weiter oben und/oder
<br /><br />
19.1.4 Informationen, die trivial oder offensichtlich sind.
<br /><br />
<strong>20 Webseiten Disclaimer</strong>
<br /><br />
20.1 Der Seitenbetreiber wird keinesfalls persönliche Daten oder Profile seiner Nutzer/Member/VIP-Member an Dritte gleich welcher Art weiter geben, Auch ist der Seitenbetreiber nicht dafür verantwortlich zu machen, dass einzelne bereit gestellte Inhalte in anderen Theritorien, die einer anderen Rechtshoheit unterliegen, als illegal gelten oder verboten sind. Nutzer/Member und/oder VIP-Member, die eigenständig die entscheidung fällen, die Dienste und/oder Leistungen dieser Internetpräsenz zu nutzen, obwohl sie einer anderen Gerichtsbarkeit unterliegen, handeln allein aus eigener Initiative und auf eigenes Risiko!
<br /><br />
20.2 Der Seitenbetreiber verwehrt sich gegen jede Verantwortlichkeit, ausgedrückt oder angenommen, für von Nutzern/Membern/VIP-Membern bereit gestellte Inhalte. Diese Webseite wird Nutzern/Membern/VIP-Membern bereit gestellt "wie sie ist" ohne jegliche wie auch immer gearteten Garantien bezüglich der Natur, der Vollständigkeit, (gleich ob gerade erst bereit gestellt oder im Laufe der Zeit aufgefallen) in Bezug auf jeglichen bereitgestellten Inhalt und/oder Profile auf dieser Webseite.
<br /><br />
20.3 Der Seitenbetreiber ist nicht verantwortlich zu machen für eevntuelle Anweisungen und/oder Ratschläge von dritter Seite auf dieser Webseite oder bezüglich diese Webseite, gleichgültig ob grundsätzlich und/oder oberflächlich oder speziell und der Nutzer/Member und/oder VIP-Member hst den Seitenbetreiber unverzüglich zu informieren, soern er von derartigen Aktionen Kenntnis erlangt und diese gegen die hier benannten Allgemeinen Geschäftsbedingungen verstoßen.
<br /><br />
<strong>21 Höhere Gewalt</strong>
<br /><br />
21.1 Der Seitenbetreiber ist nicht verantwortlich für nichterfüllte Pflichten, wenn sie ausserhalb der Kontrolle liegen, einschließlich der folgenden Ursachen:
<br /><br />
21.1.1 Höhere Gewalt, wie beispielsweise Feuer, Fluten, Erdbeben, Stürme, Hurricanes oder andere Naturkatastrophen;
<br /><br />
21.1.2 Krieg, Invasionen, Gewalt von aussen, Feindseligkeiten (gleich, ob Krieg erklärt wurde oder nicht), Bürgerkrieg, Rebellion, Revolution, Aufstände, militärische oder widerrechtliche Machtübernahme sowie Beschlagnahme oder terroristische Aktivitäten.
<br /><br />
21.1.3 Sanktionen von und durch Regierungen, Verstaatlichung, Blockaden, Arbeitskämpfe, Blockaden oder Ausfälle von Versorgungsleistungen.
<br /><br />
<strong>22 Rechte Dritter</strong>
<br /><br />
22.1 Ungeachtet anderer Bestimmungen innerhalb dieser Allgemeinen Geschäftsbedingungen haben Dritte, die nicht Vertragspartei des Seitenbetreibers sind keine Ansprüche auf die Erfüllung von Leistungen nach dem Contracts Act von 1999 (Rights of Third Parties) oder das Recht auf die Durchsetzung einzelner Bestandteile dieser Allgemeinen Geschäftsbedingungen zu bestehen. Nichts innerhalb dieser Allgemeinen Geschäftsbedingungen berührt die Rechte und Ansprüche Dritter, sofern es nicht dem oben genannten Gesetz unterliegt.
<br /><br />
<strong>23 Abtretung</strong>
<br /><br />
23.1 Das Member und/oder VIP-Member ist nicht berechtigt, Ansprüche oder Rechte aus diesen Allgemeinen Geschäftsbedingungen ganz oder in Teilen an Dritte abzutreten.
<br /><br />
<strong>24 Verzichtserklärung</strong>
<br /><br />
24.1 Das Versäumnis der Ausübung eines Rechts oder Rechtsmittels durch den Seitenbetreiber – gleich in welchem Stadium der Ausübung – gilt nicht als Verzicht auf das Recht oder Rechtsmittel, weder ganz noch in Teilen. Die Rechte und Rechtsmittel wie in diesen Allgemeinen Geschäftsbedingungen als Basis für die Geschäftsbeziehung dargelegt sind kumulativ und schließen die spätere Einforderung von Rechten & Rechtsmitteln nicht aus.
<br /><br />
Die Nichtigkeit, Rechtswidrigkeit oder Undurchführbarkeit einer Bestimmung dieser AGB berührt nicht und/oder beeinflusst nicht die weitere Gültigkeit der übrigen Bestimmungen dieser Allgemeinen Geschäftsbedingungen. Nichts in diesen Allgemeinen Geschäftsbedingungen soll als eine Partnerschaft oder ein Joint Venture jeglicher Art zwischen den Vertragsparteien verstanden werden.
<br /><br />
Jede der beiden Vertragsparteien verpflichtet sich, alle gegenseitig zugesprochenen Rechte & Pflichten nach besten Kräften und in angemessener Weise zu erfüllen und alle Schritte zu unternehmen die notwendig oder wünschenswert sind um die Erfüllung der Vertragsbestandteile im Sinne dieser Allgemeinen Geschäftsbedingungen zu gewährleisten. Die Erfüllung jedes Vertragsbestandteiles hat in angemessener, zumutbarer Weise zu erfolgen und es sind alle zumutbaren Anstrengungen zu unternehmen um dieses auch zu gewährleisten, soweit die Vertragsparteien dazu in der Lage sind. Sies schließt auch die Erstellung weiterer Urkunden & Dokumente, Versicherungen, Erfüllungshandlungen und alle anderen Dinge mit ein, welche ein Vertragspartner, wie in jedem Vertrag üblich, vernünftigerweise verlangen kann um die einzelnen Bestimmungen & Vertragsbestandteile zu erfüllen.
<br /><br />
<strong>25 Gerichtsbarkeit</strong>
<br /><br />
25.1 Diese Allgemeinen Geschäftsbedingungen unterliegen der Gerichtsbarkeit von England & Wales und der Nutzer/das Member und/oder VIP-Member stimmt dem Standort der Gerichtsbarkeit sowie der Zuständigkeit der Gerichte & Gesetzgebungen ausdrücklich zu! Der Nutzer/das Member und/oder VIP-Member akzeptiert, dass keine fremdartige oder andersartige Gesetzgebung auf diese Bestimmungen angewendet werden kann, gleich, aus welchem Land diese Internetseite aufrufbar sein sollte, gleich, ob das Angebot dieser Internetseite in anderen Ländern dieser Welt nicht dem Rechts- und/oder Geschäftsgebahren des jeweiligen Landes entsprechen sollte.
<br /><br />
<strong>26 Gesamte Vereinbarung und salvatorische Klausel</strong>
<br /><br />
26.1 Diese Allgemeinen Geschäftsbedingungen und auch die Datenschutzerklärung mögen für den Einzelnen zeitlweilig schwer verständlich sein, Dennoch ist dies eine Individualabrede zwischen zwei Parteien und regelt alle wesentlichen Bestandteile der Geschäftsbeziehung der Beteiligten.
<br /><br />
26.2 Sollte(n) eine oder mehrere Bestimmungen dieser AGB ungültig sein, so bleibt die Wirksamkeit der übrigen Bestimmungen unberührt.
<br /><br />
<strong>27 Akzeptanz</strong>
<br /><br />
27.1 Das Member und/oder VIP-Member bestätigt, diese Allgemeinen Geschäftsbedingungen gelesen und akzeptiert zu haben.
<br /><br />
Diese Allgemeinen Geschäftsbedingungen wurde nzuletzt aktualisiert am: 26.02.2013
</p>


<div style="display:block; width:555px; margin-bottom:3px;  height:30px; margin-top:5px; background-color: rgba(0, 0, 0, 0.5); -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; float:left; padding:10px;">
<div style="float:left;">
<input type="checkbox" name="accept" id="accept" value="1" onclick="checkAcept(this);" style="float:left;"/><span style="display:block; width:500px; float:left; margin-left:5px; line-height:1.3em;">{#AGB_accept_txt#}</span>
</div>
<div id="accept_info"></div>
</div>


</div>

<!--<div class="register-box-tr">
<div style="display:block; width:800px; margin-bottom:3px; float:left; height:20px; margin-top:5px;">
<div style="float:left; margin-left:117px;">
<input type="checkbox" name="accept" id="accept" value="1" onclick="checkAcept(this);" style="float:left;"/><span style="display:block; width:500px; float:left; margin-left:5px; line-height:1.3em;">{#AGB_accept_txt#}</span>
</div>
<div id="accept_info"></div>
</div>
</div> -->

<div style="margin-left:10px; margin-bottom:10px;">
<input type="hidden" name="submit_form" value="1"/>
<label class="text"></label>
<a href="javascript: void(0)" {if $smarty.cookies.flirt48_activated neq ""} onclick="javascript:alert('{#useraccount_activated#}');" {else} onclick="if(checkNullSignup1()) $('register_form').submit();" {/if} class="btn-red" style="width:305px;">{#Register#}</a>

<div style="margin-left:100px;"><a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="register-facebook"><span>register-facebook</span></a></div>
<br class="clear" />
</div> 

</form>