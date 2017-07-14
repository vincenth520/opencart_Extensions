<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-carousel" data-toggle="tooltip" title="save" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a onclick="$('#apply').attr('value', '1'); $('#form-tm-lite').submit();" data-toggle="tooltip" title="refresh" class="btn btn-success"><i class="fa fa-refresh"></i></a>
        <a href="cancel" data-toggle="tooltip" title="cancel" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Store Watermark Settings</h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Store Watermark Settings</h3>
      </div>
      <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-tm-lite" class="form-horizontal">		
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-layout">Enable Watermark ?</label>
            <div class="col-sm-10">
				<select name="watermark_status" class="form-control">
				<?php if ($watermark_status) { ?>
					<option value="1" selected="selected">Yes</option>
					<option value="0" >No</option>
				<?php } else { ?>
					<option value="1" >Yes</option>
					<option value="0" selected="selected">No</option>
				<?php } ?>
              </select>
            </div>
          </div>          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-layout">Image</label>
            <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb;?>" alt="" title="" data-placeholder="<?php echo $placeholder;?>" /></a>
                    <input type="hidden" name="store_watermark[0][image]" value="<?php echo $storeSetting[0]['image'];?>" id="input-image" />
                  </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-layout">Position</label>
            <div class="col-sm-10">
				<select name="store_watermark[0][position]" class="form-control">
				<option value="TopLeft" 		<?php echo $storeSetting[0]['position'] == "TopLeft" 	? 'selected="selected"' : ''; ?>>Top Left</option>
				<option value="TopRight" 		<?php echo $storeSetting[0]['position'] == "TopRight" 	? 'selected="selected"' : ''; ?>>Top Right</option>
				<option value="Center" 			<?php echo $storeSetting[0]['position'] == "Center" 	? 'selected="selected"' : ''; ?>>Center</option>
				<option value="BottomLeft"		<?php echo $storeSetting[0]['position'] == "BottomLeft" 	? 'selected="selected"' : ''; ?>>Bottom Left</option>
				<option  value="BottomRight"  	<?php echo $storeSetting[0]['position'] == "BottomRight" 	? 'selected="selected"' : ''; ?>>Bottom Right</option>
              </select>
            </div>
          </div>
			<input type="hidden" name="action" value="store" />
		</form>
      </div>
    </div>
  </div>  
</div>
<?php echo $footer; ?>