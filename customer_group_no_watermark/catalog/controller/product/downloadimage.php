<?php
class ControllerproductDownloadimage extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('product/Downloadimage', 'product_id='.$this->request->get['product_id'], 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . $this->customer->getId() . "' AND status = '1'");

		$download_history = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_no_watermark WHERE customer_id = '" . $this->customer->getId() . "' AND product_id = '".$this->request->get['product_id']."'");

		if($customer_query->row['no_watermark_num'] == 0 && (!$download_history->num_rows)){  //如果剩余次数为0，并且之前没有下载过，直接返回
			$this->session->data['error'] = 'Your can not download product image any more.';
			$this->response->redirect($this->url->link('product/product',  'product_id='.$this->request->get['product_id'], 'SSL'));
			exit;
		}

		$filename = 'imgzip/'.MD5($this->request->get['product_id']) . ".zip";

		if(!file_exists($filename)){  //如果文件存在，无需重新压缩

			if(!$download_history->num_rows){  //如果以前没有下载过，减去次数生成记录
				$this->db->query("insert into " . DB_PREFIX . "download_no_watermark set customer_id = '" . $this->customer->getId() . "' , product_id = '".$this->request->get['product_id']."'");
				$this->db->query("update " . DB_PREFIX . "customer set no_watermark_num = no_watermark_num-1 WHERE customer_id = '" . $this->customer->getId() . "'");
			}

			$img_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '".$this->request->get['product_id']."'");

			$zip = new ZipArchive ();
			if ($zip->open ($filename ,\ZipArchive::OVERWRITE) !== true) {
				if($zip->open ($filename ,\ZipArchive::CREATE) !== true){
			　　　　　　exit ( '无法打开文件，或者文件创建失败' );
				}
			}

			 
			foreach ($img_query->rows as $key => $value) {
				$fileName = DIR_IMAGE.$value['image']; //存放文件的真实路径
			  
				if(file_exists($fileName )){
					$zip->addFile( $fileName , basename($fileName));
				}
			}

			$zip->close (); // 关闭
		}
		 
		//下面是输出下载;
		header ( "Cache-Control: max-age=0" );
		header ( "Content-Description: File Transfer" );
		header ( 'Content-disposition: attachment; filename=' . basename ( $filename ) ); // 文件名
		header ( "Content-Type: application/zip" ); // zip格式的
		header ( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
		header ( 'Content-Length: ' . filesize ( $filename ) ); // 告诉浏览器，文件大小
		@readfile ( $filename );//输出文件;		
	}
}