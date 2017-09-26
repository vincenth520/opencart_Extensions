<?php
class ControllerModuleInvitation extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('module/invitation');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/invitation');		
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['column_inviter'] = $this->language->get('column_inviter');
		$data['column_invitee'] = $this->language->get('column_invitee');
		$data['column_time'] = $this->language->get('column_time');

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
			'href' => $this->url->link('module/invitation', 'token=' . $this->session->data['token'], 'SSL')
		);

		$page = isset($this->request->get['page'])?$this->request->get['page']:1;

		$data['invitations'] = $this->model_module_invitation->getinvitation($page);
		$total = $this->model_module_invitation->getTotalinvitation();
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$page_count = 20;
		$pagination->limit = $page_count;
		$pagination->url = $this->url->link('module/invitation', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $page_count) + 1 : 0, ((($page - 1) * $page_count) > ($total - $page_count)) ? $total : ((($page - 1) * $page_count) + $page_count), $total, ceil($total / $page_count));	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('module/invitation.tpl', $data));
	}

	public function install() {
		$this->db->query("CREATE TABLE ". DB_PREFIX . "invitation (id int primary key auto_increment,uid int,ivid int,add_time int)");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE ". DB_PREFIX . "invitation");
	}
}