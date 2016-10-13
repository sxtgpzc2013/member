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
	class SetAction extends CommonAction {

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

			$this -> model = D('Set');
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
	    public function bonus()
	    {

			//获取奖金相关设置数据
	    	$params = array(

	    		'table_name' => 'bonus_rule',

	    		'where' => "1"
    		);

    		$result = $this -> model -> easy_select($params);

    		$this -> assign('result', $result);

	    	$this -> display();
	    }


		//修改奖金相关设置
		public function editbonus()
	    {

			foreach ($_POST as $key => $value) {

				$data['value'] = $value;

				//获取奖金相关设置数据
		    	$params = array(

		    		'table_name' => 'bonus_rule',

		    		'where' => "id = ". $key,

					'data' => $data
	    		);

	    		$save = $this -> model -> my_save($params);
			}


    		redirect(__APP__."/Set/bonus", 0);
	    }

	}
