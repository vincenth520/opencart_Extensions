<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-luckly" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Save"><i class="fa fa-save"></i></button>
        <a href="<?php echo $back; ?>" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Cancel"><i class="fa fa-reply"></i></a>
        </div>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> Edit Config</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="form-luckly">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status">Status</label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <option value="1" <?php echo ($luckly_config['status'] == 1)?'selected="selected"':'' ?>>Enabled</option>
                <option value="0" <?php echo ($luckly_config['status'] == 0)?'selected="selected"':'' ?>>Disabled</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status">title</label>
            <div class="col-sm-10">
              <input type="text" name="title" value="<?php echo $luckly_config['title']?>" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status">description</label>
            <div class="col-sm-10">
              <input type="text" name="description" value="<?php echo $luckly_config['description']?>" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status">announcement</label>
            <div class="col-sm-10">
              <input type="text" name="announcement" value="<?php echo $luckly_config['announcement']?>" class="form-control">
            </div>
          </div>
        </form>
        </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">title</td>
                  <td class="text-left">type</td>
                  <td class="text-left">probability</td>
                  <td class="text-right">action</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($luckly_data) { ?>
                <?php foreach ($luckly_data as $luckly) {
                  if($luckly['type'] == 'Coupon'){
                    $luckly['title'] = $luckly['num'].'% Coupon';
                  }else{
                    if($luckly['needamount'] == 0){
                      $luckly['title'] = '$'.$luckly['num'].' Cash';
                    }else{
                      $luckly['title'] = '$'.$luckly['num'].' off over $'.$luckly['needamount'];
                    }
                  }
                ?>
                <tr>
                  <td class="text-left"><?php echo $luckly['title']; ?></td>
                  <td class="text-left"><?php echo $luckly['type']; ?></td>
                  <td class="text-left"><?php echo $luckly['probability']; ?>%</td>
                  <td class="text-right"><a href="<?php echo $edit_url;?>&id=<?php echo $luckly['id']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 