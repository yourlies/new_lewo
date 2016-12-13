<?php
return array(
	/*数据库配置*/
	//数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'lewo', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'changle815123', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'lewo_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增

	/*默认配置*/
	'DEFAULT_M_LAYER'       =>  'Model', // 默认的模型层名称
	'DEFAULT_C_LAYER'       =>  'Controller', // 默认的控制器层名称
	'DEFAULT_V_LAYER'       =>  'View', // 默认的视图层名称
	'DEFAULT_LANG'          =>  'zh-cn', // 默认语言
	'DEFAULT_THEME'         =>  '', // 默认模板主题名称
	'DEFAULT_MODULE'        =>  'Home',  // 默认模块
	'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
	'DEFAULT_ACTION'        =>  'index', // 默认操作名称
	'DEFAULT_CHARSET'       =>  'utf-8', // 默认输出编码
	'DEFAULT_TIMEZONE'      =>  'PRC',  // 默认时区
	'DEFAULT_AJAX_RETURN'   =>  'JSON',  // 默认AJAX 数据返回格式,可选JSON XML ...
	'DEFAULT_JSONP_HANDLER' =>  'jsonpReturn', // 默认JSONP格式返回的处理方法
	'DEFAULT_FILTER'        =>  'htmlspecialchars', // 默认参数过滤方法 用于I函数...

	'LOG_RECORD' => true, // 开启日志记录
	
	/*模块配置*/
	'MODULE_ALLOW_LIST'    =>    array('Admin','Home'),
	'DEFAULT_MODULE'       =>    'Home',

	'SESSION_AUTO_START' => true,

	/*过滤*/
	'VAR_FILTERS'=>'htmlspecialchars',

	'TMPL_PARSE_STRING'  =>array(
     '__UPLOAD__' => '/Uploads', // 增加新的上传路径替换规则
	),

	/**
	 * [参数定义]
	 **/
	//容许进入后台的类型
	'allow_enter_type' => array(2,3,4,99),

	'city_id'=>array(1=>"广州",2=>"重庆"),

	//[bill_type]账单类型
	'bill_type'=>array(1=>"定金",2=>"合同",3=>"日常",5=>"拖欠押金",6=>"其他",7=>"合同欠款",8=>"日常欠款",9=>"其他欠款",10=>"账单更改"),
	'bill_type_dj'=>1,
	'bill_type_ht'=>2,
	'bill_type_rc'=>3,
	'bill_type_tqyj'=>5,
	'bill_type_qt'=>6,
	'bill_type_htqk'=>7,
	'bill_type_rcqk'=>8,
	'bill_type_qtqk'=>9,
	'bill_type_zdgg'=>10,

	//支付类型 1:支付宝(线上) 2:支付宝(线下) 3：微信(线上) 4:微信(线下) 5:押金抵扣 6:现金 7.银行卡 8.退租
	'pay_type'=>array(1=>"支付宝(在线)",2=>"支付宝(转账)",3=>"微信(在线)",4=>"微信(转账)",5=>"押金抵扣",6=>"现金",7=>"银行卡",9=>"错误扣除",10=>"违约退房"),

	//合同状态合同状态 1.正常,2.换房,3转租 4.正常退租 5.违约退租
	'contract_status_arr'=>array(0=>"无效",1=>"正常",2=>"换房",3=>"转租",4=>"正常退租",5=>"违约退租"),
	
	//手续费
	'handling_percent' => 0.3,

	//应缴费日和最迟缴费日的距离
	'should_pay_times' => 5,

	//待办操作人类型 帐号类型： 1管家 2财务 3后勤
	'admin_type_arr' => array(1=>'管家',2=>'财务',3=>'后勤',4=>'出纳',99=>'管理员'),
	'admin_type_gj'=>1,
	'admin_type_cw'=>2,
	'admin_type_hq'=>3,
	'admin_type_cn'=>4,
	'admin_type_gly'=>99,

	//待办工作类型 1：退房 2：转房 3：换房 4：缴定 5:例行打款
	/*'schedule_type' => array(1=>'退房',2=>'转房',3=>'换房',4=>'交定',5=>'例行打款'),*/

	//房间号
	'room_sort_arr' => array('A'=>"A房间",'B'=>"B房间",'C'=>"C房间",'D'=>"D房间",'E'=>"E房间",'F'=>"F房间",'G'=>"G房间",'H'=>"H房间"),

	//人数
	'person_count_arr' => array(1,2,3,4),

	//待办类型
	'schedule_type_arr' => array(1=>"退房",2=>"转房",3=>"换房",4=>"交定",5=>"例行打款"),
	'schedule_type_tf' => 1,
	'schedule_type_zf' => 2,
	'schedule_type_hf' => 3,
	'schedule_type_jd' => 4,
	'schedule_type_lxdk' => 5,

	//合同类型1.正常,2.换房,3转租 4.正常退租 5.违约退租
	'ht_zc'=>1,
	'ht_hf'=>2,
	'ht_zz'=>3,
	'ht_zctz'=>4,
	'ht_hytz'=>5,

	//房间状态 0：未租 1：缴定 2:已租
	'room_wz'=>0,
	'room_jd'=>1,
	'room_yz'=>2,

	//退款方式
	"refund_type"=>array(1=>'支付宝',2=>'微信',3=>'银行卡',4=>'现金'),

	//退房类型
	'check_out_type'=>array(1=>'正常退房',2=>'换房',3=>'转房',4=>'违约退房'),

	//退房检查内容
	'check_item'=>array(1=>'室内',2=>'室外',3=>'房门',4=>'冰箱',5=>'衣柜',6=>'洗衣机',7=>'书桌',8=>'客厅灯',9=>'床垫',10=>'厕所灯',11=>'床头柜',12=>'热水器',13=>'床体',14=>'下水道',15=>'空调',16=>'空调遥控器',17=>'窗户',18=>'窗帘',19=>'房间灯'),
	//退房交接物品
	'check_out_goods'=>array(1=>'门禁卡',2=>'大门钥匙',3=>'小门钥匙',4=>'空调遥控板'),
);