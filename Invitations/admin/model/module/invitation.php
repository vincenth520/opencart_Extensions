<?php
class ModelModuleInvitation extends Model {
	public function getinvitation($page)
	{
		$limit = ($page-1)*20;
		$query = $this->db->query("SELECT oc1.firstname as inviter,oc2.firstname as invitee,oi.add_time FROM " . DB_PREFIX . "invitation oi left join " . DB_PREFIX . "customer oc1 on oi.uid=oc1.customer_id  left join " . DB_PREFIX . "customer oc2 on  oi.ivid=oc2.customer_id order by oi.add_time desc limit $limit,20");

		$invitation_data = $query->rows;

		return $invitation_data;
	}


	public function getTotalinvitation() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invitation");

		return $query->row['total'];
	}

}