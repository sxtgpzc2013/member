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
	class NewsAction extends CommonAction {

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

			$this -> model = D('News');
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

	    		'table_name' => 'news',

	    		'order' => "level asc",

	    		'where' => "is_del = 0 AND status = 0"
	    	);

	    	$result = $this -> model -> order_select($params);

	    	$this -> assign('result', $result);

			$this -> display();
	    }

		public function upload(){
			$logo = $this -> _upload_pic('products');
			echo json_encode($logo);
		}
	}
