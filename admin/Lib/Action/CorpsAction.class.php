<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update
 * @alter
 * @version 1.0.0
 *
 * 功能简介：
 * @author
 * @copyright
 * @time
 * @version 1.0.0
 */
	class CorpsAction extends CommonAction {

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

			$this -> model = D('Corps');
		}

	    /**
		 * 展示型首页
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
			$where = "";

			if($_GET['usernumber']){
				$where = "usernumber = ". htmlspecialchars($_GET['usernumber']);
			}

			//获取所有用户数据
			$params = array(

				'table_name' => 'member',

				'where' => $where,

				'order' => "uid desc"

			);

	    	$data = $this -> model -> order_select($params);

	    	$this -> assign('members', $data['result']);

			$this -> assign('page', $data['page']);

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
	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{


	    	}

	    	$this -> assign('result', $result);

	    	$this -> display();
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

	    }
	}
