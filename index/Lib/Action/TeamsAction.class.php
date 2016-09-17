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
				//更新用户资料
				$params = array(

					'table_name' => 'member',

					'data' => $data
				);

				$member_add = $this -> model -> my_add($params);

				//更新结果处理
				if($member_add !== false){

					redirect(__APP__."/Teams/register", 0);

				}else{

					$this -> _back('消费商注册失败，请重试。');return;

				}
			}

			$this->display();
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

		}

	}
