<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button id="button-send" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_send; ?>" class="btn btn-primary" onclick="send('index.php?route=module/message/send&token=<?php echo $token; ?>');"><i class="fa fa-envelope"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <style type="text/css">
    .right{text-align: right;}
    li{list-style: none;}
    .name{color:#0CF;}
    .myname{color:#165064;}
    .tm{color:#999;}
  </style>
          <?php if(isset($chat_list)){ ?>
          <div class="container">
            <div  id="chat" style="max-height: 200px;border:1px solid #ccc;overflow: auto;margin:0px 20px;">
              <div class="" style="max-height: 100%;">
                 <div class="panel-body">
                <ul>
                  <?php foreach($chat_list as $v){?>
                    <li>
                      <header><i class="fa fa-user"></i><b class="name <?php echo ($v['SenderId'] == 0 ? 'myname':'');?>"><?php echo ($v['SenderId'] == 0 ? 'administrator':$touser["firstname"]);?></b></header>
                      <div>
                        <?php echo htmlspecialchars_decode($v['MessageText']);?> 
                      </div>
                      <p class="tm"><?php echo $v['SendTime'];?></p>
                    </li> 
                  <?php } ?>
                </ul>
                </div>
                 </div>
            </div> 
          </div>
      
  <br> 
  <script type="text/javascript">
    $('#chat').scrollTop($('#chat .panel-body').height());
  </script>
      <?php } ?>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-envelope"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">


        <form class="form-horizontal">
          <div class="form-group <?php echo (isset($touser)?'hidden':'') ?>" >
            <label class="col-sm-2 control-label" for="input-store"><?php echo $entry_store; ?></label>
            <div class="col-sm-10">
              <select name="store_id" id="input-store" class="form-control">
                <option value="0"><?php echo $text_default; ?></option>
                <?php foreach ($stores as $store) { ?>
                <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group <?php echo (isset($touser)?'hidden':'') ?>">
            <label class="col-sm-2 control-label" for="input-to"><?php echo $entry_to; ?></label>
            <div class="col-sm-10">
              <select name="to" id="input-to" class="form-control">
                <option value="customer_all"><?php echo $text_customer_all; ?></option>
                <option value="customer_group"><?php echo $text_customer_group; ?></option>
                <option value="customer" <?php echo (isset($touser)?'selected="selected"':'') ?>><?php echo $text_customer; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group to <?php echo (isset($touser)?'hidden':'') ?>" id="to-customer-group">
            <label class="col-sm-2 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
            <div class="col-sm-10">
              <select name="customer_group_id" id="input-customer-group" class="form-control">
                <?php foreach ($customer_groups as $customer_group) { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group to <?php echo (isset($touser)?'hidden':'') ?>" id="to-customer">
            <label class="col-sm-2 control-label" for="input-customer"><span data-toggle="tooltip" title="<?php echo $help_customer; ?>"><?php echo $entry_customer; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="customers" value="" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              <div class="well well-sm" style="height: 150px; overflow: auto;"></div>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-message"><?php echo $entry_message; ?></label>
            <div class="col-sm-10">
              <textarea name="message" placeholder="<?php echo $entry_message; ?>" id="input-message" class="form-control"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#input-message').summernote({
	height: 300
});
//--></script>
  <script type="text/javascript"><!--
$('select[name=\'to\']').on('change', function() {
	$('.to').hide();

	$('#to-' + this.value.replace('_', '-')).show();
});

$('select[name=\'to\']').trigger('change');
//--></script>
  <script type="text/javascript"><!--
// Customers
$('input[name=\'customers\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'customers\']').val('');

		$('#customer' + item['value']).remove();

		$('#input-customer').parent().find('.well').append('<div id="customer' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="customer[]" value="' + item['value'] + '" /></div>');
	}
});
if('<?php echo $touser["customer_id"];?>'){
  $('input[name=\'customers\']').val('');

  $('#customer<?php echo $touser["customer_id"];?>').remove();

  $('#input-customer').parent().find('.well').append('<div id="customer<?php echo $touser["customer_id"];?>"><i class="fa fa-minus-circle"></i> <?php echo $touser["firstname"].'  '.$touser["lastname"];?><input type="hidden" name="customer[]" value="<?php echo $touser["customer_id"];?>" /></div>');

}


$('#input-customer').parent().find('.well').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

function send(url) {
	// Summer not fix
	$('textarea[name=\'message\']').val($('#input-message').code());

	$.ajax({
		url: url,
		type: 'post',
		data: $('#content select, #content input, #content textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-send').button('loading');
		},
		complete: function() {
			$('#button-send').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
				}

				if (json['error']['subject']) {
					$('input[name=\'subject\']').after('<div class="text-danger">' + json['error']['subject'] + '</div>');
				}

				if (json['error']['message']) {
					$('textarea[name=\'message\']').parent().append('<div class="text-danger">' + json['error']['message'] + '</div>');
				}
			}

			if (json['next']) {
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i>  ' + json['success'] + '</div>');

					send(json['next']);
				}
			} else {
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
          setTimeout(function() {
            location.reload();
          },1000);
				}
			}
		}
	});
}
//--></script></div>
<?php echo $footer; ?>
