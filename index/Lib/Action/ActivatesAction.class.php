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


				if($contact['zone'] == 1){

					$contact_parent_data['leftachievement'] = $contact_parent['leftachievement'] + $deduct;

				}elseif($contact['zone'] == 2){

					$contact_parent_data['middleachievement'] = $contact_parent['middleachievement'] + $deduct;

				}elseif($contact['zone'] == 3){

					$contact_parent_data['rightachievement'] = $contact_parent['rightachievement'] + $deduct;

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
			$this -> save_market($deduct);

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
	function save_market($deduct){
		//用户ID
		$uid = $_SESSION['Rongzi']['user']['uid'];

		//获取报单中心数据
		$params = array(

			'table_name' => 'member',

			'where' => "uid = {$uid}"
		);

		$member = $this -> model -> my_find($params);

		$data['rongzidun'] = $member['rongzidun'] + $deduct * 0.02 * 0.25;

		$data['jiangjinbi'] = $member['jiangjinbi'] + $deduct * 0.02 * 0.55;

		//保存报单中心金额
		$params = array(

			'table_name' => 'member',

			'where' => "uid = {$uid}",

			'data' => $data
		);

		$marke_save = $this -> model -> my_save($params);

		//扣除公司金额
		//my_setInc finance
		$params = array(

			'table_name' => 'finance',

			'where' => "id = 1",

			'field' => 'expend',

			'data' => $deduct * 0.02
		);

		$finance = $this -> model -> my_setInc($params);

		$bonusdata = array(

			'touserid' => $member['uid'],

			'tousernumber' => $member['usernumber'],

			'torealname' => $member['realname'],

			'jiangjinbi' => $deduct * 0.02 * 0.55,

			'rongzidun' => $deduct * 0.02 * 0.25,

			'platmoney' => $deduct * 0.02 * 0.02,

			'taxmoney' => $deduct * 0.02 * 0.17,

			'total' => $deduct * 0.02,

			'real_total' => $deduct * 0.02 * 0.8,

			'createdate' => time(),

			'lovemoney' => $deduct * 0.02 * 0.01,

			'moneytype' => 5,

		);

		//添加奖金明细记录
		$params = array(

			'table_name' => 'bonus_detail',

			'data' => $bonusdata
		);

		$bonusdata_add = $this -> model -> my_add($params);
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

		$order['notice'] = "注册数字红酒";

		$order['created_at'] = time();

		$params = array(

			'table_name' => 'orders',

			'data' => $order
		);

		$order_add = $this -> model -> my_add($params);

		if($order_add){

			$order_items['pro_id'] = $member['userrank'];

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
