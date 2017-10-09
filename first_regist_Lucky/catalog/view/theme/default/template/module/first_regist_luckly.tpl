<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop='static'>
    <div class="modal-dialog" style="width:931px;">
        <div class="modal-content" style="background: none;border: none;box-shadow: none;">
            <div class="luck-draw-alert js_luck_draw_alert layer_pageContent">
            <div class="luck-drawalerttitle">
            	<h4 style="color:#fff;"><?php echo $luckly_config['title'];?></h4>
            	<p><?php echo $luckly_config['description'];?></p>
            	<a href="javascript:void(0);" onclick="$('#myModal').modal('hide')" rel="nofollow" class="luck-draw-alert-close js_draw_alert_close"></a>
            </div>
            <div class="luck-draw-main">
            	<p><?php echo $luckly_config['announcement'];?></p>
            	<div class="prize-list">
            		<ul>
            			<?php $num = 0; foreach($luckly_data as $k => $v){
            			if($v['type'] == 'Coupon'){
		                    $v['title'] = $v['num'].'% Coupon';
		                    $class = 'coupon';
		                  }else{
		                    if($v['needamount'] == 0){
		                      $v['title'] = '$'.$v['num'].' Cash';
		                    	$class = 'cash';
		                    }else{
		                      $v['title'] = '$'.$v['num'].' off over $'.$v['needamount'];
		                    	$class = 'off';
		                    }
		                  }
            			if($num++ == 4){ ?>
            			<li class="prize-item prize-button"> <a href="javascript:void(0);" title="Check here" rel="nofollow" class="run-btn" id="js_run"></a></li>
            			<?php } ?>
            			<li class="prize-item prize-item-<?php echo $v['id']?> prize-item-<?php echo $class;?> js_prize" data-index="<?php echo $v['id']?>" data-index-prize="<?php echo $v['id']?>"> <span><?php echo $v['title'];?></span></li>
            			<?php } ?>
            		</ul>
            	</div>
            </div>
           </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop='static'>
    <div class="modal-dialog" style="width:530px;">
        <div class="modal-content" style="background: none;border: none;box-shadow: none;">
          <div class="xubox_page">
            <div class="draw-alert coupons-success js_coupons_success layer_pageContent">
              <a href="javascript:void(0);" onclick="$('#success').modal('hide')" rel="nofollow" class="draw-alert-close js_draw_alert_close"></a>
              <div class="draw-alert-container">
                <h2 class="title">Congratulations!</h2>
                <p class="you-get">You get</p>
                <div class="coupons" id="jsYouGetPrize"></div>
                <p class="now-can">Now you can：</p>
                <div class="">
                  <a rel="nofollow" href="account/register" class="js_go_register drawalertbtn">Register and get the Coupon</a>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<style type="text/css">
