<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>管家中心</title>
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="expires" content="0">    
  <meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
  <meta http-equiv="description" content="This is my page">
  <link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
  <script src="/Public/js/jquery-1.9.1.js" type="text/javascript"></script>
  <link href="/Public/css/bootstrap.min.css" rel="stylesheet">
  <link href="/Public/css/common.css" rel="stylesheet" type="text/css">
  <link href="/Public/css/grzx.css" rel="stylesheet" type="text/css">

  <style>
  #schedule_count {
    color:red;
  } 
  #wrapper {
    position: absolute;
    top: 44px;
    bottom: 50px;
    left: 0;
    width: 100%;
    overflow: hidden;
    background-color:#f8f8f8;
  }
  .img-responsive {
    display: inline-block;
    height: auto;
    max-width: 100%;
  }
  .col-xs-12 {
    padding-left: 0;
    padding-right: 0;
  }
  .col-xs-6{
    padding-left: 10px;
    padding-right: 10px;
    }
  .container-fluid {
    padding-left: 0;
    padding-right: 0;
  }
  </style>
</head>

<body>
<div id="fwxx">
  <div class="container-fluid header"></div>
    <div id="wrapper">
      <div>
        <div class="container-fluid">
          <div class="row-fluid">
            <div class="col-xs-12">
              <div class="row-fluid">
                <div class="col-xs-12">
                  <div class="img-icon1-dx">
                    <div class="row-fluid">
                      <div class="col-xs-12">
                        <div class="img-icon1-tx">
                          <?php if(!empty($steward_info["avatar"])): ?><a href="">
                            <img src="<?php echo ($steward_info["avatar"]); ?>" style="margin-top:20px;width:66px;height:66px;border:2px solid #fff;" class="img-circle">
                          </a>
                          <?php else: ?>
                          <a href="">
                            <img src="/Public/images/user.jpg" style="margin-top:20px;width:66px;height:66px;border:2px solid #fff;" class="img-circle">
                          </a><?php endif; ?>
                          
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="col-xs-12">
                        <div class="banner-wz">
                        <span class="banner-wz-tz1"><?php echo (session('steward_nickname')); ?></span>
                        <br/>
                        <a href="<?php echo U('Home/Steward/login_out');?>" class="banner-wz-tz2" style="color:#fff">退出</a></span>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="col-xs-12">
                <div class="border-bot-xt1"></div>
              </div>
            </div>
            
            <div class="row-fluid">
              <div class="col-xs-12 icon-top-h20">
                <div class="row-fluid">
                    <div class="col-xs-4 bg_lg">
                      <span><?php echo ($empty_room_count); ?>(<?php echo ($percent_room); ?>%)</span><br/><span>[&nbsp;空房间&nbsp;]</span>
                    </div>
                    <div class="col-xs-4 bg_lo">
                      <span><?php echo ($empty_bed_count); ?>(<?php echo ($percent_bed); ?>%)</span><br/><span>[&nbsp;空床位&nbsp;]</span>
                    </div>
                    <div class="col-xs-4 bg_lb">
                      <span>￥<?php echo ($JD_total_money); ?></span><br/><span>[&nbsp;缴定金额&nbsp;]</span>
                    </div>
                </div>
                <div class="row-fluid">
                  <div class="col-xs-4 bg_lo">
                      <a class="color-white" href="<?php echo U('Home/Steward/checkbill');?>"><span>&nbsp;</span><br/><span>[&nbsp;集中抄表&nbsp;]</span></a>
                    </div>
                    <div class="col-xs-4 bg_lg">
                      <a class="color-white" href="<?php echo U('Home/Steward/houses');?>"><span>&nbsp;</span><br/><span>[&nbsp;房屋管理&nbsp;]</span></a>
                    </div>
                    <div class="col-xs-4 bg_ly">
                      <a class="color-white" href="<?php echo U('Home/Steward/allhouses',array('select'=>'empty'));?>"><span>&nbsp;</span><br/><span>[&nbsp;空置房源&nbsp;]</span></a>
                    </div>
                    <div class="col-xs-4 bg_ly">
                      <a class="color-white" href="<?php echo U('Home/Steward/stewardtasks');?>"><span>&nbsp;</span><br/><span>[&nbsp;待办工作&nbsp;]<b>[<a id="steward_schedule_count"><?php echo (session('steward_schedule_count')); ?></a>]</b></span></a>
                    </div>
                    <div class="col-xs-4 bg_lb">
                      <a class="color-white" href="<?php echo U('Home/Steward/allbills');?>"><span>&nbsp;</span><br/><span>[&nbsp;账单管理&nbsp;]</span></a>
                    </div>
                </div>
                <div class="row-fluid">
                  <div class="col-xs-12">
                    <div class="border-bot-box-h25"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="button">
    <div class="button_ds">
      <div class="container-fluid">
        <!-- <div class="row-fluid">
          <a href="check-bill.html">
            <div class="col-xs-4"> <img src="images/icon_sy.png" class="img-responsive img-icon3-dx"> <br>
              <span class="nav-bootom-zt">集中抄表</span>
            </div>
          </a>
           <a href="houses.html">
            <div class="col-xs-4"> <img src="images/icon_zf.png" class="img-responsive img-icon3-dx"> <br>
              <span class="nav-bootom-zt">入住</span>
            </div>
          </a>
          <a href="steward-tasks.html">
            <div class="col-xs-4"> <img src="images/icon_wo.png" class="img-responsive img-icon3-dx"> <br>
              <span class="nav-bootom-zt" style="color:#68bb9b;">待办</span>
            </div>
          </a>
        </div> -->
      </div>
    </div>
  </div>
</body>
</html>
<script>
   var refesh_schedule_count_url = "<?php echo U('Home/Steward/refesh_schedule_count');?>";

   //监控待办数目，异步修改#steward_schedule_count
    setInterval(function(){
      $.ajax({
         type: "POST",
         url: refesh_schedule_count_url,
         data: "steward_id="+<?php echo (session('steward_id')); ?>,
         dataType: "json",
         success: function(data){
           $("#steward_schedule_count").html(data.steward_schedule_count);
         }
      });
    },60000);
</script>