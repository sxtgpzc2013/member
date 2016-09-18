<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
class IndexAction extends CommonAction {
    public function index()
    {
		$this -> display();
	}
}