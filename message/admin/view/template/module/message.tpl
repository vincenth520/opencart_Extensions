<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
    <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Add Message"><i class="fa fa-plus"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-invitation">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">Message</td>
                  <td class="text-left">User</td>
                  <td class="text-right">SendTime</td>
                  <td class="text-right">ReadFlag</td>
                  <td class="text-right">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($message_list) { ?>
                <?php foreach ($message_list as $message) { ?>
                <tr>
                  <td class="text-left"><?php echo strip_tags(htmlspecialchars_decode($message['messageText'])); ?></td>
                  <td class="text-left"><?php echo $customers[$message['SenderId']]; ?></td>
                  <td class="text-right"><?php echo $message['SendTime']; ?></td>
                  <td class="text-right"><?php echo ($message['ReadFlag'] == 0)?'Unread':'Read'; ?></td>
                  <td class="text-right"><a href="<?php echo $add; ?>&id=<?php echo ($message['ReceiverId'] == 0)?$message['SenderId']:$message['ReceiverId']; ?>">Reply</a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 