<?php
class Controllermodulepopular extends Controller {
	public function index() {
		$this->load->language('module/popular');
		if (isset($this->request->get['letter'])) {
			$letter = $this->request->get['letter'];
		}else{
			$letter = 'A';
		}
		$letter_arr = range('A', 'Z');
		$letter_arr[] = '0_9';

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => 'Popular',
			'href' => $this->url->link('module/popular')
		);

		if(!in_array($letter, $letter_arr)){
			$text_error = 'Page Not Found';
			$this->document->setTitle($text_error);

			$data['breadcrumbs'][] = array(
				'text' => $text_error,
				'href' => $this->url->link('common/home')
			);
			$data['heading_title'] = $text_error;

			$data['text_error'] = $text_error;
			$data['column_left'] = $data['column_right'] = $data['content_bottom'] = $data['content_top'] = false;
			$data['button_continue'] = $this->language->get('button_continue');
			$data['continue'] = $this->url->link('common/home');

			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				return $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('tag_list').' '.$letter,
			'href' => $this->url->link('module/popular','letter='.$letter)
		);

		$this->document->setTitle($this->language->get('tag_list').':'.$letter);
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		$data['letter_arr'] = $letter_arr;
		$data['letter'] = $letter;
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->load->model('module/popular');
		
		$page = isset($this->request->get['page'])?$this->request->get['page']:1;
		$data['populars'] = $this->model_module_popular->getPopular($letter,$page);	

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		$total = $this->model_module_popular->getTotalPopular($letter);
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$page_count = 100;
		$pagination->limit = $page_count;
		$pagination->url = $this->url->link('module/popular', 'token=' . $this->session->data['token'] . '&letter='.$letter.'&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $page_count) + 1 : 0, ((($page - 1) * $page_count) > ($total - $page_count)) ? $total : ((($page - 1) * $page_count) + $page_count), $total, ceil($total / $page_count));	


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/popular.tpl')) {
			return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/popular.tpl', $data));
		} else {
			return $this->response->setOutput($this->load->view('default/template/module/popular.tpl', $data));
		}
	}

	public function item()
	{
		$this->load->model('module/popular');
		$page = isset($this->request->get['page'])?$this->request->get['page']:1;
		$results = $this->model_module_popular->getProductByTagName($this->request->get['name'],$page);
		$this->load->model('tool/image');

		$total = $results['total'];
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$page_count = 50;
		$pagination->limit = $page_count;
		$pagination->url = $this->url->link('module/popular/item', 'token=' . $this->session->data['token'] . '&name='.$this->request->get['name'].'&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $page_count) + 1 : 0, ((($page - 1) * $page_count) > ($total - $page_count)) ? $total : ((($page - 1) * $page_count) + $page_count), $total, ceil($total / $page_count));	

		if($results){
			foreach ($results['list'] as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
		}else{
			$data['products'] = array();
		}
		$this->load->language('product/search');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_search'] = $this->language->get('text_search');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_sub_category'] = $this->language->get('text_sub_category');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_points'] = $this->language->get('text_points');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');

		$data['entry_search'] = $this->language->get('entry_search');
		$data['entry_description'] = $this->language->get('entry_description');

		$data['button_search'] = $this->language->get('button_search');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');

		$data['compare'] = $this->url->link('product/compare');
		$letter_arr = range('A', 'Z');
		$letter_arr[] = '0_9';
		$data['letter_arr'] = $letter_arr;
		$letter = substr($this->request->get['name'], 0,1);
		if(ord($letter) >= 48 && ord($letter) <= 57){
			$letter = '0_9';
		}
		$data['letter'] = $letter;
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => 'Popular',
			'href' => $this->url->link('module/popular')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('tag_list').' '.$letter,
			'href' => $this->url->link('module/popular','letter='.$letter)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->request->get['name'],
			'href' => $this->url->link('module/popular/item','name='.$this->request->get['name'])
		);
		$this->document->setTitle($this->language->get('tag_list').':'.$letter);
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/popular_item.tpl')) {
			return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/popular_item.tpl', $data));
		} else {
			return $this->response->setOutput($this->load->view('default/template/module/popular_item.tpl', $data));
		}
	}
}