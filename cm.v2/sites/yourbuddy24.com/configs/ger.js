/***************
 *Register Page
 ***************/
var error_txt = 'Es ist ein Fehler aufgetreten!';
var already_txt = 'Bitte die Eingabe prüfen. Dieser Eintrag existiert bereits bei';
var complete_txt = 'Das Mitglied wurde zu deiner Favoritenliste hinzugefügt.';
var Remove_txt = 'Du hast dieses Profil aus deinen Favoriten entfernt.';
var fail_txt = 'Fail!';
var ok_txt = 'Ok!';
var forname_alert = 'Bitte gib deinen Vornamen ein!';
var surname_alert = 'Bitte gib deinen Nachnamen ein!';
var username_alert = 'Bitte den Benutzernamen eingeben.';
var username_invalid_alert = 'Ungültige Benutzernamen';
var usernameLength_alert = 'Der Benutzername muß mindestens 6 Zeichen lang sein';
var password_alert = 'Bitte Passwort eingeben.';
var passwordLength_alert = 'Das Passwort muß mindestens 6 Zeichen lang sein';
var confirmpassword_alert = 'Bitte bestätige das Passwort';
var confirmpasswordMatch_alert = 'Passwort und Passwortbestätigung sind nicht identisch!';
var accept_alert = 'Die AGB`s müssen gelesen und akzeptiert werden. <br/>Anderenfalls ist keine Anmeldung möglich.';
var email_alert = 'Bitte gib deine Email-Adresse an!';
var confirmemail_alert = 'Bitte bestätige deine Email-Adresse!';
var emailForm_alert = 'Bitte trage eine korrekte Email-Adresse ein!';
var confirmemailForm_alert = 'Bitte korrigiere deine Email-Adresse in der Bestätigung!';
var confirmemailMatch_alert = 'Email-Adresse und Email-Bestätigung sind nicht identisch!';
var gender_alert = 'Bitte gib dein Geschlecht an!';
var area_alert = 'Bitte gib deine Postleitzahl an!';
var height_alert = 'Bitte wähle ein Körpergröße aus!';
var weight_alert = 'Bitte wähle ein Gewicht aus!';
var tattos_alert = 'Bitte wähle bei der Option "Tattoos" Ja/Nein aus!';
var smoking_alert = 'Bitte wähle bei der Option "Raucher" Ja/Nein aus!';
var glasses_alert = 'Bitte wähle bei der Option "Brille" Ja/Nein aus!';
var handicapped_alert = 'Bitte wähle bei der Option "Behinderung" Ja/Nein aus!';
var piercings_alert = 'Bitte wähle bei der Option "Piercings" Ja/Nein aus.';
var lookingfor_alert = 'Bitte gib bei Your&rsquo;re an, was du suchst!'; //
var lookmen_alert = 'Bitte wähle bei der Option "Suche nach Männern" Ja/Nein aus.';
var lookwomen_alert = 'Bitte wähle bei der Option "Suche nach Frauen" Ja/Nein aus.';
var lookpairs_alert = 'Bitte wähle bei der Option "Suche nach Paaren" Ja/Nein aus.';
var relationship_alert = 'Bitte wähle bei der Option "Suche nach Beziehung" Ja/Nein aus.';
var onenightstand_alert = 'Bitte wähle bei der Option "One Night Stand" Ja/Nein aus.';
var affair_alert = 'Bitte wähle bei der Option "Affäre" Ja/Nein aus.';
var friendship_alert = 'Bitte wähle bei der Option "Freundschaft" Ja/Nein aus.';
var cybersex_alert = 'Bitte wähle bei der Option "Cybersex" Ja/Nein aus.';
var picture_swapping_alert = 'Bitte wähle bei der Option "Bildertausch" Ja/Nein aus.';
var live_dating_alert = 'Bitte wähle bei der Option "Dating" Ja/Nein aus.';
var role_playing_alert = 'Bitte wähle bei der Option "Rollenspiele" Ja/Nein aus.';
var s_m_alert = 'Bitte wähle bei der Option "SM" Ja/Nein aus.';
var partner_exchange_alert = 'Bitte wähle bei der Option "Partnertausch" Ja/Nein aus.';
var voyeurism_alert = 'Bitte wähle bei der Option "Voyeurismus" Ja/Nein aus.';
var description_alert = 'Bitte gebe eine kurze Beschreibung zu dir selbst ein!';
var subject_alert = 'Bitte gib einen Betreff ein!';
var message_alert = 'Bitte gib eine Nachricht ein!';
var upload_alert = 'Bitte eine Datei auswählen';
var to_alert = 'Bitte gebe den Empfänger deiner Nachricht an!';
var to_username_alert = "Dieser Benutzername ist falsch.";
var headline_alert = 'Bitte eine Überschrift eintragen!';
var text_alert = 'Bitte Text eintragen!';
var confirm_delete_box = 'Soll der Eintrag wirklich gelöscht werden?';
var select_alert = 'Bitte erst auswählen!';
var cannot_del_alert = 'Dieser Eintrag kann nicht gelöscht werden!';
var login_alert = 'Benutzername & Passwort passen nicht zusammen!';
var select_country = 'Nation auswählen';
var select_state = 'Land auswählen';
var select_city = 'Stadt auswählen';
var country_alert = 'Bitte wähle ein Land aus';
var state_alert = 'Bitte wähle ein Bundesland aus';
var city_alert = 'Bitte wähle deinen Wohnort aus';
var mobile_alert = 'Die Handynummer muss eine Länge von 9-14 Ziffern aufweisen';
var mobile_existing = 'Leider ist diese Handynummer bereits im System registriert.';
var mobile_less_than_7_digit = 'Die Handynummer muss mindestens 7 Ziffern enthalten!';

