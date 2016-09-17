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
		 * 首页
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function registers()
	    {
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

			$this->display();
	    }


	}
