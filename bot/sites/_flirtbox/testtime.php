<?php

echo "seconds : ".date('s');

echo "<br>";
for($i=1; $i<=20; $i++)
{
	if($i>=10)
	{
		sleep(2);
	}
	echo "this is second $i : ".date('s')."<br>";
}
