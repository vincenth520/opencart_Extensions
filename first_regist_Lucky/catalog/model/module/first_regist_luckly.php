<?php
class ModelModuleFirstRegistLuckly extends Model {


	public function getConfig() {
		$query = $this->db->query("select * from ". DB_PREFIX . "first_regist_luckly_config");

		$luckly_data = $query->row;

		return $luckly_data;
	}


	
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


	public function editCoupon($data,$code)
	{
		if($this->db->query("update ". DB_PREFIX . "coupon SET user = '".$data['user']."' where code = '".$code."'")){
			unset($this->session->data['regist_coupon']);
		}
	}

}