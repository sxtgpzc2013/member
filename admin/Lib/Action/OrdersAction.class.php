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
        $start = $_GET['start'] ? strtotime($_GET['start']) : strtotime(date('Y-m-d', time()));

        $stop = $_GET['stop'] ? strtotime($_GET['stop']) + 24 * 60 * 60 : time() + 24 * 60 * 60 ;

        $where = "1";

        if($start && $stop){

            $where = "created_at >= {$start} AND created_at <= {$stop}";

        }

        if($_GET['usernumber']){

            $where = $where ." AND usernumber = {$_GET['usernumber']}";

        }


    	$params = array(

    		'table_name' => 'orders',

    		'where' => $where ." AND is_del = 0 AND status = 1",

    		'order' => 'created_at desc'
    	);

    	$result = $this -> model -> order_select($params);

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
    public function info()
    {
    	$id = isset($_POST['id']) ? intval($_POST['id']) : intval($_GET['id']);

    	if (!$id)
    	{
    		$this -> _back('缺少必要参数');
    	}

    	$params = array(

    		'table_name' => 'orders',

    		'where' => "is_del = 0 AND id = {$id}"
    	);

    	$result['order'] = $this -> model -> my_find($params);

    	if (!$result['order'])
    	{
    		$this -> _back('未找到指定订单');
    	}

    	$params = array(

    		'table_name' => 'order_items',

    		'where' => "order_id = {$id}"
    	);

    	$result['items'] = $this -> model -> easy_select($params);

    	if (isset($_POST['form_key']) && $_POST['form_key'] == 'yes')
    	{
    		$data['sendCommpany'] = isset($_POST['sendCommpany']) && $_POST['sendCommpany'] != '' ? htmlspecialchars($_POST['sendCommpany']) : $this -> _back('请填写物流公司');

    		$data['logistics_number'] = isset($_POST['logistics_number']) && $_POST['logistics_number'] != '' ? htmlspecialchars($_POST['logistics_number']) : $this -> _back('请填写物流编号');

    		$data['status'] = 2;

    		$data['updated_at'] = time();

    		$params = array(

    			'table_name' => 'orders',

    			'where' => "id = {$id}",

    			'data' => $data
    		);

    		$order_save = $this -> model -> my_save($params);

    		if ($order_save)
    		{
    			redirect(__APP__.'/Orders/wait', 0);
    		}
    		else
    		{
    			$this -> _back('发货信息保存失败 请重试');
    		}
    	}

    	$this -> assign('result', $result);

    	$this -> display();
    }

    /**
	 * 已发货订单
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function sent()
    {
        $start = $_GET['start'] ? strtotime($_GET['start']) : strtotime(date('Y-m-d', time()));

        $stop = $_GET['stop'] ? strtotime($_GET['stop']) + 24 * 60 * 60 : time() + 24 * 60 * 60 ;

        $where = "1";

        if($start && $stop){

            $where = "created_at >= {$start} AND created_at <= {$stop}";

        }

        if($_GET['usernumber']){

            $where = $where ." AND usernumber = {$_GET['usernumber']}";

        }

    	$params = array(

    		'table_name' => 'orders',

    		'where' => $where ." AND is_del = 0 AND status = 2",

    		'order' => 'created_at desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }

    /**
	 * 已签收订单
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
        $start = $_GET['start'] ? strtotime($_GET['start']) : strtotime(date('Y-m-d', time()));

        $stop = $_GET['stop'] ? strtotime($_GET['stop']) + 24 * 60 * 60 : time() + 24 * 60 * 60 ;

        $where = "1";

        if($start && $stop){

            $where = "created_at >= {$start} AND created_at <= {$stop}";

        }

        if($_GET['usernumber']){

            $where = $where ." AND usernumber = {$_GET['usernumber']}";

        }

    	$params = array(

    		'table_name' => 'orders',

    		'where' => $where ." AND is_del = 0 AND status = 3",

    		'order' => 'created_at desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }
}
