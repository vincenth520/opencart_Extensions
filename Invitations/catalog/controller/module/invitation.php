<?php
class Controllermoduleinvitation extends Controller {
	public function index() {
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('module/invitation', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('module/invitation');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_empty'] = $this->language->get('text_empty');
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => 'invitation',
			'href' => $this->url->link('module/invitation')
		);

		$this->load->model('module/invitation');
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
		$data['column_inviter'] = $this->language->get('column_inviter');
		$data['column_invitee'] = $this->language->get('column_invitee');
		$data['column_time'] = $this->language->get('column_time');
		$data['invitation_link'] = $this->url->link('/','','SSL').'?invite='.$this->customer->getid();
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/invitation.tpl')) {
			return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/invitation.tpl', $data));
		} else {
			return $this->response->setOutput($this->load->view('default/template/module/invitation.tpl', $data));
		}
	}
}