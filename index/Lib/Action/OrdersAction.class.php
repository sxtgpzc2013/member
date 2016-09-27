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

	    					'moneytype' => 6,

	    					'status' => 1,

	    					'userid' => $member_find['uid'],

	    					'usernumber' => $member_find['usernumber'],

	    					'changetype' => 13,

	    					'recordtype' => 0,

	    					'money' => $result['total_jprice'],

	    					'hasmoney' => $member_find['jiangjinbi'] - $result['total_jprice'],

	    					'createtime' => time(),

	    					'targetrealname' => '系统',

	    					'realname' => $member_find['realname']
	    				)
	    			);

	    			$jiangjinbi_money_change_add = $this -> model -> my_add($params);

	    			$params = array( //戎子盾

	    				'table_name' => 'money_change',

	    				'data' => array(

	    					'moneytype' => 3,

	    					'status' => 1,

	    					'userid' => $member_find['uid'],

	    					'usernumber' => $member_find['usernumber'],

	    					'changetype' => 13,

	    					'recordtype' => 0,

	    					'money' => $result['total_rprice'],

	    					'hasmoney' => $member_find['rongzidun'] - $result['total_rprice'],

	    					'createtime' => time(),

	    					'targetrealname' => '系统',

	    					'realname' => $member_find['realname']
	    				)
	    			);

	    			$rongzidun_money_change_add = $this -> model -> my_add($params);

	    			//获取消费补贴比例
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

	    			$mem_save = $this -> model -> my_save($params);

	    			$member_data['jiangjinbi'] = ($member_find['jiangjinbi'] - $result['total_jprice']) + ($result['total_price'] * ($bonus_rule_find['value']/100) * 0.8);

	    			//写入bonus_detail
	    			$member_jiangjinbi = $result['total_price'] * ($bonus_rule_find['value']/100);
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
	    			$params = array(

	    				'table_name' => 'money_change',

	    				'data' => array(

	    					'moneytype' => 6,

	    					'status' => 1,

	    					'targetuserid' => $member_find['uid'],

	    					'targetusernumber' => $member_find['usernumber'],

	    					'changetype' => 8,

	    					'recordtype' => 1,

	    					'money' => ($result['total_price'] * ($bonus_rule_find['value']/100) * 0.8),

	    					'hasmoney' => $member_data['jiangjinbi'],

	    					'createtime' => time(),

	    					'realname' => '系统',

	    					'targetrealname' => $member_find['realname']
	    				)
	    			);

	    			$jiangjinbi_8_add = $this -> model -> my_add($params);

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

	    			$upid[1] = count($upids) >= 1 ? $upids[count($upids)-1] : 0;

	    			$upid[2] = count($upids) >= 2 ? $upids[count($upids)-2] : 0;

	    			$upid[3] = count($upids) >= 3 ? $upids[count($upids)-3] : 0;

	    			//更新
	    			foreach ($upid as $k => $v)
	    			{
		    			if ($v != 0 && $v != 1)
		    			{
		    				$params = array(

		    					'table_name' => 'member',

		    					'where' => "uid = {$v}"
		    				);

		    				$up_find = $this -> model -> my_find($params);

			    			$params = array(

			    				'table_name' => 'member',

			    				'where' => "uid = {$v}",

			    				'data' => array(

			    					'jiangjinbi' => $up_find['jiangjinbi'] + ($result['total_price'] * ($bonus_rule_find['value']/100) * $bonus_rules_re[$k] * 0.8),

			    					'update_time' => time()
			    				)
			    			);

			    			$up_save = $this -> model -> my_save($params);

			    			//计入流水
			    			$params = array(

			    				'table_name' => 'money_change',

			    				'data' => array(

			    					'moneytype' => 6,

			    					'status' => $up_save,

			    					'targetuserid' => $up_find['uid'],

			    					'targetusernumber' => $up_find['usernumber'],

			    					'changetype' => 9,

			    					'recordtype' => 1,

			    					'money' => $result['total_price'] * ($bonus_rule_find['value']/100) * $bonus_rules_re[$k] * 0.8,

			    					'hasmoney' => $up_find['jiangjinbi'] + ($result['total_price'] * ($bonus_rule_find['value']/100) * $bonus_rules_re[$k] * 0.8),

			    					'createtime' => time(),

			    					'realname' => '系统',

			    					'targetrealname' => $up_find['realname']
			    				)
			    			);

			    			$up_money_change_add = $this -> model -> my_add($params);

			    			//写入bonus_detail
			    			$up_jiangjinbi = $result['total_price'] * ($bonus_rule_find['value']/100) * $bonus_rules_re[$k];
			    			$params = array(

			    				'table_name' => 'bonus_detail',

			    				'data' => array(

			    					'touserid' => $up_find['uid'],

			    					'tousernumber' => $up_find['usernumber'],

			    					'torealname' => $up_find['realname'],

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
	}
