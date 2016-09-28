<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last
 * @alter
 * @version 1.0.0
 *
 * 功能简介：
 * @author
 * @copyright
 * @time
 * @version 1.0.0
 */
	class TeamsAction extends CommonAction {

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

			$this -> model = D('Teams');
		}

	    /**
		 * 消费商注册
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function register()
	    {
			$form_key = htmlspecialchars($_POST['form_key']);

			if ($form_key == 'yes')
			{
				//查询用户手机号是否注册 查询用户编号是否注册
				$params = array(

					'table_name' => 'member',

					'where' => "usernumber = '{$_POST['usernumber']}'"

				);

				$member = $this -> model -> my_find($params);

				if($member){

					$this -> _back('用户编号或手机号已注册');return;

				}

				if($_POST['zone'] == "" || $_POST["zone"] == 0){
					$this -> _back('推荐人区间为空,请重新选择推荐人！');return;
				}

				$data = $_POST;
				
				// foreach ($data as $key => $value) {
				// 	if(empty($value)){
				// 		$this -> _back('请完善数据后再提交！');return;
				// 	}
				// }

				unset($_data['form_key']);

				$data['proxy_state'] = 1;

				if($data['userrank'] == 1){

					$data['proxy_state'] = 0;

				}

				//处理密码操作
				$data['psd1'] = md5(md5($data['psd1']));

				$data['psd2'] = md5(md5($data['psd2']));

				$data['reg_time'] = time();

				$data['reg_uid'] = $_SESSION['Rongzi']['user']['uid'];

				//获取推荐人ID
				$data['tuijianid'] = $this -> get_recommend_user_id($data['tuijiannumber']);

				//获取接点人ID
				$data['parentid'] = $this -> get_contact_user_id($data['parentnumber']);

				//报单中心人ID
				$data['billcenterid'] = $this -> get_user_center_id($data['billcenternumber']);

				$pic = $this -> _upload_pic_all('member');

				if ($pic['ID_address_face']['status'] == 1)
				{
					$data['ID_address_face'] = $pic['ID_address_face']['msg'];
				}


				if ($pic['ID_address_back']['status'] == 1)
				{
					$data['ID_address_back'] = $pic['ID_address_back']['msg'];
				}

				if($data['billcenterid'] == 0){
					$this -> _back("{$data['billcenternumber']}不是报单中心,消费商注册失败,请重试。");return;
				}

				//添加用户资料
				$params = array(

					'table_name' => 'member',

					'data' => $data
				);

				$member_add = $this -> model -> my_add($params);

				//更新结果处理
				if($member_add !== false){

					//处理接点区域是否被占
					$this -> update_user_zone($data);

					$member_id = $member_add;

					//用户接点路径
					$update_data['contactuserpath'] = $this -> get_user_path($data['parentnumber'], 1).",".$member_id;

					//用户推荐路径
					$update_data['recommenduserpath'] = $this -> get_user_path($data['tuijiannumber'], 2).",".$member_id;

					//用户报单路径
					$update_data['billuserpath'] = $this -> get_user_path($data['billcenternumber'], 3).",".$member_id;

					$this -> update_user_path($member_id, $update_data);

					//添加用户默认地址
					$this -> add_user_address($member_id, $data);

					redirect(__APP__."/Index/index", 0);

				}else{

					$this -> _back('消费商注册失败，请重试。');return;

				}
			}

			//获取用户数据
			$uid = htmlspecialchars($_GET['uid']) ? htmlspecialchars($_GET['uid']) : $_SESSION['Rongzi']['user']['uid'];

			$params = array(

				'table_name' => 'member',

				'where' => "uid = '{$uid}' AND status = 1"

			);
			$member = $this -> model -> my_find($params);

			$this -> assign("usernumber", $this->get_user_number());

			$this -> assign("member", $member);

			$this -> display();
	    }

		//添加用户默认地址
		public function add_user_address($uid, $data)
		{

			$address_data['user_id'] = $uid;

			$address_data['name'] =  $data['realname'];

			$address_data['mobile'] =  $data['mobile'];

			$address_data['address'] =  $data['s_province'] ." ". $data['s_city'] ." ". $data['s_county'] ." ". $data['s_address'];

			$address_data['area'] =  $data['s_province'] ." ". $data['s_city'] ." ". $data['s_county'];

			$address_data['is_default'] = 1;

			$params = array(

				'table_name' => 'user_address',

				'data' => $address_data

			);

			$address = $this -> model -> my_add($params);

			if($address){

				return true;

			}else{

				return false;

			}
		}

		/**
		 * 获取报单中心
		 *
		 * 参数描述：@usernumber 推荐人编号
		 *
		 * 返回值：
		 *
		 */
		 public function get_billcenternumber()
		 {

			$usernumber = htmlspecialchars($_GET['usernumber']);

			//查询用户资料数据
 			$params = array(

 				'table_name' => 'member',

 				'where' => "usernumber = '{$usernumber}' AND status = 1"

 			);

 			$member = $this -> model -> my_find($params);

			$billcenternumber = 0;

 			if($member){

				$billcenternumber = $member['isbill'] == 1 ? $member['usernumber'] : $member['billcenternumber'];

 			}

			die(json_encode(array("success" => true, "code" => 200, "msg" => "报单中心获取成功", "data" => $billcenternumber)));
		 }

		 /**
 		 * 验证消费者编号
 		 *
 		 * 参数描述：@usernumber 用户编号
 		 *
 		 * 返回值：
 		 *
 		 */
		 public function get_usernumber()
		 {
			$usernumber = htmlspecialchars($_GET['usernumber']);

 			//查询用户资料数据
  			$params = array(

  				'table_name' => 'member',

  				'where' => "usernumber = '{$usernumber}' AND status = 1"

  			);

  			$member = $this -> model -> my_find($params);

			$data = 0;

  			if($member){

 				$data = $member['usernumbers'];

  			}

			die(json_encode(array("success" => true, "code" => 200, "msg" => "验证消费者编号", "data" => $data)));
		 }

		 /**
 		 * 获取用户接点区
 		 *
 		 * 参数描述：@usernumber 用户编号
 		 *
 		 * 返回值：
 		 *
 		 */
		 public function get_parentnumber_zone()
		 {
			$usernumber = htmlspecialchars($_GET['usernumber']);

			//查询用户资料数据
   			$params = array(

   				'table_name' => 'member',

   				'where' => "usernumber = '{$usernumber}' AND status = 1"

   			);

   			$member = $this -> model -> my_find($params);

 			$data = array();

   			if($member){

  				$data["left_zone"] = $member['left_zone'];

				$data["middle_zone"] = $member['middle_zone'];

				$data["right_zone"] = $member['right_zone'];

   			}

 			die(json_encode(array("success" => true, "code" => 200, "msg" => "获取用户接点区", "data" => $data)));
		 }

		/**
		 * 获取推荐人ID
		 *
		 * 参数描述：@tuijiannumber 推荐人编号
		 *
		 * 返回值：
		 *
		 */
		function get_recommend_user_id($usernumber){

			//查询用户资料数据
			$params = array(

				'table_name' => 'member',

				'where' => "usernumber = '{$usernumber}' AND status = 1"

			);

			$member = $this -> model -> my_find($params);

			if($member){

				return $member['uid'];

			}else{

				return 0;

			}
		}

		/**
		 * 获取推荐人ID
		 *
		 * 参数描述：@tuijiannumber 推荐人编号
		 *
		 * 返回值：
		 *
		 */
		function get_contact_user_id($usernumber){

			//查询用户资料数据
			$params = array(

				'table_name' => 'member',

				'where' => "usernumber = '{$usernumber}' AND status = 1"

			);

			$member = $this -> model -> my_find($params);

			if($member){

				if($member['left_zone'] == 1 && $member['middle_zone'] == 1 && $member['right_zone'] == 1 ){
					$this -> _back('接点人区间已满,请重新选择推荐人！');return;
				}else{

					return $member['uid'];
				}

			}else{

				return 0;

			}
		}

		/**
		 * 获取报单中心ID
		 *
		 * 参数描述：@tuijiannumber 推荐人编号
		 *
		 * 返回值：
		 *
		 */
		function get_user_center_id($usernumber){

			//查询用户资料数据
			$params = array(

				'table_name' => 'member',

				'where' => "usernumber = '{$usernumber}' AND status = 1 AND isbill = 1"

			);

			$member = $this -> model -> my_find($params);

			if($member){

				return $member['uid'];

			}else{

				return 0;

			}
		}

		/**
		 * 获取用户所在位置
		 *
		 * 参数描述：@$usernumber 用户编号  type 1 接点 2推荐  3报单
		 *
		 * 返回值：
		 *
		 */
		function get_user_path($usernumber, $type){

			//查询用户资料数据
			$params = array(

				'table_name' => 'member',

				'where' => "usernumber = '{$usernumber}' AND status = 1"

			);

			$member = $this -> model -> my_find($params);

			if($member){

				if($type == 1){
					$path = $member['contactuserpath'];
				}elseif($type == 2){
					$path = $member['recommenduserpath'];
				}elseif($type == 3){
					$path = $member['billuserpath'];
				}

				if(empty($path)){
					return 0;
				}

				return $path;


			}else{

				return 0;

			}
		}


		/**
		 * 修改用户所在位置
		 *
		 * 参数描述：@tuijiannumber 推荐人编号
		 *
		 * 返回值：
		 *
		 */
		function update_user_path($uid, $data){

			//查询用户资料数据
			$params = array(

				'table_name' => 'member',

				'where' => "uid = '{$uid}'",

				'data' => $data

			);

			$member = $this -> model -> my_save($params);

			if($member){

				return true;

			}else{

				return false;

			}
		}
		/**
		 * 更新推荐人接点区间是否被占
		 *
		 * 参数描述：@data 用户数据
		 *
		 * 返回值：
		 *
		 */
		function update_user_zone($userdata){
			$uid = $userdata['parentid'];

			switch ($userdata['zone']) {
				case '1':
					# 左区...
					$zone_name = 'left_zone';
					break;
				case '2':
					# 左区...
					$zone_name = 'middle_zone';
					break;
				case '3':
					# 左区...
					$zone_name = 'right_zone';
					break;

				default:
					# 左区...
					$zone_name = 'left_zone';
					break;
			}

			$update_data[$zone_name] = 1;

			//更新用户资料数据
			$params = array(

				'table_name' => 'member',

				'where' => "uid = '{$uid}' AND status = 1",

				'data' => $update_data

			);

			$member = $this -> model -> my_save($params);

			if($member){

				$params = array(

					'table_name' => 'member',

					'where' => "uid = {$uid}",

					'field' => 'znum',

					'data' => 1
				);

				$setInc = $this -> model -> my_setInc($params);

				if($setInc){
					return true;
				}else{
					return false;
				}


			}else{

				return false;

			}
		}

		/**
		 * 团队管理推荐关系列表
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
		public function contact_relation()
		{
			$params = array(

				'table_name' => 'member',

				'where' => "status = 1 AND uid = {$_SESSION['Rongzi']['user']['uid']} AND usernumber = '{$_SESSION['Rongzi']['user']['usernumber']}'"
			);

			$contact = $this -> model -> my_find($params);

			$params = array(

				'table_name' => "member",

				'order' => "uid desc",

				'where' => "status = 1 AND parentid = {$contact['uid']} AND parentnumber = '{$contact['usernumber']}' AND uid != {$contact['uid']} "
			);

			$contact_children_list = $this -> model -> easy_select($params);

			//拼装输出参数
			$exp_result['name'] = $contact['realname'];

			$exp_result['title'] = $contact['userrank'];

			$exp_result['usernumber'] = $contact['usernumber'];

			$exp_result['achievement'] = array('left' => $contact['leftachievement'], 'middle' => $contact['middleachievement'], 'right' => $contact['rightachievement']);

			$exp_result['achievement_today'] = $this->get_today_achievement($contact['uid']);

			$exp_result['relationship']['children_num'] = $this -> model -> get_count($params);

			$exp_result['relationship']['parent_num'] = 1;

			$exp_children[0] = array(
				'children' => array(),
				'relationship' => array('children_num' => 1, 'parent_num' => 0),
				'is_null' => 'true',
				'zone' => 1,
				'parentid' => $contact['uid']
			);

			$exp_children[1] = array(
				'children' => array(),
				'relationship' => array('children_num' => 1, 'parent_num' => 0),
				'is_null' => 'true',
				'zone' => 2,
				'parentid' => $contact['uid']
			);


			$exp_children[2] = array(
				'children' => array(),
				'relationship' => array('children_num' => 1, 'parent_num' => 0),
				'is_null' => 'true',
				'zone' => 3,
				'parentid' => $contact['uid']
			);

			foreach ($contact_children_list as $key => $value) {
				if($key < 3){

					//获取三级下的关系
					$params = array(

						'table_name' => 'member',

						'where' => "status = 1 AND parentid = {$value['uid']} AND parentnumber = '{$value['usernumber']}'"
					);

					$children_list = $this -> model -> easy_select($params);

					//$exp_children_children = array();

					$exp_children_children[0] = array(
						'children' => array(),
						'relationship' => array('children_num' => 1, 'parent_num' => 0),
						'is_null' => 'true',
						'zone' => 1,
						'parentid' => $value['uid']
					);

					$exp_children_children[1] = array(
						'children' => array(),
						'relationship' => array('children_num' => 1, 'parent_num' => 0),
						'is_null' => 'true',
						'zone' => 2,
						'parentid' => $value['uid']
					);


					$exp_children_children[2] = array(
						'children' => array(),
						'relationship' => array('children_num' => 1, 'parent_num' => 0),
						'is_null' => 'true',
						'zone' => 3,
						'parentid' => $value['uid']
					);

					foreach ($children_list as $ckey => $cvalue) {

						//获取四级下的关系
						$params = array(

							'table_name' => 'member',

							'where' => "status = 1 AND parentid = {$cvalue['uid']} AND parentnumber = '{$cvalue['usernumber']}'"
						);

						$four_children_list = $this -> model -> easy_select($params);

						$exp_four_children_children[0] = array(
							'children' => array(),
							'relationship' => array('children_num' => 1, 'parent_num' => 0),
							'is_null' => 'true',
							'zone' => 1,
							'parentid' => $cvalue['uid']
						);

						$exp_four_children_children[1] = array(
							'children' => array(),
							'relationship' => array('children_num' => 1, 'parent_num' => 0),
							'is_null' => 'true',
							'zone' => 2,
							'parentid' => $cvalue['uid']
						);


						$exp_four_children_children[2] = array(
							'children' => array(),
							'relationship' => array('children_num' => 1, 'parent_num' => 0),
							'is_null' => 'true',
							'zone' => 3,
							'parentid' => $cvalue['uid']
						);

						foreach ($four_children_list as $fkey => $fvalue) {
							if($fvalue['zone'] == 1){
								$exp_four_children_children[0] = array(
									'children' => array(),
									'relationship' => array('children_num' => 0, 'parent_num' => 0),
									'name' => $fvalue['realname'],
									'title' => $fvalue['userrank'],
									'usernumber' => $fvalue['usernumber'],
									'achievement' => array('left' => $fvalue['leftachievement'], 'middle' => $fvalue['middleachievement'], 'right' => $fvalue['rightachievement']),
									'achievement_today' => $this->get_today_achievement($fvalue['uid'])
								);
							}

							if($fvalue['zone'] == 2){
								$exp_four_children_children[1] = array(
									'children' => array(),
									'relationship' => array('children_num' => 0, 'parent_num' => 0),
									'name' => $fvalue['realname'],
									'title' => $fvalue['userrank'],
									'usernumber' => $fvalue['usernumber'],
									'achievement' => array('left' => $fvalue['leftachievement'], 'middle' => $fvalue['middleachievement'], 'right' => $fvalue['rightachievement']),
									'achievement_today' => $this->get_today_achievement($fvalue['uid'])
								);
							}


							if($fvalue['zone'] == 3){
								$exp_four_children_children[2] = array(
									'children' => array(),
									'relationship' => array('children_num' => 0, 'parent_num' => 0),
									'name' => $fvalue['realname'],
									'title' => $fvalue['userrank'],
									'usernumber' => $fvalue['usernumber'],
									'achievement' => array('left' => $fvalue['leftachievement'], 'middle' => $fvalue['middleachievement'], 'right' => $fvalue['rightachievement']),
									'achievement_today' => $this->get_today_achievement($fvalue['uid'])
								);
							}
						}

						if($cvalue['zone'] == 1){
							$exp_children_children[0] = array(
								'children' => $exp_four_children_children,
								'relationship' => array('children_num' => 0, 'parent_num' => 0),
								'name' => $cvalue['realname'],
								'title' => $cvalue['userrank'],
								'usernumber' => $cvalue['usernumber'],
								'achievement' => array('left' => $cvalue['leftachievement'], 'middle' => $cvalue['middleachievement'], 'right' => $cvalue['rightachievement']),
								'achievement_today' => $this->get_today_achievement($cvalue['uid'])
							);
						}

						if($cvalue['zone'] == 2){
							$exp_children_children[1] = array(
								'children' => $exp_four_children_children,
								'relationship' => array('children_num' => 0, 'parent_num' => 0),
								'name' => $cvalue['realname'],
								'title' => $cvalue['userrank'],
								'usernumber' => $cvalue['usernumber'],
								'achievement' => array('left' => $cvalue['leftachievement'], 'middle' => $cvalue['middleachievement'], 'right' => $cvalue['rightachievement']),
								'achievement_today' => $this->get_today_achievement($cvalue['uid'])
							);
						}


						if($cvalue['zone'] == 3){
							$exp_children_children[2] = array(
								'children' => $exp_four_children_children,
								'relationship' => array('children_num' => 0, 'parent_num' => 0),
								'name' => $cvalue['realname'],
								'title' => $cvalue['userrank'],
								'usernumber' => $cvalue['usernumber'],
								'achievement' => array('left' => $cvalue['leftachievement'], 'middle' => $cvalue['middleachievement'], 'right' => $cvalue['rightachievement']),
								'achievement_today' => $this->get_today_achievement($cvalue['uid'])
							);
						}
					}

					if($value['zone'] == 1){
						$exp_children[0] = array(
							'children' => $exp_children_children,
							'relationship' => array('children_num' => $this -> model -> get_count($params), 'parent_num' => 0),
							'name' => $value['realname'],
							'title' => $value['userrank'],
							'usernumber' => $value['usernumber'],
							'achievement' => array('left' => $value['leftachievement'], 'middle' => $value['middleachievement'], 'right' => $value['rightachievement']),
							'achievement_today' => $this->get_today_achievement($value['uid'])
						);
					}

					if($value['zone'] == 2){
						$exp_children[1] = array(
							'children' => $exp_children_children,
							'relationship' => array('children_num' => $this -> model -> get_count($params), 'parent_num' => 0),
							'name' => $value['realname'],
							'title' => $value['userrank'],
							'usernumber' => $value['usernumber'],
							'achievement' => array('left' => $value['leftachievement'], 'middle' => $value['middleachievement'], 'right' => $value['rightachievement']),
							'achievement_today' => $this->get_today_achievement($value['uid'])
						);
					}

					if($value['zone'] == 3){
						$exp_children[2] = array(
							'children' => $exp_children_children,
							'relationship' => array('children_num' => $this -> model -> get_count($params), 'parent_num' => 0),
							'name' => $value['realname'],
							'title' => $value['userrank'],
							'usernumber' => $value['usernumber'],
							'achievement' => array('left' => $value['leftachievement'], 'middle' => $value['middleachievement'], 'right' => $value['rightachievement']),
							'achievement_today' => $this->get_today_achievement($value['uid'])
						);
					}
				}

			}

			$exp_result['children'] = $exp_children;

			$this -> assign('exp_result', json_encode($exp_result));

			$this -> display();
		}


		/**
		 * 获取用户今日最新消费业绩
		 *
		 * 参数描述：@uid 推荐人编号
		 *
		 * 返回值：
		 *
		 */
		public function get_today_achievement($uid){
			//查询用户资料数据
			$params = array(

				'table_name' => 'achievement_log',

				'where' => "uid = '{$uid}' AND created_at > ".strtotime(date('Y-m-d', time()))." AND created_at < ".time()

			);

			$achievement = $this -> model -> easy_select($params);

			if($achievement){

				$achievement_result = array('left' => "0.00", 'middle' => "0.00", 'right' => "0.00");

				foreach ($achievement as $key => $value) {

					if($value['zone'] == 1){

						$achievement_result['left'] = $achievement_result['left'] + $value['deduct'];

					}elseif($value['zone'] == 2){

						$achievement_result['middle'] = $achievement_result['middle'] + $value['deduct'];

					}elseif($value['zone'] == 3){

						$achievement_result['right'] = $achievement_result['right'] + $value['deduct'];

					}

				}

				return $achievement_result;

			}else{

				return array('left' => "0.00", 'middle' => "0.00", 'right' => "0.00");

			}
		}




		/**
		 * 团队管理推荐关系列表
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
		public function recommend_relation()
		{
			$params = array(

				'table_name' => 'member',

				'where' => "status = 1 AND uid = {$_SESSION['Rongzi']['user']['uid']} AND usernumber = '{$_SESSION['Rongzi']['user']['usernumber']}'"
			);

			$recommend_list = $this -> model -> easy_select($params);

			foreach ($recommend_list as $key => $value) {

				// $params = array(
				//
				// 	'table_name' => 'member',
				//
				// 	'where' => "status = 1 AND tuijianid = {$value['uid']} AND tuijiannumber = '{$value['usernumber']}'"
				// );
				//
				// $recommend_count = $this -> model -> get_count($params);
				//
				// $recommend_list[$key]["num"] = $recommend_count;

				if($value['zone'] == 1){

					$recommend_list[$key]["zone_name"] = "左区";

				}else if($value['zone'] == 2){

					$recommend_list[$key]["zone_name"] = "中区";

				}else if($value['zone'] == 3){

					$recommend_list[$key]["zone_name"] = "右区";

				}

				$recommend_list[$key]["leftachievement"] = intval($value['leftachievement']);

				$recommend_list[$key]["middleachievement"] = intval($value['middleachievement']);

				$recommend_list[$key]["rightachievement"] = intval($value['rightachievement']);

				$recommend_list[$key]["achievement"] = intval($value['achievement']);

				$recommend_list[$key]["jianglijifen"] = intval($value['jianglijifen']);

				$recommend_list[$key]["baodanbi"] = intval($value['baodanbi']);

				$recommend_list[$key]["jiangjinbi"] = intval($value['jiangjinbi']);

				$recommend_list[$key]["rongzidun"] = intval($value['rongzidun']);

				$recommend_list[$key]["jihuobi"] = intval($value['jihuobi']);

				$userrank = array("无头衔","一星会员","二星会员","三星会员","四星会员","五星会员","六星会员","七星会员");

				$recommend_list[$key]["usertitle"] = $userrank[$value['userrank']];

				$userrank_content = array("","普卡","银卡","金卡","钻卡");

				$recommend_list[$key]["userrank"] = $userrank_content[$value['userrank']];


			}

			$this -> assign('recommend_list', $recommend_list);

			$this -> display();
		}

		/**
		 * 团队管理推荐关系列表
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
		public function get_recommend_relation()
		{
			$tuijianid = $_GET['uid'];

			$usernumber = $_GET['usernumber'];

			$params = array(

				'table_name' => 'member',

				'where' => "status = 1 AND tuijianid = {$tuijianid} AND uid != {$tuijianid}"
			);

			$recommend_list = $this -> model -> easy_select($params);

			$recommend_list_result = array();

			foreach ($recommend_list as $key => $value) {

				// $params = array(
				//
				// 	'table_name' => 'member',
				//
				// 	'where' => "status = 1 AND tuijianid = {$value['uid']}"
				// );
				//
				// $recommend_count = $this -> model -> get_count($params);
				//
				// $recommend_list_result[$key]["num"] = $recommend_count;

				$recommend_list_result[$key]["realname"] = $value['realname'];

				$recommend_list_result[$key]["usernumber"] = $value['usernumber'];

				$recommend_list_result[$key]["uid"] = $value['uid'];

				$recommend_list_result[$key]["leftachievement"] = intval($value['leftachievement']);

				$recommend_list_result[$key]["middleachievement"] = intval($value['middleachievement']);

				$recommend_list_result[$key]["rightachievement"] = intval($value['rightachievement']);

				$recommend_list_result[$key]["achievement"] = intval($value['achievement']);

				$recommend_list_result[$key]["jianglijifen"] = intval($value['jianglijifen']);

				$recommend_list_result[$key]["baodanbi"] = intval($value['baodanbi']);

				$recommend_list_result[$key]["jiangjinbi"] = intval($value['jiangjinbi']);

				$recommend_list_result[$key]["rongzidun"] = intval($value['rongzidun']);

				$recommend_list_result[$key]["jihuobi"] = intval($value['jihuobi']);

				$userrank = array("无头衔","一星会员","二星会员","三星会员","四星会员","五星会员","六星会员","七星会员");

				$userrank_content = array("","普卡","银卡","金卡","钻卡");

				$recommend_list_result[$key]["userrank"] = $userrank_content[$value['userrank']];

				$recommend_list_result[$key]["usertitle"] = $userrank[$value['usertitle']];

				if($value['zone'] == 1){

					$recommend_list_result[$key]["zone_name"] = "左区";

				}else if($value['zone'] == 2){

					$recommend_list_result[$key]["zone_name"] = "中区";

				}else if($value['zone'] == 3){

					$recommend_list_result[$key]["zone_name"] = "右区";

				}
			}

			die(json_encode(array("success" => true, "code" => 200, "msg" => "获取用推荐关系成功", "data" => $recommend_list_result)));
		}

	}
