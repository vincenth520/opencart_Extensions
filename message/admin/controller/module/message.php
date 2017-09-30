<?php
class ControllerModuleMessage extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/message');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');


		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/message', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->load->model('module/message');

		$page = isset($this->request->get['page'])?$this->request->get['page']:1;

		$data['message_list'] = $this->model_module_message->getMessages($page);
		$total = $this->model_module_message->getMessagestation();
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$page_count = 20;
		$pagination->limit = $page_count;
		$pagination->url = $this->url->link('module/message', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $page_count) + 1 : 0, ((($page - 1) * $page_count) > ($total - $page_count)) ? $total : ((($page - 1) * $page_count) + $page_count), $total, ceil($total / $page_count));	
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['add'] = $this->url->link('module/message/add', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$customers = $this->model_customer_customer->getCustomers();
		foreach ($customers as $key => $value) {
			$newCustomers[$value['customer_id']] = $value['firstname'];
		}
		$data['customers'] = $newCustomers;
		$this->response->setOutput($this->load->view('module/message.tpl', $data));
	}

	public function add()
	{
		$this->load->language('module/message');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_default'] = $this->language->get('text_default');
		$data['text_customer_all'] = $this->language->get('text_customer_all');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_customer_group'] = $this->language->get('text_customer_group');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_to'] = $this->language->get('entry_to');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_message'] = $this->language->get('entry_message');

		$data['help_customer'] = $this->language->get('help_customer');

		$data['button_send'] = $this->language->get('button_send');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/message', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => 'add message',
			'href' => $this->url->link('module/message/add', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['cancel'] = $this->url->link('module/message', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if(isset($this->request->get['id'])){
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getCustomer($this->request->get['id']);
			$data['touser'] = $customer_info;
			
			if($customer_info){
				$this->load->model('module/message');
				
				$data['chat_list'] = $this->model_module_message->getMessageItem($customer_info['customer_id'],0);
			}
		}

		$this->response->setOutput($this->load->view('module/message_add.tpl', $data));
	}

	public function send() {
		$this->load->language('module/message');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if (!$this->request->post['message']) {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if (!$json) {

				$this->load->model('customer/customer');

				$this->load->model('customer/customer_group');

				switch ($this->request->post['to']) {

					case 'customer_all':

						$email_total = $this->model_customer_customer->getTotalCustomers();

						$results = $this->model_customer_customer->getCustomers();

						foreach ($results as $result) {
							$Received[] = $result['customer_id'];
						}
						break;
					case 'customer_group':
						$customer_data = array(
							'filter_customer_group_id' => $this->request->post['customer_group_id']
						);

						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);

						$results = $this->model_customer_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$Received[] = $result['customer_id'];
						}
						break;
					case 'customer':
						if (!empty($this->request->post['customer'])) {
							foreach ($this->request->post['customer'] as $customer_id) {
								$customer_info = $this->model_customer_customer->getCustomer($customer_id);
								if ($customer_info) {
									$Received[] = $customer_info['customer_id'];
								}
							}
						}
						break;
				}
				if ($Received) {

					$message = $this->request->post['message'];

					$this->load->model('module/message');

					$MessageTextId = $this->model_module_message->insertMessageText(0, $message);

					foreach ($Received as $v) {
						$this->model_module_message->insertMessage(0, $v, $MessageTextId);
					}

					$json['success'] = $this->language->get('text_success');
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	public function install()
	{
		$sql = "CREATE TABLE " . DB_PREFIX . "message (id bigint primary key auto_increment,SenderId int,ReceiverId int,SendTime datetime,ReadFlag tinyint default 0,MessageTextId bigint)";
		$this->db->query($sql);

		$sql = "CREATE TABLE " . DB_PREFIX . "messageText (id bigint primary key auto_increment,SenderId int,MessageText text)";
		$this->db->query($sql);
	}

	public function uninstall()
	{
		$sql = "DROP TABLE " . DB_PREFIX . "message";
		$this->db->query($sql);
		$sql = "DROP TABLE " . DB_PREFIX . "messageText";
		$this->db->query($sql);
	}
}