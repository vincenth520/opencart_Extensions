<?php
class ControllerproductAdvancedquickorder extends Controller {
	public function index() {

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => 'quickorder',
			'href' => $this->url->link('product/Advancedquickorder')
		);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_right'] = $this->load->controller('common/content_right');
		$data['content_left'] = $this->load->controller('common/content_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/advancedquickorder.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/advancedquickorder.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/advancedquickorder.tpl', $data));
		}
			
	}

	public function getList()
	{
		$product_query = $this->db->query("select p.price,p.image,pd.name,p.sku,p.product_id from " . DB_PREFIX . "product p," . DB_PREFIX . "product_description pd where p.sku like '%".$this->request->post['sku']."%' and pd.product_id = p.product_id");
		$data = array();
		$this->load->model('tool/image');
		foreach($product_query->rows as $k=>$v){
			$data[$k]['name'] = $v['name'];
			$data[$k]['price'] = $v['price'];
			$data[$k]['image'] = $this->model_tool_image->resize($v['image'], 50,50);
			$data[$k]['sku'] = $v['sku'];
			$data[$k]['product_id'] = $v['product_id'];
		}
		echo json_encode($data);
	}

	public function import(){
		if(isset($_FILES['productcsv']) && $_FILES['productcsv']['name'] != ''){
			$extend=pathinfo($_FILES['productcsv']['name']);
			$ext = array('xls','xlsx','csv');
			if(!in_array($extend['extension'], $ext)){
				$data['error_warning'] = 'File format error';
				return $this->response->redirect($this->url->link('product/Advancedquickorder/index', 'SSL'));
			}
			ini_set("memory_limit","512M");
			ini_set("max_execution_time",180);
			chdir( DIR_SYSTEM.'PHPExcel' );
			require_once( 'Classes/PHPExcel.php' );
			chdir( '../../admin' );
			$filename = $_FILES['productcsv']['tmp_name'];
			$inputFileType = PHPExcel_IOFactory::identify($filename);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$reader = $objReader->load($filename);
			$data = $reader->getSheet(0);
			$k = $data->getHighestRow();
			for ($i=0; $i<$k; $i+=1) {
				$j = 1;
				$r['sku'] = $this->getCell($data,$i,$j++);
				$rd['quantity'] = $this->getCell($data,$i,$j++);
				$product_query = $this->db->query("select product_id from " . DB_PREFIX . "product  where sku = '".$r['sku']."'");
				$rd['product_id'] = $product_query->row['product_id'];
				$right_num = 0;
				$error_num = 0;
				if($this->addCart($rd['product_id'],$r['sku'])){
					$right_num++;
				}else{
					$error_num++;
				}
			}
			$data = array();
			$data['right_num'] = $right_num;
			$data['error_num'] = $error_num;

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);
			$data['breadcrumbs'][] = array(
				'text' => 'quickorder',
				'href' => $this->url->link('product/Advancedquickorder')
			);
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_right'] = $this->load->controller('common/content_right');
			$data['content_left'] = $this->load->controller('common/content_left');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/advancedquickorder_import.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/advancedquickorder_import.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/advancedquickorder_import.tpl', $data));
			}

			return;
		}
	}

	protected function addCart($product_id,$quantity){
		$this->load->model('catalog/product');
		$product_info = $this->model_catalog_product->getProduct($product_id);
		if ($product_info) {
			if (isset($quantity) && ((int)$quantity >= $product_info['minimum'])) {
				$quantity = (int)$quantity;
			} else {
				$quantity = $product_info['minimum'] ? $product_info['minimum'] : 1;
			}

			$option = array();

			$json = array();
			$product_options = $this->model_catalog_product->getProductOptions($product_id);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}

			if (isset($this->request->post['recurring_id'])) {
				$recurring_id = $this->request->post['recurring_id'];
			} else {
				$recurring_id = 0;
			}

			$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

			if ($recurrings) {
				$recurring_ids = array();

				foreach ($recurrings as $recurring) {
					$recurring_ids[] = $recurring['recurring_id'];
				}

				if (!in_array($recurring_id, $recurring_ids)) {
					$json['error']['recurring'] = $this->language->get('error_recurring_required');
				}
			}

			if (!$json) {
				$this->cart->add($product_id, $quantity, $option, $recurring_id);
				return true;
			} else {
				return false;
			}
		}
	}

	protected function getCell(&$worksheet,$row,$col,$default_val='') {
		$col -= 1; // we use 1-based, PHPExcel uses 0-based column index
		$row += 1; // we use 0-based, PHPExcel used 1-based row index
		return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getValue() : $default_val;
	}
}