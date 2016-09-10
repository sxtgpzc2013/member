<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-06-26
 * @alter 张睿
 * @version 1.0.0
 *
 * 功能简介：商户后台设置控制器类
 * @author 张睿
 * @copyright 
 * @time 2014-06-26
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
		 * 账户设置
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
	    	$id = CORP_ID;

	    	//查询这个展示信息
	    	$params = array(

	    		'table_name' => 'corps',

	    		'where' => "id = {$id} AND is_del = 0"
	    	);

	    	$result['corp_find'] = $this -> model -> my_find($params);

	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{
	    		//数据包
				$data['name'] = $this -> _is_null(htmlspecialchars($_POST['name']), '请输入名称');

	    		$logo = $this -> _upload_pic('corps');

	    		if ($logo['status'] == 1)
	    		{
	    			$data['logo'] = $logo['msg'];
	    		}

	    		$data['contact'] = $this -> _is_null(htmlspecialchars($_POST['contact']), '请输入联系人');

	    		$data['content'] = htmlspecialchars($_POST['content']);
	    		
	    		//预留状态值
	    		$data['tel'] = htmlspecialchars($_POST['tel']);
	    		
	    		$data['city_name'] = htmlspecialchars($_POST['city_name']);
	    		
	    		$data['address'] = htmlspecialchars($_POST['address']);
				
				$data['lng'] = htmlspecialchars($_POST['longitude']);
				
				$data['lat'] = htmlspecialchars($_POST['latitude']);

				//营业执照注册号、送货范围、营业时间、起送价、送货费。请参照最后一版的页面原型
	    		$data['corp_register_number'] = htmlspecialchars($_POST['corp_register_number']);

	    		$data['cell_scope'] = htmlspecialchars($_POST['cell_scope']);
	    		//送货时间
	    		$data['cell_time'] = $_POST['cell_time'];

	    		$data['corp_bulletin'] = htmlspecialchars($_POST['corp_bulletin']);

	    		$data['started_at'] = htmlspecialchars($_POST['started_at']);

	    		$data['stoped_at'] = htmlspecialchars($_POST['stoped_at']);

	    		$data['is_cell'] = htmlspecialchars($_POST['is_cell']);

	    		$data['cell_money'] = htmlspecialchars($_POST['cell_money']);
	    		
	    		$data['updated_at'] = time();
			

	    		//写入数据库
	    		$params = array(

	    			'table_name' => 'corps',

	    			'where' => "id = {$_POST['id']} AND is_del = 0",

	    			'data' => $data
	    		);

	    		$info_save = $this -> model -> my_save($params);
	    		if ($info_save == 1)
	    		{
	    			redirect(__APP__.'/Set/info', 0);
	    		}
	    		else
	    		{
	    			$this -> _back('保存失败，请重试。');
	    		}
	    	}

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

	    /**
		 * 更新SESSION中的商户信息
		 *
		 * 参数描述：
		 *   corp_id
		 *   
		 *
		 * 返回值：
		 *   true/false
		 */
	    public function _update_corp($corp_id)
	    {
	    	$params = array(

	    		'table_name' => 'corps',

	    		'where' => "id = {$corp_id}"
	    	);

	    	$corp = $this -> model -> my_find($params);

	    	if ($corp)
	    	{
	    		$_SESSION['OftenGo']['user'] = $corp;

	    		return true;
	    	}
	    	else
	    	{
	    		return false;
	    	}
	    }

	    /**
		 * 修改密码
		 *
		 * 参数描述：
		 *   
		 *   
		 *
		 * 返回值：
		 *   
		 */
	    public function edit_password()
	    {
	    	$params = array(

	    		'table_name' => 'corps',

	    		'where' => "id = {$_SESSION['OftenGo']['user']['id']}"
	    	);

	    	$corp = $this -> model -> my_find($params);

	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{
	    		$opwd = md5(md5($_POST['old_password']));

	    		$npwd = md5(md5($_POST['new_password']));

	    		$npwda = md5(md5($_POST['new_password_again']));

	    		if ($opwd == $corp['password'])
	    		{
	    			if ($npwd == $npwda)
	    			{
	    				$data['updated_at'] = time();

	    				$data['password'] = $npwd;

	    				$params = array(

	    					'table_name' => 'corps',

	    					'where' => "id = {$corp['id']}",

	    					'data' => $data
	    				);

	    				//保存
	    				$corp_save = $this -> model -> my_save($params);

	    				if ($corp_save == 1)
	    				{
	    					//修改成功后清除所有SESSION表中的LOGIN_STR
	    					$params = array(

	    						'table_name' => 'corp_sessions',

	    						'where' => "corp_id = ".CORP_ID
	    					);

	    					$corp_session_del = $this -> model -> my_del($params);

	    					redirect(__APP__.'/Login/logout', 0);
	    				}
	    				else
	    				{
	    					$this -> _back('保存失败');
	    				}
	    			}
	    			else
	    			{
	    				$this -> _back('两次密码输入不一致');
	    			}
	    		}
	    		else
	    		{
	    			$this -> _back('与原密码不匹配');
	    		}
	    	}

	    	$this -> display();
	    }

	    /**
		 * 短信充值
		 *
		 * 参数描述：
		 *   
		 *   
		 *
		 * 返回值：
		 *   
		 */
	    public function sms()
	    {
	    	$this -> display();
	    }

	    /**
		 * 短信充值记录
		 *
		 * 参数描述：
		 *   
		 *   
		 *
		 * 返回值：
		 *   
		 */
	    public function sms_pay_logs()
	    {
	    	$params = array(

	    		'table_name' => 'sms_pay_logs',

	    		'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']}",

	    		'order' => 'created_at desc'
	    	);

	    	$result['sms_pay_logs_order'] = $this -> model -> order_select($params, 'no');

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

	    /**
		 * 桌台设置
		 *
		 * 参数描述：
		 *   
		 *   
		 *
		 * 返回值：
		 *   
		 */
	    public function rank_tags()
	    {
	    	$form_key = htmlspecialchars($_POST['form_key']);

	    	if ($form_key == 'yes')
	    	{
	    		$type = htmlspecialchars($_POST['type']);

	    		if ($type == 'new')
	    		{
	    			$data['name'] = htmlspecialchars($_POST['name']) ? htmlspecialchars($_POST['name']) : $this -> _back('请填写名称');

	    			$data['corp_id'] = $_SESSION['OftenGo']['user']['id'];

	    			$data['created_at'] = time();

	    			$data['updated_at'] = time();

	    			$data['is_del'] = 0;

	    			$params = array(

	    				'table_name' => 'rank_tags',

	    				'data' => $data
	    			);

	    			$rank_tag_add = $this -> model -> my_add($params);
	    		}
	    		elseif ($type == 'edit')
	    		{
	    			$rank_tag_id = intval($_POST['rank_tag_id']);

	    			$data['name'] = htmlspecialchars($_POST['name']) ? htmlspecialchars($_POST['name']) : $this -> _back('请填写名称');

	    			$data['update_at'] = time();

	    			$params = array(

	    				'table_name' => 'rank_tags',

	    				'where' => "id = {$rank_tag_id}",

	    				'data' => $data
	    			);

	    			$rank_tag_save = $this -> model -> my_save($params);
	    		}
	    	}

	    	$params = array(

	    		'table_name' => 'rank_tags',

	    		'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND is_del = 0"
	    	);

	    	$result['rank_tags'] = $this -> model -> easy_select($params);

	    	$params = array(

	    		'table_name' => 'corps',

	    		'where' => "id = {$_SESSION['OftenGo']['user']['id']}"
	    	);

	    	$result['corp'] = $this -> model -> my_find($params);

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

	    /**
		 * 删除桌子
		 *
		 * 参数描述：
		 *   
		 *   
		 *
		 * 返回值：
		 *   
		 */
	    public function rank_tag_delete()
	    {
	    	$id = intval($_GET['id']);

	    	$data['is_del'] = 1;

	    	$params = array(

	    		'table_name' => 'rank_tags',

	    		'where' => "id = {$id}",

	    		'data' => $data
	    	);

	    	$rank_tag_save = $this -> model -> my_save($params);

	    	if ($rank_tag_save == 1)
	    	{
	    		redirect(__APP__.'/Set/rank_tags', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('删除失败');
	    	}
	    }

	    /**
		 * 修改排号开关
		 *
		 * 参数描述：
		 *   
		 *   
		 *
		 * 返回值：
		 *   
		 */
	    public function rank_switch()
	    {
	    	$id = intval($_GET['id']);

	    	$type = htmlspecialchars($_GET['type']);

	    	if ($type == 'on')
	    	{
	    		$data['rank_switch'] = 1;
	    	}
	    	elseif ($type == 'off')
	    	{
	    		$data['rank_switch'] = 0;
	    	}

	    	$data['update_at'] = time();

	    	$params = array(

	    		'table_name' => 'rank_tags',

	    		'where' => "id = {$id}",

	    		'data' => $data
	    	);

	    	$rank_tag_save = $this -> model -> my_save($params);

	    	if ($rank_tag_save == 1)
	    	{
	    		redirect(__APP__.'/Set/rank_tags', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('操作失败');
	    	}
	    }

	    /**
		 * 修改预约开关
		 *
		 * 参数描述：
		 *   
		 *   
		 *
		 * 返回值：
		 *   
		 */
	    public function reserve_switch()
	    {
	    	$id = intval($_GET['id']);

	    	$type = htmlspecialchars($_GET['type']);

	    	if ($type == 'on')
	    	{
	    		$data['reserve_switch'] = 1;
	    	}
	    	elseif ($type == 'off')
	    	{
	    		$data['reserve_switch'] = 0;
	    	}

	    	$data['update_at'] = time();

	    	$params = array(

	    		'table_name' => 'rank_tags',

	    		'where' => "id = {$id}",

	    		'data' => $data
	    	);

	    	$rank_tag_save = $this -> model -> my_save($params);

	    	if ($rank_tag_save == 1)
	    	{
	    		redirect(__APP__.'/Set/rank_tags', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('操作失败');
	    	}
	    }

	    /**
		 * 修改发送提醒短信的位置
		 *
		 * 参数描述：
		 *   
		 *   
		 *
		 * 返回值：
		 *   
		 */
	    public function send_wait_count()
	    {
	    	$data['send_wait_count'] = intval($_POST['send_wait_count']);

	    	$data['updated_at'] = time();

	    	$params = array(

	    		'table_name' => 'corps',

	    		'where' => "id = {$_SESSION['OftenGo']['user']['id']}",

	    		'data' => $data
	    	);

	    	$corp_save = $this -> model -> my_save($params);

	    	if ($corp_save == 1)
	    	{
	    		redirect(__APP__.'/Set/rank_tags', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('保存失败');
	    	}
	    }

	    /**
		 * 返回
		 *
		 * 参数描述：
		 *   message
		 *   
		 *
		 * 返回值：
		 *   
		 */
	    public function _back($message)
	    {
	    	$msg = $message ? $message : '出现错误，请稍后再试。';

	    	die('<meta http-equiv="Content-Type" content="text/html"; charset="utf8"><script language="javascript">alert("' . $msg . '");window.history.back(-1);</script>');
	    }
	     /**
		 * 预约设置
		 *
		 * 参数描述：
		 * 返回值：
		 *   
		 */
	    public function reserve_set(){
	    	$params = array(

	    		'table_name' => 'rank_tags',

	    		'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND is_del = 0"
	    	);

	    	$result['rank_tags'] = $this -> model -> easy_select($params);

	    	foreach ($result['rank_tags'] as $key => $value) {
	    		# 进行桌号处理
	    		$res_params = array(

	    		'table_name' => 'tables',

	    		'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND rank_tag_id = {$value['id']} AND is_del = 0"
		    	);

		    	$result['rank_tags'][$key]['tags'] = $this -> model -> easy_select($res_params);


		    	/*var_dump($result['rank_tags'][$key]);echo '<br>';echo '<br>';echo '<br>';*/
	    	}

	    	$reserve_product_params = array(

	    		'table_name' => 'account_menus',

	    		'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND is_del = 0 AND cate_str = 'products' "
	    	);

	    	$result['reserve_product'] = $this -> model -> easy_select($reserve_product_params);

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

	        /**
		 * 预约设置
		 *
		 * 参数描述：
		 * 返回值：
		 *   
		 */
	    public function reserve_time_set(){

	    	$params = array(

	    		'table_name' => 'reserve_times',

	    		'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND is_del = 0"
	    	);

	    	$result['rank_tags'] = $this -> model -> easy_select($params);
	    	
	    	$result['count'] = count($result['rank_tags']);

	    	if ($result['count'] == 24) {
	    		# 隐藏添加
	    		$result['res_time_show'] = 'hidden';
	    	} else {
	    		# 显示添加
	    		$result['res_time_show'] = 'show';
	    	}
	    	

	    	$this -> assign('result', $result);

	    	$this -> display();
	    }

	        /**
		 * 预约设置
		 *
		 * 参数描述：
		 * 返回值：
		 *   
		 */
	    public function reserve_time_set_del(){

	    	$data['is_del'] = 1;

	    	$params = array(

	    		'table_name' => 'reserve_times',

	    		'data' => $data,

	    		'where' => "id = {$_GET['id']}"
	    	);

	    	$reserve_tag = $this -> model -> my_save($params);

			if ($reserve_tag)
			{
				redirect(__APP__."/Set/reserve_time_set", 0);
			}
			else
			{
				$this -> _back('删除失败');
			}	
	    	
	    }
	     /**
		 * 添加预约桌子
		 *
		 * 参数描述：
		 * 返回值：
		 *   
		 */
	    public function reserve_add_do(){
	    	//获取添加信息
			$param['name']=$this->_post('name');
			$param['rank_tag_id']=$this->_post('rank_tag_id');
			$param['person_count']=$this->_post('person_count');
			$param['created_at']=time();
			$param['updated_at']=time();
			$param['corp_id']=$_SESSION['OftenGo']['user']['id'];	
			$params = array(
				'table_name' => 'tables',
				'data' => $param
			);
			
			$reserve_tag = $this -> model -> my_add($params);

			if ($reserve_tag)
			{
				redirect(__APP__."/Set/reserve_set", 0);
			}
			else
			{
				$this -> _back('新增失败');
			}	
	    }

	     /**
		 * 添加预约时间段
		 *
		 * 参数描述：
		 * 返回值：
		 *   
		 */
	    public function reserve_time_add_do(){
	    	//获取添加信息
			$param['star_time']=$this->_post('star_time');
			$param['end_time']=$this->_post('end_time');
			$param['created_at']=time();
			$param['updated_at']=time();
			$param['corp_id']=$_SESSION['OftenGo']['user']['id'];	
			$params = array(
				'table_name' => 'reserve_times',
				'data' => $param
			);
			
			$reserve_tag = $this -> model -> my_add($params);

			if ($reserve_tag)
			{
				redirect(__APP__."/Set/reserve_time_set", 0);
			}
			else
			{
				$this -> _back('新增失败');
			}	
	    }

	    /**
		 * 添加24小时预约段 00:00~00:59  01:00~01:59 02:00~02:59 03:00~03:59    
		 *
		 * 参数描述：
		 * 返回值：
		 *   
		 */
	    public function reserve_time_add_do_24(){
	    	//
	    	//
	    	$param['corp_id'] = $_SESSION['OftenGo']['user']['id'];	

				$params = array(
					'table_name' => 'reserve_times',
					'where' => 'corp_id = '.CORP_ID
				);
				
			$reserve_tag = $this -> model -> easy_select($params);
			foreach ($reserve_tag as $key => $value) {
				# code...
				//var_dump($value);echo '<br></br>';
				$param['is_del'] = 0;	

				$params = array(
					'table_name' => 'reserve_times',
					'data' => $param,
					'where' => "id = {$value['id']}"
				);

				$reserve_tag = $this -> model -> my_save($params);
			}
		
			//获取24小时时间段
			for($i=0;$i<24;$i++){
				//开始时段	date('H:i',strtotime(date('Y-m-d',time()))+$i*60*60);
				$star_time =  date('H:i',strtotime(date('Y-m-d',time()))+$i*60*60);
				$j = $i+1;
				//结束时段  date('H:i',strtotime(date('Y-m-d',time()))+$j*60*60-1);
				$end_time =  date('H:i',strtotime(date('Y-m-d',time()))+$j*60*60-1);
				
				$param['star_time'] = $star_time;
				$param['end_time'] = $end_time;
				$param['created_at'] = time();
				$param['updated_at'] = time();
				$param['corp_id'] = $_SESSION['OftenGo']['user']['id'];	

				$params = array(
					'table_name' => 'reserve_times',
					'data' => $param
				);
				
				$reserve_tag = $this -> model -> my_add($params);

			}

			

			if ($reserve_tag)
			{
				redirect(__APP__."/Set/reserve_time_set", 0);
			}
			else
			{
				$this -> _back('新增失败');
			}	
	    }
	     /**
		 * 获取本桌型预约桌子信息
		 *
		 * 参数描述：
		 * 返回值：
		 *   
		 */
		public function reserve_edit(){
	    	$url = "http://jingchangqu.com/cms.php/Set/reserve_edit_do";
	    	$id = $_POST['id'];
    		$res_params = array(

    		'table_name' => 'tables',

    		'where' => "corp_id = {$_SESSION['OftenGo']['user']['id']} AND rank_tag_id = {$id} AND is_del = 0"
	    	);

	    	$result = $this -> model -> easy_select($res_params);
	    	$html = '';
	    	foreach ($result as $key => $value) {
	    		$retVal = ($value['status'] == 0) ? '关闭' : '开启' ;
	    		$html .="<form action='{$url}' method='post'><input type='hidden' name='id' value='{$value['id']}'>
	    					<input type='text' name='name' value='{$value['name']}'>
	    					<input type='text' name='person_count' value='{$value['person_count']}' style='width:10%'>
	    					<input type='submit' class='btn' value='修改' style='margin-top:-10px;'>&nbsp;&nbsp;<a class='btn' style='margin-top:-10px;' href='del_table/id/{$value['id']}'>删除</a>
	    					&nbsp;&nbsp;
	    					<a class='btn reserve_table_switch' data-val='{$value['status']}' data-id='{$value['id']}' style='margin-top:-10px;'>{$retVal}</a>
	    				</form>";
	    	}
    		
    		echo "<center>{$html}</center>
    		<script>
    		$(function() {
				$('.reserve_table_switch').click(function() {
					var 	n_reserve_val = $(this).attr('data-val');
					if(n_reserve_val == '0'){
						n_reserve_val = '1'
						table_switch = '开启'
					}else{ 
						n_reserve_val ='0'
						table_switch = '关闭'
					}
				
					var 	n_reserve_id = $(this).attr('data-id');
					var 	url = 'http://jingchangqu.com/cms.php/Set/reserve_edit_update';

					$.ajax({
						type: 'post',
						url: url,
						dataType: 'text',
						data: {id:n_reserve_id,n_reserve_val:n_reserve_val},
						success: function(msg){
							
							//$('#my_reserve_edit').html(msg);
							//$('#backdata').css({color: 'green'});
						}
					});
					$(this).attr('data-val',n_reserve_val);
							$(this).html(table_switch);
				});
			
			//处理桌子开关
			});
    		</script>
    		";


	    }

	    /**
		 * 删除桌子
		 *
		 * 参数描述：
		 *   
		 *   
		 *
		 * 返回值：
		 *   
		 */
	    public function del_table()
	    {
	    	$id = intval($_GET['id']) ? intval($_GET['id']) : $this -> _back('非法操作！');

	    	$data['is_del'] = 1;

	    	$data['updated_at'] = time();

	    	$params = array(

	    		'table_name' => 'tables',

	    		'where' => "id = {$id} AND corp_id = ".CORP_ID,

	    		'data' => $data
	    	);

	    	$table_save = $this -> model -> my_save($params);

	    	if ($table_save == 1)
	    	{
	    		redirect(__APP__.'/Set/reserve_set', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('删除失败，请重试。');
	    	}
	    }

		 /**
		 * 修改预约桌子处理
		 *
		 * 参数描述：
		 * 返回值：
		 *   
		 */
		public function reserve_edit_do(){
	    	$id = intval($_POST['id']);
	    	//对数据进行修改
	    	$data['name'] = $_POST['name'];
	    	$data['person_count'] = $_POST['person_count'];
	    	$params = array(

	    		'table_name' => 'tables',

	    		'where' => "id = {$id}",

	    		'data' => $data
	    	);

	    	$rank_tag_save = $this -> model -> my_save($params);

	    	if ($rank_tag_save == 1)
	    	{
	    		redirect(__APP__.'/Set/reserve_set', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('操作失败');
	    	}
	    }
		 /**
		 * 修改预约桌子处理
		 *
		 * 参数描述：开启 关闭
		 * 返回值：
		 *   
		 */
		public function reserve_edit_update(){
	    	$id = intval($_POST['id']);
	    	//对数据进行修改
	    	$data['status'] = $_POST['n_reserve_val'];
	    	$params = array(

	    		'table_name' => 'tables',

	    		'where' => "id = {$id}",

	    		'data' => $data
	    	);

	    	$rank_tag_save = $this -> model -> my_save($params);

	    	echo true;
	    }
	     /**
		 * 修改预约项目是否可以预约
		 *
		 * 参数描述：开启1 关闭0
		 * 返回值：
		 *   
		 */
		 public function UpdateReserveSet(){

		 	$id = intval($_GET['id']);
	    	//对数据进行修改
	    	if ($_GET['is_reserve'] == 0) {
	    		# 可预约
	    		$data['is_reserve'] = 1;
	    	} else {
	    		# 取消可预约
	    		$data['is_reserve'] = 0;
	    	}
	    	
	    	$params = array(

	    		'table_name' => 'account_menus',

	    		'where' => "id = {$id}",

	    		'data' => $data
	    	);

	    	$rank_tag_save = $this -> model -> my_save($params);
	    	
	    	if ($rank_tag_save == 1)
	    	{
	    		redirect(__APP__.'/Set/reserve_set', 0);
	    	}
	    	else
	    	{
	    		$this -> _back('操作失败');
	    	}
		 }

		 /**
		 * 设置打票机
		 *
		 * 参数描述：
		 *
		 * 返回值：
		 *   
		 */
		 public function ticket()
		 {
		 	//查询商户附加信息
		 	$params = array(

		 		'table_name' => 'appendixs',

		 		'where' => "is_del = 0 AND corp_id = ".CORP_ID
		 	);

		 	$result['appendix_find'] = $this -> model -> my_find($params);

		 	if (htmlspecialchars($_POST['form_key']) == 'yes')
		 	{
		 		if ($result['appendix_find'])
		 		{
		 			//若存在，则修改
		 			$data['ticket_content'] = htmlspecialchars($_POST['ticket_content']);

		 			$data['updated_at'] = time();

		 			$params = array(

		 				'table_name' => 'appendixs',

		 				'where' => "id = {$result['appendix_find']['id']}",

		 				'data' => $data
		 			);

		 			$appendix_save = $this -> model -> my_save($params);

		 			if ($appendix_save == 1)
		 			{
		 				redirect(__APP__.'/Set/ticket', 0);
		 			}
		 			else
		 			{
		 				$this -> _back('保存失败，请稍后重试。');
		 			}
		 		}
		 		else
		 		{
		 			//若不存在，则新增
		 			$data['corp_id'] = CORP_ID;

		 			$data['ticket_content'] = htmlspecialchars($_POST['ticket_content']);

		 			$data['created_at'] = time();

		 			$data['updated_at'] = time();

		 			$data['is_del'] = 0;

		 			$params = array(

		 				'table_name' => 'appendixs',

		 				'data' => $data
		 			);

		 			$appendix_add = $this -> model -> my_add($params);

		 			if ($appendix_add)
		 			{
		 				redirect(__APP__.'/Set/ticket', 0);
		 			}
		 			else
		 			{
		 				$this -> _back('保存失败，请稍后重试。');
		 			}
		 		}
		 	}

		 	$this -> assign('result', $result);

		 	$this -> display();
		 }
	}