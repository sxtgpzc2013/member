<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
class IndexAction extends CommonAction {
    public function index()
    {
        //获取会员总数
        //获取今日会员新增数
        //获取充值申请
        //获取提现申请
        //获取公司财务
		$this -> display();
	}
}
