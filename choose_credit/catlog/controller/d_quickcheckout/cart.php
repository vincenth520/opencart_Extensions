<?php 

class ControllerDQuickcheckoutCart extends Controller {
   	
	public function index($config){
        $this->load->model('module/d_quickcheckout');
        $this->model_module_d_quickcheckout->logWrite('Controller:: cart/index');

        $this->load->language('checkout/cart');
        if(VERSION >= '2.1.0.1'){
            $this->load->language('total/coupon');
        }else{
            $this->load->language('checkout/coupon');
        }
        
        if(!$config['general']['compress']){
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/model/cart.js');
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/view/cart.js');
        }
        
        $data['col'] = $config['account']['guest']['cart']['column'];
        $data['row'] = $config['account']['guest']['cart']['row'];

        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_name'); 
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity'); 
        $data['column_price'] = $this->language->get('column_price'); 
        $data['column_total'] = $this->language->get('column_total');
        $data['text_recurring_item'] = $this->language->get('text_recurring_item');
        
        if(VERSION >= '2.1.0.1'){
            $this->load->language('total/coupon');
        }else{
            $this->load->language('checkout/coupon');
        }
        $data['text_use_coupon'] = $this->language->get('heading_title');
        
        if(VERSION >= '2.1.0.1'){
            $this->load->language('total/voucher');
        }else{
            $this->load->language('checkout/voucher');
        }
        $data['text_use_voucher'] = $this->language->get('heading_title');
            

        //reward
        $points = $this->customer->getRewardPoints();
        $points_total = 0;
        foreach ($this->cart->getProducts() as $product) {
            if ($product['points']) {
                $points_total += $product['points'];
            }
        }

        if ($points && $points_total && $this->config->get('reward_status')) {
            $data['reward_points'] = true;
           
            if(VERSION >= '2.1.0.1'){
                 $this->load->language('total/reward');
            }else{
                $this->load->language('checkout/reward');
            }
            $data['text_use_reward'] = sprintf($this->language->get('heading_title'), $points);
            $data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);
        }else{
            $data['reward_points'] = false;
        }

        $json = array();
        $json['error'] = '';
        $json['errors'] = array();
        $json['successes'] = array();

        $json['account'] = $this->session->data['account'];
        
        $json = $this->prepare($json);

        $json['coupon'] = (isset($this->session->data['coupon'])) ? $this->session->data['coupon'] : '';
        $json['voucher'] = (isset($this->session->data['voucher'])) ? $this->session->data['voucher'] : '';
        $json['reward'] = (isset($this->session->data['reward'])) ? $this->session->data['reward'] : '';

        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $json['totals'] = $this->session->data['totals'] = $this->model_d_quickcheckout_order->getTotals($total_data);
        $json['error'] = $json['cart_error'];
 
