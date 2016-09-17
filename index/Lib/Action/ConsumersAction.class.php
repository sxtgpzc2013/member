<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-06-12
 * @alter 张睿
 * @version 1.0.0
 *
 * 功能简介：商户后台首页控制器类
 * @author 张睿
 * @copyright
 * @time 2014-06-12
 * @version 1.0.0
 */
	class ConsumersAction extends CommonAction {

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

			$this -> model = D('Consumers');
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
	    public function edit_info()
	    {
			//获取用户数据
			$uid = $_SESSION['Rongzi']['user']['uid'];
			$form_key = htmlspecialchars($_POST['form_key']);

			if ($form_key == 'yes')
			{
				unset($_POST['form_key']);

				//更新用户资料
				$params = array(
					'table_name' => 'member',
					'where' => "uid = {$uid} AND status = 1",
					'data' => $_POST
				);
				$member_save = $this -> model -> my_save($params);

				//更新结果处理
				if($member_save !== false){
					redirect(__APP__."/Consumers/edit_info", 0);
				}else{
					$this -> _back('账户资料修改失败，请重试。');return;
				}
			}

			//查询用户资料数据
			$params = array(
				'table_name' => 'member',
				'where' => "uid = '{$uid}' AND status = 1"
			);
			$member = $this -> model -> my_find($params);

			$this -> assign('member', $member);
			$this -> display();
	    }
	}
