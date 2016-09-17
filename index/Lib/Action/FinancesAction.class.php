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
		 * 首页
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
	    		$data['money'] = isset($_POST['money']) && floatval($_POST['money']) > 0 ? floatval($_POST['money']) : $this -> _back('非法的提现金额');

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

	    		$data['createtime'] = time();

	    		$data['status'] = 1;

	    		$params = array(

	    			'table_name' => 'withdrawal',

	    			'data' => $data
	    		);

	    		$withdrawal_add = $this -> model -> my_add($params);

	    		if ($withdrawal_add)
	    		{
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
		 * 消费商转币
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

	    		'where' => "userid = {$_SESSION['Rongzi']['user']['uid']} OR targetuserid = {$_SESSION['Rongzi']['user']['uid']}"
	    	);

	    	$transfers = $this -> model -> easy_select($params);

	    	$this -> assign('transfers', $transfers);

	    	$this -> display();
	    }
	}
