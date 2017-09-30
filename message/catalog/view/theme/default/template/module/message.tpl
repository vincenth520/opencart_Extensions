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
          <div class="col-sm-12">
              <textarea name="message" id="input-message" class="form-control"></textarea>
          </div>
          <p><button class="btn btn-info send" onclick="send('<?php echo $send_link; ?>');"><?php echo $button_send;?></button></p>
      </div>
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
      </div></div>
  <br> 
      <?php } ?>
</div>
<?php echo $footer; ?>
<style type="text/css">
.note-editor  .btn,.note-editor button {
     background: #fff; 
     border: 1px solid #ccc; 
    /* font-size: 14px; */
    /* line-height: 15px; */
     padding: 5px 10px; 
    text-transform: uppercase;
    color: #ccc; 
    border-radius: 0;
}
.send{margin:20px;float: right;}
    .right{text-align: right;}
    li{list-style: none;}
    .name{color:#0CF;}
    .myname{color:#165064;}
    .tm{color:#999;}
  </style>

<link href="catalog/view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="catalog/view/javascript/summernote/summernote.js"></script>
  <script type="text/javascript"><!--
$('#input-message').summernote({
  height: 100
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
//--></script>