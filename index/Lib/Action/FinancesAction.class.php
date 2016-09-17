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
		 * 首页
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
	}
