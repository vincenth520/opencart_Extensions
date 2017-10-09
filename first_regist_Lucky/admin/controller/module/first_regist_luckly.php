<?php
class ControllerModuleFirstRegistLuckly extends Controller {

	public function index(){

		$this->load->language('module/first_regist_luckly');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/first_regist_luckly', 'token=' . $this->session->data['token'], 'SSL')
		);


		
		$this->load->model('module/first_regist_luckly');		
		
		
		$data['luckly_config'] = $this->model_module_first_regist_luckly->getConfig();
		$data['luckly_data'] = $this->model_module_first_regist_luckly->getLuckly();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['edit_url'] =  $this->url->link('module/first_regist_luckly/edit', 'token=' . $this->session->data['token'], 'SSL');
		$data['save'] =  $this->url->link('module/first_regist_luckly/save', 'token=' . $this->session->data['token'], 'SSL');
		$data['back'] =  $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->response->setOutput($this->load->view('module/first_regist_luckly.tpl', $data));
	}

	public function save(){
		$this->load->language('module/first_regist_luckly');
		$this->load->model('module/first_regist_luckly');
		if($this->model_module_first_regist_luckly->saveConfig($this->request->post)){
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('module/first_regist_luckly', 'token=' . $this->session->data['token'], 'SSL'));
		}else{			
			$this->response->redirect($this->url->link('module/first_regist_luckly', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	public function edit()
	{
		$this->load->language('module/first_regist_luckly');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/first_regist_luckly', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['button_save'] = $this->language->get('button_save');

		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['text_form'] = $this->language->get('edit_title');
		
		$data['heading_title'] = $this->language->get('edit_title');


		$this->load->model('module/first_regist_luckly');		
		
		
		$data['luckly_data'] = $this->model_module_first_regist_luckly->getLuckly($this->request->get['id']);

		$data['header'] = $this->load->controller('common/header');

		$data['column_left'] = $this->load->controller('common/column_left');

		$data['footer'] = $this->load->controller('common/footer');
		
		$data['save'] =  $this->url->link('module/first_regist_luckly/save_luckly', 'token=' . $this->session->data['token'], 'SSL');
		$this->response->setOutput($this->load->view('module/first_regist_luckly_edit.tpl', $data));
	}

	public function save_luckly()
	{
		$this->load->language('module/first_regist_luckly');
		$this->load->model('module/first_regist_luckly');
		if($this->model_module_first_regist_luckly->saveLuckly($this->request->post)){
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('module/first_regist_luckly', 'token=' . $this->session->data['token'], 'SSL'));
		}else{			
			$this->response->redirect($this->url->link('module/first_regist_luckly', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}






	public function install() { 
	  $this->db->query("CREATE TABLE ". DB_PREFIX . "first_regist_luckly_config (title varchar(200),description varchar(500),announcement varchar(500),status tinyint default 0)");

	  $this->db->query("insert into ". DB_PREFIX . "first_regist_luckly_config (title,description,announcement,status) values('Welcome to Sammydress!','All new customers can have a chance to join the lucky draw.','After collecting, coupon will only last for 72 hours, hurry!',0)");
	  $this->db->query("CREATE TABLE ". DB_PREFIX . "first_regist_luckly_list (id int primary key auto_increment,num int,type varchar(40),needamount int,probability int)");
	  $this->db->query("insert into ". DB_PREFIX . "first_regist_luckly_list (num,type,needamount,probability) values(20,'Coupon',0,100)");
	  $this->db->query("insert into ". DB_PREFIX . "first_regist_luckly_list (num,type,needamount,probability) values(30,'Cash',0,10)");
	  $this->db->query("insert into ". DB_PREFIX . "first_regist_luckly_list (num,type,needamount,probability) values(9,'Cash',30,100)");
	  $this->db->query("insert into ". DB_PREFIX . "first_regist_luckly_list (num,type,needamount,probability) values(5,'Cash',20,100)");
	  $this->db->query("insert into ". DB_PREFIX . "first_regist_luckly_list (num,type,needamount,probability) values(15,'Coupon',0,100)");
	  $this->db->query("insert into ". DB_PREFIX . "first_regist_luckly_list (num,type,needamount,probability) values(5,'Cash',0,100)");
	  $this->db->query("insert into ". DB_PREFIX . "first_regist_luckly_list (num,type,needamount,probability) values(10,'Coupon',0,100)");
	  $this->db->query("insert into ". DB_PREFIX . "first_regist_luckly_list (num,type,needamount,probability) values(2,'Coupon',0,100)");
	}


	public function uninstall() {
		$this->db->query("DROP TABLE ". DB_PREFIX . "first_regist_luckly_config");
		$this->db->query("DROP TABLE ". DB_PREFIX . "first_regist_luckly_list");
	}

}

?>