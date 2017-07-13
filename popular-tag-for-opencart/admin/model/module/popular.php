<?php
class ModelModulePopular extends Model {

	public function addPopular($data) {
		$this->load->language('module/popular');
		$this->db->query("INSERT ignore INTO " . DB_PREFIX . "popular SET name = '" . $this->db->escape($data[$this->language->get('column_name')]) . "',keywords = '" . $this->db->escape($data[$this->language->get('column_keywords')]) . "',description = '" . $this->db->escape($data[$this->language->get('column_description')]) . "'");

		$store_id = $this->db->getLastId();

		return $store_id;
	}

	public function getPopularById($id) {	
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "popular where id = ".$id);

		$popular_data = $query->row;

		return $popular_data;
	}

	public function editPopular($store_id, $data) {
		$this->load->language('module/popular');
		$sql = "UPDATE " . DB_PREFIX . "popular SET name = '" . $this->db->escape($data[$this->language->get('column_name')]) . "',keywords = '" . $this->db->escape($data[$this->language->get('column_keywords')]) . "',description = '" . $this->db->escape($data[$this->language->get('column_description')]) . "' WHERE id = '" . (int)$store_id . "'";
		return $this->db->query($sql);
	}	

	public function getPopular($page) {		
		$limit = ($page-1)*20;

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "popular order by name asc limit $limit,20");

		$popular_data = $query->rows;

		return $popular_data;
	}

	public function getTotalPopular() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "popular");

		return $query->row['total'];
	}

	public function deletePopular($store_id)
	{
		return $this->db->query("delete from " . DB_PREFIX . "popular WHERE id = '" . (int)$store_id . "'");
	}
}