<?php
namespace Home\Controller;
use Think\Controller;
class StewardController extends Controller {
	public function __construct(){
		parent::__construct();
		if ( empty($_SESSION['steward_id'])) {
			$this->login();
			die();
		}
	}
    public function index(){
        //待办数量
        $steward_id = $_SESSION['steward_id'];
        $DSchedule = D("schedule");
        $TFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_tf"),0,C("admin_type_gj"));
        $ZFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_zf"),0,C("admin_type_gj"));
        $HFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_hf"),0,C("admin_type_gj"));
        $JDcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_jd"),0,C("admin_type_gj"));
        $_SESSION['steward_schedule_count'] = $TFcount+$ZFcount+$HFcount+$JDcount;

        $today = date("Y-m-d",time());//今天2016-08-19
        $yesterday = date("Y-m-d",strtotime($today." -1 day")); 

        $today_total_money = getTotalMoney($today);//今日总金额：今日新签合同收入的总金额
        $yesterday_total_money = getTotalMoney($yesterday);//昨天总金额：昨天签合同收入的总金额
        $today_sign_count = getSignCount($today);//签约个数：今天签约的个数
        $JD_total_money = getJDMoney($today);//缴定金额：今天缴定金额
        //空房间
        $room_count = getRoomCount();//总房间数量
        $empty_room_count = getEmptyRoomCount();//空房间数量
        $percent_room = $empty_room_count / $room_count;
        $percent_room = $percent_room? ceil($percent_room*100) : 0;
        $this->assign("empty_room_count",$empty_room_count);
        $this->assign("percent_room",$percent_room);
        //空床位
        $bed_count = getBedCount();//总房间数量
        $empty_bed_count = getEmptyBedCount();//空房间数量
        $percent_bed = $empty_bed_count / $bed_count;
        $percent_bed = $percent_bed? ceil($percent_bed*100) : 0;

        $steward_info = M("admin_user")->where(array("id"=>$_SESSION['steward_id']))->find();
        
        $this->assign("steward_info",$steward_info);
        $this->assign("empty_bed_count",$empty_bed_count);
        $this->assign("percent_bed",$percent_bed);
        $this->assign("today_total_money",$today_total_money);
        $this->assign("yesterday_total_money",$yesterday_total_money);
        $this->assign("today_sign_count",$today_sign_count);
        $this->assign("JD_total_money",$JD_total_money);
        $this->display("steward-index");
    }
    /**
    * [重新计算待办未完成数目]
    **/
    public function refesh_schedule_count(){
        //待办数量
        $steward_id = I("steward_id");
        $DSchedule = D("schedule");
        $TFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_tf"),0,C("admin_type_gj"));
        $ZFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_zf"),0,C("admin_type_gj"));
        $HFcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_hf"),0,C("admin_type_gj"));
        $JDcount = $DSchedule->getScheduleCount($steward_id,C("schedule_type_jd"),0,C("admin_type_gj"));
        $_SESSION['schedule_count'] = $TFcount+$ZFcount+$HFcount+$JDcount;
        die(json_encode(array("stewrad_schedule_count"=>$_SESSION['stewrad_schedule_count'])));
    }
    /**
     * [登录]
     **/
    public function login(){
    	if ( !empty($_POST) ) {
    		$where['username'] = I("post.userName");
            $where['password'] = md5(I("post.userPass"));
    		$Muser = M("admin_user");
    		$result = $Muser->where($where)->find();
    		if ( !is_null($result) ) {
                $_SESSION['steward_user'] = I("post.userName");
                $_SESSION['steward_id'] = $result['id'];
                $_SESSION['steward_nickname'] = $result['nickname'];
                $this->redirect('Home/Steward/index');
    		} else {
    			$this->error("登录失败");
    		}
    	} else {
    		$this->display('login');
    	}
    }

    /*
     * [退出登录]
     */
    public function login_out(){
        session_destroy();
        $this->success("退出成功",U("Home/Steward/login"));
    }

