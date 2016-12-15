<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">    
<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
<meta http-equiv="description" content="This is my page">
<title>本期账单</title>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width"><meta name="format-detection" content="email=no,address=no,telephone=no">
<link href="/Public/css/normalize.css" rel="stylesheet" type="text/css">
<link href="/Public/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/css/bootstrap-theme.min.css" type="text/css">
<script src="/Public/js/jquery.min.js"></script>
<script src="/Public/js/bootstrap.min.js"></script>
<link href="/Public/css/common.css" rel="stylesheet" type="text/css">
<link href="/Public/css/bill.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/Public/js/iscroll.js"></script>
<script type="text/javascript">
var myScroll;

function loaded () {
	myScroll = new IScroll('#wrapper', { mouseWheel: true, click: true });
	         
	/*if('1' == '1'){
		document.getElementById("jf").style.display="none";
	}
	if('1' == '0'){
		document.getElementById("pz").value="返回";
		document.getElementById("pz").setAttribute("onclick", "toIndex();");
	}
	if('0.0' == '0.0'){
		document.getElementById("fz").style.display="none";
	}*/
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false)
function toIndex(){
	location.href="toAccountIndex.do";
}


</script>
<style>
#wrapper {
	position:absolute;
	top:44px;
	bottom:0px;
	left:0;
	width:100%;
	overflow:hidden;
	background-color:#f4f4f4;
	}
.img-responsive {
	display: inline-block;
	height: auto;
	max-width: 100%;
}
</style>

</head>

<body onload="loaded()">
<!--遮盖层头-->
<!--<div id="loading" style="width:auto; height:1080px; background-color:rgba(51,51,51,0.4); z-index:100; position:relative;">
	<img src="view/images/loading1.gif" class="load-h-w" />
