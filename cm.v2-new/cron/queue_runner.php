<?php

require_once 'bonus_queue.php';
define('QUEUE_TYPE_BONUS', 1);

function runQueue() {

	$sql = "SELECT * FROM queue WHERE processed IS NULL AND running = 0";
	
	$results = DBconnect::assoc_query_2D($sql);
	
	printf("We have %d in the queue\n", count($results));
	
	foreach ($results as $result) {
		
		$sql = "UPDATE queue SET running = 1 WHERE id = " . $result['id'];
		echo $sql;
		DBConnect::execute($sql);
		
		$_POST = unserialize($result['post']);
		$_GET = unserialize($result['get']);
		$_SESSION = unserialize($result['session']);
		$_SERVER = unserialize($result['server']);
		
		switch ($result['type']) {
			case QUEUE_TYPE_BONUS:
				runBonusQueue();
				break;	
		}
		
		$sql = "UPDATE queue SET running = 0, processed = NOW() WHERE id = " . $result['id'];
		DBConnect::execute($sql);
	}
}