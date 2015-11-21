<?php 

class dbo_message extends dbo {
	
	public function addMessage($to, $from, $subject, $message, $coins, $gift)
	{
		$this->addToInbox($to, $from, $subject, $message, $coins, $gift);
		$this->addToOutbox($to, $from, $subject, $message, $coins, $gift);
	}
	
	public function addToInbox($to, $from, $subject, $message, $coins, $gift)
	{
		
		$sql = "INSERT INTO message_inbox (to_id, from_id, subject, message, attachment_coins, gift_id, datetime) "
			 . "VALUES (:to, :from, :subject, :message, :coins, :gift, NOW()) ";

		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':to' => $to, ':from' => $from, ':subject' => $subject, ':message' => $message, ':coins' => $coins, ':gift' => $gift));
	}
	
	public function addToOutbox($to, $from, $subject, $message, $coins, $gift)
	{
		$sql = "INSERT INTO message_outbox (to_id, from_id, subject, message, attachment_coins, gift_id, datetime) "
				. "VALUES (:to, :from, :subject, :message, :coins, :gift, NOW()) ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':to' => $to, ':from' => $from, ':subject' => $subject, ':message' => $message, ':coins' => $coins, ':gift' => $gift));
	}
	
	/**
	 * @todo this function needs to be profiled to see the performance. could potentially use something like radis to improve
	 * @param unknown $id
	 * @return multitype:
	 */
	public function getContacts($id)
	{
		$inbox = array(); $idin = array(); $outbox = array(); $idout = array();
		
		$sql = "SELECT from_id AS rcpt, MIN(read_date) AS read_date, MAX(datetime) AS `datetime` FROM message_inbox WHERE to_id = :to_id GROUP BY from_id ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':to_id' => $id));
		
		if ($sth->rowCount() != 0) $inbox = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($inbox as $i) $idin[$i['rcpt']] = array('read_date' => $i['read_date'], 'datetime' => $i['datetime']);
		
		$sql = "SELECT to_id AS rcpt, MAX(datetime) AS `datetime` FROM message_outbox WHERE from_id = :from_id GROUP BY to_id ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':from_id' => $id));
		
		if ($sth->rowCount() != 0) $outbox = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($outbox as $o) $idout[$o['rcpt']] = array('datetime' => $o['datetime']);
		
		$outonly = array_diff(array_keys($idout), array_keys($idin));
		$rows = array();
		$rowsout = array();
		if (count($idin) > 0) {
			$idins = implode(",", array_keys($idin));
			$sql = "SELECT m.username, m.picturepath, m.id, IF(ISNULL(f.child_id), 0, 1) AS isfavorite FROM member AS m LEFT JOIN favorite AS f ON f.parent_id = :parent AND f.child_id = m.id WHERE m.id IN ({$idins})";
			$sth = $this->dbo->prepare($sql);
			$sth->execute(array(':parent' => $id));
			
			if ($sth->rowCount() != 0) {
				$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
				foreach ($rows as &$row) {
					$row['readx'] = 1;
					if (isset($idin[$row['id']]) && $idin[$row['id']]['read_date'] == '0000-00-00 00:00:00') {
						$row['readx'] = 0;
					}
					$row['lastmsg'] = $idin[$row['id']]['datetime'];
				}
			}
		}
		
		if (count($outonly) > 0) {
			$idouts = implode(",", $outonly);
			$sql = "SELECT m.username, m.picturepath, m.id, IF(ISNULL(f.child_id), 0, 1) AS isfavorite, '1' AS readx FROM member AS m LEFT JOIN favorite AS f ON f.parent_id = :parent AND f.child_id = m.id WHERE m.id IN ({$idouts})";
			$sth = $this->dbo->prepare($sql);
			$sth->execute(array(':parent' => $id));
				
			if ($sth->rowCount() != 0) {
				$rowsout = $sth->fetchAll(PDO::FETCH_ASSOC);
				foreach ($rowsout as &$rowout) {
					$rowout['lastmsg'] = $idout[$rowout['id']]['datetime'];
				}
			}
		}
		
		$data = array_merge($rows, $rowsout);
		usort($data, function ($a, $b) {
			return strtotime($b['lastmsg']) - strtotime($a['lastmsg']);
		});
		
		return $data;
	}
	
	public function getNewMessageCount($reciver)
	{
		$sql = "SELECT COUNT(*) AS cnt FROM message_inbox WHERE to_id = :to_id AND read_date = '0000-00-00 00:00:00' ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':to_id' => $reciver));
		
		return $sth->fetch(PDO::FETCH_ASSOC)['cnt'];
	}
	
	
	public function markRead($sender, $receiver)
	{
		$sql = "UPDATE message_inbox SET read_date = NOW() WHERE to_id = :to_id AND from_id = :from_id ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':to_id' => $sender, ':from_id' => $receiver));
	}
	
	public function clearChat($sender, $receiver)
	{
		$sql = "DELETE FROM message_inbox WHERE to_id = :to_id AND from_id = :from_id ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':to_id' => $sender, ':from_id' => $receiver));
		
		$sql = "DELETE FROM message_outbox WHERE from_id = :from_id AND to_id = :to_id ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':from_id' => $sender, ':to_id' => $receiver));
	}
	
	public function getMessageHistory($sender, $receiver)
	{
		$sql = "SELECT 'inbox' as type, from_id AS rcpt, datetime, to_id as send, message, attachment_coins, gift_id FROM message_inbox WHERE to_id = :i_to_id AND from_id = :i_from_id "
			 . " UNION ALL "
			 . "SELECT 'outbox' as type, to_id  AS rcpt, datetime, from_id as send, message, attachment_coins, gift_id FROM message_outbox WHERE from_id = :o_from_id AND to_id = :o_to_id ORDER BY datetime DESC LIMIT 30 ";

		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':i_to_id' => $sender, ':i_from_id' => $receiver, ':o_from_id' => $sender, ':o_to_id' => $receiver));
		
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
}