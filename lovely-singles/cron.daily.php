<?php
require_once('classes/top.class.php');
//if in archive not delete

//delete message inbox, already read 5 days
$sqlInbox5 = "delete from message_inbox where datediff(now(),read_date) > 5 and status = 1 and (archive != 1)";
DBconnect::execute_q($sqlInbox5);

//delete inbox eq or more than 20days
$sqlInbox20 = "delete from message_inbox where datediff(now(),read_date) > 20 and (archive != 1)";
DBconnect::execute_q($sqlInbox20);

//delete outbox
$sqlOutbox = "delete from message_outbox where datediff(now(),datetime) > 5 and (archive != 1)";
DBconnect::execute_q($sqlOutbox);



?>