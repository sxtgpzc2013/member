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
    	$this -> display();
    }

    /**
	 * 待发货订单
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function wait()
    {
    	$params = array(

    		'table_name' => 'orders',

    		'where' => "is_del = 0 AND status = 1",

    		'order' => 'created_at desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }
}