blockquote,body,dd,div,dl,dt,fieldset,form,h1,h2,h3,h4,h5,h6,input,li,ol,p,pre,textarea,ul{margin:0;padding:0}.widget-bag-list .checkoutBtn:hover{color:#000;background-color:#fff;padding:6px;border:2px solid #000;text-decoration:none}.none{display:none}.luck-draw-alert{width:931px}.luck-draw-alert .luck-drawalerttitle{background-image:url(image/sprite.png?v=f90adf5279);background-position:0 0;width:931px;height:140px;text-align:center;color:#fff;position:relative;margin-bottom:-25px}.luck-draw-alert .luck-drawalerttitle h4{line-height:60px;padding-top:15px;font-size:36px;font-weight:700}.luck-draw-alert .luck-drawalerttitle p{font-size:14px}.luck-draw-alert .luck-drawalerttitle .luck-draw-alert-close{position:absolute;display:inline-block;width:30px;height:30px;right:10px;top:10px}.luck-draw-alert .luck-draw-main{width:894px;padding:10px 27.5px 60px 27.5px;margin:0 auto;background-color:#fff;text-align:center}.luck-draw-alert .luck-draw-main>p{font-size:14px;color:#333;padding-left:30px;position:relative;display:inline-block;margin:10px 0}.luck-draw-alert .luck-draw-main>p:before{content:'';display:inline-block;background-image:url(image/sprite.png?v=f90adf5279);background-position:-730px -148px;width:30px;height:30px;position:absolute;left:0;top:-4px}.luck-draw-alert .luck-draw-main>p span{font-size:16px;color:#ff5f6e;font-weight:700}.luck-draw-alert .luck-draw-main .prize-list{font-size:0}.luck-draw-alert .luck-draw-main .prize-list li{width:252px;height:137px;margin:12px 4.5px;display:inline-block;background-color:#ff8b96;position:relative;vertical-align:top}.luck-draw-alert .luck-draw-main .prize-list li:after{display:inline-block;content:'';background-image:url(image/sprite.png?v=f90adf5279);background-position:-516px -148px;width:130px;height:70px;position:absolute;margin:auto;bottom:20px;right:0;left:0}.luck-draw-alert .luck-draw-main .prize-list li span{font-size:22px;color:#fff;font-weight:700;line-height:50px;position:relative}.luck-draw-alert .luck-draw-main .prize-list li.activated:before{content:'';display:block;width:100%;height:100%;position:absolute;top:0;left:0;background-color:#c92525;filter:alpha(opacity=20);opacity:.2}.luck-draw-alert .luck-draw-main .prize-list .prize-button{background-color:transparent}.luck-draw-alert .luck-draw-main .prize-list .prize-button:after{background-image:none}.luck-draw-alert .luck-draw-main .prize-list .run-btn{display:block;width:156px;height:156px;background-image:url(image/check_here.gif);position:absolute;margin:auto;top:0;bottom:0;left:0;right:0;z-index:10;cursor:pointer}.luck-draw-alert .luck-draw-main .prize-list .run-btn.activated{background-image:url(image/sprite.png?v=f90adf5279);background-position:0 -231px;width:156px;height:156px}
.luck-draw-alert .luck-draw-main .prize-list .prize-item-2{background-color:#ffaf97}
.luck-draw-alert .luck-draw-main .prize-list .prize-item-2:after{background-image:url(image/sprite.png?v=f90adf5279);background-position:-302px -231px;width:130px;height:90px;bottom:10px}

.luck-draw-alert .luck-draw-main .prize-list .prize-item-4{background-color:#ffaf97}
.luck-draw-alert .luck-draw-main .prize-list .prize-item-off:after{background-image:url(image/sprite.png?v=f90adf5279);background-position:-358px -148px;width:150px;height:70px}
.luck-draw-alert .luck-draw-main .prize-list .prize-item-5{background-color:#ffaf97}
.luck-draw-alert .luck-draw-main .prize-list .prize-item-7{background-color:#ffaf97}
.luck-draw-alert .luck-draw-main .prize-list .prize-item-cash:after{background-image:url(image/sprite.png?v=f90adf5279);background-position:-302px -231px;width:130px;height:90px;bottom:10px}
.draw-alert{padding:50px 0;text-align:center;width:532px;background-color:#fff;position:relative}
.draw-alert .draw-alert-close{position:absolute;right:10px;top:10px;background-image:url(image/sprite.png?v=f90adf5279);background-position:-692px -148px;width:30px;height:30px}.draw-alert .title{font-size:40px;font-weight:700;line-height:60px;color:#333}.draw-alert .you-get{font-size:20px;color:#ec5b68;line-height:30px}.draw-alert .coupons{background-image:url(image/sprite.png?v=f90adf5279);background-position:0 -148px;width:350px;height:75px;margin:auto;line-height:75px;font-size:30px;color:#ec5b68}.draw-alert .now-can{font-size:14px;line-height:30px;margin-top:20px;color:#333;font-weight:700}.draw-alert .drawalertbtn{text-decoration:none;display:block;margin:auto;font-size:20px;width:286px;line-height:50px;color:#fff;background-color:#f37173;border-radius:5px}.register-success{padding-bottom:30px}.register-success p{font-size:14px;line-height:45px}.register-success p a{color:#f37173;font-weight:700;text-decoration:underline}.register-success .now-can{margin-top:15px}.register-success .coupons{margin-top:20px}.adv-draw .adv-draw-img{background-image:url(image/big-coupon.gif);width:166px;height:166px;display:block;cursor:pointer;position:fixed;bottom:150px;margin-left:650px;left:50%;z-index:100}
</style>


<script type="text/javascript">
  var drawAlertIndex = "";
  var stop = true;
  var rotateTimer = null;
  var count = 0;
  var speed = 150;
  var result = null;
  var prizeSort = $(".js_prize").sort(function (a, b) {
      if ($(a).attr("data-index") > $(b).attr("data-index")) {
          return 1;
      } else {
          return -1;
      }
  });

  $("#js_run").click(function () {
            $(this).attr('disabled', true).addClass('activated');
            if (result == null) {
                getLotteryData(function (res) {
                    result = res;
                    if (stop == false) {
                        return;
                    }
                    stop = false;
                    run();
                    function run() {
                        count++;
                        for (var j = 1; j <= prizeSort.length; j++) {
                            (function (param) {
                                if (count >= 0 && count <= 2) {
                                    if (speed >= 50) {
                                        speed -= 3;
                                    }
                                } else {
                                    if (speed <= 160) {
                                        speed += 10;
                                    }
                                    if (count >= 4 && count >= 5) {
                                        speed += 45;
                                    }
                                    if (count == 5 && param >= 7) {
                                        speed -= 15;
                                    }
                                }

                                rotateTimer = setTimeout(function () {
                                    if (stop == false) {
                                        prizeSort.eq(param - 1).addClass("activated").siblings().removeClass('activated');
                                        if (count >= 5 && result.index == prizeSort.eq(param - 1).attr("data-index-prize")) {
                                            clearTimeout(rotateTimer);
                                            count = 0;
                                            speed = 150;
                                            stop = true;
                                            setTimeout(function () {
                                                $('#myModal').modal('hide');
                                                $("#jsYouGetPrize").html(result.name);
                                                $('#success').modal();
                                                $("#js_run").attr('disabled', false).removeClass('activated');
                                                result = null;
                                            }, 1000)
                                            return;
                                        }
                                        if (param >= 8) {
                                            run();
                                        }
                                    }
                                }, speed * j)
                            })(j)
                        }
                    }
                }, function (errorMsg) {
                    alertTip(errorMsg);
                    stop = true;
                    result = null;
                    $("#js_run").attr('disabled', false).removeClass('activated');
                });
            }
        })
    function getLotteryData(success, error) {
        $.ajax({
                    url: 'module/first_regist_luckly/fun',
                    type: 'GET',
                    dataType: 'json',
                    data: {act: 'do_lottery'},
                })
                .done(function (res) {
                    if (res.status == 1) {
                        success(res);
                    } else {
                        error(res.msg || '');
                    }
                })
                .fail(function () {
                    error('Error,Please try again later!');
                })
                .always(function () {
                });
    }
</script>