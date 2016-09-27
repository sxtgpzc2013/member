<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-06-12
 * @alter
 * @version 1.0.0
 *
 * 功能简介：商户后台首页控制器类
 * @author
 * @copyright
 * @time 2014-06-12
 * @version 1.0.0
 */
	class IndexAction extends CommonAction {

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

			$this -> model = D('Index');
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
			//获取用户数据
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$_SESSION['Rongzi']['user']['uid']}"

			);

	    	$member = $this -> model -> my_find($params);

			//获取奖金明细
			$params = array(

				'table_name' => 'bonus_count',

				'where' => "touserid = {$_SESSION['Rongzi']['user']['uid']}"

			);

	    	$bonus_count = $this -> model -> order_select($params);

			$userrank = array("","一","二","三","四","五","六","七");

			$member["userrank"] = $userrank[$member['userrank']];

			$member["usertitle"] = $userrank[$member['usertitle']];


			$this -> assign('member', $member);

			$this -> assign('bonus_count', $bonus_count['result']);

			$this -> display();
	    }
	}
