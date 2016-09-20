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

					'where' => "usernumber = '{$_POST['usernumber']}' OR mobile = '{$_POST['mobile']}'"

				);

				$member = $this -> model -> my_find($params);

				if($member){

					$this -> _back('用户编号或手机号已注册');return;

				}

				$data = $_POST;

				unset($_data['form_key']);

				//处理密码操作
				$data['psd1'] = md5(md5($data['psd1']));

				$data['psd2'] = md5(md5($data['psd2']));

				$data['reg_time'] = time();

				//获取推荐人ID
				$data['tuijianid'] = $this -> get_user_id($data['tuijiannumber']);

				//获取接点人ID
				$data['parentid'] = $this -> get_user_id($data['parentnumber']);

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

					//修改接点人接点区是否被占用处理
					redirect(__APP__."/Teams/register", 0);

				}else{

					$this -> _back('消费商注册失败，请重试。');return;

				}
			}

			$this -> display();
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

 				'where' => "usernumber = '{$usernumber}' AND status = 1 AND isbill = 1"

 			);

 			$member = $this -> model -> my_find($params);

			$billcenternumber = 0;

 			if($member){

				$billcenternumber = $member['billcenternumber'];

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
		function get_user_id($usernumber){

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

				return true;

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
		public function recommend_relation()
		{
			$params = array(

				'table_name' => 'member',

				'where' => "status = 1 AND tuijianid = {$_SESSION['Rongzi']['user']['uid']} AND tuijiannumber = '{$_SESSION['Rongzi']['user']['usernumber']}'"
			);

			$recommend_list = $this -> model -> easy_select($params);

			foreach ($recommend_list as $key => $value) {
				$params = array(

					'table_name' => 'member',

					'where' => "status = 1 AND tuijianid = {$value['uid']} AND tuijiannumber = '{$value['usernumber']}'"
				);

				$recommend_count = $this -> model -> get_count($params);

				$recommend_list[$key]["num"] = $recommend_count;
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

				'where' => "status = 1 AND tuijianid = {$tuijianid}"
			);

			$recommend_list = $this -> model -> easy_select($params);

			foreach ($recommend_list as $key => $value) {
				$params = array(

					'table_name' => 'member',

					'where' => "status = 1 AND tuijianid = {$value['uid']}"
				);

				$recommend_count = $this -> model -> get_count($params);

				$recommend_list[$key]["num"] = $recommend_count;
			}

			die(json_encode(array("success" => true, "code" => 200, "msg" => "获取用推荐关系成功", "data" => $recommend_list)));
		}

	}
