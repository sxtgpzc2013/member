<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update
 * @alter
 * @version 1.0.0
 *
 * 功能简介：
 * @author
 * @copyright
 * @time
 * @version 1.0.0
 */
	class CorpsAction extends CommonAction {

		/**
		 * 构造方法-实例化MODEL
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */


		public function __construct()
		{
			parent::__construct();

			$this -> model = D('Corps');
		}

	    /**
		 * 首页
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function index()
	    {
			$where = "";

			if($_GET['usernumber']){
				$where = "usernumber = ". htmlspecialchars($_GET['usernumber']);
			}

			//获取所有用户数据
			$params = array(

				'table_name' => 'member',

				'where' => $where,

				'order' => "uid desc"

			);

	    	$data = $this -> model -> order_select($params);

	    	$this -> assign('members', $data['result']);

			$this -> assign('page', $data['page']);

	    	$this -> display();
	    }


	    /**
		 * 展示型首页
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function download()
	    {
			$where = "";

			if($_GET['usernumber']){
				$where = "usernumber = ". htmlspecialchars($_GET['usernumber']);
			}

			//获取所有用户数据
			$params = array(

				'table_name' => 'member',

				'where' => $where,

				'order' => "uid desc"

			);

	    	$xlsData = $this -> model -> easy_select($params);

			$xlsName  = "Corps";

		    $xlsCell  = array(
			    array('usernumber','用户编号'),
			    array('realname','姓名'),
			    array('userrank','级别'),
			    array('usertitle','头衔'),
			    array('red_wine_number','数字红酒'),
			    array('jiangjinbi','奖金币'),
			    array('baodanbi','报单币'),
			    array('rongzidun','戎子盾'),
			    array('jianglijifen','奖励积分'),
			    array('tuijiannumber','推荐人'),
			    array('parentnumber','上级人'),
			    array('billcenterid','代理商编号'),
			   	array('reg_time','注册时间'),
			    array('status','状态')
		    );

		    foreach ($xlsData as $key => $value) {

		    	# 处理标题数据
				if ($value['userrank'] == 1) {
					$xlsData[$key]['userrank'] = "普卡销费商";
				} elseif ($value['userrank'] == 2) {
					$xlsData[$key]['userrank'] = "银卡销费商";
				} elseif ($value['userrank'] == 3) {
					$xlsData[$key]['userrank'] = "金卡销费商";
				} elseif ($value['userrank'] == 4) {
					$xlsData[$key]['userrank'] = "钻卡销费商";
				} else {
					$xlsData[$key]['userrank'] = "无";
				}

				$xlsData[$key]['usertitle'] = $xlsData[$key]['usertitle']."级销费商";

				if ($value['status'] == 0) {
					$xlsData[$key]['status'] = "未激活";
				} elseif ($value['status'] == 1) {
					$xlsData[$key]['status'] = "已激活";
				} elseif ($value['status'] == -1) {
					$xlsData[$key]['status'] = "已删除";
				} elseif ($value['status'] == -2) {
					$xlsData[$key]['status'] = "已冻结";
				} else {
					$xlsData[$key]['status'] = "未知";
				}

				$xlsData[$key]['reg_time'] = date("Y-m-d", $value['reg_time']);

		    }
		    $this->exportExcel($xlsName,$xlsCell,$xlsData);
	    }

		/**
		 * 销费商修改相关页面
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function edit()
	    {
			$uid = intval($_GET['uid']);

			//获取用户数据
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid} AND status = 1"

			);

	    	$member = $this -> model -> my_find($params);

			$usertitle = array("","一","二","三","四","五","六");

			$member["usertitle"] = $usertitle[$member['usertitle']];

			$zone = array("1" => "左", "2" => "中", "3" => "右");

			$member["zone"] = $zone[$member['zone']];

			//获取代理商编号数据
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$member['billcenterid']} AND status = 1"

			);

	    	$billmember = $this -> model -> my_find($params);

			//获取推荐人数据
			$params = array(
				'table_name' => 'member',

				'where' => "uid = {$member['tuijianid']} AND status = 1"

			);

	    	$recommendmember = $this -> model -> my_find($params);

			//获取位置编号据 parentid
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$member['parentid']} AND status = 1"

			);

	    	$parentmember = $this -> model -> my_find($params);

	    	$this -> assign('member', $member);

			$this -> assign('billmember', $billmember);

			$this -> assign('recommendmember', $recommendmember);

			$this -> assign('parentmember', $parentmember);

	    	$this -> display();
	    }


		/**
		 * 设置为代理商编号
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function set_bill_center()
	    {
			$uid = intval($_GET['uid']);

			$data['update_time'] = time();

			$data['isbill'] = intval($_GET['isbill']) ? $_GET['isbill'] : 1;

			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid}",

				'data' => $data
			);

			$my_save = $this -> model -> my_save($params);

			if ($my_save == 1){
				$this -> redirect("/Corps/edit?uid=".$uid);
			}else{
				$this -> _back('销费商代理商编号设置失败失败');
			}
	    }

		/**
		 * 设置为代理商编号
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function no_set_bill_center()
	    {
			$uid = intval($_GET['uid']);

			$data['update_time'] = time();

			$data['isbill'] = 0;

			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid}",

				'data' => $data
			);

			$my_save = $this -> model -> my_save($params);

			if ($my_save == 1){
				$this -> redirect("/Corps/edit?uid=".$uid);
			}else{
				$this -> _back('销费商代理商编号取消失败');
			}
	    }

	    /**
		 * 冻结销费商
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function freeze()
	    {
			$uid = intval($_GET['uid']);

			$data['update_time'] = time();

			$data['status'] = -2;

			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid}",

				'data' => $data
			);

			$my_save = $this -> model -> my_save($params);

			if ($my_save == 1){
				$this -> redirect("/Corps/index");
			}else{
				$this -> _back('冻结销费商失败');
			}
	    }

		/**
		 * 冻结销费商
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function nofreeze()
	    {
			$uid = intval($_GET['uid']);

			$data['update_time'] = time();

			$data['status'] = 1;

			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid}",

				'data' => $data
			);

			$my_save = $this -> model -> my_save($params);

			if ($my_save == 1){
				$this -> redirect("/Corps/index");
			}else{
				$this -> _back('冻结销费商失败');
			}
	    }

	    /**
		 * 删除销费商
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function delete()
	    {
			$uid = intval($_GET['uid']);

			$data['update_time'] = time();

			$data['status'] = -1;

			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid}",

				'data' => $data
			);

			$my_save = $this -> model -> my_save($params);

			if ($my_save == 1){
				$this -> redirect("/Corps/index");
			}else{
				$this -> _back('删除销费商失败');
			}
	    }

		/**
		 * 删除销费商
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function nodelete()
	    {
			$uid = intval($_GET['uid']);

			$data['update_time'] = time();

			$data['status'] = 1;

			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid}",

				'data' => $data
			);

			$my_save = $this -> model -> my_save($params);

			if ($my_save == 1){
				$this -> redirect("/Corps/index");
			}else{
				$this -> _back('删除销费商失败');
			}
	    }


		/**
		 * 获取消费商信息
		 *
		 * 参数描述：@usernumber 推荐人编号
		 *
		 * 返回值：
		 *
		 */
		 public function get_userinfo()
		 {

			$usernumber = htmlspecialchars($_GET['usernumber']);

			//查询用户资料数据
 			$params = array(

 				'table_name' => 'member',

 				'where' => "usernumber = '{$usernumber}' AND status = 1"

 			);

 			$member = $this -> model -> my_find($params);

			$userinfo = array();

 			if($member){

				$userinfo['realname'] = $member['realname'];

				$userinfo['uid'] = $member['uid'];

				$userrank_content = array("","普卡","银卡","金卡","钻卡");

				$userinfo['userrankname'] = $userrank_content[$member['userrank']];

				$userinfo['userrank'] = $member['userrank'];

				$userinfo['canlevel'] = array();

				$userinfo['upgrade_status'] = $member['upgrade_status'];
 			}

			die(json_encode(array("success" => true, "code" => 200, "msg" => "代理商编号获取成功", "data" => $userinfo)));
		 }


		 /**
 		 * 销费商升级
 		 *
 		 * 参数描述：
 		 *
 		 *
 		 *
 		 * 返回值：
 		 *
 		 */
 	    public function upgrade()
 	    {
 			$form_key = htmlspecialchars($_POST['form_key']);

 			if ($form_key == 'yes')
 			{

				$data['userrank'] = $_POST['canlevel'];

				//获取会员级别
				// switch (intval($_POST['canlevel'])) {
				// 	case '1':
				// 		# 1980...
				// 		$data['upgrade_level'] = 0;
				// 		break;
				// 	case '2':
				// 		# 10000...
				// 		$data['upgrade_level'] = 0;
				// 		break;
				// 	case '3':
				// 		# 30000...
				// 		$data['upgrade_level'] = $_POST['canlevel'] - $_POST['level'] * 2;
				// 		break;
				// 	case '4':
				// 		# 50000...
				// 		$data['upgrade_level'] = 50000;
				// 		break;

				// 	default:
				// 		# code...
				// 		$data['upgrade_level'] = 0;
				// 		break;
				// }

				$data['upgrade_level'] = ($_POST['canlevel'] - $_POST['oldrank']) * 2;

				$data['upgrade_status'] = 1;

				$data['upgrade_time'] = time();

 				//查询用户手机号是否注册 查询用户编号是否注册
 				$params = array(

 					'table_name' => 'member',

 					'where' => "uid = {$_POST['uid']} AND usernumber = '{$_POST['usernumber']}'",

					'data' => $data

 				);

 				$my_save = $this -> model -> my_save($params);
				if ($my_save == 1){

					//更新相关信息业绩和激活信息
					$this -> update_upgrade_info($_POST['uid']);

					echo '<script language="JavaScript">;alert("消费商升级成功");</script>;';
					//$this -> redirect("/Corps/upgrade");
				}else{
					$this -> _back('销费商代理商编号设置失败失败');
				}
			}

			$this -> display();
		}


		function update_upgrade_info($uid)
		{

			$Activates=A("Activates");

			//查询该用户是否符合升级条件
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid} AND status = 1"

			);

			$member = $this -> model -> my_find($params);

			if($member){

				$deduct = $member['upgrade_level'] * 10000;

				$add_finance = $Activates -> add_finance($deduct);

			}else{
				$this -> _back('升级账号获取失败，请重试。');
			}

			//修改相关所有人业绩
			$contactuserpath_arr = array_reverse(explode(",", $member['contactuserpath']));

			foreach ($contactuserpath_arr as $key => $value) {

				//查询该用户在左区中区还是右区
				if($contactuserpath_arr[$key] && $contactuserpath_arr[$key+1] && $member['userrank'] != 1){

					# 获取当前用户区间
					$contact_uid = $contactuserpath_arr[$key];

					//查询当前用户在父类的哪个区间
					$params = array(

						'table_name' => 'member',

						'where' => "uid = {$contact_uid}"

					);

					$contact = $this -> model -> my_find($params);

					#获取父类用户相关信息
					$contact_parent_uid = $contactuserpath_arr[$key+1];

					//获取父类相关数据
					$params = array(

						'table_name' => 'member',

						'where' => "uid = {$contact_parent_uid}"

					);
					$contact_parent = $this -> model -> my_find($params);

					$contact_parent_data = array();

					if($contact['zone'] == 1){

						$contact_parent_data['leftachievement'] = $contact_parent['leftachievement'] + $deduct;

						$contact_parent_data['achievement'] = $contact_parent['achievement'] + $contact_parent_data['leftachievement'];

					}elseif($contact['zone'] == 2){

						$contact_parent_data['middleachievement'] = $contact_parent['middleachievement'] + $deduct;

						$contact_parent_data['achievement'] = $contact_parent['achievement'] + $contact_parent_data['middleachievement'];

					}elseif($contact['zone'] == 3){

						$contact_parent_data['rightachievement'] = $contact_parent['rightachievement'] + $deduct;

						$contact_parent_data['achievement'] = $contact_parent['achievement'] + $contact_parent_data['rightachievement'];
					}

					$contact_parent_data['num'] = $contact_parent['num'] + 1;

					//修改父类相关数据
					$params = array(

						'table_name' => 'member',

						'where' => "uid = {$contact_parent_uid} AND status = 1",

						'data' => $contact_parent_data

					);

					$contact_parent_save = $this -> model -> my_save($params);

					$achievementdata = array();
					//业绩区间
					$achievementdata['zone'] = $contact['zone'];
					//业绩金额
					$achievementdata['deduct'] = $deduct;
					//业绩来源用户
					$achievementdata['fromuid'] = $contact_uid;
					//业绩用户
					$achievementdata['uid'] = $contact_parent_uid;
					//业绩产生用户
					$achievementdata['produceuid'] = $uid;
					//业绩时间
					$achievementdata['created_at'] = time();

					//添加业绩增加LOG
					$params = array(

						'table_name' => 'achievement_log',

						'data' => $achievementdata

					);

					$achievementadd = $this -> model -> my_add($params);
				}
			}


			$data['update_time'] = time();

			if($member['userrank'] == 3){
				$data['red_wine_number'] = 1;
			}elseif($member['userrank'] == 4){
				$data['red_wine_number'] = 3;
			}


			//写入数据库
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid}",

				'data' => $data
			);

			$my_save = $this -> model -> my_save($params);

			if ($my_save == 1)
			{
				//更新上级伞下人数
				//$this -> save_member_num($member);

				//更新市场补贴
				$Activates -> save_market_subsidy($deduct);

				//更新拓展补贴
				$Activates -> save_expand_subsidy($member, $deduct);

				//调用Python脚本
				//exec("python ./");
				system("python ./scripts/upgrade.py {$uid}", $ret);
	   			//system("python ./scripts/achievement.py", $ret2);
				//更新消费套餐红酒订单 添加一份订单
				$Activates -> save_red_order($member);

				redirect(__APP__.'/Activates/index', 0);
			}
			else
			{
				$this -> _back('激活失败，请重试。');
			}
		}
	}
