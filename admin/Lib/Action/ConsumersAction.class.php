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
		 * 修改销费商信息
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
			$uid = $_GET['uid'] ? $_GET['uid'] : $_POST['uid'];

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
					redirect(__APP__."/Consumers/edit_info?uid=".$uid, 0);
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

		/**
		 * 修改销费商信息
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function edit_password()
	    {
			//获取用户数据
			$uid = $_GET['uid'] ? $_GET['uid'] : $_POST['uid'];

			$form_key = htmlspecialchars($_POST['form_key']);

			$form_type = htmlspecialchars($_POST['form_type']);

			if ($form_key == 'yes')
			{
				#修改一级密码
				if($form_type == 'psd1'){

					//判断一级密码是否正确
					// if(md5(md5($_POST['oldpassword'])) != $_SESSION['Rongzi']['user']['psd1']){
					// 	$this -> _back('一级密码错误');return;
					// }

					$data['psd1'] = md5(md5($_POST['password']));

				}elseif($form_type == 'psd2'){

					//判断二级密码是否正确
					// if(md5(md5($_POST['oldpassword2'])) != $_SESSION['Rongzi']['user']['psd2']){
					// 	$this -> _back('二级密码错误');return;
					// }

					$data['psd2'] = md5(md5($_POST['password2']));
				}

				//更新用户资料
				$params = array(

					'table_name' => 'member',

					'where' => "uid = {$uid} AND status = 1",

					'data' => $data
				);

				$member_save = $this -> model -> my_save($params);

				//更新结果处理
				if($member_save !== false){
					$this->redirect('/Consumers/edit_password?uid='.$uid);
				}else{
					$this -> _back('账户资料修改失败，请重试。');return;
				}
			}

			$this -> display();
	    }
	}
