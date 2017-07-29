<?php
class ControllerTotalCredit extends Controller {
	public function index() {
		if ($this->config->get('credit_status')) {
			$this->load->language('total/credit');

			$data['heading_title'] = 'Use Credit';

			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_credit'] = 'Enter your Credit here';

			$data['button_credit'] = 'Apply Credit';

        	$data['balance'] = $this->customer->getBalance();
			
			if (isset($this->session->data['credit'])) {
				$data['credit'] = $this->session->data['credit'];
			} else {
				$data['credit'] = '';
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/credit.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/total/credit.tpl', $data);
			} else {
				return $this->load->view('default/template/total/credit.tpl', $data);
			}
		}
	}

	public function credit() {
		$this->load->language('total/credit');

		$json = array();

		if (isset($this->request->post['credit'])) {
            $credit = $this->request->post['credit'];
        } else {
            $credit = 0;
        }

        $this->session->data['credit'] = $this->request->post['credit'];


		$this->session->data['credit'] = $this->request->post['credit'];

		$this->session->data['success'] = 'Success: You have successfully used credit!';

		$json['redirect'] = $this->url->link('checkout/cart');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
