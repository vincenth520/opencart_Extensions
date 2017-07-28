<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><a href="#collapse-credit" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"><?php echo $heading_title; ?> <i class="fa fa-caret-down"></i></a></h4>
  </div>
  <div id="collapse-credit" class="panel-collapse collapse">
    <div class="panel-body">
      <label class="col-sm-2 control-label" for="input-credit"><?php echo $entry_credit; ?></label>
      <div class="input-group">
        <input type="text" name="credit" value="<?php echo $credit; ?>" placeholder="<?php echo $entry_credit; ?>" id="input-credit" class="form-control" />
        <span class="input-group-btn">
        <input type="button" value="<?php echo $button_credit; ?>" id="button-credit" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary" />
        </span></div>
      <script type="text/javascript"><!--
$('#button-credit').on('click', function() {
	$.ajax({
		url: 'index.php?route=total/credit/credit',
		type: 'post',
		data: 'credit=' + encodeURIComponent($('input[name=\'credit\']').val()),
		dataType: 'json',
		beforeSend: function() {
			$('#button-credit').button('loading');
		},
		complete: function() {
			$('#button-credit').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script>
    </div>
  </div>
</div>
