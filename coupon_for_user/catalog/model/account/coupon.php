<?php
class ModelAccountCoupon extends Model {
	public function getcoupons($data = array()) {

		$sql = "SELECT cp.*,cp_h.coupon_history_id FROM `" . DB_PREFIX . "coupon` cp left join `" . DB_PREFIX . "coupon_history` cp_h ON cp.coupon_id = cp_h.coupon_id and cp_h.customer_id = '". (int)$this->customer->getId() ."' where (left(cp.user,1) = 'A' or cp.user = 'G". (int)$this->customer->getGroupId() ."' OR (left(cp.user,1) = 'C' and cp.user like '%|". (int)$this->customer->getId() ."|%')) and cp.see = 1 and cp.date_end >= '".date('Y-m-d',time())."'";
		$sort_data = array(
			'type',
			'discount',
			'date_end',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalcoupons() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "coupon` cp WHERE (left(cp.user,1) = 'A' or cp.user = 'G". (int)$this->customer->getGroupId() ."' OR (left(cp.user,1) = 'C' and cp.user like '%|". (int)$this->customer->getId() ."|%')) and cp.see = 1 and date_end >= '".date('Y-m-d',time())."'");

		return $query->row['total'];
	}

}

?>