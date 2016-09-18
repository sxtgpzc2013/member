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

class ProductsAction extends CommonAction {

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

		$this -> model = D('Products');
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

			var_dump($_POST);exit;
		}

		$this -> assign('result', $result);

		$this -> display();
	}

}
