<?php
class ModelModulePopular extends Model {
	public function getProductByTagName($tag,$page)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "popular where name = '".$tag."'");

		$popular_data = $query->row;

		if(empty($popular_data)){
			return false;
		}
		$limit = 50;
		$filter_data = array(
			'filter_tag'          => $popular_data['keywords'],
			'start'               => ($page - 1) * $limit,
			'limit'               => $limit
		);

		$this->load->model('catalog/product');
		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		$results = $this->model_catalog_product->getProducts($filter_data);
		$data['total'] = $product_total;
		$data['list'] = $results;
		return $data;
	}

	public function getPopular($letter,$page) {
		$limit = ($page-1)*100;

		$letter = strtoupper($letter);
		
		if($letter == '0_9'){
			$sql = "SELECT * from oc_popular where ASCII(name) BETWEEN 48 and 57 order by id desc limit $limit,100";
		}else{
			$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "popular where name like '".$letter."%'  order by id desc limit $limit,100";
		}

		$query = $this->db->query($sql);

		$popular_data = $query->rows;

		return $popular_data;
	}

	public function getTotalPopular($letter) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "popular where name like '".$letter."%'");

		return $query->row['total'];
	}
}