<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
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

        $this -> model = D('Corps');
    }

    public function index()
    {
        //获取会员总数
        $params = array(

            'table_name' => 'member',

            'where' => "1 AND uid != 1"
        );

        $result['member_count'] = $this -> model -> get_count($params);


        //获取今日会员新增数
        $params = array(

            'table_name' => 'member',

            'where' => " uid != 1 AND reg_time >= ".strtotime(date("Y-m-d", time()))." AND reg_time <= ". (strtotime(date("Y-m-d", time())) + 24 * 60 * 60)
        );

        $result['today_member_count'] = $this -> model -> get_count($params);

        //获取充值申请
        //money_change changetype = 1
        $params = array(

            'table_name' => 'money_change',

            'where' => "changetype = 1"
        );

        $result['money_change'] = $this -> model -> get_count($params);

        //获取提现申请
        $params = array(

            'table_name' => 'withdrawal',

            'where' => "1"
        );

        $result['withdrawal'] = $this -> model -> get_count($params);

        //获取公司财务
        $params = array(

            'table_name' => 'finance',

            'where' => "id = 1"
        );

        $result['finance'] = $this -> model -> my_find($params);

        $this -> assign('result', $result);

		$this -> display();
	}
}
