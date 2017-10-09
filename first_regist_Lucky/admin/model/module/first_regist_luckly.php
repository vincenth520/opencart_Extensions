<?php
class ModelModuleFirstRegistLuckly extends Model {

	public function getLuckly($id = '') {
		if ($id == '') {
			$query = $this->db->query("select * from ". DB_PREFIX . "first_regist_luckly_list");

			$luckly_data = $query->rows;
		}else{
			$query = $this->db->query("select * from ". DB_PREFIX . "first_regist_luckly_list where id = '".$id."'");

			$luckly_data = $query->row;			
		}
		

		return $luckly_data;
	}

	public function getConfig() {
		$query = $this->db->query("select * from ". DB_PREFIX . "first_regist_luckly_config");

		$luckly_data = $query->row;

		return $luckly_data;
	}

	public function saveConfig($data)
	{
		$sql = "update ". DB_PREFIX . "first_regist_luckly_config set ";
		foreach ($data as $key => $value) {
			$sql .= $key.'="'.$value.'",';
		}
		$sql = substr($sql,0,-1);

		return $this->db->query($sql);
	}

	public function saveLuckly($data)
	{
		$id = $data['id'];
		unset($data['id']);
		$sql = "update ". DB_PREFIX . "first_regist_luckly_list set ";
		foreach ($data as $key => $value) {
			$sql .= $key.'="'.$value.'",';
		}
		$sql = substr($sql,0,-1);

		$sql .= ' where id = "'.$id.'"';

		return $this->db->query($sql);
	}
}