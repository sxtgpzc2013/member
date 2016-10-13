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


<<<<<<< HEAD
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
			    array('tuijianid','推荐人'),
			    array('parentid','上级人'),
			    array('billcenterid','代理商编号'),
			   	array('reg_time','注册时间'),
			    array('status','状态')
		    );

		    foreach ($xlsData as $key => $value) {
		    	
		    	# 处理标题数据
				if ($value['userrank'] == 1) {
					$xlsData[$key]['title'] = "普卡销费商";
				} elseif ($value['userrank'] == 2) {
					$xlsData[$key]['title'] = "银卡销费商";
				} elseif ($value['userrank'] == 3) {
					$xlsData[$key]['title'] = "金卡销费商";
				} elseif ($value['userrank'] == 4) {
					$xlsData[$key]['title'] = "钻卡销费商";
				} else {
					$xlsData[$key]['title'] = "无";
				}

				$xlsData[$key]['title'] = $xlsData[$key]['title']."级销费商";

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
				

		    }
		    $this->exportExcel($xlsName,$xlsCell,$xlsData);
	    }

=======
>>>>>>> a86f3fd366dd857e360a4f935716171cc865090d
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

			$userrank = array("","一","二","三","四","五","六","七");

			$member["userrank"] = $userrank[$member['userrank']];

			$member["usertitle"] = $userrank[$member['usertitle']];

			$zone = array("1" => "左", "2" => "中", "3" => "右");

			$member["zone"] = $zone[$member['zone']];
<<<<<<< HEAD


			//获取代理商编号数据
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$member['billcenterid']} AND status = 1"

			);

	    	$billmember = $this -> model -> my_find($params);

			//获取推荐人数据
			$params = array(

=======


			//获取代理商编号数据
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$member['billcenterid']} AND status = 1"

			);

	    	$billmember = $this -> model -> my_find($params);

			//获取推荐人数据
			$params = array(

>>>>>>> a86f3fd366dd857e360a4f935716171cc865090d
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

			$data['isbill'] = 1;

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
<<<<<<< HEAD

				'data' => $data
			);

=======

				'data' => $data
			);

>>>>>>> a86f3fd366dd857e360a4f935716171cc865090d
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
 			}

			die(json_encode(array("success" => true, "code" => 200, "msg" => "代理商编号获取成功", "data" => $userinfo)));
		 }


		 /**
 		 * 销费商注册
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

 				//查询用户手机号是否注册 查询用户编号是否注册
 				$params = array(

 					'table_name' => 'member',

 					'where' => "uid = {$_POST['uid']} AND usernumber = '{$_POST['usernumber']}'",

					'data' => $data

 				);

 				$my_save = $this -> model -> my_save($params);
				if ($my_save == 1){
					echo '<script language="JavaScript">;alert("消费商升级成功");</script>;';
					$this -> redirect("/Corps/upgrade");
				}else{
					$this -> _back('销费商代理商编号设置失败失败');
				}
			}

			$this -> display();
		}
	}
