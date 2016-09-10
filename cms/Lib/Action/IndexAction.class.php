<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-06-12
 * @alter 张睿
 * @version 1.0.0
 *
 * 功能简介：商户后台首页控制器类
 * @author 张睿
 * @copyright
 * @time 2014-06-12
 * @version 1.0.0
 */
	class IndexAction extends CommonAction {

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

			$this -> model = D('Index');
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
			//获取商家短信条数 surplus_sms_count
			$corp_mess_params = array(

				'table_name' => 'corps',

				'where' => "id = ".CORP_ID." "
			);
			$corps['corp_mess'] = $this -> model -> my_find($corp_mess_params);


			//获取商家已发送短信条数 sms_posts
			$mobile_sms_params = array(

				'table_name' => 'sms_posts',

				'where' => "corp_id = ".CORP_ID.""
			);
			$corps['sms_posts_count'] = $this -> model -> order_select_count($mobile_sms_params);

	    	//获取会员总数
	    	$member_params = array(

				'table_name' => 'members',

				'where' => "corp_id = ".CORP_ID."  AND is_del = 0"
			);
			$corps['members_count'] = $this -> model -> order_select_count($member_params);

			//获取商户微信信息
			$wx_accounts = array(

				'table_name' => 'wx_accounts',

				'where' => "corp_id = ".CORP_ID
			);

			$corps['corp_wx'] = $this -> model -> my_find($wx_accounts);
	    	//
	    	/*****************动态图开始******************/
	    	/*$tomorrow = strtotime(date('Y-m-d', time())) + (24 * 60 * 60) - 1;

			for ($i=0; $i<=6; $i++)
			{
				$time=$tomorrow - ($i * 24 * 60 * 60);

				$time_last=$tomorrow - (($i + 1) * 24 * 60 * 60);

				$rank_params = array(

					'table_name' => 'rank_numbers',

					'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND created_at<{$time} AND created_at>{$time_last}"
				);

				$rankcount['count'][$i] = $this -> model -> order_select_count($rank_params);

				$rankcount['date'][$i] = Date('Y-m-d',$time);

				$rankcount['date'] = $time;
			}*/

/*			$result['rank_num_count']=implode(',',array_reverse($rankcount['count']));

			$result['rank_date_count']='"'.implode('","',array_reverse($rankcount['date'])).'"';

			$result['date'] = date('Y-m-d', time());*/

			/*****************动态图结束****************/

		/*	$today = strtotime(date('Y-m-d', time()));

			$yesterday = $today - (24 * 60 * 60)+1;
*/
			/******************查询今日预定*****************/
/*
			$params = array(

				'table_name' => 'reserves',

				'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND created_at >= {$today}"
			);

			$result['today_reserves_count']= $this -> model -> get_count($params);

			$params = array(

				'table_name' => 'reserves',

				'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND created_at >= {$yesterday} AND created_at < {$today}"
			);

			$yesterday_reserves_count = $this -> model -> get_count($params);

			$result['today_reserves_percent'] = $this -> _get_percent($yesterday_reserves_count, $result['today_reserves_count']);
		*/
			//var_dump($result['today_reserves_percent']);
			/******************查询今日预定*****************/

			/******************查询今日排号*****************/

/*			$params = array(

				'table_name' => 'rank_numbers',

				'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND created_at >= {$today}"
			);

			$result['today_ranks_count'] = $this -> model -> get_count($params);

			$params = array(

				'table_name' => 'rank_numbers',

				'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND created_at >= {$yesterday} AND created_at < {$today}"
			);

			$yesterday_ranks_count = $this -> model -> get_count($params);

			$result['today_ranks_percent'] = $this -> _get_percent($yesterday_ranks_count, $result['today_ranks_count']);
*/// dump($today);exit;
			/******************查询今日排号*****************/

			/******************查询会员数*****************/

		/*	$params = array(

				'table_name' => 'members',

				'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND created_at >= {$today}"
			);

			$result['today_members_count'] = $this -> model -> get_count($params);

			$params = array(

				'table_name' => 'members',

				'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND created_at >= {$yesterday} AND created_at < {$today}"
			);

			$yesterday_members_count = $this -> model -> get_count($params);

			$result['today_members_percent'] = $this -> _get_percent($yesterday_members_count, $result['today_members_count']);
*/
			/******************查询会员数*****************/

			/******************查询手机号*****************/

		/*	$params = array(

				'table_name' => 'mobiles',

				'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND created_at >= {$today}"
			);

			$result['today_mobiles_count'] = $this -> model -> get_count($params);

			$params = array(

				'table_name' => 'mobiles',

				'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND created_at >= {$yesterday} AND created_at < {$today}"
			);

			$yesterday_mobiles_count = $this -> model -> get_count($params);

			$result['today_mobiles_percent'] = $this -> _get_percent($yesterday_mobiles_count, $result['today_mobiles_count']);

			*//******************查询手机号*****************/

			$corps['used_at'] = date('Y-m-d', $corps['corp_mess']['delivered_at']).'到'.date('Y-m-d', ($corps['corp_mess']['delivered_at'] + (365*24*60*60)));

	    	//TODO 获取五天数据
	    	$this -> assign('result', $corps);

	    	$this -> display();
	    }

	    /**
		 * 获取百分比
		 *
		 * 参数描述：
		 *   $ycount int - 昨天的数量
		 *   $tcount int - 今天的数量
		 *
		 * 返回值：
		 *   INT 没有%的百分比
		 */
	    private function _get_percent(int $ycount, int $tcount)
	    {
	    	if ($ycount != 0)
	    	{
	    		$percent = intval((($tcount - $ycount) / $ycount) * 100);

	    		if ($percent > 0)
	    		{
	    			return array('percent' => $percent.'%', 'class' => 'up');
	    		}
	    		elseif ($percent == 0)
	    		{
	    			return array('percent' => '--', 'class' => 'no');
	    		}
	    		elseif ($percent < 0)
	    		{
	    			return array('percent' => abs($percent).'%', 'class' => 'down');
	    		}
	    	}
	    	else
	    	{
	    		return '';
	    	}
	    }
	    public function wx_login(){
	    	//查询微信账号
    		$wx_accounts = array(

				'table_name' => 'wx_accounts',

				'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']}"
			);

			$result = $this -> model -> my_find($wx_accounts);
			$this->assign('result',$result);
			//var_dump($result);
	    	$this->display();
	    }
	}
