<?php
class ControllerAccountCoupon extends Controller {
	public function index()
	{
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/coupon', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/coupon');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_coupon'),
			'href' => $this->url->link('account/coupon', '', 'SSL')
		);

		$this->load->model('account/coupon');

		$data['heading_title'] = $this->language->get('heading_title');


		$data['text_total'] = $this->language->get('text_total');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['coupons'] = array();

		$filter_data = array(
			'sort'  => 'date_end',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);

		$data['total'] = $coupon_total = $this->model_account_coupon->getTotalcoupons();

		$results = $this->model_account_coupon->getcoupons($filter_data);

		foreach ($results as $result) {
			$result['discount'] = (int)$result['discount'];
			$data['coupons'][] = array(
				'code'    => $result['code'],
				'discount'      => ($result['type'] == 'F')?('$'.(floor($result['discount']*100))/100):(((floor($result['discount']*100))/100).'%'),
				'Conditions' => ((int)$result['total'] == 0)?'No limit':'off over $'.((int)($result['total']*100))/100,
				'date_end'  => $result['date_end'],
				'status'        => (!$result['coupon_history_id'])?'unused':'used'
			);
		}

		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/coupon', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($coupon_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($coupon_total - 10)) ? $coupon_total : ((($page - 1) * 10) + 10), $coupon_total, ceil($coupon_total / 10));


		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/coupon.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/coupon.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/coupon.tpl', $data));
		}
	}
}

?>