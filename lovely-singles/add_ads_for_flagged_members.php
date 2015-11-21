<?php
$start = microtime();
require_once('classes/top.class.php');

$list = DBConnect::assoc_query_2D("SELECT id,signup_datetime FROM member WHERE signup_datetime='0000-00-00 00:00:00'");
foreach($list as $member)
{
	$date=date("Y-m-d H:i:s",rand(time()-(3*30*24*60*60),time()-(5*24*60*60)));
	DBConnect::execute("UPDATE member SET signup_datetime='".$date."' WHERE id=".$member['id']);
}
exit;

$list = DBConnect::assoc_query_2D("SELECT id,username,description,gender,signup_datetime FROM member WHERE country=24");
echo "<pre>";
foreach($list as $member)
{
	if($member['gender'] == 1)
		$sex = 2;
	else
		$sex = 1;

	$category = 4;
	/*$subject_arr = array(
				"Gibt es dich da draussen?",
				"Suche mehr als einen Flirt!",
				"Wo bist du?",
				"Deckel zum Topf?",
				"Einsamkeit kann weh tun...",
				"Suche vielleicht genau dich...",
				"Erster Versuch",
				"Hallo ihr da draussen",
				"Gibt es noch Liebe?",
				"Suche Freundschaft und mehr",
				"I miss you",
				"Love is in the air",
				"Herzblatt gesucht...",
				"Kannst du mich glücklich machen?",
				"Schluss mit allein sein...",
				"Suche Glück, biete Liebe...",
				"Ehrlichkeit und Leidenschaft...",
				"Entzünde meine Flamme...",
				"Ich suche.. genau dich?!",
				"Sei mein Junimond...",
				"Liebesturteln im Sommer...",
				"Verzauber mich...",
				"Pferd sucht Sattel",
				"Partner fürs Leben?",
				"Topf sucht seinen Deckel...",
				"Feuer sucht Flamme",
				"Kannst du mich entflammen?",
				"Rendevouz ala carte...",
				"Umarmung in langen Nächten gesucht...",
				"Mach dem allein sein ein Ende",
				"Zu zweit in den Sonnenuntergang...",
				"Liebe für mehr als einen Sommer?",
				"Sonnenschein gesucht...",
				"Vielleicht schwierig... aber lieb!",
				"Dickkopf mit Verwöhncharakter",
				"Liege sucht Decke...",
				"Nie mehr Urlaub alleine...",
				"Einsame Nächte beenden...",
				"Schluss mit der Einsamkeit...",
				"Ich will die Suche einfach nicht aufgeben...",
				"Dinner for two?"
				);*/
	$subject_arr = array(
				"Are you out there?",
				"Looking for more then a flirt",
				"Where are you?",
				"Lokking for someone, who fits to me",
				"Loneliness can hurt ...",
				"Maybe i am looking exactly for you ...",
				"First try",
				"Hello out there",
				"Is there still love?",
				"Looking for friendship and maybe more",
				"I miss you",
				"Love is in the air",
				"Looking for my soulmate ...",
				"Will you be able to make me lucky?",
				"Let`s end up to be alone ...",
				"Looking for happiness, offer love ...",
				"Honesty and passion ...",
				"Light my fire ...",
				"Looking for ... you?!",
				"Be my mate ...",
				"Summer love ...",
				"Enchant me ...",
				"Horse looking for saddle",
				"Companions for life?",
				"Fire looking for flame ...",
				"Will you enlight me?",
				"Rendezvous a la carte ...",
				"Looking for love in lonely nights ...",
				"Let`s stop being alone ...",
				"Together into the sunset ...",
				"Love lasting longer then a summer?",
				"Looking for my sweetheart ...",
				"Maybe dufficult ... but sweet!",
				"Pampering bullhead ...",
				"Couch looking for ceiling",
				"No more lonely holidays ...",
				"No more lonely nights ...",
				"I don`t want to be alone anymore ...",
				"I can`t stop looking for my soulmate ...",
				"Dinner for two?"
				);
	$subject = $subject_arr[mt_rand(0,count($subject_arr)-1)];
	if($member['description'] != '')
		$description = $member['description'];
	else
	{
		echo "Ignored [{$member['username']}]\r\n";
		continue;
	}

	$interval = 0;//mt_rand(0,120000);
	$date = $member['signup_datetime'];
	if($date == "0000-00-00 00:00:00")
		$date = date("Y-m-d H:i:s");
	$sql = "INSERT INTO lonely_heart_ads (`id`, `userid`, `target`, `category`, `headline`, `text`, `admin`, `datetime`) VALUES (NULL, '{$member['id']}', '{$sex}', '{$category}', '{$subject}', '{$description}', '0', '".$date."');";

	echo "Add [{$member['username']}] => $subject : $description\r\n";
	DBConnect::execute($sql);
}

echo "\r\nTime used : ".(microtime()-$start)." seconds</pre>";
?>