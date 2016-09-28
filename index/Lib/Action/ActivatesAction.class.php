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
class ActivatesAction extends CommonAction {

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

		$this -> model = D('Activates');
	}

	/**
	 * 消费商激活列表
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
		//报单中心ID
		$billcenterid = $_SESSION['Rongzi']['user']['uid'];
		//报单中心编号
		$billcenternumber = $_SESSION['Rongzi']['user']['usernumber'];

		//查询用户资料数据
		$params = array(

			'table_name' => 'member',

			'where' => "billcenterid = {$billcenterid} AND billcenternumber = {$billcenternumber} AND status = 0"

		);

    	$data = $this -> model -> order_select($params);

		foreach ($data['result'] as $key => $value) {
			if($value['userrank'] == 1){
				$data['result'][$key]['money'] = 1980;
			}

			if($value['userrank'] == 2){
				$data['result'][$key]['money'] = 10000;
			}


			if($value['userrank'] == 3){
				$data['result'][$key]['money'] = 30000;
			}

			if($value['userrank'] == 4){
				$data['result'][$key]['money'] = 50000;
			}
		}

    	$result['members'] = $data['result'];

		$result['page'] = $data['page'];

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
		$uid = intval($_GET['uid']);

		//数据包
		$data['status'] = -2;

		$data['update_time'] = time();

		//写入数据库
		$params = array(

		   'table_name' => 'member',

		   'where' => "uid = {$uid}",

		   'data' => $data
		);

		$my_save = $this -> model -> my_save($params);

		$params = array(

		   'table_name' => 'member',

		   'where' => "uid = {$uid}"
		);

		$member = $this -> model -> my_find($params);

		switch ($member['zone']) {
			case '1':
				$updatedata['left_zone'] = 0;
				break;
			case '2':
				$updatedata['middle_zone'] = 0;
				break;
			case '3':
				$updatedata['right_zone'] = 0;
				break;
		}

		//修改父类相关区间是否被占
		$params = array(

		  'table_name' => 'member',

		  'where' => "uid = {$member['parentid']}",

		  'data' => $updatedata
		);

		$my_save = $this -> model -> my_save($params);


		if ($my_save == 1)
		{
		   redirect(__APP__.'/Activates/index', 0);
		}
		else
		{
		   $this -> _back('删除失败，请重试。');
		}
   }

	/**
	* 激活处理
	*
	* 参数描述：
	*
	*
	*
	* 返回值：
	*
	*/
	public function activate()
	{
		$uid = intval($_GET['uid']);

		//查询该用户是否符合激活条件
		$params = array(

			'table_name' => 'member',

			'where' => "uid = {$uid} AND status = 0"

		);

		$member = $this -> model -> my_find($params);

		if($member){
			//获取会员级别
			switch (intval($member['userrank'])) {
				case '1':
					# 1980...
					$deduct = 1980;
					break;
				case '2':
					# 10000...
					$deduct = 10000;
					break;
				case '3':
					# 30000...
					$deduct = 30000;
					break;
				case '4':
					# 50000...
					$deduct = 50000;
					break;

				default:
					# code...
					$deduct = 1980;
					break;
			}

			//获取报单中心数据
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$member['billcenterid']} AND isbill = 1"

			);

			$billmember = $this -> model -> my_find($params);

			if(intval($billmember['baodanbi']) < $deduct/2){
				$this -> _back("账户激活币不足{$billmember['baodanbi']}");return;
			}

			if(intval($billmember['jihuobi']) < $deduct/2){
				$this -> _back("账户激活币不足{$billmember['jihuobi']}");return;
			}

			//报单币余额计算
			$billdata['baodanbi'] = intval($billmember['baodanbi']) - $deduct/2;

			//激活币余额计算
			$billdata['jihuobi'] = intval($billmember['jihuobi']) - $deduct/2;

			//更新报单中心相应数据
			$billparams = array(

				'table_name' => 'member',

				'where' => "uid = {$member['billcenterid']}",

				'data' => $billdata
			);

			$bill_member_save = $this -> model -> my_save($billparams);

			if ($bill_member_save == 1){
				//记录报单中心消费流水 奖金币 报单币的流水

				//增加财务流水
				$flow_data['money'] = $deduct/2;

				$flow_data['moneytype'] = 1;

				$flow_data['changetype'] = 2;

				$flow_data['realname'] = "{$billmember['realname']}";

				$flow_data['targetrealname'] = "戎子";

				$flow_data['status'] = 1;

				$flow_data['targetuserid'] = 1;

				$flow_data['targetusernumber'] = 1;

				$flow_data['userid'] = $billmember['uid'];

				$flow_data['usernumber'] = $billmember['usernumber'];

				$flow_data['recordtype'] = 0;

				$flow_data['createtime'] = time();

				$params = array(

					'table_name' => 'money_change',

					'data' => $flow_data
				);

				$transfer_flow = $this -> model -> my_add($params);

				$to_data['money'] = $deduct/2;

				$to_data['moneytype'] = 2;

				$to_data['changetype'] = 2;

				$to_data['realname'] = "{$billmember['realname']}";

				$to_data['targetrealname'] = "戎子";

				$to_data['status'] = 1;

				$to_data['targetuserid'] = 1;

				$to_data['targetusernumber'] = 1;

				$to_data['userid'] = $billmember['uid'];

				$to_data['usernumber'] = $billmember['usernumber'];

				$to_data['recordtype'] = 0;

				$to_data['createtime'] = time();

				$params = array(

					'table_name' => 'money_change',

					'data' => $to_data
				);

				$to_transfer_flow = $this -> model -> my_add($params);


			}else{
				$this -> _back('报单中心激活数据保存失败，请重试。');
			}

		}else{
			$this -> _back('激活账号获取失败，请重试。');
		}

		//报单中心ID
		$billcenterid = $_SESSION['Rongzi']['user']['uid'];

		//报单中心编号
		$billcenternumber = $_SESSION['Rongzi']['user']['usernumber'];

		//数据包
		$data['status'] = 1;

		$data['active_time'] = time();

		$data['active_uid'] = $_SESSION['Rongzi']['user']['uid'];

		$data['update_time'] = time();

		//修改相关所有人业绩
		$contactuserpath_arr = array_reverse(explode(",", $member['contactuserpath']));

		foreach ($contactuserpath_arr as $key => $value) {

			//查询该用户在左区中区还是右区
			if($contactuserpath_arr[$key] and $contactuserpath_arr[$key+1]){

				# 获取当前用户区间
				$contact_uid = $contactuserpath_arr[$key];

				//查询当前用户在父类的哪个区间
				$params = array(

					'table_name' => 'member',

					'where' => "uid = {$contact_uid}"

				);

				$contact = $this -> model -> my_find($params);

				#获取父类用户相关信息
				$contact_parent_uid = $contactuserpath_arr[$key+1];

				//获取父类相关数据
				$params = array(

					'table_name' => 'member',

					'where' => "uid = {$contact_parent_uid}"

				);
				$contact_parent = $this -> model -> my_find($params);

				$contact_parent_data = array();
				
				if($contact['zone'] == 1){

					$contact_parent_data['leftachievement'] = $contact_parent['leftachievement'] + $deduct;

					$contact_parent_data['achievement'] = $contact_parent['achievement'] + $contact_parent_data['leftachievement'];

				}elseif($contact['zone'] == 2){

					$contact_parent_data['middleachievement'] = $contact_parent['middleachievement'] + $deduct;

					$contact_parent_data['achievement'] = $contact_parent['achievement'] + $contact_parent_data['middleachievement'];

				}elseif($contact['zone'] == 3){

					$contact_parent_data['rightachievement'] = $contact_parent['rightachievement'] + $deduct;

					$contact_parent_data['achievement'] = $contact_parent['achievement'] + $contact_parent_data['rightachievement'];
				}

				$contact_parent_data['num'] = $contact_parent['num'] + 1;

				//修改父类相关数据
				$params = array(

					'table_name' => 'member',

					'where' => "uid = {$contact_parent_uid} AND status = 1",

					'data' => $contact_parent_data

				);

				$contact_parent_save = $this -> model -> my_save($params);

				$achievementdata = array();
				//业绩区间
				$achievementdata['zone'] = $contact['zone'];
				//业绩金额
				$achievementdata['deduct'] = $deduct;
				//业绩来源用户
				$achievementdata['fromuid'] = $contact_uid;
				//业绩用户
				$achievementdata['uid'] = $contact_parent_uid;
				//业绩产生用户
				$achievementdata['produceuid'] = $uid;
				//业绩时间
				$achievementdata['created_at'] = time();

				//添加业绩增加LOG
				$params = array(

					'table_name' => 'achievement_log',

					'data' => $achievementdata

				);

				$achievementadd = $this -> model -> my_add($params);
			}
		}

		//写入数据库
		$params = array(

			'table_name' => 'member',

			'where' => "uid = {$uid} AND billcenterid = {$billcenterid} AND billcenternumber = {$billcenternumber} AND status = 0",

			'data' => $data
		);

		$my_save = $this -> model -> my_save($params);

		if ($my_save == 1)
		{
			//更新上级伞下人数
			//$this -> save_member_num($member);

			//更新市场补贴
			$this -> save_market_subsidy($deduct);

			//更新拓展补贴
			$this -> save_expand_subsidy($member, $deduct);

			//更新赠送红酒订单 添加一份订单
			$this -> save_red_order($member);

			redirect(__APP__.'/Activates/index', 0);
		}
		else
		{
			$this -> _back('激活失败，请重试。');
		}
	}

	//更新报单中心服务市场补贴
	function save_market_subsidy($deduct){
		//用户ID
		$uid = $_SESSION['Rongzi']['user']['uid'];

		//获取报单中心数据
		$params = array(

			'table_name' => 'member',

			'where' => "uid = {$uid}"
		);

		$member = $this -> model -> my_find($params);

		//获取市场补贴比例
		$marketratio = $this -> get_market_ratio();

		$data['rongzidun'] = $member['rongzidun'] + $deduct * $marketratio * 0.25;

		$data['jiangjinbi'] = $member['jiangjinbi'] + $deduct * $marketratio * 0.55;

		//保存报单中心金额
		$params = array(

			'table_name' => 'member',

			'where' => "uid = {$uid}",

			'data' => $data
		);

		$marke_save = $this -> model -> my_save($params);

		//扣除公司金额
		$this -> save_finance($deduct * $marketratio);

		$bonusdata = array(

			'touserid' => $member['uid'],

			'tousernumber' => $member['usernumber'],

			'torealname' => $member['realname'],

			'jiangjinbi' => $deduct * $marketratio * 0.55,

			'rongzidun' => $deduct * $marketratio * 0.25,

			'platmoney' => $deduct * $marketratio * 0.02,

			'taxmoney' => $deduct * $marketratio * 0.17,

			'total' => $deduct * $marketratio,

			'real_total' => $deduct * $marketratio * 0.8,

			'createdate' => strtotime(date("Y-m-d", time())),

			'lovemoney' => $deduct * $marketratio * 0.01,

			'moneytype' => 5

		);

		//添加奖金明细记录
		$params = array(

			'table_name' => 'bonus_detail',

			'data' => $bonusdata
		);

		$bonusdata_add = $this -> model -> my_add($params);

		//添加到财务流水 money_change
		$money_change_data['changetype'] = 7;

		$money_change_data['realname'] = "戎子";

		$money_change_data['status'] = 1;

		$money_change_data['targetrealname'] = $member['realname'];

		$money_change_data['targetuserid'] = $member['uid'];

		$money_change_data['targetusernumber'] = $member['usernumber'];

		$money_change_data['userid'] = 1;

		$money_change_data['usernumber'] = 1;

		$money_change_data['createtime'] = time();

		//jiangjinbi rongzidun platmoney taxmoney lovemoney

		$add_array = array(
			"0" => array(
				'name' => "jiangjinbi",
				'moneytype' => 1,
				'ratio' => "0.55",
				'recordtype' => 1
			),
			"1" => array(
				'name' => "rongzidun",
				'moneytype' => 3,
				'ratio' => "0.25",
				'recordtype' => 1
			),
			"2" => array(
				'name' => "platmoney",
				'moneytype' => 7,
				'ratio' => "0.02",
				'recordtype' => 0
			),
			"3" => array(
				'name' => "taxmoney",
				'moneytype' => 8,
				'ratio' => "0.17",
				'recordtype' => 0
			),
			"4" => array(
				'name' => "lovemoney",
				'moneytype' => 6,
				'ratio' => "0.01",
				'recordtype' => 0
			),
		);

		foreach ($add_array as $key => $value) {


			$money_change_data['recordtype'] = $value['recordtype'];

			$money_change_data['money'] = $deduct * $marketratio * $value['ratio'];

			$money_change_data['moneytype'] = $value['moneytype'];

			if($value !== 1){
				//添加财务明细记录
				$params = array(

					'table_name' => 'money_change',

					'data' => $money_change_data
				);

				$money_change_add = $this -> model -> my_add($params);
			}

		}

	}

	//更新拓展补贴
	public function save_expand_subsidy($expand_member, $deduct){

		//获取当前用户拓展数据
		$expand = array_reverse(explode(",", $expand_member['recommenduserpath']));

		$offset = array_search($expand_member['uid'], $expand, true);

		$admin_offset = array_search(1, $expand, true);

		if($offset !== false && $admin_offset !== false){
			unset($expand[$offset]);
			unset($expand[$admin_offset]);
		}

		$expand_slice = array_slice($expand, 0, 3);

		//处理几级拓展补贴

		foreach ($expand_slice as $key => $value) {

			//一级消费商
			if($key == 0){
				//获取拓展比例
				$expand_ratio = $this -> get_expand_ratio(1);
			}

			if($key == 0){
				//获取拓展比例
				$expand_ratio = $this -> get_expand_ratio(2);
			}


			if($key == 2){
				//获取拓展比例
				$expand_ratio = $this -> get_expand_ratio(3);
			}

			//计入奖金明细 zx_bonus_detail 4

			//计入财务流水 zx_money_change 6

			//用户ID
			$uid = $value;

			//获取报单中心数据
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid}"
			);

			$member = $this -> model -> my_find($params);

			$data['rongzidun'] = $member['rongzidun'] + $deduct * $expand_ratio * 0.25;

			$data['jiangjinbi'] = $member['jiangjinbi'] + $deduct * $expand_ratio * 0.55;

			//保存报单中心金额
			$params = array(

				'table_name' => 'member',

				'where' => "uid = {$uid}",

				'data' => $data
			);

			$marke_save = $this -> model -> my_save($params);

			//扣除公司金额
			$this -> save_finance($deduct * $expand_ratio);

			$bonusdata = array(

				'touserid' => $member['uid'],

				'tousernumber' => $member['usernumber'],

				'torealname' => $member['realname'],

				'jiangjinbi' => $deduct * $expand_ratio * 0.55,

				'rongzidun' => $deduct * $expand_ratio * 0.25,

				'platmoney' => $deduct * $expand_ratio * 0.02,

				'taxmoney' => $deduct * $expand_ratio * 0.17,

				'total' => $deduct * $expand_ratio,

				'real_total' => $deduct * $expand_ratio * 0.8,

				'createdate' => strtotime(date("Y-m-d", time())),

				'lovemoney' => $deduct * $expand_ratio * 0.01,

				'moneytype' => 4

			);

			//添加奖金明细记录
			$params = array(

				'table_name' => 'bonus_detail',

				'data' => $bonusdata
			);

			$bonusdata_add = $this -> model -> my_add($params);

			//添加到财务流水 money_change
			$money_change_data['changetype'] = 6;

			$money_change_data['realname'] = "戎子";

			$money_change_data['status'] = 1;

			$money_change_data['targetrealname'] = $member['realname'];

			$money_change_data['targetuserid'] = $member['uid'];

			$money_change_data['targetusernumber'] = $member['usernumber'];

			$money_change_data['userid'] = 1;

			$money_change_data['usernumber'] = 1;

			$money_change_data['createtime'] = time();

			//jiangjinbi rongzidun platmoney taxmoney lovemoney

			$add_array = array(
				"0" => array(
					'name' => "jiangjinbi",
					'moneytype' => 1,
					'ratio' => "0.55",
					'recordtype' => 1
				),
				"1" => array(
					'name' => "rongzidun",
					'moneytype' => 3,
					'ratio' => "0.25",
					'recordtype' => 1
				),
				"2" => array(
					'name' => "platmoney",
					'moneytype' => 7,
					'ratio' => "0.02",
					'recordtype' => 0
				),
				"3" => array(
					'name' => "taxmoney",
					'moneytype' => 8,
					'ratio' => "0.17",
					'recordtype' => 0
				),
				"4" => array(
					'name' => "lovemoney",
					'moneytype' => 6,
					'ratio' => "0.01",
					'recordtype' => 0
				),
			);

			foreach ($add_array as $key => $value) {


				$money_change_data['recordtype'] = $value['recordtype'];

				$money_change_data['money'] = $deduct * $expand_ratio * $value['ratio'];

				$money_change_data['moneytype'] = $value['moneytype'];

				//添加财务明细记录
				$params = array(

					'table_name' => 'money_change',

					'data' => $money_change_data
				);

				$money_change_add = $this -> model -> my_add($params);

			}

		}
	}

	//更新伞下人数
	function save_member_num($expand_member){
		//获取当前用户接点数据
		$expand = array_reverse(explode(",", $expand_member['contactuserpath']));

		$offset = array_search($expand_member['uid'], $expand, true);

		if($offset !== false){
			unset($expand[$offset]);
		}

		//$expand_slice = array_slice($expand, 0, 1);

		//更新上级所有人的伞下人数
		foreach ($expand as $key => $value) {

			// $params = array(
			// 	'table_name' => 'member',
			//
			// 	'where' => "uid = {$value}",
			//
			// 	'field' => 'num',
			//
			// 	'data' => 0
			// );
			//
			// $result = $this -> model -> my_setInc($params);
		}
	}

	//扣除公司金额 支出字段增加多少
	function save_finance($data){

		$params = array(
			'table_name' => 'finance',

			'where' => "id = 1",

			'field' => 'expend',

			'data' => $data
		);

		$finance = $this -> model -> my_setInc($params);
	}

	//获取市场补贴比例
	function get_market_ratio(){
		$params = array(

			'table_name' => 'bonus_rule',

			'where' => "category = 'marketcash' AND `key` = 0"
		);

		$my_find = $this -> model -> my_find($params);

		if($my_find){
			if($my_find['value'] && $my_find['value'] > 0){
				return $my_find['value']/100;
			}else{
				return "0.00";
			}
		}else{
			return "0.00";
		}
	}

	//获取市场补贴比例
	function get_expand_ratio($key){
		$params = array(

			'table_name' => 'bonus_rule',

			'where' => "category = 'expandcash' AND `key` = {$key}"
		);
		$my_find = $this -> model -> my_find($params);

		if($my_find){
			if($my_find['value'] && $my_find['value'] > 0){
				return $my_find['value']/100;
			}else{
				return 0.00;
			}
		}else{
			return 0.00;
		}
	}

	//更新报单中心服务市场补贴
	function save_red_order($member){

		$order['order_code'] = $this -> get_order_number();

		$order['user_id'] = $member['uid'];

		$order['sendName'] = $member['realname'];

		//获取用户默认送货地址

		$order['sendAddress'] = $this -> get_user_default_address($member['uid']);

		$order['memberCode'] = $member['usernumber'];

		$order['sendTel'] = $member['mobile'];

		$order['total_price'] = "0.00";

		$order['status'] = 1;

		$order['notice'] = "注册消费商赠送";

		$order['created_at'] = time();

		$params = array(

			'table_name' => 'orders',

			'data' => $order
		);

		$order_add = $this -> model -> my_add($params);

		if($order_add){

			//name logo content
			//根据用户等级添加商品详情 $member['userrank']
			switch ($member['userrank']) {
				case '1':
					# 红酒2瓶
					$pro_id = 1;
					break;
				case '2':
					# 红酒2瓶
					$pro_id = 2;
					break;
				case '3':
					# 红酒2瓶
					$pro_id = 3;
					break;
				case '4':
					# 红酒2瓶
					$pro_id = 4;
					break;

			}

			$order_items['pro_id'] = $pro_id;

			$product = $this -> get_product($pro_id);

			$order_items['name'] = $product['name'];

			$order_items['logo'] = $product['logo'];

			$order_items['content'] = $product['content'];

			$order_items['order_id'] = $order_add;

			$order_items['count'] = 1;

			$order_items['created_at'] = time();

			$params = array(

				'table_name' => 'order_items',

				'data' => $order_items
			);

			$order_items_add = $this -> model -> my_add($params);

		}

	}


	//获取商品数据
	function get_product($pro_id){
		$params = array(

			'table_name' => 'products',

			'where' => "id = {$pro_id}"
		);

		return $product = $this -> model -> my_find($params);
	}

	//获取用户默认送货地址
	public function get_user_default_address($uid){

		$params = array(

			'table_name' => 'user_address',

			'where' => "user_id = {$uid} AND is_default = 1 AND is_del = 0"
		);

		$default_address = $this -> model -> my_find($params);

		if($default_address){
			return $default_address['address'];
		}else{
			return "";
		}
	}

}
