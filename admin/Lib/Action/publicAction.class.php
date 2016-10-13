<?php
// 公共头部，脚步，左侧菜单
class publicAction extends CommonAction {
    public function header(){
		
		$this->display();
	}

	public function footer(){
		
		$this->display();
	}
	
	public function sidebar(){
		
		$this->display();
	}
}