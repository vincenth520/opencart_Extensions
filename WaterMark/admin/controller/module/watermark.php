<?php
class ControllerModuleWatermark extends Controller {
	private $error = array(); 
	
	public function index() {   
		
		$this->load->model('module/watermark');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$success = false;
			
			if(isset($this->request->post['action']))
			{
				
				if($this->request->post['action']== 'store')
				{
					$success = ($this->model_module_watermark->updateWatermarkStatus($this->request->post['watermark_status'])) && ($this->model_module_watermark->updateApplySetting($this->request->post['store_watermark']));
					
				} 
			}
			
			if($success)
			{
			
				$this->model_module_watermark->clearCache(DIR_IMAGE . 'cache');	
						
				$this->session->data['success'] = 'Success: You have modified Watermark Settings!';
							
				$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
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

		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => 'Modules',
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => 'Watermark Modification',
			'href'      => $this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
				
		$data['token'] = $this->session->data['token'];
		
		
		if (isset($this->request->post['watermark_status'])) {
			$data['watermark_status'] = $this->request->post['watermark_status'];
		} else {
			$data['watermark_status'] = $this->config->get('watermark_status');
		}	
		
		if(isset($this->request->post['store_watermark']))
			$data['storeSetting'] = $this->request->post['store_watermark'];
		else
			$data['storeSetting'] = $this->model_module_watermark->getApplySetting();
		
		$this->load->model('tool/image');

		if (isset($data['storeSetting'][0]['image']) && is_file(DIR_IMAGE . $data['storeSetting'][0]['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($data['storeSetting'][0]['image'], 100, 100);
		} elseif ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
			$data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['stores'] = array();
		
		$data['stores'][] = array(
			'store_id'			=> 0,
			'name'     			=> $this->config->get('config_name') . $this->language->get('text_default')
		);	
			
		$this->load->model('setting/store');
		
		$results = $this->model_setting_store->getStores();
		foreach($results as $result)
			$data['stores'][] = array(
				'store_id' 			=> $result['store_id'],
				'name'				=> $result['name']
			);
		
		
    	$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
    	$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('module/watermark.tpl', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/watermark')) {
			$this->error['warning'] = "Warning: You do not have permission to modify this module!";
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>