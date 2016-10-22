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
	class BonusAction extends CommonAction {

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

			$this -> model = D('Bonus');

			if(ACTION_NAME != "password"){
				if($_SESSION['Rongzi']['twopwd']){

				}else{
					redirect(__APP__.'/Finances/password?callback='.urlencode($this -> get_url()), 0);
				}
			}
		}

		/**
		 * 奖金统计
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

	    		'table_name' => 'bonus_count',

	    		'where' => "touserid = {$_SESSION['Rongzi']['user']['uid']} AND tousernumber = '{$_SESSION['Rongzi']['user']['usernumber']}'",

	    		'order' => 'count_date desc'
	    	);

	    	$result = $this -> model -> order_select($params);

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

		/**
		 * 奖金明细
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
	    	$id = isset($_GET['id']) ? intval($_GET['id']) : $this -> _back('非法的参数');

	    	$params = array(

	    		'table_name' => 'bonus_count',

	    		'where' => "id = {$id}"
	    	);

	    	$count_find = $this -> model -> my_find($params);

	    	if (!$count_find)
	    	{
	    		$this -> _back('没有找到相关记录');
	    	}

	    	$that_day = strtotime(date('Y-m-d', $count_find['count_date']));

	    	$tomorrow = $that_day + (60 * 60 * 24);

	    	$params = array(

	    		'table_name' => 'bonus_detail',

	    		'where' => "touserid = {$count_find['touserid']} AND createdate >= {$that_day} AND createdate <= {$tomorrow}"
	    	);

	    	$result = $this -> model -> order_select($params);

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

		/**
		 * 奖金明细
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function down_info()
	    {
	    	$id = isset($_GET['id']) ? intval($_GET['id']) : $this -> _back('非法的参数');

	    	$params = array(

	    		'table_name' => 'bonus_count',

	    		'where' => "id = {$id}"
	    	);

	    	$count_find = $this -> model -> my_find($params);

	    	if (!$count_find)
	    	{
	    		$this -> _back('没有找到相关记录');
	    	}

	    	$that_day = strtotime(date('Y-m-d', $count_find['count_date']));

	    	$tomorrow = $that_day + (60 * 60 * 24);

	    	$params = array(

	    		'table_name' => 'bonus_detail',

	    		'where' => "touserid = {$count_find['touserid']} AND createdate >= {$that_day} AND createdate <= {$tomorrow}"
	    	);

	    	$xlsData = $this -> model -> easy_select($params);

			$xlsName  = "User";
		    $xlsCell  = array(
			    array('title','类型'),
			    array('jiangjinbi','奖金币'),
			    array('baodanbi','注册币'),
			    array('jihuobi','激活币'),
			    array('rongzidun','戎子盾'),
			    array('lovemoney','爱心基金'),
			    array('jianglijifen','福利积分'),
			    array('platmoney','平台管理费'),
			    array('taxmoney','税费'),
			    array('total','总奖金'),
			    array('real_total','实发奖金')
		    );

		    foreach ($xlsData as $key => $value) {
		    	# 处理标题数据
				if($value['moneytype'] == 1){
					$xlsData[$key]['title'] = "分红";
				}elseif($value['moneytype'] == 2){
					$xlsData[$key]['title'] = "管理补贴";
				}elseif($value['moneytype'] == 3){
					$xlsData[$key]['title'] = "互助补贴";
				}elseif($value['moneytype'] == 4){
					$xlsData[$key]['title'] = "拓展补贴";
				}elseif($value['moneytype'] == 5){
					$xlsData[$key]['title'] = "市场补贴";
				}elseif($value['moneytype'] == 6){
					$xlsData[$key]['title'] = "销售补贴";
				}elseif($value['moneytype'] == 7){
					$xlsData[$key]['title'] = "服务补贴";
				}elseif($value['moneytype'] == 8){
					$xlsData[$key]['title'] = "服务补贴";
				}
		    }
		    $this->exportExcel($xlsName,$xlsCell,$xlsData);
	    }

	    /**
		 * 财务流水
		 *
		 * 参数描述：
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function flow()
	    {
			$start = htmlspecialchars($_GET['start']);

			$stop = htmlspecialchars($_GET['stop']);

			$where = "userid = {$_SESSION['Rongzi']['user']['uid']}";

			if($start && $stop){
				$where = "createtime >= ".strtotime($start)." AND createtime <= ".(strtotime($stop)+24*60*60) . " AND (userid = {$_SESSION['Rongzi']['user']['uid']} )";
			}

	    	$params = array(

	    		'table_name' => 'money_change',

	    		'where' => $where,

	    		'order' => 'createtime desc'
	    	);
	    	$result = $this -> model -> order_select($params);

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }
	}
