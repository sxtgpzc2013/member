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
	class OrdersAction extends CommonAction {

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

			$this -> model = D('Orders');
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
	    	$params = array(

	    		'table_name' => 'orders',

	    		'where' => "user_id = {$_SESSION['Rongzi']['user']['uid']} AND is_del = 0",

	    		'order' => 'updated_at desc'
	    	);

	    	$result = $this -> model -> order_select($params);

	    	$this -> assign('result', $result);

			$this -> display();
	    }

	    /**
		 * 新增
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function add()
	    {
	    	$cart = $_SESSION['Rongzi']['cart'];

	    	$keys = array_keys($_SESSION['Rongzi']['cart']);

	    	if (empty($cart))
	    	{
	    		$this -> _back('请先添加商品');
	    	}

	    	$params = array(

	    		'table_name' => 'user_address',

	    		'where' => "user_id = {$_SESSION['Rongzi']['user']['uid']} AND is_del = 0"
	    	);

	    	$result['address'] = $this -> model -> easy_select($params);

	    	$params = array(

	    		'table_name' => 'products',

	    		'where' => "is_del = 0 AND status = 0 AND id IN(".implode(',', $keys).")"
	    	);

	    	$result['products'] = $this -> model -> easy_select($params);

	    	$result['total_price'] = 0;

	    	$result['total_rprice'] = 0;

	    	$result['total_jprice'] = 0;

	    	foreach ($result['products'] as $k => $v)
	    	{
	    		if (isset($_SESSION['Rongzi']['cart'][$v['id']]['count']) && $_SESSION['Rongzi']['cart'][$v['id']]['count'] > 0)
	    		{
	    			$result['products'][$k]['count'] = $_SESSION['Rongzi']['cart'][$v['id']]['count'];

	    			$result['products'][$k]['total_jprice'] = $result['products'][$k]['count'] * $v['jprice'];

	    			$result['products'][$k]['total_rprice'] = $result['products'][$k]['count'] * $v['rprice'];

	    			$result['total_rprice'] += $result['products'][$k]['total_rprice'];

	    			$result['total_jprice'] += $result['products'][$k]['total_jprice'];

	    			$result['total_price'] += ($result['products'][$k]['total_jprice'] + $result['products'][$k]['total_rprice']);
	    		}
	    		else
	    		{
	    			unset($result['products'][$k]);
	    		}
	    	}

	    	if (isset($_POST['form_key']) && htmlspecialchars($_POST['form_key']) == 'yes')
	    	{
	    		$address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : $this -> _back('请选择收货人信息');

	    		$params = array(

	    			'table_name' => 'user_address',

	    			'where' => "id = {$address_id} AND is_del = 0 AND user_id = {$_SESSION['Rongzi']['user']['uid']}"
	    		);

	    		$address_find = $this -> model -> my_find($params);

	    		if (!$address_find)
	    		{
	    			$this -> _back('请选择正确的收货人信息');
	    		}

	    		$params = array(

	    			'table_name' => 'member',

	    			'where' => "uid = {$_SESSION['Rongzi']['user']['uid']} AND status = 1"
	    		);

	    		$member_find = $this -> model -> my_find($params);

	    		if (!$member_find)
	    		{
	    			$this -> _back('您的身份信息有误 请重新登录');
	    		}

	    		$data['order_code'] = $this -> get_order_number();

	    		$data['user_id'] = $_SESSION['Rongzi']['user']['uid'];

	    		$data['sendName'] = $address_find['name'];

	    		$data['sendAddress'] = $address_find['area'].' '.$address_find['address'];

	    		$data['memberCode'] = $member_find['usernumber'];

	    		$data['sendTel'] = $address_find['mobile'];

	    		$data['sendCommpany'] = '';

	    		$data['total_price'] = $result['total_price'];

	    		$data['total_jprice'] = $result['total_jprice'];

	    		$data['total_rprice'] = $result['total_rprice'];

	    		$data['notice'] = isset($_POST['notice']) ? htmlspecialchars($_POST['notice']) : '';

	    		$data['status'] = 1;

	    		$data['created_at'] = time();

	    		$data['updated_at'] = time();

	    		$data['is_del'] = 0;

	    		$data['pay_type'] = 0;

	    		$data['logistics_number'] = '';

	    		$data['logistics_tel'] = '';

	    		if ($member_find['jiangjinbi'] < $result['total_jprice'])
	    		{
	    			$this -> _back('奖金币余额不足');
	    		}

	    		if ($member_find['rongzidun'] < $result['total_rprice'])
	    		{
	    			$this -> _back('戎子盾余额不足');
	    		}

	    		$params = array(

	    			'table_name' => 'orders',

	    			'data' => $data
	    		);

	    		$order_add = $this -> model -> my_add($params);

	    		if ($order_add)
	    		{
	    			foreach ($result['products'] as $k => $v)
	    			{
    					$params = array(

    						'table_name' => 'products',

    						'where' => "id = {$v['id']} AND status = 0 AND is_del = 0 AND surplus > 0"
    					);

    					$product_find = $this -> model -> my_find($params);

    					if (!$product_find)
    					{
    						//扣除订单金额
    						$order_back_data['total_price'] = $data['total_price'] - $v['total_rprice'] - $v['total_jprice'];

    						$order_back_data['updated_at'] = time();

    						$params = array(

    							'table_name' => 'orders',

    							'where' => "id = {$order_add}",

    							'data' => $order_back_data
    						);

    						$order_save = $this -> model -> my_save($params);

    						continue;
    					}

	    				$item_data['pro_id'] = $v['id'];

	    				$item_data['name'] = $product_find['name'];

	    				$item_data['logo'] = $product_find['logo'];

	    				$item_data['content'] = $product_find['content'];

	    				$item_data['unit_jprice'] = $product_find['jprice'];

	    				$item_data['unit_rprice'] = $product_find['rprice'];

	    				$item_data['order_id'] = $order_add;

	    				$item_data['rprice'] = $v['total_rprice'];

	    				$item_data['jprice'] = $v['total_jprice'];

	    				$item_data['count'] = $v['count'];

	    				$item_data['total_price'] = $v['total_rprice'] + $v['total_jprice'];

	    				$item_data['created_at'] = time();

	    				$item_data['updated_at'] = time();

	    				$item_data['is_del'] = 0;

	    				$params = array(

	    					'table_name' => 'order_items',

	    					'data' => $item_data
	    				);

	    				$item_add = $this -> model -> my_add($params);

	    				if ($item_add)
	    				{
	    					//扣除库存
	    					$product_data['surplus'] = ($product_find['surplus'] - $v['count']) >= 0 ? intval($product_find['surplus'] - $v['count']) : 0;

	    					//增加已售卖数量
	    					$product_data['sell_count'] = $product_find['sell_count'] + $v['count'];

	    					$product_data['updated_at'] = time();

	    					$params = array(

	    						'table_name' => 'products',

	    						'where' => "id = {$v['id']}",

	    						'data' => $product_data
	    					);

	    					$product_save = $this -> model -> my_save($params);
	    				}
	    			}

	    			//扣钱
	    			$params = array(

	    				'table_name' => 'member',

	    				'where' => "uid = {$member_find['uid']}",

	    				'data' => array(

	    					'jiangjinbi' => $member_find['jiangjinbi'] - $result['total_jprice'],

	    					'rongzidun' => $member_find['rongzidun'] - $result['total_rprice'],

	    					'update_time' => time()
	    				)
	    			);

	    			$member_save = $this -> model -> my_save($params);

	    			//计入流水
	    			$params = array( //奖金币

	    				'table_name' => 'money_change',

	    				'data' => array(

	    					'moneytype' => 1,

	    					'status' => 1,

	    					'targetuserid' => 1,

	    					'targetusernumber' => 1,

	    					'userid' => $member_find['uid'],

	    					'usernumber' => $member_find['usernumber'],

	    					'changetype' => 13,

	    					'recordtype' => 0,

	    					'money' => $result['total_jprice'],

	    					'hasmoney' => $member_find['jiangjinbi'] - $result['total_jprice'],

	    					'createtime' => time(),

	    					'targetrealname' => '戎子',

	    					'realname' => $member_find['realname']
	    				)
	    			);

	    			$jiangjinbi_money_change_add = $this -> model -> my_add($params);

	    			$params = array( //戎子盾

	    				'table_name' => 'money_change',

	    				'data' => array(

	    					'moneytype' => 3,

	    					'status' => 1,

	    					'targetuserid' => 1,

	    					'targetusernumber' => 1,

	    					'userid' => $member_find['uid'],

	    					'usernumber' => $member_find['usernumber'],

	    					'changetype' => 13,

	    					'recordtype' => 0,

	    					'money' => $result['total_rprice'],

	    					'hasmoney' => $member_find['rongzidun'] - $result['total_rprice'],

	    					'createtime' => time(),

	    					'targetrealname' => '戎子',

	    					'realname' => $member_find['realname']
	    				)
	    			);

	    			$rongzidun_money_change_add = $this -> model -> my_add($params);

	    			//计算最大奖金数
	    			// $params = array(

	    			// 	'table_name' => 'bonus_rule',

	    			// 	'where' => "category = 'maxcash' AND `key` = {$member_find['userrank']}"
	    			// );

	    			//最大比例
	    			// $maxcash_find = $this -> model -> my_find($params);

	    			// $params = array(

	    			// 	'table_name' => 'bonus_rule',

	    			// 	'where' => "category = 'userrank' AND `key` = {$member_find['userrank']}"
	    			// );

	    			//最大金额
	    			// $userrank_find = $this -> model -> my_find($params);
	    			
	    			//用户当前最大金额
	    			$max_bonus = $member_find['max_bonus'];

	    			// if ($member_find['proxy_state'] == 1)
	    			// {
		    			//获取销售补贴比例
		    			$params = array(

		    				'table_name' => 'bonus_rule',

		    				'where' => "category = 'salecash' AND `key` = {$member_find['userrank']}"
		    			);

		    			$bonus_rule_find = $this -> model -> my_find($params);

		    			$params = array(

		    				'table_name' => 'member',

		    				'where' => "uid = {$member_find['uid']}",

		    				'data' => array(

		    					'jiangjinbi' => ($member_find['jiangjinbi'] - $result['total_jprice']) + ($result['total_price'] * ($bonus_rule_find['value']/100) * 0.8),

		    					'update_time' => time()
		    				)
		    			);

		    			//判断是否超出最大奖金限制
		    			$add_bonus_value = $result['total_price'] * ($bonus_rule_find['value']/100); //应发

		    			// if (($add_bonus_value + $max_bonus) < ($maxcash_find['value'] * $userrank_find['value']))
		    			// {
			    			$mem_save = $this -> model -> my_save($params);

			    			$member_data['jiangjinbi'] = ($member_find['jiangjinbi'] - $result['total_jprice']) + ($result['total_price'] * ($bonus_rule_find['value']/100) * 0.8);

			    			//写入bonus_detail
			    			$member_jiangjinbi = $result['total_price'] * ($bonus_rule_find['value']/100);

			    			//写入财务
			    			$finance_save = $this -> _save_to_finance($add_bonus_value, 'expend');

			    			$params = array(

			    				'table_name' => 'bonus_detail',

			    				'data' => array(

			    					'touserid' => $member_find['uid'],

			    					'tousernumber' => $member_find['usernumber'],

			    					'torealname' => $member_find['realname'],

			    					'moneytype' => 6,

			    					'jiangjinbi' => $member_jiangjinbi * 0.8,

			    					'lovemoney' => $member_jiangjinbi * 0.01,

			    					'platmoney' => $member_jiangjinbi * 0.02,

			    					'taxmoney' => $member_jiangjinbi * 0.17,

			    					'total' => $member_jiangjinbi,

			    					'real_total' => $member_jiangjinbi * 0.8,

			    					'createdate' => time()
			    				)
			    			);

			    			$member_detail_add = $this -> model -> my_add($params);

			    			//写入流水
			    			$sell_money_change = $this -> _send_money_change(array(

			    				'member' => $member_find,

		    					'money' => $member_jiangjinbi,

		    					'hasmoney' => $member_data['jiangjinbi'],

		    					'changetype' => 8
			    			));

			    			//写入流水
			    			// $params = array(

			    			// 	'table_name' => 'money_change',

			    			// 	'data' => array(

			    			// 		'moneytype' => 1,

			    			// 		'status' => 1,

			    			// 		'targetuserid' => $member_find['uid'],

			    			// 		'targetusernumber' => $member_find['usernumber'],

			    			// 		'userid' => 1,

			    			// 		'usernumber' => 1,

			    			// 		'changetype' => 8,

			    			// 		'recordtype' => 1,

			    			// 		'money' => ($result['total_price'] * ($bonus_rule_find['value']/100) * 0.8),

			    			// 		'hasmoney' => $member_data['jiangjinbi'],

			    			// 		'createtime' => time(),

			    			// 		'realname' => '戎子',

			    			// 		'targetrealname' => $member_find['realname']
			    			// 	)
			    			// );

			    			// $jiangjinbi_8_add = $this -> model -> my_add($params);

			    			//增加用户max_bonus
			    			$params = array(

			    				'table_name' => 'member',

			    				'where' => "id = {$member_find['id']}",

			    				'data' => array(

			    					'max_bonus' => $add_bonus_value + $max_bonus,

			    					'update_time' => time()
			    				)
			    			);

			    			$member_max_bonus_save = $this -> model -> my_save($params);
			    		// }
			    		// else //超出最大限制 修改状态为不分红
			    		// {
			    		// 	$params = array(

			    		// 		'table_name' => 'member',

			    		// 		'where' => "id = {$member_find['id']}",

			    		// 		'data' => array(

			    		// 			'proxy_state' => 0,

			    		// 			'update_time' => time()
			    		// 		)
			    		// 	);

			    		// 	$member_proxy_state_save = $this -> model -> my_save($params);
			    		// }
		    		// }

	    			//获取服务补贴比例
	    			$params = array(

	    				'table_name' => 'bonus_rule',

	    				'where' => "category = 'servicecash' AND `key` IN(1,2,3)"
	    			);

	    			$bonus_rules = $this -> model -> easy_select($params);

	    			foreach ($bonus_rules as $k => $v)
	    			{
	    				$bonus_rules_re[$v['key']] = $v['value']/100;
	    			}

	    			//获取上三代ID
	    			$upids = explode(',', $member_find['recommenduserpath']);

	    			//查询上三代信息
	    			$params = array(

	    				'table_name' => 'member',

	    				'where' => "status = 1 AND uid IN ({$member_find['recommenduserpath']})",

	    				'order' => "field(uid,{$member_find['recommenduserpath']})"
	    			);

	    			$members = $this -> model -> order_select($params, 'no');

	    			$upmember[1] = count($members) >= 2 ? $members[count($members)-2] : 0;

	    			$upmember[2] = count($members) >= 3 ? $members[count($members)-3] : 0;

	    			$upmember[3] = count($members) >= 4 ? $members[count($members)-4] : 0;

	    			//更新
	    			foreach ($upmember as $k => $v)
	    			{
		    			if ($v['uid'] != 0 && $v['uid'] != 1)
		    			{
		    				// $params = array(

		    				// 	'table_name' => 'member',

		    				// 	'where' => "uid = {$v}"
		    				// );

		    				// $up_find = $this -> model -> my_find($params);

		    				//计算最大奖金数
			    			// $params = array(

			    			// 	'table_name' => 'bonus_rule',

			    			// 	'where' => "category = 'maxcash' AND `key` = {$v['userrank']}"
			    			// );

			    			//最大比例
			    			// $up_maxcash_find = $this -> model -> my_find($params);

			    			// $params = array(

			    			// 	'table_name' => 'bonus_rule',

			    			// 	'where' => "category = 'userrank' AND `key` = {$v['userrank']}"
			    			// );

			    			//最大金额
			    			// $up_userrank_find = $this -> model -> my_find($params);
			    			
			    			//用户当前最大金额
			    			$up_max_bonus = $v['max_bonus'];

			    			//要增加的奖金币应发
			    			$up_add_jprice = $result['total_price'] * ($bonus_rule_find['value']/100) * $bonus_rules_re[$k];

			    			//实发后余额
			    			$up_add_real_jprice = $v['jiangjinbi'] + ($up_add_jprice * 0.8);

			    			//最大限额
			    			$up_max_bonus_top = $up_maxcash_find['value'] * $up_userrank_find['value'];

			    			$params = array(

			    				'table_name' => 'member',

			    				'where' => "uid = {$v}",

			    				'data' => array(

			    					'update_time' => time()
			    				)
			    			);

			    			// if (($up_max_bonus + $up_add_jprice) >= $up_max_bonus_top)
			    			// {
			    			// 	$params['data']['proxy_state'] = 0;

			    			// 	$up_save = $this -> model -> my_save($params);
			    			// }
			    			// else
			    			// {
			    				$params['data']['jiangjinbi'] = $up_add_real_jprice;

			    				$up_save = $this -> model -> my_save($params);

			    				//写入财务
			    				$finance_save = $this -> _save_to_finance($up_add_jprice, 'expend');

			    				//写入流水
				    			$fuwu_money_change = $this -> _send_money_change(array(

				    				'member' => $v,

			    					'money' => $up_add_jprice,

			    					'hasmoney' => $up_add_real_jpricev,

			    					'changetype' => 9
				    			));

				    			//计入流水
				    			// $params = array(

				    			// 	'table_name' => 'money_change',

				    			// 	'data' => array(

				    			// 		'moneytype' => 1,

				    			// 		'status' => $up_save,

				    			// 		'targetuserid' => $v['uid'],

				    			// 		'targetusernumber' => $v['usernumber'],

				    			// 		'userid' => 1,

				    			// 		'usernumber' => 1,

				    			// 		'userid' => 1,

				    			// 		'usernumber' => 1,

				    			// 		'changetype' => 9,

				    			// 		'recordtype' => 1,

				    			// 		'money' => $result['total_price'] * ($bonus_rule_find['value']/100) * $bonus_rules_re[$k] * 0.8,

				    			// 		'hasmoney' => $v['jiangjinbi'] + ($result['total_price'] * ($bonus_rule_find['value']/100) * $bonus_rules_re[$k] * 0.8),

				    			// 		'createtime' => time(),

				    			// 		'realname' => '戎子',

				    			// 		'targetrealname' => $v['realname']
				    			// 	)
				    			// );

				    			// $up_money_change_add = $this -> model -> my_add($params);

				    			//写入bonus_detail
				    			$up_jiangjinbi = $result['total_price'] * ($bonus_rule_find['value']/100) * $bonus_rules_re[$k];
				    			$params = array(

				    				'table_name' => 'bonus_detail',

				    				'data' => array(

				    					'touserid' => $v['uid'],

				    					'tousernumber' => $v['usernumber'],

				    					'torealname' => $v['realname'],

				    					'moneytype' => 7,

				    					'jiangjinbi' => $up_jiangjinbi * 0.8,

				    					'lovemoney' => $up_jiangjinbi * 0.01,

				    					'platmoney' => $up_jiangjinbi * 0.02,

				    					'taxmoney' => $up_jiangjinbi * 0.17,

				    					'total' => $up_jiangjinbi,

				    					'real_total' => $up_jiangjinbi * 0.8,

				    					'createdate' => time()
				    				)
				    			);

				    			$up_detail_add = $this -> model -> my_add($params);
			    			// }
			    		}
			    	}

	    			//清空购物车
	    			$_SESSION['Rongzi']['cart'] = array();

	    			redirect(__APP__.'/Orders/index', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('订单创建失败 请重试');
	    		}
	    	}

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

	    /**
		 * 写入财务记录
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function _save_to_finance($money, $type)
	    {
	    	$params = array(

	    		'table_name' => 'finance',

	    		'where' => 'id = 1'
	    	);

	    	if ($type == 'income') //存入
	    	{
	    		$params['data']['income'] = "`income` + {$money}";
	    	}
	    	else //支出
	    	{
	    		$params['data']['expend'] = "`expend` + {$money}";
	    	}

	    	$finance_save = $this -> model -> my_save($params);

	    	return $finance_save;
	    }

	    /**
		 * 详情
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function detail()
	    {
	    	$id = isset($_GET['id']) ? intval($_GET['id']) : $this -> _back('缺少必要参数');

	    	$params = array(

	    		'table_name' => 'orders',

	    		'where' => "id = {$id} AND is_del = 0"
	    	);

	    	$result['order'] = $this -> model -> my_find($params);

	    	if (!$result['order'])
	    	{
	    		$this -> _back('无效或已被删除的订单');
	    	}

	    	$params = array(

	    		'table_name' => 'order_items',

	    		'where' => "order_id = {$id} AND is_del = 0"
	    	);

	    	$result['items'] = $this -> model -> easy_select($params);

	    	$params = array(

	    		'table_name' => 'member',

	    		'where' => "uid = {$_SESSION['Rongzi']['user']['uid']}"
	    	);

	    	$result['member'] = $this -> model -> my_find($params);

	    	$this -> assign('result', $result);

			$this -> display();
	    }

	    /**
		 * 签收
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function sign()
	    {
	    	$id = isset($_GET['id']) ? intval($_GET['id']) : $this -> _back('缺少必要参数');

	    	$params = array(

	    		'table_name' => 'orders',

	    		'where' => "user_id = {$_SESSION['Rongzi']['user']['uid']} AND id = {$id} AND is_del = 0 AND status = 2",

	    		'data' => array(

	    			'status' => 3,

	    			'updated_at' => time()
	    		)
	    	);

	    	$order_save = $this -> model -> my_save($params);

	    	if ($order_save)
	    	{
	    		redirect(__APP__.'/Orders/index', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('签收失败 请重试');
	    	}
	    }

	    /**
		 * 写入补贴流水记录
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function _send_money_change($dt)
	    {
	    	//写入奖金币增加流水
			$params = array(

				'table_name' => 'money_change',

				'data' => array(

					'moneytype' => 1,

					'status' => 1,

					'targetuserid' => $dt['member']['uid'],

					'targetusernumber' => $dt['member']['usernumber'],

					'userid' => 1,

					'usernumber' => 1,

					'changetype' => $dt['changetype'],

					'recordtype' => 1,

					'money' => $dt['money'] * 0.8,

					'hasmoney' => $dt['hasmoney'],

					'createtime' => time(),

					'realname' => '戎子',

					'targetrealname' => $dt['member']['realname']
				)
			);

			$jiangjinbi_add = $this -> model -> my_add($params);

			//写入奖金币出账流水
			$params = array(

				'table_name' => 'money_change',

				'data' => array(

					'moneytype' => 1,

					'status' => 1,

					'targetuserid' => $dt['member']['uid'],

					'targetusernumber' => $dt['member']['usernumber'],

					'userid' => 1,

					'usernumber' => 1,

					'changetype' => $dt['changetype'],

					'recordtype' => 0,

					'money' => $dt['money'] * 0.8,

					'hasmoney' => $dt['hasmoney'],

					'createtime' => time(),

					'realname' => $dt['member']['realname'],

					'targetrealname' => '戎子'
				)
			);

			$jiangjinbi_rongzi_add = $this -> model -> my_add($params);

	    	//写入爱心基金增加流水
			$params = array(

				'table_name' => 'money_change',

				'data' => array(

					'moneytype' => 6,

					'status' => 1,

					'targetuserid' => $dt['member']['uid'],

					'targetusernumber' => $dt['member']['usernumber'],

					'userid' => 1,

					'usernumber' => 1,

					'changetype' => $dt['changetype'],

					'recordtype' => 0,

					'money' => $dt['money'] * 0.01,

					'hasmoney' => $dt['money'] * 0.01,

					'createtime' => time(),

					'realname' => '戎子',

					'targetrealname' => $dt['member']['realname']
				)
			);

			$lovemoney_add = $this -> model -> my_add($params);

			//写入爱心基金出账流水
			$params = array(

				'table_name' => 'money_change',

				'data' => array(

					'moneytype' => 6,

					'status' => 1,

					'targetuserid' => 1,

					'targetusernumber' => 1,

					'userid' => $dt['member']['uid'],

					'usernumber' => $dt['member']['usernumber'],

					'changetype' => $dt['changetype'],

					'recordtype' => 1,

					'money' => $dt['money'] * 0.01,

					'hasmoney' => $dt['money'] * 0.01,

					'createtime' => time(),

					'realname' => $dt['member']['realname'],

					'targetrealname' => '戎子'
				)
			);

			$lovemoney_rongzi_add = $this -> model -> my_add($params);

	    	//写入平台管理费增加流水
			$params = array(

				'table_name' => 'money_change',

				'data' => array(

					'moneytype' => 7,

					'status' => 1,

					'targetuserid' => $dt['member']['uid'],

					'targetusernumber' => $dt['member']['usernumber'],

					'userid' => 1,

					'usernumber' => 1,

					'changetype' => $dt['changetype'],

					'recordtype' => 0,

					'money' => $dt['money'] * 0.02,

					'hasmoney' => $dt['money'] * 0.02,

					'createtime' => time(),

					'realname' => '戎子',

					'targetrealname' => $dt['member']['realname']
				)
			);

			$platmoney_add = $this -> model -> my_add($params);

	    	//写入平台管理费出账流水
			$params = array(

				'table_name' => 'money_change',

				'data' => array(

					'moneytype' => 7,

					'status' => 1,

					'targetuserid' => 1,

					'targetusernumber' => 1,

					'userid' => $dt['member']['uid'],

					'usernumber' => $dt['member']['usernumber'],

					'changetype' => $dt['changetype'],

					'recordtype' => 1,

					'money' => $dt['money'] * 0.02,

					'hasmoney' => $dt['money'] * 0.02,

					'createtime' => time(),

					'realname' => $dt['member']['realname'],

					'targetrealname' => '戎子'
				)
			);

			$platmoney_rongzi_add = $this -> model -> my_add($params);

	    	//写入税费增加流水
			$params = array(

				'table_name' => 'money_change',

				'data' => array(

					'moneytype' => 8,

					'status' => 1,

					'targetuserid' => $dt['member']['uid'],

					'targetusernumber' => $dt['member']['usernumber'],

					'userid' => 1,

					'usernumber' => 1,

					'changetype' => $dt['changetype'],

					'recordtype' => 0,

					'money' => $dt['money'] * 0.17,

					'hasmoney' => $dt['money'] * 0.17,

					'createtime' => time(),

					'realname' => '戎子',

					'targetrealname' => $dt['member']['realname']
				)
			);

			$taxmoney_add = $this -> model -> my_add($params);

	    	//写入税费出账流水
			$params = array(

				'table_name' => 'money_change',

				'data' => array(

					'moneytype' => 8,

					'status' => 1,

					'targetuserid' => 1,

					'targetusernumber' => 1,

					'userid' => $dt['member']['uid'],

					'usernumber' => $dt['member']['usernumber'],

					'changetype' => $dt['changetype'],

					'recordtype' => 1,

					'money' => $dt['money'] * 0.17,

					'hasmoney' => $dt['money'] * 0.17,

					'createtime' => time(),

					'realname' => $dt['member']['realname'],

					'targetrealname' => '戎子'
				)
			);

			$taxmoney_rongzi_add = $this -> model -> my_add($params);
	    }
	}