</div>-->
<!--遮盖层尾-->
<div id="fwxx">
	<div class="container-fluid header">
    	<div class="row-fluid">
        	<div class="col-xs-3 header-icon1"><a href="javascript:window.history.go(-1)"><div class="retun-db-box"><img src="/Public/images/icon-db-retun.png" class="icon-db-retun"></div></a></div>
         <div class="col-xs-3"></div><div class="col-xs-3"></div>  <!--  <div class="col-xs-3 header-icon2-1"><a href="#"><div class="header-icon2-1icon"><img src="view/images/icon-tenant-but-wdj.png" class="icon-db-but-z" /></div><span class="header-icon1-1text">本期账单</span></a></div>
            <div class="col-xs-3 header-icon2-2"><a href="#"><div class="header-icon2-2icon"><img src="view/images/icon-tenant-but-ydj.png" class="icon-db-but-y" /></div><span class="header-icon2-2text">往前账单</span></a></div> -->
            <div class="col-xs-3 header-icon3"><a href="<?php echo U('Home/Tenant/feelist');?>"><div class="menu-db-box"><img src="/Public/images/icon-db-menu.png" class="icon-db-menu"></div></a></div>
        </div>
    </div>
    <div id="wrapper">
        <div>
			<div class="container-fluid" id="XS">
                <div class="row-fluid">
                    <div class="col-xs-12">
                        <div class="row-fluid">
                            <div class="col-xs-12 banner-box-bjs">
                                <div class="row-fluid">
                                    <div class="col-xs-12 banner-box-bjt"><img src="/Public/images/icon-tenant-top.png" class="icon-tenant-top">
                                        <div class="row-fluid">
                                            <div class="col-xs-12"><span class="banner-title-1"><?php echo ($bill_info["area_name"]); ?>(<?php echo ($bill_info["building"]); ?>-<?php echo ($bill_info["floor"]); ?>-<?php echo ($bill_info["door_no"]); ?>)&nbsp;&nbsp;<?php echo ($bill_info["house_code"]); ?></span></div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="col-xs-6 banner-title-2"><p class="banner-title-2-1">本期费用</p></div>
                                            <div class="col-xs-6 banner-title-3"><p class="banner-title-3-1">￥<?php echo ($bill_info["price"]); ?></p></div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="col-xs-6 banner-title-4"><p class="banner-title-4-1">最晚支付</p><p class="banner-title-4-2">最晚时间 
												<?php echo ($bill_info["should_date"]); ?></p></div>
                                            <div class="col-xs-6 banner-title-5">
                                                <?php if($bill_info['pay_status'] == 1): ?><p class="banner-title-5-1">已完成</p>
                                                    <?php else: ?>
                                                    <p class="banner-title-5-1">未完成</p><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="col-xs-12"><div class="innre-bill-image-ia"></div></div>
                        </div>
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-ene" aria-expanded="false" class="collapsed">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($bill_info["total_energy_fee"]); ?></li>
                                    <li class="innre-bill-text1">电费</li><br>
                                    <li class="innre-bill-text2"><?php echo ($bill_info["input_year"]); ?>年<?php echo ($bill_info["input_month"]); ?>月</li>
                                    </ul>
                                </div>
                                </a>
                                <div id="collapseFour-ene" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">本月房屋电表起度:<?php echo ($bill_info["start_energy"]); ?>  止度:<?php echo ($bill_info["end_energy"]); ?> 本月公共区域总电费为: <?php echo ($bill_info["public_energy_fee"]); ?>元<br>
                                    本月房间用电量:<?php echo ($bill_info["room_energy_add"]); ?>度&nbsp;&nbsp;您的房间电费为: <?php echo ($bill_info["room_energy_fee"]); ?>元&nbsp;&nbsp;&nbsp;&nbsp;房内所有人本月居住天数总和:<?php echo ($bill_info["total_person_day"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;你本月居住天数:<?php echo ($bill_info["person_day"]); ?>
                                             <br> 
                                            您的公摊电费为:<?php echo ($bill_info["public_energy_fee"]); ?>÷<?php echo ($bill_info["total_person_day"]); ?>×<?php echo ($bill_info["person_day"]); ?>=<?php echo ($bill_info["energy_fee"]); ?>元<br>                                          
                                    </div>
                                </div>
                                <div class="innre-bill-image-i-box"></div>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-water" aria-expanded="false" class="collapsed">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($bill_info["water_fee"]); ?></li>
                                    <li class="innre-bill-text1">水费</li><br>
                                    <li class="innre-bill-text2"><?php echo ($bill_info["input_year"]); ?>年<?php echo ($bill_info["input_month"]); ?>月</li>
                                    </ul>
                                </div>
                                </a>
                                <div id="collapseFour-water" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">总水起度:<?php echo ($bill_info["start_water"]); ?>  总水止度:<?php echo ($bill_info["end_water"]); ?>  总水费:<?php echo ($bill_info["total_water"]); ?>元&nbsp;&nbsp;&nbsp;&nbsp;房内所有人本月居住天数总和:<?php echo ($bill_info["total_person_day"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;你本月居住天数:<?php echo ($bill_info["person_day"]); ?><br>
                                    		您的水费为: <?php echo ($bill_info["total_water"]); ?>÷<?php echo ($bill_info["total_person_day"]); ?>×<?php echo ($bill_info["person_day"]); ?>=<?php echo ($bill_info["water_fee"]); ?>元
                                    </div>
                                </div>
                                <div class="innre-bill-image-i-box"></div>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-gas" aria-expanded="false" class="collapsed">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($bill_info["total_gas_fee"]); ?></li>
                                    <li class="innre-bill-text1">气费</li><br>
                                    <li class="innre-bill-text2"><?php echo ($bill_info["input_year"]); ?>年<?php echo ($bill_info["input_month"]); ?>月</li>
                                    </ul>
                                </div>
                                </a>
                                <div id="collapseFour-gas" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">总气起度:<?php echo ($bill_info["start_gas"]); ?>  总气止度:<?php echo ($bill_info["end_gas"]); ?>   总气费:<?php echo ($bill_info["total_gas"]); ?>元&nbsp;&nbsp;&nbsp;&nbsp;房内所有人本月居住天数总和:<?php echo ($bill_info["total_person_day"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;你本月居住天数:<?php echo ($bill_info["person_day"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;<br>
                                    		您的气费为: <?php echo ($bill_info["total_gas"]); ?>÷<?php echo ($bill_info["total_person_day"]); ?>×<?php echo ($bill_info["person_day"]); ?>=<?php echo ($bill_info["gas_fee"]); ?>元&nbsp;&nbsp;燃气垃圾处理费:<?php echo ($bill_info["rubbish_fee"]); ?>
                                    </div>
                                </div>
                                <div class="innre-bill-image-i-box"></div>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        
                    <?php if($bill_info['type'] == 1): ?><!--点击弹出开-->
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wg" aria-expanded="false" class="collapsed">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($bill_info["wgfee_unit"]); ?></li>
                                    <li class="innre-bill-text1">物业费</li><br>
                                    <li class="innre-bill-text2"><?php echo ($bill_info["input_year"]); ?>年<?php echo ($bill_info["input_month"]); ?>月</li>
                                    </ul>
                                </div>
                                </a>
                                <div id="collapseFour-wg" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <div class="panel-body">总物业费:<?php echo ($WYfee); ?>元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                                    		物业费为: <?php echo ($WYfee); ?>/房间数=<?php echo ($bill_info["wgfee_unit"]); ?>元
                                    </div>
                                </div>
                                <div class="innre-bill-image-i-box"></div>
                            </div>
                        </div>
                        <!--点击弹出关-->
                        
                        <!--点击弹出开-->
                        <?php if($bill_info["rent_fee"] != 0): ?><div id="fz">
                        <div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-fz">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($bill_info["rent_fee"]); ?></li>
                                    <li class="innre-bill-text1">房租</li><br>
                                    <li class="innre-bill-text2"><?php echo ($bill_info["rent_des"]); ?></li>
                                    </ul>
                                </div>
                                </a>
                                <div id="collapseFour-fz" class="panel-collapse collapse">
                                    <div class="panel-body"><?php echo ($bill_info["rent_des"]); ?>
                                    </div>
                                </div>
                                <div class="innre-bill-image-i-box"></div>
                            </div>
                        </div><?php endif; ?>
                        <!--点击弹出关-->
                        <!--点击弹出开-->
                        <?php if($bill_info["service_fee"] != 0): ?><div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-sv">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($bill_info["service_fee"]); ?></li>
                                    <li class="innre-bill-text1">服务费</li><br>
                                    <li class="innre-bill-text2"><?php echo ($bill_info["service_fee_des"]); ?></li>
                                    </ul>
                                </div>
                                </a>
                                <div id="collapseFour-sv" class="panel-collapse collapse">
                                    <div class="panel-body">
                                    	<?php echo ($bill_info["service_fee_des"]); ?>
                                    </div>
                                </div>
                                <div class="innre-bill-image-i-box"></div>
                            </div>
                        </div><?php endif; endif; ?>
                        <?php if($bill_info['wx_fee'] != 0): ?><div class="row-fluid">
                            <div class="col-xs-12 innre-bill-box">
                                <a data-toggle="collapse" data-parent="#accordion" href="http://mp.loveto.co/#collapseFour-wx">
                                <div class="panel-heading innre-bill-image-i">
                                    <ul>
                                    <li class="innre-bill-logo"><img src="/Public/images/icon-tenant-logo.png" class="icon-tenant-logo"></li>
                                    <li class="innre-bill-top"><img src="/Public/images/icon-tenant-bot.png" class="icon-tenant-bot"></li>
                                    <li class="innre-bill-text3">￥<?php echo ($bill_info["wx_fee"]); ?></li>
                                    <li class="innre-bill-text1">维修费</li><br>
                                    <li class="innre-bill-text2"><?php echo ($bill_info["input_year"]); ?>年<?php echo ($bill_info["input_month"]); ?>月</li>
                                    </ul>
                                </div>
                                </a>
                                <div id="collapseFour-wx" class="panel-collapse collapse">
                                    <div class="panel-body">
                                    	<?php echo ($bill_info["wx_des"]); ?>
                                    </div>
                                </div>
                                <div class="innre-bill-image-i-box"></div>
                            </div>
                        </div><?php endif; ?>
                        </div>
                        <!--点击弹出关-->
                        <!--滑动过度区域-->
                        <div class="row-fluid">
                            <div class="col-xs-12"><div style="height:90px;"></div></div>
                        </div>
                        <!--滑动过度区域-->
                    </div>
                </div>
			</div>          
        </div>  
    </div>
    <div class="container-fluid button">
        <div class="row-fluid button-ds">
            <div class="col-xs-12 padding-left-right-24">
                
            </div>
        </div>
    </div>
    <!--
    <?php if($bill_info['pay_status'] != 1): ?><div class="container-fluid button">
        <div class="row-fluid button-ds">
            <div class="col-xs-12 padding-left-right-10" >
                    <a style="background-color: #68BB9B; display: block; color:#fff; padding: 5px;" href="<?php echo U('Home/Alipay/pay',array('WIDout_trade_no'=>$bill_info['pro_id'],'WIDsubject'=>$bill_info['bill_info'],'WIDtotal_fee'=>$bill_info['price']));?>">立即支付</a>
            </div>
        </div>
    </div><?php endif; ?>
    -->
    <!--  
	<div class="container-fluid button">
    	<div class="row-fluid button-ds">
	       	<div class="col-xs-6 padding-left-right-10" >
	       		<div class="button-ckzi1" >
	         		<input name="#" type="button" value="查看凭证" id="pz" class="button-htzh-dx" />
	       		</div>
        	</div>
      		<div class="col-xs-6 padding-left-right-10" >
        		<div class="button-ckzi2">
            		<input name="#" type="button" value="立即缴费" id="jf" class="button-htzh-dx" />
        		</div>
			</div>
		</div>-->
</div>
<script type="text/javascript">
   $(function () { $('#collapseFour').collapse({
      toggle: false
   })});
   $(function () { $('#collapseTwo').collapse('show')});
   $(function () { $('#collapseThree').collapse('toggle')});
   $(function () { $('#collapseOne').collapse('hide')});
   
</script>  


</body></html>