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
		 * 消费商激活
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
		public function activate()
	    {
			//报单中心ID
			$billcenterid = $_SESSION['Rongzi']['user']['billcenterid'];
			//报单中心编号
			$billcenternumber = $_SESSION['Rongzi']['user']['billcenternumber'];

			//查询用户资料数据
			$params = array(

				'table_name' => 'member',

				'where' => "billcenterid = {$billcenterid} AND billcenternumber = {$billcenternumber} AND status = 0"

			);

	    	$data = $this -> model -> order_select($params);

	    	$result['members'] = $data['result'];

			$result['page'] = $data['page'];

	    	$this -> assign('result', $result);

			$this -> display();
	    }

		/**
		* 删除
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

		   //数据包
		   $data['status'] = -2;

		   $data['update_time'] = time();

		   //写入数据库
		   $params = array(

			   'table_name' => 'member',

			   'where' => "uid = {$uid} AND billcenterid = {$billcenterid} AND billcenternumber = {$billcenternumber} AND status = 0",

			   'data' => $data
		   );

		   $my_save = $this -> model -> my_save($params);

		   if ($my_save == 1)
		   {
			   redirect(__APP__.'/Teams/activate/', 0);
		   }
		   else
		   {
			   $this -> _back('删除失败，请重试。');
		   }
	   }

	}
