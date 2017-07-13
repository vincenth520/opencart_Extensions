<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="letter">
    <?php foreach ($letter_arr as $breadcrumb) { ?>
      <a href="index.php?route=module/popular&letter=<?php echo $breadcrumb; ?>" class="input-group-addon btn <?php echo ($letter == $breadcrumb)?'btn-danger':'btn-default'?>"><?php echo $breadcrumb; ?></a>
    <?php } ?>
</div>
<br>
<style type="text/css">
  .popular_tag{color: #565;}
  .popular_tag .col-md-3{padding-top: 15px;}
</style>
<div class="row breadcrumb">
    <?php foreach ($populars as $v){ ?>
      <a href="index.php?route=module/popular/item&name=<?php echo $v['name'];?>" class="popular_tag"><div class="col-md-3"><?php echo $v['name'];?></div></a>
    <?php } ?>
</div>

<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
<?php echo $footer; ?>