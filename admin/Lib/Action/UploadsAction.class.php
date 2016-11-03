<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update
 * @alter
 * @version 1.0.0
 *
 * 功能简介：
 * @author
 * @copyright
 * @time
 * @version 1.0.0
 */
	class UploadsAction extends CommonAction {

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

			$this -> model = D('Uploads');
		}

		public function upload(){

			$upload_dir = $_POST['upload_dir'] ? $_POST['upload_dir'] : 'products';

			$upload_result = $this -> _upload_pic($upload_dir);

			if ($upload_result['status'] == 1)
    		{
    			$data['upload_result'] = $upload_result['msg'];
    			echo json_encode(array('code' => 200, 'imagename' => $upload_result['msg']));
    		}
    		elseif ($upload_result['status'] == 0)
    		{
    			echo json_encode(array('code' => 0, 'imagename' => $upload_result['msg']));
    		}

		}
	}
