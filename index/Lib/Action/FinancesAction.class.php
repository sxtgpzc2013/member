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
	class FinancesAction extends CommonAction {

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

			$this -> model = D('Finances');

			if(ACTION_NAME != "password"){
				if($_SESSION['Rongzi']['twopwd']){

				}else{
					redirect(__APP__.'/Finances/password?callback='.urlencode($this -> get_url()), 0);
				}
			}

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
			$this -> display();
	    }

	    /**
		 * cash
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function cash()
	    {
	    	$params = array(

	    		'table_name' => 'member',

	    		'where' => "uid = {$_SESSION['Rongzi']['user']['uid']}"
	    	);

	    	$member = $this -> model -> my_find($params);

	    	if (isset($_POST['form_key']) && $_POST['form_key'] == 'yes')
	    	{
	    		$data['money'] = isset($_POST['money']) && floatval($_POST['money']) >= 500 && floatval($_POST['money']) <= $member['jiangjinbi'] ? floatval($_POST['money']) : $this -> _back('非法的提现金额');

	    		$data['mobile'] = is_tel($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : $this -> _back('非法的手机号');

	    		$data['banknumber'] = isset($_POST['banknumber']) ? htmlspecialchars($_POST['banknumber']) : $this -> _back('银行卡号不能为空');

	    		$data['bankname'] = isset($_POST['bankname']) ? htmlspecialchars($_POST['bankname']) : $this -> _back('开户银行不能为空');

	    		$data['bank_address'] = isset($_POST['bank_address']) ? htmlspecialchars($_POST['bank_address']) : $this -> _back('开户行地址不能为空');

	    		$data['bankholder'] = isset($_POST['bankholder']) ? htmlspecialchars($_POST['bankholder']) : $this -> _back('开户人不能为空');

	    		$data['realname'] = $member['realname'];

	    		$data['moneytype'] = 0;

	    		$data['userid'] = $member['uid'];

	    		$data['usernumber'] = $member['usernumber'];

	    		$data['fee'] = $data['money'] / 10;

	    		$data['arrival_amount'] = $date['money'] - $data['free'];

	    		$data['createtime'] = time();

	    		$data['status'] = 1;

	    		$params = array(

	    			'table_name' => 'withdrawal',

	    			'data' => $data
	    		);

	    		$withdrawal_add = $this -> model -> my_add($params);

	    		if ($withdrawal_add)
	    		{
	    			//扣款
	    			$member_data['jiangjinbi'] = $member['jiangjinbi'] - $data['money'];

	    			$member_data['update_time'] = time();

	    			$params = array(

	    				'table_name' => 'member',

	    				'where' => "uid = {$member['uid']}",

	    				'data' => $member_data
	    			);

	    			$member_save = $this -> model -> my_save($params);

	    			//存入流水
	    			$money_change_data['moneytype'] = 6;

					$money_change_data['status'] = $member_save ? 1 : 0;

					$money_change_data['targetrealname'] = '系统';

					$money_change_data['userid'] = $member['uid'];

					$money_change_data['usernumber'] = $member['usernumber'];

					$money_change_data['realname'] = $member['realname'];

					$money_change_data['changetype'] = 11;

					$money_change_data['recordtype'] = 0;

					$money_change_data['money'] = $data['money'];

					$money_change_data['hasmoney'] = $member_data['jiangjinbi'];

					$money_change_data['createtime'] = time();

					//存入流水
					$params = array(

						'table_name' => 'money_change',

						'data' => $money_change_data
					);

					$money_change_add = $this -> model -> my_add($params);

	    			redirect(__APP__.'/Finances/cash', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('申请失败 请重试');
	    		}

	    	}

	    	$this -> assign('member', $member);

			$this -> display();
	    }

	    /**
		 * 提现记录
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function cash_list()
	    {
	    	$params = array(

	    		'table_name' => 'withdrawal',

	    		'where' => "userid = {$_SESSION['Rongzi']['user']['uid']}"
	    	);

	    	$withdrawals = $this -> model -> easy_select($params);

	    	$this -> assign('withdrawals', $withdrawals);

			$this -> display();
	    }

	    /**
		 * 系统内部转账
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function transfer()
	    {
	    	$params = array(

	    		'table_name' => 'member',

	    		'where' => "uid = {$_SESSION['Rongzi']['user']['uid']}"
	    	);

	    	$member = $this -> model -> my_find($params);

	    	if (isset($_POST['form_key']) && $_POST['form_key'] == 'yes')
	    	{
	    		$targetusernumber = isset($_POST['targetusernumber']) && intval($_POST['targetusernumber']) != $member['usernumber'] ? intval($_POST['targetusernumber']) : $this -> _back('非法的转入用户编号');

	    		$money = isset($_POST['money']) && floatval($_POST['money']) > 0 ? floatval($_POST['money']) : $this -> _back('非法的转币数量');

	    		if ($member['baodanbi'] - $money < 0)
	    		{
	    			$this -> _back('余额不足');
	    		}

	    		//查询目标用户
	    		$params = array(

	    			'table_name' => 'member',

	    			'where' => "usernumber = {$targetusernumber} AND status = 1"
	    		);

	    		$target_member = $this -> model -> my_find($params);

	    		if (!$target_member)
	    		{
	    			$this -> _back('非法的转入用户编号');
	    		}

	    		//扣除当前用户数值
	    		$params = array(

	    			'table_name' => 'member',

	    			'where' => "uid = {$member['uid']}",

	    			'data' => array(

	    				'baodanbi' => $member['baodanbi'] - $money
	    			)
	    		);

	    		$member_model = M('member');

	    		//开启事务
	    		$member_model -> startTrans();

	    		$member_save = $this -> model -> my_save($params);

	    		if (!$member_save)
	    		{
	    			$member_model -> rollback();

	    			$this -> _back('操作失败 请稍后重试');
	    		}

	    		//修改对方的余额
	    		$params = array(

	    			'table_name' => 'member',

	    			'where' => "uid = {$target_member['uid']}",

	    			'data' => array(

	    				'baodanbi' => $target_member['baodanbi'] + $money
	    			)
	    		);

	    		$target_member_save = $this -> model -> my_save($params);

	    		if ($target_member_save)
	    		{
	    			$member_model -> commit();

	    			$data['status'] = 0;
	    		}
	    		else
	    		{
	    			$member_model -> rollback();

	    			$data['status'] = 1;
	    		}

	    		$data['userid'] = $member['uid'];

	    		$data['usernumber'] = $member['usernumber'];

	    		$data['username'] = $member['realname'];

	    		$data['targetuserid'] = $target_member['uid'];

	    		$data['targetusernumber'] = $target_member['usernumber'];

	    		$data['targetusername'] = $target_member['realname'];

	    		$data['moneytype'] = 0;

	    		$data['money'] = $money;

	    		$data['createtime'] = time();

	    		$params = array(

	    			'table_name' => 'transfer',

	    			'data' => $data
	    		);

	    		$transfer_add = $this -> model -> my_add($params);

	    		if ($transfer_add)
	    		{

					//增加财务流水
					$data['money'] = $money;

					$data['moneytype'] = 2;

					$data['changetype'] = 14;

					$data['realname'] = "{$member['realname']}";

					$data['targetrealname'] = "{$target_member['realname']}";

					$data['status'] = 1;

					$data['targetuserid'] = $target_member['uid'];

					$data['targetusernumber'] = $target_member['usernumber'];

					$data['userid'] = $member['uid'];

					$data['usernumber'] = $member['usernumber'];

					$data['recordtype'] = 0;

		    		$data['createtime'] = time();

		    		$params = array(

		    			'table_name' => 'money_change',

		    			'data' => $data
		    		);

		    		$transfer_flow = $this -> model -> my_add($params);


					$to_data['money'] = $money;

					$to_data['moneytype'] = 2;

					$to_data['changetype'] = 14;

					$to_data['realname'] = "{$target_member['realname']}";

					$to_data['targetrealname'] = "{$member['realname']}";

					$to_data['status'] = 1;

					$to_data['targetuserid'] = $member['uid'];

					$to_data['targetusernumber'] = $member['usernumber'];

					$to_data['userid'] = $target_member['uid'];

					$to_data['usernumber'] = $target_member['usernumber'];

					$to_data['recordtype'] = 1;

		    		$to_data['createtime'] = time();

		    		$params = array(

		    			'table_name' => 'money_change',

		    			'data' => $to_data
		    		);

					$to_transfer_flow = $this -> model -> my_add($params);

	    			redirect(__APP__.'/Finances/transfer', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('记录保存失败 请稍后重试');
	    		}
	    	}

	    	$this -> assign('member', $member);

	    	$this -> display();
	    }

	    /**
		 * 币种转换
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function convert()
	    {
	    	$params = array(

	    		'table_name' => 'member',

	    		'where' => "uid = {$_SESSION['Rongzi']['user']['uid']}"
	    	);

	    	$member = $this -> model -> my_find($params);

	    	if (isset($_POST['form_key']) && $_POST['form_key'] == 'yes')
	    	{
	    		$transfer_money = isset($_POST['transfer_money']) && intval($_POST['transfer_money']) > 0 && intval($_POST['transfer_money']) <= $member['jiangjinbi'] ? intval($_POST['transfer_money']) : $this -> _back('非法的转币数量');

	    		//这里是转换比例
	    		$persant = 1;

	    		$params = array(

	    			'table_name' => 'member',

	    			'where' => "uid = {$member['uid']}",

	    			'data' => array(

	    				'jiangjinbi' => $member['jiangjinbi'] - $transfer_money,

	    				'baodanbi' => $member['baodanbi'] + ($persant * $transfer_money)
	    			)
	    		);

	    		$member_save = $this -> model -> my_save($params);

	    		if ($member_save)
	    		{
					//增加财务流水
					$data['money'] = $transfer_money;

					$data['moneytype'] = 1;

					$data['changetype'] = 15;

					$data['realname'] = "{$member['realname']}";

					$data['targetrealname'] = "{$member['realname']}";

					$data['status'] = 1;

					$data['targetuserid'] = $member['uid'];

					$data['targetusernumber'] = $member['usernumber'];

					$data['userid'] = $member['uid'];

					$data['usernumber'] = $member['usernumber'];

					$data['recordtype'] = 0;

		    		$data['createtime'] = time();

		    		$params = array(

		    			'table_name' => 'money_change',

		    			'data' => $data
		    		);

		    		$transfer_flow = $this -> model -> my_add($params);


					$to_data['money'] = $transfer_money;

					$to_data['moneytype'] = 2;

					$to_data['changetype'] = 15;

					$to_data['realname'] = "{$member['realname']}";

					$to_data['targetrealname'] = "{$member['realname']}";

					$to_data['status'] = 1;

					$to_data['targetuserid'] = $member['uid'];

					$to_data['targetusernumber'] = $member['usernumber'];

					$to_data['userid'] = $member['uid'];

					$to_data['usernumber'] = $member['usernumber'];

					$to_data['recordtype'] = 1;

		    		$to_data['createtime'] = time();

		    		$params = array(

		    			'table_name' => 'money_change',

		    			'data' => $to_data
		    		);

					$to_transfer_flow = $this -> model -> my_add($params);

	    			redirect(__APP__.'/Finances/convert', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('转换失败 请稍后重试');
	    		}
	    	}

			$this -> assign('member', $member);

	    	$this -> display();
	    }

	    /**
		 * 转币记录
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function transfer_list()
	    {
	    	$params = array(

	    		'table_name' => 'transfer',

	    		'where' => "userid = {$_SESSION['Rongzi']['user']['uid']} OR targetuserid = {$_SESSION['Rongzi']['user']['uid']}",

				'order' => "createtime desc"
			);

	    	$transfers = $this -> model -> order_select($params);

	    	$this -> assign('transfers', $transfers['result']);

			$this -> assign('page', $transfers['page']);

	    	$this -> display();
	    }

		/**
		 * 二级密码登陆页
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function password()
	    {
			$form_key = htmlspecialchars($_POST['form_key']);

			if ($form_key == 'yes')
			{
				$usernumber = $_SESSION['Rongzi']['user']['usernumber'];

		    	$password = md5(md5($_POST['password']));

		    	$params = array(

		    		'table_name' => 'member',

		    		'where' => "usernumber = '{$usernumber}' AND psd2 = '{$password}' AND status = 1"
		    	);

		    	$member = $this -> model -> my_find($params);

		    	if ($member)
		    	{
		    		$_SESSION['Rongzi']['twopwd'] = true;

		    		$data['last_time'] = time();

		    		$params = array(

		    			'table_name' => 'member',

		    			'where' => "uid = {$member['uid']} AND status = 1",

		    			'data' => $data
		    		);

		    		$member_save = $this -> model -> my_save($params);

					$call_back = urldecode($_POST['call_back']);

		    		redirect($call_back, 0);
		    	}
		    	else
		    	{
		    		$this -> _back('登陆失败，请重试。');
		    	}
			}

			$this -> display();
	    }
	}