var mobile_format = "Bitte verwenden Sie nur Zahlen für Telefonnummern.";
var can_not_empty = "Du kannst nicht gehen diese leer.";
var duplicate_nickname = "Dieser Nickname ist bereits im Einsatz.";
var duplicate_email = "Diese e-mail ist bereits im Einsatz.";

var mobile_ver_code_alert = "Du erhältst innerhalb weniger Sekunden einen Validierungscode. <br/>Bitte trage diesen in dem unten stehenden Feld ein, um deine Handynummer zu verifizieren!";
/***************
*	SMS
***************/
var text_msg_less = "Die Nachricht enthält weniger als";
var mobile_must_be_digit = "Bitte nur Zahlen eintragen";
var mobile_mus_less_than_8_digit = "Die Handynummer darf nicht mehr als zehn Ziffern enthalten";
var txt_greater_than = "Die Nachricht enthält mehr als";
var age_min_must_less_than_age_max = "Das min. Alter muss kleiner sein als das max. Alter";
var email_ivalid = "Die Email-Adresse enthält unerlaubte Zeichen";
var zipcode_mus_be_digit = "Die Postleitzahl muss aus Ziffern bestehen";
var plz_in_flirtter = "Bitte gib einen Flirttext ein";
var plz_in_zipcode = "Bitte gib eine PLZ ein";
var plz_in_mobile = "Bitte trage deine Handynummer ein";
var plz_in_nickname = "Bitte trage deinen Nickname ein";
var plz_in_pass = "Bitte trage dein Passwort ein";
var plz_in_from = "Please enter receiver and sender.";
var plz_in_reference = "Please enter reference.";
var plz_in_message = "Please enter message.";
var plz_in_age = "Please enter age.";
var plz_in_username = "Bitte trage deinen Benutzernamen ein";
var pass_not_match = "These passwords don't match. Try again?";
var you_must_accept = "Du musst die AGB's akzeptieren";
/****************
*   SEND MESSAGE
****************/
var send_msg_to_alert = "Bitte setzen Sie";
var send_msg_subject_alert = "Bitte geben Sie unterliegen";
var send_msg_sms_alert = "Bitte setzen Nachricht";
/***************
*   NEWMESSAGE
***************/
var newmessage = "Neu"

var favorite_added = "Du hast dieses Profil erfolgreich zu deinen Favoriten hinzu gefügt";