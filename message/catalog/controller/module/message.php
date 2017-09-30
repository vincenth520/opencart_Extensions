<?php
class ControllerModuleMessage extends Controller {
	private $error = array();

	public function index()
	{
		$this->load->language('module/message');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_send'] = $this->language->get('button_send');


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/message', '', 'SSL')
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->load->model('module/message');
		$data['send_link'] = $this->url->link('module/message/send');
		$data['touser']['customer_id'] = $this->customer->getId();
		$data['touser']['firstname'] = $this->customer->getFirstName();
		$data['chat_list'] = $this->model_module_message->getMessageItem(0,$this->customer->getId());
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/message.tpl')) {
			echo $this->load->view($this->config->get('config_template') . '/template/module/message.tpl', $data);
		} else {
			echo $this->load->view('default/template/module/message.tpl', $data);
		}
	}

	public function send() {
		$this->load->language('module/message');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if (!$this->request->post['message'] || $this->request->post['message'] == '') {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if (!$json) {
				
				$message = $this->request->post['message'];

				$this->load->model('module/message');

				$MessageTextId = $this->model_module_message->insertMessageText($this->customer->getId(), $message);

				$this->model_module_message->insertMessage($this->customer->getId(), 0, $MessageTextId);

				$json['success'] = $this->language->get('text_success');

			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}