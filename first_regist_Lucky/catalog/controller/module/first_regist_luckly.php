<?php
class ControllerModuleFirstRegistLuckly extends Controller {
	public function index() {

		//如果用户登录过，直接返回空
		if ($this->customer->isLogged()) {
			exit('');
		}

		if(!isset($this->session->data['first_open']) || $this->session->data['first_open'] != 1){


			$this->load->model('module/first_regist_luckly');		
			
			$data['base'] = $this->url->link('/', '', 'SSL');
			$data['luckly_config'] = $this->model_module_first_regist_luckly->getConfig();
			$data['luckly_data'] = $this->model_module_first_regist_luckly->getLuckly();

			$this->session->data['first_open'] = 1;
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/first_regist_luckly.tpl')) {
				return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/first_regist_luckly.tpl', $data));
			} else {
				return $this->response->setOutput($this->load->view('default/template/module/first_regist_luckly.tpl', $data));
			}
		}else{
			exit('');
		}


	}

	public function fun()
	{
		$this->load->model('module/first_regist_luckly');
		$luckly_data = $this->model_module_first_regist_luckly->getLuckly();
		$radom = array();
		foreach ($luckly_data as $key => $value) {
			$luckly[$value['id']] = $value;
			for($i = 0; $i<$value['probability']; $i++){
				$radom[] = $value['id'];
			}
		}

		$result['status'] = 1;
		$result['index'] = $radom[array_rand($radom)];
		$luckly = $luckly[$result['index']];
		if($luckly['type'] == 'Coupon'){
            $luckly['title'] = $luckly['num'].'% Coupon';
            $type = 'P';
        }else{
            if($luckly['needamount'] == 0){
              $luckly['title'] = '$'.$luckly['num'].' Cash';
            }else{
              $luckly['title'] = '$'.$luckly['num'].' off over $'.$luckly['needamount'];
            }
            $type = 'F';
        }
		$result['name'] = $luckly['title'];
		$code = strtoupper(substr(md5(time().rand()),3,10));
		$result['coupon_code'] = $code;
		$this->addCoupon($type,$luckly['num'],$luckly['needamount'],$code);
		$this->session->data['regist_coupon'] = $code;
		echo json_encode($result);
	}

	private function addCoupon($type,$num,$max,$code)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon where user = 'C' and date_start < '" . date('Y-m-d',time()) . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = 'Registration gift', code = '" . $code . "', discount = '" . (float)$num . "', type = '" . $type . "', total = '" . $max . "', logged = 0, shipping = 0, date_start = '" . date('Y-m-d',time()) . "', date_end = '".date('Y-m-d',time()+(72*60*60))."', uses_total = 1, uses_customer = 1, status = 1, date_added = NOW(),user = 'C'");
	}

}

?>