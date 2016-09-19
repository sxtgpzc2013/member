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
	class ActivatesAction extends CommonAction {

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

			$this -> model = D('Activates');
		}

		/**
		 * 消费商激活列表
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

		   //报单中心ID
		   $billcenterid = $_SESSION['Rongzi']['user']['billcenterid'];

		   //报单中心编号
		   $billcenternumber = $_SESSION['Rongzi']['user']['billcenternumber'];

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
			   redirect(__APP__.'/Activates/index', 0);
		   }
		   else
		   {
			   $this -> _back('删除失败，请重试。');
		   }
	   }

	   /**
	   * 激活处理
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
		  $uid = intval($_GET['uid']);

		  //查询该用户是否符合激活条件
		  $params = array(

			  'table_name' => 'member',

			  'where' => "uid = '{$uid}' AND status = 0"

		  );

		  $member = $this -> model -> my_find($params);

		  if($member){
			  //获取会员级别
			  switch (intval($member['userrank'])) {
			  	case '1':
			  		# 1980...
					$deduct = 1980;
			  		break;
				case '2':
					# 10000...
					$deduct = 10000;
					break;
				case '3':
			  		# 30000...
					$deduct = 30000;
			  		break;
				case '4':
			  		# 50000...
					$deduct = 50000;
			  		break;

			  	default:
			  		# code...
					$deduct = 1980;
			  		break;
			  }

			  if(intval($member['baodanbi']) < $deduct/2){
				  $this -> _back("账户激活币不足{$member['baodanbi']}");return;
			  }

			  if(intval($member['jihuobi']) < $deduct/2){
				  $this -> _back("账户激活币不足{$member['jihuobi']}");return;
			  }

			  //报单币余额计算
			  $data['baodanbi'] = intval($member['baodanbi']) - $deduct/2;

			  //激活币余额计算
			  $data['jihuobi'] = intval($member['jihuobi']) - $deduct/2;

		  }else{
			  $this -> _back('激活账号获取失败，请重试。');
		  }

		  //报单中心ID
		  $billcenterid = $_SESSION['Rongzi']['user']['billcenterid'];

		  //报单中心编号
		  $billcenternumber = $_SESSION['Rongzi']['user']['billcenternumber'];

		  //数据包
		  $data['status'] = 1;

		  $data['active_time'] = time();

		  $data['active_uid'] = $_SESSION['Rongzi']['user']['uid'];

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
			  redirect(__APP__.'/Activates/index', 0);
		  }
		  else
		  {
			  $this -> _back('激活失败，请重试。');
		  }
	  }

	}
