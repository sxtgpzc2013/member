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
	class CartsAction extends CommonAction {

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

			$this -> model = D('Carts');
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
	    	$keys = array_keys($_SESSION['Rongzi']['cart']);

	    	$params = array(

	    		'table_name' => 'products',

	    		'where' => "is_del = 0 AND status = 0 AND id IN(".implode(',', $keys).")"
	    	);

	    	$result = $this -> model -> easy_select($params);

	    	foreach ($result as $k => $v)
	    	{
	    		if (isset($_SESSION['Rongzi']['cart'][$v['id']]['count']) && $_SESSION['Rongzi']['cart'][$v['id']]['count'] > 0)
	    		{
	    			$result[$k]['count'] = $_SESSION['Rongzi']['cart'][$v['id']]['count'];

	    			$result[$k]['total_jprice'] = $result[$k]['count'] * $v['jprice'];

	    			$result[$k]['total_rprice'] = $result[$k]['count'] * $v['rprice'];
	    		}
	    		else
	    		{
	    			unset($result[$k]);
	    		}
	    	}

	    	$this -> assign('result', $result);

			$this->display();
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
	    	$id = isset($_GET['id']) ? intval($_GET['id']) : $this -> _back('非法操作');

	    	unset($_SESSION['Rongzi']['cart'][$id]);

	    	redirect(__APP__.'/Carts/index', 0);
	    }

	    /**
		 * 更改数量
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function changecount()
	    {
	    	$id = isset($_GET['id']) ? intval($_GET['id']) : $this -> _back('非法操作');

	    	$type = isset($_GET['type']) ? intval($_GET['type']) : $this -> _back('非法操作');

	    	//查询这个商品
	    	$params = array(

	    		'table_name' => 'products',

	    		'where' => "id = {$id} AND is_del = 0"
	    	);

	    	$product_find = $this -> model -> my_find($params);

	    	if (!$product_find)
	    	{
	    		$this -> _back('无效商品');
	    	}

	    	if ($type == 0 && $_SESSION['Rongzi']['cart'][$id]['count'] >= 1) //减少
	    	{
	    		$_SESSION['Rongzi']['cart'][$id]['count'] = $_SESSION['Rongzi']['cart'][$id]['count'] - 1;
	    	} elseif ($type == 1 && $product_find['surplus'] >= ($_SESSION['Rongzi']['cart'][$id]['count'] + 1)) {
	    		$_SESSION['Rongzi']['cart'][$id]['count'] = $_SESSION['Rongzi']['cart'][$id]['count'] + 1;
	    	}

	    	redirect(__APP__.'/Carts/index', 0);
	    }
	}
