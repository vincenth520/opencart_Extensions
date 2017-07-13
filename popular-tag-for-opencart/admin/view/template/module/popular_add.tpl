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
        <form action="" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <?php if(!isset($popular)){ ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name">import</label>
                <div class="col-sm-10"  style="line-height: 40px;">
                <input type="file" name="file" id="exampleInputFile" style="float: left;">
                <div class="btn btn-danger hidden" id="delfile" onclick="delfile()" title="delete file"><i class="fa fa-trash-o"></i></div>
                </div>
              </div>
              <?php } ?>
              <div class="show2">
              <?php if(!isset($popular)){ ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-name"></label>
                  <div class="col-sm-10"><b>OR</b></div>
                </div>
              <?php } ?>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $column_name; ?></label>
                <div class="col-sm-10">
                <?php if(isset($popular)){?>
                  <input type="hidden" name="id" value="<?php echo $popular['id']?>">
                <?php  }?>
                  <input type="text" name="<?php echo $column_name; ?>" value="<?php echo @$popular['name']; ?>" placeholder="<?php echo $column_name; ?>" id="input-name" class="form-control" />
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $column_keywords; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="<?php echo $column_keywords; ?>" value="<?php echo @$popular['keywords']; ?>" placeholder="<?php echo $column_keywords; ?>,Separated by commas" id="input-name" class="form-control" />
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name">description</label>
                <div class="col-sm-10">
                  <textarea name="<?php echo $column_description;?>" placeholder="<?php echo $column_description;?>" id="description"><?php echo @$popular['description']; ?></textarea>          
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
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
  $('#description').summernote({height: 300});

  function delfile() {
      $('#exampleInputFile').val('');
      $('.show2').show();
      $('#delfile').addClass('hidden');
  }

  $('#exampleInputFile').change(function() {
    $('.show2').hide();
    $('#delfile').removeClass('hidden');
  })

</script>