    /**
     * [集中查表]
     **/
    public function checkbill(){
        $where = array();
        $where['steward_id'] = $_SESSION['steward_id'];
        $search = I("search");
        $is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['door_no'] = $search_arr['2'];
            }
        } else {
            $where['_string'] = "house_code LIKE '%".$search."%' OR area_name LIKE '%".$search."%'";
        }
        $this->assign("search",$search);
        $DHouses = D("houses");
        $houses = $DHouses->getHouseAndRoom($where);

        $MAmmeter = M("ammeter_house");
        foreach($houses AS $key=>$val){
            $where = array();
            $where['house_id'] = $val['id'];
            $where['input_year'] = date("Y");
            $where['input_month'] = date("m");
            $where['status']  = 1;
            $result = $MAmmeter->where($where)->find();
            if ( !is_null($result) ) {
                $houses[$key]['is_typein'] = true;
            } else {
                $houses[$key]['is_typein'] = false;
            }
        }
        $this->assign("houses", $houses);
        $this->assign("month",date("m"));
        $this->display("check-bill");
        $this->display("Steward/common/footer");
    }

    /**
     * [显示所有集中查表]
     **/
    public function allcheckbill(){
        $where = array();
        $search = I("search");
        $is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['door_no'] = $search_arr['2'];
            }
        } else {
            $where['_string'] = "house_code LIKE '%".$search."%' OR area_name LIKE '%".$search."%'";
        }
        $this->assign("search",$search);

        $DHouses = D("houses");
        $houses = $DHouses->getHouseAndRoom($where);
        $MAmmeter = M("ammeter_house");
        foreach($houses AS $key=>$val){
            $where = array();
            $where['house_id'] = $val['id'];
            $where['input_year'] = date("Y");
            $where['input_month'] = date("m");
            $where['status']  = 1;
            $result = $MAmmeter->where($where)->find();
            if ( !empty($result) ) {
                $houses[$key]['is_typein'] = true;
            } else {
                $houses[$key]['is_typein'] = false;
            }
        }
        $this->assign("houses", $houses);
        $this->assign("month",date("m"));
        $this->display("check-bill");
        $this->display("Steward/common/footer");
    }
    /**
     * [显示该房屋的水电气列表]
     **/
    public function show_ammeter(){
        $house_id = I("house_id");
        $DHouses = D("houses");
        $MArea = M("area");
        $houses_info = $DHouses->where(array("id"=>$house_id))->find();
        $houses_info['area_name'] = $MArea->where(array("id"=>$houses_info['area_id']))->getField("area_name");
        $year = date("Y");
        $month = date("m");
        $lastDate = date("Y-m",strtotime($year."-".$month) - 1);
        $lastYear = date("Y",strtotime($lastDate));
        $lastMonth = date("m",strtotime($lastDate));
        $DAmmeter = D("ammeter_house");
        $DAmmeter->checkHouseAmmeterByDate($house_id,$year,$month);//检查当月是否生成水电气数据
        $DAmmeter->checkHouseAmmeterByDate($house_id,$lastYear,$lastMonth);//检查上个月是否生成水电气数据

        //检测房屋是否按间或者按床位，按床位不用电表

        

        $ammter_list = $DAmmeter->getHouseAmmeterById($house_id);
        $this->assign('house_id',$house_id);
        $this->assign('houses_info',$houses_info);
        $this->assign('ammter_list',$ammter_list);
        $this->display("show-ammeter");
        $this->display("Steward/common/footer");    
    }
    /**
    * [写入水电气]
    **/
    public function type_in(){
        if ( !empty($_POST) ) {
            $is_clear = I("is_clear");
            $is_clear = !empty($is_clear)?I("is_clear"):0;
            $amme_id = I("amme_id");
            $house_id = I("house_id");
            $year = I("input_year");
            $month = I("input_month"); 
            $lastDate = date("Y-m",strtotime($year."-".$month) - 1);
            $lastYear = date("Y",strtotime($lastDate));
            $lastMonth = date("m",strtotime($lastDate));

            $DAmmeter = D("ammeter_house");
            $DAmmeterROOM = D("ammeter_room");
            //获取上个月的房屋度数
            $last_ammeter_house_info = $DAmmeter->where(array("house_id"=>$house_id,"input_year"=>$lastYear,"input_month"=>$lastMonth))->find();
            if ( !is_null($last_ammeter_house_info) && $is_clear != 1) {
                //判断录入该月是否小于上个月的度数
                if ( I("zD") < $last_ammeter_house_info['total_energy'] ) {
                    $this->error("录入的电度数:".I("post.zD")."少于最新电度数:".$last_ammeter_house_info['total_energy']."，请检查!","",10);
                }
                if ( I("zS") < $last_ammeter_house_info['total_water'] ) {
                     $this->error("录入的水度数:".I("post.zS")."少于最新水度数:".$last_ammeter_house_info['total_water']."，请检查!","",10);
                }
                if ( I("zQ") < $last_ammeter_house_info['total_gas'] ) {
                    $this->error("录入的气度:".I("post.zQ")."少于最新气度数:".$last_ammeter_house_info['total_gas']."，请检查!","",10);
                }
            }
            
            
            foreach ( I('roomD') AS $key=>$val ) {
                //获取上个月 房间的电表
                $last_ammeter_room_info = $DAmmeterROOM->where(array("room_id"=>$val['room_id'],"house_id"=>$house_id,"input_year"=>$lastYear,"input_month"=>$lastMonth))->find();
                if ( !is_null($last_ammeter_room_info) && $is_clear != 1) {
                    if ( $val['room_energy'] < $last_ammeter_room_info['room_energy'] ) {
                        $this->error($val['room_code']."该房间电度数低于上个月!请重新录入");
                    }
                }
                $DAmmeterROOM->updateAmmeterRoomById($key,$val['room_energy']);
            }

            $return = $DAmmeter->updateAmmeterById($amme_id,$_POST);
            $this->success("保存成功",U('Home/Steward/checkbill'));

        } else {
            $amme_id = I("amme_id");
            $house_id = I("house_id");
            $input_year = I("input_year");
            $input_month = I("input_month");
            $this->assign("house_id",$house_id);
            $this->assign("input_year",$input_year);
            $this->assign("input_month",$input_month);

            $DHouse = D("houses");
            $house_info = $DHouse->getHouseById($house_id);
            $this->assign("house_info",$house_info);

            $DAmmeter = D("ammeter_house");
            $house_ammeter = $DAmmeter->getAmmeterById($amme_id);
            $this->assign("house_ammeter",$house_ammeter);

            $DAmmeterRoom = D("ammeter_room");
            
            $aRoom_list = $DAmmeterRoom->getAmmeterRoom($house_id,$input_year,$input_month);
            $this->assign("aRoom_list",$aRoom_list);
            $this->assign('amme_id',$amme_id);
            $this->display("type-in");
        }
    }

    /**
     * [入住--房屋列表]
     **/
    public function houses(){
        $where = array();
        $_SESSION['stewrad_houses_back_url'] = U('Home/Steward/houses'); 
        $where['steward_id'] = $_SESSION['steward_id'];
        $search = I("search");
        $is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['door_no'] = $search_arr['2'];
            }
        } else {
            $where['_string'] = "house_code LIKE '%".$search."%' OR area_name LIKE '%".$search."%'";
        }
        $this->assign("search",$search);
        
        $DHouses = D("houses");
        $this->assign("houses",$DHouses->getHouseAndRoom($where));
        $this->display('houses');
        $this->display("Steward/common/footer");
    }

    public function tenant_info(){
        $account_id = I("account_id");
        $room_id = I("room_id");
        $MAccount = M("account");
        $MRoom = M("room");
        $MHouses = M("houses");
        $MArea = M("area");
        $MAccount->startTrans();
        if ( !empty($_POST) ) {
            //修改租客信息
            $save['realname'] = I("realname"); 
            $save['mobile'] = I("mobile");
            $save['email'] = I("email");
            $save['sex'] = I("sex");
            $save['card_no'] = I("card_no");
            $save['birthday'] = I("birthday");
            $save['sleep_habit'] = I("sleep_habit");
            $save['tag'] = I("tag");
            $save['job'] = I("job");
            $save['hobby'] = I("hobby");
            $save['specialty'] = I("specialty");
            $save['edu_history'] = I("edu_history");
            $result = $MAccount->where(array('id'=>$account_id))->save($save);

            if ($result == 1) {
                $MAccount->commit();
                $this->success("修改成功!");
            } else {
                $MAccount->rollback();
                $this->error("修改失败!");
            }
        } else {
            $account_info = $MAccount->where(array("id"=>$account_id))->find();
            $room_info = $MRoom
                        ->alias('r')
                        ->field('r.id,r.room_code,r.bed_code,h.building,h.floor,h.door_no,area.area_name')
                        ->join('lewo_houses h ON r.house_code=h.house_code','left')
                        ->join('lewo_area area ON area.id=h.area_id','left')
                        ->where(array('r.id'=>$room_id))
                        ->find();

            $this->assign('check_out_type',C('check_out_type'));
            $this->assign("area_name",$area_name);
            $this->assign("account_info",$account_info);
            $this->assign("room_info",$room_info);
            $this->display("tenant-info");
        }
    }

    public function total_daily_room_fee(){
        header("Content-type: text/html; charset=utf-8"); 
        $account_id = I("account_id");
        $pro_id = I("pro_id");
        $DArea = D("area");
        $MArea = M("area");
        $DCharge = D("charge_house");
        $DChargeBill = D("charge_bill");
        $DHouses = D("houses");
        $DAmmeterHouse = D("ammeter_house");
        $bill_info = $DChargeBill->getDetailBillById($pro_id);
        $bill_info['total_energy_fee'] = $bill_info['energy_fee'] + $bill_info['room_energy_fee'];
        $bill_info['total_gas_fee'] = $bill_info['gas_fee'] + $bill_info['rubbish_fee'];
        $bill_info['service_fee_des'] = preg_replace('/房租/', '服务费', $bill_info['rent_des']);

        if ( $bill_info['account_id'] != $account_id){
            $this->error("非法查看~");
        }
        $area_id = $DHouses->getAreaIdByCode($bill_info['house_code']);
        $house_id = $DHouses->getHouseIdByCode($bill_info['house_code']);
        
        $WYfee = $DHouses->getWYfee($bill_info['house_code']);
        $this->assign("WYfee",$WYfee);
        $this->assign("bill_info",$bill_info);
        $this->display("detail-fee");
        
    }

    /*
     * author: changle
     */
    public function allbills(){
        //获取账单类别
        //$type = I("select");
        //$_SESSION['stewrad_houses_back_url'] = U('Home/Steward/allhouses'); 
        $where = [];
        $search = trim(I("search"));
        $chips = empty($search) ? '' : explode('-', $search);
        if (isset($chips[0])) {
            $where['building'] = $chips[0];
        }
        if (isset($chips[1])) {
            $where['floor'] = $chips[1];
        }
        if (isset($chips[2])) {
            $where['door_no'] = $chips[2];
        }
        /*
        //$is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['door_no'] = $search_arr['2'];
            }
        } else {
            $where['_string'] = "house_code LIKE '%".$search."%' OR area_name LIKE '%".$search."%'";
        }
        */
        //$this->assign("search",$search);
        //输出房屋类别
        //$this->assign("type", $type);
        $type = '';
        $DPay = D('pay');
        $bills = $DPay->getBills($where, $type);
        //echo'<pre>';print_r($bills);
        if (!empty($search)) {
            $this->assign('search_history', $search);
        }
        $this->assign('houses', $bills);
        $this->display('bills');
        $this->display("Steward/common/footer");
    }

    /**
     * [入住--房屋列表]
     **/
    public function allhouses(){
        //获取房屋类别
        $type = I("select");
        $_SESSION['stewrad_houses_back_url'] = U('Home/Steward/allhouses'); 
        $where = array();
        $search = I("search");
        $is_has_flag = strpos($search, '-');
        if ( $is_has_flag && !empty($search)) {
            $search_arr = explode('-',$search);
            if ( !is_null($search_arr['0']) ) {
                $where['building'] = $search_arr['0'];
            }
            if ( !is_null($search_arr['1']) ) {
                $where['floor'] = $search_arr['1'];
            }
            if ( !is_null($search_arr['2']) ) {
                $where['door_no'] = $search_arr['2'];
            }
        } else {
            $where['_string'] = "house_code LIKE '%".$search."%' OR area_name LIKE '%".$search."%'";
        }
        $this->assign("search",$search);
        //输出房屋类别
        $this->assign("type", $type);
        $DHouses = D("houses");
        $this->assign("houses",$DHouses->getHouseAndRoom($where, $type));
        $this->display('houses');
        $this->display("Steward/common/footer");
    }
    /**
     * [入住-房间信息]
     **/
    public function roominfo(){
        $id = I("id");
        $DHouses = D('Houses');
        $info = $DHouses->getRoom($id);
        $otherRoom = $DHouses->getOtherRoom($info['house_code'],$id);
        $this->assign('otherRoom',$otherRoom);
        $this->assign('info',$info);
        $this->display('room-info');
    }
    /**
     * [入住-缴定]
     **/
    public function order(){
        if ( !empty($_POST) ) {
            $appoint_time = I("post.appoint_time");
            $today = date('Y-m-d',time());
            if ( strtotime($appoint_time) < strtotime($today) ) {
                $this->error('约定时间不能少于现在的时间');
            }
            $data['realname'] = I('post.realname');
            $data['password'] = md5("123456");
            $data['mobile'] = I('post.mobile');
            $data['register_time'] = date("Y-m-d H:i:s",time());

            $Maccount = M("account");
            $DPay = D('pay');
            $result = $Maccount->where(array('mobile'=>$data['mobile']))->find();
            if ( $result == null ) {               
                $id = $Maccount->add($data); // 生成帐号后返回ID
            } else {
                $id = $result['id'];
            }
            if ( $id ) {
                //缴定后 插入到待办中 并生成账单
                $sch['room_id'] = I('post.id');
                $sch['money'] = I('post.money');
                $sch['account_id'] = $id;
                $sch['mobile'] = $data['mobile'];
                $sch['schedule_type'] = 4; //缴定
                $sch['status'] = 1;
                $sch['appoint_time'] = $appoint_time;
                $sch['msg'] = I("post.msg");
                $sch['steward_id'] = $_SESSION['steward_id'];
                $sch['admin_type'] = C("admin_type_gj");
                $DSchedule = D("schedule");
                $result2 = $DSchedule->addOneSchedule($sch);

                $param = array(
                            'account_id'=>$id,
                            'room_id'=>$sch['room_id'],
                            'bill_type'=>1,
                            'price'=>$sch['money'],
                            );
                $DPay->create_bill($param);
                //插入lewo_pay bill_type为1 的缴定账单 未做
                if ( $result2 ) {
                    M("room")->where(array("id"=>$sch['room_id']))->save(array("account_id"=>$id,"status"=>1));//将房间修改成缴定状态
                    $this->success("缴定成功！",U("Home/Steward/houses"));
                } else {
                    //缴定失败也把帐号摧毁 待定
                     $this->error("缴定失败！");
                }
            }
        } else {
            $id = I('id');
            $DRomm = D("houses");
            $this->assign('room_info',$DRomm->getRoom($id));
            $this->assign('pay_type_list',C('pay_type'));
            $this->display("order");
        }
    }
    /**
     * [入住-缴定后-待办-签约]
     **/
    public function order_checkin(){
        if ( !empty($_POST) ) {
            $DContract = D("contract");
            $post = $_POST;
            $schedule_id = I("schedule_id");//缴定待办id
            $DContract = D("contract");
            $room_id = I("room_id");
            $house_code = M("room")->where(array("id"=>$room_id))->getField("house_code");
            $end_date = M("houses")->where(array("house_code"=>$house_code))->getField("end_date");//托管结束日
            if ( strtotime($_POST['startDate']) > strtotime($_POST['endDate']) ) {
                //判断租期结束日是否大于房间托管结束日
                $this->error("签约失败,租期开始日:".$_POST['startDate']." 大于 租期结束日:".$_POST['endDate'],'',10);
            }
            if ( strtotime($_POST['endDate']) > strtotime($end_date) ) {
                //判断租期结束日是否大于房间托管结束日
                $this->error("签约失败,租期结束日:".$_POST['endDate']." 大于 房间托管结束日:".$end_date);
            }
            
            if ( !empty($_FILES['photo']['name']['0']) ) {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
                $upload->savePath  =     ''; // 设置附件上传（子）目录
                
                $info   =   $upload->upload();

                if(count($info) != 0) { 
                    foreach( $info AS $key=>$val ){
                        if ( $val['key'] == 'photo' ) {
                            $post['photo'] = $val['savepath'].$val['savename'];
                        }
                    }
                }
            }

            $result = $DContract->add($post);

            if ( $result['result'] ) {
                M("schedule")->where(array("id"=>$schedule_id))->save(array("is_finish"=>1));
                $this->success("合同生成成功!",U("Home/Steward/check_contract",array("id"=>$result['id'])));
            } else {
                $this->error($result['error_msg'].",签约失败0。0",'',10);
            }
        } else {
            $id = I('schedule_id');//schedule_id待办id
            $DRoom = D("houses");
            $DSchedule = D("schedule");
            $schedule_info = $DSchedule->getScheduleByID($id);
            $this->assign("schedule_id",$id);
            $this->assign("account_id",$schedule_info['account_id']);
            $this->assign('schedule_info',$schedule_info);
            $this->assign('today',date("Y-m-d",time()));
            $this->assign('room_info',$DRoom->getRoom($schedule_info['room_id']));
            $this->display("check-in");
        }
    }

    /**
     * [入住-签约]
     **/
    public function checkin(){
        if ( !empty($_POST) ) {   
            $DContract = D("contract");
            $post = $_POST;         
            $room_id = I("room_id");
            $house_code = M("room")->where(array("id"=>$room_id))->getField("house_code");
            $end_date = M("houses")->where(array("house_code"=>$house_code))->getField("end_date");//托管结束日
            if ( strtotime($_POST['startDate']) > strtotime($_POST['endDate']) ) {
                //判断租期结束日是否大于房间托管结束日
                $this->error("签约失败,租期开始日:".$_POST['startDate']." 大于 租期结束日:".$_POST['endDate'],'',10);
            }
            if ( strtotime($_POST['endDate']) > strtotime($end_date) ) {
                //判断租期结束日是否大于房间托管结束日
                $this->error("签约失败,租期结束日:".$_POST['endDate']." 大于 房间托管结束日:".$end_date);
            }

            if ( !empty($_FILES['photo']['name']['0']) ) {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
                $upload->savePath  =     ''; // 设置附件上传（子）目录
                
                $info   =   $upload->upload();

                if(count($info) != 0) { 
                    foreach( $info AS $key=>$val ){
                        if ( $val['key'] == 'photo' ) {
                            $post['photo'] = $val['savepath'].$val['savename'];
                        }
                    }
                }
            }
            

            $result = $DContract->add($post);
            if ( $result['result'] ) {
                //显示合同详情信息
                $this->success("合同生成成功!",U("Home/Steward/check_contract",array("id"=>$result['id'])));
                /*$this->success("合同生成成功,请到个人页面进行支付",U("Home/Steward/houses"));*/
            } else {
                $this->error($result['error_msg']);
            }
        } else {
            $id = I('id');//room_id
            $DRoom = D("houses");
            $this->assign('today',date("Y-m-d",time()));
            $this->assign('room_info',$DRoom->getRoom($id));
            $this->display("check-in");
        }
    }

    /**
    * [查看合同账单]
    **/
    public function check_contract(){
        $id = I("id");
        $MPay = M("pay");
        $contract_info=$MPay
                    ->field('a.*,c.*,p.*')
                    ->alias('p')
                    ->join('lewo_contract c ON p.pro_id=c.pro_id')
                    ->join('lewo_account a ON a.id=c.account_id')
                    ->where(array('p.id'=>$id))
                    ->find();
        
        $rent_type = explode("_",$contract_info['rent_type']);

        $contract_info['rent_type'] = $rent_type;
        $this->assign("contract_info",$contract_info);
        $this->display("detail-contract");
    }

    //管家查看的租客合同 自有管家才能进
    public function tenant_contract(){
        $account_id = I('account_id');
        $room_id = I('room_id');
        $pro_id = I('pro_id');
        $filters = empty($pro_id)
        ? ['c.account_id' => $account_id, 'c.room_id' => $room_id]
        : ['c.pro_id' => $pro_id];
        $MContract = M('contract');
        $contract_list = $MContract
        ->alias('c')
        ->field('a.mobile,a.realname,c.deposit,c.book_deposit,c.balance,c.period,c.rent_type,c.contract_status,c.actual_end_time,c.start_time,c.end_time,c.rent_date,c.rent,c.fee,c.contact2,c.person_count,c.photo,c.wg_fee,c.total_fee,c.pro_id,r.room_code,r.house_code,h.area_id,area.area_name,p.price')
        ->join('lewo_pay p ON p.pro_id = c.pro_id')
        ->join('lewo_account a ON a.id = c.account_id')
        ->join('lewo_room r ON r.id = c.room_id')
        ->join('lewo_houses h ON h.house_code = r.house_code')
        ->join('lewo_area area ON h.area_id = area.id')
        ->where($filters)
        ->select();
        foreach ($contract_list as $key => $val) {
            $contract_list[$key]['rent_type'] = explode('_', $val['rent_type']);
        }
        $this->assign('contract_list',$contract_list);
        $this->display('tenant-contract');
    }

     /**
    * [发送立刻缴费]
    **/
    public function send_contract(){
        $pro_id = I("pro_id");
        $pay_info = M("pay")->where(array("pro_id"=>$pro_id))->find();
        $mobile = M("account")->where(array("id"=>$pay_info['account_id']))->getField("mobile");
        M("pay")->where(array("pro_id"=>$pro_id))->save(array('is_send'=>1));
        Vendor('ChuanglanSms.chuanglanSmsApi');
        $clapi  = new \ChuanglanSmsApi();

        $msg = '欢迎你来到乐窝~请到个人页面进行支付 http://'.$_SERVER['HTTP_HOST'];
        $result = $clapi->sendSMS($mobile, $msg,'true');
        $result = $clapi->execResult($result);
        $send_time = date("Y-m-d H:i:s",time());
        M("sms_log")->add(array("mobile"=>$mobile,"message"=>$msg,"send_time"=>$send_time,"account_id"=>$pay_info['account_id'],"room_id"=>$pay_info['room_id'],"result"=>serialize($result)));
        if($result[1]==0){
            $this->success("发送成功");
        } else {
            $this->success("发送失败");
        }
    }
    

    /**
     * [管家待办]
     **/
    public function stewardtasks(){
        $DSchedule = D("schedule");
        $schedule_list = $DSchedule->getScheduleBySteward($_SESSION['steward_id']);
        $this->assign('schedule_list',$schedule_list);
        $tf_count = $DSchedule->getScheduleCount($_SESSION['steward_id'],C("schedule_type_tf"),0,C("admin_type_gj"));
        $zf_count = $DSchedule->getScheduleCount($_SESSION['steward_id'],C("schedule_type_zf"),0,C("admin_type_gj"));
        $hf_count = $DSchedule->getScheduleCount($_SESSION['steward_id'],C("schedule_type_hf"),0,C("admin_type_gj"));
        $jd_count = $DSchedule->getScheduleCount($_SESSION['steward_id'],C("schedule_type_jd"),0,C("admin_type_gj"));
        
        $this->assign("tf_count",$tf_count);
        $this->assign("zf_count",$zf_count);
        $this->assign("hf_count",$hf_count);
        $this->assign("jd_count",$jd_count);
        $this->display('steward-tasks');
        $this->display("Steward/common/footer");
    }

    /**
    * [退房管家抄水电气]
    **/
    public function check_ammeter(){
        if ( !empty($_POST) ) {
            $schedule_type = I("schedule_type");
            $schedule_id = I("schedule_id");
            $MSchedule = M("schedule");
            $schedule_info = $MSchedule->where(array("id"=>$schedule_id))->find();
            $MRoom = M("room");
            $MHouses = M("houses");
            $house_code = $MRoom->where(array("id"=>$schedule_info['room_id']))->getField("house_code");
            $house_id = $MHouses->where(array("house_code"=>$house_code))->getField("id");
            $DAmmeter = D("ammeter_house");
            $ammeter_house = $DAmmeter->getFirstInfoByHouseId($house_id); //获取一条最新的水电气信息
            //判断录入的水电气是否低于最新录入的水电气
            $not_judge = I("not_judge");
            if ( empty($not_judge) && $not_judge != 1 ) {
                if ( I("post.zS") < $ammeter_house['total_water'] ) {
                    $this->error("录入的水度数:".I("post.zS")."少于最新水度数:".$ammeter_house['total_water']."，请检查!","",10);
                }
                if ( I("post.zD") < $ammeter_house['total_energy'] ) {
                    $this->error("录入的电度数:".I("post.zD")."少于最新电度数:".$ammeter_house['total_energy']."，请检查!","",10);
                }
                if ( I("post.zQ") < $ammeter_house['total_gas'] ) {
                    $this->error("录入的气度:".I("post.zQ")."少于最新气度数:".$ammeter_house['total_gas']."，请检查!","",10);
                }
            }

            $data['account_id'] = $schedule_info['account_id'];
            $data['create_time'] = date("Y-m-d H:i:s",time());
            $data['create_date'] = $schedule_info['create_date'];
            $data['mobile'] = $schedule_info['mobile'];
            $data['schedule_type'] = $schedule_type;
            $data['status'] = 2;//已经录入完水电气
            $data['room_id'] = $schedule_info['room_id'];
            $data['pay_account'] = $schedule_info['pay_account'];
            $data['pay_type'] = $schedule_info['pay_type'];
            $data['appoint_time'] = $schedule_info['appoint_time'];
            $data['msg'] = $schedule_info['msg'];
            $data['is_break'] = $schedule_info['is_break'];
            $data['steward_id'] = $schedule_info['steward_id'];
            $data['admin_type'] = C("admin_type_cw");
            $data['check_out_type'] = I("check_out_type");
            $data['zS'] = I("zS");
            $data['zD'] = I("zD");
            $data['zQ'] = I("zQ");
            $data['roomD'] = I("roomD");
            $data['wx_fee'] = I("wx_fee");
            $data['wx_des'] = I("wx_des");
            $data['last_schedule_id'] = $schedule_id;//上一个schedule_id
            //将租客申请的待办改成完成
            $MSchedule->where(array("id"=>$schedule_id))->save(array("is_finish"=>1));
            //插入一条新的待办
            $result = $MSchedule->add($data);
            if ( $result ) {
                $this->success("提交成功",U("Home/Steward/index"));
            } else {
                $this->error("提交失败");
            }
        } else {
            $schedule_type = I("schedule_type");
            $schedule_id = I("schedule_id");

            $DSchedule = D("schedule");
            $schedule_info = $DSchedule->getScheduleByID($schedule_id);
            $this->assign("schedule_info",$schedule_info);

            $DAccount = D("account");
            $account_info = $DAccount->getAccountInfoById($schedule_info['account_id']);
            $this->assign("account_info",$account_info);

            $DRoom = D("room");
            $room_info = $DRoom->getRoom($schedule_info['room_id']);
            $this->assign("room_info",$room_info);
            $this->assign("schedule_type",$schedule_type);
            $this->assign("schedule_type_name",C("schedule_type_arr")[$schedule_type]);
            $this->assign("schedule_id",$schedule_id);
            $this->display("check-ammeter");
        }
    }

    /**
    * [还原密码]
    **/
    public function recovery(){

        $account_id = I('account_id');
        $MAccount   = M('account');
        $MAccount->startTrans();

        $where['id'] = $account_id;
        $save['password'] = md5('123456');
        $result = $MAccount->where($where)->save($save);
        if ($result){
            // 成功后返回客户端新增的用户ID，并返回提示信息和操作状态
            $MAccount->commit();
            $result['msg'] = '修改成功';
            $this->ajaxReturn($result,'JSON');
        }else{
            // 错误后返回错误的操作状态和提示信息
            $result['msg'] = '修改失败';
            $MAccount->rollback();
            $this->ajaxReturn($result,'JSON');
        }
    }

    /**
    * [管家处理租客退房]
    **/
    public function check_out(){
        $steward_id = $_SESSION['steward_id'];
        $account_id = I('account_id');
        $room_id    = I('room_id');
        $type       = I('type');
        $MAccount   = M('account');
        $MRoom      = M('room');
        $DSchedule  = D('schedule');
        $DAmmeter   = D("ammeter_house");
        $DAmmeterRoom = D("ammeter_room");
        $flag       = true;
        switch ($type) {
            case 1:
                $schedule_type = 1;//退房
                break;
            case 2:
                $schedule_type = 2;//换房
                break;
            case 3:
                $schedule_type = 3;//转房
                break;
            case 4:
                $schedule_type = 1;//退房
                $param['check_out_type'] = 1;//违约退租
                break;
            default:
                $this->error('type丢失');
                break;
        }
        M()->startTrans();
        if ( empty($account_id) || empty($room_id) ) $this->error('数据丢失');
        if ( !empty($_POST) ) {
            if ( I('is_success') != 1 ) $this->error('未验房');
            $check_item_keys  = array_fill_keys(array_keys(C('check_item')),'0');//将config中的数据 值转换成 false
            //序列化 检查物品数据
            $check_item_data = serialize(array_replace($check_item_keys, I('check_item')));

            $house_id = $MRoom->alias('r')->join('lewo_houses h ON r.house_code=h.house_code')->where(array('r.id'=>$room_id))->getField('h.id');
            //判断录入的水电气是否低于最新录入的水电气
            $ammeter_house = $DAmmeter->getFirstInfoByHouseId($house_id); //获取一条最新的水电气信息
            if ( I("post.zS") < $ammeter_house['total_water'] ) {
                $this->error("录入的水度数:".I("post.zS")."少于最新水度数:".$ammeter_house['total_water']."，请检查!","",10);
            }
            if ( I("post.zD") < $ammeter_house['total_energy'] ) {
                $this->error("录入的电度数:".I("post.zD")."少于最新电度数:".$ammeter_house['total_energy']."，请检查!","",10);
            }
            if ( I("post.zQ") < $ammeter_house['total_gas'] ) {
                $this->error("录入的气度:".I("post.zQ")."少于最新气度数:".$ammeter_house['total_gas']."，请检查!","",10);
            }
            foreach ( I('roomD') AS $key=>$val ) {
                //获取上个月 房间的电表
                $last_ammeter_room_info = $DAmmeterRoom->getFirstInfoByRoomId($key);
                if ( !is_null($last_ammeter_room_info) ) {
                    if ( $val['room_energy'] < $last_ammeter_room_info['room_energy'] ) {
                        $this->error($val['room_code']."该房间电度数低于上个月!请重新录入");
                    }
                }
            }
            $roomD = serialize(i_array_column(I('roomD'), 'room_energy'));

            $param = array(
                'steward_id' => $_SESSION['steward_id'],
                'account_id' => $account_id,
                'room_id' => $room_id,
                'schedule_type' => $schedule_type,
                'check_item' => $check_item_data,
                'pay_account' => I('pay_account'),
                'pay_type' => I('pay_type'),
                'admin_type' => C('admin_type_cn'),//出纳
                'zS' => I('zS'),
                'zD' => I('zD'),
                'zQ' => I('zQ'),
                'roomD' => $roomD,
                'wx_fee' => I('wx_fee'),
                'wx_des' => I('wx_des'),
                );
            dump($param);exit;
            //将数据放入schedule表中，展示给后台看
            $DSchedule->create_new_schedule($param);
exit;
            //执行退房修改操作
            //修改房间状态
            $room_result = $MRoom->where(array('id'=>$room_id))->save(array('account_id'=>0,'status'=>0));
            if ( $room_result != 1 ) $flag = false;
            if ( $flag ) {
                /*M()->commit();*/
                $this->success('提交成功');
            } else {
                M()->rollback();
                $this->error('提交失败');
            }
        } else {
            $DAccount = D("account");
            $DRoom    = D("room");
            $account_info = $DAccount->getAccountInfoById($account_id);
            $room_info = $DRoom->getRoom($room_id);
            $room_list = $MRoom->field('id,room_code')->where(array('house_id'=>$room_info['house_id'],'is_show'=>1))->select();


            $this->assign('room_list',$room_list);
            $this->assign('check_item',C('check_item'));
            $this->assign('check_out_goods',C('check_out_goods'));
            $this->assign('account_id',$account_id);
            $this->assign('room_id',$room_id);
            $this->assign('type',$type);            
            $this->assign('refund_type',C('refund_type'));
            $this->assign('check_out_type',C('check_out_type'));
            $this->assign('now_day',date('Y-m-d',time()));
            $this->assign("account_info",$account_info);
            $this->assign("room_info",$room_info);
            $this->display("check-ammeter");
        }
    }
}