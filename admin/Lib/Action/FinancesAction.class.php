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

class FinancesAction extends CommonAction {

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

		$this -> model = D('Finances');
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
	 * 补贴统计
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function bonus_list()
    {

        $start = $_GET['start'] ? strtotime($_GET['start']) : "";

        $stop = $_GET['stop'] ? strtotime($_GET['stop']) + 24 * 60 * 60 : "" ;

        $where = "1";

        if($start && $stop){

            $where = "count_date >= {$start} AND count_date <= {$stop}";

        }

        if($_GET['usernumber']){

            $where = $where ." AND tousernumber = {$_GET['usernumber']}";

        }

    	$params = array(

    		'table_name' => 'bonus_count',

    		'where' => $where,

    		'order' => 'count_date desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }

    /**
     * 补贴统计
     *
     * 参数描述：
     *
     *
     *
     * 返回值：
     *
     */
    public function bonus_list_download()
    {

        $start = $_GET['start'] ? strtotime($_GET['start']) : time();

        $stop = $_GET['stop'] ? strtotime($_GET['stop']) + 24 * 60 * 60 : time() ;

        $where = "1";

        if($start && $stop){

            $where = "count_date >= {$start} AND count_date <= {$stop}";

        }

        if($_GET['usernumber']){

            $where = $where ." AND tousernumber = {$_GET['usernumber']}";

        }

        $params = array(

            'table_name' => 'bonus_count',

            'where' => $where,

            'order' => 'count_date desc'
        );

        $xlsData = $this -> model -> easy_select($params);

            $xlsName  = "补贴统计";

            $xlsCell  = array(
                array('count_date','日期'),
                array('tousernumber','销费商编号'),
                array('torealname','销费商姓名'),
                array('bonus1','分红'),
                array('bonus2','管理补贴'),
                array('bonus3','互助补贴'),
                array('bonus4','拓展补贴'),
                array('bonus5','市场补贴'),
                array('bonus6','消费补贴'),
                array('bonus7','服务补贴'),
                array('bonus8','二次消费补贴'),
                array('total','应发金额累计'),
                array('real_total','实发金额累计')
            );

            foreach ($xlsData as $key => $value) {
                $xlsData[$key]['count_date'] = date('Y-m-d', $value['count_date']);
            }
            $this->exportExcel($xlsName,$xlsCell,$xlsData);
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
    public function bonus_info()
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
	 * 财务流水
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function finance_flow()
    {
        //默认导出今天数据
        $start = $_GET['start'] ? strtotime($_GET['start']) : strtotime(date('Y-m-d', time()));

        $stop = $_GET['stop'] ? strtotime($_GET['stop']) + 24 * 60 * 60 : time() + 24 * 60 * 60 ;

        $where = "1";

        if($start){

            $where .= " AND createtime >= {$start}";

        }

        if($stop){

            $where .= " AND createtime <= {$stop}";

        }

        if($_GET['usernumber']){

            $where = $where ." AND (targetusernumber = '{$_GET['usernumber']}' OR usernumber = '{$_GET['usernumber']}')";

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
    public function finance_flow_download()
    {

        //默认导出今天数据

        //导出筛选数据
        $params = array(

            'table_name' => 'money_change',

            'where' => "1",

            'order' => 'createtime desc'
        );

        //相关销费商   目标销费商   变更金额    变更币种    类型  变更状态    时间
        $xlsData = $this -> model -> easy_select($params);

        $xlsName  = "财务流水";

        $xlsCell  = array(
            array('realname','相关销费商'),
            array('targetrealname','目标销费商'),
            array('money','变更金额'),
            array('moneytype','变更币种'),
            array('changetype','类型'),
            array('recordtype','变更状态'),
            array('createtime','时间')
        );

        foreach ($xlsData as $key => $value) {

            if ($item['moneytype'] == 1){
                $xlsData[$key]['moneytype'] = "奖金币";
            } elseif ($value['moneytype'] == 2){
                $xlsData[$key]['moneytype'] = "注册币";
            } elseif ($value['moneytype'] == 3){
                $xlsData[$key]['moneytype'] = "戎子盾";
            } elseif ($value['moneytype'] == 4){
                $xlsData[$key]['moneytype'] = "激活币";
            } elseif ($value['moneytype'] == 5){
                $xlsData[$key]['moneytype'] = "福利积分";
            } elseif ($value['moneytype'] == 6){
                $xlsData[$key]['moneytype'] = "爱心基金";
            } elseif ($value['moneytype'] == 7){
                $xlsData[$key]['moneytype'] = "平台管理费";
            } elseif ($value['moneytype'] == 8){
                $xlsData[$key]['moneytype'] = "税费";
            }

            // $xlsData[$key]['title'] = $xlsData[$key]['title']."级销费商";

            // if ($value['status'] == 0) {
            //     $xlsData[$key]['status'] = "未激活";
            // } elseif ($value['status'] == 1) {
            //     $xlsData[$key]['status'] = "已激活";
            // } elseif ($value['status'] == -1) {
            //     $xlsData[$key]['status'] = "已删除";
            // } elseif ($value['status'] == -2) {
            //     $xlsData[$key]['status'] = "已冻结";
            // } else {
            //     $xlsData[$key]['status'] = "未知";
            // }


        }

        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }

    /**
	 * 转账明细
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function transfer_list()
    {
        //默认导出今天数据
        $start = $_GET['start'] ? strtotime($_GET['start']) : strtotime(date('Y-m-d', time()));

        $stop = $_GET['stop'] ? strtotime($_GET['stop']) + 24 * 60 * 60 : time() ;

        $where = "1";

        if($start){

            $where .= " AND createtime >= {$start}";

        }

        if($stop){

            $where .= " AND createtime <= {$stop}";

        }

        if($_GET['usernumber']){

            $where = $where ." AND (usernumber = '{$_GET['usernumber']}' OR targetusernumber = '{$_GET['usernumber']}')";

        }

    	$params = array(

    		'table_name' => 'transfer',

    		'where' => $where,

    		'order' => 'createtime desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }

    /**
	 * 公司充值
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function company_recharge()
    {
    	if (isset($_POST['form_key']) && htmlspecialchars($_POST['form_key']) == 'yes')
    	{
    		$usernumber = isset($_POST['usernumber']) ? intval($_POST['usernumber']) : $this -> _back('非法的销费商');

    		$money = isset($_POST['money']) ? floatval($_POST['money']) : $this -> _back('非法的金额');

    		$type = isset($_POST['type']) ? intval($_POST['type']) : $this -> _back('非法的类型');

            //查询目标用户
            $params = array(

                'table_name' => 'member',

                'where' => "usernumber = {$usernumber}"
            );

            $member_find = $this -> model -> my_find($params);

    		//查戎子用户
    		$params = array(

    			'table_name' => 'member',

    			'where' => "usernumber = 1"
    		);

    		$rongzi = $this -> model -> my_find($params);

    		if (!$member_find)
    		{
    			$this -> _back('无效的销费商');
    		}

    		//查询当前登陆者
    		$params = array(

    			'table_name' => 'member',

    			'where' => "uid = {$_SESSION['Rongzi']['admin']['id']}"
    		);

    		$user_find = $this -> model -> my_find($params);

    		if (!$user_find)
    		{
    			$this -> _back('请先登录');
    		}

            if ($user_find['uid'] == $member_find['uid'])
            {
                $this -> _back('请勿给自己充值');
            }

            $key = true;

    		switch ($type)
    		{
    			case 1 : //奖金币

    				$data['jiangjinbi'] = $member_find['jiangjinbi'] + $money;

    				$balance = $data['jiangjinbi'];

    				$moneytype = 1;

    				break;

    			case 2 : //注册币

                    if (($rongzi['baodanbi'] - $money) >= 0)
                    {
        				$data['baodanbi'] = $member_find['baodanbi'] + $money;

                        $rongzi_data['baodanbi'] = $rongzi['baodanbi'] - $money;

        				$balance = $data['baodanbi'];

                        $rongzi_balance = $rongzi_data['baodanbi'];

        				$moneytype = 2;

        				break;
                    } else {
                        $key = false;

                        break;
                    }

    			case 3 : //激活币

                    if (($rongzi['jihuobi'] - $money) >= 0)
                    {
        				$data['jihuobi'] = $member_find['jihuobi'] + $money;

                        $rongzi_data['jihuobi'] = $rongzi['jihuobi'] - $money;

        				$balance = $data['jihuobi'];

                        $rongzi_balance = $rongzi_data['jihuobi'];

        				$moneytype = 4;

        				break;
                    } else {
                        $key = false;

                        break;
                    }

    			case 4 : //戎子盾

    				$data['rongzidun'] = $member_find['rongzidun'] + $money;

    				$balance = $data['rongzidun'];

    				$moneytype = 3;

    				break;

    			case 5 : //福利积分

    				$data['jianglijifen'] = $member_find['jianglijifen'] + $money;

    				$balance = $data['jianglijifen'];

    				$moneytype = 5;

    				break;
    		}

            if ($key == false)
            {
                $this -> _back('戎子账户余额不足');return;
            }

    		$data['update_time'] = time();

            $rongzi_data['update_time'] = time();

    		$params = array(

    			'table_name' => 'member',

    			'where' => "usernumber = {$usernumber}",

    			'data' => $data
    		);

    		$member_save = $this -> model -> my_save($params);

    		$money_change_data['moneytype'] = $moneytype;

			$money_change_data['status'] = $member_save ? 1 : 0;

			$money_change_data['targetuserid'] = $member_find['uid'];

			$money_change_data['targetusernumber'] = $member_find['usernumber'];

			$money_change_data['targetrealname'] = $member_find['realname'];

			$money_change_data['userid'] = $user_find['uid'];

			$money_change_data['usernumber'] = $user_find['usernumber'];

			$money_change_data['realname'] = $user_find['realname'];

			$money_change_data['changetype'] = 1;

			$money_change_data['recordtype'] = 1;

			$money_change_data['money'] = $money;

			$money_change_data['hasmoney'] = $balance;

			$money_change_data['createtime'] = time();

			//存入流水
			$params = array(

				'table_name' => 'money_change',

				'data' => $money_change_data
			);

			$money_change_add = $this -> model -> my_add($params);

    		if ($member_save)
    		{
                //扣除戎子账户
                $params = array(

                    'table_name' => 'member',

                    'where' => "usernumber = 1",

                    'data' => $rongzi_data
                );

                $rongzi_save = $this -> model -> my_save($params);

                //写入流水
                $money_change_data = [];

                $money_change_data['moneytype'] = $moneytype;

                $money_change_data['status'] = $rongzi_save ? 1 : 0;

                $money_change_data['targetuserid'] = $user_find['uid'];

                $money_change_data['targetusernumber'] = $user_find['usernumber'];

                $money_change_data['targetrealname'] = $user_find['realname'];

                $money_change_data['userid'] = $member_find['uid'];

                $money_change_data['usernumber'] = $member_find['usernumber'];

                $money_change_data['realname'] = $member_find['realname'];

                $money_change_data['changetype'] = 1;

                $money_change_data['recordtype'] = 0;

                $money_change_data['money'] = $money;

                $money_change_data['hasmoney'] = $rongzi_balance;

                $money_change_data['createtime'] = time();

                //存入流水
                $params = array(

                    'table_name' => 'money_change',

                    'data' => $money_change_data
                );

                $money_change_add = $this -> model -> my_add($params);

    			redirect(__APP__.'/Finances/recharge_list', 0);
    		}
    		else
    		{
    			$this -> _back('充值失败 请重试');
    		}
    	}

    	$this -> display();
    }

    /**
	 * 激活扣币
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function company_deduct_money()
    {
    	if (isset($_POST['form_key']) && htmlspecialchars($_POST['form_key']) == 'yes')
    	{
    		$usernumber = isset($_POST['usernumber']) ? intval($_POST['usernumber']) : $this -> _back('非法的销费商');

    		$money = isset($_POST['money']) ? floatval($_POST['money']) : $this -> _back('非法的金额');

    		$type = isset($_POST['type']) ? intval($_POST['type']) : $this -> _back('非法的类型');

    		//查询目标用户
    		$params = array(

    			'table_name' => 'member',

    			'where' => "usernumber = {$usernumber}"
    		);

    		$member_find = $this -> model -> my_find($params);

    		if (!$member_find)
    		{
    			$this -> _back('无效的销费商');
    		}

    		//查询当前登陆者
    		$params = array(

    			'table_name' => 'member',

    			'where' => "uid = {$_SESSION['Rongzi']['admin']['id']}"
    		);

    		$user_find = $this -> model -> my_find($params);

    		if (!$user_find)
    		{
    			$this -> _back('请先登录');
    		}

    		switch ($type)
    		{
    			case 1 : //奖金币

    				$data['jiangjinbi'] = $member_find['jiangjinbi'] - $money;

    				$balance = $data['jiangjinbi'];

    				$moneytype = 1;

    				break;

    			case 2 : //注册币

    				$data['baodanbi'] = $member_find['baodanbi'] - $money;

    				$balance = $data['baodanbi'];

    				$moneytype = 2;

    				break;

    			case 3 : //激活币

    				$data['jihuobi'] = $member_find['jihuobi'] - $money;

    				$balance = $data['jihuobi'];

    				$moneytype = 4;

    				break;

    			case 4 : //戎子盾

    				$data['rongzidun'] = $member_find['rongzidun'] - $money;

    				$balance = $data['rongzidun'];

    				$moneytype = 3;

    				break;

    			case 5 : //福利积分

    				$data['jianglijifen'] = $member_find['jianglijifen'] - $money;

    				$balance = $data['jianglijifen'];

    				$moneytype = 5;

    				break;
    		}

    		$data['update_time'] = time();

    		$params = array(

    			'table_name' => 'member',

    			'where' => "usernumber = {$usernumber}",

    			'data' => $data
    		);

    		$member_save = $this -> model -> my_save($params);

    		$money_change_data['moneytype'] = $moneytype;

			$money_change_data['status'] = $member_save ? 1 : 0;

			$money_change_data['targetuserid'] = $member_find['uid'];

			$money_change_data['targetusernumber'] = $member_find['usernumber'];

			$money_change_data['targetrealname'] = $member_find['realname'];

			$money_change_data['userid'] = $user_find['uid'];

			$money_change_data['usernumber'] = $user_find['usernumber'];

			$money_change_data['realname'] = $user_find['realname'];

			$money_change_data['changetype'] = 2;

			$money_change_data['recordtype'] = 0;

			$money_change_data['money'] = $money;

			$money_change_data['hasmoney'] = $balance;

			$money_change_data['createtime'] = time();

			//存入流水
			$params = array(

				'table_name' => 'money_change',

				'data' => $money_change_data
			);

			$money_change_add = $this -> model -> my_add($params);

    		if ($member_save)
    		{
    			redirect(__APP__.'/Finances/finance_flow', 0);
    		}
    		else
    		{
    			$this -> _back('扣币失败 请重试');
    		}
    	}

    	$this -> display();
    }

    /**
	 * 充值记录
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function recharge_list()
    {
        //默认导出今天数据
        $start = $_GET['start'] ? strtotime($_GET['start']) : strtotime(date('Y-m-d',time()));

        $stop = $_GET['stop'] ? strtotime($_GET['stop']) + 24 * 60 * 60 : time() ;

        $where = "1";

        if($start){

            $where .= " AND createtime >= {$start}";

        }

        if($stop){

            $where .= " AND createtime <= {$stop}";

        }

        if($_GET['usernumber']){

            $where = $where ." AND (targetusernumber = '{$_GET['usernumber']}' OR usernumber = '{$_GET['usernumber']}')";

        }

    	$params = array(

    		'table_name' => 'money_change',

    		'where' => $where." AND changetype = 1",

    		'order' => 'createtime desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }

    /**
	 * 提现申请
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function cash()
    {
        $start = $_GET['start'] ? strtotime($_GET['start']) : strtotime(date('Y-m-d', time()));

        $stop = $_GET['stop'] ? strtotime($_GET['stop']) + 24 * 60 * 60 : time() ;

        $where = "1";

        if($start){

            $where .= " AND createtime >= {$start}";

        }

        if($stop){

            $where .= " AND createtime <= {$stop}";

        }

        if($_GET['usernumber']){

            $where = $where ." AND usernumber = '{$_GET['usernumber']}'";

        }

    	$params = array(

    		'table_name' => 'withdrawal',

    		'where' => $where ." AND status = 1",

    		'order' => 'createtime desc'
    	);

    	$result = $this -> model -> order_select($params);

    	$this -> assign('result', $result);

    	$this -> display();
    }

    /**
	 * 提现处理
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    public function cash_action()
    {
    	$id = isset($_GET['id']) ? intval($_GET['id']) : $this -> _back('非法的指向');

    	$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : $this -> _back('非法的类型');

    	//查询这个提现申请
    	$params = array(

    		'table_name' => 'withdrawal',

    		'where' => "id = {$id} AND status = 1"
    	);

    	$cash_find = $this -> model -> my_find($params);

    	if (!$cash_find)
    	{
    		$this -> _back('没有找到该提现记录');
    	}

    	//查询这个人
    	$params = array(

    		'table_name' => 'member',

    		'where' => "usernumber = {$cash_find['usernumber']}"
    	);

    	$member_find = $this -> model -> my_find($params);

    	if (!$member_find)
    	{
    		$this -> _back('查无此人');
    	}

    	if ($type == 'agreen')
    	{
    		//同意
    		$data['status'] = 0;
    	}
    	elseif ($type == 'refuse')
    	{
    		//拒绝
    		$data['status'] = 2;
    	}

    	$data['arrival_amount'] = $cash_find['money'] - $cash_find['fee'];

    	$data['handtime'] = time();

    	$params = array(

    		'table_name' => 'withdrawal',

    		'where' => "id = {$id}",

    		'data' => $data
    	);

    	$cash_save = $this -> model -> my_save($params);

    	if ($cash_save)
    	{
    		if ($type == 'refuse')
    		{
    			if ($cash_find['moneytype'] == 0)
    			{
	    			//退回原账户
	    			$member_data['jiangjinbi'] = $member_find['jiangjinbi'] + $cash_find['money'];
	    		}

	    		$member_data['update_time'] = time();

	    		$params = array(

	    			'table_name' => 'member',

	    			'where' => "uid = {$member_find['uid']}",

	    			'data' => $member_data
	    		);

	    		$member_save = $this -> model -> my_save($params);

	    		$money_change_data['status'] = $member_save ? 1 : 0;

				$money_change_data['recordtype'] = 1;

				$money_change_data['hasmoney'] = $cash_find['moneytype'] == 0 && $member_save ? $member_find['jiangjinbi'] + $cash_find['money'] : $member_find['jiangjinbi'];
    		}
    		elseif ($type == 'agreen')
    		{
				$money_change_data['status'] = 1;

				$money_change_data['recordtype'] = 0;

				$money_change_data['hasmoney'] = $cash_find['moneytype'] == 0 ? $member_find['jiangjinbi'] : 0;
    		}

    		$money_change_data['moneytype'] = $cash_find['moneytype'] == 0 ? 1 : 0;

			$money_change_data['targetuserid'] = $member_find['uid'];

			$money_change_data['targetusernumber'] = $member_find['usernumber'];

			$money_change_data['targetrealname'] = $member_find['realname'];

			$money_change_data['realname'] = '系统';

			$money_change_data['changetype'] = 12;

			$money_change_data['money'] = $cash_find['money'];

			$money_change_data['createtime'] = time();

			//存入流水
			$params = array(

				'table_name' => 'money_change',

				'data' => $money_change_data
			);

			$money_change_add = $this -> model -> my_add($params);

    		redirect(__APP__.'/Finances/cash');
    	}
    	else
    	{
    		$this -> _back('操作失败 请重试');
    	}

    	$this -> display();
    }

    /**
     * ajax提现处理
     *
     * 参数描述：
     *
     *
     *
     * 返回值：
     *
     */
    public function ajax_cash_action()
    {
        $id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : die('非法的指向');

        $type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : $this -> _back('非法的类型');

        //查询这个提现申请
        $params = array(

            'table_name' => 'withdrawal',

            'where' => "id IN ('{$id}') AND status = 1"
        );

        $cashes = $this -> model -> easy_select($params);

        if (!$cashes)
        {
            die('没有找到该提现记录');
        }

        foreach ($cashes as $cash_find)
        {
            $data = [];

            //查询这个人
            $params = array(

                'table_name' => 'member',

                'where' => "usernumber = {$cash_find['usernumber']}"
            );

            $member_find = $this -> model -> my_find($params);

            if (!$member_find)
            {
                die('查无此人');
            }

            if ($type == 'agreen')
            {
                //同意
                $data['status'] = 0;
            }
            elseif ($type == 'refuse')
            {
                //拒绝
                $data['status'] = 2;
            }

            $data['arrival_amount'] = $cash_find['money'] - $cash_find['fee'];

            $data['handtime'] = time();

            $params = array(

                'table_name' => 'withdrawal',

                'where' => "id = {$cash_find['id']}",

                'data' => $data
            );

            $cash_save = $this -> model -> my_save($params);

            if ($cash_save)
            {
                if ($type == 'refuse')
                {
                    $member_data = [];

                    if ($cash_find['moneytype'] == 0)
                    {
                        //退回原账户
                        $member_data['jiangjinbi'] = $member_find['jiangjinbi'] + $cash_find['money'];
                    }

                    $member_data['update_time'] = time();

                    $params = array(

                        'table_name' => 'member',

                        'where' => "uid = {$member_find['uid']}",

                        'data' => $member_data
                    );

                    $member_save = $this -> model -> my_save($params);

                    $money_change_data['status'] = $member_save ? 1 : 0;

                    $money_change_data['recordtype'] = 1;

                    $money_change_data['hasmoney'] = $cash_find['moneytype'] == 0 && $member_save ? $member_find['jiangjinbi'] + $cash_find['money'] : $member_find['jiangjinbi'];
                }
                elseif ($type == 'agreen')
                {
                    $money_change_data['status'] = 1;

                    $money_change_data['recordtype'] = 0;

                    $money_change_data['hasmoney'] = $cash_find['moneytype'] == 0 ? $member_find['jiangjinbi'] : 0;
                }

                $money_change_data['moneytype'] = $cash_find['moneytype'] == 0 ? 1 : 0;

                $money_change_data['targetuserid'] = $member_find['uid'];

                $money_change_data['targetusernumber'] = $member_find['usernumber'];

                $money_change_data['targetrealname'] = $member_find['realname'];

                $money_change_data['realname'] = '系统';

                $money_change_data['changetype'] = 12;

                $money_change_data['money'] = $cash_find['money'];

                $money_change_data['createtime'] = time();

                //存入流水
                $params = array(

                    'table_name' => 'money_change',

                    'data' => $money_change_data
                );

                $money_change_add = $this -> model -> my_add($params);

                die(1);
            }
            else
            {
                die('操作失败 请重试');
            }
        }
    }

    /**
     * 提现记录
     *
     * 参数描述：
     *
     *
     *
     * 返回值：
     *
     */
    public function cash_list()
    {
        //默认导出今天数据
        $start = $_GET['start'] ? strtotime($_GET['start']) : strtotime(date('Y-m-d',time()));

        $stop = $_GET['stop'] ? strtotime($_GET['stop']) + 24 * 60 * 60 : time() ;

        $where = "1";

        if($start){

            $where .= " AND createtime >= {$start}";

        }

        if($stop){

            $where .= " AND createtime <= {$stop}";

        }

        if($_GET['usernumber']){

            $where = $where ." AND usernumber = '{$_GET['usernumber']}'";

        }

        $params = array(

            'table_name' => 'withdrawal',

            'where' => $where." AND (status = 0 OR status = 2)",

            'order' => 'createtime desc'
        );

        $result = $this -> model -> order_select($params);

        $this -> assign('result', $result);

        $this -> display();
    }

    /**
	 * 业绩统计
	 *
	 * 参数描述：
	 *
	 *
	 *
	 * 返回值：
	 *
	 */
    // public function performance()
    // {
    // 	$params = array(

    // 		'table_name' => 'withdrawal',

    // 		'where' => "status = 0 OR status = 2",

    // 		'order' => 'createtime desc'
    // 	);

    // 	$result = $this -> model -> order_select($params);

    // 	$this -> assign('result', $result);

    // 	$this -> display();
    // }
}
