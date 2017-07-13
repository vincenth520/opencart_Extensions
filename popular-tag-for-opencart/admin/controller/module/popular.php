<?php
class ControllerModulePopular extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('module/popular');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/popular');		
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_keywords'] = $this->language->get('column_keywords');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['add'] = $this->url->link('module/popular/add', 'token=' . $this->session->data['token'], 'SSL');

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
			'href' => $this->url->link('module/popular', 'token=' . $this->session->data['token'], 'SSL')
		);

		$page = isset($this->request->get['page'])?$this->request->get['page']:1;

		$data['populars'] = $this->model_module_popular->getPopular($page);
		$total = $this->model_module_popular->getTotalPopular();
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$page_count = 20;
		$pagination->limit = $page_count;
		$pagination->url = $this->url->link('module/popular', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $page_count) + 1 : 0, ((($page - 1) * $page_count) > ($total - $page_count)) ? $total : ((($page - 1) * $page_count) + $page_count), $total, ceil($total / $page_count));	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['edit_url'] =  $this->url->link('module/popular/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['delete'] =  $this->url->link('module/popular/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->response->setOutput($this->load->view('module/popular.tpl', $data));
	}

	public function add()
	{
		$this->load->language('module/popular');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/popular');

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
			'href' => $this->url->link('module/popular', 'token=' . $this->session->data['token'], 'SSL')
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

		$data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('add_title') : $this->language->get('edit_title');
		
		$data['heading_title'] = $this->language->get('edit_title');

		$data['column_name'] = $this->language->get('column_name');
		
		$data['column_keywords'] = $this->language->get('column_keywords');

		$data['column_description'] = $this->language->get('column_description');


		$data['header'] = $this->load->controller('common/header');

		$data['column_left'] = $this->load->controller('common/column_left');

		$data['footer'] = $this->load->controller('common/footer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if(isset($_FILES['file']) && $_FILES['file']['name'] != ''){
				$extend=pathinfo($_FILES['file']['name']);
				$ext = array('xls','xlsx','csv');
				if(!in_array($extend['extension'], $ext)){
					$data['error_warning'] = 'File format error';
					return $this->response->setOutput($this->load->view('module/popular_add.tpl', $data));
				}
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				chdir( '../system/PHPExcel' );
				require_once( 'Classes/PHPExcel.php' );
				chdir( '../../admin' );
				$filename = $_FILES['file']['tmp_name'];
				$inputFileType = PHPExcel_IOFactory::identify($filename);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objReader->setReadDataOnly(true);
				$reader = $objReader->load($filename);
				$data = $reader->getSheet(0);
				$k = $data->getHighestRow();
				for ($i=0; $i<$k; $i+=1) {
					$j = 1;
					$r[$this->language->get('column_name')] = $this->getCell($data,$i,$j++);
					$r[$this->language->get('column_keywords')] = $this->getCell($data,$i,$j++);
					$r[$this->language->get('column_description')] = $this->getCell($data,$i,$j++);
					//var_dump($r);exit;
					$store_id = $this->model_module_popular->addPopular($r);
				}
				$this->response->redirect($this->url->link('module/popular', 'token=' . $this->session->data['token'], 'SSL'));

				return;
			}
			if ($this->request->post[$this->language->get('column_name')] == '' || $this->request->post[$this->language->get('column_keywords')] == '' || $this->request->post[$this->language->get('column_description')] == '') {
				$data['error_warning'] = 'Please input full';
				return $this->response->setOutput($this->load->view('module/popular_add.tpl', $data));
			}
			if (isset($this->request->post['id'])) {
				$store_id = $this->model_module_popular->editPopular($this->request->post['id'],$this->request->post);
			}else{
				$store_id = $this->model_module_popular->addPopular($this->request->post);
			}			

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('module/popular', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if(isset($this->request->get['id'])){
			$data['popular'] = $this->model_module_popular->getPopularById($this->request->get['id']);			
		}


		$this->response->setOutput($this->load->view('module/popular_add.tpl', $data));
	}

	public function delete() {
		$this->load->language('module/popular');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('module/popular');

		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $store_id) {
				$this->model_module_popular->deletePopular($store_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('module/popular', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	protected function getCell(&$worksheet,$row,$col,$default_val='') {
		$col -= 1; // we use 1-based, PHPExcel uses 0-based column index
		$row += 1; // we use 0-based, PHPExcel used 1-based row index
		return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getValue() : $default_val;
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/popular')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}

	public function install() {
		$this->db->query("CREATE TABLE ". DB_PREFIX . "popular (id int primary key auto_increment,name varchar(100),keywords varchar(500),description text,UNIQUE (name))");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE ". DB_PREFIX . "popular");
	}
}