<?php


$arr_data= Array
(
    'machine' => 1,
    'site' => 1,
    'profiles' => Array
        (
            '0' => Array
                (
                    'username' => 'monalisa69',
                    'password' => 'thtl19'
                ),

            '1' => Array
                (
                    'username' => 'merri69',
                    'password' => 'thtl19'
                ),

            '2' => Array
                (
                    'username' => 'Saric88',
                    'password' => 'thtl19'
                )

        ),

    'messages' => Array
        (
            '0' => Array
                (
                    'subject' => 'Hello',
                    'message' => 'Hi there!  Somehow, your profile really attracted me! I want to get to know you better, but since I\'m always travelling, I find it hard to send messages via internet. If you don\'t mind, I\'d really want to get to know you better. I\'m mainly on your [dot] hux [dot] de and my nickname is the same there. Please don\'t keep me waiting too long! x'
                ),

            '1' => Array
                (
                    'subject' => 'Hi there!',
                    'message' => 'Wow, you are amazing! I cannot believe it took me all these time to finally find someone who may be my match! I hope you feel the same way too! I\'m a sweet person, looking for someone who could take care of my heart! I certainly hope you could be that person for me. As I\'m mainly on your [dot] hux [dot] de. I was wondering if you don\'t mind joining here, my nickname is the same there. I really hope we could connect! See you! xoxo'
                ),

            '2' => Array
                (
                    'subject' => 'Hi',
                    'message' => 'Hey there good-looking!  Is there any chance we could be friends? I couldn\'t resist myself from typing you this message, I hope you don\'t mind. Maybe we could hang out sometimes on weekends or something. ;) \r\nI\'m off for a special holiday trip for the next 2 weeks leaving tomorrow evening and wanted to keep in touch with you, but personally, I feel, using a computer to send you messages is a bit troublesome. Therefore, the best solution I can find for us, is to send messages for free via phone through this site I found your [dot] hux [dot] de . I have the same username there as well. \r\nReally lookward to seeing you there! x'
                ),

        ),

    'timer' => 120,
    'send-amount' => 10,
    'start' => 18,
    'end' => 18,
    'gender' => 'M'
);
echo "<pre>";
//print_r($arr_data);
echo "</pre>";

foreach ($arr_data as $key=>$result)
{
	if($key=='profiles' or $key=='messages')
	{
		echo "<pre>";
		print_r($result);
		echo "</pre>";
	}else
	{
		echo 'key: '.$key."    val: ".$result."<br>";
	}
}
