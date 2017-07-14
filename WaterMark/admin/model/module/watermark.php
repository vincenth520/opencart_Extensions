<?php 
class ModelModuleWatermark extends Model {
	
	public function __construct($register) {
		parent::__construct($register);
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'watermark_settings'");
		
		if(! $query->num_rows)
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = 'watermark', `key` = 'watermark_settings', `value` = '" . $this->db->escape(serialize(array())) . "', serialized = '1'");
			
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'watermark_status'");
		
		if(! $query->num_rows)
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = 'watermark', `key` = 'watermark_status', `value` = '0', serialized = '0'");
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'watermark_apply'");
		
		if(! $query->num_rows)
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = 'watermark', `key` = 'watermark_apply', `value` = '" . $this->db->escape(serialize(array())) . "', serialized = '1'");
		
	}
	
	public function updateApplySetting($data)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(serialize($data)) . "' WHERE  `key` = 'watermark_apply'");	
		
		return true;
	}
	
	public function getApplySetting()
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'watermark_apply'");
		
		if($query->num_rows == 1)		
			return unserialize($query->row['value']);
		
		
		return false;
	}
	
	
	public function updateWatermarkStatus($data){
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($data) . "' WHERE  `key` = 'watermark_status'");	
		
		return true;
	}

	
	public function clearCache($path) {
	
		if (empty($path)) return false;
		
		$files = scandir($path);
		
		foreach ($files as $file) {
		
			if (!in_array($file, array('.', '..', 'index.html'))) {
			
				if (is_file($path.'/'.$file)) unlink ($path.'/'.$file);
				
				if (is_dir($path.'/'.$file)) {
					$this->clearCache($path.'/'.$file);	
					rmdir($path.'/'.$file);
				}
			}
		}
		
	}
}
?>