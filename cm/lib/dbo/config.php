<?php 

require_once 'lib/dbo.php';

class dbo_config extends dbo {
	
	public function getConfig($key)
	{
		$sql = "SELECT value FROM config WHERE name = :name ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':name' => $key));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['value'];
	}
	
	public function getCostEmail()
	{
		return $this->getConfig('COIN_EMAIL');
	}
	
	public function getGiftCost($gift_id)
	{
		$sql = "SELECT coins FROM gift WHERE id = :id LIMIT 1";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $gift_id));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['coins'];
	}
	
	public function getGiftPath($gift_id)
	{
		$sql = "SELECT image_path FROM gift WHERE id = :id LIMIT 1";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $gift_id));
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetch()['image_path'];
	}
	
	public function getCountryList()
	{
		$sql = "SELECT id, name_de AS name, country_prefix, country_prefix_hidden FROM xml_countries WHERE status = 1 ORDER BY priority ASC";
		$sth = $this->dbo->prepare($sql);
		$sth->execute();
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetchAll();
	}
	
	public function getSmilies()
	{
		$sql = "SELECT id, text_version, image_path, active FROM emoticon";
		$sth = $this->dbo->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getGift($gift_id)
	{
		$sql = "SELECT image_path FROM gift WHERE id = :id";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $gift_id));
		
		if ($sth->rowCount() == 0) return false;
		
		return $sth->fetch()['image_path'];
	}
	
	public function getStateList($country)
	{		
		$sql = "SELECT id, name_de AS name, parent FROM xml_states WHERE parent=:id AND status = 1 ORDER BY name_de ASC";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $country));
	
		if ($sth->rowCount() === 0) return false;
	
		return $sth->fetchAll();
	}
	
	public function getCityList($state)
	{
		$sql = "SELECT id, name_de AS name, parent, plz FROM xml_cities WHERE parent=:id AND status = 1 ORDER BY name_de ASC";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':id' => $state));
	
		if ($sth->rowCount() === 0) return false;
	
		return $sth->fetchAll();
	}
	
	public function getAllCities()
	{
		$sql = "SELECT id, name_de AS name, parent, plz FROM xml_cities WHERE status = 1 ORDER BY name_de ASC";
		$sth = $this->dbo->prepare($sql);
		$sth->execute();
		
		if ($sth->rowCount() === 0) return false;
		
		return $sth->fetchAll();
	}
}