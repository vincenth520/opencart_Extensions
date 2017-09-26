<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row">
    <?php $class = 'col-sm-12'; ?>
    <div id="content" class="<?php echo $class; ?>">
      <h1><?php echo $heading_title; ?></h1>
      <div class="container">
        <h2>REFERRAL LINK</h2>
        <p>Copy + paste your personal link into your blog, email or IM.</p>
        <p><input type="text" value="<?php echo $invitation_link;?>" style="width: 80%;height: 20px;line-height: 20px;padding: 0;    text-align: left;color: #000;"></p>
        <p><!-- Go to www.addthis.com/dashboard to customize your tools --> <div class="addthis_inline_share_toolbox"></div><!-- Go to www.addthis.com/dashboard to customize your tools --> 
        <script type="text/javascript">var addthis_share = {
    description: "",
    title: "",
    url: '<?php echo $invitation_link;?>'
}</script>
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-59c8bb1540db10a2"></script> </p>
      </div>
      <br><br>
      <h2>My invitation list</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_invitee; ?></td>
              <td class="text-right"><?php echo $column_time; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($invitations) { ?>
            <?php foreach ($invitations  as $invitation) { ?>
            <tr>
              <td class="text-left"><?php echo $invitation['invitee']; ?></td>
              <td class="text-right"><?php echo date('Y-m-d H:i:s',$invitation['add_time']); ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="3"><?php echo $text_empty; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div></div></div>
</div>
<?php echo $footer; ?>