        $data['json'] = json_encode($json);
        $data['balance'] = $this->customer->getBalance();
		if(VERSION >= '2.2.0.0'){
            $template = 'd_quickcheckout/cart';
        }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_quickcheckout/cart.tpl')) {
			$template = $this->config->get('config_template') . '/template/d_quickcheckout/cart.tpl';
		} else {
			$template = 'default/template/d_quickcheckout/cart.tpl';
		}

        return $this->load->view($template, $data);

	}

    public function prepare($json){
        $this->load->language('checkout/cart');
        $this->load->model('tool/upload');

        $json['show_price'] = $this->model_d_quickcheckout_order->showPrice();

        if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
            $data['error_warning'] = $this->language->get('error_stock');
        } else {
            $data['error_warning'] = '';
        }

        if ($this->cart->getTotal() < $this->session->data['d_quickcheckout']['general']['min_order']['value']) {
            $data['error_warning'] = sprintf($this->session->data['d_quickcheckout']['general']['min_order']['text'], $this->session->data['d_quickcheckout']['general']['min_order']['value']);
        } 

        if ($this->cart->countProducts() < $this->session->data['d_quickcheckout']['general']['min_quantity']['value']) {
            $data['error_warning'] = sprintf($this->session->data['d_quickcheckout']['general']['min_quantity']['text'], $this->session->data['d_quickcheckout']['general']['min_quantity']['value']);
        }
 
        if(VERSION < '2.1.0.1'){
            foreach($this->session->data['cart'] as $key => $value){
                $this->cart->update($key, $value);
            }
        }
        

        $products = $this->cart->getProducts();
        $this->load->model('tool/image');
        $json['products'] = array();
        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
            }

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $this->session->data['d_quickcheckout']['design']['cart_image_size']['width'], $this->session->data['d_quickcheckout']['design']['cart_image_size']['height']);
            } else {
                $image = '';
            }

            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    
                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']);
            } else {
                $price = false;
            }

            // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'],$this->session->data['currency']);
            } else {
                $total = false;
            }

            $recurring = '';

            if ($product['recurring']) {
                $frequencies = array(
                    'day'        => $this->language->get('text_day'),
                    'week'       => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month'      => $this->language->get('text_month'),
                    'year'       => $this->language->get('text_year'),
                );

                if ($product['recurring']['trial']) {
                    $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                }

                if ($product['recurring']['duration']) {
                    $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                } else {
                    $recurring .= sprintf($this->language->get('text_payment_until_canceled_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                }
            }

            $json['products'][] = array(
                'key'       => (isset($product['cart_id'])) ? $product['cart_id'] : $product['key'],
                'image'     => $image,
                'thumb'     => $image,
                'name'      => $product['name'],
                'model'     => $product['model'],
                'option'    => $option_data,
                'recurring' => $recurring,
                'quantity'  => $product['quantity'],
                'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'price'     => $price,
                'total'     => $total,
                'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
            );
            // fix for 2.1.0.0
            $json['cart'][(isset($product['cart_id'])) ? $product['cart_id'] : $product['key']] = $product['quantity'];
        
        }

        // Gift Voucher
        
        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $json['vouchers'][] = array(
                    'key'         => $key,
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount'],$this->session->data['currency']),
                    'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)
                );
            }
        }

        if ($this->config->get('config_cart_weight')) {
            $json['cart_weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'),$this->session->data['currency']);
        } else {
            $json['cart_weight'] = false;
        }

        $json['cart_error'] = (!empty($data['error_warning'])) ? $data['error_warning'] : '';

        return $json;
    }

	public function update(){
        $this->load->model('module/d_quickcheckout');
        $this->load->model('d_quickcheckout/address');
        $this->load->model('d_quickcheckout/method');
        $this->load->model('d_quickcheckout/order');

        if(isset($this->request->post['cart'])){
            foreach($this->request->post['cart'] as $key => $value){
                $this->cart->update($key, $value);
            }
        }

        $json = array();

        if($this->model_d_quickcheckout_order->isCartEmpty()){
            $json['redirect'] = $this->model_module_d_quickcheckout->ajax($this->url->link('checkout/cart'));
        }else{

            //payment address
            $json['shipping_required'] = $this->model_d_quickcheckout_method->shippingRequired();

             //shipping address
            $json = $this->load->controller('d_quickcheckout/shipping_address/prepare', $json);
            
            //cart
            $json = $this->prepare($json);
            
            //shipping method
            $json = $this->load->controller('d_quickcheckout/shipping_method/prepare', $json);

            //payment method
             $json = $this->load->controller('d_quickcheckout/payment_method/prepare', $json);

            //totals
            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );
            $json['totals'] = $this->session->data['totals'] = $this->model_d_quickcheckout_order->getTotals($total_data);
            $json['total'] = $this->model_d_quickcheckout_order->getCartTotal($total);

            //confirm
            $json['show_confirm'] = $this->model_d_quickcheckout_order->showConfirm();

            $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('d_quickcheckout/confirm/updateOrder');
            //payment
            $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);

            //statistic
            $statistic = array(
                'click' => array(
                    'cart' => 1
                )
            );
            $this->model_module_d_quickcheckout->updateStatistic($statistic);
        }
       
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateReward(){
        $this->load->model('module/d_quickcheckout');
        $this->load->model('d_quickcheckout/order');

        if(VERSION >= '2.1.0.1'){
            $this->load->language('total/reward');
        }else{
            $this->load->language('checkout/reward');
        }

        $json = array();

        $points = $this->customer->getRewardPoints();

        $points_total = 0;

        foreach ($this->cart->getProducts() as $product) {
            if ($product['points']) {
                $points_total += $product['points'];
            }
        }

        if (empty($this->request->post['reward'])) {
            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );
            $json['totals'] = $this->session->data['totals'] = $this->model_d_quickcheckout_order->getTotals($total_data);
            $json['total'] = $this->model_d_quickcheckout_order->getCartTotal($total);
            $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('d_quickcheckout/confirm/updateOrder');
            //payment
            $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);
        }

        if ($this->request->post['reward'] > $points) {
            $json['cart_errors']['reward'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
        }

        if ($this->request->post['reward'] > $points_total) {
            $json['cart_errors']['reward'] = sprintf($this->language->get('error_maximum'), $points_total);
        }

        if (!$json) {
            $this->session->data['reward'] = abs($this->request->post['reward']);

            $json['cart_successes']['reward'] = $this->language->get('text_success');
            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );
            $json['totals'] = $this->session->data['totals'] = $this->model_d_quickcheckout_order->getTotals($total_data);
            $json['total'] = $this->model_d_quickcheckout_order->getCartTotal($total);
            $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('d_quickcheckout/confirm/updateOrder');
            //payment
            $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);

        }

        //statistic
        $statistic = array(
            'update' => array(
                'reward' => 1
            )
        );
        $this->model_module_d_quickcheckout->updateStatistic($statistic);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateCoupon(){
        $this->load->model('module/d_quickcheckout');
        $this->load->model('d_quickcheckout/order');
        if(VERSION >= '2.1.0.1'){
            $this->load->language('total/coupon');
        }else{
            $this->load->language('checkout/coupon');
        }

        $json = array();
        if(VERSION >= '2.1.0.1'){
            $this->load->model('total/coupon');
        }else{
            $this->load->model('checkout/coupon');
        }
      

        if (isset($this->request->post['coupon'])) {
            $coupon = $this->request->post['coupon'];
        } else {
            $coupon = '';
        }

        if(VERSION >= '2.1.0.1'){
               $coupon_info = $this->model_total_coupon->getCoupon($coupon);
        }else{
               $coupon_info = $this->model_checkout_coupon->getCoupon($coupon);
        }

        if (empty($this->request->post['coupon'])) {
            unset($this->session->data['coupon']);
            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );
            $json['totals'] = $this->session->data['totals'] = $this->model_d_quickcheckout_order->getTotals($total_data);
            $json['total'] = $this->model_d_quickcheckout_order->getCartTotal($total);
            $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('d_quickcheckout/confirm/updateOrder');
            //payment
            $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);

        } elseif ($coupon_info) {
            $this->session->data['coupon'] = $this->request->post['coupon'];

            $json['cart_successes']['coupon'] =  $this->language->get('text_success');
            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );
            $json['totals'] = $this->session->data['totals'] = $this->model_d_quickcheckout_order->getTotals($total_data);
            $json['total'] = $this->model_d_quickcheckout_order->getCartTotal($total);
            $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('d_quickcheckout/confirm/updateOrder');
            //payment
            $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);

        } else {
            $json['cart_errors']['coupon'] =  $this->language->get('error_coupon');
        }

        //statistic
        $statistic = array(
            'update' => array(
                'coupon' => 1
            )
        );
        $this->model_module_d_quickcheckout->updateStatistic($statistic);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateCredit(){
        $this->load->model('module/d_quickcheckout');
        $this->load->model('d_quickcheckout/order');

        $json = array();
      

        if (isset($this->request->post['credit'])) {
            $credit = $this->request->post['credit'];
        } else {
            $credit = 0;
        }

        $this->session->data['credit'] = $this->request->post['credit'];

        $json['cart_successes']['credit'] =  'Success: You have successfully used credit!';
        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );
        $json['totals'] = $this->session->data['totals'] = $this->model_d_quickcheckout_order->getTotals($total_data,$credit);
        $json['total'] = $this->model_d_quickcheckout_order->getCartTotal($total);
        $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('d_quickcheckout/confirm/updateOrder');
        //payment
        $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);


        //statistic
        $statistic = array(
            'update' => array(
                'credit' => 1
            )
        );
        $this->model_module_d_quickcheckout->updateStatistic($statistic);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updateVoucher(){
        $this->load->model('module/d_quickcheckout');
        $this->load->model('d_quickcheckout/order');
        
        if(VERSION >= '2.1.0.1'){
            $this->load->language('total/voucher');
             $this->load->model('total/voucher');
        }else{
            $this->load->language('checkout/voucher');
             $this->load->model('checkout/voucher');
        }

        $json = array();

        if (isset($this->request->post['voucher'])) {
            $voucher = $this->request->post['voucher'];
        } else {
            $voucher = '';
        }

        if(VERSION >= '2.1.0.1'){
             $voucher_info = $this->model_total_voucher->getVoucher($voucher);
        }else{
             $voucher_info = $this->model_checkout_voucher->getVoucher($voucher);
        }
    
        $statistic = array();
        if (empty($this->request->post['voucher'])) {
            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );
            $json['totals'] = $this->session->data['totals'] = $this->model_d_quickcheckout_order->getTotals($total_data);
            $json['total'] = $this->model_d_quickcheckout_order->getCartTotal($total);
            $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('d_quickcheckout/confirm/updateOrder');
            //payment
            $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);
            
            $statistic += array(
                'error' => array(
                    'voucher' => 1
                )
            );
        } elseif ($voucher_info) {
            $this->session->data['voucher'] = $this->request->post['voucher'];
            $json['cart_successes']['voucher'] =  $this->language->get('text_success');
            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );
            $json['totals'] = $this->session->data['totals'] = $this->model_d_quickcheckout_order->getTotals($total_data);
            $json['total'] = $this->model_d_quickcheckout_order->getCartTotal($total);
            $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('d_quickcheckout/confirm/updateOrder');
            //payment
            $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);

        } else {
            $json['cart_errors']['voucher'] =  $this->language->get('error_voucher');
            $statistic += array(
                'error' => array(
                    'voucher' => 1
                )
            );
        }

        //statistic
        $statistic += array(
            'update' => array(
                'voucher' => 1
            )
        );
        $this->model_module_d_quickcheckout->updateStatistic($statistic);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}