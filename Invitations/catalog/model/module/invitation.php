<?php
class ModelModuleInvitation extends Model {
	public function getinvitation($page)
	{
		$limit = ($page-1)*20;
		$query = $this->db->query("SELECT oc2.firstname as invitee,oi.add_time FROM " . DB_PREFIX . "invitation oi  left join " . DB_PREFIX . "customer oc2 on  oi.ivid=oc2.customer_id where oi.uid = '".$this->customer->getid()."' order by oi.add_time desc limit $limit,20");

		$invitation_data = $query->rows;

		return $invitation_data;
	}


	public function getTotalinvitation() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invitation where uid = '".$this->customer->getid()."'");

		return $query->row['total'];
	}


	public function addInvitation($data)
	{
		$this->db->query("insert into " . DB_PREFIX . "invitation set uid = '".$data['uid']."',ivid = '".$data['ivid']."',add_time = '".time()."'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward set customer_id = '".$data['uid']."',order_id = '0',date_added = '".date('Y-m-d H:i:s',time())."',points = 50,description = 'Invite user(id:".$data['ivid'].")'");
	}

}