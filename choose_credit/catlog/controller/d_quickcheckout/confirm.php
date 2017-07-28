<?php 

class ControllerDQuickcheckoutConfirm extends Controller {

	public function index($config){

        $this->load->model('d_quickcheckout/method');
        $this->load->model('module/d_quickcheckout');
        $this->model_module_d_quickcheckout->logWrite('Controller:: confirm/index');

        if(!$config['general']['compress']){
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/model/confirm.js');
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/view/confirm.js');
        }
        
        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['button_continue'] = $this->language->get('button_continue');

        $data['col'] = $config['account']['guest']['confirm']['column'];
        $data['row'] = $config['account']['guest']['confirm']['row'];
		
        $json['account'] = $this->session->data['account'];
        $json['confirm'] = $this->session->data['confirm'];
        //fix lost data
     //   $json['data'] = $this->session->data;

        $this->load->model('d_quickcheckout/order');
        $json['show_confirm'] = $this->model_d_quickcheckout_order->showConfirm();
        $json['payment_popup'] =  $this->model_d_quickcheckout_method->getPaymentPopup($this->session->data['payment_method']['code']);
        
        
        $data['json'] = json_encode($json);
		if(VERSION >= '2.2.0.0'){
            $template = 'd_quickcheckout/confirm';
        }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_quickcheckout/confirm.tpl')) {
			$template = $this->config->get('config_template') . '/template/d_quickcheckout/confirm.tpl';
		} else {
			$template ='default/template/d_quickcheckout/confirm.tpl';
		}

