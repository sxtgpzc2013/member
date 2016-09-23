<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-09-15
 * @alter
 * @version 1.0.0
 *
 * 功能简介：商户后台订单管理控制器类
 * @author
 * @copyright
 * @time 2014-09-15
 * @version 1.0.0
 */
	class TestAction extends CommonAction {

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

	    	//查询这个商户所有的订单
	    	$params = array(

	    		'table_name' => 'orders',

	    		'where' => "is_del = 0 AND corp_id = ".CORP_ID,

	    		'order' => 'created_at desc'
	    	);

	    	$orders = $this -> model -> order_select($params);

	    	$result['orders'] = $orders['result'];

	    	$result['page'] = $orders['page'];

	    	//循环处理数据细节
	    	foreach ($result['orders'] as $k => $v)
	    	{
	    		//标注类型
	    		$result['orders'][$k]['type_name'] = $this -> _get_type_name($v['type_str']);

	    		//标注来源
	    		$result['orders'][$k]['from_name'] = $this -> _get_from_name($v['from_str']);

	    		//标注状态
	    		$result['orders'][$k]['status_name'] = $this -> _get_status_name($v['status']);

	    		//标注支付方式
	    		$result['orders'][$k]['pay_type_name'] = $this -> _get_pay_type_name($v['pay_type']);

	    		//标注审核状态
	    		$result['orders'][$k]['review_name'] = $this -> _get_review_name($v['review']);
	    	}

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

	    /**
		 * 订单详情
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function show()
	    {
	    	//订单ID
	    	$id = intval($_GET['id']) ? intval($_GET['id']) : $this -> _back('非法操作！');

	    	//查询这个订单
	    	$params = array(

	    		'table_name' => 'orders',

	    		'where' => "id = {$id} AND is_del = 0 AND corp_id = ".CORP_ID
	    	);

	    	$result['order_find'] = $this -> model -> my_find($params);

	    	if (!$result['order_find']) { $this -> _back('没有找到你要的数据，请稍后重试。'); }

	    	//订单类型
	    	$result['order_find']['type_name'] = $this -> _get_type_name($result['order_find']['type_str']);

	    	//订单来源
	    	$result['order_find']['from_name'] = $this -> _get_from_name($result['order_find']['from_str']);

	    	//如果是订餐，查询桌台和桌号
	    	if ($result['order_find']['type_str'] == 'CookBook')
	    	{
	    		//桌台
	    		$result['order_find']['rank_tag_find'] = $this -> _get_rank_tag($result['order_find']['rank_tag_id']);

	    		//桌号
	    		$result['order_find']['table_find'] = $this -> _get_table($result['order_find']['table_id']);
	    	}

	    	//客户
	    	$result['order_find']['member_find'] = $this -> _get_member($result['order_find']['member_id']);

	    	//查询子项
	    	$params = array(

	    		'table_name' => 'order_items',

	    		'where' => "is_del = 0 AND order_id = {$result['order_find']['id']}"
	    	);

	    	$result['order_items'] = $this -> model -> easy_select($params);

	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{
	    		$data['status'] = intval($_POST['status']);

	    		$data['pay_type'] = intval($_POST['pay_type']);

	    		$data['review'] = intval($_POST['review']);

	    		$data['updated_at'] = time();

	    		//保存
	    		$params = array(

	    			'table_name' => 'orders',

	    			'where' => "id = {$result['order_find']['id']}",

	    			'data' => $data
	    		);

	    		$order_save = $this -> model -> my_save($params);

	    		if ($order_save == 1)
	    		{
	    			redirect(__APP__.'/Orders/show/id/'.$result['order_find']['id'], 0);
	    		}
	    		else
	    		{
	    			$this -> _back('保存失败，请重试。');
	    		}
	    	}

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

	    /**
		 * 获取客户
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    private function _get_member($member_id)
	    {
	    	$params = array(

	    		'table_name' => 'members',

	    		'where' => "id = {$member_id}"
	    	);

	    	$member_find = $this -> model -> my_find($params);

	    	return $member_find;
	    }

	    /**
		 * 获取桌台
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    private function _get_rank_tag($rank_tag_id)
	    {
	    	//查询这个桌台
	    	$params = array(

	    		'table_name' => 'rank_tags',

	    		'where' => "id = {$rank_tag_id}"
	    	);

	    	$rank_tag_find = $this -> model -> my_find($params);

	    	return $rank_tag_find;
	    }

	    /**
		 * 获取桌号
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    private function _get_table($table_id)
	    {
	    	$params = array(

	    		'table_name' => 'tables',

	    		'where' => "id = {$table_id}"
	    	);

	    	$table_find = $this -> model -> my_find($params);

	    	return $table_find;
	    }

	    /**
		 * 获取审核状态
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    private function _get_review_name($review)
	    {
	    	$review_config = array(

	    		0 => '已审核',

	    		1 => '未审核'
	    	);

	    	return $review_config[$review];
	    }

	    /**
		 * 获取支付方式
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    private function _get_pay_type_name($pay_type)
	    {
	    	$pay_type_config = array(

	    		0 => '货到付款',

	    		1 => '现金支付'
	    	);

	    	return $pay_type_config[$pay_type];
	    }

	    /**
		 * 获取状态名称
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    private function _get_status_name($status)
	    {
	    	$status_config = array(

	    		0 => '未付款',

	    		1 => '已付款',

	    		2 => '已发货',

	    		3 => '已完成',

	    		4 => '换货处理中',

	    		5 => '退货处理中',

	    		6 => '等待用户邮寄'
	    	);

	    	return $status_config[$status];
	    }

	    /**
		 * 获取来源名称
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    private function _get_from_name($from_name)
	    {
	    	switch ($from_name)
	    	{
	    		case 'ShowPad':

	    			$from_name = '自助PAD';

	    			break;

	    		case 'WeiXin':

	    			$from_name = '微信';

	    			break;

	    		default :

	    			$from_name = '未知';

	    			break;
	    	}

	    	return $from_name;
	    }

	    /**
		 * 获取类型名称
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    private function _get_type_name($type_str)
	    {
	    	switch ($type_str)
	    	{
	    		case 'CookBook':

	    			$type_name = '订餐';

	    			break;

	    		default :

	    			$type_name = '未知';

	    			break;
	    	}

	    	return $type_name;
	    }


	}
