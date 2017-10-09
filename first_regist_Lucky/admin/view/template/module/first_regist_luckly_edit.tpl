<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="javascript:window.history.go(-1)" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="show2">
              <div class="form-group">
              <input type="hidden" name="id" value="<?php echo @$luckly_data['id']; ?>">
                <label class="col-sm-2 control-label" for="input-name">type</label>
                <div class="col-sm-10">
                <select name="type" class="form-control">
                  <option value="Coupon" <?php echo (@$luckly_data['type'] == 'Coupon')?'selected="selected"':'' ?>>Coupon</option>
                  <option value="Cash" <?php echo (@$luckly_data['type'] == 'Cash')?'selected="selected"':'' ?>>Cash</option>
                </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name">num</label>
                <div class="col-sm-10">
                  <div class="input-group">
                  <input type="text" name="num" value="<?php echo @$luckly_data['num']; ?>"  class="form-control" style="width:80px;"/>
                    <label class="col-sm-2 control-label <?php echo (@$luckly_data['type'] == 'Cash')?'hidden"':'' ?>" id="numhidden"> %</label>
                  </div>
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name"><span data-toggle="tooltip" title="" data-original-title="The total amount that must be reached before the coupon is valid.">Total Amount</span></label>
                <div class="col-sm-10">
                  <input type="text" name="needamount" value="<?php echo @$luckly_data['needamount']; ?>"  class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name">probability</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <input type="text" name="probability" value="<?php echo @$luckly_data['probability']; ?>"  class="form-control" style="width:80px;" />
                    <label class="col-sm-2 control-label"> %</label>
                  </div>
                   
                </div>
              </div>
              </div>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
  $('[name="type"]').change(function() {
    if($(this).val() == 'Cash'){
      $('#numhidden').addClass('hidden');
    }else{
      $('#numhidden').removeClass('hidden');
    }
  })
</script>