        return $this->load->view($template, $data);
	}

	public function updateField(){
        $json['confirm'] = $this->session->data['confirm'] = array_merge($this->session->data['confirm'], $this->request->post['confirm']);
        $this->session->data['comment'] = $this->session->data['confirm']['comment'];

        //statistic
        $this->load->model('module/d_quickcheckout');
        $statistic = array(
            'update' => array(
                'confirm' => 1
            )
        );
        $this->model_module_d_quickcheckout->updateStatistic($statistic);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function recreateOrder(){
        $this->load->model('d_quickcheckout/order');
        $this->model_d_quickcheckout_order->recreateOrder();
        return true;
    }

    public function update(){
        $json = array();
        $this->load->model('account/address');
        $this->load->model('module/d_quickcheckout');
        $this->load->model('d_quickcheckout/address');
        $this->load->model('d_quickcheckout/order');

        if(isset($this->session->data['payment_address']) && isset($this->request->post['payment_address'])){
            $this->session->data['payment_address'] = array_replace($this->session->data['payment_address'], $this->request->post['payment_address']);
        }
        if(isset($this->session->data['shipping_address']) && isset($this->request->post['shipping_address'])){
            $this->session->data['shipping_address'] = array_replace($this->session->data['shipping_address'], $this->request->post['shipping_address']);
        }

        if($this->customer->isLogged()){

            if (empty($this->session->data['payment_address']['address_id'])) {
                $json['addresses'] = $this->model_d_quickcheckout_address->getAddresses();
                $json['payment_address']['address_id'] = $this->customer->getAddressId();
                $json['shipping_address']['address_id'] = $this->customer->getAddressId();
            }

            if($this->session->data['payment_address']['address_id'] == 'new'){
                $json['payment_address']['address_id'] = $this->session->data['payment_address']['address_id'] = $this->model_account_address->addAddress($this->session->data['payment_address']);
            }
            if($this->model_d_quickcheckout_address->showShippingAddress()){
                if($this->session->data['payment_address']['shipping_address'] == 1){
                    $json['shipping_address']['address_id'] = $this->session->data['payment_address']['address_id'];
                }else if($this->session->data['shipping_address']['address_id'] == 'new'){
                    $json['shipping_address']['address_id'] = $this->session->data['shipping_address']['address_id'] = $this->model_account_address->addAddress($this->session->data['shipping_address']);
                }
            }
            
        }else{
            
            if($this->session->data['account'] == 'register'){

                $this->load->model('account/customer');
                $showShippingAddress = $this->model_d_quickcheckout_address->showShippingAddress();

                $this->model_account_customer->addCustomer($this->session->data['payment_address']);
  
                if($this->customer->login($this->session->data['payment_address']['email'], $this->session->data['payment_address']['password'])){
                    $this->model_d_quickcheckout_order->updateCartForNewCustomerId();
                    $json['account'] = $this->session->data['account'] = 'logged';

                    if($showShippingAddress){
                        $this->session->data['shipping_address']['address_id'] = $this->model_account_address->addAddress($this->session->data['shipping_address']);
                    }

                    $address = $json['addresses'] = $this->model_d_quickcheckout_address->getAddresses();

                    reset($address);
                    $this->session->data['payment_address']['address_id'] = key($address);

                    $json = $this->load->controller('d_quickcheckout/payment_address/prepare', $json);
                    $json = $this->load->controller('d_quickcheckout/shipping_address/prepare', $json);
                }

                //2.1.0.1 fix
                
            }
        }
        $this->load->model('d_quickcheckout/method');
        if($this->model_d_quickcheckout_method->getPaymentPopup($this->session->data['payment_method']['code'])){
            $json['cofirm_order'] = true;
            $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);
        }
        
        $json['order_id'] = $this->session->data['order_id'] = $this->updateOrder();

        //statistic
        $statistic = array(
            'click' => array(
                'confirm' => 1
            )
        );
        $this->model_module_d_quickcheckout->updateStatistic($statistic);

        $this->session->data['credit'] = 0;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateOrder(){

        $order_data = array();

        $this->load->model('d_quickcheckout/order');

        $order_data['totals'] = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        $total_data = array(
            'totals' => &$order_data['totals'],
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $this->model_d_quickcheckout_order->getTotals($total_data);


        $this->load->language('checkout/checkout');

        $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        $order_data['store_id'] = $this->config->get('config_store_id');
        $order_data['store_name'] = $this->config->get('config_name');

        if ($order_data['store_id']) {
            $order_data['store_url'] = $this->config->get('config_url');
        } else {
            $order_data['store_url'] = HTTP_SERVER;
        }

        if ($this->customer->isLogged()) {
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

            $order_data['customer_id'] = $this->customer->getId();
            $order_data['customer_group_id'] = $customer_info['customer_group_id'];
            $order_data['firstname'] = $customer_info['firstname'];
            $order_data['lastname'] = $customer_info['lastname'];
            $order_data['email'] = $customer_info['email'];
            $order_data['telephone'] = $customer_info['telephone'];
            $order_data['fax'] = $customer_info['fax'];
            if(VERSION >= '2.1.0.1'){
             $order_data['custom_field'] = json_decode($customer_info['custom_field']);
            }else{
              $order_data['custom_field'] = unserialize($customer_info['custom_field']);
            }
        } elseif (isset($this->session->data['guest'])) {
            $order_data['customer_id'] = 0;
            $order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
            $order_data['firstname'] = $this->session->data['guest']['firstname'];
            $order_data['lastname'] = $this->session->data['guest']['lastname'];
            $order_data['email'] = $this->session->data['guest']['email'];
            $order_data['telephone'] = $this->session->data['guest']['telephone'];
            $order_data['fax'] = $this->session->data['guest']['fax'];
            $order_data['custom_field'] = (isset($this->session->data['guest']['custom_field']['account'])) ? $this->session->data['guest']['custom_field']['account'] : array();
        }
		
		if (!$order_data['email']) $order_data['email'] = $this->config->get('config_email');

        $order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
        $order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
        $order_data['payment_company'] = $this->session->data['payment_address']['company'];
        $order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
        $order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
        $order_data['payment_city'] = $this->session->data['payment_address']['city'];
        $order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
        $order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
        $order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
        $order_data['payment_country'] = $this->session->data['payment_address']['country'];
        $order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
        $order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
        $order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']['address']) ? $this->session->data['payment_address']['custom_field']['address'] : array());

        if (isset($this->session->data['payment_method']['title'])) {
            $order_data['payment_method'] = $this->session->data['payment_method']['title'];
        } else {
            $order_data['payment_method'] = '';
        }

        if (isset($this->session->data['payment_method']['code'])) {
            $order_data['payment_code'] = $this->session->data['payment_method']['code'];
        } else {
            $order_data['payment_code'] = '';
        }

        if ($this->cart->hasShipping()) {
            $order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
            $order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
            $order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
            $order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
            $order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
            $order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
            $order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
            $order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
            $order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
            $order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
            $order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
            $order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
            $order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']['address']) ? $this->session->data['shipping_address']['custom_field']['address'] : array());

            if (isset($this->session->data['shipping_method']['title'])) {
                $order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
            } else {
                $order_data['shipping_method'] = '';
            }

            if (isset($this->session->data['shipping_method']['code'])) {
                $order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
            } else {
                $order_data['shipping_code'] = '';
            }
        } else {
            $order_data['shipping_firstname'] = '';
            $order_data['shipping_lastname'] = '';
            $order_data['shipping_company'] = '';
            $order_data['shipping_address_1'] = '';
            $order_data['shipping_address_2'] = '';
            $order_data['shipping_city'] = '';
            $order_data['shipping_postcode'] = '';
            $order_data['shipping_zone'] = '';
            $order_data['shipping_zone_id'] = '';
            $order_data['shipping_country'] = '';
            $order_data['shipping_country_id'] = '';
            $order_data['shipping_address_format'] = '';
            $order_data['shipping_custom_field'] = array();
            $order_data['shipping_method'] = '';
            $order_data['shipping_code'] = '';
        }

        $order_data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                $option_data[] = array(
                    'product_option_id'       => $option['product_option_id'],
                    'product_option_value_id' => $option['product_option_value_id'],
                    'option_id'               => $option['option_id'],
                    'option_value_id'         => $option['option_value_id'],
                    'name'                    => $option['name'],
                    'value'                   => $option['value'],
                    'type'                    => $option['type']
                );
            }

            $order_data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'download'   => $product['download'],
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $product['price'],
                'total'      => $product['total'],
                'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                'reward'     => $product['reward']
            );
        }

        // Gift Voucher
        $order_data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $order_data['vouchers'][] = array(
                    'description'      => $voucher['description'],
                    'code'             => substr(md5(mt_rand()), 0, 10),
                    'to_name'          => $voucher['to_name'],
                    'to_email'         => $voucher['to_email'],
                    'from_name'        => $voucher['from_name'],
                    'from_email'       => $voucher['from_email'],
                    'voucher_theme_id' => $voucher['voucher_theme_id'],
                    'message'          => $voucher['message'],
                    'amount'           => $voucher['amount']
                );
            }
        }



        $order_data['comment'] = $this->session->data['comment'];
        $order_data['total'] = $total;

        if (isset($this->request->cookie['tracking'])) {
            $order_data['tracking'] = $this->request->cookie['tracking'];

            $subtotal = $this->cart->getSubTotal();

            // Affiliate
            $this->load->model('affiliate/affiliate');

            $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

            if ($affiliate_info) {
                $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
                $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
            } else {
                $order_data['affiliate_id'] = 0;
                $order_data['commission'] = 0;
            }

            // Marketing
            $this->load->model('checkout/marketing');

            $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

            if ($marketing_info) {
                $order_data['marketing_id'] = $marketing_info['marketing_id'];
            } else {
                $order_data['marketing_id'] = 0;
            }
        } else {
            $order_data['affiliate_id'] = 0;
            $order_data['commission'] = 0;
            $order_data['marketing_id'] = 0;
            $order_data['tracking'] = '';
        }

        $order_data['language_id'] = $this->config->get('config_language_id');
        $order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
        $order_data['currency_code'] = $this->session->data['currency'];
        $order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
        $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

        if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
        } else {
            $order_data['forwarded_ip'] = '';
        }

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
        } else {
            $order_data['user_agent'] = '';
        }

        if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
            $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
        } else {
            $order_data['accept_language'] = '';
        }
        $this->load->model('d_quickcheckout/order');
        $this->model_module_d_quickcheckout->logWrite('Controller:: confirm/updateOrder for order ='.$this->session->data['order_id'].' with $order_data =' .json_encode($order_data));
        return $this->model_d_quickcheckout_order->updateOrder($this->session->data['order_id'], $order_data);
    }
}