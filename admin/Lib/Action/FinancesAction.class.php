<?php if (!defined('THINK_PATH')) exit();

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
	 * 财务流水
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function finance_flow()
    {
    	$params = array(

    		'table_name' => 'money_change',

    		'where' => "1",

    		'order' => 'createtime desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }

    /**
	 * 转账明细
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

    		'where' => "1",

    		'order' => 'createtime desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }

    /**
	 * 公司充值
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function company_recharge()
    {
    	if (isset($_POST['form_key']) && htmlspecialchars($_POST['form_key']) == 'yes')
    	{
    		$usernumber = isset($_POST['usernumber']) ? intval($_POST['usernumber']) : $this -> _back('非法的消费商');

    		$money = isset($_POST['money']) ? floatval($_POST['money']) : $this -> _back('非法的金额');

    		$type = isset($_POST['type']) ? intval($_POST['type']) : $this -> _back('非法的类型');

    		//查询目标用户
    		$params = array(

    			'table_name' => 'member',

    			'where' => "usernumber = {$usernumber}"
    		);

    		$member_find = $this -> model -> my_find($params);

    		if (!$member_find)
    		{
    			$this -> _back('无效的消费商');
    		}

    		//查询当前登陆者
    		$params = array(

    			'table_name' => 'member',

    			'where' => "uid = {$_SESSION['Rongzi']['admin']['id']}"
    		);

    		$user_find = $this -> model -> my_find($params);

    		if (!$user_find)
    		{
    			$this -> _back('请先登录');
    		}

    		switch ($type)
    		{
    			case 1 : //奖金币

    				$data['jiangjinbi'] = $member_find['jiangjinbi'] + $money;

    				$balance = $data['jiangjinbi'];

    				$moneytype = 6;

    				break;

    			case 2 : //报单币

    				$data['baodanbi'] = $member_find['baodanbi'] + $money;

    				$balance = $data['baodanbi'];

    				$moneytype = 2;

    				break;

    			case 3 : //激活币

    				$data['jihuobi'] = $member_find['jihuobi'] + $money;

    				$balance = $data['jihuobi'];

    				$moneytype = 4;

    				break;

    			case 4 : //戎子盾

    				$data['rongzidun'] = $member_find['rongzidun'] + $money;

    				$balance = $data['rongzidun'];

    				$moneytype = 3;

    				break;

    			case 5 : //奖励积分

    				$data['jianglijifen'] = $member_find['jianglijifen'] + $money;

    				$balance = $data['jianglijifen'];

    				$moneytype = 5;

    				break;
    		}

    		$data['update_time'] = time();

    		$params = array(

    			'table_name' => 'member',

    			'where' => "usernumber = {$usernumber}",

    			'data' => $data
    		);

    		$member_save = $this -> model -> my_save($params);

    		$money_change_data['moneytype'] = $moneytype;
    			
			$money_change_data['status'] = $member_save ? 1 : 0;
			
			$money_change_data['targetuserid'] = $member_find['uid'];
			
			$money_change_data['targetusernumber'] = $member_find['usernumber'];
			
			$money_change_data['targetrealname'] = $member_find['realname'];
			
			$money_change_data['userid'] = $user_find['uid'];
			
			$money_change_data['usernumber'] = $user_find['usernumber'];
			
			$money_change_data['realname'] = $user_find['realname'];
			
			$money_change_data['changetype'] = 1;
			
			$money_change_data['recordtype'] = 1;
			
			$money_change_data['money'] = $money;
			
			$money_change_data['hasmoney'] = $balance;
			
			$money_change_data['createtime'] = time();

			//存入流水
			$params = array(

				'table_name' => 'money_change',

				'data' => $money_change_data
			);

			$money_change_add = $this -> model -> my_add($params);

    		if ($member_save)
    		{
    			redirect(__APP__.'/Finances/recharge_list', 0);
    		}
    		else
    		{
    			$this -> _back('充值失败 请重试');
    		}
    	}

    	$this -> display();
    }

    /**
	 * 公司扣币
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function company_deduct_money()
    {
    	if (isset($_POST['form_key']) && htmlspecialchars($_POST['form_key']) == 'yes')
    	{
    		$usernumber = isset($_POST['usernumber']) ? intval($_POST['usernumber']) : $this -> _back('非法的消费商');

    		$money = isset($_POST['money']) ? floatval($_POST['money']) : $this -> _back('非法的金额');

    		$type = isset($_POST['type']) ? intval($_POST['type']) : $this -> _back('非法的类型');

    		//查询目标用户
    		$params = array(

    			'table_name' => 'member',

    			'where' => "usernumber = {$usernumber}"
    		);

    		$member_find = $this -> model -> my_find($params);

    		if (!$member_find)
    		{
    			$this -> _back('无效的消费商');
    		}

    		//查询当前登陆者
    		$params = array(

    			'table_name' => 'member',

    			'where' => "uid = {$_SESSION['Rongzi']['admin']['id']}"
    		);

    		$user_find = $this -> model -> my_find($params);

    		if (!$user_find)
    		{
    			$this -> _back('请先登录');
    		}

    		switch ($type)
    		{
    			case 1 : //奖金币

    				$data['jiangjinbi'] = $member_find['jiangjinbi'] - $money;

    				$balance = $data['jiangjinbi'];

    				$moneytype = 6;

    				break;

    			case 2 : //报单币

    				$data['baodanbi'] = $member_find['baodanbi'] - $money;

    				$balance = $data['baodanbi'];

    				$moneytype = 2;

    				break;

    			case 3 : //激活币

    				$data['jihuobi'] = $member_find['jihuobi'] - $money;

    				$balance = $data['jihuobi'];

    				$moneytype = 4;

    				break;

    			case 4 : //戎子盾

    				$data['rongzidun'] = $member_find['rongzidun'] - $money;

    				$balance = $data['rongzidun'];

    				$moneytype = 3;

    				break;

    			case 5 : //奖励积分

    				$data['jianglijifen'] = $member_find['jianglijifen'] - $money;

    				$balance = $data['jianglijifen'];

    				$moneytype = 5;

    				break;
    		}

    		$data['update_time'] = time();

    		$params = array(

    			'table_name' => 'member',

    			'where' => "usernumber = {$usernumber}",

    			'data' => $data
    		);

    		$member_save = $this -> model -> my_save($params);

    		$money_change_data['moneytype'] = $moneytype;
    			
			$money_change_data['status'] = $member_save ? 1 : 0;
			
			$money_change_data['targetuserid'] = $member_find['uid'];
			
			$money_change_data['targetusernumber'] = $member_find['usernumber'];
			
			$money_change_data['targetrealname'] = $member_find['realname'];
			
			$money_change_data['userid'] = $user_find['uid'];
			
			$money_change_data['usernumber'] = $user_find['usernumber'];
			
			$money_change_data['realname'] = $user_find['realname'];
			
			$money_change_data['changetype'] = 2;
			
			$money_change_data['recordtype'] = 0;
			
			$money_change_data['money'] = $money;
			
			$money_change_data['hasmoney'] = $balance;
			
			$money_change_data['createtime'] = time();

			//存入流水
			$params = array(

				'table_name' => 'money_change',

				'data' => $money_change_data
			);

			$money_change_add = $this -> model -> my_add($params);

    		if ($member_save)
    		{
    			redirect(__APP__.'/Finances/finance_flow', 0);
    		}
    		else
    		{
    			$this -> _back('扣币失败 请重试');
    		}
    	}

    	$this -> display();
    }

    /**
	 * 充值记录
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function recharge_list()
    {
    	$params = array(

    		'table_name' => 'money_change',

    		'where' => "changetype = 1",

    		'order' => 'createtime desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }

    /**
	 * 提现记录（未完成 疑似跟上边功能重复）
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

    		'where' => "1",

    		'order' => 'createtime desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }
}
