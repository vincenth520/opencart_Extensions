<?php echo $header; ?>
<div class="container category-row">
	<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
			<?php } ?>
	</ul>
	<div class="row">
    <?php $class = 'col-md-12 col-sm-12 col-xs-12'; ?>
    <div id="content" class="<?php echo $class; ?>"><h2>Quick Order</h2>
	<article class="col-main">
	<style type="text/css">
		input{font-size: 12px!important;padding: 0!important}
		table{width: 100%;}
		tr{height: 40px;}
		th{font-weight: 500;color: #000;}
		.p-item img, .p-item span {
		    float: left;
		    display: inline-block;
		}
		.p-auto{width: 277px;position: absolute;-moz-box-shadow: #ccc; box-shadow: 0px 0px 10px #ccc;background: #fff;z-index: 10000;}
		.p-auto:hover{background: #ccc;color:#fff;}
		.p-item img{margin: 5px;}
		.p-item span{width: 210px!important;height: 30px;overflow: hidden;}
		.name label{font-weight: 400;font-size: 14px;margin-top: 10px;color: #000;}
        button { background-color: #990e0b;color: #ffffff;border:none;}
        button:hover{background-color:red;color: #ffffff}
	</style>
	<div id="quickorder-form">
				                                                   
                        <table id="search-table">
                            <thead>
                                <tr>
                                    <th style="width: 90px;">SKU</th>
                                    <th style="width: 145px;">Product Code(SKU)</th>
                                    <th style="width: 335px;">Product Name</th>
                                    <th style="width: 40px;">Qty</th>
                                    <th style="width: 60px;">Price</th>
                                    <th style="width: 153px;padding-right:0">Action</th>
                                </tr>
                            </thead>
                            <tbody>   
                            </tbody>
                        </table> 
                            <table>
                            	<tr>
                                    <td class="form-action-button" colspan="6">
                                        <label class="add-item" style=" line-height: 40px;float:left; cursor: pointer; font-weight: 700;margin-top: 50px">+ Add item</label>
                                        <label class="remove-item" style="line-height: 40px;margin-left:15px;float:left; cursor: pointer; font-weight: 700;margin-top: 50px">- Remove item</label>
                                        <button class="add-all button" style="margin-right:55px;float: right;margin-top: 50px"><span><span>Add All to Cart</span></span></button>
                                    </td>
                                </tr>
                            </table>
                            <a class="download" href="upload/import_product_to_cart.csv"><span class="xls-icon"></span>Download Template (.csv)</a>
                            <form method="post" enctype="multipart/form-data" action="product/advancedquickorder/import"> 
                                <div class="import-to-cart">
                                    <label for="productcsv">Import File:</label>
                                    <div class="input-box"><input id="productcsv" type="file" value="" size="20" name="productcsv"></div>
                                </div> 
                                <button class="button import btn-quick-import" type="submit">
                                    <span>
                                        <span>IMPORT</span>
                                    </span>
                                </button> 
                                <div class="info-tips">
                                    <span>Reminder: Product contains option is temporaily not supported. Please use the above Quick Order interface for product need to choose option</span>
                                </div>
                            </form>                        
                        </div>
	  </article>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>

<div class="p-auto">
     
</div>

<script type="text/javascript">
	tm = setTimeout('',1);
	var productList = new Array();
	$('.add-item').click(function() {
		var str = '<tr class="product"><input type="hidden" value="" id="product_id">'+
                '<td id="sku1" class="txt-sku" style="width:90px;">&ensp;</td>'+
                '<td class="td-pcode" style="width:145px;"><input class="txt-pcode" type="text" style="color:#ccc;" onblur="if(this.value == \'\')this.value=\'Please input product SKU\';this.style.color=\'#000\';" onclick="if(this.value == \'Please input product SKU\')this.value=\'\';this.style.color=\'#000\';" value="Please input product SKU"></td>'+
                '<td style="width:335px" class="pname">&ensp;</td>'+
                '<td style="width: 40px;" class="td-qty"><input style="width: 40px;" class="txt-qty" type="text" id="qty1"></td>'+
                '<td style="width: 60px;" class="pqty">&ensp;</td>'+
                '<td class="td-action">'+
                '    <button class="btn-addcart button"><span><span>Add to Cart</span></span></button>'+
                '    <button class="btn-remove-cart button"><span><span>Clear</span></span></button>'+
                '</td>'+
            '</tr>';
        $('#search-table tbody').append(str);
	})

	$('.add-item').click();
	$('.add-item').click();
	$('.add-item').click();
	$('.remove-item').click(function() {
		if($('.product').length > 1){
			$('.product').eq($('.product').length-1).remove();
		}
	})

	$(".txt-pcode").bind('input propertychange',function(){
    	clearTimeout(tm);
    	var that = $(this);
		var index = that.parent().parent().index();
    	tm = setTimeout(function() {
    		getList(that.val(),index);
    	},1000);
    });

    $('.btn-remove-cart').click(function() {
    	var item = $(this).parent().parent();
    	item.find('#sku1').html('');
    	item.find('#product_id').val('');
    	item.find('.txt-pcode').val('');
    	item.find('.pname').html('');
    	item.find('.pqty').html('');
    	item.find('.txt-qty').val('');
    })

    $('.btn-addcart').click(function() {
    	var item = $(this).parent().parent();
    	if(item.find('#product_id').val() == ''){
    		item.find('.txt-pcode').focus();
    	}
    	cart.add(item.find('#product_id').val(),item.find('.txt-qty').val());
    	item.find('.btn-remove-cart').click();
    })

    $('.add-all').click(function() {
    	$('.product').each(function(i,item){
    		item = $(item);
    		if(item.find('#product_id').val() != ''){
		    	cart.add(item.find('#product_id').val(),item.find('.txt-qty').val());
		    	item.find('.btn-remove-cart').click();
	    	}
    	})
    })


    function getList(sku,index) {
    	if(sku == ''){
    		return false;
    	}
    	$.post('product/Advancedquickorder/getList',{sku:sku},function(data) {
    		data = JSON.parse(data);
    		productList = data;
    		console.log(productList);
    		var str = '';
    		if(data.length != 0){
	    		$.each(data,function(i,j) {
	    			str += '<div class="p-item" onclick="setSku('+i+','+index+')">'+
	        				'<img width="50" height="50" src="'+j.image+'" />'+
					        '<span class="name"><label>'+j.name+'</label></span>'+
					        '<span>SKU#: <label class="sku">'+j.sku+'</label></span>'+
					        '<input type="hidden" class="pprice" value="$'+j.price+'" />'+
					        '<input type="hidden" class="prodid" value="'+j.product_id+'" />'+
					    	'</div>';
	    		})
	    	}else{
	    		str += '<div class="p-item"'+
		        '<span class="name"><label>Please input sku.</label></span>'+
		    	'</div>';
	    	}
    		$('.p-auto').html(str);
    		$('.p-auto').show();
    		$('.p-auto').css({top:$('.product').eq(index).find('.txt-pcode').offset().top+32,left:$('.product').eq(index).find('.txt-pcode').offset().left});
    	})
    }

    function setSku(i,index) {
    	$('.product').eq(index).find('#sku1').html(productList[i].sku);
    	$('.product').eq(index).find('.txt-pcode').val(productList[i].name);
    	$('.product').eq(index).find('.pname').html(productList[i].name);
    	$('.product').eq(index).find('.pqty').html(productList[i].price);
    	$('.product').eq(index).find('.txt-qty').val(1);
    	$('.product').eq(index).find('#product_id').val(productList[i].product_id);
    	$('.p-auto').hide();
    }


</script>