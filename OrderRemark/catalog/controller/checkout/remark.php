<?php
class ControllerCheckoutRemark extends Controller {

	public function index()
	{
		$sql = 'show tables like "' . DB_PREFIX . 'order_remark"';
		$query = $this->db->query($sql);
		if (!$query->row) {
			$this->install();
		}
		if(!isset($this->session->data['order_id'])){
			exit('no order');
		}
		if(!isset($this->request->post['shortage'])){
			exit('no shortage');
		}
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_remark where order_id = '". $this->session->data['order_id'] ."'");
		if(!$query->row){
			$sql = "INSERT INTO " . DB_PREFIX . "order_remark (order_id,shortage,shipment,remark) values('" . $this->session->data['order_id'] . "','" . $this->request->post['shortage'] . "','" . $this->request->post['shipment'] . "','" . $this->request->post['remark'] . "')";
		}else{
			$sql = "UPDATE " . DB_PREFIX . "order_remark SET order_id = '" . $this->session->data['order_id'] . "',shortage = '" . $this->request->post['shortage'] . "',shipment = '" . $this->request->post['shipment'] . "',remark = '" . $this->request->post['remark'] . "' where order_id = '". $this->session->data['order_id'] ."'";
		}
		if($this->db->query($sql)){
			exit('ok');
		}
	}

	public function install()
	{
		$sql = 'CREATE TABLE ' . DB_PREFIX . 'order_remark(id int auto_increment primary key,order_id int,shortage varchar(50),shipment tinyint default 0,remark text)';
		$this->db->query($sql);
	}
}