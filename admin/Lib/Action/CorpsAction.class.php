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
		 * 展示型首页
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

			//获取位置编号数据 parentid
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
	}
